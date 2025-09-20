
Imports Microsoft.Web.WebView2.WinForms
Imports System.Windows.Forms
Imports System.IO
Imports System.Text
Imports System.Text.RegularExpressions
Imports UglyToad.PdfPig

Public Class TrinityLogisticsControl
    Inherits UserControl

    Private WithEvents webView As WebView2

    Public Sub New()
        Me.Dock = DockStyle.Fill

        ' Initialize WebView2
        webView = New WebView2() With {
            .Dock = DockStyle.Fill
        }

        Me.Controls.Add(webView)

        ' Start the browser initialization and navigation
        InitializeAsync()
    End Sub

    Private Async Sub InitializeAsync()
        Try
            Await webView.EnsureCoreWebView2Async(Nothing)
            webView.Source = New Uri("https://www.ucbloops.com/loops/import_pdf_vendor_payment.php")
        Catch ex As Exception
            MessageBox.Show("WebView2 failed to load: " & ex.Message)
        End Try
    End Sub
End Class

'Imports System.IO
'Imports System.Windows.Forms
'Imports UglyToad.PdfPig

'Public Class TrinityLogisticsControl
'    Inherits UserControl

'    Private WithEvents btnUpload As Button
'    Private grid As DataGridView

'    Public Sub New()
'        Me.Dock = DockStyle.Fill

'        btnUpload = New Button() With {
'            .Text = "Upload PDF and Extract Invoice",
'            .Dock = DockStyle.Top,
'            .Height = 40
'        }

'        grid = New DataGridView() With {
'            .Dock = DockStyle.Fill,
'            .AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.Fill
'        }

'        Me.Controls.Add(grid)
'        Me.Controls.Add(btnUpload)
'    End Sub

'    Private Sub btnUpload_Click(sender As Object, e As EventArgs) Handles btnUpload.Click
'        Dim ofd As New OpenFileDialog With {.Filter = "PDF Files|*.pdf"}
'        If ofd.ShowDialog() = DialogResult.OK Then
'            ParsePdf(ofd.FileName)
'        End If
'    End Sub

'    Private Sub ParsePdf(pdfPath As String)
'        Dim rows As New List(Of List(Of String))()

'        Using document = PdfDocument.Open(pdfPath)
'            For Each page In document.GetPages()
'                ' Group words by Y position
'                Dim wordGroups = page.GetWords() _
'                    .GroupBy(Function(w) Math.Round(w.BoundingBox.Bottom, 0)) _
'                    .OrderByDescending(Function(g) g.Key)

'                For Each group In wordGroups
'                    Dim row As List(Of String) = group _
'                        .OrderBy(Function(w) w.BoundingBox.Left) _
'                        .Select(Function(w) w.Text).ToList()

'                    If row.Count > 0 Then rows.Add(row)
'                Next
'            Next
'        End Using

'        ' Convert to DataTable
'        Dim dt As New DataTable()
'        Dim maxCols = rows.Max(Function(r) r.Count)
'        For i As Integer = 0 To maxCols - 1
'            dt.Columns.Add("Col" & (i + 1))
'        Next

'        For Each r In rows
'            dt.Rows.Add(r.ToArray())
'        Next

'        grid.DataSource = dt
'    End Sub

'End Class




'Imports System.IO
'Imports System.Text.RegularExpressions
'Imports System.Windows.Forms
'Imports UglyToad.PdfPig

'Public Class TrinityLogisticsControl
'    Inherits UserControl

'    Private WithEvents btnUpload As Button
'    Private txtInvoiceDate As TextBox
'    Private txtMasterInvoice As TextBox
'    Private grid As DataGridView

'    Public Sub New()
'        Me.Dock = DockStyle.Fill

'        btnUpload = New Button() With {
'            .Text = "Upload PDF and Extract Invoice",
'            .Dock = DockStyle.Top,
'            .Height = 40
'        }

'        txtInvoiceDate = New TextBox() With {.Dock = DockStyle.Top, .Tag = "Invoice Date"}
'        txtMasterInvoice = New TextBox() With {.Dock = DockStyle.Top, .Tag = "Master Invoice #"}

'        grid = New DataGridView() With {
'            .Dock = DockStyle.Fill,
'            .AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.Fill
'        }

'        Me.Controls.Add(grid)
'        Me.Controls.Add(txtMasterInvoice)
'        Me.Controls.Add(txtInvoiceDate)
'        Me.Controls.Add(btnUpload)
'    End Sub


