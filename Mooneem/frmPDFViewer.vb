Imports System.IO
Imports System.Windows.Controls

Public Class frmPDFViewer
    Private pdfPath As String

    Public Sub New(pdfFilePath As String)
        InitializeComponent() ' This will exist since it's a Form
        Me.pdfPath = pdfFilePath
    End Sub

    Private Sub frmPDFViewer_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        If File.Exists(pdfPath) Then
            WebBrowser1.Navigate(pdfPath)
        Else
            MessageBox.Show("PDF file not found.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
            Me.Close()
        End If
    End Sub
End Class
