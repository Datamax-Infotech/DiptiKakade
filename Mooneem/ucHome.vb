Imports System.Drawing
Imports System.Drawing.Drawing2D
Imports System.Windows.Forms
Imports System.Windows.Forms.DataVisualization.Charting
Imports FontAwesome.Sharp
Imports MySql.Data.MySqlClient

Public Class ucHome
    Inherits UserControl

    Public Sub New()
        InitializeComponent()
        BuildUI()
    End Sub

    Private Sub BuildUI()
        Me.Dock = DockStyle.Fill

        Dim tlp As New TableLayoutPanel With {
            .Dock = DockStyle.Fill,
            .ColumnCount = 3,
            .RowCount = 2,
            .Padding = New Padding(20)
        }

        ' 3 columns
        For i = 0 To 2
            tlp.ColumnStyles.Add(New ColumnStyle(SizeType.Percent, 33.33!))
        Next
        tlp.RowStyles.Add(New RowStyle(SizeType.Absolute, 140))
        tlp.RowStyles.Add(New RowStyle(SizeType.Percent, 100))

        ' Use consistent, dashboard-matching light colors
        Dim baseColor = Color.White        ' Light blue-gray
        Dim hoverColor = Color.FromArgb(225, 235, 250)       ' Slightly darker on hover
        Dim borderColor = Color.FromArgb(180, 200, 230)      ' Soft border

        ' Cards
        'Dim card1 = CreateCard("Sales", "₹1.2M", cardColor, hoverColor, borderColor)
        'Dim card2 = CreateCard("Orders", "2,340", cardColor, hoverColor, borderColor)
        'Dim card3 = CreateCard("Customers", "480", cardColor, hoverColor, borderColor)
        Dim card1 = CreateCard("Sales", "₹1.2M", baseColor, hoverColor, borderColor, IconChar.ChartLine)
        Dim card2 = CreateCard("Orders", "2,340", baseColor, hoverColor, borderColor, IconChar.ShoppingCart)
        Dim card3 = CreateCard("Customers", "480", baseColor, hoverColor, borderColor, IconChar.Users)

        tlp.Controls.Add(card1, 0, 0)
        tlp.Controls.Add(card2, 1, 0)
        tlp.Controls.Add(card3, 2, 0)

        ' Chart
        'Dim chart1 As New Chart With {.Dock = DockStyle.Fill, .BackColor = Color.White}
        'chart1.ChartAreas.Add(New ChartArea("Area"))
        'Dim series As New Series("Sales") With {
        '    .ChartType = SeriesChartType.Spline,
        '    .BorderWidth = 3,
        '    .Color = Color.FromArgb(0, 150, 136)
        '}
        'Dim months = {"Jan", "Feb", "Mar", "Apr", "May"}
        'Dim vals = {120, 90, 150, 180, 210}
        'For i = 0 To months.Length - 1
        '    series.Points.AddXY(months(i), vals(i))
        'Next
        'chart1.Series.Add(series)

        'tlp.SetColumnSpan(chart1, 3)
        'tlp.Controls.Add(chart1, 0, 1)

        Me.Controls.Add(tlp)
    End Sub

    Private Function CreateCard(title As String, value As String, baseColor As Color, hoverColor As Color, borderColor As Color, icon As IconChar) As Panel
        Dim card As New Panel With {
        .Height = 120,
        .Dock = DockStyle.Fill,
        .Margin = New Padding(10),
        .BackColor = baseColor,
        .Tag = baseColor
    }

        ' Hover effect
        AddHandler card.MouseEnter, Sub() card.BackColor = hoverColor
        AddHandler card.MouseLeave, Sub() card.BackColor = DirectCast(card.Tag, Color)

        ' Rounded border
        AddHandler card.Paint, Sub(sender, e)
                                   Dim g = e.Graphics
                                   g.SmoothingMode = SmoothingMode.AntiAlias
                                   Dim r = New Rectangle(0, 0, card.Width - 1, card.Height - 1)
                                   Dim path As New GraphicsPath()
                                   Dim radius = 10
                                   path.AddArc(r.Left, r.Top, radius, radius, 180, 90)
                                   path.AddArc(r.Right - radius, r.Top, radius, radius, 270, 90)
                                   path.AddArc(r.Right - radius, r.Bottom - radius, radius, radius, 0, 90)
                                   path.AddArc(r.Left, r.Bottom - radius, radius, radius, 90, 90)
                                   path.CloseFigure()
                                   g.DrawPath(New Pen(borderColor, 1.5F), path)
                               End Sub

        ' Title
        Dim lblTitle As New Label With {
        .Text = title.ToUpper(),
        .Dock = DockStyle.Top,
        .Font = New Font("Verdana", 9, FontStyle.Bold),
        .ForeColor = Color.Gray,
        .Height = 25,
        .TextAlign = ContentAlignment.BottomLeft,
        .Padding = New Padding(20, 0, 0, 0)
    }

        ' Icon + Value in a row
        Dim pnlValueRow As New Panel With {
        .Dock = DockStyle.Fill,
        .Padding = New Padding(20, 40, 0, 0)
    }

        Dim lblValue As New Label With {
        .Text = value,
        .AutoSize = True,
        .Font = New Font("Verdana", 22, FontStyle.Bold),
        .ForeColor = Color.FromArgb(0, 150, 136),
        .Dock = DockStyle.Left,
        .TextAlign = ContentAlignment.MiddleLeft
    }

        Dim iconPic As New IconPictureBox With {
        .IconChar = icon,
        .IconColor = Color.FromArgb(0, 150, 136),
        .IconSize = 32,
        .Size = New Size(40, 40),
        .Dock = DockStyle.Left,
        .Margin = New Padding(10, 12, 0, 0),
        .BackColor = Color.Transparent
    }


        pnlValueRow.Controls.Add(lblValue)
        pnlValueRow.Controls.Add(iconPic)
        card.Controls.Add(pnlValueRow)
        card.Controls.Add(lblTitle)

        Return card
    End Function


