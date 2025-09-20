' ======================  InsertTransaction.vb  =======================
Imports MySql.Data.MySqlClient   ' ⇦  NuGet: MySql.Data
Imports QBFC12Lib
Imports System.Data
Imports System.Linq
Imports System.Net
Imports System.Net.Mail
Imports System.Runtime.InteropServices
Imports System.Threading
Imports System.IO
Imports System.Diagnostics
Imports OfficeOpenXml

Public Class UpdateInvoice
    Inherits UserControl

    '── DB objects ----------------------------------------------------------
    Dim conn As MySqlConnection
    Dim conn2 As MySqlConnection
    Dim command As MySqlCommand
    Dim reader As MySqlDataReader


    ' Set the connection string
    Dim connString As String = "Server=208.109.231.62;Database=usedcardboardbox_production;Uid=usedcardboardbox_production_usr;Pwd=YtoA#[I[^.Ay;"
    Dim B2BconnString As String = "Server=208.109.231.62;Database=usedcardboardbox_b2b;Uid=usedcardboardbox_b2b_usr;Pwd=0JX+o3u4PM_l;"
    'Dim connString As String = "Server=localhost;Database=ucbdata_usedcard_production;Uid=root;Pwd=;"
    'Dim B2BconnString As String = "Server=localhost;Database=ucbdata_usedcard_b2b;Uid=root;Pwd=;"
    Dim sessionManager As QBSessionManager
    Dim msgSetRequest As IMsgSetRequest
    Private ReadOnly dt As New DataTable()

    '── UI objects ----------------------------------------------------------
    Private WithEvents btnSubmit As Button
    Private WithEvents btnUpdate As Button
    Private dataview As DataGridView
    Private dataviewUploaded As DataGridView
    Private pnlOverlay As Panel, lblStatus As Label, lblUploadedHeader As Label
    ' Private btnSubmit As Button
    Private btnUploadSelected As Button ' 👈 new upload button
    Private btnExportExcel As Button ' 👈 new upload button
    Public Sub New()
        InitializeComponent()      ' Keeps the designer happy
        BuildUI()


    End Sub


    Private Function CheckConnection() As Boolean
        Try
            conn = New MySqlConnection(connString)
            conn.Open()
            conn2 = New MySqlConnection(B2BconnString)
            conn2.Open()
            conn.Close()
            Return True
        Catch ex As Exception
            MessageBox.Show("Connection Failed: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
            Return False
        End Try
    End Function




    Private Sub PositionHeaderCheckBox(sender As Object, e As PaintEventArgs)
        Dim cb As CheckBox = dataview.Controls.OfType(Of CheckBox)().
                         FirstOrDefault(Function(c) c.Name = "HeaderCheckBox")
        If cb Is Nothing Then Exit Sub

        ' Prevent crash if header cell isn't ready yet
        If dataview.ColumnCount = 0 OrElse dataview.Columns(0).HeaderCell Is Nothing Then Exit Sub

        Dim rect As Rectangle = dataview.GetCellDisplayRectangle(0, -1, True)

        ' Check if column is visible
        If rect.Width <= 0 OrElse rect.X < 0 Then
            cb.Visible = False
            Exit Sub
        End If

        cb.Visible = True
        cb.Location = New Point(
        rect.X + (rect.Width - cb.Width) \ 2,
        rect.Y + (rect.Height - cb.Height) \ 2
    )
    End Sub

    Private Sub RemoveHeaderCheckBox()
        Dim cb = dataview.Controls.OfType(Of CheckBox)().
             FirstOrDefault(Function(c) c.Name = "HeaderCheckBox")
        If cb IsNot Nothing Then dataview.Controls.Remove(cb)

        RemoveHandler dataview.Paint, AddressOf PositionHeaderCheckBox
    End Sub

    Private Sub AddSelectAllCheckbox()
        ' Remove any previous checkbox
        RemoveHeaderCheckBox()

        Dim headerCheck As New CheckBox With {
        .Name = "HeaderCheckBox",
        .Size = New Size(15, 15),
        .BackColor = Color.Transparent
    }

        ' Store it so we can move it during Paint
        dataview.Controls.Add(headerCheck)

        AddHandler headerCheck.CheckedChanged, AddressOf HeaderCheckBoxClicked
        AddHandler dataview.Paint, AddressOf PositionHeaderCheckBox
    End Sub

    Private Sub HeaderCheckBoxClicked(sender As Object, e As EventArgs)
        Dim headerCheckbox As CheckBox = CType(sender, CheckBox)

        ' Loop through all rows and set their checkbox values
        For Each row As DataGridViewRow In dataview.Rows
            row.Cells("Select").Value = headerCheckbox.Checked
        Next
    End Sub





    '──────────────── helper: nice buttons ────────────────────────────────
    Private Shared Function MakeButton(text As String) As Button
        Return New Button With {
            .Text = text,
            .Font = New Font("Verdana", 10, FontStyle.Bold),
            .Width = 130, .Height = 30,
            .BackColor = Color.FromArgb(0, 150, 136),
            .ForeColor = Color.White,
            .FlatStyle = FlatStyle.Flat,
            .Anchor = AnchorStyles.Right
        }
    End Function

    '──────────────── Build UI ─────────────────────────────────────────────

    Private datePicker As DateTimePicker = Nothing


    Private Sub Grid_CellClick(sender As Object, e As DataGridViewCellEventArgs)
        If e.RowIndex < 0 OrElse e.ColumnIndex < 0 Then Exit Sub

        Dim grid = DirectCast(sender, DataGridView)
        Dim col = grid.Columns(e.ColumnIndex)

        If col.Name = "InvoiceDate" Then
            ' Remove old picker if exists
            If datePicker IsNot Nothing Then
                RemoveHandler datePicker.CloseUp, AddressOf DatePicker_CloseUp
                grid.Controls.Remove(datePicker)
                datePicker.Dispose()
                datePicker = Nothing
            End If

            ' Create new date picker
            datePicker = New DateTimePicker With {
            .Format = DateTimePickerFormat.Short,
            .Visible = True
        }

            ' Set current cell value if exists
            If grid.CurrentCell.Value IsNot Nothing AndAlso IsDate(grid.CurrentCell.Value) Then
                datePicker.Value = CDate(grid.CurrentCell.Value)
            End If

            ' Set bounds and add to grid
            Dim rect = grid.GetCellDisplayRectangle(e.ColumnIndex, e.RowIndex, True)
            datePicker.Bounds = rect
            AddHandler datePicker.CloseUp, AddressOf DatePicker_CloseUp
            grid.Controls.Add(datePicker)
            datePicker.BringToFront()
            datePicker.Focus()
        End If
    End Sub

    Private Sub DatePicker_CloseUp(sender As Object, e As EventArgs)
        Dim picker = DirectCast(sender, DateTimePicker)
        If dataview.CurrentCell IsNot Nothing Then
            dataview.CurrentCell.Value = picker.Value.Date
        End If
        RemoveHandler picker.CloseUp, AddressOf DatePicker_CloseUp
        dataview.Controls.Remove(picker)
        picker.Dispose()
        datePicker = Nothing
    End Sub

    Private Sub ucYourControl_Load(sender As Object, e As EventArgs) Handles Me.Load

        Try


            If CheckConnection() Then
                LoadData()
                BeginInvoke(Sub()
                                ShowStatus("🔄 Validating rows, please wait...")
                                ValidateAndHighlightRows()
                            End Sub)
            End If

        Catch ex As Exception
            MessageBox.Show("Error initializing QuickBooks session: " & ex.Message, "QuickBooks Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
        ' Start loading the data normally

    End Sub

    Private Sub LoadData()
        Try
            conn.Open()
            ' SELECT ltb.id AS TransactionID, lw.company_name AS CompanyName, lw.quick_books_company_name AS QuickBooksCompanyName, ltb.loop_qb_invoice_no AS InvoiceNumber, ( SELECT MAX(timestamp) FROM loop_invoice_details lid WHERE lid.trans_rec_id = ltb.id ) AS InvoiceDate, ltb.inv_amount AS InvoiceAmount, ltb.qb_import_done_flg AS InvoiceCreatedFlag, ltb.inv_entered AS InvoiceUploadedFlag FROM loop_transaction_buyer ltb INNER JOIN loop_warehouse lw ON ltb.warehouse_id = lw.id left join loop_bol_files lbf on ltb.id = lbf.trans_rec_id WHERE ltb.shipped = 1 and lbf.bol_shipment_received = 1 and inv_entered = 0 and ltb.ignore = 0 and ltb.no_invoice = 0 and ltb.id in (select trans_rec_id from loop_invoice_details) GROUP BY ltb.id ORDER BY ltb.id;
            Dim sql As String = "SELECT ltb.id AS TransactionID, lw.company_name AS CompanyName, lw.quick_books_company_name AS QuickBooksCompanyName, ltb.loop_qb_invoice_no AS InvoiceNumber, 
                                    DATE_FORMAT((
                                        SELECT MAX(timestamp)
                                        FROM loop_invoice_details lid
                                        WHERE lid.trans_rec_id = ltb.id
                                    ), '%Y-%m-%d %H:%i:%s') AS InvoiceDate
                                    , ltb.inv_amount AS InvoiceAmount, ltb.qb_import_done_flg AS InvoiceCreatedFlag, ltb.inv_entered AS InvoiceUploadedFlag FROM loop_transaction_buyer ltb INNER JOIN loop_warehouse lw ON ltb.warehouse_id = lw.id left join loop_bol_files lbf on ltb.id = lbf.trans_rec_id 
                                    WHERE  year(ltb.transaction_date) > 2024 and ltb.shipped = 1 and lbf.bol_shipment_received = 1 and inv_entered = 0 and 
                                    ltb.ignore = 0 and ltb.no_invoice = 0 and ltb.id in (select trans_rec_id 
                                    from loop_invoice_details) AND ltb.loop_qb_invoice_no LIKE '%R'
                                      GROUP BY ltb.id ORDER BY ltb.id DESC;"

            Dim da As New MySqlDataAdapter(sql, conn)
            dt.Clear()
            da.Fill(dt)
            If Not dt.Columns.Contains("Status") Then
                dt.Columns.Add("Status", GetType(String))
            End If

            ' 🔹 Give it a default value (can be empty or "Pending")
            For Each row As DataRow In dt.Rows
                row("Status") = ""  ' or "Pending"
            Next
            If dt.Rows.Count = 0 Then
                MessageBox.Show("No records found!", "Info",
                        MessageBoxButtons.OK, MessageBoxIcon.Information)
                dataview.DataSource = Nothing
                Return
            End If

            '──────────────────── bind then custom‑build the grid ──────────────
            dataview.DataSource = Nothing
            dataview.Columns.Clear()
            dataview.DataSource = dt
            AddHandler dataview.CellPainting, AddressOf DataGridView_CellPainting
            RemoveHandler dataview.CellFormatting, AddressOf dataview_CellFormatting
            AddHandler dataview.CellFormatting, AddressOf dataview_CellFormatting


            ' Move Status column before TransactionID
            If dataview.Columns.Contains("Status") Then
                dataview.Columns("Status").DisplayIndex = dataview.Columns("TransactionID").DisplayIndex
            End If
            ' ① CHECKBOX column (first)
            Dim cbCol As New DataGridViewCheckBoxColumn With {
            .Name = "Select",
            .HeaderText = "",
            .Width = 40,
            .DefaultCellStyle = New DataGridViewCellStyle With {
                .Alignment = DataGridViewContentAlignment.MiddleCenter}
        }
            dataview.Columns.Insert(0, cbCol)

            ' ② EDIT button column (second)
            Dim btnCol As New DataGridViewButtonColumn With {
            .Name = "EditRow",
            .HeaderText = "Action",
            .Text = "✎",
            .UseColumnTextForButtonValue = True,
            .Width = 60
        }
            dataview.Columns.Insert(1, btnCol)


            Dim uploadCol As New DataGridViewButtonColumn With {
                    .Name = "UploadInvoice",
                    .HeaderText = "Upload",
                    .Width = 60
                }

            dataview.Columns.Insert(2, uploadCol)


            ' ④.1 STATUS column
            'Dim statusCol As New DataGridViewTextBoxColumn With {
            '    .Name = "Status",
            '    .HeaderText = "Status",
            '    .Width = 300,
            '    .ReadOnly = True
            '}
            'dataview.Columns.Insert(3, statusCol)

            ' ④ Friendly headers + widths
            Dim widths = New Dictionary(Of String, Integer) From {
            {"TransactionID", 80},
            {"CompanyName", 300},
            {"QuickBooksCompanyName", 300},
            {"InvoiceNumber", 90},
            {"InvoiceDate", 90},
            {"InvoiceAmount", 95}
        }

            For Each kvp In widths
                With dataview.Columns(kvp.Key)
                    .HeaderText = kvp.Key.Replace("TransactionID", "Trans ID").
                                         Replace("CompanyName", "Company Name").
                                         Replace("QuickBooksCompanyName", "QB Company Name").
                                         Replace("InvoiceNumber", "Invoice #").
                                         Replace("InvoiceDate", "Inv Date").
                                         Replace("InvoiceAmount", "Inv Amount")
                    .AutoSizeMode = DataGridViewAutoSizeColumnMode.None
                    .Width = kvp.Value
                End With
            Next

            ' ⑤ Allow editing in all except fixed columns
            For Each col As DataGridViewColumn In dataview.Columns
                col.ReadOnly = (col.Name = "EditRow" OrElse col.Name = "TransactionID")
            Next

            ' ⑥ Hide Upload button if invoice not created
            For Each row As DataGridViewRow In dataview.Rows
                Dim invoiceCreated As Boolean = False
                If row.Cells("InvoiceCreatedFlag").Value IsNot DBNull.Value Then
                    invoiceCreated = Convert.ToBoolean(row.Cells("InvoiceCreatedFlag").Value)
                End If

                ' Always show 📤



                Dim invoiceUploaded As Boolean = False
                If row.Cells("InvoiceUploadedFlag").Value IsNot DBNull.Value Then
                    invoiceUploaded = Convert.ToBoolean(row.Cells("InvoiceUploadedFlag").Value)
                End If

                If invoiceUploaded Then

                    ' Fully done, disable (optional)
                    row.Cells("UploadInvoice").ReadOnly = False
                ElseIf invoiceCreated Then
                    ' Ready to upload
                    row.Cells("UploadInvoice").ReadOnly = True
                Else
                    ' Not created, keep readonly
                    row.Cells("UploadInvoice").ReadOnly = True
                End If

            Next



            ' Hide the flag column (optional)
            dataview.Columns("InvoiceUploadedFlag").Visible = False
            dataview.Columns("InvoiceCreatedFlag").Visible = False
            ' ⑦ Tick every row & add header checkbox
            AddSelectAllCheckbox()
            Dim hdr = dataview.Controls.OfType(Of CheckBox)().First(Function(cb) cb.Name = "HeaderCheckBox")
            'hdr.Checked = True

        Catch ex As Exception
            MessageBox.Show("DB Error: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        Finally
            conn.Close()
        End Try
    End Sub

    Private Sub DataGridView_CellPainting(sender As Object, e As DataGridViewCellPaintingEventArgs)
        If e.RowIndex < 0 Then Return

        Dim dgv As DataGridView = CType(sender, DataGridView)

        If e.ColumnIndex = dgv.Columns("UploadInvoice").Index Then
            e.Handled = True
            e.PaintBackground(e.CellBounds, True)

            Dim isCreated As Boolean = False
            Dim isUploaded As Boolean = False

            Dim createdVal = dgv.Rows(e.RowIndex).Cells("InvoiceCreatedFlag").Value
            Dim uploadedVal = dgv.Rows(e.RowIndex).Cells("InvoiceUploadedFlag").Value

            If createdVal IsNot DBNull.Value Then
                isCreated = Convert.ToBoolean(createdVal)
            End If
            If uploadedVal IsNot DBNull.Value Then
                isUploaded = Convert.ToBoolean(uploadedVal)
            End If

            Dim iconText As String
            Dim iconColor As Color
            Dim iconFont As New Font(dgv.Font.FontFamily, dgv.Font.Size + 2, FontStyle.Bold)

            If isUploaded Then
                iconText = "✅"  ' Uploaded done
                iconColor = Color.FromArgb(0, 150, 136)
            ElseIf isCreated Then
                iconText = "📤"  ' Ready to upload
                iconColor = Color.FromArgb(0, 150, 136)
            Else
                iconText = "📤"  ' Not created
                iconColor = Color.LightGray
            End If

            TextRenderer.DrawText(
        e.Graphics,
        iconText,
        iconFont,
        e.CellBounds,
        iconColor,
        TextFormatFlags.HorizontalCenter Or TextFormatFlags.VerticalCenter
    )
        End If




        If e.ColumnIndex = dgv.Columns("EditRow").Index Then
            e.Handled = True
            e.PaintBackground(e.CellBounds, True)

            Dim editFont As New Font(dgv.Font.FontFamily, dgv.Font.Size + 3, FontStyle.Bold)
            Dim editColor As Color = Color.FromArgb(0, 150, 136) ' Change to your preferred color (e.g., Navy, DarkCyan, etc.)

            TextRenderer.DrawText(
                e.Graphics,
                "✎",
                editFont,
                e.CellBounds,
                editColor,
                TextFormatFlags.HorizontalCenter Or TextFormatFlags.VerticalCenter
            )
        End If
    End Sub



    Private Sub dataview_CellFormatting(sender As Object, e As DataGridViewCellFormattingEventArgs)
        If dataview.Columns(e.ColumnIndex).Name = "Status" Then
            Dim statusText As String = If(e.Value, "").ToString()

            If statusText.StartsWith("✔ Validation Passed") Then
                dataview.Rows(e.RowIndex).DefaultCellStyle.BackColor = Color.LightGreen
            ElseIf statusText.StartsWith("❌") Then
                dataview.Rows(e.RowIndex).DefaultCellStyle.BackColor = Color.FromArgb(255, 220, 220)

            Else
                dataview.Rows(e.RowIndex).DefaultCellStyle.BackColor = Color.White
            End If
        End If
    End Sub



    Private Sub Grid_DataBindingComplete(sender As Object, e As DataGridViewBindingCompleteEventArgs)

        ValidateAndHighlightRows() ' ✅ call your logic here
        dataview.ClearSelection()
    End Sub



    Private Sub LegendFilter_Click(sender As Object, e As EventArgs)
        If dataview.DataSource Is Nothing Then Return

        Dim dt As DataTable = TryCast(dataview.DataSource, DataTable)
        If dt Is Nothing Then Return

        Dim clickedText As String = ""
        If TypeOf sender Is Label Then
            clickedText = DirectCast(sender, Label).Text
        ElseIf TypeOf sender Is Panel Then
            ' Find the label next to the panel
            Dim parent As FlowLayoutPanel = DirectCast(DirectCast(sender, Panel).Parent, FlowLayoutPanel)
            Dim idx As Integer = parent.Controls.IndexOf(DirectCast(sender, Control))
            If idx >= 0 AndAlso idx + 1 < parent.Controls.Count AndAlso TypeOf parent.Controls(idx + 1) Is Label Then
                clickedText = DirectCast(parent.Controls(idx + 1), Label).Text
            End If
        End If

        ' Reset filter (show all if clicked again)
        Static lastFilter As String = ""
        If clickedText = lastFilter Then
            dt.DefaultView.RowFilter = ""
            dataview.ClearSelection() ' ✅ clear selection after reset
            lastFilter = ""
            Exit Sub
        End If

        ' Apply filter based on Status column
        Select Case clickedText
            Case "Transaction ready for insert"
                dt.DefaultView.RowFilter = "Status LIKE '✔ Validation Passed%'"

            Case "Not ready for insert"
                dt.DefaultView.RowFilter = "Status LIKE '❌%'"

            Case "Credit Memo"
                dt.DefaultView.RowFilter = "Status LIKE '✔ Credit Memo Entry%'"
        End Select

        ' ✅ clear selection so first row doesn’t stay blue
        dataview.ClearSelection()

        lastFilter = clickedText
    End Sub

    Private Sub BuildUI()

        Dock = DockStyle.Fill
        BackColor = Color.White

        ' ▸ top strip --------------------------------------------------------
        Dim tlpTop As New TableLayoutPanel With {
        .Dock = DockStyle.Top,
        .AutoSize = True,
        .ColumnCount = 3,
        .RowCount = 1,
        .Padding = New Padding(15)
    }
        tlpTop.ColumnStyles.Add(New ColumnStyle(SizeType.AutoSize))   ' legend on left
        tlpTop.ColumnStyles.Add(New ColumnStyle(SizeType.Percent, 100)) ' filler middle
        tlpTop.ColumnStyles.Add(New ColumnStyle(SizeType.AutoSize))   ' buttons on right

        ' ▸ Legend panel -----------------------------------------------------
        Dim pnlLegend As New FlowLayoutPanel With {
        .FlowDirection = FlowDirection.LeftToRight,
        .WrapContents = False,
        .AutoSize = True,
        .AutoSizeMode = AutoSizeMode.GrowAndShrink,
        .Dock = DockStyle.Fill,
        .Margin = New Padding(0, 9, 0, 0)
    }

        ' Green legend
        Dim pnlGreen As New Panel With {.BackColor = Color.LightGreen, .Size = New Size(15, 15), .Margin = New Padding(0, 0, 5, 0)}
        Dim lblGreen As New Label With {.Text = "Transaction ready for insert", .AutoSize = True, .Height = 18, .TextAlign = ContentAlignment.MiddleLeft, .Font = New Font("Verdana", 9, FontStyle.Regular), .Margin = New Padding(0, 0, 15, 0)}

        ' Red legend
        Dim pnlRed As New Panel With {.BackColor = Color.LightCoral, .Size = New Size(15, 15), .Margin = New Padding(0, 0, 5, 0)}
        Dim lblRed As New Label With {.Text = "Not ready for insert", .AutoSize = True, .Height = 18, .TextAlign = ContentAlignment.MiddleLeft, .Font = New Font("Verdana", 9, FontStyle.Regular)}

        ' Add to legend
        pnlLegend.Controls.Add(pnlGreen)
        pnlLegend.Controls.Add(lblGreen)
        pnlLegend.Controls.Add(pnlRed)
        pnlLegend.Controls.Add(lblRed)

        ' Legend container
        Dim pnlLegendContainer As New Panel With {
        .Padding = New Padding(15, 8, 15, 8),
        .BorderStyle = BorderStyle.FixedSingle,
        .AutoSize = True,
        .AutoSizeMode = AutoSizeMode.GrowAndShrink,
        .Margin = New Padding(0, 5, 0, 0)
    }
        pnlLegendContainer.Controls.Add(pnlLegend)
        AddHandler pnlGreen.Click, AddressOf LegendFilter_Click
        AddHandler lblGreen.Click, AddressOf LegendFilter_Click

        AddHandler pnlRed.Click, AddressOf LegendFilter_Click
        AddHandler lblRed.Click, AddressOf LegendFilter_Click

        ' Buttons
        btnUpdate = MakeButton("Update Invoice") : btnUpdate.Width = 150
        btnUploadSelected = MakeButton("Upload Invoice") : btnUploadSelected.Width = 150

        Dim pnlButtons As New FlowLayoutPanel With {
        .FlowDirection = FlowDirection.LeftToRight,
        .WrapContents = False,
        .AutoSize = True,
        .AutoSizeMode = AutoSizeMode.GrowAndShrink,
        .Dock = DockStyle.Fill,
        .Margin = New Padding(0)
    }

        btnExportExcel = MakeButton("Export to Excel")
        btnExportExcel.Width = 150
        pnlButtons.Controls.Add(btnExportExcel)

        AddHandler btnExportExcel.Click, AddressOf btnExportExcel_Click

        pnlButtons.Controls.Add(btnUpdate)

        ' Add legend + filler + buttons
        tlpTop.Controls.Add(pnlLegendContainer, 0, 0)
        tlpTop.Controls.Add(New Label(), 1, 0)
        tlpTop.Controls.Add(pnlButtons, 2, 0)

        ' ▸ MAIN dataview ----------------------------------------------------
        dataview = New DataGridView With {
        .Dock = DockStyle.Fill,
        .ReadOnly = False,
        .AllowUserToAddRows = False,
        .AllowUserToDeleteRows = False,
        .SelectionMode = DataGridViewSelectionMode.FullRowSelect,
        .MultiSelect = False,
        .EnableHeadersVisualStyles = False,
        .AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.None,
        .ColumnHeadersDefaultCellStyle = New DataGridViewCellStyle With {
            .BackColor = Color.FromArgb(31, 30, 68),
            .ForeColor = Color.White,
            .Font = New Font("Verdana", 10, FontStyle.Bold)
        },
        .Font = New Font("Verdana", 10),
        .RowTemplate = New DataGridViewRow With {.Height = 26},
        .BackgroundColor = Color.White
    }

        ' ▸ uploaded dataview (COMMENTED OUT) --------------------------------
        'dataviewUploaded = New DataGridView With {
        '    .Dock = DockStyle.Fill,
        '    .ReadOnly = True,
        '    .AllowUserToAddRows = False,
        '    .AllowUserToDeleteRows = False,
        '    .SelectionMode = DataGridViewSelectionMode.FullRowSelect,
        '    .MultiSelect = False,
        '    .EnableHeadersVisualStyles = False,
        '    .AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.None,
        '    .ColumnHeadersDefaultCellStyle = New DataGridViewCellStyle With {
        '        .BackColor = Color.FromArgb(31, 30, 68),
        '        .ForeColor = Color.White,
        '        .Font = New Font("Verdana", 10, FontStyle.Bold)
        '    },
        '    .Font = New Font("Verdana", 10),
        '    .RowTemplate = New DataGridViewRow With {.Height = 26},
        '    .BackgroundColor = Color.White
        '}

        ' ▸ combined vertical layout -----------------------------------------
        Dim mainLayout As New TableLayoutPanel With {
        .Dock = DockStyle.Fill,
        .AutoScroll = True,
        .AutoSize = True,
        .ColumnCount = 1,
        .RowCount = 2
    }

        mainLayout.RowStyles.Add(New RowStyle(SizeType.AutoSize))          ' top legend/buttons
        mainLayout.RowStyles.Add(New RowStyle(SizeType.Percent, 100))      ' ONLY dataview

        mainLayout.Controls.Add(tlpTop, 0, 0)
        mainLayout.Controls.Add(dataview, 0, 1)
        'mainLayout.Controls.Add(dataviewUploaded, 0, 2)

        ' ▸ overlay panel ----------------------------------------------------
        pnlOverlay = New Panel With {
        .Size = New Size(400, 50),
        .BackColor = Color.FromArgb(20, 0, 150, 136),
        .Visible = False,
        .Anchor = AnchorStyles.None
    }

        lblStatus = New Label With {
        .Dock = DockStyle.Fill,
        .ForeColor = Color.Black,
        .TextAlign = ContentAlignment.MiddleCenter,
        .Font = New Font("Verdana", 10, FontStyle.Bold)
    }

        pnlOverlay.Controls.Add(lblStatus)
        pnlOverlay.Location = New Point(-90, 30)

        ' ▸ final layout -----------------------------------------------------
        Controls.Add(mainLayout)
        Controls.Add(pnlOverlay)
        pnlOverlay.BringToFront()

        ' ▸ event wiring -----------------------------------------------------
        AddHandler dataview.DataError, Sub(s, eargs) eargs.ThrowException = False
        AddHandler dataview.CellContentClick, AddressOf GridActionClicked
        AddHandler btnUpdate.Click, AddressOf SubmitSelectedRows

    End Sub




    Private Sub btnExportExcel_Click(sender As Object, e As EventArgs)
        Try
            If dataview Is Nothing OrElse dataview.Rows.Count = 0 Then
                MessageBox.Show("No data to export!", "Info", MessageBoxButtons.OK, MessageBoxIcon.Information)
                Return
            End If

            ' Ask user where to save
            Dim sfd As New SaveFileDialog With {
            .Filter = "Excel Files|*.xlsx",
            .Title = "Save Exported Data",
            .FileName = "Export_" & DateTime.Now.ToString("yyyyMMdd_HHmmss") & ".xlsx"
        }

            If sfd.ShowDialog() = DialogResult.OK Then
                ExcelPackage.LicenseContext = OfficeOpenXml.LicenseContext.NonCommercial

                Using pck As New OfficeOpenXml.ExcelPackage()
                    Dim ws = pck.Workbook.Worksheets.Add("ExportedData")

                    ' Add headers
                    For col As Integer = 0 To dataview.Columns.Count - 1
                        ws.Cells(1, col + 1).Value = dataview.Columns(col).HeaderText
                        ws.Cells(1, col + 1).Style.Font.Bold = True
                    Next

                    ' Add rows
                    For row As Integer = 0 To dataview.Rows.Count - 1
                        For col As Integer = 0 To dataview.Columns.Count - 1
                            ws.Cells(row + 2, col + 1).Value = dataview.Rows(row).Cells(col).Value
                        Next
                    Next

                    ' Auto-fit columns
                    ws.Cells(ws.Dimension.Address).AutoFitColumns()

                    ' Save file
                    pck.SaveAs(New IO.FileInfo(sfd.FileName))
                End Using

                MessageBox.Show("Data exported successfully to " & sfd.FileName, "Export Complete", MessageBoxButtons.OK, MessageBoxIcon.Information)
            End If

        Catch ex As Exception
            MessageBox.Show("Error exporting to Excel: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
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


    Private Sub ValidateAndHighlightRows()
        Cursor.Current = Cursors.WaitCursor
        Dim totalRows As Integer = dataview.Rows.Count

        ShowStatus($"Please wait, loading data... (0 of {totalRows})")

        Dim sessionManager As QBSessionManager = Nothing

        Try
            sessionManager = New QBSessionManager()
            sessionManager.OpenConnection("", "Mooneem App")
            sessionManager.BeginSession("", ENOpenMode.omDontCare)

            Dim currentRow As Integer = 0

            For Each row As DataGridViewRow In dataview.Rows
                currentRow += 1

                ' ✅ Update message with row count
                ShowStatus($"Please wait, loading data... ({currentRow} of {totalRows})")
                Application.DoEvents() ' Allow UI to refresh the status label

                Try
                    Dim buyerID As Integer = Convert.ToInt32(row.Cells("TransactionID").Value)
                    Dim QBcompanyName As String = row.Cells("QuickBooksCompanyName").Value?.ToString()?.Trim()
                    Dim invoiceNumber As String = row.Cells("InvoiceNumber").Value?.ToString()?.Trim()

                    Dim isValid As Boolean = True
                    Dim reasons As New List(Of String)

                    If String.IsNullOrWhiteSpace(QBcompanyName) OrElse Not CustomerExistsInQB(QBcompanyName, sessionManager) Then
                        isValid = False
                        reasons.Add("❌ QuickBooks customer not found or name is blank.")
                    End If

                    Dim repName As String = GetRep(buyerID)
                    If Not String.IsNullOrWhiteSpace(repName) AndAlso Not SalesRepExistsInQB(repName, sessionManager) Then
                        isValid = False
                        reasons.Add($"❌ Sales Rep '{repName}' not found in QuickBooks.")
                    End If

                    Dim itemDetails As List(Of ItemDetail) = GetItemDetailsFromDatabase(buyerID)
                    For Each item As ItemDetail In itemDetails
                        If Not String.IsNullOrWhiteSpace(item.ClassRef) AndAlso Not ClassRefExistsInQB(item.ClassRef, sessionManager) Then
                            isValid = False
                            reasons.Add($"❌ ClassRef '{item.ClassRef}' not found in QuickBooks.")
                            Exit For
                        End If
                    Next

                    ' Apply styles
                    If isValid Then
                        row.DefaultCellStyle.BackColor = Color.LightGreen
                        row.DefaultCellStyle.SelectionBackColor = Color.LightGreen
                        row.DefaultCellStyle.ForeColor = Color.Black
                        row.DefaultCellStyle.SelectionForeColor = Color.Black
                        row.Cells("Select").ReadOnly = False
                        ' ✅ Set status text
                        row.Cells("Status").Value = "✔ Validation Passed"
                    Else
                        row.Cells("Select").ReadOnly = True

                        row.DefaultCellStyle.SelectionBackColor = Color.FromArgb(255, 220, 220)
                        row.DefaultCellStyle.BackColor = Color.FromArgb(255, 220, 220)
                        row.DefaultCellStyle.ForeColor = Color.Black
                        row.DefaultCellStyle.SelectionForeColor = Color.Black
                        Dim tooltipText As String = "⛔ Validation Failed:" & vbCrLf & String.Join(vbCrLf & vbCrLf, reasons)
                        For Each cell As DataGridViewCell In row.Cells
                            cell.ToolTipText = tooltipText
                        Next
                        ' ❌ Set status text
                        row.Cells("Status").Value = String.Join(" | ", reasons)
                    End If

                Catch exRow As Exception
                    For Each cell As DataGridViewCell In row.Cells
                        cell.ToolTipText = "⚠️ Unexpected error during validation."
                    Next
                End Try
            Next

        Catch ex As Exception
            MessageBox.Show("Error during validation: " & ex.Message, "QuickBooks Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        Finally
            If sessionManager IsNot Nothing Then
                Try
                    sessionManager.EndSession()
                    sessionManager.CloseConnection()
                Catch
                End Try
            End If

            HideStatus()
            Cursor.Current = Cursors.Default
            dataview.Refresh()
        End Try
    End Sub





    Private Sub GridActionClicked(sender As Object, e As DataGridViewCellEventArgs)
        If e.RowIndex < 0 Then Return

        Dim clickedColumn = dataview.Columns(e.ColumnIndex).Name
        Dim r = dataview.Rows(e.RowIndex)


        Select Case clickedColumn
            Case "EditRow"
                ' Edit action
                Dim transId = CInt(r.Cells("TransactionID").Value)
                Dim invNo = r.Cells("InvoiceNumber").Value?.ToString()
                Dim invDate = If(Date.TryParse(r.Cells("InvoiceDate").Value?.ToString(), Nothing), CDate(r.Cells("InvoiceDate").Value), Date.Today)
                Dim amount = CDec(Val(r.Cells("InvoiceAmount").Value))
                Dim comp = r.Cells("CompanyName").Value?.ToString()
                Dim qbComp = r.Cells("QuickBooksCompanyName").Value?.ToString()

                ' Create popup form and show user control
                Dim popup As New Form()
                popup.FormBorderStyle = FormBorderStyle.FixedDialog
                popup.StartPosition = FormStartPosition.CenterScreen
                popup.Size = New Size(500, 300)
                popup.Text = "Edit Invoice"
                popup.MaximizeBox = False
                popup.MinimizeBox = False
                popup.ShowInTaskbar = False

                Dim uc As New ucEditInvoicePopup()
                uc.Dock = DockStyle.Fill
                uc.LoadData(transId, invNo, invDate, amount, comp, qbComp)

                popup.Controls.Add(uc)

                If popup.ShowDialog() = DialogResult.OK Then
                    'LoadData() ' Reload your grid
                    'LoadUploadedData()
                    'BeginInvoke(Sub()
                    '                ShowStatus("🔄 Loading data, please wait...")
                    '                ValidateAndHighlightRows()
                    '            End Sub)
                End If



            Case "UploadInvoice"
                Dim row As DataGridViewRow = dataview.Rows(e.RowIndex)
                Dim invoiceuploaded As Boolean = False
                If row.Cells("InvoiceUploadedFlag").Value IsNot DBNull.Value Then
                    invoiceuploaded = Convert.ToBoolean(row.Cells("InvoiceUploadedFlag").Value)
                End If



                If invoiceuploaded Then
                    ' ✅ Get Transaction ID
                    Dim transId As Integer = CInt(row.Cells("TransactionID").Value)

                    ' ✅ Fetch file name from DB
                    Dim fileName As String = ""
                    Using cmd As New MySqlCommand("SELECT inv_file FROM loop_transaction_buyer WHERE id = @id", conn)
                        cmd.Parameters.AddWithValue("@id", transId)
                        conn.Open()
                        Dim result = cmd.ExecuteScalar()
                        If result IsNot Nothing Then fileName = result.ToString()
                        conn.Close()
                    End Using

                    If String.IsNullOrWhiteSpace(fileName) Then
                        MessageBox.Show("No invoice file found for this record.", "Info", MessageBoxButtons.OK, MessageBoxIcon.Information)
                        Return
                    End If

                    ' ✅ Download to temp location
                    Dim tempPath As String = Path.Combine(Path.GetTempPath(), fileName)
                    If FTPDownloader.DownloadFileFromFTP(fileName, tempPath) Then
                        ' ✅ Show popup form with PDF viewer control
                        Dim popup As New Form With {
                .FormBorderStyle = FormBorderStyle.FixedDialog,
                .StartPosition = FormStartPosition.CenterScreen,
                .Size = New Size(800, 600),
                .Text = "View Uploaded Invoice",
                .MaximizeBox = False,
                .MinimizeBox = False,
                .ShowInTaskbar = False
            }

                        Dim viewer As New ucInvoicePdfViewer()
                        viewer.Dock = DockStyle.Fill
                        viewer.LoadPdf(tempPath)

                        popup.Controls.Add(viewer)
                        popup.ShowDialog()
                    End If

                Else
                    ' 🟡 Future: implement fresh upload logic here
                    MessageBox.Show("Invoice not yet uploaded.", "Info", MessageBoxButtons.OK, MessageBoxIcon.Information)
                End If



        End Select
    End Sub





    Private Sub UploadedGrid_ViewClicked(sender As Object, e As DataGridViewCellEventArgs)
        If e.RowIndex < 0 Then Exit Sub

        Dim grid As DataGridView = CType(sender, DataGridView)
        If grid.Columns(e.ColumnIndex).Name = "ViewInvoice" Then
            Dim row As DataGridViewRow = grid.Rows(e.RowIndex)
            Dim transId As Integer = CInt(row.Cells("TransactionID").Value)

            ' ✅ Fetch file name
            Dim fileName As String = ""
            Using cmd As New MySqlCommand("SELECT inv_file FROM loop_transaction_buyer WHERE id = @id", conn)
                cmd.Parameters.AddWithValue("@id", transId)
                conn.Open()
                Dim result = cmd.ExecuteScalar()
                If result IsNot Nothing Then fileName = result.ToString()
                conn.Close()
            End Using

            If String.IsNullOrWhiteSpace(fileName) Then
                MessageBox.Show("No invoice file found for this record.", "Info", MessageBoxButtons.OK, MessageBoxIcon.Information)
                Return
            End If

            ' ✅ Download and view
            Dim tempPath As String = Path.Combine(Path.GetTempPath(), fileName)
            If FTPDownloader.DownloadFileFromFTP(fileName, tempPath) Then
                Dim popup As New Form With {
                .FormBorderStyle = FormBorderStyle.FixedDialog,
                .StartPosition = FormStartPosition.CenterScreen,
                .Size = New Size(800, 600),
                .Text = "View Uploaded Invoice",
                .MaximizeBox = False,
                .MinimizeBox = False,
                .ShowInTaskbar = False
            }

                Dim viewer As New ucInvoicePdfViewer()
                viewer.Dock = DockStyle.Fill
                viewer.LoadPdf(tempPath)

                popup.Controls.Add(viewer)
                popup.ShowDialog()
            End If
        End If
    End Sub




    Private Function GetRep(transRecID As Int32) As String
        Dim repValue As String = "" ' Default to empty string if no data found

        Try
            Using conn As New MySqlConnection(connString)
                conn.Open()

                Dim query As String = "SELECT rep FROM loop_invoice_details WHERE trans_rec_id = @transRecID LIMIT 1"
                Using cmd As New MySqlCommand(query, conn)
                    cmd.Parameters.AddWithValue("@transRecID", transRecID)
                    Using reader As MySqlDataReader = cmd.ExecuteReader()
                        If reader.Read() Then
                            repValue = reader("rep").ToString()
                        End If
                    End Using
                End Using
            End Using
        Catch ex As Exception
            MessageBox.Show("Error fetching Rep: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try

        Return repValue ' Return empty string if no data found
    End Function

    Private Function GetInvoiceDate(transRecID As Integer) As String
        Dim invoiceDate As String = ""

        Try
            Using conn As New MySqlConnection(connString)
                conn.Open()

                Dim query As String = "SELECT timestamp FROM loop_invoice_details WHERE trans_rec_id = @TransRecID"
                Using cmd As New MySqlCommand(query, conn)
                    cmd.Parameters.AddWithValue("@TransRecID", transRecID)
                    Using reader As MySqlDataReader = cmd.ExecuteReader()
                        If reader.Read() AndAlso Not IsDBNull(reader("timestamp")) Then
                            Dim fullDateTime As DateTime = Convert.ToDateTime(reader("timestamp"))
                            invoiceDate = fullDateTime.ToString("yyyy-MM-dd") ' Extract only the date part
                        End If
                    End Using
                End Using
            End Using
        Catch ex As Exception
            MessageBox.Show("Error retrieving invoice date: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try

        Return invoiceDate ' If no date found, returns an empty string
    End Function
    Private Function GetCompanyAndBillToAddress(b2bID As Integer) As String
        Dim companyName As String = ""
        Dim billToAddress As String = ""
        Dim billToAddress1 As String = ""
        Dim billToAddress2 As String = ""
        Try
            Using conn2 As New MySqlConnection(B2BconnString)
                conn2.Open()

                ' Fetch company name using b2bid from companyInfo table
                Dim companyQuery As String = "SELECT company FROM companyInfo WHERE ID = @B2BID"
                Using cmd As New MySqlCommand(companyQuery, conn2)
                    cmd.Parameters.AddWithValue("@B2BID", b2bID)
                    Using reader As MySqlDataReader = cmd.ExecuteReader()
                        If reader.Read() Then
                            companyName = reader("company").ToString()
                        End If
                    End Using
                End Using

                ' Fetch bill-to address using b2bid from b2bbillto table
                Dim addressQuery As String = "SELECT address, address2, address, city, state, zipcode, mainphone FROM b2bbillto WHERE companyid = @B2BID"
                Using cmd As New MySqlCommand(addressQuery, conn2)
                    cmd.Parameters.AddWithValue("@B2BID", b2bID)
                    Using reader As MySqlDataReader = cmd.ExecuteReader()
                        If reader.Read() Then
                            billToAddress1 = reader("address").ToString()
                            billToAddress2 = reader("address2").ToString()
                            If (billToAddress) <> "" And Trim(billToAddress2) <> "" Then
                                billToAddress = billToAddress1 + " " + billToAddress2
                            End If

                            billToAddress = billToAddress + "<br>" + reader("city").ToString() + ", " + reader("state").ToString() + " " + reader("zipcode").ToString() + "<br>Ph.# " + reader("mainphone").ToString()
                        End If
                    End Using
                End Using

            End Using
        Catch ex As Exception
            MessageBox.Show("Error retrieving data: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try

        ' Combine company name and bill-to address
        Return $"{companyName} {billToAddress}"
    End Function


    Private Function GetB2BIDFromWarehouse(QBcompanyName As String) As Integer
        Dim b2bID As Integer = 0
        Try
            Using conn As New MySqlConnection(connString)
                conn.Open()

                Dim query As String = "SELECT b2bid FROM loop_warehouse WHERE quick_books_company_name = @QBcompanyName"
                Using cmd As New MySqlCommand(query, conn)
                    cmd.Parameters.AddWithValue("@QBcompanyName", QBcompanyName)
                    Using reader As MySqlDataReader = cmd.ExecuteReader()
                        If reader.Read() Then
                            b2bID = Convert.ToInt32(reader("b2bid"))
                        End If
                    End Using
                End Using
            End Using
        Catch ex As Exception
            MessageBox.Show("Error retrieving B2B ID: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
        Return b2bID
    End Function

    Private Function GetShipAddressFromCompanyInfo(b2bID As Integer) As String
        Dim shipAddress As String = ""
        Dim companyName As String = ""
        Dim shipAddress1 As String = ""
        Dim shipAddress2 As String = ""

        Try
            Using conn2 As New MySqlConnection(B2BconnString)
                conn2.Open()

                ' Query to fetch both company name and shipping address
                Dim query As String = "SELECT company, shipAddress, shipAddress2, shipCity, shipState, shipZip, shipPhone FROM companyInfo WHERE ID = @B2BID"
                Using cmd As New MySqlCommand(query, conn2)
                    cmd.Parameters.AddWithValue("@B2BID", b2bID)
                    Using reader As MySqlDataReader = cmd.ExecuteReader()
                        If reader.Read() Then
                            companyName = reader("company").ToString() ' Get company name
                            shipAddress = reader("shipAddress").ToString() ' Get shipping address

                            shipAddress1 = reader("shipAddress").ToString()
                            shipAddress2 = reader("shipAddress2").ToString()
                            If (shipAddress1) <> "" And Trim(shipAddress2) <> "" Then
                                shipAddress = shipAddress1 + " " + shipAddress2
                            End If

                            shipAddress = shipAddress + "<br>" + reader("shipCity").ToString() + ", " + reader("shipState").ToString() + " " + reader("shipZip").ToString() + "<br>Ph.# " + reader("shipPhone").ToString()

                        End If
                    End Using
                End Using
            End Using

            ' Combine company name with ship address
            If Not String.IsNullOrEmpty(companyName) Then
                shipAddress = $"{companyName} {shipAddress}"
            End If

        Catch ex As Exception
            MessageBox.Show("Error retrieving shipping address and company name: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try

        Return shipAddress
    End Function


    Private Function Getshipmethodvia(transRecID As Integer) As String
        Dim shipmethodvia As String = ""
        Try
            Using conn As New MySqlConnection(connString)
                conn.Open()

                Dim query As String = "SELECT via FROM loop_invoice_details WHERE trans_rec_id = @TransRecID"
                Using cmd As New MySqlCommand(query, conn)
                    cmd.Parameters.AddWithValue("@TransRecID", transRecID)
                    Using reader As MySqlDataReader = cmd.ExecuteReader()
                        If reader.Read() Then
                            If (reader("via").ToString() = "Pickup") Then
                                shipmethodvia = "Pick-up"
                            End If
                            If (reader("via").ToString() = "Third Party") Then
                                shipmethodvia = "Third Party"
                            End If
                        End If
                    End Using
                End Using
            End Using
        Catch ex As Exception
            MessageBox.Show("Error retrieving shipmethodvia: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
        Return shipmethodvia
    End Function

    Private Function GetInvoiceNumber(transRecID As Integer) As String
        Dim invoiceNumber As String = ""

        invoiceNumber = ""
        Try
            Using conn As New MySqlConnection(connString)
                conn.Open()

                Dim query As String = "SELECT loop_qb_invoice_no FROM loop_transaction_buyer WHERE id = @TransRecID"
                Using cmd As New MySqlCommand(query, conn)
                    cmd.Parameters.AddWithValue("@TransRecID", transRecID)
                    Using reader As MySqlDataReader = cmd.ExecuteReader()
                        invoiceNumber = ""
                        If reader.Read() Then
                            invoiceNumber = reader("loop_qb_invoice_no").ToString()
                        End If
                    End Using
                End Using
            End Using
        Catch ex As Exception
            MessageBox.Show("Error retrieving invoice number: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try

        Return invoiceNumber
    End Function

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

    Private Function GetBtypeFromInventory(categoryID As Integer) As String
        Dim btype As String = "Other"
        Try
            Using conn2 As New MySqlConnection(connString)
                conn2.Open()

                ' ✅ Directly fetch category from category_master
                Dim query As String = "SELECT category FROM category_master WHERE category_id = @CategoryID"

                Using cmd As New MySqlCommand(query, conn2)
                    cmd.Parameters.AddWithValue("@CategoryID", categoryID)
                    Dim result As Object = cmd.ExecuteScalar()

                    If result IsNot Nothing AndAlso Not String.IsNullOrWhiteSpace(result.ToString()) Then
                        btype = result.ToString()
                    End If
                End Using
            End Using
        Catch ex As Exception
            MessageBox.Show("Error retrieving btype: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
        Return btype
    End Function


    Private Function GetDivisionName(divisionID As Integer) As String
        Dim division As String = "Unknown"
        Try
            Using conn As New MySqlConnection(connString)
                conn.Open()

                ' Query to get division name from division_master
                Dim query As String = "SELECT qb_name FROM division_master WHERE division_id = @DivisionID"
                'MessageBox.Show(query, "Executed SQL Query")

                Using cmd As New MySqlCommand(query, conn)
                    cmd.Parameters.AddWithValue("@DivisionID", divisionID)
                    Using reader As MySqlDataReader = cmd.ExecuteReader()
                        If reader.Read() Then
                            division = reader("qb_name").ToString()
                        End If
                    End Using
                End Using
            End Using
        Catch ex As Exception
            MessageBox.Show("Error retrieving division name: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
        Return division
    End Function


    Private Function GetItemDetailsFromDatabase(transRecID As Integer) As List(Of ItemDetail)
        Dim itemDetails As New List(Of ItemDetail)()
        Try
            Using conn As New MySqlConnection(connString)
                conn.Open()

                Dim query As String = "SELECT quantity, description, price,category_id, loop_box_id, division_id, total " &
                                  "FROM loop_invoice_items WHERE trans_rec_id = @TransRecID"

                ' ✅ Show the query with parameter value
                'MessageBox.Show($"Executing Query:" & Environment.NewLine &
                '            query.Replace("@TransRecID", transRecID.ToString()),
                '            "Debug - SQL Query", MessageBoxButtons.OK, MessageBoxIcon.Information)

                Using cmd As New MySqlCommand(query, conn)
                    cmd.Parameters.AddWithValue("@TransRecID", transRecID)
                    Using reader As MySqlDataReader = cmd.ExecuteReader()
                        While reader.Read()
                            ' ✅ Show raw row from DB
                            'MessageBox.Show($"Raw DB Row:" & Environment.NewLine &
                            '            $"Quantity: {reader("quantity")}" & Environment.NewLine &
                            '            $"Description: {reader("description")}" & Environment.NewLine &
                            '            $"Price: {reader("price")}" & Environment.NewLine &
                            '            $"LoopBoxID: {reader("loop_box_id")}" & Environment.NewLine &
                            '            $"DivisionID: {reader("division_id")}" & Environment.NewLine &
                            '            $"Total: {reader("total")}",
                            '            "Debug - DB Row", MessageBoxButtons.OK, MessageBoxIcon.Information)

                            Dim price As Decimal = 0D
                            If Not IsDBNull(reader("price")) Then
                                price = Math.Abs(Convert.ToDecimal(reader("price")))
                            End If

                            Dim loopBoxID As Integer = If(IsDBNull(reader("loop_box_id")), 0, Convert.ToInt32(reader("loop_box_id")))
                            Dim divisionID As Integer = If(IsDBNull(reader("division_id")), 0, Convert.ToInt32(reader("division_id")))
                            Dim categoryID As Integer = If(IsDBNull(reader("category_id")), 0, Convert.ToInt32(reader("category_id")))

                            'Dim btype As String = ""
                            Dim classRef As String = MapClassRef(GetDivisionName(divisionID))

                            ' ✅ Show division/class mapping
                            'MessageBox.Show($"DivisionID: {divisionID}" & Environment.NewLine &
                            '            $"Mapped ClassRef: {classRef}",
                            '            "Debug - Division Mapping", MessageBoxButtons.OK, MessageBoxIcon.Information)

                            ' ✅ Get Btype from CategoryID (new logic)
                            Dim btype As String = GetBtypeFromInventory(categoryID)

                            'If loopBoxID > 0 Then
                            '    btype = GetBtypeFromInventory(loopBoxID)

                            '    ' ✅ Show what came back from inventory lookup
                            '    'MessageBox.Show($"LoopBoxID: {loopBoxID}" & Environment.NewLine &
                            '    '            $"Mapped Btype: {btype}",
                            '    '            "Debug - Btype Lookup", MessageBoxButtons.OK, MessageBoxIcon.Information)
                            'Else
                            '    btype = "Other"
                            '    'MessageBox.Show("LoopBoxID is 0 → Setting Btype = 'Other'",
                            '    '            "Debug - Btype Default", MessageBoxButtons.OK, MessageBoxIcon.Information)
                            'End If

                            If btype = "Pallets" AndAlso classRef = "B2B:Brokerage" Then
                                classRef = "Pallets"
                                'MessageBox.Show("Special case: Btype=Pallets & ClassRef=B2B:Brokerage → ClassRef=Pallets",
                                '            "Debug - Special Case", MessageBoxButtons.OK, MessageBoxIcon.Information)
                            End If

                            ' ✅ Calculate amount properly
                            Dim amount As Decimal = 0D
                            If Not IsDBNull(reader("total")) AndAlso Convert.ToDecimal(reader("total")) > 0 Then
                                amount = Convert.ToDecimal(reader("total"))
                            Else
                                amount = Convert.ToInt32(reader("quantity")) * price
                            End If

                            ' ✅ Final item debug
                            Dim item As New ItemDetail() With {
                            .Quantity = Convert.ToInt32(reader("quantity")),
                            .ItemCode = btype,
                            .Description = reader("description").ToString(),
                            .Priceeach = price,
                            .Amount = amount,
                            .ClassRef = classRef
                        }

                            'MessageBox.Show($"Final Item Created:" & Environment.NewLine &
                            '            $"ItemCode: {item.ItemCode}" & Environment.NewLine &
                            '            $"Description: {item.Description}" & Environment.NewLine &
                            '            $"Quantity: {item.Quantity}" & Environment.NewLine &
                            '            $"PriceEach: {item.Priceeach}" & Environment.NewLine &
                            '            $"Amount: {item.Amount}" & Environment.NewLine &
                            '            $"ClassRef: {item.ClassRef}",
                            '            "Debug - Final Item", MessageBoxButtons.OK, MessageBoxIcon.Information)

                            itemDetails.Add(item)
                        End While
                    End Using
                End Using
            End Using

        Catch ex As Exception
            MessageBox.Show("Error retrieving item details: " & ex.Message,
                        "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try

        Return itemDetails
    End Function








    Private Function MapClassRef(classRef As String) As String
        Select Case classRef
            Case "B2B Brokerage"
                Return "B2B:Brokerage"
            Case "UCB Warehouse HA"
                Return "B2B:HA"
            Case "UCB Warehouse ML"
                Return "B2B:ML"
            Case "UCB Warehouse HV"
                Return "B2B:HV"
            Case "3PL"
                Return "B2B:3PL"
            Case Else
                Return classRef ' Return original if no match found
        End Select
    End Function




    Public Class ItemDetail
        Public Property Quantity As Integer
        Public Property ItemCode As String
        Public Property Priceeach As Decimal
        Public Property Description As String
        Public Property Amount As Decimal
        Public Property ClassRef As String
    End Class

    Private Function GetTermsFromDatabase(buyerID As Integer) As String
        Dim terms As String = String.Empty
        Try
            ' Establish database connection
            Using conn As New MySqlConnection(connString)
                conn.Open()

                ' Query to get the terms for the given BuyerID (trans_rec_id)
                Dim query As String = "SELECT terms FROM loop_invoice_details WHERE trans_rec_id = @BuyerID"
                Using cmd As New MySqlCommand(query, conn)
                    cmd.Parameters.AddWithValue("@BuyerID", buyerID)

                    ' Execute the query and retrieve the terms
                    Dim result = cmd.ExecuteScalar()
                    If result IsNot Nothing Then
                        terms = result.ToString()
                    End If
                End Using
            End Using
        Catch ex As Exception
            MessageBox.Show("Error retrieving terms: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try

        Return terms
    End Function
    Private Function GetshipDateFromDatabase(buyerID As Integer) As Date
        Dim bol_pickupdate As String = String.Empty
        Try
            ' Establish database connection
            Using conn As New MySqlConnection(connString)
                conn.Open()

                ' Query to get the bol_pickupdate for the given BuyerID (trans_rec_id)
                Dim query As String = "SELECT bol_pickupdate FROM loop_bol_tracking WHERE trans_rec_id = @BuyerID"
                Using cmd As New MySqlCommand(query, conn)
                    cmd.Parameters.AddWithValue("@BuyerID", buyerID)

                    ' Execute the query and retrieve the bol_pickupdate
                    Dim result = cmd.ExecuteScalar()
                    If result IsNot Nothing Then
                        bol_pickupdate = result.ToString()

                        Dim message As String = "bol_pickupdate value: '" & bol_pickupdate & "'" ' Always display the value

                        If String.IsNullOrWhiteSpace(bol_pickupdate) Then
                            MessageBox.Show(message & vbCrLf & "The pick-up date is missing or empty!", "Error", MessageBoxButtons.OK, MessageBoxIcon.Warning)
                            Return Date.MinValue
                        End If

                    End If
                End Using
            End Using

            ' Attempt to parse the date string into a Date object
            Dim parsedDate As Date
            If Date.TryParseExact(bol_pickupdate, "MM/dd/yyyy", Globalization.CultureInfo.InvariantCulture, Globalization.DateTimeStyles.None, parsedDate) Then
                Return parsedDate
            Else
                MessageBox.Show("The date format in the database is invalid or unsupported: " & bol_pickupdate, "Error", MessageBoxButtons.OK, MessageBoxIcon.Warning)
                Return Date.MinValue ' Return a default value
            End If
        Catch ex As Exception
            MessageBox.Show("Error retrieving bol_pickupdate: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
            Return Date.MinValue ' Return a default value in case of an error
        End Try
    End Function
    Private Function HasValidShipDate(buyerID As Integer) As Boolean
        Try
            Using conn As New MySqlConnection(connString)
                conn.Open()

                Dim query As String = "SELECT bol_pickupdate FROM loop_bol_tracking WHERE trans_rec_id = @BuyerID"
                Using cmd As New MySqlCommand(query, conn)
                    cmd.Parameters.AddWithValue("@BuyerID", buyerID)

                    Dim result = cmd.ExecuteScalar()

                    If result IsNot Nothing AndAlso Not String.IsNullOrWhiteSpace(result.ToString()) Then
                        Dim parsedDate As Date
                        ' Try to parse as date using general parsing
                        If Date.TryParse(result.ToString(), parsedDate) Then
                            Return True
                        End If
                    End If
                End Using
            End Using

            ' Return false if null, empty, or not a valid date
            Return False

        Catch ex As Exception
            MessageBox.Show("Error checking bol_pickupdate: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
            Return False
        End Try
    End Function

    Private Function GetPONumberFromDatabase(buyerID As Integer) As String
        Dim po As String = String.Empty
        Try
            ' Establish database connection
            Using conn As New MySqlConnection(connString)
                conn.Open()

                ' Query to get the PO number for the given BuyerID (trans_rec_id)
                Dim query As String = "SELECT PO FROM loop_invoice_details WHERE trans_rec_id = @BuyerID"
                Using cmd As New MySqlCommand(query, conn)
                    cmd.Parameters.AddWithValue("@BuyerID", buyerID)

                    ' Execute the query and retrieve the PO number
                    Dim result = cmd.ExecuteScalar()
                    If result IsNot Nothing Then
                        po = result.ToString()
                    End If
                End Using
            End Using
        Catch ex As Exception
            MessageBox.Show("Error retrieving PO number: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try

        Return po
    End Function


    Private Function InvoiceAlreadyExists(invoiceNumber As String, sessionManager As QBSessionManager) As Boolean
        Try
            ' Create a QuickBooks request message set
            Dim requestSet As IMsgSetRequest = sessionManager.CreateMsgSetRequest("US", 12, 0)
            requestSet.Attributes.OnError = ENRqOnError.roeContinue

            ' Create an invoice query request
            Dim invoiceQuery As IInvoiceQuery = requestSet.AppendInvoiceQueryRq()

            ' Set the MatchCriterion to check if invoice number contains the given value
            invoiceQuery.ORInvoiceQuery.InvoiceFilter.ORRefNumberFilter.RefNumberFilter.MatchCriterion.SetValue(ENMatchCriterion.mcContains)
            invoiceQuery.ORInvoiceQuery.InvoiceFilter.ORRefNumberFilter.RefNumberFilter.RefNumber.SetValue(invoiceNumber)

            ' Send the request to QuickBooks
            Dim responseSet As IMsgSetResponse = sessionManager.DoRequests(requestSet)
            Dim response As IResponse = responseSet.ResponseList.GetAt(0)

            ' Check if response contains any invoice matches
            If response.StatusCode = 0 AndAlso response.Detail IsNot Nothing Then
                Dim invoiceRetList As IInvoiceRetList = TryCast(response.Detail, IInvoiceRetList)
                If invoiceRetList IsNot Nothing AndAlso invoiceRetList.Count > 0 Then
                    Return True ' Invoice already exists
                End If
            End If


        Catch ex As Exception
            MessageBox.Show("Error checking invoice existence: " & ex.Message, "QuickBooks Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
        Return False ' Invoice does not exist
    End Function

    Private Function CustomerExistsInQB(qbCompanyName As String, sessionManager As QBSessionManager) As Boolean
        Try
            If String.IsNullOrWhiteSpace(qbCompanyName) Then Return False

            ' Create a request to query customers
            Dim requestSet As IMsgSetRequest = sessionManager.CreateMsgSetRequest("US", 12, 0)
            requestSet.Attributes.OnError = ENRqOnError.roeContinue

            Dim customerQuery As ICustomerQuery = requestSet.AppendCustomerQueryRq()
            customerQuery.ORCustomerListQuery.FullNameList.Add(qbCompanyName)

            ' Send the request to QuickBooks
            Dim responseSet As IMsgSetResponse = sessionManager.DoRequests(requestSet)
            Dim response As IResponse = responseSet.ResponseList.GetAt(0)

            ' Check the response
            If response.StatusCode = 0 AndAlso response.Detail IsNot Nothing Then
                Dim customerRetList As ICustomerRetList = TryCast(response.Detail, ICustomerRetList)
                If customerRetList IsNot Nothing AndAlso customerRetList.Count > 0 Then
                    Return True ' Customer exists
                End If
            End If
        Catch ex As Exception
            MessageBox.Show("Error checking customer in QuickBooks: " & ex.Message, "QuickBooks Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try

        Return False
    End Function

    Private Function ClassRefExistsInQB(classRef As String, sessionManager As QBSessionManager) As Boolean
        Try
            If String.IsNullOrWhiteSpace(classRef) Then Return False

            Dim requestSet As IMsgSetRequest = sessionManager.CreateMsgSetRequest("US", 12, 0)
            requestSet.Attributes.OnError = ENRqOnError.roeContinue

            ' Correct usage for QBFC 12+ (use ORListQuery)
            Dim classQuery As IClassQuery = requestSet.AppendClassQueryRq()
            classQuery.ORListQuery.FullNameList.Add(classRef)

            Dim responseSet As IMsgSetResponse = sessionManager.DoRequests(requestSet)
            Dim response As IResponse = responseSet.ResponseList.GetAt(0)

            If response.StatusCode = 0 AndAlso response.Detail IsNot Nothing Then
                Dim classList As IClassRetList = TryCast(response.Detail, IClassRetList)
                If classList IsNot Nothing AndAlso classList.Count > 0 Then
                    Return True ' Class exists
                End If
            End If
        Catch ex As Exception
            MessageBox.Show("Error checking class in QuickBooks: " & ex.Message, "QuickBooks Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try

        Return False ' Not found or error occurred
    End Function

    Private Function SalesRepExistsInQB(repName As String, sessionManager As QBSessionManager) As Boolean
        Try
            If String.IsNullOrWhiteSpace(repName) Then Return False

            Dim requestSet As IMsgSetRequest = sessionManager.CreateMsgSetRequest("US", 12, 0)
            requestSet.Attributes.OnError = ENRqOnError.roeContinue

            Dim salesRepQuery As ISalesRepQuery = requestSet.AppendSalesRepQueryRq()
            salesRepQuery.ORListQuery.FullNameList.Add(repName)

            Dim responseSet As IMsgSetResponse = sessionManager.DoRequests(requestSet)
            Dim response As IResponse = responseSet.ResponseList.GetAt(0)

            If response.StatusCode = 0 AndAlso response.Detail IsNot Nothing Then
                Dim repList As ISalesRepRetList = TryCast(response.Detail, ISalesRepRetList)
                If repList IsNot Nothing AndAlso repList.Count > 0 Then
                    Return True
                End If
            End If
        Catch ex As Exception
            MessageBox.Show("Error checking SalesRep in QuickBooks: " & ex.Message, "QuickBooks Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try

        Return False
    End Function



    Private Sub SubmitSelectedRows(sender As Object, e As EventArgs)
        Dim createdInvoices As New List(Of String)
        Dim failedInvoices As New List(Of String)

        Dim sessionManager As QBSessionManager = Nothing
        Dim started As Boolean = False
        Try
            ' Prompt for template name
            Dim templateName As String = InputBox("Enter the invoice template name (e.g., Intuit Product Invoice withlogo):", "Template Selection", "Intuit Product Invoice withlogo")
            If String.IsNullOrEmpty(templateName) Then
                MessageBox.Show("Template name is required.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Warning)
                Exit Sub
            End If

            ' Initialize QuickBooks session
            sessionManager = New QBSessionManager()
            sessionManager.OpenConnection("", "Mooneem App")
            sessionManager.BeginSession("", ENOpenMode.omDontCare)
            started = True

            msgSetRequest = sessionManager.CreateMsgSetRequest("US", 12, 0)
            msgSetRequest.Attributes.OnError = ENRqOnError.roeContinue

            ' Loop through selected rows
            For Each row As DataGridViewRow In dataview.Rows
                If row.Cells("Select").Value IsNot Nothing AndAlso Convert.ToBoolean(row.Cells("Select").Value) Then
                    Try
                        Dim companyName As String = If(row.Cells("CompanyName").Value?.ToString(), "")
                        Dim QBcompanyName As String = If(row.Cells("QuickBooksCompanyName").Value?.ToString(), "")
                        Dim buyerID As Integer = If(IsNumeric(row.Cells("TransactionID").Value), Convert.ToInt32(row.Cells("TransactionID").Value), 0)

                        If String.IsNullOrWhiteSpace(QBcompanyName) Then
                            failedInvoices.Add($"Row {row.Index + 1}: QB Company Name is missing.")
                            Continue For
                        End If

                        ' Fetch DB values
                        Dim terms As String = If(GetTermsFromDatabase(buyerID), "")
                        Dim po As String = If(GetPONumberFromDatabase(buyerID), "")
                        Dim shipDate As Date = Date.MinValue
                        Dim shipDateRaw As Object = GetshipDateFromDatabase(buyerID)
                        If shipDateRaw IsNot Nothing AndAlso IsDate(shipDateRaw) Then shipDate = Convert.ToDateTime(shipDateRaw)

                        Dim invoiceNumber As String = If(GetInvoiceNumber(buyerID), "")
                        Dim shipmethodvia As String = If(Getshipmethodvia(buyerID), "")
                        Dim itemDetails As List(Of ItemDetail) = If(GetItemDetailsFromDatabase(buyerID), New List(Of ItemDetail))

                        Dim invoiceDate As Date = Date.MinValue
                        Dim invoiceDateRaw As Object = GetInvoiceDate(buyerID)
                        If invoiceDateRaw IsNot Nothing AndAlso IsDate(invoiceDateRaw) Then invoiceDate = Convert.ToDateTime(invoiceDateRaw)

                        Dim b2bid As Integer = GetB2BIDFromWarehouse(QBcompanyName)
                        Dim memo As String = ""

                        ' --- Validations ---
                        If Not CustomerExistsInQB(QBcompanyName, sessionManager) Then
                            failedInvoices.Add($"{QBcompanyName} - Customer not found in QuickBooks.")
                            Continue For
                        End If

                        If String.IsNullOrWhiteSpace(invoiceNumber) Then
                            failedInvoices.Add($"{QBcompanyName} - Invoice Number missing.")
                            Continue For
                        End If

                        ' Skip duplicate check if it's a revision (-R), because we WANT to update
                        If Not invoiceNumber.EndsWith("R") AndAlso InvoiceAlreadyExists(invoiceNumber, sessionManager) Then
                            failedInvoices.Add($"{QBcompanyName} - Invoice {invoiceNumber} already exists in QuickBooks.")
                            Continue For
                        End If

                        ' Sales Rep validation
                        Dim repValue As String = GetRep(buyerID)
                        If Not String.IsNullOrEmpty(repValue) AndAlso Not SalesRepExistsInQB(repValue, sessionManager) Then
                            failedInvoices.Add($"{QBcompanyName} - Sales Rep '{repValue}' not found in QuickBooks.")
                            Continue For
                        End If

                        ' ClassRef validation
                        Dim invalidClassRef As Boolean = False
                        For Each item As ItemDetail In itemDetails
                            If Not String.IsNullOrEmpty(item.ClassRef) AndAlso Not ClassRefExistsInQB(item.ClassRef, sessionManager) Then
                                failedInvoices.Add($"{QBcompanyName} - Class '{item.ClassRef}' not found in QuickBooks.")
                                invalidClassRef = True
                                Exit For
                            End If
                        Next
                        If invalidClassRef Then Continue For

                        ' --- Decide Create vs Update ---
                        Dim success As Boolean
                        If invoiceNumber.EndsWith("R") Then
                            success = UpdateInvoice(sessionManager, msgSetRequest,
                                   buyerID, b2bid, invoiceDate, shipDate, itemDetails,
                                   companyName, QBcompanyName, memo, po, terms,
                                   templateName, invoiceNumber, shipmethodvia)
                        Else

                        End If

                        If success Then
                            createdInvoices.Add($"Invoice#: {invoiceNumber}")
                        Else
                            failedInvoices.Add($"{QBcompanyName} - Failed to process invoice {invoiceNumber}.")
                        End If

                    Catch exRow As Exception
                        failedInvoices.Add($"Row {row.Index + 1}: Error - {exRow.Message}")
                    End Try
                End If
            Next

            ' --- Summary ---
            Dim message As String = $"{createdInvoices.Count} invoice(s) updated successfully." & vbCrLf
            If createdInvoices.Count > 0 Then
                message &= "Invoices:" & vbCrLf & String.Join(vbCrLf, createdInvoices) & vbCrLf
            End If
            If failedInvoices.Count > 0 Then
                message &= $"{failedInvoices.Count} invoice(s) failed:" & vbCrLf & String.Join(vbCrLf, failedInvoices)
            End If
            MessageBox.Show(message, "Invoice Processing Summary", MessageBoxButtons.OK, MessageBoxIcon.Information)

        Catch ex As Exception
            MessageBox.Show("Error processing invoices: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        Finally
            Try
                If started Then sessionManager.EndSession()
                If sessionManager IsNot Nothing Then sessionManager.CloseConnection()
            Catch
            End Try
        End Try
    End Sub

    Private Function UpdateInvoice(sessionManager As QBSessionManager,
                               msgSetRequest As IMsgSetRequest,
                               buyerID As Int32,
                               B2BID As Int32,
                               invoiceDate As Date,
                               shipDate As Date,
                               itemDetails As List(Of ItemDetail),
                               companyName As String,
                               QBcompanyName As String,
                               memo As String,
                               po As String,
                               terms As String,
                               templateName As String,
                               invoiceNumber As String,
                               shipmethodvia As String) As Boolean

        Try
            ' --- Trim -R from invoice number (revision invoices)
            Dim baseInvoiceNumber As String = invoiceNumber
            If baseInvoiceNumber.EndsWith("R") Then
                baseInvoiceNumber = baseInvoiceNumber.Substring(0, baseInvoiceNumber.Length - 1)
            End If

            ' --- Step 1: Query invoice in QB to get TxnID + EditSequence
            Dim queryRq As IMsgSetRequest = sessionManager.CreateMsgSetRequest("US", 12, 0)
            queryRq.Attributes.OnError = ENRqOnError.roeContinue

            Dim invoiceQuery As IInvoiceQuery = queryRq.AppendInvoiceQueryRq()
            invoiceQuery.ORInvoiceQuery.RefNumberList.Add(baseInvoiceNumber)

            Dim queryResp As IMsgSetResponse = sessionManager.DoRequests(queryRq)
            If queryResp Is Nothing OrElse queryResp.ResponseList.Count = 0 Then
                MessageBox.Show($"Invoice {baseInvoiceNumber} not found in QuickBooks.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
                Return False
            End If

            Dim invoiceResp As IResponse = queryResp.ResponseList.GetAt(0)
            If invoiceResp.StatusCode <> 0 OrElse invoiceResp.Detail Is Nothing Then
                MessageBox.Show($"Unable to query invoice {baseInvoiceNumber}: {invoiceResp.StatusMessage}", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
                Return False
            End If

            ' Use IInvoiceRetList, not IInvoiceRet directly
            Dim invoiceList As IInvoiceRetList = CType(invoiceResp.Detail, IInvoiceRetList)
            If invoiceList Is Nothing OrElse invoiceList.Count = 0 Then
                MessageBox.Show($"Invoice {baseInvoiceNumber} not found in QuickBooks.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
                Return False
            End If

            Dim invoiceRet As IInvoiceRet = invoiceList.GetAt(0)
            Dim txnID As String = invoiceRet.TxnID.GetValue()
            Dim editSeq As String = invoiceRet.EditSequence.GetValue()

            ' --- Step 2: Build InvoiceModRq
            msgSetRequest.ClearRequests()
            Dim invoiceMod As IInvoiceMod = msgSetRequest.AppendInvoiceModRq()
            invoiceMod.TxnID.SetValue(txnID)
            invoiceMod.EditSequence.SetValue(editSeq)

            ' --- Step 3: Header fields
            If Not String.IsNullOrWhiteSpace(QBcompanyName) Then invoiceMod.CustomerRef.FullName.SetValue(QBcompanyName)
            If invoiceDate <> Date.MinValue Then invoiceMod.TxnDate.SetValue(invoiceDate)
            If shipDate <> Date.MinValue Then invoiceMod.ShipDate.SetValue(shipDate)
            If Not String.IsNullOrWhiteSpace(terms) Then invoiceMod.TermsRef.FullName.SetValue(terms)
            If Not String.IsNullOrWhiteSpace(memo) Then invoiceMod.Memo.SetValue(memo)
            If Not String.IsNullOrWhiteSpace(po) Then invoiceMod.PONumber.SetValue(po)
            If Not String.IsNullOrWhiteSpace(templateName) Then invoiceMod.TemplateRef.FullName.SetValue(templateName)
            If Not String.IsNullOrWhiteSpace(shipmethodvia) Then invoiceMod.ShipMethodRef.FullName.SetValue(shipmethodvia)

            ' Sales rep
            Dim repValue As String = GetRep(buyerID)
            If Not String.IsNullOrEmpty(repValue) Then invoiceMod.SalesRepRef.FullName.SetValue(repValue)

            ' --- Step 4: Line items (replace all items with fresh ones)
            If itemDetails IsNot Nothing AndAlso itemDetails.Count > 0 Then
                For Each item In itemDetails
                    Dim lineMod As IInvoiceLineMod = invoiceMod.ORInvoiceLineModList.Append().InvoiceLineMod
                    lineMod.TxnLineID.SetValue("-1") ' -1 = add new line
                    If Not String.IsNullOrWhiteSpace(item.ItemCode) Then lineMod.ItemRef.FullName.SetValue(item.ItemCode)
                    If item.Quantity > 0 Then lineMod.Quantity.SetValue(item.Quantity)
                    If item.Amount > 0 Then lineMod.Amount.SetValue(item.Amount)
                    If Not String.IsNullOrWhiteSpace(item.Description) Then lineMod.Desc.SetValue(item.Description)
                    If Not String.IsNullOrWhiteSpace(item.ClassRef) Then lineMod.ClassRef.FullName.SetValue(item.ClassRef)
                    lineMod.SalesTaxCodeRef.FullName.SetValue("NON")
                Next
            End If

            ' --- Step 5: Send Update request
            Dim modResp As IMsgSetResponse = sessionManager.DoRequests(msgSetRequest)
            Dim invoiceResponse As IResponse = modResp.ResponseList.GetAt(0)

            If invoiceResponse.StatusCode = 0 Then
                ' ✅ Update DB after success
                Using conn2 As New MySqlConnection(connString)
                    conn2.Open()
                    Dim query_upd As String = "UPDATE loop_transaction_buyer SET qb_import_done_flg = 1, qb_import_done_date_time = NOW() WHERE id = @buyerID"
                    Using cmd_upd As New MySqlCommand(query_upd, conn2)
                        cmd_upd.Parameters.AddWithValue("@buyerID", buyerID)
                        cmd_upd.ExecuteNonQuery()
                    End Using
                End Using
                Return True
            Else
                MessageBox.Show("Error updating invoice: " & invoiceResponse.StatusMessage, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
                Return False
            End If

        Catch ex As Exception
            MessageBox.Show("Error while updating invoice: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
            Return False
        End Try
    End Function


    'Private Function UpdateInvoice(sessionManager As QBSessionManager,
    '                       msgSetRequest As IMsgSetRequest,
    '                       buyerID As Int32,
    '                       B2BID As Int32,
    '                       invoiceDate As Date,
    '                       shipDate As Date,
    '                       itemDetails As List(Of ItemDetail),
    '                       companyName As String,
    '                       QBcompanyName As String,
    '                       memo As String,
    '                       po As String,
    '                       terms As String,
    '                       templateName As String,
    '                       invoiceNumber As String,
    '                       shipmethodvia As String) As Boolean

    '    Try
    '        ' --- Trim -R from invoice number
    '        Dim baseInvoiceNumber As String = invoiceNumber
    '        If baseInvoiceNumber.EndsWith("-R") Then
    '            baseInvoiceNumber = baseInvoiceNumber.Substring(0, baseInvoiceNumber.Length - 2)
    '        End If

    '        ' --- Step 1: Query invoice in QB
    '        Dim queryRq = sessionManager.CreateMsgSetRequest("US", 12, 0)
    '        Dim invoiceQuery As IInvoiceQuery = queryRq.AppendInvoiceQueryRq()
    '        invoiceQuery.RefNumber.SetValue(baseInvoiceNumber)
    '        invoiceQuery.ORInvoiceQuery.InvoiceFilter.ORRefNumberFilter.RefNumberFilter.RefNumber.SetValue(baseInvoiceNumber)
    '        Dim queryResp = sessionManager.DoRequests(queryRq)
    '        If queryResp Is Nothing OrElse queryResp.ResponseList.Count = 0 Then
    '            MessageBox.Show($"Invoice {baseInvoiceNumber} not found in QuickBooks.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
    '            Return False
    '        End If

    '        Dim invoiceRet As IInvoiceRet = CType(queryResp.ResponseList.GetAt(0).Detail, IInvoiceRet)
    '        Dim txnID As String = invoiceRet.TxnID.GetValue()
    '        Dim editSeq As String = invoiceRet.EditSequence.GetValue()

    '        ' --- Step 2: Build InvoiceModRq
    '        msgSetRequest.ClearRequests()
    '        Dim invoiceMod As IInvoiceMod = msgSetRequest.AppendInvoiceModRq()
    '        invoiceMod.TxnID.SetValue(txnID)
    '        invoiceMod.EditSequence.SetValue(editSeq)

    '        ' --- Step 3: Header fields
    '        If invoiceMod.CustomerRef IsNot Nothing Then invoiceMod.CustomerRef.FullName.SetValue(QBcompanyName)
    '        If invoiceDate <> Date.MinValue Then invoiceMod.TxnDate.SetValue(invoiceDate)
    '        If shipDate <> Date.MinValue Then invoiceMod.ShipDate.SetValue(shipDate)
    '        If Not String.IsNullOrWhiteSpace(terms) AndAlso invoiceMod.TermsRef IsNot Nothing Then invoiceMod.TermsRef.FullName.SetValue(terms)
    '        If Not String.IsNullOrWhiteSpace(memo) Then invoiceMod.Memo.SetValue(memo)
    '        If Not String.IsNullOrWhiteSpace(po) Then invoiceMod.PONumber.SetValue(po)
    '        If Not String.IsNullOrWhiteSpace(templateName) AndAlso invoiceMod.TemplateRef IsNot Nothing Then invoiceMod.TemplateRef.FullName.SetValue(templateName)
    '        If Not String.IsNullOrWhiteSpace(shipmethodvia) AndAlso invoiceMod.ShipMethodRef IsNot Nothing Then invoiceMod.ShipMethodRef.FullName.SetValue(shipmethodvia)

    '        ' Sales rep
    '        Dim repValue As String = GetRep(buyerID)
    '        If Not String.IsNullOrEmpty(repValue) AndAlso invoiceMod.SalesRepRef IsNot Nothing Then invoiceMod.SalesRepRef.FullName.SetValue(repValue)

    '        ' --- Step 4: Line items (replace all items with fresh ones)
    '        If itemDetails IsNot Nothing AndAlso itemDetails.Count > 0 Then
    '            For Each item In itemDetails
    '                Dim lineMod = invoiceMod.ORInvoiceLineModList.Append().InvoiceLineMod
    '                lineMod.TxnLineID.SetValue("-1") ' -1 means add new line
    '                If Not String.IsNullOrWhiteSpace(item.ItemCode) Then lineMod.ItemRef.FullName.SetValue(item.ItemCode)
    '                If item.Quantity > 0 Then lineMod.Quantity.SetValue(item.Quantity)
    '                If item.Amount > 0 Then lineMod.Amount.SetValue(item.Amount)
    '                If Not String.IsNullOrWhiteSpace(item.Description) Then lineMod.Desc.SetValue(item.Description)
    '                If Not String.IsNullOrWhiteSpace(item.ClassRef) Then lineMod.ClassRef.FullName.SetValue(item.ClassRef)
    '                lineMod.SalesTaxCodeRef.FullName.SetValue("NON")
    '            Next
    '        End If

    '        ' --- Step 5: Send Update request
    '        Dim modResp = sessionManager.DoRequests(msgSetRequest)
    '        Dim invoiceResponse = modResp.ResponseList.GetAt(0)

    '        If invoiceResponse.StatusCode = 0 Then
    '            ' ✅ Update DB
    '            Using conn2 As New MySqlConnection(connString)
    '                conn2.Open()
    '                Dim query_upd As String = "UPDATE loop_transaction_buyer SET qb_import_done_flg = 1, qb_import_done_date_time = NOW() WHERE id = @buyerID"
    '                Using cmd_upd As New MySqlCommand(query_upd, conn2)
    '                    cmd_upd.Parameters.AddWithValue("@buyerID", buyerID)
    '                    cmd_upd.ExecuteNonQuery()
    '                End Using
    '            End Using
    '            Return True
    '        Else
    '            MessageBox.Show("Error updating invoice: " & invoiceResponse.StatusMessage, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
    '            Return False
    '        End If

    '    Catch ex As Exception
    '        MessageBox.Show("Error while updating invoice: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
    '        Return False
    '    End Try
    'End Function


    Private Function TruncateString(value As String, maxLength As Integer) As String
        If String.IsNullOrEmpty(value) Then
            Return value
        End If

        If value.Length > maxLength Then
            Return value.Substring(0, maxLength)
        Else
            Return value
        End If
    End Function



End Class

