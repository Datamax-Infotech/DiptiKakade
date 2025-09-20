Imports System.ComponentModel
Imports System.Data.OleDb
Imports System.Globalization
Imports System.IO
Imports System.Text.RegularExpressions
Imports System.Windows.Forms
Imports System.Xml
Imports Mysqlx.XDevAPI.CRUD



Imports Org.BouncyCastle.Asn1.Cmp
Imports QBFC12Lib
Imports OfficeOpenXml
Imports QBXMLRP2Lib
Public Class BalanceSheet
    Inherits UserControl

    ' --- DB connection string (adjust path as needed) ---
    'Private ReadOnly connStr As String =
    '    "Provider=Microsoft.ACE.OLEDB.12.0;" &
    '    "Data Source=C:\Users\lenovo\Documents\MooneemDB.accdb;" &
    '    "Persist Security Info=False;"
    Private ReadOnly connStr As String = "Provider=Microsoft.ACE.OLEDB.16.0;Data Source=" + Application.StartupPath + "\MooneemDB.accdb;Persist Security Info=False;"

    ' --- UI controls ---
    Private comboClient As ComboBox
    Private dtpSelDate As DateTimePicker

    Private btnFetch As Button
    Private btnExport As Button
    Private dgvReport As DataGridView
    Private qbCompanyName As String = "Unknown"
    Private pnlOverlay As Panel
    Private lblStatus As Label
    Public Sub New()
        InitializeComponent()
        BuildUI()
        LoadClients()          ' <‑‑ populate combo
        AddHandler btnFetch.Click, AddressOf FetchReport   ' ← add this line
        AddHandler btnExport.Click, AddressOf ExportToExcel

    End Sub

    ' Simple DTO for combobox binding
    Public Class ClientInfo
        Public Property Name As String
        Public Property FilePath As String
        Public Sub New(n As String, p As String)
            Name = n : FilePath = p
        End Sub
        Public Overrides Function ToString() As String
            Return Name
        End Function
    End Class

    ' =====================================================
    ' FetchReport : connects to QuickBooks and runs report
    ' =====================================================
    Private Sub FetchReport(sender As Object, e As EventArgs)

        Dim sessionManager As New QBSessionManager()
        Dim startedSession As Boolean = False
        Try
            If comboClient.SelectedItem Is Nothing Then
                MessageBox.Show("Please select a client.")
                Return
            End If

            'lblStatus.Visible = True
            'lblStatus.Text = "🔄 Starting QuickBooks session..."
            'Application.DoEvents()
            Cursor.Current = Cursors.WaitCursor
            ShowStatus("🔄 Connecting to QuickBooks…")
            Dim selectedClient As ClientInfo = CType(comboClient.SelectedItem, ClientInfo)
            Dim qbFilePath As String = selectedClient.FilePath

            sessionManager.OpenConnection("", "Mooneem App")
            sessionManager.BeginSession(qbFilePath, ENOpenMode.omDontCare)
            startedSession = True
            ' Get company name (optional)
            Dim companyRequestSet As IMsgSetRequest = sessionManager.CreateMsgSetRequest("US", 12, 0)
            companyRequestSet.Attributes.OnError = ENRqOnError.roeStop
            companyRequestSet.AppendCompanyQueryRq()
            Dim companyResponseSet As IMsgSetResponse = sessionManager.DoRequests(companyRequestSet)
            Dim companyResp As IResponse = companyResponseSet.ResponseList.GetAt(0)
            qbCompanyName = If(companyResp.StatusCode = 0 AndAlso companyResp.Detail IsNot Nothing,
                           CType(companyResp.Detail, ICompanyRet).CompanyName.GetValue(),
                           "Unknown")

            Dim msgSetRequest As IMsgSetRequest = sessionManager.CreateMsgSetRequest("US", 12, 0)
            msgSetRequest.Attributes.OnError = ENRqOnError.roeStop
            Dim reportQuery As IGeneralSummaryReportQuery = msgSetRequest.AppendGeneralSummaryReportQueryRq()
            reportQuery.GeneralSummaryReportType.SetValue(ENGeneralSummaryReportType.gsrtBalanceSheetStandard)
            reportQuery.ORReportPeriod.ReportPeriod.ToReportDate.SetValue(dtpSelDate.Value.Date)

            Dim msgSetResponse As IMsgSetResponse = sessionManager.DoRequests(msgSetRequest)
            Dim response As IResponse = msgSetResponse.ResponseList.GetAt(0)
            If response.StatusCode <> 0 OrElse response.Detail Is Nothing Then
                MessageBox.Show("No report data returned or an error occurred.")
                'GoTo CloseSession
            End If

            Dim xmlOut As String = msgSetResponse.ToXMLString()

            Dim xmlDoc As New XmlDocument()
            xmlDoc.LoadXml(xmlOut)

            Dim unmatchedEntries As New List(Of String)()
            Dim unmatchedFsAccounts As New List(Of String)()


            'lblStatus.Text = "🔍 Checking Balance Sheet mappings..."
            'Application.DoEvents()
            ShowStatus("🔍 Checking Balance Sheet mappings...")

            Using conn As New OleDbConnection(connStr)
                conn.Open()

                ' Step 1: Check Balance Sheet Mapping from XML report
                Dim dataNodes As XmlNodeList = xmlDoc.SelectNodes("//QBXML//ReportData//DataRow")
                For Each node As XmlNode In dataNodes
                    Dim colNode As XmlNode = node.SelectSingleNode("ColData[@colID='1']")
                    If colNode IsNot Nothing Then
                        Dim fullLabel As String = colNode.Attributes("value")?.Value.Trim()
                        If Not String.IsNullOrWhiteSpace(fullLabel) AndAlso fullLabel.Contains("·") Then
                            Dim parts() As String = fullLabel.Split("·"c)
                            Dim groupCode As String = parts(0).Trim()
                            Dim groupLabel As String = parts(1).Trim()

                            Dim cmd As New OleDbCommand("SELECT COUNT(*) FROM BalanceQBMapping WHERE ClientName = ? AND ReportID = 1 AND GroupCode = ?", conn)
                            cmd.Parameters.AddWithValue("?", selectedClient.Name)
                            cmd.Parameters.AddWithValue("?", groupCode)
                            'cmd.Parameters.AddWithValue("?", groupLabel)

                            Dim count As Integer = CInt(cmd.ExecuteScalar())
                            Dim labelDisplay As String = $"{groupCode} · {groupLabel}"

                            If count = 0 Then
                                unmatchedEntries.Add(labelDisplay)
                            End If
                        End If
                    End If
                Next

                ' Show unmatched Balance Sheet entries
                If unmatchedEntries.Count > 0 Then
                    Dim msg As String = "❌ The following Balance Sheet accounts are NOT MAPPED:" & vbCrLf &
                            String.Join(vbCrLf, unmatchedEntries)
                    MessageBox.Show(msg, "Balance Sheet Mapping Check")
                End If

                ' Step 2: Fetch mapped GroupLabels from DB to limit which Chart of Accounts entries to check
                Dim validAccountNames As New HashSet(Of String)(StringComparer.OrdinalIgnoreCase)
                Dim labelCmd As New OleDbCommand("SELECT DISTINCT GroupLabel FROM BalanceQBMapping WHERE ClientName = ? AND ReportID = 1", conn)
                labelCmd.Parameters.AddWithValue("?", selectedClient.Name)
                Using reader = labelCmd.ExecuteReader()
                    While reader.Read()
                        validAccountNames.Add(reader("GroupLabel").ToString().Trim())
                    End While
                End Using

                ' Step 3: Fetch Chart of Accounts via QBFC
                Dim coaRequest As IMsgSetRequest = sessionManager.CreateMsgSetRequest("US", 12, 0)
                coaRequest.Attributes.OnError = ENRqOnError.roeStop
                coaRequest.AppendAccountQueryRq()
                Dim coaResponse As IMsgSetResponse = sessionManager.DoRequests(coaRequest)

                Dim coaResponseDetail As IResponse = coaResponse.ResponseList.GetAt(0)
                If coaResponseDetail.StatusCode = 0 AndAlso coaResponseDetail.Detail IsNot Nothing Then
                    Dim accountList As IAccountRetList = CType(coaResponseDetail.Detail, IAccountRetList)

                    For i As Integer = 0 To accountList.Count - 1
                        Dim account As IAccountRet = accountList.GetAt(i)
                        Dim descText As String = If(account.Desc IsNot Nothing, account.Desc.GetValue(), "")
                        Dim accountName As String = If(account.Name IsNot Nothing, account.Name.GetValue(), "").Trim()

                        ' ONLY check accounts that are in DB
                        If Not validAccountNames.Contains(accountName) Then Continue For

                        Dim matches As MatchCollection = Regex.Matches(descText, "<fsaccount>(\d+)</fsaccount>|\b\d{5}\b")

                        If matches.Count = 0 Then
                            unmatchedFsAccounts.Add(accountName)
                        Else
                            For Each match As Match In matches
                                Dim code As String = If(match.Groups(1).Success, match.Groups(1).Value, match.Value)

                                Dim cmd As New OleDbCommand("SELECT COUNT(*) FROM BalanceQBMapping WHERE ClientName = ? AND GroupCode = ? AND ReportID = 1", conn)
                                cmd.Parameters.AddWithValue("?", selectedClient.Name)
                                cmd.Parameters.AddWithValue("?", code)
                                ' cmd.Parameters.AddWithValue("?", accountName)

                                Dim count As Integer = CInt(cmd.ExecuteScalar())
                                If count = 0 Then
                                    unmatchedFsAccounts.Add($"{code} · {accountName}")
                                End If
                            Next
                        End If
                    Next
                End If

                ' Show unmatched FSAccounts from Chart of Accounts
                If unmatchedFsAccounts.Count > 0 Then
                    Dim msg As String = "❌ The following FSAccount codes in Chart of Accounts are NOT MAPPED:" & vbCrLf &
                            String.Join(vbCrLf & vbCrLf, unmatchedFsAccounts)
                    MessageBox.Show(msg, "FSAccount Mapping Check")
                End If
            End Using


            ' Load grid only if both sections are fully matched
            If unmatchedEntries.Count = 0 AndAlso unmatchedFsAccounts.Count = 0 Then
                'lblStatus.Text = "📊 Loading data into grid view. Please wait..."
                'Application.DoEvents()
                ShowStatus("📊 Loading data into grid view. Please wait...")
                LoadBalanceSheetIntoGrid(xmlOut)
            End If


            'lblStatus.Visible = False
            HideStatus()
            Cursor.Current = Cursors.Default

            'CloseSession:
            '            sessionManager.EndSession()
            '            sessionManager.CloseConnection()

            '        Catch ex As Exception
            '            MessageBox.Show("Error: " & ex.Message)
            '            Try : sessionManager.CloseConnection() : Catch : End Try
            '        End Try

        Catch ex As Exception
            MessageBox.Show("Error: " & ex.Message)
        Finally
            Try
                If startedSession Then
                    sessionManager.EndSession()
                End If
                sessionManager.CloseConnection()
            Catch
            End Try
        End Try
    End Sub

    Private Function FormatUSCurrency(amountStr As String) As String
        Dim amountDecimal As Decimal
        If Decimal.TryParse(amountStr.Replace(",", ""), amountDecimal) Then
            ' Format as US number with commas and 2 decimal places, no dollar sign
            Return amountDecimal.ToString("N2", CultureInfo.CreateSpecificCulture("en-US"))
        Else
            Return amountStr ' fallback
        End If
    End Function

    Private Function CleanLabel(input As String) As String
        If String.IsNullOrWhiteSpace(input) Then Return input
        ' Remove patterns like "10100 · ", "2000 · ", etc.
        Return Regex.Replace(input.Trim(), "^\s*(Total\s+)?\d+\s*·\s*", "$1", RegexOptions.IgnoreCase)
    End Function

    Private Sub LoadBalanceSheetIntoGrid(xmlData As String)
        Dim xmlDoc As New XmlDocument()
        Try
            xmlDoc.LoadXml(xmlData)
        Catch ex As Exception
            MessageBox.Show("XML Load Error: " & ex.Message)
            Return
        End Try



        Dim dt As New DataTable()
        dt.Columns.Add("Label")
        dt.Columns.Add("Amount")
        dt.Columns.Add("RowType")
        dt.Columns.Add("RowNumber")
        dt.Columns.Add("SortOrder", GetType(Integer))

        Dim reportTitle = xmlDoc.SelectSingleNode("//QBXML//ReportTitle")?.InnerText
        Dim reportSubtitle = xmlDoc.SelectSingleNode("//QBXML//ReportSubtitle")?.InnerText
        Dim titleAndSubtitle = reportTitle
        If Not String.IsNullOrEmpty(reportSubtitle) Then
            titleAndSubtitle &= " - " & reportSubtitle
        End If
        If Not String.IsNullOrEmpty(titleAndSubtitle) Then
            dt.Rows.Add("Report", titleAndSubtitle, "", "", 0)
        End If
        dt.Rows.Add("", "", "Empty", "", 0)

        Dim allNodes As XmlNodeList = xmlDoc.SelectNodes("//QBXML//ReportData/*")
        If allNodes Is Nothing OrElse allNodes.Count = 0 Then
            MessageBox.Show("No data found in ReportData.")
            Return
        End If

        Dim selectedClient As ClientInfo = CType(comboClient.SelectedItem, ClientInfo)
        Dim nodeName As String

        Dim reportDataNode As XmlNode = xmlDoc.SelectSingleNode("//QBXML//ReportData")



        For Each node As XmlNode In allNodes

            nodeName = node.Name
            Dim colstmp As XmlNodeList = node.SelectNodes("ColData")

            For Each col As XmlNode In colstmp
                Dim colId = col.Attributes("colID")?.Value
                Dim val = col.Attributes("value")?.Value

                If val = "Net Income" Then
                    nodeName = "SubtotalRow"
                End If
            Next

            Select Case nodeName
                Case "TextRow"
                    Dim headingValue As String = node.Attributes("value")?.Value
                    Dim rowNumber As String = node.Attributes("rowNumber")?.Value
                    Dim sortOrder As Integer = 9999

                    If Not String.IsNullOrEmpty(headingValue) Then
                        Dim shouldPrint As Boolean = False
                        Using conn As New OleDbConnection(connStr)
                            conn.Open()
                            Using cmd As New OleDbCommand("SELECT COUNT(*) FROM BalanceQBMapping WHERE ClientName = ? AND ReportID = 1 AND IsActive = True AND (FSGroupName = ? OR TopLabel = ?)", conn)
                                cmd.Parameters.AddWithValue("?", selectedClient.Name)
                                cmd.Parameters.AddWithValue("?", headingValue)
                                cmd.Parameters.AddWithValue("?", headingValue)
                                shouldPrint = CInt(cmd.ExecuteScalar()) > 0
                            End Using
                            Using cmdSort As New OleDbCommand("SELECT MIN(SortOrder) FROM BalanceQBMapping WHERE ClientName = ? AND ReportID = 1 AND IsActive = True AND (FSGroupName = ? OR TopLabel = ?)", conn)
                                cmdSort.Parameters.AddWithValue("?", selectedClient.Name)
                                cmdSort.Parameters.AddWithValue("?", headingValue)
                                cmdSort.Parameters.AddWithValue("?", headingValue)
                                Dim sortResult = cmdSort.ExecuteScalar()
                                If sortResult IsNot Nothing AndAlso IsNumeric(sortResult) Then
                                    sortOrder = CInt(sortResult)
                                End If
                            End Using
                        End Using
                        If shouldPrint Then
                            dt.Rows.Add(CleanLabel(headingValue), "", nodeName, rowNumber, sortOrder)
                        End If
                    End If

                Case "DataRow"
                    Dim rawLabel As String = ""
                    Dim cleanedLabel As String = ""
                    Dim amount As String = "0.00"
                    Dim colIds As String = ""
                    Dim sortOrder As Integer = 9999

                    For Each col As XmlNode In node.SelectNodes("ColData")
                        Dim colId = col.Attributes("colID")?.Value
                        Dim val = col.Attributes("value")?.Value
                        If Not String.IsNullOrEmpty(colId) Then
                            If colIds <> "" Then colIds &= ","
                            colIds &= colId
                        End If
                        If colId = "1" Then
                            rawLabel = val
                            cleanedLabel = CleanLabel(val)
                        ElseIf colId = "2" Then
                            amount = FormatUSCurrency(val)
                        End If
                    Next

                    Dim groupCode As String = ""
                    Dim match = System.Text.RegularExpressions.Regex.Match(rawLabel, "(\d+)")
                    If match.Success Then
                        groupCode = match.Groups(1).Value
                    End If

                    Dim shouldPrint As Boolean = False
                    Using conn As New OleDbConnection(connStr)
                        conn.Open()
                        Using cmd As New OleDbCommand("SELECT COUNT(*) FROM BalanceQBMapping WHERE ClientName = ? AND ReportID = 1 AND IsActive = True AND GroupCode = ?", conn)
                            cmd.Parameters.AddWithValue("?", selectedClient.Name)
                            cmd.Parameters.AddWithValue("?", groupCode)
                            shouldPrint = CInt(cmd.ExecuteScalar()) > 0
                        End Using
                        Using cmdSort As New OleDbCommand("SELECT TOP 1 SortOrder FROM BalanceQBMapping WHERE ClientName = ? AND ReportID = 1 AND IsActive = True AND GroupCode = ?", conn)
                            cmdSort.Parameters.AddWithValue("?", selectedClient.Name)
                            cmdSort.Parameters.AddWithValue("?", groupCode)
                            Dim sortResult = cmdSort.ExecuteScalar()
                            If sortResult IsNot Nothing AndAlso IsNumeric(sortResult) Then
                                sortOrder = CInt(sortResult)
                            End If
                        End Using
                    End Using

                    If shouldPrint Then
                        dt.Rows.Add(rawLabel, amount, nodeName, colIds, sortOrder)
                    End If

                Case "SubtotalRow", "TotalRow"
                    Dim col1 As String = ""
                    Dim col2 As String = ""
                    Dim colIds As String = ""
                    Dim sortOrder As Integer = 9999

                    For Each col As XmlNode In colstmp
                        Dim colId = col.Attributes("colID")?.Value
                        Dim val = col.Attributes("value")?.Value
                        If Not String.IsNullOrEmpty(colId) Then
                            If colIds <> "" Then colIds &= ","
                            colIds &= colId
                        End If

                        'FormatUSCurrency
                        If colId = "1" Then col1 = CleanLabel(val)
                        If colId = "2" Then col2 = FormatUSCurrency(val)
                    Next


                    Dim shouldPrint As Boolean = True
                    'If node.Name = "SubtotalRow" Then
                    Using conn As New OleDbConnection(connStr)
                        conn.Open()
                        Using cmd As New OleDbCommand("SELECT COUNT(*) FROM BalanceQBMapping WHERE ClientName = ? AND ReportID = 1 AND IsActive = True AND GroupLabel = ?", conn)
                            cmd.Parameters.AddWithValue("?", selectedClient.Name)
                            cmd.Parameters.AddWithValue("?", col1)
                            shouldPrint = CInt(cmd.ExecuteScalar()) > 0
                        End Using
                    End Using
                    If Not shouldPrint Then Exit Select
                    'End If

                    Using conn As New OleDbConnection(connStr)
                        conn.Open()
                        Using cmdSort As New OleDbCommand("SELECT TOP 1 SortOrder FROM BalanceQBMapping WHERE ClientName = ? AND ReportID = 1 AND IsActive = True AND GroupLabel = ?", conn)
                            cmdSort.Parameters.AddWithValue("?", selectedClient.Name)
                            cmdSort.Parameters.AddWithValue("?", col1)
                            Dim sortResult = cmdSort.ExecuteScalar()
                            If sortResult IsNot Nothing AndAlso IsNumeric(sortResult) Then
                                sortOrder = CInt(sortResult)
                            End If
                        End Using
                    End Using

                    'RichTextBox1.Text = RichTextBox1.Text & "/ncol1 value" & col1 & "col2 value" & col2 & "Nodenm value" & nodeName

                    If col1 = "Net Income" Then
                        dt.Rows.Add(col1, col2, "DataRow", colIds, sortOrder)
                    Else
                        dt.Rows.Add(col1, col2, nodeName, colIds, sortOrder)
                    End If
            End Select
        Next

        Dim sortedView As DataView = dt.DefaultView
        sortedView.Sort = "SortOrder ASC"
        dgvReport.DataSource = sortedView.ToTable()

        dgvReport.Columns("Label").Width = 250
        dgvReport.Columns("Amount").Width = 250
        dgvReport.Columns("RowType").Visible = False
        dgvReport.Columns("RowNumber").Visible = False
        dgvReport.Columns("SortOrder").Visible = False
    End Sub


    Private Sub LoadClients()
        Try
            Using conn As New OleDbConnection(connStr)
                conn.Open()

                Dim cmd As New OleDbCommand(
                "SELECT ClientName, QBFilePath FROM GroupClientMapping ORDER BY ClientName", conn)

                Dim reader As OleDbDataReader = cmd.ExecuteReader()
                Dim clientList As New List(Of ClientInfo)()

                While reader.Read()
                    Dim cname = reader("ClientName").ToString()
                    Dim cpath = reader("QBFilePath").ToString()
                    clientList.Add(New ClientInfo(cname, cpath))
                End While
                reader.Close()

                comboClient.DataSource = clientList
                comboClient.DisplayMember = "Name"
                comboClient.ValueMember = "FilePath"
                comboClient.Visible = (comboClient.Items.Count > 0)

                ' ------- modern styling -------
                With comboClient
                    .DropDownStyle = ComboBoxStyle.DropDown  ' ← allow typing
                    .AutoCompleteMode = AutoCompleteMode.SuggestAppend
                    .AutoCompleteSource = AutoCompleteSource.ListItems
                    .FlatStyle = FlatStyle.Standard
                    .BackColor = Color.White
                    .ForeColor = Color.DarkSlateGray
                    .Font = New Font("Verdana", 11)
                End With


                'With btnFetch
                '    .BackColor = Color.FromArgb(0, 150, 136)
                '    .ForeColor = Color.White
                '    .FlatStyle = FlatStyle.Flat
                '    .FlatAppearance.BorderSize = 0
                '    .Font = New Font("Verdana", 11, FontStyle.Bold)
                '    .Text = "📊 Fetch Report"
                'End With

                'With btnExport
                '    .BackColor = Color.FromArgb(37, 36, 81)
                '    .ForeColor = Color.White
                '    .FlatStyle = FlatStyle.Flat
                '    .FlatAppearance.BorderSize = 0
                '    .Font = New Font("Verdana", 11, FontStyle.Bold)
                '    .Text = "📥 Export to Excel"
                'End With

                For Each dtp In {dtpSelDate}
                    With dtp
                        .Font = New Font("Verdana", 11)
                        .CustomFormat = "dd MMM yyyy"
                        .Format = DateTimePickerFormat.Custom
                        .BackColor = Color.White
                        .ForeColor = Color.Black
                    End With
                Next

            End Using
        Catch ex As Exception
            MessageBox.Show("Error loading clients: " & ex.Message,
                        "DB Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Sub

    Private Sub ExportToExcel(sender As Object, e As EventArgs)



        If dgvReport.Rows.Count = 0 Then
            MessageBox.Show("No data to export.")
            Return
        End If

        ExcelPackage.LicenseContext = OfficeOpenXml.LicenseContext.NonCommercial

        Dim reportTitle As String = dgvReport.Rows(0).Cells(1).Value.ToString()

        Dim sfd As New SaveFileDialog()
        sfd.Filter = "Excel Files (.xlsx)|.xlsx"

        Dim sanitizedCompanyName As String = qbCompanyName.Replace(" ", "_").Trim()
        Dim randomNumber As String = New Random().Next(100000, 999999).ToString()
        Dim defaultFileName As String = $"{sanitizedCompanyName} - Balance_sheet_summary_Report {randomNumber}.xlsx"

        sfd.FileName = defaultFileName
        ' Step 1: Load GroupLabels where MarkForGRP = True
        Dim groupLabelsMarkedForGRP As New HashSet(Of String)(StringComparer.OrdinalIgnoreCase)
        Using conn As New OleDbConnection(connStr)
            conn.Open()
            Using cmd As New OleDbCommand("SELECT GroupLabel FROM BalanceQBMapping WHERE ReportID = 1 AND MarkForGRP = True", conn)
                Using reader = cmd.ExecuteReader()
                    While reader.Read()
                        Dim label = reader("GroupLabel")?.ToString()
                        If Not String.IsNullOrWhiteSpace(label) Then
                            groupLabelsMarkedForGRP.Add(label.Trim())
                        End If
                    End While
                End Using
            End Using
        End Using

        If sfd.ShowDialog() = DialogResult.OK Then
            'lblStatus.Text = "📥 Exporting to Excel. Please wait..."
            'lblStatus.Visible = True
            'Application.DoEvents()
            Cursor.Current = Cursors.WaitCursor
            ShowStatus("📥 Exporting to Excel. Please wait...")
            Using package As New ExcelPackage(New FileInfo(sfd.FileName))
                Dim worksheet As ExcelWorksheet = package.Workbook.Worksheets.Add("Report")
                ' Setup connection to Access DB
                Dim reportHeading As String = ""


                Using conn As New OleDbConnection(connStr)
                    conn.Open()

                    Dim cmd As New OleDbCommand("SELECT ClientName, ReportHead FROM ReportHeading WHERE ReportId = 1", conn)

                    Using reader As OleDbDataReader = cmd.ExecuteReader()
                        While reader.Read()
                            Dim dbClientName As String = reader("ClientName").ToString().Trim().TrimEnd("."c)
                            Dim inputClientName As String = qbCompanyName.Trim().TrimEnd("."c)

                            If String.Equals(dbClientName, inputClientName, StringComparison.OrdinalIgnoreCase) Then
                                reportHeading = reader("ReportHead").ToString().Trim()
                                Exit While
                            End If
                        End While
                    End Using
                End Using

                ' Align the last two columns (assumed to be columns 9 and 10) to the right
                worksheet.Column(5).Style.HorizontalAlignment = OfficeOpenXml.Style.ExcelHorizontalAlignment.Right
                worksheet.Column(6).Style.HorizontalAlignment = OfficeOpenXml.Style.ExcelHorizontalAlignment.Right

                Dim headingRow As Integer = 1
                Dim formattedDate As String = dtpSelDate.Value.ToString("MMMM dd, yyyy").ToUpper()

                Dim part1 As String = ""
                Dim part2 As String = ""

                worksheet.Cells(headingRow, 1).Value = qbCompanyName

                If reportHeading.Contains(")") Then
                    ' If a closing parenthesis is found
                    Dim idx As Integer = reportHeading.IndexOf(")") + 1
                    part1 = reportHeading.Substring(0, idx).Trim()
                    part2 = reportHeading.Substring(idx).Trim()
                Else
                    ' If no parenthesis found, whole heading goes to part2
                    part1 = ""
                    part2 = reportHeading.Trim()
                End If


                worksheet.Cells(headingRow + 1, 1).Value = part1
                worksheet.Cells(headingRow + 2, 1).Value = part2
                worksheet.Cells(headingRow + 3, 1).Value = $"AS OF {formattedDate}"

                ' Specify the total number of columns you want the header to span across (e.g., 10 columns)
                Dim totalColumns As Integer = 9 ' Adjust this as per your requirement

                ' Merge and style headings
                For r As Integer = headingRow To headingRow + 3
                    worksheet.Cells(r, 1, r, totalColumns).Merge = True ' Merge across the specified columns (totalColumns)
                    worksheet.Cells(r, 1, r, totalColumns).Style.HorizontalAlignment = OfficeOpenXml.Style.ExcelHorizontalAlignment.Center
                    worksheet.Cells(r, 1, r, totalColumns).Style.VerticalAlignment = OfficeOpenXml.Style.ExcelVerticalAlignment.Center
                    worksheet.Row(r).Height = 20
                    worksheet.Cells(r, 1, r, totalColumns).Style.Font.Bold = True
                    worksheet.Cells(r, 1, r, totalColumns).Style.Font.Size = 13
                Next

                ' For now, let's avoid writing the column headers
                Dim excelRow As Integer = headingRow + 5 ' Start writing from the next available row.
                Dim firstRowShown As Boolean = False

                For rowIndex As Integer = 0 To dgvReport.Rows.Count - 1




                    ' Skip the first row containing headers (Label, Amount, RowType, RowNumber)
                    If rowIndex = 0 Then
                        Continue For
                    End If

                    Dim rowType As String = dgvReport.Rows(rowIndex).Cells(2).Value?.ToString() ' Hidden RowType column

                    Dim currentnewLabel = dgvReport.Rows(rowIndex).Cells(0).Value?.ToString()
                    ' Check if current label is in the GRP-marked group labels
                    If Not String.IsNullOrWhiteSpace(currentnewLabel) AndAlso groupLabelsMarkedForGRP.Contains(currentnewLabel.Trim()) Then
                        rowType = "DataRow"
                    End If
                    If String.IsNullOrEmpty(rowType) Then
                        Continue For
                    End If

                    ' Add this at the top of the loop to capture current and next row info
                    Dim currentLabel As String = dgvReport.Rows(rowIndex).Cells(0).Value?.ToString()
                    Dim nextLabel As String = If(rowIndex + 1 < dgvReport.Rows.Count, dgvReport.Rows(rowIndex + 1).Cells(0).Value?.ToString(), "")
                    'Dim allowedHeadings = GetAllowedMainHeadings()

                    ' worksheet.Column(1).Width = 2
                    worksheet.Column(2).Width = 4
                    ' worksheet.Column(3).Width = 3
                    worksheet.Column(4).Width = 5
                    worksheet.Column(5).Width = 5
                    ' worksheet.Column(6).Width = 5
                    worksheet.Column(6).Width = 22
                    worksheet.Column(7).Width = 5
                    worksheet.Column(8).Width = 22

                    Select Case rowType

                        Case "TextRow"
                            Dim textValue As String = dgvReport.Rows(rowIndex).Cells(0).Value?.ToString()
                            Dim rowNumber As String = dgvReport.Rows(rowIndex).Cells(3).Value?.ToString()
                            Dim selectedClient As ClientInfo = CType(comboClient.SelectedItem, ClientInfo)

                            Dim labelToDisplay As String = textValue
                            Dim foundInTopLabel As Boolean = False

                            ' First try: fetch RenameTop using separate query
                            Using connTop As New OleDbConnection(connStr)
                                connTop.Open()

                                Dim renameTopQuery As String = "SELECT TOP 1 RenameTop FROM BalanceQBMapping WHERE ClientName = ? AND ReportID = 1 AND IsActive = True AND TopLabel = ? AND Trim(RenameTop) <> ''"
                                Using cmdRenameTop As New OleDbCommand(renameTopQuery, connTop)
                                    cmdRenameTop.Parameters.AddWithValue("?", selectedClient.Name)
                                    cmdRenameTop.Parameters.AddWithValue("?", textValue)

                                    Using readerRenameTop As OleDbDataReader = cmdRenameTop.ExecuteReader()
                                        If readerRenameTop.Read() Then
                                            Dim renameTop As String = readerRenameTop("RenameTop")?.ToString().Trim()
                                            If Not String.IsNullOrWhiteSpace(renameTop) Then
                                                labelToDisplay = renameTop
                                                foundInTopLabel = True
                                            End If
                                        End If
                                    End Using
                                End Using

                                ' If RenameTop not found, fallback to TopLabel directly
                                If Not foundInTopLabel Then
                                    Dim topLabelQuery As String = "SELECT TOP 1 TopLabel FROM BalanceQBMapping WHERE ClientName = ? AND ReportID = 1 AND IsActive = True AND TopLabel = ?"
                                    Using cmdTopLabel As New OleDbCommand(topLabelQuery, connTop)
                                        cmdTopLabel.Parameters.AddWithValue("?", selectedClient.Name)
                                        cmdTopLabel.Parameters.AddWithValue("?", textValue)

                                        Using readerTop As OleDbDataReader = cmdTopLabel.ExecuteReader()
                                            If readerTop.Read() Then
                                                Dim topLabel As String = readerTop("TopLabel")?.ToString().Trim()
                                                If Not String.IsNullOrWhiteSpace(topLabel) Then
                                                    labelToDisplay = topLabel
                                                    foundInTopLabel = True
                                                End If
                                            End If
                                        End Using
                                    End Using
                                End If
                            End Using

                            If foundInTopLabel Then
                                ' Apply TopLabel/RenameTop formatting (centered)
                                worksheet.Cells(excelRow, 1, excelRow, 9).Merge = True
                                worksheet.Cells(excelRow, 1).Value = labelToDisplay
                                worksheet.Cells(excelRow, 1).Style.HorizontalAlignment = OfficeOpenXml.Style.ExcelHorizontalAlignment.Center
                                worksheet.Cells(excelRow, 1).Style.Font.Size = 13
                                worksheet.Cells(excelRow, 1).Style.Font.Bold = True
                            Else
                                ' --- New query to check FSGroupName and RenameFS ---
                                Dim fsgroupLabelToShow As String = textValue
                                'Console.WriteLine("Input FSGroup label: " & textValue)

                                Using conn2 As New OleDbConnection(connStr)
                                    conn2.Open()

                                    ' Step 1: Try to get RenameFS that is not NULL or empty
                                    Dim renameQuery As String = "SELECT RenameFS FROM BalanceQBMapping WHERE ClientName = ? AND ReportID = 1 AND IsActive = True AND FSGroupName = ? AND RenameFS IS NOT NULL AND Trim(RenameFS) <> ''"

                                    Using cmdRename As New OleDbCommand(renameQuery, conn2)
                                        cmdRename.Parameters.AddWithValue("?", selectedClient.Name)
                                        cmdRename.Parameters.AddWithValue("?", textValue)

                                        Using readerRename As OleDbDataReader = cmdRename.ExecuteReader()
                                            If readerRename.Read() Then
                                                Dim renameFS As String = readerRename("RenameFS")?.ToString().Trim()
                                                fsgroupLabelToShow = renameFS
                                                'Console.WriteLine("Using RenameFS: " & renameFS)
                                            Else
                                                ' Step 2: If no RenameFS, get FSGroupName (any one, since all will be same)
                                                Dim fallbackQuery As String = "SELECT FSGroupName FROM BalanceQBMapping WHERE ClientName = ? AND ReportID = 1 AND IsActive = True AND FSGroupName = ?"

                                                Using cmdFSGroup As New OleDbCommand(fallbackQuery, conn2)
                                                    cmdFSGroup.Parameters.AddWithValue("?", selectedClient.Name)
                                                    cmdFSGroup.Parameters.AddWithValue("?", textValue)

                                                    Using readerFSGroup As OleDbDataReader = cmdFSGroup.ExecuteReader()
                                                        If readerFSGroup.Read() Then
                                                            Dim fsGroupName As String = readerFSGroup("FSGroupName")?.ToString().Trim()
                                                            fsgroupLabelToShow = fsGroupName
                                                            'Console.WriteLine("Fallback to FSGroupName: " & fsGroupName)
                                                        Else
                                                            'Console.WriteLine("No matching FSGroupName found.")
                                                        End If
                                                    End Using
                                                End Using
                                            End If
                                        End Using
                                    End Using
                                End Using

                                ' Retain last valid fsWidth across loop iterations
                                Static fsWidth As Double = 2 ' default width

                                Using conn3 As New OleDbConnection(connStr)
                                    conn3.Open()

                                    Dim widthQuery As String = "SELECT TOP 1 FSWidth FROM BalanceQBMapping " &
                                    "WHERE ClientName = ? AND ReportID = 1 AND IsActive = True " &
                                    "AND FSGroupName = ? AND FSWidth IS NOT NULL"

                                    Using cmdWidth As New OleDbCommand(widthQuery, conn3)
                                        cmdWidth.Parameters.AddWithValue("?", selectedClient.Name)
                                        cmdWidth.Parameters.AddWithValue("?", textValue)

                                        Using readerWidth As OleDbDataReader = cmdWidth.ExecuteReader()
                                            If readerWidth.Read() Then
                                                Dim dbWidthStr As String = readerWidth("FSWidth")?.ToString().Trim()
                                                Dim parsed As Double
                                                If Not String.IsNullOrWhiteSpace(dbWidthStr) AndAlso Double.TryParse(dbWidthStr, parsed) Then
                                                    fsWidth = parsed ' update only if valid
                                                End If
                                            End If
                                        End Using
                                    End Using
                                End Using

                                'Console.WriteLine("Final fsWidth being applied for FSGroup '" & textValue & "': " & fsWidth)

                                ' Apply the retained or updated width
                                worksheet.Column(1).Width = fsWidth

                                ' Default width if no value found in DB
                                'Dim fsWidth As Double = 2

                                'Using conn3 As New OleDbConnection(connStr)
                                '    conn3.Open()

                                '    Dim widthQuery As String = "SELECT TOP 1 FSWidth FROM BalanceQBMapping WHERE ClientName = ? AND ReportID = 1 AND IsActive = True AND FSGroupName = ? AND FSWidth IS NOT NULL"

                                '    Using cmdWidth As New OleDbCommand(widthQuery, conn3)
                                '        cmdWidth.Parameters.AddWithValue("?", selectedClient.Name)
                                '        cmdWidth.Parameters.AddWithValue("?", textValue)

                                '        Using readerWidth As OleDbDataReader = cmdWidth.ExecuteReader()
                                '            If readerWidth.Read() Then
                                '                Dim dbWidthStr As String = readerWidth("FSWidth")?.ToString().Trim()
                                '                If Double.TryParse(dbWidthStr, fsWidth) Then
                                '                    ' parsed successfully into fsWidth
                                '                End If
                                '            End If
                                '        End Using
                                '    End Using
                                'End Using

                                '' Now apply that width dynamically to Column(1)
                                'worksheet.Column(1).Width = fsWidth

                                ' Apply to Excel cell
                                worksheet.Cells(excelRow, 1).Style.Font.Bold = True
                                worksheet.Cells(excelRow, 1).Value = fsgroupLabelToShow
                                worksheet.Cells(excelRow, 1).Style.Font.Size = 13
                                worksheet.Cells(excelRow, 1).Style.HorizontalAlignment = OfficeOpenXml.Style.ExcelHorizontalAlignment.Left


                            End If

                            'Else
                            '    worksheet.Cells(excelRow, 1).Style.Font.Bold = True
                            '    worksheet.Cells(excelRow, 1).Value = labelToDisplay
                            '    worksheet.Cells(excelRow, 1).Style.Font.Size = 12
                            '    worksheet.Cells(excelRow, 1).Style.HorizontalAlignment = OfficeOpenXml.Style.ExcelHorizontalAlignment.Left
                            'End If

                            If textValue = "LIABILITIES & EQUITY" Then
                                worksheet.InsertRow(excelRow, 2) ' Insert 2 rows above
                                worksheet.Cells(excelRow, 1).Value = ""
                                worksheet.Cells(excelRow + 1, 1).Value = ""
                                excelRow += 2
                            End If

                            excelRow += 1

                        Case "SubtotalRow", "TotalRow"
                            ' Lowercase current label for consistent comparison
                            Dim currentLabelLower As String = currentLabel.ToLowerInvariant()

                            ' Normal data rows
                            Dim col1Value As String = dgvReport.Rows(rowIndex).Cells(0).Value?.ToString()
                            Dim col2Value As String = dgvReport.Rows(rowIndex).Cells(1).Value?.ToString()
                            Dim rowNumberValue As String = dgvReport.Rows(rowIndex).Cells(3).Value?.ToString().Trim() ' RowNumber with trimming
                            'MsgBox(col1Value)
                            ' Split RowNumber value by comma (if multiple values)
                            Dim rowNumbers As String() = rowNumberValue.Split(","c)

                            ' Flag to ensure the label is only written once
                            Dim isLabelWritten As Boolean = False
                            ' Declare this above your loop (e.g., For Each or While loop)
                            Dim isFirstCol2Written As Boolean = False

                            Dim displayLabel As String = col1Value
                            Dim selectedClient As ClientInfo = CType(comboClient.SelectedItem, ClientInfo)

                            ' Check if label might be an FSGroup (and needs to be renamed) RenameGrpLabel
                            Using conn As New OleDbConnection(connStr)
                                conn.Open()

                                Using cmd As New OleDbCommand("SELECT RenameGrpLabel FROM BalanceQBMapping WHERE ClientName = ? AND ReportID = 1 AND GroupLabel = ?", conn)
                                    cmd.Parameters.AddWithValue("?", selectedClient.Name)
                                    cmd.Parameters.AddWithValue("?", col1Value)

                                    Dim result As Object = cmd.ExecuteScalar()
                                    If result IsNot Nothing AndAlso Not Convert.ToString(result).Trim() = "" Then
                                        displayLabel = Convert.ToString(result).Trim()
                                    End If
                                End Using
                            End Using

                            ' Loop through each value in the split RowNumber string
                            For Each number In rowNumbers
                                Dim trimmedNumber As String = number.Trim() ' Remove any spaces from the number

                                Dim shouldPrint As Boolean = False
                                worksheet.Cells(excelRow, 2).Style.Font.Size = 13
                                worksheet.Cells(excelRow, 3).Style.Font.Size = 13
                                worksheet.Cells(excelRow, 8).Style.Font.Size = 13
                                worksheet.Cells(excelRow, 6).Style.Font.Size = 13
                                worksheet.Cells(excelRow, 4).Style.Font.Size = 13
                                Dim needsTotalDollarFormat As Boolean = False
                                Dim isTopLabel As Boolean = False
                                'Dim selectedClient As ClientInfo = CType(cmbClients.SelectedItem, ClientInfo)
                                Dim extractedLabel As String = System.Text.RegularExpressions.Regex.Replace(col1Value, "^Total\s+", "", RegexOptions.IgnoreCase).Trim()

                                Using conn As New OleDbConnection(connStr)
                                    conn.Open()

                                    ' 1. Check for TotalDollarFlag based on FSGroupName (from extracted label)
                                    Using cmd As New OleDbCommand("SELECT DollarFlag FROM BalanceQBMapping WHERE ClientName = ? AND ReportID = 1 AND GroupLabel = ?", conn)
                                        cmd.Parameters.AddWithValue("?", selectedClient.Name)
                                        cmd.Parameters.AddWithValue("?", col1Value)

                                        Dim result As Object = cmd.ExecuteScalar()
                                        If result IsNot Nothing AndAlso result IsNot DBNull.Value Then
                                            needsTotalDollarFormat = Convert.ToBoolean(result)
                                        End If
                                    End Using

                                    ' 2. Check if col1Value is a TopLabel (direct match from col1Value to TopLabel)
                                    Using cmd2 As New OleDbCommand("SELECT COUNT(*) FROM BalanceQBMapping WHERE ClientName = ? AND ReportID = 1 AND TopLabel = ?", conn)
                                        cmd2.Parameters.AddWithValue("?", selectedClient.Name)
                                        cmd2.Parameters.AddWithValue("?", extractedLabel)

                                        Dim count As Integer = Convert.ToInt32(cmd2.ExecuteScalar())
                                        If count > 0 Then
                                            isTopLabel = True
                                        End If
                                    End Using
                                End Using

                                If isTopLabel Then
                                    worksheet.Cells(excelRow, 1).Value = displayLabel 'col1Value ' Column A
                                    worksheet.Cells(excelRow, 1).Style.Font.Size = 13
                                    worksheet.Cells(excelRow, 1).Style.Font.Bold = True
                                    worksheet.Cells(excelRow, 1).Style.HorizontalAlignment = OfficeOpenXml.Style.ExcelHorizontalAlignment.Left

                                    worksheet.Cells(excelRow, 8).Value = Convert.ToDecimal(col2Value)
                                    worksheet.Cells(excelRow, 8).Style.Numberformat.Format = "$   #,##0.00;($   #,##0.00)"
                                    worksheet.Cells(excelRow, 8).Style.Font.Size = 13
                                    worksheet.Cells(excelRow, 8).Style.Font.Bold = True
                                    worksheet.Cells(excelRow, 8).Style.Border.Bottom.Style = OfficeOpenXml.Style.ExcelBorderStyle.Thin
                                    worksheet.Cells(excelRow, 8).Style.Border.Bottom.Color.SetColor(System.Drawing.Color.Black)

                                    worksheet.Cells(excelRow, 8).Style.Border.Top.Style = OfficeOpenXml.Style.ExcelBorderStyle.Thin
                                    worksheet.Cells(excelRow, 8).Style.Border.Top.Color.SetColor(System.Drawing.Color.Black)
                                    'MsgBox(col1Value)
                                Else
                                    worksheet.Cells(excelRow, 3).Value = displayLabel

                                    Dim GRPWidth As Double = 50

                                    Using conn3 As New OleDbConnection(connStr)
                                        conn3.Open()

                                        Dim widthQuery As String = "SELECT TOP 1 GRPWidth FROM BalanceQBMapping WHERE ClientName = ? AND ReportID = 1 AND IsActive = True AND GroupLabel = ? AND GRPWidth IS NOT NULL"

                                        Using cmdWidth As New OleDbCommand(widthQuery, conn3)
                                            cmdWidth.Parameters.AddWithValue("?", selectedClient.Name)
                                            cmdWidth.Parameters.AddWithValue("?", col1Value)

                                            Using readerWidth As OleDbDataReader = cmdWidth.ExecuteReader()
                                                If readerWidth.Read() Then
                                                    Dim dbWidthStr As String = readerWidth("GRPWidth")?.ToString().Trim()
                                                    Dim parsedWidth As Double
                                                    If Double.TryParse(dbWidthStr, parsedWidth) Then
                                                        GRPWidth = parsedWidth
                                                        Console.WriteLine("GRPWidth applied: " & GRPWidth) ' Optional: for checking width
                                                    End If
                                                End If
                                            End Using
                                        End Using
                                    End Using

                                    ' Now apply that width dynamically to Column(4)
                                    worksheet.Column(3).Width = GRPWidth

                                    Dim isSubtotalRow As Boolean = False
                                    Dim isTopDivider As Boolean = False

                                    Using conn2 As New OleDbConnection(connStr)
                                        conn2.Open()

                                        ' Fetch SubtotalRow
                                        Using cmd As New OleDbCommand("SELECT SubtotalRow FROM BalanceQBMapping WHERE ClientName = ? AND ReportID = 1 AND GroupLabel = ?", conn2)
                                            cmd.Parameters.AddWithValue("?", selectedClient.Name)
                                            cmd.Parameters.AddWithValue("?", col1Value)

                                            Dim subtotalResult As Object = cmd.ExecuteScalar()
                                            If subtotalResult IsNot Nothing AndAlso subtotalResult IsNot DBNull.Value Then
                                                isSubtotalRow = Convert.ToBoolean(subtotalResult)
                                            End If
                                        End Using

                                        ' Fetch TopDivider
                                        Using cmdTop As New OleDbCommand("SELECT TopDivider FROM BalanceQBMapping WHERE ClientName = ? AND ReportID = 1 AND GroupLabel = ?", conn2)
                                            cmdTop.Parameters.AddWithValue("?", selectedClient.Name)
                                            cmdTop.Parameters.AddWithValue("?", col1Value)

                                            Dim topDividerResult As Object = cmdTop.ExecuteScalar()
                                            If topDividerResult IsNot Nothing AndAlso topDividerResult IsNot DBNull.Value Then
                                                isTopDivider = Convert.ToBoolean(topDividerResult)
                                            End If
                                        End Using
                                    End Using

                                    ' Step: If SubtotalRow is true, print to Column 5
                                    If isSubtotalRow Then
                                        If needsTotalDollarFormat Then
                                            worksheet.Cells(excelRow, 6).Value = Convert.ToDecimal(col2Value)
                                            worksheet.Cells(excelRow, 6).Style.Numberformat.Format = "$   #,##0.00;($   #,##0.00)"
                                        Else
                                            worksheet.Cells(excelRow, 6).Value = Convert.ToDecimal(col2Value)
                                            worksheet.Cells(excelRow, 6).Style.Numberformat.Format = "#,##0.00;(#,##0.00);0"
                                        End If
                                    Else
                                        If needsTotalDollarFormat Then
                                            worksheet.Cells(excelRow, 8).Value = Convert.ToDecimal(col2Value)
                                            worksheet.Cells(excelRow, 8).Style.Numberformat.Format = "$   #,##0.00;($   #,##0.00)"
                                        Else
                                            worksheet.Cells(excelRow, 8).Value = Convert.ToDecimal(col2Value)
                                            worksheet.Cells(excelRow, 8).Style.Numberformat.Format = "#,##0.00;(#,##0.00);0"
                                        End If

                                        ' worksheet.Cells(excelRow, 4).Style.Font.Bold = True
                                        worksheet.Cells(excelRow, 8).Style.Font.Bold = True
                                    End If

                                    ' ✅ Only apply top border if TopDivider is true
                                    If isTopDivider Then
                                        worksheet.Cells(excelRow, 6).Style.Border.Top.Style = OfficeOpenXml.Style.ExcelBorderStyle.Thin
                                        worksheet.Cells(excelRow, 6).Style.Border.Top.Color.SetColor(System.Drawing.Color.Black)
                                    End If

                                End If


                            Next

                            ' After writing to the row, increment the excelRow to ensure the next data goes to a new row.
                            excelRow += 1

                        Case "DataRow"
                            Dim selectedClient As ClientInfo = CType(comboClient.SelectedItem, ClientInfo)

                            ' Lowercase current label for consistent comparison
                            Dim currentLabelLower As String = currentLabel.ToLowerInvariant()

                            ' Normal data rows
                            Dim col1Value As String = dgvReport.Rows(rowIndex).Cells(0).Value?.ToString()
                            Dim col2Value As String = dgvReport.Rows(rowIndex).Cells(1).Value?.ToString()
                            Dim rowNumberValue As String = dgvReport.Rows(rowIndex).Cells(3).Value?.ToString().Trim() ' RowNumber with trimming


                            Dim newrawLabel As String = ""
                            newrawLabel = col1Value
                            col1Value = CleanLabel(col1Value)
                            Dim groupCode As String = ""
                            Dim match = System.Text.RegularExpressions.Regex.Match(newrawLabel, "(\d+)")
                            If match.Success Then
                                groupCode = match.Groups(1).Value
                            End If
                            ' Split RowNumber value by comma (if multiple values)
                            Dim rowNumbers As String() = rowNumberValue.Split(","c)

                            ' Flag to ensure the label is only written once
                            Dim isLabelWritten As Boolean = False
                            ' Declare this above your loop (e.g., For Each or While loop)
                            Dim isFirstCol2Written As Boolean = False

                            ' Set column widths before populating cells
                            ' Default width if no value found in DB
                            Dim GRPWidth As Double = 3

                            Using conn3 As New OleDbConnection(connStr)
                                conn3.Open()

                                Dim widthQuery As String = "SELECT TOP 1 GRPWidth FROM BalanceQBMapping WHERE ClientName = ? AND ReportID = 1 AND IsActive = True AND GroupLabel = ? AND GRPWidth IS NOT NULL"

                                Using cmdWidth As New OleDbCommand(widthQuery, conn3)
                                    cmdWidth.Parameters.AddWithValue("?", selectedClient.Name)
                                    cmdWidth.Parameters.AddWithValue("?", col1Value)

                                    Using readerWidth As OleDbDataReader = cmdWidth.ExecuteReader()
                                        If readerWidth.Read() Then
                                            Dim dbWidthStr As String = readerWidth("GRPWidth")?.ToString().Trim()
                                            Dim tempWidth As Double
                                            If Double.TryParse(dbWidthStr, tempWidth) Then
                                                GRPWidth = tempWidth
                                            End If
                                        End If
                                    End Using
                                End Using
                            End Using

                            ' Now apply that width dynamically to Column(3)
                            worksheet.Column(2).Width = GRPWidth

                            'Dim GRPWidth As Double = 3

                            'Using conn3 As New OleDbConnection(connStr)
                            '    conn3.Open()

                            '    Dim widthQuery As String = "SELECT TOP 1 GRPWidth FROM BalanceQBMapping WHERE ClientName = ? AND ReportID = 1 AND IsActive = True AND GroupLabel = ? AND GRPWidth IS NOT NULL"

                            '    Using cmdWidth As New OleDbCommand(widthQuery, conn3)
                            '        cmdWidth.Parameters.AddWithValue("?", selectedClient.Name)
                            '        cmdWidth.Parameters.AddWithValue("?", col1Value)

                            '        Using readerWidth As OleDbDataReader = cmdWidth.ExecuteReader()
                            '            If readerWidth.Read() Then
                            '                Dim dbWidthStr As String = readerWidth("GRPWidth")?.ToString().Trim()
                            '                If Double.TryParse(dbWidthStr, GRPWidth) Then
                            '                    ' parsed successfully into fsWidth
                            '                End If
                            '            End If
                            '        End Using
                            '    End Using
                            'End Using

                            '' Now apply that width dynamically to Column(1)
                            'worksheet.Column(3).Width = GRPWidth

                            ' Loop through each value in the split RowNumber string
                            For Each number In rowNumbers
                                Dim trimmedNumber As String = number.Trim() ' Remove any spaces from the number

                                If trimmedNumber = "1" Then
                                    worksheet.Cells(excelRow, 2).Style.Font.Size = 13

                                    ' Place data in Column A, but only if the label hasn't been written yet
                                    If Not isLabelWritten Then
                                        'worksheet.Cells(excelRow, 2).Value = col1Value ' Column A
                                        Dim displayLabel As String = col1Value ' Default label to show

                                        Using conn As New OleDbConnection(connStr)
                                            conn.Open()

                                            ' Fetch RenameGrpLabel if available
                                            Using cmd As New OleDbCommand("SELECT RenameGrpLabel FROM BalanceQBMapping WHERE ClientName = ? AND ReportID = 1 AND GroupLabel = ?", conn)
                                                cmd.Parameters.AddWithValue("?", selectedClient.Name)
                                                cmd.Parameters.AddWithValue("?", col1Value)

                                                Dim result As Object = cmd.ExecuteScalar()
                                                If result IsNot Nothing AndAlso Not Convert.ToString(result).Trim() = "" Then
                                                    displayLabel = Convert.ToString(result).Trim()
                                                End If
                                            End Using
                                        End Using

                                        worksheet.Cells(excelRow, 2).Value = displayLabel

                                        isLabelWritten = True ' Set flag to true to avoid writing it again
                                    End If
                                    worksheet.Cells(excelRow, 6).Value = col2Value ' Column G
                                    'worksheet.Cells(excelRow, 5).Value = Convert.ToDecimal(col2Value)

                                ElseIf trimmedNumber = "2" Then
                                    worksheet.Cells(excelRow, 6).Style.Font.Size = 13

                                    ' Check if col1Value matches any of the labels (case-insensitive)
                                    Dim col1ValLower As String = If(col1Value, "").ToLower()
                                    ' --- Remove the old label array and instead fetch DollarFlag from database ---

                                    Dim needsDollarFormat As Boolean = False

                                    'Using conn As New OleDbConnection(connStr)
                                    '    conn.Open()

                                    '    Using cmd As New OleDbCommand("SELECT DollarFlag FROM BalanceQBMapping WHERE ClientName = ? AND ReportID = 1  AND GroupCode = ?", conn)
                                    '        cmd.Parameters.AddWithValue("?", selectedClient.Name)
                                    '        cmd.Parameters.AddWithValue("?", groupCode)

                                    '        Dim result As Object = cmd.ExecuteScalar()
                                    '        If result IsNot Nothing AndAlso result IsNot DBNull.Value Then
                                    '            needsDollarFormat = Convert.ToBoolean(result)
                                    '        End If
                                    '    End Using
                                    'End Using
                                    Using conn As New OleDbConnection(connStr)
                                        conn.Open()

                                        Dim query As String
                                        If col1Value = "Net Income" Then
                                            query = "SELECT DollarFlag FROM BalanceQBMapping WHERE ClientName = ? AND ReportID = 1 AND GroupLabel = ?"
                                        Else
                                            query = "SELECT DollarFlag FROM BalanceQBMapping WHERE ClientName = ? AND ReportID = 1 AND GroupCode = ?"
                                        End If

                                        Using cmd As New OleDbCommand(query, conn)
                                            cmd.Parameters.AddWithValue("?", selectedClient.Name)
                                            If col1Value = "Net Income" Then
                                                cmd.Parameters.AddWithValue("?", col1Value)
                                            Else
                                                cmd.Parameters.AddWithValue("?", groupCode)
                                            End If

                                            Dim result As Object = cmd.ExecuteScalar()
                                            If result IsNot Nothing AndAlso result IsNot DBNull.Value Then
                                                needsDollarFormat = Convert.ToBoolean(result)
                                            End If
                                        End Using
                                    End Using
                                    If needsDollarFormat Then
                                        ' Apply currency format
                                        Dim cleanedValue As String = col2Value?.Replace(",", "").Trim()

                                        Dim parsedValue As Decimal
                                        If Decimal.TryParse(cleanedValue, Globalization.NumberStyles.Any, CultureInfo.InvariantCulture, parsedValue) Then
                                            worksheet.Cells(excelRow, 6).Value = parsedValue
                                            If Not isFirstCol2Written Then
                                                worksheet.Cells(excelRow, 6).Style.Numberformat.Format = "$   #,##0.00;($   #,##0.00)"
                                                isFirstCol2Written = True
                                            End If
                                        Else
                                            worksheet.Cells(excelRow, 6).Value = col2Value ' fallback
                                        End If

                                        'worksheet.Column(7).AutoFit()
                                    Else
                                        ' No dollar format - regular numeric formatting
                                        Dim cleanedValue As String = col2Value?.Replace(",", "").Trim()

                                        Dim parsedValue As Decimal
                                        If Decimal.TryParse(cleanedValue, Globalization.NumberStyles.Any, CultureInfo.InvariantCulture, parsedValue) Then
                                            worksheet.Cells(excelRow, 6).Value = parsedValue
                                            If Not isFirstCol2Written Then
                                                worksheet.Cells(excelRow, 6).Style.Numberformat.Format = "#,##0.00_);(#,##0.00)"
                                                isFirstCol2Written = True
                                            End If
                                        Else
                                            worksheet.Cells(excelRow, 6).Value = col2Value ' fallback
                                        End If
                                    End If

                                Else
                                    ' For any other RowNumber value, place it in Column C (or handle as needed)

                                End If

                            Next

                            ' After writing to the row, increment the excelRow to ensure the next data goes to a new row.
                            excelRow += 1

                    End Select
                Next

                ' Auto-fit
                package.Save()
            End Using

            'lblStatus.Text = ""
            'lblStatus.Visible = False
            HideStatus()
            Cursor.Current = Cursors.Default
            MessageBox.Show("Data exported to Excel successfully.", "Success", MessageBoxButtons.OK, MessageBoxIcon.Information)
        End If
    End Sub


    Private Sub ShowStatus(msg As String)
        lblStatus.Text = msg
        pnlOverlay.BringToFront()
        pnlOverlay.Visible = True
        Application.DoEvents()          ' repaint immediately
    End Sub

    ' hide
    Private Sub HideStatus()
        pnlOverlay.Visible = False
    End Sub

    Private Sub BuildUI()
        Me.Dock = DockStyle.Fill
        Me.BackColor = Color.White

        ' ---------- Top Panel ----------
        Dim topPanel As New Panel With {
        .Height = 80,
        .Dock = DockStyle.Top,
        .Padding = New Padding(15)
    }

        comboClient = New ComboBox With {
        .DropDownStyle = ComboBoxStyle.DropDownList,
        .Width = 220,
        .Font = New Font("Verdana", 11),
        .Height = 32
    }
        comboClient.Items.AddRange({"Client A", "Client B", "Client C"})

        dtpSelDate = New DateTimePicker With {
        .Format = DateTimePickerFormat.Short,
        .Width = 140,
        .Font = New Font("Verdana", 11)
    }

        ' Fetch Button Styles
        btnFetch = New Button With {
        .Text = "Fetch Report",
        .Width = 180,
        .Height = 36,
        .Font = New Font("Verdana", 10, FontStyle.Bold),
        .BackColor = Color.Transparent,
        .ForeColor = Color.FromArgb(0, 150, 136),
        .FlatStyle = FlatStyle.Flat
    }
        btnFetch.FlatAppearance.BorderColor = Color.FromArgb(0, 150, 136)
        btnFetch.FlatAppearance.BorderSize = 2
        AddHandler btnFetch.MouseEnter, Sub()
                                            btnFetch.BackColor = Color.FromArgb(0, 150, 136)
                                            btnFetch.ForeColor = Color.White
                                        End Sub
        AddHandler btnFetch.MouseLeave, Sub()
                                            btnFetch.BackColor = Color.Transparent
                                            btnFetch.ForeColor = Color.FromArgb(0, 150, 136)
                                        End Sub

        ' Export Button Styles
        btnExport = New Button With {
        .Text = "Export to Excel",
        .Width = 180,
        .Height = 36,
        .Font = New Font("Verdana", 10, FontStyle.Bold),
        .BackColor = Color.Transparent,
        .ForeColor = Color.FromArgb(37, 36, 81),
        .FlatStyle = FlatStyle.Flat
    }
        btnExport.FlatAppearance.BorderColor = Color.FromArgb(37, 36, 81)
        btnExport.FlatAppearance.BorderSize = 2
        AddHandler btnExport.MouseEnter, Sub()
                                             btnExport.BackColor = Color.FromArgb(37, 36, 81)
                                             btnExport.ForeColor = Color.White
                                         End Sub
        AddHandler btnExport.MouseLeave, Sub()
                                             btnExport.BackColor = Color.Transparent
                                             btnExport.ForeColor = Color.FromArgb(37, 36, 81)
                                         End Sub

        ' Add controls to panel
        topPanel.Controls.Add(comboClient)
        topPanel.Controls.Add(dtpSelDate)
        topPanel.Controls.Add(btnFetch)
        topPanel.Controls.Add(btnExport)

        ' Positioning
        comboClient.Location = New Point(10, 20)
        dtpSelDate.Location = New Point(comboClient.Right + 15, 20)
        btnFetch.Location = New Point(dtpSelDate.Right + 30, 18)
        btnExport.Location = New Point(btnFetch.Right + 10, 18)

        ' ---------- DataGridView ----------
        dgvReport = New DataGridView With {
        .Dock = DockStyle.Fill,
        .Font = New Font("Verdana", 10),
        .ColumnHeadersHeightSizeMode = DataGridViewColumnHeadersHeightSizeMode.AutoSize,
        .RowTemplate = New DataGridViewRow() With {.Height = 28},
        .EnableHeadersVisualStyles = False,
        .ColumnHeadersDefaultCellStyle = New DataGridViewCellStyle With {
            .BackColor = Color.FromArgb(31, 30, 68),
            .ForeColor = Color.White,
            .Font = New Font("Verdana", 10, FontStyle.Bold)
        },
        .AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.Fill,
        .BackgroundColor = Color.White
    }

        ' ---------- Overlay Panel ----------
        pnlOverlay = New Panel With {
        .Size = New Size(400, 50),
        .BackColor = Color.FromArgb(20, 0, 150, 136),
        .Visible = False,
        .Anchor = AnchorStyles.None
    }
        AddHandler Me.Resize, Sub(sender, e)
                                  pnlOverlay.Location = New Point(
                                  (Me.ClientSize.Width - pnlOverlay.Width) \ 2,
                                  (Me.ClientSize.Height - pnlOverlay.Height) \ 2)
                              End Sub

        lblStatus = New Label With {
        .Dock = DockStyle.Fill,
        .Font = New Font("Verdana Semibold", 11, FontStyle.Bold),
        .ForeColor = Color.Black,
        .BackColor = Color.Transparent,
        .TextAlign = ContentAlignment.MiddleCenter
    }

        AddHandler pnlOverlay.SizeChanged,
        Sub() lblStatus.Location = New Point(
            (pnlOverlay.Width - lblStatus.Width) \ 2,
            (pnlOverlay.Height - lblStatus.Height) \ 2)

        pnlOverlay.Controls.Add(lblStatus)

        ' Add controls
        Me.Controls.Add(pnlOverlay)
        Me.Controls.Add(dgvReport)
        Me.Controls.Add(topPanel)
    End Sub



End Class



'If comboClient.SelectedItem Is Nothing Then
'    MessageBox.Show("Please select a client.", "Missing", MessageBoxButtons.OK,
'                MessageBoxIcon.Warning)
'    Return
'End If

'Dim selected As ClientInfo = CType(comboClient.SelectedItem, ClientInfo)
'Dim qbPath As String = selected.FilePath
'Dim reportDate As Date = dtpSelDate.Value.Date   ' you renamed dtTo -> dtFrom only

'Dim sessionMgr As New QBSessionManager()
'Try
'    ' --- Start QB session ---

'    Cursor.Current = Cursors.WaitCursor
'    ShowStatus("🔄 Connecting to QuickBooks…")
'    sessionMgr.OpenConnection("", "Mooneem App")
'    sessionMgr.BeginSession(qbPath, ENOpenMode.omDontCare)
'    ' 1️⃣  GET COMPANY NAME ---------------------------------
'    Dim compReq As IMsgSetRequest = sessionMgr.CreateMsgSetRequest("US", 8, 0)
'    compReq.Attributes.OnError = ENRqOnError.roeStop
'    compReq.AppendCompanyQueryRq()

'    Dim compRespSet As IMsgSetResponse = sessionMgr.DoRequests(compReq)
'    Dim compResp As IResponse = compRespSet.ResponseList.GetAt(0)

'    qbCompanyName = "Unknown"
'    If compResp.StatusCode = 0 AndAlso compResp.Detail IsNot Nothing Then
'        qbCompanyName = CType(compResp.Detail, ICompanyRet).CompanyName.GetValue()
'    End If
'    ' --- Build balance‑sheet request ---
'    Dim reqSet As IMsgSetRequest = sessionMgr.CreateMsgSetRequest("US", 8, 0)
'    reqSet.Attributes.OnError = ENRqOnError.roeStop
'    Dim repQuery As IGeneralSummaryReportQuery = reqSet.AppendGeneralSummaryReportQueryRq()
'    repQuery.GeneralSummaryReportType.SetValue(ENGeneralSummaryReportType.gsrtBalanceSheetStandard)
'    repQuery.ORReportPeriod.ReportPeriod.ToReportDate.SetValue(reportDate)

'    ' --- Send request ---
'    Dim respSet As IMsgSetResponse = sessionMgr.DoRequests(reqSet)
'    Dim resp As IResponse = respSet.ResponseList.GetAt(0)

'    If resp.StatusCode = 0 AndAlso resp.Detail IsNot Nothing Then
'        Dim xmlOut As String = respSet.ToXMLString()

'        ' ⬇️  LOAD DATA INTO GRID
'        LoadBalanceSheetIntoGrid(xmlOut)
'        'MessageBox.Show($"✅ Balance Sheet fetched for {selected.Name} on {reportDate:d}.",
'        '            "Success", MessageBoxButtons.OK, MessageBoxIcon.Information)
'    Else
'        MessageBox.Show($"No data returned. Status: {resp.StatusMessage}",
'                    "QuickBooks", MessageBoxButtons.OK, MessageBoxIcon.Warning)
'    End If
'Catch ex As Exception
'    MessageBox.Show("QuickBooks error: " & ex.Message, "Error",
'                MessageBoxButtons.OK, MessageBoxIcon.Error)
'Finally
'    Try
'        sessionMgr.EndSession()
'        sessionMgr.CloseConnection()
'    Catch : End Try
'End Try