End Class


'Private Sub btnUpload_Click(sender As Object, e As EventArgs) Handles btnUpload.Click
'    Dim ofd As New OpenFileDialog With {.Filter = "PDF Files|*.pdf"}
'    If ofd.ShowDialog() = DialogResult.OK Then
'        Dim pdfText As New Text.StringBuilder()

'        ' 🔹 Read PDF text
'        Using doc = PdfDocument.Open(ofd.FileName)
'            For Each page In doc.GetPages()
'                pdfText.AppendLine(page.Text)
'            Next
'        End Using

'        ' 🔹 Regex: match whole invoice row until Total Charges
'        Dim pattern As String = "(INV\d+UCB.*?Total Charges)"
'        Dim matches = Regex.Matches(pdfText.ToString(), pattern, RegexOptions.Singleline)

'        ' 🔹 Prepare DataTable
'        Dim dt As New DataTable()
'        dt.Columns.Add("Invoice #")
'        dt.Columns.Add("Trans #")
'        dt.Columns.Add("Amount")

'        For Each m As Match In matches
'            Dim line As String = m.Groups(1).Value.Replace(vbCr, " ").Replace(vbLf, " ")

'            ' Extract Invoice #
'            Dim invoiceMatch = Regex.Match(line, "(INV\d+UCB)")
'            Dim invoiceNo As String = If(invoiceMatch.Success, invoiceMatch.Value, "")

'            ' Extract Amount
'            Dim amountMatch = Regex.Match(line, "(\$\s*\d{1,3}(?:,\d{3})*(?:\.\d{2}))\s*Total Charges")
'            Dim amount As String = If(amountMatch.Success, amountMatch.Groups(1).Value, "")

'            ' Extract all numbers in the line
'            Dim numMatches = Regex.Matches(line, "\b\d+\b")
'            Dim transNo As String = ""

'            ' Look for duplicate adjacent numbers
'            For i As Integer = 0 To numMatches.Count - 2
'                If numMatches(i).Value = numMatches(i + 1).Value Then
'                    transNo = numMatches(i).Value
'                    Exit For
'                End If
'            Next

'            dt.Rows.Add(invoiceNo, transNo, amount)
'        Next

'        grid.DataSource = dt
'    End If
'End Sub


'Private Sub btnUpload_Click(sender As Object, e As EventArgs) Handles btnUpload.Click
'    Dim ofd As New OpenFileDialog With {.Filter = "PDF Files|*.pdf"}
'    If ofd.ShowDialog() = DialogResult.OK Then
'        Dim pdfText As New Text.StringBuilder()

'        ' 🔹 Read PDF text
'        Using doc = PdfDocument.Open(ofd.FileName)
'            For Each page In doc.GetPages()
'                pdfText.AppendLine(page.Text)
'            Next
'        End Using

'        ' 🔹 Regex: Invoice #, optional Shipment #, and Amount
'        ' Example match: INV03691UCB 44142 ... $ 781.74 Total Charges
'        Dim pattern As String = "(INV\d+UCB)(?:\s+(\d+))?.*?(\$\s*\d{1,3}(?:,\d{3})*(?:\.\d{2}))\s*Total Charges"
'        Dim matches = Regex.Matches(pdfText.ToString(), pattern, RegexOptions.Singleline)

'        ' 🔹 Prepare DataTable for grid
'        Dim dt As New DataTable()
'        dt.Columns.Add("Invoice #")
'        dt.Columns.Add("Shipment #")
'        dt.Columns.Add("Amount")

'        For Each m As Match In matches
'            Dim invoiceNo As String = m.Groups(1).Value
'            Dim shipmentNo As String = m.Groups(2).Value
'            Dim amount As String = m.Groups(3).Value

'            dt.Rows.Add(invoiceNo, shipmentNo, amount)
'        Next

'        grid.DataSource = dt
'    End If
'End Sub





'Private Sub LoadData()
'    Try
'        conn.Open()
'        Dim fromDate As Date = fromDatePicker.Value.Date
'        Dim toDate As Date = toDatePicker.Value.Date.AddDays(1).AddSeconds(-1)


