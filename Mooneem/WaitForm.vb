Imports System.Windows.Forms
Imports System.Drawing

Public Class WaitForm
    Inherits Form
    Private lbl As Label
    Private progress As ProgressBar

    Public Sub New(msg As String)
        ' Basic Form Styling
        Me.FormBorderStyle = FormBorderStyle.None
        Me.StartPosition = FormStartPosition.CenterScreen
        Me.Size = New Size(400, 150)
        Me.BackColor = Color.FromArgb(0, 150, 136)   ' Material Design Teal 500

        Me.Padding = New Padding(20)
        Me.TopMost = True

        ' Rounded Corners
        Me.Region = New Region(New Rectangle(0, 0, Me.Width, Me.Height))
        AddHandler Me.Paint, AddressOf DrawBorder

        ' Label
        lbl = New Label With {
            .Text = msg,
            .Dock = DockStyle.Top,
            .TextAlign = ContentAlignment.MiddleCenter,
            .Font = New Font("Segoe UI", 12, FontStyle.Bold),
            .ForeColor = Color.White,
            .Height = 50
        }

        ' Progress bar animation
        progress = New ProgressBar With {
            .Dock = DockStyle.Bottom,
            .Style = ProgressBarStyle.Marquee,
            .Height = 30
        }

        ' Add Controls
        Me.Controls.Add(lbl)
        Me.Controls.Add(progress)
    End Sub

    ' Optional nice border
    Private Sub DrawBorder(sender As Object, e As PaintEventArgs)
        Dim g As Graphics = e.Graphics
        Using p As New Pen(Color.White, 2)
            g.DrawRectangle(p, 1, 1, Me.Width - 2, Me.Height - 2)
        End Using
    End Sub
End Class
