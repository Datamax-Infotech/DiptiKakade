<?php


require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

$supplier_loopid = 0;
$sql = "SELECT id, warehouse_name FROM loop_warehouse where b2bid = " . $_REQUEST['ID'] . " ";
//echo $sql;
db();
$result = db_query($sql);
$warehouse_name = "";
while ($myrowsel = array_shift($result)) {
    $supplier_loopid = $myrowsel["id"];
    $warehouse_name = $myrowsel["warehouse_name"];
}

// function encrypt_password($txt){
// 	$key = "1sw54@$sa$offj";

// 	$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
// 	$iv = openssl_random_pseudo_bytes($ivlen);
// 	$ciphertext_raw = openssl_encrypt($txt, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
// 	$hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
// 	$ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw );
// 	return $ciphertext;
// }

?>
<html>

<head>

    <title>Supplier Dashboard Setup</title>

    <style>
    .textbox-label {
        background: transperant;
        border: none;
        width: 300px;
        min-width: 90px;
        max-width: 300px;
        transition: width 0.25s;
    }
    </style>

    <script language="javascript">
    function supplierdash_chkfrm() {
        var mailformat = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        var supplierdash_username = document.getElementById('supplierdash_username');
        var supplierdash_pwd = document.getElementById('supplierdash_pwd');
        if (supplierdash_username.value == '') {
            alert("Please enter the Email as User name.");
            supplierdash_username.focus();
            return false;
        } else if (mailformat.test(supplierdash_username.value) == false) {
            alert("Enter valid email as User name!");
            supplierdash_username.focus();
            return false;
        } else if (supplierdash_pwd.value == '') {
            alert("Please enter the Password.");
            supplierdash_pwd.focus();
            return false;
        } else {
            document.supplierdash_adduser.submit();
            return true;
        }

    }

    function show_w_add_ctrl() {
        var sorting_warehouse_val = document.getElementById('sorting_warehouse').value;
        //alert('sorting_warehouse_val - '+sorting_warehouse_val)
        /*if (sorting_warehouse_val == "Other"){
        	document.getElementById('div_w_add_ctrl_1').style.display = "table-row";
        	document.getElementById('div_w_add_ctrl_2').style.display = "table-row";
        	document.getElementById('div_w_add_ctrl_3').style.display = "table-row";
        	document.getElementById('div_w_add_ctrl_4').style.display = "table-row";
        	document.getElementById('div_w_add_ctrl_5').style.display = "table-row";
        	document.getElementById('div_w_add_ctrl_6').style.display = "table-row";
        	document.getElementById('div_w_add_ctrl_7').style.display = "table-row";
        }else{
        	document.getElementById('div_w_add_ctrl_1').style.display = "none";
        	document.getElementById('div_w_add_ctrl_2').style.display = "none";
        	document.getElementById('div_w_add_ctrl_3').style.display = "none";
        	document.getElementById('div_w_add_ctrl_4').style.display = "none";
        	document.getElementById('div_w_add_ctrl_5').style.display = "none";
        	document.getElementById('div_w_add_ctrl_6').style.display = "none";
        	document.getElementById('div_w_add_ctrl_7').style.display = "none";
        }	*/

        var compid = <?php echo $_REQUEST["ID"]; ?>

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                //alert(xmlhttp.responseText)
                document.getElementById('res_sorting_warehouse').style.display = "block";
                document.getElementById('res_sorting_warehouse').innerHTML = xmlhttp.responseText;
                //alert(document.getElementById('lw_company_email').value)
                var lw_company_email = document.getElementById('lw_company_email').value;
                if (lw_company_email != '') {
                    document.getElementById('txtEmailCc2').value = lw_company_email;

                    /*var txtEmailTo = document.getElementById('txtEmailTo').value;
                    var txtEmailToVal = txtEmailTo.concat(lw_company_email+';');
                    document.getElementById('txtEmailTo').value = txtEmailToVal;
                    */
                }
            }
        }
        xmlhttp.open("GET", "sorting_warehouse_response.php?sorting_warehouse_val=" + sorting_warehouse_val +
            "&compid=" + compid, true);
        xmlhttp.send();


    }


    function supplierdash_dele(loginid, id) {
        var alertval = confirm("Are you sure you want to delete the supplier user.");
        if (alertval) {
            var useraction_flg = 1;
            var alertval2 = confirm(
                "Username will be deleted from this record. Do you wish to also delete this same username from all connected records as well?"
            );
            if (alertval2) {
                var useraction_flg = 2;
            }

            document.location = "supplierdashboard_deluser_new.php?loginid=" + loginid + "&companyid=" + id +
                "&useraction_flg=" + useraction_flg;
            return true;
        }
    }

    /*function supplierdash_sec_dele(sectionid, id) {
    	var alertval = confirm("Are you sure you want to delete the record.");
    	if (alertval) {
    		document.location = "supplierdashboard_del_sec_new.php?sectionid=" + sectionid + "&companyid=" + id+"&flg=delsec" ;
    		return true; 
    	}
    }	
    
    function supplierdash_item_sec_dele(sectionid, id) {
    	var alertval = confirm("Are you sure you want to delete the record.");
    	if (alertval) {
    		document.location = "supplierdashboard_del_sec_new.php?sectionid=" + sectionid + "&companyid=" + id +"&flg=delseccols" ;
    		return true; 
    	}
    }	
    
    function supplierdash_file_dele(sectionid, id,supplier_loopid) {
    	var alertval = confirm("Are you sure you want to delete the record.");
    	if (alertval) {
    		document.location = "supplierdashboard_del_sec_new.php?sectionid=" + sectionid + "&companyid=" + id +"&warehouse_id="+supplier_loopid+"&flg=delfiles";
    		return true; 
    	}
    }*/

    function supplierdash_dock_edit(id, warehouseid, compid) {

        var supplierdash_address_one = "";
        var supplierdash_address_two = "";
        var supplierdash_city = "";
        var supplierdash_state = "";
        var supplierdash_zip = "";
        var supplierdash_phone = "";
        var supplierdash_email_address = "";
        var supplierdash_sorting_warehouse = "";

        if (document.getElementById('supplierdash_sorting_warehouse' + id)) {
            supplierdash_sorting_warehouse = document.getElementById('supplierdash_sorting_warehouse' + id).value
        }
        if (document.getElementById('supplierdash_address_one' + id)) {
            supplierdash_address_one = document.getElementById('supplierdash_address_one' + id).value
        }
        if (document.getElementById('supplierdash_address_two' + id)) {
            supplierdash_address_two = document.getElementById('supplierdash_address_two' + id).value
        }
        if (document.getElementById('supplierdash_city' + id)) {
            supplierdash_city = document.getElementById('supplierdash_city' + id).value
        }
        if (document.getElementById('supplierdash_state' + id)) {
            supplierdash_state = document.getElementById('supplierdash_state' + id).value
        }
        if (document.getElementById('supplierdash_zip' + id)) {
            supplierdash_zip = document.getElementById('supplierdash_zip' + id).value
        }
        if (document.getElementById('supplierdash_phone' + id)) {
            supplierdash_phone = document.getElementById('supplierdash_phone' + id).value
        }
        if (document.getElementById('supplierdash_email_address' + id)) {
            supplierdash_email_address = document.getElementById('supplierdash_email_address' + id).value
        }

        var supplierdash_email_to = "";
        var supplierdash_email_cc = "";
        var supplierdash_email_bcc = "";
        var supplierdash_email_freight_comp = "";
        var supplierdash_email_delivery_name = "";
        var supplierdash_email_delivery_add = "";
        var supplierdash_email_delivery_company = "";
        var supplierdash_email_bol_notes = "";

        if (document.getElementById('supplierdash_email_to' + id)) {
            supplierdash_email_to = document.getElementById('supplierdash_email_to' + id).value
        }
        if (document.getElementById('supplierdash_email_cc' + id)) {
            supplierdash_email_cc = document.getElementById('supplierdash_email_cc' + id).value
        }
        if (document.getElementById('supplierdash_email_bcc' + id)) {
            supplierdash_email_bcc = document.getElementById('supplierdash_email_bcc' + id).value
        }
        if (document.getElementById('supplierdash_email_freight_comp' + id)) {
            supplierdash_email_freight_comp = document.getElementById('supplierdash_email_freight_comp' + id).value
        }
        if (document.getElementById('supplierdash_email_delivery_name' + id)) {
            supplierdash_email_delivery_name = document.getElementById('supplierdash_email_delivery_name' + id).value
        }
        if (document.getElementById('supplierdash_email_delivery_add' + id)) {
            supplierdash_email_delivery_add = document.getElementById('supplierdash_email_delivery_add' + id).value
        }
        if (document.getElementById('supplierdash_email_delivery_company' + id)) {
            supplierdash_email_delivery_company = document.getElementById('supplierdash_email_delivery_company' + id)
                .value
        }
        if (document.getElementById('supplierdash_email_bol_notes' + id)) {
            supplierdash_email_bol_notes = document.getElementById('supplierdash_email_bol_notes' + id).value
        }

        supplierdash_carrier_name = document.getElementById('supplierdash_carrier_name' + id).value

        document.location = "supplierdashboard_dock_edit.php?editrec=yes&id=" + id + "&warehouseid=" + warehouseid +
            "&companyid=" + compid +
            "&supplierdash_dock_nm=" + document.getElementById('supplierdash_dock_nm' + id).value +
            "&supplierdash_dock_order=" + document.getElementById('supplierdash_dock_order' + id).value +
            "&supplierdash_carrier_name=" + supplierdash_carrier_name + "&supplierdash_sorting_warehouse=" +
            supplierdash_sorting_warehouse +
            "&supplierdash_address_one=" + supplierdash_address_one + "&supplierdash_address_two=" +
            supplierdash_address_two +
            "&supplierdash_city=" + supplierdash_city + "&supplierdash_state=" + supplierdash_state +
            "&supplierdash_zip=" + supplierdash_zip + "&supplierdash_phone=" + supplierdash_phone +
            "&supplierdash_email_address=" + supplierdash_email_address +
            "&supplierdash_email_to=" + supplierdash_email_to + "&supplierdash_email_cc=" + supplierdash_email_cc +
            "&supplierdash_email_bcc=" + supplierdash_email_bcc +
            "&supplierdash_email_freight_comp=" + supplierdash_email_freight_comp +
            "&supplierdash_email_delivery_name=" + supplierdash_email_delivery_name +
            "&supplierdash_email_delivery_add=" + supplierdash_email_delivery_add +
            "&supplierdash_email_delivery_company=" + supplierdash_email_delivery_company +
            "&supplierdash_email_bol_notes=" + supplierdash_email_bol_notes;
    }

    function supplierdash_dock_dele(id, warehouseid, compid) {
        var alertval = confirm("Are you sure you want to delete the record.");
        if (alertval) {
            document.location = "supplierdashboard_dock_edit.php?deleterec=yes&id=" + id + "&warehouseid=" +
                warehouseid + "&companyid=" + compid;
            return true;
        }
    }

    function supplierdashEditSave() {
        var loginid = document.getElementById('loginid').value;
        var id = document.getElementById('ID').value;
        var warehouse_id = document.getElementById('warehouse_id').value;
        var companyid = document.getElementById('companyid').value;
        var txtDropName = document.getElementById('txtDropName').value;
        var sorting_warehouse = document.getElementById('sorting_warehouse').value;
        var DockCarrier = document.getElementById('DockCarrier').value;
        var txtEmailTo = document.getElementById('txtEmailTo').value;
        var txtEmailTo2 = document.getElementById('txtEmailTo2').value;
        var txtEmailCc = document.getElementById('txtEmailCc').value;
        var txtEmailCc2 = document.getElementById('txtEmailCc2').value;
        var txtEmailBcc = document.getElementById('txtEmailBcc').value;
        var txtEmailSub = document.getElementById('txtEmailSub').value;
        var txtDockCN = document.getElementById('txtDockCN').value;
        var txtShippingClass = document.getElementById('txtShippingClass').value;
        var txtEmailBOLNotes = document.getElementById('txtEmailBOLNotes').value;

        var supplierdash_username_edit = document.getElementById('supplierdash_username_edit' + loginid);
        var supplierdash_pwd_edit = document.getElementById('supplierdash_pwd_edit' + loginid);
        var supplierdash_flg = document.getElementById('supplierdash_flg' + loginid);
        var supplierdash_eml_edit = document.getElementById('supplierdash_eml_edit' + loginid);
        if (supplierdash_username_edit.value == '') {
            alert("Please enter the User name.");
            supplierdash_username_edit.focus();
            return false;
        } else if (supplierdash_pwd_edit.value == '') {
            alert("Please enter the Password.");
            supplierdash_pwd_edit.focus();
            return false;
        } else {
            //document.frmSupplierDashboard.submit(); 
            //return true;
            if (sorting_warehouse != '') {
                var txtShipFromLine1 = document.getElementById('txtShipFromLine1').value;
                var txtShipFromLine2 = document.getElementById('txtShipFromLine2').value;
                var txtShipFromLine3 = document.getElementById('txtShipFromLine3').value;
                var txtShipToLine1 = document.getElementById('txtShipToLine1').value;
                var txtShipToLine2 = document.getElementById('txtShipToLine2').value;
                var txtShipToLine3 = document.getElementById('txtShipToLine3').value;
                var txtShipToLine4 = document.getElementById('txtShipToLine4').value;
            } else {
                var txtShipFromLine1 = '';
                var txtShipFromLine2 = '';
                var txtShipFromLine3 = '';
                var txtShipToLine1 = '';
                var txtShipToLine2 = '';
                var txtShipToLine3 = '';
                var txtShipToLine4 = '';
            }

            txtShipFromLine1 = txtShipFromLine1.replace(/&/g, "`");

            document.location = "frmSupplierDashboardSave.php?warehouse_id=" + warehouse_id + "&companyid=" +
                companyid + "&supplierdash_username_edit=" + supplierdash_username_edit.value +
                "&supplierdash_pwd_edit=" + supplierdash_pwd_edit.value + "&user_edit=yes&loginid=" + loginid +
                "&supplierdash_flg=" + supplierdash_flg.value + "&supplierdash_eml_edit=" + supplierdash_eml_edit
                .value + "&txtDropName=" + txtDropName + "&sorting_warehouse=" + sorting_warehouse + "&DockCarrier=" +
                DockCarrier + "&txtEmailTo=" + txtEmailTo + "&txtEmailTo2=" + txtEmailTo2 + "&txtEmailCc=" +
                txtEmailCc + "&txtEmailCc2=" + txtEmailCc2 + "&txtEmailBcc=" + txtEmailBcc + "&txtEmailSub=" +
                txtEmailSub + "&txtDockCN=" + txtDockCN + "&txtShippingClass=" + txtShippingClass +
                "&txtEmailBOLNotes=" + txtEmailBOLNotes + "&txtShipFromLine1=" + txtShipFromLine1 +
                "&txtShipFromLine2=" + txtShipFromLine2 + "&txtShipFromLine3=" + txtShipFromLine3 + "&txtShipToLine1=" +
                txtShipToLine1 + "&txtShipToLine2=" + txtShipToLine2 + "&txtShipToLine3=" + txtShipToLine3 +
                "&txtShipToLine4=" + txtShipToLine4;
            return true;
        }
    }

    function addItem() {
        var txtAddItemCount = document.getElementById('txtAddItemCount');
        if (txtAddItemCount.value == '') {
            alert("Please enter the Item Count.");
            txtAddItemCount.focus();
            return false;
        }
        var txtAddItemWeight = document.getElementById('txtAddItemWeight');
        if (txtAddItemWeight.value == '') {
            alert("Please enter the Item Weight.");
            txtAddItemWeight.focus();
            return false;
        }
        var txtAddItem = document.getElementById('txtAddItem');
        if (txtAddItem.value == '') {
            alert("Please enter the Item.");
            txtAddItem.focus();
            return false;
        }
        document.frmSupplierdashAdditem.submit();
        return true;
    }

    function chkSupplierRequest() {
        var warehouse_id = document.getElementById('warehouse_id').value;
        var companyid = document.getElementById('companyid').value;
        var rdoDT_LL = document.querySelector('input[name="rdoDT_LL"]:checked');
        //alert('OK '+rdoDT_LL.value)
        document.location = "supplierdashboard_user_status.php?warehouse_id=" + warehouse_id + "&companyid=" +
            companyid + "&rdoDT_LL=" + rdoDT_LL.value + "&status=chkSupplierRequest";
        return true;
    }

    function chkSupplier_commodity() {
        var companyid = document.getElementById('companyid').value;
        var e = document.getElementById("comm_value");
        var obmval = e.options[e.selectedIndex].value;

        document.location = "supplierdashboard_user_status.php?companyid=" + companyid + "&obm=" + obmval +
            "&status=chkCommodm";
        return true;
    }

    function handleActive(loginid, id) {
        var checkbox = document.getElementById('supplierdash_flg' + loginid).checked;
        if (checkbox) {
            var alertval = confirm("Are you sure you want to 'activate' the supplier user.");
        } else {
            var alertval = confirm("Are you sure you want to 'deactivate' the supplier user.");
        }
        if (alertval) {
            var useraction_flg = 1;
            if (checkbox) {
                var alertval = confirm(
                    "Username will be 'activated' from this record. Do you wish to also 'activate' this same username from all connected records as well?"
                );
            } else {
                var alertval = confirm(
                    "Username will be 'deactivated' from this record. Do you wish to also 'deactivate' this same username from all connected records as well?"
                );
            }
            if (alertval) {
                var useraction_flg = 2;
            }

            document.location = "supplierdashboard_user_status.php?loginid=" + loginid + "&useraction_flg=" +
                useraction_flg + "&companyid=" + id + "&checkbox=" + checkbox + "&status=handleActive";
            return true;
        } else {
            return false;
        }
    }

    function setCarrierName() {
        var DockCarrier = document.getElementById('DockCarrier').value;
        if (DockCarrier != "") {
            var Docksel = document.getElementById("DockCarrier");
            var Docktext = Docksel.options[Docksel.selectedIndex].text;

            document.getElementById('txtDockCN').value = Docktext;
        }
        //alert('ok - '+DockCarrier);
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            $resArr = [];
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                //alert(xmlhttp.responseText)
                $resArr = xmlhttp.responseText.split('~');
                //document.getElementById('txtDockCN').value = $resArr[0];
                if ($resArr[1] != '') {
                    document.getElementById('txtEmailTo2').value = $resArr[1];
                    /*var txtEmailTo = document.getElementById('txtEmailTo').value;
                    var txtEmailToVal = txtEmailTo.concat($resArr[1]+';');
                    document.getElementById('txtEmailTo').value = txtEmailToVal;
                    */
                }
            }
        }
        xmlhttp.open("GET", "dock_carrier_response.php?DockCarrier=" + DockCarrier, true);
        xmlhttp.send();
    }

    function setNotes() {
        var txtDropName = document.getElementById('txtDropName').value;
        document.getElementById("txtEmailBOLNotes").value = txtDropName + ', load locks/straps must be used';
    }

    function setDockEditSection(id, warehouseid, compid) {
        document.location = "supplierdashboard_setup_new.php?ID=" + compid + "&warehouseid=" + warehouseid + "&id=" +
            id + "&doctEdit=yes";
        return true;
    }


    function editCancel(compid) {
        document.location = "supplierdashboard_setup_new.php?ID=" + compid;
        return true;
    }

    function setNotesEdit() {
        var txtDropName = document.getElementById('txtDropNameEdit').value;
        document.getElementById("txtEmailBOLNotesEdit").value = txtDropName + ', load locks/straps must be used';
    }

    function show_w_add_ctrlEdit(compid) {
        var sorting_warehouse_val = document.getElementById('sorting_warehouseEdit').value;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                //alert(xmlhttp.responseText)
                document.getElementById('res_sorting_warehouse_edit').style.display = "none";
                document.getElementById('res_sorting_warehouseEdit').style.display = "block";
                document.getElementById('res_sorting_warehouseEdit').innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET", "sorting_warehouse_response.php?sorting_warehouse_val=" + sorting_warehouse_val +
            "&compid=" + compid, true);
        xmlhttp.send();
    }

    function setCarrierNameEdit() {
        var DockCarrier = document.getElementById('DockCarrierEdit').value;
        //alert('ok - '+DockCarrier);
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                //alert(xmlhttp.responseText)
                document.getElementById('txtDockCNEdit').value = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET", "dock_carrier_response.php?DockCarrier=" + DockCarrier, true);
        xmlhttp.send();
    }

    function validateEmail(email) {
        var regexva =
            /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (email != '') {
            return regexva.test(email);
        } else {
            return true;
        }
    }

    function emailtocheck(txtEmail, inputtxtName) {
        var emailchk = "pass";
        if (txtEmail != "") {
            if (txtEmail.includes(',')) {
                const txtEmailarr = txtEmail.split(",");
                var flg = "no";
                for (let i = 0; i < txtEmailarr.length; i++) {
                    if (validateEmail(txtEmailarr[i]) == false) {
                        flg = "yes";
                        emailchk = "fail";
                        alert('Please enter a valid email address \n' + txtEmailarr[i] + '\n in [' + inputtxtName +
                            '].');
                    }
                }
                if (flg == "no") {
                    emailchk = "pass";
                }
            } else if (txtEmail.includes(';')) {
                const txtEmailarr = txtEmail.split(";");
                var flg = "no";
                for (let i = 0; i < txtEmailarr.length; i++) {
                    if (validateEmail(txtEmailarr[i]) == false) {
                        flg = "yes";
                        emailchk = "fail";
                        alert('Please enter a valid email address \n' + txtEmailarr[i] + '\n in [' + inputtxtName +
                            '].');
                    }
                }
                if (flg == "no") {
                    emailchk = "pass";
                }
            } else {
                if (validateEmail(txtEmail) == false) {
                    emailchk = "fail";
                    alert('Please enter a valid email address \n' + txtEmail + '\n in [' + inputtxtName + '].');
                } else {
                    emailchk = "pass";
                }
            }

        } else {
            emailchk = "pass";
        }

        return emailchk;
    }

    function editUpdate(id, warehouseid, compid) {

        var txtDropName = document.getElementById('txtDropNameEdit').value;
        var sorting_warehouse = document.getElementById('sorting_warehouseEdit').value;
        var DockCarrier = document.getElementById('DockCarrierEdit').value;
        var txtEmailTo = document.getElementById('txtEmailToEdit').value;
        var txtEmailTo2 = document.getElementById('txtEmailToEdit2').value;
        var txtEmailCc = document.getElementById('txtEmailCcEdit').value;
        var txtEmailCc2 = document.getElementById('txtEmailCcEdit2').value;
        var txtEmailBcc = document.getElementById('txtEmailBccEdit').value;
        var txtEmailSub = document.getElementById('txtEmailSubEdit').value;
        var txtDockCN = document.getElementById('txtDockCNEdit').value;
        var txtShippingClass = document.getElementById('txtShippingClassEdit').value;
        var txtEmailBOLNotes = document.getElementById('txtEmailBOLNotesEdit').value;

        emailchk = emailtocheck(txtEmailTo, 'Send auto email TO');
        if (emailchk == "fail") {
            return false;
        }
        emailchk = emailtocheck(txtEmailTo2, 'Send auto email TO 2');
        if (emailchk == "fail") {
            return false;
        }
        emailchk = emailtocheck(txtEmailCc, 'Send auto email CC');
        if (emailchk == "fail") {
            return false;
        }
        emailchk = emailtocheck(txtEmailCc2, 'Send auto email CC 2');
        if (emailchk == "fail") {
            return false;
        }
        emailchk = emailtocheck(txtEmailBcc, 'Send auto email BCC');
        if (emailchk == "fail") {
            return false;
        }

        if (sorting_warehouse != '') {
            var txtShipFromLine1 = document.getElementById('txtShipFromLine1').value;
            var txtShipFromLine2 = document.getElementById('txtShipFromLine2').value;
            var txtShipFromLine3 = document.getElementById('txtShipFromLine3').value;
            var txtShipToLine1 = document.getElementById('txtShipToLine1').value;
            var txtShipToLine2 = document.getElementById('txtShipToLine2').value;
            var txtShipToLine3 = document.getElementById('txtShipToLine3').value;
            var txtShipToLine4 = document.getElementById('txtShipToLine4').value;
        } else {
            var txtShipFromLine1 = '';
            var txtShipFromLine2 = '';
            var txtShipFromLine3 = '';
            var txtShipToLine1 = '';
            var txtShipToLine2 = '';
            var txtShipToLine3 = '';
            var txtShipToLine4 = '';
        }
        txtShipFromLine1 = txtShipFromLine1.replace(/&/g, "`");

        document.location = "frmSupplierDashboardSave.php?warehouse_id=" + warehouseid + "&companyid=" + compid +
            "&txtDropName=" + txtDropName + "&sorting_warehouse=" + sorting_warehouse + "&DockCarrier=" + DockCarrier +
            "&txtEmailTo=" + txtEmailTo + "&txtEmailTo2=" + txtEmailTo2 + "&txtEmailCc=" + txtEmailCc +
            "&txtEmailCc2=" + txtEmailCc2 + "&txtEmailBcc=" + txtEmailBcc + "&txtEmailSub=" + txtEmailSub +
            "&txtDockCN=" + txtDockCN + "&txtShippingClass=" + txtShippingClass + "&txtEmailBOLNotes=" +
            txtEmailBOLNotes + "&txtShipFromLine1=" + txtShipFromLine1 + "&txtShipFromLine2=" + txtShipFromLine2 +
            "&txtShipFromLine3=" + txtShipFromLine3 + "&txtShipToLine1=" + txtShipToLine1 + "&txtShipToLine2=" +
            txtShipToLine2 + "&txtShipToLine3=" + txtShipToLine3 + "&txtShipToLine4=" + txtShipToLine4 + "&editId=" +
            id + "&editUpdate=yes";
        return true;
    }

    function item_edit(id, warehouseid, compid) {
        //alert(id+" / "+warehouseid+" / "+compid)
        var itemCount = document.getElementById('itemCount' + id).value;
        var itemWeight = document.getElementById('itemWeight' + id).value;
        var itemDesc = document.getElementById('itemDesc' + id).value;

        document.location = "frmSupplierdashAdditemSave.php?warehouse_id=" + warehouseid + "&companyid=" + compid +
            "&editId=" + id + "&itemCount=" + itemCount + "&itemWeight=" + itemWeight + "&itemDesc=" + itemDesc +
            "&editItem=yes";
        return true;
    }

    function item_delete(id, warehouseid, compid) {
        var alertval = confirm("Are you sure you want to delete the record.");
        if (alertval) {
            document.location = "frmSupplierdashAdditemSave.php?deleteItem=yes&deleteId=" + id + "&warehouseid=" +
                warehouseid + "&companyid=" + compid;
            return true;
        }
    }

    function supplierpwd_update(id, compid) {
        var username = document.getElementById('supplierdash_username_edit' + id).value;
        var passnew = document.getElementById('supplierdash_pwd_edit' + id).value;
        var usnm = document.getElementById('supplierdash_username_edit' + id).value;

        var alertval = confirm("Are you sure you want to update password for user " + username);
        if (alertval) {
            document.location = "frmSupplierdashpwdupdate.php?update=yes&pwd=" + passnew + "&loginid=" + id +
                "&companyid=" + compid + "&usnm=" + usnm;
            return true;
        }
    }

    function supplierdash_AddNew_dock(compid) {
        document.location = "supplierdashboard_setup_new.php?ID=" + compid + "&doctAddnew=yes";
        return true;
    }

    function supplierdash_AddNew_bol(compid) {
        document.location = "supplierdashboard_setup_new.php?ID=" + compid + "&doctAddBol=yes";
        return true;
    }
    </script>
    <!-- tooltip style start -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- tooltip style end -->
    <link rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>
