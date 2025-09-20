' ============================== TransactionSummary.vb =============================
' A modern WinForms user‑control that shows:
'   • CheckedListBox   (clbFilter)
'   • RadioButton      (rdoOption)
'   • DateTimePickers  (dtpFrom, dtpTo)
'   • Buttons          (btnFetch, btnExport)
'   • DataGridView     (dgvReport)
' ------------------------------------------------------------------------------

Imports System.Windows.Forms
Imports System.Drawing
Imports System.Data.OleDb
Imports QBFC12Lib
Imports System.IO
Imports System.Runtime.InteropServices.ComTypes
Imports OfficeOpenXml

Public Class TransactionSummary


    Inherits UserControl

    ' ───── controls ──────────────────────────────────────────────────────────────
    Private clbFilter As CheckedListBox
    Private rdoOption As RadioButton
    Private dtpFrom As DateTimePicker
    Private dtpTo As DateTimePicker
    Private btnFetch As Button
    Private btnExport As Button
    ' ▼▼  NEW – three grids the FetchReport code expects  ▼▼
    Private dgvOutput As DataGridView   ' full detail
    Private dgvSummary As DataGridView   ' summary #1
    Private dgvSummarytwo As DataGridView   ' summary #2
    Private lblStatus As Label
    Private pnlOverlay As Panel
    'Private ReadOnly connStr As String =
    '    "Provider=Microsoft.ACE.OLEDB.12.0;" &
    '    "Data Source=C:\Users\lenovo\Documents\MooneemDB.accdb;" &
    '    "Persist Security Info=False;"
    Private ReadOnly connStr As String = "Provider=Microsoft.ACE.OLEDB.12.0;Data Source=" + Application.StartupPath + "\MooneemDB.accdb;Persist Security Info=False;"

    Public Sub New()
        InitializeComponent()
        BuildUI()
    End Sub

    ' helper – turn the overlay on, pump the message‑queue once
    Private Sub ShowStatus(msg As String)
        lblStatus.Text = msg
        pnlOverlay.Visible = True          ' ⇦ make the panel visible
        pnlOverlay.BringToFront()          ' keep it above the grids
        Application.DoEvents()             ' let the UI repaint *now*
    End Sub

    Private Sub HideStatus()
        pnlOverlay.Visible = False
    End Sub

    Private Sub StyleGrid(grid As DataGridView)
        With grid
            .Font = New Font("Verdana", 10)
            .RowTemplate = New DataGridViewRow() With {.Height = 28}
            .EnableHeadersVisualStyles = False
            .ColumnHeadersHeightSizeMode = DataGridViewColumnHeadersHeightSizeMode.AutoSize
            .ColumnHeadersDefaultCellStyle = New DataGridViewCellStyle With {
            .BackColor = Color.FromArgb(31, 30, 68),
            .ForeColor = Color.White,
            .Font = New Font("Verdana", 10, FontStyle.Bold)
        }
            .BackgroundColor = Color.White
            .AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.Fill
        End With
    End Sub

    ' ────────────────────────── UI LAYOUT ───────────────────────────────────────
    Private Sub BuildUI()

        Me.Dock = DockStyle.Fill
        Me.BackColor = Color.White

        ' ----- top panel (inputs) ------------------------------------------------
        Dim pnlTop As New Panel With {
            .Dock = DockStyle.Top,
            .Height = 110,
            .Padding = New Padding(15)
        }

        ' CheckedListBox (filter list)
        clbFilter = New CheckedListBox With {
            .Font = New Font("Verdana", 11),
            .Width = 300,
            .Height = 98,
            .CheckOnClick = True
        }
        ' Comment this out:

        LoadClients()


        ' RadioButton (single option)
        rdoOption = New RadioButton With {
            .Text = "Include Voided",
            .Font = New Font("Verdana", 11),
            .AutoSize = True
        }

        ' Date pickers
        dtpFrom = New DateTimePicker With {
            .Format = DateTimePickerFormat.Short,
            .Font = New Font("Verdana", 11),
             .Width = 150 ' 👈 manually set width
        }
        dtpTo = New DateTimePicker With {
            .Format = DateTimePickerFormat.Short,
            .Font = New Font("Verdana", 11),
             .Width = 150 ' 👈 manually set width
        }

        ' Buttons
        'btnFetch = New Button With {
        '    .Text = "📊  Fetch Report",
        '    .Font = New Font("Verdana Semibold", 11, FontStyle.Bold),
        '    .BackColor = Color.FromArgb(0, 150, 136),
        '    .ForeColor = Color.White,
        '    .FlatStyle = FlatStyle.Flat,
        '    .Width = 185,
        '    .Height = 34
        '}
        'btnFetch.FlatAppearance.BorderSize = 0

        'btnExport = New Button With {
        '    .Text = "📥  Export to Excel",
        '    .Font = New Font("Verdana Semibold", 11, FontStyle.Bold),
        '    .BackColor = Color.FromArgb(63, 81, 181),
        '    .ForeColor = Color.White,
        '    .FlatStyle = FlatStyle.Flat,
        '    .Width = 185,
        '    .Height = 34
        '}
        'btnExport.FlatAppearance.BorderSize = 0

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


        ' ------ manual positions inside pnlTop (flexible table layout also fine) --


        ' friendly gaps ----------------------------------------------------------
        Const padLeft As Integer = 25   ' distance from sidebar
        Const padTop As Integer = 15
        Const hGap As Integer = 30   ' horizontal gap between groups

        ' CheckedListBox on left
        clbFilter.Location = New Point(padLeft, padTop)

        ' Radio button – same row
        rdoOption.Text = "Select All"
        rdoOption.Location = New Point(clbFilter.Right + hGap, padTop + 2)

        ' ----- NEW: date labels -------------------------------------------------
        Dim lblFrom As New Label With {
            .Text = "Start Date:",
            .AutoSize = True,
            .Font = New Font("Verdana", 11, FontStyle.Regular)
        }
        Dim lblTo As New Label With {
            .Text = "End Date:",
            .AutoSize = True,
            .Font = New Font("Verdana", 11, FontStyle.Regular)
        }
        pnlTop.Controls.AddRange({lblFrom, lblTo})

        ' Align labels + pickers on same baseline
        lblFrom.Location = New Point(rdoOption.Right + hGap, padTop + 4)
        dtpFrom.Location = New Point(lblFrom.Right + 5, padTop)

        lblTo.Location = New Point(dtpFrom.Right + hGap, padTop + 4)
        dtpTo.Location = New Point(lblTo.Right + 5, padTop)


        btnFetch.Location = New Point(350, 65)   ' put them wherever you want
        btnExport.Location = New Point(550, 65)
        pnlTop.Controls.AddRange({clbFilter, rdoOption, dtpFrom, dtpTo, btnFetch, btnExport})

        ' ----- data grid ---------------------------------------------------------
        ' ───────────── 3‑grid layout  ─────────────────────────────────────
        ' --- instantiate ---
        ' --- instantiate the grids ---
        dgvOutput = New DataGridView With {.Dock = DockStyle.Fill, .ReadOnly = True,
                                       .AllowUserToAddRows = False,
                                       .AllowUserToDeleteRows = False}
        dgvSummary = New DataGridView With {.Dock = DockStyle.Fill, .ReadOnly = True,
                                       .AllowUserToAddRows = False,
                                       .AllowUserToDeleteRows = False}
        dgvSummarytwo = New DataGridView With {.Dock = DockStyle.Fill, .ReadOnly = True,
                                       .AllowUserToAddRows = False,
                                       .AllowUserToDeleteRows = False}

        ' --- apply consistent styling ---
        StyleGrid(dgvOutput)
        StyleGrid(dgvSummary)
        StyleGrid(dgvSummarytwo)



        ' --- arrange with a TableLayoutPanel ---
        Dim tbl As New TableLayoutPanel With {.Dock = DockStyle.Fill, .ColumnCount = 2, .RowCount = 2}
        tbl.ColumnStyles.Add(New ColumnStyle(SizeType.Percent, 50))
        tbl.ColumnStyles.Add(New ColumnStyle(SizeType.Percent, 50))
        tbl.RowStyles.Add(New RowStyle(SizeType.Percent, 55))   ' top grid takes full width
        tbl.RowStyles.Add(New RowStyle(SizeType.Percent, 45))   ' bottom two grids side‑by‑side

        tbl.Controls.Add(dgvOutput, 0, 0) : tbl.SetColumnSpan(dgvOutput, 2)
        tbl.Controls.Add(dgvSummary, 0, 1)
        tbl.Controls.Add(dgvSummarytwo, 1, 1)

        ' --- finally drop the table into the UserControl *before* lblStatus ---
        Me.Controls.Add(tbl)   '   (this line comes before “Me.Controls.Add(lblStatus)”)

        Me.Controls.Add(pnlTop)
        Me.Controls.Add(lblStatus)

        ' ----- events ------------------------------------------------------------
        AddHandler btnFetch.Click, AddressOf FetchReport
        AddHandler btnExport.Click, AddressOf ExportToExcel

        ' ===== Three‑grid layout (TableLayoutPanel) ===============================
        Dim tlpGrid As New TableLayoutPanel With {
            .Dock = DockStyle.Fill,
            .ColumnCount = 2,
            .RowCount = 2,
            .Padding = New Padding(0),
            .BackColor = Color.White
        }
        ' Row styles: first row 55 %, second row 45 %
        tlpGrid.RowStyles.Add(New RowStyle(SizeType.Percent, 55))
        tlpGrid.RowStyles.Add(New RowStyle(SizeType.Percent, 45))
        ' Column styles: second row only (two halves)
        tlpGrid.ColumnStyles.Add(New ColumnStyle(SizeType.Percent, 50))
        tlpGrid.ColumnStyles.Add(New ColumnStyle(SizeType.Percent, 50))

        ' --- create the three DataGridViews --------------------------------------
        Dim dgvTop As New DataGridView With {
            .Dock = DockStyle.Fill,
            .ReadOnly = True,
            .AllowUserToAddRows = False,
            .AllowUserToDeleteRows = False,
            .AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.Fill
        }
        Dim dgvBottomLeft As New DataGridView With {
            .Dock = DockStyle.Fill,
            .ReadOnly = True,
            .AllowUserToAddRows = False,
            .AllowUserToDeleteRows = False,
            .AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.Fill
        }
        Dim dgvBottomRight As New DataGridView With {
            .Dock = DockStyle.Fill,
            .ReadOnly = True,
            .AllowUserToAddRows = False,
            .AllowUserToDeleteRows = False,
            .AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.Fill
        }

        ' --- add to table --------------------------------------------------------
        ' Row 0: span both columns
        tlpGrid.SetColumnSpan(dgvTop, 2)
        tlpGrid.Controls.Add(dgvTop, 0, 0)

        ' Row 1: two columns
        tlpGrid.Controls.Add(dgvBottomLeft, 0, 1)
        tlpGrid.Controls.Add(dgvBottomRight, 1, 1)

        ' --- finally add TLP to the user‑control ---------------------------------
        Me.Controls.Add(tlpGrid)


        ' ── overlay for status/messages ─────────────────────────────
        pnlOverlay = New Panel With {
    .Size = New Size(390, 40),
    .BackColor = Color.FromArgb(20, 0, 150, 136),
    .Visible = False,
    .Anchor = AnchorStyles.None                       ' free‑floating
}

        lblStatus = New Label With {
    .Dock = DockStyle.Fill,
    .Font = New Font("Verdana Semibold", 11, FontStyle.Bold),
    .ForeColor = Color.Black,
    .BackColor = Color.Transparent,
    .TextAlign = ContentAlignment.MiddleCenter
}

        pnlOverlay.Controls.Add(lblStatus)
        Me.Controls.Add(pnlOverlay)
        pnlOverlay.BringToFront()

        ' recalc centre whenever parent resized
        AddHandler Me.SizeChanged,
    Sub()
        pnlOverlay.Location = New Point(
            (Me.ClientSize.Width - pnlOverlay.Width) \ 2,
            (Me.ClientSize.Height - pnlOverlay.Height) \ 2)
    End Sub




    End Sub



    Private Sub LoadClients()
        Try
            Using conn As New OleDbConnection(connStr)
                conn.Open()
                Dim cmd As New OleDbCommand("SELECT ClientName, QBFilePath FROM GroupClientMapping", conn)
                Dim reader As OleDbDataReader = cmd.ExecuteReader()

                clbFilter.Items.Clear()

                Dim clientList As New List(Of ClientInfo)()
                While reader.Read()
                    Dim clientName As String = reader("ClientName").ToString()
                    Dim filePath As String = reader("QBFilePath").ToString()
                    clientList.Add(New ClientInfo(clientName, filePath))
                End While
                reader.Close()

                ' Sort alphabetically
                clientList = clientList.OrderBy(Function(c) c.Name).ToList()

                ' Add to CheckedListBox
                For Each client In clientList
                    clbFilter.Items.Add(client)
                Next
            End Using

        Catch ex As Exception
            MessageBox.Show("Error loading clients: " & ex.Message)
        End Try
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



    ' ─────────────────────── BUSINESS‑LOGIC STUBS ──────────────────────────────
    Private Sub FetchReport(sender As Object, e As EventArgs)
        Dim qbFileLocations As New List(Of String)()
        Dim fileToClient As New Dictionary(Of String, String)(StringComparer.OrdinalIgnoreCase)

        If rdoOption.Checked Then
             Dim basePath As String = "\\TS240482AD\TKAssociates_Company_Files"
            'Dim basePath As String = "C:\Users\Public\Documents\Intuit\QuickBooks\Sample Company Files\QuickBooks Enterprise Solutions 13.0"

            If Directory.Exists(basePath) Then
                Dim qbwFiles As String() = Directory.GetFiles(basePath, "*.qbw", SearchOption.AllDirectories)
                qbFileLocations.AddRange(qbwFiles)
            Else
                MessageBox.Show("❌ Shared folder path not found: " & basePath, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
                Return
            End If
        Else
            ' Use selected clients from CheckedListBox
            For Each item As Object In clbFilter.CheckedItems
                Dim clientInfo As ClientInfo = CType(item, ClientInfo)
                Dim qbFileLocation As String = clientInfo.FilePath
                Dim clientName As String = clientInfo.Name

                If Not String.IsNullOrWhiteSpace(qbFileLocation) Then
                    qbFileLocations.Add(qbFileLocation)
                    If Not fileToClient.ContainsKey(qbFileLocation) Then
                        fileToClient(qbFileLocation) = clientName
                    End If
                End If
            Next
        End If
        Dim selectedStartDate As DateTime = dtpFrom.Value
        Dim selectedEndDate As DateTime = dtpTo.Value
        If selectedStartDate > selectedEndDate Then
            MessageBox.Show("Start date cannot be later than end date.", "Date Range Error", MessageBoxButtons.OK, MessageBoxIcon.Warning)
            Return
        End If



        ' Full detail DataTable
        Dim dataTable As New DataTable()
        dataTable.Columns.Add("Trans #", GetType(Object))
        dataTable.Columns.Add("Type", GetType(String))
        dataTable.Columns.Add("Entered/Last Modified", GetType(String))
        dataTable.Columns.Add("Last Modified By", GetType(String))
        ' dataTable.Columns.Add("Month", GetType(String))
        dataTable.Columns.Add("Date", GetType(String))
        '  dataTable.Columns.Add("Num", GetType(String))
        dataTable.Columns.Add("Name", GetType(String))
        dataTable.Columns.Add("Memo", GetType(String))
        dataTable.Columns.Add("Account", GetType(String))
        dataTable.Columns.Add("Clr", GetType(Object))
        dataTable.Columns.Add("Split", GetType(Object))
        dataTable.Columns.Add("Amount", GetType(Double))
        dataTable.Columns.Add("Class", GetType(Object))
        dataTable.Columns.Add("Client Name", GetType(String))


        ' Summary: client → (month → count)
        Dim summaryDict As New Dictionary(Of String, Dictionary(Of String, Integer))()
        Dim runningcnt As Int16
        Dim sessionManager As QBSessionManager = Nothing
        Dim requestMsgSet As IMsgSetRequest

        runningcnt = 1
        For Each qbFileLocation In qbFileLocations

            Dim clientName As String = If(fileToClient.ContainsKey(qbFileLocation), fileToClient(qbFileLocation), Path.GetFileNameWithoutExtension(qbFileLocation))

            'lbl_process_qbmsg.Text = "Processing Qb file " & clientName
            Cursor.Current = Cursors.WaitCursor
            ShowStatus("Processing Qb file " & clientName)

            Try
                sessionManager = New QBSessionManager()
                sessionManager.OpenConnection("", "Mooneem App")
                sessionManager.BeginSession(qbFileLocation, ENOpenMode.omDontCare)
                requestMsgSet = sessionManager.CreateMsgSetRequest("US", 8, 0)
                requestMsgSet.Attributes.OnError = ENRqOnError.roeContinue

                Dim reportRq As IGeneralDetailReportQuery = requestMsgSet.AppendGeneralDetailReportQueryRq
                reportRq.GeneralDetailReportType.SetValue(ENGeneralDetailReportType.gdrtTxnListByDate)
                reportRq.ORReportPeriod.ReportPeriod.FromReportDate.SetValue(selectedStartDate)
                reportRq.ORReportPeriod.ReportPeriod.ToReportDate.SetValue(selectedEndDate)
                reportRq.IncludeColumnList.Add(ENIncludeColumn.icTxnNumber)
                reportRq.IncludeColumnList.Add(ENIncludeColumn.icTxnType)
                reportRq.IncludeColumnList.Add(ENIncludeColumn.icModifiedTime)
                reportRq.IncludeColumnList.Add(ENIncludeColumn.icLastModifiedBy)
                reportRq.IncludeColumnList.Add(ENIncludeColumn.icDate)
                '  reportRq.IncludeColumnList.Add(ENIncludeColumn.icRefNumber)
                reportRq.IncludeColumnList.Add(ENIncludeColumn.icName)
                reportRq.IncludeColumnList.Add(ENIncludeColumn.icMemo)
                reportRq.IncludeColumnList.Add(ENIncludeColumn.icAccount)
                reportRq.IncludeColumnList.Add(ENIncludeColumn.icClearedStatus)
                reportRq.IncludeColumnList.Add(ENIncludeColumn.icSplitAccount)
                reportRq.IncludeColumnList.Add(ENIncludeColumn.icAmount)

                reportRq.IncludeColumnList.Add(ENIncludeColumn.icClass)

                Dim responseMsgSet As IMsgSetResponse = sessionManager.DoRequests(requestMsgSet)
                Dim response As IResponse = responseMsgSet.ResponseList.GetAt(0)

                If response.StatusCode = 0 Then
                    Dim reportRet As IReportRet = response.Detail
                    If reportRet IsNot Nothing Then
                        For i As Integer = 0 To reportRet.ReportData.ORReportDataList.Count - 1
                            Dim rowData As IORReportData = reportRet.ReportData.ORReportDataList.GetAt(i)
                            If rowData.DataRow IsNot Nothing Then

                                Dim txnDate As String = "", trans As String = "", modifiedDate As String = "", txnType As String = "", lastModifiedBy As String = ""
                                Dim memo As String = "", name As String = "", split As String = "", num As String = "", vname As String = "",
                                 clr As String = "", className As String = ""
                                Dim txnamt As Double = 0

                                Dim colMap As New Dictionary(Of String, String)

                                For cnt As Integer = 0 To rowData.DataRow.ColDataList.Count - 1
                                    Dim colData As IColData = rowData.DataRow.ColDataList.GetAt(cnt)
                                    Dim colID As String = If(colData.colID IsNot Nothing, colData.colID.GetValue(), "")
                                    Dim val As String = If(colData.value IsNot Nothing, colData.value.GetValue(), "")

                                    If Not String.IsNullOrEmpty(colID) Then
                                        colMap(colID) = val
                                    End If
                                Next

                                ' Now safely extract values using column IDs
                                trans = If(colMap.ContainsKey("2"), colMap("2"), "")              ' TxnNumber
                                txnType = If(colMap.ContainsKey("3"), colMap("3"), "")            ' TxnType
                                modifiedDate = If(colMap.ContainsKey("4"), colMap("4"), "")       ' ModifiedTime
                                lastModifiedBy = If(colMap.ContainsKey("5"), colMap("5"), "")     ' LastModifiedBy
                                txnDate = If(colMap.ContainsKey("6"), colMap("6"), "")            ' Date
                                ' num = If(colMap.ContainsKey("7"), colMap("7"), "")               ' Memo — safely handled!
                                'vname = If(colMap.ContainsKey("8"), colMap("8"), "")               ' Account
                                'memo = If(colMap.ContainsKey("9"), colMap("9"), "")               ' Memo — safely handled!
                                'name = If(colMap.ContainsKey("10"), colMap("10"), "")               ' Account
                                'clr = If(colMap.ContainsKey("11"), colMap("11"), "")               ' Memo — safely handled!
                                'split = If(colMap.ContainsKey("12"), colMap("12"), "")              ' SplitAccount

                                'If colMap.ContainsKey("13") Then
                                '    Double.TryParse(colMap("13"), txnamt)
                                'End If
                                'className = If(colMap.ContainsKey("14"), colMap("14"), "")               ' Memo — safely handled!
                                vname = If(colMap.ContainsKey("7"), colMap("7"), "")               ' Account
                                memo = If(colMap.ContainsKey("8"), colMap("8"), "")               ' Memo — safely handled!
                                name = If(colMap.ContainsKey("9"), colMap("9"), "")               ' Account
                                clr = If(colMap.ContainsKey("10"), colMap("10"), "")               ' Memo — safely handled!
                                split = If(colMap.ContainsKey("11"), colMap("11"), "")              ' SplitAccount

                                If colMap.ContainsKey("12") Then
                                    Double.TryParse(colMap("12"), txnamt)
                                End If
                                className = If(colMap.ContainsKey("13"), colMap("13"), "")

                                Dim txnDateTime As DateTime
                                If DateTime.TryParse(txnDate, txnDateTime) Then
                                    Dim monthName As String = txnDateTime.ToString("MMMM")
                                    Dim transValue As Object = trans
                                    Dim tempInt As Integer
                                    If Integer.TryParse(trans, tempInt) Then
                                        transValue = tempInt
                                    End If
                                    dataTable.Rows.Add(transValue, txnType, modifiedDate, lastModifiedBy, txnDateTime.ToString("yyyy-MM-dd"), vname,
                   memo, name, clr, split, txnamt, className, clientName)

                                    If Not summaryDict.ContainsKey(clientName) Then summaryDict(clientName) = New Dictionary(Of String, Integer)()
                                    If Not summaryDict(clientName).ContainsKey(monthName) Then summaryDict(clientName)(monthName) = 0
                                    summaryDict(clientName)(monthName) += 1
                                End If
                            End If
                        Next

                    End If
                Else
                    MessageBox.Show("Error in " & clientName & ": " & response.StatusMessage)
                End If




                requestMsgSet.ClearRequests()

                'sessionManager.EndSession()
                'sessionManager.CloseConnection()

            Catch ex As Exception
                MessageBox.Show("Error in file " & qbFileLocation & ": " & ex.Message)
            Finally
                'Try
                '    sessionManager.EndSession()
                '    sessionManager.CloseConnection()
                'Catch ex As Exception
                '    ' ignore
                'End Try
                If sessionManager IsNot Nothing Then
                    Try
                        sessionManager.EndSession()
                        sessionManager.CloseConnection()
                    Catch
                        ' Ignore cleanup errors
                    End Try
                End If
            End Try




        Next


        MsgBox("✅ Data fetched successfully for selected clients.", MsgBoxStyle.Information, "Done")
        HideStatus()
        Cursor.Current = Cursors.Default
        '' Grid #1: Full transaction detail
        dgvOutput.DataSource = dataTable


        ' Grid #2: Flattened pivot-style summary

        Dim summaryTable As New DataTable()
        summaryTable.Columns.Add("Row Labels", GetType(String))
        summaryTable.Columns.Add("Count of Date", GetType(Integer))

        Dim grandTotal As Integer = 0

        For Each client In summaryDict.Keys
            summaryTable.Rows.Add(client, DBNull.Value)

            Dim clientTotal As Integer = 0
            Dim monthDict = summaryDict(client)
            For Each m In monthDict.Keys.OrderBy(Function(k) DateTime.ParseExact(k, "MMMM", Nothing))
                Dim count = monthDict(m)
                summaryTable.Rows.Add("   " & m, count)
                clientTotal += count
            Next

            summaryTable.Rows.Add(client & " Total", clientTotal)
            grandTotal += clientTotal
        Next

        ' Add Grand Total row
        summaryTable.Rows.Add("Grand Total", grandTotal)

        ' Bind to DataGridView
        dgvSummary.DataSource = summaryTable
        dgvSummary.AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.None
        dgvSummary.Columns("Row Labels").Width = 250
        dgvSummary.Columns("Count of Date").Width = 230



        ' Prepare the new summary table
        Dim summaryTableTwo As New DataTable()
        summaryTableTwo.Columns.Add("Row Data", GetType(String))
        summaryTableTwo.Columns.Add("Count of Transaction", GetType(Integer))
        summaryTableTwo.Columns.Add("Calculated Column", GetType(String)) ' Third column for formula
        Dim newgrandTotal As Integer = 0

        ' Use start date's year as selected year (can be adjusted if needed)
        Dim selectedYear As String = selectedStartDate.Year.ToString()

        ' First row: Total on top
        For Each client In summaryDict.Keys
            Dim clientMonthCounts = summaryDict(client)
            For Each m In clientMonthCounts.Keys
                newgrandTotal += clientMonthCounts(m)
            Next
        Next
        summaryTableTwo.Rows.Add("Total", newgrandTotal)

        ' Second row: Year header
        summaryTableTwo.Rows.Add("Row Labels", selectedYear)

        ' Grouped data by client
        For Each client In summaryDict.Keys
            summaryTableTwo.Rows.Add(client, DBNull.Value)

            Dim clientTotal As Integer = 0
            Dim monthDict = summaryDict(client)
            For Each m In monthDict.Keys.OrderBy(Function(k) DateTime.ParseExact(k, "MMMM", Nothing))
                Dim count = monthDict(m)
                summaryTableTwo.Rows.Add("   " & m, count)
                clientTotal += count
            Next

            summaryTableTwo.Rows.Add(client & " Total", clientTotal)
        Next


        ' Bind to the third DataGridView
        dgvSummarytwo.DataSource = summaryTableTwo
        dgvSummarytwo.AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.None
        dgvSummarytwo.Columns("Row Data").Width = 250
        dgvSummarytwo.Columns("Count of Transaction").Width = 230
        dgvSummarytwo.Columns("Calculated Column").Visible = False
    End Sub

    Private Sub ExportToExcel(sender As Object, e As EventArgs)
        If dgvOutput.Rows.Count = 0 Then
            MessageBox.Show("No data to export.", "Export", MessageBoxButtons.OK, MessageBoxIcon.Information)
            Return
        End If



        'Dim sfd As New SaveFileDialog()
        Dim saveFileDialog As New SaveFileDialog()
        saveFileDialog.Filter = "Excel Files (.xlsx)|.xlsx"

        Dim randomNumber As String = New Random().Next(100000, 999999).ToString()
        Dim defaultFileName As String = $"Transaction_Summary_Report {randomNumber}.xlsx"

        saveFileDialog.FileName = defaultFileName

        If saveFileDialog.ShowDialog() = DialogResult.OK Then
            Try
                Dim preview As New System.Text.StringBuilder()

                ' Add headers
                For Each column As DataGridViewColumn In dgvOutput.Columns
                    preview.Append(column.HeaderText & vbTab)
                Next
                preview.AppendLine()

                ' Add row data
                For Each row As DataGridViewRow In dgvOutput.Rows
                    If Not row.IsNewRow Then
                        For Each cell As DataGridViewCell In row.Cells
                            preview.Append(cell.Value?.ToString() & vbTab)
                        Next
                        preview.AppendLine()
                    End If
                Next

                'MessageBox.Show(preview.ToString(), "Preview Data from QuickBooks", MessageBoxButtons.OK, MessageBoxIcon.Information)

                OfficeOpenXml.ExcelPackage.LicenseContext = OfficeOpenXml.LicenseContext.NonCommercial

                Using package As New ExcelPackage()
                    ' Sheet 1: Full transaction data
                    Dim ws1 = package.Workbook.Worksheets.Add("Data")

                    ExportDataGridViewToWorksheet(dgvOutput, ws1)

                    ' Sheet 2: Summary data
                    Dim ws2 = package.Workbook.Worksheets.Add("Counts")
                    NewExportDataGridViewToWorksheet(dgvSummary, ws2)

                    ' Sheet 3: Summary with formulas
                    Dim ws3 = package.Workbook.Worksheets.Add("Invoice Working")
                    ExportSummaryTwoWithFormulas(dgvSummarytwo, ws3)

                    ' Save to file
                    Dim fi As New FileInfo(saveFileDialog.FileName)
                    package.SaveAs(fi)

                    MessageBox.Show("Excel file saved successfully.", "Export Complete", MessageBoxButtons.OK, MessageBoxIcon.Information)
                End Using
            Catch ex As Exception
                MessageBox.Show("Error while saving Excel: " & ex.Message, "Export Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
            End Try
        End If
    End Sub


    Private Sub ExportDataGridViewToWorksheet(dgv As DataGridView, ws As ExcelWorksheet)
        ' Write headers
        For col = 0 To dgv.Columns.Count - 1
            ws.Cells(1, col + 1).Value = dgv.Columns(col).HeaderText
            ws.Cells(1, col + 1).Style.Font.Bold = True
            ws.Cells(1, col + 1).Style.HorizontalAlignment = OfficeOpenXml.Style.ExcelHorizontalAlignment.Left
        Next

        ' Write data rows
        For row = 0 To dgv.Rows.Count - 1
            If Not dgv.Rows(row).IsNewRow Then
                For col = 0 To dgv.Columns.Count - 1
                    ws.Cells(row + 2, col + 1).Value = dgv.Rows(row).Cells(col).Value
                    ws.Cells(row + 2, col + 1).Style.HorizontalAlignment = OfficeOpenXml.Style.ExcelHorizontalAlignment.Left
                Next
            End If
        Next

        ' Auto-fit columns
        ws.Cells.AutoFitColumns()
    End Sub

    Private Sub NewExportDataGridViewToWorksheet(dgv As DataGridView, ws As ExcelWorksheet)
        ' Write headers
        For col = 0 To dgv.Columns.Count - 1
            ws.Cells(1, col + 1).Value = dgv.Columns(col).HeaderText
            ws.Cells(1, col + 1).Style.Font.Bold = True

        Next

        ' Write data rows
        For row = 0 To dgv.Rows.Count - 1
            If Not dgv.Rows(row).IsNewRow Then
                For col = 0 To dgv.Columns.Count - 1
                    ws.Cells(row + 2, col + 1).Value = dgv.Rows(row).Cells(col).Value

                Next
            End If
        Next

        ' Auto-fit columns
        ws.Cells.AutoFitColumns()
    End Sub





    Private Sub ExportSummaryTwoWithFormulas(dgv As DataGridView, ws As ExcelWorksheet)


        ' Write headers
        'For col = 0 To dgv.Columns.Count - 1
        '    MessageBox.Show("Header: " & dgv.Columns(col).HeaderText)
        '    ws.Cells(1, col + 1).Value = dgv.Columns(col).HeaderText
        '    ws.Cells(1, col + 1).Style.Font.Bold = True
        'Next

        Dim currentExcelRow As Integer = 2
        Dim calculatedRows As New List(Of Integer)() ' For grand total
        Dim groupStartRow As Integer = -1

        For row = 0 To dgv.Rows.Count - 1
            If dgv.Rows(row).IsNewRow Then Continue For

            Dim rowLabel As String = dgv.Rows(row).Cells(0).Value?.ToString()?.TrimEnd()
            Dim value = dgv.Rows(row).Cells(1).Value


            ' Column A and B
            ws.Cells(currentExcelRow, 1).Value = rowLabel
            ws.Cells(currentExcelRow, 2).Value = value
            ws.Row(1).Hidden = True
            ' Column C logic
            If rowLabel = "Total" Then
                ' Placeholder — fill formula later
            ElseIf rowLabel = "Row Labels" OrElse String.IsNullOrWhiteSpace(rowLabel) Then
                ' Header rows — skip
            ElseIf rowLabel.EndsWith("Total") Then
                ' Client total row – insert formula from groupStartRow to currentExcelRow - 1
                If groupStartRow > 0 AndAlso groupStartRow < currentExcelRow Then
                    ws.Cells(currentExcelRow, 3).Formula = $"=SUM(C{groupStartRow}:C{currentExcelRow - 1})"
                End If
                groupStartRow = -1 ' reset
            ElseIf dgv.Rows(row).Cells(0).Value.ToString().StartsWith("   ") Then
                ' Indented month row — apply formula
                ws.Cells(currentExcelRow, 3).Formula = $"=IF(B{currentExcelRow}>200, (175 + ((INT((B{currentExcelRow}-200)/50.001))+1)*45), 175)"
                calculatedRows.Add(currentExcelRow)
                If groupStartRow = -1 Then groupStartRow = currentExcelRow
            Else
                ' Unindented client name row — skip formula
                ws.Cells(currentExcelRow, 3).Value = ""
            End If

            currentExcelRow += 1
        Next

        ' Final grand total in row 2, column C
        If calculatedRows.Count > 0 Then
            Dim firstRow = calculatedRows.Min()
            Dim lastRow = calculatedRows.Max()
            ws.Cells(2, 3).Formula = $"=SUM(C{firstRow}:C{lastRow})/2"
        End If

        ' Auto-fit
        ws.Cells.AutoFitColumns()
    End Sub




End Class
