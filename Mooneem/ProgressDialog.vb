Imports System.Windows.Forms

Public Class ProgressDialog
    Private lblCurrentFile As Label
    Private fileNameToolTip As ToolTip

    Public Sub New()
        InitializeComponent()
    End Sub

    Private Sub ProgressDialog_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        Me.FormBorderStyle = FormBorderStyle.FixedDialog
        Me.ControlBox = False
        Me.StartPosition = FormStartPosition.Manual
        Me.Location = New Point(400, 200)
        Me.Size = New Size(400, 180) ' Increased height for extra lines
        Me.Text = "Uploading Files"

        ' Add a loading message
        Dim lblMessage As New Label() With {
            .Text = "Uploading files... Please wait.",
            .AutoSize = True,
            .Location = New Point(50, 30)
        }

        ' Add a spinner
        Dim spinner As New ProgressBar() With {
            .Style = ProgressBarStyle.Marquee,
            .Location = New Point(50, 60),
            .Width = 250,
            .Height = 20
        }

        ' Add a label for the current file being uploaded
        lblCurrentFile = New Label() With {
            .Text = "Current file: ",
            .AutoSize = False,
            .Location = New Point(50, 90),
            .Width = 280,
            .Height = 40, ' Increased height for text wrapping
            .MaximumSize = New Size(280, 60), ' Ensures multi-line display
            .TextAlign = ContentAlignment.TopLeft
        }

        ' Add Tooltip for long file names
        fileNameToolTip = New ToolTip()

        ' Add controls
        Me.Controls.Add(lblMessage)
        Me.Controls.Add(spinner)
        Me.Controls.Add(lblCurrentFile)
    End Sub

    ' Method to update the current file name
    Public Sub UpdateCurrentFile(fileName As String)
        If lblCurrentFile.InvokeRequired Then
            lblCurrentFile.Invoke(Sub()
                                      lblCurrentFile.Text = $"Current file: {fileName}"
                                      fileNameToolTip.SetToolTip(lblCurrentFile, fileName) ' Show full name in tooltip
                                  End Sub)
        Else
            lblCurrentFile.Text = $"Current file: {fileName}"
            fileNameToolTip.SetToolTip(lblCurrentFile, fileName)
        End If
    End Sub
End Class
