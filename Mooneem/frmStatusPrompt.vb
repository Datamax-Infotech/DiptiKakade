Public Class frmStatusPrompt

    Private Sub frmStatusPrompt_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        ProgressBar1.Style = ProgressBarStyle.Marquee
        ProgressBar1.MarqueeAnimationSpeed = 30
        lblMessage.Text = "Please wait, uploading invoice..."
    End Sub


End Class