</head>

<?php

$ID = $_REQUEST["ID"];
$account_owner = 0;
$company_log = "";
db();
$res = db_query("SELECT * FROM supplier_dash_details WHERE companyid = $ID");
while ($fetch_data = array_shift($res)) {
    $account_owner = $fetch_data["accountmanager_empid"];
    $company_log = $fetch_data["logo_image"];
}

if ($account_owner == 0) {

    db_b2b();
    $res = db_query("SELECT assignedto FROM companyInfo WHERE ID = $ID");
    while ($fetch_data = array_shift($res)) {
        $account_owner = $fetch_data["assignedto"];
    }
    db();
    $res = db_query("INSERT INTO supplier_dash_details ( companyid, accountmanager_empid, logo_image) VALUES ($ID, $account_owner, '')");
}

db_b2b();
$res = db_query("SELECT company, haveNeed, email FROM companyInfo WHERE ID = $ID");
$buyer_seller_flg = 0;
$supplier_eml = "";
while ($fetch_data = array_shift($res)) {
    if ($fetch_data["haveNeed"] == "Have Boxes") {
        $buyer_seller_flg = 0;
    } else {
        $buyer_seller_flg = 1;
    }
    $supplier_eml = $fetch_data["email"];
    $supplier_company_name = get_nickname_val($fetch_data["company"], $ID);
}