'    Private Sub btnUpload_Click(sender As Object, e As EventArgs) Handles btnUpload.Click
'        Dim ofd As New OpenFileDialog With {.Filter = "PDF Files|*.pdf"}
'        If ofd.ShowDialog() = DialogResult.OK Then
'            Dim pdfText As New Text.StringBuilder()

'            ' 🔹 Read PDF text
'            Using doc = PdfDocument.Open(ofd.FileName)
'                For Each page In doc.GetPages()
'                    pdfText.AppendLine(page.Text)
'                Next
'            End Using

'            ' 🔹 Regex: match whole invoice row until Total Charges
'            Dim pattern As String = "(INV\d+UCB.*?Total Charges)"
'            Dim matches = Regex.Matches(pdfText.ToString(), pattern, RegexOptions.Singleline)

'            ' 🔹 Prepare DataTable
'            Dim dt As New DataTable()
'            dt.Columns.Add("Invoice #")
'            dt.Columns.Add("PO Number")
'            dt.Columns.Add("Ship Date")
'            dt.Columns.Add("Amount")

'            For Each m As Match In matches
'                Dim line As String = m.Groups(1).Value.Replace(vbCr, " ").Replace(vbLf, " ")

'                ' Extract Invoice #
'                Dim invoiceMatch = Regex.Match(line, "(INV\d+UCB)")
'                Dim invoiceNo As String = If(invoiceMatch.Success, invoiceMatch.Value, "")

'                ' Extract Amount
'                Dim amountMatch = Regex.Match(line, "(\$\s*\d{1,3}(?:,\d{3})*(?:\.\d{2}))\s*Total Charges")
'                Dim amount As String = If(amountMatch.Success, amountMatch.Groups(1).Value, "")

'                ' Extract Ship Date (MM/DD/YYYY)
'                Dim dateMatch = Regex.Match(line, "\d{2}/\d{2}/\d{4}")
'                Dim shipDate As String = If(dateMatch.Success, dateMatch.Value, "")

'                ' Extract PO Number (number just before Ship Date, if any)
'                Dim poNo As String = ""
'                If dateMatch.Success Then
'                    Dim idx As Integer = line.IndexOf(dateMatch.Value)
'                    Dim numMatches = Regex.Matches(line, "\b\d+\b")

'                    Dim foundBeforeDate As Boolean = False
'                    For i As Integer = numMatches.Count - 1 To 0 Step -1
'                        If numMatches(i).Index < idx Then
'                            poNo = numMatches(i).Value
'                            foundBeforeDate = True
'                            Exit For
'                        End If
'                    Next

'                    ' Handle case where nothing found (like MANUAL row)
'                    If Not foundBeforeDate Then
'                        poNo = ""  ' or set to "N/A"
'                    End If
'                End If

'                dt.Rows.Add(invoiceNo, poNo, shipDate, amount)
'            Next

'            grid.DataSource = dt
'        End If
'    End Sub



'    'Private Sub btnUpload_Click(sender As Object, e As EventArgs) Handles btnUpload.Click
'    '    Dim ofd As New OpenFileDialog With {.Filter = "PDF Files|*.pdf"}
'    '    If ofd.ShowDialog() = DialogResult.OK Then
'    '        Dim pdfText As New Text.StringBuilder()

'    '        ' 🔹 Read PDF text
'    '        Using doc = PdfDocument.Open(ofd.FileName)
'    '            For Each page In doc.GetPages()
'    '                pdfText.AppendLine(page.Text)
'    '            Next
'    '        End Using

'    '        ' 🔹 Regex: match whole invoice row until Total Charges
'    '        Dim pattern As String = "(INV\d+UCB.*?Total Charges)"
'    '        Dim matches = Regex.Matches(pdfText.ToString(), pattern, RegexOptions.Singleline)

'    '        ' 🔹 Prepare DataTable
'    '        Dim dt As New DataTable()
'    '        dt.Columns.Add("Invoice #")
'    '        dt.Columns.Add("Trans #")
'    '        dt.Columns.Add("Amount")

'    '        For Each m As Match In matches
'    '            Dim line As String = m.Groups(1).Value.Replace(vbCr, " ").Replace(vbLf, " ")

