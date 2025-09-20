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
Public Class InsertInvoice
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
    Private fromDatePicker As DateTimePicker
    Private toDatePicker As DateTimePicker

    '── UI objects ----------------------------------------------------------
    Private WithEvents btnSubmit As Button
    ' Private WithEvents btnUpdate As Button
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



    Private Sub btnFilter_Click(sender As Object, e As EventArgs)
        Dim fromDate As Date = fromDatePicker.Value.Date
        Dim toDate As Date = toDatePicker.Value.Date.AddDays(1).AddSeconds(-1)

        If fromDate > toDate Then
            MessageBox.Show("From Date cannot be greater than To Date.", "Invalid Dates",
                        MessageBoxButtons.OK, MessageBoxIcon.Warning)
            Return
        End If

        ShowStatus("🔄 Validating rows, please wait...")
        ' MessageBox.Show($"Filter range: from = {fromDate:yyyy-MM-dd HH:mm:ss}  to = {toDate:yyyy-MM-dd HH:mm:ss}", "Debug")

        Task.Run(Sub()
                     Dim result As DataTable = Nothing
                     Try
                         result = FetchData(fromDate, toDate)
                     Catch ex As Exception
                         Me.Invoke(Sub()
                                       HideStatus()
                                       MessageBox.Show("DB Error: " & ex.Message, "Error",
                                                   MessageBoxButtons.OK, MessageBoxIcon.Error)
                                   End Sub)
                         Return
                     End Try

                     ' back to UI thread
                     Me.Invoke(Sub()
                                   BindData(result)
                                   ValidateAndHighlightRows()
                                   HideStatus()
                                   ' ShowStatus("✅ Validation complete.")  ' optional
                               End Sub)
                 End Sub)
    End Sub


    '── ① FETCH DATA FROM DATABASE -------------------------------------------
    Private Function FetchData(fromDate As Date, toDate As Date) As DataTable
        Dim localDT As New DataTable()
        Try
            If conn Is Nothing Then
                conn = New MySqlConnection(connString)
            End If

            If conn.State <> ConnectionState.Open Then
                conn.Open()
            End If
            Dim sql As String = $"
    SELECT ltb.id AS TransactionID, lw.company_name AS CompanyName, lw.quick_books_company_name AS QuickBooksCompanyName,
           ltb.loop_qb_invoice_no AS InvoiceNumber,
           DATE_FORMAT((SELECT MAX(timestamp) 
                        FROM loop_invoice_details lid 
                        WHERE lid.trans_rec_id = ltb.id), '%Y-%m-%d %H:%i:%s') AS InvoiceDate,
           ltb.inv_amount AS InvoiceAmount, 
           ltb.qb_import_done_flg AS InvoiceCreatedFlag, 
           ltb.inv_entered AS InvoiceUploadedFlag
    FROM loop_transaction_buyer ltb
    INNER JOIN loop_warehouse lw ON ltb.warehouse_id = lw.id
    LEFT JOIN loop_bol_files lbf ON ltb.id = lbf.trans_rec_id
    WHERE ltb.shipped = 1
      AND lbf.bol_shipment_received = 1
      AND inv_entered = 0
      AND ltb.ignore = 0
      AND ltb.no_invoice = 0
      AND ltb.id IN (SELECT trans_rec_id FROM loop_invoice_details)
      AND ltb.transaction_date BETWEEN '{fromDate:yyyy-MM-dd}' AND '{toDate:yyyy-MM-dd}'
    GROUP BY ltb.id
    ORDER BY ltb.id DESC;"


            Dim cmd As New MySqlCommand(sql, conn)
            'cmd.Parameters.AddWithValue("@fromDate", fromDate)
            'cmd.Parameters.AddWithValue("@toDate", toDate)
            ' 👉 Build debug query with values
            'Dim debugSql As String = sql.Replace("@fromDate", "'" & fromDate.ToString("yyyy-MM-dd HH:mm:ss") & "'") _
            '.Replace("@toDate", "'" & toDate.ToString("yyyy-MM-dd HH:mm:ss") & "'")

            'MessageBox.Show(debugSql, "SQL Debug", MessageBoxButtons.OK, MessageBoxIcon.Information)

            Dim da As New MySqlDataAdapter(cmd)
            da.Fill(localDT)

            If Not localDT.Columns.Contains("Status") Then
                localDT.Columns.Add("Status", GetType(String))
            End If

            ' 🔹 Give it a default value (can be empty or "Pending")
            For Each row As DataRow In localDT.Rows
                row("Status") = ""  ' or "Pending"
            Next

        Finally
            conn.Close()
        End Try
        Return localDt
    End Function


    '── ② BIND DATA TO GRID --------------------------------------------------
    Private Sub BindData(dt As DataTable)
        dataview.DataSource = Nothing
        dataview.Columns.Clear()

        If dt.Rows.Count = 0 Then
            MessageBox.Show("No records found!", "Info",
                        MessageBoxButtons.OK, MessageBoxIcon.Information)
            Return
        End If

        dataview.DataSource = dt
        AddHandler dataview.CellPainting, AddressOf DataGridView_CellPainting
        RemoveHandler dataview.CellFormatting, AddressOf dataview_CellFormatting
        AddHandler dataview.CellFormatting, AddressOf dataview_CellFormatting


        ' Move Status column before TransactionID
        If dataview.Columns.Contains("Status") Then
            dataview.Columns("Status").DisplayIndex = dataview.Columns("TransactionID").DisplayIndex
        End If
        ' Checkbox column
        Dim cbCol As New DataGridViewCheckBoxColumn With {
        .Name = "Select",
        .HeaderText = "",
        .Width = 40,
        .DefaultCellStyle = New DataGridViewCellStyle With {
            .Alignment = DataGridViewContentAlignment.MiddleCenter}
    }
        dataview.Columns.Insert(0, cbCol)

        ' Edit button column
        Dim btnCol As New DataGridViewButtonColumn With {
        .Name = "EditRow",
        .HeaderText = "Action",
        .Text = "✎",
        .UseColumnTextForButtonValue = True,
        .Width = 60
    }
        dataview.Columns.Insert(1, btnCol)

        ' Upload button column
        Dim uploadCol As New DataGridViewButtonColumn With {
        .Name = "UploadInvoice",
        .HeaderText = "Upload",
        .Width = 60
    }
        dataview.Columns.Insert(2, uploadCol)

        ' Status column
        '    Dim statusCol As New DataGridViewTextBoxColumn With {
        '    .Name = "Status",
        '    .HeaderText = "Status",
        '    .Width = 300,
        '    .ReadOnly = True
        '}
        '    dataview.Columns.Insert(3, statusCol)

        ' Friendly headers + widths
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

        ' Editable columns
        For Each col As DataGridViewColumn In dataview.Columns
            col.ReadOnly = (col.Name = "EditRow" OrElse col.Name = "TransactionID")
        Next

        ' Hide flag columns
        dataview.Columns("InvoiceUploadedFlag").Visible = False
        dataview.Columns("InvoiceCreatedFlag").Visible = False

        ' Add header checkbox
        AddSelectAllCheckbox()
    End Sub

    'Private Sub LoadData(fromDate As Date, toDate As Date)
    '    Try
    '        conn.Open()
    '        'Dim sql As String = "SELECT ltb.id AS TransactionID, lw.company_name AS CompanyName, lw.quick_books_company_name AS QuickBooksCompanyName, ltb.loop_qb_invoice_no AS InvoiceNumber, 
    '        '                        DATE_FORMAT((
    '        '                            SELECT MAX(timestamp)
    '        '                            FROM loop_invoice_details lid
    '        '                            WHERE lid.trans_rec_id = ltb.id
    '        '                        ), '%Y-%m-%d %H:%i:%s') AS InvoiceDate
    '        '                        , ltb.inv_amount AS InvoiceAmount, ltb.qb_import_done_flg AS InvoiceCreatedFlag, ltb.inv_entered AS InvoiceUploadedFlag FROM loop_transaction_buyer ltb INNER JOIN loop_warehouse lw ON ltb.warehouse_id = lw.id left join loop_bol_files lbf on ltb.id = lbf.trans_rec_id 
    '        '                        WHERE  year(ltb.transaction_date) > 2024 and ltb.shipped = 1 and lbf.bol_shipment_received = 1 and inv_entered = 0 and 
    '        '                        ltb.ignore = 0 and ltb.no_invoice = 0 and ltb.id in (select trans_rec_id 
    '        '                        from loop_invoice_details)  GROUP BY ltb.id ORDER BY ltb.id DESC;"

    '        'Dim da As New MySqlDataAdapter(sql, conn)
    '        'dt.Clear()
    '        'da.Fill(dt)
    '        Dim sql As String =
    '        "SELECT ltb.id AS TransactionID, lw.company_name AS CompanyName, lw.quick_books_company_name AS QuickBooksCompanyName, " &
    '        "ltb.loop_qb_invoice_no AS InvoiceNumber, " &
    '        "DATE_FORMAT((SELECT MAX(timestamp) FROM loop_invoice_details lid WHERE lid.trans_rec_id = ltb.id), '%Y-%m-%d %H:%i:%s') AS InvoiceDate, " &
    '        "ltb.inv_amount AS InvoiceAmount, ltb.qb_import_done_flg AS InvoiceCreatedFlag, ltb.inv_entered AS InvoiceUploadedFlag " &
    '        "FROM loop_transaction_buyer ltb " &
    '        "INNER JOIN loop_warehouse lw ON ltb.warehouse_id = lw.id " &
    '        "LEFT JOIN loop_bol_files lbf ON ltb.id = lbf.trans_rec_id " &
    '        "WHERE YEAR(ltb.transaction_date) > 2024 " &
    '        "AND ltb.shipped = 1 " &
    '        "AND lbf.bol_shipment_received = 1 " &
    '        "AND inv_entered = 0 " &
    '        "AND ltb.ignore = 0 " &
    '        "AND ltb.no_invoice = 0 " &
    '        "AND ltb.id IN (SELECT trans_rec_id FROM loop_invoice_details) " &
    '        "AND ltb.transaction_date BETWEEN @fromDate AND @toDate " &
    '        "GROUP BY ltb.id " &
    '        "ORDER BY ltb.id DESC;"

    '        Dim da As New MySqlDataAdapter(sql, conn)
    '        da.SelectCommand.Parameters.AddWithValue("@fromDate", fromDate)
    '        da.SelectCommand.Parameters.AddWithValue("@toDate", toDate)

    '        dt.Clear()
    '        da.Fill(dt)
    '        If dt.Rows.Count = 0 Then
    '            MessageBox.Show("No records found!", "Info",
    '                    MessageBoxButtons.OK, MessageBoxIcon.Information)
    '            dataview.DataSource = Nothing
    '            Return
    '        End If

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

    '        ' ③ UPLOAD button column (third)
    '        '    Dim uploadCol As New DataGridViewButtonColumn With {
    '        '    .Name = "UploadInvoice",
    '        '    .HeaderText = "Upload",
    '        '    .Text = "📤",
    '        '    .UseColumnTextForButtonValue = True,
    '        '    .Width = 60
    '        '}
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







    Private Sub Grid_DataBindingComplete(sender As Object, e As DataGridViewBindingCompleteEventArgs)

        ValidateAndHighlightRows() ' ✅ call your logic here
        dataview.ClearSelection()
    End Sub



    Private Sub BuildUI()

        Dock = DockStyle.Fill
        BackColor = Color.White

        ' ▸ Legend row (row 1) -------------------------------------------------
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
        Dim lblGreen As New Label With {.Text = "Transaction ready for insert", .AutoSize = True, .Font = New Font("Verdana", 9)}
        Dim pnlRed As New Panel With {.BackColor = Color.LightCoral, .Size = New Size(15, 15), .Margin = New Padding(20, 0, 5, 0)}
        Dim lblRed As New Label With {.Text = "Not ready for insert", .AutoSize = True, .Font = New Font("Verdana", 9)}

        pnlLegend.Controls.Add(pnlGreen)
        pnlLegend.Controls.Add(lblGreen)
        pnlLegend.Controls.Add(pnlRed)
        pnlLegend.Controls.Add(lblRed)

        Dim pnlLegendContainer As New Panel With {
        .Padding = New Padding(15, 8, 15, 8),
        .BorderStyle = BorderStyle.FixedSingle,
        .AutoSize = True,
        .AutoSizeMode = AutoSizeMode.GrowAndShrink
    }
        pnlLegendContainer.Controls.Add(pnlLegend)


        AddHandler pnlGreen.Click, AddressOf LegendFilter_Click
        AddHandler lblGreen.Click, AddressOf LegendFilter_Click

        AddHandler pnlRed.Click, AddressOf LegendFilter_Click
        AddHandler lblRed.Click, AddressOf LegendFilter_Click

        ' ▸ Date selectors + Buttons row (row 2) -------------------------------
        Dim tlpDatesButtons As New TableLayoutPanel With {
        .Dock = DockStyle.Top,
        .AutoSize = True,
        .ColumnCount = 2
    }
        tlpDatesButtons.ColumnStyles.Add(New ColumnStyle(SizeType.Percent, 50))   ' left: dates
        tlpDatesButtons.ColumnStyles.Add(New ColumnStyle(SizeType.Percent, 50))   ' right: buttons

        ' Left side: dates
        Dim flpDates As New FlowLayoutPanel With {
        .FlowDirection = FlowDirection.LeftToRight,
        .WrapContents = False,
        .AutoSize = True,
        .Dock = DockStyle.Left
    }

        Dim lblFromDate As New Label With {.Text = "From Date:", .AutoSize = True, .Font = New Font("Verdana", 9), .Margin = New Padding(0, 8, 5, 0)}

        Dim lblToDate As New Label With {.Text = "To Date:", .AutoSize = True, .Font = New Font("Verdana", 9), .Margin = New Padding(20, 8, 5, 0)}
        ' From Date
        fromDatePicker = New DateTimePicker With {
    .Format = DateTimePickerFormat.Short,
    .Width = 120,
    .Font = New Font("Verdana", 9)
}

        ' To Date
        toDatePicker = New DateTimePicker With {
    .Format = DateTimePickerFormat.Short,
    .Width = 120,
    .Font = New Font("Verdana", 9)
}

        flpDates.Controls.Add(lblFromDate)
        flpDates.Controls.Add(fromDatePicker)
        flpDates.Controls.Add(lblToDate)
        flpDates.Controls.Add(toDatePicker)

        ' Right side: buttons
        Dim flpButtons As New FlowLayoutPanel With {
        .FlowDirection = FlowDirection.LeftToRight,
        .WrapContents = False,
        .AutoSize = True,
        .Dock = DockStyle.Right
    }

        Dim btnFilter As Button = MakeButton("Filter") : btnFilter.Width = 120
        AddHandler btnFilter.Click, AddressOf btnFilter_Click

        btnExportExcel = MakeButton("Export to Excel") : btnExportExcel.Width = 150
        AddHandler btnExportExcel.Click, AddressOf btnExportExcel_Click

        btnSubmit = MakeButton("Insert Invoice") : btnSubmit.Width = 150
        'btnUploadSelected = MakeButton("Upload Invoice") : btnUploadSelected.Width = 150

        flpButtons.Controls.Add(btnFilter)
        flpButtons.Controls.Add(btnExportExcel)
        flpButtons.Controls.Add(btnSubmit)
        'flpButtons.Controls.Add(btnUploadSelected)


        tlpDatesButtons.Controls.Add(flpDates, 0, 0)
        tlpDatesButtons.Controls.Add(flpButtons, 1, 0)

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

        ' ▸ label between two grids ------------------------------------------
        lblUploadedHeader = New Label With {
        .Text = "Below are uploaded invoices",
        .Dock = DockStyle.Top,
        .Height = 30,
        .Padding = New Padding(10, 5, 0, 0),
        .Font = New Font("Verdana", 10, FontStyle.Bold),
        .ForeColor = Color.FromArgb(31, 30, 68),
        .Margin = New Padding(0, 20, 0, 0)
    }

        ' ▸ uploaded dataview ------------------------------------------------
        dataviewUploaded = New DataGridView With {
        .Dock = DockStyle.Fill,
        .ReadOnly = True,
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

        ' ▸ combined vertical layout -----------------------------------------
        Dim mainLayout As New TableLayoutPanel With {
        .Dock = DockStyle.Fill,
        .AutoScroll = True,
        .AutoSize = True,
        .ColumnCount = 1,
        .RowCount = 5
    }

        mainLayout.RowStyles.Add(New RowStyle(SizeType.AutoSize))  ' legend row
        mainLayout.RowStyles.Add(New RowStyle(SizeType.AutoSize))  ' dates + buttons row
        mainLayout.RowStyles.Add(New RowStyle(SizeType.Percent, 120)) ' dataview
        mainLayout.RowStyles.Add(New RowStyle(SizeType.AutoSize))  ' label
        mainLayout.RowStyles.Add(New RowStyle(SizeType.Percent, 100)) ' uploaded dataview

        mainLayout.Controls.Add(pnlLegendContainer, 0, 0)
        mainLayout.Controls.Add(tlpDatesButtons, 0, 1)
        mainLayout.Controls.Add(dataview, 0, 2)
        mainLayout.Controls.Add(lblUploadedHeader, 0, 3)
        mainLayout.Controls.Add(dataviewUploaded, 0, 4)

        ' ▸ overlay panel ----------------------------------------------------
        pnlOverlay = New Panel With {
        .Size = New Size(400, 50),
        .BackColor = Color.FromArgb(20, 0, 150, 136),
        .Visible = False,
        .Anchor = AnchorStyles.None
    }
        lblStatus = New Label With {.Dock = DockStyle.Fill, .ForeColor = Color.Black, .TextAlign = ContentAlignment.MiddleCenter, .Font = New Font("Verdana", 10, FontStyle.Bold)}
        pnlOverlay.Controls.Add(lblStatus)
        pnlOverlay.Location = New Point(-90, 30)

        ' ▸ final layout -----------------------------------------------------
        Controls.Add(mainLayout)
        Controls.Add(pnlOverlay)
        pnlOverlay.BringToFront()

        ' ▸ event wiring -----------------------------------------------------
        AddHandler btnSubmit.Click, AddressOf SubmitSelectedRows
        'AddHandler btnUploadSelected.Click, AddressOf btnUploadSelected_Click
        AddHandler dataview.DataError, Sub(s, eargs) eargs.ThrowException = False
        AddHandler dataview.CellContentClick, AddressOf GridActionClicked
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


    'Private Sub BuildUI()

    '    Dock = DockStyle.Fill
    '    BackColor = Color.White

    '    ' ▸ top strip --------------------------------------------------------
    '    Dim tlpTop As New TableLayoutPanel With {
    '    .Dock = DockStyle.Top,
    '    .AutoSize = True,
    '    .ColumnCount = 3,
    '    .RowCount = 1,
    '    .Padding = New Padding(15)
    '}
    '    tlpTop.ColumnStyles.Add(New ColumnStyle(SizeType.AutoSize))   ' legend on left
    '    tlpTop.ColumnStyles.Add(New ColumnStyle(SizeType.Percent, 100)) ' filler middle
    '    tlpTop.ColumnStyles.Add(New ColumnStyle(SizeType.AutoSize))   ' buttons on right

    '    ' ▸ Legend panel (inside border container) --------------------------
    '    Dim pnlLegend As New FlowLayoutPanel With {
    '    .FlowDirection = FlowDirection.LeftToRight,
    '    .WrapContents = False,
    '    .AutoSize = True,
    '    .AutoSizeMode = AutoSizeMode.GrowAndShrink,
    '    .Dock = DockStyle.Fill,
    '    .Margin = New Padding(0, 9, 0, 0) ' Push legend down a bit
    '}

    '    ' Green legend item
    '    Dim pnlGreen As New Panel With {
    '    .BackColor = Color.LightGreen,
    '    .Size = New Size(15, 15),
    '    .Margin = New Padding(0, 0, 5, 0)
    '}
    '    Dim lblGreen As New Label With {
    '    .Text = "Transaction ready for insert",
    '    .AutoSize = True,
    '    .Height = 18,
    '    .TextAlign = ContentAlignment.MiddleLeft,
    '    .Font = New Font("Verdana", 9, FontStyle.Regular),
    '    .Margin = New Padding(0, 0, 15, 0)
    '}

    '    ' Red legend item
    '    Dim pnlRed As New Panel With {
    '    .BackColor = Color.LightCoral,
    '    .Size = New Size(15, 15),
    '    .Margin = New Padding(0, 0, 5, 0)
    '}
    '    Dim lblRed As New Label With {
    '    .Text = "Not ready for insert",
    '    .AutoSize = True,
    '    .Height = 18,
    '    .TextAlign = ContentAlignment.MiddleLeft,
    '    .Font = New Font("Verdana", 9, FontStyle.Regular)
    '}

    '    ' Add items to legend panel
    '    pnlLegend.Controls.Add(pnlGreen)
    '    pnlLegend.Controls.Add(lblGreen)
    '    pnlLegend.Controls.Add(pnlRed)
    '    pnlLegend.Controls.Add(lblRed)

    '    ' Wrap legend in a border container
    '    Dim pnlLegendContainer As New Panel With {
    '    .Padding = New Padding(15, 8, 15, 8), ' more horizontal and vertical padding
    '    .BorderStyle = BorderStyle.FixedSingle,
    '    .AutoSize = True,
    '    .AutoSizeMode = AutoSizeMode.GrowAndShrink,
    '    .Margin = New Padding(0, 5, 0, 0) ' adjust top spacing
    '}
    '    pnlLegendContainer.Controls.Add(pnlLegend)

    '    ' Buttons

    '    btnSubmit = MakeButton("Insert Invoice")
    '    btnSubmit.Width = 150
    '    btnUpdate = MakeButton("Update Invoice")
    '    btnUpdate.Width = 150
    '    btnUploadSelected = MakeButton("Upload Invoice")
    '    btnUploadSelected.Width = 150

    '    Dim pnlButtons As New FlowLayoutPanel With {
    '    .FlowDirection = FlowDirection.LeftToRight,
    '    .WrapContents = False,
    '    .AutoSize = True,
    '    .AutoSizeMode = AutoSizeMode.GrowAndShrink,
    '    .Dock = DockStyle.Fill,
    '    .Margin = New Padding(0)
    '}


    '    btnExportExcel = MakeButton("Export to Excel")
    '    btnExportExcel.Width = 150
    '    pnlButtons.Controls.Add(btnExportExcel)

    '    ' Event handler
    '    AddHandler btnExportExcel.Click, AddressOf btnExportExcel_Click

    '    pnlButtons.Controls.Add(btnSubmit)
    '    pnlButtons.Controls.Add(btnUploadSelected)

    '    ' Add legend container, filler, and buttons to top strip
    '    tlpTop.Controls.Add(pnlLegendContainer, 0, 0)
    '    tlpTop.Controls.Add(New Label(), 1, 0) ' filler
    '    tlpTop.Controls.Add(pnlButtons, 2, 0)

    '    ' ▸ MAIN dataview ----------------------------------------------------
    '    dataview = New DataGridView With {
    '    .Dock = DockStyle.Fill,
    '    .ReadOnly = False,
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

    '    ' ▸ label between two grids ------------------------------------------
    '    lblUploadedHeader = New Label With {
    '    .Text = "Below are uploaded invoices",
    '    .Dock = DockStyle.Top,
    '    .Height = 30,
    '    .Padding = New Padding(10, 5, 0, 0),
    '    .Font = New Font("Verdana", 10, FontStyle.Bold),
    '    .ForeColor = Color.FromArgb(31, 30, 68)
    '}
    '    lblUploadedHeader.Margin = New Padding(0, 20, 0, 0)

    '    ' ▸ uploaded dataview ------------------------------------------------
    '    dataviewUploaded = New DataGridView With {
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

    '    ' ▸ combined vertical layout -----------------------------------------
    '    Dim mainLayout As New TableLayoutPanel With {
    '    .Dock = DockStyle.Fill,
    '    .AutoScroll = True,
    '    .AutoSize = True,
    '    .ColumnCount = 1,
    '    .RowCount = 4
    '}

    '    mainLayout.RowStyles.Add(New RowStyle(SizeType.AutoSize))
    '    mainLayout.RowStyles.Add(New RowStyle(SizeType.Percent, 120))
    '    mainLayout.RowStyles.Add(New RowStyle(SizeType.AutoSize))
    '    mainLayout.RowStyles.Add(New RowStyle(SizeType.Percent, 100))

    '    mainLayout.Controls.Add(tlpTop, 0, 0)
    '    mainLayout.Controls.Add(dataview, 0, 1)
    '    mainLayout.Controls.Add(lblUploadedHeader, 0, 2)
    '    mainLayout.Controls.Add(dataviewUploaded, 0, 3)

    '    ' ▸ overlay panel ----------------------------------------------------
    '    pnlOverlay = New Panel With {
    '    .Size = New Size(400, 50),
    '    .BackColor = Color.FromArgb(20, 0, 150, 136),
    '    .Visible = False,
    '    .Anchor = AnchorStyles.None
    '}

    '    lblStatus = New Label With {
    '    .Dock = DockStyle.Fill,
    '    .ForeColor = Color.Black,
    '    .TextAlign = ContentAlignment.MiddleCenter,
    '    .Font = New Font("Verdana", 10, FontStyle.Bold)
    '}

    '    pnlOverlay.Controls.Add(lblStatus)
    '    pnlOverlay.Location = New Point(-90, 30)

    '    ' ▸ final layout -----------------------------------------------------
    '    Controls.Add(mainLayout)
    '    Controls.Add(pnlOverlay)
    '    pnlOverlay.BringToFront()

    '    ' ▸ event wiring -----------------------------------------------------
    '    AddHandler btnSubmit.Click, AddressOf SubmitSelectedRows
    '    AddHandler btnUploadSelected.Click, AddressOf btnUploadSelected_Click
    '    AddHandler dataview.DataError, Sub(s, eargs) eargs.ThrowException = False
    '    AddHandler dataview.CellContentClick, AddressOf GridActionClicked
    '    AddHandler btnUpdate.Click, AddressOf btnUpdate_Click
    'End Sub





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



    Private Sub dataview_CellFormatting(sender As Object, e As DataGridViewCellFormattingEventArgs)
        If dataview.Columns(e.ColumnIndex).Name = "Status" Then
            Dim statusText As String = If(e.Value, "").ToString()

            If statusText.StartsWith("✔ Validation Passed") Then
                dataview.Rows(e.RowIndex).DefaultCellStyle.BackColor = Color.LightGreen
            ElseIf statusText.StartsWith("❌") Then
                dataview.Rows(e.RowIndex).DefaultCellStyle.BackColor = Color.FromArgb(255, 220, 220)
            ElseIf statusText.StartsWith("✔ Credit Memo Entry") Then
                dataview.Rows(e.RowIndex).DefaultCellStyle.BackColor = Color.Khaki
            Else
                dataview.Rows(e.RowIndex).DefaultCellStyle.BackColor = Color.White
            End If
        End If
    End Sub


    Private Sub GridActionClicked(sender As Object, e As DataGridViewCellEventArgs)
        If e.RowIndex < 0 Then Return

        Dim clickedColumn = dataview.Columns(e.ColumnIndex).Name
        Dim r = dataview.Rows(e.RowIndex)
        'If e.RowIndex < 0 OrElse dataview.Columns(e.ColumnIndex).Name <> "EditRow" Then Return

        'Dim r = dataview.Rows(e.RowIndex)


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

                'If popup.ShowDialog() = DialogResult.OK Then
                '    LoadData() ' Reload your grid
                '    LoadUploadedData()
                '    BeginInvoke(Sub()
                '                    ShowStatus("🔄 Loading data, please wait...")
                '                    ValidateAndHighlightRows()
                '                End Sub)
                'End If
                If popup.ShowDialog() = DialogResult.OK Then
                    ShowStatus("🔄 Loading data, please wait...")

                    Dim fromDate As Date = fromDatePicker.Value.Date
                    Dim toDate As Date = fromDatePicker.Value.Date.AddDays(1).AddSeconds(-1)

                    Task.Run(Sub()
                                 Dim result As DataTable = Nothing
                                 Try
                                     result = FetchData(fromDate, toDate)
                                 Catch ex As Exception
                                     Me.Invoke(Sub()
                                                   HideStatus()
                                                   MessageBox.Show("DB Error: " & ex.Message, "Error",
                                                   MessageBoxButtons.OK, MessageBoxIcon.Error)
                                               End Sub)
                                     Return
                                 End Try

                                 ' back on UI thread
                                 Me.Invoke(Sub()
                                               BindData(result)
                                               ValidateAndHighlightRows()
                                               ShowStatus("✅ Data reload complete.")
                                           End Sub)
                             End Sub)
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



    Private Sub LoadUploadedData()
        Try
            conn.Open()

            Dim sql As String =
        "SELECT  ltb.id                              AS TransactionID,
                 lw.company_name                     AS CompanyName,
                 lw.quick_books_company_name         AS QuickBooksCompanyName,
                 ltb.loop_qb_invoice_no              AS InvoiceNumber,
                 ( SELECT MAX(timestamp)
                     FROM loop_invoice_details lid
                     WHERE lid.trans_rec_id = ltb.id ) AS InvoiceDate,
                 ltb.inv_amount                      AS InvoiceAmount 
         FROM      loop_transaction_buyer ltb
         JOIN      loop_warehouse          lw  ON ltb.warehouse_id  = lw.id
         LEFT JOIN loop_bol_files          lbf ON ltb.id            = lbf.trans_rec_id
         WHERE ltb.shipped = 1 
           AND YEAR(ltb.transaction_date) > 2023
           AND ltb.id IN (SELECT trans_rec_id FROM loop_invoice_details)
           AND ltb.loop_qb_invoice_no LIKE 'ZW%'
           AND ltb.inv_entered = 1 
           AND ltb.inv_employee = 'MNM'
         ORDER BY ltb.id;"

            Dim da As New MySqlDataAdapter(sql, conn)
            Dim uploadedDt As New DataTable()
            da.Fill(uploadedDt)

            If uploadedDt.Rows.Count = 0 Then
                dataviewUploaded.DataSource = Nothing
                Return
            End If

            ' Bind uploaded data grid
            dataviewUploaded.DataSource = Nothing
            dataviewUploaded.Columns.Clear()
            dataviewUploaded.DataSource = uploadedDt

            ' Add view button
            Dim btnCol As New DataGridViewButtonColumn With {
            .Name = "ViewInvoice",
            .HeaderText = "Action",
            .Text = "🔍 View",
            .UseColumnTextForButtonValue = True,
            .Width = 80
        }
            dataviewUploaded.Columns.Insert(0, btnCol)

            ' Set friendly headers and widths
            Dim widths = New Dictionary(Of String, Integer) From {
            {"TransactionID", 80},
            {"CompanyName", 300},
            {"QuickBooksCompanyName", 300},
            {"InvoiceNumber", 90},
            {"InvoiceDate", 90},
            {"InvoiceAmount", 95}
        }

            For Each kvp In widths
                With dataviewUploaded.Columns(kvp.Key)
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

            ' Make all columns readonly
            For Each col As DataGridViewColumn In dataviewUploaded.Columns
                col.ReadOnly = True
            Next

            ' Handle View button click
            AddHandler dataviewUploaded.CellContentClick, AddressOf UploadedGrid_ViewClicked

        Catch ex As Exception
            MessageBox.Show("DB Error: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        Finally
            conn.Close()
        End Try
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

    Private Sub btnUploadSelected_Click(sender As Object, e As EventArgs)
        Dim statusForm As New frmStatusPrompt()
        statusForm.lblMessage.Text = "Uploading invoices, please wait..."
        statusForm.Show()
        Application.DoEvents() ' Let UI update before SendKeys

        Dim totalSelected As Integer = 0
        Dim totalUploaded As Integer = 0
        Dim totalSkipped As Integer = 0
        Dim totalFailed As Integer = 0
        Dim failedInvoices As New List(Of String)()

        For Each row As DataGridViewRow In dataview.Rows
            If Convert.ToBoolean(row.Cells("Select").Value) Then
                totalSelected += 1

                ' Check if invoice is created
                Dim invoiceCreated As Boolean = False
                If row.Cells("InvoiceCreatedFlag").Value IsNot DBNull.Value Then
                    invoiceCreated = Convert.ToBoolean(row.Cells("InvoiceCreatedFlag").Value)
                End If

                If Not invoiceCreated Then
                    totalSkipped += 1
                    Continue For
                End If

                ' Gather info
                Dim transRecId As String = row.Cells("TransactionID").Value.ToString()
                Dim companyName As String = row.Cells("CompanyName").Value.ToString()
                Dim invoiceNumber As String = row.Cells("InvoiceNumber").Value.ToString()
                Dim invoiceDateOf As String = row.Cells("InvoiceDate").Value.ToString()
                Dim invoiceAmount As String = row.Cells("InvoiceAmount").Value.ToString().Replace("₹", "").Replace("$", "").Replace(",", "").Trim()

                ' Print from QB to PDF
                Dim qbAutomation As New QuickBooksAutomation()
                qbAutomation.PrintInvoice(invoiceNumber)

                ' Check if PDF exists
                Dim desktopPath As String = Environment.GetFolderPath(Environment.SpecialFolder.Desktop)
                Dim pdfFilePath As String = System.IO.Path.Combine(desktopPath, "Invoice_" & invoiceNumber & ".pdf")

                If Not System.IO.File.Exists(pdfFilePath) Then
                    failedInvoices.Add(invoiceNumber & " (PDF not found)")
                    totalFailed += 1
                    Continue For
                End If

                ' Upload to FTP
                Dim remoteFileName As String = New Random().Next(1000, 9999).ToString() & "_Invoice_" & invoiceNumber & ".pdf"
                Dim success As Boolean = FTPUploader.UploadFileToFTP(pdfFilePath, remoteFileName)

                If success Then
                    UploadInvoiceToDatabase(transRecId, companyName, invoiceNumber, invoiceAmount, invoiceDateOf, remoteFileName)
                    totalUploaded += 1
                Else
                    failedInvoices.Add(invoiceNumber & " (FTP failed)")
                    totalFailed += 1
                End If
            End If
        Next

        ' ── Show Summary ──
        Dim summaryMsg As String = $"Upload Summary:{vbCrLf}" &
                               $"---------------------------------------{vbCrLf}" &
                               $"Total Selected: {totalSelected}{vbCrLf}" &
                               $"Uploaded Successfully: {totalUploaded}{vbCrLf}" &
                               $"Skipped (Not Created): {totalSkipped}{vbCrLf}" &
                               $"Failed: {totalFailed}"
        'LoadData() ' Reload your grid
        'LoadUploadedData()
        'BeginInvoke(Sub()
        '                ShowStatus("🔄 Please wait data loading...")
        '                ValidateAndHighlightRows()

        '            End Sub)
        If failedInvoices.Count > 0 Then
            summaryMsg &= vbCrLf & vbCrLf & "Failed Invoices:" & vbCrLf & String.Join(vbCrLf, failedInvoices)
        End If
        statusForm.Close()
        MessageBox.Show(summaryMsg, "Upload Summary", MessageBoxButtons.OK, MessageBoxIcon.Information)

    End Sub





    Public Class FTPUploader
        Public Shared Function UploadFileToFTP(localFilePath As String, remoteFileName As String) As Boolean
            Try
                ' Set FTP server details
                Dim ftpServer As String = "ftp://www.ucbloops.com/loops/files/"
                Dim ftpUsername As String = "ftpuser@ucbloops.com"
                Dim ftpPassword As String = "0@qYSOdPuR9+"

                ' Create FTP request
                Dim request As FtpWebRequest = CType(WebRequest.Create(ftpServer & remoteFileName), FtpWebRequest)
                request.Credentials = New NetworkCredential(ftpUsername, ftpPassword)
                request.Method = WebRequestMethods.Ftp.UploadFile
                request.UseBinary = True
                request.UsePassive = True
                request.KeepAlive = False

                ' Read the file into a byte array
                Dim fileContents As Byte() = System.IO.File.ReadAllBytes(localFilePath)
                request.ContentLength = fileContents.Length

                Using requestStream As System.IO.Stream = request.GetRequestStream()
                    requestStream.Write(fileContents, 0, fileContents.Length)
                End Using

                Using response As FtpWebResponse = CType(request.GetResponse(), FtpWebResponse)
                    Console.WriteLine("Upload Successful: " & response.StatusDescription)
                End Using

                Return True
            Catch ex As Exception
                MessageBox.Show("FTP Upload Error: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
                Return False
            End Try
        End Function
    End Class





    Public Class QuickBooksAutomation

        ' ⬅️ Add these at the top of your form/class


        <DllImport("user32.dll")>
        Private Shared Function FindWindow(lpClassName As String, lpWindowName As String) As IntPtr
        End Function

        <DllImport("user32.dll")>
        Private Shared Function SetForegroundWindow(hWnd As IntPtr) As Boolean
        End Function

        Public Sub PrintInvoice(invoiceNumber As String)
            Try
                ' 🔍 Find QuickBooks Window by partial title
                Dim foundQBWindow As Boolean = False
                For Each p As Process In Process.GetProcesses()
                    If Not String.IsNullOrEmpty(p.MainWindowTitle) AndAlso
                   p.MainWindowTitle.Contains("QuickBooks") Then

                        ' Activate window
                        SetForegroundWindow(p.MainWindowHandle)
                        foundQBWindow = True
                        Exit For
                    End If
                Next

                If Not foundQBWindow Then
                    MessageBox.Show("QuickBooks window not found. Please make sure it is open.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
                    Exit Sub
                End If

                ' Wait for QuickBooks to be in focus
                Thread.Sleep(1000)
                SendKeys.SendWait("{ESC}")
                Thread.Sleep(500)
                ' Open the "Find" window (Ctrl + F)
                SendKeys.SendWait("^f")
                Thread.Sleep(1000)

                SendKeys.SendWait("%i")   ' % = Alt, so %i = Alt + I
                Thread.Sleep(500)

                ' Move focus to the tabs section first
                SendKeys.SendWait("+{TAB}") ' Shift+Tab to move focus to tabs
                Thread.Sleep(500)

                SendKeys.SendWait("+{LEFT}") ' Shift + Left Arrow to go to the left tab
                Thread.Sleep(500)

                ' Move into Invoice tab's search field
                SendKeys.SendWait("{TAB}")
                Thread.Sleep(500)
                ' Move down to "Invoice" tab
                For i As Integer = 1 To 4
                    SendKeys.SendWait("{DOWN}")
                    Thread.Sleep(500)
                Next

                ' Move into Invoice tab's search field
                SendKeys.SendWait("{TAB}")
                Thread.Sleep(500)

                ' Enter the Invoice Number
                SendKeys.SendWait(invoiceNumber)
                Thread.Sleep(500)

                ' Press Enter to search
                SendKeys.SendWait("~")
                Thread.Sleep(3000) ' Wait for search results
                ' Press Enter to search


                ' Move focus to search results and select first match
                SendKeys.SendWait("{TAB}") ' Move to results area
                Thread.Sleep(500)
                SendKeys.SendWait("{DOWN}") ' Select first result
                Thread.Sleep(500)

                ' Click "Go To" instead of "Export"
                SendKeys.SendWait("{TAB}") ' Move to "Go To"
                Thread.Sleep(500)
                SendKeys.SendWait("~") ' Press Enter to open the invoice
                Thread.Sleep(3000) ' Wait for the invoice to open

                ' 🔹 PRINTING PROCESS 🔹
                SendKeys.SendWait("^p") ' Open print dialog
                Thread.Sleep(2000)

                ' Select printer dropdown
                SendKeys.SendWait("{TAB}") ' Move focus to printer dropdown
                Thread.Sleep(500)
                SendKeys.SendWait("{DOWN}") ' Open dropdown
                Thread.Sleep(500)

                ' Select "Microsoft Print to PDF"
                For i As Integer = 1 To 5 ' Adjust based on position in the list
                    SendKeys.SendWait("{DOWN}")
                    Thread.Sleep(500)
                Next
                SendKeys.SendWait("~") ' Select printer
                Thread.Sleep(1000)

                ' Click "Print" (first tab on the right side)
                SendKeys.SendWait("~") ' Press Enter to print
                Thread.Sleep(2000)

                ' 🔹 Save PDF 🔹
                'Dim saveFileName As String = "Invoice_" & invoiceNumber & ".pdf"
                'Dim desktopPath As String = Environment.GetFolderPath(Environment.SpecialFolder.Desktop)
                ''Dim saveFilePath As String = My.Application.Info.DirectoryPath & "\Invoice_pdf_files\Invoice_" & invoiceNumber & ".pdf"
                'Dim saveFilePath As String = "C:\Users\lenovo\Desktop\Invoice_" & invoiceNumber & ".pdf"

                'SendKeys.SendWait(saveFilePath) ' Type file name
                'Thread.Sleep(500)
                'SendKeys.SendWait("~") ' Press Enter to save
                'Thread.Sleep(3000)

                '' Close QuickBooks popups
                'SendKeys.SendWait("{ESC}")
                'Thread.Sleep(500)

                Dim desktopPath As String = Environment.GetFolderPath(Environment.SpecialFolder.Desktop)
                Dim saveFilePath As String = "C:\Users\lenovo\Desktop\Invoice_" & invoiceNumber & ".pdf"

                ' === DELETE EXISTING FILE IF EXISTS (to avoid "Replace?" prompt) ===
                If System.IO.File.Exists(saveFilePath) Then
                    System.IO.File.Delete(saveFilePath)
                    Thread.Sleep(300) ' small pause to let file system register deletion
                End If

                SendKeys.SendWait(saveFilePath) ' Type file name
                Thread.Sleep(500)
                SendKeys.SendWait("~") ' Press Enter to save
                Thread.Sleep(3000)

                ' Close QuickBooks popups
                SendKeys.SendWait("{ESC}")
                Thread.Sleep(500)

            Catch ex As Exception
                MessageBox.Show("Error printing invoice: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
            End Try
        End Sub
    End Class

    Private Sub UploadInvoiceToDatabase(transRecId As Integer, companyName As String, invoiceNumber As String, invoiceAmount As String, invoiceDateOf As String, filePath As String)
        Try
            Dim todayDate As String = DateTime.Now.ToString("yyyy-MM-dd HH:mm:ss")
            Dim conn As New MySqlConnection(connString)
            conn.Open()

            ' Fetch company ID using company name
            Dim companyId As Integer = -1
            Dim fetchCompanyQuery As String = "SELECT b2bid FROM loop_warehouse WHERE company_name = @companyName LIMIT 1"
            Dim fetchCompanyCmd As New MySqlCommand(fetchCompanyQuery, conn)
            fetchCompanyCmd.Parameters.AddWithValue("@companyName", companyName)

            Dim reader As MySqlDataReader = fetchCompanyCmd.ExecuteReader()
            If reader.Read() Then
                companyId = Convert.ToInt32(reader("b2bid"))
            End If
            reader.Close()

            ' Ensure companyId is found
            If companyId < 0 Then
                MessageBox.Show("Company ID not found for: " & companyName, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
                conn.Close()
                Exit Sub
            End If

            ' Update transaction record
            Dim updateQuery As String = "UPDATE loop_transaction_buyer SET inv_file = @inv_file, inv_employee = @inv_employee, inv_amount = @inv_amount, total_revenue = @total_revenue, inv_date_of = @inv_date_of, inv_date = @inv_date, inv_entered = 1 WHERE id = @transRecId"

            Dim updateCmd As New MySqlCommand(updateQuery, conn)
            updateCmd.Parameters.AddWithValue("@inv_file", filePath)
            updateCmd.Parameters.AddWithValue("@inv_employee", "MNM")
            updateCmd.Parameters.AddWithValue("@inv_amount", invoiceAmount)
            updateCmd.Parameters.AddWithValue("@total_revenue", invoiceAmount)
            updateCmd.Parameters.AddWithValue("@inv_date_of", invoiceDateOf)
            updateCmd.Parameters.AddWithValue("@inv_date", todayDate)
            updateCmd.Parameters.AddWithValue("@transRecId", transRecId)
            updateCmd.ExecuteNonQuery()

            ' Insert into loop_transaction_buyer_inv_sent
            Dim formattedInvoiceDateOf As String = DateTime.Parse(invoiceDateOf).ToString("yyyy-MM-dd HH:mm:ss")
            Dim insertQuery As String = "INSERT INTO loop_transaction_buyer_inv_sent (trans_rec_id, inv_file, inv_employee, inv_number, inv_amount, inv_date_of, inv_date) " &
                                    "VALUES (@trans_rec_id, @inv_file, @inv_employee, @inv_number, @inv_amount, @inv_date_of, @inv_date)"

            Dim insertCmd As New MySqlCommand(insertQuery, conn)
            insertCmd.Parameters.AddWithValue("@trans_rec_id", transRecId)
            insertCmd.Parameters.AddWithValue("@inv_file", filePath)
            insertCmd.Parameters.AddWithValue("@inv_employee", "MNM")
            insertCmd.Parameters.AddWithValue("@inv_number", invoiceNumber)
            insertCmd.Parameters.AddWithValue("@inv_amount", invoiceAmount)
            insertCmd.Parameters.AddWithValue("@inv_date_of", formattedInvoiceDateOf)
            insertCmd.Parameters.AddWithValue("@inv_date", todayDate)
            insertCmd.ExecuteNonQuery()

            ' Insert into rep_p_and_l_affect_amt_history
            Dim insertHistoryQuery As String = "INSERT INTO rep_p_and_l_affect_amt_history (transaction_buyer_id, company_id, employee_id, entry_date, inv_amount, inv_date, change_value, updation_notes, add_del_upd_flg) " &
                                           "VALUES (@transaction_buyer_id, @company_id, @employee_id, @entry_date, @inv_amount, @inv_date, '', 'Added from ''INVOICE SENT'' table.', 1)"
            Dim insertHistoryCmd As New MySqlCommand(insertHistoryQuery, conn)
            insertHistoryCmd.Parameters.AddWithValue("@transaction_buyer_id", transRecId)
            insertHistoryCmd.Parameters.AddWithValue("@company_id", companyId)
            insertHistoryCmd.Parameters.AddWithValue("@employee_id", 43)
            insertHistoryCmd.Parameters.AddWithValue("@entry_date", todayDate)
            insertHistoryCmd.Parameters.AddWithValue("@inv_amount", invoiceAmount.Replace(",", ""))
            insertHistoryCmd.Parameters.AddWithValue("@inv_date", formattedInvoiceDateOf)
            insertHistoryCmd.ExecuteNonQuery()


            ' =================== Insert into loop_crm (PHP Logic Implementation) ====================
            Dim message As String = "<strong>Note for Transaction # " & transRecId & "</strong>: MNM Uploaded an Invoice: " & filePath
            Dim insertCrmQuery As String = "INSERT INTO loop_crm (warehouse_id, message_date, employee, comm_type, message) " &
                                       "VALUES (@warehouse_id, @message_date, @employee, @comm_type, @message)"
            Dim insertCrmCmd As New MySqlCommand(insertCrmQuery, conn)
            insertCrmCmd.Parameters.AddWithValue("@warehouse_id", companyId)
            insertCrmCmd.Parameters.AddWithValue("@message_date", todayDate)
            insertCrmCmd.Parameters.AddWithValue("@employee", "MNM")
            insertCrmCmd.Parameters.AddWithValue("@comm_type", 5)
            insertCrmCmd.Parameters.AddWithValue("@message", message)
            insertCrmCmd.ExecuteNonQuery()


            'MessageBox.Show("Invoice uploaded and database updated successfully!", "Success", MessageBoxButtons.OK, MessageBoxIcon.Information)

            SendInvoiceEmail(transRecId, invoiceNumber, invoiceAmount, companyId)
            'MessageBox.Show("email10", "Success", MessageBoxButtons.OK, MessageBoxIcon.Information)

            conn.Close()
        Catch ex As Exception
            MessageBox.Show("Database Update Error: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Sub


    Private Sub SendInvoiceEmail(transRecId As Integer, invoiceNumber As String, invoiceAmount As Decimal, companyId As Integer)
        Try
            Dim conn As New MySqlConnection(connString)
            conn.Open()

            ' Query to get invoice details
            Dim sql1 As String = "SELECT * FROM loop_invoice_details WHERE trans_rec_id = @transRecId"
            Dim cmd As New MySqlCommand(sql1, conn)
            cmd.Parameters.AddWithValue("@transRecId", transRecId)
            Dim reader1 As MySqlDataReader = cmd.ExecuteReader()

            Dim invoiceDate As String = ""
            Dim creditTerms As String = ""
            Dim bookkeeper As String = ""
            Dim po_ponumber As String = ""

            If reader1.Read() Then
                invoiceDate = DateTime.Parse(reader1("timestamp").ToString()).ToString("MM/dd/yyyy")
                creditTerms = reader1("terms").ToString()
                bookkeeper = reader1("bookkeeper").ToString()
                po_ponumber = reader1("po").ToString() ' Fetch PO number
            End If
            reader1.Close()

            ' Fetch warehouse_id and company_id based on invoiceNumber
            Dim warehouse_id As String = ""

            Dim sql2 As String = "SELECT warehouse_id FROM loop_transaction_buyer WHERE inv_number = @invoiceNumber"
            Dim cmd2 As New MySqlCommand(sql2, conn)
            cmd2.Parameters.AddWithValue("@invoiceNumber", invoiceNumber)
            Dim reader2 As MySqlDataReader = cmd2.ExecuteReader()

            If reader2.Read() Then
                warehouse_id = reader2("warehouse_id").ToString()

            End If
            reader2.Close()

            ' Build Email Body
            Dim emailBody As String = "<html style=""width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';padding:0;Margin:0;"">"
            emailBody &= "<head><link rel='preconnect' href='https://fonts.gstatic.com'>"
            emailBody &= "<link href='https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600&display=swap' rel='stylesheet'>"
            emailBody &= "<style>@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600&display=swap');</style>"
            emailBody &= "<style scoped>.tablestyle { width:800px; } table.ordertbl tr td{ padding:4px; }"
            emailBody &= "@media only screen and (max-width: 768px) { .tablestyle { width:98%; } }</style>"
            emailBody &= "</head><body style=""width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';padding:0;Margin:0;"">"

            emailBody &= "<div style='padding:20px;' align='center'>"
            emailBody &= "<table border='0' cellspacing='0' cellpadding='0' width='100%' style='width:100%;border-collapse:collapse;max-width:100%'><tbody>"

            ' Order Details
            emailBody &= "<tr><td style=""width:100%;font-size:16pt;color:#a6a6a6;""><br>"
            emailBody &= "<span style=""font-size:12pt;color:#a6a6a6;"">ORDER # " & transRecId & " (PO " & po_ponumber & ")</span></td></tr>"

            ' Company Name with Link
            emailBody &= "<tr><td><br><div style=""width:100%;font-size:16pt;color:#000000;"">"
            emailBody &= "<a href='https://www.ucbloops.com/loops/viewCompany.php?ID=" & companyId & "&show=transactions&warehouse_id=" & warehouse_id & "&rec_type=Supplier&proc=View&searchcrit=&id=" & warehouse_id & "&rec_id=" & transRecId & "&display=buyer_payment' target='_blank' style='color:#007bff;text-decoration:underline;'>View Company Details</a>"

            'emailBody &= "<a href='viewCompany.php?ID=" & companyId & "&show=transactions&warehouse_id=" & warehouse_id & "&rec_type=Supplier&proc=View&searchcrit=&id=" & warehouse_id & "&rec_id=" & transRecId & "&display=buyer_payment'>"
            'emailBody &= "View Company Details</a></div></td></tr>"

            ' Invoice Uploaded Notification
            emailBody &= "<tr><td><br><div style=""width:100%;font-size:19pt;color:#000000;"">QuickBooks invoice uploaded</div></td></tr>"

            ' Message Body
            emailBody &= "<tr><td><br><div style=""font-size:12pt;color:#767171; margin-top:3px; margin-bottom:3px;"">"
            emailBody &= "UCB Accounts Receivable Team, the matching invoice from QuickBooks has been uploaded.</div></td></tr>"

            ' Invoice Details
            emailBody &= "<tr><td><br><span style=""font-size:17pt;color:#3b3838;"">Invoice details</span><br></td></tr>"

            emailBody &= "<tr><td><div style=""font-size:13pt;color:#808080;""><strong>Invoice #:</strong> " & invoiceNumber & "</div></td></tr>"
            emailBody &= "<tr><td><div style=""font-size:13pt;color:#808080;""><strong>Invoice Amount:</strong> $" & invoiceAmount.ToString("F2") & "</div></td></tr>"
            emailBody &= "<tr><td><div style=""font-size:13pt;color:#808080;""><strong>Invoice Date:</strong> " & invoiceDate & "</div></td></tr>"
            emailBody &= "<tr><td><div style=""font-size:13pt;color:#808080;""><strong>Credit Terms:</strong> " & creditTerms & "</div></td></tr>"
            emailBody &= "<tr><td><div style=""font-size:13pt;color:#808080;""><strong>Bookkeeper Notes:</strong> " & bookkeeper & "</div></td></tr>"

            emailBody &= "</tbody></table></div></body></html>"

            ' Email Details
            Dim fromEmail As String = "accounting@usedcardboardboxes.com"
            Dim toEmail As String = "bk@mooneem.com"
            Dim subject As String = "QuickBooks Invoice " & invoiceNumber & " Uploaded for Order #" & transRecId

            ' Send Email
            Dim emailSuccess As Boolean = False
            SendEmail(toEmail, subject, emailBody, fromEmail, emailSuccess)

            ' Show Success or Failure Message
            If emailSuccess Then
                ' MessageBox.Show("Invoice uploaded and email sent successfully!", "Success", MessageBoxButtons.OK, MessageBoxIcon.Information)
            Else
                MessageBox.Show("Invoice uploaded, but email sending failed!", "Warning", MessageBoxButtons.OK, MessageBoxIcon.Warning)
            End If

            conn.Close()
        Catch ex As Exception
            MessageBox.Show("Error: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Sub


    Private Sub SendEmail(toEmail As String, subject As String, body As String, fromEmail As String, ByRef emailSuccess As Boolean)
        Try
            ' Create mail object

            Dim mail As New MailMessage()
            mail.From = New MailAddress(fromEmail)
            mail.To.Add(toEmail)
            mail.Subject = subject
            mail.Body = body
            mail.IsBodyHtml = True ' Enable HTML format

            ' Configure SMTP
            Dim smtp As New SmtpClient("smtp.office365.com")
            smtp.Port = 587
            smtp.EnableSsl = True
            smtp.UseDefaultCredentials = False

            ' Get SMTP Credentials
            Dim credentials As NetworkCredential = Nothing
            GetSmtpCredentials(fromEmail, credentials)
            smtp.Credentials = credentials

            ' Open connection and send email
            smtp.Send(mail)

            emailSuccess = True ' Email sent successfully
        Catch ex As Exception
            MessageBox.Show("Email Error: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
            emailSuccess = False ' Email sending failed
        End Try
    End Sub

    Private Sub GetSmtpCredentials(fromEmail As String, ByRef credentials As NetworkCredential)
        If fromEmail = "UCBZWEmail@UCBZeroWaste.com" Then
            credentials = New NetworkCredential("UCBZWEmail@UCBZeroWaste.com", "#UCBgrn4652")
        Else
            credentials = New NetworkCredential("ucbemail@usedcardboardboxes.com", "WH@ToGap$222")
        End If
    End Sub


    'Private Sub TruncateString(ByRef input As String, ByVal maxLength As Integer)
    '    If Not String.IsNullOrEmpty(input) AndAlso input.Length > maxLength Then
    '        input = input.Substring(0, maxLength)
    '    End If
    'End Sub


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

    'Private Function GetItemDetailsFromDatabase(transRecID As Integer) As List(Of ItemDetail)
    '    Dim itemDetails As New List(Of ItemDetail)()
    '    Try
    '        Using conn As New MySqlConnection(connString)
    '            conn.Open()

    '            Dim query As String = "SELECT quantity, description, price, loop_box_id, division_id, total FROM loop_invoice_items WHERE trans_rec_id = @TransRecID"
    '            Using cmd As New MySqlCommand(query, conn)
    '                cmd.Parameters.AddWithValue("@TransRecID", transRecID)
    '                Using reader As MySqlDataReader = cmd.ExecuteReader()
    '                    While reader.Read()
    '                        Dim price As Decimal = 0D
    '                        If Not IsDBNull(reader("price")) Then
    '                            price = Math.Abs(Convert.ToDecimal(reader("price")))
    '                        End If

    '                        Dim loopBoxID As Integer = If(IsDBNull(reader("loop_box_id")), 0, Convert.ToInt32(reader("loop_box_id")))
    '                        Dim divisionID As Integer = If(IsDBNull(reader("division_id")), 0, Convert.ToInt32(reader("division_id")))

    '                        Dim btype As String = ""
    '                        Dim classRef As String = MapClassRef(GetDivisionName(divisionID))

    '                        If loopBoxID > 0 Then
    '                            btype = GetBtypeFromInventory(loopBoxID)
    '                        End If

    '                        If btype = "Pallets" AndAlso classRef = "B2B:Brokerage" Then
    '                            classRef = "Pallets"
    '                        End If

    '                        If Not String.IsNullOrWhiteSpace(btype) Then
    '                            Dim item As New ItemDetail() With {
    '                            .Quantity = Convert.ToInt32(reader("quantity")),
    '                            .ItemCode = btype,
    '                            .Description = reader("description").ToString(),
    '                            .Priceeach = price,
    '                            .Amount = Convert.ToDecimal(reader("total")),
    '                            .ClassRef = classRef
    '                        }

    '                            ' ✅ Debug here
    '                            MessageBox.Show($"Fetched Item:" & Environment.NewLine &
    '                $"ItemCode: {item.ItemCode}" & Environment.NewLine &
    '                $"Description: {item.Description}" & Environment.NewLine &
    '                $"Quantity: {item.Quantity}" & Environment.NewLine &
    '                $"PriceEach: {item.Priceeach}" & Environment.NewLine &
    '                $"Amount: {item.Amount}" & Environment.NewLine &
    '                $"ClassRef: {item.ClassRef}",
    '                "Debug - Item Fetched",
    '                MessageBoxButtons.OK, MessageBoxIcon.Information)
    '                            itemDetails.Add(item)
    '                        End If
    '                    End While
    '                End Using
    '            End Using
    '        End Using

    '        ' ✅ If no valid items were added, insert a fallback item
    '        'If itemDetails.Count = 0 Then
    '        '    itemDetails.Add(New ItemDetail With {
    '        '    .ItemCode = "UCBZeroWaste", ' Must exist in QuickBooks
    '        '    .Description = "UCBZeroWaste",
    '        '    .Quantity = 1,
    '        '    .Priceeach = 100,
    '        '    .Amount = 100,
    '        '    .ClassRef = "Zero Waste"
    '        '})
    '        'End If
    '    Catch ex As Exception
    '        MessageBox.Show("Error retrieving item details: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
    '    End Try

    '    Return itemDetails
    'End Function







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
                MessageBox.Show("Template name is required to create an invoice.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Warning)
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

                        ' Fetch data safely
                        Dim terms As String = If(GetTermsFromDatabase(buyerID), "")
                        Dim po As String = If(GetPONumberFromDatabase(buyerID), "")
                        Dim shipDate As Date = Date.MinValue
                        Dim shipDateRaw As Object = GetshipDateFromDatabase(buyerID)
                        If shipDateRaw IsNot Nothing AndAlso IsDate(shipDateRaw) Then shipDate = Convert.ToDateTime(shipDateRaw)

                        Dim invoiceNumber As String = If(GetInvoiceNumber(buyerID), "")
                        Dim shipmethodvia As String = If(Getshipmethodvia(buyerID), "")
                        Dim itemDetails As List(Of ItemDetail) = If(GetItemDetailsFromDatabase(buyerID), New List(Of ItemDetail))

                        ' ✅ Debug: Show how many items came back
                        '        MessageBox.Show($"TransactionID: {buyerID}" & Environment.NewLine &
                        '$"Fetched {itemDetails.Count} item(s) from DB",
                        '"Debug - ItemDetails Count", MessageBoxButtons.OK, MessageBoxIcon.Information)

                        ' ✅ Debug: Show each item detail
                        '    For Each itm As ItemDetail In itemDetails
                        '        MessageBox.Show($"Item from DB:" & Environment.NewLine &
                        '$"ItemCode: {itm.ItemCode}" & Environment.NewLine &
                        '$"Description: {itm.Description}" & Environment.NewLine &
                        '$"Quantity: {itm.Quantity}" & Environment.NewLine &
                        '$"PriceEach: {itm.Priceeach}" & Environment.NewLine &
                        '$"Amount: {itm.Amount}" & Environment.NewLine &
                        '$"ClassRef: {itm.ClassRef}",
                        '"Debug - ItemDetails", MessageBoxButtons.OK, MessageBoxIcon.Information)
                        '    Next


                        Dim invoiceDate As Date = Date.MinValue
                        Dim invoiceDateRaw As Object = GetInvoiceDate(buyerID)
                        If invoiceDateRaw IsNot Nothing AndAlso IsDate(invoiceDateRaw) Then invoiceDate = Convert.ToDateTime(invoiceDateRaw)

                        Dim b2bid As Integer = GetB2BIDFromWarehouse(QBcompanyName)
                        Dim memo As String = ""

                        ' Validate before invoice creation
                        If Not CustomerExistsInQB(QBcompanyName, sessionManager) Then
                            failedInvoices.Add($"{QBcompanyName} - Customer not found in QuickBooks.")
                            Continue For
                        End If

                        If String.IsNullOrWhiteSpace(invoiceNumber) Then
                            failedInvoices.Add($"{QBcompanyName} - Invoice Number missing.")
                            Continue For
                        End If

                        If InvoiceAlreadyExists(invoiceNumber, sessionManager) Then
                            failedInvoices.Add($"{QBcompanyName} - Invoice {invoiceNumber} already exists in QuickBooks.")
                            Continue For
                        End If

                        ' Sales rep validation
                        Dim repValue As String = GetRep(buyerID)
                        If Not String.IsNullOrEmpty(repValue) AndAlso Not SalesRepExistsInQB(repValue, sessionManager) Then
                            failedInvoices.Add($"{QBcompanyName} - Sales Rep '{repValue}' not found in QuickBooks.")
                            Continue For
                        End If

                        ' Class ref validation
                        Dim invalidClassRef As Boolean = False
                        For Each item As ItemDetail In itemDetails
                            If Not String.IsNullOrEmpty(item.ClassRef) AndAlso Not ClassRefExistsInQB(item.ClassRef, sessionManager) Then
                                failedInvoices.Add($"{QBcompanyName} - Class '{item.ClassRef}' not found in QuickBooks.")
                                invalidClassRef = True
                                Exit For
                            End If
                        Next
                        If invalidClassRef Then Continue For

                        ' ✅ Create Invoice
                        Dim success As Boolean = CreateInvoice(sessionManager, msgSetRequest,
                                       buyerID, b2bid, invoiceDate, shipDate, itemDetails,
                                       companyName, QBcompanyName, memo, po, terms,
                                       templateName, invoiceNumber, shipmethodvia)


                        ' Dim success As Boolean = CreateInvoice(buyerID, b2bid, invoiceDate, shipDate, itemDetails, companyName, QBcompanyName, memo, po, terms, templateName, invoiceNumber, shipmethodvia)
                        'If success Then
                        '    createdInvoices.Add(QBcompanyName)
                        'Else
                        '    failedInvoices.Add($"{QBcompanyName} - Failed to create invoice due to QuickBooks/API error.")
                        'End If
                        If success Then
                            createdInvoices.Add($"Invoice#: {invoiceNumber}")
                        Else
                            failedInvoices.Add($"{QBcompanyName} - Failed to create invoice due to QuickBooks/API error.")
                        End If

                    Catch exRow As Exception
                        failedInvoices.Add($"Row {row.Index + 1}: Error processing record - {exRow.Message}")
                    End Try
                End If
            Next

            ' Summary
            'Dim message As String = $"{createdInvoices.Count} invoice(s) created successfully." & vbCrLf
            'If failedInvoices.Count > 0 Then
            '    message &= $"{failedInvoices.Count} invoice(s) failed:" & vbCrLf & String.Join(vbCrLf, failedInvoices)
            'End If
            'MessageBox.Show(message, "Invoice Processing Summary", MessageBoxButtons.OK, MessageBoxIcon.Information)
            ' Summary
            Dim message As String = $"{createdInvoices.Count} invoice(s) created successfully." & vbCrLf
            If createdInvoices.Count > 0 Then
                message &= "Created Invoices:" & vbCrLf & String.Join(vbCrLf, createdInvoices) & vbCrLf
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


    Private Function CreateInvoice(sessionManager As QBSessionManager,
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
                               Optional invoiceNumber As String = "",
                               Optional shipmethodvia As String = "") As Boolean

        Try

            If sessionManager Is Nothing Then
                MessageBox.Show("QuickBooks sessionManager was not passed in.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
                Return False
            End If

            If msgSetRequest Is Nothing Then
                MessageBox.Show("QuickBooks msgSetRequest was not passed in.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
                Return False
            End If

            ' 🔒 Check: Company name required
            If String.IsNullOrWhiteSpace(QBcompanyName) Then
                MessageBox.Show("Invoice not created: Company Name is missing.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Warning)
                Return False
            End If

            ' 🔒 Check: msgSetRequest is initialized
            If msgSetRequest Is Nothing Then
                MessageBox.Show("QuickBooks request object (msgSetRequest) is not initialized.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
                Return False
            End If

            msgSetRequest.Attributes.OnError = ENRqOnError.roeContinue

            Dim invoiceAdd As IInvoiceAdd = msgSetRequest.AppendInvoiceAddRq()
            If invoiceAdd Is Nothing Then
                MessageBox.Show("Failed to create InvoiceAdd request (invoiceAdd is Nothing).", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
                Return False
            End If

            ' 🔒 Truncate input values
            QBcompanyName = TruncateString(QBcompanyName, 50)
            memo = TruncateString(memo, 4095)
            po = TruncateString(po, 25)

            ' 🔒 Populate invoice header
            If invoiceAdd.CustomerRef IsNot Nothing Then invoiceAdd.CustomerRef.FullName.SetValue(QBcompanyName)
            If invoiceDate <> Date.MinValue Then invoiceAdd.TxnDate.SetValue(invoiceDate)
            If shipDate <> Date.MinValue Then invoiceAdd.ShipDate.SetValue(shipDate)
            If Not String.IsNullOrWhiteSpace(terms) AndAlso invoiceAdd.TermsRef IsNot Nothing Then invoiceAdd.TermsRef.FullName.SetValue(terms)
            If Not String.IsNullOrWhiteSpace(memo) Then invoiceAdd.Memo.SetValue(memo)
            If Not String.IsNullOrWhiteSpace(po) Then invoiceAdd.PONumber.SetValue(po)
            If Not String.IsNullOrWhiteSpace(templateName) AndAlso invoiceAdd.TemplateRef IsNot Nothing Then invoiceAdd.TemplateRef.FullName.SetValue(templateName)
            If Not String.IsNullOrWhiteSpace(shipmethodvia) AndAlso invoiceAdd.ShipMethodRef IsNot Nothing Then invoiceAdd.ShipMethodRef.FullName.SetValue(shipmethodvia)

            Dim repValue As String = GetRep(buyerID)
            If Not String.IsNullOrEmpty(repValue) AndAlso invoiceAdd.SalesRepRef IsNot Nothing Then invoiceAdd.SalesRepRef.FullName.SetValue(repValue)

            invoiceAdd.Other.SetValue(buyerID)

            ' 🔒 Address blocks (safe assign)
            Dim shipAddrBlock = invoiceAdd.ShipAddress
            Dim billToAddrBlock = invoiceAdd.BillAddress

            Using conn2 As New MySqlConnection(B2BconnString)
                conn2.Open()

                ' Shipping address
                Dim query As String = "SELECT shipAddress, shipAddress2, shipCity, shipState, shipZip, shipPhone FROM companyInfo WHERE ID = @B2BID"
                Using cmd As New MySqlCommand(query, conn2)
                    cmd.Parameters.AddWithValue("@B2BID", B2BID)
                    Using reader As MySqlDataReader = cmd.ExecuteReader()
                        If reader.Read() AndAlso shipAddrBlock IsNot Nothing Then
                            If Not String.IsNullOrWhiteSpace(companyName) Then shipAddrBlock.Addr1.SetValue(companyName)
                            If Not String.IsNullOrWhiteSpace(reader("shipAddress").ToString()) Then shipAddrBlock.Addr2.SetValue(reader("shipAddress").ToString())
                            If Not String.IsNullOrWhiteSpace(reader("shipAddress2").ToString()) Then shipAddrBlock.Addr3.SetValue(reader("shipAddress2").ToString())
                            If Not String.IsNullOrWhiteSpace(reader("shipCity").ToString()) Then shipAddrBlock.City.SetValue(reader("shipCity").ToString())
                            If Not String.IsNullOrWhiteSpace(reader("shipState").ToString()) Then shipAddrBlock.State.SetValue(reader("shipState").ToString())
                            If Not String.IsNullOrWhiteSpace(reader("shipZip").ToString()) Then shipAddrBlock.PostalCode.SetValue(reader("shipZip").ToString())
                            If Not String.IsNullOrWhiteSpace(reader("shipPhone").ToString()) Then shipAddrBlock.Note.SetValue("Ph.# " & reader("shipPhone").ToString())
                        End If
                    End Using
                End Using

                ' Billing address
                Dim addressQuery As String = "SELECT address, address2, city, state, zipcode, mainphone FROM b2bbillto WHERE companyid = @B2BID"
                Using cmd As New MySqlCommand(addressQuery, conn2)
                    cmd.Parameters.AddWithValue("@B2BID", B2BID)
                    Using reader As MySqlDataReader = cmd.ExecuteReader()
                        If reader.Read() AndAlso billToAddrBlock IsNot Nothing Then
                            If Not String.IsNullOrWhiteSpace(companyName) Then billToAddrBlock.Addr1.SetValue(companyName)
                            If Not String.IsNullOrWhiteSpace(reader("address").ToString()) Then billToAddrBlock.Addr2.SetValue(reader("address").ToString())
                            If Not String.IsNullOrWhiteSpace(reader("address2").ToString()) Then billToAddrBlock.Addr3.SetValue(reader("address2").ToString())
                            If Not String.IsNullOrWhiteSpace(reader("city").ToString()) Then billToAddrBlock.City.SetValue(reader("city").ToString())
                            If Not String.IsNullOrWhiteSpace(reader("state").ToString()) Then billToAddrBlock.State.SetValue(reader("state").ToString())
                            If Not String.IsNullOrWhiteSpace(reader("zipcode").ToString()) Then billToAddrBlock.PostalCode.SetValue(reader("zipcode").ToString())
                            If Not String.IsNullOrWhiteSpace(reader("mainphone").ToString()) Then billToAddrBlock.Note.SetValue("Ph.# " & reader("mainphone").ToString())
                        End If
                    End Using
                End Using
            End Using

            If Not String.IsNullOrEmpty(invoiceNumber) Then invoiceAdd.RefNumber.SetValue(invoiceNumber)

            ' 🔒 Line items
            If itemDetails IsNot Nothing AndAlso itemDetails.Count > 0 Then
                For Each item In itemDetails
                    Dim invoiceLine = invoiceAdd.ORInvoiceLineAddList.Append()
                    If invoiceLine Is Nothing OrElse invoiceLine.InvoiceLineAdd Is Nothing Then
                        MessageBox.Show("Error: Failed to create invoice line item object.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
                        Continue For
                    End If

                    If Not String.IsNullOrWhiteSpace(item.ItemCode) Then invoiceLine.InvoiceLineAdd.ItemRef.FullName.SetValue(item.ItemCode)
                    If item.Quantity > 0 Then invoiceLine.InvoiceLineAdd.Quantity.SetValue(item.Quantity)
                    If item.Amount > 0 Then invoiceLine.InvoiceLineAdd.Amount.SetValue(item.Amount)
                    If Not String.IsNullOrWhiteSpace(item.Description) Then invoiceLine.InvoiceLineAdd.Desc.SetValue(item.Description)
                    If Not String.IsNullOrWhiteSpace(item.ClassRef) Then invoiceLine.InvoiceLineAdd.ClassRef.FullName.SetValue(item.ClassRef)

                    invoiceLine.InvoiceLineAdd.SalesTaxCodeRef.FullName.SetValue("NON")
                Next
            End If

            ' 🔒 Send request to QB
            If sessionManager Is Nothing Then
                MessageBox.Show("QuickBooks sessionManager is not initialized.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
                Return False
            End If

            Dim response = sessionManager.DoRequests(msgSetRequest)
            If response Is Nothing OrElse response.ResponseList Is Nothing OrElse response.ResponseList.Count = 0 Then
                MessageBox.Show("No response received from QuickBooks.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
                msgSetRequest.ClearRequests()
                Return False
            End If

            Dim invoiceResponse = response.ResponseList.GetAt(0)
            If invoiceResponse IsNot Nothing AndAlso invoiceResponse.StatusCode = 0 Then
                ' ✅ Success - update DB
                Using conn2 As New MySqlConnection(connString)
                    conn2.Open()
                    Dim query_upd As String = "UPDATE loop_transaction_buyer SET qb_import_done_flg = 1, qb_import_done_date_time = NOW() WHERE id = @buyerID"
                    Using cmd_upd As New MySqlCommand(query_upd, conn2)
                        cmd_upd.Parameters.AddWithValue("@buyerID", buyerID)
                        cmd_upd.ExecuteNonQuery()
                    End Using
                End Using

                msgSetRequest.ClearRequests()
                Return True
            Else
                Dim errMsg As String = If(invoiceResponse IsNot Nothing, invoiceResponse.StatusMessage, "Unknown QuickBooks error")
                MessageBox.Show("Error creating invoice: " & errMsg, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
                msgSetRequest.ClearRequests()
                Return False
            End If

        Catch ex As Exception
            MessageBox.Show("Error while creating invoice: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
            If msgSetRequest IsNot Nothing Then msgSetRequest.ClearRequests()
            Return False
        End Try
    End Function


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


    'Private Sub SubmitSelectedRows(sender As Object, e As EventArgs)
    '    Dim createdInvoices As New List(Of String) ' To store successfully created invoices
    '    Dim failedInvoices As New List(Of String) ' To store failed invoices
    '    'Dim processingForm As New ProcessingInvoice()

    '    Dim sessionManager As QBSessionManager = Nothing
    '    Dim started As Boolean = False
    '    Try
    '        ' Prompt for template name

    '        'Dim templateName As String = InputBox("Enter the invoice template name (e.g., Intuit Product Invoice withlogo):", "Template Selection", "Intuit Product Invoice withlogo")
    '        'If String.IsNullOrEmpty(templateName) Then
    '        '    MessageBox.Show("Template name is required to create an invoice.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Warning)
    '        '    Exit Sub
    '        'End If
    '        Dim templateName As String = InputBox("Enter the invoice template name (e.g., Intuit Product Invoice withlogo):", "Template Selection", "Intuit Product Invoice withlogo")
    '        If String.IsNullOrEmpty(templateName) Then
    '            MessageBox.Show("Template name is required to create an invoice.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Warning)
    '            Exit Sub
    '        End If

    '        'processingForm.Show()
    '        'processingForm.Refresh()
    '        ' Initialize QuickBooks session manager
    '        sessionManager = New QBSessionManager()
    '        sessionManager.OpenConnection("", "Mooneem App") ' Open connection to QuickBooks
    '        sessionManager.BeginSession("", ENOpenMode.omDontCare) ' Begin session in single-user mode
    '        started = True
    '        ' Create a new message set request
    '        msgSetRequest = sessionManager.CreateMsgSetRequest("US", 12, 0) ' US = Country, QB Version = 4.0 (QuickBooks 2013 Pro)
    '        msgSetRequest.Attributes.OnError = ENRqOnError.roeContinue

    '        ' Loop through selected rows in the DataGridView
    '        For Each row As DataGridViewRow In dataview.Rows
    '            If Convert.ToBoolean(row.Cells("Select").Value) Then
    '                Try
    '                    Dim companyName As String = row.Cells("CompanyName").Value.ToString()
    '                    Dim QBcompanyName As String = row.Cells("QuickBooksCompanyName").Value.ToString()
    '                    Dim buyerID As Integer = Convert.ToInt32(row.Cells("TransactionID").Value) ' Get the selected BuyerID
    '                    'MessageBox.Show($"Processing: {companyName} (BuyerID: {buyerID})")
    '                    ' Fetch data from the database
    '                    Dim terms As String = GetTermsFromDatabase(buyerID)
    '                    Dim po As String = GetPONumberFromDatabase(buyerID)
    '                    Dim shipDate As String = GetshipDateFromDatabase(buyerID)
    '                    'Dim shipDate As String = "2025-08-05"

    '                    Dim invoiceNumber As String = GetInvoiceNumber(buyerID)
    '                    Dim shipmethodvia As String = Getshipmethodvia(buyerID)
    '                    ' Retrieve item details
    '                    Dim itemDetails As List(Of ItemDetail) = GetItemDetailsFromDatabase(buyerID)

    '                    Dim invoiceDate As String = GetInvoiceDate(buyerID)
    '                    Dim b2bid As Integer = GetB2BIDFromWarehouse(QBcompanyName)

    '                    'Dim shipAddress As String = GetShipAddressFromCompanyInfo(b2bid)

    '                    'shipAddress = shipAddress.Replace(",", "").Replace(".", "")
    '                    'Dim billToAddress As String = GetCompanyAndBillToAddress(b2bid)

    '                    Dim memo As String = "" '$"Invoice generated via Mooneem App using template {templateName}" ' Memo with template name , ,


    '                    If Not String.IsNullOrWhiteSpace(QBcompanyName) Then
    '                        If CustomerExistsInQB(QBcompanyName, sessionManager) Then
    '                            If Not String.IsNullOrWhiteSpace(invoiceNumber) Then
    '                                If Not InvoiceAlreadyExists(invoiceNumber, sessionManager) Then
    '                                    'CreateInvoice(buyerID, b2bid, invoiceDate, shipDate, itemDetails, companyName, QBcompanyName, memo, po, terms, templateName, invoiceNumber, shipmethodvia)
    '                                    'createdInvoices.Add(QBcompanyName)
    '                                    ' Validate class refs before creating invoice
    '                                    ' 🔍 Validate all class refs in the item list before proceeding

    '                                    Dim repValue As String = GetRep(buyerID)
    '                                    If Not SalesRepExistsInQB(repValue, sessionManager) Then
    '                                        failedInvoices.Add($"{QBcompanyName} - Sales Rep '{repValue}' not found in QuickBooks.")
    '                                        Continue For
    '                                    End If

    '                                    Dim invalidClassRef As Boolean = False
    '                                    For Each item As ItemDetail In itemDetails
    '                                        If Not ClassRefExistsInQB(item.ClassRef, sessionManager) Then
    '                                            failedInvoices.Add($"{QBcompanyName} - Class '{item.ClassRef}' not found in QuickBooks.")
    '                                            invalidClassRef = True
    '                                            Exit For
    '                                        End If
    '                                    Next

    '                                    'If Not invalidClassRef Then
    '                                    '    ' ✅ All class refs are valid, proceed to create the invoice
    '                                    '    CreateInvoice(buyerID, b2bid, invoiceDate, shipDate, itemDetails, companyName, QBcompanyName, memo, po, terms, templateName, invoiceNumber, shipmethodvia)
    '                                    '    createdInvoices.Add(QBcompanyName)
    '                                    'End If
    '                                    If Not invalidClassRef Then
    '                                        ' ✅ All class refs are valid, proceed to create the invoice
    '                                        Dim success As Boolean = CreateInvoice(buyerID, b2bid, invoiceDate, shipDate, itemDetails, companyName, QBcompanyName, memo, po, terms, templateName, invoiceNumber, shipmethodvia)
    '                                        If success Then
    '                                            createdInvoices.Add(QBcompanyName)
    '                                        Else
    '                                            failedInvoices.Add($"{QBcompanyName} - Failed to create invoice due to QuickBooks/API error.")
    '                                        End If
    '                                    End If

    '                                Else
    '                                    failedInvoices.Add($"{QBcompanyName} - Invoice {invoiceNumber} already exists in QuickBooks.")
    '                                End If
    '                            Else
    '                                'CreateInvoice(buyerID, b2bid, invoiceDate, shipDate, itemDetails, companyName, QBcompanyName, memo, po, terms, templateName, "", shipmethodvia)
    '                                'createdInvoices.Add(QBcompanyName)
    '                            End If
    '                        Else
    '                            failedInvoices.Add($"{QBcompanyName} - Customer not found in QuickBooks.")
    '                        End If
    '                    Else
    '                        MsgBox("Invoice not created because QB Company Name is null or empty.", MsgBoxStyle.Exclamation, "Invoice Creation Failed")
    '                    End If
    '                    'MessageBox.Show($"Invoice created successfully for {companyName} using template {templateName}", "Invoice Created", MessageBoxButtons.OK, MessageBoxIcon.Information)
    '                    'Else
    '                    'failedInvoices.Add($"Customer not found: {companyName}") ' Add to failure list
    '                    'MessageBox.Show($"Customer not found in QuickBooks for {companyName}", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
    '                    'End If
    '                Catch exRow As Exception
    '                    MessageBox.Show($"Error processing record for {row.Cells("CompanyName").Value}: {exRow.Message}", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
    '                End Try
    '            End If
    '        Next
    '        Dim message As String = $"{createdInvoices.Count} invoice(s) created successfully." & vbCrLf
    '        If failedInvoices.Count > 0 Then
    '            message &= $"{failedInvoices.Count} invoice(s) failed:" & vbCrLf & String.Join(vbCrLf, failedInvoices)
    '        End If
    '        MessageBox.Show(message, "Invoice Processing Summary", MessageBoxButtons.OK, MessageBoxIcon.Information)
    '        'LoadData() ' Reload your grid
    '        'LoadUploadedData()
    '        'BeginInvoke(Sub()
    '        '                ShowStatus("🔄 Please wait data loading...")
    '        '                ValidateAndHighlightRows()

    '        '            End Sub)
    '        'Catch ex As Exception
    '        '    MessageBox.Show("Error processing invoices: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
    '        'Finally
    '        '    ' End QuickBooks session and close connection
    '        '    ' processingForm.Close()
    '        '    'sessionManager.EndSession()
    '        '    'sessionManager.CloseConnection()
    '        'End Try
    '    Catch ex As Exception
    '        MessageBox.Show("Error processing invoices: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
    '    Finally
    '        Try
    '            If started Then sessionManager.EndSession()
    '            If sessionManager IsNot Nothing Then sessionManager.CloseConnection()
    '        Catch
    '        End Try
    '    End Try
    'End Sub


    'Private Function CreateInvoice(buyerID As Int32, B2BID As Int32, invoiceDate As Date, shipDate As Date, itemDetails As List(Of ItemDetail), companyName As String, QBcompanyName As String, memo As String, po As String, terms As String, templateName As String, Optional invoiceNumber As String = "", Optional shipmethodvia As String = "") As Boolean
    '    Try
    '        ' Ensure company name is provided
    '        If String.IsNullOrWhiteSpace(QBcompanyName) Then
    '            MsgBox("Invoice not created: Company Name is missing.", MessageBoxButtons.OK, MessageBoxIcon.Warning)
    '            Return False
    '        End If

    '        ' Initialize the invoice add request  
    '        msgSetRequest.Attributes.OnError = ENRqOnError.roeContinue
    '        Dim invoiceAdd = msgSetRequest.AppendInvoiceAddRq()
    '        Dim repValue As String = GetRep(buyerID)
    '        Dim maxLength As Integer = 50
    '        If QBcompanyName.Length > maxLength Then Return False

    '        TruncateString(QBcompanyName, 50)
    '        TruncateString(memo, 4095)
    '        TruncateString(po, 25)
    '        'invoiceAdd.PrintInvoice.SetValue(False)
    '        'invoiceAdd.Email.SetValue(False)

    '        invoiceAdd.CustomerRef.FullName.SetValue(QBcompanyName)
    '        If invoiceDate <> Date.MinValue Then invoiceAdd.TxnDate.SetValue(invoiceDate)
    '        If shipDate <> Date.MinValue Then invoiceAdd.ShipDate.SetValue(shipDate)
    '        If Not String.IsNullOrWhiteSpace(terms) Then invoiceAdd.TermsRef.FullName.SetValue(terms)
    '        If Not String.IsNullOrWhiteSpace(memo) Then invoiceAdd.Memo.SetValue(memo)
    '        If Not String.IsNullOrWhiteSpace(po) Then invoiceAdd.PONumber.SetValue(po)
    '        If Not String.IsNullOrWhiteSpace(templateName) Then invoiceAdd.TemplateRef.FullName.SetValue(templateName)
    '        If Not String.IsNullOrWhiteSpace(shipmethodvia) Then invoiceAdd.ShipMethodRef.FullName.SetValue(shipmethodvia)
    '        If Not String.IsNullOrEmpty(repValue) Then invoiceAdd.SalesRepRef.FullName.SetValue(repValue)
    '        invoiceAdd.Other.SetValue(buyerID)

    '        Dim shipAddrBlock = invoiceAdd.ShipAddress
    '        Dim billToAddrBlock = invoiceAdd.BillAddress

    '        ' ── Fetch Ship & Bill Address ──
    '        Using conn2 As New MySqlConnection(B2BconnString)
    '            conn2.Open()

    '            ' Shipping address
    '            Dim query As String = "SELECT shipAddress, shipAddress2, shipCity, shipState, shipZip, shipPhone FROM companyInfo WHERE ID = @B2BID"
    '            Using cmd As New MySqlCommand(query, conn2)
    '                cmd.Parameters.AddWithValue("@B2BID", B2BID)
    '                Using reader As MySqlDataReader = cmd.ExecuteReader()
    '                    If reader.Read() Then
    '                        If Not String.IsNullOrWhiteSpace(companyName) Then shipAddrBlock.Addr1.SetValue(companyName)
    '                        If Not String.IsNullOrWhiteSpace(reader("shipAddress").ToString()) Then shipAddrBlock.Addr2.SetValue(reader("shipAddress").ToString())
    '                        If Not String.IsNullOrWhiteSpace(reader("shipAddress2").ToString()) Then shipAddrBlock.Addr3.SetValue(reader("shipAddress2").ToString())
    '                        If Not String.IsNullOrWhiteSpace(reader("shipCity").ToString()) Then shipAddrBlock.City.SetValue(reader("shipCity").ToString())
    '                        If Not String.IsNullOrWhiteSpace(reader("shipState").ToString()) Then shipAddrBlock.State.SetValue(reader("shipState").ToString())
    '                        If Not String.IsNullOrWhiteSpace(reader("shipZip").ToString()) Then shipAddrBlock.PostalCode.SetValue(reader("shipZip").ToString())
    '                        If Not String.IsNullOrWhiteSpace(reader("shipPhone").ToString()) Then shipAddrBlock.Note.SetValue("Ph.# " & reader("shipPhone").ToString())
    '                    End If
    '                End Using
    '            End Using

    '            ' Billing address
    '            Dim addressQuery As String = "SELECT address, address2, city, state, zipcode, mainphone FROM b2bbillto WHERE companyid = @B2BID"
    '            Using cmd As New MySqlCommand(addressQuery, conn2)
    '                cmd.Parameters.AddWithValue("@B2BID", B2BID)
    '                Using reader As MySqlDataReader = cmd.ExecuteReader()
    '                    If reader.Read() Then
    '                        If Not String.IsNullOrWhiteSpace(companyName) Then billToAddrBlock.Addr1.SetValue(companyName)
    '                        If Not String.IsNullOrWhiteSpace(reader("address").ToString()) Then billToAddrBlock.Addr2.SetValue(reader("address").ToString())
    '                        If Not String.IsNullOrWhiteSpace(reader("address2").ToString()) Then billToAddrBlock.Addr3.SetValue(reader("address2").ToString())
    '                        If Not String.IsNullOrWhiteSpace(reader("city").ToString()) Then billToAddrBlock.City.SetValue(reader("city").ToString())
    '                        If Not String.IsNullOrWhiteSpace(reader("state").ToString()) Then billToAddrBlock.State.SetValue(reader("state").ToString())
    '                        If Not String.IsNullOrWhiteSpace(reader("zipcode").ToString()) Then billToAddrBlock.PostalCode.SetValue(reader("zipcode").ToString())
    '                        If Not String.IsNullOrWhiteSpace(reader("mainphone").ToString()) Then billToAddrBlock.Note.SetValue("Ph.# " & reader("mainphone").ToString())
    '                    End If
    '                End Using
    '            End Using
    '        End Using

    '        If Not String.IsNullOrEmpty(invoiceNumber) Then
    '            invoiceAdd.RefNumber.SetValue(invoiceNumber)
    '        End If

    '        ' Line items
    '        If itemDetails IsNot Nothing AndAlso itemDetails.Count > 0 Then
    '            For Each item In itemDetails
    '                Dim invoiceLine = invoiceAdd.ORInvoiceLineAddList.Append()
    '                If Not String.IsNullOrWhiteSpace(item.ItemCode) Then invoiceLine.InvoiceLineAdd.ItemRef.FullName.SetValue(item.ItemCode)
    '                If item.Quantity > 0 Then invoiceLine.InvoiceLineAdd.Quantity.SetValue(item.Quantity)
    '                If item.Amount > 0 Then invoiceLine.InvoiceLineAdd.Amount.SetValue(item.Amount)
    '                If Not String.IsNullOrWhiteSpace(item.Description) Then invoiceLine.InvoiceLineAdd.Desc.SetValue(item.Description)
    '                If Not String.IsNullOrWhiteSpace(item.ClassRef) Then invoiceLine.InvoiceLineAdd.ClassRef.FullName.SetValue(item.ClassRef)
    '                invoiceLine.InvoiceLineAdd.SalesTaxCodeRef.FullName.SetValue("NON")
    '            Next
    '        End If

    '        ' Send request to QB
    '        Dim response = sessionManager.DoRequests(msgSetRequest)

    '        If response.ResponseList.Count > 0 Then
    '            Dim invoiceResponse = response.ResponseList.GetAt(0)
    '            If invoiceResponse.StatusCode = 0 Then
    '                ' Update DB flag
    '                Using conn2 As New MySqlConnection(connString)
    '                    conn2.Open()
    '                    Dim query_upd As String = "UPDATE loop_transaction_buyer SET qb_import_done_flg = 1, qb_import_done_date_time = NOW() WHERE id = @buyerID"
    '                    Using cmd_upd As New MySqlCommand(query_upd, conn2)
    '                        cmd_upd.Parameters.AddWithValue("@buyerID", buyerID)
    '                        cmd_upd.ExecuteNonQuery()
    '                    End Using
    '                End Using

    '                msgSetRequest.ClearRequests()
    '                Return True
    '            Else
    '                MessageBox.Show("Error creating invoice: " & invoiceResponse.StatusMessage, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
    '                msgSetRequest.ClearRequests()
    '                Return False
    '            End If
    '        Else
    '            MessageBox.Show("No response received from QuickBooks", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
    '            msgSetRequest.ClearRequests()
    '            Return False
    '        End If

    '    Catch ex As Exception
    '        MessageBox.Show("Error while creating invoice: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
    '        msgSetRequest.ClearRequests()
    '        Return False
    '    End Try
    'End Function



End Class

