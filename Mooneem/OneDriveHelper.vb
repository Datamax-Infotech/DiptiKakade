Imports System.Net.Http
Imports System.Net.Http.Headers
Imports Newtonsoft.Json.Linq
Imports System.Threading.Tasks
Imports Azure.Core
Imports System.IO
Imports System.Net
Imports System.Text

Public Class OneDriveLogin

    Private Const ClientId As String = "21ff30c0-43d0-464d-83db-709128cc4932"
    Private Const TenantId As String = "a6f866f7-082c-4842-ae24-6be890b3ba8c"
    Dim clientSecret As String = "YOUR_SECRET_HERE"
    Private Const userId As String = "3b47dd52-9b64-45f5-a869-904918d8be4c"

    ' Function to retrieve the access token


    Public Async Function GetAccessTokenAsync() As Task(Of String)
        Try
            'MessageBox.Show("Attempting to retrieve access token...") ' Step 2: Confirm token request started

            Using client As New HttpClient()
                Dim values = New Dictionary(Of String, String) From {
                {"client_id", ClientId},
                {"scope", "https://graph.microsoft.com/.default"},
                {"client_secret", ClientSecret},
                {"grant_type", "client_credentials"}
            }


                Dim content = New FormUrlEncodedContent(values)
                Dim response = Await client.PostAsync($"https://login.microsoftonline.com/{TenantId}/oauth2/v2.0/token", content)
                Dim result = Await response.Content.ReadAsStringAsync()

                'MessageBox.Show("Response Received: " & response.StatusCode.ToString()) ' Step 3: Confirm token response
                Dim jsonResult = JObject.Parse(result)
                Dim accessToken As String = jsonResult("access_token").ToString()

                'MessageBox.Show("Token Retrieved (First 100 chars): " & accessToken)
                If Not response.IsSuccessStatusCode Then
                    MessageBox.Show("Token Error: " & result)
                    Return String.Empty
                End If


                Return jsonResult("access_token").ToString()


            End Using
        Catch ex As Exception
            MessageBox.Show("Error in GetAccessTokenAsync: " & ex.Message)
            Return String.Empty
        End Try
    End Function



    Private Async Function VerifyOneDriveAccessAsync(accessToken As String) As Task
        Try
            ' MessageBox.Show("Verifying OneDrive Access...")

            Using client As New HttpClient()
                client.DefaultRequestHeaders.Authorization = New AuthenticationHeaderValue("Bearer", accessToken)

                ' Using Graph API endpoint to list OneDrive root folder items
                ' Dim response = Await client.GetAsync("https://graph.microsoft.com/v1.0/me/drive/root/children")
                ' Dim response = Await client.GetAsync("https://graph.microsoft.com/v1.0/drives")
                Dim response = Await client.GetAsync("https://graph.microsoft.com/v1.0/users/3b47dd52-9b64-45f5-a869-904918d8be4c/drive/root/children")

                Dim result = Await response.Content.ReadAsStringAsync()

                If response.IsSuccessStatusCode Then
                    'MessageBox.Show("OneDrive Access Verified. Data retrieved successfully.")
                    'Console.WriteLine("OneDrive Data: " & result)
                Else
                    MessageBox.Show("Failed to access OneDrive: " & result)
                End If
            End Using
        Catch ex As Exception
            MessageBox.Show("Error in VerifyOneDriveAccessAsync: " & ex.Message)
        End Try
    End Function

    ' Updated Login method to include verification
    Public Async Function LoginToOneDriveAsync() As Task
        Try
            ' MessageBox.Show("Starting OneDrive Login Process...")

            Dim accessToken As String = Await GetAccessTokenAsync()

            If String.IsNullOrEmpty(accessToken) Then
                'MessageBox.Show("Access token not retrieved. Check Azure configuration.")
                Return
            End If

            ' MessageBox.Show("Login Successful! Access Token Retrieved.")
            Await VerifyOneDriveAccessAsync(accessToken)

        Catch ex As Exception
            MessageBox.Show("Error in LoginToOneDriveAsync: " & ex.Message)
        End Try
    End Function






    Public Async Function UploadFileToOneDriveAsync(accessToken As String, filePath As String, fileName As String, oneDriveFolder As String) As Task(Of Boolean)
        Try
            If IsFileLocked(filePath) Then
                MessageBox.Show($"File is locked or in use: {filePath}", "File Access Error", MessageBoxButtons.OK, MessageBoxIcon.Warning)
                Return False
            End If

            Using client As New HttpClient()
                client.Timeout = TimeSpan.FromMinutes(5)
                client.DefaultRequestHeaders.Authorization = New AuthenticationHeaderValue("Bearer", accessToken)

                Dim fileBytes As Byte() = System.IO.File.ReadAllBytes(filePath)
                Dim encodedFolder As String = Uri.EscapeDataString(oneDriveFolder)
                Dim uploadUrl As String = $"https://graph.microsoft.com/v1.0/users/{userId}/drive/root:/{encodedFolder}/{fileName}:/content"

                If fileBytes.Length > 4 * 1024 * 1024 Then
                    Return Await UploadLargeFileViaSession(accessToken, filePath, fileName, oneDriveFolder)
                End If

                Dim content As New ByteArrayContent(fileBytes)
                content.Headers.ContentType = New MediaTypeHeaderValue("application/octet-stream")

                Dim response As HttpResponseMessage = Await client.PutAsync(uploadUrl, content)

                If response.IsSuccessStatusCode Then
                    Return True
                Else
                    Dim errorDetails As String = Await response.Content.ReadAsStringAsync()
                    MessageBox.Show($"Upload failed for {fileName}. Server responded with: {response.StatusCode}{Environment.NewLine}{errorDetails}", "Upload Failed", MessageBoxButtons.OK, MessageBoxIcon.Error)
                    Return False
                End If
            End Using

        Catch ex As TaskCanceledException
            MessageBox.Show($"Upload canceled or timed out for {fileName}. Reason: {ex.Message}", "Upload Timeout", MessageBoxButtons.OK, MessageBoxIcon.Warning)
            Return False

        Catch ex As IOException
            MessageBox.Show($"I/O error while uploading {fileName}: {ex.Message}", "File Read Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
            Return False

        Catch ex As HttpRequestException
            MessageBox.Show($"Network issue while uploading {fileName}: {ex.Message}", "Network Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
            Return False

        Catch ex As Exception
            MessageBox.Show($"Unexpected error uploading {fileName}: {ex.Message}", "Upload Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
            Return False
        End Try
    End Function


    Private Function IsFileLocked(filePath As String) As Boolean
        Try
            Using fs As New FileStream(filePath, FileMode.Open, FileAccess.Read, FileShare.None)
                ' File can be opened and read exclusively — not locked
            End Using
            Return False
        Catch ex As IOException
            ' File is likely in use or locked
            Return True
        End Try
    End Function

    Private Async Function UploadLargeFileViaSession(accessToken As String, filePath As String, fileName As String, oneDriveFolder As String) As Task(Of Boolean)
        Try
            Dim uploadSessionUrl As String = $"https://graph.microsoft.com/v1.0/users/{userId}/drive/root:/{Uri.EscapeDataString(oneDriveFolder)}/{fileName}:/createUploadSession"

            Using client As New HttpClient()
                client.DefaultRequestHeaders.Authorization = New AuthenticationHeaderValue("Bearer", accessToken)

                Dim sessionPayload As New StringContent("{""item"": {""@microsoft.graph.conflictBehavior"": ""replace""}}", Encoding.UTF8, "application/json")
                Dim sessionResponse = Await client.PostAsync(uploadSessionUrl, sessionPayload)

                If Not sessionResponse.IsSuccessStatusCode Then
                    Dim errorDetails = Await sessionResponse.Content.ReadAsStringAsync()
                    MessageBox.Show("Failed to create upload session: " & errorDetails)
                    Return False
                End If

                Dim sessionJson = Await sessionResponse.Content.ReadAsStringAsync()
                Dim uploadUrl = JObject.Parse(sessionJson)("uploadUrl").ToString()

                Const chunkSize As Integer = 10 * 1024 * 1024 ' 10 MB

                Dim fileInfo = New FileInfo(filePath)
                Dim fileSize = fileInfo.Length
                Dim start As Long = 0

                Using fs As New FileStream(filePath, FileMode.Open, FileAccess.Read)
                    While start < fileSize
                        Dim remaining = fileSize - start
                        Dim currentChunkSize = Math.Min(chunkSize, remaining)
                        Dim buffer(currentChunkSize - 1) As Byte

                        Dim bytesRead = Await fs.ReadAsync(buffer, 0, currentChunkSize)
                        If bytesRead = 0 Then Exit While

                        Dim chunkContent = New ByteArrayContent(buffer)
                        chunkContent.Headers.Add("Content-Range", $"bytes {start}-{start + bytesRead - 1}/{fileSize}")
                        chunkContent.Headers.ContentType = New MediaTypeHeaderValue("application/octet-stream")

                        Dim chunkResponse = Await client.PutAsync(uploadUrl, chunkContent)

                        If Not chunkResponse.IsSuccessStatusCode AndAlso chunkResponse.StatusCode <> HttpStatusCode.Accepted Then
                            Dim err = Await chunkResponse.Content.ReadAsStringAsync()
                            MessageBox.Show($"Chunk upload failed at {start}-{start + bytesRead - 1}: {err}")
                            Return False
                        End If

                        start += bytesRead
                    End While
                End Using

                Return True
            End Using

        Catch ex As Exception
            MessageBox.Show("Error uploading large file: " & ex.Message)
            Return False
        End Try
    End Function



    Public Async Function GetUploadedFileSizeAsync(accessToken As String, folderPath As String, fileName As String) As Task(Of Long)
        Dim encodedFolder As String = Uri.EscapeDataString(folderPath)
        Dim apiUrl As String = $"https://graph.microsoft.com/v1.0/users/{userId}/drive/root:/{encodedFolder}/{fileName}"

        Dim request = New HttpRequestMessage(HttpMethod.Get, apiUrl)
        request.Headers.Authorization = New AuthenticationHeaderValue("Bearer", accessToken)

        Using client As New HttpClient()
            Dim response = Await client.SendAsync(request)
            Dim responseBody = Await response.Content.ReadAsStringAsync()

            'MessageBox.Show($"API Response Body: {responseBody}", "API Debug Info", MessageBoxButtons.OK, MessageBoxIcon.Information)

            If response.IsSuccessStatusCode Then
                Dim fileDetails = JObject.Parse(responseBody)
                Return CLng(fileDetails("size"))
            Else
                Return 0 ' File not found or request failed
            End If
        End Using
    End Function









End Class



