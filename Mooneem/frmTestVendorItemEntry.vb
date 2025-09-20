Imports QBFC12Lib

Public Class frmTestVendorItemEntry



    'Private Sub btnAddVendorBill_Click(sender As Object, e As EventArgs) Handles btnAddVendorBill.Click
    '    Try
    '        Dim sessMgr As New QBSessionManager()
    '        sessMgr.OpenConnection("", "Mooneem Vendor Bill Test")
    '        sessMgr.BeginSession("", ENOpenMode.omDontCare)

    '        Dim msgSetReq As IMsgSetRequest
    '        msgSetReq = sessMgr.CreateMsgSetRequest("US", 12, 0)
    '        msgSetReq.Attributes.OnError = ENRqOnError.roeContinue

    '        Dim billAdd As IVendorBillAdd = msgSetReq.AppendVendorBillAddRq()

    '        ' ✅ Change to existing vendor in your QuickBooks
    '        billAdd.VendorRef.FullName.SetValue("ABC Vendor")

    '        ' ✅ Item line 1
    '        Dim line1 As IVendorBillLineAdd = billAdd.ORVendorBillLineAddList.Append().VendorBillLineAdd
    '        line1.ItemRef.FullName.SetValue("Sample Item 1") ' Must exist
    '        line1.Quantity.SetValue(2)
    '        line1.Cost.SetValue(50)

    '        ' ✅ Item line 2
    '        Dim line2 As IVendorBillLineAdd = billAdd.ORVendorBillLineAddList.Append().VendorBillLineAdd
    '        line2.ItemRef.FullName.SetValue("Sample Item 2")
    '        line2.Quantity.SetValue(1)
    '        line2.Cost.SetValue(100)

    '        Dim msgSetResp As IMsgSetResponse = sessMgr.DoRequests(msgSetReq)
    '        Dim response As IResponse = msgSetResp.ResponseList.GetAt(0)

    '        If response.StatusCode = 0 Then
    '            MessageBox.Show("✅ Vendor Bill created successfully in QuickBooks.")
    '        Else
    '            MessageBox.Show("❌ Error: " & response.StatusMessage)
    '        End If

    '        sessMgr.EndSession()
    '        sessMgr.CloseConnection()

    '    Catch ex As Exception
    '        MessageBox.Show("❌ Exception: " & ex.Message)
    '    End Try
    'End Sub


End Class