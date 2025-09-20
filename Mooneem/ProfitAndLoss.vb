' ProfitAndLoss.vb  –  UserControl for P&L report
Imports System.Data.OleDb
Imports System.Drawing
Imports System.IO
Imports System.Text.RegularExpressions
Imports System.Windows.Forms
Imports System.Xml
Imports OfficeOpenXml
Imports Org.BouncyCastle.Asn1.Cmp
Imports QBFC12Lib
Imports QBXMLRP2Lib

Public Class ProfitAndLoss
    Inherits UserControl

    ' ---- DB connection (adjust path) ----
    'Private ReadOnly connStr As String =
    '    "Provider=Microsoft.ACE.OLEDB.12.0;" &
    '    "Data Source=C:\Users\lenovo\Documents\MooneemDB.accdb;" &
    '    "Persist Security Info=False;"
    Private ReadOnly connStr As String = "Provider=Microsoft.ACE.OLEDB.12.0;Data Source=" + Application.StartupPath + "\MooneemDB.accdb;Persist Security Info=False;"

    ' ---- UI controls ----
    Private comboClient As ComboBox
    Private dtpFrom As DateTimePicker
    Private dtpTo As DateTimePicker
    Private btnFetch As Button
    Private btnExport As Button
    Private dgvReport As DataGridView
    Private pnlOverlay As Panel
    Private lblStatus As Label


    ' ---- QuickBooks helper ----
    Private qbCompanyName As String = "Unknown"

    Public Sub New()
        InitializeComponent()
        BuildUI()
        LoadClients()

    End Sub

    Public Class ClientInfo
        Public Property Name As String
        Public Property FilePath As String

        Public Sub New(name As String, filePath As String)
            Me.Name = name
            Me.FilePath = filePath
        End Sub

        Public Overrides Function ToString() As String
            Return Name
        End Function
    End Class

    ' ---------------- Build UI ----------------
    Private Sub BuildUI()
        Me.Dock = DockStyle.Fill
        Me.BackColor = Color.White

        ' Top panel (inputs + buttons)
        Dim pnlTop As New Panel With {.Dock = DockStyle.Top, .Height = 80, .Padding = New Padding(15)}

        comboClient = New ComboBox With {.Width = 220, .Font = New Font("Verdana", 11), .DropDownStyle = ComboBoxStyle.DropDown}
        dtpFrom = New DateTimePicker With {.Format = DateTimePickerFormat.Short, .Width = 140, .Font = New Font("Verdana", 11)}
        dtpTo = New DateTimePicker With {.Format = DateTimePickerFormat.Short, .Width = 140, .Font = New Font("Verdana", 11)}

        'btnFetch = New Button With {
        '    .Text = "Fetch Report", .Width = 140, .Height = 36,
        '    .Font = New Font("Verdana", 10, FontStyle.Bold),
        '    .BackColor = Color.FromArgb(0, 150, 136), .ForeColor = Color.White,
        '    .FlatStyle = FlatStyle.Flat
        '}
        'btnExport = New Button With {
        '    .Text = "Export to Excel", .Width = 140, .Height = 36,
        '    .Font = New Font("Verdana", 10, FontStyle.Bold),
        '    .BackColor = Color.FromArgb(37, 36, 81), .ForeColor = Color.White,
        '    .FlatStyle = FlatStyle.Flat
        '}
        Dim primaryColor = Color.FromArgb(0, 150, 136)
        Dim secondaryColor = Color.FromArgb(37, 36, 81)

        ' --- Fetch Report Button ---
        btnFetch = New Button With {
            .Text = "Fetch Report",
            .Width = 180,
            .Height = 36,
            .Font = New Font("Verdana", 10, FontStyle.Bold),
            .BackColor = Color.Transparent,
            .ForeColor = primaryColor,
            .FlatStyle = FlatStyle.Flat
        }
        btnFetch.FlatAppearance.BorderColor = primaryColor
        btnFetch.FlatAppearance.BorderSize = 2
        AddHandler btnFetch.MouseEnter, Sub()
                                            btnFetch.BackColor = primaryColor
                                            btnFetch.ForeColor = Color.White
                                        End Sub
        AddHandler btnFetch.MouseLeave, Sub()
                                            btnFetch.BackColor = Color.Transparent
                                            btnFetch.ForeColor = primaryColor
                                        End Sub

        ' --- Export to Excel Button ---
        btnExport = New Button With {
            .Text = "Export to Excel",
            .Width = 180,
            .Height = 36,
            .Font = New Font("Verdana", 10, FontStyle.Bold),
            .BackColor = Color.Transparent,
            .ForeColor = secondaryColor,
            .FlatStyle = FlatStyle.Flat
        }
        btnExport.FlatAppearance.BorderColor = secondaryColor
        btnExport.FlatAppearance.BorderSize = 2
        AddHandler btnExport.MouseEnter, Sub()
                                             btnExport.BackColor = secondaryColor
                                             btnExport.ForeColor = Color.White
                                         End Sub
        AddHandler btnExport.MouseLeave, Sub()
                                             btnExport.BackColor = Color.Transparent
                                             btnExport.ForeColor = secondaryColor
                                         End Sub
        ' Add controls to top panel
        pnlTop.Controls.AddRange({comboClient, dtpFrom, dtpTo, btnFetch, btnExport})

        ' Manual positioning
        comboClient.Location = New Point(10, 22)
        dtpFrom.Location = New Point(comboClient.Right + 15, 22)
        dtpTo.Location = New Point(dtpFrom.Right + 15, 22)
        btnFetch.Location = New Point(dtpTo.Right + 30, 20)
        btnExport.Location = New Point(btnFetch.Right + 10, 20)

        ' Data grid
        dgvReport = New DataGridView With {
            .Dock = DockStyle.Fill,
            .AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.Fill,
            .Font = New Font("Verdana", 10),
            .RowTemplate = New DataGridViewRow() With {.Height = 28},
            .BackgroundColor = Color.White,
            .EnableHeadersVisualStyles = False
        }
        dgvReport.ColumnHeadersDefaultCellStyle = New DataGridViewCellStyle With {
            .BackColor = Color.FromArgb(31, 30, 68),
            .ForeColor = Color.White,
            .Font = New Font("Verdana", 10, FontStyle.Bold)
        }


        ' ---------- overlay strip ----------
        pnlOverlay = New Panel With {
             .Size = New Size(300, 100),
            .Height = 40,
            .Width = 390,
            .BackColor = Color.FromArgb(20, 0, 150, 136),   ' teal tint 8 %
            .Visible = False
        }
        'pnlOverlay.BorderStyle = BorderStyle.FixedSingle   ' or Fixed3D

        lblStatus = New Label With {
            .Dock = DockStyle.Fill,
            .Font = New Font("Verdana Semibold", 11, FontStyle.Bold),
            .ForeColor = Color.Black,
            .BackColor = Color.Transparent,
            .TextAlign = ContentAlignment.MiddleCenter
        }
        ' Position in center of form
        pnlOverlay.Location = New Point(400, 340)


        ' centre the label whenever overlay resizes
        AddHandler pnlOverlay.SizeChanged,
    Sub() lblStatus.Location = New Point(
            (pnlOverlay.Width - lblStatus.Width) \ 2,
            (pnlOverlay.Height - lblStatus.Height) \ 2)

        pnlOverlay.Controls.Add(lblStatus)

        ' add overlay LAST so it sits on top of every other control
        Me.Controls.Add(pnlOverlay)


        ' Add to UserControl
        Me.Controls.Add(dgvReport)
        Me.Controls.Add(pnlTop)

        ' Event handlers
        AddHandler btnFetch.Click, AddressOf FetchReport
        AddHandler btnExport.Click, AddressOf ExportToExcel
    End Sub

    ' ---------------- Load clients ----------------
    Private Sub LoadClients()
        Try
            Using conn As New OleDbConnection(connStr)
                conn.Open()
                Dim cmd As New OleDbCommand("SELECT ClientName, QBFilePath FROM GroupClientMapping ORDER BY ClientName", conn)
                Using rdr = cmd.ExecuteReader()
                    Dim list As New List(Of ClientInfo)
                    While rdr.Read()
                        list.Add(New ClientInfo(rdr("ClientName").ToString(), rdr("QBFilePath").ToString()))
                    End While
                    comboClient.DataSource = list
                    comboClient.DisplayMember = "Name"
                    comboClient.ValueMember = "FilePath"
                End Using
            End Using

            ' styling
            With comboClient
                .AutoCompleteMode = AutoCompleteMode.SuggestAppend
                .AutoCompleteSource = AutoCompleteSource.ListItems
                .ForeColor = Color.DarkSlateGray
            End With
        Catch ex As Exception
            MessageBox.Show("DB load error: " & ex.Message)
        End Try
    End Sub


    Private Function FormatAmountWithBrackets(amount As Decimal) As String
        If amount < 0 Then
            ' Return "(" & Math.Abs(amount).ToString("N2") & ")"
            Return amount.ToString("N2")
        Else
            Return amount.ToString("N2")
        End If
    End Function




    Private Function CleanLabel(input As String) As String
        If String.IsNullOrWhiteSpace(input) Then Return input
        ' Remove patterns like "10100 · ", "2000 · ", etc.
        Return Regex.Replace(input.Trim(), "^\s*(Total\s+)?\d+\s*·\s*", "$1", RegexOptions.IgnoreCase)
    End Function


    Private Function CleanLabelnew(label As String) As String
        ' Remove "Total" prefix if present
        label = System.Text.RegularExpressions.Regex.Replace(label, "^\s*Total\s+", "", RegexOptions.IgnoreCase)

        ' Remove any numeric code with optional dot or bullet and spaces (e.g., "40100 · ", "50000. ", etc.)
        label = System.Text.RegularExpressions.Regex.Replace(label, "\d+\s*[·\.]?\s*", "")

        ' Return cleaned label
        Return label.Trim()
    End Function

    ' show
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

    ' ---------------- Fetch QBD P&L (stub) ----------------
    Private Sub FetchReport(sender As Object, e As EventArgs)

        Dim processor As New RequestProcessor2()
        Dim dtFinal As New DataTable()
        dtFinal.Columns.Add("Month")
        dtFinal.Columns.Add("Label")
        dtFinal.Columns.Add("Amount")
        dtFinal.Columns.Add("RowType")
        dtFinal.Columns.Add("RowNumber")
        dtFinal.Columns.Add("SortOrder")

        Dim selectedClient As ClientInfo = CType(comboClient.SelectedItem, ClientInfo)
        Dim qbFilePath As String = selectedClient.FilePath
        processor.OpenConnection("", "Mooneem App")
        Dim ticket As String = processor.BeginSession(qbFilePath, ENOpenMode.omDontCare)

        Try

            If comboClient.SelectedItem Is Nothing Then
                MessageBox.Show("Please select a client.")
                Return
            End If

            'lblStatus.Visible = True
            Cursor.Current = Cursors.WaitCursor
            ShowStatus("🔄 Connecting to QuickBooks…")



            ' Get Company Name (QBName)
            Dim companyInfoRequest As String = "<?xml version=""1.0"" encoding=""utf-8""?>" & vbCrLf &
            "<?qbxml version=""8.0""?>" & vbCrLf &
            "<QBXML>" & vbCrLf &
            "  <QBXMLMsgsRq onError=""stopOnError"">" & vbCrLf &
            "    <CompanyQueryRq/>" & vbCrLf &
            "  </QBXMLMsgsRq>" & vbCrLf &
            "</QBXML>"

            Dim companyInfoResponse As String = processor.ProcessRequest(ticket, companyInfoRequest)

            ' Load the response into XML and extract company name
            Dim companyDoc As New XmlDocument()
            companyDoc.LoadXml(companyInfoResponse)
            Dim companyNameNode As XmlNode = companyDoc.SelectSingleNode("//CompanyName")

            If companyNameNode IsNot Nothing Then
                Dim qbName As String = companyNameNode.InnerText
            End If


            qbCompanyName = If(companyNameNode IsNot Nothing, companyNameNode.InnerText, "Unknown")





            Dim startDate As Date = dtpFrom.Value
            Dim endDate As Date = dtpTo.Value
            Dim current As Date = startDate


            While current <= endDate
                Dim fromDate As String
                Dim toDate As String

                ' First month — use user-selected start date
                If current.Month = startDate.Month AndAlso current.Year = startDate.Year Then
                    ' First month — start from the exact selected date
                    fromDate = startDate.ToString("yyyy-MM-dd")
                Else
                    ' Other months — start from 1st of the month
                    fromDate = New Date(current.Year, current.Month, 1).ToString("yyyy-MM-dd")
                End If

                If current.Month = endDate.Month AndAlso current.Year = endDate.Year Then
                    ' Last month — end on exact selected date
                    toDate = endDate.ToString("yyyy-MM-dd")
                Else
                    ' Other months — end on last day of the month
                    toDate = New Date(current.Year, current.Month, 1).AddMonths(1).AddDays(-1).ToString("yyyy-MM-dd")
                End If
                'Debug.WriteLine($"📤 Fetching data: {fromDate} to {toDate}")
                Dim monthName As String = current.ToString("MMM yyyy")
                Dim requestXml As String =
                "<?xml version=""1.0"" encoding=""utf-8""?>" & vbCrLf &
                "<?qbxml version=""8.0""?>" & vbCrLf &
                "<QBXML>" & vbCrLf &
                "  <QBXMLMsgsRq onError=""stopOnError"">" & vbCrLf &
                "    <GeneralSummaryReportQueryRq>" & vbCrLf &
                "      <GeneralSummaryReportType>ProfitAndLossStandard</GeneralSummaryReportType>" & vbCrLf &
                "      <ReportPeriod>" & vbCrLf &
                $"        <FromReportDate>{fromDate}</FromReportDate>" & vbCrLf &
                $"        <ToReportDate>{toDate}</ToReportDate>" & vbCrLf &
                "      </ReportPeriod>" & vbCrLf &
                "      <ReportBasis>Accrual</ReportBasis>" & vbCrLf &
                "    </GeneralSummaryReportQueryRq>" & vbCrLf &
                "  </QBXMLMsgsRq>" & vbCrLf &
                "</QBXML>"

                Dim responseXml As String = processor.ProcessRequest(ticket, requestXml)
                Dim monthlyData As DataTable = ParseMonthlyProfitAndLoss(responseXml, monthName)

                If monthlyData IsNot Nothing Then
                    For Each row As DataRow In monthlyData.Rows
                        dtFinal.ImportRow(row)
                    Next
                End If

                current = current.AddMonths(1)
            End While

            ShowStatus("🔍 Checking Profit and loss mappings...")
            'lblStatus.Text = "🔍 Checking Profit and loss mappings..."
            'Application.DoEvents()

            Dim unmatchedLabelList As New List(Of String)()
            Dim unmatchedCOAList As New List(Of String)()

            Using conn As New OleDbConnection(connStr)
                conn.Open()

                ' --- STEP 1: Check dtFinal row labels (e.g., 12345 · Rent)
                For Each row As DataRow In dtFinal.Rows
                    If row("RowType").ToString() = "DataRow" Then
                        Dim fullLabel As String = row("Label").ToString().Trim()

                        If fullLabel.Contains("·") Then
                            Dim parts() As String = fullLabel.Split("·"c)
                            If parts.Length = 2 Then
                                Dim groupCode As String = parts(0).Trim()
                                Dim groupLabel As String = parts(1).Trim()

                                Dim cmd As New OleDbCommand("SELECT COUNT(*) FROM PLQBMapping WHERE ClientName = ? AND ReportID = 2 AND GroupCode = ?", conn)
                                cmd.Parameters.AddWithValue("?", selectedClient.Name)
                                cmd.Parameters.AddWithValue("?", groupCode)
                                'cmd.Parameters.AddWithValue("?", groupLabel)

                                Dim count As Integer = Convert.ToInt32(cmd.ExecuteScalar())
                                If count = 0 Then
                                    unmatchedLabelList.Add(groupCode & " · " & groupLabel)
                                End If
                            End If
                        End If
                    End If
                Next

                ' --- STEP 2: Fetch and check Chart of Accounts using processor (QBXML)
                Dim coaRequestXml As String =
                "<?xml version=""1.0"" encoding=""utf-8""?>" & vbCrLf &
                "<?qbxml version=""8.0""?>" & vbCrLf &
                "<QBXML>" & vbCrLf &
                "  <QBXMLMsgsRq onError=""stopOnError"">" & vbCrLf &
                "    <AccountQueryRq />" & vbCrLf &
                "  </QBXMLMsgsRq>" & vbCrLf &
                "</QBXML>"

                Dim coaResponseXml As String = processor.ProcessRequest(ticket, coaRequestXml)

                ' --- STEP 2a: Load allowed account names from DB to limit checks
                Dim validAccountNames As New HashSet(Of String)(StringComparer.OrdinalIgnoreCase)
                Dim nameCmd As New OleDbCommand("SELECT DISTINCT GroupLabel FROM PLQBMapping WHERE ClientName = ? AND ReportID = 2", conn)
                nameCmd.Parameters.AddWithValue("?", selectedClient.Name)
                Using reader = nameCmd.ExecuteReader()
                    While reader.Read()
                        validAccountNames.Add(reader("GroupLabel").ToString().Trim())
                    End While
                End Using

                ' --- STEP 2b: Parse QBXML Chart of Accounts
                Dim coaDoc As New XmlDocument()
                coaDoc.LoadXml(coaResponseXml)
                Dim accountNodes As XmlNodeList = coaDoc.SelectNodes("//AccountRet")

                For Each node As XmlNode In accountNodes
                    Dim accountName As String = node.SelectSingleNode("Name")?.InnerText.Trim()
                    If String.IsNullOrEmpty(accountName) OrElse Not validAccountNames.Contains(accountName) Then Continue For

                    Dim descNode As XmlNode = node.SelectSingleNode("Desc")
                    Dim descText As String = If(descNode IsNot Nothing, descNode.InnerText.Trim(), "")

                    ' Look for <fsaccount>12345</fsaccount> or plain 5-digit codes
                    Dim matches As MatchCollection = Regex.Matches(descText, "<fsaccount>(\d+)</fsaccount>|\b\d{5}\b")

                    If matches.Count = 0 Then
                        unmatchedCOAList.Add(accountName)
                    Else
                        For Each match As Match In matches
                            Dim code As String = If(match.Groups(1).Success, match.Groups(1).Value, match.Value)

                            Dim cmd As New OleDbCommand("SELECT COUNT(*) FROM PLQBMapping WHERE ClientName = ? AND ReportID = 2 AND GroupCode = ?", conn)
                            cmd.Parameters.AddWithValue("?", selectedClient.Name)
                            cmd.Parameters.AddWithValue("?", code)
                            'cmd.Parameters.AddWithValue("?", accountName)

                            Dim count As Integer = Convert.ToInt32(cmd.ExecuteScalar())
                            If count = 0 Then
                                unmatchedCOAList.Add(code & " · " & accountName)
                            End If
                        Next
                    End If
                Next
            End Using


            ' --- STEP 3: Show combined unmatched info
            If unmatchedLabelList.Count > 0 OrElse unmatchedCOAList.Count > 0 Then
                'lblStatus.Visible = False
                HideStatus()
                Cursor.Current = Cursors.Default
                Dim msg As String = ""

                If unmatchedLabelList.Count > 0 Then
                    msg &= "🔸 Unmatched Profit & Loss Labels from Report:" & vbCrLf &
               String.Join(vbCrLf, unmatchedLabelList.Distinct()) & vbCrLf & vbCrLf
                End If

                If unmatchedCOAList.Count > 0 Then
                    msg &= "🔸 Unmatched Accounts from Chart of Accounts (FS code mapping):" & vbCrLf &
               String.Join(vbCrLf, unmatchedCOAList.Distinct())
                End If

                MessageBox.Show(msg, "Unmatched P&L Mappings", MessageBoxButtons.OK, MessageBoxIcon.Warning)
                Exit Sub

            Else
                'lblStatus.Text = "📊 Loading data into grid view. Please wait..."
                'Application.DoEvents()
                ShowStatus("📊 Loading data into grid view. Please wait...")
            End If

            ' --- Close QB session
            'processor.EndSession(ticket)
            'processor.CloseConnection()
            HideStatus()
            Cursor.Current = Cursors.Default
            ' lblStatus.Visible = False
            '            ' ----------- Pivot + Row Order Logic ----------------
            Dim pivotTable As New DataTable()
            pivotTable.Columns.Add("Label")
            pivotTable.Columns.Add("RowNumber")
            pivotTable.Columns.Add("RowType")
            pivotTable.Columns.Add("SortOrder")


            Dim monthNames As New List(Of String)()
            Dim labelMap As New Dictionary(Of String, DataRow)(StringComparer.OrdinalIgnoreCase)

            ' ----------- Rename Total Expense to Operating Expense before pivot logic -----------
            Dim hasRenamed As Boolean = False
            ' ----------- Rename Total Expense to Operating Expense and Total Income to Total Revenue -----------
            For Each row As DataRow In dtFinal.Rows
                Dim label As String = row("Label").ToString().Trim()

            Next



            For Each row As DataRow In dtFinal.Rows
                Dim label = row("Label").ToString()
                Dim rowNumber = row("RowNumber").ToString()
                Dim rowType = row("RowType").ToString()
                Dim cleanedLabel As String = CleanLabelnew(label)

                If Not labelMap.ContainsKey(label) Then
                    Dim dr = pivotTable.NewRow()
                    dr("Label") = label
                    dr("RowNumber") = rowNumber
                    dr("RowType") = rowType
                    dr("SortOrder") = row("SortOrder")

                    labelMap(label) = dr
                End If

            Next


            For Each row As DataRow In dtFinal.Rows
                Dim month = row("Month").ToString()
                If Not pivotTable.Columns.Contains(month) Then
                    pivotTable.Columns.Add(month)
                    monthNames.Add(month)
                End If
            Next




            For Each row As DataRow In dtFinal.Rows
                Dim label = row("Label").ToString()
                Dim month = row("Month").ToString()
                Dim amount = row("Amount").ToString()
                Dim targetRow = labelMap(label)
                Dim amtDecimal As Decimal

                If String.IsNullOrWhiteSpace(amount) OrElse Not Decimal.TryParse(amount, amtDecimal) Then
                    amtDecimal = 0D
                End If

                targetRow(month) = FormatAmountWithBrackets(amtDecimal)
            Next


            pivotTable.Columns.Add("Total")



            For Each kvp In labelMap
                Dim total As Decimal = 0D
                For Each m In monthNames
                    Dim strVal As String = kvp.Value(m)?.ToString()
                    If Not String.IsNullOrWhiteSpace(strVal) Then
                        strVal = strVal.Trim()

                        ' Handle bracketed negative numbers: (123.45) => -123.45
                        If strVal.StartsWith("(") AndAlso strVal.EndsWith(")") Then
                            strVal = "-" & strVal.Trim("(", ")")
                        End If

                        Dim val As Decimal
                        If Decimal.TryParse(strVal, val) Then
                            total += val
                        End If
                    End If
                Next
                kvp.Value("Total") = FormatAmountWithBrackets(total)
            Next



            Dim allRows As List(Of DataRow) = labelMap.
    OrderBy(Function(k)
                Dim s = k.Value("SortOrder")
                If IsDBNull(s) OrElse Not IsNumeric(s) Then
                    Return 9999
                Else
                    Return Convert.ToInt32(s)
                End If
            End Function).
    Select(Function(k) k.Value).ToList()


            ' Dim allRows As List(Of DataRow) = labelMap.OrderBy(Function(k) Convert.ToInt32(k.Value("RowNumber"))).Select(Function(k) k.Value).ToList()

            Dim newtotalRevenueIndex As Integer = -1
            Dim totalRevenueRow As DataRow = Nothing

            ' Declare totalRevenueValue here with default value to keep it in scope
            Dim totalRevenueValue As String = ""

            For i As Integer = 0 To allRows.Count - 1
                Dim label = allRows(i)("Label").ToString().Trim()

                If newtotalRevenueIndex = -1 AndAlso label.Equals("Total Income", StringComparison.OrdinalIgnoreCase) Then
                    newtotalRevenueIndex = i
                    totalRevenueRow = allRows(i)
                    Exit For ' stop looping once found
                End If
            Next

            If newtotalRevenueIndex <> -1 Then
                totalRevenueValue = totalRevenueRow("Total").ToString()
            Else
                ' MessageBox.Show("Total Revenue row not found.")
            End If

            pivotTable.Columns.Add("%")

            ' Convert totalRevenueValue string to decimal, handle bracketed negatives
            Dim strTotalRevenue = totalRevenueValue.Trim()

            If strTotalRevenue.StartsWith("(") AndAlso strTotalRevenue.EndsWith(")") Then
                strTotalRevenue = "-" & strTotalRevenue.Trim("("c, ")"c)
            End If

            Dim grandTotal As Decimal = 0D
            If Not Decimal.TryParse(strTotalRevenue, grandTotal) OrElse grandTotal = 0 Then
            End If



            For Each row As DataRow In labelMap.Values
                Dim strRowTotal As String = row("Total")?.ToString()?.Trim()

                ' Handle bracketed negative values
                If strRowTotal.StartsWith("(") AndAlso strRowTotal.EndsWith(")") Then
                    strRowTotal = "-" & strRowTotal.Trim("("c, ")"c)
                End If

                Dim rowTotal As Decimal
                If Decimal.TryParse(strRowTotal, rowTotal) AndAlso grandTotal <> 0 Then
                    Dim percent As Decimal = (rowTotal / grandTotal) * 100
                    row("%") = FormatAmountWithBrackets(percent)
                Else
                    row("%") = "0.00"
                End If
            Next


            'new
            ' Initial setup
            Dim totalExpenseRow As DataRow = Nothing
            Dim totalExpenseIndex As Integer = -1
            Dim totalRevenueIndex As Integer = -1

            ' Find Total Expense and Total Revenue indexes
            For i = 0 To allRows.Count - 1
                Dim label = allRows(i)("Label").ToString().Trim()
                If totalExpenseIndex = -1 AndAlso label.Equals("Total Expense", StringComparison.OrdinalIgnoreCase) Then
                    totalExpenseIndex = i
                    totalExpenseRow = allRows(i)
                End If
                If totalRevenueIndex = -1 AndAlso label.Equals("Total Income", StringComparison.OrdinalIgnoreCase) Then
                    totalRevenueIndex = i
                End If
            Next

            Dim beforeList As New List(Of DataRow)()
            Dim moveList As New List(Of DataRow)()
            Dim afterList As New List(Of DataRow)()


            pivotTable.Rows.Clear()
            For Each row In allRows
                pivotTable.Rows.Add(row.ItemArray)
            Next


            If totalExpenseRow IsNot Nothing Then
                Dim revenueIndex As Integer = -1
                Dim insertIndex As Integer = -1

                For i As Integer = 0 To pivotTable.Rows.Count - 1
                    Dim lbl As String = pivotTable.Rows(i)("Label").ToString().Trim()
                    If lbl.Equals("Net Ordinary Income", StringComparison.OrdinalIgnoreCase) Then
                        insertIndex = i ' Insert before Operating Income
                        Exit For
                    End If
                Next
                ' Step 2: If "Operating Income" not found, look for "Total Income"
                If insertIndex = -1 Then
                    For i As Integer = 0 To pivotTable.Rows.Count - 1
                        Dim lbl As String = pivotTable.Rows(i)("Label").ToString().Trim()
                        If lbl.Equals("Other Income", StringComparison.OrdinalIgnoreCase) Then
                            insertIndex = i ' Insert before Other Income
                            Exit For
                        End If
                    Next
                End If
                ' Only insert duplicate if "Total Revenue" was found


                If insertIndex <> -1 Then
                    Dim duplicateRow As DataRow = pivotTable.NewRow()
                    duplicateRow.ItemArray = totalExpenseRow.ItemArray.Clone() ' Clone original
                    duplicateRow("Label") = "Less: Operating Expense"
                    pivotTable.Rows.InsertAt(duplicateRow, insertIndex)
                End If


            End If





            ' ------------------ Bind to Grid ------------------
            dgvReport.DataSource = pivotTable

            dgvReport.Columns("Label").Width = 250
            For Each m In monthNames
                dgvReport.Columns(m).Width = 100
            Next
            dgvReport.Columns("Total").Width = 120
            dgvReport.Columns("%").Width = 80

            dgvReport.Columns("RowType").Visible = False
            dgvReport.Columns("RowNumber").Visible = False
            dgvReport.Columns("SortOrder").Visible = False

            'dgvReport.Columns("AccountType").Visible = False
            'Catch ex As Exception
            '    MessageBox.Show("Error: " & ex.Message)
            '    Try
            '        processor.CloseConnection()
            '    Catch
            '    End Try
            'End Try
        Catch ex As Exception
            MessageBox.Show("Error: " & ex.Message)
        Finally
            '(force closing QB session and connection here)
            Try
                If ticket <> "" Then
                    processor.EndSession(ticket)
                End If
                processor.CloseConnection()
            Catch
                ' ignore errors in closing
            End Try
            Cursor.Current = Cursors.Default
            HideStatus()
        End Try
    End Sub


    Private Function ParseMonthlyProfitAndLoss(xmlData As String, monthLabel As String) As DataTable
        Dim xmlDoc As New XmlDocument()
        Dim dt As New DataTable()
        dt.Columns.Add("Month")
        dt.Columns.Add("Label")
        dt.Columns.Add("Amount")
        dt.Columns.Add("RowType")
        dt.Columns.Add("RowNumber")
        dt.Columns.Add("SortOrder", GetType(Integer))

        Try
            xmlDoc.LoadXml(xmlData)
        Catch ex As Exception
            MessageBox.Show("XML Load Error: " & ex.Message)
            Return Nothing
        End Try

        Dim allNodes As XmlNodeList = xmlDoc.SelectNodes("//QBXML//ReportData/*")
        If allNodes Is Nothing OrElse allNodes.Count = 0 Then Return dt

        Dim selectedClient As ClientInfo = CType(comboClient.SelectedItem, ClientInfo)

        For Each node As XmlNode In allNodes
            Select Case node.Name
                Case "TextRow"
                    Dim label = node.Attributes("value")?.Value
                    Dim rowNumber = node.Attributes("rowNumber")?.Value
                    Dim sortOrder As Integer = 9999 ' default
                    Dim shouldPrint As Boolean = False

                    label = CleanLabel(label)
                    'MsgBox(label)
                    Using conn As New OleDbConnection(connStr)
                        conn.Open()

                        Using cmd As New OleDbCommand("SELECT COUNT(*) FROM PLQBMapping WHERE ClientName = ? AND ReportID = 2 AND IsActive = True AND (FSGroupName = ? OR TopLabel = ?)", conn)
                            cmd.Parameters.AddWithValue("?", selectedClient.Name)
                            cmd.Parameters.AddWithValue("?", label)
                            cmd.Parameters.AddWithValue("?", label)
                            shouldPrint = CInt(cmd.ExecuteScalar()) > 0
                        End Using

                        Using cmdSort As New OleDbCommand("SELECT MIN(SortOrder) FROM PLQBMapping WHERE ClientName = ? AND  IsActive = True AND ReportID = 2 AND (FSGroupName = ? OR TopLabel = ?)", conn)
                            cmdSort.Parameters.AddWithValue("?", selectedClient.Name)
                            cmdSort.Parameters.AddWithValue("?", label)
                            cmdSort.Parameters.AddWithValue("?", label)
                            Dim result = cmdSort.ExecuteScalar()
                            ' Dim debugQuery As String = "SELECT TOP 1 SortOrder FROM PLQBMapping WHERE ClientName = '" & selectedClient.Name & "' AND IsActive = True AND ReportID = 2 AND (FSGroupName = '" & label & "' OR TopLabel = '" & label & "')"
                            ' Dim resultText As String = If(result Is Nothing, "NULL", result.ToString())

                            ' MessageBox.Show("Running query: " & vbCrLf & debugQuery & vbCrLf & vbCrLf & "Result: " & resultText)
                            If result IsNot Nothing AndAlso IsNumeric(result) Then
                                sortOrder = CInt(result)
                            End If
                        End Using
                    End Using

                    If shouldPrint Then
                        'MsgBox("shouldpritn")
                        'MsgBox(label)
                        dt.Rows.Add(monthLabel, label, "", node.Name, rowNumber, sortOrder)
                    End If


                Case "DataRow"
                    Dim rowNumber = node.Attributes("rowNumber")?.Value
                    Dim rawLabel As String = ""
                    Dim cleanedLabel As String = ""
                    Dim amount As String = "0.00"
                    Dim sortOrder As Integer = 9999
                    Dim shouldPrint As Boolean = False

                    For Each col As XmlNode In node.SelectNodes("ColData")
                        Dim colId = col.Attributes("colID")?.Value
                        Dim val = col.Attributes("value")?.Value
                        If colId = "1" Then
                            rawLabel = val
                            cleanedLabel = CleanLabel(val)
                        End If
                        If colId = "2" Then amount = val
                    Next
                    ' MsgBox(rawLabel)
                    ' Extract numeric group code from the raw label (e.g., "3050 · Patient Fees")
                    Dim groupCode As String = ""
                    Dim match = System.Text.RegularExpressions.Regex.Match(rawLabel, "(\d+)")
                    If match.Success Then
                        groupCode = match.Groups(1).Value
                    End If

                    Using conn As New OleDbConnection(connStr)
                        conn.Open()

                        ' Use groupCode to check if this row should print
                        Using cmd As New OleDbCommand("SELECT COUNT(*) FROM PLQBMapping WHERE ClientName = ? AND ReportID = 2 AND IsActive = True AND GroupCode = ?", conn)
                            cmd.Parameters.AddWithValue("?", selectedClient.Name)
                            cmd.Parameters.AddWithValue("?", groupCode)
                            shouldPrint = CInt(cmd.ExecuteScalar()) > 0
                        End Using

                        ' Fetch SortOrder using GroupCode (not label)
                        Using cmdSort As New OleDbCommand("SELECT TOP 1 SortOrder FROM PLQBMapping WHERE ClientName = ? AND IsActive = True AND ReportID = 2 AND GroupCode = ?", conn)
                            cmdSort.Parameters.AddWithValue("?", selectedClient.Name)
                            cmdSort.Parameters.AddWithValue("?", groupCode)
                            Dim result = cmdSort.ExecuteScalar()
                            If result IsNot Nothing AndAlso IsNumeric(result) Then
                                sortOrder = CInt(result)
                            End If
                        End Using
                    End Using

                    If shouldPrint AndAlso Not String.IsNullOrEmpty(cleanedLabel) Then
                        'MsgBox("shouldpritn")
                        'MsgBox(rawLabel)
                        dt.Rows.Add(monthLabel, rawLabel, amount, node.Name, rowNumber, sortOrder)
                    End If


                Case "SubtotalRow", "TotalRow"
                    Dim rowNumber = node.Attributes("rowNumber")?.Value
                    Dim label As String = ""
                    Dim amount As String = "0.00"
                    Dim sortOrder As Integer = 0

                    For Each col As XmlNode In node.SelectNodes("ColData")
                        Dim colId = col.Attributes("colID")?.Value
                        Dim val = col.Attributes("value")?.Value
                        If colId = "1" Then label = CleanLabel(val)
                        If colId = "2" Then amount = val
                    Next

                    'Dim extractedLabel As String = System.Text.RegularExpressions.Regex.Replace(label, "^Total\s+", "", RegexOptions.IgnoreCase).Trim()
                    Dim shouldPrint As Boolean = False
                    Dim skipThisRow As Boolean = False

                    Using conn As New OleDbConnection(connStr)
                        conn.Open()



                        'If Not skipThisRow Then
                        Using cmd As New OleDbCommand("SELECT COUNT(*) FROM PLQBMapping WHERE ClientName = ? AND ReportID = 2 AND IsActive = True AND (GroupLabel = ?)", conn)
                            cmd.Parameters.AddWithValue("?", selectedClient.Name)
                            ' cmd.Parameters.AddWithValue("?", extractedLabel)
                            cmd.Parameters.AddWithValue("?", label)
                            shouldPrint = CInt(cmd.ExecuteScalar()) > 0
                        End Using

                        Using cmdSort As New OleDbCommand("SELECT TOP 1 SortOrder FROM PLQBMapping WHERE ClientName = ? AND ReportID = 2 AND IsActive = True AND  GroupLabel = ?", conn)
                            cmdSort.Parameters.AddWithValue("?", selectedClient.Name)
                            cmdSort.Parameters.AddWithValue("?", label)
                            Dim result = cmdSort.ExecuteScalar()
                            If result IsNot Nothing AndAlso IsNumeric(result) Then
                                sortOrder = CInt(result)
                            End If
                        End Using
                        'End If
                    End Using

                    If Not skipThisRow AndAlso shouldPrint AndAlso Not String.IsNullOrEmpty(label) Then
                        dt.Rows.Add(monthLabel, label, amount, node.Name, rowNumber, sortOrder)
                    End If
            End Select
        Next

        ' ✅ Sort the result by SortOrder ascending
        'Dim sortedView As DataView = dt.DefaultView
        'sortedView.Sort = "SortOrder ASC"
        'Return sortedView.ToTable()
        Return dt
        '   Dim sorted = dt.AsEnumerable().
        'OrderBy(Function(row) row.Field(Of Integer)("SortOrder")).
        'ThenBy(Function(row) If(row.Field(Of String)("RowType") = "TextRow", 0, 1))

        '   Return sorted.CopyToDataTable()


    End Function


    Private Function NumberToWords(number As Integer) As String
        Dim words As String = ""
        Dim unitsMap() As String = {"zero", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine", "ten",
                                 "eleven", "twelve", "thirteen", "fourteen", "fifteen", "sixteen",
                                 "seventeen", "eighteen", "nineteen"}
        Dim tensMap() As String = {"zero", "ten", "twenty", "thirty", "forty", "fifty", "sixty", "seventy", "eighty", "ninety"}

        If number < 20 Then
            words = unitsMap(number)
        Else
            words = tensMap(number \ 10)
            If number Mod 10 > 0 Then
                words &= "-" & unitsMap(number Mod 10)
            End If
        End If

        Return words
    End Function


    ' ---------------- Export to Excel (stub) --------------
    Private Sub ExportToExcel(sender As Object, e As EventArgs)
        If dgvReport.Rows.Count = 0 Then
            MessageBox.Show("No data to export.")
            Return
        End If
        Dim selectedClient As ClientInfo = CType(comboClient.SelectedItem, ClientInfo)


        ExcelPackage.LicenseContext = OfficeOpenXml.LicenseContext.NonCommercial


        Dim sfd As New SaveFileDialog()
        sfd.Filter = "Excel Files (.xlsx)|.xlsx"

        Dim sanitizedCompanyName As String = qbCompanyName.Replace(" ", "_").Trim()
        Dim randomNumber As String = New Random().Next(100000, 999999).ToString()
        Dim defaultFileName As String = $"{sanitizedCompanyName} - Profit_and_loss_Report {randomNumber}.xlsx"

        sfd.FileName = defaultFileName

        If sfd.ShowDialog() = DialogResult.OK Then

            'lblStatus.Text = "📥 Exporting to Excel. Please wait..."
            'lblStatus.Visible = True
            Cursor.Current = Cursors.WaitCursor
            ShowStatus("📥 Exporting to Excel. Please wait...")
            Application.DoEvents()
            Using package As New ExcelPackage(New FileInfo(sfd.FileName))
                Dim worksheet As ExcelWorksheet = package.Workbook.Worksheets.Add("Report")

                Dim reportHeading As String = ""


                Using conn As New OleDbConnection(connStr)
                    conn.Open()

                    Dim cmd As New OleDbCommand("SELECT ClientName, ReportHead FROM ReportHeading WHERE ReportId = 2", conn)

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

                ' --- Header ---
                Dim headingRow As Integer = 1
                ' Dim totalColumns As Integer = 12 ' Adjust if needed
                Dim totalColumns As Integer = dgvReport.Columns.Cast(Of DataGridViewColumn)().Count(Function(col) col.Visible) + 3

                Dim startDate As Date = dtpFrom.Value
                Dim endDate As Date = dtpTo.Value

                ' Calculate the total number of full months between start and end date
                Dim coveredMonths As Integer = ((endDate.Year - startDate.Year) * 12) + endDate.Month - startDate.Month + 1
                Dim monthsInWords As String = NumberToWords(coveredMonths).ToUpper()
                Dim formattedDate As String = endDate.ToString("MMMM dd, yyyy").ToUpper()

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
                worksheet.Cells(headingRow + 3, 1).Value = $"FOR THE {monthsInWords} MONTHS ENDED {formattedDate}"

                For r As Integer = headingRow To headingRow + 3
                    worksheet.Cells(r, 1, r, totalColumns).Merge = True
                    worksheet.Cells(r, 1, r, totalColumns).Style.HorizontalAlignment = OfficeOpenXml.Style.ExcelHorizontalAlignment.Center
                    worksheet.Cells(r, 1, r, totalColumns).Style.VerticalAlignment = OfficeOpenXml.Style.ExcelVerticalAlignment.Center
                    worksheet.Row(r).Height = 20
                    worksheet.Cells(r, 1, r, totalColumns).Style.Font.Bold = True
                    worksheet.Cells(r, 1, r, totalColumns).Style.Font.Size = 13
                Next

                ' --- Start writing data ---
                Dim excelRow As Integer = headingRow + 5

                ' Write headers
                For colIndex As Integer = 0 To dgvReport.Columns.Count - 1

                    If dgvReport.Columns(colIndex).Visible Then
                        Dim headerText As String = dgvReport.Columns(colIndex).HeaderText

                        worksheet.Cells(excelRow, colIndex + 1).Value = If(headerText = "Label", "", headerText)
                        worksheet.Cells(excelRow, colIndex + 1).Style.Font.Bold = True
                        worksheet.Cells(excelRow, colIndex + 1).Style.HorizontalAlignment = OfficeOpenXml.Style.ExcelHorizontalAlignment.Right

                        If colIndex > 0 Then
                            worksheet.Cells(excelRow, colIndex + 1).Style.Border.Bottom.Style = OfficeOpenXml.Style.ExcelBorderStyle.Thin
                        End If
                    End If
                Next

                excelRow += 1

                ' Write data with custom layout logic
                ' "Other Expense",
                Dim firstValueWritten As New HashSet(Of Integer)


                For Each gridRow As DataGridViewRow In dgvReport.Rows
                    If gridRow.IsNewRow Then Continue For

                    Dim label As String = gridRow.Cells("Label").Value?.ToString()
                    Dim rowType As String = gridRow.Cells("RowType").Value?.ToString()
                    Dim rowNumberStr As String = gridRow.Cells("RowNumber").Value?.ToString()
                    Dim rowNumber As Integer = 0
                    Integer.TryParse(rowNumberStr, rowNumber)

                    'worksheet.Column(1).Width = 4
                    worksheet.Column(2).Width = 4
                    ' worksheet.Column(3).Width = 4
                    'worksheet.Column(4).Width = 40



                    'If Not String.IsNullOrEmpty(label) Then
                    '    label = System.Text.RegularExpressions.Regex.Replace(label, "^\d+\s*·\s*", "")



                    'End If
                    Dim accountTypeColIndex As Integer = -1
                    For i As Integer = 0 To dgvReport.Columns.Count - 1
                        If dgvReport.Columns(i).Name.Equals("SortOrder", StringComparison.OrdinalIgnoreCase) Then
                            accountTypeColIndex = i
                            Exit For
                        End If
                    Next
                    If rowType = "TextRow" Then



                        Dim targetColumn As Integer = 1

                        If rowNumber > 0 Then
                            targetColumn = 2
                        End If
                        For i As Integer = 0 To dgvReport.Columns.Count - 1
                            If dgvReport.Columns(i).Name.Equals("SortOrder", StringComparison.OrdinalIgnoreCase) Then
                                accountTypeColIndex = i
                                Exit For
                            End If
                        Next




                        If label = "Expense" Then




                            'end
                            ' Insert 6 rows above the current row (2 blank rows + 4 header rows)
                            worksheet.InsertRow(excelRow, 6) ' Insert 6 rows

                            ' Add your header content starting from row (excelRow + 2)
                            worksheet.Cells(excelRow + 2, 1).Value = qbCompanyName
                            worksheet.Cells(excelRow + 3, 1).Value = part1
                            worksheet.Cells(excelRow + 4, 1).Value = part2
                            worksheet.Cells(excelRow + 5, 1).Value = $"For THE {monthsInWords} MONTHS ENDED {formattedDate}"

                            ' Merge cells and apply formatting for the header
                            For r As Integer = excelRow + 2 To excelRow + 5 ' Starting from 2 rows down
                                worksheet.Cells(r, 1, r, totalColumns).Merge = True
                                worksheet.Cells(r, 1, r, totalColumns).Style.HorizontalAlignment = OfficeOpenXml.Style.ExcelHorizontalAlignment.Center
                                worksheet.Cells(r, 1, r, totalColumns).Style.VerticalAlignment = OfficeOpenXml.Style.ExcelVerticalAlignment.Center
                                worksheet.Row(r).Height = 20
                                worksheet.Cells(r, 1, r, totalColumns).Style.Font.Bold = True
                                worksheet.Cells(r, 1, r, totalColumns).Style.Font.Size = 13
                            Next



                            ' Add one empty row (already created by row insert), now add the new heading
                            worksheet.Cells(excelRow + 6, 1).Value = ""
                            worksheet.Cells(excelRow + 6, 1).Value = "SCHEDULE Of OPERATING EXPENSES"
                            worksheet.Cells(excelRow + 6, 1, excelRow + 6, totalColumns).Merge = True
                            worksheet.Cells(excelRow + 6, 1, excelRow + 6, totalColumns).Style.HorizontalAlignment = OfficeOpenXml.Style.ExcelHorizontalAlignment.Center
                            worksheet.Cells(excelRow + 6, 1, excelRow + 6, totalColumns).Style.Font.Bold = True
                            worksheet.Cells(excelRow + 6, 1, excelRow + 6, totalColumns).Style.Font.Size = 14

                            excelRow += 1
                            ' Move excelRow down by 7 rows to avoid overwriting inserted content
                            excelRow += 7
                            ' --- Add headers again ---
                            For colIndex As Integer = 0 To dgvReport.Columns.Count - 1
                                If dgvReport.Columns(colIndex).Visible Then
                                    Dim headerText As String = dgvReport.Columns(colIndex).HeaderText
                                    ' Skip "AccountType" column

                                    worksheet.Cells(excelRow, colIndex + 1).Value = If(headerText = "Label", "", headerText)
                                    worksheet.Cells(excelRow, colIndex + 1).Style.Font.Bold = True
                                    worksheet.Cells(excelRow, colIndex + 1).Style.HorizontalAlignment = OfficeOpenXml.Style.ExcelHorizontalAlignment.Right

                                    If colIndex > 0 Then
                                        worksheet.Cells(excelRow, colIndex + 1).Style.Border.Bottom.Style = OfficeOpenXml.Style.ExcelBorderStyle.Thin
                                    End If
                                End If
                            Next

                            excelRow += 1
                        End If

                        'worksheet.Cells(excelRow, targetColumn).Value = label
                        Dim displayLabel As String = label

                        ' Check if label might be an FSGroup (and needs to be renamed)
                        Using conn As New OleDbConnection(connStr)
                            conn.Open()

                            Using cmd As New OleDbCommand("SELECT RenameTop FROM PLQBMapping WHERE ClientName = ? AND ReportID = 2 AND TopLabel = ?", conn)
                                cmd.Parameters.AddWithValue("?", selectedClient.Name)
                                cmd.Parameters.AddWithValue("?", label)

                                Dim result As Object = cmd.ExecuteScalar()
                                If result IsNot Nothing AndAlso Not Convert.ToString(result).Trim() = "" Then
                                    displayLabel = Convert.ToString(result).Trim()
                                End If
                            End Using
                        End Using



                        ' Retain the last valid width across multiple label checks
                        Static topWidth As Double = 4 ' default width

                        Using conn3 As New OleDbConnection(connStr)
                            conn3.Open()

                            Dim widthQuery As String = "SELECT TOP 1 TopLabel, TopWidth, FSGroupName, FSWidth " &
                               "FROM PLQBMapping " &
                               "WHERE ClientName = ? AND ReportID = 2 AND IsActive = True " &
                               "AND (TopLabel = ? OR FSGroupName = ?) " &
                               "AND (TopWidth IS NOT NULL OR FSWidth IS NOT NULL)"

                            Using cmdWidth As New OleDbCommand(widthQuery, conn3)
                                cmdWidth.Parameters.AddWithValue("?", selectedClient.Name)
                                cmdWidth.Parameters.AddWithValue("?", label)
                                cmdWidth.Parameters.AddWithValue("?", label)

                                Using readerWidth As OleDbDataReader = cmdWidth.ExecuteReader()
                                    If readerWidth.Read() Then
                                        Dim dbTopLabel As String = readerWidth("TopLabel")?.ToString().Trim()
                                        Dim dbTopWidthStr As String = readerWidth("TopWidth")?.ToString().Trim()
                                        Dim dbFSGroupName As String = readerWidth("FSGroupName")?.ToString().Trim()
                                        Dim dbFSWidthStr As String = readerWidth("FSWidth")?.ToString().Trim()

                                        Dim parsed As Double
                                        If label = dbTopLabel AndAlso Not String.IsNullOrWhiteSpace(dbTopWidthStr) AndAlso Double.TryParse(dbTopWidthStr, parsed) Then
                                            topWidth = parsed ' update with TopWidth
                                        ElseIf label = dbFSGroupName AndAlso Not String.IsNullOrWhiteSpace(dbFSWidthStr) AndAlso Double.TryParse(dbFSWidthStr, parsed) Then
                                            topWidth = parsed ' update with FSWidth
                                        End If
                                    End If
                                End Using
                            End Using
                        End Using

                        worksheet.Column(1).Width = topWidth






                        worksheet.Cells(excelRow, targetColumn).Value = displayLabel
                        worksheet.Cells(excelRow, targetColumn).Style.Font.Bold = True


                        ' Find the index of the "AccountType" column once


                    ElseIf {"DataRow"}.Contains(rowType) Then

                        ' Determine target column based on rowNumber
                        'Dim targetColumn As Integer = 1 ' Default
                        'If rowNumber > 2 Then
                        '    targetColumn = 3 ' Column C
                        'End If
                        Dim targetColumn As Integer = 3 ' Always print label in Column C

                        Dim groupCode As String = ""
                        Dim match = System.Text.RegularExpressions.Regex.Match(label, "(\d+)")
                        If match.Success Then
                            groupCode = match.Groups(1).Value
                        End If
                        Dim needsDollarFormat As Boolean = False

                        Using conn As New OleDbConnection(connStr)
                            conn.Open()

                            Using cmd As New OleDbCommand("Select DollarFlag FROM PLQBMapping WHERE ClientName = ? And ReportID = 2 And GroupCode = ?", conn)
                                cmd.Parameters.AddWithValue("?", selectedClient.Name)
                                cmd.Parameters.AddWithValue("?", groupCode)

                                Dim result As Object = cmd.ExecuteScalar()
                                If result IsNot Nothing AndAlso result IsNot DBNull.Value Then
                                    needsDollarFormat = Convert.ToBoolean(result)
                                End If
                            End Using
                        End Using

                        label = CleanLabel(label)
                        Dim displayLabel As String = label


                        ' Check if label might be an FSGroup (and needs to be renamed)
                        Using conn As New OleDbConnection(connStr)
                            conn.Open()

                            Using cmd As New OleDbCommand("SELECT RenameGrpLabel FROM PLQBMapping WHERE ClientName = ? AND ReportID = 2 AND GroupLabel = ?", conn)
                                cmd.Parameters.AddWithValue("?", selectedClient.Name)
                                cmd.Parameters.AddWithValue("?", label)

                                Dim result As Object = cmd.ExecuteScalar()
                                If result IsNot Nothing AndAlso Not Convert.ToString(result).Trim() = "" Then
                                    displayLabel = Convert.ToString(result).Trim()
                                End If
                            End Using
                        End Using


                        Static GRPWidth As Double = 4 ' default width

                        Using conn3 As New OleDbConnection(connStr)
                            conn3.Open()

                            Dim widthQuery As String = "SELECT TOP 1 GRPWidth " &
                                "FROM PLQBMapping " &
                                "WHERE ClientName = ? AND ReportID = 2 AND IsActive = True " &
                                "AND GroupLabel = ? AND GRPWidth IS NOT NULL"

                            Using cmdWidth As New OleDbCommand(widthQuery, conn3)
                                cmdWidth.Parameters.AddWithValue("?", selectedClient.Name)
                                cmdWidth.Parameters.AddWithValue("?", label)

                                Using readerWidth As OleDbDataReader = cmdWidth.ExecuteReader()
                                    If readerWidth.Read() Then
                                        Dim dbWidthStr As String = readerWidth("GRPWidth")?.ToString().Trim()
                                        Dim parsed As Double
                                        If Not String.IsNullOrWhiteSpace(dbWidthStr) AndAlso Double.TryParse(dbWidthStr, parsed) Then
                                            GRPWidth = parsed ' update only if valid
                                        End If
                                    End If
                                End Using
                            End Using
                        End Using

                        Console.WriteLine("Final GRPWidth being applied for label '" & label & "': " & GRPWidth)

                        ' Apply the last valid GRPWidth to column 4
                        worksheet.Column(3).Width = GRPWidth


                        worksheet.Cells(excelRow, targetColumn).Value = displayLabel
                        worksheet.Cells(excelRow, targetColumn).Style.HorizontalAlignment = OfficeOpenXml.Style.ExcelHorizontalAlignment.Left




                        ' Step 1: Fetch dynamic divider labels
                        Dim topDividerLabels As New List(Of String)()
                        Dim topBottomDividerLabels As New List(Of String)()

                        Using conn As New OleDbConnection(connStr)
                            conn.Open()

                            ' Fetch TopDivider labels
                            Using cmdTop As New OleDbCommand("SELECT GroupLabel FROM PLQBMapping WHERE ClientName = ? AND ReportID = 2 AND TopDivider = True", conn)
                                cmdTop.Parameters.AddWithValue("?", selectedClient.Name)

                                Using reader = cmdTop.ExecuteReader()
                                    While reader.Read()
                                        topDividerLabels.Add(reader("GroupLabel").ToString().Trim())
                                    End While
                                End Using
                            End Using

                            ' Fetch TopBottomDivider labels
                            Using cmdBottom As New OleDbCommand("SELECT GroupLabel FROM PLQBMapping WHERE ClientName = ? AND ReportID = 2 AND TopBottomDivider = True", conn)
                                cmdBottom.Parameters.AddWithValue("?", selectedClient.Name)

                                Using reader = cmdBottom.ExecuteReader()
                                    While reader.Read()
                                        topBottomDividerLabels.Add(reader("GroupLabel").ToString().Trim())
                                    End While
                                End Using
                            End Using
                        End Using

                        ' Inside your worksheet formatting logic
                        If topBottomDividerLabels.Any(Function(s) s.Equals(label, StringComparison.OrdinalIgnoreCase)) Then
                            For colIndex As Integer = targetColumn + 1 To targetColumn + dgvReport.Columns.Count - 3
                                Dim actualColIndex = colIndex - 1
                                If actualColIndex = accountTypeColIndex Then Continue For

                                worksheet.Cells(excelRow, colIndex).Style.Border.Top.Style = OfficeOpenXml.Style.ExcelBorderStyle.Thin
                                worksheet.Cells(excelRow, colIndex).Style.Border.Bottom.Style = OfficeOpenXml.Style.ExcelBorderStyle.Thick
                            Next
                        End If

                        If topDividerLabels.Any(Function(s) s.Equals(label, StringComparison.OrdinalIgnoreCase)) Then
                            For colIndex As Integer = targetColumn + 1 To targetColumn + dgvReport.Columns.Count - 3
                                Dim actualColIndex = colIndex - 1
                                If actualColIndex = accountTypeColIndex Then Continue For

                                worksheet.Cells(excelRow, colIndex).Style.Border.Top.Style = OfficeOpenXml.Style.ExcelBorderStyle.Thin
                            Next
                        End If

                        ' Fill month values, skipping AccountType column
                        For colIndex As Integer = 1 To dgvReport.Columns.Count - 1
                            Dim colName = dgvReport.Columns(colIndex).HeaderText
                            Dim val = gridRow.Cells(colIndex).Value?.ToString()?.Trim()

                            ' Handle null or empty as "0.00"
                            If String.IsNullOrEmpty(val) Then
                                val = "0.00"

                            End If


                            If label.Equals("Provision For Income Taxes", StringComparison.OrdinalIgnoreCase) AndAlso colIndex < dgvReport.Columns.Count - 1 Then
                                ' Remove brackets, commas, and minus signs to get numeric value
                                Dim cleanVal As String = val.Replace("(", "").Replace(")", "").Replace(",", "").Replace("-", "").Trim()
                                Dim numericVal As Double
                                If Double.TryParse(cleanVal, numericVal) Then
                                    ' Make it negative
                                    val = (-numericVal).ToString()
                                Else
                                    ' fallback to original value if not numeric
                                    val = val
                                End If
                            End If

                            If dgvReport.Columns(colIndex).Visible AndAlso Not String.IsNullOrEmpty(val) Then
                                Dim outputColumn = targetColumn + colIndex - 2
                                If outputColumn < 1 Then outputColumn = 1

                                Dim numericVal As Double
                                Dim isLastColumn = (colIndex = dgvReport.Columns.Count - 1)

                                ' Check if value is in bracket format like (43,392.98)
                                Dim isBracketed = System.Text.RegularExpressions.Regex.IsMatch(val, "^\(\d{1,3}(,\d{3})*(\.\d{1,2})?\)$")
                                ' Check if value is malformed like -(43,392.98) or - (43,392.98)
                                Dim isMalformedBracketed = System.Text.RegularExpressions.Regex.IsMatch(val, "^-?\s*\(\d{1,3}(,\d{3})*(\.\d{1,2})?\)$")

                                If Double.TryParse(val.Replace(",", ""), numericVal) Then
                                    ' Normal numeric value
                                    worksheet.Cells(excelRow, outputColumn).Value = numericVal

                                ElseIf isBracketed Then
                                    ' Bracketed negative value
                                    Dim cleaned = val.Replace("(", "").Replace(")", "").Replace(",", "").Replace("-", "")
                                    If Double.TryParse(cleaned, numericVal) Then
                                        worksheet.Cells(excelRow, outputColumn).Value = -numericVal
                                    Else
                                        worksheet.Cells(excelRow, outputColumn).Value = val ' fallback
                                    End If

                                Else
                                    worksheet.Cells(excelRow, outputColumn).Value = val
                                End If

                                worksheet.Cells(excelRow, outputColumn).Style.HorizontalAlignment = OfficeOpenXml.Style.ExcelHorizontalAlignment.Right
                                worksheet.Column(outputColumn).Width = 13

                                ' Format cell using DollarFlag
                                If needsDollarFormat Then
                                    worksheet.Cells(excelRow, outputColumn).Style.Numberformat.Format = "$   #,##0.00;($   #,##0.00)"
                                Else
                                    Dim cellVal As Double
                                    If Double.TryParse(worksheet.Cells(excelRow, outputColumn).Value.ToString(), cellVal) Then
                                        If cellVal < 0 Then
                                            worksheet.Cells(excelRow, outputColumn).Style.Numberformat.Format = "#,##0.00;(#,##0.00);0"
                                        Else
                                            worksheet.Cells(excelRow, outputColumn).Style.Numberformat.Format = "#,##0.00"
                                        End If
                                    Else
                                        worksheet.Cells(excelRow, outputColumn).Style.Numberformat.Format = "#,##0.00"
                                    End If
                                End If
                            End If




                        Next

                    ElseIf {"SubtotalRow", "TotalRow"}.Contains(rowType) Then

                        ' Determine target column based on rowNumber
                        Dim targetColumn As Integer = 1 ' Default
                        If rowNumber > 2 Then
                            'targetColumn = 4 ' Column C
                            If label.Trim().StartsWith("Total", StringComparison.OrdinalIgnoreCase) Then
                                targetColumn = 4 ' Column D for Totals
                            Else
                                targetColumn = 3 ' Column C for others
                            End If
                        End If

                        Dim needsDollarFormat As Boolean = False

                        Using conn As New OleDbConnection(connStr)
                            conn.Open()

                            Using cmd As New OleDbCommand("Select DollarFlag FROM PLQBMapping WHERE ClientName = ? And ReportID = 2 And GroupLabel = ?", conn)
                                cmd.Parameters.AddWithValue("?", selectedClient.Name)
                                cmd.Parameters.AddWithValue("?", label)

                                Dim result As Object = cmd.ExecuteScalar()
                                If result IsNot Nothing AndAlso result IsNot DBNull.Value Then
                                    needsDollarFormat = Convert.ToBoolean(result)
                                End If
                            End Using
                        End Using

                        label = CleanLabel(label)
                        Dim displayLabel As String = label


                        ' Check if label might be an FSGroup (and needs to be renamed)
                        Using conn As New OleDbConnection(connStr)
                            conn.Open()

                            Using cmd As New OleDbCommand("SELECT RenameGrpLabel FROM PLQBMapping WHERE ClientName = ? AND ReportID = 2 AND GroupLabel = ?", conn)
                                cmd.Parameters.AddWithValue("?", selectedClient.Name)
                                cmd.Parameters.AddWithValue("?", label)

                                Dim result As Object = cmd.ExecuteScalar()
                                If result IsNot Nothing AndAlso Not Convert.ToString(result).Trim() = "" Then
                                    displayLabel = Convert.ToString(result).Trim()
                                End If
                            End Using
                        End Using


                        ' Retain the last valid width across multiple label checks
                        Static subGRPWidth As Double = 40 ' default width

                        Using conn3 As New OleDbConnection(connStr)
                            conn3.Open()

                            Dim widthQuery As String = "SELECT TOP 1 GRPWidth " &
                                "FROM PLQBMapping " &
                                "WHERE ClientName = ? AND ReportID = 2 AND IsActive = True " &
                                "AND GroupLabel = ? AND GRPWidth IS NOT NULL"

                            Using cmdWidth As New OleDbCommand(widthQuery, conn3)
                                cmdWidth.Parameters.AddWithValue("?", selectedClient.Name)
                                cmdWidth.Parameters.AddWithValue("?", label)

                                Using readerWidth As OleDbDataReader = cmdWidth.ExecuteReader()
                                    If readerWidth.Read() Then
                                        Dim dbWidthStr As String = readerWidth("GRPWidth")?.ToString().Trim()
                                        Dim parsed As Double
                                        If Not String.IsNullOrWhiteSpace(dbWidthStr) AndAlso Double.TryParse(dbWidthStr, parsed) Then
                                            subGRPWidth = parsed ' update only if valid
                                        End If
                                    End If
                                End Using
                            End Using
                        End Using

                        ' Console.WriteLine("Final GRPWidth being applied for label '" & label & "': " & GRPWidth)

                        ' Apply the last valid GRPWidth to column 4
                        worksheet.Column(4).Width = subGRPWidth



                        worksheet.Cells(excelRow, targetColumn).Value = displayLabel
                        worksheet.Cells(excelRow, targetColumn).Style.HorizontalAlignment = OfficeOpenXml.Style.ExcelHorizontalAlignment.Left




                        ' Step 1: Fetch dynamic divider labels
                        Dim topDividerLabels As New List(Of String)()
                        Dim topBottomDividerLabels As New List(Of String)()

                        Using conn As New OleDbConnection(connStr)
                            conn.Open()

                            ' Fetch TopDivider labels
                            Using cmdTop As New OleDbCommand("SELECT GroupLabel FROM PLQBMapping WHERE ClientName = ? AND ReportID = 2 AND TopDivider = True", conn)
                                cmdTop.Parameters.AddWithValue("?", selectedClient.Name)

                                Using reader = cmdTop.ExecuteReader()
                                    While reader.Read()
                                        topDividerLabels.Add(reader("GroupLabel").ToString().Trim())
                                    End While
                                End Using
                            End Using

                            ' Fetch TopBottomDivider labels
                            Using cmdBottom As New OleDbCommand("SELECT GroupLabel FROM PLQBMapping WHERE ClientName = ? AND ReportID = 2 AND TopBottomDivider = True", conn)
                                cmdBottom.Parameters.AddWithValue("?", selectedClient.Name)

                                Using reader = cmdBottom.ExecuteReader()
                                    While reader.Read()
                                        topBottomDividerLabels.Add(reader("GroupLabel").ToString().Trim())
                                    End While
                                End Using
                            End Using
                        End Using

                        ' Inside your worksheet formatting logic
                        If topBottomDividerLabels.Any(Function(s) s.Equals(label, StringComparison.OrdinalIgnoreCase)) Then
                            For colIndex As Integer = 3 + 1 To 3 + dgvReport.Columns.Count - 3
                                Dim actualColIndex = colIndex - 1
                                If actualColIndex = accountTypeColIndex Then Continue For

                                worksheet.Cells(excelRow, colIndex).Style.Border.Top.Style = OfficeOpenXml.Style.ExcelBorderStyle.Thin
                                worksheet.Cells(excelRow, colIndex).Style.Border.Bottom.Style = OfficeOpenXml.Style.ExcelBorderStyle.Thick
                            Next
                        End If

                        If topDividerLabels.Any(Function(s) s.Equals(label, StringComparison.OrdinalIgnoreCase)) Then
                            For colIndex As Integer = 3 + 1 To 3 + dgvReport.Columns.Count - 3
                                Dim actualColIndex = colIndex - 1
                                If actualColIndex = accountTypeColIndex Then Continue For

                                worksheet.Cells(excelRow, colIndex).Style.Border.Top.Style = OfficeOpenXml.Style.ExcelBorderStyle.Thin
                            Next
                        End If

                        ' Fill month values, skipping AccountType column
                        For colIndex As Integer = 1 To dgvReport.Columns.Count - 1
                            Dim colName = dgvReport.Columns(colIndex).HeaderText
                            Dim val = gridRow.Cells(colIndex).Value?.ToString()?.Trim()

                            ' Handle null or empty as "0.00"
                            If String.IsNullOrEmpty(val) Then
                                val = "0.00"

                            End If


                            If label.Equals("Provision For Income Taxes", StringComparison.OrdinalIgnoreCase) AndAlso colIndex < dgvReport.Columns.Count - 1 Then
                                ' Remove brackets, commas, and minus signs to get numeric value
                                Dim cleanVal As String = val.Replace("(", "").Replace(")", "").Replace(",", "").Replace("-", "").Trim()
                                Dim numericVal As Double
                                If Double.TryParse(cleanVal, numericVal) Then
                                    ' Make it negative
                                    val = (-numericVal).ToString()
                                Else
                                    ' fallback to original value if not numeric
                                    val = val
                                End If
                            End If

                            If dgvReport.Columns(colIndex).Visible AndAlso Not String.IsNullOrEmpty(val) Then
                                'Dim outputColumn = targetColumn + colIndex - 2
                                Dim outputColumn = 3 + colIndex - 2
                                'Dim outputColumn As Integer

                                'If label.Trim().StartsWith("Total", StringComparison.OrdinalIgnoreCase) Then
                                '    ' Label is in column 4, so start months from column 5
                                '    outputColumn = 3 + colIndex - 1
                                'Else
                                '    ' Label is in column 3, so start months from column 4
                                '    outputColumn = 3 + colIndex - 1
                                'End If

                                If outputColumn < 1 Then outputColumn = 1

                                Dim numericVal As Double
                                Dim isLastColumn = (colIndex = dgvReport.Columns.Count - 1)

                                ' Check if value is in bracket format like (43,392.98)
                                Dim isBracketed = System.Text.RegularExpressions.Regex.IsMatch(val, "^\(\d{1,3}(,\d{3})*(\.\d{1,2})?\)$")
                                ' Check if value is malformed like -(43,392.98) or - (43,392.98)
                                Dim isMalformedBracketed = System.Text.RegularExpressions.Regex.IsMatch(val, "^-?\s*\(\d{1,3}(,\d{3})*(\.\d{1,2})?\)$")

                                If Double.TryParse(val.Replace(",", ""), numericVal) Then
                                    ' Normal numeric value
                                    worksheet.Cells(excelRow, outputColumn).Value = numericVal

                                ElseIf isBracketed Then
                                    ' Bracketed negative value
                                    Dim cleaned = val.Replace("(", "").Replace(")", "").Replace(",", "").Replace("-", "")
                                    If Double.TryParse(cleaned, numericVal) Then
                                        worksheet.Cells(excelRow, outputColumn).Value = -numericVal
                                    Else
                                        worksheet.Cells(excelRow, outputColumn).Value = val ' fallback
                                    End If

                                Else
                                    worksheet.Cells(excelRow, outputColumn).Value = val
                                End If

                                worksheet.Cells(excelRow, outputColumn).Style.HorizontalAlignment = OfficeOpenXml.Style.ExcelHorizontalAlignment.Right

                                ' Format cell using DollarFlag
                                If needsDollarFormat Then
                                    worksheet.Column(excelRow).AutoFit()
                                    worksheet.Cells(excelRow, outputColumn).Style.Numberformat.Format = "$   #,##0.00;($   #,##0.00)"
                                Else
                                    worksheet.Column(excelRow).AutoFit()
                                    Dim cellVal As Double
                                    If Double.TryParse(worksheet.Cells(excelRow, outputColumn).Value.ToString(), cellVal) Then
                                        If cellVal < 0 Then
                                            worksheet.Cells(excelRow, outputColumn).Style.Numberformat.Format = "#,##0.00;(#,##0.00);0"
                                        Else
                                            worksheet.Cells(excelRow, outputColumn).Style.Numberformat.Format = "#,##0.00"
                                        End If
                                    Else
                                        worksheet.Cells(excelRow, outputColumn).Style.Numberformat.Format = "#,##0.00"
                                    End If
                                End If
                            End If




                        Next



                    Else

                    End If

                    excelRow += 1
                Next

                ' worksheet.Cells.AutoFitColumns()
                ' --- Cleanup: Remove "$" from column G ---
                Dim lastUsedRow As Integer = worksheet.Dimension.End.Row


                Dim lastColumnIndex As Integer = 0
                For i As Integer = dgvReport.Columns.Count - 1 To 0 Step -1
                    If dgvReport.Columns(i).Visible Then
                        lastColumnIndex = i + 1 ' Excel columns are 1-based
                        Exit For
                    End If
                Next

                For row As Integer = headingRow + 5 To lastUsedRow
                    Dim cell = worksheet.Cells(row, lastColumnIndex)
                    Dim val = cell.Text
                    If val.Contains("$") Then
                        Dim numericVal As Double
                        If Double.TryParse(val.Replace("$", "").Replace(",", "").Trim(), numericVal) Then
                            cell.Value = numericVal
                            cell.Style.Numberformat.Format = "#,##0.00" ' Reapply without dollar sign
                        End If
                    End If
                Next


                package.Save()
            End Using
            'lblStatus.Text = ""
            HideStatus()
            Cursor.Current = Cursors.Default
            'lblStatus.Visible = False
            MessageBox.Show("Excel exported successfully.")
        End If
    End Sub
End Class

' ---------- DTO reused from Balance Sheet ----------
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
