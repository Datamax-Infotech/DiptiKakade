Imports System.Windows.Forms
Imports MySql.Data.MySqlClient

Public Class ucEditInvoicePopup
    Inherits UserControl

    ' Fields to track IDs
    Private _transId As Integer
    Private _whId As Integer
    Dim connString As String = "Server=208.109.231.62;Database=usedcardboardbox_production;Uid=usedcardboardbox_production_usr;Pwd=YtoA#[I[^.Ay;"
    Dim B2BconnString As String = "Server=208.109.231.62;Database=usedcardboardbox_b2b;Uid=usedcardboardbox_b2b_usr;Pwd=0JX+o3u4PM_l;"
    'Dim connString As String = "Server=localhost;Database=ucbdata_usedcard_production;Uid=root;Pwd=;"
    'Dim B2BconnString As String = "Server=localhost;Database=ucbdata_usedcard_b2b;Uid=root;Pwd=;"

    Private txtInvNo As New TextBox With {.Width = 300}
    'Private dtpDate As New DateTimePicker()
    Private WithEvents dtpDate As New DateTimePicker()

    Private txtAmount As New TextBox With {.Width = 300}
    Private txtComp As New TextBox With {.Width = 300}
    Private txtQBComp As New TextBox With {.Width = 300}
    Private btnUpdate As New Button With {.Text = "Update Data", .Width = 150, .Height = 40, .BackColor = Color.FromArgb(0, 150, 136), .ForeColor = Color.White}
    Public Sub New()
        ' Set font for the entire UserControl
        Me.Font = New Font("Verdana", 11)

        ' Build layout
        Dim layout As New TableLayoutPanel With {
        .Dock = DockStyle.Fill,
        .RowCount = 6,
        .ColumnCount = 2,
        .Padding = New Padding(20),
        .AutoSize = True
    }

        layout.ColumnStyles.Add(New ColumnStyle(SizeType.AutoSize))
        layout.ColumnStyles.Add(New ColumnStyle(SizeType.Percent, 100))
        layout.RowStyles.Clear()
        For i = 0 To 4
            layout.RowStyles.Add(New RowStyle(SizeType.AutoSize))
        Next
        layout.RowStyles.Add(New RowStyle(SizeType.Absolute, 50))

        ' Set control properties (same font, better spacing)
        txtInvNo.Font = New Font("Verdana", 11)
        txtAmount.Font = New Font("Verdana", 11)
        txtComp.Font = New Font("Verdana", 11)
        txtQBComp.Font = New Font("Verdana", 11)
        dtpDate.Font = New Font("Verdana", 11)
        btnUpdate.Font = New Font("Verdana", 11, FontStyle.Bold)

        dtpDate.Format = DateTimePickerFormat.Custom
        dtpDate.CustomFormat = "yyyy-MM-dd HH:mm:ss"

        ' Add controls
        layout.Controls.Add(New Label() With {.Text = "Invoice #", .AutoSize = True}, 0, 0)
        layout.Controls.Add(txtInvNo, 1, 0)

        layout.Controls.Add(New Label() With {.Text = "Invoice Date", .AutoSize = True}, 0, 1)
        layout.Controls.Add(dtpDate, 1, 1)

        layout.Controls.Add(New Label() With {.Text = "Amount", .AutoSize = True}, 0, 2)
        layout.Controls.Add(txtAmount, 1, 2)

        layout.Controls.Add(New Label() With {.Text = "Company Name", .AutoSize = True}, 0, 3)
        layout.Controls.Add(txtComp, 1, 3)

        layout.Controls.Add(New Label() With {.Text = "QB Company Name", .AutoSize = True}, 0, 4)
        layout.Controls.Add(txtQBComp, 1, 4)

        ' Align the button to right

        Dim btnPanel As New FlowLayoutPanel With {
    .FlowDirection = FlowDirection.LeftToRight,
    .Dock = DockStyle.Fill,
    .AutoSize = True,
    .WrapContents = False,
    .Anchor = AnchorStyles.None
}
        btnPanel.Controls.Add(btnUpdate)

        layout.Controls.Add(btnPanel, 0, 5)
        layout.SetColumnSpan(btnPanel, 2) ' ⭐ Important for centering



        Me.Controls.Add(layout)

        ' Event handler
        AddHandler btnUpdate.Click, AddressOf BtnUpdate_Click
    End Sub



    ' Load values from row
    Public Sub LoadData(transId As Integer, invNo As String, invDate As Date, amount As Decimal, comp As String, qbComp As String)
        _transId = transId
        txtInvNo.Text = invNo
        dtpDate.Value = invDate
        txtAmount.Text = amount.ToString()
        txtComp.Text = comp
        txtQBComp.Text = qbComp

        ' Get warehouse ID
        Using c As New MySqlConnection(connString)
            c.Open()
            Using cmd As New MySqlCommand("SELECT warehouse_id FROM loop_transaction_buyer WHERE id = @id", c)
                cmd.Parameters.AddWithValue("@id", _transId)
                _whId = Convert.ToInt32(cmd.ExecuteScalar())
            End Using
        End Using
    End Sub

    Private Sub BtnUpdate_Click(sender As Object, e As EventArgs)
        Try
            Using c As New MySqlConnection(connString)
                c.Open()
                Using trx = c.BeginTransaction()


                    ' Update loop_transaction_buyer
                    Using cmd As New MySqlCommand("UPDATE loop_transaction_buyer SET loop_qb_invoice_no = @invNo, inv_amount = @amt WHERE id = @id", c, trx)
                        cmd.Parameters.AddWithValue("@invNo", txtInvNo.Text.Trim())
                        cmd.Parameters.AddWithValue("@amt", Convert.ToDecimal(txtAmount.Text.Trim()))
                        cmd.Parameters.AddWithValue("@id", _transId)
                        cmd.ExecuteNonQuery()
                    End Using

                    ' UPSERT loop_invoice_details
                    Using cmd As New MySqlCommand("UPDATE loop_invoice_details SET timestamp = @dt WHERE trans_rec_id = @id", c, trx)
                        cmd.Parameters.Add("@dt", MySqlDbType.DateTime).Value = dtpDate.Value
                        cmd.Parameters.AddWithValue("@id", _transId)
                        cmd.ExecuteNonQuery()
                    End Using


                    ' Update loop_warehouse
                    Using cmd As New MySqlCommand("UPDATE loop_warehouse SET company_name = @comp, quick_books_company_name = @qb WHERE id = @whId", c, trx)
                        cmd.Parameters.AddWithValue("@comp", txtComp.Text.Trim())
                        cmd.Parameters.AddWithValue("@qb", txtQBComp.Text.Trim())
                        cmd.Parameters.AddWithValue("@whId", _whId)
                        cmd.ExecuteNonQuery()
                    End Using

                    trx.Commit()
                End Using
            End Using

            MessageBox.Show("Saved successfully!", "Info", MessageBoxButtons.OK, MessageBoxIcon.Information)

            ' Close parent modal
            Dim f = TryCast(Me.FindForm(), Form)
            If f IsNot Nothing Then
                f.DialogResult = DialogResult.OK
                f.Close()
            End If
        Catch ex As Exception
            MessageBox.Show("Error: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Sub
End Class
