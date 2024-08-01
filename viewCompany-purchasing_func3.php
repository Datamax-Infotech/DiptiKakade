<?php
ini_set("display_errors", "1");
error_reporting(E_ERROR);

require_once("../mainfunctions/database.php");
require_once("../mainfunctions/general-functions.php");
require("tablefunctions_mrg_purchasing.php");
//require ("inc/functions_purchasing.php"); 
//require ("inc/databaseeml.php");

$b2bid = $_REQUEST['ID'];

if ($_REQUEST["show"] == "communications" or 1 == 1) { // && $b2bid > 0) {

	echo "<span class='font_family_Ariel font_size_4' color='#333333'><b>Communications</b><br><br>";
	$b2bid = $_REQUEST["ID"];
?>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="css/common_inline_style.css" rel="stylesheet" type="text/css">
	<style>
		/*Tooltip style*/
		.tooltip {
			position: relative;
			display: inline-block;

		}

		.tooltip .tooltiptext {
			visibility: hidden;
			width: 250px;
			background-color: #464646;
			color: #fff;
			text-align: left;
			border-radius: 6px;
			padding: 5px 7px;
			position: absolute;
			z-index: 1;
			top: -5px;
			left: 110%;
			/*white-space: nowrap;*/
			font-size: 12px;
			font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif !important;
		}

		.tooltip .tooltiptext::after {
			content: "";
			position: absolute;
			top: 35%;
			right: 100%;
			margin-top: -5px;
			border-width: 5px;
			border-style: solid;
			border-color: transparent black transparent transparent;
		}

		.tooltip:hover .tooltiptext {
			visibility: visible;
		}

		.fa-info-circle {
			font-size: 9px;
			color: #767676;
		}
	</style>

	<script language="javascript">
		function displayemail_mysqli(id) {
			parent.document.getElementById("light").innerHTML = document.getElementById("emlmsg" + id).innerHTML;

			selectobject = document.getElementById(id);
			n_left = f_getPosition(selectobject, 'Left');
			n_top = f_getPosition(selectobject, 'Top');

			parent.document.getElementById('light').style.left = (n_left + 100) + 'px';
			parent.document.getElementById('light').style.top = n_top + 250 + 'px';

			parent.document.getElementById('light').style.display = 'block';
		}

		function showwater_dropdown() {
			if (document.getElementById("chk_water_flg").checked) {
				document.getElementById("div_ucbzw_status").style.display = "block";
				document.getElementById("div_ucbzw_acc_owner").style.display = "block";
				document.getElementById("div_ucbzw_nxtdt").style.display = "block";
			} else {
				document.getElementById("div_ucbzw_status").style.display = "none";
				document.getElementById("div_ucbzw_acc_owner").style.display = "none";
				document.getElementById("div_ucbzw_nxtdt").style.display = "none";
			}
		}

		function showpallet_dropdown() {
			if (document.getElementById("chk_pallet_flg").checked) {
				document.getElementById("div_pallet_status").style.display = "block";
				document.getElementById("div_pallet_acc_owner").style.display = "block";
				document.getElementById("div_pallet_nxtdt").style.display = "block";
			} else {
				document.getElementById("div_pallet_status").style.display = "none";
				document.getElementById("div_pallet_acc_owner").style.display = "none";
				document.getElementById("div_pallet_nxtdt").style.display = "none";
			}
		}

		function get_crm_details(compid, searchEmail, eu, limit, page_name) {
			if ((document.getElementById("div_crm_data").style.display == "") || (document.getElementById("div_crm_data").style.display == "none")) {
				document.getElementById("div_crm_data").style.display = "inline";
				document.getElementById("div_crm_data").innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("div_crm_data").innerHTML = xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "loop_crm_get_details.php?b2bid=" + compid + "&eu=" + eu + "&limit=" + limit + "&searchEmail=" + encodeURIComponent(searchEmail) + "&page_name=" + encodeURIComponent(page_name), true);
				xmlhttp.send();
			} else {
				document.getElementById("div_crm_data").style.display = "inline";
			}
		}

		function collapse_get_crm_details() {
			document.getElementById("div_crm_data").style.display = "none";
		}

		function get_attachment_detail(attachID) {
			if ((document.getElementById("div_attachment_data").style.display == "") || (document.getElementById("div_attachment_data").style.display == "none")) {
				document.getElementById("div_attachment_data").style.display = "inline";
				document.getElementById("div_attachment_data").innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("div_attachment_data").innerHTML = xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "get_attachment_details.php?attachID=" + attachID, true);
				xmlhttp.send();
			} else {
				document.getElementById("div_attachment_data").style.display = "inline";
			}
		}

		function collapse_get_attachment_detail() {
			document.getElementById("div_attachment_data").style.display = "none";
		}

		function addtodoitem() {
			//alert(selectedText);
			document.getElementById("todo_div").innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />";

			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("todo_div").innerHTML = xmlhttp.responseText;
				}
			}

			var compid = document.getElementById('todo_companyID').value;
			var todo_message = document.getElementById('todo_message').value;
			var todo_employee = document.getElementById('todo_employee').value;
			var todo_date = document.getElementById('todo_date').value;
			var task_priority = document.getElementById('task_priority').value;
			var task_created_by = document.getElementById('todo_created_by').value;

			xmlhttp.open("GET", "todolist_update.php?compid=" + compid + "&task_created_by=" + encodeURIComponent(task_created_by) + "&todo_message=" + encodeURIComponent(todo_message) + "&todo_employee=" + todo_employee + "&todo_date=" + todo_date + "&task_priority=" + encodeURIComponent(task_priority), true);
			xmlhttp.send();
		}

		function get_owner_count(compid) {
			document.getElementById("ajaxResult").innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />";

			scountstdate = document.getElementById("scountstdate").value;
			scountendate = document.getElementById("scountendate").value;

			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("ajaxResult").innerHTML = xmlhttp.responseText;
				}
			}

			xmlhttp.open("GET", "ajaxModelbox.php?compid=" + compid + "&scountstdate=" + scountstdate + "&scountendate=" + scountendate, true);
			xmlhttp.send();
		}

		function displayemail_owner_cnt(id) {
			parent.document.getElementById("light").innerHTML = document.getElementById("emlmsg" + id).innerHTML;
			parent.document.getElementById('light').style.display = 'block';
			parent.document.getElementById('fade').style.display = 'block';
		}


		function display_owner_count(compid) {
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("div_owner_count").style.display = "block";
					document.getElementById("div_owner_count").innerHTML = xmlhttp.responseText;
				}
			}

			xmlhttp.open("GET", "viewCompany_showCount_modelbox.php?compid=" + compid, true);
			xmlhttp.send();
		}

		function update_edit_item(unqid) {
			document.getElementById("todo_div").innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />";

			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("todo_div").innerHTML = xmlhttp.responseText;
					document.getElementById('light_todo').style.display = 'none';
				}
			}

			var compid = document.getElementById('todo_companyID').value;
			var todo_message = document.getElementById('todo_message_edit').value;
			var todo_employee = document.getElementById('todo_employee_edit').value;
			var todo_date = document.getElementById('todo_date_edit').value;
			var task_priority = document.getElementById('task_priority_edit').value;

			xmlhttp.open("GET", "todolist_update.php?inedit_mode=1&unqid=" + unqid + "&compid=" + compid + "&todo_message=" + encodeURIComponent(todo_message) + "&todo_employee=" + todo_employee + "&todo_date=" + todo_date + "&task_priority=" + encodeURIComponent(task_priority), true);
			xmlhttp.send();
		} 

		function todoitem_edit(unqid, compid) {
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					selectobject = document.getElementById("todo_edit" + unqid);
					n_left = f_getPosition(selectobject, 'Left');
					n_top = f_getPosition(selectobject, 'Top');

					parent.document.getElementById("light_reminder").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light_reminder').style.display='none';document.getElementById('fade_todo').style.display='none'>Close</a> &nbsp;<center></center><br/>" + xmlhttp.responseText;
					parent.document.getElementById('light_reminder').style.display = 'block';

					parent.document.getElementById('light_reminder').style.left = (n_left + 30) + 'px';
					parent.document.getElementById('light_reminder').style.top = n_top + 200 + 'px';
					parent.document.getElementById('light_reminder').style.width = 700 + 'px';
					parent.document.getElementById('light_reminder').style.height = 200 + 'px';

				}
			}

			xmlhttp.open("GET", "todolist_edit_data.php?compid=" + compid + "&unqid=" + unqid, true);
			xmlhttp.send();
		}

		function todoitem_markcomp(unqid, compid) {
			//alert(selectedText);
			document.getElementById("todo_div").innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />";

			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("todo_div").innerHTML = xmlhttp.responseText;
				}
			}

			xmlhttp.open("GET", "todolist_update.php?compid=" + compid + "&unqid=" + unqid + "&markcomp=1", true);
			xmlhttp.send();
		}

		function f_getPosition(e_elemRef, s_coord) {
			var n_pos = 0,
				n_offset,
				e_elem = e_elemRef;

			while (e_elem) {
				n_offset = e_elem["offset" + s_coord];
				n_pos += n_offset;
				e_elem = e_elem.offsetParent;
			}

			e_elem = e_elemRef;
			while (e_elem != document.body) {
				n_offset = e_elem["scroll" + s_coord];
				if (n_offset && e_elem.style.overflow == 'scroll')
					n_pos -= n_offset;
				e_elem = e_elem.parentNode;
			}
			return n_pos;
		}

		function todoitem_showall(compid) {
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					selectobject = document.getElementById("todoshowall");
					n_left = f_getPosition(selectobject, 'Left');
					n_top = f_getPosition(selectobject, 'Top');

					document.getElementById("light_todo").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light_todo').style.display='none';document.getElementById('fade_todo').style.display='none'>Close</a> &nbsp;<center></center><br/>" + xmlhttp.responseText;
					document.getElementById('light_todo').style.display = 'block';

					document.getElementById('light_todo').style.left = (n_left - 200) + 'px';
					document.getElementById('light_todo').style.top = n_top - 40 + 'px';
					document.getElementById('light_todo').style.width = 700 + 'px';
				}
			}

			xmlhttp.open("GET", "todolist_view.php?compid=" + compid, true);
			xmlhttp.send();
		}

		function account_status_showall(compid) {
			//alert(compid);
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					selectobject = document.getElementById("account_status_history");
					n_left = f_getPosition(selectobject, 'Left');
					n_top = f_getPosition(selectobject, 'Top');

					document.getElementById("light_todo").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light_todo').style.display='none';document.getElementById('fade_todo').style.display='none'>Close</a> &nbsp;<center></center><br/>" + xmlhttp.responseText;
					document.getElementById('light_todo').style.display = 'block';

					//document.getElementById('light_todo').style.left=(n_Next Step Dateleft - 200) + 'px';
					document.getElementById('light_todo').style.left = 15 + 'px';
					document.getElementById('light_todo').style.top = n_top - 40 + 'px';
					document.getElementById('light_todo').style.width = 700 + 'px';
				}
			}

			xmlhttp.open("GET", "account_status_view.php?compid=" + compid, true);
			xmlhttp.send();
		}

		function account_status_showall_ucbw(compid) {
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					selectobject = document.getElementById("account_status_history_ucbw");
					n_left = f_getPosition(selectobject, 'Left');
					n_top = f_getPosition(selectobject, 'Top');

					document.getElementById("light_todo").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light_todo').style.display='none';document.getElementById('fade_todo').style.display='none'>Close</a> &nbsp;<center></center><br/>" + xmlhttp.responseText;
					document.getElementById('light_todo').style.display = 'block';

					document.getElementById('light_todo').style.left = 15 + 'px';
					document.getElementById('light_todo').style.top = n_top - 40 + 'px';
					document.getElementById('light_todo').style.width = 700 + 'px';
				}
			}

			xmlhttp.open("GET", "account_status_view_ucbzw.php?compid=" + compid, true);
			xmlhttp.send();
		}

		function sendemail_popup_sendrecordlink(compid) {
			var selectedText, selectobject, rec_type, skillsSelect, n_left, n_top;
			selectobject = document.getElementById("sendemailtrfacc");
			employeed = document.getElementById("employeed").value;
			n_left = f_getPosition(selectobject, 'Left');
			n_top = f_getPosition(selectobject, 'Top');

			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					parent.document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
					parent.document.getElementById('light_reminder').style.display = 'block';

					parent.document.getElementById('light_reminder').style.left = n_left + 50 + 'px';
					parent.document.getElementById('light_reminder').style.top = n_top + 50 + 'px';
				}
			}

			xmlhttp.open("POST", "fckeditor_email_sendrecordlink_mysqli.php?companyID=" + compid + "&employeed=" + employeed, true);
			xmlhttp.send();
		}

		function sendemail_popup_sendrecordlink_publichandoff(compid) {
			var selectedText, selectobject, rec_type, skillsSelect, n_left, n_top;
			selectobject = document.getElementById("sendemailtrfacc");
			employeed = document.getElementById("employeed_publich").value;
			n_left = f_getPosition(selectobject, 'Left');
			n_top = f_getPosition(selectobject, 'Top');

			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					parent.document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
					parent.document.getElementById('light_reminder').style.display = 'block';

					parent.document.getElementById('light_reminder').style.left = n_left + 50 + 'px';
					parent.document.getElementById('light_reminder').style.top = n_top + 50 + 'px';
				}
			}

			xmlhttp.open("POST", "fckeditor_email_sendrecordlink_publichandoff_mysqli.php?companyID=" + compid + "&employeed=" + employeed, true);
			xmlhttp.send();
		}


		function addLogsitem() {
			document.getElementById("log_div").innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />";
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("log_div").innerHTML = xmlhttp.responseText;
					document.getElementById('light_log').style.display = 'none';
					document.getElementById("logs_message").value = "";
					document.getElementById("logs_employee").value = "";
					document.getElementById("logs_date").value = "";
					document.getElementById("logs_priority").value = "";
				}
			}

			var logsCompanyID = document.getElementById('logs_companyID').value;
			var logsMessage = document.getElementById('logs_message').value;
			var logsCreatedBy = document.getElementById('logs_created_by').value;
			var logsEmployee = document.getElementById('logs_employee').value;
			var logsDate = document.getElementById('logs_date').value;
			var logsPriority = document.getElementById('logs_priority').value;

			xmlhttp.open("GET", "logs_details_update.php?logsCompanyID=" + logsCompanyID + "&logsCreatedBy=" + encodeURIComponent(logsCreatedBy) + "&logsMessage=" + encodeURIComponent(logsMessage) + "&logsEmployee=" + logsEmployee + "&logsDate=" + logsDate + "&logsPriority=" + encodeURIComponent(logsPriority), true);
			xmlhttp.send();
		}

		function log_markcomp(unqid, compid) {
			//alert(selectedText);
			document.getElementById("log_div").innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />";
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("log_div").innerHTML = xmlhttp.responseText;
					document.getElementById('light_log').style.display = 'none';
				}
			}

			xmlhttp.open("GET", "logs_details_update.php?logsCompanyID=" + compid + "&unqid=" + unqid + "&markcomp=1", true);
			xmlhttp.send();
		}

		function log_edits(unqid, compid) {
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					selectobject = document.getElementById("log_edit" + unqid);
					n_left = f_getPosition(selectobject, 'Left');
					n_top = f_getPosition(selectobject, 'Top');

					parent.document.getElementById('light').style.display = 'block';
					parent.document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=parent.document.getElementById('light').style.display='none';>Close</a> &nbsp;<center></center><br/>" + xmlhttp.responseText;

					parent.document.getElementById('light').style.width = 600 + 'px';
					parent.document.getElementById('light').style.height = 250 + 'px';
					parent.document.getElementById('light').style.left = (n_left + 100) + 'px';
					parent.document.getElementById('light').style.top = n_top + 400 + 'px';
				}
			}

			xmlhttp.open("GET", "logs_details_update.php?logsCompanyID=" + compid + "&unqid=" + unqid + '&edit_log=yes', true);
			xmlhttp.send();
		}

		function fun_log_delete(unqid, compid) {
			document.getElementById("log_div").innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />";
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("log_div").innerHTML = xmlhttp.responseText;
					document.getElementById('light_log').style.display = 'none';
				}
			}

			xmlhttp.open("GET", "logs_details_update.php?logsCompanyID=" + compid + "&unqid=" + unqid + "&logdel=1", true);
			xmlhttp.send();
		}

		function log_showall(compid) {
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					selectobject = document.getElementById("logshowall");
					n_left = f_getPosition(selectobject, 'Left');
					n_top = f_getPosition(selectobject, 'Top');

					document.getElementById("light_log").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light_log').style.display='none';document.getElementById('fade_todo').style.display='none'>Close</a> &nbsp;<center></center><br/>" + xmlhttp.responseText;
					document.getElementById('light_log').style.display = 'block';

					document.getElementById('light_log').style.left = (n_left - 200) + 'px';
					document.getElementById('light_log').style.top = n_top - 40 + 'px';
					document.getElementById('light_log').style.width = 700 + 'px';
				}
			}

			xmlhttp.open("GET", "logs_details_update.php?logsCompanyID=" + compid + '&logshowall=yes', true);
			xmlhttp.send();
		}


		function display_contact_eml() {
			if (document.getElementById('type').value == "phone") {
				document.getElementById('div_crm_comp_email').style.display = "inline";
			} else {
				document.getElementById('div_crm_comp_email').style.display = "none";
			}
		}

		function crm_validate() {
			if (document.getElementById('type').value == "phone") {
				if (document.getElementById('crm_comp_email').options.length > 1) {
					if (document.getElementById('crm_comp_email').value == "") {
						alert("Please select the Contact whom you have contacted.");
						return false;
					}
				}
			}
		}
	</script>

	<SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
	<script LANGUAGE="JavaScript">
		document.write(getCalendarStyles());
	</script>
	<script LANGUAGE="JavaScript">
		var cal2enxx = new CalendarPopup("listdiv_edit");
		cal2enxx.showNavigationDropdowns();

		var cal1nxxrep = new CalendarPopup("listdiv_edit");
		cal1nxxrep.showNavigationDropdowns();
	</script>

	<style type="text/css">
		.black_overlay {
			display: none !important;
			position: absolute;
			top: 0%;
			left: 0%;
			width: 100%;
			height: 100%;
			background-color: gray;
			z-index: 1001;
			-moz-opacity: 0.8;
			opacity: .80;
			filter: alpha(opacity=80);
		}

		.white_content {
			display: none !important;
			position: absolute;
			top: 5%;
			left: 10%;
			width: 60%;
			height: 90%;
			padding: 16px;
			border: 1px solid gray;
			background-color: white;
			z-index: 1002;
			overflow: auto;
		}
	</style>

	<div id="light_todo" class="white_content"></div>
	<div id="fade_todo" class="black_overlay"></div>
	<div id="light_log" class="white_content"></div>
	<?php
	$show_crm_flg = "yes";
	$crm_trans_tbl_flg = "yes";
	if ($_REQUEST["incrm"] == "yes") {
		$show_crm_flg = "no";
	}

	if ($_REQUEST["crm_trans_tbl_flg"] == "yes") {
		$crm_trans_tbl_flg = "no";
	}

	db_b2b();
	if ($b2bid > 0) {
		$x = "Select * from companyInfo Where ID =?";
		$dt_view_res = db_query($x, array("i"), array($b2bid));
		$row = array_shift($dt_view_res);
	} else {
		$x = "Select * from companyInfo Where ID = " . $_REQUEST["b2bid"];
		$dt_view_res = db_query($x, array("i"), array($_REQUEST['b2bid']));
		$row = array_shift($dt_view_res);
	}
	//echo $x;
	$crm_archive_flg = $row["crm_archive_flg"];
	$crm_archive_flg = $row["crm_archive_flg"];
	$arr = explode(",", $row["assignedto"]);

	$assign_id_1 = $row['assignedto'];
	$viewable1 = $row['viewable1'];
	$viewable2 = $row['viewable2'];
	$viewable3 = $row['viewable3'];
	$viewable4 = $row['viewable4'];

	$ucbzw_account_owner = $row['ucbzw_account_owner'];
	$ucbzw_account_status = $row['ucbzw_account_status'];

	$login_id = $_COOKIE["employeeid"];
	db();
	$ew = "SELECT id, b2b_id, name, level, status FROM `loop_employees` WHERE id= ?";
	$ew_res1 = db_query($ew, array("i"), array($login_id));
	$ew_row = array_shift($ew_res1);
	$login_b2bid = $ew_row["b2b_id"];
	$login_level = $ew_row["level"];

	if ($ew_row["status"] == "Inactive") {
		$login_level = 2; //set this so that Inactive emp can be set to new user
	}

	$update_flg = "yes";
	$disable_assign_btn = "no";

	if (($assign_id_1 != "") && ($assign_id_1 != $login_b2bid) && ($login_level != 2)) {
		$disable_assign_btn = "yes";
		//echo "In step 1 <br>";
	}

	if ($ucbzw_account_owner != "" && ($ucbzw_account_owner != $login_b2bid) && ($login_level != 2)) {
		$disable_assign_btn = "yes";
		if (($assign_id_1 != "") && ($assign_id_1 == $login_b2bid)) {
			$disable_assign_btn = "no";
		}
	}
	if ($ucbzw_account_owner != "" && ($ucbzw_account_owner == $login_b2bid)) {
		$disable_assign_btn = "no";
	}
	?>

	<table width="100%">
		<tr>
			<td valign="top">
				<input type="hidden" name="incrm" id="incrm" value="<?php echo $_REQUEST["incrm"] ?>">
				<input type="hidden" name="crm_trans_tbl_flg" id="crm_trans_tbl_flg" value="<?php echo $_REQUEST["crm_trans_tbl_flg"] ?>">

				<?php if ($_REQUEST["datasaved"] == "yes") { ?>
					<script>
						alert("Data updated successfully");
					</script>
				<?php }
				?>

				<table width="600" border="0" cellspacing="0" cellpadding="0" class="bg_color_e4e4e4">
					<tr align="center">
						<td colspan="2" class="bg_color_c0cdda">
							<span class='font_family_Ariel font_size_2' color="#333333">Assignments, Status, Next Steps and Notifications</span>
						</td>
					</tr>
					<tr>
						<td colspan="2" valign="middle" align="center"><span class='font_family_Ariel font_size_1' color="#333333"><br>
							</span>
						</td>
					</tr>
					<tr>
						<td>
							<form method="GET" ENCTYPE="multipart/form-data" action="assigntonew_mrg.php" name="nextstep" id="nextstep">
								<input type=hidden name="companyID" value="<?php echo $b2bid; ?>">
								<input type=hidden name="oldassignedto" value="<?php echo $row["assignedto"] ?>">
								<table>
									<tr>
										<td>
											<span class='font_family_Ariel font_size_2' color="#333333">UCB Account Owner</span><br />
											<select size="1" name="assignid1" id="assignid1">
												<option value="0">Assign To</option>
												<?php
												db_b2b();
												$qassign = "SELECT * FROM employees WHERE status='Active' order by name asc";
												$dt_view_res_assign = db_query($qassign);
												while ($res_assign = array_shift($dt_view_res_assign)) {
													if ($row["assignedto"] != $res_assign["employeeID"]) {
												?>
														<option value="<?php echo $res_assign["employeeID"] ?>"><?php echo $res_assign["name"] ?></option>
													<?php
													} else { ?>
														<option selected value="<?php echo $res_assign["employeeID"] ?>"><?php echo $res_assign["name"]; ?></option>
												<?php
													}
												}
												?>
											</select>
										</td>
									</tr>
								</table>
								<br />
								<span class='font_family_Ariel font_size_2' color="#333333">Also Viewable By</span><br />
								<select size="1" name="assignid2">
									<option value="0">Assign To</option>
									<?php
									$qassign = "SELECT * FROM employees WHERE status='Active' order by name asc";
									$dt_view_res_assign = db_query($qassign);
									while ($res_assign = array_shift($dt_view_res_assign)) {
										if ($row["viewable1"] != $res_assign["employeeID"]) {
									?>
											<option value="<?php echo $res_assign["employeeID"] ?>"><?php echo $res_assign["name"] ?></option>
										<?php
										} else { ?>
											<option selected value="<?php echo $res_assign["employeeID"] ?>"><?php echo $res_assign["name"]; ?></option>
									<?php
										}
									}
									?>
								</select>
								<br />
								<select size="1" name="assignid3">
									<option value="0">Assign To</option>
									<?php

									$qassign = "SELECT * FROM employees WHERE status='Active' order by name asc";
									$dt_view_res_assign = db_query($qassign);
									while ($res_assign = array_shift($dt_view_res_assign)) {
										if ($row["viewable2"] != $res_assign["employeeID"]) {
									?>
											<option value="<?php echo $res_assign["employeeID"] ?>"><?php echo $res_assign["name"] ?></option>
										<?php
										} else { ?>
											<option selected value="<?php echo $res_assign["employeeID"] ?>"><?php echo $res_assign["name"]; ?></option>
									<?php
										}
									}
									?>
								</select>
								<br />
								<select size="1" name="assignid4">
									<option value="0">Assign To</option>
									<?php

									$qassign = "SELECT * FROM employees WHERE status='Active' order by name asc";
									$dt_view_res_assign = db_query($qassign);
									while ($res_assign = array_shift($dt_view_res_assign)) {
										if ($row["viewable3"] != $res_assign["employeeID"]) {
									?>
											<option value="<?php echo $res_assign["employeeID"] ?>"><?php echo $res_assign["name"] ?></option>
										<?php
										} else { ?>
											<option selected value="<?php echo $res_assign["employeeID"] ?>"><?php echo $res_assign["name"]; ?></option>
									<?php
										}
									}
									?>
								</select>
								<br />
								<select size="1" name="assignid5">
									<option value="0">Assign To</option>
									<?php

									$qassign = "SELECT * FROM employees WHERE status='Active' order by name asc";
									$dt_view_res_assign = db_query($qassign);
									while ($res_assign = array_shift($dt_view_res_assign)) {
										if ($row["viewable4"] != $res_assign["employeeID"]) {
									?>
											<option value="<?php echo $res_assign["employeeID"] ?>"><?php echo $res_assign["name"] ?></option>
										<?php
										} else { ?>
											<option selected value="<?php echo $res_assign["employeeID"] ?>"><?php echo $res_assign["name"]; ?></option>
									<?php
										}
									}
									?>
								</select>
						</td>

						<td valign="top">
							<?php
							$ucbzw_account_owner = "";
							$ucbzw_status = "";
							$chk_water_flg = 0;
							$chk_pallet_flg = 0;
							$pallet_account_owner = "";
							$pallet_status = "";
							$qassign = "SELECT ucbzw_account_owner, ucbzw_account_status, ucbzw_flg, pallet_flg, pallet_account_owner, pallet_account_status FROM companyInfo where ID =?";
							$dt_view_res_assign = db_query($qassign, array("i"), array($_REQUEST['ID']));
							while ($res_assign = array_shift($dt_view_res_assign)) {
								$ucbzw_account_owner = $res_assign["ucbzw_account_owner"];
								$ucbzw_status = $res_assign["ucbzw_account_status"];
								$chk_water_flg = $res_assign["ucbzw_flg"];

								$pallet_account_owner = $res_assign["pallet_account_owner"];
								$pallet_status = $res_assign["pallet_account_status"];
								$chk_pallet_flg = $res_assign["pallet_flg"];
							}

							$div_style1 = "";
							if ($chk_water_flg == 0) {
								$div_style1 = "display:none;";
							} ?>
							<div style="display: flex;">
								<div id="div_ucbzw_acc_owner" style="width: 50%;<?php echo $div_style1; ?>">
									<table>
										<tr>
											<td>
												<span class='font_family_Ariel font_size_2' color="#333333">UCBZeroWaste Account Owner</span><br />
												<select size="1" name="ucbzw_assignid1" id="ucbzw_assignid1">
													<option value="0">Assign To</option>
													<?php

													$qassign = "SELECT * FROM employees WHERE status='Active' order by name asc";
													$dt_view_res_assign = db_query($qassign);
													while ($res_assign = array_shift($dt_view_res_assign)) {
														if ($ucbzw_account_owner == $res_assign["employeeID"]) {
													?>
															<option selected value="<?php echo $res_assign["employeeID"] ?>"><?php echo $res_assign["name"] ?></option>
														<?php
														} else { ?>
															<option value="<?php echo $res_assign["employeeID"] ?>"><?php echo $res_assign["name"]; ?></option>
													<?php
														}
													}
													?>
												</select>
											</td>
										</tr>
									</table>
								</div>

								<?php
								$div_style1 = "";
								if ($chk_pallet_flg == 0) {
									$div_style1 = "display:none;";
								} ?>
								<div id="div_pallet_acc_owner" style="width: 50%;<?php echo $div_style1; ?>">
									<table>
										<tr>
											<td>
												<span class='font_family_Ariel font_size_2' color="#333333">Pallet Account Owner</span><br />
												<select size="1" name="pallet_assignid1" id="pallet_assignid1">
													<option value="0">Assign To</option>
													<?php

													$qassign = "SELECT * FROM employees WHERE status='Active' order by name asc";
													$dt_view_res_assign = db_query($qassign);
													while ($res_assign = array_shift($dt_view_res_assign)) {
														if ($pallet_account_owner == $res_assign["employeeID"]) {
													?>
															<option selected value="<?php echo $res_assign["employeeID"] ?>"><?php echo $res_assign["name"] ?></option>
														<?php
														} else { ?>
															<option value="<?php echo $res_assign["employeeID"] ?>"><?php echo $res_assign["name"]; ?></option>
													<?php
														}
													}
													?>
												</select>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</td>
					</tr>

					<?php if (($row["haveNeed"] == "Need Boxes") || ($row["haveNeed"] == "Looking / Have Boxes") || ($row["haveNeed"] == "Have Boxes")) { ?>
						<tr>
							<td width="12%" colspan="2">
								<hr />
							</td>
						</tr>

						<tr>
							<td width="12%" valign="middle" align="left"><span class='font_family_Ariel font_size_2' color="#333333">

									<table>
										<tr>
											<td>
												<span class='font_family_Ariel font_size_2' color="#333333">UCB Account Status</span><br />
												<select size="1" name="status" id="status" style="width:180px;">
													<option value="0">Select the Status</option>
													<?php
													$val = "";
													$status = "Select * from status ";
													if (($row["haveNeed"] == "Need Boxes")) {
														$status .= "where (sales_flg = 1 or sales_flg = 2) order by sort_order";
													} else {
														$status .= "where (sales_flg = 0 or sales_flg = 2) order by sort_order";
													}
													$dt_view_res4 = db_query($status);
													while ($objStatus = array_shift($dt_view_res4)) {

													?>
														<option value="<?php echo $objStatus["id"] ?>" <?php if ($objStatus["id"] == $row["status"])
																											echo " selected "; ?>>
															<?php echo $objStatus["name"]; ?>
														</option>
													<?php
													}
													?>
												</select>
												<br>
												&nbsp;<a style="cursor:pointer;" id="account_status_history" onclick="account_status_showall(<?php echo $b2bid; ?>); return false;"><span class='font_size_1'><u>Show Status History</span></u></a>
											</td>
									</table>
							</td>

							<td valign="top">

								<div style="display: flex;">
									<?php
									$div_style = "";
									$next_step_cols = 25;
									if ($chk_water_flg == 0) {
										$div_style = "display:none;";
										$next_step_cols = 60;
									} ?>

									<div id="div_ucbzw_status" style="width: 50%; <?php echo $div_style; ?> ">
										<table>
											<tr>
												<td>
													<span class='font_family_Ariel font_size_2' color="#333333">UCBZW Account Status</span><br />
													<select size="1" name="ucbzw_status" id="ucbzw_status" style="width:180px">
														<option value="0">Select the Status</option>
														<?php
														$val = "";
														$status = "Select * from status ";
														$status .= "where (sales_flg = 3) order by sort_order";
														$dt_view_res4 = db_query($status);
														while ($objStatus = array_shift($dt_view_res4)) {
														?>
															<option value="<?php echo $objStatus["id"] ?>" <?php if ($objStatus["id"] == $ucbzw_status)
																												echo " selected "; ?>>
																<?php echo $objStatus["name"]; ?>
															</option>
														<?php
														}
														?>
													</select>
													<br>
													&nbsp;<a style="cursor:pointer;" id="account_status_history_ucbw" onclick="account_status_showall_ucbw(<?php echo $b2bid; ?>); return false;"><span class='font_size_1'><u>Show Status History</span></u></a>

												</td>
											</tr>
										</table>
									</div>

									<?php
									$div_style_pallet = "";
									if ($chk_pallet_flg == 0) {
										$div_style_pallet = "display:none;";
									} else {
										$next_step_cols = 25;
									}
									?>

									<div id="div_pallet_status" style="width: 50%; <?php echo $div_style_pallet; ?>">
										<table>
											<tr>
												<td>
													<span class='font_family_Ariel font_size_2' color="#333333">Pallet Account Status</span><br />
													<select size="1" name="pallet_status" id="pallet_status" style="width:180px">
														<option value="0">Select the Status</option>
														<?php
														$val = "";
														$status = "Select * from status ";
														if (($row["haveNeed"] == "Need Boxes")) {
															$status .= "where (sales_flg = 1 or sales_flg = 2) order by sort_order";
														} else {
															$status .= "where (sales_flg = 0 or sales_flg = 2) order by sort_order";
														}
														$dt_view_res4 = db_query($status);
														while ($objStatus = array_shift($dt_view_res4)) {
														?>
															<option value="<?php echo $objStatus["id"] ?>" <?php if ($objStatus["id"] == $pallet_status)
																		echo " selected "; ?>>
																<?php echo $objStatus["name"]; ?>
															</option>
														<?php
														}
														?>
													</select>
													<br>
													&nbsp;<a style="cursor:pointer;" id="account_status_history_pallet" onclick="account_status_showall_pallet(<?php echo $b2bid; ?>); return false;"><span class='font_size_1'><u>Show Status History</span></u></a>

												</td>
											</tr>
										</table>
									</div>
								</div>
							</td>
						</tr>

						<tr>
							<td colspan="2"><span class='font_family_Ariel font_size_2' color="#333333">
									<hr />
							</td>
						</tr>

						<tr>
							<td colspan="2"><span class='font_family_Ariel font_size_2' color="#333333">
									<table>
										<tr>
											<td>
												<span class='font_family_Ariel font_size_2' color="#333333">Special Ops?</span> &nbsp; <input type="checkbox" name="chk_special_ops" id="chk_special_ops" value="1" <?php if ($row["special_ops"] == 1) echo " checked "; ?>>
											</td>
										</tr>
										<tr>
											<td>
												<?php if ($row["haveNeed"] == "Have Boxes") { ?>
													<span class='font_family_Ariel font_size_2' color="#333333">UCBZeroWaste Opportunity?</span> &nbsp; <input type="checkbox" name="chk_water_flg" onclick="showwater_dropdown()" id="chk_water_flg" value="1" <?php if ($chk_water_flg == 1) echo " checked "; ?>>
												<?php  } ?>
											</td>
										</tr>
										<tr>
											<td>
												<span class='font_family_Ariel font_size_2' color="#333333">Pallet Opportunity?</span> &nbsp;
												<input type="checkbox" name="chk_pallet_flg" onclick="showpallet_dropdown()" id="chk_pallet_flg" value="1" <?php if ($chk_pallet_flg == 1) echo " checked "; ?>>
											</td>
										</tr>
									</table>
							</td>
						</tr>
						<tr>
							<td colspan="2"><span class='font_family_Ariel font_size_2' color="#333333">
									<br />
									<hr />
							</td>
						</tr>
						<tr>
							<td valign="middle" align="left">
								<span class='font_family_Ariel font_size_2' color="#333333">UCB Next Step </span><br />
								<textarea name="next_step" rows="3" cols="30"><?php echo $row["next_step"]; ?></textarea>

								<br /><span class='font_family_Ariel font_size_2' color="#333333">UCB Next Step Date:</span><br>
								<SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
								<script LANGUAGE="JavaScript">
									document.write(getCalendarStyles());
								</script>
								<script LANGUAGE="JavaScript">
									var cal1xx = new CalendarPopup("listdiv");
									cal1xx.showNavigationDropdowns();
								</script>
								<?php
								$start_date = isset($row["next_date"]) ? strtotime($row["next_date"]) : "";
								?>
								<input type="text" name="start_date" id="start_date" size="11" value="<?php echo (isset($row["next_date"]) && $row["next_date"] != "") ? date('m/d/Y', $start_date) : "" ?>"> <a style='color:#0000FF;' href="#" onclick="cal1xx.select(document.nextstep.start_date,'anchor1xx','MM/dd/yyyy'); return false;" name="anchor1xx" id="anchor1xx"><img border="0" src="images/calendar.jpg"></a>
								<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
							</td>

							<td>
								<div style="display: flex;">
									<?php  //if ($chk_water_flg == 1) { 
									?>
									<div id="div_ucbzw_nxtdt" style="width:50%;<?php echo $div_style; ?>">
										<table>
											<tr>
												<td>
													<span class='font_family_Ariel font_size_2' color="#333333">UCBZW Next Step</span><br />
													<textarea name="ucbzw_next_step" rows="3" cols="25"><?php echo $row["ucbzw_next_step"]; ?></textarea>
													<br />
													<span class='font_family_Ariel font_size_2' color="#333333">UCBZW Next Step Date:</span><br>
													<SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
													<script LANGUAGE="JavaScript">
														document.write(getCalendarStyles());
													</script>
													<script LANGUAGE="JavaScript">
														var cal2xx = new CalendarPopup("listdiv");
														cal2xx.showNavigationDropdowns();
													</script>
													<?php
													$ucbzw_start_date = isset($row["ucbzw_next_date"]) ? strtotime($row["ucbzw_next_date"]) : "";
													if ($ucbzw_start_date == "0000-00-00") {
														$ucbzw_start_date = "";
													}
													?>
													<input type="text" name="ucbzw_start_date" id="ucbzw_start_date" size="11" value="<?php echo (isset($row["ucbzw_next_date"]) && $ucbzw_start_date != "") ? date('m/d/Y', $ucbzw_start_date) : ""; ?>">
													<a style='color:#0000FF;' href="#" onclick="cal2xx.select(document.nextstep.ucbzw_start_date,'anchor2xx','MM/dd/yyyy'); return false;" name="anchor2xx" id="anchor2xx"><img border="0" src="images/calendar.jpg"></a>
													<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
												</td>
											</tr>
										</table>
									</div>
									<?php  //} 
									?>

									<?php  //if ($chk_pallet_flg == 0) { 
									?>
									<div id="div_pallet_nxtdt" style="width:50%;<?php echo $div_style_pallet; ?>">
										<table>
											<tr>
												<td>
													<span class='font_family_Ariel font_size_2' color="#333333">Pallet Next Step</span><br />
													<textarea name="pallet_next_step" rows="3" cols="25"><?php echo $row["pallet_next_step"]; ?></textarea>
													<br />
													<span class='font_family_Ariel font_size_2' color="#333333">Pallet Next Step Date:</span><br>
													<SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
													<script LANGUAGE="JavaScript">
														document.write(getCalendarStyles());
													</script>
													<script LANGUAGE="JavaScript">
														var cal3xx = new CalendarPopup("listdiv");
														cal3xx.showNavigationDropdowns();
													</script>
													<?php
													if ($row["pallet_next_date"] == "0000-00-00") {
														$pallet_start_date = "";
													} else {
														$pallet_start_date = strtotime($row["pallet_next_date"]);
													}
													?>
													<input type="text" name="pallet_start_date" id="pallet_start_date" size="11" value="<?php echo ($pallet_start_date != "") ? date('m/d/Y', $pallet_start_date) : ""; ?>">
													<a style='color:#0000FF;' href="#" onclick="cal3xx.select(document.nextstep.pallet_start_date,'anchor3xx','MM/dd/yyyy'); return false;" name="anchor3xx" id="anchor3xx"><img border="0" src="images/calendar.jpg"></a>
													<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
												</td>
											</tr>
										</table>
									</div>
									<?php  //} 
									?>
								</div>
							</td>
						</tr>

						<tr>
							<td width="12%" valign="middle" colspan="2" align="center">
								<hr />
								<input style="cursor:pointer;" type="submit" value="Update" name="B1">
							</td>
						</tr>
						</form>

					<?php  } ?>

					<?php if (($row["status"] == 6) || ($row["vendor"] > 0)) { ?>
						<tr>
							<td width="12%" valign="middle" colspan="2" align="left">
								<form method="POST" action="updateVendor.asp">
									<input type=hidden name="companyID" value="<?php echo $_REQUEST["ID"] ?>">
									<span class='font_family_Ariel font_size_2' color="#333333">Assign Vendor</span>
									<select name="vendors" size="1">
										<option value="0">Please Select</option>
										<?php
										db_b2b();
										$strQuery = "SELECT * FROM vendors WHERE Email<>'' ORDER BY Name ASC";
										$dt_view_res5 = db_query($strQuery);
										while ($rsProp = array_shift($dt_view_res5)) {

											if ($rsProp["id"] == $row["vendor"])
												$sel = " selected";
											else
												$sel = "";

										?>

											<option value="<?php echo $rsProp["id"] ?>" <?php echo $sel ?>><?php echo $rsProp["Name"] ?></option>
										<?php
										}
										?>
									</select>
									<input style="cursor:pointer;" type="submit" value="Update" name="B1">&nbsp;
								</form>
							</td>
						</tr>
					<?php  } ?>

				</table>

				<?php if ($crm_trans_tbl_flg == "yes") {  ?>
					<br><br>

					<table width="600" border="0" cellspacing="0" cellpadding="0" class="bg_color_e4e4e4">
						<tr>
							<td width="12%" valign="middle" colspan="2" align="left" style="background-color:#b8b8ff;">

								<form method="POST" ENCTYPE="multipart/form-data" name="frmm" action="emailuser_mrg_mysqli.php" onsubmit="javascript:return validate()">
									<script language="javascript">
										function validate() {

											var e = document.getElementById("employeed");
											var strUser = e.options[e.selectedIndex].value;
											//alert(strUser);
											if (strUser == "") {
												alert("Please Select an Employee.");
												document.nextstep.employeed.focus();
												return false;
											}
										}
									</script>
									<input type=hidden name="companyID" value="<?php echo $b2bid; ?>">
									<hr />
									<span class='font_family_Ariel font_size_2' color="#333333">Send Record's Link to Another User</span><br />
									<select size="1" name="employeed" id="employeed">
										<option value="">Choose Recipient</option>
										<?php

										$qr2 = "SELECT * FROM employees WHERE status='Active' order by name";
										$dt_view_res6 = db_query($qr2);
										while ($remp2 = array_shift($dt_view_res6)) {
										?>
											<option value="<?php echo $remp2["employeeID"] ?>"><?php echo $remp2["name"] ?></option>
										<?php
										}
										?>
									</select>
									<input style="cursor:pointer;" type="button" value="Send Link" id="sendemailtrfacc" name="B1" onclick="sendemail_popup_sendrecordlink(<?php echo $b2bid; ?>); return false;">&nbsp;
								</form>
							</td>
						</tr>

						<?php
						$super_user = "no";
						db();
						$dt_so = "SELECT level FROM loop_employees WHERE initials = '" .  $_COOKIE['userinitials'] . "'";
						$dt_res_so = db_query($dt_so);
						while ($so_row = array_shift($dt_res_so)) {
							if ($so_row["level"] == 2) {
								$super_user = "yes";
							}
						}
						db_b2b();
						if ($super_user == "yes") { ?>
							<tr>
								<td width="12%" valign="middle" colspan="2" align="left">

									<form method="POST" ENCTYPE="multipart/form-data" name="frmm" action="emailuser_mrg_publichandoff_mysqli.php">
										<input type="hidden" name="companyID" value="<?php echo $b2bid; ?>">
										<hr />
										<select size="1" name="employeed_publich" id="employeed_publich">
											<option value="">Choose Recipient</option>
											<?php

											$qr2 = "SELECT * FROM employees WHERE status='Active' order by name";
											$dt_view_res6 = db_query($qr2);
											while ($remp2 = array_shift($dt_view_res6)) {
											?>
												<option value="<?php echo $remp2["employeeID"] ?>"><?php echo $remp2["name"] ?></option>
											<?php
											}
											?>
										</select>
										<input style="cursor:pointer;" type="button" value="Public Handoff" id="sendemailpublichandoff" name="sendemailpublichandoff" onclick="sendemail_popup_sendrecordlink_publichandoff(<?php echo $b2bid; ?>); return false;">&nbsp;
									</form>
								</td>
							</tr>

						<?php  } ?>

					</table>

					<br>

					<table width='600' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>
						<br>
						<?php if ($_REQUEST["editedonedriveurl"] == "yes") {
							echo "<span class='font_size_1' color='#ff0000'>OneDrive File url added successfully</span><br>";
						}
						?>

						<tr align='center'>
							<td colspan='2' bgcolor='#C0CDDA'><span class='font_family_Ariel font_size_1' color='#333333'>OneDrive File url</span>
							</td>
						</tr>
						<tr>
							<td colspan='2'>
								<?php if ($row["one_drive_url"] != "") { ?>
									<a href="<?php echo $row["one_drive_url"]; ?>" target="_blank"><?php echo $row["one_drive_url"]; ?></a>
									<br>
								<?php  } ?>

								<form method='POST' action='update_one_drive_url.php' id='frmupdate_update_one_drive_url' name='frmupdate_update_one_drive_url'>
									<span class='font_size_2'> Enter OneDrive url: </span><input type='text' name='txt_onedriveurl' id='txt_onedriveurl' value="<?php echo $row["one_drive_url"]; ?>" size="70" />
									<input type="hidden" name='id' value="<?php echo $b2bid; ?>">
									&nbsp;&nbsp;<input style="cursor:pointer;" type='Submit' value='Update' name='btn_onedriveurl' id='btn_onedriveurl'>
								</form>
							</td>
						</tr>
					</table>
					<br>


					<!----------------------- Start Attachments ------------------>
					<?php if ($_REQUEST["editedattach"] == "yes") {
						echo "<span class='font_size_1' color='#ff0000'>Attachment added successfully</span>";
					}
					?>
					<table width="600" border="0" cellspacing="1" cellpadding="1">
						<tr>
							<td colspan="7" class="bg_color_c0cdda" align="center">
								<span class='font_family_Ariel font_size_1' color="#333333">ATTACHMENTS </span>
							</td>
						</tr>
						<tr class="bg_color_e4e4e4">
							<td><span class='font_family_Ariel font_size_1' color="#333333"></span></td>
							<td><span class='font_family_Ariel font_size_1' color="#333333">Description</span></td>
							<td><span class='font_family_Ariel font_size_1' color="#333333">Date</span></td>
							<td><span class='font_family_Ariel font_size_1' color="#333333" width="5%">Remove</span></td>
						</tr>
						<?php
						if ($b2bid > 0) {
							$attch_id = $b2bid;
						} else {
							$attch_id = $_REQUEST['ID'];
						}
						$attSql = "Select * from Attachments Where companyID = " . $attch_id . " ORDER by ID DESC";
						//echo $attSql;

						$x = 1;
						$dt_view_res = db_query($attSql);
						if (tep_db_num_rows($dt_view_res) < 5) {
							while ($at = array_shift($dt_view_res)) {

						?>
								<tr class="bg_color_e4e4e4">
									<td>
										<span class='font_family_Ariel font_size_1' color="#333333">
											<a style='color:#0000FF;' href="b2battachments/<?php echo htmlentities($at["path"]) ?>" target="_blank"><?php
																																					$fileType = strtolower(right($at["path"], 3));

																																					if ($fileType == "doc") {
																																					?>
													<img src="images/doc.jpg">
												<?php  } elseif ($fileType == "pdf") { ?>
													<img src="images/pdf.jpg">
												<?php  } elseif ($fileType == "xls") { ?>
													<img src="images/xls.jpg">
												<?php  } elseif ($fileType == "jpg") { ?>
													<img src="b2battachments/<?php echo htmlentities($at["path"]) ?>" width=40 height=40>
												<?php  } elseif ($fileType == "gif") { ?>
													<img src="b2battachments/<?php echo htmlentities($at["path"]) ?>" width=40 height=40>
												<?php  } else { ?>
													<img src="images/file.jpg">
												<?php  } ?>
											</a></span>
									</td>
									<td>
										<span class='font_family_Ariel font_size_1' color="#333333">
											<a style='color:#0000FF;' href="b2battachments/<?php echo htmlentities($at["path"]) ?>" target="_blank"><?php echo $at["description"] ?></a></span>
									</td>
									<td><span class='font_family_Ariel font_size_1' color="#333333"><?php echo timestamp_to_datetime_new($at["dateAdded"]) ?></span></td>
									<td><span class='font_family_Ariel font_size_1' color="#333333"><a style='color:#0000FF;' href="deleteattachment_mrg_mysqli.php?ID=<?php echo $at["id"]; ?>">X</a></span></td>
								</tr>
							<?php
							}
						} else {
							?>
							<tr>
								<td align=center colspan="7">
									<span id='getAttachmentdata' onclick="get_attachment_detail(<?php echo $attch_id; ?>)"><span class="font_family_Ariel font_size_1"><u>Expand Attachment</u></span></span> /
									<span id='collapse_getAttachmentdata' onclick='collapse_get_attachment_detail()'><span class="font_family_Ariel font_size_1"><u>Collapse Attachment</u></span></span>
								</td>
							</tr>
						<?php
						}
						?>
						<tr>
							<td class="bg_color_e4e4e4" colspan="4">
								<div id="div_attachment_data"></div>
							</td>
						</tr>

						<form METHOD="POST" ENCTYPE="multipart/form-data" action="addattachment_mrg_mysqli.php">
							<tr>
								<td align=center colspan="7" style="padding-top:20px">
									<span class='font_family_Ariel font_size_1' color="#333333">
										Description: <input type="text" name="description" size="35"><br>
										File: <input type="file" name="File[]" size="10" multiple>
										<input type="hidden" name="companyID" value="<?php echo $attch_id; ?>">
										<input style="cursor:pointer;" type="submit" value="Upload">
									</span>
								</td>
							</tr>
						</form>

					</table>
					<!----------------------- End Attachments ------------------>

					<br>
					<?php if ($_REQUEST["editednrate"] == "yes") {
						echo "<span class='font_size_1' color='#ff0000'>NEGOTIATED RATE updated successfully</span>";
					}
					?>

					<table width='600' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>
						<br>
						<tr align='center'>
							<td colspan='2' bgcolor='#C0CDDA'><span class='font_family_Ariel font_size_1' color='#333333'>NEGOTIATED RATE</span>
							</td>
						</tr>
						<tr>
							<td colspan='2'>
								<form method='POST' action='update_negotiatedrate_mrg.php' id='frmupdate_negotiatedrate' name='frmupdate_negotiatedrate'>
									<?php
									//negotiated rate
									db();
									$sql_b2b = "SELECT * FROM loop_warehouse WHERE id=?";
									$result_b2b = db_query($sql_b2b, array("i"), array($_REQUEST['ID']));
									$b2b_id = 0;
									while ($dt_view_row = array_shift($result_b2b)) {
										$b2b_id = $dt_view_row["b2bid"];
									}

									if ($b2b_id != "") {
										$sql_b2b = "SELECT negotiated_rate FROM companyInfo WHERE ID= $b2b_id ";
									} else {
										$sql_b2b = "SELECT negotiated_rate FROM companyInfo WHERE ID=" . $_REQUEST['ID'];
									}
									//echo $sql_b2b;
									db_b2b();
									$result_b2b = db_query($sql_b2b);
									$negotiated_rate = "";
									while ($dt_view_row = array_shift($result_b2b)) {
										$negotiated_rate = $dt_view_row["negotiated_rate"];
									}

									?>
									<span class='font_size_2'> Enter negotiated rate details: </span><input type='text' name='txt_negotiatedrate' id='txt_negotiatedrate' value="<?php echo $negotiated_rate; ?>" size="70" />
									<input type="hidden" name='id' value="<?php echo $b2bid; ?>">
									&nbsp;&nbsp;<input style="cursor:pointer;" type='Submit' value='Update' name='btn_negotiatedrate' id='btn_negotiatedrate'>
								</form>
							</td>
						</tr>
					</table>
					<br>
				<?php  } //crm_trans_tbl_flg condition  
				?>

				<?php if ($show_crm_flg == "yes") {  ?>
					<form method="POST" action="updateIntNotes_mrg_mysqli.php" id="intNotes" name="intNotes">
						<input type=hidden name="id" value="<?php echo $b2bid; ?>">
						<table border="0" width="600" cellspacing="0" cellpadding="0">
							<tr>
								<td width="100%" id="msgNote"></td>
							</tr>
							<tr>
								<td width="100%" align="center"><b>Internal Notes </b><span class='font_family_Ariel font_size_2' color="#333333"><br />
										(These notes apply to the account in general, and don't generally change every time we communicate. For notes after every call, use "Customer Communications")
									</span><br><textarea rows="16" name="int_notes" cols="3" style="width:90%"><?php echo $row["int_notes"] ?></textarea> <br /><input style="cursor:pointer;" type="Submit" value="Update" name="B1">
									<?php
									if ($_REQUEST["noteadd"] == "yes") {
										echo "<span class='font_size_1' color='#ff0000'>Note updated successfully</span>";
									}
									?>
								</td>
							</tr>
						</table>
					</form>
					<br />
				<?php  }  ?>

				<?php if ($row["haveNeed"] == "Have Boxes" && $_REQUEST["showorg"] == "watertransactions") { ?>

					<form method="post" action="#" name="frmLogs">
						<table width="600" border="0" cellspacing="1" cellpadding="1">
							<tr align="center">
								<td colspan="6" style="background-color:#99FF99;">
									<span class="font_family_Ariel font_size_1">UCBZeroWaste Accounting LOG</span>
								</td>
							</tr>
							<tr align="center">
								<td style="background-color:#99FF99;">
									<span style="font-size:10px;">Log</span>
								</td>
								<td style="background-color:#99FF99;">
									<span style="font-size:10px;">Created By</span>
								</td>
								<td style="background-color:#99FF99;">
									<span style="font-size:10px;">Assigned To</span>
								</td>
								<td style="background-color:#99FF99;">
									<span style="font-size:10px;">Due Date</span>
								</td>
								<td style="background-color:#99FF99;">
									<span style="font-size:10px;">Priority</span>
								</td>
								<td style="background-color:#99FF99;">&nbsp;</td>
							</tr>
							<tr align="center">
								<input type="hidden" name="logs_companyID" id="logs_companyID" value="<?php echo $b2bid; ?>">

								<td class="bg_color_e4e4e4"><textarea rows="5" name="logs_message" id="logs_message" cols="25"></textarea>
								</td>

								<td class="bg_color_e4e4e4">
									<select size="1" name="logs_created_by" id="logs_created_by">
										<?php
										db();
										$eq = "SELECT * FROM loop_employees WHERE status='Active' order by initials";
										$dt_view_res = db_query($eq);
										while ($emp = array_shift($dt_view_res)) {
										?>
											<option <?php if ($emp["initials"] == $_COOKIE["userinitials"]) {
														echo " selected ";
													} ?>><?php echo $emp["initials"] ?></option>
										<?php  } ?>
									</select>
								</td>
								<td class="bg_color_e4e4e4">
									<select size="1" name="logs_employee" id="logs_employee">
										<option></option>
										<?php
										$eq = "SELECT * FROM loop_employees WHERE status='Active' order by initials";
										$dt_view_res = db_query($eq);
										while ($emp = array_shift($dt_view_res)) {
										?>
											<option><?php echo $emp["initials"] ?></option>
										<?php  } ?>
									</select>
								</td>
								<td class="bg_color_e4e4e4" align="middle">
									<script LANGUAGE="JavaScript">
										document.write(getCalendarStyles());
									</script>
									<script LANGUAGE="JavaScript">
										var cal2nxx = new CalendarPopup("listdiv");
										cal2nxx.showNavigationDropdowns();
									</script>
									<input type="text" name="logs_date" id="logs_date" size="8" value="<?php echo date('m/d/Y') ?>">
									<a style='color:#0000FF;' href="#" onclick="cal2nxx.select(document.frmLogs.logs_date,'anchor2nxx','MM/dd/yyyy'); return false;" name="anchor2nxx" id="anchor2nxx"><img border="0" src="images/calendar.jpg"></a>
								</td>
								<td class="bg_color_e4e4e4">
									<select size="1" name="logs_priority" id="logs_priority">
										<option></option>
										<option value="Low">Low</option>
										<option value="Medium">Medium</option>
										<option value="High">High</option>
									</select>
								</td>
								<td class="bg_color_e4e4e4" align="middle">
									<input style="cursor:pointer;" type="button" value="Add Log" name="btnLogsfrm" onclick="addLogsitem()">
								</td>
							</tr>
						</table>

						<div id="log_div">
							<table width="600" border="0" cellspacing="1" cellpadding="1">
								<tr align="center">
									<td colspan="10" style="background-color:#99FF99;">
										<span class="font_family_Ariel font_size_1">Active Logs</span>
									</td>
								</tr>
								<tr align="center">
									<td style="background-color:#99FF99;">
										<span style="font-size:10px;">Log Name</span>
									</td>
									<td style="background-color:#99FF99;">
										<span style="font-size:10px;">Created By</span>
									</td>
									<td style="background-color:#99FF99;">
										<span style="font-size:10px;">Assigned To</span>
									</td>
									<td style="background-color:#99FF99;">
										<span style="font-size:10px;">Created On</span>
									</td>
									<td style="background-color:#99FF99;">
										<span style="font-size:10px;">Due Date</span>
									</td>
									<td style="background-color:#99FF99;">
										<span style="font-size:10px;">Priority</span>
									</td>
									<td style="background-color:#99FF99;">&nbsp; </td>
									<td style="background-color:#99FF99;">&nbsp; </td>
									<td style="background-color:#99FF99;">&nbsp; </td>
								</tr>
								<?php
								$resLogDetails = db_query("SELECT * FROM company_logs where company_id = '" . $b2bid . "' AND status = 1 ORDER BY id");
								while ($rowsLogDetails = array_shift($resLogDetails)) {
									$date1 = new DateTime($rowsLogDetails["due_date"]);
									$date2 = new DateTime();

									$days = (strtotime($rowsLogDetails["due_date"]) - strtotime(date("Y-m-d"))) / (60 * 60 * 24);
								?>
									<tr align="center">
										<td class="bg_color_e4e4e4" align="left"><span style="font-size:10px;"><?php echo $rowsLogDetails["log_name"] ?></span></td>

										<td class="bg_color_e4e4e4"><span style="font-size:10px;"><?php echo $rowsLogDetails["log_created_by"] ?></span></td>

										<td class="bg_color_e4e4e4"><span style="font-size:10px;"><?php echo $rowsLogDetails["assign_to"] ?></span></td>

										<td class="bg_color_e4e4e4"><span style="font-size:10px;"><?php echo date("m/d/Y H:i:s", strtotime($rowsLogDetails["log_added_on"]))  . " CT"; ?></span></td>

										<?php if ($days == 0) { ?>
											<td bgcolor="green"><span style="font-size:10px;"><?php echo date("m/d/Y", strtotime($rowsLogDetails["due_date"])) . " CT"; ?></span></td>
										<?php  }

										if ($days > 0) { ?>
											<td class="bg_color_e4e4e4"><span style="font-size:10px;"><?php echo date("m/d/Y", strtotime($rowsLogDetails["due_date"])) . " CT"; ?></span></td>
										<?php  }

										if ($days < 0) { ?>
											<td bgcolor="red"><span style="font-size:10px;"><?php echo date("m/d/Y", strtotime($rowsLogDetails["due_date"])) . " CT"; ?></span></td>
										<?php  } ?>

										<td class="bg_color_e4e4e4"><span style="font-size:10px;"><?php echo $rowsLogDetails["log_priority"]; ?></span></td>

										<?php if ($_COOKIE["userinitials"] == $rowsLogDetails["log_created_by"]) { ?>
											<td class="bg_color_e4e4e4" align="middle">
												<input type="button" value="Edit" onclick="javascript: log_edits(<?php echo $rowsLogDetails['id']; ?>, <?php echo $b2bid; ?>);" name="log_edit" id="log_edit<?php echo $rowsLogDetails["id"]; ?>">
											</td>
										<?php  } else { ?>
											<td class="bg_color_e4e4e4" align="middle">&nbsp; </td>
										<?php  } ?>
										<?php if ($_COOKIE["userinitials"] == $rowsLogDetails["log_created_by"]) { ?>
											<td class="bg_color_e4e4e4" align="middle">
												<input type="button" value="Delete" onclick="javascript: fun_log_delete(<?php echo $rowsLogDetails['id']; ?>, <?php echo $b2bid; ?>);" name="log_delete" id="log_delete<?php echo $rowsLogDetails["id"]; ?>">
											</td>
										<?php  } else { ?>
											<td class="bg_color_e4e4e4" align="middle">&nbsp; </td>
										<?php  } ?>
										<td class="bg_color_e4e4e4" align="middle">
											<input type="button" value="Mark Complete" name="log_markcompl" id="log_markcompl" onclick="log_markcomp(<?php echo $rowsLogDetails['id']; ?>, <?php echo $b2bid; ?>)">
										</td>
									</tr>
								<?php
								}
								?>
							</table>
							<br>
							<table width="600" border="0" cellspacing="1" cellpadding="1">
								<tr align="center">
									<td colspan="6" style="background-color:#99FF99;">
										<span class="font_family_Ariel font_size_1">Recently Completed Logs</span>
									</td>
								</tr>
								<tr align="center">
									<td style="background-color:#99FF99;">
										<span style="font-size:10px;">Log Name</span>
									</td>
									<td style="background-color:#99FF99;">
										<span style="font-size:10px;">Assigned To</span>
									</td>
									<td style="background-color:#99FF99;">
										<span style="font-size:10px;">Created On</span>
									</td>
									<td style="background-color:#99FF99;">
										<span style="font-size:10px;">Due Date</span>
									</td>
									<td style="background-color:#99FF99;">
										<span style="font-size:10px;">Priority</span>
									</td>
									<td style="background-color:#99FF99;">
										<span style="font-size:10px;">Completed</span>
									</td>
								</tr>
								<?php
								$sql = "SELECT * FROM company_logs WHERE company_id =?  AND status = 2 ORDER BY due_date LIMIT 5 ";
								$resLogDtls = db_query($sql, array("i"), array($b2bid));
								while ($rowsLogDtls = array_shift($resLogDtls)) {
								?>
									<tr align="center">
										<td class="bg_color_e4e4e4" align="left"><span style="font-size:10px;"><?php echo $rowsLogDtls["log_name"] ?></span></td>
										<td class="bg_color_e4e4e4"><span style="font-size:10px;"><?php echo $rowsLogDtls["assign_to"] ?></span></td>

										<td class="bg_color_e4e4e4"><span style="font-size:10px;"><?php echo date("m/d/Y H:i:s", strtotime($rowsLogDtls["log_added_on"]))  . " CT"; ?></span></td>

										<td class="bg_color_e4e4e4"><span style="font-size:10px;"><?php echo date("m/d/Y", strtotime($rowsLogDtls["due_date"])) . " CT"; ?></span></td>

										<td class="bg_color_e4e4e4"><span style="font-size:10px;"><?php echo $rowsLogDtls["log_priority"]; ?></span></td>

										<td class="bg_color_e4e4e4"><span style="font-size:10px;"><?php echo $rowsLogDtls["mark_comp_by"] . " " . date("m/d/Y", strtotime($rowsLogDtls["mark_comp_on"])) . " CT"; ?></span></td>
									</tr>
								<?php
								}
								?>
								<tr align="center">
									<td colspan="7" style="background-color:#99FF99;">
										<input type="button" id="logshowall" onclick="log_showall(<?php echo $b2bid; ?>)" value="Show All" />
									</td>
								</tr>

							</table>
						</div>
					</form>
				<?php  } ?>


				<?php if ($crm_trans_tbl_flg == "yes") {  ?>
					<!-- To-Do list-->
					<form method="post" action="#" name="todoform">
						<table width="600" border="0" cellspacing="1" cellpadding="1">
							<tr align="center">
								<td colspan="6" class="bg_color_c0cdda">
									<span class="font_family_Ariel font_size_1">TASK LIST</span>
								</td>
							</tr>
							<tr align="center">
								<td class="bg_color_c0cdda">
									<span style="font-size:10px;">Task Name</span>
								</td>
								<td class="bg_color_c0cdda">
									<span style="font-size:10px;">Created By</span>
								</td>
								<td class="bg_color_c0cdda">
									<span style="font-size:10px;">Assigned To</span>
								</td>
								<td class="bg_color_c0cdda">
									<span style="font-size:10px;">Due Date</span>
								</td>
								<td class="bg_color_c0cdda">
									<span style="font-size:10px;">Priority</span>
								</td>
								<td class="bg_color_c0cdda">&nbsp;

								</td>
							</tr>
							<tr align="center">
								<input type="hidden" name="todo_companyID" id="todo_companyID" value="<?php echo $b2bid; ?>">

								<td class="bg_color_e4e4e4"><textarea rows="5" name="todo_message" id="todo_message" cols="25"></textarea>
								</td>

								<td class="bg_color_e4e4e4">
									<select size="1" name="todo_created_by" id="todo_created_by">
										<?php
										db();
										$eq = "SELECT * FROM loop_employees WHERE status='Active' order by initials";
										$dt_view_res = db_query($eq);
										while ($emp = array_shift($dt_view_res)) {
										?>
											<option <?php if ($emp["initials"] == $_COOKIE["userinitials"]) {
														echo " selected ";
													} ?>><?php echo $emp["initials"] ?></option>
										<?php  } ?>
									</select>
								</td>
								<td class="bg_color_e4e4e4">
									<select size="1" name="todo_employee" id="todo_employee">
										<option></option>
										<?php
										$eq = "SELECT * FROM loop_employees WHERE status='Active' order by initials";
										$dt_view_res = db_query($eq);
										while ($emp = array_shift($dt_view_res)) {
										?>
											<option><?php echo $emp["initials"] ?></option>
										<?php  } ?>
									</select>
								</td>
								<td class="bg_color_e4e4e4" align="middle">
									<script LANGUAGE="JavaScript">
										document.write(getCalendarStyles());
									</script>
									<script LANGUAGE="JavaScript">
										var cal1nxx = new CalendarPopup("listdiv");
										cal1nxx.showNavigationDropdowns();
									</script>
									<input type="text" name="todo_date" id="todo_date" size="8" value="<?php echo date('m/d/Y') ?>">
									<a style='color:#0000FF;' href="#" onclick="cal1nxx.select(document.todoform.todo_date,'anchor1nxx','MM/dd/yyyy'); return false;" name="anchor1nxx" id="anchor1nxx"><img border="0" src="images/calendar.jpg"></a>
								</td>
								<td class="bg_color_e4e4e4">
									<select size="1" name="task_priority" id="task_priority">
										<option></option>
										<option value="Low">Low</option>
										<option value="Medium">Medium</option>
										<option value="High">High</option>
									</select>
								</td>
								<td class="bg_color_e4e4e4" align="middle">
									<input style="cursor:pointer;" type="button" value="Add Task" name="todo_btn" onclick="addtodoitem()">
								</td>
							</tr>
						</table>

						<div id="todo_div">
							<table width="600" border="0" cellspacing="1" cellpadding="1">
								<tr align="center">
									<td colspan="9" class="bg_color_c0cdda">
										<span class="font_family_Ariel font_size_1">Active Tasks</span>
									</td>
								</tr>
								<tr align="center">
									<td class="bg_color_c0cdda">
										<span style="font-size:10px;">Task Name</span>
									</td>
									<td class="bg_color_c0cdda">
										<span style="font-size:10px;">Created By</span>
									</td>
									<td class="bg_color_c0cdda">
										<span style="font-size:10px;">Assigned To</span>
									</td>
									<td class="bg_color_c0cdda">
										<span style="font-size:10px;">Created On</span>
									</td>
									<td class="bg_color_c0cdda">
										<span style="font-size:10px;">Due Date</span>
									</td>
									<td class="bg_color_c0cdda">
										<span style="font-size:10px;">Priority</span>
									</td>
									<td class="bg_color_c0cdda">&nbsp;

									</td>
									<td class="bg_color_c0cdda">&nbsp;

									</td>
								</tr>
								<?php
								$sql = "SELECT * FROM todolist WHERE companyid =? and `status` = 1 order by unqid";
								$result = db_query($sql, array("i"), array($b2bid));
								while ($myrowsel = array_shift($result)) {
									$date1 = new DateTime($myrowsel["due_date"]);
									$date2 = new DateTime();

									$days = (strtotime($myrowsel["due_date"]) - strtotime(date("Y-m-d"))) / (60 * 60 * 24);
								?>
									<tr align="center">
										<td class="bg_color_e4e4e4" align="left"><span style="font-size:10px;"><?php echo $myrowsel["task_name"] ?></span></td>

										<td class="bg_color_e4e4e4"><span style="font-size:10px;"><?php echo $myrowsel["task_created_by"] ?></span></td>

										<td class="bg_color_e4e4e4"><span style="font-size:10px;"><?php echo $myrowsel["assign_to"] ?></span></td>

										<td class="bg_color_e4e4e4"><span style="font-size:10px;"><?php echo date("m/d/Y H:i:s", strtotime($myrowsel["task_added_on"]))  . " CT"; ?></span></td>

										<?php if ($days == 0) { ?>
											<td bgcolor="green"><span style="font-size:10px;"><?php echo date("m/d/Y", strtotime($myrowsel["due_date"])) . " CT"; ?></span></td>
										<?php  }

										if ($days > 0) { ?>
											<td class="bg_color_e4e4e4"><span style="font-size:10px;"><?php echo date("m/d/Y", strtotime($myrowsel["due_date"])) . " CT"; ?></span></td>
										<?php  }

										if ($days < 0) { ?>
											<td bgcolor="red"><span style="font-size:10px;"><?php echo date("m/d/Y", strtotime($myrowsel["due_date"])) . " CT"; ?></span></td>
										<?php  } ?>

										<td class="bg_color_e4e4e4"><span style="font-size:10px;"><?php echo $myrowsel["task_priority"]; ?></span></td>
										<?php if ($_COOKIE["userinitials"] == $myrowsel["task_created_by"]) { ?>
											<td class="bg_color_e4e4e4" align="middle">
												<input type="button" value="Edit" name="todo_edit" id="todo_edit<?php echo $myrowsel["unqid"]; ?>" onclick="todoitem_edit(<?php echo $myrowsel['unqid']; ?>, <?php echo $b2bid; ?>)">
											</td>
										<?php  } else { ?>
											<td class="bg_color_e4e4e4" align="middle">&nbsp;

											</td>
										<?php  } ?>
										<td class="bg_color_e4e4e4" align="middle">
											<input type="button" value="Mark Complete" name="todo_markcompl" id="todo_markcompl" onclick="todoitem_markcomp(<?php echo $myrowsel['unqid']; ?>, <?php echo $b2bid; ?>)">
										</td>
									</tr>
								<?php
								}
								?>
							</table>

							<br>
							<table width="600" border="0" cellspacing="1" cellpadding="1">
								<tr align="center">
									<td colspan="6" class="bg_color_c0cdda">
										<span class="font_family_Ariel font_size_1">Recently Completed Tasks</span>
									</td>
								</tr>
								<tr align="center">
									<td class="bg_color_c0cdda">
										<span style="font-size:10px;">Task Name</span>
									</td>
									<td class="bg_color_c0cdda">
										<span style="font-size:10px;">Assigned To</span>
									</td>
									<td class="bg_color_c0cdda">
										<span style="font-size:10px;">Created On</span>
									</td>
									<td class="bg_color_c0cdda">
										<span style="font-size:10px;">Due Date</span>
									</td>
									<td class="bg_color_c0cdda">
										<span style="font-size:10px;">Priority</span>
									</td>
									<td class="bg_color_c0cdda">
										<span style="font-size:10px;">Completed</span>
									</td>
								</tr>
								<?php
								$sql = "SELECT * FROM todolist where companyid =?  and status = 2 order by due_date desc limit 5";
								$result = db_query($sql, array("i"), array($b2bid));
								while ($myrowsel = array_shift($result)) {
								?>
									<tr align="center">
										<td class="bg_color_e4e4e4" align="left"><span style="font-size:10px;"><?php echo $myrowsel["task_name"] ?></span></td>
										<td class="bg_color_e4e4e4"><span style="font-size:10px;"><?php echo $myrowsel["assign_to"] ?></span></td>

										<td class="bg_color_e4e4e4"><span style="font-size:10px;"><?php echo date("m/d/Y H:i:s", strtotime($myrowsel["task_added_on"]))  . " CT"; ?></span></td>

										<td class="bg_color_e4e4e4"><span style="font-size:10px;"><?php echo date("m/d/Y", strtotime($myrowsel["due_date"])) . " CT"; ?></span></td>

										<td class="bg_color_e4e4e4"><span style="font-size:10px;"><?php echo $myrowsel["task_priority"]; ?></span></td>

										<td class="bg_color_e4e4e4"><span style="font-size:10px;"><?php echo $myrowsel["mark_comp_by"] . " " . date("m/d/Y", strtotime($myrowsel["mark_comp_on"])) . " CT"; ?></span></td>
									</tr>
								<?php
								}
								?>
								<tr align="center">
									<td colspan="7" class="bg_color_c0cdda">
										<input type="button" id="todoshowall" onclick="todoitem_showall(<?php echo $b2bid; ?>)" value="Show All" />
									</td>
								</tr>

							</table>
						</div>
					</form>

					<!-- To-Do list-->

					<br />
					<?php
					/*
					db_email();
					$crm_numberof_chr = 0;
					$crm_rows_per_page = 0;
					$crm_numberof_chr_divheight = 0;
					$sql = "SELECT * FROM tblvariable ";
					$result = db_query($sql);
					while ($myrowsel = array_shift($result)) {
						if (strtoupper($myrowsel["variablename"]) == strtoupper("crm_numberof_chr")) {
							$crm_numberof_chr = $myrowsel["variablevalue"];
						}
						if (strtoupper($myrowsel["variablename"]) == strtoupper("crm_rows_per_page")) {
							$crm_rows_per_page = $myrowsel["variablevalue"];
						}
						if (strtoupper($myrowsel["variablename"]) == strtoupper("crm_numberof_chr_divheight")) {
							$crm_numberof_chr_divheight = $myrowsel["variablevalue"];
						}
					}
					*/
					if (isset($_REQUEST['btnEmailSrch']) && !empty($_REQUEST['searchEmail'])) {
						$page_name = "viewCompany-purchasing_func3.php?ID=" . $_REQUEST['ID'] . "&show=" . $_REQUEST['show'] . "&rec_type=" . $_REQUEST['rec_type'] . "&btnEmailSrch=" . $_REQUEST['btnEmailSrch'] . "&searchEmail=" . $_REQUEST['searchEmail'];
					} else {
						$page_name = "viewCompany-purchasing_func3.php?ID=" . $_REQUEST['ID'] . "&show=" . $_REQUEST['show'] . "&rec_type=" . $_REQUEST['rec_type'];
					}


					$start = (isset($_GET['start'])) ? $_GET['start'] : 0;
					$eu = ($start - 0);
					$limit = $crm_rows_per_page;

					$limit = 7;
					$this1 = $eu + $limit;
					$back = $eu - $limit;
					$next = $eu + $limit;



					if ($_REQUEST["editedcrm"] == "yes") {
						echo "<span class='font_size_1' color='#ff0000'>Entry in CRM has been added</span>";
					?>
						<script>
							alert("Entry in CRM has been added.");
						</script>
					<?php
					}
					?>
					<table width="600" border="0" cellspacing="1" cellpadding="1">
						<tr align="center">
							<td colspan="5" class="bg_color_c0cdda">
								<span class="font_family_Ariel font_size_1">CUSTOMER COMMUNICATIONS</span>
								<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
									<span class="tooltiptext">
										Only shows since 2019, click "View All" to see all log entries
									</span>
								</div>

								<?php if ($crm_archive_flg > 0) { ?>
									<a href="display_all_crm_mysqli.php?ID=<?php echo $b2bid; ?>" target="_blank"><span class="font_family_Ariel font_size_1">
											View All </span><span class='font_family_Ariel font_size_1' style='color:red;'>(Including Archived Data Prior to 1/1/2019, <?php echo $crm_archive_flg; ?> Entries)
										</span></a>
								<?php  } else { ?>
									<a href="display_all_crm_mysqli.php?ID=<?php echo $b2bid; ?>" target="_blank"><span class="font_family_Ariel font_size_1">
											View All (No Archived Data Prior to 1/1/2019)
										</span></a>
								<?php  } ?>
								<script LANGUAGE="JavaScript">
									document.write(getCalendarStyles());
								</script>
								<script LANGUAGE="JavaScript">
									var cal6xx = new CalendarPopup("listdiv2");
									cal6xx.showNavigationDropdowns();
								</script>

								&nbsp;<a href="#" onclick="display_owner_count(<?php echo $b2bid; ?>); return false;"><span class="font_family_Ariel font_size_1">Owner Contact Count</span></a>
								<div id="div_owner_count"></div>
								<?php
								//	require_once("viewCompany_showCount_modelbox.php");
								?>

								&nbsp;<a href="display_my_crm_mysqli.php?ID=<?php echo $b2bid; ?>" target="_blank"><span class="font_family_Ariel font_size_1">Show Mine</span></a>
							</td>
						</tr>
						<tr align="center">
							<td colspan="5">

								<?php
								$srchEmailText_CRM = '';
								$srchEmailTxt_emailtext = '';
								$srchEmailText_tblemail_body_txt = '';
								$searchEmailVal = '';
								if (isset($_REQUEST['btnEmailSrch']) && !empty($_REQUEST['searchEmail'])) {
									$srchEmailText_CRM = " AND message LIKE '%" . $_REQUEST['searchEmail'] . "%'";
									$srchEmailTxt_emailtext = " AND email_name LIKE '%" . $_REQUEST['searchEmail'] . "%'";
									$srchEmailText_tblemail_body_txt = " AND body_txt LIKE '%" . $_REQUEST['searchEmail'] . "%'";
									$searchEmailVal = $_REQUEST['searchEmail'];
								}
								?>
								<form method="post" action="#" enctype="multipart/form-data">
									<table width="600" border="0" cellspacing="1" cellpadding="1">
										<tr valign="top">
											<td class="bg_color_e4e4e4"><span class='font_size_2'> Search Email : </span></td>
											<td class="bg_color_e4e4e4"><span class='font_size_2'>
													<input type="text" name="searchEmail" size="50" id="searchEmail" value="<?php echo $searchEmailVal; ?>" class="ser_form_component">
													<input type="submit" name="btnEmailSrch" id="btnEmailSrch" style="cursor:pointer;" value="Search"></span>
											</td>
										</tr>
									</table>

								</form>
								<form method="post" action="addCRM_mrg_mysqli.php" enctype="multipart/form-data" onsubmit="javascript:return crm_validate()">

									<table width="600" border="0" cellspacing="1" cellpadding="1">
										<tr valign="top">
											<td class="bg_color_e4e4e4">
												<input type=hidden name="companyID" value="<?php echo $b2bid; ?>">


												<select size="1" name="type" id="type" onchange="display_contact_eml()">
													<option value='note'>Note</option>
													<option value='phone'>Phone Call</option>
												</select>&nbsp;
												<br><br>

											</td>
											<td class="bg_color_e4e4e4"><textarea rows="5" name="message" cols="30"></textarea>
											</td>
											<td class="bg_color_e4e4e4">
												<select size="1" name="employee">
													<option><?php echo $_COOKIE["userinitials"] ?></option>
													<?php
													db_b2b();
													$eq = "SELECT * FROM employees WHERE status='Active'";
													$dt_view_res = db_query($eq);
													while ($emp = array_shift($dt_view_res)) {
													?>
														<option><?php echo $emp["initials"] ?></option>
													<?php  } ?>
												</select>
											</td>
											<td class="bg_color_e4e4e4" rowspan="2" align="middle">
												<input style="cursor:pointer;" type="submit" value="Add">
											</td>
										</tr>
										<tr>
											<td class="bg_color_e4e4e4" colspan="4">
												<div id="div_crm_comp_email" style="display:none;">
													<?php
													$email_array[] = "";
													$sellto_main_email_array_cnt = 0;
													$equery = "select ID, contact, email, shipemail, shipContact from companyInfo where ID=?";
													$eqry_res = db_query($equery, array("i"), array($b2bid));
													$email_array1 = ""; $email_array2 = ""; $email_contact_array1 = ""; 
													$ship_email_contact_ar = ""; $ship_email_contact_array1 = "";
													$email_contact_array1 = "";
													while ($erows = array_shift($eqry_res)) {
														$new_emailto = "";
														if ($erows["email"] != "") {
															$email_to = $erows["email"];
															$email_contact = $erows["contact"];
															
															if (strpos($email_to, ";") !== false) {
																$new_emailto = str_replace(";", ",", $email_contact . " - " . $email_to);
																$noofemail = count(explode(",", $new_emailto));
															} else {
																$new_emailto = $email_contact . " - " . $email_to;
																$email_contact_ar = $email_contact;
															}

															$email_array1 .= $new_emailto . ",";
															$email_contact_array1 .= $email_contact_ar . ",";
															$sellto_main_email_array_cnt = 1;
														}
														//
														if ($erows["shipemail"] != "") {
															$semail_to = $erows["shipemail"];
															$ship_contact = $erows["shipContact"];
															if (strpos($semail_to, ";") !== false) {
																$new_semailto = str_replace(";", ",", $ship_contact . " - " . $semail_to);
																$noofemail = count(explode(",", $new_emailto));
																for ($i = 0; $i <= $noofemail; $i++) {
																	$ship_email_contact_ar .= $ship_contact . ",";
																}
															} else {
																$new_semailto = $ship_contact . " - " . $semail_to;
																$ship_email_contact_ar = $ship_contact;
															}
															$email_array2 .= $new_semailto . ",";
															$ship_email_contact_array1 .= $ship_email_contact_ar . ",";
															$sellto_main_email_array_cnt = 1;
														}
													}
													//					 
													$billto_email_arry = ""; $bemail_contact_arry = ""; 
													$sellto_email_arry = ""; $semail_contact_arry = "";
													$ebquery = "Select * from b2bbillto where email <> '' and companyid =?";
													$ebqry_res = db_query($ebquery, array("i"), array($b2bid));
													while ($ebrows = array_shift($ebqry_res)) {
														$email_billto = $ebrows["email"];
														$bemail_contact = $ebrows["name"];
														if (strpos($email_billto, ";") !== false) {
															$new_emailto = str_replace(";", ",", $bemail_contact . " - " . $email_billto);
															$bemail_contact_ar = $bemail_contact . "," . $bemail_contact;
														} else {
															$new_emailto = $bemail_contact . " - " . $email_billto;
															$bemail_contact_ar = $bemail_contact;
														}

														$billto_email_arry .= $new_emailto . ",";
														$bemail_contact_arry .= $bemail_contact_ar . ",";
													}

													$esquery = "Select * from b2bsellto where email <> '' and  companyid =? order by selltoid";
													$esqry_res = db_query($esquery, array("i"), array($b2bid));
													while ($esrows = array_shift($esqry_res)) {
														$email_sellto = $esrows["email"];
														$semail_contact = $esrows["name"];
														if (strpos($email_sellto, ";") !== false) {
															$new_emailsellto = str_replace(";", ",", $semail_contact . " - " . $email_sellto);
															$semail_contact_ar = $semail_contact . "," . $semail_contact;
														} else {
															$new_emailsellto   = $semail_contact . " - " . $email_sellto;
															$semail_contact_ar = $semail_contact;
														}
														$sellto_email_arry .= $new_emailsellto . ",";
														$semail_contact_arry .= $semail_contact_ar . ",";
													}
													//

													$billto_email_array_cnt = 0;
													$billto_email_arry1 = rtrim($billto_email_arry, ',');
													if ($billto_email_arry1 != "") {
														$billto_email_array = explode(",", $billto_email_arry1);
														$billto_email_array_cnt = 1;
													} else {
														$billto_email_array = "";
														$billto_email_array_cnt = 0;
													}

													$bill_email_contact = $bemail_contact_arry;
													$bill_email_contact1 = rtrim($bill_email_contact, ',');
													if ($bill_email_contact1 != "") {
														$bill_email_contact_array = explode(",", $bill_email_contact1);
													} else {
														$bill_email_contact_array = "";
													}

													//
													$sellto_email_array_cnt = 0;
													$sellto_email_arry1 = rtrim($sellto_email_arry, ',');
													if ($sellto_email_arry1 != "") {
														$sellto_email_array = explode(",", $sellto_email_arry1);
														$sellto_email_array_cnt = 1;
													} else {
														$sellto_email_array = "";
													}

													$sell_email_contact = $semail_contact_arry;
													$sell_email_contact1 = rtrim($sell_email_contact, ',');
													if ($sell_email_contact1 != "") {
														$sell_email_contact_array = explode(",", $sell_email_contact1);
													} else {
														$sell_email_contact_array = "";
													}

													//
													$earray = $email_array1 . $email_array2;
													$earray1 = rtrim($earray, ',');
													if ($earray1 != "") {
														$email_array = explode(",", $earray1);
													} else {
														$email_array = $email_array1 . $email_array2;
													}
													//var_dump($email_array);
													$all_email_contact = $email_contact_array1 . $ship_email_contact_array1;
													$all_email_contact1 = rtrim($all_email_contact, ',');
													if ($all_email_contact1 != "") {
														$comp_email_contact_array = explode(",", $all_email_contact1);
													} else {
														$comp_email_contact_array = "";
													}
													$email_list = array();
													//echo $sellto_main_email_array_cnt . " - " . $billto_email_array_cnt . " - " . $sellto_email_array_cnt . "<br>";
													if (($billto_email_array_cnt > 0) || ($sellto_email_array_cnt > 0)) {
														if ($billto_email_array_cnt > 0) {
															$email_list = array_unique(array_merge($email_array, $billto_email_array));
														}
														if ($sellto_email_array_cnt > 0) {
															$email_list = array_unique(array_merge($email_array, $sellto_email_array));
														}
														if (($sellto_main_email_array_cnt == 0)	&& ($billto_email_array_cnt > 0) && ($sellto_email_array_cnt > 0)) {
															$email_list = array_unique(array_merge($billto_email_array, $sellto_email_array));
														}
														if (($sellto_main_email_array_cnt == 0)	&& ($billto_email_array_cnt > 0) && ($sellto_email_array_cnt == 0)) {
															$email_list = array_unique($billto_email_array);
														}
														if (($sellto_main_email_array_cnt == 0)	&& ($billto_email_array_cnt == 0) && ($sellto_email_array_cnt > 0)) {
															$email_list = array_unique($sellto_email_array);
														}
													} else {
														$email_list = array_unique($email_array);
													}
													//var_dump($email_list);
													//$MGArraysort_I = array();

													//foreach ($email_list as $MGArraytmp) {
													//	$MGArraysort_I[] = $MGArraytmp[0];
													//}
													//array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$email_list); 
													?>
													<span class='font_size_2'>Whom you have contacted:</span>
													<select size="1" name="crm_comp_email" id="crm_comp_email">
														<option value=''>Please select</option>
														<?php
														foreach ($email_list as $email_list_ar) {
														?>
															<option value='<?php echo $email_list_ar; ?>'><?php echo $email_list_ar; ?></option>
														<?php
														}
														?>
													</select>
												</div>
											</td>
										</tr>

										<tr>
											<td class="bg_color_e4e4e4" colspan="4"><input type="file" name="file" size="50">
											</td>
										</tr>
									</table>
								</form>

								<?php
								$searchEmail = "";
								if (isset($_REQUEST["searchEmail"])) {
									$searchEmail = $_REQUEST["searchEmail"];
								}
								?>
								<span id='getcrmdata' onclick="get_crm_details(<?php echo $b2bid; ?>, '<?php echo $searchEmail; ?>', <?php echo $eu; ?>, <?php echo $limit; ?>, '<?php echo $page_name; ?>' )"><span class="font_family_Ariel font_size_1"><u>Expand CRM</u></span></span> /
								<span id='collapse_getcrmdata' onclick='collapse_get_crm_details()'><span class="font_family_Ariel font_size_1"><u>Collapse CRM</u></span></span>

						<tr>
							<td class="bg_color_e4e4e4" colspan="4">
								<div id="div_crm_data">
								</div>
							</td>
						</tr>

						<?php if ($_REQUEST["callfrompg"] == 1) { ?>
							<script>
								get_crm_details(<?php echo $b2bid; ?>, '<?php echo $searchEmail; ?>', <?php echo $eu; ?>, <?php echo $limit; ?>, '<?php echo $page_name; ?>');
							</script>
						<?php  } ?>

			</td>
		</tr>
	</table>
<?php
				}
				///////////////////////////////////////////////////////////////////////////////// End communications

			} //crm_trans_tbl_flg flg
?>