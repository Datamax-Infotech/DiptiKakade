Imports System.Data.OleDb
Imports System.IO
Imports System.IO.Compression

Public Class DatabaseHelper
    ' Connection string for Access Database
    Dim constr = Application.StartupPath
    'Private ReadOnly connectionString As String =
    '    "Provider=Microsoft.ACE.OLEDB.12.0;" &
    '    "Data Source=C:\Users\lenovo\Documents\MooneemDB.accdb;" &
    '    "Persist Security Info=False;"
    Private ReadOnly connectionString As String = "Provider=Microsoft.ACE.OLEDB.12.0;Data Source=" + Application.StartupPath + "\MooneemDB.accdb;Persist Security Info=False;"

    Public Function GetBackupPaths() As List(Of String)
        Dim folderPaths As New List(Of String)

        Try
            Using conn As New OleDbConnection(connectionString)
                conn.Open()

                Dim query As String = "SELECT FolderPath FROM BackupPaths"
                Using cmd As New OleDbCommand(query, conn)
                    Using reader As OleDbDataReader = cmd.ExecuteReader()
                        While reader.Read()
                            folderPaths.Add(reader("FolderPath").ToString())
                        End While
                    End Using
                End Using
            End Using

        Catch ex As Exception
            MessageBox.Show("Error fetching folder paths: " & ex.Message, "Database Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try

        Return folderPaths
    End Function











End Class
