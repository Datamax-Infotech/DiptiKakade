Imports System.Net.Http
Imports System.Text
Imports Newtonsoft.Json

Public Class BillComHelper
    Inherits UserControl

    Private btnTestLogin As Button

    Public Sub New()
        Me.Dock = DockStyle.Fill
        InitializeUI()
    End Sub

    Private Sub InitializeUI()
        btnTestLogin = New Button With {
            .Text = "Test Bill.com Login",
            .Width = 200,
            .Height = 40,
            .Top = 50,
            .Left = 50
        }
        AddHandler btnTestLogin.Click, AddressOf TestLogin
        Me.Controls.Add(btnTestLogin)
    End Sub

    Private Async Sub TestLogin(sender As Object, e As EventArgs)
        Try
            Dim loginUrl As String = "https://api.bill.com/api/v2/Login.json"

            ' 👉 Form data instead of JSON
            Dim values = New Dictionary(Of String, String) From {
            {"userName", "davidkrasnow@ucbzerowaste.com"},
            {"password", "BILgrn4652"},
            {"devKey", "01KBONLGJMYQGBZK1554"},
            {"orgId", "00801MOOWKXSBAJ2mi3v"}
        }


            Using client As New HttpClient()
                Dim content = New FormUrlEncodedContent(values)
                Dim response = Await client.PostAsync(loginUrl, content)
                Dim result = Await response.Content.ReadAsStringAsync()

                If response.IsSuccessStatusCode Then
                    MessageBox.Show("✅ Login Successful! Response: " & result)
                Else
                    MessageBox.Show("❌ Login Failed! Response: " & result)
                End If
            End Using

        Catch ex As Exception
            MessageBox.Show("⚠️ Error: " & ex.Message)
        End Try
    End Sub


End Class