if ($buyer_seller_flg == 0) {

    db();
    $res = db_query("SELECT section_id FROM supplier_dash_section_details WHERE companyid = $ID AND section_id = 1");
    $rec_found = "no";
    while ($fetch_data = array_shift($res)) {
        $rec_found = "yes";
    }
    if ($rec_found == "no") {

        db();
        $res = db_query("INSERT INTO supplier_dash_section_details ( companyid, section_id, activate_deactivate) VALUES ($ID, 1, 1)");
        $res = db_query("INSERT INTO supplier_dash_section_details ( companyid, section_id, activate_deactivate) VALUES ($ID, 2, 1)");
        $res = db_query("INSERT INTO supplier_dash_section_details ( companyid, section_id, activate_deactivate) VALUES ($ID, 3, 0)");
        $res = db_query("INSERT INTO supplier_dash_section_details ( companyid, section_id, activate_deactivate) VALUES ($ID, 4, 1)");
        $res = db_query("INSERT INTO supplier_dash_section_details ( companyid, section_id, activate_deactivate) VALUES ($ID, 5, 1)");
        $res = db_query("INSERT INTO supplier_dash_section_details ( companyid, section_id, activate_deactivate) VALUES ($ID, 6, 1)");
        $res = db_query("INSERT INTO supplier_dash_section_details ( companyid, section_id, activate_deactivate) VALUES ($ID, 7, 1)");
        $res = db_query("INSERT INTO supplier_dash_section_details ( companyid, section_id, activate_deactivate) VALUES ($ID, 8, 1)");
        $res = db_query("INSERT INTO supplier_dash_section_details ( companyid, section_id, activate_deactivate) VALUES ($ID, 9, 0)");

        $res = db_query("INSERT INTO supplier_dash_section_col_details ( companyid, section_col_id, displayflg) VALUES ($ID, 1, 1)");
        $res = db_query("INSERT INTO supplier_dash_section_col_details ( companyid, section_col_id, displayflg) VALUES ($ID, 2, 1)");
        $res = db_query("INSERT INTO supplier_dash_section_col_details ( companyid, section_col_id, displayflg) VALUES ($ID, 3, 1)");
        $res = db_query("INSERT INTO supplier_dash_section_col_details ( companyid, section_col_id, displayflg) VALUES ($ID, 4 ,1)");
        $res = db_query("INSERT INTO supplier_dash_section_col_details ( companyid, section_col_id, displayflg) VALUES ($ID, 5, 1)");
        $res = db_query("INSERT INTO supplier_dash_section_col_details ( companyid, section_col_id, displayflg) VALUES ($ID, 6, 1)");
        $res = db_query("INSERT INTO supplier_dash_section_col_details ( companyid, section_col_id, displayflg) VALUES ($ID, 7, 1)");
        $res = db_query("INSERT INTO supplier_dash_section_col_details ( companyid, section_col_id, displayflg) VALUES ($ID, 8, 1)");
        $res = db_query("INSERT INTO supplier_dash_section_col_details ( companyid, section_col_id, displayflg) VALUES ($ID, 9, 1)");
    }
}
?>

