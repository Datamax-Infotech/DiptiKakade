Imports System.Windows.Forms
Imports System.Windows.Forms.VisualStyles.VisualStyleElement

Public Class ProgressForm
    Public Property ProgressText As String
        Get
            Return lblStatus.Text
        End Get
        Set(value As String)
            lblStatus.Text = value
            lblStatus.Refresh()
        End Set
    End Property

    Public Property ProgressValue As Integer
        Get
            Return ProgressBar1.Value
        End Get
        Set(value As Integer)
            ProgressBar1.Value = value
            ProgressBar1.Refresh()
        End Set
    End Property

    Private Sub ProgressForm_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        ' Set the position and size manually
        Dim posX As Integer = 500 ' Adjust X position
        Dim posY As Integer = 300 ' Adjust Y position
        Dim formWidth As Integer = 400 ' Adjust width
        Dim formHeight As Integer = 180 ' Adjust height

        Me.StartPosition = FormStartPosition.Manual ' Set manual positioning
        Me.Location = New Point(posX, posY) ' Set the custom position
        Me.Size = New Size(formWidth, formHeight) ' Set the custom size

        Me.FormBorderStyle = FormBorderStyle.FixedDialog ' Prevent resizing
        'lblStatus.AutoSize = False
        'lblStatus.TextAlign = ContentAlignment.MiddleCenter
        'lblStatus.Dock = DockStyle.Top
        ' Status label styling
        lblStatus.AutoSize = False
        lblStatus.TextAlign = ContentAlignment.TopCenter
        lblStatus.Dock = DockStyle.Top
        lblStatus.Height = 50
        lblStatus.Padding = New Padding(0, 35, 0, 0) ' Top padding (10px)

        ProgressBar1.Dock = DockStyle.Bottom
    End Sub


End Class
