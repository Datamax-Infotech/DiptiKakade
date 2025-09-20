Imports System.Data.OleDb
Imports QBFC12Lib

Public Class DeleteTransaction
    Inherits UserControl

    Private cmbClients As ComboBox
    Private dtpMonthSelector As DateTimePicker
    Private btnDeleteTransactions As Button
    Private dgvTransactions As DataGridView
    Private lblLoading As Label
    Dim sessionManager As QBSessionManager
    Public Sub New()
        BuildUI()
        LoadClients()
    End Sub


    'Private ReadOnly connStr As String =
    '    "Provider=Microsoft.ACE.OLEDB.12.0;" &
    '    "Data Source=C:\Users\lenovo\Documents\MooneemDB.accdb;" &
    '    "Persist Security Info=False;"
    Private ReadOnly connStr As String = "Provider=Microsoft.ACE.OLEDB.12.0;Data Source=" + Application.StartupPath + "\MooneemDB.accdb;Persist Security Info=False;"

    Private Sub ConnectToQuickBooks(qbFilePath As String)
        'sessionManager = New QBSessionManager()
        'sessionManager.OpenConnection("", "Mooneem App")

        '' Connect to the specific company file
        'sessionManager.BeginSession(qbFilePath, ENOpenMode.omDontCare)
        Dim sessionManager As QBSessionManager
        Dim startedSession As Boolean = False

        Try
            ConnectToQuickBooks(qbFilePath)
            startedSession = True

            ' Now make requests...
        Catch ex As Exception
            MessageBox.Show("Error: " & ex.Message)
        Finally
            Try
                If startedSession Then sessionManager.EndSession()
                sessionManager.CloseConnection()
            Catch
            End Try
        End Try

    End Sub

    ' Disconnect from QuickBooks
    Private Sub DisconnectFromQuickBooks()
        If sessionManager IsNot Nothing Then
            sessionManager.EndSession()
            sessionManager.CloseConnection()
        End If
    End Sub
    Private Sub BuildUI()
        Me.Dock = DockStyle.Fill
        Me.BackColor = Color.White

        ' ▸ ComboBox for Clients
        cmbClients = New ComboBox With {
        .DropDownStyle = ComboBoxStyle.DropDown,
        .Font = New Font("Verdana", 11),
        .AutoCompleteMode = AutoCompleteMode.SuggestAppend,
        .AutoCompleteSource = AutoCompleteSource.ListItems,
        .Width = 300,
        .Margin = New Padding(10)
    }

        ' ▸ Month selector
        dtpMonthSelector = New DateTimePicker With {
        .Format = DateTimePickerFormat.Custom,
        .CustomFormat = "MMMM yyyy",
        .Font = New Font("Verdana", 11),
        .Width = 200,
        .Margin = New Padding(10)
    }

        ' ▸ Fetch Data button
        Dim btnFetchData As New Button With {
        .Text = "Fetch Data",
        .Font = New Font("Verdana", 11, FontStyle.Bold),
        .BackColor = Color.DodgerBlue,
        .ForeColor = Color.White,
        .Height = 40,
        .Width = 150,
        .Margin = New Padding(10)
    }

        ' ▸ Delete Transactions button
        btnDeleteTransactions = New Button With {
        .Text = "Delete Transactions",
        .Font = New Font("Verdana", 11, FontStyle.Bold),
        .BackColor = Color.IndianRed,
        .ForeColor = Color.White,
        .Height = 40,
        .Width = 200,
        .Margin = New Padding(10)
    }

        ' ▸ Top panel with controls
        Dim topPanel As New FlowLayoutPanel With {
        .Dock = DockStyle.Top,
        .Height = 60,
        .AutoSize = True
    }
        topPanel.Controls.AddRange({cmbClients, dtpMonthSelector, btnFetchData, btnDeleteTransactions})

        ' ▸ DataGridView
        dgvTransactions = New DataGridView With {
        .Dock = DockStyle.Fill,
        .Font = New Font("Verdana", 11),
        .AllowUserToAddRows = False,
        .AllowUserToDeleteRows = False,
        .ReadOnly = False,
        .SelectionMode = DataGridViewSelectionMode.FullRowSelect,
        .AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.None,
        .EnableHeadersVisualStyles = False,
        .BackgroundColor = Color.White
    }

        dgvTransactions.ColumnHeadersDefaultCellStyle = New DataGridViewCellStyle With {
        .BackColor = Color.FromArgb(31, 30, 68),
        .ForeColor = Color.White,
        .Font = New Font("Verdana", 10, FontStyle.Bold)
    }

        ' Add columns in correct order
        dgvTransactions.Columns.Add(New DataGridViewCheckBoxColumn With {.Name = "Select", .HeaderText = "Select", .Width = 70})
        dgvTransactions.Columns.Add(New DataGridViewTextBoxColumn With {.Name = "TxnID", .HeaderText = "Txn ID", .Visible = False})
        dgvTransactions.Columns.Add("TxnType", "Type")
        dgvTransactions.Columns.Add("TxnDate", "Date")
        dgvTransactions.Columns.Add("RefNumber", "Num")
        dgvTransactions.Columns.Add("Memo", "Memo")
        dgvTransactions.Columns.Add("AccountName", "Account")
        dgvTransactions.Columns.Add("Amount", "Amount")

        ' Set widths
        dgvTransactions.Columns("TxnDate").Width = 190
        dgvTransactions.Columns("TxnType").Width = 190
        dgvTransactions.Columns("AccountName").Width = 200
        dgvTransactions.Columns("Amount").Width = 190
        dgvTransactions.Columns("Memo").Width = 190
        dgvTransactions.Columns("RefNumber").Width = 190

        ' ▸ Label for loading
        lblLoading = New Label With {
        .Text = "Loading...",
        .Dock = DockStyle.Bottom,
        .Height = 30,
        .TextAlign = ContentAlignment.MiddleCenter,
        .Font = New Font("Verdana", 10, FontStyle.Bold),
        .ForeColor = Color.DarkBlue,
        .Visible = False
    }

        ' Add everything to the UserControl
        Controls.Add(dgvTransactions)
        Controls.Add(lblLoading)
        Controls.Add(topPanel)

        ' Event handlers
        AddHandler btnDeleteTransactions.Click, AddressOf btnDeleteTransactions_Click
        AddHandler btnFetchData.Click, AddressOf btnFetchData_Click
    End Sub


    Private Sub btnFetchData_Click(sender As Object, e As EventArgs)
        Try
            lblLoading.Text = "Please wait, loading data..."
            lblLoading.Visible = True
            lblLoading.Refresh()
            Application.DoEvents()

            Dim selectedDate As DateTime = dtpMonthSelector.Value
            Dim startDate As New DateTime(selectedDate.Year, selectedDate.Month, 1)
            Dim endDate As DateTime = startDate.AddMonths(1).AddDays(-1)

            Dim selectedClient As ClientInfo = CType(cmbClients.SelectedItem, ClientInfo)
            If selectedClient Is Nothing OrElse String.IsNullOrWhiteSpace(selectedClient.FilePath) Then
                MessageBox.Show("Please select a valid client.")
                lblLoading.Visible = False
                Return
            End If

            ConnectToQuickBooks(selectedClient.FilePath)

            Dim msgSetRequest = sessionManager.CreateMsgSetRequest("US", 8, 0)
            msgSetRequest.Attributes.OnError = ENRqOnError.roeContinue
            msgSetRequest.AppendTransactionQueryRq()

            Dim msgSetResponse = sessionManager.DoRequests(msgSetRequest)
            Dim txnList As ITransactionRetList = TryCast(msgSetResponse.ResponseList.GetAt(0).Detail, ITransactionRetList)

            dgvTransactions.Rows.Clear()

            If txnList IsNot Nothing Then
                For i As Integer = 0 To txnList.Count - 1
                    Dim txn As ITransactionRet = txnList.GetAt(i)
                    If txn IsNot Nothing AndAlso txn.TxnDate IsNot Nothing Then
                        Dim txnDate As DateTime = txn.TxnDate.GetValue()
                        If txnDate >= startDate AndAlso txnDate <= endDate Then
                            dgvTransactions.Rows.Add(
                            False,
                            If(txn.TxnID IsNot Nothing, txn.TxnID.GetValue(), ""),
                            If(txn.TxnType IsNot Nothing, txn.TxnType.GetValue().ToString().Replace("tt", ""), ""),
                            txnDate,
                            If(txn.RefNumber IsNot Nothing, txn.RefNumber.GetValue(), ""),
                            If(txn.Memo IsNot Nothing, txn.Memo.GetValue(), ""),
                            If(txn.AccountRef IsNot Nothing AndAlso txn.AccountRef.FullName IsNot Nothing, txn.AccountRef.FullName.GetValue(), ""),
                            If(txn.Amount IsNot Nothing, txn.Amount.GetValue(), 0)
                        )
                        End If
                    End If
                Next
            End If
        Catch ex As Exception
            MessageBox.Show($"Error fetching transactions: {ex.Message}")
        Finally
            lblLoading.Visible = False
            DisconnectFromQuickBooks()
        End Try
    End Sub


    Private Sub btnDeleteTransactions_Click(sender As Object, e As EventArgs)
        Try
            Dim selectedClient As ClientInfo = CType(cmbClients.SelectedItem, ClientInfo)
            If selectedClient Is Nothing OrElse String.IsNullOrWhiteSpace(selectedClient.FilePath) Then
                MessageBox.Show("Please select a valid client.")
                Return
            End If

            ConnectToQuickBooks(selectedClient.FilePath)

            Dim rowsToDelete As New List(Of DataGridViewRow)

            For Each row As DataGridViewRow In dgvTransactions.Rows
                If row.IsNewRow = False AndAlso Convert.ToBoolean(row.Cells("Select").Value) Then
                    Dim txnID As String = row.Cells("TxnID").Value?.ToString()
                    Dim txnDate As DateTime = Convert.ToDateTime(row.Cells("TxnDate").Value)
                    Dim txnType As String = row.Cells("TxnType").Value.ToString()
                    Dim accountName As String = row.Cells("AccountName").Value.ToString()
                    Dim amount As Decimal = Convert.ToDecimal(row.Cells("Amount").Value)

                    If Not String.IsNullOrEmpty(txnID) Then
                        ' Map the transaction type string to the corresponding enum
                        Dim txnDelType As ENTxnDelType
                        Select Case txnType.ToLower()
                            Case "invoice" : txnDelType = ENTxnDelType.tdtInvoice
                            Case "bill" : txnDelType = ENTxnDelType.tdtBill
                            Case "check" : txnDelType = ENTxnDelType.tdtCheck
                            Case "deposit" : txnDelType = ENTxnDelType.tdtDeposit
                            Case "payment", "receivepayment" : txnDelType = ENTxnDelType.tdtReceivePayment
                            Case "creditmemo" : txnDelType = ENTxnDelType.tdtCreditMemo
                            Case "salesreceipt" : txnDelType = ENTxnDelType.tdtSalesReceipt
                            Case "journalentry" : txnDelType = ENTxnDelType.tdtJournalEntry
                            Case Else
                                MessageBox.Show($"Unsupported transaction type: {txnType}")
                                Continue For
                        End Select

                        Dim msgSetRequest = sessionManager.CreateMsgSetRequest("US", 4, 0)
                        msgSetRequest.Attributes.OnError = ENRqOnError.roeContinue
                        Dim txnDelRq = msgSetRequest.AppendTxnDelRq()
                        txnDelRq.TxnID.SetValue(txnID)
                        txnDelRq.TxnDelType.SetValue(txnDelType)

                        Dim response = sessionManager.DoRequests(msgSetRequest)
                        Dim resultCode As Integer = response.ResponseList.GetAt(0).StatusCode

                        If resultCode = 0 Then
                            rowsToDelete.Add(row)
                            MessageBox.Show($"Transaction {txnID} deleted successfully.")
                            LogDeletedTransaction(txnID, txnDate, txnType, accountName, amount, DateTime.Now)
                        ElseIf resultCode = 3120 Then
                            MessageBox.Show($"Transaction {txnID} is currently in use or locked.")
                        Else
                            MessageBox.Show($"Failed to delete transaction {txnID}. Status Code: {resultCode}")
                        End If
                    Else
                        MessageBox.Show("Transaction ID is invalid.")
                    End If
                End If
            Next

            ' Safely remove rows after loop
            For Each row In rowsToDelete
                dgvTransactions.Rows.Remove(row)
            Next

        Catch ex As Exception
            MessageBox.Show($"Error deleting transactions: {ex.Message}")
        Finally
            DisconnectFromQuickBooks()
        End Try
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

                cmbClients.DataSource = clientList
                cmbClients.DisplayMember = "Name"
                cmbClients.ValueMember = "FilePath"
                cmbClients.Visible = (cmbClients.Items.Count > 0)

                ' ------- modern styling -------
                With cmbClients
                    .DropDownStyle = ComboBoxStyle.DropDown  ' ← allow typing
                    .AutoCompleteMode = AutoCompleteMode.SuggestAppend
                    .AutoCompleteSource = AutoCompleteSource.ListItems
                    .FlatStyle = FlatStyle.Standard
                    .BackColor = Color.White
                    .ForeColor = Color.DarkSlateGray
                    .Font = New Font("Verdana", 11)
                End With





            End Using
        Catch ex As Exception
            MessageBox.Show("Error loading clients: " & ex.Message,
                        "DB Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Sub
    Private Sub LogDeletedTransaction(txnID As String, txnDate As DateTime, txnType As String, accountName As String, amount As Decimal, deletedOn As DateTime)
        Try
            ' Define the connection string for your Access database

            ' Use the connection string to open a connection
            Using connection As New OleDb.OleDbConnection(connStr)
                connection.Open()

                ' Define the insert query
                Dim query As String = "INSERT INTO DeletedTransactions (TxnID, TxnDate, TxnType, AccountName, Amount, DeletedOn) VALUES (?, ?, ?, ?, ?, ?)"

                ' Create a command object
                Using cmd As New OleDb.OleDbCommand(query, connection)
                    ' Add parameters in exact order and specify types
                    cmd.Parameters.Add("TxnID", OleDb.OleDbType.VarChar).Value = txnID
                    cmd.Parameters.Add("TxnDate", OleDb.OleDbType.Date).Value = txnDate
                    cmd.Parameters.Add("TxnType", OleDb.OleDbType.VarChar).Value = txnType
                    cmd.Parameters.Add("AccountName", OleDb.OleDbType.VarChar).Value = accountName
                    cmd.Parameters.Add("Amount", OleDb.OleDbType.Currency).Value = amount
                    cmd.Parameters.Add("DeletedOn", OleDb.OleDbType.Date).Value = deletedOn

                    ' Execute the command
                    cmd.ExecuteNonQuery()
                End Using


            End Using

            ' Optionally, show a success message
            MessageBox.Show($"Transaction {txnID} logged successfully.")
        Catch ex As Exception
            MessageBox.Show($"Error logging transaction {txnID}: {ex.Message}")
        End Try
    End Sub

End Class