'        Dim sql As String = $"SELECT 
'                    ltb.id AS TransactionID,
'                    lw.company_name AS CompanyName,
'                    lw.quick_books_company_name AS QuickBooksCompanyName,
'                    ltb.inv_number AS InvoiceNumber,
'                    DATE_FORMAT(
'                        CASE 
'                            WHEN ltb.inv_date_of IS NULL OR ltb.inv_date_of = '' 
'                            THEN lid.timestamp 
'                            ELSE STR_TO_DATE(ltb.inv_date_of, '%m/%d/%Y') 
'                        END, 
'                        '%Y-%m-%d %H:%i:%s'
'                    ) AS InvoiceDate,
'                    ltb.inv_amount AS InvoiceAmount,
'                   ltb.qb_import_done_flg AS InvoiceCreatedFlag, ltb.inv_entered AS InvoiceUploadedFlag
'                FROM loop_transaction_buyer ltb
'                LEFT JOIN loop_invoice_details lid 
'                    ON lid.trans_rec_id = ltb.id
'                INNER JOIN loop_warehouse lw 
'                    ON lw.id = ltb.warehouse_id
'                WHERE ltb.Leaderboard = 'UCBZW'
'                  AND ltb.ignore < 1
'                  AND (
'                    CASE 
'                        WHEN ltb.inv_date_of IS NULL OR ltb.inv_date_of = '' 
'                        THEN lid.timestamp 
'                        ELSE STR_TO_DATE(CONCAT(ltb.inv_date_of, ' 00:00:00'), '%m/%d/%Y %H:%i:%s') 
'                    END
'                ) BETWEEN '{fromDate:yyyy-MM-dd HH:mm:ss}' AND '{toDate:yyyy-MM-dd HH:mm:ss}'
'                            ORDER BY ltb.id DESC;"
'        'AND (
'        '                CASE 
'        '                    WHEN ltb.inv_date_of IS NULL OR ltb.inv_date_of = '' 
'        '                    THEN lid.timestamp 
'        '                    ELSE STR_TO_DATE(ltb.inv_date_of, '%m/%d/%Y') 
'        '                END
'        '            ) BETWEEN '{fromDate:yyyy-MM-dd}' AND '{toDate:yyyy-MM-dd}'

'        Dim da As New MySqlDataAdapter(sql, conn)
'        dt.Clear()
'        da.Fill(dt)

'        If dt.Rows.Count = 0 Then
'            MessageBox.Show("No records found!", "Info",
'                    MessageBoxButtons.OK, MessageBoxIcon.Information)
'            dataview.DataSource = Nothing
'            Return
'        End If

'        ' ② Connect to QuickBooks and filter out invoices that already exist
'        FilterOutInvoicesAlreadyInQB()
'        '──────────────────── bind then custom‑build the grid ──────────────
'        dataview.DataSource = Nothing
'        dataview.Columns.Clear()
'        dataview.DataSource = dt
'        AddHandler dataview.CellPainting, AddressOf DataGridView_CellPainting

'        ' ① CHECKBOX column (first)
'        Dim cbCol As New DataGridViewCheckBoxColumn With {
'        .Name = "Select",
'        .HeaderText = "",
'        .Width = 40,
'        .DefaultCellStyle = New DataGridViewCellStyle With {
'            .Alignment = DataGridViewContentAlignment.MiddleCenter}
'    }
'        dataview.Columns.Insert(0, cbCol)

'        ' ② EDIT button column (second)
'        Dim btnCol As New DataGridViewButtonColumn With {
'        .Name = "EditRow",
'        .HeaderText = "Action",
'        .Text = "✎",
'        .UseColumnTextForButtonValue = True,
'        .Width = 60
'    }
'        dataview.Columns.Insert(1, btnCol)

'        Dim uploadCol As New DataGridViewButtonColumn With {
'                .Name = "UploadInvoice",
'                .HeaderText = "Upload",
'                .Width = 60
'            }

'        dataview.Columns.Insert(2, uploadCol)


'        ' ④.1 STATUS column
'        Dim statusCol As New DataGridViewTextBoxColumn With {
'            .Name = "Status",
'            .HeaderText = "Status",
'            .Width = 300,
'            .ReadOnly = True
'        }
'        dataview.Columns.Insert(3, statusCol)

'        ' ④ Friendly headers + widths
'        Dim widths = New Dictionary(Of String, Integer) From {
'        {"TransactionID", 80},
'        {"CompanyName", 300},
'        {"QuickBooksCompanyName", 300},
'        {"InvoiceNumber", 90},
'        {"InvoiceDate", 90},
'        {"InvoiceAmount", 95}
'    }

'        For Each kvp In widths
'            With dataview.Columns(kvp.Key)
'                .HeaderText = kvp.Key.Replace("TransactionID", "Trans ID").
'                                     Replace("CompanyName", "Company Name").
'                                     Replace("QuickBooksCompanyName", "QB Company Name").
'                                     Replace("InvoiceNumber", "Invoice #").
'                                     Replace("InvoiceDate", "Inv Date").
'                                     Replace("InvoiceAmount", "Inv Amount")
'                .AutoSizeMode = DataGridViewAutoSizeColumnMode.None
'                .Width = kvp.Value
'            End With
'        Next

'        ' ⑤ Allow editing in all except fixed columns
'        For Each col As DataGridViewColumn In dataview.Columns
'            col.ReadOnly = (col.Name = "EditRow" OrElse col.Name = "TransactionID")
'        Next

'        ' ⑥ Hide Upload button if invoice not created
'        For Each row As DataGridViewRow In dataview.Rows
'            Dim invoiceCreated As Boolean = False
'            If row.Cells("InvoiceCreatedFlag").Value IsNot DBNull.Value Then
'                invoiceCreated = Convert.ToBoolean(row.Cells("InvoiceCreatedFlag").Value)
'            End If

'            ' Always show 📤



'            Dim invoiceUploaded As Boolean = False
'            If row.Cells("InvoiceUploadedFlag").Value IsNot DBNull.Value Then
'                invoiceUploaded = Convert.ToBoolean(row.Cells("InvoiceUploadedFlag").Value)
'            End If

