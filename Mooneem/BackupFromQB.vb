Imports System.ComponentModel
Imports System.Data.OleDb
Imports System.IO
Imports QBFC12Lib

Public Class BackupFromQB
    Inherits UserControl

    'Private ReadOnly connectionString As String =
    '    "Provider=Microsoft.ACE.OLEDB.12.0;" &
    '    "Data Source=C:\Users\lenovo\Documents\MooneemDB.accdb;" &
    '    "Persist Security Info=False;"
    Private ReadOnly connectionString As String = "Provider=Microsoft.ACE.OLEDB.12.0;Data Source=" + Application.StartupPath + "\MooneemDB.accdb;Persist Security Info=False;"

    Private WithEvents backgroundWorker As New BackgroundWorker()
    Private progressForm As ProgressForm

    Private lblAddPath As Label
    Private txtFolderPath As TextBox
    Private btnBrowse As Button
    Private btnSubmitPath As Button
    Private dgvPaths As DataGridView
    Private btnBackup As Button
    Private lblBackupHeader As Label

    Public Sub New()
        InitializeComponent()
        InitializeUI()
        LoadFolderPaths()
    End Sub
    Private Sub InitializeUI()
        Me.Dock = DockStyle.Fill
        Me.BackColor = Color.White ' Set full page background to white

        ' Define consistent theme colors
        Dim primaryColor As Color = Color.FromArgb(0, 150, 136)
        Dim secondaryColor As Color = Color.FromArgb(37, 36, 81)

        ' Heading label
        lblAddPath = New Label With {
        .Text = "Add Folder Path:",
        .Font = New Font("Verdana", 12, FontStyle.Bold),
        .AutoSize = True,
        .Top = 20,
        .Left = 20
    }

        ' Folder path input
        txtFolderPath = New TextBox With {
        .Font = New Font("Verdana", 11),
        .Width = 400,
        .Top = lblAddPath.Bottom + 10,
        .Left = 20
    }

        ' Browse button
        btnBrowse = New Button With {
        .Text = "...",
        .Font = New Font("Verdana", 11),
        .Top = txtFolderPath.Top,
        .Height = 30,
        .Left = txtFolderPath.Right + 10
    }
        AddHandler btnBrowse.Click, AddressOf btnBrowse_Click

        ' Styled Submit button
        btnSubmitPath = New Button With {
        .Text = "Submit",
        .Font = New Font("Verdana", 10, FontStyle.Bold),
        .Width = 160,
        .Height = 36,
        .Top = txtFolderPath.Bottom + 10,
        .Left = 20,
        .BackColor = Color.Transparent,
        .ForeColor = primaryColor,
        .FlatStyle = FlatStyle.Flat
    }
        btnSubmitPath.FlatAppearance.BorderColor = primaryColor
        btnSubmitPath.FlatAppearance.BorderSize = 2
        AddHandler btnSubmitPath.MouseEnter, Sub()
                                                 btnSubmitPath.BackColor = primaryColor
                                                 btnSubmitPath.ForeColor = Color.White
                                             End Sub
        AddHandler btnSubmitPath.MouseLeave, Sub()
                                                 btnSubmitPath.BackColor = Color.Transparent
                                                 btnSubmitPath.ForeColor = primaryColor
                                             End Sub
        AddHandler btnSubmitPath.Click, AddressOf btnSubmitPath_Click

        ' Data grid to show folder paths
        dgvPaths = New DataGridView With {
        .Top = btnSubmitPath.Bottom + 20,
        .Left = 20,
        .Width = 1050,
        .Height = 200,
        .ReadOnly = True,
        .AllowUserToAddRows = False,
        .Font = New Font("Verdana", 11)
    }

        ' Add Folder Path column manually
        Dim colFolderPath As New DataGridViewTextBoxColumn With {
        .Name = "FolderPath",
        .HeaderText = "Folder Path",
        .DataPropertyName = "FolderPath",
        .Width = 1006,
        .ReadOnly = True
    }
        dgvPaths.Columns.Add(colFolderPath)

        ' Backup section heading
        lblBackupHeader = New Label With {
        .Text = "Take Backup Data:",
        .Font = New Font("Verdana", 12, FontStyle.Bold),
        .Top = dgvPaths.Bottom + 30,
        .Left = 20,
        .Width = 300
    }

        ' Styled Start Backup button
        btnBackup = New Button With {
        .Text = "Start Backup",
        .Font = New Font("Verdana", 10, FontStyle.Bold),
        .Width = 160,
        .Height = 36,
        .Top = lblBackupHeader.Bottom + 10,
        .Left = 20,
        .BackColor = Color.Transparent,
        .ForeColor = secondaryColor,
        .FlatStyle = FlatStyle.Flat
    }
        btnBackup.FlatAppearance.BorderColor = secondaryColor
        btnBackup.FlatAppearance.BorderSize = 2
        AddHandler btnBackup.MouseEnter, Sub()
                                             btnBackup.BackColor = secondaryColor
                                             btnBackup.ForeColor = Color.White
                                         End Sub
        AddHandler btnBackup.MouseLeave, Sub()
                                             btnBackup.BackColor = Color.Transparent
                                             btnBackup.ForeColor = secondaryColor
                                         End Sub
        AddHandler btnBackup.Click, AddressOf btnBackup_Click

        ' Add all controls to the UserControl or Form
        Me.Controls.Add(lblAddPath)
        Me.Controls.Add(txtFolderPath)
        Me.Controls.Add(btnBrowse)
        Me.Controls.Add(btnSubmitPath)
        Me.Controls.Add(dgvPaths)
        Me.Controls.Add(lblBackupHeader)
        Me.Controls.Add(btnBackup)
    End Sub




    Private Sub btnBrowse_Click(sender As Object, e As EventArgs)
        Using fbd As New FolderBrowserDialog()
            If fbd.ShowDialog() = DialogResult.OK Then
                txtFolderPath.Text = fbd.SelectedPath
            End If
        End Using
    End Sub

    Private Sub btnSubmitPath_Click(sender As Object, e As EventArgs)
        If Directory.Exists(txtFolderPath.Text.Trim()) Then
            Try
                Using conn As New OleDbConnection(connectionString)
                    conn.Open()
                    Dim cmd As New OleDbCommand("INSERT INTO BackupPaths (FolderPath) VALUES (?)", conn)
                    cmd.Parameters.AddWithValue("@FolderPath", txtFolderPath.Text.Trim())
                    cmd.ExecuteNonQuery()
                End Using
                LoadFolderPaths()
                txtFolderPath.Text = ""
            Catch ex As Exception
                MessageBox.Show("Error inserting folder path: " & ex.Message)
            End Try
        Else
            MessageBox.Show("Selected folder does not exist.")
        End If
    End Sub

    Private Sub LoadFolderPaths()
        Try
            Using conn As New OleDbConnection(connectionString)
                conn.Open()
                Dim da As New OleDbDataAdapter("SELECT FolderPath FROM BackupPaths", conn)
                Dim dt As New DataTable()
                da.Fill(dt)
                dgvPaths.DataSource = dt
            End Using
        Catch ex As Exception
            MessageBox.Show("Error loading folder paths: " & ex.Message)
        End Try
    End Sub

    Private Sub btnBackup_Click(sender As Object, e As EventArgs)
        backgroundWorker.WorkerReportsProgress = True
        backgroundWorker.WorkerSupportsCancellation = False
        backgroundWorker.RunWorkerAsync()
    End Sub


    Private Sub backgroundWorker_DoWork(sender As Object, e As DoWorkEventArgs) Handles backgroundWorker.DoWork
        Try
            Dim backupFolderPath As String = "C:\QuickBooksBackup\"
            If Not Directory.Exists(backupFolderPath) Then
                Directory.CreateDirectory(backupFolderPath)
            End If

            Dim folderPaths As List(Of String) = GetBackupPaths()
            If folderPaths.Count = 0 Then
                MessageBox.Show("No backup folder paths found in the database!", "Error")
                Return
            End If

            Dim successList As New List(Of String)()
            Dim failedList As New List(Of String)()
            Dim allFiles As New List(Of String)()

            For Each folderPath As String In folderPaths
                If Directory.Exists(folderPath) Then
                    allFiles.AddRange(Directory.GetFiles(folderPath, "*.QBW", SearchOption.AllDirectories))
                End If
            Next

            If allFiles.Count = 0 Then
                MessageBox.Show("No QuickBooks company files found!", "Info")
                Return
            End If

            Me.Invoke(Sub()
                          progressForm = New ProgressForm()
                          progressForm.ProgressValue = 0
                          progressForm.ProgressText = "Initializing backup..."
                          progressForm.ProgressBar1.Maximum = allFiles.Count
                          progressForm.Show()
                      End Sub)

            Dim sessionManager As New QBSessionManager()
            sessionManager.OpenConnection("", "Mooneem App")

            Dim index As Integer = 0
            For Each qbFile In allFiles
                Try
                    index += 1
                    Me.Invoke(Sub()
                                  progressForm.ProgressText = "Backing up: " & Path.GetFileName(qbFile)
                                  progressForm.ProgressValue = index
                              End Sub)

                    sessionManager.BeginSession(qbFile, ENOpenMode.omSingleUser)
                    Dim msgSetReq As IMsgSetRequest = sessionManager.CreateMsgSetRequest("US", 12, 0)
                    msgSetReq.AppendCompanyQueryRq()
                    Dim response As IMsgSetResponse = sessionManager.DoRequests(msgSetReq)

                    Dim fileName As String = Path.GetFileNameWithoutExtension(qbFile)
                    Dim backupFilePath As String = Path.Combine(backupFolderPath, fileName & ".QBB")
                    File.WriteAllText(backupFilePath, response.ToXMLString)

                    sessionManager.EndSession()
                    successList.Add(fileName & ".QBB")
                Catch ex As Exception
                    failedList.Add(Path.GetFileName(qbFile) & " - " & ex.Message)
                End Try
            Next

            sessionManager.CloseConnection()
            Me.Invoke(Sub() progressForm.Close())

            Dim summary As String = "Backup process completed!" & vbCrLf &
                "✅ Successfully backed up: " & successList.Count & " files" & vbCrLf &
                String.Join(vbCrLf, successList) & vbCrLf & vbCrLf &
                "❌ Failed backups: " & failedList.Count & " files" & vbCrLf &
                String.Join(vbCrLf, failedList)

            MessageBox.Show(summary, "Backup Summary", MessageBoxButtons.OK, MessageBoxIcon.Information)
        Catch ex As Exception
            MessageBox.Show("Error: " & ex.Message, "Exception", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Sub

    Private Function GetBackupPaths() As List(Of String)
        Dim folderPaths As New List(Of String)()
        Try
            Using conn As New OleDbConnection(connectionString)
                conn.Open()
                Dim cmd As New OleDbCommand("SELECT FolderPath FROM BackupPaths", conn)
                Using reader As OleDbDataReader = cmd.ExecuteReader()
                    While reader.Read()
                        folderPaths.Add(reader("FolderPath").ToString())
                    End While
                End Using
            End Using
        Catch ex As Exception
            MessageBox.Show("Error fetching folder paths: " & ex.Message)
        End Try
        Return folderPaths
    End Function
End Class
