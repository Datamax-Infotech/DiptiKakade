
Imports FontAwesome.Sharp
Imports System.Drawing
Imports System.Windows.Forms

Public Class frmMain
    Inherits Form

    ' ===== fields ==============================================================
    Private ReadOnly sidebar As Panel
    Private ReadOnly pnlLogo As Panel
    Private ReadOnly btnToggle As IconButton
    Private ReadOnly btnDashboard As IconButton
    Private ReadOnly btnReports As IconButton
    Private ReadOnly btnClients As IconButton      ' NEW parent‑menu
    Private ReadOnly btnSetUp As IconButton
    Private ReadOnly btnTransactions As IconButton
    Private ReadOnly btnUtilities As IconButton
    Private ReadOnly setupContainer As Panel
    Private ReadOnly btnAddAppInQB As IconButton  ' NEW parent‑menu
    Private ReadOnly indicator As Panel
    Private ReadOnly btnInsertInvoice As IconButton
    Private ReadOnly btnUpdInvoice As IconButton
    Private ReadOnly btnUCBZWConsolidated As IconButton
    Private ReadOnly btnUpdateUCBZWConsolidated As IconButton
    Private ReadOnly btnUpdateZW As IconButton
    Private ReadOnly btnUCBZWVZ As IconButton
    Private ReadOnly btnJVEntry As IconButton
    Private ReadOnly btnUCBEnteringPayment As IconButton
    Private ReadOnly btnDeleteTxn As IconButton
    Private ReadOnly btnInsertTxn As IconButton
    Private ReadOnly utilitiesContainer As Panel
    Private ReadOnly txnContainer As Panel
    Private ReadOnly reportsContainer As Panel           ' ▼ Reports dropdown
    Private ReadOnly btnBalanceSheet As IconButton
    Private ReadOnly btnProfitLoss As IconButton
    Private ReadOnly btnTxnSummary As IconButton
    Private ReadOnly btnTxnCount As IconButton
    Private ReadOnly btnTrinityLogistics As IconButton

    Private ReadOnly lblLogo As Label

    Private ReadOnly clientsContainer As Panel           ' ▼ Clients dropdown
    Private ReadOnly btnAddEditClient As IconButton
    Private ReadOnly btnQBMapping As IconButton

    Private ReadOnly header As Panel
    Private ReadOnly lblTitle As Label
    Private ReadOnly content As Panel
    Private currentButton As IconButton
    Private ReadOnly btnIntegrations As IconButton
    Private ReadOnly integrationsContainer As Panel
    Private ReadOnly btnQBBackup As IconButton
    Private ReadOnly btnBillcom As IconButton
    Private ReadOnly btnOneDriveUpload As IconButton
    Private activeContainer As Panel = Nothing

    ' ===== helper =============================================================
    Private Function MakeButton(text As String, ic As IconChar) As IconButton
        Dim b As New IconButton With {
            .Text = text,
            .IconChar = ic,
            .IconColor = Color.Gainsboro,
            .IconSize = 22,
            .ImageAlign = ContentAlignment.MiddleLeft,
            .TextAlign = ContentAlignment.MiddleLeft,
            .TextImageRelation = TextImageRelation.ImageBeforeText,
            .Font = New Font("Verdana", 10, FontStyle.Bold),
            .ForeColor = Color.Gainsboro,
            .Dock = DockStyle.Top,
            .Height = 52,
            .FlatStyle = FlatStyle.Flat,
            .Padding = New Padding(20, 0, 10, 0),
            .AutoSize = False
        }
        b.FlatAppearance.BorderSize = 0
        Return b
    End Function



    Public Sub New()
        ' ---- form -------------------------------------------------------------
        Me.Text = "Modern Dashboard"
        'Me.MinimumSize = New Size(1600, 900)
        Me.StartPosition = FormStartPosition.CenterScreen
        Me.FormBorderStyle = FormBorderStyle.FixedSingle
        Me.MaximizeBox = True
        Me.WindowState = FormWindowState.Maximized

        ' ---- sidebar shell ----------------------------------------------------
        sidebar = New Panel With {
        .BackColor = Color.FromArgb(31, 30, 68),
        .Dock = DockStyle.Left,
        .Width = 345
    }

        ' ---- logo panel -------------------------------------------------------
        lblLogo = New Label With {
        .Text = "Mooneem App",
        .Dock = DockStyle.Fill,
        .TextAlign = ContentAlignment.MiddleCenter,
        .ForeColor = Color.White,
        .Font = New Font("Verdana", 17, FontStyle.Bold)
    }
        pnlLogo = New Panel With {.Height = 80, .Dock = DockStyle.Top}
        pnlLogo.Controls.Add(lblLogo)

        ' ---- top‑level buttons ------------------------------------------------
        btnToggle = MakeButton("Toggle", IconChar.Bars) : btnToggle.Name = "btnToggle"
        btnDashboard = MakeButton("Dashboard", IconChar.ChartColumn) : btnDashboard.Name = "btnDashboard"
        btnReports = MakeButton("Reports ▸", IconChar.FileInvoiceDollar) : btnReports.Name = "btnReports"
        btnClients = MakeButton("Clients ▸", IconChar.Users) : btnClients.Name = "btnClients"
        btnSetUp = MakeButton("Setup ▸", IconChar.Tools) : btnSetUp.Name = "btnSetup"
        btnUtilities = MakeButton("Utilities ▸", IconChar.Cogs) : btnUtilities.Name = "btnUtilities"
        btnTransactions = MakeButton("Transaction ▸", IconChar.MoneyBillTransfer) : btnTransactions.Name = "btnTransactions"
        btnIntegrations = MakeButton("Integrations ▸", IconChar.Plug) : btnIntegrations.Name = "btnIntegrations"

        ' ---- reports dropdown --------------------------------------------------
        reportsContainer = New Panel With {.Dock = DockStyle.Top, .BackColor = Color.FromArgb(35, 35, 80), .Visible = False, .Height = 52 * 4}
        btnBalanceSheet = MakeButton("General - Balance Sheet Summary", IconChar.FileAlt)
        btnProfitLoss = MakeButton("General - Profit && Loss Summary", IconChar.ChartBar)
        btnTxnSummary = MakeButton("General - Transaction Summary", IconChar.ListCheck)
        btnTxnCount = MakeButton("General - Transaction Count", IconChar.Calculator)
        reportsContainer.Controls.Add(btnTxnCount)
        reportsContainer.Controls.Add(btnTxnSummary)
        reportsContainer.Controls.Add(btnProfitLoss)
        reportsContainer.Controls.Add(btnBalanceSheet)

        ' ---- clients dropdown --------------------------------------------------
        clientsContainer = New Panel With {.Dock = DockStyle.Top, .BackColor = Color.FromArgb(35, 35, 80), .Visible = False, .Height = 52 * 2}
        btnAddEditClient = MakeButton("Add / Edit Client", IconChar.UserPen)
        btnQBMapping = MakeButton("QB Mapping", IconChar.TableList)
        clientsContainer.Controls.Add(btnQBMapping)
        clientsContainer.Controls.Add(btnAddEditClient)

        ' ---- setup dropdown ----------------------------------------------------
        setupContainer = New Panel With {.Dock = DockStyle.Top, .BackColor = Color.FromArgb(35, 35, 80), .Visible = False, .Height = 52}
        btnAddAppInQB = MakeButton("Add App in QB", IconChar.Plus)
        setupContainer.Controls.Add(btnAddAppInQB)

        ' ---- utilities dropdown ------------------------------------------------
        'utilitiesContainer = New Panel With {.Dock = DockStyle.Top, .BackColor = Color.FromArgb(35, 35, 80), .Visible = False, .Height = 52}
        'btnInsertInvoice = MakeButton("Insert Invoice", IconChar.FileInvoice)
        'utilitiesContainer.Controls.Add(btnInsertInvoice)
        ' ---- utilities dropdown ------------------------------------------------
        utilitiesContainer = New Panel With {.Dock = DockStyle.Top, .BackColor = Color.FromArgb(35, 35, 80), .Visible = False, .Height = 52 * 7}
        btnInsertInvoice = MakeButton("UCB - Insert Invoice", IconChar.FileInvoice)
        btnUPdInvoice = MakeButton("UCB - Update Invoice", IconChar.FileInvoice)
        btnUCBZWConsolidated = MakeButton("UCBZW - Consolidated Invoice", IconChar.FileInvoice)
        btnUpdateUCBZWConsolidated = MakeButton("UCBZW - Update Consolidated", IconChar.FileInvoice)
        btnUpdateZW = MakeButton("UCBZW - Update VZ", IconChar.FileInvoice)
        btnUCBZWVZ = MakeButton("UCBZW - VZ Invoice", IconChar.FileInvoiceDollar)
        btnUCBEnteringPayment = MakeButton("UCB - Entering Payment", IconChar.MoneyCheckDollar)
        btnTrinityLogistics = MakeButton("UCB - Trinity Logistics", IconChar.FileUpload)

        utilitiesContainer.Controls.Add(btnUpdateZW)
        utilitiesContainer.Controls.Add(btnUCBZWVZ)
        utilitiesContainer.Controls.Add(btnUpdateUCBZWConsolidated)
        utilitiesContainer.Controls.Add(btnUCBZWConsolidated)
        utilitiesContainer.Controls.Add(btnUpdInvoice)
        utilitiesContainer.Controls.Add(btnInsertInvoice)
        utilitiesContainer.Controls.Add(btnTrinityLogistics)


        ' ---- transactions dropdown ---------------------------------------------
        txnContainer = New Panel With {.Dock = DockStyle.Top, .BackColor = Color.FromArgb(35, 35, 80), .Visible = False, .Height = 52 * 2}
        btnInsertTxn = MakeButton("Insert Transaction", IconChar.PlusSquare)
        btnDeleteTxn = MakeButton("Delete Transaction", IconChar.Trash)
        txnContainer.Controls.Add(btnDeleteTxn)
        txnContainer.Controls.Add(btnInsertTxn)

        ' ---- integrations dropdown ---------------------------------------------
        integrationsContainer = New Panel With {.Dock = DockStyle.Top, .BackColor = Color.FromArgb(35, 35, 80), .Visible = False, .Height = 52 * 4}

        btnJVEntry = MakeButton("JV Entry", IconChar.FileAlt) ' New button
        btnQBBackup = MakeButton("Backup from QB", IconChar.Database)

        btnBillcom = MakeButton("Bill Site", IconChar.Database)
        btnOneDriveUpload = MakeButton("Upload to OneDrive", IconChar.CloudUploadAlt)
        integrationsContainer.Controls.Add(btnOneDriveUpload)
        integrationsContainer.Controls.Add(btnQBBackup)
        integrationsContainer.Controls.Add(btnBillcom)
        integrationsContainer.Controls.Add(btnJVEntry)


        ' -------------------------------------------------------
        ' Indent sub-menu buttons so they look like dropdown items
        For Each ctrl As Control In reportsContainer.Controls
            ctrl.Padding = New Padding(35, 0, 0, 0)
        Next
        For Each ctrl As Control In clientsContainer.Controls
            ctrl.Padding = New Padding(35, 0, 0, 0)
        Next
        For Each ctrl As Control In utilitiesContainer.Controls
            ctrl.Padding = New Padding(35, 0, 0, 0)
        Next
        For Each ctrl As Control In setupContainer.Controls
            ctrl.Padding = New Padding(35, 0, 0, 0)
        Next
        For Each ctrl As Control In txnContainer.Controls
            ctrl.Padding = New Padding(35, 0, 0, 0)
        Next
        For Each ctrl As Control In integrationsContainer.Controls
            ctrl.Padding = New Padding(35, 0, 0, 0)
        Next
        ' -------------------------------------------------------

        AddHandler btnDashboard.Click, AddressOf TopLevelClick
        AddHandler btnReports.Click, AddressOf TopLevelClick
        AddHandler btnClients.Click, AddressOf TopLevelClick
        AddHandler btnSetUp.Click, AddressOf TopLevelClick
        AddHandler btnUtilities.Click, AddressOf TopLevelClick
        AddHandler btnTransactions.Click, AddressOf TopLevelClick
        AddHandler btnIntegrations.Click, AddressOf TopLevelClick

        ' Toggle Button
        AddHandler btnToggle.Click, Sub(sender, e) ToggleSidebar(sender, e)

        ' Reports
        AddHandler btnBalanceSheet.Click, Sub(sender, e)
                                              ActivateButton(btnBalanceSheet)
                                              LoadControl(New BalanceSheet())
                                          End Sub

        AddHandler btnProfitLoss.Click, Sub(sender, e)
                                            ActivateButton(btnProfitLoss)
                                            LoadControl(New ProfitAndLoss())
                                        End Sub

        AddHandler btnTxnSummary.Click, Sub(sender, e)
                                            ActivateButton(btnTxnSummary)
                                            LoadControl(New TransactionSummary())
                                        End Sub

        AddHandler btnTxnCount.Click, Sub(sender, e)
                                          ActivateButton(btnTxnCount)
                                          LoadControl(New TransactionCountSummary())
                                      End Sub

        ' Clients
        AddHandler btnAddEditClient.Click, Sub(sender, e)
                                               ActivateButton(btnAddEditClient)
                                               LoadControl(New AddEditClient())
                                           End Sub

        AddHandler btnQBMapping.Click, Sub(sender, e)
                                           ActivateButton(btnQBMapping)
                                           LoadControl(New QBMappingEditor())
                                       End Sub

        ' Setup
        AddHandler btnAddAppInQB.Click, Sub(sender, e)
                                            ActivateButton(btnAddAppInQB)
                                            LoadControl(New SetupApp())
                                        End Sub

        ' Utilities
        AddHandler btnInsertInvoice.Click, Sub(sender, e)
                                               ActivateButton(btnInsertInvoice)
                                               LoadControl(New InsertInvoice())
                                           End Sub
        AddHandler btnUpdInvoice.Click, Sub(sender, e)
                                            ActivateButton(btnUpdInvoice)
                                            LoadControl(New UpdateInvoice())
                                        End Sub

        AddHandler btnUpdateUCBZWConsolidated.Click, Sub(sender, e)
                                                         ActivateButton(btnUpdateUCBZWConsolidated)
                                                         LoadControl(New UpdateConsolidatedInvoice())
                                                     End Sub
        'AddHandler btnUpdateZW.Click, Sub(sender, e)
        '                                  ActivateButton(btnUpdateZW)
        '                                  LoadControl(New UpdateVZInvoice())
        '                              End Sub

        AddHandler btnJVEntry.Click, Sub(sender, e)
                                         ActivateButton(btnJVEntry)
                                         LoadControl(New JVEntryControl()) ' This will be your new user control
                                     End Sub


        AddHandler btnTrinityLogistics.Click, Sub(sender, e)
                                                  ActivateButton(btnTrinityLogistics)
                                                  LoadControl(New TrinityLogisticsControl())
                                              End Sub

        ' Transactions
        AddHandler btnInsertTxn.Click, Sub(sender, e)
                                           ActivateButton(btnInsertTxn)
                                           LoadControl(New InsertTransaction())
                                       End Sub

        AddHandler btnDeleteTxn.Click, Sub(sender, e)
                                           ActivateButton(btnDeleteTxn)
                                           LoadControl(New DeleteTransaction())
                                       End Sub

        ' Integrations
        AddHandler btnQBBackup.Click, Sub(sender, e)
                                          ActivateButton(btnQBBackup)
                                          LoadControl(New BackupFromQB())
                                      End Sub

        AddHandler btnBillcom.Click, Sub(sender, e)
                                         ActivateButton(btnBillcom)
                                         LoadControl(New BillComHelper())
                                     End Sub

        AddHandler btnOneDriveUpload.Click, Sub(sender, e)
                                                ActivateButton(btnOneDriveUpload)
                                                LoadControl(New OneDriveUploader())
                                            End Sub
        ' Utilities - UCB Entering Payment
        AddHandler btnUCBEnteringPayment.Click, Sub(sender, e)
                                                    ActivateButton(btnUCBEnteringPayment)
                                                    LoadControl(New UCB_Entering_Payment()) ' Use your actual control class name here
                                                End Sub

        AddHandler btnUCBZWConsolidated.Click, Sub(sender, e)
                                                   ActivateButton(btnUCBZWConsolidated)
                                                   LoadControl(New UCBZW_ConsolidatedInvoiceControl())
                                               End Sub

        AddHandler btnUCBZWVZ.Click, Sub(sender, e)
                                         ActivateButton(btnUCBZWVZ)
                                         LoadControl(New VZ_waterInvoiceControl())
                                     End Sub

        ' ---- indicator --------------------------------------------------------
        indicator = New Panel With {.BackColor = Color.FromArgb(0, 150, 136), .Size = New Size(5, 52), .Visible = False}

        ' ---- header -----------------------------------------------------------
        header = New Panel With {.BackColor = Color.FromArgb(26, 25, 62), .Dock = DockStyle.Top, .Height = 60}
        lblTitle = New Label With {.Text = "Dashboard", .Dock = DockStyle.Fill, .TextAlign = ContentAlignment.MiddleLeft, .ForeColor = Color.White, .Font = New Font("Verdana", 14, FontStyle.Bold)}
        header.Controls.Add(lblTitle)

        ' ---- content ----------------------------------------------------------
        content = New Panel With {.Dock = DockStyle.Fill, .BackColor = Color.Gainsboro}

        ' ---- sidebar assembly -------------------------------------------------
        sidebar.Controls.Add(integrationsContainer)
        sidebar.Controls.Add(btnIntegrations)
        sidebar.Controls.Add(txnContainer)
        sidebar.Controls.Add(btnTransactions)
        sidebar.Controls.Add(utilitiesContainer)
        sidebar.Controls.Add(btnUtilities)
        sidebar.Controls.Add(setupContainer)
        sidebar.Controls.Add(btnSetUp)
        sidebar.Controls.Add(clientsContainer)
        sidebar.Controls.Add(btnClients)
        sidebar.Controls.Add(reportsContainer)
        sidebar.Controls.Add(btnReports)
        ' sidebar.Controls.Add(btnDashboard)
        sidebar.Controls.Add(btnToggle)
        sidebar.Controls.Add(pnlLogo)
        sidebar.Controls.Add(indicator)

        ' ---- form assembly ----------------------------------------------------
        Me.Controls.Add(content)
        Me.Controls.Add(header)
        Me.Controls.Add(sidebar)

        ' ---- default view -----------------------------------------------------
        ActivateButton(btnReports)
        LoadControl(New BalanceSheet())
    End Sub

    Private Sub ActivateButton(btn As IconButton)
        If currentButton IsNot Nothing Then
            currentButton.BackColor = Color.FromArgb(31, 30, 68)
            currentButton.ForeColor = Color.White
            currentButton.IconColor = Color.White
        End If
        currentButton = btn
        currentButton.BackColor = Color.FromArgb(37, 36, 81)
        currentButton.ForeColor = Color.FromArgb(0, 150, 136)
        currentButton.IconColor = Color.FromArgb(0, 150, 136)

        indicator.Visible = True
        indicator.Location = New Point(0, currentButton.Top)
        indicator.BringToFront()
        lblTitle.Text = currentButton.Text
    End Sub

    Private Sub TopLevelClick(sender As Object, e As EventArgs)
        Dim btn = CType(sender, IconButton)
        ActivateButton(btn)

        ' Find which submenu belongs to this button
        Dim clickedContainer As Panel = Nothing
        Select Case btn.Name
            Case btnReports.Name
                clickedContainer = reportsContainer
            Case btnClients.Name
                clickedContainer = clientsContainer
            Case btnSetUp.Name
                clickedContainer = setupContainer
            Case btnUtilities.Name
                clickedContainer = utilitiesContainer
            Case btnTransactions.Name
                clickedContainer = txnContainer
            Case btnIntegrations.Name
                clickedContainer = integrationsContainer
        End Select

        ' If dashboard, just load control and exit
        If btn.Name = btnDashboard.Name Then
            LoadControl(New ucHome())
            Exit Sub
        End If

        ' ✅ If same submenu is already visible → collapse it
        If clickedContainer IsNot Nothing AndAlso clickedContainer.Visible Then
            clickedContainer.Visible = False
            activeContainer = Nothing
            Exit Sub
        End If

        ' Otherwise → close all and open the new one
        reportsContainer.Visible = False
        clientsContainer.Visible = False
        setupContainer.Visible = False
        utilitiesContainer.Visible = False
        txnContainer.Visible = False
        integrationsContainer.Visible = False

        If clickedContainer IsNot Nothing Then
            clickedContainer.Visible = True
            activeContainer = clickedContainer
        End If
    End Sub


    Private Sub ToggleSidebar(sender As Object, e As EventArgs)
        If sidebar.Width > 60 Then
            ' Collapse
            sidebar.Width = 40
            lblLogo.Text = "📁"
            lblLogo.Font = New Font("Verdana", 17, FontStyle.Bold)

            For Each ctrl As Control In sidebar.Controls
                If TypeOf ctrl Is IconButton Then
                    Dim ib = CType(ctrl, IconButton)
                    If ib IsNot btnToggle Then
                        ib.Tag = ib.Text
                        ib.Text = ""
                        ib.Padding = New Padding(0)
                    Else
                        ib.Text = ""
                        ib.Padding = New Padding(0)
                    End If
                End If
            Next

            ' Also hide dropdown container if sidebar is collapsed
            If activeContainer IsNot Nothing Then
                activeContainer.Visible = False
            End If

        Else
            ' Expand
            sidebar.Width = 345
            lblLogo.Text = "Mooneem App"
            lblLogo.Font = New Font("Verdana", 15, FontStyle.Bold)

            For Each ctrl As Control In sidebar.Controls
                If TypeOf ctrl Is IconButton AndAlso ctrl IsNot btnToggle Then
                    Dim ib = CType(ctrl, IconButton)
                    If ib.Tag IsNot Nothing Then
                        ib.Text = ib.Tag.ToString()
                        ib.Padding = New Padding(10, 0, 10, 0)
                    End If
                End If
            Next

            btnToggle.Text = "Toggle"
            btnToggle.Padding = New Padding(10, 0, 10, 0)

            ' Restore the active dropdown
            If activeContainer IsNot Nothing Then
                activeContainer.Visible = True
            End If
        End If
    End Sub

    Private Sub LoadControl(ctrl As UserControl)
        content.Controls.Clear()
        ctrl.Dock = DockStyle.Fill
        content.Controls.Add(ctrl)
    End Sub
End Class

