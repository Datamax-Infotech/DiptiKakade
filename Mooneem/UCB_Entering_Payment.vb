

Imports System.Windows.Forms
Imports System.IO
Imports System.Data
Imports ExcelDataReader
Imports Microsoft.VisualBasic.FileIO
Imports System.Data.OleDb
Imports System.Text.RegularExpressions
Imports Org.BouncyCastle.Math.Primes
Imports QBFC12Lib
Imports MySql.Data.MySqlClient
Imports System.Text
Imports System.Net.Mail
Imports System.Net.Mime
Imports Mysqlx.XDevAPI.Common
Public Class UCB_Entering_Payment
    Inherits UserControl

    Private lblUpload As Label
    Private btnBrowse As Button
    'Private btnSubmit As Button
    Private WithEvents dgvData As DataGridView ' <-- Add this at class level
    ' Private dgvData As DataGridView
    Private openFileDialog As OpenFileDialog
    Private lblFileName As Label
    Private filePath As String = ""
    Private comboClient As ComboBox
    Private lblClient As Label
    Private WithEvents txtOutput As TextBox

    Private btnSubmit As Button
    Private depositAccountSuffix As String = ""

    'Private ReadOnly connStr As String =
    '    "Provider=Microsoft.ACE.OLEDB.12.0;" &
    '    "Data Source=C:\Users\lenovo\Documents\MooneemDB.accdb;" &
    '    "Persist Security Info=False;"
    Private ReadOnly connStr As String = "Provider=Microsoft.ACE.OLEDB.12.0;Data Source=" + Application.StartupPath + "\MooneemDB.accdb;Persist Security Info=False;"

    Public Sub New()
        Me.BackColor = Color.White
        BuildUI()
    End Sub

    Private Sub BuildUI()
        Dim primaryColor = Color.FromArgb(0, 150, 136)
        Dim secondaryColor = Color.FromArgb(37, 36, 81)
        Dim btnFont As New Font("Verdana", 10, FontStyle.Bold)

        ' --- Client Label ---
        lblClient = New Label With {
        .Text = "Select Client:",
        .Font = btnFont,
        .Location = New Point(20, 20),
        .AutoSize = True
    }

        ' --- Client ComboBox ---
        comboClient = New ComboBox With {
        .DropDownStyle = ComboBoxStyle.DropDownList,
        .Font = New Font("Verdana", 10),
        .Location = New Point(140, 18),
        .Width = 220
    }

        ' --- Upload Label ---
        lblUpload = New Label With {
        .Text = "Upload CSV File:",
        .Font = btnFont,
        .Location = New Point(380, 20),
        .AutoSize = True
    }

        ' --- Browse Button ---
        btnBrowse = New Button With {
        .Text = "Browse",
        .Width = 100,
        .Height = 30,
        .Font = btnFont,
        .BackColor = Color.Transparent,
        .ForeColor = primaryColor,
        .FlatStyle = FlatStyle.Flat,
        .Location = New Point(520, 15)
    }
        btnBrowse.FlatAppearance.BorderColor = primaryColor
        btnBrowse.FlatAppearance.BorderSize = 2

        AddHandler btnBrowse.Click, AddressOf BtnBrowse_Click
        AddHandler btnBrowse.MouseEnter, Sub()
                                             btnBrowse.BackColor = primaryColor
                                             btnBrowse.ForeColor = Color.White
                                         End Sub
        AddHandler btnBrowse.MouseLeave, Sub()
                                             btnBrowse.BackColor = Color.Transparent
                                             btnBrowse.ForeColor = primaryColor
                                         End Sub

        ' --- Submit Button ---
        btnSubmit = New Button With {
        .Text = "Submit",
        .Width = 100,
        .Height = 30,
        .Font = btnFont,
        .BackColor = Color.Transparent,
        .ForeColor = secondaryColor,
        .FlatStyle = FlatStyle.Flat,
        .Location = New Point(630, 15)
    }
        btnSubmit.FlatAppearance.BorderColor = secondaryColor
        btnSubmit.FlatAppearance.BorderSize = 2
        AddHandler btnSubmit.MouseEnter, Sub()
                                             btnSubmit.BackColor = secondaryColor
                                             btnSubmit.ForeColor = Color.White
                                         End Sub
        AddHandler btnSubmit.MouseLeave, Sub()
                                             btnSubmit.BackColor = Color.Transparent
                                             btnSubmit.ForeColor = secondaryColor
                                         End Sub
        AddHandler btnSubmit.Click, AddressOf btnSubmit_Click
        ' --- File Name Label ---
        lblFileName = New Label With {
        .Text = "Selected File: None",
        .Font = New Font("Verdana", 9, FontStyle.Italic),
        .ForeColor = Color.Gray,
        .AutoSize = True,
        .Location = New Point(20, 60)
    }


        txtOutput = New TextBox() With {
    .Location = New Point(20, 510),
    .Multiline = True,
    .Size = New Size(1050, 100), '
                                 .Visible = False, ' <--- Hide it on load
        .Font = New Font("Verdana", 10)
}
        Me.Controls.Add(txtOutput)

        ' --- DataGridView ---
        dgvData = New DataGridView With {
        .Location = New Point(20, 100),
        .Size = New Size(1050, 500),
        .AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.Fill,
        .ReadOnly = True,
        .Font = New Font("Verdana", 10)
    }

        ' --- File Dialog ---
        openFileDialog = New OpenFileDialog With {
        .Filter = "CSV Files (*.csv)|*.csv|Excel Files (*.xls;*.xlsx)|*.xls;*.xlsx|All Files (*.*)|*.*",
        .Title = "Select Statement File"
    }

        ' --- Add Controls ---
        Me.Controls.Add(lblClient)
        Me.Controls.Add(comboClient)
        Me.Controls.Add(lblUpload)
        Me.Controls.Add(btnBrowse)
        Me.Controls.Add(btnSubmit)
        Me.Controls.Add(lblFileName)
        Me.Controls.Add(dgvData)

        ' --- UserControl Size ---
        Me.Size = New Size(1100, 650)

        ' --- Load Clients into Combo ---
        LoadClients()
    End Sub




    'Private Sub ShowStatus(message As String)
    '    ' Optional: update label or output box
    '    txtOutput.AppendText(message & Environment.NewLine)
    '    Application.DoEvents()
    'End Sub




    'Private Sub ResetStatusUI()
    '    txtOutput.Visible = False
    '    txtOutput.Clear()
    'End Sub


    Private Sub BtnBrowse_Click(sender As Object, e As EventArgs)
        Using ofd As New OpenFileDialog()
            'ofd.Filter = "Excel Files|*.xls;*.xlsx|CSV Files|*.csv"
            ofd.Title = "Select Statement File"

            If ofd.ShowDialog() = DialogResult.OK Then
                filePath = ofd.FileName
                lblFileName.Text = "Selected File: " & Path.GetFileName(filePath)
                Dim fileNameOnly As String = Path.GetFileNameWithoutExtension(filePath)
                Dim accountSuffixMatch As Match = Regex.Match(fileNameOnly, "\D*(\d{4,})")
                If accountSuffixMatch.Success Then
                    depositAccountSuffix = accountSuffixMatch.Groups(1).Value
                Else
                    MessageBox.Show("❌ Could not find numeric suffix in filename.")
                    Return
                End If


                LoadCsvData(filePath)

            End If
        End Using
    End Sub


    Private Sub btnSubmit_Click(sender As Object, e As EventArgs)
        Dim qbSuccessCount As Integer = 0
        Dim qbFailCount As Integer = 0
        Dim dbSuccessCount As Integer = 0
        Dim dbFailCount As Integer = 0
        Dim emailSuccessCount As Integer = 0
        Dim emailFailCount As Integer = 0
        Dim sessionMgr As QBSessionManager = Nothing
        Try
            If comboClient.SelectedItem Is Nothing Then
                MessageBox.Show("❌ Please select a client before submitting.")
                Return
            End If

            Dim selectedClient As ClientInfo = CType(comboClient.SelectedItem, ClientInfo)
            Dim qbFilePath As String = selectedClient.FilePath

            sessionMgr = New QBSessionManager()
            sessionMgr.OpenConnection("", "Mooneem App")
            sessionMgr.BeginSession(qbFilePath, ENOpenMode.omDontCare)

            ShowStatus("✅ Connected to QuickBooks. Starting payment creation...")

            Dim msgSetRequest As IMsgSetRequest = sessionMgr.CreateMsgSetRequest("US", 12, 0)
            msgSetRequest.Attributes.OnError = ENRqOnError.roeContinue

            For Each row As DataGridViewRow In dgvData.Rows
                If row.IsNewRow Then Continue For

                Dim txRow = row.Index + 1
                Dim rawAmount As String = row.Cells("Amount").Value?.ToString().Trim()
                Dim amount As Decimal = 0D

                If Not Decimal.TryParse(rawAmount, Globalization.NumberStyles.Any, Globalization.CultureInfo.InvariantCulture, amount) Then
                    ShowStatus($"⚠️ Row {txRow}: Invalid amount: {rawAmount}")
                    Continue For
                End If

                Dim rawDate As String = row.Cells("Posting Date").Value?.ToString().Trim()
                Dim postDate As Date
                If Not Date.TryParse(rawDate, Globalization.CultureInfo.InvariantCulture, Globalization.DateTimeStyles.None, postDate) Then
                    ShowStatus($"❌ Row {txRow}: Invalid date: '{rawDate}'")
                    Continue For
                End If

                Dim description As String = row.Cells("Description").Value.ToString()
                Dim reference As String = ExtractTRN(description)
                Dim invoiceNumber As String = ExtractInvoiceNumber(description)
                Dim orderID As String = row.Cells("TransID").Value?.ToString()

                If String.IsNullOrWhiteSpace(invoiceNumber) Then
                    ShowStatus($"❌ Row {txRow}: Could not extract invoice number.")
                    Continue For
                End If

                ShowStatus($"🔄 Row {txRow}: Processing Invoice {invoiceNumber}...")

                msgSetRequest.ClearRequests()
                Dim invoiceQuery = msgSetRequest.AppendInvoiceQueryRq()
                invoiceQuery.ORInvoiceQuery.RefNumberList.Add(invoiceNumber)

                Dim invoiceResponseSet = sessionMgr.DoRequests(msgSetRequest)
                Dim invoiceResponse As IResponse = invoiceResponseSet.ResponseList.GetAt(0)

                If invoiceResponse.StatusCode <> 0 OrElse invoiceResponse.Detail Is Nothing Then
                    ShowStatus($"❌ Row {txRow}: Invoice {invoiceNumber} not found in QuickBooks.")
                    qbFailCount += 1
                    Continue For
                End If

                Dim invoiceList = CType(invoiceResponse.Detail, IInvoiceRetList)
                If invoiceList.Count = 0 Then
                    ShowStatus($"❌ Row {txRow}: Empty invoice list returned.")
                    qbFailCount += 1
                    Continue For
                End If

                msgSetRequest.ClearRequests()
                Dim invoice = invoiceList.GetAt(0)
                Dim txnID = invoice.TxnID.GetValue()
                Dim customerName = invoice.CustomerRef.FullName.GetValue()

                Dim paymentAdd = msgSetRequest.AppendReceivePaymentAddRq()
                paymentAdd.CustomerRef.FullName.SetValue(customerName)
                paymentAdd.TotalAmount.SetValue(amount)
                paymentAdd.PaymentMethodRef.FullName.SetValue("EFT")
                paymentAdd.TxnDate.SetValue(postDate)
                paymentAdd.Memo.SetValue(invoiceNumber)
                paymentAdd.RefNumber.SetValue(reference)

                Dim appliedTxn = paymentAdd.ORApplyPayment.AppliedToTxnAddList.Append()
                appliedTxn.TxnID.SetValue(txnID)
                appliedTxn.PaymentAmount.SetValue(amount)

                Dim responseSet = sessionMgr.DoRequests(msgSetRequest)
                Dim resp = responseSet.ResponseList.GetAt(0)

                If resp.StatusCode = 0 Then
                    '  ShowStatus($"✅ Row {txRow}: Payment for Invoice {invoiceNumber} added.")
                    qbSuccessCount += 1

                    ' ShowStatus($"💾 Row {txRow}: Inserting DB entry...")
                    Dim dbResult As String = InsertIntoDatabase(orderID, invoiceNumber, amount, postDate, reference)

                    If dbResult.Contains("✅") Then
                        dbSuccessCount += 1
                    Else
                        dbFailCount += 1
                    End If
                    'ShowStatus(dbResult)

                    If dbResult.Contains("Email Status:") Then
                        If dbResult.Contains("❌ Email") OrElse dbResult.Contains("⚠️") Then
                            emailFailCount += 1
                        Else
                            emailSuccessCount += 1
                        End If
                    End If
                Else
                    ShowStatus($"❌ Row {txRow}: Failed to add payment for Invoice {invoiceNumber}: {resp.StatusMessage}")
                    qbFailCount += 1
                End If
            Next

            ShowStatus("✅ All rows processed. Preparing summary..." & Environment.NewLine)

            ' Final summary
            Dim summary As String = "----- Summary -----" & Environment.NewLine &
            $"🧾 QB Payments: {qbSuccessCount} succeeded, {qbFailCount} failed" & Environment.NewLine &
            $"🗃️ DB Entries: {dbSuccessCount} succeeded, {dbFailCount} failed" & Environment.NewLine &
            $"📧 Emails Sent: {emailSuccessCount} succeeded, {emailFailCount} failed"

            ' Show in output and also in popup
            ShowStatus(summary, isPopup:=False)
            MessageBox.Show(summary, "Summary Report", MessageBoxButtons.OK, MessageBoxIcon.Information)

            'Catch ex As Exception
            '    MessageBox.Show("⚠️ Error: " & ex.Message)
            'End Try
        Catch ex As Exception
            MessageBox.Show("Error: " & ex.Message)
        Finally
            ' 👉 Always close the session and connection
            If sessionMgr IsNot Nothing Then
                Try
                    sessionMgr.EndSession()
                    sessionMgr.CloseConnection()
                Catch
                    ' Ignore cleanup errors
                End Try
            End If
        End Try
    End Sub



    Private Function InsertIntoDatabase(transID As String, invoiceNumber As String, amount As Decimal, postDate As Date, reference As String) As String
        Try
            Dim connString As String = "Server=localhost;Database=ucbdata_usedcard_production;Uid=root;Pwd=;"
            Using conn As New MySqlConnection(connString)
                conn.Open()

                ' Step 0: Fetch company_id from water_transaction
                Dim companyId As Integer = 0
                Dim compCmd As New MySqlCommand("SELECT company_id FROM water_transaction WHERE id = @TransID", conn)
                compCmd.Parameters.AddWithValue("@TransID", transID)
                Using compReader = compCmd.ExecuteReader()
                    If compReader.Read() AndAlso Not IsDBNull(compReader("company_id")) Then
                        companyId = Convert.ToInt32(compReader("company_id"))
                    End If
                End Using

                ' Step 1: Check if transID exists
                Dim checkCmd As New MySqlCommand("SELECT COUNT(*) FROM loop_transaction_buyer WHERE id = @TransID", conn)
                checkCmd.Parameters.AddWithValue("@TransID", transID)
                Dim exists As Integer = Convert.ToInt32(checkCmd.ExecuteScalar())
                If exists = 0 Then
                    Return $"❌ Transaction ID {transID} not found in loop_transaction_buyer."
                End If

                ' Step 2: INSERT into loop_buyer_payments
                Dim insertCmd As New MySqlCommand("
                INSERT INTO loop_buyer_payments 
                   (date, trans_rec_id, employee, method, reference, amount, inv_receiptdate, amount_tochk) 
                VALUES 
                    (@Date, @TransID, 'MNMU2', 'EFT', @Reference, @Amount, @ReceiptDate, @Amount)", conn)
                insertCmd.Parameters.AddWithValue("@Date", Date.Now.ToString("MM/dd/yyyy"))
                insertCmd.Parameters.AddWithValue("@TransID", transID)
                insertCmd.Parameters.AddWithValue("@Reference", reference)
                insertCmd.Parameters.AddWithValue("@Amount", amount)
                insertCmd.Parameters.AddWithValue("@ReceiptDate", postDate.ToString("yyyy-MM-dd"))
                insertCmd.ExecuteNonQuery()

                ' Step 3: UPDATE water_transaction
                Dim updateCmd As New MySqlCommand("
            UPDATE water_transaction 
            SET made_payment = '', paid_by = 'MNMU2', paid_date = @ReceiptDate, 
                payment_methodid = 9, payment_proof_file = '' 
            WHERE id = @TransID", conn)
                updateCmd.Parameters.AddWithValue("@ReceiptDate", postDate.ToString("MM/dd/yyyy"))
                updateCmd.Parameters.AddWithValue("@TransID", transID)
                updateCmd.ExecuteNonQuery()

                ' Step 4: INSERT into water_transaction_log_notes
                If Not String.IsNullOrWhiteSpace(reference) Then
                    Dim logCmd As New MySqlCommand("
                 INSERT INTO water_transaction_log_notes 
                      (`date`, trans_id, employee_id, message) 
                VALUES 
                  (@NowDate, @TransID, 43, @Message)", conn)
                    logCmd.Parameters.AddWithValue("@NowDate", Date.Now.ToString("yyyy-MM-dd HH:mm:ss"))
                    logCmd.Parameters.AddWithValue("@TransID", transID)
                    logCmd.Parameters.AddWithValue("@Message", reference)
                    logCmd.ExecuteNonQuery()
                End If

                ' Step 5: Calculate invoice_amt from loop_invoice_items
                Dim invoiceAmt As Decimal = 0
                Dim invoiceItemCmd As New MySqlCommand("SELECT quantity, price FROM loop_invoice_items WHERE trans_rec_id = @TransID", conn)
                invoiceItemCmd.Parameters.AddWithValue("@TransID", transID)
                Using reader = invoiceItemCmd.ExecuteReader()
                    While reader.Read()
                        Dim qty = Convert.ToDecimal(reader("quantity"))
                        Dim price = Convert.ToDecimal(reader("price"))
                        invoiceAmt += qty * price
                    End While
                End Using

                ' Step 6: Update loop_transaction_buyer.invoice_paid
                Dim markUnpaidCmd As New MySqlCommand("UPDATE loop_transaction_buyer SET invoice_paid = 0 WHERE id = @TransID", conn)
                markUnpaidCmd.Parameters.AddWithValue("@TransID", transID)
                markUnpaidCmd.ExecuteNonQuery()

                If amount > 0 AndAlso invoiceAmt > 0 AndAlso amount >= invoiceAmt Then
                    Dim markPaidCmd As New MySqlCommand("UPDATE loop_transaction_buyer SET invoice_paid = 1 WHERE id = @TransID", conn)
                    markPaidCmd.Parameters.AddWithValue("@TransID", transID)
                    markPaidCmd.ExecuteNonQuery()
                End If

                ' Step 7: Get invoice details
                Dim invoiceNo As String = ""
                Dim invDate As Date = Nothing
                Dim invDetailsCmd As New MySqlCommand("SELECT loop_qb_invoice_no, inv_date_of FROM loop_transaction_buyer WHERE id = @TransID", conn)
                invDetailsCmd.Parameters.AddWithValue("@TransID", transID)
                Using reader = invDetailsCmd.ExecuteReader()
                    If reader.Read() Then
                        invoiceNo = reader("loop_qb_invoice_no").ToString()
                        If Not IsDBNull(reader("inv_date_of")) Then
                            invDate = Convert.ToDateTime(reader("inv_date_of"))
                        End If
                    End If
                End Using

                ' Step 8: Insert into rep_p_and_l_affect_amt_history
                Dim pnlCmd As New MySqlCommand("
                INSERT INTO rep_p_and_l_affect_amt_history 
                 (transaction_buyer_id, company_id, employee_id, entry_date, inv_amount, inv_date, change_value, updation_notes, add_del_upd_flg) 
                VALUES 
                   (@TransID, @CompanyID, @EmployeeID, @EntryDate, @Amount, @InvDate, '', 'Added from ''INVOICE PAID'' table.', 1)", conn)
                pnlCmd.Parameters.AddWithValue("@TransID", transID)
                pnlCmd.Parameters.AddWithValue("@CompanyID", companyId)
                pnlCmd.Parameters.AddWithValue("@EmployeeID", 43)
                pnlCmd.Parameters.AddWithValue("@EntryDate", Date.Now.ToString("yyyy-MM-dd HH:mm:ss"))
                pnlCmd.Parameters.AddWithValue("@Amount", amount)
                pnlCmd.Parameters.AddWithValue("@InvDate", invDate.ToString("yyyy-MM-dd"))
                pnlCmd.ExecuteNonQuery()

                ' Step 9: Fetch loop_invoice_details and send confirmation email

                Dim invDetailsQuery As String = "SELECT * FROM loop_invoice_details WHERE trans_rec_id = @TransID"
                Dim invDetailsCmdEmail As New MySqlCommand(invDetailsQuery, conn)
                invDetailsCmdEmail.Parameters.AddWithValue("@TransID", transID)
                Dim toEmail As String = "dipti.kakade@extractinfo.com"
                Dim emailStatus As String = ""
                Dim emailSent As Boolean = False

                Using reader = invDetailsCmdEmail.ExecuteReader()
                    If reader.HasRows Then
                        While reader.Read()
                            Dim invoiceTotal As Decimal = Convert.ToDecimal(reader("total"))
                            Dim invoiceDate As Date = Convert.ToDateTime(reader("timestamp"))
                            Dim creditTerms As String = reader("terms").ToString()
                            Dim bookkeeperNote As String = reader("bookkeeper").ToString()

                            Dim eml_confirmation As New StringBuilder()
                            eml_confirmation.AppendLine("<html><head><style>body{font-family:'Montserrat',sans-serif;}</style></head><body>")
                            eml_confirmation.AppendLine($"<h2>Payment Entered</h2>")
                            eml_confirmation.AppendLine($"<p><strong>Order #:</strong> {transID}</p>")
                            eml_confirmation.AppendLine($"<p><strong>Invoice #:</strong> {invoiceNumber}</p>")
                            eml_confirmation.AppendLine($"<p><strong>Amount:</strong> ${invoiceTotal:N2}</p>")
                            eml_confirmation.AppendLine($"<p><strong>Invoice Date:</strong> {invoiceDate:MM/dd/yyyy}</p>")
                            eml_confirmation.AppendLine($"<p><strong>Credit Terms:</strong> {creditTerms}</p>")
                            eml_confirmation.AppendLine($"<p><strong>Reference:</strong> {reference}</p>")
                            eml_confirmation.AppendLine($"<p><strong>Receipt Date:</strong> {postDate:MM/dd/yyyy}</p>")
                            eml_confirmation.AppendLine("</body></html>")

                            Dim subject As String = $"Payment Entered for Invoice {invoiceNumber} in Order #{transID}"
                            Try
                                Debug.WriteLine("📧 Sending confirmation email to: " & toEmail)
                                emailStatus = SendEmail(New List(Of String)(), "", toEmail, "", "", "ucbemail@usedcardboardboxes.com", "Operations UCB", "operations@usedcardboardboxes.com", subject, eml_confirmation.ToString())
                                Debug.WriteLine("📬 SendEmail result: " & emailStatus)
                                emailSent = True
                            Catch emailEx As Exception
                                emailStatus = $"❌ Email send exception: {emailEx.Message}"
                                Debug.WriteLine(emailStatus)
                            End Try
                        End While
                    Else
                        emailStatus = $"❌ No invoice details found in loop_invoice_details for Transaction ID {transID}. Email not sent."
                        Debug.WriteLine(emailStatus)
                    End If
                End Using

                ' Step 10: Insert into loop_transaction_notes
                Dim logMessage As String = $"System generated log - Invoice Paid on {Now:MM/dd/yyyy HH:mm:ss} by MNMU2"
                Dim logInsert As New MySqlCommand("
                INSERT INTO loop_transaction_notes 
                    (company_id, rec_type, rec_id, message, employee_id) 
                VALUES 
                    (@CompanyID, 'Supplier', @RecID, @Message, @EmpID)", conn)
                logInsert.Parameters.AddWithValue("@CompanyID", companyId)
                logInsert.Parameters.AddWithValue("@RecID", transID)
                logInsert.Parameters.AddWithValue("@Message", logMessage)
                logInsert.Parameters.AddWithValue("@EmpID", 43)
                logInsert.ExecuteNonQuery()

                ' Step 11: Send transaction log email
                Dim logEmailStatus As String = ""

                ' Step 11: Send transaction log email
                Try
                    logEmailStatus = SendTransactionLogEmail(companyId, transID, "Supplier", "buyer_view")
                Catch logEx As Exception
                    logEmailStatus = "⚠️ Transaction log email failed: " & logEx.Message
                    ShowStatus(logEmailStatus)
                End Try

                Return $"✅ Email Status: {emailStatus}, Log Email Status: {logEmailStatus}"

            End Using
        Catch ex As Exception
            Return "❌ DB Error: " & ex.Message
        End Try
    End Function


    Private statusPanel As Panel
    Private statusLabel As Label

    Private Sub InitializeStatusPanel()
        ' Call this once during form load
        statusPanel = New Panel With {
        .BackColor = Color.FromArgb(230, 245, 255),
        .Width = 400,
        .Height = 50,
        .Visible = False
    }

        statusLabel = New Label With {
        .AutoSize = False,
        .Dock = DockStyle.Fill,
        .TextAlign = ContentAlignment.MiddleCenter,
        .Font = New Font("Verdana", 10, FontStyle.Bold),
        .ForeColor = Color.FromArgb(0, 70, 120)
    }

        statusPanel.Controls.Add(statusLabel)
        Me.Controls.Add(statusPanel)
        statusPanel.BringToFront()
    End Sub

    Private Sub ShowStatus(message As String, Optional isPopup As Boolean = True)
        If statusPanel Is Nothing OrElse statusLabel Is Nothing Then
            InitializeStatusPanel()
        End If

        statusLabel.Text = message

        If isPopup Then
            statusPanel.Visible = True
            statusPanel.BringToFront()

            ' Center panel over dgvData
            Dim centerX As Integer = dgvData.Left + (dgvData.Width \ 2) - (statusPanel.Width \ 2)
            Dim centerY As Integer = dgvData.Top + (dgvData.Height \ 2) - (statusPanel.Height \ 2)
            statusPanel.Location = New Point(centerX, centerY)

            ' Auto-close after 3 seconds
            Dim t As New Timer With {.Interval = 3000}
            AddHandler t.Tick, Sub(sender, e)
                                   t.Stop()
                                   t.Dispose()
                                   ResetStatusUI()
                               End Sub
            t.Start()
        End If
    End Sub

    Private Sub ResetStatusUI()
        If statusPanel IsNot Nothing Then
            statusPanel.Visible = False
        End If
    End Sub



    'Private Sub ShowStatus(message As String, Optional isPopup As Boolean = True)
    '    txtOutput.Clear()
    '    txtOutput.AppendText(message & Environment.NewLine)
    '    txtOutput.ScrollToCaret()
    '    Application.DoEvents()

    '    If isPopup Then
    '        txtOutput.BackColor = Color.FromArgb(230, 245, 255) ' Light pastel blue
    '        txtOutput.ForeColor = Color.FromArgb(0, 70, 120)     ' Calm navy blue
    '        txtOutput.BorderStyle = BorderStyle.None             ' No visible black border
    '        txtOutput.Font = New Font("Segoe UI", 10, FontStyle.Bold)
    '        txtOutput.Visible = True
    '        txtOutput.BringToFront()

    '        ' Rounded corners
    '        Dim radius As Integer = 12
    '        Dim path As New Drawing2D.GraphicsPath()
    '        path.AddArc(0, 0, radius, radius, 180, 90)
    '        path.AddArc(txtOutput.Width - radius, 0, radius, radius, 270, 90)
    '        path.AddArc(txtOutput.Width - radius, txtOutput.Height - radius, radius, radius, 0, 90)
    '        path.AddArc(0, txtOutput.Height - radius, radius, radius, 90, 90)
    '        path.CloseAllFigures()
    '        txtOutput.Region = New Region(path)

    '        ' Size and center positioning
    '        txtOutput.Width = 400
    '        txtOutput.Height = 80
    '        Dim centerX As Integer = dgvData.Left + (dgvData.Width \ 2) - (txtOutput.Width \ 2)
    '        Dim centerY As Integer = dgvData.Top + (dgvData.Height \ 2) - (txtOutput.Height \ 2)
    '        txtOutput.Location = New Point(centerX, centerY)

    '        ' Optional: Transparent Panel behind txtOutput for a soft drop shadow look
    '        ' (you could use pnlOverlay here if you embed txtOutput inside it)

    '        ' Auto-close after 3 seconds
    '        Dim t As New Timer With {.Interval = 3000}
    '        AddHandler t.Tick, Sub(sender, e)
    '                               t.Stop()
    '                               t.Dispose()
    '                               ResetStatusUI()
    '                           End Sub
    '        t.Start()
    '    End If
    'End Sub

    'Private Sub ShowStatus(message As String, Optional isPopup As Boolean = True)
    '    txtOutput.AppendText(message & Environment.NewLine)
    '    txtOutput.ScrollToCaret()
    '    Application.DoEvents()

    '    If isPopup Then
    '        txtOutput.BackColor = Color.FromArgb(255, 255, 200)
    '        txtOutput.ForeColor = Color.Black
    '        txtOutput.BorderStyle = BorderStyle.FixedSingle
    '        txtOutput.Visible = True
    '        txtOutput.BringToFront()

    '        txtOutput.Width = 400
    '        txtOutput.Height = 60

    '        Dim centerX As Integer = dgvData.Left + (dgvData.Width \ 2) - (txtOutput.Width \ 2)
    '        Dim centerY As Integer = dgvData.Top + (dgvData.Height \ 2) - (txtOutput.Height \ 2)
    '        txtOutput.Location = New Point(centerX, centerY)

    '        Dim t As New Timer With {.Interval = 3000}
    '        AddHandler t.Tick, Sub(sender, e)
    '                               t.Stop()
    '                               t.Dispose()
    '                               ResetStatusUI()
    '                           End Sub
    '        t.Start()
    '    End If
    'End Sub





    Public Function SendEmail(
    files As List(Of String),
    path As String,
    mailto As String,
    scc As String,
    sbcc As String,
    fromMail As String,
    fromName As String,
    replyTo As String,
    subject As String,
    message As String) As String

        Try
            Dim mail As New MailMessage()
            mail.From = New MailAddress(fromMail, fromName)
            mail.ReplyToList.Add(New MailAddress(replyTo, fromName))
            mail.Subject = subject
            mail.Body = message
            mail.IsBodyHtml = True

            ' Add To recipients
            If Not String.IsNullOrWhiteSpace(mailto) Then
                For Each addr In mailto.Split({",", ";"}, StringSplitOptions.RemoveEmptyEntries)
                    mail.To.Add(addr.Trim())
                Next
            End If

            ' Add CC recipients
            If Not String.IsNullOrWhiteSpace(scc) Then
                For Each addr In scc.Split({",", ";"}, StringSplitOptions.RemoveEmptyEntries)
                    mail.CC.Add(addr.Trim())
                Next
            End If

            ' Add BCC recipients
            If Not String.IsNullOrWhiteSpace(sbcc) Then
                For Each addr In sbcc.Split({",", ";"}, StringSplitOptions.RemoveEmptyEntries)
                    mail.Bcc.Add(addr.Trim())
                Next
            End If

            ' Add attachments
            If files IsNot Nothing Then
                For Each fileName In files
                    Dim fullPath = IO.Path.Combine(path, fileName)
                    If IO.File.Exists(fullPath) Then
                        mail.Attachments.Add(New Attachment(fullPath))
                    End If
                Next
            End If

            ' Setup SMTP
            Dim smtp As New SmtpClient("smtp.office365.com", 587) With {
            .EnableSsl = True,
            .DeliveryMethod = SmtpDeliveryMethod.Network,
            .UseDefaultCredentials = False
        }

            ' Choose credentials
            If fromMail = "UCBZWEmail@UCBZeroWaste.com" Then
                smtp.Credentials = New Net.NetworkCredential("UCBZWEmail@UCBZeroWaste.com", "#UCBgrn4652")
            Else
                smtp.Credentials = New Net.NetworkCredential("ucbemail@usedcardboardboxes.com", "WH@ToGap$222")
            End If

            smtp.Send(mail)
            Return "emailsend"

        Catch ex As Exception
            ' Log the error if needed: Debug.WriteLine(ex.Message)
            Return "emailerror"
        End Try

    End Function

    Private Function SendTransactionLogEmail(companyId As Integer, transID As String, recType As String, viewType As String) As String
        Try
            Dim connString As String = "Server=localhost;Database=ucbdata_usedcard_production;Uid=root;Pwd=;"
            Dim b2bConnStr As String = "Server=localhost;Database=ucbdata_usedcard_b2b;Uid=root;Pwd=;"
            Dim b2bid As Integer = 0
            Dim notes_company As String = ""
            Dim acc_owner_email As String = ""

            Using conn As New MySqlConnection(connString)
                conn.Open()

                ' Get b2bid and warehouse name
                Using cmd As New MySqlCommand("SELECT b2bid, warehouse_name FROM loop_warehouse WHERE id = @id", conn)
                    cmd.Parameters.AddWithValue("@id", companyId)
                    Using reader = cmd.ExecuteReader()
                        If reader.Read() Then
                            b2bid = Convert.ToInt32(reader("b2bid"))
                            notes_company = GetNicknameVal(reader("warehouse_name").ToString(), b2bid)
                        End If
                    End Using
                End Using

                ' Get po_employee
                Dim po_employee As String = ""
                Using cmd As New MySqlCommand("SELECT po_employee FROM loop_transaction_buyer WHERE id = @id", conn)
                    cmd.Parameters.AddWithValue("@id", transID)
                    Using reader = cmd.ExecuteReader()
                        If reader.Read() Then
                            po_employee = reader("po_employee").ToString()
                        End If
                    End Using
                End Using

                If String.IsNullOrEmpty(po_employee) Then
                    Using b2bConn As New MySqlConnection(b2bConnStr)
                        b2bConn.Open()

                        Dim assignedto As Integer = 0
                        Using cmd As New MySqlCommand("SELECT assignedto FROM companyInfo WHERE ID = @id", b2bConn)
                            cmd.Parameters.AddWithValue("@id", b2bid)
                            Using reader = cmd.ExecuteReader()
                                If reader.Read() Then
                                    assignedto = Convert.ToInt32(reader("assignedto"))
                                End If
                            End Using
                        End Using

                        Using cmd As New MySqlCommand("SELECT email FROM employees WHERE status = 'Active' AND employeeID = @eid", b2bConn)
                            cmd.Parameters.AddWithValue("@eid", assignedto)
                            Using reader = cmd.ExecuteReader()
                                If reader.Read() Then
                                    acc_owner_email = reader("email").ToString()
                                End If
                            End Using
                        End Using
                    End Using
                Else
                    Using cmd As New MySqlCommand("SELECT email FROM loop_employees WHERE status = 'Active' AND initials = @initials", conn)
                        cmd.Parameters.AddWithValue("@initials", po_employee)
                        Using reader = cmd.ExecuteReader()
                            If reader.Read() Then
                                acc_owner_email = reader("email").ToString()
                            End If
                        End Using
                    End Using
                End If

                If Not String.IsNullOrEmpty(acc_owner_email) Then
                    Dim notesQuery As String =
                    "SELECT message, date, loop_employees.name FROM loop_transaction_notes " &
                    "INNER JOIN loop_employees ON loop_transaction_notes.employee_id = loop_employees.id " &
                    "WHERE loop_transaction_notes.company_id = @cid AND loop_transaction_notes.rec_id = @tid " &
                    "ORDER BY loop_transaction_notes.date DESC"

                    Dim emailHtml As New Text.StringBuilder()

                    emailHtml.Append("<html><body bgcolor='#E7F5C2'>")
                    emailHtml.Append("<table border='0' align='center' width='700px' bgcolor='#E7F5C2'>")
                    emailHtml.Append("<tr><td><p align='center'><img width='650' height='166' src='https://loops.usedcardboardboxes.com/images/ucb-banner1.jpg'></p></td></tr>")
                    emailHtml.Append("<tr><td><table width='700px' cellspacing='1' cellpadding='3'>")
                    emailHtml.Append("<tr><th colspan='3'>TRANSACTION LOG UPDATES</th></tr>")

                    Dim link As String = $"https://loops.usedcardboardboxes.com/viewCompany.php?ID={b2bid}&show=transactions&warehouse_id={companyId}&rec_type={recType}&proc=View&searchcrit=&id={companyId}&rec_id={transID}&display={viewType}"
                    emailHtml.Append($"<tr><td colspan='3' bgcolor='#98bcdf'><strong>Company Name: <a href='{link}'>{notes_company}</a></strong></td></tr>")
                    emailHtml.Append("<tr><td bgcolor='#ABC5DF'><strong>Date/Time</strong></td><td bgcolor='#ABC5DF'><strong>Employee</strong></td><td bgcolor='#ABC5DF'><strong>Notes</strong></td></tr>")

                    Using cmd As New MySqlCommand(notesQuery, conn)
                        cmd.Parameters.AddWithValue("@cid", companyId)
                        cmd.Parameters.AddWithValue("@tid", transID)
                        Using reader = cmd.ExecuteReader()
                            Dim tdno As Integer = 0
                            While reader.Read()
                                Dim logDate As String = reader("date").ToString()
                                Dim employee As String = reader("name").ToString()
                                Dim message As String = reader("message").ToString()

                                Dim rowColor As String = If(tdno Mod 2 = 0, "#d1cfce", "#e4e4e4")
                                emailHtml.Append($"<tr><td bgcolor='{rowColor}'>{logDate}</td><td bgcolor='{rowColor}'>{employee}</td><td bgcolor='{rowColor}'>{message}</td></tr>")
                                tdno += 1
                            End While
                        End Using
                    End Using

                    emailHtml.Append("</table></td></tr>")
                    emailHtml.Append("<tr><td><p align='center'><img width='650' height='87' src='http://loops.usedcardboardboxes.com/images/ucb-footer1.jpg'></p></td></tr>")
                    emailHtml.Append("</table></body></html>")

                    Dim toEmail As String = "dipti.kakade@extractinfo.com"
                    Dim subject As String = $"Transaction Log Update for {notes_company} - {transID}"

                    ' ✅ RETURN the SendEmail status
                    Return SendEmail(New List(Of String), "", toEmail, "", "", "ucbemail@usedcardboardboxes.com", "Operations Usedcardboardboxes", "operations@usedcardboardboxes.com", subject, emailHtml.ToString())
                End If

                Return "⚠️ No recipient email found to send transaction log."
            End Using

        Catch ex As Exception
            Return "❌ Error in SendTransactionLogEmail: " & ex.Message
        End Try
    End Function


    Public Function GetNicknameVal(warehouseName As String, b2bID As Integer?) As String
        Dim nickname As String = ""
        Dim b2bConnStr As String = "Server=localhost;Database=ucbdata_usedcard_b2b;Uid=root;Pwd=;"

        If b2bID.HasValue AndAlso b2bID.Value > 0 Then
            Using conn As New MySqlConnection(b2bConnStr)
                conn.Open()
                Dim sql As String = "SELECT nickname, company, shipCity, shipState FROM companyInfo WHERE ID = @id"

                Using cmd As New MySqlCommand(sql, conn)
                    cmd.Parameters.AddWithValue("@id", b2bID.Value)

                    Using reader As MySqlDataReader = cmd.ExecuteReader()
                        If reader.Read() Then
                            Dim dbNickname As String = reader("nickname")?.ToString()?.Trim()
                            Dim company As String = reader("company")?.ToString()?.Trim()
                            Dim shipCity As String = reader("shipCity")?.ToString()?.Trim()
                            Dim shipState As String = reader("shipState")?.ToString()?.Trim()

                            If Not String.IsNullOrWhiteSpace(dbNickname) Then
                                nickname = dbNickname
                            ElseIf Not String.IsNullOrWhiteSpace(company) AndAlso company.Contains("-") Then
                                nickname = company
                            ElseIf Not String.IsNullOrWhiteSpace(shipCity) OrElse Not String.IsNullOrWhiteSpace(shipState) Then
                                nickname = company & " - " & shipCity & ", " & shipState
                            Else
                                nickname = company
                            End If
                        End If
                    End Using
                End Using
            End Using
        Else
            nickname = warehouseName
        End If

        Return nickname
    End Function


    Private Function GetMatchingBankAccountName(sessionMgr As QBSessionManager, suffix As String) As String
        Dim msgSet As IMsgSetRequest = sessionMgr.CreateMsgSetRequest("US", 12, 0)
        msgSet.Attributes.OnError = ENRqOnError.roeContinue

        Dim accountQuery = msgSet.AppendAccountQueryRq()
        accountQuery.ORAccountListQuery.AccountListFilter.AccountTypeList.Add(ENAccountType.atBank)


        Dim responseSet = sessionMgr.DoRequests(msgSet)
        If responseSet.ResponseList.Count = 0 Then Return ""

        Dim accountList = CType(responseSet.ResponseList.GetAt(0).Detail, IAccountRetList)
        If accountList Is Nothing Then Return ""

        For i = 0 To accountList.Count - 1
            Dim acct = accountList.GetAt(i)
            If acct.FullName.GetValue().EndsWith(suffix) Then
                Return acct.FullName.GetValue()
            End If
        Next

        Return ""
    End Function

    Private Function ExtractOrderID(description As String) As String
        Dim pattern As String = "Order\s+(\d+)"
        Dim match = Regex.Match(description, pattern)
        If match.Success Then
            Return match.Groups(1).Value.Trim()
        End If
        Return ""
    End Function

    Function ExtractTRN(description As String) As String
        Dim match = Regex.Match(description, "TRN:\s*(\d+)")
        If match.Success Then
            Return match.Groups(1).Value
        End If
        Return ""
    End Function

    Private Function ExtractInvoiceNumber(description As String) As String
        ' Normalize spacing and trim
        description = Regex.Replace(description, "\s+", " ").Trim()

        ' Step 1: Look for "Inv" or "Invoice" followed by invoice format
        Dim invPattern As String = "(?i)\bInv(?:oice)?\s+([A-Za-z]{2,3}[- ]?\d{4,})\b"
        Dim invMatch = Regex.Match(description, invPattern)

        If invMatch.Success Then
            Dim candidate = invMatch.Groups(1).Value
            candidate = Regex.Replace(candidate, "[-\s]", "") ' Remove dash and space
            candidate = candidate.ToUpper()

            If Regex.IsMatch(candidate, "^[A-Z]{2,3}[0-9]{4,}$") AndAlso candidate.Length <= 10 Then
                Return candidate
            End If
        End If

        ' Step 2: Fallback pattern like ZW39023, LPB-43001, LP 43111
        Dim genericPattern As String = "\b([A-Za-z]{2,3}[- ]?\d{4,})\b"
        Dim matches = Regex.Matches(description, genericPattern)

        For Each match As Match In matches
            Dim candidate = match.Groups(1).Value
            candidate = Regex.Replace(candidate, "[-\s]", "") ' Remove dash and space
            candidate = candidate.ToUpper()

            If Regex.IsMatch(candidate, "^[A-Z]{2,3}[0-9]{4,}$") AndAlso candidate.Length <= 10 Then
                Return candidate
            End If
        Next

        Return ""
    End Function


    Private Sub LoadCsvData(path As String)

        ShowStatus("Loading CSV data, please wait...", True)

        Application.DoEvents() ' Force UI update
        Dim dt As New DataTable()

        ' Setup DB connection (update if needed)
        Dim connString As String = "Server=localhost;Database=ucbdata_usedcard_production;Uid=root;Pwd=;"

        Using conn As New MySqlConnection(connString)
            conn.Open()

            Using parser As New TextFieldParser(path)
                parser.TextFieldType = FieldType.Delimited
                parser.SetDelimiters(",")

                Dim isHeader As Boolean = True

                While Not parser.EndOfData
                    Dim fields = parser.ReadFields()

                    If isHeader Then
                        For Each field In fields
                            dt.Columns.Add(field.Trim())
                        Next
                        dt.Columns.Add("Reference")   ' TRN from Description
                        dt.Columns.Add("Invoice#")    ' Invoice number from Description
                        dt.Columns.Add("TransID")     ' Order ID or DB match
                        dt.Columns.Add("Tooltip")     ' Internal validation info
                        isHeader = False
                    Else
                        ' Skip empty rows
                        If fields.All(Function(f) String.IsNullOrWhiteSpace(f)) Then
                            Continue While
                        End If

                        Dim newRow = dt.NewRow()
                        For i As Integer = 0 To fields.Length - 1
                            newRow(i) = fields(i)
                        Next

                        ' Extract useful data from the "Description" field
                        Dim desc As String = If(newRow.Table.Columns.Contains("Description"), newRow("Description").ToString(), "")
                        Dim invNumber As String = ExtractInvoiceNumber(desc)
                        Dim transID As String = ExtractOrderID(desc)

                        newRow("Reference") = ExtractTRN(desc)
                        newRow("Invoice#") = invNumber
                        newRow("TransID") = transID

                        ' Fallback: If no TransID but we have Invoice#, try DB lookup
                        If String.IsNullOrWhiteSpace(transID) AndAlso Not String.IsNullOrWhiteSpace(invNumber) Then
                            Dim cmd As New MySqlCommand("SELECT id FROM loop_transaction_buyer WHERE inv_number = @inv", conn)
                            cmd.Parameters.AddWithValue("@inv", invNumber)

                            Dim dbTransID = cmd.ExecuteScalar()
                            If dbTransID IsNot Nothing Then
                                newRow("TransID") = dbTransID.ToString()
                                newRow("Tooltip") = "" ' found
                            Else
                                newRow("Tooltip") = "TransID not found for invoice"
                            End If
                        ElseIf String.IsNullOrWhiteSpace(invNumber) OrElse invNumber.Length > 10 Then
                            newRow("Tooltip") = "Invalid"
                        Else
                            newRow("Tooltip") = ""
                        End If

                        dt.Rows.Add(newRow)
                    End If
                End While
            End Using
        End Using

        ' Bind to DataGridView
        dgvData.DataSource = dt

        ' Hide Tooltip column
        If dgvData.Columns.Contains("Tooltip") Then
            dgvData.Columns("Tooltip").Visible = False
        End If

        ' Highlight rows with Tooltip (invalid rows)
        For Each row As DataGridViewRow In dgvData.Rows
            If Not row.IsNewRow Then
                Dim tooltip = row.Cells("Tooltip").Value?.ToString()
                If Not String.IsNullOrEmpty(tooltip) Then
                    row.DefaultCellStyle.BackColor = Color.FromArgb(255, 230, 230) ' Light red
                End If
            End If
        Next
         ResetStatusUI()
    End Sub


    'Private Sub LoadCsvData(path As String)
    '    Dim dt As New DataTable()

    '    ' Setup DB connection
    '    Dim connString As String = "Server=localhost;Database=ucbdata_usedcard_production;Uid=root;Pwd=;"

    '    Using conn As New MySqlConnection(connString)
    '        conn.Open()

    '        Using parser As New TextFieldParser(path)
    '            parser.TextFieldType = FieldType.Delimited
    '            parser.SetDelimiters(",")

    '            Dim isHeader As Boolean = True

    '            While Not parser.EndOfData
    '                Dim fields = parser.ReadFields()

    '                If isHeader Then
    '                    For Each field In fields
    '                        dt.Columns.Add(field.Trim())
    '                    Next
    '                    dt.Columns.Add("Reference")   ' TRN
    '                    dt.Columns.Add("Invoice#")    ' extracted from description
    '                    dt.Columns.Add("TransID")     ' extracted from description or DB
    '                    dt.Columns.Add("Tooltip")     ' for internal check only
    '                    isHeader = False
    '                Else
    '                    If fields.All(Function(f) String.IsNullOrWhiteSpace(f)) Then
    '                        Continue While
    '                    End If

    '                    Dim newRow = dt.NewRow()
    '                    For i As Integer = 0 To fields.Length - 1
    '                        newRow(i) = fields(i)
    '                    Next

    '                    Dim desc As String = If(newRow.Table.Columns.Contains("Description"), newRow("Description").ToString(), "")
    '                    Dim invNumber As String = ExtractInvoiceNumber(desc)
    '                    Dim transID As String = ExtractOrderID(desc)

    '                    newRow("Reference") = ExtractTRN(desc)
    '                    newRow("Invoice#") = invNumber
    '                    newRow("TransID") = transID

    '                    If String.IsNullOrWhiteSpace(transID) AndAlso Not String.IsNullOrWhiteSpace(invNumber) Then
    '                        ' Try to fetch from DB using inv_number
    '                        Dim cmd As New MySqlCommand("SELECT id FROM loop_transaction_buyer WHERE inv_number = @inv", conn)
    '                        cmd.Parameters.AddWithValue("@inv", invNumber)

    '                        Dim dbTransID = cmd.ExecuteScalar()
    '                        If dbTransID IsNot Nothing Then
    '                            newRow("TransID") = dbTransID.ToString()
    '                            newRow("Tooltip") = "" ' valid
    '                        Else
    '                            newRow("Tooltip") = "TransID not found for invoice"
    '                        End If
    '                    ElseIf String.IsNullOrWhiteSpace(invNumber) OrElse invNumber.Length > 10 Then
    '                        newRow("Tooltip") = "Invalid"
    '                    Else
    '                        newRow("Tooltip") = ""
    '                    End If

    '                    dt.Rows.Add(newRow)
    '                End If
    '            End While
    '        End Using
    '    End Using

    '    dgvData.DataSource = dt

    '    ' Hide the Tooltip column (optional)
    '    If dgvData.Columns.Contains("Tooltip") Then
    '        dgvData.Columns("Tooltip").Visible = False
    '    End If

    '    ' Apply row coloring for invalid invoice rows
    '    For Each row As DataGridViewRow In dgvData.Rows
    '        If Not row.IsNewRow Then
    '            Dim tooltip = row.Cells("Tooltip").Value?.ToString()
    '            If Not String.IsNullOrEmpty(tooltip) Then
    '                row.DefaultCellStyle.BackColor = Color.FromArgb(255, 230, 230) ' Light red
    '            End If
    '        End If
    '    Next
    'End Sub





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
                .DropDownStyle = ComboBoxStyle.DropDown
                .AutoCompleteMode = AutoCompleteMode.SuggestAppend
                .AutoCompleteSource = AutoCompleteSource.ListItems
                .ForeColor = Color.DarkSlateGray
            End With


        Catch ex As Exception
            MessageBox.Show("DB load error: " & ex.Message)
        End Try
    End Sub



End Class