'            If invoiceUploaded Then

'                ' Fully done, disable (optional)
'                row.Cells("UploadInvoice").ReadOnly = False
'            ElseIf invoiceCreated Then
'                ' Ready to upload
'                row.Cells("UploadInvoice").ReadOnly = True
'            Else
'                ' Not created, keep readonly
'                row.Cells("UploadInvoice").ReadOnly = True
'            End If

'        Next



'        ' Hide the flag column (optional)
'        dataview.Columns("InvoiceUploadedFlag").Visible = False
'        dataview.Columns("InvoiceCreatedFlag").Visible = False
'        ' ⑦ Tick every row & add header checkbox
'        AddSelectAllCheckbox()
'        Dim hdr = dataview.Controls.OfType(Of CheckBox)().First(Function(cb) cb.Name = "HeaderCheckBox")
'        'hdr.Checked = True

'    Catch ex As Exception
'        MessageBox.Show("DB Error: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
'    Finally
'        conn.Close()
'    End Try
'End Sub

'Private Sub btnFilter_Click(sender As Object, e As EventArgs)
'    ShowStatus("🔄 Validating rows, please wait...")

'    Task.Run(Sub()
'                 ' background thread
'                 LoadData()
'                 ValidateAndHighlightRows()

'                 ' update UI back on main thread
'                 Me.Invoke(Sub()
'                               ShowStatus("✅ Validation complete.")
'                           End Sub)
'             End Sub)
'    'LoadData()

'    'BeginInvoke(Sub()
'    '                ShowStatus("🔄 Validating rows, please wait...")
'    '                ValidateAndHighlightRows()
'    '            End Sub)
'End Sub


'Private Sub FilterOutInvoicesAlreadyInQB()
'    Dim sessionManager As QBSessionManager = Nothing

'    Try
'        'MsgBox("① Opening QB session...")
'        sessionManager = New QBSessionManager()
'        sessionManager.OpenConnection("", "Mooneem App")
'        sessionManager.BeginSession("", ENOpenMode.omDontCare)
'        ' MsgBox("② QB session opened successfully")

'        ' Iterate backward to safely remove rows
'        For i As Integer = dt.Rows.Count - 1 To 0 Step -1
'            Dim row As DataRow = dt.Rows(i)
'            Dim QBcompanyName As String = row("QuickBooksCompanyName")?.ToString()?.Trim()
'            Dim invoiceNumber As String = row("InvoiceNumber")?.ToString()?.Trim()

'            ' MsgBox("③ Checking invoice: " & invoiceNumber & " | QB Company: " & QBcompanyName)

'            If Not String.IsNullOrWhiteSpace(QBcompanyName) AndAlso Not String.IsNullOrWhiteSpace(invoiceNumber) Then
'                Try
'                    If InvoiceExistsInQB(QBcompanyName, invoiceNumber, sessionManager) Then
'                        ' MsgBox("④ Invoice FOUND in QB → Removing from grid: " & invoiceNumber)
'                        dt.Rows.Remove(row)
'                    Else
'                        'MsgBox("④ Invoice NOT found in QB → Keeping in grid: " & invoiceNumber)
'                    End If
'                Catch ex As Exception
'                    ' MsgBox("❌ Error checking invoice " & invoiceNumber & ": " & ex.Message)
'                End Try
'            Else
'                'MsgBox("⚠ Skipping row because Company/Invoice number is blank")
'            End If
'        Next

'    Catch ex As Exception
'        MessageBox.Show("Error checking QuickBooks invoices: " & ex.Message, "QB Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
'    Finally
'        If sessionManager IsNot Nothing Then
'            Try
'                ' MsgBox("⑤ Closing QB session...")
'                sessionManager.EndSession()
'                sessionManager.CloseConnection()
'                'MsgBox("⑥ QB session closed successfully")
'            Catch
'                MsgBox("⚠ Error while closing QB session")
'            End Try
'        End If
'    End Try
'End Sub


'Private Function InvoiceExistsInQB(companyName As String, invoiceNumber As String, sessionManager As QBSessionManager) As Boolean
'    Try
'        ' MsgBox("🔎 Entering InvoiceExistsInQB for: " & invoiceNumber)

'        ' ① Create request
'        Dim msgSetRequest As IMsgSetRequest = sessionManager.CreateMsgSetRequest("US", 12, 0)
'        msgSetRequest.Attributes.OnError = ENRqOnError.roeContinue
'        ' MsgBox("Step 1: Request created")

'        ' ② Add invoice query filter by RefNumber
'        Dim invoiceQuery As IInvoiceQuery = msgSetRequest.AppendInvoiceQueryRq()
'        invoiceQuery.ORInvoiceQuery.InvoiceFilter.ORRefNumberFilter.RefNumberFilter.MatchCriterion.SetValue(ENMatchCriterion.mcContains)
'        invoiceQuery.ORInvoiceQuery.InvoiceFilter.ORRefNumberFilter.RefNumberFilter.RefNumber.SetValue(invoiceNumber.Trim())
'        'MsgBox("Step 2: Query filter set → " & invoiceNumber)

'        ' ③ Send request
'        Dim msgSetResponse As IMsgSetResponse = sessionManager.DoRequests(msgSetRequest)
'        'MsgBox("Step 3: Request sent to QB")

