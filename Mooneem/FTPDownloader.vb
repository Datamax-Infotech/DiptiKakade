Imports System.IO
Imports System.Net

Public Class FTPDownloader
    Public Shared Function DownloadFileFromFTP(remoteFileName As String, localSavePath As String) As Boolean
        Try
            Dim ftpUrl As String = "ftp://www.ucbloops.com/loops/files/" & remoteFileName
            Dim request As FtpWebRequest = CType(WebRequest.Create(ftpUrl), FtpWebRequest)
            request.Method = WebRequestMethods.Ftp.DownloadFile
            request.Credentials = New NetworkCredential("ftpuser@ucbloops.com", "0@qYSOdPuR9+")
            request.UseBinary = True
            request.UsePassive = True

            Using response As FtpWebResponse = CType(request.GetResponse(), FtpWebResponse)
                Using responseStream As Stream = response.GetResponseStream()
                    Using outputStream As New FileStream(localSavePath, FileMode.Create)
                        responseStream.CopyTo(outputStream)
                    End Using
                End Using
            End Using
            Return True
        Catch ex As Exception
            MessageBox.Show("FTP Download Error: " & ex.Message)
            Return False
        End Try
    End Function
End Class
