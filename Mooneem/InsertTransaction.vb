
Imports System.Windows.Forms
Imports System.Drawing
Imports System.Runtime.InteropServices
Imports QBFC12Lib

Public Class InsertTransaction
    Inherits UserControl

    Private btnSelectFile As Button
    Private btnForWise As Button
    Private btnWiseToEmp As Button
    Private dgvTransactions As DataGridView
    Private layout As TableLayoutPanel
    Private topPanel As Panel
    Private buttonPanel As FlowLayoutPanel
    Private pnlOverlay As Panel
    Private lblStatus As Label
    Public Sub New()
        InitializeComponent()
        BuildUI()
    End Sub

    Private Sub BuildUI()
        Me.Dock = DockStyle.Fill
        Me.BackColor = Color.White
        Me.Font = New Font("Verdana", 11, FontStyle.Regular)

        ' ==== Layout ====
        layout = New TableLayoutPanel With {
            .Dock = DockStyle.Fill,
            .RowCount = 2,
            .ColumnCount = 1
        }
        layout.RowStyles.Add(New RowStyle(SizeType.Absolute, 90))
        layout.RowStyles.Add(New RowStyle(SizeType.Percent, 100))
        layout.ColumnStyles.Add(New ColumnStyle(SizeType.Percent, 100))
        Me.Controls.Add(layout)

        ' ==== Top Panel ====
        topPanel = New Panel With {.Dock = DockStyle.Fill, .Padding = New Padding(10)}
        layout.Controls.Add(topPanel, 0, 0)

        ' ==== Buttons Panel ====
        buttonPanel = New FlowLayoutPanel With {
            .FlowDirection = FlowDirection.LeftToRight,
            .Dock = DockStyle.Left,
            .AutoSize = True,
            .Padding = New Padding(0),
            .WrapContents = False
        }

        btnForWise = New Button With {
            .Text = "Check Entry (1558 to wise)",
            .Height = 35,
            .Width = 250,
            .Margin = New Padding(10)
        }

        btnWiseToEmp = New Button With {
            .Text = "Check Entry (wise to employee)",
            .Height = 35,
            .Width = 270,
            .Margin = New Padding(10)
        }

        buttonPanel.Controls.Add(btnForWise)
        buttonPanel.Controls.Add(btnWiseToEmp)
        topPanel.Controls.Add(buttonPanel)

        ' ==== Select File Button ====
        btnSelectFile = New Button With {
            .Text = "Select Excel File",
            .Anchor = AnchorStyles.Top Or AnchorStyles.Right,
            .Height = 35,
            .Width = 150,
            .Location = New Point(topPanel.Width - 170, 10)
        }
        AddHandler topPanel.Resize, Sub(sender, e)
                                        btnSelectFile.Location = New Point(topPanel.Width - btnSelectFile.Width - 20, 10)
                                    End Sub
        topPanel.Controls.Add(btnSelectFile)

        ' ==== DataGridView ====
        dgvTransactions = New DataGridView With {
            .Dock = DockStyle.Fill,
            .BackgroundColor = Color.White,
            .AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.Fill
        }
        layout.Controls.Add(dgvTransactions, 0, 1)

        pnlOverlay = New Panel With {
           .Size = New Size(400, 50),
           .BackColor = Color.FromArgb(20, 0, 150, 136),
           .Visible = False,
           .Anchor = AnchorStyles.None
       }

        ' Center it manually
        AddHandler Me.Resize, Sub(sender, e)
                                  pnlOverlay.Location = New Point(
            (Me.ClientSize.Width - pnlOverlay.Width) \ 2,
            (Me.ClientSize.Height - pnlOverlay.Height) \ 2
        )
                              End Sub
        lblStatus = New Label With {
            .Dock = DockStyle.Fill,
            .Font = New Font("Verdana Semibold", 11, FontStyle.Bold),
            .ForeColor = Color.Black,
            .BackColor = Color.Transparent,
            .TextAlign = ContentAlignment.MiddleCenter
        }
        ' Position in center of form
        pnlOverlay.Location = New Point(400, 340)
        AddHandler btnSelectFile.Click, AddressOf SelectExcelFile
        AddHandler btnForWise.Click, AddressOf CheckEntry1558ToWise
        AddHandler btnWiseToEmp.Click, AddressOf CheckEntryWiseToEmployee

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


    Private Sub SelectExcelFile(sender As Object, e As EventArgs)
        Dim openFileDialog As New OpenFileDialog()
        openFileDialog.Filter = "Excel Files (.xls; *.xlsx)|.xls;*.xlsx"

        If openFileDialog.ShowDialog() = DialogResult.OK Then
            LoadExcelDataLateBinding(openFileDialog.FileName)
        End If
    End Sub

    Private Function ValidatePayee(payeeName As String) As Boolean
        ' Check if payee name is not empty and has at least one word
        If String.IsNullOrWhiteSpace(payeeName) Then
            Return False
        End If

        ' Split the name into individual words based on spaces
        Dim words As String() = payeeName.Trim().Split(New Char() {" "c}, StringSplitOptions.RemoveEmptyEntries)

        ' Ensure the payee name contains at least one word (in case of multi-word names like 'Cristiano Ronaldo')
        If words.Length < 1 Then
            Return False
        End If

        ' Ensure the payee name has at least a first name (if needed, e.g., 'Ronaldo' should work)
        If words.Length >= 1 Then
            Return True
        End If

        ' If the name doesn't meet the criteria, return False
        Return False
    End Function

    Private Sub AddPayee(sessionManager As QBSessionManager, payee As String)
        Try
            ' Create a request to add the payee as a vendor
            Dim msgSetRequest = sessionManager.CreateMsgSetRequest("US", 4, 0)
            Dim vendorAdd = msgSetRequest.AppendVendorAddRq()
            vendorAdd.Name.SetValue(payee)

            ' Send the request
            Dim msgSetResponse = sessionManager.DoRequests(msgSetRequest)
            Dim response = msgSetResponse.ResponseList.GetAt(0)

            If response.StatusCode <> 0 Then
                Throw New Exception($"Failed to create payee '{payee}': {response.StatusMessage}")
            End If
        Catch ex As Exception
            ' Handle exceptions or log them if needed
            MessageBox.Show($"Error creating payee: {ex.Message}", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
            Throw
        End Try
    End Sub

    Private Function PayeeExists(sessionManager As QBSessionManager, payee As String) As Boolean
        Try
            ' Create a request to query the payee list
            Dim msgSetRequest = sessionManager.CreateMsgSetRequest("US", 4, 0)
            Dim listQuery = msgSetRequest.AppendVendorQueryRq()
            listQuery.ORVendorListQuery.FullNameList.Add(payee)

            ' Execute the request
            Dim msgSetResponse = sessionManager.DoRequests(msgSetRequest)
            Dim response = msgSetResponse.ResponseList.GetAt(0)

            ' Check if any vendor matches the payee name
            If response.StatusCode = 0 AndAlso response.Detail IsNot Nothing Then
                Dim vendorRetList = CType(response.Detail, IVendorRetList)
                Return vendorRetList.Count > 0
            End If
        Catch ex As Exception
            ' Handle exceptions or log them if needed
            MessageBox.Show($"Error checking payee existence: {ex.Message}", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try

        Return False
    End Function

    Private Sub CreateBankEntry_1558_to_Wise(bankAccount As String, payee As String, checkDate As String, checkAmount As Decimal, memo As String, SendMoney_Amount As Decimal, SendMoney_memo As String) 'classEntry As String
        Dim sessionManager As QBSessionManager = Nothing
        Dim msgSetRequest As IMsgSetRequest = Nothing

        Try
            ' Initialize QuickBooks session manager
            sessionManager = New QBSessionManager()
            sessionManager.OpenConnection("", "Mooneem App")
            sessionManager.BeginSession("", ENOpenMode.omDontCare)

            ' Create a new message set request
            msgSetRequest = sessionManager.CreateMsgSetRequest("US", 8, 0)
            Dim txnAddRq = msgSetRequest.AppendCheckAddRq()
            'If Not PayeeExists(sessionManager, payee) Then
            'AddPayee(sessionManager, payee)
            'End If

            ' Set values
            txnAddRq.AccountRef.FullName.SetValue("Bank of America Checking-1558")
            txnAddRq.RefNumber.SetValue("")
            'txnAddRq.AccountRef.FullName.SetValue("10100 · Checking")
            txnAddRq.PayeeEntityRef.FullName.SetValue("Wise Inc")
            'txnAddRq.PayeeEntityRef.FullName.SetValue("CalOil Company")
            txnAddRq.TxnDate.SetValue(Date.Parse(checkDate))
            txnAddRq.Memo.SetValue(memo)

            ' Add expense line
            Dim expenseLine = txnAddRq.ExpenseLineAddList.Append()
            expenseLine.AccountRef.FullName.SetValue("Wise")
            'expenseLine.AccountRef.FullName.SetValue("60110 · Fuel")
            expenseLine.Amount.SetValue(SendMoney_Amount)
            'expenseLine.Memo.SetValue(SendMoney_memo)
            'expenseLine.ClassRef.FullName.SetValue(classEntry)

            Dim expenseLine1 = txnAddRq.ExpenseLineAddList.Append()
            expenseLine1.AccountRef.FullName.SetValue("Wise fees")
            'expenseLine1.AccountRef.FullName.SetValue("60120 · Insurance")
            expenseLine1.Amount.SetValue(checkAmount)
            'expenseLine1.Memo.SetValue(memo)

            ' Send the request
            Dim msgSetResponse = sessionManager.DoRequests(msgSetRequest)
            Dim response = msgSetResponse.ResponseList.GetAt(0)

            If response.StatusCode <> 0 Then
                Throw New Exception(response.StatusMessage)
            End If

        Catch ex As Exception
            MessageBox.Show($"An error occurred: {ex.Message}", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        Finally
            If sessionManager IsNot Nothing Then
                sessionManager.EndSession()
                sessionManager.CloseConnection()
            End If
        End Try
    End Sub

    Private Sub LoadExcelDataLateBinding(filePath As String)
        Dim xlApp As Object = Nothing
        Dim xlWorkbook As Object = Nothing
        Dim xlWorksheet As Object = Nothing
        Dim xlRange As Object = Nothing

        Try
            Cursor.Current = Cursors.WaitCursor
            ShowStatus("Please wait...")

            ' Validate the file path
            If String.IsNullOrEmpty(filePath) OrElse Not IO.File.Exists(filePath) Then
                Throw New Exception("Invalid file path. Please select a valid Excel file.")
            End If

            ' Initialize Excel Application (Late Binding)
            xlApp = CreateObject("Excel.Application")
            If xlApp Is Nothing Then
                Throw New Exception("Failed to initialize Excel Application. Ensure Office is installed.")
            End If

            ' Open the Excel workbook
            xlWorkbook = xlApp.Workbooks.Open(filePath)
            If xlWorkbook Is Nothing Then
                Throw New Exception($"Failed to open the Excel file at: {filePath}. Ensure the file exists and is not in use.")
            End If

            ' Check for sheets and load the last sheet
            Dim sheetCount As Integer = xlWorkbook.Sheets.Count
            If sheetCount < 1 Then
                Throw New Exception("The workbook contains no sheets.")
            End If

            xlWorksheet = xlWorkbook.Sheets(sheetCount)
            xlRange = xlWorksheet.UsedRange

            ' Create a DataTable to hold the data
            Dim dataTable As New System.Data.DataTable()

            ' Add columns to the DataTable
            Dim columnCount As Integer = xlRange.Columns.Count
            For col As Integer = 1 To columnCount
                Dim header As Object = xlRange.Cells(1, col).Value
                dataTable.Columns.Add(If(header IsNot Nothing, header.ToString(), $"Column{col}"))
            Next

            ' Add rows to the DataTable
            Dim rowCount As Integer = xlRange.Rows.Count
            For row As Integer = 2 To rowCount
                Dim isEmpty As Boolean = True
                For col As Integer = 1 To columnCount
                    If xlRange.Cells(row, col).Value IsNot Nothing Then
                        isEmpty = False
                    End If
                Next

                If Not isEmpty Then
                    Dim dataRow As DataRow = dataTable.NewRow()
                    For col As Integer = 1 To columnCount
                        Dim cellValue As Object = xlRange.Cells(row, col).Value
                        dataRow(col - 1) = If(cellValue IsNot Nothing, cellValue.ToString(), String.Empty)
                    Next
                    dataTable.Rows.Add(dataRow)
                End If
            Next

            ' Bind the DataTable to the DataGridView
            dgvTransactions.DataSource = dataTable

            ' Add a checkbox column to the DataGridView
            Dim checkboxColumn As New DataGridViewCheckBoxColumn() With {
            .HeaderText = "Select",
            .Name = "chkSelect",
            .Width = 50,
            .TrueValue = True,
            .FalseValue = False
        }
            dgvTransactions.Columns.Insert(0, checkboxColumn)

            ' Set default checkbox values
            For Each row As DataGridViewRow In dgvTransactions.Rows
                row.Cells("chkSelect").Value = True
            Next

            ' Adjust the DataGridView width
            dgvTransactions.Width = Me.ClientSize.Width

            MessageBox.Show($"Data loaded successfully from sheet: '{xlWorksheet.Name}'!", "Excel Data Loaded", MessageBoxButtons.OK, MessageBoxIcon.Information)

        Catch ex As Exception
            MessageBox.Show($"Error loading Excel file: {ex.Message}", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        Finally
            HideStatus()
            Cursor.Current = Cursors.Default

            ' Release Excel COM objects
            If xlRange IsNot Nothing Then Marshal.ReleaseComObject(xlRange)
            If xlWorksheet IsNot Nothing Then Marshal.ReleaseComObject(xlWorksheet)
            If xlWorkbook IsNot Nothing Then xlWorkbook.Close(False)
            If xlApp IsNot Nothing Then xlApp.Quit()
            If xlWorkbook IsNot Nothing Then Marshal.ReleaseComObject(xlWorkbook)
            If xlApp IsNot Nothing Then Marshal.ReleaseComObject(xlApp)
        End Try
    End Sub

    Private Sub CheckEntry1558ToWise(sender As Object, e As EventArgs)
        Dim processingForm As New ProcessingForm()

        Try
            ' Show the processing form modelessly
            processingForm.Show()
            processingForm.Refresh()

            ' Perform the transaction processing
            Dim successCount As Integer = 0
            Dim failedTransactions As New List(Of String)

            ' Initialize Wise Charges amount
            Dim sendMoneytoamount As Decimal = 0
            Dim sendMoneyto_memo As String = ""

            ' Dictionary to store transactions by their unique ID
            Dim transactionsById As New Dictionary(Of String, (Decimal, String, String, String, String, Decimal)) ' Key: ID, Value: (Amount, Currency, PayeeName, Date, Description, Wise Charges Amount)

            ' Group transactions by ID
            For Each row As DataGridViewRow In dgvTransactions.Rows
                If Convert.ToBoolean(row.Cells("chkSelect").Value) And row.Cells("ID").Value <> "" Then
                    Dim id As String = row.Cells("ID").Value.ToString()
                    Dim description As String = row.Cells("Description").Value.ToString()
                    Dim dateColumn As String = row.Cells("Date").Value.ToString()
                    Dim amount As Decimal = Math.Abs(Convert.ToDecimal(row.Cells("Amount").Value))
                    Dim currency As String = row.Cells("Currency").Value.ToString()
                    Dim payeeName As String = row.Cells("Payee Name").Value.ToString().Trim()

                    ' Validate payee
                    If Not ValidatePayee(payeeName) Then
                        failedTransactions.Add($"Payee validation failed for: {payeeName}")
                        Continue For
                    End If

                    ' Process Wise Charges and Sent Money
                    If description.Contains("Sent money to") Then
                        sendMoneytoamount = amount ' Add Wise Charges amount
                        sendMoneyto_memo = description ' Add Wise Charges amount
                    End If

                    ' Process Sent Money transactions
                    If description.Contains("Wise Charges") Then

                        successCount += 1

                        CreateBankEntry_1558_to_Wise("Wise", "", dateColumn, amount, description, sendMoneytoamount, sendMoneyto_memo)

                    End If
                End If
            Next



            ' Show summary message
            Dim message As String = $"Process completed.{vbNewLine}" &
                                $"Successful Transactions: {successCount}{vbNewLine}" &
                                $"Failed Transactions: {failedTransactions.Count}"

            If failedTransactions.Any() Then
                message &= $"{vbNewLine}Errors:{vbNewLine}{String.Join(vbNewLine, failedTransactions)}"
            End If

            MessageBox.Show(message, "Transaction Summary", MessageBoxButtons.OK, MessageBoxIcon.Information)
        Catch ex As Exception
            MessageBox.Show($"An error occurred: {ex.Message}", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        Finally
            ' Close the processing form
            processingForm.Close()
        End Try
    End Sub

    Private Sub CreateBankEntry_Wise_to_emp(PayeeName As String, checkDate As String, checkAmount As Decimal, memo As String, SendMoney_Amount As Decimal, SendMoney_memo As String) 'classEntry As String
        Dim sessionManager As QBSessionManager = Nothing
        Dim msgSetRequest As IMsgSetRequest = Nothing

        Try
            ' Initialize QuickBooks session manager
            sessionManager = New QBSessionManager()
            sessionManager.OpenConnection("", "Mooneem App")
            sessionManager.BeginSession("", ENOpenMode.omDontCare)

            ' Create a new message set request
            msgSetRequest = sessionManager.CreateMsgSetRequest("US", 8, 0)
            Dim txnAddRq = msgSetRequest.AppendCheckAddRq()
            'If Not PayeeExists(sessionManager, payee) Then
            'AddPayee(sessionManager, payee)
            'End If

            ' Set values
            txnAddRq.AccountRef.FullName.SetValue("Wise")
            txnAddRq.PayeeEntityRef.FullName.SetValue(PayeeName)
            txnAddRq.RefNumber.SetValue("")
            'txnAddRq.PayeeEntityRef.FullName.SetValue("CalOil Company")
            txnAddRq.TxnDate.SetValue(Date.Parse(checkDate))
            txnAddRq.Memo.SetValue(SendMoney_memo)

            ' Add expense line
            Dim expenseLine = txnAddRq.ExpenseLineAddList.Append()
            expenseLine.AccountRef.FullName.SetValue("Outside Services")
            'expenseLine.AccountRef.FullName.SetValue("60110 · Fuel")
            expenseLine.Amount.SetValue(SendMoney_Amount)
            'expenseLine.Memo.SetValue(SendMoney_memo)
            'expenseLine.ClassRef.FullName.SetValue(classEntry)

            Dim expenseLine1 = txnAddRq.ExpenseLineAddList.Append()
            expenseLine1.AccountRef.FullName.SetValue("Wise fees")
            'expenseLine1.AccountRef.FullName.SetValue("60120 · Insurance")
            expenseLine1.Amount.SetValue(checkAmount)
            'expenseLine1.Memo.SetValue(memo)

            ' Send the request
            Dim msgSetResponse = sessionManager.DoRequests(msgSetRequest)
            Dim response = msgSetResponse.ResponseList.GetAt(0)

            If response.StatusCode <> 0 Then
                Throw New Exception(response.StatusMessage)
            End If

        Catch ex As Exception
            MessageBox.Show($"An error occurred: {ex.Message}", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        Finally
            If sessionManager IsNot Nothing Then
                sessionManager.EndSession()
                sessionManager.CloseConnection()
            End If
        End Try
    End Sub

    Private Sub CheckEntryWiseToEmployee(sender As Object, e As EventArgs)
        Dim processingForm As New ProcessingForm()

        Try
            ' Show the processing form modelessly
            processingForm.Show()
            processingForm.Refresh()

            ' Perform the transaction processing
            Dim successCount As Integer = 0
            Dim failedTransactions As New List(Of String)

            ' Initialize Wise Charges amount
            Dim sendMoneytoamount As Decimal = 0
            Dim sendMoneyto_memo As String = ""
            Dim sendMoneyto_payee As String = ""

            ' Dictionary to store transactions by their unique ID
            Dim transactionsById As New Dictionary(Of String, (Decimal, String, String, String, String, Decimal)) ' Key: ID, Value: (Amount, Currency, PayeeName, Date, Description, Wise Charges Amount)

            ' Group transactions by ID
            For Each row As DataGridViewRow In dgvTransactions.Rows
                If Convert.ToBoolean(row.Cells("chkSelect").Value) And row.Cells("ID").Value <> "" Then
                    Dim id As String = row.Cells("ID").Value.ToString()
                    Dim description As String = row.Cells("Description").Value.ToString()
                    Dim dateColumn As String = row.Cells("Date").Value.ToString()
                    Dim amount As Decimal = Math.Abs(Convert.ToDecimal(row.Cells("Amount").Value))
                    Dim currency As String = row.Cells("Currency").Value.ToString()
                    Dim payeeName As String = row.Cells("Payee Name").Value.ToString().Trim()


                    ' Process Wise Charges and Sent Money
                    If description.Contains("Sent money to") Then
                        sendMoneytoamount = amount ' Add Wise Charges amount
                        sendMoneyto_memo = description ' Add Wise Charges amount
                        sendMoneyto_payee = payeeName

                        ' Validate payee
                        If Not ValidatePayee(sendMoneyto_payee) Then
                            failedTransactions.Add($"Payee validation failed for: {sendMoneyto_payee}")
                            Continue For
                        End If
                    End If

                    ' Process Sent Money transactions
                    If description.Contains("Wise Charges") Then

                        successCount += 1

                        CreateBankEntry_Wise_to_emp(sendMoneyto_payee, dateColumn, amount, description, sendMoneytoamount, sendMoneyto_memo)
                    End If
                End If
            Next

            ' Show summary message
            Dim message As String = $"Process completed.{vbNewLine}" &
                                $"Successful Transactions: {successCount}{vbNewLine}" &
                                $"Failed Transactions: {failedTransactions.Count}"

            If failedTransactions.Any() Then
                message &= $"{vbNewLine}Errors:{vbNewLine}{String.Join(vbNewLine, failedTransactions)}"
            End If

            MessageBox.Show(message, "Transaction Summary", MessageBoxButtons.OK, MessageBoxIcon.Information)
        Catch ex As Exception
            MessageBox.Show($"An error occurred: {ex.Message}", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        Finally
            ' Close the processing form
            processingForm.Close()
        End Try
    End Sub

End Class