'        ' ④ Parse response
'        If msgSetResponse IsNot Nothing AndAlso msgSetResponse.ResponseList.Count > 0 Then
'            Dim response As IResponse = msgSetResponse.ResponseList.GetAt(0)
'            'MsgBox("Step 4: Got response from QB. StatusCode=" & response.StatusCode)

'            If response.StatusCode = 0 AndAlso response.Detail IsNot Nothing Then
'                Dim invoiceRetList As IInvoiceRetList = TryCast(response.Detail, IInvoiceRetList)
'                If invoiceRetList IsNot Nothing AndAlso invoiceRetList.Count > 0 Then
'                    ' MsgBox("✅ Invoice FOUND in QB: " & invoiceNumber)
'                    Return True
'                Else
'                    ' MsgBox("❌ Invoice NOT found in QB: " & invoiceNumber)
'                End If
'            Else
'                'MsgBox("⚠ QB returned empty detail for: " & invoiceNumber)
'            End If
'        Else
'            MsgBox("⚠ No response list from QB for: " & invoiceNumber)
'        End If

'    Catch ex As Exception
'        MessageBox.Show("Error in InvoiceExistsInQB: " & ex.Message, "QB Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
'    End Try

'    ' ❌ Not found or error
'    Return False
'End Function

'Private Function GetBtypeFromInventory(loopBoxID As Integer) As String
'    Dim btype As String = "Other"
'    Try
'        Using conn2 As New MySqlConnection(B2BconnString)
'            conn2.Open()

'            ' Query to get box_type from inventory
'            Dim query As String = "SELECT box_type FROM inventory WHERE loops_id = @LoopBoxID"

'            Using cmd As New MySqlCommand(query, conn2)
'                cmd.Parameters.AddWithValue("@LoopBoxID", loopBoxID)
'                Dim result As Object = cmd.ExecuteScalar()

'                If result IsNot Nothing Then
'                    Dim boxType As String = result.ToString()
'                    ' Map boxType to btype
'                    Select Case boxType
'                        Case "Gaylord", "GaylordUCB", "Loop", "PresoldGaylord", "Gaylord Totes"
'                            btype = "Gaylord Totes"
'                        Case "LoopShipping", "Box", "Boxnonucb", "Presold", "Medium", "Large", "Xlarge"
'                            btype = "Shipping Boxes"
'                        Case "SupersackUCB", "SupersacknonUCB"
'                            btype = "Supersacks"
'                        Case "PalletsUCB", "PalletsnonUCB"
'                            btype = "Pallets"
'                        Case "Pallets"
'                            btype = "Pallets"
'                        Case "DrumBarrelUCB", "DrumBarrelnonUCB"
'                            btype = "Drums/Barrels/IBCs"
'                        Case "Recycling"
'                            btype = "Recycling"
'                        Case "Delivery"
'                            btype = "Delivery"
'                        Case "Other"
'                            btype = "Other"
'                        Case Else
'                            btype = "Other"
'                    End Select
'                End If
'            End Using
'        End Using
'    Catch ex As Exception
'        MessageBox.Show("Error retrieving btype: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
'    End Try
'    Return btype
'End Function



'Dim sql As String = $"SELECT 
'        ltb.id AS TransactionID,
'        lw.company_name AS CompanyName,
'        lw.quick_books_company_name AS QuickBooksCompanyName,
'        ltb.inv_number AS InvoiceNumber,
'        DATE_FORMAT(
'            CASE 
'                WHEN ltb.inv_date_of IS NULL OR ltb.inv_date_of = '' 
'                THEN lid.timestamp 
'                ELSE STR_TO_DATE(ltb.inv_date_of, '%m/%d/%Y') 
'            END, 
'            '%Y-%m-%d %H:%i:%s'
'        ) AS InvoiceDate,
'        ltb.inv_amount AS InvoiceAmount,
'        ltb.qb_import_done_flg AS InvoiceCreatedFlag,
'        ltb.inv_entered AS InvoiceUploadedFlag
'    FROM loop_transaction_buyer ltb
'    LEFT JOIN loop_invoice_details lid 
'        ON lid.trans_rec_id = ltb.id
'    INNER JOIN loop_warehouse lw 
'        ON lw.id = ltb.warehouse_id
'    WHERE ltb.Leaderboard = 'UCBZW'
'      AND ltb.ignore < 1
'      AND (
'        CASE 
'            WHEN ltb.inv_date_of IS NULL OR ltb.inv_date_of = '' 
'            THEN lid.timestamp 
'            ELSE STR_TO_DATE(CONCAT(ltb.inv_date_of, ' 00:00:00'), '%m/%d/%Y %H:%i:%s') 
'        END
'    ) BETWEEN '{fromDate:yyyy-MM-dd}' AND '{toDate:yyyy-MM-dd}'
'    ORDER BY ltb.id DESC;"
'Dim sql As String = $"SELECT 
'  wt.id AS TransactionID,
'  wt.transaction_date,
'  lw.company_name AS CompanyName,
'  ww.Name AS VendorName,
'  wt.invoice_number AS InvoiceNumber,
'  wt.transaction_date AS InvoiceDate,
'  wt.amount AS InvoiceAmount,
'  wt.make_receive_payment AS InvoiceCreatedFlag,
'  wt.made_payment AS InvoiceUploadedFlag
'FROM water_transaction wt
'INNER JOIN loop_warehouse lw
'  ON lw.id = wt.company_id
'INNER JOIN water_vendors ww
'  ON ww.id = wt.vendor_id
'WHERE ww.active_flg = 1
'  AND wt.make_receive_payment = 1
'  AND wt.made_payment = 1
'  AND wt.amount > 0
'  AND DATE(wt.transaction_date) BETWEEN '{fromDate:yyyy-MM-dd}' AND '{toDate:yyyy-MM-dd}'
'ORDER BY wt.id DESC;
'"