'    '            ' Extract Invoice #
'    '            Dim invoiceMatch = Regex.Match(line, "(INV\d+UCB)")
'    '            Dim invoiceNo As String = If(invoiceMatch.Success, invoiceMatch.Value, "")

'    '            ' Extract Amount
'    '            Dim amountMatch = Regex.Match(line, "(\$\s*\d{1,3}(?:,\d{3})*(?:\.\d{2}))\s*Total Charges")
'    '            Dim amount As String = If(amountMatch.Success, amountMatch.Groups(1).Value, "")

'    '            ' Extract all numbers in the line
'    '            Dim numMatches = Regex.Matches(line, "\b\d+\b")
'    '            Dim transNo As String = ""

'    '            ' Look for duplicate adjacent numbers
'    '            For i As Integer = 0 To numMatches.Count - 2
'    '                If numMatches(i).Value = numMatches(i + 1).Value Then
'    '                    transNo = numMatches(i).Value
'    '                    Exit For
'    '                End If
'    '            Next

'    '            dt.Rows.Add(invoiceNo, transNo, amount)
'    '        Next

'    '        grid.DataSource = dt
'    '    End If
'    'End Sub

'    'Private Sub btnUpload_Click(sender As Object, e As EventArgs) Handles btnUpload.Click
'    '    Dim ofd As New OpenFileDialog With {.Filter = "PDF Files|*.pdf"}
'    '    If ofd.ShowDialog() = DialogResult.OK Then
'    '        Dim pdfText As New Text.StringBuilder()

'    '        ' 🔹 Read PDF text
'    '        Using doc = PdfDocument.Open(ofd.FileName)
'    '            For Each page In doc.GetPages()
'    '                pdfText.AppendLine(page.Text)
'    '            Next
'    '        End Using

'    '        ' 🔹 Regex: match whole invoice row until Total Charges
'    '        Dim pattern As String = "(INV\d+UCB.*?Total Charges)"
'    '        Dim matches = Regex.Matches(pdfText.ToString(), pattern, RegexOptions.Singleline)

'    '        ' 🔹 Prepare DataTable
'    '        Dim dt As New DataTable()
'    '        dt.Columns.Add("Invoice #")
'    '        dt.Columns.Add("Trans #")
'    '        dt.Columns.Add("Ship Date")
'    '        dt.Columns.Add("Amount")

'    '        For Each m As Match In matches
'    '            Dim line As String = m.Groups(1).Value.Replace(vbCr, " ").Replace(vbLf, " ")

'    '            ' Extract Invoice #
'    '            Dim invoiceMatch = Regex.Match(line, "(INV\d+UCB)")
'    '            Dim invoiceNo As String = If(invoiceMatch.Success, invoiceMatch.Value, "")

'    '            ' Extract Amount
'    '            Dim amountMatch = Regex.Match(line, "(\$\s*\d{1,3}(?:,\d{3})*(?:\.\d{2}))\s*Total Charges")
'    '            Dim amount As String = If(amountMatch.Success, amountMatch.Groups(1).Value, "")

'    '            ' Extract Ship Date (MM/DD/YYYY)
'    '            Dim dateMatch = Regex.Match(line, "\d{2}/\d{2}/\d{4}")
'    '            Dim shipDate As String = If(dateMatch.Success, dateMatch.Value, "")

'    '            ' Extract Trans #
'    '            Dim transNo As String = ""
'    '            Dim numMatches = Regex.Matches(line, "\b\d+\b")

'    '            ' Look for duplicate adjacent numbers
'    '            For i As Integer = 0 To numMatches.Count - 2
'    '                If numMatches(i).Value = numMatches(i + 1).Value Then
'    '                    transNo = numMatches(i).Value
'    '                    Exit For
'    '                End If
'    '            Next

'    '            ' If still empty, take the number just before Ship Date
'    '            If transNo = "" AndAlso dateMatch.Success Then
'    '                Dim idx As Integer = line.IndexOf(dateMatch.Value)
'    '                For i As Integer = numMatches.Count - 1 To 0 Step -1
'    '                    If numMatches(i).Index < idx Then
'    '                        transNo = numMatches(i).Value
'    '                        Exit For
'    '                    End If
'    '                Next
'    '            End If

'    '            dt.Rows.Add(invoiceNo, transNo, shipDate, amount)
'    '        Next

'    '        grid.DataSource = dt
'    '    End If
'    'End Sub




'End Class
