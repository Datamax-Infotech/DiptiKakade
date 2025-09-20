' ==============================================================
'  QBMappingEditor.vb     (drop into your WinForms project)
' ==============================================================
Imports System.Data.OleDb
Imports System.Drawing
Imports System.Windows.Forms
Imports OfficeOpenXml                          ' only for future Excel export, safe to remove

Public Class QBMappingEditor
    Inherits UserControl

    'Private ReadOnly connStr As String =
    '    "Provider=Microsoft.ACE.OLEDB.12.0;" &
    '    "Data Source=C:\Users\lenovo\Documents\MooneemDB.accdb;" &
    '    "Persist Security Info=False;"
    Private ReadOnly connStr As String = "Provider=Microsoft.ACE.OLEDB.12.0;Data Source=" + Application.StartupPath + "\MooneemDB.accdb;Persist Security Info=False;"


    Private cmbReportType As ComboBox
    Private txtReportHeading As TextBox
    Private btnUpdateHeading As Button

    Private cmbClientName As ComboBox

    Private btnSaveMapping As Button
    ' at the top of your class
    Private WithEvents btnCopyFromClient As Button
    Private cmbCopyClient As ComboBox        '   ◄ remains a ComboBox


    'Private dgvMapping As DataGridView
    ' at the top of the class
    Private WithEvents dgvMapping As DataGridView

    Private pnlOverlay As Panel
    Private lblStatus As Label

    '──────── small helper for the overlay ────────
    Private Sub ShowStatus(msg As String)
        lblStatus.Text = msg
        pnlOverlay.Visible = True
        pnlOverlay.BringToFront()
        Application.DoEvents()
    End Sub
    Private Sub HideStatus()
        pnlOverlay.Visible = False
    End Sub


    Public Sub New()
        InitializeComponent()
        BuildUi()
        LoadReportTypes()
        LoadClients()
    End Sub


    ' ──────────────────────────────────────────────────────────────────────────────
    ' Call this once from the control / form constructor
    ' ──────────────────────────────────────────────────────────────────────────────
    Private Sub BuildUi()

        Me.Dock = DockStyle.Fill
        Me.BackColor = Color.White

        '================ ❶ 1st ROW ─ Report‑type · Heading · Update btn ==============
        Dim flpLine1 As New FlowLayoutPanel With {
        .Dock = DockStyle.Top,
        .AutoSize = True,
        .AutoSizeMode = AutoSizeMode.GrowAndShrink,
        .Padding = New Padding(15, 15, 15, 0),
        .FlowDirection = FlowDirection.LeftToRight,
        .WrapContents = False        ' keep everything on ONE line
    }

        flpLine1.Controls.Add(New Label With {
        .Text = "Report type:",
        .Font = New Font("Verdana", 11),
        .AutoSize = True,
        .Margin = New Padding(0, 6, 4, 0)})

        cmbReportType = New ComboBox With {
        .DropDownStyle = ComboBoxStyle.DropDownList,
        .Font = New Font("Verdana", 11),
        .Width = 200}
        flpLine1.Controls.Add(cmbReportType)

        flpLine1.Controls.Add(New Label With {
        .Text = "Heading:",
        .Font = New Font("Verdana", 11),
        .AutoSize = True,
        .Margin = New Padding(25, 6, 4, 0)})          ' ← 25 px gap

        txtReportHeading = New TextBox With {
        .Font = New Font("Verdana", 11),
        .Width = 330}
        flpLine1.Controls.Add(txtReportHeading)

        btnUpdateHeading = New Button With {
        .Text = "Update heading",
        .Font = New Font("Verdana", 11, FontStyle.Bold),
        .Width = 180, .Height = 28,
        .BackColor = Color.FromArgb(0, 150, 136),
        .ForeColor = Color.White,
        .FlatStyle = FlatStyle.Flat,
        .Margin = New Padding(20, 1, 0, 0)}
        btnUpdateHeading.FlatAppearance.BorderSize = 0
        flpLine1.Controls.Add(btnUpdateHeading)

        '================ ❷ 2nd ROW ─ Client · Save · Copy‑from‑client ===============
        Dim flpLine2 As New FlowLayoutPanel With {
        .Dock = DockStyle.Top,
        .AutoSize = True,
        .AutoSizeMode = AutoSizeMode.GrowAndShrink,
        .Padding = New Padding(15, 10, 15, 5),
        .FlowDirection = FlowDirection.LeftToRight,
        .WrapContents = False}

        flpLine2.Controls.Add(New Label With {
        .Text = "Client name:",
        .Font = New Font("Verdana", 11),
        .AutoSize = True,
        .Margin = New Padding(0, 6, 4, 0)})

        cmbClientName = New ComboBox With {
        .DropDownStyle = ComboBoxStyle.DropDownList,
        .Font = New Font("Verdana", 11),
        .Width = 220}
        flpLine2.Controls.Add(cmbClientName)

        btnSaveMapping = New Button With {
        .Text = "💾 Save mapping",
        .Font = New Font("Verdana", 11, FontStyle.Bold),
        .Width = 190, .Height = 28,
        .BackColor = Color.FromArgb(63, 81, 181),
        .ForeColor = Color.White,
        .FlatStyle = FlatStyle.Flat,
        .Margin = New Padding(25, 1, 0, 0)}
        btnSaveMapping.FlatAppearance.BorderSize = 0
        flpLine2.Controls.Add(btnSaveMapping)

        btnCopyFromClient = New Button With {
        .Text = "⤵ Copy from client",
        .Font = New Font("Verdana", 11, FontStyle.Bold),
        .Width = 180, .Height = 28,
        .BackColor = Color.FromArgb(96, 125, 139),
        .ForeColor = Color.White,
        .FlatStyle = FlatStyle.Flat,
        .Margin = New Padding(25, 1, 0, 0)}
        btnCopyFromClient.FlatAppearance.BorderSize = 0
        flpLine2.Controls.Add(btnCopyFromClient)

        ' hidden combo – appears next to the copy‑button
        cmbCopyClient = New ComboBox With {
        .DropDownStyle = ComboBoxStyle.DropDownList,
        .Visible = False,
        .Width = 200,
        .Font = New Font("Verdana", 11),
        .Margin = New Padding(15, 1, 0, 0)}
        flpLine2.Controls.Add(cmbCopyClient)

        '================ ❸ Mapping grid =============================================
        dgvMapping = New DataGridView With {
        .Dock = DockStyle.Fill,
        .ReadOnly = False,
        .AllowUserToAddRows = False,
        .AllowUserToDeleteRows = False,
        .EnableHeadersVisualStyles = False,
        .AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.None,  ' <─ allow h‑scroll
        .ScrollBars = ScrollBars.Both,
        .ColumnHeadersDefaultCellStyle = New DataGridViewCellStyle With {
            .BackColor = Color.FromArgb(31, 30, 68),
            .ForeColor = Color.White,
            .Font = New Font("Verdana", 11, FontStyle.Bold)},
        .Font = New Font("Verdana", 11),
        .RowTemplate = New DataGridViewRow() With {.Height = 26},
        .BackgroundColor = Color.White}

        '================ ❹ Status overlay ===========================================
        pnlOverlay = New Panel With {
        .Size = New Size(340, 42),
        .BackColor = Color.FromArgb(20, 0, 150, 136),
        .Visible = False,
        .Anchor = AnchorStyles.None}
        lblStatus = New Label With {
        .Dock = DockStyle.Fill,
        .TextAlign = ContentAlignment.MiddleCenter,
        .ForeColor = Color.Black,
        .Font = New Font("Verdana", 11, FontStyle.Bold)}
        pnlOverlay.Controls.Add(lblStatus)

        '================ Add everything to the control ==============================
        Me.Controls.Add(dgvMapping)
        Me.Controls.Add(flpLine2)
        Me.Controls.Add(flpLine1)
        Me.Controls.Add(pnlOverlay)
        pnlOverlay.BringToFront()

        '================ Event wiring ==============================================
        AddHandler cmbReportType.SelectedIndexChanged, AddressOf cmbReportType_SelectedIndexChanged
        AddHandler cmbClientName.SelectedIndexChanged, AddressOf cmbClientName_SelectedIndexChanged
        AddHandler btnUpdateHeading.Click, AddressOf btnUpdateHeading_Click
        AddHandler btnSaveMapping.Click, AddressOf btnSaveMapping_Click
        AddHandler btnCopyFromClient.Click,
        Sub()
            ' toggle combo visibility & load options on‑demand
            If Not cmbCopyClient.Visible Then
                LoadClientsForCopy()
            End If
            cmbCopyClient.Visible = Not cmbCopyClient.Visible
        End Sub
        AddHandler cmbCopyClient.SelectedIndexChanged, AddressOf cmbCopyClient_SelectedIndexChanged
    End Sub



    Private Sub LoadReportTypes()
        Try
            Using conn As New OleDbConnection(connStr)
                conn.Open()

                Dim cmd As New OleDbCommand("SELECT ID, ReportType FROM ReportMaster", conn)
                Dim reader As OleDbDataReader = cmd.ExecuteReader()

                Dim reportList As New List(Of ReportInfo)()

                While reader.Read()
                    Dim reportId As Integer = Convert.ToInt32(reader("ID"))
                    Dim reportType As String = reader("ReportType").ToString()

                    ' Don't load heading here
                    reportList.Add(New ReportInfo(reportId, reportType, ""))
                End While

                reader.Close()

                cmbReportType.DataSource = reportList
                cmbReportType.DisplayMember = "ReportType"
                cmbReportType.ValueMember = "ID"

            End Using
        Catch ex As Exception
            MessageBox.Show("Error loading report types: " & ex.Message)
        End Try
    End Sub
    Private Sub LoadClients()
        'Dim connStr As String = "Provider=Microsoft.ACE.OLEDB.12.0;Data Source=C:\Users\lenovo\Documents\MooneemDB.accdb"
        'Dim connStr As String = "Provider=Microsoft.ACE.OLEDB.12.0;Data Source=" + Application.StartupPath + "\MooneemDB.accdb;Persist Security Info=False;"

        Using conn As New OleDbConnection(connStr)
            conn.Open()
            Dim cmd As New OleDbCommand("SELECT DISTINCT ClientName FROM GroupClientMapping", conn)
            Dim reader = cmd.ExecuteReader()
            'While reader.Read()
            '    cmbClientName.Items.Add(reader("ClientName").ToString())
            'End While
            Dim clientNames As New List(Of String)()

            While reader.Read()
                clientNames.Add(reader("ClientName").ToString())
            End While

            reader.Close()

            ' 🔽 Sort the names alphabetically
            clientNames = clientNames.OrderBy(Function(name) name).ToList()

            ' 🔽 Add to ComboBox
            cmbClientName.Items.Clear()
            For Each name As String In clientNames
                cmbClientName.Items.Add(name)
            Next


        End Using
    End Sub

    Private Sub SetupMappingGrid()
        dgvMapping.Font = New Font("Verdana", 11)
        dgvMapping.Columns.Clear()
        dgvMapping.AllowUserToAddRows = True
        dgvMapping.AllowUserToDeleteRows = True
        dgvMapping.EditMode = DataGridViewEditMode.EditOnEnter

        Dim selectedReportId As Integer = 0
        If cmbReportType.SelectedItem IsNot Nothing Then
            selectedReportId = CType(cmbReportType.SelectedItem, ReportInfo).ID
        End If

        'MessageBox.Show("Selected Report ID: " & selectedReportId) ' Debug line


        ' Common columns
        Dim idCol As New DataGridViewTextBoxColumn()
        idCol.Name = "ID"
        idCol.HeaderText = "ID"
        idCol.Visible = False
        dgvMapping.Columns.Add(idCol)

        'If selectedReportId = 1 Then
        ' 🔵 Balance Sheet specific columns
        'dgvMapping.Columns.Add("TopCode", "Top Code")
        'dgvMapping.Columns.Add("TopLabel", "Top Label")
        ' Else
        ' 🔴 For P&L, add placeholders for TopCode/TopLabel (invisible)
        Dim topCodeCol As New DataGridViewTextBoxColumn()
        topCodeCol.Name = "TopCode"
        topCodeCol.HeaderText = "Top Code"
        dgvMapping.Columns.Add(topCodeCol)

        Dim topLabelCol As New DataGridViewTextBoxColumn()
        topLabelCol.Name = "TopLabel"
        topLabelCol.HeaderText = "Top Label"
        dgvMapping.Columns.Add(topLabelCol)


        'If selectedReportId = 2 Then
        ' New column for P&L: Renamed Group Label
        Dim renameTopCol As New DataGridViewTextBoxColumn()
            renameTopCol.Name = "RenameTop"
            renameTopCol.HeaderText = "Renamed Top"
            renameTopCol.Width = 150
            dgvMapping.Columns.Add(renameTopCol)



        'End If
        If selectedReportId = 2 Then
            Dim TopWidth As New DataGridViewTextBoxColumn()
            TopWidth.Name = "TopWidth"
            TopWidth.HeaderText = "Top Width"
            TopWidth.Width = 88
            dgvMapping.Columns.Add(TopWidth)
        End If

        ' Common columns
        dgvMapping.Columns.Add("FSCode", "FS Code")
        dgvMapping.Columns.Add("FSGroupName", "FS Group")
        If selectedReportId = 1 Then
            Dim renameFS As New DataGridViewTextBoxColumn()
            renameFS.Name = "RenameFS"
            renameFS.HeaderText = "Renamed FS"
            renameFS.Width = 150
            dgvMapping.Columns.Add(renameFS)

        End If

        Dim FSWidth As New DataGridViewTextBoxColumn()
        FSWidth.Name = "FSWidth"
        FSWidth.HeaderText = "FS Width"
        FSWidth.Width = 80
        dgvMapping.Columns.Add(FSWidth)

        dgvMapping.Columns.Add("GroupCode", "Grp Code")
        dgvMapping.Columns.Add("GroupLabel", "Group Label")

        ' New column for P&L: Renamed Group Label
        Dim renameCol As New DataGridViewTextBoxColumn()
        renameCol.Name = "RenameGrpLabel"
        renameCol.HeaderText = "Renamed Group"
        renameCol.Width = 200
        dgvMapping.Columns.Add(renameCol)

        Dim GRPWidth As New DataGridViewTextBoxColumn()
        GRPWidth.Name = "GRPWidth"
        GRPWidth.HeaderText = "Grp Width"
        GRPWidth.Width = 88
        dgvMapping.Columns.Add(GRPWidth)
        ' Checkboxes
        Dim isActiveCol As New DataGridViewCheckBoxColumn()
        isActiveCol.Name = "IsActive"
        isActiveCol.HeaderText = "Active"
        isActiveCol.Width = 80
        dgvMapping.Columns.Add(isActiveCol)

        If selectedReportId = 1 Then
            Dim MarkForGRPCol As New DataGridViewCheckBoxColumn()
            MarkForGRPCol.Name = "MarkForGRP"
            MarkForGRPCol.HeaderText = "Mark as Group"
            MarkForGRPCol.Width = 80
            dgvMapping.Columns.Add(MarkForGRPCol)
        End If

        Dim isDollarCol As New DataGridViewCheckBoxColumn()
        isDollarCol.Name = "DollarFlag"
        isDollarCol.HeaderText = "($)Format"
        isDollarCol.Width = 60
        dgvMapping.Columns.Add(isDollarCol)

        Dim topDividerRowCol As New DataGridViewCheckBoxColumn()
        topDividerRowCol.Name = "TopDivider"
        topDividerRowCol.HeaderText = "Top Divider"
        topDividerRowCol.Width = 100
        dgvMapping.Columns.Add(topDividerRowCol)

        If selectedReportId = 1 Then
            ' Only for Balance Sheet
            Dim isSubTotalCol As New DataGridViewCheckBoxColumn()
            isSubTotalCol.Name = "SubTotalRow"
            isSubTotalCol.HeaderText = "Sub Total row"
            isSubTotalCol.Width = 60
            dgvMapping.Columns.Add(isSubTotalCol)

        Else





            Dim topBottomDividerRowCol As New DataGridViewCheckBoxColumn()
            topBottomDividerRowCol.Name = "TopBottomDivider"
            topBottomDividerRowCol.HeaderText = "Top Bottom Divider"
            topBottomDividerRowCol.Width = 165
            dgvMapping.Columns.Add(topBottomDividerRowCol)




        End If


        Dim addButton As New DataGridViewButtonColumn()
        addButton.Name = "btnAdd"
        addButton.HeaderText = "Add"
        addButton.Text = "➕"
        addButton.Width = 50
        addButton.UseColumnTextForButtonValue = True
        dgvMapping.Columns.Add(addButton)

        ' Buttons
        Dim deleteButton As New DataGridViewButtonColumn()
        deleteButton.Name = "btnDelete"
        deleteButton.HeaderText = "Action"
        deleteButton.Text = "🗑️"
        deleteButton.Width = 60
        deleteButton.UseColumnTextForButtonValue = True
        dgvMapping.Columns.Add(deleteButton)

        Dim moveUpBtn As New DataGridViewButtonColumn()
        moveUpBtn.Name = "btnUp"
        moveUpBtn.HeaderText = "Up"
        moveUpBtn.Text = "↑"
        moveUpBtn.Width = 60
        moveUpBtn.UseColumnTextForButtonValue = True
        dgvMapping.Columns.Add(moveUpBtn)

        Dim moveDownBtn As New DataGridViewButtonColumn()
        moveDownBtn.Name = "btnDown"
        moveDownBtn.HeaderText = "Down"
        moveDownBtn.Text = "↓"
        moveDownBtn.Width = 60
        moveDownBtn.UseColumnTextForButtonValue = True
        dgvMapping.Columns.Add(moveDownBtn)

        ' Widths
        dgvMapping.Columns("TopCode").Width = 80
        dgvMapping.Columns("TopLabel").Width = 190
        dgvMapping.Columns("FSCode").Width = 80
        dgvMapping.Columns("FSGroupName").Width = 190
        dgvMapping.Columns("GroupCode").Width = 80
        dgvMapping.Columns("GroupLabel").Width = 230
        'dgvMapping.Columns("IsActive").Width = 40
        dgvMapping.Columns("DollarFlag").Width = 60
        ' dgvMapping.Columns("TotalrowFlag").Width = 60
        'dgvMapping.Columns("TotalDollarFlag").Width = 60c  

        '  dgvMapping.Columns("IsActive").Width = 60
        If selectedReportId = 2 Then
            dgvMapping.Columns("DollarFlag").Width = 100
            dgvMapping.Columns("FSGroupName").Width = 240
            dgvMapping.Columns("GroupLabel").Width = 240
        End If
    End Sub



    Private Sub cmbReportType_SelectedIndexChanged(sender As Object, e As EventArgs)
        If cmbReportType.SelectedItem IsNot Nothing Then
            Dim selectedReport As ReportInfo = CType(cmbReportType.SelectedItem, ReportInfo)
            txtReportHeading.Text = selectedReport.Heading
            ' 👉 Rebuild the grid based on the new selected report
            SetupMappingGrid()

            Dim selectedClient As String = cmbClientName.Text
            'If Not String.IsNullOrWhiteSpace(selectedClient) Then
            '    LoadMappingsForClient(selectedClient)

            'End If
            If Not String.IsNullOrWhiteSpace(selectedClient) Then
                ' 👉 Load heading for selected report & client
                Using conn As New OleDbConnection(connStr)
                    conn.Open()

                    Dim headingCmd As New OleDbCommand("SELECT ReportHead FROM ReportHeading WHERE ReportId = ? AND ClientName = ?", conn)
                    headingCmd.Parameters.AddWithValue("?", selectedReport.ID)
                    headingCmd.Parameters.AddWithValue("?", selectedClient)

                    Dim headingResult As Object = headingCmd.ExecuteScalar()
                    If headingResult IsNot Nothing Then
                        txtReportHeading.Text = headingResult.ToString()
                    Else
                        txtReportHeading.Text = ""
                    End If
                End Using

                ' Setup grid based on selection
                SetupMappingGrid()
                LoadMappingsForClient(selectedClient)
            End If
        End If
    End Sub

    Private Sub cmbClientName_SelectedIndexChanged(sender As Object, e As EventArgs)
        Dim selectedClient As String = cmbClientName.Text
        If Not String.IsNullOrWhiteSpace(selectedClient) Then
            LoadMappingsForClient(selectedClient)
            If cmbReportType.SelectedItem IsNot Nothing Then
                Dim selectedReport As ReportInfo = CType(cmbReportType.SelectedItem, ReportInfo)

                Using conn As New OleDbConnection(connStr)
                    conn.Open()

                    Dim headingCmd As New OleDbCommand("SELECT ReportHead FROM ReportHeading WHERE ReportId = ? AND ClientName = ?", conn)
                    headingCmd.Parameters.AddWithValue("?", selectedReport.ID)
                    headingCmd.Parameters.AddWithValue("?", selectedClient)

                    Dim headingResult As Object = headingCmd.ExecuteScalar()
                    If headingResult IsNot Nothing Then
                        txtReportHeading.Text = headingResult.ToString()
                    Else
                        txtReportHeading.Text = ""
                    End If
                End Using
            End If
        End If
    End Sub

    Private Sub LoadMappingsForClient(clientName As String)
        dgvMapping.Rows.Clear()
        'Dim connStr As String = "Provider=Microsoft.ACE.OLEDB.12.0;Data Source=" + Application.StartupPath + "\MooneemDB.accdb;Persist Security Info=False;"

        'Dim connStr = "Provider=Microsoft.ACE.OLEDB.12.0;Data Source=C:\Users\lenovo\Documents\MooneemDB.accdb"
        Dim selectedReportId As Integer = 0

        If cmbReportType.SelectedValue IsNot Nothing Then
            selectedReportId = Convert.ToInt32(cmbReportType.SelectedValue)
        End If

        Using conn As New OleDbConnection(connStr)
            conn.Open()

            If selectedReportId = 1 Then
                ' 🔵 Balance Sheet Mapping (BalanceQBMapping)
                Dim cmd As New OleDbCommand("SELECT ID, TopCode, TopLabel,RenameTop, FSCode, FSGroupName,RenameFS,FSWidth, GroupCode, GroupLabel,GRPWidth,RenameGrpLabel, IsActive,MarkForGRP, DollarFlag,TopDivider,SubTotalRow FROM BalanceQBMapping WHERE ClientName=? AND ReportID=? ORDER BY SortOrder", conn)
                cmd.Parameters.AddWithValue("?", clientName)
                cmd.Parameters.AddWithValue("?", selectedReportId)

                Using reader As OleDbDataReader = cmd.ExecuteReader()
                    While reader.Read()
                        dgvMapping.Rows.Add(
                        reader("ID"),
                        reader("TopCode").ToString(),
                        reader("TopLabel").ToString(),
                        reader("RenameTop").ToString(),
                        reader("FSCode").ToString(),
                        reader("FSGroupName").ToString(),
                        reader("RenameFS").ToString(),
                        reader("FSWidth").ToString(),
                        reader("GroupCode").ToString(),
                        reader("GroupLabel").ToString(),
                        reader("RenameGrpLabel").ToString(),
                         reader("GRPWidth").ToString(),'
                        If(reader("IsActive") Is DBNull.Value, True, Convert.ToBoolean(reader("IsActive"))),
                         If(reader("MarkForGRP") Is DBNull.Value, True, Convert.ToBoolean(reader("MarkForGRP"))),
                        If(reader("DollarFlag") Is DBNull.Value, True, Convert.ToBoolean(reader("DollarFlag"))),
                         If(reader("TopDivider") Is DBNull.Value, True, Convert.ToBoolean(reader("TopDivider"))),
                         If(reader("SubTotalRow") Is DBNull.Value, True, Convert.ToBoolean(reader("SubTotalRow")))
                    )
                    End While
                End Using

            Else
                ' 🔴 Profit & Loss Mapping (PLQBMapping)
                Dim cmd As New OleDbCommand("SELECT ID,TopCode, TopLabel,RenameTop,TopWidth, FSCode, FSGroupName,FSWidth, GroupCode, GroupLabel,RenameGrpLabel,GRPWidth, IsActive, DollarFlag,TopDivider,TopBottomDivider FROM PLQBMapping WHERE ClientName=? AND ReportID=? ORDER BY SortOrder", conn)
                cmd.Parameters.AddWithValue("?", clientName)
                cmd.Parameters.AddWithValue("?", selectedReportId)

                Using reader As OleDbDataReader = cmd.ExecuteReader()
                    While reader.Read()
                        dgvMapping.Rows.Add(
                                reader("ID"),
                        reader("TopCode").ToString(),
                        reader("TopLabel").ToString(),
                           reader("RenameTop").ToString(),
                         reader("TopWidth").ToString(),
                                reader("FSCode").ToString(),
                                reader("FSGroupName").ToString(),
                                  reader("FSWidth").ToString(),
                                reader("GroupCode").ToString(),
                                reader("GroupLabel").ToString(),
                                reader("RenameGrpLabel").ToString(),
                                reader("GRPWidth").ToString(),
                                If(reader("IsActive") Is DBNull.Value, True, Convert.ToBoolean(reader("IsActive"))),
                                If(reader("DollarFlag") Is DBNull.Value, True, Convert.ToBoolean(reader("DollarFlag"))),
                                If(reader("TopDivider") Is DBNull.Value, True, Convert.ToBoolean(reader("TopDivider"))),
                                If(reader("TopBottomDivider") Is DBNull.Value, True, Convert.ToBoolean(reader("TopBottomDivider")))
                                )


                    End While
                End Using

            End If
        End Using
    End Sub


    Private Sub LoadMappingsFromAnotherClient(sourceClient As String)
        dgvMapping.Rows.Clear()
        Dim selectedReportId As Integer = Convert.ToInt32(cmbReportType.SelectedValue)

        Using conn As New OleDbConnection(connStr)
            conn.Open()

            Dim tableName As String = If(selectedReportId = 1, "BalanceQBMapping", "PLQBMapping")
            Dim query As String = "SELECT * FROM " & tableName & " WHERE ClientName = ? AND ReportID = ? ORDER BY SortOrder"

            Using cmd As New OleDbCommand(query, conn)
                cmd.Parameters.AddWithValue("?", sourceClient)
                cmd.Parameters.AddWithValue("?", selectedReportId)

                Using reader As OleDbDataReader = cmd.ExecuteReader()
                    While reader.Read()
                        Dim row As DataGridViewRow = dgvMapping.Rows(dgvMapping.Rows.Add())

                        ' Fill common columns 
                        row.Cells("TopCode").Value = reader("TopCode").ToString()
                        row.Cells("TopLabel").Value = reader("TopLabel").ToString()
                        row.Cells("RenameTop").Value = reader("RenameTop").ToString()
                        If selectedReportId = 2 Then
                            row.Cells("TopWidth").Value = reader("TopWidth").ToString()
                        End If

                        row.Cells("FSCode").Value = reader("FSCode").ToString()
                        row.Cells("FSGroupName").Value = reader("FSGroupName").ToString()
                        If selectedReportId = 1 Then
                            row.Cells("RenameFS").Value = reader("RenameFS").ToString()
                        End If
                        row.Cells("FSWidth").Value = reader("FSWidth").ToString()
                        row.Cells("GroupCode").Value = reader("GroupCode").ToString()
                        row.Cells("GroupLabel").Value = reader("GroupLabel").ToString()

                        row.Cells("RenameGrpLabel").Value = reader("RenameGrpLabel").ToString()
                        row.Cells("GRPWidth").Value = reader("GRPWidth").ToString()
                        row.Cells("IsActive").Value = If(reader("IsActive") Is DBNull.Value, True, Convert.ToBoolean(reader("IsActive")))
                        If selectedReportId = 1 Then
                            row.Cells("MarkForGRP").Value = If(reader("MarkForGRP") Is DBNull.Value, True, Convert.ToBoolean(reader("MarkForGRP")))
                        End If
                        row.Cells("DollarFlag").Value = If(reader("DollarFlag") Is DBNull.Value, True, Convert.ToBoolean(reader("DollarFlag")))
                        row.Cells("TopDivider").Value = If(reader("TopDivider") Is DBNull.Value, False, Convert.ToBoolean(reader("TopDivider")))

                        If selectedReportId = 1 Then
                            row.Cells("SubTotalRow").Value = If(reader("SubTotalRow") Is DBNull.Value, False, Convert.ToBoolean(reader("SubTotalRow")))
                        Else
                            row.Cells("TopBottomDivider").Value = If(reader("TopBottomDivider") Is DBNull.Value, False, Convert.ToBoolean(reader("TopBottomDivider")))
                        End If

                        ' DO NOT assign the "ID" or "ClientName" column – it should insert fresh
                    End While
                End Using
            End Using
        End Using

        MessageBox.Show("Data loaded from '" & sourceClient & "'. Now you can save it for '" & cmbClientName.Text & "'.")
    End Sub

    Private Sub LoadClientsForCopy()
        cmbCopyClient.Items.Clear()

        Using conn As New OleDbConnection(connStr)
            conn.Open()
            Dim cmd As New OleDbCommand("SELECT DISTINCT ClientName FROM GroupClientMapping", conn)
            Dim reader = cmd.ExecuteReader()
            While reader.Read()
                cmbCopyClient.Items.Add(reader("ClientName").ToString())
            End While
        End Using
    End Sub





    Private Sub cmbCopyClient_SelectedIndexChanged(sender As Object, e As EventArgs)
        If cmbCopyClient.SelectedItem Is Nothing Then Return

        Dim sourceClient As String = cmbCopyClient.SelectedItem.ToString()
        LoadMappingsFromAnotherClient(sourceClient)
    End Sub

    Private Sub MoveRowUp(rowIndex As Integer)
        If rowIndex <= 0 Then Return

        Dim rowAbove As DataGridViewRow = dgvMapping.Rows(rowIndex - 1)
        Dim currentRow As DataGridViewRow = dgvMapping.Rows(rowIndex)

        dgvMapping.Rows.RemoveAt(rowIndex)
        dgvMapping.Rows.Insert(rowIndex - 1, currentRow)
    End Sub

    Private Sub MoveRowDown(rowIndex As Integer)
        If rowIndex >= dgvMapping.Rows.Count - 2 Then Return ' Exclude new row
        Dim rowBelow As DataGridViewRow = dgvMapping.Rows(rowIndex + 1)
        Dim currentRow As DataGridViewRow = dgvMapping.Rows(rowIndex)

        dgvMapping.Rows.RemoveAt(rowIndex)
        dgvMapping.Rows.Insert(rowIndex + 1, currentRow)
    End Sub
    Private Sub btnSaveMapping_Click(sender As Object, e As EventArgs)
        If String.IsNullOrWhiteSpace(cmbClientName.Text) Then
            MessageBox.Show("Please select a client.")
            Return
        End If

        If String.IsNullOrWhiteSpace(cmbReportType.Text) Then
            MessageBox.Show("Please select a report type.")
            Return
        End If



        Dim selectedReport As ReportInfo = CType(cmbReportType.SelectedItem, ReportInfo)
        Dim reportID As Integer = selectedReport.ID
        Dim tableName As String = If(reportID = 1, "BalanceQBMapping", "PLQBMapping")

        Using conn As New OleDbConnection(connStr)
            conn.Open()

            Dim sortIndex As Integer = 1

            For Each row As DataGridViewRow In dgvMapping.Rows
                If row.IsNewRow Then Continue For

                Dim idValue = row.Cells("ID").Value
                Dim isNew = (idValue Is Nothing OrElse idValue Is DBNull.Value OrElse idValue.ToString() = "")

                If reportID = 1 Then
                    ' BALANCE SHEET – full insert/update
                    If isNew Then
                        Dim insertCmd As New OleDbCommand("INSERT INTO [" & tableName & "] (ClientName, TopCode, TopLabel, RenameTop, FSCode, FSGroupName,RenameFS,FSWidth, GroupCode, GroupLabel,RenameGrpLabel,GRPWidth, SortOrder, ReportID, IsActive,MarkForGRP, DollarFlag,TopDivider,SubTotalRow) " &
                                                      "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", conn)


                        insertCmd.Parameters.AddWithValue("?", cmbClientName.Text)
                        insertCmd.Parameters.AddWithValue("?", If(row.Cells("TopCode").Value Is Nothing, "", row.Cells("TopCode").Value.ToString()))
                        insertCmd.Parameters.AddWithValue("?", If(row.Cells("TopLabel").Value Is Nothing, "", row.Cells("TopLabel").Value.ToString()))
                        insertCmd.Parameters.AddWithValue("?", If(row.Cells("RenameTop").Value Is Nothing, "", row.Cells("RenameTop").Value.ToString()))
                        insertCmd.Parameters.AddWithValue("?", If(row.Cells("FSCode").Value Is Nothing, "", row.Cells("FSCode").Value.ToString()))
                        insertCmd.Parameters.AddWithValue("?", If(row.Cells("FSGroupName").Value Is Nothing, "", row.Cells("FSGroupName").Value.ToString()))
                        insertCmd.Parameters.AddWithValue("?", If(row.Cells("RenameFS").Value Is Nothing, "", row.Cells("RenameFS").Value.ToString()))
                        insertCmd.Parameters.AddWithValue("?", If(row.Cells("FSWidth").Value Is Nothing, "", row.Cells("FSWidth").Value.ToString()))

                        insertCmd.Parameters.AddWithValue("?", If(row.Cells("GroupCode").Value Is Nothing OrElse Convert.IsDBNull(row.Cells("GroupCode").Value), "", row.Cells("GroupCode").Value.ToString()))

                        insertCmd.Parameters.AddWithValue("?", If(row.Cells("GroupLabel").Value Is Nothing, "", row.Cells("GroupLabel").Value.ToString()))
                        insertCmd.Parameters.AddWithValue("?", If(row.Cells("RenameGrpLabel").Value Is Nothing, "", row.Cells("RenameGrpLabel").Value.ToString()))
                        insertCmd.Parameters.AddWithValue("?", If(row.Cells("GRPWidth").Value Is Nothing, "", row.Cells("GRPWidth").Value.ToString()))

                        insertCmd.Parameters.AddWithValue("?", sortIndex)
                        insertCmd.Parameters.AddWithValue("?", reportID)   '
                        insertCmd.Parameters.AddWithValue("?", If(row.Cells("IsActive").Value Is Nothing, False, CBool(row.Cells("IsActive").Value)))
                        insertCmd.Parameters.AddWithValue("?", If(row.Cells("MarkForGRP").Value Is Nothing, False, CBool(row.Cells("MarkForGRP").Value)))
                        insertCmd.Parameters.AddWithValue("?", If(row.Cells("DollarFlag").Value Is Nothing, False, CBool(row.Cells("DollarFlag").Value)))
                        insertCmd.Parameters.AddWithValue("?", If(row.Cells("TopDivider").Value Is Nothing, False, CBool(row.Cells("TopDivider").Value)))
                        insertCmd.Parameters.AddWithValue("?", If(row.Cells("SubTotalRow").Value Is Nothing, False, CBool(row.Cells("SubTotalRow").Value)))

                        insertCmd.ExecuteNonQuery()
                    Else
                        Dim updateCmd As New OleDbCommand("UPDATE [" & tableName & "] SET TopCode=?, TopLabel=?,RenameTop=?, FSCode=?, FSGroupName=?,RenameFS=?,FSWidth=?, GroupCode=?, GroupLabel=?,RenameGrpLabel=?,GRPWidth=?, SortOrder=?, ReportID=?, IsActive=?, MarkForGRP=?, DollarFlag=?,TopDivider=?,SubTotalRow=? WHERE ID=?", conn)

                        updateCmd.Parameters.AddWithValue("?", If(row.Cells("TopCode").Value Is Nothing, "", row.Cells("TopCode").Value.ToString()))
                        updateCmd.Parameters.AddWithValue("?", If(row.Cells("TopLabel").Value Is Nothing, "", row.Cells("TopLabel").Value.ToString()))
                        updateCmd.Parameters.AddWithValue("?", If(row.Cells("RenameTop").Value Is Nothing, "", row.Cells("RenameTop").Value.ToString()))
                        updateCmd.Parameters.AddWithValue("?", If(row.Cells("FSCode").Value Is Nothing, "", row.Cells("FSCode").Value.ToString()))
                        updateCmd.Parameters.AddWithValue("?", If(row.Cells("FSGroupName").Value Is Nothing, "", row.Cells("FSGroupName").Value.ToString()))
                        updateCmd.Parameters.AddWithValue("?", If(row.Cells("RenameFS").Value Is Nothing, "", row.Cells("RenameFS").Value.ToString()))
                        updateCmd.Parameters.AddWithValue("?", If(row.Cells("FSWidth").Value Is Nothing, "", row.Cells("FSWidth").Value.ToString()))

                        updateCmd.Parameters.AddWithValue("?", If(row.Cells("GroupCode").Value Is Nothing, "", row.Cells("GroupCode").Value.ToString()))
                        updateCmd.Parameters.AddWithValue("?", If(row.Cells("GroupLabel").Value Is Nothing, "", row.Cells("GroupLabel").Value.ToString()))
                        updateCmd.Parameters.AddWithValue("?", If(row.Cells("RenameGrpLabel").Value Is Nothing, "", row.Cells("RenameGrpLabel").Value.ToString()))
                        updateCmd.Parameters.AddWithValue("?", If(row.Cells("GRPWidth").Value Is Nothing, "", row.Cells("GRPWidth").Value.ToString()))
                        updateCmd.Parameters.AddWithValue("?", sortIndex)
                        updateCmd.Parameters.AddWithValue("?", reportID)
                        updateCmd.Parameters.AddWithValue("?", If(row.Cells("IsActive").Value Is Nothing, False, CBool(row.Cells("IsActive").Value)))
                        updateCmd.Parameters.AddWithValue("?", If(row.Cells("MarkForGRP").Value Is Nothing, False, CBool(row.Cells("MarkForGRP").Value)))
                        updateCmd.Parameters.AddWithValue("?", If(row.Cells("DollarFlag").Value Is Nothing, False, CBool(row.Cells("DollarFlag").Value)))
                        updateCmd.Parameters.AddWithValue("?", If(row.Cells("TopDivider").Value Is Nothing, False, CBool(row.Cells("TopDivider").Value)))
                        updateCmd.Parameters.AddWithValue("?", If(row.Cells("SubTotalRow").Value Is Nothing, False, CBool(row.Cells("SubTotalRow").Value)))
                        updateCmd.Parameters.AddWithValue("?", Convert.ToInt32(idValue))
                        updateCmd.ExecuteNonQuery()

                    End If

                Else
                    ' PROFIT & LOSS – insert/update without TopCode-related fields  
                    If isNew Then
                        Dim insertCmd As New OleDbCommand("INSERT INTO [" & tableName & "] (ClientName, TopCode, TopLabel,RenameTop,TopWidth, FSCode, FSGroupName,FSWidth, GroupCode, GroupLabel,RenameGrpLabel,GRPWidth, SortOrder, ReportID, IsActive, DollarFlag,TopDivider,TopBottomDivider) " &
                                                      "VALUES (?, ?, ?, ?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?,?)", conn)

                        insertCmd.Parameters.AddWithValue("?", If(String.IsNullOrWhiteSpace(cmbClientName.Text), "", cmbClientName.Text))
                        insertCmd.Parameters.AddWithValue("?", If(row.Cells("TopCode").Value Is Nothing, "", row.Cells("TopCode").Value.ToString()))
                        insertCmd.Parameters.AddWithValue("?", If(row.Cells("TopLabel").Value Is Nothing, "", row.Cells("TopLabel").Value.ToString()))
                        insertCmd.Parameters.AddWithValue("?", If(row.Cells("RenameTop").Value Is Nothing, "", row.Cells("RenameTop").Value.ToString()))

                        insertCmd.Parameters.AddWithValue("?", If(row.Cells("TopWidth").Value Is Nothing, "", row.Cells("TopWidth").Value.ToString()))

                        insertCmd.Parameters.AddWithValue("?", If(row.Cells("FSCode").Value Is Nothing, "", row.Cells("FSCode").Value.ToString()))
                        insertCmd.Parameters.AddWithValue("?", If(row.Cells("FSGroupName").Value Is Nothing, "", row.Cells("FSGroupName").Value.ToString()))
                        insertCmd.Parameters.AddWithValue("?", If(row.Cells("FSWidth").Value Is Nothing, "", row.Cells("FSWidth").Value.ToString()))

                        insertCmd.Parameters.AddWithValue("?", If(row.Cells("GroupCode").Value Is Nothing, "", row.Cells("GroupCode").Value.ToString()))
                        insertCmd.Parameters.AddWithValue("?", If(row.Cells("GroupLabel").Value Is Nothing, "", row.Cells("GroupLabel").Value.ToString()))
                        insertCmd.Parameters.AddWithValue("?", If(row.Cells("RenameGrpLabel").Value Is Nothing, "", row.Cells("RenameGrpLabel").Value.ToString()))
                        insertCmd.Parameters.AddWithValue("?", If(row.Cells("GRPWidth").Value Is Nothing, "", row.Cells("GRPWidth").Value.ToString()))

                        insertCmd.Parameters.AddWithValue("?", sortIndex)
                        insertCmd.Parameters.AddWithValue("?", reportID)
                        insertCmd.Parameters.AddWithValue("?", If(row.Cells("IsActive").Value Is Nothing, False, CBool(row.Cells("IsActive").Value)))
                        insertCmd.Parameters.AddWithValue("?", If(row.Cells("DollarFlag").Value Is Nothing, False, CBool(row.Cells("DollarFlag").Value)))
                        insertCmd.Parameters.AddWithValue("?", If(row.Cells("TopDivider").Value Is Nothing, False, CBool(row.Cells("TopDivider").Value)))
                        insertCmd.Parameters.AddWithValue("?", If(row.Cells("TopBottomDivider").Value Is Nothing, False, CBool(row.Cells("TopBottomDivider").Value)))

                        insertCmd.ExecuteNonQuery()
                    Else
                        Dim updateCmd As New OleDbCommand("UPDATE [" & tableName & "] SET TopCode=?, TopLabel=?,RenameTop=?, TopWidth=?, FSCode=?, FSGroupName=?,FSWidth=?, GroupCode=?, GroupLabel=?, RenameGrpLabel=?,GRPWidth=?,  SortOrder=?, ReportID=?, IsActive=?, DollarFlag=?, TopDivider=?, TopBottomDivider=? WHERE ID=?", conn)

                        updateCmd.Parameters.AddWithValue("?", If(row.Cells("TopCode").Value Is Nothing, "", row.Cells("TopCode").Value.ToString()))
                        updateCmd.Parameters.AddWithValue("?", If(row.Cells("TopLabel").Value Is Nothing, "", row.Cells("TopLabel").Value.ToString()))
                        updateCmd.Parameters.AddWithValue("?", If(row.Cells("RenameTop").Value Is Nothing, "", row.Cells("RenameTop").Value.ToString()))
                        updateCmd.Parameters.AddWithValue("?", If(row.Cells("TopWidth").Value Is Nothing, "", row.Cells("TopWidth").Value.ToString()))

                        updateCmd.Parameters.AddWithValue("?", If(row.Cells("FSCode").Value Is Nothing, "", row.Cells("FSCode").Value.ToString()))
                        updateCmd.Parameters.AddWithValue("?", If(row.Cells("FSGroupName").Value Is Nothing, "", row.Cells("FSGroupName").Value.ToString()))
                        updateCmd.Parameters.AddWithValue("?", If(row.Cells("FSWidth").Value Is Nothing, "", row.Cells("FSWidth").Value.ToString()))

                        updateCmd.Parameters.AddWithValue("?", If(row.Cells("GroupCode").Value Is Nothing, "", row.Cells("GroupCode").Value.ToString()))
                        updateCmd.Parameters.AddWithValue("?", If(row.Cells("GroupLabel").Value Is Nothing, "", row.Cells("GroupLabel").Value.ToString()))
                        updateCmd.Parameters.AddWithValue("?", If(row.Cells("RenameGrpLabel").Value Is Nothing, "", row.Cells("RenameGrpLabel").Value.ToString()))
                        updateCmd.Parameters.AddWithValue("?", If(row.Cells("GRPWidth").Value Is Nothing, "", row.Cells("GRPWidth").Value.ToString()))

                        updateCmd.Parameters.AddWithValue("?", sortIndex)
                        updateCmd.Parameters.AddWithValue("?", reportID)
                        updateCmd.Parameters.AddWithValue("?", If(row.Cells("IsActive").Value Is Nothing, False, CBool(row.Cells("IsActive").Value)))
                        updateCmd.Parameters.AddWithValue("?", If(row.Cells("DollarFlag").Value Is Nothing, False, CBool(row.Cells("DollarFlag").Value)))
                        updateCmd.Parameters.AddWithValue("?", If(row.Cells("TopDivider").Value Is Nothing, False, CBool(row.Cells("TopDivider").Value)))
                        updateCmd.Parameters.AddWithValue("?", If(row.Cells("TopBottomDivider").Value Is Nothing, False, CBool(row.Cells("TopBottomDivider").Value)))
                        updateCmd.Parameters.AddWithValue("?", Convert.ToInt32(idValue))

                        updateCmd.ExecuteNonQuery()
                    End If
                End If

                sortIndex += 1
            Next
        End Using

        MessageBox.Show("Mappings saved successfully.")
        LoadMappingsForClient(cmbClientName.Text)
    End Sub
    Private Sub btnUpdateHeading_Click(sender As Object, e As EventArgs)
        If String.IsNullOrWhiteSpace(cmbReportType.Text) Then
            MessageBox.Show("Please select a report type.")
            Return
        End If

        If String.IsNullOrWhiteSpace(txtReportHeading.Text) Then
            MessageBox.Show("Please enter a report heading.")
            Return
        End If

        If String.IsNullOrWhiteSpace(cmbClientName.Text) Then
            MessageBox.Show("Please select a client name.")
            Return
        End If

        Using conn As New OleDbConnection(connStr)
            conn.Open()

            Dim reportType As String = cmbReportType.Text.Trim()
            Dim newHeading As String = txtReportHeading.Text.Trim()
            Dim clientName As String = cmbClientName.Text.Trim()

            ' Get ReportId from ReportMaster
            Dim getReportIdCmd As New OleDbCommand("SELECT ID FROM ReportMaster WHERE ReportType = ?", conn)
            getReportIdCmd.Parameters.AddWithValue("?", reportType)
            Dim reportId As Object = getReportIdCmd.ExecuteScalar()

            If reportId Is Nothing Then
                MessageBox.Show("Report type not found in ReportMaster.")
                Return
            End If

            ' Check if heading exists for client + report
            Dim checkCmd As New OleDbCommand("SELECT ID, ReportHead FROM ReportHeading WHERE ClientName = ? AND ReportId = ?", conn)
            checkCmd.Parameters.AddWithValue("?", clientName)
            checkCmd.Parameters.AddWithValue("?", reportId)

            Using reader As OleDbDataReader = checkCmd.ExecuteReader()
                If reader.Read() Then
                    Dim headingId = Convert.ToInt32(reader("ID"))
                    Dim oldHeading = reader("ReportHead").ToString()

                    If oldHeading <> newHeading Then
                        ' Update existing heading
                        Dim updateCmd As New OleDbCommand("UPDATE ReportHeading SET ReportHead = ? WHERE ID = ?", conn)
                        updateCmd.Parameters.AddWithValue("?", newHeading)
                        updateCmd.Parameters.AddWithValue("?", headingId)
                        updateCmd.ExecuteNonQuery()
                        MessageBox.Show("Report heading updated.")
                    Else
                        MessageBox.Show("Heading is already up to date.")
                    End If
                Else
                    ' Insert new heading
                    Dim insertCmd As New OleDbCommand("INSERT INTO ReportHeading (ClientName, ReportId, ReportHead) VALUES (?, ?, ?)", conn)
                    insertCmd.Parameters.AddWithValue("?", clientName)
                    insertCmd.Parameters.AddWithValue("?", reportId)
                    insertCmd.Parameters.AddWithValue("?", newHeading)
                    insertCmd.ExecuteNonQuery()
                    MessageBox.Show("New report heading saved.")
                End If
            End Using
        End Using
    End Sub

    Private Sub dgvMapping_CellContentClick(sender As Object, e As DataGridViewCellEventArgs) Handles dgvMapping.CellContentClick
        If e.RowIndex < 0 Then Return

        Dim colName As String = dgvMapping.Columns(e.ColumnIndex).Name
        Dim selectedReport As ReportInfo = CType(cmbReportType.SelectedItem, ReportInfo)
        Dim reportID As Integer = selectedReport.ID

        Select Case colName


            Case "btnDelete"
                If MessageBox.Show("Delete this mapping?", "Confirm", MessageBoxButtons.YesNo) = DialogResult.Yes Then
                    Dim clientName = cmbClientName.Text.Trim()
                    Dim groupLabel = dgvMapping.Rows(e.RowIndex).Cells("GroupLabel").Value?.ToString()?.Trim()
                    Dim tableName As String

                    Using conn As New OleDbConnection(connStr)
                        conn.Open()

                        If reportID = 1 Then
                            ' 🔵 Balance Sheet
                            tableName = "BalanceQBMapping"
                        Else
                            ' 🔴 P&L
                            tableName = "PLQBMapping"
                        End If

                        ' Check if a record exists with the same clientName and groupLabel
                        Dim checkCmd As New OleDbCommand("SELECT COUNT(*) FROM [" & tableName & "] WHERE ClientName=? AND GroupLabel=? AND ReportID=?", conn)
                        checkCmd.Parameters.AddWithValue("?", clientName)
                        checkCmd.Parameters.AddWithValue("?", groupLabel)
                        checkCmd.Parameters.AddWithValue("?", reportID)

                        Dim matchCount As Integer = Convert.ToInt32(checkCmd.ExecuteScalar())

                        If matchCount > 0 Then
                            ' Proceed with deletion
                            Dim deleteCmd As New OleDbCommand("DELETE FROM [" & tableName & "] WHERE ClientName=? AND GroupLabel=? AND ReportID=?", conn)
                            deleteCmd.Parameters.AddWithValue("?", clientName)
                            deleteCmd.Parameters.AddWithValue("?", groupLabel)
                            deleteCmd.Parameters.AddWithValue("?", reportID)
                            deleteCmd.ExecuteNonQuery()

                            dgvMapping.Rows.RemoveAt(e.RowIndex)
                        Else
                            MessageBox.Show("No exact match found for Group Label. Deletion cancelled.", "No Match", MessageBoxButtons.OK, MessageBoxIcon.Warning)
                        End If
                    End Using
                End If



            Case "btnUp"
                If e.RowIndex > 0 Then
                    Dim row = dgvMapping.Rows(e.RowIndex)
                    dgvMapping.Rows.RemoveAt(e.RowIndex)
                    dgvMapping.Rows.Insert(e.RowIndex - 1, row)
                End If

            Case "btnDown"
                If e.RowIndex < dgvMapping.Rows.Count - 2 Then ' -2 to avoid moving below the new row
                    Dim row = dgvMapping.Rows(e.RowIndex)
                    dgvMapping.Rows.RemoveAt(e.RowIndex)
                    dgvMapping.Rows.Insert(e.RowIndex + 1, row)
                End If

            Case "btnAdd"
                ' ➕ Insert new blank row below the clicked one
                Dim newRow As DataGridViewRow = CType(dgvMapping.Rows(e.RowIndex).Clone(), DataGridViewRow)
                For i As Integer = 0 To newRow.Cells.Count - 1
                    If TypeOf newRow.Cells(i) Is DataGridViewCheckBoxCell Then
                        newRow.Cells(i).Value = False ' Or True depending on your default
                    Else
                        newRow.Cells(i).Value = ""
                    End If
                Next

                dgvMapping.Rows.Insert(e.RowIndex + 1, newRow)
        End Select
    End Sub

End Class


'──────── POCO for the report‑type combo ────────
Public Class ReportInfo
    Public Property ID As Integer
    Public Property ReportType As String
    Public Property Heading As String
    Public Sub New(i As Integer, t As String, h As String)
        ID = i : ReportType = t : Heading = h
    End Sub
    Public Overrides Function ToString() As String
        Return ReportType
    End Function
End Class