'Select Case
'  wt.id AS TransactionID,
'  wt.transaction_date,
'  lw.company_name As CompanyName,
'  ww.Name AS VendorName,
'  wt.invoice_number As InvoiceNumber,
'  wt.transaction_date AS InvoiceDate,
'  wt.amount As InvoiceAmount,
'  wt.make_receive_payment AS InvoiceCreatedFlag,
'  wt.made_payment AS InvoiceUploadedFlag
'FROM water_transaction wt
'INNER JOIN loop_warehouse lw
'  On lw.id = wt.company_id
'INNER JOIN water_vendors ww
'  On ww.id = wt.vendor_id
'WHERE ww.active_flg = 1
'  And wt.make_receive_payment = 1
'  And wt.made_payment = 1
'  And wt.amount > 0
'  And DATE(wt.transaction_date) BETWEEN '2025-05-01' AND '2025-09-08'
'ORDER BY wt.id DESC;

'Dim sql As String = $"SELECT 
'              wt.id AS TransactionID,
'              wt.transaction_date,
'              lw.company_name AS CompanyName,
'              ww.Name AS VendorName,
'              wt.invoice_number AS InvoiceNumber,
'              wt.transaction_date AS InvoiceDate,
'              wt.amount AS InvoiceAmount,
'              wt.make_receive_payment AS InvoiceCreatedFlag,
'              wt.made_payment AS InvoiceUploadedFlag
'            FROM water_transaction wt
'            INNER JOIN loop_warehouse lw
'              ON lw.id = wt.company_id
'            INNER JOIN water_vendors ww
'              ON ww.id = wt.vendor_id
'            WHERE ww.active_flg = 1
'              AND wt.make_receive_payment = 1
'              AND wt.made_payment = 1
'              AND wt.amount > 0
'              AND DATE(wt.transaction_date) BETWEEN '{fromDate:yyyy-MM-dd}' AND '{toDate:yyyy-MM-dd}'
'            ORDER BY wt.id DESC;
'            "

'Private Function FetchData(fromDate As Date, toDate As Date) As DataTable
'    Dim localDt As New DataTable()
'    Try
'        conn.Open()
'        'invoice_due_date
'        Dim sql As String = $"SELECT  
'                wt.id AS TransactionID,
'                wt.transaction_date,
'                lw.company_name AS CompanyName,
'                ww.Name AS VendorName,
'                wt.invoice_number AS InvoiceNumber,
'                 wt.invoice_date AS InvoiceDate,
'                wt.amount AS InvoiceAmount,
'                wt.make_receive_payment AS InvoiceCreatedFlag,
'                wt.made_payment AS InvoiceUploadedFlag,
'                wic.invoice_no AS WaterInvoiceNumber
'            FROM water_transaction wt
'            INNER JOIN loop_warehouse lw
'                ON lw.id = wt.company_id
'            INNER JOIN water_vendors ww
'                ON ww.id = wt.vendor_id
'            LEFT JOIN water_invoice_creation_history wic
'                ON wic.trans_rec_id = wt.id
'            WHERE ww.active_flg = 1
'                AND wt.make_receive_payment = 1
'                AND wt.made_payment = 1
'                AND wt.amount > 0
'                AND DATE(wt.transaction_date) BETWEEN '{fromDate:yyyy-MM-dd}' AND '{toDate:yyyy-MM-dd}'
'            ORDER BY wt.id DESC;
'            "


'        Dim da As New MySqlDataAdapter(sql, conn)
'        da.Fill(localDt)

'    Finally
'        conn.Close()
'    End Try
'    Return localDt
'End Function

''──────────── bind & build grid (UI thread only) ────────────
'Private Sub BindData(dt As DataTable)
'    If dt.Rows.Count = 0 Then
'        MessageBox.Show("No records found!", "Info",
'                        MessageBoxButtons.OK, MessageBoxIcon.Information)
'        DataView.DataSource = Nothing
'        Return
'    End If

'    ' ② Connect to QuickBooks and filter out invoices that already exist
'    'FilterOutInvoicesAlreadyInQB()

'    DataView.DataSource = Nothing
'    DataView.Columns.Clear()
'    DataView.DataSource = dt
'    AddHandler DataView.CellPainting, AddressOf DataGridView_CellPainting

'    ' ① CHECKBOX column (first)
'    Dim cbCol As New DataGridViewCheckBoxColumn With {
'        .Name = "Select",
'        .HeaderText = "",
'        .Width = 40,
'        .DefaultCellStyle = New DataGridViewCellStyle With {
'            .Alignment = DataGridViewContentAlignment.MiddleCenter}
'    }
'    DataView.Columns.Insert(0, cbCol)

'    ' ② EDIT button column (second)
'    Dim btnCol As New DataGridViewButtonColumn With {
'        .Name = "EditRow",
'        .HeaderText = "Action",
'        .Text = "✎",
'        .UseColumnTextForButtonValue = True,
'        .Width = 60
'    }
'    DataView.Columns.Insert(1, btnCol)

