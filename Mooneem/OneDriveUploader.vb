Imports System.IO
Imports System.Net
Imports System.Windows.Forms
Imports Newtonsoft.Json

Public Class OneDriveUploader
    Inherits UserControl

    ' Controls
    Private lblFolder As Label
    Private txtFolderName As TextBox
    Private btnBrowseFolder As Button
    Private btnUpload As Button

    Public Sub New()
        InitializeComponent()
        InitializeUI()
    End Sub

    Private Sub InitializeUI()
        Me.Dock = DockStyle.Fill
        Me.BackColor = Color.White

        ' Theme color
        Dim primaryColor As Color = Color.FromArgb(0, 150, 136)

        ' Label: Enter Folder Name
        lblFolder = New Label With {
            .Text = "Enter Folder Name:",
            .Font = New Font("Verdana", 12, FontStyle.Bold),
            .AutoSize = True,
            .Top = 30,
            .Left = 20
        }

        ' TextBox: Folder Name
        txtFolderName = New TextBox With {
            .Font = New Font("Verdana", 11),
            .Width = 400,
            .Top = lblFolder.Bottom + 10,
            .Left = 20
        }

        ' Browse Button
        btnBrowseFolder = New Button With {
            .Text = "...",
            .Font = New Font("Verdana", 11),
            .Top = txtFolderName.Top,
            .Height = 30,
            .Left = txtFolderName.Right + 10
        }
        AddHandler btnBrowseFolder.Click, AddressOf btnBrowseFolder_Click

        ' Upload Button (aligned left)
        btnUpload = New Button With {
            .Text = "Upload File to OneDrive",
            .Font = New Font("Verdana", 10, FontStyle.Bold),
            .Width = 240,
            .Height = 36,
            .BackColor = Color.Transparent,
            .ForeColor = primaryColor,
            .FlatStyle = FlatStyle.Flat,
            .Top = txtFolderName.Bottom + 40,
            .Left = 20
        }
        btnUpload.FlatAppearance.BorderColor = primaryColor
        btnUpload.FlatAppearance.BorderSize = 2

        AddHandler btnUpload.MouseEnter, Sub()
                                             btnUpload.BackColor = primaryColor
                                             btnUpload.ForeColor = Color.White
                                         End Sub
        AddHandler btnUpload.MouseLeave, Sub()
                                             btnUpload.BackColor = Color.Transparent
                                             btnUpload.ForeColor = primaryColor
                                         End Sub
        AddHandler btnUpload.Click, AddressOf btnUpload_Click

        ' Add controls to form
        Me.Controls.Add(lblFolder)
        Me.Controls.Add(txtFolderName)
        Me.Controls.Add(btnBrowseFolder)
        Me.Controls.Add(btnUpload)
    End Sub

    ' Browse Folder Dialog
    Private Sub btnBrowseFolder_Click(sender As Object, e As EventArgs)
        Using folderDialog As New FolderBrowserDialog()
            If folderDialog.ShowDialog() = DialogResult.OK Then
                txtFolderName.Text = folderDialog.SelectedPath
            End If
        End Using
    End Sub

    Function CheckFolderExists(ByVal apiUrl As String, ByVal token As String, ByVal folderName As String) As Boolean


        Try
            Dim request As HttpWebRequest = CType(WebRequest.Create(apiUrl & "/" & folderName), HttpWebRequest)
            request.Method = "GET"
            request.Headers.Add("Authorization", "Bearer " & token)

            Using response As HttpWebResponse = CType(request.GetResponse(), HttpWebResponse)
                If response.StatusCode = HttpStatusCode.OK Then
                    Using reader As New StreamReader(response.GetResponseStream())
                        Dim jsonResponse = reader.ReadToEnd()
                        Dim folderDetails = JsonConvert.DeserializeObject(Of Dictionary(Of String, Object))(jsonResponse)


                        Return True
                    End Using
                End If
            End Using

        Catch ex As WebException
            Dim httpResponse = CType(ex.Response, HttpWebResponse)
            If httpResponse.StatusCode = HttpStatusCode.NotFound Then
                Console.WriteLine("Folder not found. Please check the folder path and user ID.")
            Else
                Console.WriteLine("Error: " & ex.Message)
            End If
        End Try

        Return False
    End Function



    Sub CreateFolder(ByVal apiUrl As String, ByVal token As String, ByVal folderName As String)
        Try
            Dim request As HttpWebRequest = CType(WebRequest.Create(apiUrl), HttpWebRequest)
            request.Method = "POST"
            request.Headers.Add("Authorization", "Bearer " & token)
            request.ContentType = "application/json"

            ' ✅ Corrected JSON payload format for VB.NET
            Dim folderData As String = $"{{""name"":""{folderName}"", ""folder"":{{}}, ""@microsoft.graph.conflictBehavior"":""rename""}}"

            Using writer As New StreamWriter(request.GetRequestStream())
                writer.Write(folderData)
            End Using

            Using response As HttpWebResponse = CType(request.GetResponse(), HttpWebResponse)
                If response.StatusCode = HttpStatusCode.Created Then
                    Console.WriteLine($"Folder '{folderName}' created successfully.")
                Else
                    Console.WriteLine($"Unexpected response status: {response.StatusCode}")
                End If
            End Using

        Catch ex As WebException
            Using stream = ex.Response.GetResponseStream()
                Using reader As New StreamReader(stream)
                    Dim errorDetails As String = reader.ReadToEnd()
                    Console.WriteLine($"Error creating folder: {errorDetails}")
                End Using
            End Using
        Catch ex As Exception
            Console.WriteLine("Unexpected error: " & ex.Message)
        End Try
    End Sub

    Private Async Function UploadFile(file_n As String, rootFolderPath As String, dateFolderPath As String,
                                  oneDriveApiUrl As String, oneDriveApiUrl_sub As String,
                                  accessToken As String, oneDriveLogin As OneDriveLogin,
                                  progressDialog As ProgressDialog, uploadedFiles As List(Of String),
                                  failedFiles As List(Of String)) As Task

        Dim fileName As String = Path.GetFileName(file_n)
        'progressDialog.UpdateCurrentFile(fileName)
        progressDialog.UpdateCurrentFile(file_n)

        ' Build subfolder path in OneDrive
        Dim relativePath As String = Path.GetDirectoryName(file_n).Substring(rootFolderPath.Length).TrimStart("\"c)
        Dim oneDriveSubFolderPath As String = $"{dateFolderPath}/{relativePath}"

        If Not CheckFolderExists(oneDriveApiUrl, accessToken, oneDriveSubFolderPath) Then
            CreateFolder(oneDriveApiUrl_sub, accessToken, oneDriveSubFolderPath)
        End If

        Dim uploadSuccess As Boolean = Await oneDriveLogin.UploadFileToOneDriveAsync(accessToken, file_n, fileName, oneDriveSubFolderPath)

        If uploadSuccess Then
            uploadedFiles.Add(fileName)
            Dim localFileSize As Long = New FileInfo(file_n).Length
            Dim uploadedFileSize As Long = Await oneDriveLogin.GetUploadedFileSizeAsync(accessToken, oneDriveSubFolderPath, fileName)

            If localFileSize = uploadedFileSize Then
                If fileName.ToLower().EndsWith(".qbb") Then
                    Try
                        File.Delete(file_n)
                    Catch ex As Exception
                        MessageBox.Show($"Failed to delete '{fileName}' from source. Error: {ex.Message}", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
                    End Try
                End If
            Else
                MessageBox.Show($"File size mismatch for '{fileName}'.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
            End If
        Else
            failedFiles.Add(fileName)
        End If
    End Function

    ' Upload Button Stub
    Private Async Sub btnUpload_Click(sender As Object, e As EventArgs)

        Dim dbHelper As New DatabaseHelper()
        Dim folderPaths = dbHelper.GetBackupPaths()

        If folderPaths.Count = 0 Then
            MessageBox.Show("No folder paths found in the database.", "Info", MessageBoxButtons.OK, MessageBoxIcon.Information)
            Return
        End If

        Dim progressDialog As New ProgressDialog()
        progressDialog.Show()

        Dim oneDriveLogin As New OneDriveLogin()
        Dim accessToken As String = Await oneDriveLogin.GetAccessTokenAsync()

        If String.IsNullOrEmpty(accessToken) Then
            progressDialog.Close()
            MessageBox.Show("Failed to retrieve the access token. Cannot upload files to OneDrive.")
            Return
        End If

        Dim folderPath As String = "QB_Backup"
        Dim oneDriveApiUrl As String = $"https://graph.microsoft.com/v1.0/users/3b47dd52-9b64-45f5-a869-904918d8be4c/drive/root:/{folderPath}"
        Dim oneDriveApiUrl_sub As String = $"{oneDriveApiUrl}/children"
        Dim currentDateFolder As String = DateTime.Now.ToString("yyyy-MM-dd")

        ' Create the date folder first
        Dim dateFolderPath = $"{folderPath}/{currentDateFolder}"
        If Not CheckFolderExists(oneDriveApiUrl, accessToken, dateFolderPath) Then
            CreateFolder(oneDriveApiUrl_sub, accessToken, currentDateFolder)
        End If

        Dim uploadedFiles As New List(Of String)
        Dim failedFiles As New List(Of String)

        For Each rootFolderPath In folderPaths
            ' 1. Upload all *.qbw files directly in base folder


            If Not Directory.Exists(rootFolderPath) Then
                MessageBox.Show($"The folder path does not exist in your system: {rootFolderPath}", "Missing Folder", MessageBoxButtons.OK, MessageBoxIcon.Warning)
                Continue For
            End If

            For Each file_n In Directory.GetFiles(rootFolderPath, "*.qbb", SearchOption.AllDirectories)
                Await UploadFile(file_n, rootFolderPath, dateFolderPath, oneDriveApiUrl, oneDriveApiUrl_sub, accessToken, oneDriveLogin, progressDialog, uploadedFiles, failedFiles)
            Next


            ' 2. Upload all files from subfolders that end with "tax"
            For Each subFolder In Directory.GetDirectories(rootFolderPath, "*", SearchOption.AllDirectories)
                If subFolder.ToLower().EndsWith("tax form history") Then
                    For Each file_n In Directory.GetFiles(subFolder, "*", SearchOption.AllDirectories)
                        Await UploadFile(file_n, rootFolderPath, dateFolderPath, oneDriveApiUrl, oneDriveApiUrl_sub, accessToken, oneDriveLogin, progressDialog, uploadedFiles, failedFiles)
                    Next
                End If
            Next
        Next

        progressDialog.Close()

        ' Summary Message
        Dim summaryMessage As String = "Upload Summary:" & vbCrLf &
                                 $"✔️ Successfully uploaded: {uploadedFiles.Count}" & vbCrLf &
                                 $"❌ Failed uploads: {failedFiles.Count}" & vbCrLf

        If uploadedFiles.Count > 0 Then
            summaryMessage &= vbCrLf & "✅ Uploaded Files:" & vbCrLf & String.Join(vbCrLf, uploadedFiles)
        End If

        If failedFiles.Count > 0 Then
            summaryMessage &= vbCrLf & vbCrLf & "❌ Failed Files:" & vbCrLf & String.Join(vbCrLf, failedFiles)
        End If

        MessageBox.Show(summaryMessage, "Upload Summary", MessageBoxButtons.OK, MessageBoxIcon.Information)
    End Sub
End Class
