' ====================================================================
'  TransactionCountSummary.vb   (add as UserControl)
' ====================================================================
Imports System.Data.OleDb
Imports FontAwesome.Sharp
Imports OfficeOpenXml
Imports OfficeOpenXml.Style
Imports QBFC12Lib
Imports System.IO
Imports System.Drawing
Imports System.Runtime.InteropServices   ' ← top of the file
Public Class TransactionCountSummary : Inherits UserControl
    ' ── DB connection (adjust once, reuse everywhere) ────────────────
    'Private ReadOnly connStr As String =
    '    "Provider=Microsoft.ACE.OLEDB.12.0;" &
    '    "Data Source=C:\Users\lenovo\Documents\MooneemDB.accdb;" &
    '    "Persist Security Info=False;"
    Private ReadOnly connStr As String = "Provider=Microsoft.ACE.OLEDB.12.0;Data Source=" + Application.StartupPath + "\MooneemDB.accdb;Persist Security Info=False;"

    ' ── controls ------------------------------------------------------
    Private clbClients As CheckedListBox
    Private cmbDateRange As ComboBox
    Private dtpFrom, dtpTo As DateTimePicker
    Private cmbViewType As ComboBox
    Private chkTxnByDate As CheckBox
    Private btnFetch, btnExport As Button
    Private dgvReport As DataGridView

    ' status strip
    Private pnlOverlay As Panel
    Private lblStatus As Label

    Public Sub New()
        InitializeComponent()
        BuildUI()           ' ← create UI
        LoadClients()       ' ← fill clients list
    End Sub

    '──────────────────────────────────────────────────────────────────
    ' UI LAYOUT
    '──────────────────────────────────────────────────────────────────
    Private Sub BuildUI()

        Me.Dock = DockStyle.Fill
        Me.BackColor = Color.White

        ' ========== ❶ TWO-ROW TOP AREA (TableLayout) ======================
        Dim tlpTop As New TableLayoutPanel With {
        .Dock = DockStyle.Top,
        .AutoSize = True,
        .ColumnCount = 2,
        .RowCount = 2,
        .Padding = New Padding(15)
    }
        tlpTop.ColumnStyles.Add(New ColumnStyle(SizeType.Absolute, 300))   ' Left column (CheckList)
        tlpTop.ColumnStyles.Add(New ColumnStyle(SizeType.Percent, 100))    ' Right column (controls)
        tlpTop.RowStyles.Add(New RowStyle(SizeType.AutoSize))
        tlpTop.RowStyles.Add(New RowStyle(SizeType.AutoSize))

        ' ▶ Clients checklist (left column)
        clbClients = New CheckedListBox With {
        .CheckOnClick = True,
        .Font = New Font("Verdana", 10),
        .Size = New Size(280, 110)
    }
        tlpTop.Controls.Add(clbClients, 0, 0)
        tlpTop.SetRowSpan(clbClients, 2)

        ' ▶ RIGHT Column — Row 1: Date Range, View Type, Radio Button
        Dim pnlRow1 As New FlowLayoutPanel With {
        .Dock = DockStyle.Fill,
        .AutoSize = True,
        .FlowDirection = FlowDirection.LeftToRight,
        .WrapContents = False,
        .Padding = New Padding(0, 0, 0, 0)
    }

        pnlRow1.Controls.Add(MakeLabel("Date range:"))
        cmbDateRange = New ComboBox With {
        .DropDownStyle = ComboBoxStyle.DropDownList,
        .Font = New Font("Verdana", 10),
        .Width = 200
    }
        cmbDateRange.Items.AddRange({
        "Today", "Yesterday",
        "This Week", "This Week-to-date",
        "Last Week", "Last Week-to-date",
        "This Month", "This Month-to-date",
        "Last Month", "Last Month-to-date",
        "This Fiscal Quarter", "This Fiscal Quarter-to-date",
        "Last Fiscal Quarter", "Last Fiscal Quarter-to-date",
        "This Fiscal Year", "This Fiscal Year-to-date",
        "Last Fiscal Year", "Last Fiscal Year-to-date",
        "Next Week", "Next 4 Weeks", "Next Month",
        "Next Fiscal Quarter", "Next Fiscal Year",
        "Custom"
    })
        cmbDateRange.SelectedIndex = 0
        pnlRow1.Controls.Add(cmbDateRange)

        pnlRow1.Controls.Add(MakeLabel("View type:"))
        cmbViewType = New ComboBox With {
        .DropDownStyle = ComboBoxStyle.DropDownList,
        .Font = New Font("Verdana", 10),
        .Width = 130
    }

        cmbViewType.Items.AddRange({"Total Only", "Day", "Month", "Quarter", "Year"})
        cmbViewType.SelectedIndex = 0
        pnlRow1.Controls.Add(cmbViewType)


        chkTxnByDate = New CheckBox With {
            .Text = "Transaction list by date",
            .Checked = False,
            .Font = New Font("Verdana", 10),
            .AutoSize = True,
            .Margin = New Padding(25, 5, 0, 0)
        }
        pnlRow1.Controls.Add(chkTxnByDate)
        tlpTop.Controls.Add(pnlRow1, 1, 0)

        ' ▶ RIGHT Column — Row 2: From / To dates, buttons
        Dim pnlRow2 As New FlowLayoutPanel With {
        .Dock = DockStyle.Fill,
        .AutoSize = True,
        .FlowDirection = FlowDirection.LeftToRight,
        .WrapContents = False,
        .Padding = New Padding(0, 6, 0, 2)
    }

        dtpFrom = New DateTimePicker With {
        .Format = DateTimePickerFormat.Short,
        .Enabled = False,
        .Font = New Font("Verdana", 10),
         .Width = 130
    }
        dtpTo = New DateTimePicker With {
        .Format = DateTimePickerFormat.Short,
        .Enabled = False,
        .Font = New Font("Verdana", 10),
         .Width = 130
    }

        pnlRow2.Controls.Add(MakeLabel("From:"))
        pnlRow2.Controls.Add(dtpFrom)
        pnlRow2.Controls.Add(MakeLabel("To:"))
        pnlRow2.Controls.Add(dtpTo)

        'btnFetch = MakeButton("📊 Fetch Report", Color.FromArgb(0, 150, 136))
        'btnExport = MakeButton("📥 Export to Excel", Color.FromArgb(63, 81, 181))

        Dim primaryColor = Color.FromArgb(0, 150, 136)
        Dim secondaryColor = Color.FromArgb(37, 36, 81)
        btnFetch = New Button With {
           .Text = "Fetch Report",
           .Width = 160,
           .Height = 33,
           .Font = New Font("Verdana", 10, FontStyle.Bold),
           .BackColor = Color.Transparent,
           .ForeColor = primaryColor,
           .FlatStyle = FlatStyle.Flat
       }
        btnFetch.FlatAppearance.BorderColor = primaryColor
        btnFetch.FlatAppearance.BorderSize = 2
        btnFetch.Margin = New Padding(30, 3, 3, 3)  ' Add 30px left margin

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
            .Width = 170,
            .Height = 33,
            .Font = New Font("Verdana", 10, FontStyle.Bold),
            .BackColor = Color.Transparent,
            .ForeColor = secondaryColor,
            .FlatStyle = FlatStyle.Flat
        }
        btnExport.FlatAppearance.BorderColor = secondaryColor
        btnExport.FlatAppearance.BorderSize = 2
        btnExport.Margin = New Padding(10, 3, 3, 3)  ' Add 30px left margin
        AddHandler btnExport.MouseEnter, Sub()
                                             btnExport.BackColor = secondaryColor
                                             btnExport.ForeColor = Color.White
                                         End Sub
        AddHandler btnExport.MouseLeave, Sub()
                                             btnExport.BackColor = Color.Transparent
                                             btnExport.ForeColor = secondaryColor
                                         End Sub
        pnlRow2.Controls.Add(btnFetch)
        pnlRow2.Controls.Add(btnExport)

        tlpTop.Controls.Add(pnlRow2, 1, 1)

        ' ========== ❷ GRID ===========================================
        dgvReport = New DataGridView With {
        .Dock = DockStyle.Fill,
        .ReadOnly = True,
        .AllowUserToAddRows = False,
        .AllowUserToDeleteRows = False,
        .EnableHeadersVisualStyles = False,
        .AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.None, ' allow H-scroll
        .ScrollBars = ScrollBars.Both,
        .ColumnHeadersDefaultCellStyle = New DataGridViewCellStyle With {
            .BackColor = Color.FromArgb(31, 30, 68),
            .ForeColor = Color.White,
            .Font = New Font("Verdana", 10, FontStyle.Bold)
        },
        .Font = New Font("Verdana", 10),
        .RowTemplate = New DataGridViewRow With {.Height = 26},
        .BackgroundColor = Color.White
    }

        ' ========== ❸ STATUS OVERLAY =================================
        pnlOverlay = New Panel With {
        .Size = New Size(390, 40),
        .BackColor = Color.FromArgb(20, 0, 150, 136),
        .Visible = False,
        .Anchor = AnchorStyles.None
    }
        lblStatus = New Label With {
        .Dock = DockStyle.Fill,
        .TextAlign = ContentAlignment.MiddleCenter,
        .ForeColor = Color.Black,
        .Font = New Font("Verdana", 10, FontStyle.Bold)
    }
        pnlOverlay.Controls.Add(lblStatus)

        ' ========== assemble =========================================
        Me.Controls.Add(dgvReport)
        Me.Controls.Add(tlpTop)
        Me.Controls.Add(pnlOverlay)
        pnlOverlay.BringToFront()

        ' ========== events ===========================================
        AddHandler cmbDateRange.SelectedIndexChanged, AddressOf DateRangeChanged
        AddHandler btnFetch.Click, AddressOf FetchReport
        AddHandler btnExport.Click, AddressOf ExportToExcel

    End Sub


    '──────────────────────────────────────────────────────────────────
    ' helpers
    '──────────────────────────────────────────────────────────────────
    Private Shared Function MakeLabel(txt As String) As Label
        Return New Label With {
            .Text = txt,
            .Font = New Font("Verdana", 10),
            .AutoSize = True,
            .Margin = New Padding(5, 6, 4, 0)
        }
    End Function

    Private Shared Function MakeButton(txt As String, back As Color) As Button
        Dim b As New Button With {
            .Text = txt,
            .Font = New Font("Verdana", 10, FontStyle.Bold),
            .Width = 165, .Height = 30,
            .BackColor = back,
            .ForeColor = Color.White,
            .FlatStyle = FlatStyle.Flat,
            .Margin = New Padding(25, 1, 0, 0)
        }
        b.FlatAppearance.BorderSize = 0
        Return b
    End Function
    Public Class ClientInfo
        Public Property ClientName As String
        Public Property QBFilePath As String

        Public Sub New(name As String, path As String)
            ClientName = name
            QBFilePath = path
        End Sub

        Public Overrides Function ToString() As String
            Return ClientName
        End Function
    End Class

    Public Class ReportData
        Public Property TxnNumber As Object
        Public Property TxnType As String
        Public Property ModifiedDate As String
        Public Property LastModifiedBy As String
        Public Property Month As String
        Public Property IDate As String
        Public Property Account As String
        Public Property Memo As String
        Public Property Split As String
        Public Property Amount As Double
    End Class

    Public Class TransactionInfo
        Public Property TxnType As String
        Public Property IDate As DateTime
    End Class

    Public Class TransactionDetail
        Public Property TxnNum As String
        Public Property TxnType As String
        Public Property IDate As DateTime
        Public Property LastModifiedBy As String
        Public Property Num As String
        Public Property Name As String
        Public Property Memo As String
        Public Property Account As String
        Public Property Split As String
        Public Property Amount As Decimal
    End Class
    Private Sub LoadClients()

        Try
            Using conn As New OleDbConnection(connStr)
                conn.Open()
                Dim cmd As New OleDbCommand("SELECT ClientName, QBFilePath FROM GroupClientMapping", conn)
                Dim reader As OleDbDataReader = cmd.ExecuteReader()
                Dim clientList As New List(Of ClientInfo)()
                While reader.Read()
                    Dim clientName As String = reader("ClientName").ToString()
                    Dim qbFilePath As String = reader("QBFilePath").ToString()
                    'clbClients.Items.Add(New ClientInfo(clientName, qbFilePath))
                    clientList.Add(New ClientInfo(clientName, qbFilePath))
                End While

                reader.Close()

                clientList = clientList.OrderBy(Function(c) c.ClientName).ToList()

                ' 🔽 Add sorted clients to CheckedListBox
                For Each client In clientList
                    clbClients.Items.Add(client)
                Next
            End Using

            clbClients.Visible = clbClients.Items.Count > 0

        Catch ex As Exception
            MessageBox.Show("Error loading clients: " & ex.Message)
        End Try
    End Sub


    Private Sub DateRangeChanged(sender As Object, e As EventArgs)
        Dim isCustom = cmbDateRange.Text = "Custom"
        dtpFrom.Enabled = isCustom
        dtpTo.Enabled = isCustom
    End Sub



    Private Sub FetchReport(sender As Object, e As EventArgs)
        Dim fromDate As DateTime
        Dim toDate As DateTime
        Dim today As Date = Date.Today
        Dim startOfWeek As Date = today.AddDays(-(CInt(today.DayOfWeek)))
        Dim fiscalYearStart As DateTime = New DateTime(today.Year, 1, 1)
        Dim fiscalQuarterStart As DateTime

        ' Date Range Selection (your original code remains unchanged)
        Select Case cmbDateRange.SelectedItem.ToString()
            Case "Today"
                fromDate = today : toDate = today
            Case "Yesterday"
                fromDate = today.AddDays(-1) : toDate = fromDate
            Case "This Week"
                fromDate = startOfWeek : toDate = startOfWeek.AddDays(6)
            Case "This Week-to-date"
                fromDate = startOfWeek : toDate = today
            Case "Last Week"
                fromDate = startOfWeek.AddDays(-7) : toDate = startOfWeek.AddDays(-1)
            Case "Last Week-to-date"
                fromDate = startOfWeek.AddDays(-7) : toDate = today.AddDays(-1)
            Case "This Month"
                fromDate = New DateTime(today.Year, today.Month, 1)
                toDate = fromDate.AddMonths(1).AddDays(-1)
            Case "This Month-to-date"
                fromDate = New DateTime(today.Year, today.Month, 1)
                toDate = today
            Case "Last Month"
                fromDate = New DateTime(today.Year, today.Month, 1).AddMonths(-1)
                toDate = fromDate.AddMonths(1).AddDays(-1)
            Case "Last Month-to-date"
                fromDate = New DateTime(today.Year, today.Month, 1).AddMonths(-1)
                toDate = fromDate.AddDays(today.Day - 1)
            Case "This Fiscal Quarter"
                fiscalQuarterStart = New DateTime(today.Year, (((today.Month - 1) \ 3) * 3) + 1, 1)
                fromDate = fiscalQuarterStart
                toDate = fiscalQuarterStart.AddMonths(3).AddDays(-1)
            Case "This Fiscal Quarter-to-date"
                fiscalQuarterStart = New DateTime(today.Year, (((today.Month - 1) \ 3) * 3) + 1, 1)
                fromDate = fiscalQuarterStart
                toDate = today
            Case "Last Fiscal Quarter"
                fiscalQuarterStart = New DateTime(today.Year, (((today.Month - 1) \ 3) * 3) + 1, 1).AddMonths(-3)
                fromDate = fiscalQuarterStart
                toDate = fromDate.AddMonths(3).AddDays(-1)
            Case "Last Fiscal Quarter-to-date"
                fiscalQuarterStart = New DateTime(today.Year, (((today.Month - 1) \ 3) * 3) + 1, 1).AddMonths(-3)
                fromDate = fiscalQuarterStart
                toDate = today
            Case "This Fiscal Year"
                fromDate = fiscalYearStart : toDate = New DateTime(today.Year, 12, 31)
            Case "This Fiscal Year-to-date"
                fromDate = fiscalYearStart : toDate = today
            Case "Last Fiscal Year"
                fromDate = New DateTime(today.Year - 1, 1, 1)
                toDate = New DateTime(today.Year - 1, 12, 31)
            Case "Last Fiscal Year-to-date"
                fromDate = New DateTime(today.Year - 1, 1, 1)
                toDate = New DateTime(today.Year - 1, today.Month, today.Day)
            Case "Next Week"
                fromDate = startOfWeek.AddDays(7) : toDate = fromDate.AddDays(6)
            Case "Next 4 Weeks"
                fromDate = today : toDate = today.AddDays(27)
            Case "Next Month"
                fromDate = New DateTime(today.Year, today.Month, 1).AddMonths(1)
                toDate = fromDate.AddMonths(1).AddDays(-1)
            Case "Next Fiscal Quarter"
                fiscalQuarterStart = New DateTime(today.Year, (((today.Month - 1) \ 3) * 3) + 1, 1).AddMonths(3)
                fromDate = fiscalQuarterStart
                toDate = fromDate.AddMonths(3).AddDays(-1)
            Case "Next Fiscal Year"
                fromDate = New DateTime(today.Year + 1, 1, 1)
                toDate = New DateTime(today.Year + 1, 12, 31)
            Case Else
                fromDate = dtpFrom.Value.Date
                toDate = dtpTo.Value.Date
        End Select

        If fromDate > toDate Then
            MessageBox.Show("From date must be before To date.")
            Exit Sub
        End If

        ' Check if detailed checkbox is checked
        If chkTxnByDate.Checked Then
            GenerateDetailedReport(fromDate, toDate)
        Else
            GenerateSummaryReport(fromDate, toDate)
        End If
        ' lblProcessingFile.Text = "Done."
        HideStatus()
        Cursor.Current = Cursors.Default
    End Sub
    Private Function GetTransactionList(qbFilePath As String, fromDate As DateTime, toDate As DateTime) As List(Of ReportData)
        Dim reportList As New List(Of ReportData)()
        Dim sessionManager As QBSessionManager = Nothing

        Try
            ' Open connection and begin session on QB file
            sessionManager = New QBSessionManager()
            sessionManager.OpenConnection("", "Mooneem App")
            sessionManager.BeginSession(qbFilePath, ENOpenMode.omDontCare)

            ' Create request message set for US locale, QuickBooks version 8.0
            Dim requestMsgSet As IMsgSetRequest = sessionManager.CreateMsgSetRequest("US", 12, 0)
            requestMsgSet.Attributes.OnError = ENRqOnError.roeContinue

            ' Append General Detail Report Query request
            Dim reportRq As IGeneralDetailReportQuery = requestMsgSet.AppendGeneralDetailReportQueryRq()
            reportRq.GeneralDetailReportType.SetValue(ENGeneralDetailReportType.gdrtTxnListByDate)

            ' Set report period (date range)
            reportRq.ORReportPeriod.ReportPeriod.FromReportDate.SetValue(fromDate)
            reportRq.ORReportPeriod.ReportPeriod.ToReportDate.SetValue(toDate)

            ' Specify columns to include
            reportRq.IncludeColumnList.Add(ENIncludeColumn.icTxnNumber)
            reportRq.IncludeColumnList.Add(ENIncludeColumn.icTxnType)
            reportRq.IncludeColumnList.Add(ENIncludeColumn.icModifiedTime)
            reportRq.IncludeColumnList.Add(ENIncludeColumn.icLastModifiedBy)
            reportRq.IncludeColumnList.Add(ENIncludeColumn.icDate)
            reportRq.IncludeColumnList.Add(ENIncludeColumn.icAccount)
            reportRq.IncludeColumnList.Add(ENIncludeColumn.icMemo)
            reportRq.IncludeColumnList.Add(ENIncludeColumn.icSplitAccount)
            reportRq.IncludeColumnList.Add(ENIncludeColumn.icAmount)

            ' Send request to QB
            Dim responseMsgSet As IMsgSetResponse = sessionManager.DoRequests(requestMsgSet)
            Dim response As IResponse = responseMsgSet.ResponseList.GetAt(0)

            ' Check response status
            If response.StatusCode = 0 Then
                Dim reportRet As IReportRet = CType(response.Detail, IReportRet)
                If reportRet IsNot Nothing AndAlso reportRet.ReportData IsNot Nothing Then
                    Dim rows = reportRet.ReportData.ORReportDataList
                    If rows IsNot Nothing Then
                        For i As Integer = 0 To rows.Count - 1
                            Dim rowData As IORReportData = rows.GetAt(i)

                            If rowData.DataRow IsNot Nothing Then
                                Dim colMap As New Dictionary(Of String, String)
                                For cnt As Integer = 0 To rowData.DataRow.ColDataList.Count - 1
                                    Dim colData As IColData = rowData.DataRow.ColDataList.GetAt(cnt)
                                    Dim colID As String = If(colData.colID IsNot Nothing, colData.colID.GetValue(), "")
                                    Dim val As String = If(colData.value IsNot Nothing, colData.value.GetValue(), "")
                                    If Not String.IsNullOrEmpty(colID) Then
                                        colMap(colID) = val
                                    End If
                                Next

                                Dim txnNumber As Object = If(colMap.ContainsKey("2"), colMap("2"), "")
                                Dim txnType As String = If(colMap.ContainsKey("3"), colMap("3"), "")
                                Dim modifiedDate As String = If(colMap.ContainsKey("4"), colMap("4"), "")
                                Dim lastModifiedBy As String = If(colMap.ContainsKey("5"), colMap("5"), "")
                                Dim txnDate As String = If(colMap.ContainsKey("6"), colMap("6"), "")
                                Dim account As String = If(colMap.ContainsKey("7"), colMap("7"), "")
                                Dim memo As String = If(colMap.ContainsKey("8"), colMap("8"), "")
                                Dim split As String = If(colMap.ContainsKey("9"), colMap("9"), "")
                                Dim amount As Double = 0

                                If colMap.ContainsKey("10") Then
                                    Double.TryParse(colMap("10"), amount)
                                End If

                                Dim txnDateTime As DateTime
                                If DateTime.TryParse(txnDate, txnDateTime) Then
                                    Dim monthName As String = txnDateTime.ToString("MMMM")

                                    ' If txnNumber is numeric string convert to Integer else keep as String
                                    Dim tempInt As Integer
                                    If Integer.TryParse(txnNumber.ToString(), tempInt) Then
                                        txnNumber = tempInt
                                    End If

                                    ' Add a new ReportData item to the list
                                    reportList.Add(New ReportData With {
                                    .TxnNumber = txnNumber,
                                    .TxnType = txnType,
                                    .ModifiedDate = modifiedDate,
                                    .LastModifiedBy = lastModifiedBy,
                                    .Month = monthName,
                                    .IDate = txnDateTime.ToString("yyyy-MM-dd"),
                                    .Account = account,
                                    .Memo = memo,
                                    .Split = split,
                                    .Amount = amount
                                })
                                End If
                            End If
                        Next
                    End If
                End If
            Else
                ' Optionally handle non-zero status code with a message
                MessageBox.Show($"QuickBooks SDK returned status code {response.StatusCode}: {response.StatusMessage}")
            End If

        Catch ex As Exception
            MessageBox.Show("Error accessing QuickBooks: " & ex.Message)
        Finally
            ' Always end session and close connection
            'Try
            '    sessionManager.EndSession()
            'Catch
            'End Try
            'Try
            '    sessionManager.CloseConnection()
            'Catch
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

        Return reportList
    End Function

    Private Function GetTransactionListcount(qbFilePath As String, fromDate As DateTime, toDate As DateTime) As List(Of TransactionInfo)
        Dim transactionList As New List(Of TransactionInfo)()
        Dim sessionManager As QBSessionManager = Nothing

        Try
            sessionManager = New QBSessionManager()
            sessionManager.OpenConnection("", "Mooneem App") 'QB SDK 
            sessionManager.BeginSession(qbFilePath, ENOpenMode.omDontCare)

            Dim requestMsgSet = sessionManager.CreateMsgSetRequest("US", 12, 0)
            requestMsgSet.Attributes.OnError = ENRqOnError.roeContinue

            Dim reportRq As IGeneralDetailReportQuery = requestMsgSet.AppendGeneralDetailReportQueryRq()
            reportRq.GeneralDetailReportType.SetValue(ENGeneralDetailReportType.gdrtTxnListByDate)
            reportRq.ORReportPeriod.ReportPeriod.FromReportDate.SetValue(fromDate)
            reportRq.ORReportPeriod.ReportPeriod.ToReportDate.SetValue(toDate)
            reportRq.IncludeColumnList.Add(ENIncludeColumn.icTxnType)
            reportRq.IncludeColumnList.Add(ENIncludeColumn.icDate)

            Dim responseMsgSet = sessionManager.DoRequests(requestMsgSet)
            Dim response = responseMsgSet.ResponseList.GetAt(0)

            If response.StatusCode = 0 Then
                Dim reportRet = CType(response.Detail, IReportRet)
                If reportRet IsNot Nothing AndAlso reportRet.ReportData IsNot Nothing Then
                    Dim rows = reportRet.ReportData.ORReportDataList
                    If rows IsNot Nothing Then
                        For i As Integer = 0 To rows.Count - 1
                            Dim rowData = rows.GetAt(i)
                            If rowData.DataRow IsNot Nothing Then
                                Dim txnType As String = ""
                                Dim txnDate As DateTime = Date.MinValue

                                For j As Integer = 0 To rowData.DataRow.ColDataList.Count - 1
                                    Dim col = rowData.DataRow.ColDataList.GetAt(j)
                                    Select Case col.colID.GetValue()
                                        Case "2" ' TxnType
                                            txnType = col.value.GetValue()
                                        Case "3" ' Date
                                            Date.TryParse(col.value.GetValue(), txnDate)
                                    End Select
                                Next

                                If txnDate <> Date.MinValue Then
                                    transactionList.Add(New TransactionInfo With {
                                    .TxnType = txnType,
                                    .IDate = txnDate
                                })
                                End If
                            End If
                        Next
                    End If
                End If
            End If

        Catch ex As Exception
            MessageBox.Show("QB Error: " & ex.Message)
        Finally
            'If sessionManager IsNot Nothing Then
            '    Try : sessionManager.EndSession() : Catch : End Try
            '    Try : sessionManager.CloseConnection() : Catch : End Try
            'End If
            If sessionManager IsNot Nothing Then
                Try
                    sessionManager.EndSession()
                    sessionManager.CloseConnection()
                Catch
                    ' Ignore cleanup errors
                End Try
            End If
        End Try

        Return transactionList
    End Function

    Private Sub GenerateSummaryReport(fromDate As DateTime, toDate As DateTime)
        Dim viewBy As String = cmbViewType.SelectedItem.ToString()
        Dim colList As New List(Of String)
        Dim tempDate = fromDate

        ' Build column list depending on viewBy
        Do While tempDate <= toDate
            Dim colKey As String = ""

            Select Case viewBy
                Case "Day"
                    colKey = tempDate.ToString("dd MMM yyyy")
                Case "Month"
                    colKey = tempDate.ToString("MMM yyyy")
                Case "Quarter"
                    Dim q = ((tempDate.Month - 1) \ 3) + 1
                    colKey = "Q" & q.ToString() & " " & tempDate.Year
                Case "Year"
                    colKey = tempDate.Year.ToString()
                Case "Total Only"
                    colKey = "Total"
            End Select

            If Not colList.Contains(colKey) Then
                colList.Add(colKey)
            End If

            If viewBy = "Total Only" Then Exit Do
            tempDate = tempDate.AddDays(1)
        Loop

        If viewBy <> "Total Only" Then
            colList.Add("Total")
        End If

        ' Build summary dictionary
        Dim summaryDict As New Dictionary(Of String, Dictionary(Of String, Integer))

        For Each item As ClientInfo In clbClients.CheckedItems
            'lblProcessingFile.Text = "Processing file: " & System.IO.Path.GetFileName(item.QBFilePath)
            'Application.DoEvents() ' Ensure UI updates immediately
            Cursor.Current = Cursors.WaitCursor
            ShowStatus("Processing Qb file " & System.IO.Path.GetFileName(item.QBFilePath))
            Dim reportList = GetTransactionListcount(item.QBFilePath, fromDate, toDate)

            For Each txn In reportList
                Dim txnType = txn.TxnType
                Dim txnDate = txn.IDate

                If Not summaryDict.ContainsKey(txnType) Then
                    summaryDict(txnType) = New Dictionary(Of String, Integer)
                End If

                If Not summaryDict(txnType).ContainsKey("Total") Then
                    summaryDict(txnType)("Total") = 0
                End If
                summaryDict(txnType)("Total") += 1

                If viewBy = "Total Only" Then Continue For

                Dim periodKey As String = ""
                Select Case viewBy
                    Case "Day"
                        periodKey = txnDate.ToString("dd MMM yyyy")
                    Case "Month"
                        periodKey = txnDate.ToString("MMM yyyy")
                    Case "Quarter"
                        Dim q = ((txnDate.Month - 1) \ 3) + 1
                        periodKey = "Q" & q.ToString() & " " & txnDate.Year
                    Case "Year"
                        periodKey = txnDate.Year.ToString()
                End Select

                If Not summaryDict(txnType).ContainsKey(periodKey) Then
                    summaryDict(txnType)(periodKey) = 0
                End If
                summaryDict(txnType)(periodKey) += 1
            Next
        Next

        ' Fill dgvReport
        dgvReport.Columns.Clear()
        dgvReport.Rows.Clear()

        Dim txnColIndex = dgvReport.Columns.Add("TxnType", "Transaction Type")
        For Each colName In colList
            dgvReport.Columns.Add(colName, colName)
        Next

        dgvReport.Columns(txnColIndex).Width = 190
        For i = txnColIndex + 1 To dgvReport.Columns.Count - 1
            dgvReport.Columns(i).Width = 145
        Next

        For Each txnType In summaryDict.Keys
            Dim row As New List(Of Object)
            row.Add(txnType)
            For Each colName In colList
                Dim count As Integer = If(summaryDict(txnType).ContainsKey(colName), summaryDict(txnType)(colName), 0)
                row.Add(count)
            Next
            dgvReport.Rows.Add(row.ToArray())
        Next
    End Sub

    Private Sub GenerateDetailedReport(fromDate As DateTime, toDate As DateTime)
        dgvReport.Columns.Clear()
        dgvReport.Rows.Clear()

        ' Define detailed columns
        Dim columns = New String() {
        "Trans #", "Type", "Entered/Last Modified", "Last modified by",
        "Month", "Date", "Memo", "Account", "Split",
        "Amount", "Client"
    }

        For Each colName In columns
            dgvReport.Columns.Add(colName, colName)
        Next

        ' Fetch and add data for each checked client
        For Each client As ClientInfo In clbClients.CheckedItems
            'lblProcessingFile.Text = "Processing file: " & System.IO.Path.GetFileName(client.QBFilePath)
            'Application.DoEvents()
            Cursor.Current = Cursors.WaitCursor
            ShowStatus("Processing Qb file " & System.IO.Path.GetFileName(client.QBFilePath))

            Dim txnList = GetTransactionList(client.QBFilePath, fromDate, toDate)
            For Each txn In txnList
                Dim row = New List(Of Object) From {
                txn.TxnNumber,
                txn.TxnType,
                txn.ModifiedDate, ' Assuming IDate holds Entered/Last Modified date
                txn.LastModifiedBy,
                txn.Month,
                txn.IDate,
                txn.Memo,
                txn.Account,
                txn.Split,
                txn.Amount,
                client.ClientName ' Assuming client name is client.Name
            }
                dgvReport.Rows.Add(row.ToArray())
            Next
        Next

        ' Optionally resize columns to fit content nicely
        dgvReport.AutoResizeColumns()
    End Sub





    Private Sub ExportToExcel(sender As Object, e As EventArgs)
        If dgvReport.Rows.Count = 0 Then
            MessageBox.Show("No data to export.")
            Return
        End If

        Using sfd As New SaveFileDialog()
            sfd.Filter = "Excel Files (*.xlsx)|*.xlsx"
            'sfd.FileName = "Report_" & DateTime.Now.ToString("yyyyMMdd_HHmmss") & ".xlsx"
            sfd.FileName = "Report_" & DateTime.Now.ToString("yyyyMMdd_HHmmss") & ".xlsx"

            If sfd.ShowDialog() = DialogResult.OK Then
                Dim filePath As String = sfd.FileName

                ExcelPackage.LicenseContext = LicenseContext.NonCommercial
                Using package As New ExcelPackage()
                    Dim ws = package.Workbook.Worksheets.Add("Report")

                    ' Add headers
                    For col = 0 To dgvReport.Columns.Count - 1
                        ws.Cells(1, col + 1).Value = dgvReport.Columns(col).HeaderText
                        ws.Cells(1, col + 1).Style.Font.Bold = True
                        ws.Cells(1, col + 1).Style.Fill.PatternType = ExcelFillStyle.Solid
                        ws.Cells(1, col + 1).Style.Fill.BackgroundColor.SetColor(Color.LightGray)
                        ws.Cells(1, col + 1).Style.HorizontalAlignment = ExcelHorizontalAlignment.Left ' Align header

                    Next

                    ' Add data rows
                    For row = 0 To dgvReport.Rows.Count - 1
                        For col = 0 To dgvReport.Columns.Count - 1
                            ws.Cells(row + 2, col + 1).Value = dgvReport.Rows(row).Cells(col).Value
                            ws.Cells(row + 2, col + 1).Style.HorizontalAlignment = ExcelHorizontalAlignment.Left

                        Next
                    Next

                    ' Auto-fit columns
                    ws.Cells.AutoFitColumns()

                    ' Save the file
                    Dim fileBytes = package.GetAsByteArray()
                    File.WriteAllBytes(filePath, fileBytes)
                    MessageBox.Show("Exported successfully to " & filePath)
                End Using
            End If
        End Using

    End Sub


    Private Sub ShowStatus(msg As String)
        lblStatus.Text = msg
        pnlOverlay.Left = (Me.Width - pnlOverlay.Width) \ 2
        pnlOverlay.Top = (Me.Height - pnlOverlay.Height) \ 2
        pnlOverlay.Visible = True
        pnlOverlay.BringToFront()
        Application.DoEvents()
    End Sub
    Private Sub HideStatus()
        pnlOverlay.Visible = False
    End Sub

End Class