'    ' Upload button column
'    Dim uploadCol As New DataGridViewButtonColumn With {
'        .Name = "UploadInvoice",
'        .HeaderText = "Upload",
'        .Width = 60
'    }
'    DataView.Columns.Insert(2, uploadCol)

'    ' Status column
'    Dim statusCol As New DataGridViewTextBoxColumn With {
'        .Name = "Status",
'        .HeaderText = "Status",
'        .Width = 300,
'        .ReadOnly = True
'    }
'    DataView.Columns.Insert(3, statusCol)

'    ' Friendly headers + widths
'    Dim widths = New Dictionary(Of String, Integer) From {
'        {"TransactionID", 80},
'        {"CompanyName", 300},
'        {"VendorName", 300},
'        {"InvoiceNumber", 90},
'        {"InvoiceDate", 90},
'        {"InvoiceAmount", 95}
'    }

'    For Each kvp In widths
'        With DataView.Columns(kvp.Key)
'            .HeaderText = kvp.Key.Replace("TransactionID", "Trans ID").
'                                 Replace("CompanyName", "Company Name").
'                                 Replace("VendorName", "QB Company Name").
'                                 Replace("InvoiceNumber", "Invoice #").
'                                 Replace("InvoiceDate", "Inv Date").
'                                 Replace("InvoiceAmount", "Inv Amount")
'            .AutoSizeMode = DataGridViewAutoSizeColumnMode.None
'            .Width = kvp.Value
'        End With
'    Next

'    ' Make columns readonly except some
'    For Each col As DataGridViewColumn In DataView.Columns
'        col.ReadOnly = (col.Name = "EditRow" OrElse col.Name = "TransactionID")
'    Next

'    ' Hide Upload button depending on flags
'    For Each row As DataGridViewRow In DataView.Rows
'        Dim invoiceCreated As Boolean = False
'        If row.Cells("InvoiceCreatedFlag").Value IsNot DBNull.Value Then
'            invoiceCreated = Convert.ToBoolean(row.Cells("InvoiceCreatedFlag").Value)
'        End If

'        Dim invoiceUploaded As Boolean = False
'        If row.Cells("InvoiceUploadedFlag").Value IsNot DBNull.Value Then
'            invoiceUploaded = Convert.ToBoolean(row.Cells("InvoiceUploadedFlag").Value)
'        End If

'        If invoiceUploaded Then
'            row.Cells("UploadInvoice").ReadOnly = False
'        ElseIf invoiceCreated Then
'            row.Cells("UploadInvoice").ReadOnly = True
'        Else
'            row.Cells("UploadInvoice").ReadOnly = True
'        End If
'    Next

'    ' Hide flag columns
'    DataView.Columns("InvoiceUploadedFlag").Visible = False
'    DataView.Columns("InvoiceCreatedFlag").Visible = False

'    ' Add Select All checkbox
'    AddSelectAllCheckbox()
'    Dim hdr = DataView.Controls.OfType(Of CheckBox)().First(Function(cb) cb.Name = "HeaderCheckBox")
'End Sub


'Dim sql As String = "
'SELECT  ltb.id                              AS TransactionID,
'         lw.company_name                     AS CompanyName,
'         lw.quick_books_company_name         AS QuickBooksCompanyName,
'         ltb.loop_qb_invoice_no              AS InvoiceNumber,
'         ( SELECT MAX(timestamp)
'             FROM loop_invoice_details lid
'             WHERE lid.trans_rec_id = ltb.id ) AS InvoiceDate,
'         ltb.inv_amount                      AS InvoiceAmount,
'         ltb.qb_import_done_flg              AS InvoiceCreatedFlag,
'         ltb.inv_entered        AS InvoiceUploadedFlag
' FROM      loop_transaction_buyer ltb
' JOIN      loop_warehouse          lw  ON ltb.warehouse_id  = lw.id
' LEFT JOIN loop_bol_files          lbf ON ltb.id            = lbf.trans_rec_id
'    WHERE   year(ltb.transaction_date) > 2023
'    AND ltb.id IN (SELECT trans_rec_id FROM loop_invoice_details)
'    AND ltb.loop_qb_invoice_no LIKE 'ZW%' 
'    AND ltb.inv_employee != 'MNM'
'GROUP BY 
'    ltb.id, lw.company_name, lw.quick_books_company_name
'ORDER BY 
'    ltb.id DESC limit 10" ' DESC limit 10

' SELECT ltb.id AS TransactionID, lw.company_name AS CompanyName, lw.quick_books_company_name AS QuickBooksCompanyName, ltb.loop_qb_invoice_no AS InvoiceNumber, ( SELECT MAX(timestamp) FROM loop_invoice_details lid WHERE lid.trans_rec_id = ltb.id ) AS InvoiceDate, ltb.inv_amount AS InvoiceAmount, ltb.qb_import_done_flg AS InvoiceCreatedFlag, ltb.inv_entered AS InvoiceUploadedFlag FROM loop_transaction_buyer ltb INNER JOIN loop_warehouse lw ON ltb.warehouse_id = lw.id left join loop_bol_files lbf on ltb.id = lbf.trans_rec_id WHERE ltb.shipped = 1 and lbf.bol_shipment_received = 1 and inv_entered = 0 and ltb.ignore = 0 and ltb.no_invoice = 0 and ltb.id in (select trans_rec_id from loop_invoice_details) GROUP BY ltb.id ORDER BY ltb.id;



