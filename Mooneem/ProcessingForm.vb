Public Class ProcessingForm
    Public Sub New()
        ' Initialize components
        Me.Text = "Processing..."
        Me.Size = New Size(300, 100)
        Me.FormBorderStyle = FormBorderStyle.FixedDialog
        Me.StartPosition = FormStartPosition.CenterScreen
        Me.ControlBox = False
        Me.TopMost = True

        ' Create a label for the message
        Dim label As New Label() With {
            .Text = "Please wait, transactions are being processed...",
            .AutoSize = False,
            .TextAlign = ContentAlignment.MiddleCenter,
            .Dock = DockStyle.Fill
        }
        Me.Controls.Add(label)
    End Sub
End Class
