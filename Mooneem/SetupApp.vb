Imports System.Data.OleDb
Imports System.IO
Imports FontAwesome.Sharp
Imports QBFC12Lib

Public Class SetupApp
    Inherits UserControl

    Private cmbClients As ComboBox
    Private cmbApps As ComboBox
    Private btnAddApp As Button

    'Private ReadOnly connStr As String =
    '    "Provider=Microsoft.ACE.OLEDB.12.0;" &
    '    "Data Source=C:\Users\lenovo\Documents\MooneemDB.accdb;" &
    '    "Persist Security Info=False;"
    Private ReadOnly connStr As String = "Provider=Microsoft.ACE.OLEDB.12.0;Data Source=" + Application.StartupPath + "\MooneemDB.accdb;Persist Security Info=False;"


    Public Sub New()
        Me.Dock = DockStyle.Fill
        Me.BackColor = Color.White
        BuildUI()
        ' LoadClients()
    End Sub


    Private Sub BuildUI()
        ' Outer wrapper panel to center contents
        Dim outerPanel As New Panel With {
        .Dock = DockStyle.Fill,
        .BackColor = Color.White
    }

        ' Inner panel to contain the button and stay centered
        Dim innerPanel As New Panel With {
        .AutoSize = True,
        .BackColor = Color.White
    }

        ' Center the inner panel manually on resize
        AddHandler Me.Resize, Sub()
                                  innerPanel.Location = New Point(
            (Me.ClientSize.Width - innerPanel.Width) \ 2,
            (Me.ClientSize.Height - innerPanel.Height) \ 2
        )
                              End Sub

        btnAddApp = New Button With {
        .Text = "Add App in QB",
        .Font = New Font("Verdana", 10, FontStyle.Bold),
        .Size = New Size(180, 36),
        .BackColor = Color.FromArgb(0, 150, 136),
        .ForeColor = Color.White,
        .FlatStyle = FlatStyle.Flat
    }
        btnAddApp.FlatAppearance.BorderSize = 0

        AddHandler btnAddApp.Click, AddressOf AddAppInQB

        innerPanel.Controls.Add(btnAddApp)
        outerPanel.Controls.Add(innerPanel)

        Me.Controls.Add(outerPanel)
    End Sub




    Private Sub AddAppInQB(sender As Object, e As EventArgs)
        Dim sessionManager As QBSessionManager = Nothing
        Dim requestMsgSet As IMsgSetRequest

        Try
            sessionManager = New QBSessionManager()
            sessionManager.OpenConnection("", "Mooneem App")
            sessionManager.BeginSession("", ENOpenMode.omDontCare)

            requestMsgSet = sessionManager.CreateMsgSetRequest("US", 12, 0)
            requestMsgSet.Attributes.OnError = ENRqOnError.roeContinue

            Dim responseMsgSet As IMsgSetResponse = sessionManager.DoRequests(requestMsgSet)

            If responseMsgSet Is Nothing Then
                MessageBox.Show("Application not added in QB.", "", MessageBoxButtons.OK, MessageBoxIcon.Error)
            Else
                MessageBox.Show("Application added in QB.", "", MessageBoxButtons.OK, MessageBoxIcon.None)
            End If

        Catch ex As Exception
            MessageBox.Show("Error for file: " & ex.Message)
        Finally
            Try
                sessionManager.EndSession()
                sessionManager.CloseConnection()
            Catch ex As Exception
                ' ignore
            End Try
        End Try


    End Sub
End Class
