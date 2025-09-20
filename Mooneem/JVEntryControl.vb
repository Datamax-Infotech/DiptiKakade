Imports System.IO
Imports System.Data.OleDb
Imports System.Data
Imports QBFC12Lib
Imports Mysqlx.Crud
Imports OfficeOpenXml.FormulaParsing.Excel.Functions
Imports System.Text

Public Class JVEntryControl
    Inherits UserControl

    Private lblUpload As Label
    Private btnBrowse As Button
    Private comboClient As ComboBox
    Private comboJVType As ComboBox
    Private WithEvents btnSubmit As Button
    Private dgvData As DataGridView
    Private filePath As String
    Private chkSelectAll As CheckBox
    Private lblDate As Label
    Private dtpDate As DateTimePicker
    Private lblMemo As Label
    Private txtMemo As TextBox
    Private lblNum As Label
    Private txtNum As TextBox
    Private processor As New QBSessionManager()
    Private PayrollTable As DataTable

    'Private ReadOnly connStr As String =
    '    "Provider=Microsoft.ACE.OLEDB.12.0;" &
    '    "Data Source=C:\Users\lenovo\Documents\MooneemDB.accdb;" &
    '    "Persist Security Info=False;"
    Private ReadOnly connStr As String = "Provider=Microsoft.ACE.OLEDB.12.0;Data Source=" + Application.StartupPath + "\MooneemDB.accdb;Persist Security Info=False;"





    Private Sub PositionCheckBox(sender As Object, e As EventArgs)
        Dim rect As Rectangle = dgvData.GetCellDisplayRectangle(0, -1, True)
        chkSelectAll.Location = New Point(rect.X + (rect.Width - chkSelectAll.Width) \ 2, rect.Y + (rect.Height - chkSelectAll.Height) \ 2)
    End Sub

    Private Sub SelectAllChanged(sender As Object, e As EventArgs)
        For Each row As DataGridViewRow In dgvData.Rows
            row.Cells(0).Value = chkSelectAll.Checked
        Next
    End Sub




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
            comboClient.DropDownStyle = ComboBoxStyle.DropDown
            comboClient.AutoCompleteMode = AutoCompleteMode.SuggestAppend
            comboClient.AutoCompleteSource = AutoCompleteSource.ListItems

        Catch ex As Exception
            MessageBox.Show("DB load error: " & ex.Message)
        End Try
    End Sub


    Public Sub New()
        Me.Dock = DockStyle.Fill
        Me.BackColor = Color.White
        Me.Font = New Font("Verdana", 11)

        Dim controlHeight As Integer = 28

        ' ===== Top Panel (Row 1) =====
        Dim topPanel As New FlowLayoutPanel() With {
        .Dock = DockStyle.Top,
        .Height = 50,
        .Padding = New Padding(15, 10, 15, 10),
        .BackColor = Color.White,
        .AutoSize = False,
        .FlowDirection = FlowDirection.LeftToRight,
        .WrapContents = False
    }

        ' JV Type
        Dim lblJVType As New Label() With {.Text = "Select JV Type:", .AutoSize = True, .Margin = New Padding(0, 5, 5, 0)}
        comboJVType = New ComboBox() With {.Width = 200, .DropDownStyle = ComboBoxStyle.DropDownList, .Font = New Font("Verdana", 11), .Height = controlHeight}
        comboJVType.Items.AddRange({"Select JV Type", "Accrual Entry", "Payroll Entry"})
        comboJVType.SelectedIndex = 0
        AddHandler comboJVType.SelectedIndexChanged, AddressOf JVTypeChanged

        ' Upload
        lblUpload = New Label() With {.Text = "Upload Excel File:", .AutoSize = True, .Margin = New Padding(15, 5, 5, 0)}
        btnBrowse = New Button() With {.Text = "Browse...", .Width = 100, .Height = controlHeight, .Font = New Font("Verdana", 10, FontStyle.Bold)}
        AddHandler btnBrowse.Click, AddressOf BrowseExcel

        ' Client
        comboClient = New ComboBox() With {.Width = 300, .DropDownStyle = ComboBoxStyle.DropDownList, .Font = New Font("Verdana", 11), .Height = controlHeight}

        ' Submit
        btnSubmit = New Button() With {.Text = "Submit", .Width = 100, .Height = controlHeight, .Font = New Font("Verdana", 10, FontStyle.Bold)}

        ' Add controls to top row
        topPanel.Controls.AddRange({lblJVType, comboJVType, lblUpload, btnBrowse, comboClient, btnSubmit})

        ' ===== Second Row Panel =====
        Dim secondPanel As New FlowLayoutPanel() With {
        .Dock = DockStyle.Top,
        .Height = 50,
        .Padding = New Padding(15, 10, 15, 10),
        .BackColor = Color.White,
        .AutoSize = False,
        .FlowDirection = FlowDirection.LeftToRight,
        .WrapContents = False
    }

        lblDate = New Label() With {.Text = "Select Date:", .AutoSize = True, .Visible = False, .Margin = New Padding(0, 5, 5, 0)}
        dtpDate = New DateTimePicker() With {.Format = DateTimePickerFormat.Short, .Height = controlHeight, .Visible = False}

        lblNum = New Label() With {.Text = "Num:", .AutoSize = True, .Visible = False, .Margin = New Padding(15, 5, 5, 0)}
        txtNum = New TextBox() With {.Width = 120, .Height = controlHeight, .Visible = False}

        lblMemo = New Label() With {.Text = "Memo:", .AutoSize = True, .Visible = False, .Margin = New Padding(15, 5, 5, 0)}
        txtMemo = New TextBox() With {.Width = 200, .Height = controlHeight, .Visible = False}

        secondPanel.Controls.AddRange({lblDate, dtpDate, lblNum, txtNum, lblMemo, txtMemo})

        ' ===== DataGridView =====
        dgvData = New DataGridView() With {
        .Dock = DockStyle.Fill,
        .AllowUserToAddRows = False,
        .AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.Fill,
        .Font = New Font("Verdana", 11),
        .SelectionMode = DataGridViewSelectionMode.FullRowSelect
    }
        dgvData.RowTemplate.Height = 35
        dgvData.ColumnHeadersDefaultCellStyle.Font = New Font("Verdana", 11, FontStyle.Bold)

        ' ===== Add Controls in Correct Order =====
        Me.Controls.Add(dgvData)
        Me.Controls.Add(secondPanel)
        Me.Controls.Add(topPanel)

        ' Load clients
        LoadClients()
    End Sub



    Private Sub JVTypeChanged(sender As Object, e As EventArgs)
        If comboJVType.SelectedItem.ToString() = "Accrual Entry" Then
            lblDate.Visible = True
            dtpDate.Visible = True
            lblNum.Visible = True
            txtNum.Visible = True
            lblMemo.Visible = False
            txtMemo.Visible = False
        ElseIf comboJVType.SelectedItem.ToString() = "Payroll Entry" Then
            lblNum.Visible = True
            txtNum.Visible = True
            lblMemo.Visible = True
            txtMemo.Visible = True
        End If
    End Sub


    Private Sub LoadExcelData(path As String)
        Try
            Dim ext As String = System.IO.Path.GetExtension(path)
            Dim connStr As String

            If ext.ToLower() = ".xls" Then
                connStr = "Provider=Microsoft.Jet.OLEDB.4.0;Data Source=" & path & ";Extended Properties='Excel 8.0;HDR=NO;'"
            Else
                connStr = "Provider=Microsoft.ACE.OLEDB.12.0;Data Source=" & path & ";Extended Properties='Excel 12.0 Xml;HDR=NO;'"
            End If

            Using conn As New OleDbConnection(connStr)
                conn.Open()

                Dim sheetName As String = "Sheet1$"
                Dim cmd As New OleDbCommand("SELECT * FROM [" & sheetName & "]", conn)
                Dim adapter As New OleDbDataAdapter(cmd)
                Dim dt As New DataTable()
                adapter.Fill(dt)

                ' ✅ Use 3rd row as header, remove top 3 rows
                If dt.Rows.Count >= 3 Then
                    Dim headerRow As DataRow = dt.Rows(2)
                    For colIndex As Integer = 0 To dt.Columns.Count - 1
                        Dim colName As String = headerRow(colIndex)?.ToString().Trim()
                        If String.IsNullOrWhiteSpace(colName) Then
                            colName = "Column" & (colIndex + 1)
                        End If
                        dt.Columns(colIndex).ColumnName = colName
                    Next
                    dt.Rows.RemoveAt(0)
                    dt.Rows.RemoveAt(0)
                    dt.Rows.RemoveAt(0)
                End If

                ' ✅ Remove last column if it’s completely blank (ignore empty column)
                Dim lastColName As String = dt.Columns(dt.Columns.Count - 1).ColumnName
                Dim shouldRemoveLast As Boolean = True
                For Each row As DataRow In dt.Rows
                    If Not String.IsNullOrWhiteSpace(row(lastColName)?.ToString()) Then
                        shouldRemoveLast = False
                        Exit For
                    End If
                Next
                If shouldRemoveLast Then
                    dt.Columns.RemoveAt(dt.Columns.Count - 1)
                End If

                ' ✅ Fill down Account
                'Dim lastAccount As String = ""
                'If dt.Columns.Contains("Account") Then
                '    For Each row As DataRow In dt.Rows
                '        Dim currAcc As String = row("Account")?.ToString().Trim()
                '        If Not String.IsNullOrWhiteSpace(currAcc) Then
                '            lastAccount = currAcc
                '        ElseIf Not String.IsNullOrWhiteSpace(lastAccount) Then
                '            row("Account") = lastAccount
                '        End If
                '    Next
                'End If
                ' ✅ Fill down Account names & mark grand totals for removal
                Dim lastAccount As String = ""
                Dim seenAccounts As New HashSet(Of String)
                Dim rowsToRemove As New List(Of DataRow)

                If dt.Columns.Contains("Account") Then
                    For Each row As DataRow In dt.Rows
                        Dim currentAccount As String = row("Account")?.ToString().Trim()
                        Dim paymentCompany As String = If(dt.Columns.Contains("Updated Payment Company"), row("Updated Payment Company")?.ToString().Trim(), "")

                        ' ✅ Rename specific accounts
                        If String.Equals(currentAccount, "Freight", StringComparison.OrdinalIgnoreCase) Then
                            row("Account") = "COGS - Freight Expenses"
                            currentAccount = "COGS - Freight Expenses"
                        ElseIf String.Equals(currentAccount, "Pallets", StringComparison.OrdinalIgnoreCase) Then
                            row("Account") = "COGS - Pallets"
                            currentAccount = "COGS - Pallets"
                        End If
                        If Not String.IsNullOrWhiteSpace(currentAccount) Then
                            ' First time we see this account → mark as grand total row
                            If Not seenAccounts.Contains(currentAccount) AndAlso String.IsNullOrWhiteSpace(paymentCompany) Then
                                rowsToRemove.Add(row)
                                seenAccounts.Add(currentAccount)
                            End If
                            lastAccount = currentAccount
                        ElseIf Not String.IsNullOrWhiteSpace(lastAccount) Then
                            row("Account") = lastAccount
                        End If
                    Next
                End If

                '✅' Process Updated Payment Company → Account
                If dt.Columns.Contains("Updated Payment Company") AndAlso dt.Columns.Contains("Account") Then
                    For Each row As DataRow In dt.Rows
                        Dim paymentCompany As String = row("Updated Payment Company")?.ToString().Trim()
                        If Not String.IsNullOrWhiteSpace(paymentCompany) Then
                            row("Account") = "Vendor costs Accrued"
                        End If
                    Next
                End If
                ' ✅ Fill down Division
                If dt.Columns.Contains("Division") Then
                    Dim lastDiv As String = ""
                    For Each row As DataRow In dt.Rows
                        Dim cd As String = row("Division")?.ToString().Trim()
                        If Not String.IsNullOrWhiteSpace(cd) Then
                            lastDiv = cd
                        ElseIf Not String.IsNullOrWhiteSpace(lastDiv) Then
                            row("Division") = lastDiv
                        End If
                    Next
                End If

                ' ✅ Identify the second last column now (Amount column)
                Dim amountColumnIndex As Integer = dt.Columns.Count - 2
                Dim amountColumnName As String = dt.Columns(amountColumnIndex).ColumnName

                ' ✅ Add Debit & Credit columns
                dt.Columns.Add("Debit", GetType(Decimal))
                dt.Columns.Add("Credit", GetType(Decimal))

                ' ✅ Split Amount into Debit/Credit but KEEP the amount column visible
                Dim lastDivision As String = ""
                Dim isFirstAmtRow As Boolean = False

                For Each row As DataRow In dt.Rows
                    Dim currentDivision As String = If(dt.Columns.Contains("Division"), row("Division")?.ToString().Trim(), "")

                    If currentDivision <> lastDivision AndAlso Not String.IsNullOrWhiteSpace(currentDivision) Then
                        lastDivision = currentDivision
                        isFirstAmtRow = True
                    End If

                    Dim amt As Decimal
                    If Decimal.TryParse(row(amountColumnName)?.ToString().Trim(), amt) Then
                        If isFirstAmtRow Then
                            row("Debit") = amt
                            row("Credit") = 0
                            isFirstAmtRow = False
                        Else
                            row("Credit") = amt
                            row("Debit") = 0
                        End If
                    Else
                        row("Debit") = 0
                        row("Credit") = 0
                    End If
                Next
                dt.Columns.Remove(amountColumnName)

                If dt.Columns.Contains("Memo") Then
                    For i As Integer = dt.Rows.Count - 1 To 0 Step -1
                        Dim memoVal As String = dt.Rows(i)("Memo")?.ToString().Trim()
                        If String.IsNullOrWhiteSpace(memoVal) Then
                            dt.Rows.RemoveAt(i)
                        End If
                    Next
                End If
                ' ✅ Bind to Grid
                dgvData.DataSource = dt
                dgvData.DefaultCellStyle.Font = New Font("Verdana", 11)
            End Using

        Catch ex As Exception
            MessageBox.Show("Error loading Excel: " & ex.Message)
        End Try
    End Sub



    Private Sub LoadPayrollExcelData(path As String)
        Try
            Dim connStr As String
            connStr = "Provider=Microsoft.ACE.OLEDB.12.0;Data Source=" & path & ";Extended Properties='Excel 12.0 Xml;HDR=NO;IMEX=1;'"

            Using conn As New OleDbConnection(connStr)
                conn.Open()

                Dim adapter As New OleDbDataAdapter("SELECT * FROM [Sheet1$]", conn)
                Dim dtRaw As New DataTable()
                adapter.Fill(dtRaw)

                ' 1) Make first row as column names safely (replace F1,F2,...)
                If dtRaw.Rows.Count > 0 Then
                    For c As Integer = 0 To dtRaw.Columns.Count - 1
                        Dim colName As String = dtRaw.Rows(0)(c).ToString().Trim()
                        If colName = "" Then
                            colName = "Column" & (c + 1).ToString()
                        End If

                        ' If duplicate column name, make it unique
                        Dim originalName As String = colName
                        Dim counter As Integer = 1
                        While dtRaw.Columns.Cast(Of DataColumn).Any(Function(dc) dc.ColumnName = colName AndAlso dtRaw.Columns.IndexOf(dc) < c)
                            colName = originalName & "_" & counter
                            counter += 1
                        End While

                        dtRaw.Columns(c).ColumnName = colName
                    Next
                    dtRaw.Rows(0).Delete()
                    dtRaw.AcceptChanges()
                End If

                ' 2) Remove completely empty rows
                For i As Integer = dtRaw.Rows.Count - 1 To 0 Step -1
                    Dim isEmptyRow As Boolean = True
                    For j As Integer = 0 To dtRaw.Columns.Count - 1
                        If Not IsDBNull(dtRaw.Rows(i)(j)) AndAlso dtRaw.Rows(i)(j).ToString().Trim() <> "" Then
                            isEmptyRow = False
                            Exit For
                        End If
                    Next
                    If isEmptyRow Then
                        dtRaw.Rows.RemoveAt(i)
                    End If
                Next

                ' 3) Remove rows where Class is blank
                If dtRaw.Columns.Contains("Class") Then
                    For i As Integer = dtRaw.Rows.Count - 1 To 0 Step -1
                        Dim classVal As String = dtRaw.Rows(i)("Class").ToString().Trim()
                        If classVal = "" Then
                            dtRaw.Rows.RemoveAt(i)
                        End If
                    Next
                End If

                ' 4) Remove empty columns
                For c As Integer = dtRaw.Columns.Count - 1 To 0 Step -1
                    Dim isEmptyCol As Boolean = True
                    For Each row As DataRow In dtRaw.Rows
                        If Not IsDBNull(row(c)) AndAlso row(c).ToString().Trim() <> "" Then
                            isEmptyCol = False
                            Exit For
                        End If
                    Next
                    If isEmptyCol Then
                        dtRaw.Columns.RemoveAt(c)
                    End If
                Next

                ' 5) Remove last 3 columns
                If dtRaw.Columns.Count >= 3 Then
                    dtRaw.Columns.RemoveAt(dtRaw.Columns.Count - 1)
                    dtRaw.Columns.RemoveAt(dtRaw.Columns.Count - 1)
                    dtRaw.Columns.RemoveAt(dtRaw.Columns.Count - 1)
                End If

                ' 6) Propagate Account name downwards when blank
                If dtRaw.Columns.Count > 0 Then
                    Dim previousAccount As String = ""
                    For i As Integer = 0 To dtRaw.Rows.Count - 1
                        Dim accountVal As String = dtRaw.Rows(i)(0).ToString().Trim()
                        If accountVal <> "" Then
                            previousAccount = accountVal
                        ElseIf previousAccount <> "" Then
                            dtRaw.Rows(i)(0) = previousAccount
                        End If
                    Next
                End If

                ' 7) Remove rows where second column (Column2) is empty except first and last row
                If dtRaw.Rows.Count > 2 Then
                    For i As Integer = dtRaw.Rows.Count - 2 To 1 Step -1
                        Dim val As String = dtRaw.Rows(i)(1).ToString().Trim()
                        If val = "" Then
                            dtRaw.Rows.RemoveAt(i)
                        End If
                    Next
                End If

                ' 8) If last row Class is empty, set to "Corporate"
                If dtRaw.Rows.Count > 0 AndAlso dtRaw.Columns.Count > 1 Then
                    Dim lastRowIndex = dtRaw.Rows.Count - 1
                    If dtRaw.Rows(lastRowIndex)(1).ToString().Trim() = "" Then
                        dtRaw.Rows(lastRowIndex)(1) = "Corporate"
                    End If
                End If

                ' 9) Rename cell values for accounts
                For r As Integer = 0 To dtRaw.Rows.Count - 1
                    For c As Integer = 0 To dtRaw.Columns.Count - 1
                        Dim cellVal As String = dtRaw.Rows(r)(c).ToString().Trim()
                        If cellVal = "Sum of Admin Fee" Then
                            dtRaw.Rows(r)(c) = "Payroll Service Fees"
                        ElseIf cellVal = "Sum of ER Tax" Then
                            dtRaw.Rows(r)(c) = "Payroll Taxes"
                        End If
                    Next
                Next

                dgvData.DataSource = dtRaw
                PayrollTable = dtRaw   ' Save globally
                ' ✅ Make columns auto-size to fit content
                dgvData.AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.AllCells

                ' ✅ Enable scrollbars so user can scroll when there are many columns
                dgvData.ScrollBars = ScrollBars.Both

                ' ✅ Prevent columns from shrinking too small
                dgvData.AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.DisplayedCells
            End Using

        Catch ex As Exception
            MessageBox.Show("Error loading Payroll Excel: " & ex.Message)
        End Try
    End Sub





    Private Sub BrowseExcel(sender As Object, e As EventArgs)
        Dim ofd As New OpenFileDialog With {
        .Filter = "Excel Files|*.xls;*.xlsx"
    }
        If ofd.ShowDialog() = DialogResult.OK Then
            If comboJVType.SelectedItem.ToString() = "Accrual Entry" Then
                LoadExcelData(ofd.FileName) ' your current detailed logic
            ElseIf comboJVType.SelectedItem.ToString() = "Payroll Entry" Then
                LoadPayrollExcelData(ofd.FileName) ' placeholder for payroll
            End If
        End If
    End Sub


    Private Sub btnSubmit_Click(sender As Object, e As EventArgs) Handles btnSubmit.Click
        Try
            If comboJVType.SelectedItem.ToString() = "Accrual Entry" Then
                SubmitAccrualEntry()
            ElseIf comboJVType.SelectedItem.ToString() = "Payroll Entry" Then
                SubmitPayrollEntry()
            End If
        Catch ex As Exception
            MessageBox.Show("Error: " & ex.Message, "Processing Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Sub


    'Private Sub SubmitAccrualEntry()

    '    Dim sessionMgr As New QBSessionManager
    '    Dim startedSession As Boolean = False
    '    Dim waitForm As WaitForm = Nothing
    '    Try
    '        waitForm = New WaitForm("Submitting entry to QuickBooks... Please wait")
    '        waitForm.Show()
    '        Application.DoEvents()
    '        ' 1. Get selected client
    '        Dim selectedClient As ClientInfo = CType(comboClient.SelectedItem, ClientInfo)
    '        Dim qbFilePath As String = selectedClient.FilePath

    '        If String.IsNullOrEmpty(qbFilePath) Then
    '            MessageBox.Show("No QuickBooks file path found for the selected client.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
    '            Exit Sub
    '        End If

    '        ' 2. Connect to QuickBooks
    '        ' Dim sessionMgr As New QBSessionManager
    '        sessionMgr.OpenConnection("", "Mooneem App")
    '        sessionMgr.BeginSession(qbFilePath, ENOpenMode.omDontCare)
    '        startedSession = True
    '        ' Create request
    '        Dim msgSetRequest As IMsgSetRequest = sessionMgr.CreateMsgSetRequest("US", 12, 0)
    '        msgSetRequest.Attributes.OnError = ENRqOnError.roeContinue
    '        Dim journalEntryAdd As IJournalEntryAdd = msgSetRequest.AppendJournalEntryAddRq()

    '        ' Set Date & Num from first row
    '        Dim firstRow As DataGridViewRow = dgvData.Rows(0)

    '        journalEntryAdd.TxnDate.SetValue(dtpDate.Value)

    '        If Not String.IsNullOrWhiteSpace(txtNum.Text) Then
    '            journalEntryAdd.RefNumber.SetValue(txtNum.Text.Trim())
    '        End If
    '        ' Loop rows
    '        For Each row As DataGridViewRow In dgvData.Rows
    '            If row.IsNewRow Then Continue For
    '            Dim accountName As String = row.Cells("Account").Value?.ToString().Trim()
    '            If String.IsNullOrWhiteSpace(accountName) Then Continue For

    '            Dim debitValue As Double
    '            Dim creditValue As Double
    '            Double.TryParse(row.Cells("Debit").Value?.ToString(), debitValue)
    '            Double.TryParse(row.Cells("Credit").Value?.ToString(), creditValue)
    '            Dim className As String = row.Cells("Division").Value?.ToString().Trim()

    '            If debitValue <> 0 Then
    '                Dim debitORLine As IORJournalLine = journalEntryAdd.ORJournalLineList.Append()
    '                Dim debitLine As IJournalDebitLine = debitORLine.JournalDebitLine
    '                debitLine.AccountRef.FullName.SetValue(accountName)
    '                debitLine.Amount.SetValue(debitValue)
    '                If Not String.IsNullOrWhiteSpace(row.Cells("Updated Payment Company").Value?.ToString()) Then debitLine.EntityRef.FullName.SetValue(row.Cells("Updated Payment Company").Value.ToString())
    '                If Not String.IsNullOrWhiteSpace(row.Cells("Memo").Value?.ToString()) Then debitLine.Memo.SetValue(row.Cells("Memo").Value.ToString())
    '                If Not String.IsNullOrWhiteSpace(className) Then debitLine.ClassRef.FullName.SetValue(className)
    '            End If

    '            If creditValue <> 0 Then
    '                Dim creditORLine As IORJournalLine = journalEntryAdd.ORJournalLineList.Append()
    '                Dim creditLine As IJournalCreditLine = creditORLine.JournalCreditLine
    '                creditLine.AccountRef.FullName.SetValue(accountName)
    '                creditLine.Amount.SetValue(creditValue)
    '                If Not String.IsNullOrWhiteSpace(row.Cells("Updated Payment Company").Value?.ToString()) Then creditLine.EntityRef.FullName.SetValue(row.Cells("Updated Payment Company").Value.ToString())
    '                If Not String.IsNullOrWhiteSpace(row.Cells("Memo").Value?.ToString()) Then creditLine.Memo.SetValue(row.Cells("Memo").Value.ToString())
    '                If Not String.IsNullOrWhiteSpace(className) Then creditLine.ClassRef.FullName.SetValue(className)
    '            End If
    '        Next

    '        ' Send request
    '        Dim responseSet As IMsgSetResponse = sessionMgr.DoRequests(msgSetRequest)
    '        Dim response As IResponse = responseSet.ResponseList.GetAt(0)
    '        If response.StatusCode = 0 Then
    '            MessageBox.Show("✅ Journal Entry added successfully.", "Success")
    '        Else
    '            MessageBox.Show($"❌ Failed: {response.StatusMessage}", "Error")
    '        End If

    '        'sessionMgr.EndSession()
    '        'sessionMgr.CloseConnection()

    '    Catch ex As Exception
    '        MessageBox.Show("Error submitting Accrual Entry: " & ex.Message)
    '    Finally

    '        If waitForm IsNot Nothing Then waitForm.Close()
    '        Try
    '            If startedSession Then sessionMgr.EndSession()
    '            sessionMgr.CloseConnection()
    '        Catch
    '        End Try
    '    End Try
    'End Sub


    Private Sub SubmitAccrualEntry()
        Dim sessionMgr As New QBSessionManager
        Dim startedSession As Boolean = False
        Dim waitForm As WaitForm = Nothing
        Try
            waitForm = New WaitForm("Submitting Accrual Entry to QuickBooks... Please wait")
            waitForm.Show()
            Application.DoEvents()

            Dim selectedClient As ClientInfo = CType(comboClient.SelectedItem, ClientInfo)
            Dim qbFilePath As String = selectedClient.FilePath
            If String.IsNullOrEmpty(qbFilePath) Then
                MessageBox.Show("No QuickBooks file path found for the selected client.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
                Exit Sub
            End If

            sessionMgr.OpenConnection("", "Mooneem App")
            sessionMgr.BeginSession(qbFilePath, ENOpenMode.omDontCare)
            startedSession = True

            ' Lists to track success and duplicates
            Dim successfulDates As New List(Of Date)()
            Dim duplicateDates As New List(Of Date)()

            ' We have only one entry date (dtpDate)
            Dim txnDate As Date = dtpDate.Value

            'If IsJvExists(sessionMgr, txnDate) Then
            '    duplicateDates.Add(txnDate)
            'Else
            ' Create journal entry
            Dim msgSetRequest As IMsgSetRequest = sessionMgr.CreateMsgSetRequest("US", 12, 0)
                msgSetRequest.Attributes.OnError = ENRqOnError.roeContinue
                Dim journalEntryAdd As IJournalEntryAdd = msgSetRequest.AppendJournalEntryAddRq()

                journalEntryAdd.TxnDate.SetValue(txnDate)
                If Not String.IsNullOrWhiteSpace(txtNum.Text) Then
                    journalEntryAdd.RefNumber.SetValue(txtNum.Text.Trim())
                End If

                ' Loop rows
                For Each row As DataGridViewRow In dgvData.Rows
                    If row.IsNewRow Then Continue For
                    Dim accountName As String = row.Cells("Account").Value?.ToString().Trim()
                    If String.IsNullOrWhiteSpace(accountName) Then Continue For

                    Dim debitValue As Double
                    Dim creditValue As Double
                    Double.TryParse(row.Cells("Debit").Value?.ToString(), debitValue)
                    Double.TryParse(row.Cells("Credit").Value?.ToString(), creditValue)
                    Dim className As String = row.Cells("Division").Value?.ToString().Trim()

                    ' Debit Line
                    If debitValue <> 0 Then
                        Dim debitORLine As IORJournalLine = journalEntryAdd.ORJournalLineList.Append()
                        Dim debitLine As IJournalDebitLine = debitORLine.JournalDebitLine
                        debitLine.AccountRef.FullName.SetValue(accountName)
                        debitLine.Amount.SetValue(debitValue)
                        If Not String.IsNullOrWhiteSpace(row.Cells("Updated Payment Company").Value?.ToString()) Then
                            debitLine.EntityRef.FullName.SetValue(row.Cells("Updated Payment Company").Value.ToString())
                        End If
                        If Not String.IsNullOrWhiteSpace(row.Cells("Memo").Value?.ToString()) Then
                            debitLine.Memo.SetValue(row.Cells("Memo").Value.ToString())
                        End If
                        If Not String.IsNullOrWhiteSpace(className) Then debitLine.ClassRef.FullName.SetValue(className)
                    End If

                    ' Credit Line
                    If creditValue <> 0 Then
                        Dim creditORLine As IORJournalLine = journalEntryAdd.ORJournalLineList.Append()
                        Dim creditLine As IJournalCreditLine = creditORLine.JournalCreditLine
                        creditLine.AccountRef.FullName.SetValue(accountName)
                        creditLine.Amount.SetValue(creditValue)
                        If Not String.IsNullOrWhiteSpace(row.Cells("Updated Payment Company").Value?.ToString()) Then
                            creditLine.EntityRef.FullName.SetValue(row.Cells("Updated Payment Company").Value.ToString())
                        End If
                        If Not String.IsNullOrWhiteSpace(row.Cells("Memo").Value?.ToString()) Then
                            creditLine.Memo.SetValue(row.Cells("Memo").Value.ToString())
                        End If
                        If Not String.IsNullOrWhiteSpace(className) Then creditLine.ClassRef.FullName.SetValue(className)
                    End If
                Next

                ' Send request
                Dim responseSet As IMsgSetResponse = sessionMgr.DoRequests(msgSetRequest)
                Dim response As IResponse = responseSet.ResponseList.GetAt(0)
                If response.StatusCode = 0 Then
                    successfulDates.Add(txnDate)
                Else
                    MessageBox.Show($"❌ Failed on {txnDate.ToShortDateString()}: {response.StatusMessage}", "Error")
                End If
            'End If

            ' Summary
            Dim summary As New StringBuilder()
            If successfulDates.Count > 0 Then
                summary.AppendLine($"✅ Accrual JV Entry created successfully for {successfulDates.Count} date(s):")
                summary.AppendLine(String.Join(", ", successfulDates.Select(Function(d) d.ToShortDateString())))
            End If
            If duplicateDates.Count > 0 Then
                summary.AppendLine($"⚠ Skipped {duplicateDates.Count} date(s) as JV entry already exists:")
                summary.AppendLine(String.Join(", ", duplicateDates.Select(Function(d) d.ToShortDateString())))
            End If

            MessageBox.Show(summary.ToString(), "Accrual Entry Summary")

        Catch ex As Exception
            MessageBox.Show("Error submitting Accrual Entry: " & ex.Message)
        Finally
            If waitForm IsNot Nothing Then waitForm.Close()
            Try
                If startedSession Then sessionMgr.EndSession()
                sessionMgr.CloseConnection()
            Catch
            End Try
        End Try
    End Sub

    Private Sub SubmitPayrollEntry()
        Dim sessionMgr As New QBSessionManager
        Dim startedSession As Boolean = False
        Dim waitForm As WaitForm = Nothing
        Try

            waitForm = New WaitForm("Submitting entry to QuickBooks... Please wait")
            waitForm.Show()
            Application.DoEvents()
            Dim selectedClient As ClientInfo = CType(comboClient.SelectedItem, ClientInfo)
            Dim qbFilePath As String = selectedClient.FilePath

            If dgvData.Rows.Count = 0 Then
                MessageBox.Show("No data loaded.")
                Exit Sub
            End If

            sessionMgr.OpenConnection("", "Mooneem App")
            sessionMgr.BeginSession(qbFilePath, ENOpenMode.omDontCare)


            'sessionMgr.BeginSession("", ENOpenMode.omSingleUser)

            startedSession = True

            Dim allSuccess As Boolean = True

            ' List to keep track of all dates for which JV is created
            Dim jvDates As New List(Of Date)()
            Dim duplicateDates As New List(Of Date)()


            For c As Integer = 0 To dgvData.Columns.Count - 1
                Dim cellVal As String = dgvData.Rows(0).Cells(c).Value?.ToString().Trim()
                Dim txnDate As Date
                If Date.TryParse(cellVal, txnDate) Then

                    If IsJvExists(sessionMgr, txnDate) Then
                        ' Skip this date and mark as duplicate
                        duplicateDates.Add(txnDate)
                        Continue For
                    End If
                    jvDates.Add(txnDate) ' Add date to list

                    Dim msgSetRequest As IMsgSetRequest = sessionMgr.CreateMsgSetRequest("US", 12, 0)
                    msgSetRequest.Attributes.OnError = ENRqOnError.roeContinue
                    Dim journalEntryAdd As IJournalEntryAdd = msgSetRequest.AppendJournalEntryAddRq()

                    journalEntryAdd.TxnDate.SetValue(txnDate)
                    If Not String.IsNullOrWhiteSpace(txtNum.Text) Then
                        journalEntryAdd.RefNumber.SetValue(txtNum.Text.Trim())
                    End If

                    ' Define block columns
                    Dim colFee As Integer = c
                    Dim colTax As Integer = c + 1
                    Dim colGross As Integer = c + 2

                    ' CREDIT lines (from last row) first, because we may swap later if debit is negative
                    Dim lastRow = dgvData.Rows(dgvData.Rows.Count - 1)
                    Dim feeEnd = SafeDecimal(lastRow.Cells(colFee).Value)
                    Dim taxEnd = SafeDecimal(lastRow.Cells(colTax).Value)
                    Dim grossEnd = SafeDecimal(lastRow.Cells(colGross).Value)
                    Dim classLast As String = lastRow.Cells(1).Value?.ToString()
                    If Not String.IsNullOrWhiteSpace(classLast) Then classLast = classLast.Replace(": ", ":").Trim()

                    ' Debit lines
                    For i As Integer = 2 To dgvData.Rows.Count - 2
                        Dim row = dgvData.Rows(i)
                        Dim accountName As String = row.Cells(0).Value?.ToString()
                        Dim className As String = row.Cells(1).Value?.ToString()
                        If Not String.IsNullOrWhiteSpace(className) Then className = className.Replace(": ", ":").Trim()

                        Dim feeVal = SafeDecimal(row.Cells(colFee).Value)
                        Dim taxVal = SafeDecimal(row.Cells(colTax).Value)
                        Dim grossVal = SafeDecimal(row.Cells(colGross).Value)

                        ' --- SWAP logic for negative debits ---
                        If feeVal < 0 Then
                            Dim c1 = journalEntryAdd.ORJournalLineList.Append().JournalCreditLine
                            c1.AccountRef.FullName.SetValue("Payroll Service Fees")
                            c1.ClassRef.FullName.SetValue(className)
                            c1.Amount.SetValue(System.Math.Abs(feeVal))
                            If Not String.IsNullOrWhiteSpace(txtMemo.Text) Then c1.Memo.SetValue(txtMemo.Text.Trim())
                            If feeEnd <> 0 Then
                                Dim d1Swap = journalEntryAdd.ORJournalLineList.Append().JournalDebitLine
                                d1Swap.AccountRef.FullName.SetValue("Payroll Service Fees")
                                d1Swap.ClassRef.FullName.SetValue(classLast)
                                d1Swap.Amount.SetValue(System.Math.Abs(feeEnd))
                                If Not String.IsNullOrWhiteSpace(txtMemo.Text) Then d1Swap.Memo.SetValue(txtMemo.Text.Trim())
                                feeEnd = 0
                            End If
                        ElseIf feeVal <> 0 Then
                            Dim d1 = journalEntryAdd.ORJournalLineList.Append().JournalDebitLine
                            d1.AccountRef.FullName.SetValue("Payroll Service Fees")
                            d1.ClassRef.FullName.SetValue(className)
                            d1.Amount.SetValue(feeVal)
                            If Not String.IsNullOrWhiteSpace(txtMemo.Text) Then d1.Memo.SetValue(txtMemo.Text.Trim())
                        End If

                        'If taxVal < 0 Then
                        '    Dim c2 = journalEntryAdd.ORJournalLineList.Append().JournalCreditLine
                        '    c2.AccountRef.FullName.SetValue("Payroll Taxes")
                        '    c2.ClassRef.FullName.SetValue(className)
                        '    c2.Amount.SetValue(System.Math.Abs(taxVal))
                        '    If Not String.IsNullOrWhiteSpace(txtMemo.Text) Then c2.Memo.SetValue(txtMemo.Text.Trim())
                        '    If taxEnd <> 0 Then
                        '        Dim d2Swap = journalEntryAdd.ORJournalLineList.Append().JournalDebitLine
                        '        d2Swap.AccountRef.FullName.SetValue("Payroll Taxes")
                        '        d2Swap.ClassRef.FullName.SetValue(classLast)
                        '        d2Swap.Amount.SetValue(System.Math.Abs(taxEnd))
                        '        If Not String.IsNullOrWhiteSpace(txtMemo.Text) Then d2Swap.Memo.SetValue(txtMemo.Text.Trim())
                        '        taxEnd = 0
                        '    End If
                        'If taxVal < 0 Then
                        '    Dim c2 = journalEntryAdd.ORJournalLineList.Append().JournalCreditLine
                        '    c2.AccountRef.FullName.SetValue("Payroll Expenses:Payroll Taxes") ' 🔹 updated
                        '    c2.ClassRef.FullName.SetValue(className)
                        '    c2.Amount.SetValue(System.Math.Abs(taxVal))
                        '    If Not String.IsNullOrWhiteSpace(txtMemo.Text) Then c2.Memo.SetValue(txtMemo.Text.Trim())
                        '    If taxEnd <> 0 Then
                        '        Dim d2Swap = journalEntryAdd.ORJournalLineList.Append().JournalDebitLine
                        '        d2Swap.AccountRef.FullName.SetValue("Payroll Expenses:Payroll Taxes") ' 🔹 updated
                        '        d2Swap.ClassRef.FullName.SetValue(classLast)
                        '        d2Swap.Amount.SetValue(System.Math.Abs(taxEnd))
                        '        If Not String.IsNullOrWhiteSpace(txtMemo.Text) Then d2Swap.Memo.SetValue(txtMemo.Text.Trim())
                        '        taxEnd = 0
                        '    End If
                        'ElseIf taxVal <> 0 Then
                        '    Dim d2 = journalEntryAdd.ORJournalLineList.Append().JournalDebitLine
                        '    d2.AccountRef.FullName.SetValue("Payroll Taxes")
                        '    d2.ClassRef.FullName.SetValue(className)
                        '    d2.Amount.SetValue(taxVal)
                        '    If Not String.IsNullOrWhiteSpace(txtMemo.Text) Then d2.Memo.SetValue(txtMemo.Text.Trim())
                        'End If
                        If taxVal < 0 Then
                            Dim c2 = journalEntryAdd.ORJournalLineList.Append().JournalCreditLine
                            c2.AccountRef.FullName.SetValue("Payroll Expenses:Payroll Taxes") ' 🔹 updated
                            c2.ClassRef.FullName.SetValue(className)
                            c2.Amount.SetValue(System.Math.Abs(taxVal))
                            If Not String.IsNullOrWhiteSpace(txtMemo.Text) Then c2.Memo.SetValue(txtMemo.Text.Trim())
                            If taxEnd <> 0 Then
                                Dim d2Swap = journalEntryAdd.ORJournalLineList.Append().JournalDebitLine
                                d2Swap.AccountRef.FullName.SetValue("Payroll Expenses:Payroll Taxes") ' 🔹 updated
                                d2Swap.ClassRef.FullName.SetValue(classLast)
                                d2Swap.Amount.SetValue(System.Math.Abs(taxEnd))
                                If Not String.IsNullOrWhiteSpace(txtMemo.Text) Then d2Swap.Memo.SetValue(txtMemo.Text.Trim())
                                taxEnd = 0
                            End If
                        ElseIf taxVal <> 0 Then
                            Dim d2 = journalEntryAdd.ORJournalLineList.Append().JournalDebitLine
                            d2.AccountRef.FullName.SetValue("Payroll Expenses:Payroll Taxes") ' 🔹 updated here too
                            d2.ClassRef.FullName.SetValue(className)
                            d2.Amount.SetValue(taxVal)
                            If Not String.IsNullOrWhiteSpace(txtMemo.Text) Then d2.Memo.SetValue(txtMemo.Text.Trim())
                        End If
                        If grossVal < 0 Then
                            Dim c3 = journalEntryAdd.ORJournalLineList.Append().JournalCreditLine
                            c3.AccountRef.FullName.SetValue(accountName)
                            c3.ClassRef.FullName.SetValue(className)
                            c3.Amount.SetValue(System.Math.Abs(grossVal))
                            If Not String.IsNullOrWhiteSpace(txtMemo.Text) Then c3.Memo.SetValue(txtMemo.Text.Trim())
                            If grossEnd <> 0 Then
                                Dim d3Swap = journalEntryAdd.ORJournalLineList.Append().JournalDebitLine
                                d3Swap.AccountRef.FullName.SetValue("Payroll Accrual")
                                d3Swap.ClassRef.FullName.SetValue(classLast)
                                d3Swap.Amount.SetValue(System.Math.Abs(grossEnd))
                                If Not String.IsNullOrWhiteSpace(txtMemo.Text) Then d3Swap.Memo.SetValue(txtMemo.Text.Trim())
                                grossEnd = 0
                            End If
                        ElseIf grossVal <> 0 Then
                            Dim d3 = journalEntryAdd.ORJournalLineList.Append().JournalDebitLine
                            d3.AccountRef.FullName.SetValue(accountName)
                            d3.ClassRef.FullName.SetValue(className)
                            d3.Amount.SetValue(grossVal)
                            If Not String.IsNullOrWhiteSpace(txtMemo.Text) Then d3.Memo.SetValue(txtMemo.Text.Trim())
                        End If
                    Next

                    ' Remaining CREDIT lines for last row if not swapped
                    If feeEnd <> 0 Then
                        Dim c1 = journalEntryAdd.ORJournalLineList.Append().JournalCreditLine
                        c1.AccountRef.FullName.SetValue("Payroll Accrual")
                        c1.ClassRef.FullName.SetValue(classLast)
                        c1.Amount.SetValue(feeEnd)
                        If Not String.IsNullOrWhiteSpace(txtMemo.Text) Then c1.Memo.SetValue(txtMemo.Text.Trim())
                    End If
                    'If taxEnd <> 0 Then
                    '    Dim c2 = journalEntryAdd.ORJournalLineList.Append().JournalCreditLine
                    '    c2.AccountRef.FullName.SetValue("Payroll Taxes")
                    '    c2.ClassRef.FullName.SetValue(classLast)
                    '    c2.Amount.SetValue(taxEnd)
                    '    If Not String.IsNullOrWhiteSpace(txtMemo.Text) Then c2.Memo.SetValue(txtMemo.Text.Trim())
                    'End If
                    If taxEnd <> 0 Then
                        Dim c2 = journalEntryAdd.ORJournalLineList.Append().JournalCreditLine
                        c2.AccountRef.FullName.SetValue("Payroll Accrual") ' 🔹 updated
                        c2.ClassRef.FullName.SetValue(classLast)
                        c2.Amount.SetValue(taxEnd)
                        If Not String.IsNullOrWhiteSpace(txtMemo.Text) Then c2.Memo.SetValue(txtMemo.Text.Trim())
                    End If
                    If grossEnd <> 0 Then
                        Dim c3 = journalEntryAdd.ORJournalLineList.Append().JournalCreditLine
                        c3.AccountRef.FullName.SetValue("Payroll Accrual")
                        c3.ClassRef.FullName.SetValue(classLast)
                        c3.Amount.SetValue(grossEnd)
                        If Not String.IsNullOrWhiteSpace(txtMemo.Text) Then c3.Memo.SetValue(txtMemo.Text.Trim())
                    End If

                    ' Send
                    Dim resSet As IMsgSetResponse = sessionMgr.DoRequests(msgSetRequest)
                    Dim response As IResponse = resSet.ResponseList.GetAt(0)
                    If response.StatusCode <> 0 Then
                        allSuccess = False
                        MessageBox.Show($"Error on {txnDate.ToShortDateString()}: {response.StatusMessage}")
                    End If
                End If
            Next

            ' Show summary with count and dates
            'If jvDates.Count > 0 Then
            '    Dim summary As String = $"✅ Payroll JV Entries submitted successfully for {jvDates.Count} date(s):{Environment.NewLine}"
            '    summary &= String.Join(", ", jvDates.Select(Function(d) d.ToShortDateString()))
            '    MessageBox.Show(summary)
            'ElseIf Not allSuccess Then
            '    MessageBox.Show("⚠ Payroll submission completed with errors.")
            'End If
            ' Show summary
            Dim summary As New StringBuilder()
            If jvDates.Count > 0 Then
                summary.AppendLine($"✅ Payroll JV Entries submitted successfully for {jvDates.Count} date(s):")
                summary.AppendLine(String.Join(", ", jvDates.Select(Function(d) d.ToShortDateString())))
            End If
            If duplicateDates.Count > 0 Then
                summary.AppendLine($"⚠ Skipped {duplicateDates.Count} date(s) as JV entry already exists in QuickBooks:")
                summary.AppendLine(String.Join(", ", duplicateDates.Select(Function(d) d.ToShortDateString())))
            End If

            If summary.Length > 0 Then
                MessageBox.Show(summary.ToString(), "Payroll Submission Summary")
            Else
                MessageBox.Show("⚠ Payroll submission completed with errors or no data.", "Payroll Submission Summary")
            End If

        Catch ex As Exception
            MessageBox.Show("Payroll Submit Error: " & ex.Message)
        Finally
            If waitForm IsNot Nothing Then waitForm.Close()
            Try
                If startedSession Then sessionMgr.EndSession()
                sessionMgr.CloseConnection()
            Catch
            End Try
        End Try
    End Sub

    ' Function to check if a JV already exists in QB for a given date


    Private Function IsJvExists(sessionMgr As QBSessionManager, txnDate As Date) As Boolean
        Dim request As IMsgSetRequest = sessionMgr.CreateMsgSetRequest("US", 12, 0)
        request.Attributes.OnError = ENRqOnError.roeContinue

        Dim query As IJournalEntryQuery = request.AppendJournalEntryQueryRq()

        ' Use ORTxnQuery with TxnFilter and DateRangeFilter
        query.ORTxnQuery.TxnFilter.ORDateRangeFilter.TxnDateRangeFilter.ORTxnDateRangeFilter.TxnDateFilter.FromTxnDate.SetValue(txnDate)
        query.ORTxnQuery.TxnFilter.ORDateRangeFilter.TxnDateRangeFilter.ORTxnDateRangeFilter.TxnDateFilter.ToTxnDate.SetValue(txnDate)

        Dim responseSet As IMsgSetResponse = sessionMgr.DoRequests(request)
        Dim response As IResponse = responseSet.ResponseList.GetAt(0)

        ' Check if Detail exists and has any items
        If response.Detail IsNot Nothing Then
            Return response.Detail.Count > 0
        Else
            Return False
        End If
    End Function


    'Private Sub SubmitPayrollEntry()
    '    Dim sessionMgr As New QBSessionManager
    '    Dim startedSession As Boolean = False
    '    Dim waitForm As WaitForm = Nothing
    '    Try

    '        waitForm = New WaitForm("Submitting entry to QuickBooks... Please wait")
    '        waitForm.Show()
    '        Application.DoEvents()
    '        Dim selectedClient As ClientInfo = CType(comboClient.SelectedItem, ClientInfo)
    '        Dim qbFilePath As String = selectedClient.FilePath

    '        If dgvData.Rows.Count = 0 Then
    '            MessageBox.Show("No data loaded.")
    '            Exit Sub
    '        End If

    '        sessionMgr.OpenConnection("", "Mooneem App")
    '        sessionMgr.BeginSession(qbFilePath, ENOpenMode.omDontCare)
    '        startedSession = True

    '        Dim allSuccess As Boolean = True

    '        For c As Integer = 0 To dgvData.Columns.Count - 1
    '            Dim cellVal As String = dgvData.Rows(0).Cells(c).Value?.ToString().Trim()
    '            Dim txnDate As Date
    '            If Date.TryParse(cellVal, txnDate) Then

    '                Dim msgSetRequest As IMsgSetRequest = sessionMgr.CreateMsgSetRequest("US", 12, 0)
    '                msgSetRequest.Attributes.OnError = ENRqOnError.roeContinue
    '                Dim journalEntryAdd As IJournalEntryAdd = msgSetRequest.AppendJournalEntryAddRq()

    '                journalEntryAdd.TxnDate.SetValue(txnDate)
    '                If Not String.IsNullOrWhiteSpace(txtNum.Text) Then
    '                    journalEntryAdd.RefNumber.SetValue(txtNum.Text.Trim())
    '                End If

    '                ' Define block columns
    '                Dim colFee As Integer = c
    '                Dim colTax As Integer = c + 1
    '                Dim colGross As Integer = c + 2

    '                ' CREDIT lines (from last row) first, because we may swap later if debit is negative
    '                Dim lastRow = dgvData.Rows(dgvData.Rows.Count - 1)
    '                Dim feeEnd = SafeDecimal(lastRow.Cells(colFee).Value)
    '                Dim taxEnd = SafeDecimal(lastRow.Cells(colTax).Value)
    '                Dim grossEnd = SafeDecimal(lastRow.Cells(colGross).Value)
    '                Dim classLast As String = lastRow.Cells(1).Value?.ToString()
    '                If Not String.IsNullOrWhiteSpace(classLast) Then classLast = classLast.Replace(": ", ":").Trim()

    '                ' Debit lines
    '                For i As Integer = 2 To dgvData.Rows.Count - 2
    '                    Dim row = dgvData.Rows(i)
    '                    Dim accountName As String = row.Cells(0).Value?.ToString()
    '                    Dim className As String = row.Cells(1).Value?.ToString()
    '                    If Not String.IsNullOrWhiteSpace(className) Then className = className.Replace(": ", ":").Trim()

    '                    Dim feeVal = SafeDecimal(row.Cells(colFee).Value)
    '                    Dim taxVal = SafeDecimal(row.Cells(colTax).Value)
    '                    Dim grossVal = SafeDecimal(row.Cells(colGross).Value)

    '                    ' --- SWAP logic for negative debits ---
    '                    If feeVal < 0 Then
    '                        ' move debit to credit and use positive value
    '                        Dim c1 = journalEntryAdd.ORJournalLineList.Append().JournalCreditLine
    '                        c1.AccountRef.FullName.SetValue("Payroll Service Fees")
    '                        c1.ClassRef.FullName.SetValue(className)
    '                        c1.Amount.SetValue(System.Math.Abs(feeVal))
    '                        If Not String.IsNullOrWhiteSpace(txtMemo.Text) Then c1.Memo.SetValue(txtMemo.Text.Trim())

    '                        ' swap the original credit
    '                        If feeEnd <> 0 Then
    '                            Dim d1Swap = journalEntryAdd.ORJournalLineList.Append().JournalDebitLine
    '                            d1Swap.AccountRef.FullName.SetValue("Payroll Service Fees")
    '                            d1Swap.ClassRef.FullName.SetValue(classLast)
    '                            d1Swap.Amount.SetValue(System.Math.Abs(feeEnd))
    '                            If Not String.IsNullOrWhiteSpace(txtMemo.Text) Then d1Swap.Memo.SetValue(txtMemo.Text.Trim())
    '                            feeEnd = 0 ' prevent double posting
    '                        End If
    '                    ElseIf feeVal <> 0 Then
    '                        Dim d1 = journalEntryAdd.ORJournalLineList.Append().JournalDebitLine
    '                        d1.AccountRef.FullName.SetValue("Payroll Service Fees")
    '                        d1.ClassRef.FullName.SetValue(className)
    '                        d1.Amount.SetValue(feeVal)
    '                        If Not String.IsNullOrWhiteSpace(txtMemo.Text) Then d1.Memo.SetValue(txtMemo.Text.Trim())
    '                    End If

    '                    If taxVal < 0 Then
    '                        Dim c2 = journalEntryAdd.ORJournalLineList.Append().JournalCreditLine
    '                        c2.AccountRef.FullName.SetValue("Payroll Taxes")
    '                        c2.ClassRef.FullName.SetValue(className)
    '                        c2.Amount.SetValue(System.Math.Abs(taxVal))
    '                        If Not String.IsNullOrWhiteSpace(txtMemo.Text) Then c2.Memo.SetValue(txtMemo.Text.Trim())
    '                        If taxEnd <> 0 Then
    '                            Dim d2Swap = journalEntryAdd.ORJournalLineList.Append().JournalDebitLine
    '                            d2Swap.AccountRef.FullName.SetValue("Payroll Taxes")
    '                            d2Swap.ClassRef.FullName.SetValue(classLast)
    '                            d2Swap.Amount.SetValue(System.Math.Abs(taxEnd))
    '                            If Not String.IsNullOrWhiteSpace(txtMemo.Text) Then d2Swap.Memo.SetValue(txtMemo.Text.Trim())
    '                            taxEnd = 0
    '                        End If
    '                    ElseIf taxVal <> 0 Then
    '                        Dim d2 = journalEntryAdd.ORJournalLineList.Append().JournalDebitLine
    '                        d2.AccountRef.FullName.SetValue("Payroll Taxes")
    '                        d2.ClassRef.FullName.SetValue(className)
    '                        d2.Amount.SetValue(taxVal)
    '                        If Not String.IsNullOrWhiteSpace(txtMemo.Text) Then d2.Memo.SetValue(txtMemo.Text.Trim())
    '                    End If

    '                    If grossVal < 0 Then
    '                        Dim c3 = journalEntryAdd.ORJournalLineList.Append().JournalCreditLine
    '                        c3.AccountRef.FullName.SetValue(accountName)
    '                        c3.ClassRef.FullName.SetValue(className)
    '                        c3.Amount.SetValue(System.Math.Abs(grossVal))
    '                        If Not String.IsNullOrWhiteSpace(txtMemo.Text) Then c3.Memo.SetValue(txtMemo.Text.Trim())
    '                        If grossEnd <> 0 Then
    '                            Dim d3Swap = journalEntryAdd.ORJournalLineList.Append().JournalDebitLine
    '                            d3Swap.AccountRef.FullName.SetValue("Payroll Accrual")
    '                            d3Swap.ClassRef.FullName.SetValue(classLast)
    '                            d3Swap.Amount.SetValue(System.Math.Abs(grossEnd))
    '                            If Not String.IsNullOrWhiteSpace(txtMemo.Text) Then d3Swap.Memo.SetValue(txtMemo.Text.Trim())
    '                            grossEnd = 0
    '                        End If
    '                    ElseIf grossVal <> 0 Then
    '                        Dim d3 = journalEntryAdd.ORJournalLineList.Append().JournalDebitLine
    '                        d3.AccountRef.FullName.SetValue(accountName)
    '                        d3.ClassRef.FullName.SetValue(className)
    '                        d3.Amount.SetValue(grossVal)
    '                        If Not String.IsNullOrWhiteSpace(txtMemo.Text) Then d3.Memo.SetValue(txtMemo.Text.Trim())
    '                    End If
    '                Next

    '                ' Remaining CREDIT lines for last row if not swapped
    '                If feeEnd <> 0 Then
    '                    Dim c1 = journalEntryAdd.ORJournalLineList.Append().JournalCreditLine
    '                    c1.AccountRef.FullName.SetValue("Payroll Service Fees")
    '                    c1.ClassRef.FullName.SetValue(classLast)
    '                    c1.Amount.SetValue(feeEnd)
    '                    If Not String.IsNullOrWhiteSpace(txtMemo.Text) Then c1.Memo.SetValue(txtMemo.Text.Trim())
    '                End If
    '                If taxEnd <> 0 Then
    '                    Dim c2 = journalEntryAdd.ORJournalLineList.Append().JournalCreditLine
    '                    c2.AccountRef.FullName.SetValue("Payroll Taxes")
    '                    c2.ClassRef.FullName.SetValue(classLast)
    '                    c2.Amount.SetValue(taxEnd)
    '                    If Not String.IsNullOrWhiteSpace(txtMemo.Text) Then c2.Memo.SetValue(txtMemo.Text.Trim())
    '                End If
    '                If grossEnd <> 0 Then
    '                    Dim c3 = journalEntryAdd.ORJournalLineList.Append().JournalCreditLine
    '                    c3.AccountRef.FullName.SetValue("Payroll Accrual")
    '                    c3.ClassRef.FullName.SetValue(classLast)
    '                    c3.Amount.SetValue(grossEnd)
    '                    If Not String.IsNullOrWhiteSpace(txtMemo.Text) Then c3.Memo.SetValue(txtMemo.Text.Trim())
    '                End If

    '                ' Send
    '                Dim resSet As IMsgSetResponse = sessionMgr.DoRequests(msgSetRequest)
    '                Dim response As IResponse = resSet.ResponseList.GetAt(0)
    '                If response.StatusCode <> 0 Then
    '                    allSuccess = False
    '                    MessageBox.Show($"Error on {txnDate.ToShortDateString()}: {response.StatusMessage}")
    '                End If
    '            End If
    '        Next

    '        If allSuccess Then
    '            MessageBox.Show("✅ Payroll JV Entries submitted successfully.")
    '        Else
    '            MessageBox.Show("⚠ Payroll submission completed with errors.")
    '        End If

    '    Catch ex As Exception
    '        MessageBox.Show("Payroll Submit Error: " & ex.Message)
    '    Finally
    '        If waitForm IsNot Nothing Then waitForm.Close()
    '        Try
    '            If startedSession Then sessionMgr.EndSession()
    '            sessionMgr.CloseConnection()
    '        Catch
    '        End Try
    '    End Try
    'End Sub





    Private Function SafeDecimal(val As Object) As Decimal
        If IsDBNull(val) OrElse val Is Nothing Then Return 0D
        Dim s As String = val.ToString().Trim()
        If String.IsNullOrWhiteSpace(s) Then Return 0D

        ' Handle accounting format like (245.00)
        If s.StartsWith("(") AndAlso s.EndsWith(")") Then
            ' Remove ( and )
            s = s.Trim("("c, ")"c)
            ' Only prepend negative if it doesn't already start with "-"
            If Not s.StartsWith("-") Then
                s = "-" & s
            End If
        End If

        Dim result As Decimal = 0D
        Decimal.TryParse(s, result)
        Return result
    End Function





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
End Class
