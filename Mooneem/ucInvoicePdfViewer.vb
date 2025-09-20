Public Class ucInvoicePdfViewer
    Inherits UserControl

    Private WithEvents browser As New WebBrowser()

    Public Sub New()
        browser.Dock = DockStyle.Fill
        Controls.Add(browser)
    End Sub

    Public Sub LoadPdf(localFilePath As String)
        If System.IO.File.Exists(localFilePath) Then
            browser.Navigate(localFilePath)
        Else
            MessageBox.Show("PDF file not found.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End If
    End Sub
End Class