'Private Sub ValidateAndHighlightRows()
'    Cursor.Current = Cursors.WaitCursor
'    Dim totalRows As Integer = dataview.Rows.Count

'    ShowStatus($"Please wait, loading data... (0 of {totalRows})")

'    Dim sessionManager As QBSessionManager = Nothing

'    Try
'        sessionManager = New QBSessionManager()
'        sessionManager.OpenConnection("", "Mooneem App")
'        sessionManager.BeginSession("", ENOpenMode.omDontCare)

'        Dim currentRow As Integer = 0

'        For Each row As DataGridViewRow In dataview.Rows
'            currentRow += 1

'            ' ✅ Update message with row count
'            ShowStatus($"Please wait, loading data... ({currentRow} of {totalRows})")
'            Application.DoEvents() ' Allow UI to refresh the status label

'            Try
'                Dim buyerID As Integer = Convert.ToInt32(row.Cells("TransactionID").Value)
'                Dim QBcompanyName As String = row.Cells("QuickBooksCompanyName").Value?.ToString()?.Trim()
'                Dim invoiceNumber As String = row.Cells("InvoiceNumber").Value?.ToString()?.Trim()

'                Dim isValid As Boolean = True
'                Dim reasons As New List(Of String)

'                If String.IsNullOrWhiteSpace(QBcompanyName) OrElse Not CustomerExistsInQB(QBcompanyName, sessionManager) Then
'                    isValid = False
'                    reasons.Add("❌ QuickBooks customer not found or name is blank.")
'                End If

'                Dim repName As String = GetRep(buyerID)
'                If Not String.IsNullOrWhiteSpace(repName) AndAlso Not SalesRepExistsInQB(repName, sessionManager) Then
'                    isValid = False
'                    reasons.Add($"❌ Sales Rep '{repName}' not found in QuickBooks.")
'                End If

'                Dim itemDetails As List(Of ItemDetail) = GetItemDetailsFromDatabase(buyerID)
'                For Each item As ItemDetail In itemDetails
'                    If Not String.IsNullOrWhiteSpace(item.ClassRef) AndAlso Not ClassRefExistsInQB(item.ClassRef, sessionManager) Then
'                        isValid = False
'                        reasons.Add($"❌ ClassRef '{item.ClassRef}' not found in QuickBooks.")
'                        Exit For
'                    End If
'                Next


'                If Not String.IsNullOrWhiteSpace(invoiceNumber) AndAlso InvoiceAlreadyExists(invoiceNumber, sessionManager) Then
'                    isValid = False
'                    reasons.Add($"❌ Invoice '{invoiceNumber}' already exists in QuickBooks.")
'                End If

'                ' Apply styles
'                If isValid Then
'                    row.DefaultCellStyle.BackColor = Color.LightGreen
'                    row.DefaultCellStyle.SelectionBackColor = Color.LightGreen
'                    row.DefaultCellStyle.ForeColor = Color.Black
'                    row.DefaultCellStyle.SelectionForeColor = Color.Black
'                    row.Cells("Select").ReadOnly = False
'                    ' ✅ Set status text
'                    row.Cells("Status").Value = "✔ Validation Passed"
'                Else
'                    row.Cells("Select").ReadOnly = True

'                    row.DefaultCellStyle.SelectionBackColor = Color.FromArgb(255, 220, 220)
'                    row.DefaultCellStyle.BackColor = Color.FromArgb(255, 220, 220)
'                    row.DefaultCellStyle.ForeColor = Color.Black
'                    row.DefaultCellStyle.SelectionForeColor = Color.Black
'                    Dim tooltipText As String = "⛔ Validation Failed:" & vbCrLf & String.Join(vbCrLf & vbCrLf, reasons)
'                    For Each cell As DataGridViewCell In row.Cells
'                        cell.ToolTipText = tooltipText
'                    Next
'                    ' ❌ Set status text
'                    row.Cells("Status").Value = String.Join(" | ", reasons)
'                End If

'            Catch exRow As Exception
'                For Each cell As DataGridViewCell In row.Cells
'                    cell.ToolTipText = "⚠️ Unexpected error during validation."
'                Next
'            End Try
'        Next

'    Catch ex As Exception
'        MessageBox.Show("Error during validation: " & ex.Message, "QuickBooks Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
'    Finally
'        If sessionManager IsNot Nothing Then
'            Try
'                sessionManager.EndSession()
'                sessionManager.CloseConnection()
'            Catch
'            End Try
'        End If

'        'HideStatus()
'        'Cursor.Current = Cursors.Default
'        'dataview.Refresh()
'        Me.Invoke(Sub()
'                      HideStatus()
'                      Cursor.Current = Cursors.Default
'                      dataview.Refresh()
'                  End Sub)

'    End Try
'End Sub
'MessageBox.Show(sql, "SQL Query", MessageBoxButtons.OK, MessageBoxIcon.Information)
'AND (
'       CASE 
'           WHEN ltb.inv_date_of IS NULL OR ltb.inv_date_of = '' 
'           THEN lid.timestamp 
'           ELSE STR_TO_DATE(CONCAT(ltb.inv_date_of, ' 00:00:00'), '%m/%d/%Y %H:%i:%s') 
'       END
'   ) BETWEEN '{fromDate:yyyy-MM-dd}' AND '{toDate:yyyy-MM-dd}'