<body>
    <?php

    require_once('inc/header.php');

    ?>
    <div class="main_data_css">
        <br />
        <a href="viewCompany.php?ID=<?php echo $ID ?>">View B2B page</a> &nbsp;&nbsp;
        <a
            href="https://supplier.usedcardboardboxes.com/supplier_dashboard.php?companyid=<?php echo urlencode(encrypt_password($ID)); ?>&repchk=yes">View
            supplier Dashboard</a>
        <br />
        <table border="0" bgcolor="#F6F8E5" align="center"
            style="font-family:Arial, Helvetica, sans-serif; font-size:12;">
            <tr align="center">
                <td colspan="6" width="800px" bgcolor="#E8EEA8"><strong>
                        <?php
                        echo ((isset($supplier_company_name) == "") ? "Supplier" : isset($supplier_company_name));

                        ?> - Dashboard Setup
                    </strong></font>
                </td>
            </tr>
            <form method="post" name="supplierdash_adduser" action="supplierdashboard_adduser_new.php">
                <input type="hidden" name="hidden_companyid" value="<?php echo $ID; ?>" />

                <?php if (isset($_REQUEST["duprec"])) {
                    if ($_REQUEST["duprec"] == "yes") {

                        db();
                        $res = db_query("SELECT companyid FROM supplier_dash_usermaster WHERE user_name = '" . $_REQUEST["usrnm"] . "'");

                        $fetch_data = array_shift($res);
                        $cid = $fetch_data["companyid"];

                        db_b2b();
                        $ures = db_query("SELECT company, ID FROM companyInfo WHERE ID = '" . $cid . "'");
                        $ufetch_data = array_shift($ures);
                        //echo "old  name--".$cid."<br>";
                        $usr_company_name = get_nickname_val($ufetch_data["company"], $ufetch_data["ID"]);

                ?>
                <tr align="center">
                    <td colspan="6" width="960px" align="left" bgcolor="red" style="padding-left: 10px; color:#FFFFFF;">
                        This username already exists for <strong style="font-size: 13px;"><a
                                href='https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $ufetch_data["ID"]; ?>'
                                target="_blank">
                                <?php echo $usr_company_name; ?>
                            </a></strong>, add their username to this location as well?<br> <a href="supplierdashboard_adduser_new.php?hidden_companyid=<?php echo $ID; ?>&supplierdash_username=<?php echo $_REQUEST["
                            usrnm"] ?>&existing=new" style="color:#FFFFFF;">Yes</a> &nbsp;&nbsp;&nbsp; <a
                            href="supplierdashboard_setup_new.php?ID=<?php echo $ID; ?>&dupl_recheck=yes"
                            style="color:#FFFFFF;">No</a>

                        <!--User name already exists, record not added.-->
                    </td>
                </tr>
                <?php }
                } ?>
                <?php if (isset($_REQUEST["dupl_recheck"])) {
                    if ($_REQUEST["dupl_recheck"] == "yes") {
                ?>
                <tr align="center">
                    <td colspan="6" width="960px" align="left" bgcolor="red" style="padding-left: 10px; color:#FFFFFF;">
                        User name already exists, record not added.
                    </td>
                </tr>
                <?php

                    }
                }

                ?>

                <tr align="center">
                    <td colspan="6" width="960px" align="left" bgcolor="#C1C1C1">Create new login for this portal</td>
                </tr>
                <tr align="left">
                    <td width="80px">Email for login: </td>
                    <td colspan="5" width="800px" align="left"><input type="text" name="supplierdash_username"
                            id="supplierdash_username" value="" /></td>
                </tr>
                <tr align="left">
                    <td width="80px">Password: </td>
                    <td colspan="5" width="800px" align="left"><input type="password" name="supplierdash_pwd"
                            id="supplierdash_pwd" value="" /></td>
                </tr>
                <!-- <tr align="center">
				<td width="80px" >Email: </td>
				<td colspan="5" width="800px" align="left"><input type="text" name="supplierdash_eml" id="supplierdash_eml" value="<?php echo $supplier_eml; ?>" /></td>
			</tr> -->
                <tr align="center">
                    <td width="80px">&nbsp;</td>
                    <td colspan="5" align="left"><input type="button" name="supplierdash_adduser" value="Add"
                            onclick="supplierdash_chkfrm()" /></td>
                </tr>
            </form>
            <form name="frmSupplierDashboard" method="post" action="frmSupplierDashboardSave.php"
                encType="multipart/form-data">
                <!--  -->
                <input type="hidden" name="user_edit" id="user_edit" value="yes" />

                <tr align="center">
                    <td colspan="6" align="left" bgcolor="#C1C1C1">User List</td>
                </tr>
                <tr align="center">
                    <td width="80px">Email for Login</td>
                    <td width="80px" align="left">Password</td>
                    <td width="100px" align="left">
                        Active?
                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                            <span class="tooltiptext">
                                Check this box, if we want to activate this user. <br /> Uncheck this box, if we don't
                                want to activate this user.
                            </span>
                        </div>
                    </td>
                    <td width="100px" align="left">Password Update</td>
                    <td width="100px" align="left">&nbsp;</td>
                </tr>
                <?php

                $qry = "SELECT * FROM supplier_dash_usermaster WHERE companyid = $ID";
                db();
                $res = db_query($qry);
                while ($fetch_data = array_shift($res)) {

                ?>
                <input type="hidden" name="loginid" id="loginid" value="<?php echo $fetch_data[" loginid"]; ?>" />
                <input type="hidden" name="ID" id="ID" value="<?php echo $ID; ?>" />

                <tr align="center">
                    <td width="80px">
                        <input type="text" name="supplierdash_username_edit"
                            id="supplierdash_username_edit<?php echo $fetch_data[" loginid"]; ?>" value="
                        <?php echo $fetch_data["user_name"]; ?>" class="textbox-label" disabled />
                    </td>
                    <td width="80px" align="left">
                        <input type="password" name="supplierdash_pwd_edit"
                            id="supplierdash_pwd_edit<?php echo $fetch_data[" loginid"]; ?>" value="
                        <?php echo $fetch_data["password"]; ?>" />
                    </td>
                    <td width="100px" align="left">
                        <input type="checkbox" name="supplierdash_flg" id="supplierdash_flg<?php echo $fetch_data["
                            loginid"]; ?>" <?php if ($fetch_data["activate_deactivate"] == 1) {
                                                echo " checked ";
                                            } ?> onchange='handleActive(
                        <?php echo $fetch_data["loginid"]; ?>,
                        <?php echo $ID; ?>);' />
                    </td>

                    <td width="80px" align="left">
                        <input type="button" name="supplierdash_eml_edit" id="supplierdash_eml_edit<?php echo $fetch_data["
                            loginid"]; ?>" value="Update" onclick="supplierpwd_update(<?php echo $fetch_data["loginid"]; ?>,
                        <?php echo $ID; ?>)" />
                        <!-- <input type="hidden" name="supplierdash_eml_edit" id="supplierdash_eml_edit<?php echo $fetch_data["loginid"]; ?>" value="" /> -->
                    </td>
                    <td width="100px" align="left">
                        <input type="button" value="Delete" onclick="supplierdash_dele(<?php echo $fetch_data["loginid"]; ?>,
                        <?php echo $ID; ?>)" />
                    </td>
                </tr>
                <?php

                }

                ?>

                <input type="hidden" name="supplierdash_edituser_details_id" id="supplierdash_edituser_details_id"
                    value="<?php echo $ID; ?>" />

                <tr align="center">
                    <td colspan="6" align="left">&nbsp;</td>
                </tr>
        </table>
        <br>
        <table border="0" bgcolor="#F6F8E5" align="center"
            style="font-family:Arial, Helvetica, sans-serif; font-size:12;">
            <!--Drop Trailer or Live Load section start   -->
            <tr align="center">
                <td colspan="6" width="960px" align="left" bgcolor="#C1C1C1">
                    <font size="2" face="Arial">Will supplier know trailer # for each request?</font>
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <?php

                    $qry_2 = "SELECT drop_trailorOrlive_load FROM supplier_dash_details WHERE companyid =" . $_REQUEST['ID'];
                    //echo $qry_2;
                    db();
                    $res_2 = db_query($qry_2);
                    $fetch = array_shift($res_2);
                    $rdoDT_LL = $fetch['drop_trailorOrlive_load'];
                    $checkDT = $checkLL = '';
                    if (($rdoDT_LL == 'Drop Trailer') || ($rdoDT_LL == '')) {
                        $checkDT = 'checked="checked"';
                    } elseif ($rdoDT_LL == 'Live Load') {
                        $checkLL = 'checked="checked"';
                    }

                    ?>
                    <TABLE ALIGN='LEFT' border="0" width="500" bgcolor="#F6F8E5">
                        <tr align="left">
                            <td colSpan="3">
                                <input type="radio" name="rdoDT_LL" id="rdoDT_LL" value="Drop Trailer"
                                    <?php echo $checkDT ?>>
                                <font size="2" face="Arial">Yes (Drop trailer used)</font>
                            </td>
                            <td colSpan="3">
                                <input type="radio" name="rdoDT_LL" id="rdoDT_LL" value="Live Load"
                                    <?php echo $checkLL ?>>
                                <font size="2" face="Arial">No (Live loads, trailer # TBD)</font>
                                <input type="hidden" id="warehouse_id" name="warehouse_id"
                                    value="<?php echo $supplier_loopid; ?>">
                                <input type="hidden" id="companyid" name="companyid"
                                    value="<?php echo $_REQUEST['ID']; ?>">
                            </td>
                            <td colSpan="3">
                                <input type="button" name="btnSupplierReq" value="Save" onclick="chkSupplierRequest()"
                                    style="cursor:pointer;">
                            </td>
                        </tr>
                        <tr>
                            <td colSpan="5"></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <!--Drop Trailer or Live Load section ends  -->
            <tr align="center">
                <td colspan="6" align="left">&nbsp;</td>
            </tr>
        </table>
        <br>
        <table border="0" bgcolor="#F6F8E5" align="center"
            style="font-family:Arial, Helvetica, sans-serif; font-size:12;">
            <!--Commodity start   -->
            <tr align="center">
                <td colspan="6" width="960px" align="left" bgcolor="#C1C1C1">
                    <font size="2" face="Arial">Supplier default commodity</font>
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <TABLE ALIGN='LEFT' border="0" width="500" bgcolor="#F6F8E5">
                        <tr align="left">
                            <td colSpan="3">
                                <font size="2" face="Arial">Commodity:</font>
                            </td>
                            <td colSpan="3">
                                <select name="comm_value" id="comm_value">
                                    <option value="">Select One</option>
                                    <?php

                                    $oldrow = db_query("SELECT obm_value FROM supplier_dash_details WHERE companyid=" . $_REQUEST['ID']);
                                    $oldval = array_shift($oldrow);
                                    $result = db_query("SELECT * FROM loop_boxes_values WHERE obm_flg = 1");
                                    while ($row = array_shift($result)) {
                                        echo '<option value="' . $row['id'] . '" ' . (($row['id'] == $oldval['obm_value']) ? " selected " : "") . '>' . $row['name'] . '</option>';
                                    }

                                    ?>
                                </select>
                            </td>
                            <td colSpan="3">
                                <input type="button" name="btnSupplier_commodity" value="Save"
                                    onclick="chkSupplier_commodity()" style="cursor:pointer;">
                            </td>
                        </tr>
                        <tr>
                            <td colSpan="5"></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <!--Drop Trailer or Live Load section ends  -->
            <tr align="center">
                <td colspan="6" align="left">&nbsp;</td>
            </tr>
        </table>
        <br>
        <table border="0" bgcolor="#F6F8E5" align="center"
            style="font-family:Arial, Helvetica, sans-serif; font-size:12;">
            <!--Dock section start   -->
            <tr align="center">
                <td colspan="6" width="960px" align="left" bgcolor="#C1C1C1">
                    <font size="2" face="Arial">Pickup request logstics setup</font>
                </td>
            </tr>

            <!-- Dpck Edit section start -->
            <?php if ($_REQUEST['doctEdit'] == 'yes') {
                $getEditDockDtls = "SELECT * FROM supplier_dash_dock WHERE id = '" . $_REQUEST['id'] . "'";
                db();
                $resEditDockDtls = db_query($getEditDockDtls);
                //echo "<pre>"; print_r($resEditDockDtls); echo "</pre>";
                $rowEditDockDtls = array_shift($resEditDockDtls);
            ?>
            <tr id="dockEditSection">
                <td colspan="6">
                    <table align='left' border="0" width="100%"
                        style="font-family:Arial, Helvetica,sans-serif; font-size:12;">
                        <tr align="left" bgcolor="#E4D5D5">
                            <td align="Center" colspan="2"> Edit Dock : <?php echo $rowEditDockDtls['dock']; ?></td>
                        </tr>
                        <tr align="left">
                            <td width="20%">
                                <font size="2" face="Arial">Dock</font>
                            </td>
                            <td width="80%">
                                <input type="text" name="txtDropNameEdit" id="txtDropNameEdit"
                                    value="<?php echo $rowEditDockDtls['dock']; ?>" oninput="setNotesEdit()"
                                    style="width: 48%;">
                            </td>
                        </tr>
                        <tr align="left">
                            <td>
                                <font size="2" face="Arial">Sorting Warehouse</font>
                            </td>
                            <td>
                                <select name="sorting_warehouseEdit" id="sorting_warehouseEdit"
                                    onchange="show_w_add_ctrlEdit(<?php echo $_REQUEST['ID']; ?>)" style="width: 48%;">
                                    <option value="-">Select warehouse</option>
                                    <?php

                                        $sql = "SELECT * FROM loop_warehouse WHERE Active = 1 AND rec_type='Sorting' ORDER BY company_name";
                                        db();
                                        $dt_view_res = db_query($sql);
                                        while ($objData = array_shift($dt_view_res)) {
                                            if ($rowEditDockDtls['sorting_warehouse_id'] == $objData["id"]) {
                                                $selected = 'selected';
                                            } else {
                                                $selected = '';
                                            }
                                        ?>
                                    <option value="<?php echo $objData["id"] ?>" <?php echo $selected ?>>
                                        <?php echo $objData["company_name"]; ?></option>
                                    <?php

                                        }

                                        ?>
                                </select>
                            </td>
                        </tr>
                        <tr align="left">
                            <td>
                                <font size="2" face="Arial">Carrier</font>
                            </td>
                            <td>
                                <select name="DockCarrierEdit" id="DockCarrierEdit" onchange="setCarrierNameEdit();"
                                    style="width: 48%;">
                                    <?php

                                        $fsql = "SELECT * FROM loop_freightvendor ORDER BY company_name ASC";
                                        db();
                                        $fresult = db_query($fsql);
                                        while ($fmyrowsel = array_shift($fresult)) {
                                            if ($rowEditDockDtls['carrier'] == $fmyrowsel["id"]) {
                                                $selectedC = 'selected';
                                            } else {
                                                $selectedC = '';
                                            }
                                        ?>
                                    <option <?php echo $selectedC ?> value="<?php
                                                                                    echo $fmyrowsel[" id"]; ?>">
                                        <?php echo $fmyrowsel["company_name"]; ?>
                                    </option>
                                    <?php

                                        }

                                        ?>
                                </select>
                            </td>
                        </tr>
                        <tr align="left">
                            <td>
                                <font size="2" face="Arial">Send auto email TO</font>
                                <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                    <span class="tooltiptext">
                                        (Use ; to add multiple email addresses separately)
                                    </span>
                                </div>
                            </td>
                            <td>
                                <input type="text" name="txtEmailToEdit" id="txtEmailToEdit"
                                    value="<?php echo $rowEditDockDtls['email_to']; ?>" style="width: 48%;">
                                &nbsp;
                                <input type="text" name="txtEmailToEdit2" id="txtEmailToEdit2"
                                    value="<?php echo $rowEditDockDtls['email_to2']; ?>" style="width: 48%;">

                            </td>
                        </tr>
                        <tr align="left">
                            <td>
                                <font size="2" face="Arial">Send auto email CC</font>
                                <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                    <span class="tooltiptext">
                                        (Use ; to add multiple email addresses separately)
                                    </span>
                                </div>
                            </td>
                            <td>
                                <input type="text" name="txtEmailCcEdit" id="txtEmailCcEdit"
                                    value="<?php echo $rowEditDockDtls['email_cc']; ?>" style="width: 48%;">
                                &nbsp;
                                <input type="text" name="txtEmailCcEdit2" id="txtEmailCcEdit2"
                                    value="<?php echo $rowEditDockDtls['email_cc2']; ?>" style="width: 48%;">
                            </td>
                        </tr>
                        <tr align="left">
                            <td>
                                <font size="2" face="Arial">Send auto email BCC</font>
                                <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                    <span class="tooltiptext">
                                        (Use ; to add multiple email addresses separately)
                                    </span>
                                </div>
                            </td>
                            <td>
                                <input type="text" name="txtEmailBccEdit" id="txtEmailBccEdit"
                                    value="<?php echo $rowEditDockDtls['email_bcc']; ?>" style="width: 48%;">
                            </td>
                        </tr>
                        <tr align="left">
                            <td>
                                <font size="2" face="Arial">Send auto email SUBJECT</font>
                            </td>
                            <td>
                                <input type="text" name="txtEmailSubEdit" id="txtEmailSubEdit"
                                    value="<?php echo $rowEditDockDtls['email_subject']; ?>" style="width: 98%;">
                            </td>
                        </tr>
                        <tr align="left">
                            <td colspan="2" width="100%">
                                <div style="width: 100%;" id="res_sorting_warehouseEdit"></div>
                            </td>
                        </tr>
                        <tr align="left">
                            <td colspan="2" width="100%">
                                <div style="width: 100%;" id="res_sorting_warehouse_edit">
                                    <table align='left' border="0" width="100%" bgcolor="#F6F8E5">
                                        <tr align="left">
                                            <td width="20%">
                                                <font size="2" face="Arial">Ship From line 1</font>
                                            </td>
                                            <td width="80%" align="left"><input type="text" name="txtShipFromLine1"
                                                    id="txtShipFromLine1"
                                                    value="<?php echo trim($rowEditDockDtls['ship_from_line_1']); ?>"
                                                    style="width:48%; background-color: gray; border: none;" readonly>
                                            </td>
                                        </tr>
                                        <tr align="left">
                                            <td>
                                                <font size="2" face="Arial">Ship From line 2</font>
                                            </td>
                                            <td><input type="text" name="txtShipFromLine2" id="txtShipFromLine2"
                                                    value="<?php echo trim($rowEditDockDtls['ship_from_line_2']); ?>"
                                                    style="width: 48%; background-color: gray; border: none;" readonly>
                                            </td>
                                        </tr>
                                        <tr align="left">
                                            <td>
                                                <font size="2" face="Arial">Ship From line 3</font>
                                            </td>
                                            <td><input type="text" name="txtShipFromLine3" id="txtShipFromLine3"
                                                    value="<?php echo trim($rowEditDockDtls['ship_from_line_3']); ?>"
                                                    style="width: 48%; background-color: gray; border: none;" readonly>
                                            </td>
                                        </tr>

                                        <tr align="left">
                                            <td>
                                                <font size="2" face="Arial">Ship To line 1</font>
                                            </td>
                                            <td><input type="text" name="txtShipToLine1" id="txtShipToLine1"
                                                    value="<?php echo trim($rowEditDockDtls['ship_to_line_1']); ?>"
                                                    style="width: 48%;"></td>
                                        </tr>
                                        <tr align="left">
                                            <td>
                                                <font size="2" face="Arial">Ship To line 2</font>
                                            </td>
                                            <td><input type="text" name="txtShipToLine2" id="txtShipToLine2"
                                                    value="<?php echo trim($rowEditDockDtls['ship_to_line_2']); ?>"
                                                    style="width: 48%;"></td>
                                        </tr>
                                        <tr align="left">
                                            <td>
                                                <font size="2" face="Arial">Ship To line 3</font>
                                            </td>
                                            <td><input type="text" name="txtShipToLine3" id="txtShipToLine3"
                                                    value="<?php echo trim($rowEditDockDtls['ship_to_line_3']); ?>"
                                                    style="width: 48%;"></td>
                                        </tr>
                                        <tr align="left">
                                            <td>
                                                <font size="2" face="Arial">Ship To line 4</font>
                                            </td>
                                            <td><input type="text" name="txtShipToLine4" id="txtShipToLine4"
                                                    value="<?php echo trim($rowEditDockDtls['ship_to_line_4']); ?>"
                                                    style="width: 48%;"></td>
                                        </tr>

                                    </table>
                                </div>
                            </td>
                        </tr>
                        <tr align="left">
                            <td>
                                <font size="2" face="Arial">BOL carrier name</font>
                            </td>
                            <td>
                                <input type="text" name="txtDockCNEdit" id="txtDockCNEdit"
                                    value="<?php echo $rowEditDockDtls['carrier_name']; ?>" style="width: 48%;">
                            </td>
                        </tr>
                        <tr align="left">
                            <td>
                                <font size="2" face="Arial">BOL shipping class</font>
                            </td>
                            <td>
                                <input type="text" name="txtShippingClassEdit" id="txtShippingClassEdit"
                                    value="<?php echo $rowEditDockDtls['email_bol_shipping_class']; ?>"
                                    style="width: 48%;">
                            </td>
                        </tr>
                        <tr align="left">
                            <td>
                                <font size="2" face="Arial">BOL special instructions (notes)</font>
                            </td>
                            <td>
                                <input type="text" name="txtEmailBOLNotesEdit" id="txtEmailBOLNotesEdit"
                                    value="<?php echo $rowEditDockDtls['email_bol_notes']; ?>" style="width: 48%;">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center">
                                <input type="button" name="btnUpdateEdit" value="Update"
                                    onclick="editUpdate(<?php echo $rowEditDockDtls["id"]; ?>, <?php echo $supplier_loopid; ?>, <?php echo $_REQUEST["ID"]; ?>)">
                                <input type="button" name="btnCancelEdit" value="Cancel"
                                    onclick="editCancel(<?php echo $_REQUEST['ID']; ?>)">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <?php

            } else {

            ?>
            <tr>
                <td colspan="6">
                    <?php
                        $qryLastDO = "SELECT dock_order FROM supplier_dash_dock WHERE warehouse= '" . $supplier_loopid . "' ORDER BY id DESC LIMIT 1";
                        //echo "<br />".$qryLastDO;
                        db();
                        $resLastDO = db_query($qryLastDO);
                        $rowLastDO = array_shift($resLastDO);
                        //$rowLastDO = ""; !empty($rowLastDO) || 
                        if ($_REQUEST['doctAddnew'] == 'yes') {
                        ?>
                    <table align='left' border="0" width="100%" bgcolor="#F6F8E5">
                        <tr align="left" bgcolor="#E4D5D5">
                            <td align="Center" colspan="2">
                                <font size="2" face="Arial">Add New Dock</font>
                            </td>
                        </tr>
                        <tr align="left">
                            <td width="20%">
                                <font size="2" face="Arial">Dock</font>
                            </td>
                            <td width="80%">
                                <input type="text" name="txtDropName" id="txtDropName" value="" oninput="setNotes()"
                                    style="width: 48%;">
                            </td>
                        </tr>
                        <tr align="left">
                            <td>
                                <font size="2" face="Arial">Sorting Warehouse</font>
                            </td>
                            <td>
                                <select name="sorting_warehouse" id="sorting_warehouse" onchange="show_w_add_ctrl()"
                                    style="width: 48%;">
                                    <option value="-">Select warehouse</option>
                                    <?php
                                            $sql = "SELECT * FROM loop_warehouse WHERE Active = 1 AND rec_type='Sorting' ORDER BY company_name";
                                            db();
                                            $dt_view_res = db_query($sql);
                                            while ($objData = array_shift($dt_view_res)) {
                                            ?>
                                    <option value="<?php echo $objData["id"] ?>"><?php echo $objData["company_name"]; ?>
                                    </option>
                                    <?php
                                            }
                                            ?>
                                </select>
                            </td>
                        </tr>
                        <tr align="left">
                            <td>
                                <font size="2" face="Arial">Carrier</font>
                            </td>
                            <td>
                                <select name="DockCarrier" id="DockCarrier" onchange="setCarrierName();"
                                    style="width: 48%;">
                                    <?php
                                            $fsql = "SELECT * FROM loop_freightvendor ORDER BY company_name ASC";
                                            db();
                                            $fresult = db_query($fsql);
                                            while ($fmyrowsel = array_shift($fresult)) {
                                            ?>
                                    <option value="<?php echo $fmyrowsel[" id"]; ?>">
                                        <?php echo $fmyrowsel["company_name"]; ?>
                                    </option>
                                    <?php
                                            }
                                            ?>
                                </select>
                            </td>
                        </tr>
                        <tr align="left">
                            <td>
                                <font size="2" face="Arial">Send auto email TO</font>
                                <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                    <span class="tooltiptext">
                                        (Use ; to add multiple email addresses separately)
                                    </span>
                                </div>
                            </td>
                            <td>
                                <input type="text" name="txtEmailTo" id="txtEmailTo"
                                    value="TrailerPickup@UsedCardboardboxes.com" style="width: 48%;">
                                &nbsp;
                                <input type="text" name="txtEmailTo2" id="txtEmailTo2" value="" style="width: 48%;">
                            </td>
                        </tr>
                        <tr align="left">
                            <td>
                                <font size="2" face="Arial">Send auto email CC</font>
                                <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                    <span class="tooltiptext">
                                        (Use ; to add multiple email addresses separately)
                                    </span>
                                </div>
                            </td>
                            <?php

                                    db_b2b();
                                    $getCompData = db_query("SELECT email FROM companyInfo WHERE ID = " . $_REQUEST['ID']);
                                    $rowCompData = array_shift($getCompData);
                                    ?>
                            <td>
                                <input type="text" name="txtEmailCc" id="txtEmailCc"
                                    value="<?php echo $rowCompData['email']; ?>" style="width: 48%;">
                                &nbsp;
                                <input type="text" name="txtEmailCc2" id="txtEmailCc2" value="" style="width: 48%;">
                            </td>
                        </tr>
                        <tr align="left">
                            <td>
                                <font size="2" face="Arial">Send auto email BCC</font>
                                <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                    <span class="tooltiptext">
                                        (Use ; to add multiple email addresses separately)
                                    </span>
                                </div>
                            </td>
                            <td>
                                <input type="text" name="txtEmailBcc" id="txtEmailBcc" value="" style="width: 48%;">
                            </td>
                        </tr>
                        <tr align="left">
                            <td>
                                <font size="2" face="Arial">Send auto email SUBJECT</font>
                            </td>
                            <td>
                                <input type="text" name="txtEmailSub" id="txtEmailSub"
                                    value="{{nick_name}} has requested a trailer pickup on {{request_date}}"
                                    style="width: 98%;">
                            </td>
                        </tr>
                        <tr align="left">
                            <td colspan="2" width="100%">
                                <div style="width: 100%;" id="res_sorting_warehouse"></div>
                            </td>
                        </tr>
                        <!-- <div id="res_sorting_warehouse"></div> -->
                        <tr align="left">
                            <td>
                                <font size="2" face="Arial">BOL carrier name</font>
                            </td>
                            <td>
                                <input type="text" name="txtDockCN" id="txtDockCN" value="" style="width: 48%;">
                            </td>
                        </tr>
                        <tr align="left">
                            <td>
                                <font size="2" face="Arial">BOL shipping class</font>
                            </td>
                            <td>
                                <input type="text" name="txtShippingClass" id="txtShippingClass" value="125"
                                    style="width: 48%;">
                            </td>
                        </tr>
                        <tr align="left">
                            <td>
                                <font size="2" face="Arial">BOL special instructions (notes)</font>
                            </td>
                            <td>
                                <input type="text" name="txtEmailBOLNotes" id="txtEmailBOLNotes" value=" "
                                    style="width: 48%;">
                            </td>
                        </tr>
                        <tr align="center">
                            <td colspan="6" width="800px"><input type="button" name="supplier_dash_update_dtls"
                                    value="Add" onclick="supplierdashEditSave()" /></td>
                        </tr>
                    </table>

                    <?php

                        } else {

                        ?>
                    <table align='left' border="0" width="100%" bgcolor="#F6F8E5">
                        <?php if (empty($rowLastDO)) { ?>
                        <tr align="center">
                            <td colspan="2">
                                <font size="2" style="color:red;font-style:Italic;" face="Arial">
                                    Supplier will not be able to request trailers until this pickup request logistics
                                    setup is complete for at least 1 dock.
                                </font>
                            </td>
                        </tr>
                        <?php } ?>
                        <tr align="center">
                            <td colspan="2">
                                <input type="button" name="supplier_dash_add_dock" value="Add New Dock"
                                    onclick="supplierdash_AddNew_dock(<?php echo $_REQUEST[" ID"]; ?>)" />
                            </td>
                        </tr>
                    </table>
                    <?php
                        }
                        ?>
                </td>
            </tr>

            <?php
            }
            ?>
            <!-- Dpck Edit section end -->
            <tr align="center">
                <td colspan="6">
                    <?php

                    $getDockDtls = "SELECT * FROM supplier_dash_dock WHERE warehouse= '" . $supplier_loopid . "' ORDER BY id DESC";
                    //echo $getDockDtls;
                    //$res_2 = db_query($qry_2 , db());
                    db();
                    $resDockDtls = db_query($getDockDtls);
                    $reccnt = tep_db_num_rows($resDockDtls);
                    if ($reccnt > 0) {
                    ?>
                    <table style="font-family:Arial, Helvetica,sans-serif; font-size:12;">
                        <tr align="center">
                            <td width="100px" align="left" bgcolor="#E4D5D5">Dock</td>
                            <td width="120px" align="left" bgcolor="#E4D5D5">Carrier Name</td>
                            <td width="80px" align="left" bgcolor="#E4D5D5">Edit</td>
                            <td width="80px" align="left" bgcolor="#E4D5D5">Delete</td>
                        </tr>
                        <?php while ($fetch = array_shift($resDockDtls)) {
                            ?>
                        <tr align="center">
                            <td width="100px" align="left">
                                <?php echo $fetch["dock"]; ?>
                            </td>
                            <td width="120px" align="left">
                                <?php echo $fetch["carrier_name"]; ?>
                            </td>
                            <td width="80px" align="left">
                                <input type="button" value="Edit" onclick="setDockEditSection(<?php echo $fetch[" id"]; ?>,
                                <?php echo $supplier_loopid; ?>,
                                <?php echo $_REQUEST["ID"]; ?>)" />
                            </td>
                            <td width="80px" align="left"><input type="button" value="Delete" onclick="supplierdash_dock_dele(<?php echo $fetch[" id"]; ?>,
                                <?php echo $supplier_loopid; ?> ,
                                <?php echo $_REQUEST["ID"]; ?>)" />
                            </td>
                        </tr>
                        <tr align="center">
                            <td colspan="4">&nbsp;</td>
                        </tr>

                        <?php
                            }
                            ?>
                    </table>
                    <?php } ?>
                </td>
            </tr>
            <!--Dock section ends  -->
            <tr>
                <td colspan="5">
                    <hr />
                </td>
            </tr>
            </form>
            <tr align="center">
                <td colspan="6" align="left">&nbsp;</td>
            </tr>
        </table>
        <br>

        <table border="0" bgcolor="#F6F8E5" align="center"
            style="font-family:Arial, Helvetica, sans-serif; font-size:12;">
            <!--Item section start   -->
            <tr align="center">
                <td colspan="6" width="960px" align="left" bgcolor="#C1C1C1">
                    <font size="2" face="Arial">Pickup request BOL items setup</font>
                </td>
            </tr>

            <?php

            $sql_2 = "SELECT * FROM supplier_dash_item_info WHERE companyid = " . $ID . " ORDER BY id DESC";
            db();
            $result_2 = db_query($sql_2);
            $rec_cnt = tep_db_num_rows($result_2);
            //$rec_cnt = 0;
            //if ($rec_cnt > 0 || $_REQUEST['doctAddBol'] == 'yes') {

            if ($_REQUEST['doctAddBol'] == 'yes') {
            ?>
            <tr align="center">
                <td colspan="6" width="960px" align="left">
                    <font size="2" face="Arial">BOL item information</font>
                </td>
            </tr>

            <tr>
                <td colspan="6">
                    <form name="frmSupplierdashAdditem" method="post" action="frmSupplierdashAdditemSave.php"
                        encType="multipart/form-data">
                        <table align='left' border="0" width="100%"
                            style="font-family:Arial, Helvetica,sans-serif; font-size:12;">
                            <tr align="left">
                                <td>Count</td>
                                <td>Weight</td>
                                <td>Description</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr align="left">
                                <td>
                                    <input type="text" name="txtAddItemCount" id="txtAddItemCount" value="">
                                </td>
                                <td>
                                    <input type="text" name="txtAddItemWeight" id="txtAddItemWeight" value="">
                                </td>
                                <td>
                                    <input type="text" name="txtAddItem" id="txtAddItem" value="">
                                </td>
                                <td>
                                    <input type="hidden" id="warehouse_id" name="warehouse_id"
                                        value="<?php echo $supplier_loopid; ?>">
                                    <input type="hidden" id="companyid" name="companyid"
                                        value="<?php echo $_REQUEST['ID']; ?>">
                                    <input type="button" name="btnAddItem" value="Add" onclick="addItem()" />
                                </td>
                            </tr>
                        </table>
                </td>
            </tr>
            <?php

            }

            if ($rec_cnt > 0) { ?>
            <tr>
                <td colspan="6" align="center">
                    <table width="80%" style="font-family:Arial,Helvetica,sans-serif; font-size:12;">
                        <tr align="center" bgcolor="#E4D5D5">
                            <td width="10%">Count</td>
                            <td width="10%">Weight</td>
                            <td width="30%">Description</td>
                            <td width="10%">&nbsp;</td>
                            <td width="10%">&nbsp;</td>
                        </tr>
                        <?php
                            while ($myrow = array_shift($result_2)) {
                            ?>
                        <tr>
                            <td align="right"><input type="text" id="itemCount<?php echo $myrow["id"]; ?>"
                                    name="itemCount<?php echo $myrow["id"]; ?>" value="<?php echo $myrow['count'] ?>">
                            </td>
                            <td align="right"><input type="text" name="itemWeight<?php echo $myrow["id"]; ?>"
                                    id="itemWeight<?php echo $myrow["id"]; ?>" value="<?php echo $myrow['weight'] ?>">
                            </td>
                            <td align="left"><input type="text" name="itemDesc<?php echo $myrow["id"]; ?>"
                                    id="itemDesc<?php echo $myrow["id"]; ?>" value="<?php echo $myrow["item"] ?>"></td>
                            <td width="80px" align="center">
                                <input type="button" value="Update"
                                    onclick="item_edit(<?php echo $myrow["id"]; ?>, <?php echo $supplier_loopid; ?>, <?php echo $_REQUEST["ID"]; ?>)" />
                            </td>
                            <td width="80px" align="center"><input type="button" value="Delete"
                                    onclick="item_delete(<?php echo $myrow["id"]; ?>, <?php echo $supplier_loopid; ?>, <?php echo $_REQUEST["ID"]; ?>)" />
                            </td>
                        </tr>
                        <?php
                            }
                            ?>
                    </table>

                </td>
            </tr>
            <?php

            }
            ?>
            <tr>
                <td colspan="6">
                    <table align='left' border="0" width="100%" bgcolor="#F6F8E5">
                        <?php if ($rec_cnt == 0) { ?>
                        <tr align="center">
                            <td colspan="2">
                                <font size="2" style="color:red;font-style:Italic;" face="Arial">Suppler will not be
                                    able to request trailers until this pickup request BOL items setup is complete for
                                    at least 1 item.</font>
                            </td>
                        </tr>
                        <?php } ?>
                        <tr align="center">
                            <td colspan="2">
                                <input type="button" name="supplier_dash_add_bol_dock" value="Add New BOL Item"
                                    onclick="supplierdash_AddNew_bol(<?php echo $_REQUEST[" ID"]; ?>)" />
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <!--Item section ends  -->
        </table>
        <br />
    </div>
</body>

</html>