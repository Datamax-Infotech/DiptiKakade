<?php
function show_demand_entry(int $b2bid): void
{

?>
	<html>

	<head>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<link rel="stylesheet" type="text/css" href="/css/tooltip_style.css" />
		<style>
			.scrollit {
				overflow: auto;
				width: 1200px;
				height: 450px;
			}

			.form_component {
				font-size: 11px;
				margin-top: 2px;
				margin-bottom: 2px;
				font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
			}

			.display_title {
				font-size: 11px;
				padding: 3px;
				font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
				background: #ABC5DF;
				/*white-space:nowrap;*/
			}

			.display_table {
				font-size: 11px;
				padding: 3px;
				font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
				background: gainsboro;
			}

			.display_table a {
				color: #004CB3;
				font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
			}

			.display_table_alt {
				font-size: 11px;
				padding: 3px;
				font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
				background: #F7F7F7;
			}

			.display_table_alt a {
				color: #004CB3;
				font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
			}

			.display_table_expand_v {
				font-size: 11px;
				padding: 3px;
				font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
				background: #ffe3c0;
			}

			.display_table_expand_v a {
				color: #004CB3;
				font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
			}

			.display_table_expand_v_alt {
				font-size: 11px;
				padding: 3px;
				font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
				background: #fff4e6;
			}

			.display_table_expand_v_alt a {
				color: #004CB3;
				font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
			}

			.display_inact {
				font-size: 11px;
				padding: 3px;
				font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
				background: #f5dddc;
			}

			.display_title_inact {
				font-size: 11px;
				padding: 3px;
				font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
				background: #f9c1be;
			}

			.size_align {
				display: inline-block;
				text-align: center;
			}

			.ex_col_btn {
				cursor: pointer;
				font-size: 12px;
				font-family: "Calibri";
				color: #0000ff;
			}

			.size_txt_center {
				text-align: center;
			}

			table.qrtable tr td {
				/*  border: 1px solid #EBEBEB;*/
				background-color: #d6d6d6 !important;
			}

			table.item {
				display: none;
			}

			table.item tr td {
				/*background:#e4e4e4;*/
				font-size: 11px;
				font-family: Arial, Helvetica, sans-serif;
				color: "#333333";
			}

			/* 
table.item tr:nth-child(even){
	/*background: #f1f1f1;* /
}
*/
			.label_txt {
				font-size: 12px;
			}

			.table1 tr td {
				/*background:#e4e4e4;*/
				font-size: 11px;
				font-family: Arial, Helvetica, sans-serif;
				color: "#333333";
			}

			.table2 tr td {
				border: 1px solid #FFF;
				padding-left: 8px;
			}

			.table2 tr:nth-child(3) td {
				border: none;
				padding-left: 0px;
			}

			.table3 tr td {
				border: 1px solid #FFF;
				padding-left: 8px;
			}

			.minmax_display {
				float: left;
				width: 50%;
			}

			.in_table_style {
				border-collapse: collapse;
			}

			.in_table_style tr td,
			.in_table_style tr th {
				border: 1px solid #FFF !important;
				padding: 8px !important;
			}

			.quotes-generated-style tr td {
				background-color: #E4E4E4;
				font-size: 11px;
				font-family: Arial, Helvetica, sans-serif;
			}

			.quotes-generated-style tr:nth-child(2) td {
				background-color: #d6d6d6;
			}

			.quotes-generated-style tr td a {
				font-size: 11px;
				font-family: Arial, Helvetica, sans-serif;
			}

			.quote_req_div_left {
				width: 50%;
				float: left;
			}

			.quote_req_div_right {
				width: 50%;
				float: left;
				margin-left: 12px;
			}
		</style>
		<style type="text/css">
			.inner_table_style tr td {
				border: none;
			}

			.black_overlay {
				display: none;
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
				display: none;
				position: absolute;
				top: 5%;
				left: 10%;
				width: 60%;
				height: 70%;
				padding: 16px;
				border: 1px solid gray;
				background-color: white;
				z-index: 1002;
				overflow: auto;
			}

			.white_content_details {
				display: none;
				position: absolute;
				top: 0%;
				left: 10%;
				width: 50%;
				height: auto;
				padding: 16px;
				border: 1px solid gray;
				background-color: white;
				z-index: 1002;
				overflow: auto;
				box-shadow: 8px 8px 5px #888888;
			}

			.white_content_gaylord_autload {
				display: none;
				position: absolute;
				top: 0%;
				left: 0%;
				width: 200px;
				height: 520px;
				padding: 16px;
				border: 1px solid gray;
				background-color: white;
				-moz-box-shadow: 6px 6px 6px 6px #888888;
				-webkit-box-shadow: 6px 6px 6px 6px #888888;
				box-shadow: 6px 6px 6px 6px #888888;
				filter: progid:DXImageTransform.Microsoft.DropShadow(OffX=6, OffY=6, Color=#888888);
			}

			.white_content_gaylord_new {
				display: none;
				position: absolute;
				top: 0%;
				left: 0%;
				width: 1200px;
				height: 520px;
				padding: 16px;
				border: 1px solid gray;
				background-color: white;
				z-index: 1002;
				-moz-box-shadow: 6px 6px 6px 6px #888888;
				-webkit-box-shadow: 6px 6px 6px 6px #888888;
				box-shadow: 6px 6px 6px 6px #888888;
				filter: progid:DXImageTransform.Microsoft.DropShadow(OffX=6, OffY=6, Color=#888888);
			}

			.white_content_gaylord_new1 {
				display: none;
				position: absolute;
				top: 0%;
				left: 0%;
				width: 1200px;
				height: 520px;
				padding: 16px;
				border: 1px solid gray;
				background-color: white;
				z-index: 1002;
				-moz-box-shadow: 6px 6px 6px 6px #888888;
				-webkit-box-shadow: 6px 6px 6px 6px #888888;
				box-shadow: 6px 6px 6px 6px #888888;
				filter: progid:DXImageTransform.Microsoft.DropShadow(OffX=6, OffY=6, Color=#888888);
			}

			.white_content_gaylord {
				display: none;
				position: absolute;
				top: 0%;
				left: 0%;
				width: 600px;
				height: 520px;
				padding: 16px;
				border: 1px solid gray;
				background-color: white;
				z-index: 1002;
				-moz-box-shadow: 6px 6px 6px 6px #888888;
				-webkit-box-shadow: 6px 6px 6px 6px #888888;
				box-shadow: 6px 6px 6px 6px #888888;
				filter: progid:DXImageTransform.Microsoft.DropShadow(OffX=6, OffY=6, Color=#888888);
			}

			.display_table_child {
				font-size: 11px;
				padding: 3px;
				font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
				/*background: #d6d2d2;*/
				/*background: #87afd6;*/
				background: #bac0c6;
			}

			.display_table_child a {
				font-size: 11px;
				color: #004CB3;
				font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
			}

			.display_table_alt_child {
				font-size: 11px;
				padding: 3px;
				font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
				/*background: #c7c7c7;*/
				/*background: #8ac1f7;*/
				background: #fff;
			}

			.display_table_alt_child a {
				font-size: 11px;
				color: #004CB3;
				font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
			}
		</style>
		<SCRIPT language=Javascript>
			<!--
			function isNumberKey(evt) {
				var charCode = (evt.which) ? evt.which : evt.keyCode;
				if (charCode != 46 && charCode > 31 &&
					(charCode < 48 || charCode > 57))
					return false;

				return true;
			}
			//
			-->
		</SCRIPT>
		<script type="text/javascript">
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
					n_pos -= n_offset;
					e_elem = e_elem.parentNode;
				}
				return n_pos;
			}

			$(document).ready(function() {

				var de = $('#quote_item').val();
				if (de != "-1") {
					$('table#' + $('#quote_item').val()).show();
				}
				//
				$('table.table').hide();
				$('#uniform_mixed').hide();
				//$("#quote_item")[0].selectedIndex = 0;
				//
				$('#quote_item').change(function() {
					if ($(this).val() != "-1") {
						$('table.table').hide();
						$('table#' + $(this).val()).show();

						if (document.getElementById("box_sub_type")) {
							document.getElementById("box_sub_type").value = 11;
							box_sub_type_load_ctrl();
						}

						if (document.getElementById("box_pallet_sub_type")) {
							document.getElementById("box_pallet_sub_type").value = 17;
							box_pallet_sub_type_load_ctrl();
						}

					}
					if ($(this).val() == "-1") {
						$('table.table').hide();
					}
				})

				//Show gaylord totes on change uniform or mixed
				$('#select_uniform_mixed').change(function() {
					if ($(this).val() != "-1") {
						$('#gayloard_rescue_tbl').hide();
						$('#gayloard_rescue_tbl_mixed').hide();
						$('#shipping_rescue_tbl').hide();
						$('#supersack_rescue_tbl').hide();
						$('#pallets_rescue_tbl').hide();

						if ($(this).val() == "Uniform Load") {
							$('#gayloard_rescue_tbl').show();
						}
						if ($(this).val() == "Mixed Load") {
							$('#gayloard_rescue_tbl_mixed').show();
						}
					}
					if ($(this).val() == "-1") {
						$('#gayloard_rescue_tbl').hide();
						$('#gayloard_rescue_tbl_mixed').hide();
						$('#shipping_rescue_tbl').hide();
						$('#supersack_rescue_tbl').hide();
						$('#pallets_rescue_tbl').hide();
					}
				})

				//

				$("#g_item_max_length").change(function() {
					var newmaxval = parseInt(document.getElementById("g_item_max_length").value);
					var newminval = parseInt(document.getElementById('g_item_min_length').value);
					if (newminval >= newmaxval) {
						alert("Please select correct value");
						document.getElementById('g_item_max_length').value = "";
					}
				});
				$("#g_item_max_width").change(function() {
					var newmaxval1 = parseInt(document.getElementById("g_item_max_width").value);
					var newminval1 = parseInt(document.getElementById('g_item_min_width').value);
					if (newminval1 >= newmaxval1) {
						alert("Please select correct value");
						document.getElementById('g_item_max_width').value = "";
					}
				});
				$("#g_item_max_height").change(function() {
					var newmaxval2 = parseInt(document.getElementById("g_item_max_height").value);
					var newminval2 = parseInt(document.getElementById('g_item_min_height').value);
					if (newminval2 >= newmaxval2) {
						alert("Please select correct value");
						document.getElementById('g_item_max_height').value = "";
					}
				});
				$("#sb_item_max_length").change(function() {
					var newmaxval = parseInt(document.getElementById("sb_item_max_length").value);
					var newminval = parseInt(document.getElementById('sb_item_min_height').value);
					if(newminval >= newmaxval) {
						alert("Please select correct value");
						document.getElementById('sb_item_max_length').value = "";
					}

				});
				$("#sb_item_max_width").change(function() {
					var newmaxval = parseInt(document.getElementById("sb_item_max_width").value);
					var newminval = parseInt(document.getElementById('sb_item_min_width').value);
					if (newminval >= newmaxval) {
						alert("Please select correct value");
						document.getElementById('sb_item_max_width').value = "";
					}
				});
				$("#sb_item_max_height").change(function() {
					var newmaxval = parseInt(document.getElementById("sb_item_max_height").value);
					var newminval = parseInt(document.getElementById('sb_item_min_height').value);
					if (newminval >= newmaxval) {
						alert("Please select correct value");
						document.getElementById('sb_item_max_height').value = "";
					}
				});
				$("#g_item_max_length").change(function() {
					var newmaxval = parseInt(document.getElementById("g_item_max_length").value);
					var newminval = parseInt(document.getElementById('g_item_min_length').value);
					if (newminval >= newmaxval) {
						alert("Please select correct value");
						document.getElementById('g_item_max_length').value = "";
					}
				});
				$("#g_item_max_width").change(function() {
					var newmaxval1 = parseInt(document.getElementById("g_item_max_width").value);
					var newminval1 = parseInt(document.getElementById('g_item_min_width').value);
					if (newminval1 >= newmaxval1) {
						alert("Please select correct value");
						document.getElementById('g_item_max_width').value = "";
					}
				});
				$("#g_item_max_height").change(function() {
					var newmaxval2 = parseInt(document.getElementById("g_item_max_height").value);
					var newminval2 = parseInt(document.getElementById('g_item_min_height').value);
					if (newminval2 >= newmaxval2) {
						alert("Please select correct value");
						document.getElementById('g_item_max_height').value = "";
					}
				});
				$("#sb_item_max_length").change(function() {
					var newmaxval = parseInt(document.getElementById("sb_item_max_length").value);
					var newminval = parseInt(document.getElementById('sb_item_min_height').value);
					if(newminval >= newmaxval) {
						alert("Please select correct value");
						document.getElementById('sb_item_max_length').value = "";
					}

				});
				$("#sb_item_max_width").change(function() {
					var newmaxval = parseInt(document.getElementById("sb_item_max_width").value);
					var newminval = parseInt(document.getElementById('sb_item_min_width').value);
					if (newminval >= newmaxval) {
						alert("Please select correct value");
						document.getElementById('sb_item_max_width').value = "";
					}
				});
				$("#sb_item_max_height").change(function() {
					var newmaxval = parseInt(document.getElementById("sb_item_max_height").value);
					var newminval = parseInt(document.getElementById('sb_item_min_height').value);
					if (newminval >= newmaxval) {
						alert("Please select correct value");
						document.getElementById('sb_item_max_height').value = "";
					}
				});
			});
		</script>
		<script>
			function box_sub_type_load_ctrl() {
				if (document.getElementById("box_sub_type").value == 11) {
					document.getElementById("div_gaylord_criteria1").style.display = "table-row";
					document.getElementById("div_gaylord_criteria2").style.display = "table-row";
					document.getElementById("div_gaylord_criteria3").style.display = "table-row";
					document.getElementById("div_gaylord_criteria4").style.display = "table-row";
					document.getElementById("div_gaylord_criteria5").style.display = "table-row";
					document.getElementById("div_gaylord_criteria6").style.display = "table-row";
					document.getElementById("div_gaylord_criteria7").style.display = "table-row";
					document.getElementById("div_gaylord_criteria8").style.display = "table-row";
					document.getElementById("div_gaylord_criteria9").style.display = "table-row";
					document.getElementById("div_gaylord_criteria10").style.display = "table-row";
					document.getElementById("div_gaylord_criteria11").style.display = "table-row";
					document.getElementById("div_gaylord_criteria12").style.display = "table-row";
					document.getElementById("div_gaylord_criteria13").style.display = "table-row";
					document.getElementById("div_gaylord_criteria14").style.display = "table-row";
				} else {
					document.getElementById("div_gaylord_criteria1").style.display = "none";
					document.getElementById("div_gaylord_criteria2").style.display = "none";
					document.getElementById("div_gaylord_criteria3").style.display = "none";
					document.getElementById("div_gaylord_criteria4").style.display = "none";
					document.getElementById("div_gaylord_criteria5").style.display = "none";
					document.getElementById("div_gaylord_criteria6").style.display = "none";
					document.getElementById("div_gaylord_criteria7").style.display = "none";
					document.getElementById("div_gaylord_criteria8").style.display = "none";
					document.getElementById("div_gaylord_criteria9").style.display = "none";
					document.getElementById("div_gaylord_criteria10").style.display = "none";
					document.getElementById("div_gaylord_criteria11").style.display = "none";
					document.getElementById("div_gaylord_criteria12").style.display = "none";
					document.getElementById("div_gaylord_criteria13").style.display = "none";
					document.getElementById("div_gaylord_criteria14").style.display = "none";
				}
			}

			function box_sub_type_load_ctrl_sub(tableid) {
				if (document.getElementById("box_sub_type" + tableid).value == 11) {
					document.getElementById("div_gaylord_criteria1" + tableid).style.display = "table-row";
					document.getElementById("div_gaylord_criteria2" + tableid).style.display = "table-row";
					document.getElementById("div_gaylord_criteria3" + tableid).style.display = "table-row";
					document.getElementById("div_gaylord_criteria4" + tableid).style.display = "table-row";
					document.getElementById("div_gaylord_criteria5" + tableid).style.display = "table-row";
					document.getElementById("div_gaylord_criteria6" + tableid).style.display = "table-row";
					document.getElementById("div_gaylord_criteria7" + tableid).style.display = "table-row";
					document.getElementById("div_gaylord_criteria8" + tableid).style.display = "table-row";
					document.getElementById("div_gaylord_criteria9" + tableid).style.display = "table-row";
					document.getElementById("div_gaylord_criteria10" + tableid).style.display = "table-row";
					document.getElementById("div_gaylord_criteria11" + tableid).style.display = "table-row";
					document.getElementById("div_gaylord_criteria12" + tableid).style.display = "table-row";
					document.getElementById("div_gaylord_criteria13" + tableid).style.display = "table-row";
					document.getElementById("div_gaylord_criteria14" + tableid).style.display = "table-row";
				} else {
					document.getElementById("div_gaylord_criteria1" + tableid).style.display = "none";
					document.getElementById("div_gaylord_criteria2" + tableid).style.display = "none";
					document.getElementById("div_gaylord_criteria3" + tableid).style.display = "none";
					document.getElementById("div_gaylord_criteria4" + tableid).style.display = "none";
					document.getElementById("div_gaylord_criteria5" + tableid).style.display = "none";
					document.getElementById("div_gaylord_criteria6" + tableid).style.display = "none";
					document.getElementById("div_gaylord_criteria7" + tableid).style.display = "none";
					document.getElementById("div_gaylord_criteria8" + tableid).style.display = "none";
					document.getElementById("div_gaylord_criteria9" + tableid).style.display = "none";
					document.getElementById("div_gaylord_criteria10" + tableid).style.display = "none";
					document.getElementById("div_gaylord_criteria11" + tableid).style.display = "none";
					document.getElementById("div_gaylord_criteria12" + tableid).style.display = "none";
					document.getElementById("div_gaylord_criteria13" + tableid).style.display = "none";
					document.getElementById("div_gaylord_criteria14" + tableid).style.display = "none";
				}
			}

			function box_pallet_sub_type_load_ctrl() {
				if (document.getElementById("box_pallet_sub_type").value == 17) {
					document.getElementById("div_pallet_criteria1").style.display = "table-row";
					document.getElementById("div_pallet_criteria2").style.display = "table-row";
					document.getElementById("div_pallet_criteria3").style.display = "table-row";
					document.getElementById("div_pallet_criteria4").style.display = "table-row";
					document.getElementById("div_pallet_criteria5").style.display = "table-row";
					document.getElementById("div_pallet_criteria6").style.display = "table-row";
				} else {
					document.getElementById("div_pallet_criteria1").style.display = "none";
					document.getElementById("div_pallet_criteria2").style.display = "none";
					document.getElementById("div_pallet_criteria3").style.display = "none";
					document.getElementById("div_pallet_criteria4").style.display = "none";
					document.getElementById("div_pallet_criteria5").style.display = "none";
					document.getElementById("div_pallet_criteria6").style.display = "none";
				}
			}

			function box_pallet_sub_type_load_ctrl_sub(tableid) {
				if (document.getElementById("box_pallet_sub_type" + tableid).value == 17) {
					document.getElementById("div_pallet_criteria1" + tableid).style.display = "table-row";
					document.getElementById("div_pallet_criteria2" + tableid).style.display = "table-row";
					document.getElementById("div_pallet_criteria3" + tableid).style.display = "table-row";
					document.getElementById("div_pallet_criteria4" + tableid).style.display = "table-row";
					document.getElementById("div_pallet_criteria5" + tableid).style.display = "table-row";
					document.getElementById("div_pallet_criteria6" + tableid).style.display = "table-row";
				} else {
					document.getElementById("div_pallet_criteria1" + tableid).style.display = "none";
					document.getElementById("div_pallet_criteria2" + tableid).style.display = "none";
					document.getElementById("div_pallet_criteria3" + tableid).style.display = "none";
					document.getElementById("div_pallet_criteria4" + tableid).style.display = "none";
					document.getElementById("div_pallet_criteria5" + tableid).style.display = "none";
					document.getElementById("div_pallet_criteria6" + tableid).style.display = "none";
				}
			}

			function quote_req_quote_type_chg() {
				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else { // code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("div_quote_request_main").innerHTML = xmlhttp.responseText;
					}
				}

				b2bid = document.getElementById("quote_req_compid").value;
				quote_req_quote_type = document.getElementById("quote_req_quote_type").value;

				xmlhttp.open("GET", "quote_req_tracker_chg.php?company_id=" + b2bid + "&quote_req_quote_type=" + quote_req_quote_type, true);
				xmlhttp.send();
			}

			function quote_req_tracker_deny(comp_id, quote_request_tracker_id, quote_req_cnt) {
				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else { // code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("div_quote_request").innerHTML = xmlhttp.responseText;
					}
				}

				txtqreq_quoteno = document.getElementById("txtqreq_quoteno" + quote_req_cnt).value;

				xmlhttp.open("GET", "quote_req_tracker_add_save.php?deny=1&company_id=" + comp_id + "&qreq_quoteno=" + txtqreq_quoteno + "&quote_request_tracker_id=" + quote_request_tracker_id + "&quote_req_cnt=" + quote_req_cnt, true);
				xmlhttp.send();
			}

			function quote_req_tracker_update(comp_id, quote_request_tracker_id, quote_req_cnt) {

				txtqreq_quoteno = "";
				if (document.getElementById("txtqreq_quoteno" + quote_req_cnt)) {
					txtqreq_quoteno = document.getElementById("txtqreq_quoteno" + quote_req_cnt).value;
				}

				var txtqreq_notes = document.getElementById('txtqreq_notes' + quote_req_cnt);

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else { // code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("div_quote_request").innerHTML = xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "quote_req_tracker_add_save.php?upd=1&company_id=" + comp_id + "&qreq_quoteno=" + txtqreq_quoteno + "&quote_request_tracker_id=" + quote_request_tracker_id + "&quote_req_cnt=" + quote_req_cnt + "&txtqreq_notes=" + encodeURIComponent(txtqreq_notes.value), true);
				xmlhttp.send();

			}

			function add_quote_req_tracker() {
				b2bid = document.getElementById("quote_req_compid").value;
				demand_entry_list = document.getElementById("demand_entry_list").value;
				quote_type = document.getElementById("quote_req_quote_type").value;

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else { // code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("div_quote_request").innerHTML = xmlhttp.responseText;
						quote_request_send_email(demand_entry_list, b2bid, quote_type);
					}
				}

				xmlhttp.open("GET", "quote_req_tracker_add_save.php?add=1&company_id=" + b2bid + "&demand_entry_list=" + demand_entry_list, true);
				xmlhttp.send();
			}
			//
		</script>

		<script>
			function show_loc_dtls(vendorB2bRescueId, cntSubRow, sortlocationType) {

				for (var i = 0; i < cntSubRow; i++) {
					var x = document.getElementById("loc_sub_table_" + i + "_" + vendorB2bRescueId);
					var shipFrom = document.getElementById("loc_btn_" + vendorB2bRescueId).innerHTML;
					if (x.style.display === "none") {
						x.style.display = "block";
						x.removeAttribute('style');
						document.getElementById("loc_btn_" + vendorB2bRescueId).innerHTML = shipFrom;
						document.getElementById("selrow" + vendorB2bRescueId).style.backgroundColor = '#e1e8fb';

					} else {
						document.getElementById("selrow" + vendorB2bRescueId).style.backgroundColor = 'gainsboro';

						x.style.display = "none";
						document.getElementById("loc_btn_" + vendorB2bRescueId).innerHTML = shipFrom;
					}
				}
			}

			function show_g_details(gid) {
				var x = document.getElementById("g_sub_table" + gid);
				if (x.style.display === "none") {
					x.style.display = "block";
					document.getElementById("g_btn" + gid).innerHTML = "Collapse Details";
				} else {
					x.style.display = "none";
					document.getElementById("g_btn" + gid).innerHTML = "Expand Details";
				}
			}

			function show_sb_details(sbid) {
				var sb = document.getElementById("sb_sub_table" + sbid);
				if (sb.style.display === "none") {
					sb.style.display = "block";
					document.getElementById("sb_btn" + sbid).innerHTML = "Collapse Details";
				} else {
					sb.style.display = "none";
					document.getElementById("sb_btn" + sbid).innerHTML = "Expand Details";
				}
			}

			function show_sup_details(supid) {
				var sup = document.getElementById("sup_sub_table" + supid);
				if (sup.style.display === "none") {
					sup.style.display = "block";
					document.getElementById("sup_btn" + supid).innerHTML = "Collapse Details";
				} else {
					sup.style.display = "none";
					document.getElementById("sup_btn" + supid).innerHTML = "Expand Details";
				}
			}

			function show_pal_details(palid) {
				var pal = document.getElementById("pal_sub_table" + palid);
				if (pal.style.display === "none") {
					pal.style.display = "block";
					document.getElementById("pal_btn" + palid).innerHTML = "Collapse Details";
				} else {
					pal.style.display = "none";
					document.getElementById("pal_btn" + palid).innerHTML = "Expand Details";
				}
			}

			function show_other_details(otherid) {
				var other = document.getElementById("other_sub_table" + otherid);
				if (other.style.display === "none") {
					other.style.display = "block";
					document.getElementById("other_btn" + otherid).innerHTML = "Collapse Details";

				} else {
					other.style.display = "none";
					document.getElementById("other_btn" + otherid).innerHTML = "Expand Details";
				}
			}
			//
			var openurl = "";
			//

			function quote_save(b2bid) {
				var g_item_length = document.getElementById("g_item_length").value;
				var g_item_width = document.getElementById("g_item_width").value;
				var g_item_height = document.getElementById("g_item_height").value;
				var g_item_min_height = document.getElementById("g_item_min_height").value;
				var g_item_max_height = document.getElementById("g_item_max_height").value;
				var sales_desired_price_g = document.getElementById("sales_desired_price_g").value;

				//
				var g_shape_rectangular, g_shape_octagonal, g_wall_1, g_wall_2, g_wall_3, g_wall_4, g_wall_5, g_wall_6, g_wall_7, g_wall_8, g_wall_9, g_wall_10, g_no_top, g_lid_top, g_partial_flap_top, g_full_flap_top, g_no_bottom_config, g_partial_flap_w, g_full_flap_bottom, g_tray_bottom, g_partial_flap_wo, g_vents_okay;
				if (document.getElementById("g_shape_rectangular").checked) {
					g_shape_rectangular = document.getElementById("g_shape_rectangular").value;
				} else {
					g_shape_rectangular = "";
				}
				if (document.getElementById("g_shape_octagonal").checked) {
					g_shape_octagonal = document.getElementById("g_shape_octagonal").value;
				} else {
					g_shape_octagonal = "";
				}
				if (document.getElementById("g_wall_1").checked) {
					var g_wall_1 = document.getElementById("g_wall_1").value;
				} else {
					g_wall_1 = "";
				}
				if (document.getElementById("g_wall_2").checked) {
					var g_wall_2 = document.getElementById("g_wall_2").value;
				} else {
					g_wall_2 = "";
				}
				if (document.getElementById("g_wall_3").checked) {
					var g_wall_3 = document.getElementById("g_wall_3").value;
				} else {
					g_wall_3 = "";
				}
				if (document.getElementById("g_wall_4").checked) {
					var g_wall_4 = document.getElementById("g_wall_4").value;
				} else {
					g_wall_4 = "";
				}
				if (document.getElementById("g_wall_5").checked) {
					g_wall_5 = document.getElementById("g_wall_5").value;
				} else {
					g_wall_5 = "";
				}
				if (document.getElementById("g_wall_6").checked) {
					g_wall_6 = document.getElementById("g_wall_6").value;
				} else {
					g_wall_6 = "";
				}
				if (document.getElementById("g_wall_7").checked) {

					g_wall_7 = document.getElementById("g_wall_7").value;
				} else {
					g_wall_7 = "";
				}

				if (document.getElementById("g_wall_8").checked) {
					g_wall_8 = document.getElementById("g_wall_8").value;
				} else {
					g_wall_8 = "";
				}
				if (document.getElementById("g_wall_9").checked) {
					g_wall_9 = document.getElementById("g_wall_9").value;
				} else {
					g_wall_9 = "";
				}
				if (document.getElementById("g_wall_10").checked) {
					g_wall_10 = document.getElementById("g_wall_10").value;
				} else {
					g_wall_10 = "";
				}
				if (document.getElementById("g_no_top").checked) {
					g_no_top = document.getElementById("g_no_top").value;
				} else {
					g_no_top = "";
				}
				if (document.getElementById("g_lid_top").checked) {
					g_lid_top = document.getElementById("g_lid_top").value;
				} else {
					g_lid_top = "";
				}
				if (document.getElementById("g_partial_flap_top").checked) {
					g_partial_flap_top = document.getElementById("g_partial_flap_top").value;
				} else {
					g_partial_flap_top = "";
				}
				if (document.getElementById("g_full_flap_top").checked) {
					g_full_flap_top = document.getElementById("g_full_flap_top").value;
				} else {
					g_full_flap_top = "";
				}
				if (document.getElementById("g_no_bottom_config").checked) {
					g_no_bottom_config = document.getElementById("g_no_bottom_config").value;
				} else {
					g_no_bottom_config = "";
				}
				if (document.getElementById("g_partial_flap_w").checked) {
					g_partial_flap_w = document.getElementById("g_partial_flap_w").value;
				} else {
					g_partial_flap_w = "";
				}
				if (document.getElementById("g_tray_bottom").checked) {
					g_tray_bottom = document.getElementById("g_tray_bottom").value;
				} else {
					g_tray_bottom = "";
				}
				if (document.getElementById("g_full_flap_bottom").checked) {
					g_full_flap_bottom = document.getElementById("g_full_flap_bottom").value;
				} else {
					g_full_flap_bottom = "";
				}
				if (document.getElementById("g_partial_flap_wo").checked) {
					g_partial_flap_wo = document.getElementById("g_partial_flap_wo").value;
				} else {
					g_partial_flap_wo = "";
				}
				if (document.getElementById("g_vents_okay").checked) {
					g_vents_okay = document.getElementById("g_vents_okay").value;
				} else {
					g_vents_okay = "";
				}
				var need_pallets, quoterequest_saleslead_flag;
				if (document.getElementById("need_pallets").checked) {
					need_pallets = document.getElementById("need_pallets").value;
				} else {
					need_pallets = "";
				}

				//if(document.getElementById("quoterequest_saleslead_flag").checked)
				//	{
				//		quoterequest_saleslead_flag = document.getElementById("quoterequest_saleslead_flag").value;
				//	}
				//else{
				quoterequest_saleslead_flag = "";
				//}
				//
				var g_quantity_request = document.getElementById("g_quantity_request").value;
				var g_other_quantity = document.getElementById("g_other_quantity").value;
				//
				var g_frequency_order = document.getElementById("g_frequency_order").value;
				var g_how_many_order_per_yr = document.getElementById("g_how_many_order_per_yr").value;
				var g_what_used_for = document.getElementById("g_what_used_for").value;
				var date_needed_by = ""; //document.getElementById("date_needed_by").value;
				var g_item_note = encodeURIComponent(document.getElementById("g_item_note").value);
				var quote_item = document.getElementById("quote_item").value;
				var client_dash_flg;

				if (document.getElementById("client_dash_flg").checked) {
					client_dash_flg = document.getElementById("client_dash_flg").value;
				} else {
					client_dash_flg = 0;
				}

				var high_value_target;

				if (document.getElementById("high_value_target").checked) {
					high_value_target = document.getElementById("high_value_target").value;
				} else {
					high_value_target = 0;
				}
				//Validations--------------------------------------------------
				//
				var gmin = parseInt(g_item_min_height);
				var gmax = parseInt(g_item_max_height);
				//

				var box_sub_type = document.getElementById("box_sub_type").value;
				var box_criteria_flg = 0;

				if (box_sub_type == "") {

					if (g_item_min_height == 0 && g_item_max_height == 99) {
						box_criteria_flg = 1;
						var choice = confirm('Are you sure the customer has no height requirements (0-99)?');
						if (choice === false) {
							return false;
						}
					}

					if (gmin >= gmax) {
						box_criteria_flg = 1;
						alert("Please enter correct height");
						return false;
					}

					if (g_shape_rectangular == "" && g_shape_octagonal == "") {
						box_criteria_flg = 1;
						alert("Please select shape");
						return false;
					}
					if (g_wall_1 == "" && g_wall_2 == "" && g_wall_3 == "" && g_wall_4 == "" && g_wall_5 == "" && g_wall_6 == "" && g_wall_7 == "" && g_wall_8 == "" && g_wall_9 == "" && g_wall_10 == "") {
						box_criteria_flg = 1;
						alert("Please select atleast one # of Walls");
						return false;
					}
					//
					if (g_no_top == "" && g_lid_top == "" && g_partial_flap_top == "" && g_full_flap_top == "") {
						box_criteria_flg = 1;
						alert("Please select Top Config");
						return false;
					}
					//
					if (g_no_bottom_config == "" && g_partial_flap_w == "" && g_tray_bottom == "" && g_full_flap_bottom == "" && g_partial_flap_wo == "") {
						box_criteria_flg = 1;
						alert("Please select Bottom Config");
						return false;
					}
				}

				if (box_sub_type == "" && box_criteria_flg == 1) {
					alert("Please select Sub type.");
					return false;
				}

				//End Validations---------------------------------------------
				//
				if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				} else { // code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

						document.getElementById("display_quote_request").innerHTML = xmlhttp.responseText;
						alert("Record has been added successfully!!");
						$('table.table').hide();
						$('#quote_item').prop('selectedIndex', 0);
						//
						$("#show_q_div").load(location.href + " #show_q_div");
						//
						var new_quote_id = document.getElementById("quote_id_n").value;
						var comp_id = document.getElementById("comp_id").value;
						//
						if (quoterequest_saleslead_flag == "Yes") {
							//commented as new tracker is used
							//quote_request_send_email(new_quote_id,comp_id,1);
						}
						quote_req_quote_type_chg();
					}
				}
				xmlhttp.open("POST", openurl + "quote_request_save_new.php?addquotedata=1&company_id=" + b2bid + "&box_sub_type=" + box_sub_type + "&g_item_length=" + g_item_length + "&g_item_width=" + g_item_width + "&g_item_height=" + g_item_height + "&g_item_min_height=" + g_item_min_height + "&g_item_max_height=" + g_item_max_height + "&g_shape_rectangular=" + g_shape_rectangular + "&g_shape_octagonal=" + g_shape_octagonal + "&g_wall_1=" + g_wall_1 + "&g_wall_2=" + g_wall_2 + "&g_wall_3=" + g_wall_3 + "&g_wall_4=" + g_wall_4 + "&g_wall_5=" + g_wall_5 + "&g_wall_6=" + g_wall_6 + "&g_wall_7=" + g_wall_7 + "&g_wall_8=" + g_wall_8 + "&g_wall_9=" + g_wall_9 + "&g_wall_10=" + g_wall_10 + "&g_no_top=" + g_no_top + "&g_lid_top=" + g_lid_top + "&g_partial_flap_top=" + g_partial_flap_top + "&g_full_flap_top=" + g_full_flap_top + "&g_no_bottom_config=" + g_no_bottom_config + "&g_partial_flap_w=" + g_partial_flap_w + "&g_tray_bottom=" + g_tray_bottom + "&g_full_flap_bottom=" + g_full_flap_bottom + "&g_partial_flap_wo=" + g_partial_flap_wo + "&g_vents_okay=" + g_vents_okay + "&g_quantity_request=" + g_quantity_request + "&g_other_quantity=" + g_other_quantity + "&g_frequency_order=" + g_frequency_order + "&g_how_many_order_per_yr=" + g_how_many_order_per_yr + "&g_what_used_for=" + g_what_used_for + "&date_needed_by=" + date_needed_by + "&need_pallets=" + need_pallets + "&g_item_note=" + g_item_note + "&client_dash_flg=" + client_dash_flg + "&high_value_target=" + high_value_target + "&quoterequest_saleslead_flag=" + quoterequest_saleslead_flag + "&quote_item=" + quote_item + "&sales_desired_price_g=" + sales_desired_price_g, true);
				xmlhttp.send();

			}

			function sb_quote_save(b2bid) {

				var sb_item_length = document.getElementById("sb_item_length").value;
				var sb_item_width = document.getElementById("sb_item_width").value;
				var sb_item_height = document.getElementById("sb_item_height").value;
				var sb_item_min_length = document.getElementById("sb_item_min_length").value;
				var sb_item_max_length = document.getElementById("sb_item_max_length").value;
				var sb_item_min_width = document.getElementById("sb_item_min_width").value;
				var sb_item_max_width = document.getElementById("sb_item_max_width").value;
				var sb_item_min_height = document.getElementById("sb_item_min_height").value;
				var sb_item_max_height = document.getElementById("sb_item_max_height").value;
				var sb_cubic_footage_min = document.getElementById("sb_cubic_footage_min").value;
				var sb_cubic_footage_max = document.getElementById("sb_cubic_footage_max").value;
				var sb_date_needed_by = ""; //document.getElementById("sb_date_needed_by").value;

				var sb_sales_desired_price = document.getElementById("sb_sales_desired_price").value;

				var sb_quantity_requested = document.getElementById("sb_quantity_requested").value;
				var sb_other_quantity = document.getElementById("sb_other_quantity").value;
				var sb_frequency_order = document.getElementById("sb_frequency_order").value;
				var sb_how_many_order_per_yr = document.getElementById("sb_how_many_order_per_yr").value;
				var sb_what_used_for = document.getElementById("sb_what_used_for").value;
				var sb_notes = encodeURIComponent(document.getElementById("sb_notes").value);
				var quote_item = document.getElementById("quote_item").value;

				var sb_client_dash_flg;

				if ((sb_item_min_length == 0 && sb_item_max_length == 99) || (sb_item_min_width == 0 && sb_item_max_width == 99) || (sb_item_min_height == 0 && sb_item_max_height == 99)) {
					var choice = confirm('Are you sure the customer has no [dimension, either length/width/height] requirements (0-99)?');
					if (choice === false) {
						return false;
					}
				}

				if (document.getElementById("sb_client_dash_flg").checked) {
					sb_client_dash_flg = document.getElementById("sb_client_dash_flg").value;
				} else {
					sb_client_dash_flg = 0;
				}

				var high_value_target;

				if (document.getElementById("sb_high_value_target").checked) {
					high_value_target = document.getElementById("sb_high_value_target").value;
				} else {
					high_value_target = 0;
				}
				//
				var sb_wall_1, sb_wall_2, sb_no_top, sb_full_flap_top, sb_no_bottom, sb_full_flap_bottom, sb_vents_okay, sb_partial_flap_top, sb_partial_flap_bottom;
				if (document.getElementById("sb_wall_1").checked) {
					sb_wall_1 = document.getElementById("sb_wall_1").value;
				} else {
					sb_wall_1 = "";
				}
				if (document.getElementById("sb_wall_2").checked) {
					sb_wall_2 = document.getElementById("sb_wall_2").value;
				} else {
					sb_wall_2 = "";
				}
				if (document.getElementById("sb_no_top").checked) {
					sb_no_top = document.getElementById("sb_no_top").value;
				} else {
					sb_no_top = "";
				}
				if (document.getElementById("sb_full_flap_top").checked) {
					sb_full_flap_top = document.getElementById("sb_full_flap_top").value;
				} else {
					sb_full_flap_top = "";
				}
				if (document.getElementById("sb_partial_flap_top").checked) {
					sb_partial_flap_top = document.getElementById("sb_partial_flap_top").value;
				} else {
					sb_partial_flap_top = "";
				}
				if (document.getElementById("sb_no_bottom").checked) {
					sb_no_bottom = document.getElementById("sb_no_bottom").value;
				} else {
					sb_no_bottom = "";
				}
				if (document.getElementById("sb_full_flap_bottom").checked) {
					sb_full_flap_bottom = document.getElementById("sb_full_flap_bottom").value;
				} else {
					sb_full_flap_bottom = "";
				}
				if (document.getElementById("sb_partial_flap_bottom").checked) {
					sb_partial_flap_bottom = document.getElementById("sb_partial_flap_bottom").value;
				} else {
					sb_partial_flap_bottom = "";
				}

				if (document.getElementById("sb_vents_okay").checked) {
					sb_vents_okay = document.getElementById("sb_vents_okay").value;
				} else {
					sb_vents_okay = "";
				}
				var sb_need_pallets, sb_quotereq_sales_flag;
				if (document.getElementById("sb_need_pallets").checked) {
					sb_need_pallets = document.getElementById("sb_need_pallets").value;
				} else {
					sb_need_pallets = "";
				}

				/*if(document.getElementById("sb_quotereq_sales_flag").checked)
					{
						sb_quotereq_sales_flag = document.getElementById("sb_quotereq_sales_flag").value;
					}
				else{*/
				sb_quotereq_sales_flag = "";
				//}
				//
				//Validations--------------------------------------------------
				//
				var sbmin_l = parseInt(sb_item_min_length);
				var sbmax_l = parseInt(sb_item_max_length);
				if (sbmin_l >= sbmax_l) {
					alert("Please enter correct Length");
					document.getElementById('sb_item_min_length').value = "";
					return false;
				}
				var sbmin_w = parseInt(sb_item_min_width);
				var sbmax_w = parseInt(sb_item_max_width);
				if (sbmin_w >= sbmax_w) {
					alert("Please enter correct Width");
					//document.getElementById('sb_item_min_width').value="";
					//document.getElementById('sb_item_min_width').focus();
					return false;
				}
				var sbmin_h = parseInt(sb_item_min_height);
				var sbmax_h = parseInt(sb_item_max_height);
				if (sbmin_h >= sbmax_h) {
					alert("Please enter correct Height");
					//document.getElementById('sb_item_min_height').focus();
					return false;
				}
				var sbmin_cf = parseFloat(sb_cubic_footage_min);
				var sbmax_cf = parseFloat(sb_cubic_footage_max);
				if (sbmin_cf >= sbmax_cf) {
					alert("Please enter correct value of Cubic Footage");
					//document.getElementById('sb_cubic_footage_max').focus();
					return false;
				}
				//
				/*if(sb_quantity_requested=="Other"){
					if(sb_other_quantity=="" || sb_other_quantity=" ")
						{
							alert("Please enter quantity");
							document.getElementById('sb_other_quantity').focus();
						}
				}*/
				//
				if (sb_wall_1 == "" && sb_wall_2 == "") {
					alert("Please select # of Walls");
					return false;
				}
				if (sb_no_top == "" && sb_full_flap_top == "" && sb_partial_flap_top == "") {
					alert("Please select Top Config");
					return false;
				}
				if (sb_no_bottom == "" && sb_full_flap_bottom == "" && sb_partial_flap_bottom == "") {
					alert("Please select Bottom Config");
					return false;
				}

				//
				if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				} else { // code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("display_quote_request_ship").innerHTML = xmlhttp.responseText;
						alert("Record has been added successfully!!");
						$('table.table').hide();
						$('#quote_item').prop('selectedIndex', 0);
						//
						$("#show_q_div").load(location.href + " #show_q_div");
						//
						if (sb_quotereq_sales_flag == "Yes") {
							var new_quote_id = document.getElementById("sb_quote_id_n").value;
							var comp_id = document.getElementById("comp_id").value;
							//commented as new tracker is used
							//quote_request_send_email(new_quote_id,comp_id,2);
						}
						quote_req_quote_type_chg();
					}
				}

				xmlhttp.open("POST", "quote_request_save_new.php?addquotedata=1&company_id=" + b2bid + "&sb_item_length=" + sb_item_length + "&sb_item_width=" + sb_item_width + "&sb_item_height=" + sb_item_height + "&sb_item_min_length=" + sb_item_min_length + "&sb_item_max_length=" + sb_item_max_length + "&sb_item_min_width=" + sb_item_min_width + "&sb_item_max_width=" + sb_item_max_width + "&sb_item_min_height=" + sb_item_min_height + "&sb_item_max_height=" + sb_item_max_height + "&sb_cubic_footage_min=" + sb_cubic_footage_min + "&sb_cubic_footage_max=" + sb_cubic_footage_max + "&sb_wall_1=" + sb_wall_1 + "&sb_wall_2=" + sb_wall_2 + "&sb_no_top=" + sb_no_top + "&sb_full_flap_top=" + sb_full_flap_top + "&sb_no_bottom=" + sb_no_bottom + "&sb_full_flap_bottom=" + sb_full_flap_bottom + "&sb_vents_okay=" + sb_vents_okay + "&sb_quantity_requested=" + sb_quantity_requested + "&sb_other_quantity=" + sb_other_quantity + "&sb_frequency_order=" + sb_frequency_order + "&sb_how_many_order_per_yr=" + sb_how_many_order_per_yr + "&sb_what_used_for=" + sb_what_used_for + "&sb_date_needed_by=" + sb_date_needed_by + "&sb_need_pallets=" + sb_need_pallets + "&sb_quotereq_sales_flag=" + sb_quotereq_sales_flag + "&sb_notes=" + sb_notes + "&quote_item=" + quote_item + "&client_dash_flg=" + sb_client_dash_flg + "&high_value_target=" + high_value_target + "&sb_sales_desired_price=" + sb_sales_desired_price + "&sb_partial_flap_top=" + sb_partial_flap_top + "&sb_partial_flap_bottom=" + sb_partial_flap_bottom, true);
				xmlhttp.send();
			}
			//
			function sup_quote_save(b2bid) {
				var sup_item_length = document.getElementById("sup_item_length").value;
				var sup_item_width = document.getElementById("sup_item_width").value;
				var sup_item_height = document.getElementById("sup_item_height").value;

				var sup_quantity_requested = document.getElementById("sup_quantity_requested").value;
				var sup_other_quantity = document.getElementById("sup_other_quantity").value;
				var sup_frequency_order = document.getElementById("sup_frequency_order").value;
				var sup_what_used_for = document.getElementById("sup_what_used_for").value;

				var sup_sales_desired_price = document.getElementById("sup_sales_desired_price").value;

				var sup_date_needed_by = ""; //document.getElementById("sup_date_needed_by").value;
				var sup_need_pallets;
				var sup_notes = encodeURIComponent(document.getElementById("sup_notes").value);
				var sup_quotereq_sales_flag = ""; //document.getElementById("sup_quotereq_sales_flag").value;
				var quote_item = document.getElementById("quote_item").value;

				var client_dash_flg;

				if (document.getElementById("sup_client_dash_flg").checked) {
					client_dash_flg = document.getElementById("sup_client_dash_flg").value;
				} else {
					client_dash_flg = 0;
				}

				var high_value_target;

				if (document.getElementById("sup_high_value_target").checked) {
					high_value_target = document.getElementById("sup_high_value_target").value;
				} else {
					high_value_target = 0;
				}

				//
				if (document.getElementById("sup_need_pallets").checked) {
					sup_need_pallets = document.getElementById("sup_need_pallets").value;
				} else {
					sup_need_pallets = "";
				}
				//

				/*if(document.getElementById("sup_quotereq_sales_flag").checked)
					{
						sup_quotereq_sales_flag = document.getElementById("sup_quotereq_sales_flag").value;
					}
				else{*/
				sup_quotereq_sales_flag = "";
				//}
				//
				if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				} else { // code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("display_quote_request_super").innerHTML = xmlhttp.responseText;
						alert("Record has been added successfully!!");
						$('table.table').hide();
						$('#quote_item').prop('selectedIndex', 0);
						//
						$("#show_q_div").load(location.href + " #show_q_div");
						//
						//if(quoterequest_saleslead_flag=="Yes")
						//{
						var new_quote_id = document.getElementById("sup_quote_id_n").value;
						var comp_id = document.getElementById("comp_id").value;
						if (sup_quotereq_sales_flag == "Yes") {
							//commented as new tracker is used
							//quote_request_send_email(new_quote_id,comp_id,3);
						}
						quote_req_quote_type_chg();
					}
				}
				xmlhttp.open("GET", openurl + "quote_request_save_new.php?addquotedata=1&company_id=" + b2bid + "&sup_item_length=" + sup_item_length + "&sup_item_width=" + sup_item_width + "&sup_item_height=" + sup_item_height + "&sup_quantity_requested=" + sup_quantity_requested + "&sup_frequency_order=" + sup_frequency_order + "&sup_other_quantity=" + sup_other_quantity + "&sup_date_needed_by=" + sup_date_needed_by + "&sup_need_pallets=" + sup_need_pallets + "&sup_what_used_for=" + sup_what_used_for + "&sup_quotereq_sales_flag=" + sup_quotereq_sales_flag + "&sup_notes=" + sup_notes + "&quote_item=" + quote_item + "&client_dash_flg=" + client_dash_flg + "&high_value_target=" + high_value_target + "&sup_sales_desired_price=" + sup_sales_desired_price, true);
				xmlhttp.send();
			}
			//pallets_quote_save
			//
			function pallets_quote_save(b2bid) {
				var pal_item_length = document.getElementById("pal_item_length").value;
				var pal_item_width = document.getElementById("pal_item_width").value;
				var pal_quantity_requested = document.getElementById("pal_quantity_requested").value;
				var pal_other_quantity = document.getElementById("pal_other_quantity").value;
				var pal_frequency_order = document.getElementById("pal_frequency_order").value;
				var pal_how_many_order_per_yr = document.getElementById("pal_how_many_order_per_yr").value;
				var pal_what_used_for = document.getElementById("pal_what_used_for").value;
				var pal_date_needed_by = ""; //document.getElementById("pal_date_needed_by").value;
				var pal_note = encodeURIComponent(document.getElementById("pal_note").value);
				var pal_quotereq_sales_flag = ""; //document.getElementById("pal_quotereq_sales_flag").value;
				var quote_item = document.getElementById("quote_item").value;

				var client_dash_flg;

				if (document.getElementById("pal_client_dash_flg").checked) {
					client_dash_flg = document.getElementById("pal_client_dash_flg").value;
				} else {
					client_dash_flg = 0;
				}

				var high_value_target;
				if (document.getElementById("pal_high_value_target").checked) {
					high_value_target = document.getElementById("pal_high_value_target").value;
				} else {
					high_value_target = 0;
				}

				var pal_grade_a, pal_grade_b, pal_grade_c, pal_material_wooden, pal_material_plastic, pal_material_corrugate, pal_entry_2way, pal_entry_4way, pal_structure_stringer, pal_structure_block;

				if (document.getElementById("pal_grade_a").checked) {
					pal_grade_a = document.getElementById("pal_grade_a").value;
				} else {
					pal_grade_a = "";
				}

				if (document.getElementById("pal_grade_b").checked) {
					pal_grade_b = document.getElementById("pal_grade_b").value;
				} else {
					pal_grade_b = "";
				}

				if (document.getElementById("pal_grade_c").checked) {
					pal_grade_c = document.getElementById("pal_grade_c").value;
				} else {
					pal_grade_c = "";
				}

				if (document.getElementById("pal_material_wooden").checked) {
					pal_material_wooden = document.getElementById("pal_material_wooden").value;
				} else {
					pal_material_wooden = "";
				}

				if (document.getElementById("pal_material_plastic").checked) {
					pal_material_plastic = document.getElementById("pal_material_plastic").value;
				} else {
					pal_material_plastic = "";
				}

				if (document.getElementById("pal_material_corrugate").checked) {
					pal_material_corrugate = document.getElementById("pal_material_corrugate").value;
				} else {
					pal_material_corrugate = "";
				}

				if (document.getElementById("pal_entry_2way").checked) {
					pal_entry_2way = document.getElementById("pal_entry_2way").value;
				} else {
					pal_entry_2way = "";
				}

				if (document.getElementById("pal_entry_4way").checked) {
					pal_entry_4way = document.getElementById("pal_entry_4way").value;
				} else {
					pal_entry_4way = "";
				}

				if (document.getElementById("pal_structure_stringer").checked) {
					pal_structure_stringer = document.getElementById("pal_structure_stringer").value;
				} else {
					pal_structure_stringer = "";
				}

				if (document.getElementById("pal_structure_block").checked) {
					pal_structure_block = document.getElementById("pal_structure_block").value;
				} else {
					pal_structure_block = "";
				}

				var pal_heat_treated = document.getElementById("pal_heat_treated").value;
				var pal_sales_desired_price = document.getElementById("pal_sales_desired_price").value;

				//

				/*if(document.getElementById("pal_quotereq_sales_flag").checked)
					{
						pal_quotereq_sales_flag = document.getElementById("pal_quotereq_sales_flag").value;
					}
				else{*/
				pal_quotereq_sales_flag = "";
				//}
				var quote_item = document.getElementById("quote_item").value;
				var box_pallet_sub_type = document.getElementById("box_pallet_sub_type").value;

				//
				if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				} else { // code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("display_quote_request_pallets").innerHTML = xmlhttp.responseText;
						alert("Record has been added successfully!!");
						$('table.table').hide();
						$('#quote_item').prop('selectedIndex', 0);
						//
						$("#show_q_div").load(location.href + " #show_q_div");
						//
						if (pal_quotereq_sales_flag == "Yes") {
							var new_quote_id = document.getElementById("pal_quote_id_n").value;
							var comp_id = document.getElementById("comp_id").value;
							//commented as new tracker is used
							//quote_request_send_email(new_quote_id,comp_id,4);
						}
						quote_req_quote_type_chg();

					}
				}

				xmlhttp.open("GET", openurl + "quote_request_save_new.php?addquotedata=1&company_id=" + b2bid + "&pal_item_length=" + pal_item_length + "&pal_item_width=" + pal_item_width + "&pal_quantity_requested=" + pal_quantity_requested + "&pal_frequency_order=" + pal_frequency_order + "&pal_how_many_order_per_yr=" + pal_how_many_order_per_yr + "&pal_other_quantity=" + pal_other_quantity + "&pal_date_needed_by=" + pal_date_needed_by + "&pal_what_used_for=" + pal_what_used_for + "&pal_quotereq_sales_flag=" + pal_quotereq_sales_flag + "&pal_note=" + pal_note + "&quote_item=" + quote_item + "&client_dash_flg=" + client_dash_flg + "&high_value_target=" + high_value_target + "&pal_sales_desired_price=" + pal_sales_desired_price + "&pal_grade_a=" + pal_grade_a + "&pal_grade_b=" + pal_grade_b + "&pal_grade_c=" + pal_grade_c + "&pal_material_wooden=" + pal_material_wooden + "&pal_material_plastic=" + pal_material_plastic + "&pal_material_corrugate=" + pal_material_corrugate + "&pal_entry_2way=" + pal_entry_2way + "&pal_entry_4way=" + pal_entry_4way + "&pal_structure_stringer=" + pal_structure_stringer + "&pal_structure_block=" + pal_structure_block + "&pal_heat_treated=" + pal_heat_treated + "&box_pallet_sub_type=" + box_pallet_sub_type, true);
				xmlhttp.send();
			}
			//
			//Other_quote_save
			function other_quote_save(b2bid) {
				var other_quantity_requested = document.getElementById("other_quantity_requested").value;
				var other_other_quantity = document.getElementById("other_other_quantity").value;
				var other_frequency_order = document.getElementById("other_frequency_order").value;
				var other_what_used_for = document.getElementById("other_what_used_for").value;
				var other_date_needed_by = ""; //document.getElementById("other_date_needed_by").value;
				var other_need_pallets;
				var other_note = encodeURIComponent(document.getElementById("other_note").value);

				var other_quotereq_sales_flag = ""; //document.getElementById("other_quotereq_sales_flag").value;
				var quote_item = document.getElementById("quote_item").value;
				//
				if (document.getElementById("other_need_pallets").checked) {
					other_need_pallets = document.getElementById("other_need_pallets").value;
				} else {
					other_need_pallets = "";
				}

				var high_value_target;
				if (document.getElementById("other_high_value_target").checked) {
					high_value_target = document.getElementById("other_high_value_target").value;
				} else {
					high_value_target = 0;
				}

				/*if(document.getElementById("other_quotereq_sales_flag").checked)
					{
						other_quotereq_sales_flag = document.getElementById("other_quotereq_sales_flag").value;
					}
				else{*/
				other_quotereq_sales_flag = "";
				//}
				var quote_item = document.getElementById("quote_item").value;

				//
				if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				} else { // code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("display_quote_request_other").innerHTML = xmlhttp.responseText;
						alert("Record has been added successfully!!");
						$('table.table').hide();
						$('#quote_item').prop('selectedIndex', 0);
						//
						$("#show_q_div").load(location.href + " #show_q_div");
						//
						var new_quote_id = document.getElementById("other_quote_id_n").value;
						var comp_id = document.getElementById("comp_id").value;
						if (other_quotereq_sales_flag == "Yes") {
							//commented as new tracker is used
							//quote_request_send_email(new_quote_id,comp_id,7);
						}
						//display table details

						//
						quote_req_quote_type_chg();
					}
				}

				xmlhttp.open("GET", openurl + "quote_request_save_new.php?addquotedata=1&company_id=" + b2bid + "&other_quantity_requested=" + other_quantity_requested + "&other_frequency_order=" + other_frequency_order + "&other_other_quantity=" + other_other_quantity + "&other_date_needed_by=" + other_date_needed_by + "&other_need_pallets=" + other_need_pallets + "&other_what_used_for=" + other_what_used_for + "&other_quotereq_sales_flag=" + other_quotereq_sales_flag + "&other_note=" + other_note + "&quote_item=" + quote_item + "&high_value_target=" + high_value_target, true);
				xmlhttp.send();
			}

			function g_quote_edit(b2bid, tableid, quote_item, client_dash_flg) {
				//
				var p = "g";

				//
				if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				} else { // code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("g" + tableid).innerHTML = xmlhttp.responseText;
						box_sub_type_load_ctrl_sub(tableid);
					}
				}

				xmlhttp.open("POST", openurl + "quote_request_edit_new.php?editquotedata=1&company_id=" + b2bid + "&p=" + p + "&tableid=" + tableid + "&quote_item=" + quote_item + "&client_dash_flg=" + client_dash_flg, true);
				xmlhttp.send();
			}

			//edit code 2
			function sb_quote_edit(b2bid, stableid, quote_item, client_dash_flg) {
				var p = "sb";
				if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				} else { // code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("sb" + stableid).innerHTML = xmlhttp.responseText;
					}
				}
				xmlhttp.open("GET", "quote_request_edit_new.php?editquotedata=1&company_id=" + b2bid + "&p=" + p + "&stableid=" + stableid + "&quote_item=" + quote_item + "&client_dash_flg=" + client_dash_flg, true);
				xmlhttp.send();
			}

			function sb_quote_delete(stableid, quote_item, companyid) {
				var choice = confirm('Do you really want to delete this record?');
				if (choice === true) {
					var p = "sb";
					if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
						xmlhttp = new XMLHttpRequest();
					} else { // code for IE6, IE5
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					}
					xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
							document.getElementById("sb" + stableid).innerHTML = xmlhttp.responseText;
							$("#show_q_div").load(location.href + " #show_q_div");
						}
					}
					xmlhttp.open("GET", "delete_quote_request.php?editquotedata=1&p=" + p + "&stableid=" + stableid + "&quote_item=" + quote_item + "&companyid=" + companyid, true);
					xmlhttp.send();
				} else {

				}
			}

			function sb_quote_move(stableid, quote_item, companyid, clientdash_flg) {
				if (clientdash_flg == 1) {
					var choice = confirm('Do you really want to move this record from Customer Entered to UCB entered?');
				} else {
					var choice = confirm('Do you really want to move this record from UCB entered to Customer Entered?');
				}
				if (choice === true) {
					var p = "sb";
					if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
						xmlhttp = new XMLHttpRequest();
					} else { // code for IE6, IE5
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					}
					xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
							document.getElementById("sb" + stableid).innerHTML = xmlhttp.responseText;
							$("#show_q_div").load(location.href + " #show_q_div");
							location.reload();
						}
					}
					xmlhttp.open("GET", "move_quote_request.php?editquotedata=1&p=" + p + "&tableid=" + stableid + "&quote_item=" + quote_item + "&companyid=" + companyid + "&clientdash_flg=" + clientdash_flg, true);
					xmlhttp.send();
				}
			}

			//super edit
			function sup_quote_edit(b2bid, suptableid, quote_item, client_dash_flg) {
				//
				var p = "sup";

				//
				if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				} else { // code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("sup" + suptableid).innerHTML = xmlhttp.responseText;
					}
				}
				xmlhttp.open("GET", openurl + "quote_request_edit_new.php?editquotedata=1&company_id=" + b2bid + "&p=" + p + "&suptableid=" + suptableid + "&quote_item=" + quote_item + "&client_dash_flg=" + client_dash_flg, true);
				xmlhttp.send();
			}

			function sup_quote_delete(suptableid, quote_item, companyid) {
				var choice = confirm('Do you really want to delete this record?');
				if (choice === true) {
					//
					var p = "sup";
					//
					if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
						xmlhttp = new XMLHttpRequest();
					} else { // code for IE6, IE5
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					}
					xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
							document.getElementById("sup" + suptableid).innerHTML = xmlhttp.responseText;
							$("#show_q_div").load(location.href + " #show_q_div");
						}
					}
					xmlhttp.open("GET", "delete_quote_request.php?editquotedata=1&p=" + p + "&suptableid=" + suptableid + "&quote_item=" + quote_item + "&companyid=" + companyid, true);
					xmlhttp.send();
				} else {

				}
			}

			function sup_quote_move(suptableid, quote_item, companyid, clientdash_flg) {
				if (clientdash_flg == 1) {
					var choice = confirm('Do you really want to move this record from Customer Entered to UCB entered?');
				} else {
					var choice = confirm('Do you really want to move this record from UCB entered to Customer Entered?');
				}
				if (choice === true) {
					//
					var p = "sup";
					//
					if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
						xmlhttp = new XMLHttpRequest();
					} else { // code for IE6, IE5
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					}
					xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
							document.getElementById("sup" + suptableid).innerHTML = xmlhttp.responseText;
							$("#show_q_div").load(location.href + " #show_q_div");
							location.reload();
						}
					}

					xmlhttp.open("GET", "move_quote_request.php?editquotedata=1&p=" + p + "&tableid=" + suptableid + "&quote_item=" + quote_item + "&companyid=" + companyid + "&clientdash_flg=" + clientdash_flg, true);
					xmlhttp.send();
				}
			}

			//
			function pal_quote_edit(b2bid, paltableid, quote_item, client_dash_flg) {
				//
				var p = "pal";
				//
				if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				} else { // code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("pal" + paltableid).innerHTML = xmlhttp.responseText;
						box_pallet_sub_type_load_ctrl_sub(paltableid);
					}
				}
				xmlhttp.open("GET", openurl + "quote_request_edit_new.php?editquotedata=1&company_id=" + b2bid + "&p=" + p + "&paltableid=" + paltableid + "&quote_item=" + quote_item + "&client_dash_flg=" + client_dash_flg, true);
				xmlhttp.send();
			}
			//
			function other_quote_edit(b2bid, othertableid, quote_item, client_dash_flg) {
				//
				var p = "other";
				//
				if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				} else { // code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("other" + othertableid).innerHTML = xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", openurl + "quote_request_edit_new.php?editquotedata=1&company_id=" + b2bid + "&p=" + p + "&othertableid=" + othertableid + "&quote_item=" + quote_item + "&client_dash_flg=" + client_dash_flg, true);
				xmlhttp.send();
			}
			//

			function quote_update(tableid) {
				var company_id = document.getElementById("company_id" + tableid).value;

				if (document.getElementById("client_dash_flg" + tableid).checked) {
					var client_dash_flg = document.getElementById("client_dash_flg" + tableid).value;
				} else {
					var client_dash_flg = "";
				}

				var high_value_target;
				if (document.getElementById("high_value_target" + tableid).checked) {
					high_value_target = document.getElementById("high_value_target" + tableid).value;
				} else {
					high_value_target = 0;
				}


				var g_item_length = document.getElementById("g_item_length" + tableid).value;
				var g_item_width = document.getElementById("g_item_width" + tableid).value;
				var g_item_height = document.getElementById("g_item_height" + tableid).value;
				var g_item_min_height = document.getElementById("g_item_min_height" + tableid).value;
				var g_item_max_height = document.getElementById("g_item_max_height" + tableid).value;

				var sales_desired_price_g = document.getElementById("sales_desired_price_g" + tableid).value;

				//
				var g_shape_rectangular, g_shape_octagonal, g_wall_1, g_wall_2, g_wall_3, g_wall_4, g_wall_5, g_wall_6, g_wall_7, g_wall_8, g_wall_9, g_wall_10, g_no_top, g_lid_top, g_partial_flap_top, g_full_flap_top, g_no_bottom_config, g_partial_flap_w, g_full_flap_bottom, g_tray_bottom, g_partial_flap_wo, g_vents_okay;
				if (document.getElementById("g_shape_rectangular" + tableid).checked) {
					g_shape_rectangular = document.getElementById("g_shape_rectangular").value;
				} else {
					g_shape_rectangular = "";
				}
				if (document.getElementById("g_shape_octagonal" + tableid).checked) {
					g_shape_octagonal = document.getElementById("g_shape_octagonal" + tableid).value;
				} else {
					g_shape_octagonal = "";
				}
				if (document.getElementById("g_wall_1" + tableid).checked) {
					g_wall_1 = document.getElementById("g_wall_1" + tableid).value;
				} else {
					g_wall_1 = "";
				}
				if (document.getElementById("g_wall_2" + tableid).checked) {
					g_wall_2 = document.getElementById("g_wall_2" + tableid).value;
				} else {
					g_wall_2 = "";
				}
				if (document.getElementById("g_wall_3" + tableid).checked) {
					g_wall_3 = document.getElementById("g_wall_3" + tableid).value;
				} else {
					g_wall_3 = "";
				}
				if (document.getElementById("g_wall_4" + tableid).checked) {
					g_wall_4 = document.getElementById("g_wall_4" + tableid).value;
				} else {
					g_wall_4 = "";
				}
				if (document.getElementById("g_wall_5" + tableid).checked) {
					g_wall_5 = document.getElementById("g_wall_5" + tableid).value;
				} else {
					g_wall_5 = "";
				}
				if (document.getElementById("g_wall_6" + tableid).checked) {
					g_wall_6 = document.getElementById("g_wall_6" + tableid).value;
				} else {
					g_wall_6 = "";
				}
				if (document.getElementById("g_wall_7" + tableid).checked) {
					g_wall_7 = document.getElementById("g_wall_7" + tableid).value;
				} else {
					g_wall_7 = "";
				}

				if (document.getElementById("g_wall_8" + tableid).checked) {
					g_wall_8 = document.getElementById("g_wall_8" + tableid).value;
				} else {
					g_wall_8 = "";
				}
				if (document.getElementById("g_wall_9" + tableid).checked) {
					g_wall_9 = document.getElementById("g_wall_9" + tableid).value;
				} else {
					g_wall_9 = "";
				}
				if (document.getElementById("g_wall_10" + tableid).checked) {
					g_wall_10 = document.getElementById("g_wall_10" + tableid).value;
				} else {
					g_wall_10 = "";
				}
				if (document.getElementById("g_no_top" + tableid).checked) {
					g_no_top = document.getElementById("g_no_top" + tableid).value;
				} else {
					g_no_top = "";
				}
				if (document.getElementById("g_lid_top" + tableid).checked) {
					g_lid_top = document.getElementById("g_lid_top" + tableid).value;
				} else {
					g_lid_top = "";
				}
				if (document.getElementById("g_partial_flap_top" + tableid).checked) {
					g_partial_flap_top = document.getElementById("g_partial_flap_top" + tableid).value;
				} else {
					g_partial_flap_top = "";
				}
				if (document.getElementById("g_full_flap_top" + tableid).checked) {
					g_full_flap_top = document.getElementById("g_full_flap_top" + tableid).value;
				} else {
					g_full_flap_top = "";
				}
				if (document.getElementById("g_no_bottom_config" + tableid).checked) {
					g_no_bottom_config = document.getElementById("g_no_bottom_config" + tableid).value;
				} else {
					g_no_bottom_config = "";
				}
				if (document.getElementById("g_partial_flap_w" + tableid).checked) {
					g_partial_flap_w = document.getElementById("g_partial_flap_w" + tableid).value;
				} else {
					g_partial_flap_w = "";
				}
				if (document.getElementById("g_tray_bottom" + tableid).checked) {
					g_tray_bottom = document.getElementById("g_tray_bottom" + tableid).value;
				} else {
					g_tray_bottom = "";
				}
				if (document.getElementById("g_full_flap_bottom" + tableid).checked) {
					g_full_flap_bottom = document.getElementById("g_full_flap_bottom" + tableid).value;
				} else {
					g_full_flap_bottom = "";
				}
				if (document.getElementById("g_partial_flap_wo" + tableid).checked) {
					g_partial_flap_wo = document.getElementById("g_partial_flap_wo" + tableid).value;
				} else {
					g_partial_flap_wo = "";
				}
				if (document.getElementById("g_vents_okay" + tableid).checked) {
					g_vents_okay = document.getElementById("g_vents_okay" + tableid).value;
				} else {
					g_vents_okay = "";
				}
				var need_pallets, quoterequest_saleslead_flag;
				if (document.getElementById("need_pallets" + tableid).checked) {
					need_pallets = document.getElementById("need_pallets" + tableid).value;
				} else {
					need_pallets = "";
				}
				//if(document.getElementById("quoterequest_saleslead_flag"+tableid).checked)
				//	{
				//		quoterequest_saleslead_flag = document.getElementById("quoterequest_saleslead_flag"+tableid).value;
				//	}
				//else{
				quoterequest_saleslead_flag = "";
				//}
				//
				var box_sub_type = document.getElementById("box_sub_type" + tableid).value;

				var g_quantity_request = document.getElementById("g_quantity_request" + tableid).value;
				var g_other_quantity = document.getElementById("g_other_quantity" + tableid).value;
				//
				var g_frequency_order = document.getElementById("g_frequency_order" + tableid).value;
				var g_how_many_order_per_yr = document.getElementById("g_how_many_order_per_yr" + tableid).value;

				var g_what_used_for = document.getElementById("g_what_used_for" + tableid).value;
				var date_needed_by = ""; //document.getElementById("date_needed_by"+tableid).value;
				var g_item_note = encodeURIComponent(document.getElementById("g_item_note" + tableid).value);
				var quote_item = document.getElementById("quote_item" + tableid).value;
				//Validations--------------------------------------------------
				//
				var gmin = parseInt(g_item_min_height);
				var gmax = parseInt(g_item_max_height);
				if (gmin >= gmax) {
					alert("Please enter correct height");
					//document.getElementById('g_item_max_height').focus();
					return false;
				}
				//

				var box_criteria_flg = 0;
				if (box_sub_type == "") {
					if (g_shape_rectangular == "" && g_shape_octagonal == "") {
						box_criteria_flg = 1;
						alert("Please select shape");
						return false;
					}
					if (g_wall_1 == "" && g_wall_2 == "" && g_wall_3 == "" && g_wall_4 == "" && g_wall_5 == "" && g_wall_6 == "" && g_wall_7 == "" && g_wall_8 == "" && g_wall_9 == "" && g_wall_10 == "") {
						box_criteria_flg = 1;
						alert("Please select atleast one # of Walls");
						return false;
					}
					//
					if (g_no_top == "" && g_lid_top == "" && g_partial_flap_top == "" && g_full_flap_top == "") {
						box_criteria_flg = 1;
						alert("Please select Top Config");
						return false;
					}
					//
					if (g_no_bottom_config == "" && g_partial_flap_w == "" && g_tray_bottom == "" && g_full_flap_bottom == "" && g_partial_flap_wo == "") {
						box_criteria_flg = 1;
						alert("Please select Bottom Config");
						return false;
					}
				}

				if (box_sub_type == "" && box_criteria_flg == 1) {
					alert("Please select Sub type.");
					return false;
				}
				//
				if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				} else { // code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("g" + tableid).innerHTML = xmlhttp.responseText;
						//display table details
						var g = document.getElementById("g_sub_table" + tableid);
						if (g.style.display === "none") {
							g.style.display = "block";
							document.getElementById("g_btn" + tableid).innerHTML = "Collapse Details";
						}
						var new_quote_id = document.getElementById("quote_id_n").value;
						var comp_id = document.getElementById("comp_id").value;
					}
				}

				xmlhttp.open("POST", openurl + "quote_request_save_new.php?updatequotedata=1&tableid=" + tableid + "&box_sub_type=" + box_sub_type + "&company_id=" + company_id + "&g_item_length=" + g_item_length + "&g_item_width=" + g_item_width + "&g_item_height=" + g_item_height + "&g_item_min_height=" + g_item_min_height + "&g_item_max_height=" + g_item_max_height + "&g_shape_rectangular=" + g_shape_rectangular + "&g_shape_octagonal=" + g_shape_octagonal + "&g_wall_1=" + g_wall_1 + "&g_wall_2=" + g_wall_2 + "&g_wall_3=" + g_wall_3 + "&g_wall_4=" + g_wall_4 + "&g_wall_5=" + g_wall_5 + "&g_wall_6=" + g_wall_6 + "&g_wall_7=" + g_wall_7 + "&g_wall_8=" + g_wall_8 + "&g_wall_9=" + g_wall_9 + "&g_wall_10=" + g_wall_10 + "&g_no_top=" + g_no_top + "&g_lid_top=" + g_lid_top + "&g_partial_flap_top=" + g_partial_flap_top + "&g_full_flap_top=" + g_full_flap_top + "&g_no_bottom_config=" + g_no_bottom_config + "&g_partial_flap_w=" + g_partial_flap_w + "&g_tray_bottom=" + g_tray_bottom + "&g_full_flap_bottom=" + g_full_flap_bottom + "&g_partial_flap_wo=" + g_partial_flap_wo + "&g_vents_okay=" + g_vents_okay + "&g_quantity_request=" + g_quantity_request + "&g_other_quantity=" + g_other_quantity + "&g_frequency_order=" + g_frequency_order + "&g_how_many_order_per_yr=" + g_how_many_order_per_yr + "&g_what_used_for=" + g_what_used_for + "&date_needed_by=" + date_needed_by + "&need_pallets=" + need_pallets + "&g_item_note=" + g_item_note + "&quoterequest_saleslead_flag=" + quoterequest_saleslead_flag + "&quote_item=" + quote_item + "&client_dash_flg=" + client_dash_flg + "&high_value_target=" + high_value_target + "&sales_desired_price_g=" + sales_desired_price_g, true);
				xmlhttp.send();
			}

			function quote_cancel(tableid) {

				var company_id = document.getElementById("company_id" + tableid).value;
				var quote_item = document.getElementById("quote_item" + tableid).value;
				var client_dash_flg = document.getElementById("client_dash_flg" + tableid).value;

				//
				if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				} else { // code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

						document.getElementById("g" + tableid).innerHTML = xmlhttp.responseText;
						//display table details
						var g = document.getElementById("g_sub_table" + tableid);
						if (g.style.display === "none") {
							g.style.display = "block";
							document.getElementById("g_btn" + tableid).innerHTML = "Collapse Details";
						}
						//
					}
				}
				xmlhttp.open("GET", openurl + "quote_request_save_new.php?updatequotedata=2&tableid=" + tableid + "&company_id=" + company_id + "&client_dash_flg=" + client_dash_flg, true);
				xmlhttp.send();
			}

			// update1
			function quote_updates(stableid) {
				var company_id = document.getElementById("company_id" + stableid).value;

				if (document.getElementById("client_dash_flg" + stableid).checked) {
					var client_dash_flg = document.getElementById("client_dash_flg" + stableid).value;
				} else {
					var client_dash_flg = "";
				}

				var high_value_target;

				if (document.getElementById("high_value_target" + stableid).checked) {
					high_value_target = document.getElementById("high_value_target" + stableid).value;
				} else {
					high_value_target = 0;
				}

				var sb_item_length = document.getElementById("sb_item_length" + stableid).value;
				var sb_item_width = document.getElementById("sb_item_width" + stableid).value;
				var sb_item_height = document.getElementById("sb_item_height" + stableid).value;
				var sb_item_min_length = document.getElementById("sb_item_min_length" + stableid).value;
				var sb_item_max_length = document.getElementById("sb_item_max_length" + stableid).value;
				var sb_item_min_width = document.getElementById("sb_item_min_width" + stableid).value;
				var sb_item_max_width = document.getElementById("sb_item_max_width" + stableid).value;
				var sb_item_min_height = document.getElementById("sb_item_min_height" + stableid).value;
				var sb_item_max_height = document.getElementById("sb_item_max_height" + stableid).value;
				var sb_cubic_footage_min = document.getElementById("sb_cubic_footage_min" + stableid).value;
				var sb_cubic_footage_max = document.getElementById("sb_cubic_footage_max" + stableid).value;
				var sb_date_needed_by = "";

				var sb_quantity_requested = document.getElementById("sb_quantity_requested" + stableid).value;
				var sb_other_quantity = document.getElementById("sb_other_quantity" + stableid).value;
				var sb_frequency_order = document.getElementById("sb_frequency_order" + stableid).value;
				var sb_how_many_order_per_yr = document.getElementById("sb_how_many_order_per_yr" + stableid).value;
				var sb_what_used_for = document.getElementById("sb_what_used_for" + stableid).value;
				var sb_notes = encodeURIComponent(document.getElementById("sb_notes" + stableid).value);

				var sb_sales_desired_price = document.getElementById("sb_sales_desired_price" + stableid).value;

				var quote_item = document.getElementById("quote_item" + stableid).value;
				//
				var sb_wall_1, sb_wall_2, sb_no_top, sb_full_flap_top, sb_no_bottom, sb_full_flap_bottom, sb_vents_okay, sb_partial_flap_top, sb_partial_flap_bottom;
				if (document.getElementById("sb_wall_1" + stableid).checked) {
					sb_wall_1 = document.getElementById("sb_wall_1" + stableid).value;
				} else {
					sb_wall_1 = "";
				}
				if (document.getElementById("sb_wall_2" + stableid).checked) {
					sb_wall_2 = document.getElementById("sb_wall_2" + stableid).value;
				} else {
					sb_wall_2 = "";
				}
				if (document.getElementById("sb_no_top" + stableid).checked) {
					sb_no_top = document.getElementById("sb_no_top" + stableid).value;
				} else {
					sb_no_top = "";
				}
				if (document.getElementById("sb_full_flap_top" + stableid).checked) {
					sb_full_flap_top = document.getElementById("sb_full_flap_top" + stableid).value;
				} else {
					sb_full_flap_top = "";
				}
				if (document.getElementById("sb_partial_flap_top" + stableid).checked) {
					sb_partial_flap_top = document.getElementById("sb_partial_flap_top" + stableid).value;
				} else {
					sb_partial_flap_top = "";
				}
				if (document.getElementById("sb_no_bottom" + stableid).checked) {
					sb_no_bottom = document.getElementById("sb_no_bottom" + stableid).value;
				} else {
					sb_no_bottom = "";
				}
				if (document.getElementById("sb_full_flap_bottom" + stableid).checked) {
					sb_full_flap_bottom = document.getElementById("sb_full_flap_bottom" + stableid).value;
				} else {
					sb_full_flap_bottom = "";
				}
				if (document.getElementById("sb_partial_flap_bottom" + stableid).checked) {
					sb_partial_flap_bottom = document.getElementById("sb_partial_flap_bottom" + stableid).value;
				} else {
					sb_partial_flap_bottom = "";
				}

				if (document.getElementById("sb_vents_okay" + stableid).checked) {
					sb_vents_okay = document.getElementById("sb_vents_okay" + stableid).value;
				} else {
					sb_vents_okay = "";
				}
				var sb_need_pallets, sb_quotereq_sales_flag;
				if (document.getElementById("sb_need_pallets" + stableid).checked) {
					sb_need_pallets = document.getElementById("sb_need_pallets" + stableid).value;
				} else {
					sb_need_pallets = "";
				}
				/*if(document.getElementById("sb_quotereq_sales_flag"+stableid).checked)
					{
						sb_quotereq_sales_flag = document.getElementById("sb_quotereq_sales_flag"+stableid).value;
					}
				else{*/
				sb_quotereq_sales_flag = "";
				//}
				//
				//Validations--------------------------------------------------
				//
				var sbmin_l = parseInt(sb_item_min_length);
				var sbmax_l = parseInt(sb_item_max_length);
				if (sbmin_l >= sbmax_l) {
					alert("Please enter correct Length");
					document.getElementById('sb_item_min_length').value = "";
					return false;
				}
				var sbmin_w = parseInt(sb_item_min_width);
				var sbmax_w = parseInt(sb_item_max_width);
				if (sbmin_w >= sbmax_w) {
					alert("Please enter correct Width");
					//document.getElementById('sb_item_min_width').value="";
					//document.getElementById('sb_item_min_width').focus();
					return false;
				}
				var sbmin_h = parseInt(sb_item_min_height);
				var sbmax_h = parseInt(sb_item_max_height);
				if (sbmin_h >= sbmax_h) {
					alert("Please enter correct Height");
					//document.getElementById('sb_item_min_height').focus();
					return false;
				}
				var sbmin_cf = parseFloat(sb_cubic_footage_min);
				var sbmax_cf = parseFloat(sb_cubic_footage_max);
				if (sbmin_cf >= sbmax_cf) {
					alert("Please enter correct value of Cubic Footage");
					//document.getElementById('sb_cubic_footage_max').focus();
					return false;
				}
				//
				/*if(sb_quantity_requested=="Other"){
					if(sb_other_quantity=="" || sb_other_quantity=" ")
						{
							alert("Please enter quantity");
							document.getElementById('sb_other_quantity').focus();
						}
				}*/
				//
				if (sb_wall_1 == "" && sb_wall_2 == "") {
					alert("Please select # of Walls");
					return false;
				}
				if (sb_no_top == "" && sb_full_flap_top == "" && sb_partial_flap_top == "") {
					alert("Please select Top Config");
					return false;
				}
				if (sb_no_bottom == "" && sb_full_flap_bottom == "" && sb_partial_flap_bottom == "") {
					alert("Please select Bottom Config");
					return false;
				}
				//
				//
				//
				if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				} else { // code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("sb" + stableid).innerHTML = xmlhttp.responseText;
						//display table details
						var sb = document.getElementById("sb_sub_table" + stableid);
						if (sb.style.display === "none") {
							sb.style.display = "block";
							document.getElementById("sb_btn" + stableid).innerHTML = "Collapse Details";
						}
						var new_quote_id = document.getElementById("sb_quote_id_n").value;
						var comp_id = document.getElementById("comp_id").value;

					}
				}
				xmlhttp.open("POST", "quote_request_save_new.php?sbupdatequotedata=1&stableid=" + stableid + "&company_id=" + company_id + "&sb_item_length=" + sb_item_length + "&sb_item_width=" + sb_item_width + "&sb_item_height=" + sb_item_height + "&sb_item_min_length=" + sb_item_min_length + "&sb_item_max_length=" + sb_item_max_length + "&sb_item_min_width=" + sb_item_min_width + "&sb_item_max_width=" + sb_item_max_width + "&sb_item_min_height=" + sb_item_min_height + "&sb_item_max_height=" + sb_item_max_height + "&sb_cubic_footage_min=" + sb_cubic_footage_min + "&sb_cubic_footage_max=" + sb_cubic_footage_max + "&sb_wall_1=" + sb_wall_1 + "&sb_wall_2=" + sb_wall_2 + "&sb_no_top=" + sb_no_top + "&sb_full_flap_top=" + sb_full_flap_top + "&sb_no_bottom=" + sb_no_bottom + "&sb_full_flap_bottom=" + sb_full_flap_bottom + "&sb_vents_okay=" + sb_vents_okay + "&sb_quantity_requested=" + sb_quantity_requested + "&sb_other_quantity=" + sb_other_quantity + "&sb_frequency_order=" + sb_frequency_order + "&sb_how_many_order_per_yr=" + sb_how_many_order_per_yr + "&sb_what_used_for=" + sb_what_used_for + "&sb_date_needed_by=" + sb_date_needed_by + "&sb_need_pallets=" + sb_need_pallets + "&sb_quotereq_sales_flag=" + sb_quotereq_sales_flag + "&sb_notes=" + sb_notes + "&quote_item=" + quote_item + "&client_dash_flg=" + client_dash_flg + "&high_value_target=" + high_value_target + "&sb_sales_desired_price=" + sb_sales_desired_price + "&sb_partial_flap_top=" + sb_partial_flap_top + "&sb_partial_flap_bottom=" + sb_partial_flap_bottom, true);
				xmlhttp.send();
			}


			function sb_quote_cancel(stableid) {
				var company_id = document.getElementById("company_id" + stableid).value;
				var client_dash_flg = document.getElementById("client_dash_flg" + stableid).value;

				var sb_item_length = document.getElementById("sb_item_length" + stableid).value;
				var sb_item_width = document.getElementById("sb_item_width" + stableid).value;
				var sb_item_height = document.getElementById("sb_item_height" + stableid).value;
				var sb_item_min_length = document.getElementById("sb_item_min_length" + stableid).value;
				var sb_item_max_length = document.getElementById("sb_item_max_length" + stableid).value;
				var sb_item_min_width = document.getElementById("sb_item_min_width" + stableid).value;
				var sb_item_max_width = document.getElementById("sb_item_max_width" + stableid).value;
				var sb_item_min_height = document.getElementById("sb_item_min_height" + stableid).value;
				var sb_item_max_height = document.getElementById("sb_item_max_height" + stableid).value;
				var sb_cubic_footage_min = document.getElementById("sb_cubic_footage_min" + stableid).value;
				var sb_cubic_footage_max = document.getElementById("sb_cubic_footage_max" + stableid).value;
				var sb_date_needed_by = "";

				var sb_quantity_requested = document.getElementById("sb_quantity_requested" + stableid).value;
				var sb_other_quantity = document.getElementById("sb_other_quantity" + stableid).value;
				var sb_frequency_order = document.getElementById("sb_frequency_order" + stableid).value;
				var sb_how_many_order_per_yr = document.getElementById("sb_how_many_order_per_yr" + stableid).value;
				var sb_what_used_for = document.getElementById("sb_what_used_for" + stableid).value;
				var sb_notes = encodeURIComponent(document.getElementById("sb_notes" + stableid).value);
				var quote_item = document.getElementById("quote_item" + stableid).value;
				var sb_sales_desired_price = document.getElementById("sb_sales_desired_price" + stableid).value;

				//
				var sb_wall_1, sb_wall_2, sb_no_top, sb_full_flap_top, sb_no_bottom, sb_full_flap_bottom, sb_vents_okay;
				if (document.getElementById("sb_wall_1" + stableid).checked) {
					sb_wall_1 = document.getElementById("sb_wall_1" + stableid).value;
				} else {
					sb_wall_1 = "";
				}
				if (document.getElementById("sb_wall_2" + stableid).checked) {
					sb_wall_2 = document.getElementById("sb_wall_2" + stableid).value;
				} else {
					sb_wall_2 = "";
				}
				if (document.getElementById("sb_no_top" + stableid).checked) {
					sb_no_top = document.getElementById("sb_no_top" + stableid).value;
				} else {
					sb_no_top = "";
				}
				if (document.getElementById("sb_full_flap_top" + stableid).checked) {
					sb_full_flap_top = document.getElementById("sb_full_flap_top" + stableid).value;
				} else {
					sb_full_flap_top = "";
				}
				if (document.getElementById("sb_partial_flap_top" + stableid).checked) {
					sb_partial_flap_top = document.getElementById("sb_partial_flap_top" + stableid).value;
				} else {
					sb_partial_flap_top = "";
				}

				if (document.getElementById("sb_no_bottom" + stableid).checked) {
					sb_no_bottom = document.getElementById("sb_no_bottom" + stableid).value;
				} else {
					sb_no_bottom = "";
				}
				if (document.getElementById("sb_full_flap_bottom" + stableid).checked) {
					sb_full_flap_bottom = document.getElementById("sb_full_flap_bottom" + stableid).value;
				} else {
					sb_full_flap_bottom = "";
				}
				if (document.getElementById("sb_partial_flap_bottom" + stableid).checked) {
					sb_partial_flap_bottom = document.getElementById("sb_partial_flap_bottom" + stableid).value;
				} else {
					sb_partial_flap_bottom = "";
				}

				if (document.getElementById("sb_vents_okay" + stableid).checked) {
					sb_vents_okay = document.getElementById("sb_vents_okay" + stableid).value;
				} else {
					sb_vents_okay = "";
				}
				var sb_need_pallets, sb_quotereq_sales_flag;
				if (document.getElementById("sb_need_pallets" + stableid).checked) {
					sb_need_pallets = document.getElementById("sb_need_pallets" + stableid).value;
				} else {
					sb_need_pallets = "";
				}

				/*if(document.getElementById("sb_quotereq_sales_flag"+stableid).checked)
					{
						sb_quotereq_sales_flag = document.getElementById("sb_quotereq_sales_flag"+stableid).value;
					}
				else{*/
				sb_quotereq_sales_flag = "";
				//}
				var quote_item = document.getElementById("quote_item" + stableid).value;

				//
				if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				} else { // code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

						document.getElementById("sb" + stableid).innerHTML = xmlhttp.responseText;
						//display table details
						var sb = document.getElementById("sb_sub_table" + stableid);
						if (sb.style.display === "none") {
							sb.style.display = "block";
							document.getElementById("sb_btn" + stableid).innerHTML = "Collapse Details";
						}
						//
					}
				}

				xmlhttp.open("POST", "quote_request_save_new.php?sbupdatequotedata=2&stableid=" + stableid + "&company_id=" + company_id + "&sb_item_length=" + sb_item_length + "&sb_item_width=" + sb_item_width + "&sb_item_height=" + sb_item_height + "&sb_item_min_length=" + sb_item_min_length + "&sb_item_max_length=" + sb_item_max_length + "&sb_item_min_width=" + sb_item_min_width + "&sb_item_max_width=" + sb_item_max_width + "&sb_item_min_height=" + sb_item_min_height + "&sb_item_max_height=" + sb_item_max_height + "&sb_cubic_footage_min=" + sb_cubic_footage_min + "&sb_cubic_footage_max=" + sb_cubic_footage_max + "&sb_wall_1=" + sb_wall_1 + "&sb_wall_2=" + sb_wall_2 + "&sb_no_top=" + sb_no_top + "&sb_full_flap_top=" + sb_full_flap_top + "&sb_no_bottom=" + sb_no_bottom + "&sb_full_flap_bottom=" + sb_full_flap_bottom + "&sb_vents_okay=" + sb_vents_okay + "&sb_quantity_requested=" + sb_quantity_requested + "&sb_other_quantity=" + sb_other_quantity + "&sb_frequency_order=" + sb_frequency_order + "&sb_how_many_order_per_yr=" + sb_how_many_order_per_yr + "&sb_what_used_for=" + sb_what_used_for + "&sb_date_needed_by=" + sb_date_needed_by + "&sb_need_pallets=" + sb_need_pallets + "&sb_quotereq_sales_flag=" + sb_quotereq_sales_flag + "&sb_notes=" + sb_notes + "&quote_item=" + quote_item + "&client_dash_flg=" + client_dash_flg + "&sb_sales_desired_price=" + sb_sales_desired_price + "&sb_partial_flap_top=" + sb_partial_flap_top + "&sb_partial_flap_bottom=" + sb_partial_flap_bottom, true);
				xmlhttp.send();
			}
			//
			//
			function other_quote_updates(othertableid) {
				var company_id = document.getElementById("company_id" + othertableid).value;
				var other_quantity_requested = document.getElementById("other_quantity_requested" + othertableid).value;
				var other_other_quantity = document.getElementById("other_other_quantity" + othertableid).value;
				var other_frequency_order = document.getElementById("other_frequency_order" + othertableid).value;
				var other_what_used_for = document.getElementById("other_what_used_for" + othertableid).value;
				var other_date_needed_by = "";
				var other_need_pallets;
				var other_note = encodeURIComponent(document.getElementById("other_note" + othertableid).value);
				var other_quotereq_sales_flag = ""; //document.getElementById("other_quotereq_sales_flag"+othertableid).value;
				var quote_item = document.getElementById("quote_item" + othertableid).value;

				//
				if (document.getElementById("other_need_pallets" + othertableid).checked) {
					other_need_pallets = document.getElementById("other_need_pallets" + othertableid).value;
				} else {
					other_need_pallets = "";
				}

				var high_value_target;
				if (document.getElementById("high_value_target" + othertableid).checked) {
					high_value_target = document.getElementById("high_value_target" + othertableid).value;
				} else {
					high_value_target = 0;
				}

				//
				/*if(document.getElementById("other_quotereq_sales_flag"+othertableid).checked)
					{
						other_quotereq_sales_flag = document.getElementById("other_quotereq_sales_flag"+othertableid).value;
					}
				else{*/
				other_quotereq_sales_flag = "";
				//}
				var quote_item = document.getElementById("quote_item" + othertableid).value;

				//
				if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				} else { // code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("other" + othertableid).innerHTML = xmlhttp.responseText;
						//display table details
						var other = document.getElementById("other_sub_table" + othertableid);
						if (other.style.display === "none") {
							other.style.display = "block";
							document.getElementById("other_btn" + othertableid).innerHTML = "Collapse Details";
						}
						var new_quote_id = document.getElementById("other_quote_id_n").value;
						var comp_id = document.getElementById("comp_id").value;
					}
				}

				xmlhttp.open("GET", openurl + "quote_request_save_new.php?otherupdatequotedata=1&othertableid=" + othertableid + "&company_id=" + company_id + "&other_quantity_requested=" + other_quantity_requested + "&other_frequency_order=" + other_frequency_order + "&other_other_quantity=" + other_other_quantity + "&other_date_needed_by=" + other_date_needed_by + "&other_need_pallets=" + other_need_pallets + "&other_what_used_for=" + other_what_used_for + "&other_quotereq_sales_flag=" + other_quotereq_sales_flag + "&other_note=" + other_note + "&quote_item=" + quote_item + "&client_dash_flg=" + client_dash_flg + "&high_value_target=" + high_value_target, true);
				xmlhttp.send();
			}

			//supter update
			function sup_quote_updates(suptableid) {
				var company_id = document.getElementById("company_id" + suptableid).value;
				var sup_item_length = document.getElementById("sup_item_length" + suptableid).value;
				var sup_item_width = document.getElementById("sup_item_width" + suptableid).value;
				var sup_item_height = document.getElementById("sup_item_height" + suptableid).value;

				if (document.getElementById("client_dash_flg" + suptableid).checked) {
					var client_dash_flg = document.getElementById("client_dash_flg" + suptableid).value;
				} else {
					var client_dash_flg = "";
				}

				var high_value_target;

				if (document.getElementById("high_value_target" + suptableid).checked) {
					high_value_target = document.getElementById("high_value_target" + suptableid).value;
				} else {
					high_value_target = 0;
				}

				var sup_sales_desired_price = document.getElementById("sup_sales_desired_price" + suptableid).value;

				var sup_other_quantity;
				var sup_quantity_requested = document.getElementById("sup_quantity_requested" + suptableid).value;
				if (sup_quantity_requested == "Other") {
					sup_other_quantity = document.getElementById("sup_other_quantity" + suptableid).value;
				} else {
					sup_other_quantity = "";
				}

				var sup_frequency_order = document.getElementById("sup_frequency_order" + suptableid).value;
				var sup_what_used_for = document.getElementById("sup_what_used_for" + suptableid).value;
				var sup_date_needed_by = "";
				var sup_need_pallets;
				var sup_notes = encodeURIComponent(document.getElementById("sup_notes" + suptableid).value);
				var sup_quotereq_sales_flag = ""; //document.getElementById("sup_quotereq_sales_flag"+suptableid).value;
				var quote_item = document.getElementById("quote_item" + suptableid).value;
				//
				if (document.getElementById("sup_need_pallets" + suptableid).checked) {
					sup_need_pallets = document.getElementById("sup_need_pallets" + suptableid).value;
				} else {
					sup_need_pallets = "";
				}
				//

				/*if(document.getElementById("sup_quotereq_sales_flag"+suptableid).checked)
					{
						sup_quotereq_sales_flag = document.getElementById("sup_quotereq_sales_flag"+suptableid).value;
					}
				else{*/
				sup_quotereq_sales_flag = "";
				//}
				//
				if (sup_quantity_requested == "Other") {
					if (document.getElementById("sup_other_quantity" + suptableid).value == "") {
						alert("Please enter Quantity requested");
						return false;
					}

				}

				//
				if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				} else { // code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("sup" + suptableid).innerHTML = xmlhttp.responseText;
						//display table details
						var sup = document.getElementById("sup_sub_table" + suptableid);
						if (sup.style.display === "none") {
							sup.style.display = "block";
							document.getElementById("sup_btn" + suptableid).innerHTML = "Collapse Details";
						}
						var new_quote_id = document.getElementById("sup_quote_id_n").value;
						var comp_id = document.getElementById("comp_id").value;
					}
				}

				xmlhttp.open("GET", openurl + "quote_request_save_new.php?supupdatequotedata=1&suptableid=" + suptableid + "&company_id=" + company_id + "&sup_item_length=" + sup_item_length + "&sup_item_width=" + sup_item_width + "&sup_item_height=" + sup_item_height + "&sup_quantity_requested=" + sup_quantity_requested + "&sup_frequency_order=" + sup_frequency_order + "&sup_other_quantity=" + sup_other_quantity + "&sup_date_needed_by=" + sup_date_needed_by + "&sup_need_pallets=" + sup_need_pallets + "&sup_what_used_for=" + sup_what_used_for + "&sup_quotereq_sales_flag=" + sup_quotereq_sales_flag + "&sup_notes=" + sup_notes + "&quote_item=" + quote_item + "&client_dash_flg=" + client_dash_flg + "&high_value_target=" + high_value_target + "&sup_sales_desired_price=" + sup_sales_desired_price, true);
				xmlhttp.send();
			}

			function sup_quote_cancel(suptableid) {
				var company_id = document.getElementById("company_id" + suptableid).value;
				var sup_item_length = document.getElementById("sup_item_length" + suptableid).value;
				var sup_item_width = document.getElementById("sup_item_width" + suptableid).value;
				var sup_item_height = document.getElementById("sup_item_height" + suptableid).value;
				var client_dash_flg = document.getElementById("client_dash_flg" + suptableid).value;

				var sup_sales_desired_price = document.getElementById("sup_sales_desired_price" + suptableid).value;

				var sup_other_quantity;
				var sup_quantity_requested = document.getElementById("sup_quantity_requested" + suptableid).value;
				if (sup_quantity_requested == "Other") {
					sup_other_quantity = document.getElementById("sup_other_quantity" + suptableid).value;
				} else {
					sup_other_quantity = "";
				}
				var sup_frequency_order = document.getElementById("sup_frequency_order" + suptableid).value;
				var sup_what_used_for = document.getElementById("sup_what_used_for" + suptableid).value;
				var sup_date_needed_by = "";
				var sup_need_pallets;
				var sup_notes = encodeURIComponent(document.getElementById("sup_notes" + suptableid).value);
				var sup_quotereq_sales_flag = ""; //document.getElementById("sup_quotereq_sales_flag"+suptableid).value;
				var quote_item = document.getElementById("quote_item" + suptableid).value;
				//
				if (document.getElementById("sup_need_pallets" + suptableid).checked) {
					sup_need_pallets = document.getElementById("sup_need_pallets" + suptableid).value;
				} else {
					sup_need_pallets = "";
				}
				//
				/*if(document.getElementById("sup_quotereq_sales_flag"+suptableid).checked)
					{
						sup_quotereq_sales_flag = document.getElementById("sup_quotereq_sales_flag"+suptableid).value;
					}
				else{*/
				sup_quotereq_sales_flag = "";
				//}

				//
				if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				} else { // code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("sup" + suptableid).innerHTML = xmlhttp.responseText;
						//display table details
						var sup = document.getElementById("sup_sub_table" + suptableid);
						if (sup.style.display === "none") {
							sup.style.display = "block";
							document.getElementById("sup_btn" + suptableid).innerHTML = "Collapse Details";
						}
						//
					}
				}
				xmlhttp.open("GET", openurl + "quote_request_save_new.php?supupdatequotedata=2&suptableid=" + suptableid + "&company_id=" + company_id + "&sup_item_length=" + sup_item_length + "&sup_item_width=" + sup_item_width + "&sup_item_height=" + sup_item_height + "&sup_quantity_requested=" + sup_quantity_requested + "&sup_frequency_order=" + sup_frequency_order + "&sup_other_quantity=" + sup_other_quantity + "&sup_date_needed_by=" + sup_date_needed_by + "&sup_need_pallets=" + sup_need_pallets + "&sup_what_used_for=" + sup_what_used_for + "&sup_quotereq_sales_flag=" + sup_quotereq_sales_flag + "&sup_notes=" + sup_notes + "&quote_item=" + quote_item + "&client_dash_flg=" + client_dash_flg + "&sup_sales_desired_price=" + sup_sales_desired_price, true);
				xmlhttp.send();
			}

			function pal_quote_updates(paltableid) {
				var company_id = document.getElementById("company_id" + paltableid).value;
				var pal_item_length = document.getElementById("pal_item_length" + paltableid).value;
				var pal_item_width = document.getElementById("pal_item_width" + paltableid).value;

				if (document.getElementById("client_dash_flg" + paltableid).checked) {
					var client_dash_flg = document.getElementById("client_dash_flg" + paltableid).value;
				} else {
					var client_dash_flg = "";
				}

				var high_value_target;
				if (document.getElementById("high_value_target" + paltableid).checked) {
					high_value_target = document.getElementById("high_value_target" + paltableid).value;
				} else {
					high_value_target = 0;
				}

				var pal_quantity_requested = document.getElementById("pal_quantity_requested" + paltableid).value;
				var pal_other_quantity = document.getElementById("pal_other_quantity" + paltableid).value;
				var pal_frequency_order = document.getElementById("pal_frequency_order" + paltableid).value;
				var pal_how_many_order_per_yr = document.getElementById("pal_how_many_order_per_yr" + paltableid).value;
				var pal_what_used_for = document.getElementById("pal_what_used_for" + paltableid).value;
				var pal_date_needed_by = "";
				var pal_note = encodeURIComponent(document.getElementById("pal_note" + paltableid).value);
				var pal_quotereq_sales_flag = ""; //document.getElementById("pal_quotereq_sales_flag"+paltableid).value;
				var quote_item = document.getElementById("quote_item" + paltableid).value;

				var pal_sales_desired_price = document.getElementById("pal_sales_desired_price" + paltableid).value;
				var pal_grade_a, pal_grade_b, pal_grade_c, pal_material_wooden, pal_material_plastic, pal_material_corrugate, pal_entry_2way, pal_entry_4way, pal_structure_stringer, pal_structure_block, pal_heat_treated;

				var box_pallet_sub_type = document.getElementById("box_pallet_sub_type" + paltableid).value;

				if (box_pallet_sub_type == 17) {
					if (document.getElementById("pal_grade_a" + paltableid).checked) {
						pal_grade_a = document.getElementById("pal_grade_a" + paltableid).value;
					} else {
						pal_grade_a = "";
					}

					if (document.getElementById("pal_grade_b" + paltableid).checked) {
						pal_grade_b = document.getElementById("pal_grade_b" + paltableid).value;
					} else {
						pal_grade_b = "";
					}

					if (document.getElementById("pal_grade_c" + paltableid).checked) {
						pal_grade_c = document.getElementById("pal_grade_c" + paltableid).value;
					} else {
						pal_grade_c = "";
					}

					if (document.getElementById("pal_material_wooden" + paltableid).checked) {
						pal_material_wooden = document.getElementById("pal_material_wooden" + paltableid).value;
					} else {
						pal_material_wooden = "";
					}

					if (document.getElementById("pal_material_plastic" + paltableid).checked) {
						pal_material_plastic = document.getElementById("pal_material_plastic" + paltableid).value;
					} else {
						pal_material_plastic = "";
					}

					if (document.getElementById("pal_material_corrugate" + paltableid).checked) {
						pal_material_corrugate = document.getElementById("pal_material_corrugate" + paltableid).value;
					} else {
						pal_material_corrugate = "";
					}

					if (document.getElementById("pal_entry_2way" + paltableid).checked) {
						pal_entry_2way = document.getElementById("pal_entry_2way" + paltableid).value;
					} else {
						pal_entry_2way = "";
					}

					if (document.getElementById("pal_entry_4way" + paltableid).checked) {
						pal_entry_4way = document.getElementById("pal_entry_4way" + paltableid).value;
					} else {
						pal_entry_4way = "";
					}

					if (document.getElementById("pal_structure_stringer" + paltableid).checked) {
						pal_structure_stringer = document.getElementById("pal_structure_stringer" + paltableid).value;
					} else {
						pal_structure_stringer = "";
					}

					if (document.getElementById("pal_structure_block" + paltableid).checked) {
						pal_structure_block = document.getElementById("pal_structure_block" + paltableid).value;
					} else {
						pal_structure_block = "";
					}

					var pal_heat_treated = document.getElementById("pal_heat_treated" + paltableid).value;
				}
				//
				/*if(document.getElementById("pal_quotereq_sales_flag"+paltableid).checked)
					{
						pal_quotereq_sales_flag = document.getElementById("pal_quotereq_sales_flag"+paltableid).value;
					}
				else{*/
				pal_quotereq_sales_flag = "";
				//}
				var quote_item = document.getElementById("quote_item" + paltableid).value;

				//
				if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				} else { // code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("pal" + paltableid).innerHTML = xmlhttp.responseText;
						//display table details
						var pal = document.getElementById("pal_sub_table" + paltableid);
						if (pal.style.display === "none") {
							pal.style.display = "block";
							document.getElementById("pal_btn" + paltableid).innerHTML = "Collapse Details";
						}

						var new_quote_id = document.getElementById("pal_quote_id_n").value;
						var comp_id = document.getElementById("comp_id").value;
					}
				}

				xmlhttp.open("GET", openurl + "quote_request_save_new.php?palupdatequotedata=1&paltableid=" + paltableid + "&company_id=" + company_id + "&pal_item_length=" + pal_item_length + "&pal_item_width=" + pal_item_width + "&pal_quantity_requested=" + pal_quantity_requested + "&pal_frequency_order=" + pal_frequency_order + "&pal_how_many_order_per_yr=" + pal_how_many_order_per_yr + "&pal_other_quantity=" + pal_other_quantity + "&pal_date_needed_by=" + pal_date_needed_by + "&pal_what_used_for=" + pal_what_used_for + "&pal_quotereq_sales_flag=" + pal_quotereq_sales_flag + "&pal_note=" + pal_note + "&quote_item=" + quote_item + "&client_dash_flg=" + client_dash_flg + "&high_value_target=" + high_value_target + "&pal_sales_desired_price=" + pal_sales_desired_price + "&pal_grade_a=" + pal_grade_a + "&pal_grade_b=" + pal_grade_b + "&pal_grade_c=" + pal_grade_c + "&pal_material_wooden=" + pal_material_wooden + "&pal_material_plastic=" + pal_material_plastic + "&pal_material_corrugate=" + pal_material_corrugate + "&pal_entry_2way=" + pal_entry_2way + "&pal_entry_4way=" + pal_entry_4way + "&pal_structure_stringer=" + pal_structure_stringer + "&pal_structure_block=" + pal_structure_block + "&pal_heat_treated=" + pal_heat_treated + "&box_pallet_sub_type=" + box_pallet_sub_type, true);
				xmlhttp.send();
			}

			function pal_quote_cancel(paltableid) {
				var company_id = document.getElementById("company_id" + paltableid).value;

				var pal_item_length = document.getElementById("pal_item_length" + paltableid).value;
				var pal_item_width = document.getElementById("pal_item_width" + paltableid).value;

				var client_dash_flg = document.getElementById("client_dash_flg" + paltableid).value;

				var pal_sales_desired_price = document.getElementById("pal_sales_desired_price" + paltableid).value;

				var pal_quantity_requested = document.getElementById("pal_quantity_requested" + paltableid).value;
				var pal_other_quantity = document.getElementById("pal_other_quantity" + paltableid).value;
				var pal_frequency_order = document.getElementById("pal_frequency_order" + paltableid).value;
				var pal_how_many_order_per_yr = document.getElementById("pal_how_many_order_per_yr" + paltableid).value;
				var pal_what_used_for = document.getElementById("pal_what_used_for" + paltableid).value;
				var pal_date_needed_by = "";
				var pal_note = encodeURIComponent(document.getElementById("pal_note" + paltableid).value);
				var pal_quotereq_sales_flag = ""; //document.getElementById("pal_quotereq_sales_flag"+paltableid).value;
				var quote_item = document.getElementById("quote_item" + paltableid).value;
				//
				/*if(document.getElementById("pal_quotereq_sales_flag"+paltableid).checked)
					{
						pal_quotereq_sales_flag = document.getElementById("pal_quotereq_sales_flag"+paltableid).value;
					}
				else{*/
				pal_quotereq_sales_flag = "";
				//}
				var quote_item = document.getElementById("quote_item" + paltableid).value;

				//
				if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				} else { // code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("pal" + paltableid).innerHTML = xmlhttp.responseText;
						//display table details
						var pal = document.getElementById("pal_sub_table" + paltableid);
						if (pal.style.display === "none") {
							pal.style.display = "block";
							document.getElementById("pal_btn" + paltableid).innerHTML = "Collapse Details";
						}
						//
					}
				}
				xmlhttp.open("GET", "quote_request_save_new.php?palupdatequotedata=2&paltableid=" + paltableid + "&company_id=" + company_id + "&pal_item_length=" + pal_item_length + "&pal_item_width=" + pal_item_width + "&pal_quantity_requested=" + pal_quantity_requested + "&pal_frequency_order=" + pal_frequency_order + "&pal_how_many_order_per_yr=" + pal_how_many_order_per_yr + "&pal_other_quantity=" + pal_other_quantity + "&pal_date_needed_by=" + pal_date_needed_by + "&pal_what_used_for=" + pal_what_used_for + "&pal_quotereq_sales_flag=" + pal_quotereq_sales_flag + "&pal_note=" + pal_note + "&quote_item=" + quote_item + "&client_dash_flg=" + client_dash_flg + "&pal_sales_desired_price=" + pal_sales_desired_price, true);
				xmlhttp.send();
			}

			function other_quote_cancel(othertableid) {
				var company_id = document.getElementById("company_id" + othertableid).value;
				var client_dash_flg = document.getElementById("client_dash_flg" + othertableid).value;

				var other_quantity_requested = document.getElementById("other_quantity_requested" + othertableid).value;
				var other_other_quantity = document.getElementById("other_other_quantity" + othertableid).value;
				var other_frequency_order = document.getElementById("other_frequency_order" + othertableid).value;
				var other_what_used_for = document.getElementById("other_what_used_for" + othertableid).value;

				var other_date_needed_by = "";
				var other_need_pallets;
				var other_note = encodeURIComponent(document.getElementById("other_note" + othertableid).value);
				var other_quotereq_sales_flag = ""; //document.getElementById("other_quotereq_sales_flag"+othertableid).value;
				var quote_item = document.getElementById("quote_item" + othertableid).value;
				//
				if (document.getElementById("other_need_pallets" + othertableid).checked) {
					other_need_pallets = document.getElementById("other_need_pallets" + othertableid).value;
				} else {
					other_need_pallets = "";
				}
				//
				/*if(document.getElementById("other_quotereq_sales_flag"+othertableid).checked)
					{
						other_quotereq_sales_flag = document.getElementById("other_quotereq_sales_flag"+othertableid).value;
					}
				else{*/
				other_quotereq_sales_flag = "";
				//}
				var quote_item = document.getElementById("quote_item" + othertableid).value;

				//
				if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				} else { // code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("other" + othertableid).innerHTML = xmlhttp.responseText;
						//display table details
						var other = document.getElementById("other_sub_table" + othertableid);
						if (other.style.display === "none") {
							other.style.display = "block";
							document.getElementById("other_btn" + othertableid).innerHTML = "Collapse Details";
						}
						//
					}
				}
				xmlhttp.open("GET", openurl + "quote_request_save_new.php?otherupdatequotedata=2&othertableid=" + othertableid + "&company_id=" + company_id + "&other_quantity_requested=" + other_quantity_requested + "&other_frequency_order=" + other_frequency_order + "&other_other_quantity=" + other_other_quantity + "&other_date_needed_by=" + other_date_needed_by + "&other_need_pallets=" + other_need_pallets + "&other_what_used_for=" + other_what_used_for + "&other_quotereq_sales_flag=" + other_quotereq_sales_flag + "&other_note=" + other_note + "&quote_item=" + quote_item + "&client_dash_flg=" + client_dash_flg, true);
				xmlhttp.send();
			}

			//delete code for gylord
			function g_quote_delete(tableid, quote_item, companyid) {
				//
				var choice = confirm('Do you really want to delete this record?');
				if (choice === true) {
					var p = "g";
					//
					if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
						xmlhttp = new XMLHttpRequest();
					} else { // code for IE6, IE5
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					}
					xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
							document.getElementById("g" + tableid).innerHTML = xmlhttp.responseText;
							$("#show_q_div").load(location.href + " #show_q_div");
						}
					}
					xmlhttp.open("GET", "delete_quote_request.php?deletequotedata=1&p=" + p + "&tableid=" + tableid + "&quote_item=" + quote_item + "&companyid=" + companyid, true);
					xmlhttp.send();
				} else {

				}
			} //end g delete function


			//move code for gylord
			function g_quote_move(tableid, quote_item, companyid, clientdash_flg) {
				if (clientdash_flg == 1) {
					var choice = confirm('Do you really want to move this record from Customer Entered to UCB entered?');
				} else {
					var choice = confirm('Do you really want to move this record from UCB entered to Customer Entered?');
				}
				if (choice === true) {
					var p = "g";
					//
					if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
						xmlhttp = new XMLHttpRequest();
					} else { // code for IE6, IE5
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					}
					xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
							document.getElementById("g" + tableid).innerHTML = xmlhttp.responseText;
							$("#show_q_div").load(location.href + " #show_q_div");
							location.reload();
						}
					}
					xmlhttp.open("GET", "move_quote_request.php?deletequotedata=1&p=" + p + "&tableid=" + tableid + "&quote_item=" + quote_item + "&companyid=" + companyid + "&clientdash_flg=" + clientdash_flg, true);
					xmlhttp.send();
				}
			} //end g move function

			function pal_quote_delete(paltableid, quote_item, companyid) {
				var choice = confirm('Do you really want to delete this record?');
				if (choice === true) {
					//
					var p = "pal";
					//
					if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
						xmlhttp = new XMLHttpRequest();
					} else { // code for IE6, IE5
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					}
					xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
							document.getElementById("pal" + paltableid).innerHTML = xmlhttp.responseText;
							$("#show_q_div").load(location.href + " #show_q_div");
						}
					}
					xmlhttp.open("GET", "delete_quote_request.php?editquotedata=1&p=" + p + "&paltableid=" + paltableid + "&quote_item=" + quote_item + "&companyid=" + companyid, true);
					xmlhttp.send();
				} else {

				}
			}

			function pal_quote_move(paltableid, quote_item, companyid, clientdash_flg) {
				if (clientdash_flg == 1) {
					var choice = confirm('Do you really want to move this record from Customer Entered to UCB entered?');
				} else {
					var choice = confirm('Do you really want to move this record from UCB entered to Customer Entered?');
				}
				if (choice === true) {
					//
					var p = "pal";
					//
					if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
						xmlhttp = new XMLHttpRequest();
					} else { // code for IE6, IE5
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					}
					xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
							document.getElementById("pal" + paltableid).innerHTML = xmlhttp.responseText;
							$("#show_q_div").load(location.href + " #show_q_div");
							location.reload();
						}
					}

					xmlhttp.open("GET", "move_quote_request.php?editquotedata=1&p=" + p + "&tableid=" + paltableid + "&quote_item=" + quote_item + "&companyid=" + companyid + "&clientdash_flg=" + clientdash_flg, true);
					xmlhttp.send();
				}
			}

			//
			function other_quote_delete(othertableid, quote_item, companyid) {
				var choice = confirm('Do you really want to delete this record?');
				if (choice === true) {
					//
					var p = "other";
					//
					if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
						xmlhttp = new XMLHttpRequest();
					} else { // code for IE6, IE5
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					}
					xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
							document.getElementById("other" + othertableid).innerHTML = xmlhttp.responseText;
							$("#show_q_div").load(location.href + " #show_q_div");
						}
					}
					xmlhttp.open("GET", "delete_quote_request.php?editquotedata=1&p=" + p + "&othertableid=" + othertableid + "&quote_item=" + quote_item + "&companyid=" + companyid, true);
					xmlhttp.send();
				} else {

				}
			}

			function other_quote_move(othertableid, quote_item, companyid, clientdash_flg) {
				if (clientdash_flg == 1) {
					var choice = confirm('Do you really want to move this record from Customer Entered to UCB entered?');
				} else {
					var choice = confirm('Do you really want to move this record from UCB entered to Customer Entered?');
				}
				if (choice === true) {
					//
					var p = "other";
					//
					if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
						xmlhttp = new XMLHttpRequest();
					} else { // code for IE6, IE5
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					}
					xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
							document.getElementById("other" + othertableid).innerHTML = xmlhttp.responseText;
							$("#show_q_div").load(location.href + " #show_q_div");
							location.reload();
						}
					}

					xmlhttp.open("GET", "move_quote_request.php?editquotedata=1&p=" + p + "&tableid=" + othertableid + "&quote_item=" + quote_item + "&companyid=" + companyid + "&clientdash_flg=" + clientdash_flg, true);
					xmlhttp.send();
				}
			}

			//---------------------------------------------------------------------------
			//Matching tool
			//Quote request matching tool for Gaylord

			function display_request_gaylords(id, boxid, flg, viewflg, client_flg, load_all = 0) {
				//alert(id+"--"+boxid+"--"+flg+"--"+viewflg+"--"+client_flg+"--"+load_all);
				//if (document.getElementById("light_gaylord_new1").innerHTML == "") 
				//{
				var selectobject = document.getElementById("lightbox_g" + boxid);
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');

				var g_timing = 1;

				document.getElementById('light_gaylord_new1').style.display = 'block';
				document.getElementById('light_gaylord_new1').style.left = n_left + 20 + 'px';
				document.getElementById('light_gaylord_new1').style.top = n_top + 20 + 'px';
				document.getElementById('light_gaylord_new1').style.height = 580 + 'px';

				document.getElementById("light_gaylord_new1").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

				var sstr = "";
				sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
				sstr = sstr + "<tr align='center'>";
				sstr = sstr + "<td class='display_maintitle'>";
				sstr = sstr + "GAYLORD MATCHING TOOL";
				sstr = sstr + "&nbsp;&nbsp;";
				sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new1').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
				sstr = sstr + "Timing&nbsp;&nbsp;";
				sstr = sstr + "<select class='basic_style' name='g_timing' id='g_timing' onChange='display_request_gaylords_child(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (flg == 1) {
					sstr = sstr + " selected ";
					g_timing = 1;
				}
				sstr = sstr + ">Rdy Now + Presell</option><option value='3'";

				if (flg == 3) {
					sstr = sstr + "selected";
					g_timing = 3;
				}
				sstr = sstr + ">Rdy < 3mo </option><option value='2'";
				if (flg == 2) {
					sstr = sstr + "selected";
					g_timing = 2;
				}
				sstr = sstr + ">FTL Rdy Now ONLY</option>";
				sstr = sstr + "</select>";
				sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";
				//sstr = sstr + "<br>";
				//if (flg == 0) {

				sstr = sstr + "<select class='basic_style' name='sort_g_tool' id='sort_g_tool' onChange='display_request_gaylords_child(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (flg == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Available to Sell</option><option value='2'";
				if (flg == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Available to Sell + Potential to Get</option></select>";
				//
				sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";
				//sstr = sstr + "<br>";
				//if (flg == 0) {

				sstr = sstr + "<select class='basic_style' name='sort_g_tool2' id='sort_g_tool2' onChange='display_request_gaylords_child(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (flg == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Matching</option><option value='2'";
				if (flg == 2) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">All Items (Ignore Criteria)</option></select>";

				sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

				sstr = sstr + "View&nbsp;&nbsp;";

				//if client dash
				if (client_flg != 1) {
					sstr = sstr + "<select class='basic_style' name='sort_g_view' id='sort_g_view' onChange='display_request_gaylords_child(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";
					if (viewflg == 1) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">UCB View</option><option value='2'";
					if (viewflg == 2) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				} else {
					sstr = sstr + "<select class='basic_style' name='sort_g_view' id='sort_g_view' onChange='display_request_gaylords_child(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='2'";
					if (viewflg == 2) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">Customer Facing View</option></select>";

				}

				//

				sstr = sstr + "</td>";
				sstr = sstr + "</tr>";
				sstr = sstr + "</table>";

				var sstr_load_all = "";
				sstr_load_all = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
				sstr_load_all = sstr_load_all + "<tr align='center'>";
				sstr_load_all = sstr_load_all + "<td class='display_maintitle'>";
				sstr_load_all = sstr_load_all + "GAYLORD MATCHING TOOL";
				sstr_load_all = sstr_load_all + "&nbsp;&nbsp;";
				sstr_load_all = sstr_load_all + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new1').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";

				sstr_load_all = sstr_load_all + "</td>";
				sstr_load_all = sstr_load_all + "</tr>";
				sstr_load_all = sstr_load_all + "</table>";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						if (load_all == 0) {
							document.getElementById("light_gaylord_new1").innerHTML = sstr + xmlhttp.responseText;
						} else {
							document.getElementById("light_gaylord_new1").innerHTML = sstr_load_all + xmlhttp.responseText;
						}
					}
				}


				xmlhttp.open("GET", "quote_request_gaylords_new.php?ID=" + id + "&gbox=" + boxid + "&display-allrec=" + flg + "&display_view=" + viewflg + "&sort_g_tool2=" + flg + "&load_all=" + load_all + "&client_flg=" + client_flg + "&g_timing=" + g_timing, true);
				xmlhttp.send();
				//}
				/*else 
				{
					var selectobject = document.getElementById("lightbox"); 
					var n_left = f_getPosition(selectobject, 'Left');
					var n_top  = f_getPosition(selectobject, 'Top');
					document.getElementById('light_gaylord_new1').style.display='block';
					document.getElementById('light_gaylord_new1').style.left = n_left + 20 + 'px';
					document.getElementById('light_gaylord_new1').style.top = n_top + 20 + 'px';
				}*/
			}

			function display_request_gaylords_child(id, flg, boxid, viewflg, client_flg, n_left, n_top) {
				var flgs = document.getElementById("sort_g_tool").value;
				var flgs_org = document.getElementById("sort_g_tool").value;
				var viewflgs = document.getElementById("sort_g_view").value;
				//alert(flgs);
				//

				var g_timing = document.getElementById("g_timing").value;
				var sort_g_tool2 = document.getElementById("sort_g_tool2").value;

				var selectobject = document.getElementById("lightbox_g" + boxid);
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');

				var sstr = "";
				sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
				sstr = sstr + "<tr align='center'>";
				sstr = sstr + "<td class='display_maintitle'>";
				sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' >GAYLORD MATCHING TOOL</font>";
				sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;";
				sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new1').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br><font face='Arial, Helvetica, sans-serif' size='1'>";
				//
				if (flgs == 1) {
					sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
				}
				if (flgs == 2) {
					sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!', <br>'Unavailable (New Lead', 'Unavailable (Qualified, In Process)', <br>'Unavailable (Qualified, No Customers)' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
				}

				/* if(sort_g_tool2 == 1){
            sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display all boxes with status 'Available', <br>'Available, but Need Approval to Sell', <br>'Available, Currently Recycling, Sell w/ Lead Time!' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
        }
		if(sort_g_tool2 == 2){
            sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display all boxes with status 'Available', <br>'Available, but Need Approval to Sell', <br>'Available, Currently Recycling, Sell w/ Lead Time!', <br>'Unavailable (New Lead', 'Unavailable (Qualified, In Process)', <br>'Unavailable (Qualified, No Customers)' <br>and UCB Owned inventory &nbsp;&nbsp;</font></span></div>";
        }*/
				//
				sstr = sstr + "<br>";
				//if (flg == 0) {
				//  alert(flgs);

				//new code		  
				sstr = sstr + "Timing&nbsp;&nbsp;";
				sstr = sstr + "<select class='basic_style' name='g_timing' id='g_timing' onChange='display_request_gaylords_child(" + id + "," + this.value + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (g_timing == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Rdy Now + Presell</option><option value='3'";

				if (g_timing == 3) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Rdy < 3mo </option><option value='2'";

				if (g_timing == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">FTL Rdy Now ONLY</option>";
				sstr = sstr + "</select>";
				sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";
				//sstr = sstr + "<br>";
				//if (flg == 0) {

				sstr = sstr + "<select class='basic_style' name='sort_g_tool' id='sort_g_tool' onChange='display_request_gaylords_child(" + id + "," + this.value + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (flgs_org == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Available to Sell</option><option value='2'";
				if (flgs_org == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Available to Sell + Potential to Get</option></select>";
				//
				sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";
				//sstr = sstr + "<br>";
				//if (flg == 0) {

				sstr = sstr + "<select class='basic_style' name='sort_g_tool2' id='sort_g_tool2' onChange='display_request_gaylords_child(" + id + "," + this.value + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (sort_g_tool2 == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Matching</option><option value='2'";
				if (sort_g_tool2 == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">All Items (Ignore Criteria)</option></select>";

				sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

				sstr = sstr + "View&nbsp;&nbsp;";

				//
				if (client_flg != 1) {
					sstr = sstr + "<select class='basic_style' name='sort_g_view' id='sort_g_view' onChange='display_request_gaylords_child(" + id + "," + flgs + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";
					if (viewflgs == 1) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">UCB View</option><option value='2'";
					if (viewflgs == 2) {
						sstr = sstr + "selected";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				} else {
					sstr = sstr + "<select class='basic_style' name='sort_g_view' id='sort_g_view' onChange='display_request_gaylords_child(" + id + "," + flgs + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='2'";

					if (viewflgs == 2) {
						sstr = sstr + "selected";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				}

				sstr = sstr + "</td>";
				sstr = sstr + "</tr>";
				sstr = sstr + "</table>";
				//New code

				/*if(flgs==1 || flgs==2 || sort_g_tool2 == 1 || sort_g_tool2 == 2){
					sstr = sstr + "<table width='100%' border='0' cellspacing='1' cellpadding='1' ><tr>";
					sstr = sstr + "<td colspan='14'><font face='Arial, Helvetica, sans-serif' size='1'>";
					sstr = sstr + "<a href='#' onmouseover=" + String.fromCharCode(34) + "Tip('Program search for the neareast Box location based on the Ship To Zipcode. This is done based on the Zip code Latitude and longitude values stored in the database. ";
					if(flgs==1){
						sstr = sstr + "And shown boxes based on Matching Criteria & Available only.')";
					} 
					if(flgs==2){
						sstr = sstr + "And shown boxes based on Matching Criteria & Available & Potential.')";
					} 
					if(sort_g_tool2 == 1){
						sstr = sstr + "And shown All Boxes (Available).')";
					} 
					if(sort_g_tool2 == 2){
						sstr = sstr + "And shown All Boxes (Available & Potential).')";
					} 
					sstr = sstr + String.fromCharCode(34) + " onmouseout='UnTip()'>How it Works</a></font></table>";
				}*/

				var selectobject = document.getElementById("lightbox");
				//var n_left = f_getPosition(selectobject, 'Left');
				//var n_top  = f_getPosition(selectobject, 'Top');
				document.getElementById('light_gaylord_new1').style.display = 'block';
				document.getElementById('light_gaylord_new1').style.left = n_left + 20 + 'px';
				document.getElementById('light_gaylord_new1').style.top = n_top + 20 + 'px';

				document.getElementById("light_gaylord_new1").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("light_gaylord_new1").innerHTML = sstr + xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "quote_request_gaylords_new.php?ID=" + id + "&gbox=" + boxid + "&display-allrec=" + flgs + "&display_view=" + viewflgs + "&g_timing=" + g_timing + "&sort_g_tool2=" + sort_g_tool2 + "&client_flg=" + client_flg, true);
				xmlhttp.send();
			}

			function display_request_gaylords_speed(id, boxid, flg, viewflg, client_flg, load_all = 0) {
				//alert(id+"--"+boxid+"--"+flg+"--"+viewflg+"--"+client_flg+"--"+load_all);
				var selectobject = document.getElementById("lightbox_g" + boxid);
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');
				document.getElementById('light_gaylord_new1').style.display = 'block';
				document.getElementById('light_gaylord_new1').style.left = n_left + 20 + 'px';
				document.getElementById('light_gaylord_new1').style.top = n_top + 20 + 'px';
				document.getElementById('light_gaylord_new1').style.height = 580 + 'px';

				document.getElementById("light_gaylord_new1").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

				var sstr = "";
				sstr = "<table width='100%' border='0' cellspacing='0' cellpadding='2' class='basic_style'>";
				sstr = sstr + "<tr align='center'>";
				sstr = sstr + "<td class='display_maintitle' colspan='6'>";
				sstr = sstr + "GAYLORD MATCHING TOOL";
				sstr = sstr + "&nbsp;&nbsp;";
				sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new1').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
				sstr = sstr + "</td></tr><tr><td class='display_maintitle'>&nbsp;&nbsp;&nbsp;Timing<br>";
				sstr = sstr + "&nbsp;&nbsp;&nbsp;<select class='basic_style' name='g_timing' id='g_timing' onChange='display_request_gaylords_child_speed(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (flg == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Rdy Now + Presell</option><option value='3'";

				if (flg == 3) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Rdy < 3mo </option><option value='2'";

				if (flg == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">FTL Rdy Now ONLY</option>";
				sstr = sstr + "</select>";
				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "&nbsp;Status&nbsp;<br>";
				sstr = sstr + "<select class='basic_style' name='sort_g_tool' id='sort_g_tool' onChange='display_request_gaylords_child_speed(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (flg == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Available to Sell</option><option value='2'";
				if (flg == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Available to Sell + Potential to Get</option></select>";

				sstr = sstr + "</td><td class='display_maintitle'>";
				sstr = sstr + "&nbsp;Criteria&nbsp;<br>";
				sstr = sstr + "<select class='basic_style' name='sort_g_tool2' id='sort_g_tool2' onChange='display_request_gaylords_child_speed(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (flg == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Matching</option><option value='2'";
				if (flg == 2) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">All Items (Ignore Criteria)</option></select>";

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "&nbsp;View&nbsp;<br>";

				//if client dash
				if (client_flg != 1) {
					sstr = sstr + "<select class='basic_style' name='sort_g_view' id='sort_g_view' onChange='display_request_gaylords_child_speed(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";
					if (viewflg == 1) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">UCB View</option><option value='2'";
					if (viewflg == 2) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				} else {
					sstr = sstr + "<select class='basic_style' name='sort_g_view' id='sort_g_view' onChange='display_request_gaylords_child_speed(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='2'";
					if (viewflg == 2) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">Customer Facing View</option></select>";

				}

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "&nbsp;Combine Item View <br>";
				sstr = sstr + "<select class='basic_style' name='sort_g_location' id= 'sort_g_location' onChange='display_request_gaylords_child_speed(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")' > ";
				sstr = sstr + "<option value='1'>By Item</option>";
				sstr = sstr + "<option value='2'>By Location</option>";
				sstr = sstr + " </select>";

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "<input type='checkbox' name='canship_ltl' id='canship_ltl' onChange='display_request_gaylords_child_speed(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'>";
				sstr = sstr + "&nbsp;&nbsp;Can Ship LTL Only <br>";

				sstr = sstr + "<input type='checkbox' name='customer_pickup_allowed' id='customer_pickup_allowed' onChange='display_request_gaylords_child_speed(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'>";
				sstr = sstr + "&nbsp;&nbsp;Customer Pickups Allowed Only";

				sstr = sstr + "</td>";
				sstr = sstr + "</tr>";
				sstr = sstr + "</table>";

				var sstr_load_all = "";
				sstr_load_all = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
				sstr_load_all = sstr_load_all + "<tr align='center'>";
				sstr_load_all = sstr_load_all + "<td class='display_maintitle'>";
				sstr_load_all = sstr_load_all + "GAYLORD MATCHING TOOL";
				sstr_load_all = sstr_load_all + "&nbsp;&nbsp;";
				sstr_load_all = sstr_load_all + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new1').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";

				sstr_load_all = sstr_load_all + "</td>";
				sstr_load_all = sstr_load_all + "</tr>";
				sstr_load_all = sstr_load_all + "</table>";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() { //alert('res -> '+xmlhttp.responseText);
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						if (load_all == 0) {
							document.getElementById("light_gaylord_new1").innerHTML = sstr + xmlhttp.responseText;
						} else {
							document.getElementById("light_gaylord_new1").innerHTML = sstr_load_all + xmlhttp.responseText;
						}
					}
				}

				//alert("quote_request_gaylords_test1.php?ID="+id+"&gbox="+boxid+"&display-allrec="+flg+"&display_view="+viewflg+"&sort_g_tool2="+flg+"&load_all="+load_all+"&client_flg="+client_flg);

				xmlhttp.open("GET", "quote_request_gaylords_test1.php?ID=" + id + "&gbox=" + boxid + "&display-allrec=" + flg + "&display_view=" + viewflg + "&sort_g_tool2=" + flg + "&load_all=" + load_all + "&client_flg=" + client_flg, true);
				xmlhttp.send();
			}

			function display_request_gaylords_child_speed(id, flg, boxid, viewflg, client_flg, n_left, n_top) {
				var flgs = document.getElementById("sort_g_tool").value;
				var flgs_org = document.getElementById("sort_g_tool").value;
				var viewflgs = document.getElementById("sort_g_view").value;

				var g_timing = document.getElementById("g_timing").value;
				var sort_g_tool2 = document.getElementById("sort_g_tool2").value;
				var sort_g_location = document.getElementById("sort_g_location").value;

				if (document.getElementById("canship_ltl").checked) {
					var canship_ltl = 1;
				} else {
					var canship_ltl = 0;
				}

				if (document.getElementById("customer_pickup_allowed").checked) {
					var customer_pickup = 1;
				} else {
					var customer_pickup = 0;
				}
				//alert('sort_g_location -> ' + sort_g_location);	
				var selectobject = document.getElementById("lightbox_g" + boxid);
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');

				var sstr = "";
				sstr = "<table width='100%' border='0' cellspacing='0' cellpadding='2' class='basic_style'>";
				sstr = sstr + "<tr align='center'>";
				sstr = sstr + "<td class='display_maintitle' colspan='6'>";
				sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' >GAYLORD MATCHING TOOL</font>";
				sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;";
				sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new1').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br><font face='Arial, Helvetica, sans-serif' size='1'>";

				if (flgs == 1) {
					sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
				}
				if (flgs == 2) {
					sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!', <br>'Unavailable (New Lead', 'Unavailable (Qualified, In Process)', <br>'Unavailable (Qualified, No Customers)' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
				}
				//sstr = sstr + "<br>";

				sstr = sstr + "</td></tr><tr><td class='display_maintitle'>";
				sstr = sstr + "Timing&nbsp;<br>";
				sstr = sstr + "<select class='basic_style' name='g_timing' id='g_timing' onChange='display_request_gaylords_child_speed(" + id + "," + this.value + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (g_timing == 1) {
					sstr = sstr + " selected ";
				}

				sstr = sstr + ">Rdy Now + Presell</option><option value='3'";

				if (g_timing == 3) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Rdy < 3mo </option><option value='2'";
				if (g_timing == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">FTL Rdy Now ONLY</option>";
				sstr = sstr + "</select>";

				sstr = sstr + "</td><td class='display_maintitle'>";
				sstr = sstr + "Status&nbsp;<br>";

				sstr = sstr + "<select class='basic_style' name='sort_g_tool' id='sort_g_tool' onChange='display_request_gaylords_child_speed(" + id + "," + this.value + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (flgs_org == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Available to Sell</option><option value='2'";
				if (flgs_org == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Available to Sell + Potential to Get</option></select>";

				sstr = sstr + "</td><td class='display_maintitle'>";
				sstr = sstr + "Criteria&nbsp;<br>";

				sstr = sstr + "<select class='basic_style' name='sort_g_tool2' id='sort_g_tool2' onChange='display_request_gaylords_child_speed(" + id + "," + this.value + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (sort_g_tool2 == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Matching</option><option value='2'";
				if (sort_g_tool2 == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">All Items (Ignore Criteria)</option></select>";

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "View&nbsp;<br>";

				if (client_flg != 1) {
					sstr = sstr + "<select class='basic_style' name='sort_g_view' id='sort_g_view' onChange='display_request_gaylords_child_speed(" + id + "," + flgs + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";
					if (viewflgs == 1) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">UCB View</option><option value='2'";
					if (viewflgs == 2) {
						sstr = sstr + "selected";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				} else {
					sstr = sstr + "<select class='basic_style' name='sort_g_view' id='sort_g_view' onChange='display_request_gaylords_child_speed(" + id + "," + flgs + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='2'";

					if (viewflgs == 2) {
						sstr = sstr + "selected";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				}

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "Combine Item View&nbsp;<br>";
				sstr = sstr + "<select class='basic_style' name='sort_g_location' id= 'sort_g_location' onChange='display_request_gaylords_child_speed(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")' > ";
				sstr = sstr + "<option value='1'";
				if (sort_g_location == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">By Item</option>";
				sstr = sstr + "<option value='2'";
				if (sort_g_location == 2) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">By Location</option>";
				sstr = sstr + " </select>";

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "<input type='checkbox' name='canship_ltl' id='canship_ltl' onChange='display_request_gaylords_child_speed(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'";

				if (canship_ltl == 1) {
					sstr = sstr + " checked ";
				}
				sstr = sstr + ">";
				sstr = sstr + "&nbsp;&nbsp;Can Ship LTL Only <br>";

				sstr = sstr + "<input type='checkbox' name='customer_pickup_allowed' id='customer_pickup_allowed' onChange='display_request_gaylords_child_speed(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")' ";
				if (customer_pickup == 1) {
					sstr = sstr + " checked ";
				}
				sstr = sstr + ">";
				sstr = sstr + "&nbsp;&nbsp;Customer Pickups Allowed Only";

				sstr = sstr + "</td>";
				sstr = sstr + "</tr>";
				sstr = sstr + "</table>";

				var selectobject = document.getElementById("lightbox_g");
				document.getElementById('light_gaylord_new1').style.display = 'block';
				document.getElementById('light_gaylord_new1').style.left = n_left + 20 + 'px';
				document.getElementById('light_gaylord_new1').style.top = n_top + 20 + 'px';

				document.getElementById("light_gaylord_new1").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("light_gaylord_new1").innerHTML = sstr + xmlhttp.responseText;
					}
				}

				//alert("quote_request_gaylords_test1.php?ID="+id+"&gbox="+boxid+"&display-allrec="+flgs+"&display_view="+viewflgs+"&g_timing="+g_timing+"&sort_g_tool2="+ sort_g_tool2+"&client_flg="+client_flg+"&sort_g_location="+sort_g_location+"&canship_ltl="+canship_ltl+"&customer_pickup="+customer_pickup);

				xmlhttp.open("GET", "quote_request_gaylords_test1.php?ID=" + id + "&gbox=" + boxid + "&display-allrec=" + flgs + "&display_view=" + viewflgs + "&g_timing=" + g_timing + "&sort_g_tool2=" + sort_g_tool2 + "&client_flg=" + client_flg + "&sort_g_location=" + sort_g_location + "&canship_ltl=" + canship_ltl + "&customer_pickup=" + customer_pickup, true);
				xmlhttp.send();
			}

			/*<!-- TEST GAYLORD SECTION START - ->  */
			function display_request_gaylords_test(id, boxid, flg, viewflg, client_flg, load_all = 0, onlyftl = 0) {
				//alert(id+"--"+boxid+"--"+flg+"--"+viewflg+"--"+client_flg+"--"+load_all);
				var selectobject = document.getElementById("lightbox_g" + boxid);
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');
				document.getElementById('light_gaylord_new1').style.display = 'block';
				document.getElementById('light_gaylord_new1').style.left = n_left + 20 + 'px';
				document.getElementById('light_gaylord_new1').style.top = n_top + 20 + 'px';
				document.getElementById('light_gaylord_new1').style.height = 580 + 'px';

				document.getElementById("light_gaylord_new1").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

				var sstr = "";
				sstr = "<table width='100%' border='0' cellspacing='0' cellpadding='2' class='basic_style'>";
				sstr = sstr + "<tr align='center'>";
				sstr = sstr + "<td class='display_maintitle' colspan='6'>";
				sstr = sstr + "GAYLORD MATCHING TOOL";
				sstr = sstr + "&nbsp;&nbsp;";
				sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new1').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
				sstr = sstr + "</td></tr><tr><td class='display_maintitle'>&nbsp;&nbsp;&nbsp;Timing<br>";
				sstr = sstr + "&nbsp;&nbsp;&nbsp;<select class='basic_style' name='g_timing' id='g_timing' onChange='display_request_gaylords_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				var gtiming = 0;
				if (flg == 1 || boxid == 0) {
					gtiming = 1;
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Rdy Now + Presell</option><option value='3'";

				if (flg == 3) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Rdy < 3mo </option><option value='2'";

				if (flg == 2 && boxid != 0) {
					sstr = sstr + "selected";
				}
				if (onlyftl == 1) {
					gtiming = 2;
					sstr = sstr + "selected";
				}
				sstr = sstr + ">FTL Rdy Now ONLY</option>";
				sstr = sstr + "</select>";
				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "&nbsp;Status&nbsp;<br>";
				sstr = sstr + "<select class='basic_style' name='sort_g_tool' id='sort_g_tool' onChange='display_request_gaylords_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (flg == 1 || boxid == 0) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Available to Sell</option><option value='2'";
				if (flg == 2 && boxid != 0) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Available to Sell + Potential to Get</option></select>";

				sstr = sstr + "</td><td class='display_maintitle'>";
				sstr = sstr + "&nbsp;Criteria&nbsp;<br>";
				sstr = sstr + "<select class='basic_style' name='sort_g_tool2' id='sort_g_tool2' onChange='display_request_gaylords_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (flg == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Matching</option><option value='2'";
				if (flg == 2) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">All Items (Ignore Criteria)</option></select>";

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "&nbsp;View&nbsp;<br>";

				//if client dash
				if (client_flg != 1) {
					sstr = sstr + "<select class='basic_style' name='sort_g_view' id='sort_g_view' onChange='display_request_gaylords_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";
					if (viewflg == 1) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">UCB View</option><option value='2'";
					if (viewflg == 2) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				} else {
					sstr = sstr + "<select class='basic_style' name='sort_g_view' id='sort_g_view' onChange='display_request_gaylords_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='2'";
					if (viewflg == 2) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">Customer Facing View</option></select>";

				}

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "&nbsp;Combine Item View <br>";
				sstr = sstr + "<select class='basic_style' name='sort_g_location' id= 'sort_g_location' onChange='display_request_gaylords_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")' > ";
				sstr = sstr + "<option value='1'>By Item</option>";
				sstr = sstr + "<option value='2'>By Location</option>";
				sstr = sstr + " </select>";

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "<input type='checkbox' name='canship_ltl' id='canship_ltl' onChange='display_request_gaylords_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'>";
				sstr = sstr + "&nbsp;&nbsp;Can Ship LTL Only <br>";

				sstr = sstr + "<input type='checkbox' name='customer_pickup_allowed' id='customer_pickup_allowed' onChange='display_request_gaylords_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'>";
				sstr = sstr + "&nbsp;&nbsp;Customer Pickups Allowed Only";

				sstr = sstr + "</td>";
				sstr = sstr + "</tr>";
				sstr = sstr + "</table>";

				var sstr_load_all = "";
				sstr_load_all = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
				sstr_load_all = sstr_load_all + "<tr align='center'>";
				sstr_load_all = sstr_load_all + "<td class='display_maintitle'>";
				sstr_load_all = sstr_load_all + "GAYLORD MATCHING TOOL";
				sstr_load_all = sstr_load_all + "&nbsp;&nbsp;";
				sstr_load_all = sstr_load_all + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new1').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";

				sstr_load_all = sstr_load_all + "</td>";
				sstr_load_all = sstr_load_all + "</tr>";
				sstr_load_all = sstr_load_all + "</table>";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() { //alert('res -> '+xmlhttp.responseText);
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						if (load_all == 0) {
							document.getElementById("light_gaylord_new1").innerHTML = sstr + xmlhttp.responseText;
						} else {
							document.getElementById("light_gaylord_new1").innerHTML = sstr_load_all + xmlhttp.responseText;
						}
					}
				}

				//alert("quote_request_gaylords_tool_v2.php?ID="+id+"&gbox="+boxid+"&display-allrec="+flg+"&display_view="+viewflg+"&sort_g_tool2="+flg+"&load_all="+load_all+"&client_flg="+client_flg);

				xmlhttp.open("GET", "quote_request_gaylords_tool_v2.php?ID=" + id + "&gbox=" + boxid + "&g_timing=" + gtiming + "&onlyftl=" + onlyftl + "&display-allrec=" + flg + "&display_view=" + viewflg + "&sort_g_tool2=" + flg + "&load_all=" + load_all + "&client_flg=" + client_flg, true);
				xmlhttp.send();
			}

			function display_request_gaylords_child_test(id, flg, boxid, viewflg, client_flg, n_left, n_top) {
				var flgs = document.getElementById("sort_g_tool").value;
				var flgs_org = document.getElementById("sort_g_tool").value;
				var viewflgs = document.getElementById("sort_g_view").value;

				var g_timing = document.getElementById("g_timing").value;
				var sort_g_tool2 = document.getElementById("sort_g_tool2").value;
				var sort_g_location = document.getElementById("sort_g_location").value;

				if (document.getElementById("canship_ltl").checked) {
					var canship_ltl = 1;
				} else {
					var canship_ltl = 0;
				}

				if (document.getElementById("customer_pickup_allowed").checked) {
					var customer_pickup = 1;
				} else {
					var customer_pickup = 0;
				}
				//alert('sort_g_location -> ' + sort_g_location);	
				var selectobject = document.getElementById("lightbox_g" + boxid);
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');

				if (sort_g_tool2 == 2) {
					boxid = 0;
				}

				var sstr = "";
				sstr = "<table width='100%' border='0' cellspacing='0' cellpadding='2' class='basic_style'>";
				sstr = sstr + "<tr align='center'>";
				sstr = sstr + "<td class='display_maintitle' colspan='6'>";
				sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' >GAYLORD MATCHING TOOL</font>";
				sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;";
				sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new1').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br><font face='Arial, Helvetica, sans-serif' size='1'>";

				if (flgs == 1) {
					sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
				}
				if (flgs == 2) {
					sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!', <br>'Unavailable (New Lead', 'Unavailable (Qualified, In Process)', <br>'Unavailable (Qualified, No Customers)' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
				}
				//sstr = sstr + "<br>";

				sstr = sstr + "</td></tr><tr><td class='display_maintitle'>";
				sstr = sstr + "Timing&nbsp;<br>";
				sstr = sstr + "<select class='basic_style' name='g_timing' id='g_timing' onChange='display_request_gaylords_child_test(" + id + "," + flg + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (g_timing == 1) {
					sstr = sstr + " selected ";
				}

				sstr = sstr + ">Rdy Now + Presell</option><option value='3'";

				if (g_timing == 3) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Rdy < 3mo </option><option value='2'";
				if (g_timing == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">FTL Rdy Now ONLY</option>";
				sstr = sstr + "</select>";

				sstr = sstr + "</td><td class='display_maintitle'>";
				sstr = sstr + "Status&nbsp;<br>";

				sstr = sstr + "<select class='basic_style' name='sort_g_tool' id='sort_g_tool' onChange='display_request_gaylords_child_test(" + id + "," + flg + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (flgs_org == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Available to Sell</option><option value='2'";
				if (flgs_org == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Available to Sell + Potential to Get</option></select>";

				sstr = sstr + "</td><td class='display_maintitle'>";
				sstr = sstr + "Criteria&nbsp;<br>";

				sstr = sstr + "<select class='basic_style' name='sort_g_tool2' id='sort_g_tool2' onChange='display_request_gaylords_child_test(" + id + "," + flg + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (sort_g_tool2 == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Matching</option><option value='2'";
				if (sort_g_tool2 == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">All Items (Ignore Criteria)</option></select>";

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "View&nbsp;<br>";

				if (client_flg != 1) {
					sstr = sstr + "<select class='basic_style' name='sort_g_view' id='sort_g_view' onChange='display_request_gaylords_child_test(" + id + "," + flg + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";
					if (viewflgs == 1) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">UCB View</option><option value='2'";
					if (viewflgs == 2) {
						sstr = sstr + "selected";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				} else {
					sstr = sstr + "<select class='basic_style' name='sort_g_view' id='sort_g_view' onChange='display_request_gaylords_child_test(" + id + "," + flg + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='2'";

					if (viewflgs == 2) {
						sstr = sstr + "selected";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				}

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "Combine Item View&nbsp;<br>";
				sstr = sstr + "<select class='basic_style' name='sort_g_location' id= 'sort_g_location' onChange='display_request_gaylords_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")' > ";
				sstr = sstr + "<option value='1'";
				if (sort_g_location == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">By Item</option>";
				sstr = sstr + "<option value='2'";
				if (sort_g_location == 2) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">By Location</option>";
				sstr = sstr + " </select>";

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "<input type='checkbox' name='canship_ltl' id='canship_ltl' onChange='display_request_gaylords_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'";

				if (canship_ltl == 1) {
					sstr = sstr + " checked ";
				}
				sstr = sstr + ">";
				sstr = sstr + "&nbsp;&nbsp;Can Ship LTL Only <br>";

				sstr = sstr + "<input type='checkbox' name='customer_pickup_allowed' id='customer_pickup_allowed' onChange='display_request_gaylords_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")' ";
				if (customer_pickup == 1) {
					sstr = sstr + " checked ";
				}
				sstr = sstr + ">";
				sstr = sstr + "&nbsp;&nbsp;Customer Pickups Allowed Only";

				sstr = sstr + "</td>";
				sstr = sstr + "</tr>";
				sstr = sstr + "</table>";

				var selectobject = document.getElementById("lightbox_g");
				document.getElementById('light_gaylord_new1').style.display = 'block';
				document.getElementById('light_gaylord_new1').style.left = n_left + 20 + 'px';
				document.getElementById('light_gaylord_new1').style.top = n_top + 20 + 'px';

				document.getElementById("light_gaylord_new1").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("light_gaylord_new1").innerHTML = sstr + xmlhttp.responseText;
					}
				}

				//alert("quote_request_gaylords_tool_v2.php?ID="+id+"&gbox="+boxid+"&display-allrec="+flgs+"&display_view="+viewflgs+"&g_timing="+g_timing+"&sort_g_tool2="+ sort_g_tool2+"&client_flg="+client_flg+"&sort_g_location="+sort_g_location+"&canship_ltl="+canship_ltl+"&customer_pickup="+customer_pickup);

				xmlhttp.open("GET", "quote_request_gaylords_tool_v2.php?ID=" + id + "&gbox=" + boxid + "&display-allrec=" + flgs + "&display_view=" + viewflgs + "&g_timing=" + g_timing + "&sort_g_tool2=" + sort_g_tool2 + "&client_flg=" + client_flg + "&sort_g_location=" + sort_g_location + "&canship_ltl=" + canship_ltl + "&customer_pickup=" + customer_pickup, true);
				xmlhttp.send();
			}

			function display_child_box(companyid, inventoryid, loopid, boxid, orgboxid) {
				if (boxid > 0) {
					var selectobject = document.getElementById("lightbox_g" + boxid);
					var n_left = f_getPosition(selectobject, 'Left');
					var n_top = f_getPosition(selectobject, 'Top');
				} else {
					var selectobject = document.getElementById("lightbox_g" + orgboxid);
					var n_left = f_getPosition(selectobject, 'Left');
					var n_top = f_getPosition(selectobject, 'Top');
				}

				document.getElementById('light_gaylord_v3').style.display = 'block';
				document.getElementById('light_gaylord_v3').style.left = n_left + 50 + 'px';
				document.getElementById('light_gaylord_v3').style.top = n_top - 50 + 'px';
				document.getElementById('light_gaylord_v3').style.width = 600 + 'px';
				document.getElementById("light_gaylord_v3").innerHTML = "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_v3').style.display='none';>Close</a><br>" + document.getElementById("addtocart_parentdiv" + loopid).innerHTML
			}

			/*<!-- TEST GAYLORD SECTION ENDS -- >*/

			function get_all_variations(id, flg, boxid, viewflg, client_flg, orgboxid, clicked_box_id, child_ids_array, row_id) {
				var flgs = document.getElementById("sort_g_tool").value;
				var flgs_org = document.getElementById("sort_g_tool").value;
				var viewflgs = document.getElementById("sort_g_view").value;

				var g_timing = document.getElementById("g_timing").value;
				var g_timing_enter_dt = "";
				if (g_timing == 9) {
					document.getElementById("g_timing_enter_dt").style.display = "inline";
					document.getElementById("g_timing_enter_dt_btn").style.display = "inline";

					g_timing_enter_dt = document.getElementById("g_timing_enter_dt").value;
				}

				var sort_g_tool2 = document.getElementById("sort_g_tool2").value;
				//var sort_g_location = document.getElementById("sort_g_location").value;
				var sort_g_location = "";

				if (document.getElementById("canship_ltl").checked) {
					var canship_ltl = 1;
				} else {
					var canship_ltl = 0;
				}

				if (document.getElementById("customer_pickup_allowed").checked) {
					var customer_pickup = 1;
				} else {
					var customer_pickup = 0;
				}

				if (sort_g_tool2 == 2) {
					boxid = 0;
				}
				if (sort_g_tool2 == 1) {
					boxid = orgboxid;
				}

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("expand_var" + clicked_box_id).style.display = "inline";
						document.getElementById("expand_var" + clicked_box_id).innerHTML = xmlhttp.responseText;
						document.getElementById("span_expand_v" + clicked_box_id).style.display = "none";
						document.getElementById("span_expand_v_collapse" + clicked_box_id).style.display = "inline";
					}
				}

				xmlhttp.open("GET", "quote_request_gaylords_tool_v3.php?ID=" + id + "&gbox=" + boxid + "&orgboxid=" + orgboxid + "&display-allrec=&display_view=" + viewflgs + "&g_timing=&sort_g_tool2=" + sort_g_tool2 + "&client_flg=" + client_flg + "&sort_g_location=" + sort_g_location + "&canship_ltl=" + canship_ltl + "&customer_pickup=" + customer_pickup + "&g_timing_enter_dt=" + g_timing_enter_dt + "&view_child=1&clicked_box_id=" + clicked_box_id + "&child_ids_array=" + child_ids_array, false);
				xmlhttp.send();
			}

			function get_all_variations_collapse(id, flg, boxid, viewflg, client_flg, orgboxid, clicked_box_id, child_ids_array, row_id) {
				document.getElementById("expand_var" + clicked_box_id).style.display = "none";
				document.getElementById("span_expand_v" + clicked_box_id).style.display = "inline";
				document.getElementById("span_expand_v_collapse" + clicked_box_id).style.display = "none";
			}

			//New Gaylord Matching tool ver 3
			function display_matching_tool_gaylords_v3(id, boxid, flg, viewflg, client_flg, load_all = 0, onlyftl = 0) {
				var selectobject = document.getElementById("lightbox_g" + boxid);
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');
				document.getElementById('light_gaylord_new1').style.display = 'block';
				document.getElementById('light_gaylord_new1').style.left = n_left + 20 + 'px';
				document.getElementById('light_gaylord_new1').style.top = n_top + 20 + 'px';
				document.getElementById('light_gaylord_new1').style.height = 580 + 'px';

				document.getElementById("light_gaylord_new1").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

				var sstr = "";
				sstr = "<table width='100%' border='0' cellspacing='0' cellpadding='2' class='basic_style'>";
				sstr = sstr + "<tr align='center'>";
				sstr = sstr + "<td class='display_maintitle' colspan='6'>";
				sstr = sstr + "GAYLORD MATCHING TOOL";
				sstr = sstr + "&nbsp;&nbsp;";
				sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new1').style.display='none';document.getElementById('fade').style.display='none'>Close</a>";
				sstr = sstr + "<br></td></tr><tr><td class='display_maintitle'>&nbsp;&nbsp;&nbsp;Timing<br>";

				sstr = sstr + "&nbsp;&nbsp;&nbsp;<select class='basic_style' name='g_timing' id='g_timing' onChange='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + "," + boxid + ")'>";
				var gtiming = 4;
				sstr = sstr + "<option value='4'>Can ship in 2 weeks</option>";
				sstr = sstr + "<option value='5'>Can ship immediately</option>";
				sstr = sstr + "<option value='7'>Can ship this month</option>";
				sstr = sstr + "<option value='8'>Can ship next month</option>";
				if (boxid == 0) {
					var gtiming = 6;
					sstr = sstr + "<option value='6' selected>Ready to ship whenever</option>";
				} else {
					sstr = sstr + "<option value='6'>Ready to ship whenever</option>";
				}
				sstr = sstr + "<option value='9'>Enter ship by date</option>";
				sstr = sstr + "</select>";
				sstr = sstr + "<input type='text' id='g_timing_enter_dt' name='g_timing_enter_dt' value='' placeholder='mm/dd/yyyy' style='width:100px; display:none;'>";
				sstr = sstr + "<input type='button' id='g_timing_enter_dt_btn' name='g_timing_enter_dt_btn' value='Load' onClick='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + "," + boxid + ")' style='display:none;'>";
				sstr = sstr + "</td>";

				sstr = sstr + "<td class='display_maintitle'>";
				sstr = sstr + "&nbsp;Status&nbsp;<br>";
				sstr = sstr + "<select class='basic_style' name='sort_g_tool' id='sort_g_tool' onChange='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + "," + boxid + ")'><option value='1'";

				if (flg == 1 || boxid != 0) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Available to Sell</option><option value='2'";
				if (flg == 2 && boxid == 0) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Available to Sell + Potential to Get</option></select>";

				sstr = sstr + "</td><td class='display_maintitle'>";
				sstr = sstr + "&nbsp;Criteria&nbsp;<br>";
				sstr = sstr + "<select class='basic_style' name='sort_g_tool2' id='sort_g_tool2' onChange='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + "," + boxid + ")'><option value='1'";

				if (flg == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Matching</option><option value='2'";
				if (flg == 2) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">All Items (Ignore Criteria)</option></select>";

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "&nbsp;View&nbsp;<br>";

				//if client dash
				if (client_flg != 1) {
					sstr = sstr + "<select class='basic_style' name='sort_g_view' id='sort_g_view' onChange='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + "," + boxid + ")'><option value='1'";
					if (viewflg == 1) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">UCB View</option><option value='2'";
					if (viewflg == 2) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				} else {
					sstr = sstr + "<select class='basic_style' name='sort_g_view' id='sort_g_view' onChange='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + "," + boxid + ")'><option value='2'";
					if (viewflg == 2) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">Customer Facing View</option></select>";

				}

				sstr = sstr + "</td>";

				/*
				sstr = sstr +"<td class='display_maintitle'>&nbsp;Combine Item View <br>";
				sstr = sstr +"<select class='basic_style' name='sort_g_location' id= 'sort_g_location' onChange='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ","+ boxid + ")' > "; 
				sstr = sstr +"<option value='1'>By Item</option>";
				sstr = sstr +"<option value='2'>By Location</option>";
				sstr = sstr +" </select>";
				sstr = sstr + "</td>";
				*/

				var chk_no_loads_available_val = "chk_no_loads_available_no";
				if (boxid == 0) {
					var chk_no_loads_available_val = "chk_no_loads_available_yes";
					sstr = sstr + "<td class='display_maintitle'><input type='checkbox' checked id='chk_no_loads_available' name='chk_no_loads_available' value='noload' onChange='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + "," + boxid + ")'>";
				} else {
					sstr = sstr + "<td class='display_maintitle'><input type='checkbox' id='chk_no_loads_available' name='chk_no_loads_available' value='noload' onChange='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + "," + boxid + ")'>";
				}
				sstr = sstr + "&nbsp;&nbsp;No Loads Available:</td>";

				sstr = sstr + "<td class='display_maintitle'><input type='checkbox' name='canship_ltl' id='canship_ltl' onChange='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + "," + boxid + ")'>";
				sstr = sstr + "&nbsp;&nbsp;Can Ship LTL Only <br>";

				sstr = sstr + "<input type='checkbox' name='customer_pickup_allowed' id='customer_pickup_allowed' onChange='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + "," + boxid + ")'>";
				sstr = sstr + "&nbsp;&nbsp;Customer Pickups Allowed Only";

				sstr = sstr + "</td>";
				sstr = sstr + "</tr>";
				sstr = sstr + "</table>";

				var sstr_load_all = "";
				sstr_load_all = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
				sstr_load_all = sstr_load_all + "<tr align='center'>";
				sstr_load_all = sstr_load_all + "<td class='display_maintitle'>";
				sstr_load_all = sstr_load_all + "GAYLORD MATCHING TOOL";
				sstr_load_all = sstr_load_all + "&nbsp;&nbsp;";
				sstr_load_all = sstr_load_all + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new1').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";

				sstr_load_all = sstr_load_all + "</td>";
				sstr_load_all = sstr_load_all + "</tr>";
				sstr_load_all = sstr_load_all + "</table>";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() { //alert('res -> '+xmlhttp.responseText);
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						if (load_all == 0) {
							document.getElementById("light_gaylord_new1").innerHTML = sstr + xmlhttp.responseText;
						} else {
							document.getElementById("light_gaylord_new1").innerHTML = sstr_load_all + xmlhttp.responseText;
						}

						if (document.getElementById("gayloardtoolautoload")) {
							//document.getElementById("gayloardtoolautoload").innerHTML = "Data loaded."; 
						}
					}
				}

				xmlhttp.open("GET", "quote_request_gaylords_tool_v3.php?ID=" + id + "&gbox=" + boxid + "&g_timing=" + gtiming + "&onlyftl=" + onlyftl + "&display-allrec=" + flg + "&display_view=" + viewflg + "&sort_g_tool=" + flg + "&sort_g_tool2=" + flg + "&load_all=" + load_all + "&client_flg=" + client_flg + "&chk_no_loads_available_val=" + chk_no_loads_available_val, true);
				xmlhttp.send();
			}

			function display_request_gaylords_child_v3(id, flg, boxid, viewflg, client_flg, n_left, n_top, orgboxid) {
				var flgs = document.getElementById("sort_g_tool").value;
				var flgs_org = document.getElementById("sort_g_tool").value;
				var viewflgs = document.getElementById("sort_g_view").value;

				var g_timing = document.getElementById("g_timing").value;
				var g_timing_enter_dt = "";
				if (g_timing == 9) {
					document.getElementById("g_timing_enter_dt").style.display = "inline";
					document.getElementById("g_timing_enter_dt_btn").style.display = "inline";

					g_timing_enter_dt = document.getElementById("g_timing_enter_dt").value;
				}

				var sort_g_tool2 = document.getElementById("sort_g_tool2").value;
				//var sort_g_location = document.getElementById("sort_g_location").value;
				var sort_g_location = "";
				var chk_no_loads_available = document.getElementById('chk_no_loads_available').checked;
				var chk_no_loads_available_val = "chk_no_loads_available_no";
				if (chk_no_loads_available == true) {
					chk_no_loads_available_val = "chk_no_loads_available_yes";
				}
				if (document.getElementById("canship_ltl").checked) {
					var canship_ltl = 1;
				} else {
					var canship_ltl = 0;
				}

				if (document.getElementById("customer_pickup_allowed").checked) {
					var customer_pickup = 1;
				} else {
					var customer_pickup = 0;
				}
				//alert('sort_g_location -> ' + sort_g_location);	
				if (document.getElementById("lightbox_g" + orgboxid)) {
					var selectobject = document.getElementById("lightbox_g" + orgboxid);
					var n_left = f_getPosition(selectobject, 'Left');
					var n_top = f_getPosition(selectobject, 'Top');
				}

				if (sort_g_tool2 == 2) {
					boxid = 0;
				}
				if (sort_g_tool2 == 1) {
					boxid = orgboxid;
				}

				var sstr = "";
				sstr = "<table width='100%' border='0' cellspacing='0' cellpadding='2' class='basic_style'>";
				sstr = sstr + "<tr align='center'>";
				sstr = sstr + "<td class='display_maintitle' colspan='6'>";
				sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' >GAYLORD MATCHING TOOL</font>";
				sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;";
				sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new1').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br><font face='Arial, Helvetica, sans-serif' size='1'>";

				if (flgs == 1) {
					sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
				}
				if (flgs == 2) {
					sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!', <br>'Unavailable (New Lead', 'Unavailable (Qualified, In Process)', <br>'Unavailable (Qualified, No Customers)' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
				}
				//sstr = sstr + "<br>";

				sstr = sstr + "</td></tr><tr><td class='display_maintitle'>";
				sstr = sstr + "Timing&nbsp;<br>";
				sstr = sstr + "<select class='basic_style' name='g_timing' id='g_timing' onChange='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + "," + orgboxid + ")'>";

				sstr = sstr + "<option value='4'";
				if (g_timing == 4) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + " >Can ship in 2 weeks</option>";
				sstr = sstr + "<option value='5'";
				if (g_timing == 5) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + " >Can ship immediately</option>";
				sstr = sstr + "<option value='7'";
				if (g_timing == 7) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + " >Can ship this month</option>";
				sstr = sstr + "<option value='8'";
				if (g_timing == 8) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + " >Can ship next month</option>";
				sstr = sstr + "<option value='6'";
				if (g_timing == 6) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + " >Ready to ship whenever</option>";
				sstr = sstr + "<option value='9'";
				if (g_timing == 9) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + " >Enter ship by date</option>";

				sstr = sstr + "</select>";
				sstr = sstr + "<input type='text' id='g_timing_enter_dt' name='g_timing_enter_dt' value='' placeholder='mm/dd/yyyy' style='width: 100px; display:none;'>";
				sstr = sstr + "<input type='button' id='g_timing_enter_dt_btn' name='g_timing_enter_dt_btn' value='Load' onClick='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + "," + orgboxid + ")' style='display:none;'>";
				sstr = sstr + "</td>";
				sstr = sstr + "<td class='display_maintitle'>";
				sstr = sstr + "Status&nbsp;<br>";

				sstr = sstr + "<select class='basic_style' name='sort_g_tool' id='sort_g_tool' onChange='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + "," + orgboxid + ")'><option value='1'";

				if (flgs_org == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Available to Sell</option><option value='2'";
				if (flgs_org == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Available to Sell + Potential to Get</option></select>";

				sstr = sstr + "</td><td class='display_maintitle'>";
				sstr = sstr + "Criteria&nbsp;<br>";

				sstr = sstr + "<select class='basic_style' name='sort_g_tool2' id='sort_g_tool2' onChange='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + "," + orgboxid + ")'><option value='1'";

				if (sort_g_tool2 == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Matching</option><option value='2'";
				if (sort_g_tool2 == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">All Items (Ignore Criteria)</option></select>";

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "View&nbsp;<br>";

				if (client_flg != 1) {
					sstr = sstr + "<select class='basic_style' name='sort_g_view' id='sort_g_view' onChange='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + "," + orgboxid + ")'><option value='1'";
					if (viewflgs == 1) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">UCB View</option><option value='2'";
					if (viewflgs == 2) {
						sstr = sstr + "selected";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				} else {
					sstr = sstr + "<select class='basic_style' name='sort_g_view' id='sort_g_view' onChange='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + "," + orgboxid + ")'><option value='2'";

					if (viewflgs == 2) {
						sstr = sstr + "selected";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				}

				sstr = sstr + "</td>";

				/*
				sstr = sstr +"<td class='display_maintitle'>Combine Item View&nbsp;<br>";
				sstr = sstr +"<select class='basic_style' name='sort_g_location' id= 'sort_g_location' onChange='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ","+ orgboxid + ")' > "; 
				sstr = sstr +"<option value='1'";
					if(sort_g_location == 1){
						sstr = sstr + " selected ";
					}
				sstr = sstr +">By Item</option>";
				sstr = sstr +"<option value='2'";
					if(sort_g_location == 2){
						sstr = sstr + " selected ";
					}
				sstr = sstr +">By Location</option>";
				sstr = sstr +" </select>";

				sstr = sstr + "</td>";
				*/
				sstr = sstr + "<td class='display_maintitle'><input type='checkbox' name='chk_no_loads_available' id='chk_no_loads_available' onChange='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + "," + orgboxid + ")'";

				if (chk_no_loads_available_val == "chk_no_loads_available_yes") {
					sstr = sstr + " checked ";
				}
				sstr = sstr + ">";
				sstr = sstr + "&nbsp;&nbsp;No Loads Available: </td>";

				sstr = sstr + "<td class='display_maintitle'><input type='checkbox' name='canship_ltl' id='canship_ltl' onChange='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + "," + orgboxid + ")'";

				if (canship_ltl == 1) {
					sstr = sstr + " checked ";
				}
				sstr = sstr + ">";
				sstr = sstr + "&nbsp;&nbsp;Can Ship LTL Only <br>";

				sstr = sstr + "<input type='checkbox' name='customer_pickup_allowed' id='customer_pickup_allowed' onChange='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + "," + orgboxid + ")' ";
				if (customer_pickup == 1) {
					sstr = sstr + " checked ";
				}
				sstr = sstr + ">";
				sstr = sstr + "&nbsp;&nbsp;Customer Pickups Allowed Only";

				sstr = sstr + "</td>";
				sstr = sstr + "</tr>";
				sstr = sstr + "</table>";

				var selectobject = document.getElementById("lightbox_g");
				document.getElementById('light_gaylord_new1').style.display = 'block';
				document.getElementById('light_gaylord_new1').style.left = n_left + 20 + 'px';
				document.getElementById('light_gaylord_new1').style.top = n_top + 20 + 'px';

				if (g_timing == 9 && g_timing_enter_dt == "") {
					document.getElementById("light_gaylord_new1").innerHTML = '<link rel="stylesheet" type="text/css" href="css/newstylechange.css" /><br>' + sstr;

					document.getElementById("g_timing_enter_dt").style.display = "inline";
					document.getElementById("g_timing_enter_dt_btn").style.display = "inline";
				} else {
					document.getElementById("light_gaylord_new1").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

					if (window.XMLHttpRequest) {
						xmlhttp = new XMLHttpRequest();
					} else {
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					}
					xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
							document.getElementById("light_gaylord_new1").innerHTML = sstr + xmlhttp.responseText;

							if (g_timing == 9) {
								document.getElementById("g_timing_enter_dt").style.display = "inline";
								document.getElementById("g_timing_enter_dt_btn").style.display = "inline";

								document.getElementById("g_timing_enter_dt").value = g_timing_enter_dt;
							}

						}
					}

					xmlhttp.open("GET", "quote_request_gaylords_tool_v3.php?ID=" + id + "&gbox=" + boxid + "&orgboxid=" + orgboxid + "&display-allrec=" + flgs + "&display_view=" + viewflgs + "&g_timing=" + g_timing + "&sort_g_tool=" + flgs + "&sort_g_tool2=" + sort_g_tool2 + "&client_flg=" + client_flg + "&sort_g_location=" + sort_g_location + "&canship_ltl=" + canship_ltl + "&customer_pickup=" + customer_pickup + "&g_timing_enter_dt=" + g_timing_enter_dt + "&chk_no_loads_available_val=" + chk_no_loads_available_val, true);
					xmlhttp.send();
				}
			}
			//New Gaylord Matching tool ver 3



			function calculate_delivery(inv_b2b_id, companyID, minfob, autoloadflg = 0) {

				if (autoloadflg == 1) {
					document.getElementById("td_cal_delctrauto" + inv_b2b_id).innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";
				} else {
					document.getElementById("td_cal_del" + inv_b2b_id).innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";
				}

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						//var aa = xmlhttp.responseText; 
						if (autoloadflg == 1) {
							document.getElementById("td_cal_delctrauto" + inv_b2b_id).innerHTML = xmlhttp.responseText;
						} else {
							document.getElementById("td_cal_del" + inv_b2b_id).innerHTML = xmlhttp.responseText;
						}
					}
				}

				xmlhttp.open("GET", "uber_freight_matching_tool_v3.php?inv_b2b_id=" + inv_b2b_id + "&companyID=" + companyID + "&minfob=" + minfob, true);
				xmlhttp.send();
			}

			//End quote request matching tool for Gaylord
			//---------------------------------------------------------------------------
			//Display quote request shipping matching tool
			function display_request_shipping_tool(id, flg, viewflg, client_flg, boxid, load_all = 0) {

				var selectobject = document.getElementById("lightbox_req_shipping" + boxid);
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');
				document.getElementById('light_new_shipping').style.display = 'block';
				document.getElementById('light_new_shipping').style.left = n_left + 20 + 'px';
				document.getElementById('light_new_shipping').style.top = n_top + 20 + 'px';
				document.getElementById('light_new_shipping').style.height = 580 + 'px';

				document.getElementById("light_new_shipping").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

				var g_timing = 1;

				var sstr = "";
				sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
				sstr = sstr + "<tr align='center'>";
				sstr = sstr + "<td class='display_maintitle'>";
				sstr = sstr + "SHIPPING BOX MATCHING TOOL";
				sstr = sstr + "&nbsp;&nbsp;";
				sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_shipping').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
				sstr = sstr + "Timing&nbsp;&nbsp;";
				sstr = sstr + "<select class='basic_style' name='g_timing_shipping' id='g_timing_shipping' onChange='display_request_shipping_child(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (flg == 1) {
					sstr = sstr + " selected ";
					g_timing = 1;
				}
				sstr = sstr + ">Rdy Now + Presell</option><option value='3'";

				if (flg == 3) {
					sstr = sstr + "selected";
					g_timing = 3;
				}
				sstr = sstr + ">Rdy < 3mo </option><option value='2'";
				if (flg == 2) {
					sstr = sstr + "selected";
					g_timing = 2;
				}

				sstr = sstr + ">FTL Rdy Now ONLY</option>";
				sstr = sstr + "</select>";
				sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";
				//sstr = sstr + "<br>";
				//if (flg == 0) {

				sstr = sstr + "<select class='basic_style' name='sort_g_tool_shipping' id='sort_g_tool_shipping' onChange='display_request_shipping_child(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (flg == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Available to Sell</option><option value='2'";
				if (flg == 2) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Available to Sell + Potential to Get</option></select>";
				//
				sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";
				//sstr = sstr + "<br>";
				//if (flg == 0) {

				sstr = sstr + "<select class='basic_style' name='sort_g_tool_shipping2' id='sort_g_tool_shipping2' onChange='display_request_shipping_child(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (flg == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Matching</option><option value='2'";
				if (flg == 2) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">All Items (Ignore Criteria)</option></select>";

				sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

				sstr = sstr + "View&nbsp;&nbsp;";

				//if(client_flg!=1)
				//{
				sstr = sstr + "<select class='basic_style' name='sort_g_view_shipping' id='sort_g_view_shipping' onChange='display_request_shipping_child(" + id + "," + flg + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";
				if (viewflg == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">UCB View</option><option value='2'";
				if (viewflg == 2) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Customer Facing View</option></select>";
				//}
				/*else{
					sstr = sstr + "<select class='basic_style' name='sort_g_view_shipping' id='sort_g_view_shipping' onChange='display_request_shipping_child(" + id + "," + flg + "," + boxid + "," + this.value + ","+ client_flg + "," + n_left + "," + n_top + ")'><option value='2'";
					if(viewflg==1){
					   sstr = sstr + " selected ";
					} 
					sstr = sstr +">Customer Facing View</option></select>";
				}*/


				sstr = sstr + "</td>";
				sstr = sstr + "</tr>";
				sstr = sstr + "</table>";

				var sstr_load_all = "";
				sstr_load_all = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
				sstr_load_all = sstr_load_all + "<tr align='center'>";
				sstr_load_all = sstr_load_all + "<td class='display_maintitle'>";
				sstr_load_all = sstr_load_all + "SHIPPING BOX MATCHING TOOL";
				sstr_load_all = sstr_load_all + "&nbsp;&nbsp;";
				sstr_load_all = sstr_load_all + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_shipping').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";

				sstr_load_all = sstr_load_all + "</td>";
				sstr_load_all = sstr_load_all + "</tr>";
				sstr_load_all = sstr_load_all + "</table>";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						if (load_all == 0) {
							document.getElementById("light_new_shipping").innerHTML = sstr + xmlhttp.responseText;
						} else {
							document.getElementById("light_new_shipping").innerHTML = sstr_load_all + xmlhttp.responseText;
						}

					}
				}

				xmlhttp.open("GET", "quote_request_shipping_tool_new.php?ID=" + id + "&gbox=" + boxid + "&display-allrec=" + flg + "&display_view=" + viewflg + "&sort_g_tool2=" + flg + "&load_all=" + load_all + "&client_flg=" + client_flg + "&g_timing=" + g_timing, true);
				xmlhttp.send();
			}

			function display_request_shipping_child(id, flg, boxid, viewflg, client_flg, n_left, n_top) {
				var flgs = document.getElementById("sort_g_tool_shipping").value;
				var flgs_org = document.getElementById("sort_g_tool_shipping").value;
				var viewflgs = document.getElementById("sort_g_view_shipping").value;

				var g_timing_shipping = document.getElementById("g_timing_shipping").value;
				var sort_g_tool_shipping2 = document.getElementById("sort_g_tool_shipping2").value;

				var selectobject = document.getElementById("lightbox_req_shipping" + boxid);
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');

				var sstr = "";
				sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
				sstr = sstr + "<tr align='center'>";
				sstr = sstr + "<td class='display_maintitle'>";
				sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' >SHIPPING BOX MATCHING TOOL</font>";
				sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;";
				sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_shipping').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br><font face='Arial, Helvetica, sans-serif' size='1'>";
				//
				if (flgs == 1) {
					sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
				}
				if (flgs == 2) {
					sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!', <br>'Unavailable (New Lead', 'Unavailable (Qualified, In Process)', <br>'Unavailable (Qualified, No Customers)' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
				}

				sstr = sstr + "<br>";

				//new code		  
				sstr = sstr + "Timing&nbsp;&nbsp;";
				sstr = sstr + "<select class='basic_style' name='g_timing_shipping' id='g_timing_shipping' onChange='display_request_shipping_child(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (g_timing_shipping == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Rdy Now + Presell</option><option value='3'";

				if (g_timing_shipping == 3) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Rdy < 3mo </option><option value='2'";
				if (g_timing_shipping == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">FTL Rdy Now ONLY</option>";
				sstr = sstr + "</select>";
				sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";
				//sstr = sstr + "<br>";
				//if (flg == 0) {

				sstr = sstr + "<select class='basic_style' name='sort_g_tool_shipping' id='sort_g_tool_shipping' onChange='display_request_shipping_child(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (flgs_org == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Available to Sell</option><option value='2'";
				if (flgs_org == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Available to Sell + Potential to Get</option></select>";
				//
				sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";
				//sstr = sstr + "<br>";
				//if (flg == 0) {

				sstr = sstr + "<select class='basic_style' name='sort_g_tool_shipping2' id='sort_g_tool_shipping2' onChange='display_request_shipping_child(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (sort_g_tool_shipping2 == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Matching</option><option value='2'";
				if (sort_g_tool_shipping2 == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">All Items (Ignore Criteria)</option></select>";

				sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

				sstr = sstr + "View&nbsp;&nbsp;";

				//if(client_flg!=1)
				//{
				sstr = sstr + "<select class='basic_style' name='sort_g_view_shipping' id='sort_g_view_shipping' onChange='display_request_shipping_child(" + id + "," + flgs + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";
				if (viewflgs == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">UCB View</option><option value='2'";
				if (viewflgs == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Customer Facing View</option></select>";
				/*}
				else{
					sstr = sstr + "<select class='basic_style' name='sort_g_view_shipping' id='sort_g_view_shipping' onChange='display_request_shipping_child(" + id + "," + flgs + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='2'";
					
					if(viewflgs==2){
					   sstr = sstr +"selected";
					}  
					sstr = sstr +">Customer Facing View</option></select>";
				}*/


				sstr = sstr + "</td>";
				sstr = sstr + "</tr>";
				sstr = sstr + "</table>";

				//New code
				var selectobject = document.getElementById("lightbox");
				document.getElementById('light_new_shipping').style.display = 'block';
				document.getElementById('light_new_shipping').style.left = n_left + 20 + 'px';
				document.getElementById('light_new_shipping').style.top = n_top + 20 + 'px';

				document.getElementById("light_new_shipping").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("light_new_shipping").innerHTML = sstr + xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "quote_request_shipping_tool_new.php?ID=" + id + "&gbox=" + boxid + "&display-allrec=" + flgs + "&display_view=" + viewflgs + "&g_timing=" + g_timing_shipping + "&sort_g_tool2=" + sort_g_tool_shipping2 + "&client_flg=" + client_flg, true);
				xmlhttp.send();
			}



			// TEST SHIPPING SECTION START 	
			function display_request_shipping_tool_test(id, flg, viewflg, client_flg, boxid, load_all = 0, onlyftl = 0) {
				var selectobject = document.getElementById("lightbox_req_shipping" + boxid);
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');
				document.getElementById('light_new_shipping').style.display = 'block';
				document.getElementById('light_new_shipping').style.left = n_left + 20 + 'px';
				document.getElementById('light_new_shipping').style.top = n_top + 20 + 'px';
				document.getElementById('light_new_shipping').style.height = 580 + 'px';

				document.getElementById("light_new_shipping").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

				var gtiming = 0;
				var sstr = "";
				sstr = "<table width='100%' border='0' cellspacing='0' cellpadding='2' class='basic_style'>";
				sstr = sstr + "<tr align='center'>";
				sstr = sstr + "<td class='display_maintitle' colspan='6'>";
				sstr = sstr + "SHIPPING BOX MATCHING TOOL";
				sstr = sstr + "&nbsp;&nbsp;";
				sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_shipping').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
				sstr = sstr + "</td></tr><tr><td class='display_maintitle'>&nbsp;&nbsp;&nbsp;Timing<br>";
				sstr = sstr + "<select class='basic_style' name='g_timing_shipping' id='g_timing_shipping' onChange='display_request_shipping_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (flg == 1 || boxid == 0) {
					gtiming = 1;
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Rdy Now + Presell</option><option value='3'";

				if (flg == 3) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Rdy < 3mo </option><option value='2'";

				if (flg == 2 && boxid != 0) {
					sstr = sstr + "selected";
				}
				if (onlyftl == 1) {
					gtiming = 2;
					sstr = sstr + "selected";
				}
				sstr = sstr + ">FTL Rdy Now ONLY</option>";
				sstr = sstr + "</select>";
				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "&nbsp;Status&nbsp;<br>";
				sstr = sstr + "<select class='basic_style' name='sort_g_tool_shipping' id='sort_g_tool_shipping' onChange='display_request_shipping_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (flg == 1 || boxid == 0) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Available to Sell</option><option value='2'";
				if (flg == 2 && boxid != 0) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Available to Sell + Potential to Get</option></select>";

				sstr = sstr + "</td><td class='display_maintitle'>";
				sstr = sstr + "&nbsp;Criteria&nbsp;<br>";
				sstr = sstr + "<select class='basic_style' name='sort_g_tool_shipping2' id='sort_g_tool_shipping2' onChange='display_request_shipping_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (flg == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Matching</option><option value='2'";
				if (flg == 2) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">All Items (Ignore Criteria)</option></select>";

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "&nbsp;View&nbsp;<br>";

				//if client dash
				if (client_flg != 1) {
					sstr = sstr + "<select class='basic_style' name='sort_g_view_shipping' id='sort_g_view_shipping' onChange='display_request_shipping_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";
					if (viewflg == 1) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">UCB View</option><option value='2'";
					if (viewflg == 2) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				} else {
					sstr = sstr + "<select class='basic_style' name='sort_g_view_shipping' id='sort_g_view_shipping' onChange='display_request_shipping_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='2'";
					if (viewflg == 2) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">Customer Facing View</option></select>";

				}

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "&nbsp;Combine Item View <br>";
				sstr = sstr + "<select class='basic_style' name='sort_g_location_shipping' id= 'sort_g_location_shipping' onChange='display_request_shipping_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")' > ";
				sstr = sstr + "<option value='1'>By Item</option>";
				sstr = sstr + "<option value='2'>By Location</option>";
				sstr = sstr + " </select>";

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "<input type='checkbox' name='canship_ltl' id='canship_ltl' onChange='display_request_shipping_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'>";
				sstr = sstr + "&nbsp;&nbsp;Can Ship LTL Only <br>";

				sstr = sstr + "<input type='checkbox' name='customer_pickup_allowed' id='customer_pickup_allowed' onChange='display_request_shipping_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'>";
				sstr = sstr + "&nbsp;&nbsp;Customer Pickups Allowed Only";

				sstr = sstr + "</td>";
				sstr = sstr + "</tr>";
				sstr = sstr + "</table>";

				var sstr_load_all = "";
				sstr_load_all = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
				sstr_load_all = sstr_load_all + "<tr align='center'>";
				sstr_load_all = sstr_load_all + "<td class='display_maintitle'>";
				sstr_load_all = sstr_load_all + "SHIPPING BOX MATCHING TOOL";
				sstr_load_all = sstr_load_all + "&nbsp;&nbsp;";
				sstr_load_all = sstr_load_all + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_shipping').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";

				sstr_load_all = sstr_load_all + "</td>";
				sstr_load_all = sstr_load_all + "</tr>";
				sstr_load_all = sstr_load_all + "</table>";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() { //alert('res -> '+xmlhttp.responseText);
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						if (load_all == 0) {
							document.getElementById("light_new_shipping").innerHTML = sstr + xmlhttp.responseText;
						} else {
							document.getElementById("light_new_shipping").innerHTML = sstr_load_all + xmlhttp.responseText;
						}
					}
				}

				xmlhttp.open("GET", "quote_request_shipping_tool_v2.php?ID=" + id + "&gbox=" + boxid + "&g_timing=" + gtiming + "&onlyftl=" + onlyftl + "&display-allrec=" + flg + "&display_view=" + viewflg + "&sort_g_tool2=" + flg + "&load_all=" + load_all + "&client_flg=" + client_flg, true);
				xmlhttp.send();
			}

			function display_request_shipping_child_test(id, flg, boxid, viewflg, client_flg, n_left, n_top) {
				var flgs = document.getElementById("sort_g_tool_shipping").value;
				var flgs_org = document.getElementById("sort_g_tool_shipping").value;
				var viewflgs = document.getElementById("sort_g_view_shipping").value;

				var g_timing_shipping = document.getElementById("g_timing_shipping").value;
				var sort_g_tool_shipping2 = document.getElementById("sort_g_tool_shipping2").value;
				var sort_g_location_shipping = document.getElementById("sort_g_location_shipping").value;

				if (document.getElementById("canship_ltl").checked) {
					var canship_ltl = 1;
				} else {
					var canship_ltl = 0;
				}

				if (document.getElementById("customer_pickup_allowed").checked) {
					var customer_pickup = 1;
				} else {
					var customer_pickup = 0;
				}
				//alert('sort_g_location -> ' + sort_g_location);	
				var selectobject = document.getElementById("lightbox_req_shipping" + boxid);
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');

				var sstr = "";
				sstr = "<table width='100%' border='0' cellspacing='0' cellpadding='2' class='basic_style'>";
				sstr = sstr + "<tr align='center'>";
				sstr = sstr + "<td class='display_maintitle' colspan='6'>";
				sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' >SHIPPING BOX MATCHING TOOL</font>";
				sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;";
				sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_shipping').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br><font face='Arial, Helvetica, sans-serif' size='1'>";

				if (flgs == 1) {
					sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
				}
				if (flgs == 2) {
					sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!', <br>'Unavailable (New Lead', 'Unavailable (Qualified, In Process)', <br>'Unavailable (Qualified, No Customers)' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
				}
				//sstr = sstr + "<br>";

				sstr = sstr + "</td></tr><tr><td class='display_maintitle'>";
				sstr = sstr + "Timing&nbsp;<br>";
				sstr = sstr + "<select class='basic_style' name='g_timing_shipping' id='g_timing_shipping' onChange='display_request_shipping_child_test(" + id + "," + this.value + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (g_timing_shipping == 1) {
					sstr = sstr + " selected ";
				}

				sstr = sstr + ">Rdy Now + Presell</option><option value='3'";

				if (g_timing_shipping == 3) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Rdy < 3mo </option><option value='2'";
				if (g_timing_shipping == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">FTL Rdy Now ONLY</option>";
				sstr = sstr + "</select>";

				sstr = sstr + "</td><td class='display_maintitle'>";
				sstr = sstr + "Status&nbsp;<br>";

				sstr = sstr + "<select class='basic_style' name='sort_g_tool_shipping' id='sort_g_tool_shipping' onChange='display_request_shipping_child_test(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (flgs_org == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Available to Sell</option><option value='2'";
				if (flgs_org == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Available to Sell + Potential to Get</option></select>";

				sstr = sstr + "</td><td class='display_maintitle'>";
				sstr = sstr + "Criteria&nbsp;<br>";

				sstr = sstr + "<select class='basic_style' name='sort_g_tool_shipping2' id='sort_g_tool_shipping2' onChange='display_request_shipping_child_test(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (sort_g_tool_shipping2 == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Matching</option><option value='2'";
				if (sort_g_tool_shipping2 == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">All Items (Ignore Criteria)</option></select>";

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "View&nbsp;<br>";

				if (client_flg != 1) {
					sstr = sstr + "<select class='basic_style' name='sort_g_view_shipping' id='sort_g_view_shipping' onChange='display_request_shipping_child_test(" + id + "," + flgs + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";
					if (viewflgs == 1) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">UCB View</option><option value='2'";
					if (viewflgs == 2) {
						sstr = sstr + "selected";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				} else {
					sstr = sstr + "<select class='basic_style' name='sort_g_view_shipping' id='sort_g_view_shipping' onChange='display_request_shipping_child_test(" + id + "," + flgs + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='2'";

					if (viewflgs == 2) {
						sstr = sstr + "selected";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				}

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "Combine Item View&nbsp;<br>";
				sstr = sstr + "<select class='basic_style' name='sort_g_location_shipping' id= 'sort_g_location_shipping' onChange='display_request_shipping_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")' > ";
				sstr = sstr + "<option value='1'";
				if (sort_g_location_shipping == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">By Item</option>";
				sstr = sstr + "<option value='2'";
				if (sort_g_location_shipping == 2) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">By Location</option>";
				sstr = sstr + " </select>";

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "<input type='checkbox' name='canship_ltl' id='canship_ltl' onChange='display_request_shipping_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'";

				if (canship_ltl == 1) {
					sstr = sstr + " checked ";
				}
				sstr = sstr + ">";
				sstr = sstr + "&nbsp;&nbsp;Can Ship LTL Only <br>";

				sstr = sstr + "<input type='checkbox' name='customer_pickup_allowed' id='customer_pickup_allowed' onChange='display_request_shipping_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")' ";
				if (customer_pickup == 1) {
					sstr = sstr + " checked ";
				}
				sstr = sstr + ">";
				sstr = sstr + "&nbsp;&nbsp;Customer Pickups Allowed Only";

				sstr = sstr + "</td>";
				sstr = sstr + "</tr>";
				sstr = sstr + "</table>";

				var selectobject = document.getElementById("lightbox");
				document.getElementById('light_new_shipping').style.display = 'block';
				document.getElementById('light_new_shipping').style.left = n_left + 20 + 'px';
				document.getElementById('light_new_shipping').style.top = n_top + 20 + 'px';

				document.getElementById("light_new_shipping").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("light_new_shipping").innerHTML = sstr + xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "quote_request_shipping_tool_v2.php?ID=" + id + "&gbox=" + boxid + "&display-allrec=" + flgs + "&display_view=" + viewflgs + "&g_timing=" + g_timing_shipping + "&sort_g_tool2=" + sort_g_tool_shipping2 + "&client_flg=" + client_flg + "&sort_g_location_shipping=" + sort_g_location_shipping + "&canship_ltl=" + canship_ltl + "&customer_pickup=" + customer_pickup, true);
				xmlhttp.send();
			}
			// TEST SHIPPING SECTION ENDS 




			//end quote request shipping matching tool
			//
			//------------------------------------------------------------------
			//Supersack Matching tool
			//Display quote request supersacks matching tool
			function display_request_supersacks_tool(id, flg, viewflg, client_flg, boxid, load_all = 0) {

				var selectobject = document.getElementById("lightbox_req_supersacks" + boxid);
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');
				document.getElementById('light_new_supersacks').style.display = 'block';
				document.getElementById('light_new_supersacks').style.left = n_left + 20 + 'px';
				document.getElementById('light_new_supersacks').style.top = n_top + 20 + 'px';
				document.getElementById('light_new_supersacks').style.height = 580 + 'px';

				document.getElementById("light_new_supersacks").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

				var sstr = "";
				sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
				sstr = sstr + "<tr align='center'>";
				sstr = sstr + "<td class='display_maintitle'>";
				sstr = sstr + "SUPERSACK MATCHING TOOL";
				sstr = sstr + "&nbsp;&nbsp;";
				sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_supersacks').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
				sstr = sstr + "Timing&nbsp;&nbsp;";
				sstr = sstr + "<select class='basic_style' name='g_timing_supersack' id='g_timing_supersack' onChange='display_request_supersack_child(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (flg == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Rdy Now + Presell</option><option value='3'";
				if (flg == 3) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Rdy < 3mo </option><option value='2'";
				if (flg == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">FTL Rdy Now ONLY</option>";
				sstr = sstr + "</select>";
				sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";
				//sstr = sstr + "<br>";
				//if (flg == 0) {

				sstr = sstr + "<select class='basic_style' name='sort_g_tool_supersack' id='sort_g_tool_supersack' onChange='display_request_supersack_child(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (flg == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Available to Sell</option><option value='2'";
				if (flg == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Available to Sell + Potential to Get</option></select>";
				//
				sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";
				//sstr = sstr + "<br>";
				//if (flg == 0) {

				sstr = sstr + "<select class='basic_style' name='sort_g_tool_supersack2' id='sort_g_tool_supersack2' onChange='display_request_supersack_child(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (flg == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Matching</option><option value='2'";
				if (flg == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">All Items (Ignore Criteria)</option></select>";

				sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

				sstr = sstr + "View&nbsp;&nbsp;";

				sstr = sstr + "<select class='basic_style' name='sort_g_view_supersack' id='sort_g_view_supersack' onChange='display_request_supersack_child(" + id + "," + flg + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";
				if (viewflg == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">UCB View</option><option value='2'";
				if (viewflg == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Customer Facing View</option></select>";

				sstr = sstr + "</td>";
				sstr = sstr + "</tr>";
				sstr = sstr + "</table>";

				var sstr_load_all = "";
				sstr_load_all = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
				sstr_load_all = sstr_load_all + "<tr align='center'>";
				sstr_load_all = sstr_load_all + "<td class='display_maintitle'>";
				sstr_load_all = sstr_load_all + "SUPERSACK MATCHING TOOL";
				sstr_load_all = sstr_load_all + "&nbsp;&nbsp;";
				sstr_load_all = sstr_load_all + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_supersacks').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";

				sstr_load_all = sstr_load_all + "</td>";
				sstr_load_all = sstr_load_all + "</tr>";
				sstr_load_all = sstr_load_all + "</table>";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						if (load_all == 0) {
							document.getElementById("light_new_supersacks").innerHTML = sstr + xmlhttp.responseText;
						} else {
							document.getElementById("light_new_supersacks").innerHTML = sstr_load_all + xmlhttp.responseText;
						}
					}
				}

				xmlhttp.open("GET", "quote_req_supersacks_matching_new.php?ID=" + id + "&gbox=" + boxid + "&display-allrec=" + flg + "&display_view=" + viewflg + "&display_view=" + viewflg + "&load_all=" + load_all, true);
				xmlhttp.send();
			}

			function display_request_supersack_child(id, flg, boxid, viewflg, client_flg, n_left, n_top) {
				var flgs = document.getElementById("sort_g_tool_supersack").value;
				var flgs_org = document.getElementById("sort_g_tool_supersack").value;
				var viewflgs = document.getElementById("sort_g_view_supersack").value;
				//alert(boxid);
				//

				var g_timing_supersack = document.getElementById("g_timing_supersack").value;
				var sort_g_tool_supersack2 = document.getElementById("sort_g_tool_supersack2").value;

				var selectobject = document.getElementById("lightbox_req_supersacks" + boxid);
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');

				var sstr = "";
				sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
				sstr = sstr + "<tr align='center'>";
				sstr = sstr + "<td class='display_maintitle'>";
				sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' >SUPERSACK MATCHING TOOL</font>";
				sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;";
				sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_supersacks').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br><font face='Arial, Helvetica, sans-serif' size='1'>";
				//
				if (flgs == 1) {
					sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
				}
				if (flgs == 2) {
					sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!', <br>'Unavailable (New Lead', 'Unavailable (Qualified, In Process)', <br>'Unavailable (Qualified, No Customers)' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
				}

				/* if(sort_g_tool_supersack2 == 1){
            sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display all boxes with status 'Available', <br>'Available, but Need Approval to Sell', <br>'Available, Currently Recycling, Sell w/ Lead Time!' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
        }
		if(sort_g_tool_supersack2 == 2){
            sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display all boxes with status 'Available', <br>'Available, but Need Approval to Sell', <br>'Available, Currently Recycling, Sell w/ Lead Time!', <br>'Unavailable (New Lead', 'Unavailable (Qualified, In Process)', <br>'Unavailable (Qualified, No Customers)' <br>and UCB Owned inventory &nbsp;&nbsp;</font></span></div>";
        }*/
				//
				sstr = sstr + "<br>";
				//if (flg == 0) {
				//  alert(flgs);

				//new code		  
				sstr = sstr + "Timing&nbsp;&nbsp;";
				sstr = sstr + "<select class='basic_style' name='g_timing_supersack' id='g_timing_supersack' onChange='display_request_supersack_child(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (g_timing_supersack == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Rdy Now + Presell</option><option value='3'";
				if (g_timing_supersack == 3) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Rdy < 3mo </option><option value='2'";
				if (g_timing_supersack == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">FTL Rdy Now ONLY</option>";
				sstr = sstr + "</select>";
				sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";
				//sstr = sstr + "<br>";
				//if (flg == 0) {

				sstr = sstr + "<select class='basic_style' name='sort_g_tool_supersack' id='sort_g_tool_supersack' onChange='display_request_supersack_child(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (flgs_org == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Available to Sell</option><option value='2'";
				if (flgs_org == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Available to Sell + Potential to Get</option></select>";
				//
				sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";
				//sstr = sstr + "<br>";
				//if (flg == 0) {

				sstr = sstr + "<select class='basic_style' name='sort_g_tool_supersack2' id='sort_g_tool_supersack2' onChange='display_request_supersack_child(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (sort_g_tool_supersack2 == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Matching</option><option value='2'";
				if (sort_g_tool_supersack2 == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">All Items (Ignore Criteria)</option></select>";

				sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

				sstr = sstr + "View&nbsp;&nbsp;";

				if (client_flg != 1) {
					sstr = sstr + "<select class='basic_style' name='sort_g_view_supersack' id='sort_g_view_supersack' onChange='display_request_supersack_child(" + id + "," + flgs + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";
					if (viewflgs == 1) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">UCB View</option><option value='2'";
					if (viewflgs == 2) {
						sstr = sstr + "selected";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				} else {
					sstr = sstr + "<select class='basic_style' name='sort_g_view_supersack' id='sort_g_view_supersack' onChange='display_request_supersack_child(" + id + "," + flgs + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='2'";

					if (viewflgs == 2) {
						sstr = sstr + "selected";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				}

				sstr = sstr + "</td>";
				sstr = sstr + "</tr>";
				sstr = sstr + "</table>";

				var selectobject = document.getElementById("lightbox");
				//var n_left = f_getPosition(selectobject, 'Left');
				//var n_top  = f_getPosition(selectobject, 'Top');
				document.getElementById('light_new_supersacks').style.display = 'block';
				document.getElementById('light_new_supersacks').style.left = n_left + 20 + 'px';
				document.getElementById('light_new_supersacks').style.top = n_top + 20 + 'px';

				document.getElementById("light_new_supersacks").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("light_new_supersacks").innerHTML = sstr + xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "quote_req_supersacks_matching_new.php?ID=" + id + "&gbox=" + boxid + "&display-allrec=" + flgs + "&display_view=" + viewflgs + "&g_timing=" + g_timing_supersack + "&sort_g_tool2=" + sort_g_tool_supersack2 + "&client_flg=" + client_flg, true);
				xmlhttp.send();
			}

			// TEST SUPERSACKS SECTION START	
			function display_request_supersacks_tool_test(id, flg, viewflg, client_flg, boxid, load_all = 0, onlyftl = 0) {
				var selectobject = document.getElementById("lightbox_req_supersacks" + boxid);
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');
				document.getElementById('light_new_supersacks').style.display = 'block';
				document.getElementById('light_new_supersacks').style.left = n_left + 20 + 'px';
				document.getElementById('light_new_supersacks').style.top = n_top + 20 + 'px';
				document.getElementById('light_new_supersacks').style.height = 580 + 'px';

				document.getElementById("light_new_supersacks").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

				var gtiming = 0;
				var sstr = "";
				sstr = "<table width='100%' border='0' cellspacing='0' cellpadding='2' class='basic_style'>";
				sstr = sstr + "<tr align='center'>";
				sstr = sstr + "<td class='display_maintitle' colspan='6'>";
				sstr = sstr + "SUPERSACK MATCHING TOOL";
				sstr = sstr + "&nbsp;&nbsp;";
				sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_supersacks').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
				sstr = sstr + "</td></tr><tr><td class='display_maintitle'>&nbsp;&nbsp;&nbsp;Timing<br>";
				sstr = sstr + "&nbsp;&nbsp;&nbsp;<select class='basic_style' name='g_timing_supersack' id='g_timing_supersack' onChange='display_request_supersack_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (flg == 1 || boxid == 0) {
					gtiming = 1;
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Rdy Now + Presell</option><option value='3'";

				if (flg == 3) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Rdy < 3mo </option><option value='2'";
				if (flg == 2 && boxid != 0) {
					sstr = sstr + "selected";
				}
				if (onlyftl == 1) {
					gtiming = 2;
					sstr = sstr + "selected";
				}
				sstr = sstr + ">FTL Rdy Now ONLY</option>";
				sstr = sstr + "</select>";
				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "&nbsp;Status&nbsp;<br>";
				sstr = sstr + "<select class='basic_style' name='sort_g_tool_supersack' id='sort_g_tool_supersack' onChange='display_request_supersack_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (flg == 1 || boxid == 0) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Available to Sell</option><option value='2'";
				if (flg == 2 && boxid != 0) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Available to Sell + Potential to Get</option></select>";

				sstr = sstr + "</td><td class='display_maintitle'>";
				sstr = sstr + "&nbsp;Criteria&nbsp;<br>";
				sstr = sstr + "<select class='basic_style' name='sort_g_tool_supersack2' id='sort_g_tool_supersack2' onChange='display_request_supersack_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (flg == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Matching</option><option value='2'";
				if (flg == 2) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">All Items (Ignore Criteria)</option></select>";

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "&nbsp;View&nbsp;<br>";

				//if client dash
				if (client_flg != 1) {
					sstr = sstr + "<select class='basic_style' name='sort_g_view_supersack' id='sort_g_view_supersack' onChange='display_request_supersack_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";
					if (viewflg == 1) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">UCB View</option><option value='2'";
					if (viewflg == 2) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				} else {
					sstr = sstr + "<select class='basic_style' name='sort_g_view_supersack' id='sort_g_view_supersack' onChange='display_request_supersack_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='2'";
					if (viewflg == 2) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">Customer Facing View</option></select>";

				}

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "&nbsp;Combine Item View <br>";
				sstr = sstr + "<select class='basic_style' name='sort_g_location_supersack' id= 'sort_g_location_supersack' onChange='display_request_supersack_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")' > ";
				sstr = sstr + "<option value='1'>By Item</option>";
				sstr = sstr + "<option value='2'>By Location</option>";
				sstr = sstr + " </select>";

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "<input type='checkbox' name='canship_ltl' id='canship_ltl' onChange='display_request_supersack_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'>";
				sstr = sstr + "&nbsp;&nbsp;Can Ship LTL Only <br>";

				sstr = sstr + "<input type='checkbox' name='customer_pickup_allowed' id='customer_pickup_allowed' onChange='display_request_supersack_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'>";
				sstr = sstr + "&nbsp;&nbsp;Customer Pickups Allowed Only";

				sstr = sstr + "</td>";
				sstr = sstr + "</tr>";
				sstr = sstr + "</table>";

				var sstr_load_all = "";
				sstr_load_all = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
				sstr_load_all = sstr_load_all + "<tr align='center'>";
				sstr_load_all = sstr_load_all + "<td class='display_maintitle'>";
				sstr_load_all = sstr_load_all + "SUPERSACK MATCHING TOOL";
				sstr_load_all = sstr_load_all + "&nbsp;&nbsp;";
				sstr_load_all = sstr_load_all + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_supersacks').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";

				sstr_load_all = sstr_load_all + "</td>";
				sstr_load_all = sstr_load_all + "</tr>";
				sstr_load_all = sstr_load_all + "</table>";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() { //alert('res -> '+xmlhttp.responseText);
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						if (load_all == 0) {
							document.getElementById("light_new_supersacks").innerHTML = sstr + xmlhttp.responseText;
						} else {
							document.getElementById("light_new_supersacks").innerHTML = sstr_load_all + xmlhttp.responseText;
						}
					}
				}

				xmlhttp.open("GET", "quote_request_supersack_tool_v2.php?ID=" + id + "&gbox=" + boxid + "&g_timing_supersack=" + gtiming + "&onlyftl=" + onlyftl + "&display-allrec=" + flg + "&display_view=" + viewflg + "&sort_g_tool2=" + flg + "&load_all=" + load_all + "&client_flg=" + client_flg, true);
				xmlhttp.send();
			}

			function display_request_supersack_child_test(id, flg, boxid, viewflg, client_flg, n_left, n_top) {
				var flgs = document.getElementById("sort_g_tool_supersack").value;
				var flgs_org = document.getElementById("sort_g_tool_supersack").value;
				var viewflgs = document.getElementById("sort_g_view_supersack").value;

				var g_timing_supersack = document.getElementById("g_timing_supersack").value;
				var sort_g_tool_supersack2 = document.getElementById("sort_g_tool_supersack2").value;
				var sort_g_location_supersack = document.getElementById("sort_g_location_supersack").value;

				if (document.getElementById("canship_ltl").checked) {
					var canship_ltl = 1;
				} else {
					var canship_ltl = 0;
				}

				if (document.getElementById("customer_pickup_allowed").checked) {
					var customer_pickup = 1;
				} else {
					var customer_pickup = 0;
				}
				//alert('sort_g_location -> ' + sort_g_location);	
				var selectobject = document.getElementById("lightbox_req_supersacks" + boxid);
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');

				var sstr = "";
				sstr = "<table width='100%' border='0' cellspacing='0' cellpadding='2' class='basic_style'>";
				sstr = sstr + "<tr align='center'>";
				sstr = sstr + "<td class='display_maintitle' colspan='6'>";
				sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' >SUPERSACKS MATCHING TOOL</font>";
				sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;";
				sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_supersacks').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br><font face='Arial, Helvetica, sans-serif' size='1'>";

				if (flgs == 1) {
					sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
				}
				if (flgs == 2) {
					sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!', <br>'Unavailable (New Lead', 'Unavailable (Qualified, In Process)', <br>'Unavailable (Qualified, No Customers)' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
				}
				//sstr = sstr + "<br>";

				sstr = sstr + "</td></tr><tr><td class='display_maintitle'>";
				sstr = sstr + "Timing&nbsp;<br>";
				sstr = sstr + "<select class='basic_style' name='g_timing_supersack' id='g_timing_supersack' onChange='display_request_supersack_child_test(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (g_timing_supersack == 1) {
					sstr = sstr + " selected ";
				}

				sstr = sstr + ">Rdy Now + Presell</option><option value='3'";

				if (g_timing_supersack == 3) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Rdy < 3mo </option><option value='2'";
				if (g_timing_supersack == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">FTL Rdy Now ONLY</option>";
				sstr = sstr + "</select>";

				sstr = sstr + "</td><td class='display_maintitle'>";
				sstr = sstr + "Status&nbsp;<br>";

				sstr = sstr + "<select class='basic_style' name='sort_g_tool_supersack' id='sort_g_tool_supersack' onChange='display_request_supersack_child_test(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (flgs_org == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Available to Sell</option><option value='2'";
				if (flgs_org == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Available to Sell + Potential to Get</option></select>";

				sstr = sstr + "</td><td class='display_maintitle'>";
				sstr = sstr + "Criteria&nbsp;<br>";

				sstr = sstr + "<select class='basic_style' name='sort_g_tool_supersack2' id='sort_g_tool_supersack2' onChange='display_request_supersack_child_test(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (sort_g_tool_supersack2 == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Matching</option><option value='2'";
				if (sort_g_tool_supersack2 == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">All Items (Ignore Criteria)</option></select>";

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "View&nbsp;<br>";

				if (client_flg != 1) {
					sstr = sstr + "<select class='basic_style' name='sort_g_view_supersack' id='sort_g_view_supersack' onChange='display_request_supersack_child_test(" + id + "," + flgs + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";
					if (viewflgs == 1) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">UCB View</option><option value='2'";
					if (viewflgs == 2) {
						sstr = sstr + "selected";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				} else {
					sstr = sstr + "<select class='basic_style' name='sort_g_view_supersack' id='sort_g_view_supersack' onChange='display_request_supersack_child_test(" + id + "," + flgs + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='2'";

					if (viewflgs == 2) {
						sstr = sstr + "selected";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				}

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "Combine Item View&nbsp;<br>";
				sstr = sstr + "<select class='basic_style' name='sort_g_location_supersack' id= 'sort_g_location_supersack' onChange='display_request_supersack_child_test(" + id + "," + flgs + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")' > ";
				sstr = sstr + "<option value='1'";
				if (sort_g_location_supersack == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">By Item</option>";
				sstr = sstr + "<option value='2'";
				if (sort_g_location_supersack == 2) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">By Location</option>";
				sstr = sstr + " </select>";

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "<input type='checkbox' name='canship_ltl' id='canship_ltl' onChange='display_request_supersack_child_test(" + id + "," + flgs + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'";

				if (canship_ltl == 1) {
					sstr = sstr + " checked ";
				}
				sstr = sstr + ">";
				sstr = sstr + "&nbsp;&nbsp;Can Ship LTL Only <br>";

				sstr = sstr + "<input type='checkbox' name='customer_pickup_allowed' id='customer_pickup_allowed' onChange='display_request_supersack_child_test(" + id + "," + flgs + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")' ";
				if (customer_pickup == 1) {
					sstr = sstr + " checked ";
				}
				sstr = sstr + ">";
				sstr = sstr + "&nbsp;&nbsp;Customer Pickups Allowed Only";

				sstr = sstr + "</td>";
				sstr = sstr + "</tr>";
				sstr = sstr + "</table>";

				var selectobject = document.getElementById("lightbox");
				document.getElementById('light_new_supersacks').style.display = 'block';
				document.getElementById('light_new_supersacks').style.left = n_left + 20 + 'px';
				document.getElementById('light_new_supersacks').style.top = n_top + 20 + 'px';

				document.getElementById("light_new_supersacks").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("light_new_supersacks").innerHTML = sstr + xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "quote_request_supersack_tool_v2.php?ID=" + id + "&gbox=" + boxid + "&display-allrec=" + flgs + "&display_view=" + viewflgs + "&g_timing_supersack=" + g_timing_supersack + "&sort_g_tool2=" + sort_g_tool_supersack2 + "&client_flg=" + client_flg + "&sort_g_location_supersack=" + sort_g_location_supersack + "&canship_ltl=" + canship_ltl + "&customer_pickup=" + customer_pickup, true);
				xmlhttp.send();
			}

			// TEST SUPERSACKS SECTION ENDS
			//
			//Display quote request Pallet matching tool
			function display_request_Pallet_tool(id, flg, viewflg, client_flg, boxid, pallet_height = 0, pallet_width = 0, ctrlid = 0, load_all = 0) {
				if (boxid == 0) {
					var selectobject = document.getElementById("lightbox_req_pal" + ctrlid);
				} else {
					var selectobject = document.getElementById("lightbox_req_pal" + boxid);
				}
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');
				document.getElementById('light_new_pal').style.display = 'block';
				document.getElementById('light_new_pal').style.left = n_left + 20 + 'px';
				document.getElementById('light_new_pal').style.top = n_top + 20 + 'px';
				document.getElementById('light_new_pal').style.height = 580 + 'px';

				document.getElementById("light_new_pal").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

				var sstr = "";
				sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
				sstr = sstr + "<tr align='center'>";
				sstr = sstr + "<td class='display_maintitle'>";
				sstr = sstr + "PALLET MATCHING TOOL";
				sstr = sstr + "&nbsp;&nbsp;";
				sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_pal').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
				sstr = sstr + "Timing&nbsp;&nbsp;";
				sstr = sstr + "<select class='basic_style' name='g_timing_pallet' id='g_timing_pallet' onChange='display_request_pallet_child(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='1'";

				if (flg == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Rdy Now + Presell</option><option value='3'";
				if (flg == 3) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Rdy < 3mo </option><option value='2'";
				if (flg == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">FTL Rdy Now ONLY</option>";
				sstr = sstr + "</select>";
				sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";

				sstr = sstr + "<select class='basic_style' name='sort_g_tool_pallet' id='sort_g_tool_pallet' onChange='display_request_pallet_child(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='1'";

				if (flg == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Available to Sell</option><option value='2'";
				if (flg == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Available to Sell + Potential to Get</option></select>";

				sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";

				sstr = sstr + "<select class='basic_style' name='sort_g_tool_pallet2' id='sort_g_tool_pallet2' onChange='display_request_pallet_child(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='1'";

				if (flg == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Matching</option><option value='2'";
				if (flg == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">All Items (Ignore Criteria)</option></select>";

				sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

				sstr = sstr + "View&nbsp;&nbsp;";

				if (client_flg != 1) {
					sstr = sstr + "<select class='basic_style' name='sort_g_view_pallet' id='sort_g_view_pallet' onChange='display_request_pallet_child(" + id + "," + flg + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='1'";
					if (viewflg == 1) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">UCB View</option><option value='2'";
					if (viewflg == 2) {
						sstr = sstr + "selected";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				} else {
					sstr = sstr + "<select class='basic_style' name='sort_g_view_pallet' id='sort_g_view_pallet' onChange='display_request_pallet_child(" + id + "," + flg + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='2'";

					if (viewflg == 2) {
						sstr = sstr + "selected";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				}


				sstr = sstr + "</td>";
				sstr = sstr + "</tr>";
				sstr = sstr + "</table>";

				var sstr_load_all = "";
				sstr_load_all = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
				sstr_load_all = sstr_load_all + "<tr align='center'>";
				sstr_load_all = sstr_load_all + "<td class='display_maintitle'>";
				sstr_load_all = sstr_load_all + "PALLET MATCHING TOOL";
				sstr_load_all = sstr_load_all + "&nbsp;&nbsp;";
				sstr_load_all = sstr_load_all + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_pal').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";

				sstr_load_all = sstr_load_all + "</td>";
				sstr_load_all = sstr_load_all + "</tr>";
				sstr_load_all = sstr_load_all + "</table>";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						if (load_all == 0) {
							document.getElementById("light_new_pal").innerHTML = sstr + xmlhttp.responseText;
						} else {
							document.getElementById("light_new_pal").innerHTML = sstr_load_all + xmlhttp.responseText;
						}
					}
				}

				xmlhttp.open("GET", "quote_req_pallet_matching_new.php?ID=" + id + "&gbox=" + boxid + "&display-allrec=" + flg + "&display_view=" + viewflg + "&pallet_height=" + pallet_height + "&pallet_width=" + pallet_width + "&load_all=" + load_all, true);
				xmlhttp.send();
			}

			function display_request_pallet_child(id, flg, boxid, viewflg, client_flg, n_left, n_top, pallet_height = 0, pallet_width = 0, ctrlid = 0) {
				var flgs = document.getElementById("sort_g_tool_pallet").value;
				var flgs_org = document.getElementById("sort_g_tool_pallet").value;
				var viewflgs = document.getElementById("sort_g_view_pallet").value;
				//alert(flgs);
				//

				var g_timing_pallet = document.getElementById("g_timing_pallet").value;
				var sort_g_tool_pallet2 = document.getElementById("sort_g_tool_pallet2").value;

				if (boxid == 0) {
					var selectobject = document.getElementById("lightbox_req_pal" + ctrlid);
				} else {
					var selectobject = document.getElementById("lightbox_req_pal" + boxid);
				}

				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');

				var sstr = "";
				sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
				sstr = sstr + "<tr align='center'>";
				sstr = sstr + "<td class='display_maintitle'>";
				sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' >PALLET MATCHING TOOL</font>";
				sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;";
				sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_pal').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br><font face='Arial, Helvetica, sans-serif' size='1'>";
				//
				if (flgs == 1) {
					sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
				}
				if (flgs == 2) {
					sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!', <br>'Unavailable (New Lead', 'Unavailable (Qualified, In Process)', <br>'Unavailable (Qualified, No Customers)' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
				}
				sstr = sstr + "<br>";

				//new code		  
				sstr = sstr + "Timing&nbsp;&nbsp;";
				sstr = sstr + "<select class='basic_style' name='g_timing_pallet' id='g_timing_pallet' onChange='display_request_pallet_child(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='1'";

				if (g_timing_pallet == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Rdy Now + Presell</option><option value='3'";
				if (g_timing_pallet == 3) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Rdy < 3mo </option><option value='2'";
				if (g_timing_pallet == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">FTL Rdy Now ONLY</option>";
				sstr = sstr + "</select>";
				sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";
				//sstr = sstr + "<br>";
				//if (flg == 0) {

				sstr = sstr + "<select class='basic_style' name='sort_g_tool_pallet' id='sort_g_tool_pallet' onChange='display_request_pallet_child(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='1'";

				if (flgs_org == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Available to Sell</option><option value='2'";
				if (flgs_org == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Available to Sell + Potential to Get</option></select>";
				//
				sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";
				//sstr = sstr + "<br>";
				//if (flg == 0) {

				sstr = sstr + "<select class='basic_style' name='sort_g_tool_pallet2' id='sort_g_tool_pallet2' onChange='display_request_pallet_child(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='1'";

				if (sort_g_tool_pallet2 == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Matching</option><option value='2'";
				if (sort_g_tool_pallet2 == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">All Items (Ignore Criteria)</option></select>";

				sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

				sstr = sstr + "View&nbsp;&nbsp;";

				if (client_flg != 1) {
					sstr = sstr + "<select class='basic_style' name='sort_g_view_pallet' id='sort_g_view_pallet' onChange='display_request_pallet_child(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='1'";
					if (viewflgs == 1) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">UCB View</option><option value='2'";
					if (viewflgs == 2) {
						sstr = sstr + "selected";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				} else {
					sstr = sstr + "<select class='basic_style' name='sort_g_view_pallet' id='sort_g_view_pallet' onChange='display_request_pallet_child(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='2'";

					if (viewflgs == 2) {
						sstr = sstr + "selected";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				}

				sstr = sstr + "</td>";
				sstr = sstr + "</tr>";
				sstr = sstr + "</table>";

				var selectobject = document.getElementById("lightbox");
				//var n_left = f_getPosition(selectobject, 'Left');
				//var n_top  = f_getPosition(selectobject, 'Top');
				document.getElementById('light_new_pal').style.display = 'block';
				document.getElementById('light_new_pal').style.left = n_left + 20 + 'px';
				document.getElementById('light_new_pal').style.top = n_top + 20 + 'px';

				document.getElementById("light_new_pal").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("light_new_pal").innerHTML = sstr + xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "quote_req_pallet_matching_new.php?ID=" + id + "&gbox=" + boxid + "&display-allrec=" + flgs + "&display_view=" + viewflgs + "&g_timing=" + g_timing_pallet + "&sort_g_tool2=" + sort_g_tool_pallet2 + "&pallet_height=" + pallet_height + "&pallet_width=" + pallet_width, true);
				xmlhttp.send();
			}

			//TEST PALLET SECTION START 
			function display_request_Pallet_tool_test(id, flg, viewflg, client_flg, boxid, pallet_height = 0, pallet_width = 0, ctrlid = 0, load_all = 0, onlyftl = 0) {
				if (boxid == 0) {
					var selectobject = document.getElementById("lightbox_req_pal" + ctrlid);
				} else {
					var selectobject = document.getElementById("lightbox_req_pal" + boxid);
				}
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');
				document.getElementById('light_new_pal').style.display = 'block';
				document.getElementById('light_new_pal').style.left = n_left + 20 + 'px';
				document.getElementById('light_new_pal').style.top = n_top + 20 + 'px';
				document.getElementById('light_new_pal').style.height = 580 + 'px';

				document.getElementById("light_new_pal").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

				var gtiming = 0;
				var sstr = "";
				sstr = "<table width='100%' border='0' cellspacing='0' cellpadding='2' class='basic_style'>";
				sstr = sstr + "<tr align='center'>";
				sstr = sstr + "<td class='display_maintitle' colspan='6'>";
				sstr = sstr + "PALLET MATCHING TOOL";
				sstr = sstr + "&nbsp;&nbsp;";
				sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_pal').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
				sstr = sstr + "</td></tr><tr><td class='display_maintitle'>&nbsp;&nbsp;&nbsp;Timing<br>";
				sstr = sstr + "&nbsp;&nbsp;&nbsp;<select class='basic_style' name='g_timing_pallet' id='g_timing_pallet' onChange='display_request_pallet_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='1'";

				if (flg == 1 || boxid == 0) {
					gtiming = 1;
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Rdy Now + Presell</option><option value='3'";

				if (flg == 3) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Rdy < 3mo </option><option value='2'";

				if (flg == 2 && boxid != 0) {
					sstr = sstr + "selected";
				}
				if (onlyftl == 1) {
					gtiming = 2;
					sstr = sstr + "selected";
				}
				sstr = sstr + ">FTL Rdy Now ONLY</option>";
				sstr = sstr + "</select>";
				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "&nbsp;Status&nbsp;<br>";
				sstr = sstr + "<select class='basic_style' name='sort_g_tool_pallet' id='sort_g_tool_pallet' onChange='display_request_pallet_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='1'";

				if (flg == 1 || boxid == 0) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Available to Sell</option><option value='2'";
				if (flg == 2 && boxid != 0) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Available to Sell + Potential to Get</option></select>";

				sstr = sstr + "</td><td class='display_maintitle'>";
				sstr = sstr + "&nbsp;Criteria&nbsp;<br>";
				sstr = sstr + "<select class='basic_style' name='sort_g_tool_pallet2' id='sort_g_tool_pallet2' onChange='display_request_pallet_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='1'";

				if (flg == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Matching</option><option value='2'";
				if (flg == 2) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">All Items (Ignore Criteria)</option></select>";

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "&nbsp;View&nbsp;<br>";

				//if client dash
				if (client_flg != 1) {
					sstr = sstr + "<select class='basic_style' name='sort_g_view_pallet' id='sort_g_view_pallet' onChange='display_request_pallet_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='1'";
					if (viewflg == 1) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">UCB View</option><option value='2'";
					if (viewflg == 2) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				} else {
					sstr = sstr + "<select class='basic_style' name='sort_g_view_pallet' id='sort_g_view_pallet' onChange='display_request_pallet_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='2'";
					if (viewflg == 2) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">Customer Facing View</option></select>";

				}

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "&nbsp;Combine Item View <br>";
				sstr = sstr + "<select class='basic_style' name='sort_g_location_pallet' id= 'sort_g_location_pallet' onChange='display_request_pallet_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")' > ";
				sstr = sstr + "<option value='1'>By Item</option>";
				sstr = sstr + "<option value='2'>By Location</option>";
				sstr = sstr + " </select>";

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "<input type='checkbox' name='canship_ltl' id='canship_ltl' onChange='display_request_pallet_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'>";
				sstr = sstr + "&nbsp;&nbsp;Can Ship LTL Only <br>";

				sstr = sstr + "<input type='checkbox' name='customer_pickup_allowed' id='customer_pickup_allowed' onChange='display_request_pallet_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'>";
				sstr = sstr + "&nbsp;&nbsp;Customer Pickups Allowed Only";

				sstr = sstr + "</td>";
				sstr = sstr + "</tr>";
				sstr = sstr + "</table>";

				var sstr_load_all = "";
				sstr_load_all = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
				sstr_load_all = sstr_load_all + "<tr align='center'>";
				sstr_load_all = sstr_load_all + "<td class='display_maintitle'>";
				sstr_load_all = sstr_load_all + "PALLET MATCHING TOOL";
				sstr_load_all = sstr_load_all + "&nbsp;&nbsp;";
				sstr_load_all = sstr_load_all + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_pal').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";

				sstr_load_all = sstr_load_all + "</td>";
				sstr_load_all = sstr_load_all + "</tr>";
				sstr_load_all = sstr_load_all + "</table>";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() { //alert('res -> '+xmlhttp.responseText);
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						if (load_all == 0) {
							document.getElementById("light_new_pal").innerHTML = sstr + xmlhttp.responseText;
						} else {
							document.getElementById("light_new_pal").innerHTML = sstr_load_all + xmlhttp.responseText;
						}
					}
				}

				xmlhttp.open("GET", "quote_request_pallet_tool_v2.php?ID=" + id + "&gbox=" + boxid + "&g_timing=" + gtiming + "&onlyftl=" + onlyftl + "&g_timing_pallet=" + gtiming + "&display-allrec=" + flg + "&display_view=" + viewflg + "&sort_g_tool2=" + flg + "&pallet_height=" + pallet_height + "&pallet_width=" + pallet_width + "&load_all=" + load_all + "&client_flg=" + client_flg, true);
				xmlhttp.send();
			}

			function display_request_pallet_child_test(id, flg, boxid, viewflg, client_flg, n_left, n_top, pallet_height = 0, pallet_width = 0, ctrlid = 0, load_all = 0) {
				var flgs = document.getElementById("sort_g_tool_pallet").value;
				var flgs_org = document.getElementById("sort_g_tool_pallet").value;
				var viewflgs = document.getElementById("sort_g_view_pallet").value;

				var g_timing_pallet = document.getElementById("g_timing_pallet").value;
				var sort_g_tool_pallet2 = document.getElementById("sort_g_tool_pallet2").value;
				var sort_g_location_pallet = document.getElementById("sort_g_location_pallet").value;

				if (document.getElementById("canship_ltl").checked) {
					var canship_ltl = 1;
				} else {
					var canship_ltl = 0;
				}

				if (document.getElementById("customer_pickup_allowed").checked) {
					var customer_pickup = 1;
				} else {
					var customer_pickup = 0;
				}
				//alert('sort_g_location -> ' + sort_g_location);	
				if (boxid == 0) {
					var selectobject = document.getElementById("lightbox_req_pal" + ctrlid);
				} else {
					var selectobject = document.getElementById("lightbox_req_pal" + boxid);
				}
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');

				var sstr = "";
				sstr = "<table width='100%' border='0' cellspacing='0' cellpadding='2' class='basic_style'>";
				sstr = sstr + "<tr align='center'>";
				sstr = sstr + "<td class='display_maintitle' colspan='6'>";
				sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' >PALLET MATCHING TOOL</font>";
				sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;";
				sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_pal').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br><font face='Arial, Helvetica, sans-serif' size='1'>";

				if (flgs == 1) {
					sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
				}
				if (flgs == 2) {
					sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!', <br>'Unavailable (New Lead', 'Unavailable (Qualified, In Process)', <br>'Unavailable (Qualified, No Customers)' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
				}
				//sstr = sstr + "<br>";

				sstr = sstr + "</td></tr><tr><td class='display_maintitle'>";
				sstr = sstr + "Timing&nbsp;<br>";
				sstr = sstr + "<select class='basic_style' name='g_timing_pallet' id='g_timing_pallet' onChange='display_request_pallet_child_test(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='1'";

				if (g_timing_pallet == 1) {
					sstr = sstr + " selected ";
				}

				sstr = sstr + ">Rdy Now + Presell</option><option value='3'";

				if (g_timing_pallet == 3) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Rdy < 3mo </option><option value='2'";
				if (g_timing_pallet == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">FTL Rdy Now ONLY</option>";
				sstr = sstr + "</select>";

				sstr = sstr + "</td><td class='display_maintitle'>";
				sstr = sstr + "Status&nbsp;<br>";

				sstr = sstr + "<select class='basic_style' name='sort_g_tool_pallet' id='sort_g_tool_pallet' onChange='display_request_pallet_child_test(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='1'";

				if (flgs_org == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Available to Sell</option><option value='2'";
				if (flgs_org == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Available to Sell + Potential to Get</option></select>";

				sstr = sstr + "</td><td class='display_maintitle'>";
				sstr = sstr + "Criteria&nbsp;<br>";

				sstr = sstr + "<select class='basic_style' name='sort_g_tool_pallet2' id='sort_g_tool_pallet2' onChange='display_request_pallet_child_test(" + id + "," + this.value + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='1'";

				if (sort_g_tool_pallet2 == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Matching</option><option value='2'";
				if (sort_g_tool_pallet2 == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">All Items (Ignore Criteria)</option></select>";

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "View&nbsp;<br>";

				if (client_flg != 1) {
					sstr = sstr + "<select class='basic_style' name='sort_g_view_pallet' id='sort_g_view_pallet' onChange='display_request_pallet_child_test(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='1'";
					if (viewflgs == 1) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">UCB View</option><option value='2'";
					if (viewflgs == 2) {
						sstr = sstr + "selected";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				} else {
					sstr = sstr + "<select class='basic_style' name='sort_g_view_pallet' id='sort_g_view_pallet' onChange='display_request_pallet_child_test(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='2'";

					if (viewflgs == 2) {
						sstr = sstr + "selected";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				}

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "Combine Item View&nbsp;<br>";
				sstr = sstr + "<select class='basic_style' name='sort_g_location_pallet' id= 'sort_g_location_pallet' onChange='display_request_pallet_child_test(" + id + "," + flg + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")' > ";
				sstr = sstr + "<option value='1'";
				if (sort_g_location_pallet == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">By Item</option>";
				sstr = sstr + "<option value='2'";
				if (sort_g_location_pallet == 2) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">By Location</option>";
				sstr = sstr + " </select>";

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "<input type='checkbox' name='canship_ltl' id='canship_ltl' onChange='display_request_pallet_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'";

				if (canship_ltl == 1) {
					sstr = sstr + " checked ";
				}
				sstr = sstr + ">";
				sstr = sstr + "&nbsp;&nbsp;Can Ship LTL Only <br>";

				sstr = sstr + "<input type='checkbox' name='customer_pickup_allowed' id='customer_pickup_allowed' onChange='display_request_pallet_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")' ";
				if (customer_pickup == 1) {
					sstr = sstr + " checked ";
				}
				sstr = sstr + ">";
				sstr = sstr + "&nbsp;&nbsp;Customer Pickups Allowed Only";

				sstr = sstr + "</td>";
				sstr = sstr + "</tr>";
				sstr = sstr + "</table>";

				if (boxid == 0) {
					var selectobject = document.getElementById("lightbox_req_pal" + ctrlid);
				} else {
					var selectobject = document.getElementById("lightbox_req_pal" + boxid);
				}
				document.getElementById('light_new_pal').style.display = 'block';
				document.getElementById('light_new_pal').style.left = n_left + 20 + 'px';
				document.getElementById('light_new_pal').style.top = n_top + 20 + 'px';

				document.getElementById("light_new_pal").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("light_new_pal").innerHTML = sstr + xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "quote_request_pallet_tool_v2.php?ID=" + id + "&gbox=" + boxid + "&display-allrec=" + flgs + "&display_view=" + viewflgs + "&g_timing_pallet=" + g_timing_pallet + "&sort_g_tool2=" + sort_g_tool_pallet2 + "&client_flg=" + client_flg + "&sort_g_location_pallet=" + sort_g_location_pallet + "&canship_ltl=" + canship_ltl + "&customer_pickup=" + customer_pickup + "&pallet_height=" + pallet_height + "&pallet_width=" + pallet_width + "&load_all=" + load_all, true);
				xmlhttp.send();
			}
			// TEST PALLET SECTION ENDS 



			// TEST PALLET V3 SECTION START
			function display_request_Pallet_tool_v3(id, flg, viewflg, client_flg, boxid, pallet_height = 0, pallet_width = 0, ctrlid = 0, load_all = 0, onlyftl = 0) {
				if (boxid == 0) {
					var selectobject = document.getElementById("lightbox_req_pal" + ctrlid);
				} else {
					var selectobject = document.getElementById("lightbox_req_pal" + boxid);
				}
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');
				document.getElementById('light_new_pal').style.display = 'block';
				document.getElementById('light_new_pal').style.left = n_left + 20 + 'px';
				document.getElementById('light_new_pal').style.top = n_top + 20 + 'px';
				document.getElementById('light_new_pal').style.height = 580 + 'px';

				document.getElementById("light_new_pal").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

				var gtiming = 0;
				var sstr = "";
				sstr = "<table width='100%' border='0' cellspacing='0' cellpadding='2' class='basic_style'>";
				sstr = sstr + "<tr align='center'>";
				sstr = sstr + "<td class='display_maintitle' colspan='6'>";
				sstr = sstr + "PALLET MATCHING TOOL";
				sstr = sstr + "&nbsp;&nbsp;";
				sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_pal').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
				sstr = sstr + "</td></tr><tr align='center'><td class='display_maintitle' colspan='2' width='35%'>&nbsp;</td>";
				sstr = sstr + "<td class='display_maintitle' width='15%'>&nbsp;Criteria&nbsp;<br>";
				sstr = sstr + "<select class='basic_style' name='sort_g_tool_pallet2' id='sort_g_tool_pallet2' onChange='display_request_pallet_child_v3(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='1'";

				if (flg == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Matching</option><option value='2'";
				if (flg == 2) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">All Items (Ignore Criteria)</option></select>";

				sstr = sstr + "</td><td class='display_maintitle' width='15%'>";

				sstr = sstr + "&nbsp;View&nbsp;<br>";

				//if client dash
				if (client_flg != 1) {
					sstr = sstr + "<select class='basic_style' name='sort_g_view_pallet' id='sort_g_view_pallet' onChange='display_request_pallet_child_v3(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='1'";
					if (viewflg == 1) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">UCB View</option><option value='2'";
					if (viewflg == 2) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				} else {
					sstr = sstr + "<select class='basic_style' name='sort_g_view_pallet' id='sort_g_view_pallet' onChange='display_request_pallet_child_v3(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='2'";
					if (viewflg == 2) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">Customer Facing View</option></select>";

				}

				sstr = sstr + "</td>";
				sstr = sstr + "<td class='display_maintitle' colspan='2' width='35%'>&nbsp;</td></tr>";
				sstr = sstr + "</table>";

				var sstr_load_all = "";
				sstr_load_all = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
				sstr_load_all = sstr_load_all + "<tr align='center'>";
				sstr_load_all = sstr_load_all + "<td class='display_maintitle'>";
				sstr_load_all = sstr_load_all + "PALLET MATCHING TOOL";
				sstr_load_all = sstr_load_all + "&nbsp;&nbsp;";
				sstr_load_all = sstr_load_all + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_pal').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";

				sstr_load_all = sstr_load_all + "</td>";
				sstr_load_all = sstr_load_all + "</tr>";
				sstr_load_all = sstr_load_all + "</table>";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() { //alert('res -> '+xmlhttp.responseText);
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						if (load_all == 0) {
							document.getElementById("light_new_pal").innerHTML = sstr + xmlhttp.responseText;
						} else {
							document.getElementById("light_new_pal").innerHTML = sstr_load_all + xmlhttp.responseText;
						}
					}
				}

				xmlhttp.open("GET", "quote_request_pallets_tool_v3.php?ID=" + id + "&gbox=" + boxid + "&onlyftl=" + onlyftl + "&display-allrec=" + flg + "&display_view=" + viewflg + "&pallet_height=" + pallet_height + "&pallet_width=" + pallet_width + "&load_all=" + load_all + "&client_flg=" + client_flg + "&g_timing_pallet=0", true);
				xmlhttp.send();
			}

			function display_request_pallet_child_v3(id, flg, boxid, viewflg, client_flg, n_left, n_top, pallet_height = 0, pallet_width = 0, ctrlid = 0, load_all = 0) {
				var flgs = document.getElementById("sort_g_tool_pallet2").value;
				//var flgs_org = document.getElementById("sort_g_tool_pallet").value;

				//var g_timing_pallet = document.getElementById("g_timing_pallet").value;

				var sort_g_tool_pallet2 = document.getElementById("sort_g_tool_pallet2").value;
				var viewflgs = document.getElementById("sort_g_view_pallet").value;

				//if(document.getElementById("customer_pickup_allowed").checked){
				//	var customer_pickup = 1;
				//}else{
				//	var customer_pickup = 0;
				//}
				//alert('sort_g_location -> ' + sort_g_location);	
				if (boxid == 0) {
					var selectobject = document.getElementById("lightbox_req_pal" + ctrlid);
				} else {
					var selectobject = document.getElementById("lightbox_req_pal" + boxid);
				}
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');

				var sstr = "";
				sstr = "<table width='100%' border='0' cellspacing='0' cellpadding='2' class='basic_style'>";
				sstr = sstr + "<tr align='center'>";
				sstr = sstr + "<td class='display_maintitle' colspan='6'>";
				sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' >PALLET MATCHING TOOL</font>";
				sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;";
				sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_pal').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br><font face='Arial, Helvetica, sans-serif' size='1'>";
				//sstr = sstr + "<br>";

				sstr = sstr + "</td></tr><tr>";
				sstr = sstr + "<td class='display_maintitle' width='35%' colspan='2'></td><td class='display_maintitle' width='15%'>";
				sstr = sstr + "Criteria&nbsp;<br>";

				sstr = sstr + "<select class='basic_style' name='sort_g_tool_pallet2' id='sort_g_tool_pallet2' onChange='display_request_pallet_child_v3(" + id + "," + this.value + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='1'";

				if (sort_g_tool_pallet2 == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Matching</option><option value='2'";
				if (sort_g_tool_pallet2 == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">All Items (Ignore Criteria)</option></select>";

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "View&nbsp;<br>";

				if (client_flg != 1) {
					sstr = sstr + "<select class='basic_style' name='sort_g_view_pallet' id='sort_g_view_pallet' onChange='display_request_pallet_child_v3(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='1'";
					if (viewflgs == 1) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">UCB View</option><option value='2'";
					if (viewflgs == 2) {
						sstr = sstr + "selected";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				} else {
					sstr = sstr + "<select class='basic_style' name='sort_g_view_pallet' id='sort_g_view_pallet' onChange='display_request_pallet_child_v3(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='2'";

					if (viewflgs == 2) {
						sstr = sstr + "selected";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				}

				sstr = sstr + "</td>";
				sstr = sstr + "<td class='display_maintitle' colspan='2' width='35%'>&nbsp;</td></tr>";
				sstr = sstr + "</table>";

				if (boxid == 0) {
					var selectobject = document.getElementById("lightbox_req_pal" + ctrlid);
				} else {
					var selectobject = document.getElementById("lightbox_req_pal" + boxid);
				}
				document.getElementById('light_new_pal').style.display = 'block';
				document.getElementById('light_new_pal').style.left = n_left + 20 + 'px';
				document.getElementById('light_new_pal').style.top = n_top + 20 + 'px';

				document.getElementById("light_new_pal").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("light_new_pal").innerHTML = sstr + xmlhttp.responseText;
					}
				}

				//xmlhttp.open("GET","quote_request_pallets_tool_v3.php?ID="+id+"&gbox="+boxid+"&display-allrec="+flgs+"&display_view="+viewflgs+"&g_timing_pallet="+g_timing_pallet+"&sort_g_tool2="+ sort_g_tool_pallet2+"&client_flg="+client_flg+"&sort_g_location_pallet="+sort_g_location_pallet+"&canship_ltl="+canship_ltl+"&customer_pickup="+customer_pickup+ "&pallet_height="+pallet_height+"&pallet_width="+pallet_width+"&load_all="+load_all,true);			
				xmlhttp.open("GET", "quote_request_pallets_tool_v3.php?ID=" + id + "&gbox=" + boxid + "&display-allrec=" + flgs + "&display_view=" + viewflgs + "&g_timing_pallet=0&sort_g_tool2=" + sort_g_tool_pallet2 + "&client_flg=" + client_flg + "&canship_ltl=0&customer_pickup=0&pallet_height=" + pallet_height + "&pallet_width=" + pallet_width + "&load_all=" + load_all, true);
				xmlhttp.send();
			}
			// TEST PALLET V3 SECTION ENDS 

			// TEST PALLET V4 SECTION START 
			function display_request_Pallet_tool_v4(id, flg, viewflg, client_flg, boxid, subtype = 0, pallet_price = 0, ctrlid = 0, load_all = 0, onlyftl = 0) {

				if (boxid == 0) {
					var selectobject = document.getElementById("lightbox_req_pal" + ctrlid);
				} else {
					var selectobject = document.getElementById("lightbox_req_pal" + boxid);
				}
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');
				document.getElementById('light_new_pal').style.display = 'block';
				document.getElementById('light_new_pal').style.left = n_left + 20 + 'px';
				document.getElementById('light_new_pal').style.top = n_top + 20 + 'px';
				document.getElementById('light_new_pal').style.height = 580 + 'px';

				document.getElementById("light_new_pal").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";


				var gtiming = 0;
				var sstr = "";
				sstr = "<table width='100%' border='0' cellspacing='0' cellpadding='2' class='basic_style'>";
				sstr = sstr + "<tr align='center'>";
				sstr = sstr + "<td class='display_maintitle' colspan='6'>";
				sstr = sstr + "PALLET MATCHING TOOL";
				sstr = sstr + "&nbsp;&nbsp;";
				sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_pal').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
				sstr = sstr + "</td></tr><tr align='center'><td class='display_maintitle' width='20%'>&nbsp;</td>";
				sstr = sstr + "<td class='display_maintitle' width='15%'>&nbsp;Criteria&nbsp;<br>";
				sstr = sstr + "<select class='basic_style' name='sort_g_tool_pallet2' id='sort_g_tool_pallet2' onChange='display_request_pallet_child_v4(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + subtype + "," + pallet_price + "," + ctrlid + ")'><option value='1'";

				if (flg == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Matching</option><option value='2'";
				if (flg == 2) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">All Items (Ignore Criteria)</option></select>";

				sstr = sstr + "</td><td class='display_maintitle' width='15%'>";

				sstr = sstr + "&nbsp;View&nbsp;<br>";

				//if client dash
				if (client_flg != 1) {
					sstr = sstr + "<select class='basic_style' name='sort_g_view_pallet' id='sort_g_view_pallet' onChange='display_request_pallet_child_v4(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + subtype + "," + pallet_price + "," + ctrlid + ")'><option value='1'";
					if (viewflg == 1) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">UCB View</option><option value='2'";
					if (viewflg == 2) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				} else {
					sstr = sstr + "<select class='basic_style' name='sort_g_view_pallet' id='sort_g_view_pallet' onChange='display_request_pallet_child_v4(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + subtype + "," + pallet_price + "," + ctrlid + ")'><option value='2'";
					if (viewflg == 2) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">Customer Facing View</option></select>";

				}

				sstr = sstr + "</td>";

				sstr = sstr + "<td class='display_maintitle' width='15%'>&nbsp;Sub Type&nbsp;<br>";
				sstr = sstr + "<select class='basic_style' name='sort_pallet_subtype' id='sort_pallet_subtype' onChange='display_request_pallet_child_v4(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + subtype + "," + pallet_price + "," + ctrlid + ")'>";
				sstr = sstr + "<option value='0'";
				if (subtype == 0) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">SELECT One<option value='12'";
				if (subtype == 12) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">48x40 4-way Stringer - Grade B</option><option value='13'";
				if (subtype == 13) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">48x40 4-way Stringer - Grade B, Heat Treated</option><option value='14'";
				if (subtype == 14) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">48x40 4-way Stringer - Grade A</option><option value='15'";
				if (subtype == 15) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">48x40 4-way Stringer - Grade A, Heat Treated</option>";

				sstr = sstr + "</select></td>";

				sstr = sstr + "<td class='display_maintitle' width='20%'>&nbsp;</td></tr>";
				sstr = sstr + "</table>";
				/*
				var sstr_load_all = "";		
				sstr_load_all = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
				sstr_load_all = sstr_load_all + "<tr align='center'>";
				sstr_load_all = sstr_load_all +  "<td class='display_maintitle'>";
				sstr_load_all = sstr_load_all + "PALLET MATCHING TOOL"; 
				sstr_load_all = sstr_load_all + "&nbsp;&nbsp;";
				sstr_load_all = sstr_load_all + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_pal').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
				
				sstr_load_all = sstr_load_all + "</td>";
				sstr_load_all = sstr_load_all + "</tr>";
				sstr_load_all = sstr_load_all + "</table>";
				*/
				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() { //alert('res -> '+xmlhttp.responseText);
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						//document.getElementById("light_new_pal").innerHTML = sstr ; 
						document.getElementById("light_new_pal").innerHTML = sstr + xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "quote_request_pallets_tool_v4.php?ID=" + id + "&gbox=" + boxid + "&display-allrec=" + flg + "&display_view=" + viewflg + "&subtype=" + subtype + "&pallet_price=&client_flg=" + client_flg, true);

				xmlhttp.send();

			}

			function display_request_pallet_child_v4(id, flg, boxid, viewflg, client_flg, subtype, pallet_price, ctrlid = 0) {
				var flgs = document.getElementById("sort_g_tool_pallet2").value;
				var sort_g_tool_pallet2 = document.getElementById("sort_g_tool_pallet2").value;
				var viewflgs = document.getElementById("sort_g_view_pallet").value;
				var subtype = document.getElementById("sort_pallet_subtype").value;
				var pallet_price = 0;

				if (boxid == 0) {
					var selectobject = document.getElementById("lightbox_req_pal" + ctrlid);
				} else {
					var selectobject = document.getElementById("lightbox_req_pal" + boxid);
				}
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');

				var sstr = "";
				sstr = "<table width='100%' border='0' cellspacing='0' cellpadding='2' class='basic_style'>";
				sstr = sstr + "<tr align='center'>";
				sstr = sstr + "<td class='display_maintitle' colspan='6'>";
				sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' >PALLET MATCHING TOOL</font>";
				sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;";
				sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_pal').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br><font face='Arial, Helvetica, sans-serif' size='1'>";
				//sstr = sstr + "<br>";

				sstr = sstr + "</td></tr><tr>";
				sstr = sstr + "<td class='display_maintitle' width='20%'></td><td class='display_maintitle' width='15%'>";
				sstr = sstr + "Criteria&nbsp;<br>";

				sstr = sstr + "<select class='basic_style' name='sort_g_tool_pallet2' id='sort_g_tool_pallet2' onChange='display_request_pallet_child_v4(" + id + "," + this.value + "," + boxid + "," + viewflgs + "," + client_flg + "," + subtype + "," + pallet_price + "," + ctrlid + ")'><option value='1'";

				if (sort_g_tool_pallet2 == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Matching</option><option value='2'";
				if (sort_g_tool_pallet2 == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">All Items (Ignore Criteria)</option></select>";

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "View&nbsp;<br>";

				if (client_flg != 1) {
					sstr = sstr + "<select class='basic_style' name='sort_g_view_pallet' id='sort_g_view_pallet' onChange='display_request_pallet_child_v4(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + subtype + "," + pallet_price + "," + ctrlid + ")'><option value='1'";
					if (viewflgs == 1) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">UCB View</option><option value='2'";
					if (viewflgs == 2) {
						sstr = sstr + "selected";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				} else {
					sstr = sstr + "<select class='basic_style' name='sort_g_view_pallet' id='sort_g_view_pallet' onChange='display_request_pallet_child_v4(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + subtype + "," + pallet_price + "," + ctrlid + ")'><option value='2'";

					if (viewflgs == 2) {
						sstr = sstr + "selected";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				}

				sstr = sstr + "</td>";
				sstr = sstr + "<td class='display_maintitle' width='15%'>&nbsp;Sub Type&nbsp;<br>";
				sstr = sstr + "<select class='basic_style' name='sort_pallet_subtype' id='sort_pallet_subtype' onChange='display_request_pallet_child_v4(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + this.value + "," + pallet_price + "," + ctrlid + ")'>";
				sstr = sstr + "<option value='0'";
				if (subtype == 0) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">SELECT One<option value='12'";
				if (subtype == 12) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">48x40 4-way Stringer - Grade B</option><option value='13'";
				if (subtype == 13) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">48x40 4-way Stringer - Grade B, Heat Treated</option><option value='14'";
				if (subtype == 14) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">48x40 4-way Stringer - Grade A</option><option value='15'";
				if (subtype == 15) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">48x40 4-way Stringer - Grade A, Heat Treated</option>";

				sstr = sstr + "</select></td>";

				sstr = sstr + "<td class='display_maintitle' width='20%'>&nbsp;</td></tr>";
				sstr = sstr + "</table>";

				if (boxid == 0) {
					var selectobject = document.getElementById("lightbox_req_pal" + ctrlid);
				} else {
					var selectobject = document.getElementById("lightbox_req_pal" + boxid);
				}
				document.getElementById('light_new_pal').style.display = 'block';
				document.getElementById('light_new_pal').style.left = n_left + 20 + 'px';
				document.getElementById('light_new_pal').style.top = n_top + 20 + 'px';

				document.getElementById("light_new_pal").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("light_new_pal").innerHTML = sstr + xmlhttp.responseText;
					}
				}


				xmlhttp.open("GET", "quote_request_pallets_tool_v4.php?ID=" + id + "&gbox=" + boxid + "&display-allrec=" + flgs + "&display_view=" + viewflgs + "&sort_g_tool2=" + sort_g_tool_pallet2 + "&client_flg=" + client_flg + "&pallet_price=" + pallet_price + "&subtype=" + subtype, true);
				xmlhttp.send();
			}
			// TEST PALLET V4 SECTION ENDS 


			//
			//------------------------------------------------------------------
			//Other Matching tool
			//Display quote request Other matching tool
			function display_request_other_tool(id, flg, viewflg, client_flg, boxid, load_all = 0) {

				var selectobject = document.getElementById("lightbox_req_other" + boxid);
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');
				document.getElementById('light_new_other').style.display = 'block';
				document.getElementById('light_new_other').style.left = n_left + 20 + 'px';
				document.getElementById('light_new_other').style.top = n_top + 20 + 'px';
				document.getElementById('light_new_other').style.height = 580 + 'px';

				document.getElementById("light_new_other").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

				var sstr = "";
				sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
				sstr = sstr + "<tr align='center'>";
				sstr = sstr + "<td class='display_maintitle'>";
				sstr = sstr + "OTHER MATCHING TOOL";
				sstr = sstr + "&nbsp;&nbsp;";
				sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_other').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
				sstr = sstr + "Timing&nbsp;&nbsp;";
				sstr = sstr + "<select class='basic_style' name='g_timing_other' id='g_timing_other' onChange='display_request_other_child(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (flg == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Rdy Now + Presell</option><option value='3'";

				if (flg == 3) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Rdy < 3mo </option><option value='2'";

				if (flg == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">FTL Rdy Now ONLY</option>";
				sstr = sstr + "</select>";
				sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";
				//sstr = sstr + "<br>";
				//if (flg == 0) {

				sstr = sstr + "<select class='basic_style' name='sort_g_tool_other' id='sort_g_tool_other' onChange='display_request_other_child(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (flg == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Available to Sell</option><option value='2'";
				if (flg == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Available to Sell + Potential to Get</option></select>";
				//
				sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";
				//sstr = sstr + "<br>";
				//if (flg == 0) {

				sstr = sstr + "<select class='basic_style' name='sort_g_tool_other2' id='sort_g_tool_other2' onChange='display_request_other_child(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (flg == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Matching</option><option value='2'";
				if (flg == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">All Items (Ignore Criteria)</option></select>";

				sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

				sstr = sstr + "View&nbsp;&nbsp;";

				sstr = sstr + "<select class='basic_style' name='sort_g_view_other' id='sort_g_view_other' onChange='display_request_other_child(" + id + "," + flg + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";
				if (viewflg == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">UCB View</option><option value='2'";
				if (viewflg == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Customer Facing View</option></select>";

				sstr = sstr + "</td>";
				sstr = sstr + "</tr>";
				sstr = sstr + "</table>";

				var sstr_load_all = "";
				sstr_load_all = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
				sstr_load_all = sstr_load_all + "<tr align='center'>";
				sstr_load_all = sstr_load_all + "<td class='display_maintitle'>";
				sstr_load_all = sstr_load_all + "OTHER MATCHING TOOL";
				sstr_load_all = sstr_load_all + "&nbsp;&nbsp;";
				sstr_load_all = sstr_load_all + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_other').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";

				sstr_load_all = sstr_load_all + "</td>";
				sstr_load_all = sstr_load_all + "</tr>";
				sstr_load_all = sstr_load_all + "</table>";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						if (load_all == 0) {
							document.getElementById("light_new_other").innerHTML = sstr + xmlhttp.responseText;
						} else {
							document.getElementById("light_new_other").innerHTML = sstr_load_all + xmlhttp.responseText;
						}
					}
				}

				xmlhttp.open("GET", "quote_req_other_matching_new.php?ID=" + id + "&gbox=" + boxid + "&display-allrec=" + flg + "&display_view=" + viewflg + "&display_view=" + viewflg + "&load_all=" + load_all, true);
				xmlhttp.send();
			}

			function display_request_other_child(id, flg, boxid, viewflg, client_flg, n_left, n_top) {
				var flgs = document.getElementById("sort_g_tool_other").value;
				var flgs_org = document.getElementById("sort_g_tool_other").value;
				var viewflgs = document.getElementById("sort_g_view_other").value;
				//alert(boxid);
				//

				var g_timing_other = document.getElementById("g_timing_other").value;
				var sort_g_tool_other2 = document.getElementById("sort_g_tool_other2").value;

				var selectobject = document.getElementById("lightbox_req_other" + boxid);
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');

				var sstr = "";
				sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
				sstr = sstr + "<tr align='center'>";
				sstr = sstr + "<td class='display_maintitle'>";
				sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' >OTHER MATCHING TOOL</font>";
				sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;";
				sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_other').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br><font face='Arial, Helvetica, sans-serif' size='1'>";
				//
				if (flgs == 1) {
					sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
				}
				if (flgs == 2) {
					sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!', <br>'Unavailable (New Lead', 'Unavailable (Qualified, In Process)', <br>'Unavailable (Qualified, No Customers)' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
				}

				/* if(sort_g_tool_supersack2 == 1){
            sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display all boxes with status 'Available', <br>'Available, but Need Approval to Sell', <br>'Available, Currently Recycling, Sell w/ Lead Time!' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
        }
		if(sort_g_tool_other2 == 2){
            sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display all boxes with status 'Available', <br>'Available, but Need Approval to Sell', <br>'Available, Currently Recycling, Sell w/ Lead Time!', <br>'Unavailable (New Lead', 'Unavailable (Qualified, In Process)', <br>'Unavailable (Qualified, No Customers)' <br>and UCB Owned inventory &nbsp;&nbsp;</font></span></div>";
        }*/
				//
				sstr = sstr + "<br>";
				//if (flg == 0) {
				//  alert(flgs);

				//new code		  
				sstr = sstr + "Timing&nbsp;&nbsp;";
				sstr = sstr + "<select class='basic_style' name='g_timing_other' id='g_timing_other' onChange='display_request_other_child(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (g_timing_other == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Rdy Now + Presell</option><option value='3'";

				if (g_timing_other == 3) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Rdy < 3mo </option><option value='2'";

				if (g_timing_other == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">FTL Rdy Now ONLY</option>";
				sstr = sstr + "</select>";
				sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";
				//sstr = sstr + "<br>";
				//if (flg == 0) {

				sstr = sstr + "<select class='basic_style' name='sort_g_tool_other' id='sort_g_tool_other' onChange='display_request_other_child(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (flgs_org == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Available to Sell</option><option value='2'";
				if (flgs_org == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Available to Sell + Potential to Get</option></select>";
				//
				sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";
				//sstr = sstr + "<br>";
				//if (flg == 0) {

				sstr = sstr + "<select class='basic_style' name='sort_g_tool_other2' id='sort_g_tool_other2' onChange='display_request_other_child(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (sort_g_tool_other2 == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Matching</option><option value='2'";
				if (sort_g_tool_other2 == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">All Items (Ignore Criteria)</option></select>";

				sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

				sstr = sstr + "View&nbsp;&nbsp;";

				if (client_flg != 1) {
					sstr = sstr + "<select class='basic_style' name='sort_g_view_other' id='sort_g_view_other' onChange='display_request_other_child(" + id + "," + flgs + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";
					if (viewflgs == 1) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">UCB View</option><option value='2'";
					if (viewflgs == 2) {
						sstr = sstr + "selected";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				} else {
					sstr = sstr + "<select class='basic_style' name='sort_g_view_other' id='sort_g_view_other' onChange='display_request_other_child(" + id + "," + flgs + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='2'";

					if (viewflgs == 2) {
						sstr = sstr + "selected";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				}

				sstr = sstr + "</td>";
				sstr = sstr + "</tr>";
				sstr = sstr + "</table>";

				var selectobject = document.getElementById("lightbox");
				//var n_left = f_getPosition(selectobject, 'Left');
				//var n_top  = f_getPosition(selectobject, 'Top');
				document.getElementById('light_new_other').style.display = 'block';
				document.getElementById('light_new_other').style.left = n_left + 20 + 'px';
				document.getElementById('light_new_other').style.top = n_top + 20 + 'px';

				document.getElementById("light_new_other").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("light_new_other").innerHTML = sstr + xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "quote_req_other_matching_new.php?ID=" + id + "&gbox=" + boxid + "&display-allrec=" + flgs + "&display_view=" + viewflgs + "&g_timing=" + g_timing_other + "&sort_g_tool2=" + sort_g_tool_other2 + "&client_flg=" + client_flg, true);
				xmlhttp.send();
			}


			// TEST OTHER MATCHING  SECTION START 

			//  TEST OTHER SECTION START 
			function display_request_other_tool_test(id, flg, viewflg, client_flg, boxid, load_all = 0, onlyftl = 0) {
				var selectobject = document.getElementById("lightbox_req_other" + boxid);
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');
				document.getElementById('light_new_other').style.display = 'block';
				document.getElementById('light_new_other').style.left = n_left + 20 + 'px';
				document.getElementById('light_new_other').style.top = n_top + 20 + 'px';
				document.getElementById('light_new_other').style.height = 580 + 'px';

				document.getElementById("light_new_other").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

				var gtiming = 0;
				var sstr = "";
				sstr = "<table width='100%' border='0' cellspacing='0' cellpadding='2' class='basic_style'>";
				sstr = sstr + "<tr align='center'>";
				sstr = sstr + "<td class='display_maintitle' colspan='6'>";
				sstr = sstr + "OTHER MATCHING TOOL";
				sstr = sstr + "&nbsp;&nbsp;";
				sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_other').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
				sstr = sstr + "</td></tr><tr><td class='display_maintitle'>&nbsp;&nbsp;&nbsp;Timing<br>";
				sstr = sstr + "&nbsp;&nbsp;&nbsp;<select class='basic_style' name='g_timing_other' id='g_timing_other' onChange='display_request_other_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (flg == 1 || boxid == 0) {
					gtiming = 1;
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Rdy Now + Presell</option><option value='3'";

				if (flg == 3) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Rdy < 3mo </option><option value='2'";

				if (flg == 2 && boxid != 0) {
					sstr = sstr + "selected";
				}
				if (onlyftl == 1) {
					gtiming = 2;
					sstr = sstr + "selected";
				}
				sstr = sstr + ">FTL Rdy Now ONLY</option>";
				sstr = sstr + "</select>";
				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "&nbsp;Status&nbsp;<br>";
				sstr = sstr + "<select class='basic_style' name='sort_g_tool_other' id='sort_g_tool_other' onChange='display_request_other_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (flg == 1 || boxid == 0) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Available to Sell</option><option value='2'";
				if (flg == 2 && boxid != 0) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Available to Sell + Potential to Get</option></select>";

				sstr = sstr + "</td><td class='display_maintitle'>";
				sstr = sstr + "&nbsp;Criteria&nbsp;<br>";
				sstr = sstr + "<select class='basic_style' name='sort_g_tool_other2' id='sort_g_tool_other2' onChange='display_request_other_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (flg == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Matching</option><option value='2'";
				if (flg == 2) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">All Items (Ignore Criteria)</option></select>";

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "&nbsp;View&nbsp;<br>";

				//if client dash
				if (client_flg != 1) {
					sstr = sstr + "<select class='basic_style' name='sort_g_view_other' id='sort_g_view_other' onChange='display_request_other_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";
					if (viewflg == 1) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">UCB View</option><option value='2'";
					if (viewflg == 2) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				} else {
					sstr = sstr + "<select class='basic_style' name='sort_g_view_other' id='sort_g_view_other' onChange='display_request_other_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='2'";
					if (viewflg == 2) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">Customer Facing View</option></select>";

				}

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "&nbsp;Combine Item View <br>";
				sstr = sstr + "<select class='basic_style' name='sort_g_location_other' id= 'sort_g_location_other' onChange='display_request_other_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")' > ";
				sstr = sstr + "<option value='1'>By Item</option>";
				sstr = sstr + "<option value='2'>By Location</option>";
				sstr = sstr + " </select>";

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "<input type='checkbox' name='canship_ltl' id='canship_ltl' onChange='display_request_other_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'>";
				sstr = sstr + "&nbsp;&nbsp;Can Ship LTL Only <br>";

				sstr = sstr + "<input type='checkbox' name='customer_pickup_allowed' id='customer_pickup_allowed' onChange='display_request_other_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'>";
				sstr = sstr + "&nbsp;&nbsp;Customer Pickups Allowed Only";

				sstr = sstr + "</td>";
				sstr = sstr + "</tr>";
				sstr = sstr + "</table>";

				var sstr_load_all = "";
				sstr_load_all = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
				sstr_load_all = sstr_load_all + "<tr align='center'>";
				sstr_load_all = sstr_load_all + "<td class='display_maintitle'>";
				sstr_load_all = sstr_load_all + "OTHER MATCHING TOOL";
				sstr_load_all = sstr_load_all + "&nbsp;&nbsp;";
				sstr_load_all = sstr_load_all + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_other').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";

				sstr_load_all = sstr_load_all + "</td>";
				sstr_load_all = sstr_load_all + "</tr>";
				sstr_load_all = sstr_load_all + "</table>";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() { //alert('res -> '+xmlhttp.responseText);
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						if (load_all == 0) {
							document.getElementById("light_new_other").innerHTML = sstr + xmlhttp.responseText;
						} else {
							document.getElementById("light_new_other").innerHTML = sstr_load_all + xmlhttp.responseText;
						}
					}
				}

				xmlhttp.open("GET", "quote_request_other_tool_v2.php?ID=" + id + "&gbox=" + boxid + "&g_timing=" + gtiming + "&onlyftl=" + onlyftl + "&g_timing_other=" + gtiming + "&display-allrec=" + flg + "&display_view=" + viewflg + "&sort_g_tool2=" + flg + "&load_all=" + load_all + "&client_flg=" + client_flg, true);
				xmlhttp.send();
			}

			function display_request_other_child_test(id, flg, boxid, viewflg, client_flg, n_left, n_top) {
				var flgs = document.getElementById("sort_g_tool_other").value;
				var flgs_org = document.getElementById("sort_g_tool_other").value;
				var viewflgs = document.getElementById("sort_g_view_other").value;

				var g_timing_other = document.getElementById("g_timing_other").value;
				var sort_g_tool_other2 = document.getElementById("sort_g_tool_other2").value;
				var sort_g_location_other = document.getElementById("sort_g_location_other").value;

				if (document.getElementById("canship_ltl").checked) {
					var canship_ltl = 1;
				} else {
					var canship_ltl = 0;
				}

				if (document.getElementById("customer_pickup_allowed").checked) {
					var customer_pickup = 1;
				} else {
					var customer_pickup = 0;
				}
				//alert('sort_g_location -> ' + sort_g_location);	
				var selectobject = document.getElementById("lightbox_req_other" + boxid);
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');

				var sstr = "";
				sstr = "<table width='100%' border='0' cellspacing='0' cellpadding='2' class='basic_style'>";
				sstr = sstr + "<tr align='center'>";
				sstr = sstr + "<td class='display_maintitle' colspan='6'>";
				sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' >OTHER MATCHING TOOL</font>";
				sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;";
				sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_other').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br><font face='Arial, Helvetica, sans-serif' size='1'>";

				if (flgs == 1) {
					sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
				}
				if (flgs == 2) {
					sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!', <br>'Unavailable (New Lead', 'Unavailable (Qualified, In Process)', <br>'Unavailable (Qualified, No Customers)' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
				}
				//sstr = sstr + "<br>";

				sstr = sstr + "</td></tr><tr><td class='display_maintitle'>";
				sstr = sstr + "Timing&nbsp;<br>";
				sstr = sstr + "<select class='basic_style' name='g_timing_other' id='g_timing_other' onChange='display_request_other_child_test(" + id + "," + this.value + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (g_timing_other == 1) {
					sstr = sstr + " selected ";
				}

				sstr = sstr + ">Rdy Now + Presell</option><option value='3'";

				if (g_timing_other == 3) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Rdy < 3mo </option><option value='2'";
				if (g_timing_other == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">FTL Rdy Now ONLY</option>";
				sstr = sstr + "</select>";

				sstr = sstr + "</td><td class='display_maintitle'>";
				sstr = sstr + "Status&nbsp;<br>";

				sstr = sstr + "<select class='basic_style' name='sort_g_tool_other' id='sort_g_tool_other' onChange='display_request_other_child_test(" + id + "," + this.value + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (flgs_org == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Available to Sell</option><option value='2'";
				if (flgs_org == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">Available to Sell + Potential to Get</option></select>";

				sstr = sstr + "</td><td class='display_maintitle'>";
				sstr = sstr + "Criteria&nbsp;<br>";

				sstr = sstr + "<select class='basic_style' name='sort_g_tool_other2' id='sort_g_tool_other2' onChange='display_request_other_child_test(" + id + "," + this.value + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if (sort_g_tool_other2 == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">Matching</option><option value='2'";
				if (sort_g_tool_other2 == 2) {
					sstr = sstr + "selected";
				}
				sstr = sstr + ">All Items (Ignore Criteria)</option></select>";

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "View&nbsp;<br>";

				if (client_flg != 1) {
					sstr = sstr + "<select class='basic_style' name='sort_g_view_other' id='sort_g_view_other' onChange='display_request_other_child_test(" + id + "," + flgs + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";
					if (viewflgs == 1) {
						sstr = sstr + " selected ";
					}
					sstr = sstr + ">UCB View</option><option value='2'";
					if (viewflgs == 2) {
						sstr = sstr + "selected";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				} else {
					sstr = sstr + "<select class='basic_style' name='sort_g_view_other' id='sort_g_view_other' onChange='display_request_other_child_test(" + id + "," + flgs + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='2'";

					if (viewflgs == 2) {
						sstr = sstr + "selected";
					}
					sstr = sstr + ">Customer Facing View</option></select>";
				}

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "Combine Item View&nbsp;<br>";
				sstr = sstr + "<select class='basic_style' name='sort_g_location_other' id= 'sort_g_location_other' onChange='display_request_other_child_test(" + id + "," + flgs + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")' > ";
				sstr = sstr + "<option value='1'";
				if (sort_g_location_other == 1) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">By Item</option>";
				sstr = sstr + "<option value='2'";
				if (sort_g_location_other == 2) {
					sstr = sstr + " selected ";
				}
				sstr = sstr + ">By Location</option>";
				sstr = sstr + " </select>";

				sstr = sstr + "</td><td class='display_maintitle'>";

				sstr = sstr + "<input type='checkbox' name='canship_ltl' id='canship_ltl' onChange='display_request_other_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'";

				if (canship_ltl == 1) {
					sstr = sstr + " checked ";
				}
				sstr = sstr + ">";
				sstr = sstr + "&nbsp;&nbsp;Can Ship LTL Only <br>";

				sstr = sstr + "<input type='checkbox' name='customer_pickup_allowed' id='customer_pickup_allowed' onChange='display_request_other_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")' ";
				if (customer_pickup == 1) {
					sstr = sstr + " checked ";
				}
				sstr = sstr + ">";
				sstr = sstr + "&nbsp;&nbsp;Customer Pickups Allowed Only";

				sstr = sstr + "</td>";
				sstr = sstr + "</tr>";
				sstr = sstr + "</table>";

				var selectobject = document.getElementById("lightbox");

				document.getElementById('light_new_other').style.display = 'block';
				document.getElementById('light_new_other').style.left = n_left + 20 + 'px';
				document.getElementById('light_new_other').style.top = n_top + 20 + 'px';

				document.getElementById("light_new_other").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("light_new_other").innerHTML = sstr + xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "quote_request_other_tool_v2.php?ID=" + id + "&gbox=" + boxid + "&display-allrec=" + flgs + "&display_view=" + viewflgs + "&g_timing_other=" + g_timing_other + "&sort_g_tool2=" + sort_g_tool_other2 + "&client_flg=" + client_flg + "&sort_g_location_other=" + sort_g_location_other + "&canship_ltl=" + canship_ltl + "&customer_pickup=" + customer_pickup, true);
				xmlhttp.send();
			}
			// TEST OTHER SECTION ENDS 
			// TEST OTHER MATCHING  SECTION ENDS 
			//

			function add_item_as_favorite(bid) {
				var boxtype = document.getElementById("fav_boxtype").value;
				var fav_qty_avail = document.getElementById("fav_qty_avail" + bid).value;
				var fav_estimated_next_load = document.getElementById("fav_estimated_next_load" + bid).value;
				var fav_expected_loads_per_mo = document.getElementById("fav_expected_loads_per_mo" + bid).value;
				var fav_boxes_per_trailer = document.getElementById("fav_boxes_per_trailer" + bid).value;
				var fav_fob = document.getElementById("fav_fob" + bid).value;
				var fav_bl = document.getElementById("fav_bl" + bid).value;
				var fav_bw = document.getElementById("fav_bw" + bid).value;
				var fav_bh = document.getElementById("fav_bh" + bid).value;
				var fav_walls = document.getElementById("fav_walls" + bid).value;
				var fav_desc = document.getElementById("fav_desc" + bid).value;
				var fav_shipfrom = document.getElementById("fav_shipfrom" + bid).value;
				//
				var fav_match_id = document.getElementById("fav_match_id").value;
				var fav_match_boxid = document.getElementById("fav_match_boxid").value;
				var fav_match_flg = document.getElementById("fav_match_flg").value;
				var fav_match_viewflg = document.getElementById("fav_match_viewflg").value;
				var fav_match_client_flg = document.getElementById("fav_match_client_flg").value;
				var fav_match_load_all = document.getElementById("fav_match_load_all").value;
				//

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						if (xmlhttp.responseText == "done") {
							alert("Added an item as a favorite");
						}
						if (boxtype == 'g') {
							display_request_gaylords(fav_match_id, fav_match_boxid, fav_match_flg, fav_match_viewflg, fav_match_client_flg, fav_match_load_all);
						}
						if (boxtype == 'sb') {
							display_request_shipping_tool(fav_match_id, fav_match_flg, fav_match_viewflg, fav_match_client_flg, fav_match_boxid, fav_match_load_all);
						}
						if (boxtype == 'sup') {
							display_request_supersacks_tool(fav_match_id, fav_match_flg, fav_match_viewflg, fav_match_client_flg, fav_match_boxid, fav_match_load_all);
						}
						if (boxtype == 'pal') {
							display_request_pallet_tool(fav_match_id, fav_match_flg, fav_match_viewflg, fav_match_client_flg, fav_match_boxid, fav_match_load_all);
						}

					}
				}

				xmlhttp.open("GET", "add_favorite_inv_item.php?bid=" + bid + "&fav_qty_avail=" + fav_qty_avail + "&fav_estimated_next_load=" + fav_estimated_next_load + "&fav_expected_loads_per_mo=" + fav_expected_loads_per_mo + "&fav_boxes_per_trailer=" + fav_boxes_per_trailer + "&fav_bl=" + fav_bl + "&fav_bw=" + fav_bw + "&fav_bh=" + fav_bh + "&fav_walls=" + fav_walls + "&fav_fob=" + fav_fob + "&fav_desc=" + fav_desc + "&fav_shipfrom=" + fav_shipfrom + "&fav_match_id=" + fav_match_id + "&boxtype=" + boxtype, true);
				xmlhttp.send();
			}

			var option_count = 1;

			function see_other_option(rowIds) {
				const others = document.getElementsByClassName("other_options" + rowIds);
				for (const other of others) {
					if (option_count % 2 == 0) {
						other.style.display = "none";
					} else {
						other.style.display = "table-cell";
					}
				}
				if (option_count % 2 == 0) {
					document.getElementById('btn' + rowIds).innerHTML = "See Other Options";
				} else {
					document.getElementById('btn' + rowIds).innerHTML = "Hide Other Option";
				}
				option_count++;
			}
			//end quote request pallet matching tool		
			//------------------------------------------------------------------
			//To create a full load inventory
			function display_gaylords_load(id, boxid, btype, bval) {
				var selectobject = document.getElementById("lightbox_g" + boxid);
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');
				document.getElementById('light_gaylord_new1').style.display = 'block';
				document.getElementById('light_gaylord_new1').style.left = n_left + 20 + 'px';
				document.getElementById('light_gaylord_new1').style.top = n_top + 20 + 'px';
				document.getElementById('light_gaylord_new1').style.height = 480 + 'px';
				sstr = "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none; color:black' onclick=document.getElementById('light_gaylord_new1').style.display='none'>Close</a><br>";

				document.getElementById("light_gaylord_new1").innerHTML = sstr + "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";
				if (window.XMLHttpRequest) {
					xhr = new XMLHttpRequest();
				} else {
					xhr = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && xhr.status == 200) {
						document.getElementById("light_gaylord_new1").innerHTML = sstr + xhr.responseText;
						moveSummary();
					}
				}

				xhr.open("POST", "inventory_quote.php?ID=" + id + "&gbox=" + boxid + "&btype=" + btype + "&bval=" + bval, true);
				xhr.send();
			}
			//
			function moveSummary() {
				var ele = document.getElementById("summary_report");
				var eleTop = document.getElementById("ucb_own_inventory_summary");

				eleTop.innerHTML = ele.innerHTML;
				ele.style.display = "none";
			}

			function checkboxSelectAll() {

				totcnt = document.getElementById("tot_count_arry").value;

				for (var tmpcnt = 0; tmpcnt < totcnt; tmpcnt++) {
					if (document.getElementById("chk_sel_all").checked == true) {
						document.getElementById("chk_sel" + tmpcnt).checked = true;
					}
					if (document.getElementById("chk_sel_all").checked == false) {
						document.getElementById("chk_sel" + tmpcnt).checked = false;
					}
				}

				//checkboxClicked();
			}

			function show_inventories() {
				document.getElementById("content_result_div").innerHTML = "<center><br><br> Loading..... <img src='images/wait_animated.gif'/></center>";

				var selectedBoxType = getSelectedCheckboxes("inv_boxtype");
				var selectedWall = getSelectedCheckboxes("inv_boxwall");
				var selectedTop = getSelectedCheckboxes("inv_topconfig");
				var selectedBottom = getSelectedCheckboxes("inv_bottomconfig");
				var selectedShape = getSelectedCheckboxes("inv_boxshape");
				var selectedVents = getSelectedCheckboxes("inv_boxvents");
				var lengthMin = document.getElementById("boxlength_min").value;
				var lengthMax = document.getElementById("boxlength_max").value;
				var widthMin = document.getElementById("boxwidth_min").value;
				var widthMax = document.getElementById("boxwidth_max").value;
				var heightMin = document.getElementById("boxheight_min").value;
				var heightMax = document.getElementById("boxheight_max").value;
				var cubicMin = document.getElementById("boxcubic_min").value;
				var cubicMax = document.getElementById("boxcubic_max").value;
				var warehouse_input = document.getElementById("warehouse_input").value;

				if (window.XMLHttpRequest) {
					var xhr = new XMLHttpRequest();
				} else {
					var xhr = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && xhr.status == 200) {
						document.getElementById("content_result_div").innerHTML = xhr.responseText;

					}
				}
				var data = "boxtype=" + encodeURIComponent(selectedBoxType) +
					"&shape=" + encodeURIComponent(selectedShape) +
					"&wall=" + encodeURIComponent(selectedWall) +
					"&top=" + encodeURIComponent(selectedTop) +
					"&bottom=" + encodeURIComponent(selectedBottom) +
					"&vents=" + encodeURIComponent(selectedVents) +
					"&heightMin=" + heightMin + "&heightMax=" + heightMax +
					"&lengthMin=" + lengthMin + "&lengthMax=" + lengthMax +
					"&widthMin=" + widthMin + "&widthMax=" + widthMax +
					"&cubicMin=" + cubicMin + "&cubicMax=" + cubicMax + "&warehouse_input=" + warehouse_input;

				xhr.open("POST", "inventory_quote_result.php", true);
				xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhr.send(data);
			}

			function getSelectedCheckboxes(name) {
				var checkboxes = document.getElementsByName(name);
				var selectedCheckboxes = [];

				checkboxes.forEach(function(checkbox) {
					if (checkbox.checked) {
						selectedCheckboxes.push(checkbox.value);
					}
				});

				return selectedCheckboxes.join(",");
			}
		</script>
		<script src="demand_inventories.js"></script>
		<link rel='stylesheet' type='text/css' href='css/quote_inventory_cron.css'>
	</head>

	<body>

		<div id="light_gaylord" class="white_content_gaylord_new"></div>
		<div id="light_gaylord_new" class="white_content_gaylord_new"></div>
		<!--For new Gaylord tool-->
		<div id="light_gaylord_new1" class="white_content_gaylord_new1"></div>
		<div id="light_pallets_new" class="white_content_gaylord_new1"></div>
		<div id="light_new_shipping" class="white_content_gaylord_new"></div>
		<div id="light_new_supersacks" class="white_content_gaylord_new"></div>
		<div id="light_new_other" class="white_content_gaylord_new"></div>
		<div id="light_new_pal" class="white_content_gaylord_new"></div>
		<div id="fade" class="black_overlay"></div>

		<?php
		db();
		$quotes_archive_date = "";
		$query = "SELECT variablevalue FROM tblvariable where variablename = 'quotes_archive_date'";
		$dt_view_res3 = db_query($query);
		while ($objQuote = array_shift($dt_view_res3)) {
			$quotes_archive_date = $objQuote["variablevalue"];
		}

		?>
		<table width='650px'>
			<script language="JavaScript" src="inc/CalendarPopup.js"></script>
			<script language="JavaScript">
				document.write(getCalendarStyles());
			</script>
			<script language="JavaScript">
				var cal1xx_quote_new = new CalendarPopup("listdiv");
				cal1xx_quote_new.showNavigationDropdowns();
				//
				var cal19xx = new CalendarPopup("listdivg");
				cal19xx.showNavigationDropdowns();
				//
				var cal1xx_sb_quote_new = new CalendarPopup("sb_listdiv");
				cal1xx_sb_quote_new.showNavigationDropdowns();
				//
				var cal1xx_supquote_new = new CalendarPopup("sup_listdiv");
				cal1xx_supquote_new.showNavigationDropdowns();
				//
				var cal1xx_palquote_new = new CalendarPopup("pal_listdiv");
				cal1xx_palquote_new.showNavigationDropdowns();
				//
				var cal1xx_other_quote_new = new CalendarPopup("other_listdiv");
				cal1xx_other_quote_new.showNavigationDropdowns();
			</script>

			<!--New landing page - Sales lead request -->
			<!--End New landing page - Sales request-->
			<?php
			$date_needed_by = '';
			$clientdash_flg = 0;
			$subheading = "#ccd9e7";
			$rowcolor1 = "#e4e4e4";
			$rowcolor2 = "#ececec";
			$buttonrow = "#ccd9e7";
			$subheading2 = "#d5d5d5";
			/*if ($clientdash_flg == 1){
		$main_heading = "What Do They Buy? (Customer Entered)";
		$subheading="#e3e1bb";
		$rowcolor1="#e4e4e4";
		$rowcolor2="#ececec";
		//$buttonrow="#d5d5d5";
		$buttonrow="#e3e1bb";
		$subheading2="#d5d5d5";
		
	}else{
		$main_heading = "What Do They Buy? (UCB Entered)";
		
		$subheading="#ccd9e7";
		$rowcolor1="#e4e4e4";
		$rowcolor2="#ececec";
		//$buttonrow="#d5d5d5";
		$buttonrow="#ccd9e7";
		$subheading2="#d5d5d5";
	}	*/
			$main_heading = "What Do They Buy?";
			?>
			<tr>
				<td colspan="2">
					<font size="4" face="arial" color="#333333"><b><?php echo $main_heading; ?></b></font><br>
					<table width="100%">
						<tr>
							<form method="POST" style="margin-bottom:0px;">
								<td bgcolor="<?php echo $buttonrow; ?>" valign="middle" cellpadding="5" height="30px">
									<font size="1">Item
										<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">What commodity does this company want to buy</span> </div>
									</font>
									<select name="quote_item" id="quote_item">
										<option value="-1">Select</option>
										<?php
										$item_query = "Select * from quote_request_item where status=1";
										$item_res = db_query($item_query);
										while ($item_rows = array_shift($item_res)) {
										?>
											<option value="<?php echo $item_rows["quote_rq_id"]; ?>"><?php echo $item_rows["item"]; ?></option>
										<?php
										}
										?>
									</select>
								</td>
							</form>
						</tr>
						<tr>
							<td><!-- Table for Gaylord Totes-->
								<form name="rptSearch">
									<table width="100%" id="1" class="table item" cellpadding="3" cellspacing="1">
										<tr bgcolor="<?php echo $rowcolor2; ?>">
											<td>Ideal Size (in)
												<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">If they were to buy brand new, what would be the size they would buy</span> </div>
											</td>
											<td width="130px" align="center">
												<div class="size_align"> <span class="label_txt">L</span><br>
													<input type="text" name="g_item_length" id="g_item_length" size="5" onKeyPress="return isNumberKey(event)" class="size_txt_center">
												</div>
											</td>
											<td width="20px" align="center">x</td>
											<td width="130px" align="center">
												<div class="size_align"> <span class="label_txt">W</span><br>
													<input type="text" name="g_item_width" id="g_item_width" size="5" onKeyPress="return isNumberKey(event)" class="size_txt_center">
												</div>
											</td>
											<td width="20px" align="center">x</td>
											<td width="130px" align="center">
												<div class="size_align"> <span class="label_txt">H</span><br>
													<input type="text" name="g_item_height" id="g_item_height" size="5" onKeyPress="return isNumberKey(event)" class="size_txt_center">
												</div>
											</td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td> Quantity Requested
												<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">How much of this item do they order at a time</span> </div>
											</td>
											<td colspan=5><select name="g_quantity_request" id="g_quantity_request" onChange="show_otherqty_text(this)">
													<option>Select One</option>
													<option>Full Truckload</option>
													<option>Half Truckload</option>
													<option>Quarter Truckload</option>
													<option>Other</option>
												</select>
												<br>
												<input type="text" name="g_other_quantity" id="g_other_quantity" size="10" style="display:none;" onKeyPress="return isNumberKey(event)">
											</td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor2; ?>">
											<td> Frequency of Order
												<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">How often do they order this item</span> </div>
											</td>
											<td colspan=5><select name="g_frequency_order" id="g_frequency_order">
													<option>Select One</option>
													<option>Multiple per Week</option>
													<option>Multiple per Month</option>
													<option>Once per Month</option>
													<option>Multiple per Year</option>
													<option>Once per Year</option>
													<option>One-Time Purchase</option>
												</select></td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td>Annual Appetite
												<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">How many do they order per year?</span> </div>
											</td>
											<td colspan=5><input type="text" id="g_how_many_order_per_yr"></td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td> What Used For?
												<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">What do they put in this item, how much weight is going in it?</span> </div>
											</td>
											<td colspan=5><input type="text" id="g_what_used_for"></td>
										</tr>
										<!-- <tr bgcolor="<?php echo $rowcolor2; ?>">
					<td>
						Date Needed By? <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
					<span class="tooltiptext">When is the latest they can take delivery</span>
					</div>
					</td>
					<td colspan=5>
						 <font face="Arial, Helvetica, sans-serif" color="#333333" size="2"><input type="text" name="date_needed_by" id="date_needed_by" size="11" value="<?php echo (isset($_REQUEST["date_needed_by"]) && $_REQUEST["date_needed_by"] != "") ? date('m/d/Y', strtotime("now")) : "" ?>"> <a href="#" onclick="cal1xx_quote_new.select(document.rptSearch.date_needed_by,'anchor1xx_quote_new','MM/dd/yyyy'); return false;" name="anchor1xx_quote_new" id="anchor1xx_quote_new"><img border="0" src="images/calendar.jpg"></a> </font>
					</td>
					</tr> -->
										<div id="listdiv" style="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td> Also Need Pallets?
												<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">Do they also order pallets</span> </div>
											</td>
											<td colspan=5><input type="checkbox" name="need_pallets" id="need_pallets" value="Yes"></td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td>
												Desired Price
												<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">The price point the client is trying to stay under. This value is per unit.</span> </div>
											</td>
											<td colspan=5>
												$ <input type="text" name="sales_desired_price_g" id="sales_desired_price_g" size="11">
											</td>
										</tr>

										<tr bgcolor="<?php echo $rowcolor2; ?>">
											<td> Notes
												<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">Add any additional notes that will assist in selling these items. More info is better.</span> </div>
											</td>
											<td colspan=5><textarea name="g_item_note" id="g_item_note"></textarea></td>
										</tr>

										<tr bgcolor="<?php echo $subheading2; ?>">
											<td colspan="6"><strong>Sub Type:</strong>
												<div class="tooltip_large"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext_large">Sub Type.</span> </div>
											</td>
										</tr>

										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td>Sub Type</td>
											<td colspan="5">
												<select name="box_sub_type" id="box_sub_type" onchange="box_sub_type_load_ctrl()">
													<option value="">Select One</option>
													<?php
													$q1 = "SELECT * FROM loop_boxes_sub_type_master where box_type = 'Gaylord' and active_flg = 1 ORDER BY display_order ASC";
													$selected_txt = "";
													$query = db_query($q1);
													while ($fetch = array_shift($query)) {
														$id_tmp = $fetch['unqid'];

														if ($_REQUEST["box_sub_type"] == $id_tmp) {
															$selected_txt = " SELECTED ";
														} else {
															$selected_txt = "";
														}
													?>
														<option value="<?php echo $id_tmp; ?>" <?php echo $selected_txt; ?>><?php echo $fetch['sub_type_name']; ?></option>
													<?php 	} ?>
												</select>
											</td>
										</tr>

										<tr bgcolor="<?php echo $subheading2; ?>" id="div_gaylord_criteria1" style="display: none;">
											<td colspan="6"><strong>Criteria of what they SHOULD be able to use:</strong>
												<div class="tooltip_large"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext_large">It will be extremely difficult to find the exact size the company is asking for, so fill out the criteria and ranges of what the company SHOULD be able to use for this item. The more flexible the criteria, the more likely UCB can find options close to them (less expensive). The more strict the criteria, the more difficult it is for UCB to find options close to them (more expensive). All options will default to include all items, edit details to scale back the options.</span> </div>
											</td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>" id="div_gaylord_criteria2" style="display: none;">
											<td><!-- align="right"-->
												Height Flexibility </td>
											<td><span class="label_txt">Min</span> <br>
												<input type="text" name="g_item_min_height" id="g_item_min_height" value="0" size="5" onKeyPress="return isNumberKey(event)">
											</td>
											<td align="center">-</td>
											<td colspan="3"><span class="label_txt">Max</span>
												<input type="text" name="g_item_max_height" id="g_item_max_height" value="99" size="5" onKeyPress="return isNumberKey(event)">
											</td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor2; ?>" id="div_gaylord_criteria3" style="display: none;">
											<td> Shape </td>
											<td> Rectangular </td>
											<td><input type="checkbox" id="g_shape_rectangular" value="Yes" checked="checked"></td>
											<td> Octagonal </td>
											<td colspan="2"><input type="checkbox" id="g_shape_octagonal" value="Yes" checked="checked"></td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>" id="div_gaylord_criteria4" style="display: none;">
											<td rowspan="5"> # of Walls </td>
											<td> 1ply </td>
											<td><input type="checkbox" id="g_wall_1" value="Yes" checked="checked"></td>
											<td> 6ply </td>
											<td colspan="2"><input type="checkbox" id="g_wall_6" value="Yes" checked="checked"></td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>" id="div_gaylord_criteria5" style="display: none;">
											<td> 2ply </td>
											<td><input type="checkbox" id="g_wall_2" value="Yes" checked="checked"></td>
											<td> 7ply </td>
											<td colspan="2"><input type="checkbox" id="g_wall_7" value="Yes" checked="checked"></td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>" id="div_gaylord_criteria6" style="display: none;">
											<td> 3ply </td>
											<td><input type="checkbox" id="g_wall_3" value="Yes" checked="checked"></td>
											<td> 8ply </td>
											<td colspan="2"><input type="checkbox" id="g_wall_8" value="Yes" checked="checked"></td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>" id="div_gaylord_criteria7" style="display: none;">
											<td> 4ply </td>
											<td><input type="checkbox" id="g_wall_4" value="Yes" checked="checked"></td>
											<td> 9ply </td>
											<td colspan="2"><input type="checkbox" id="g_wall_9" value="Yes" checked="checked"></td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>" id="div_gaylord_criteria8" style="display: none;">
											<td> 5ply </td>
											<td><input type="checkbox" id="g_wall_5" value="Yes" checked="checked"></td>
											<td> 10ply </td>
											<td colspan="2"><input type="checkbox" id="g_wall_10" value="Yes" checked="checked"></td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor2; ?>" id="div_gaylord_criteria9" style="display: none;">
											<td rowspan="2"> Top Config </td>
											<td> No Top </td>
											<td><input name="g_no_top" type="checkbox" id="g_no_top" value="Yes" checked="checked"></td>
											<td> Lid Top </td>
											<td colspan="2"><input name="g_lid_top" type="checkbox" id="g_lid_top" value="Yes" checked="checked"></td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor2; ?>" id="div_gaylord_criteria10" style="display: none;">
											<td> Partial Flap Top </td>
											<td><input name="g_partial_flap_top" type="checkbox" id="g_partial_flap_top" value="Yes" checked="checked"></td>
											<td> Full Flap Top </td>
											<td colspan="2"><input name="g_full_flap_top" type="checkbox" id="g_full_flap_top" value="Yes" checked="checked"></td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>" id="div_gaylord_criteria11" style="display: none;">
											<td rowspan="3"> Bottom Config </td>
											<td> No Bottom </td>
											<td><input name="g_no_bottom_config" type="checkbox" id="g_no_bottom_config" value="Yes" checked="checked"></td>
											<td> Partial Flap w/ Slipsheet </td>
											<td colspan="2"><input name="g_partial_flap_w" type="checkbox" id="g_partial_flap_w" value="Yes" checked="checked"></td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>" id="div_gaylord_criteria12" style="display: none;">
											<td> Tray Bottom </td>
											<td><input name="g_tray_bottom" type="checkbox" id="g_tray_bottom" value="Yes" checked="checked"></td>
											<td> Full Flap Bottom </td>
											<td colspan="2"><input name="g_full_flap_bottom" type="checkbox" id="g_full_flap_bottom" value="Yes" checked="checked"></td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>" id="div_gaylord_criteria13" style="display: none;">
											<td> Partial Flap w/o SlipSheet </td>
											<td colspan="4"><input name="g_partial_flap_wo" type="checkbox" id="g_partial_flap_wo" value="Yes" checked="checked"></td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor2; ?>" id="div_gaylord_criteria14" style="display: none;">
											<td> Vents Okay? </td>
											<td colspan=5><input name="g_vents_okay" type="checkbox" id="g_vents_okay" value="Yes" checked="checked"></td>
										</tr>

										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td> Show on Boomerang Portal? </td>
											<td colspan=5><input name="client_dash_flg" type="checkbox" id="client_dash_flg" value="1"></td>
										</tr>

										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td> High Value Opportunity </td>
											<td colspan=5><input name="high_value_target" type="checkbox" id="high_value_target" value="1"></td>
										</tr>

										<tr align="center" bgcolor="<?php echo $buttonrow; ?>">
											<td colspan="6" align="center">
												<input type="button" name="g_item_submit" value="Submit" onClick="quote_save(<?php echo $b2bid; ?>)">
											</td>
										</tr>
									</table>
								</form>
								<!-- Table for Shipping Boxes-->
								<form name="sb">
									<table width="100%" id="2" class="table item" cellpadding="3" cellspacing="1">
										<tr bgcolor="<?php echo $rowcolor2; ?>">
											<td>Ideal Size (in)
												<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">If they were to buy brand new, what would be the size they would buy</span> </div>
											</td>
											<td width="130px" align="center">
												<div class="size_align"> <span class="label_txt">L</span><br>
													<input type="text" name="sb_item_length" id="sb_item_length" size="5" onKeyPress="return isNumberKey(event)" class="size_txt_center">
												</div>
											</td>
											<td width="20px" align="center">x</td>
											<td width="130px" align="center">
												<div class="size_align"> <span class="label_txt">W</span><br>
													<input type="text" name="sb_item_width" id="sb_item_width" size="5" onKeyPress="return isNumberKey(event)" class="size_txt_center">
												</div>
											</td>
											<td width="20px" align="center">x</td>
											<td width="130px" align="center">
												<div class="size_align"> <span class="label_txt">H</span><br>
													<input type="text" name="sb_item_height" id="sb_item_height" size="5" onKeyPress="return isNumberKey(event)" class="size_txt_center">
												</div>
											</td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td> Quantity Requested
												<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">How much of this item do they order at a time</span> </div>
											</td>
											<td colspan=5><select name="sb_quantity_requested" id="sb_quantity_requested" onChange="show_sb_otherqty_text(this)">
													<option>Select One</option>
													<option>Full Truckload</option>
													<option>Half Truckload</option>
													<option>Quarter Truckload</option>
													<option>Other</option>
												</select>
												<br>
												<input type="text" name="sb_other_quantity" id="sb_other_quantity" size="10" style="display:none;" onKeyPress="return isNumberKey(event)">
											</td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor2; ?>">
											<td> Frequency of Order
												<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">How often do they order this item</span> </div>
											</td>
											<td colspan=5><select name="sb_frequency_order" id="sb_frequency_order">
													<option>Select One</option>
													<option>Multiple per Week</option>
													<option>Multiple per Month</option>
													<option>Once per Month</option>
													<option>Multiple per Year</option>
													<option>Once per Year</option>
													<option>One-Time Purchase</option>
												</select></td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td> Annual Appetite
												<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">How many do they order per year?</span> </div>
											</td>
											<td colspan=5><input type="text" id="sb_how_many_order_per_yr"></td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td> What Used For?
												<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">What do they put in this item, how much weight is going in it?</span> </div>
											</td>
											<td colspan=5><input type="text" id="sb_what_used_for"></td>
										</tr>
										<div id="sb_listdiv" style="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td> Also Need Pallets?
												<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">Do they also order pallets</span> </div>
											</td>
											<td colspan=5><input name="sb_need_pallets" type="checkbox" id="sb_need_pallets" value="Yes"></td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td>
												Desired Price
												<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">The price point the client is trying to stay under. This value is per unit.</span> </div>
											</td>
											<td colspan=5>
												$ <input type="text" name="sb_sales_desired_price" id="sb_sales_desired_price" size="11">
											</td>
										</tr>

										<tr bgcolor="<?php echo $rowcolor2; ?>">
											<td> Notes
												<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">Add any additional notes that will assist in selling these items. More info is better.</span> </div>
											</td>
											<td colspan=5><textarea name="sb_notes" id="sb_notes"></textarea></td>
										</tr>
										<tr bgcolor="<?php echo $subheading2; ?>">
											<td colspan="6"><strong>Criteria of what they SHOULD be able to use:</strong>
												<div class="tooltip_large"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext_large">It will be extremely difficult to find the exact size the company is asking for, so fill out the criteria and ranges of what the company SHOULD be able to use for this item. The more flexible the criteria, the more likely UCB can find options close to them (less expensive). The more strict the criteria, the more difficult it is for UCB to find options close to them (more expensive). All options will default to include all items, edit details to scale back the options.</span> </div>
											</td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td colspan="6"><strong>Size Flexibility</strong></td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor2; ?>">
											<td><!-- align="right"-->
												Length </td>
											<td><span class="label_txt">Min</span> <br>
												<input type="text" name="sb_item_min_length" id="sb_item_min_length" value="0" size="5" onKeyPress="return isNumberKey(event)">
											</td>
											<td align="center">-</td>
											<td colspan="3"><span class="label_txt">Max</span>
												<input type="text" name="sb_item_max_length" id="sb_item_max_length" value="99" size="5" onKeyPress="return isNumberKey(event)">
											</td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td><!-- align="right"-->
												Width </td>
											<td><span class="label_txt">Min</span> <br>
												<input type="text" name="sb_item_min_width" id="sb_item_min_width" value="0" size="5" onKeyPress="return isNumberKey(event)">
											</td>
											<td align="center">-</td>
											<td colspan="3"><span class="label_txt">Max</span>
												<input type="text" name="sb_item_max_width" id="sb_item_max_width" value="99" size="5" onKeyPress="return isNumberKey(event)">
											</td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor2; ?>">
											<td><!-- align="right"-->
												Height </td>
											<td><span class="label_txt">Min</span> <br>
												<input type="text" name="sb_item_min_height" id="sb_item_min_height" value="0" size="5" onKeyPress="return isNumberKey(event)">
											</td>
											<td align="center">-</td>
											<td colspan="3"><span class="label_txt">Max</span>
												<input type="text" name="sb_item_max_height" id="sb_item_max_height" value="99" size="5" onKeyPress="return isNumberKey(event)">
											</td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td><!-- align="right"-->
												Cubic Footage </td>
											<td><span class="label_txt">Min</span> <br>
												<input type="text" name="sb_cubic_footage_min" id="sb_cubic_footage_min" value="0" size="5" onKeyPress="return isNumberKey(event)">
											</td>
											<td align="center">-</td>
											<td colspan="3"><span class="label_txt">Max</span>
												<input type="text" name="sb_cubic_footage_max" id="sb_cubic_footage_max" value="99" size="5" onKeyPress="return isNumberKey(event)">
											</td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor2; ?>">
											<td> # of Walls </td>
											<td> 1ply </td>
											<td><input type="checkbox" id="sb_wall_1" value="Yes" checked="checked"></td>
											<td> 2ply </td>
											<td colspan="2"><input type="checkbox" id="sb_wall_2" value="Yes" checked="checked"></td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td> Top Config </td>
											<td> No Top </td>
											<td><input name="sb_no_top" type="checkbox" id="sb_no_top" value="Yes"></td>
											<td> Full Flap Top </td>
											<td colspan="2"><input name="sb_full_flap_top" type="checkbox" id="sb_full_flap_top" value="Yes" checked="checked"></td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td>&nbsp;</td>
											<td> Partial Flap Top </td>
											<td><input name="sb_partial_flap_top" type="checkbox" id="sb_partial_flap_top" value="Yes"></td>
											<td>&nbsp;</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor2; ?>">
											<td> Bottom Config </td>
											<td> No Bottom </td>
											<td><input name="sb_no_bottom" type="checkbox" id="sb_no_bottom" value="Yes"></td>
											<td> Full Flap Bottom </td>
											<td colspan="2"><input name="sb_full_flap_bottom" type="checkbox" id="sb_full_flap_bottom" value="Yes" checked="checked"></td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor2; ?>">
											<td>&nbsp;</td>
											<td> Partial Flap Bottom </td>
											<td><input name="sb_partial_flap_bottom" type="checkbox" id="sb_partial_flap_bottom" value="Yes"></td>
											<td>&nbsp;</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td> Vents Okay? </td>
											<td colspan=5><input name="sb_vents_okay" type="checkbox" id="sb_vents_okay" value="Yes"></td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td> Show on Boomerang Portal? </td>
											<td colspan=5><input name="sb_client_dash_flg" type="checkbox" id="sb_client_dash_flg" value="1"></td>
										</tr>

										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td> High Value Opportunity </td>
											<td colspan=5><input name="sb_high_value_target" type="checkbox" id="sb_high_value_target" value="1"></td>
										</tr>
										<tr align="center" bgcolor="<?php echo $buttonrow; ?>">
											<td colspan="6" align="center"><input type="button" name="sb_item_submit" value="Submit" onClick="sb_quote_save(<?php echo $b2bid; ?>)"></td>
										</tr>
									</table>
								</form>
								<!-- Table for Supersacks-->
								<form name="sup">
									<table width="100%" id="3" class="table item" cellpadding="3" cellspacing="1">
										<tr bgcolor="<?php echo $rowcolor2; ?>">
											<td>Ideal Size (in)
												<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">If they were to buy brand new, what would be the size they would buy</span> </div>
											</td>
											<td width="130px" align="center">
												<div class="size_align"> <span class="label_txt">L</span><br>
													<input type="text" name="sup_item_length" id="sup_item_length" size="5" onKeyPress="return isNumberKey(event)" class="size_txt_center">
												</div>
											</td>
											<td width="20px" align="center">x</td>
											<td width="130px" align="center">
												<div class="size_align"> <span class="label_txt">W</span><br>
													<input type="text" name="sup_item_width" id="sup_item_width" size="5" onKeyPress="return isNumberKey(event)" class="size_txt_center">
												</div>
											</td>
											<td width="20px" align="center">x</td>
											<td width="130px" align="center">
												<div class="size_align"> <span class="label_txt">H</span><br>
													<input type="text" name="sup_item_height" id="sup_item_height" size="5" onKeyPress="return isNumberKey(event)" class="size_txt_center">
												</div>
											</td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td> Quantity Requested
												<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">How much of this item do they order at a time</span> </div>
											</td>
											<td colspan=5><select name="sup_quantity_requested" id="sup_quantity_requested" onChange="show_sup_otherqty_text(this)">
													<option>Select One</option>
													<option>Full Truckload</option>
													<option>Half Truckload</option>
													<option>Quarter Truckload</option>
													<option>Other</option>
												</select>
												<br>
												<input type="text" name="sup_other_quantity" id="sup_other_quantity" size="10" style="display:none;">
											</td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor2; ?>">
											<td> Frequency of Order
												<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">How often do they order this item</span> </div>
											</td>
											<td colspan=5><select name="sup_frequency_order" id="sup_frequency_order">
													<option>Select One</option>
													<option>Multiple per Week</option>
													<option>Multiple per Month</option>
													<option>Once per Month</option>
													<option>Multiple per Year</option>
													<option>Once per Year</option>
													<option>One-Time Purchase</option>
												</select></td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td> What Used For?
												<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">What do they put in this item, how much weight is going in it?</span> </div>
											</td>
											<td colspan=5><input type="text" id="sup_what_used_for"></td>
										</tr>
										<div id="sup_listdiv" style="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td> Also Need Pallets?
												<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">Do they also order pallets</span> </div>
											</td>
											<td colspan=5><input type="checkbox" name="sup_need_pallets" id="sup_need_pallets" value="Yes"></td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td>
												Desired Price
												<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">The price point the client is trying to stay under. This value is per unit.</span> </div>
											</td>
											<td colspan=5>
												$ <input type="text" name="sup_sales_desired_price" id="sup_sales_desired_price" size="11">
											</td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor2; ?>">
											<td> Notes
												<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">Add any additional notes that will assist in selling these items. More info is better.</span> </div>
											</td>
											<td colspan=5><textarea name="sup_notes" id="sup_notes"></textarea></td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td> Show on Boomerang Portal? </td>
											<td colspan=5><input name="sup_client_dash_flg" type="checkbox" id="sup_client_dash_flg" value="1"></td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td> High Value Opportunity </td>
											<td colspan=5><input name="sup_high_value_target" type="checkbox" id="sup_high_value_target" value="1"></td>
										</tr>

										<tr align="center" bgcolor="<?php echo $buttonrow; ?>">
											<td colspan="6" align="center">
												<input type="button" name="sup_item_submit" value="Submit" onClick="sup_quote_save(<?php echo $b2bid; ?>)">
											</td>
										</tr>
									</table>
								</form>
								<!-- Table for Pallets-->
								<form name="pallets">
									<table width="100%" id="4" class="table item" cellpadding="3" cellspacing="1">
										<tr bgcolor="<?php echo $rowcolor2; ?>">
											<td width="220px">Ideal Size (in)
												<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">If they were to buy brand new, what would be the size they would buy</span> </div>
											</td>
											<td width="93px" align="center">
												<div class="size_align"> <span class="label_txt">L</span><br>
													<input type="text" name="pal_item_length" id="pal_item_length" size="5" onKeyPress="return isNumberKey(event)" class="size_txt_center">
												</div>
											</td>
											<td width="30px" align="center">x</td>
											<td colspan="4">
												<div class="size_align" style="padding-left:10px;">
													<span class="label_txt">W</span><br>
													<input type="text" name="pal_item_width" id="pal_item_width" size="5" onKeyPress="return isNumberKey(event)" class="size_txt_center">
												</div>
											</td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td> Quantity Requested
												<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">How much of this item do they order at a time</span> </div>
											</td>
											<td colspan=6><select name="pal_quantity_requested" id="pal_quantity_requested" onChange="show_pal_otherqty_text(this)">
													<option>Select One</option>
													<option>Full Truckload</option>
													<option>Half Truckload</option>
													<option>Quarter Truckload</option>
													<option>Other</option>
												</select>
												<br>
												<input type="text" name="pal_other_quantity" id="pal_other_quantity" size="10" style="display:none;">
											</td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor2; ?>">
											<td> Frequency of Order
												<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">How often do they order this item</span> </div>
											</td>
											<td colspan=6><select name="pal_frequency_order" id="pal_frequency_order">
													<option>Select One</option>
													<option>Multiple per Week</option>
													<option>Multiple per Month</option>
													<option>Once per Month</option>
													<option>Multiple per Year</option>
													<option>Once per Year</option>
													<option>One-Time Purchase</option>
												</select></td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td> Annual Appetite
												<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">How many do they order per year?</span> </div>
											</td>
											<td colspan=6><input type="text" id="pal_how_many_order_per_yr"></td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td> What Used For?
												<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">What do they put in this item, how much weight is going in it?</span> </div>
											</td>
											<td colspan=6><input type="text" id="pal_what_used_for"></td>
										</tr>

										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td>
												Desired Price
												<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">The price point the client is trying to stay under. This value is per unit.</span> </div>
											</td>
											<td colspan=6>
												$ <input type="text" name="pal_sales_desired_price" id="pal_sales_desired_price" size="11">
											</td>
										</tr>
										<div id="pal_listdiv" style="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
										<tr bgcolor="<?php echo $rowcolor2; ?>">
											<td> Notes
												<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">Add any additional notes that will assist in selling these items. More info is better.</span> </div>
											</td>
											<td colspan=6><textarea name="pal_note" id="pal_note"></textarea></td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td> Show on Boomerang Portal? </td>
											<td colspan=6><input name="pal_client_dash_flg" type="checkbox" id="pal_client_dash_flg" value="1"></td>
										</tr>

										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td> High Value Opportunity </td>
											<td colspan=6><input name="pal_high_value_target" type="checkbox" id="pal_high_value_target" value="1"></td>
										</tr>

										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td colspan="7"><strong>Sub Type:</strong>
												<div class="tooltip_large"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext_large">Sub Type.</span> </div>
											</td>
										</tr>

										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td>Sub Type</td>
											<td colspan="7">
												<select name="box_pallet_sub_type" id="box_pallet_sub_type" onchange="box_pallet_sub_type_load_ctrl()">
													<option value="">Select One</option>
													<?php
													$q1 = "SELECT * FROM loop_boxes_sub_type_master where box_type = 'Pallets' and active_flg = 1 ORDER BY display_order ASC";
													$selected_txt = "";
													$query = db_query($q1);
													while ($fetch = array_shift($query)) {
														$id_tmp = $fetch['unqid'];

														if ($_REQUEST["box_pallet_sub_type"] == $id_tmp) {
															$selected_txt = " SELECTED ";
														} else {
															$selected_txt = "";
														}
													?>
														<option value="<?php echo $id_tmp; ?>" <?php echo $selected_txt; ?>><?php echo $fetch['sub_type_name']; ?></option>
													<?php 	} ?>
												</select>
											</td>
										</tr>

										<tr bgcolor="<?php echo $subheading2; ?>" id="div_pallet_criteria1" style="display: none;">
											<td colspan="7"><strong>Criteria of what they SHOULD be able to use:</strong>
												<div class="tooltip_large"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext_large">It will be extremely difficult to find the exact size the company is asking for, so fill out the criteria and ranges of what the company SHOULD be able to use for this item. The more flexible the criteria, the more likely UCB can find options close to them (less expensive). The more strict the criteria, the more difficult it is for UCB to find options close to them (more expensive). All options will default to include all items, edit details to scale back the options.</span> </div>
											</td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>" id="div_pallet_criteria2" style="display: none;">
											<td>Grade </td>
											<td>A</td>
											<td align="center"><input type="checkbox" id="pal_grade_a" value="Yes" checked="checked"></td>
											<td width="93px">B</td>
											<td width="30px" align="center"><input type="checkbox" id="pal_grade_b" value="Yes" checked="checked"></td>
											<td width="93px">C</td>
											<td align="center"><input type="checkbox" id="pal_grade_c" value="Yes" checked="checked"></td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor2; ?>" id="div_pallet_criteria3" style="display: none;">
											<td>Material </td>
											<td>Wooden</td>
											<td align="center"><input type="checkbox" id="pal_material_wooden" value="Yes" checked="checked"></td>
											<td>Plastic</td>
											<td align="center"><input type="checkbox" id="pal_material_plastic" value="Yes"></td>
											<td>Corrugate</td>
											<td align="center"><input type="checkbox" id="pal_material_corrugate" value="Yes"></td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>" id="div_pallet_criteria4" style="display: none;">
											<td>Entry</td>
											<td>2-way</td>
											<td align="center"><input type="checkbox" id="pal_entry_2way" value="Yes"></td>
											<td>4-way</td>
											<td colspan="3">&ensp;<input type="checkbox" id="pal_entry_4way" value="Yes" checked="checked"></td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor2; ?>" id="div_pallet_criteria5" style="display: none;">
											<td>Structure</td>
											<td>Stringer</td>
											<td align="center"><input type="checkbox" id="pal_structure_stringer" value="Yes"></td>
											<td>Block</td>
											<td colspan="3">&ensp;<input type="checkbox" id="pal_structure_block" value="Yes"></td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>" id="div_pallet_criteria6" style="display: none;">
											<td>Heat Treated</td>
											<td colspan=6>
												<select name="pal_heat_treated" id="pal_heat_treated">
													<option>Select One</option>
													<option>Required</option>
													<option>Not Required</option>
												</select>
											</td>
										</tr>



										<tr align="center" bgcolor="<?php echo $buttonrow; ?>">
											<td colspan="7" align="center">
												<input type="button" name="pallets_item_submit" value="Submit" onClick="pallets_quote_save(<?php echo $b2bid; ?>)">
											</td>
										</tr>
									</table>
								</form>
								<!-- Table for Drums/Barrels/IBC-->
								<form name="dbi">
									<table width="100%" id="5" class="table item" cellpadding="3" cellspacing="1">
										<tr>
											<td> Notes </td>
											<td><textarea name="dbi_notes" id="dbi_notes"></textarea></td>
										</tr>
										<tr align="center">
											<td colspan="4" align="center"><input type="button" name="dbi_item_submit" value="Submit" onClick="dbi_quote_save(<?php echo $b2bid; ?>)"></td>
										</tr>
									</table>
								</form>
								<!-- Recycling -->
								<form name="recycling">
									<table width="100%" id="6" class="table item" cellpadding="3" cellspacing="1">
										<tr>
											<td> Notes </td>
											<td><textarea name="recycling_notes" id="recycling_notes"></textarea></td>
										</tr>
										<tr align="center">
											<td colspan="4" align="center"><input type="button" name="recycling_item_submit" value="Submit" onClick="recycling_quote_save(<?php echo $b2bid; ?>)"></td>
										</tr>
									</table>
								</form>

								<!-- Other -->
								<form name="other">
									<table width="100%" id="7" class="table item" cellpadding="3" cellspacing="1">
										<tr bgcolor="<?php echo $rowcolor2; ?>">
											<td> Quantity Requested
												<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">How much of this item do they order at a time</span> </div>
											</td>
											<td><select name="other_quantity_requested" id="other_quantity_requested" onChange="show_other_otherqty_text(this)">
													<option>Select One</option>
													<option>Full Truckload</option>
													<option>Half Truckload</option>
													<option>Quarter Truckload</option>
													<option>Other</option>
												</select>
												<br>
												<input type="text" name="other_other_quantity" id="other_other_quantity" size="10" style="display:none;">
											</td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td> Frequency of Order
												<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">How often do they order this item</span> </div>
											</td>
											<td><select name="other_frequency_order" id="other_frequency_order">
													<option>Select One</option>
													<option>Multiple per Week</option>
													<option>Multiple per Month</option>
													<option>Once per Month</option>
													<option>Multiple per Year</option>
													<option>Once per Year</option>
													<option>One-Time Purchase</option>
												</select></td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor2; ?>">
											<td> What Used For?
												<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">What do they put in this item, how much weight is going in it?</span> </div>
											</td>
											<td><input type="text" id="other_what_used_for"></td>
										</tr>
										<div id="other_listdiv" style="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
										<tr bgcolor="<?php echo $rowcolor2; ?>">
											<td> Also Need Pallets?
												<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">Do they also order pallets</span> </div>
											</td>
											<td><input type="checkbox" name="other_need_pallets" id="other_need_pallets" value="Yes"></td>
										</tr>

										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td> Notes
												<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">Add any additional notes that will assist in selling these items. More info is better.</span> </div>
											</td>
											<td><textarea name="other_note" id="other_note"></textarea></td>
										</tr>

										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td> High Value Opportunity
												<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">High Value Opportunity</span> </div>
											</td>
											<td><input type="checkbox" name="other_high_value_target" id="other_high_value_target" value="1"></td>
										</tr>
										<tr align="center" bgcolor="<?php echo $buttonrow; ?>">
											<td colspan="4" align="center"><input type="button" name="other_item_submit" value="Submit" onClick="other_quote_save(<?php echo $b2bid; ?>)"></td>
										</tr>
									</table>
								</form>
						</tr>
						<tr>
							<td><?php
								//require("quote_request_display");
								?>
								<div id="display_quote_request">
									<?php
									//if($clientdash_flg==1)
									//{
									$getrecquery = "Select * from quote_request INNER JOIN quote_gaylord ON quote_request.quote_id = quote_gaylord.quote_id where companyID = '" . $b2bid . "' and quote_item=1 order by quote_gaylord.id asc";
									/*}
			else{
				$getrecquery = "Select * from quote_request INNER JOIN quote_gaylord ON quote_request.quote_id = quote_gaylord.quote_id where companyID = '" . $b2bid . "' and quote_item=1 and client_dash_flg=0 order by quote_gaylord.id asc";
			}*/

									//
									$g_res = db_query($getrecquery);
									//echo tep_db_num_rows($g_res);
									$chkinitials =  $_COOKIE['userinitials'];
									//
									while ($g_data = array_shift($g_res)) {
										//
										$client_dash_flg_g = $g_data["client_dash_flg"];
										//
										if ($client_dash_flg_g == 1) {
											$subheading = "#e3e1bb";
											$rowcolor1 = "#e4e4e4";
											$rowcolor2 = "#ececec";
											//$buttonrow="#d5d5d5";
											$buttonrow = "#e3e1bb";
											$subheading2 = "#d5d5d5";
										} else {
											$subheading = "#ccd9e7";
											$rowcolor1 = "#e4e4e4";
											$rowcolor2 = "#ececec";
											//$buttonrow="#d5d5d5";
											$buttonrow = "#ccd9e7";
											$subheading2 = "#d5d5d5";
										}
										//
									?>
										<div id="g<?php echo $g_data["id"] ?>">
											<table width="100%" class="table1" cellpadding="3" cellspacing="1">
												<?php

												$quote_item = $g_data["quote_item"];
												//Get Item Name
												$getquotequery = db_query("Select * from quote_request_item where quote_rq_id=?", array("i"), array($quote_item));
												$quote_item_rs = array_shift($getquotequery);
												$quote_item_name = $quote_item_rs['item'];
												$quote_date = $g_data["quote_date"];
												$g_id = $g_data["id"];
												$g_qut_id = $g_data['quote_id'];
												db_b2b();
												$chk_quote_query1 = "Select * from quote where companyID=?";
												$chk_quote_res1 = db_query($chk_quote_query1, array("i"), array($g_data['companyID']));
												$g_no_of_quote_sent = "";
												$g_no_of_quote_sent1 = "";
												$g_quote_sent_status = '';
												$qtr = 0;
												while ($quote_rows1 = array_shift($chk_quote_res1)) {
													$quote_req = $quote_rows1["quoteRequest"];
													$quote_req_id = explode(",", $quote_req);
													$total_id = count($quote_req_id);
													for ($req = 0; $req < $total_id; $req++) {
														if ($quote_req_id[$req] == $g_qut_id) {

															if ($quote_rows1["filename"] != "") {

																$qtid = $quote_rows1["ID"];
																$qtf = $quote_rows1["filename"];

																$archeive_date = new DateTime(date("Y-m-d", strtotime($quotes_archive_date)));
																$quote_date = new DateTime(date("Y-m-d", strtotime($quote_rows1["quoteDate"])));

																if ($quote_date < $archeive_date) {
																	$link = "<a target='_blank' id='quotespdfs" . $qtid . "' href='https://usedcardboardboxes.sharepoint.com/:b:/r/sites/LoopsCRMEmailAttachments/Shared%20Documents/quotes/before_oct_18_2022/" . $qtf . "'>";
																} else {
																	$link = "<a href='#' id='quotespdf" . $qtid . "' onclick=\"show_file_inviewer_pos('quotes/" . $qtf . "', 'Quote', 'quotespdf" . $qtid . "'); return false;\">";
																}
															} else {
																if ($quote_rows1["quoteType"] == "Quote") {
																	$link = "<a target='_blank' href='fullquote_mrg.php?ID=" . $quote_rows1["ID"] . "'>";
																} elseif ($quote_rows1["quoteType"] == "Quote Select") {
																	$link = "<a href='http://ucbloops.com/b2b/b2b5/quoteselect.asp?ID=" . $quote_rows1["ID"] . "' target='_blank'>";
																} else {
																	$link = "<a href='#'>";
																}
															}
															$new_quote_id = ($quote_rows1["ID"] + 3770);

															$g_no_of_quote_sent1 .= $link . $new_quote_id . "</a>, ";
															$qtr++;
															$g_no_of_quote_sent = rtrim($g_no_of_quote_sent1, ", ");
														}

														if ($qtr != 0) {
															$g_quote_sent_status = "<span style='color:#004B03;'>QUOTE SENT</span> - " . $g_no_of_quote_sent;
														} else {
															$g_quote_sent_status = "<span style='color:#FF0000;'>STILL NEEDS QUOTE SENT</span>";
														}
													}

													//}//End str pos
												}
												//
												db();

												$g_quotereq_sales_flag = "";
												$chk_deny_query = "Select * from quote_gaylord where quote_id=?";
												$chk_deny_res = db_query($chk_deny_query, array("i"), array($g_data['quote_id']));
												while ($deny_row = array_shift($chk_deny_res)) {
													$g_quotereq_sales_flag = $deny_row["g_quotereq_sales_flag"];
												}

												$g_item_sub_type = "";
												$chk_deny_query = "SELECT sub_type_name FROM loop_boxes_sub_type_master where box_type = 'Gaylord' and active_flg = 1 and unqid = '" . $g_data["g_item_sub_type"] . "' ORDER BY sub_type_name ASC";
												$chk_deny_res = db_query($chk_deny_query);
												while ($deny_row = array_shift($chk_deny_res)) {
													$g_item_sub_type = $deny_row["sub_type_name"];
												}


												if ($client_dash_flg_g == 1) {
													if ($g_quotereq_sales_flag == "Yes") {
														$quotereq_sales_flag_color = "#e3e1bb";
													} else {
														$quotereq_sales_flag_color = "#e3e1bb";
													}
												} else {
													if ($g_quotereq_sales_flag == "Yes") {
														$quotereq_sales_flag_color = "#D3FFB9";
													} else {
														$quotereq_sales_flag_color = "#C0CDDA";
													}
												}
												?>
												<tr bgcolor="#e4e4e4">
													<td style="background:<?php echo $quotereq_sales_flag_color; ?>; padding:5px;">
														<table cellpadding="3">
															<tr>
																<td style="background:<?php echo $quotereq_sales_flag_color; ?>;"><strong>Demand Entry ID: <?php echo $g_data["quote_id"]; ?></strong></td>
																<td width="200px" style="background:<?php echo $quotereq_sales_flag_color; ?>;"><strong>Quote Item: <?php echo $quote_item_name; ?></strong></td>
																<td style="background:<?php echo $quotereq_sales_flag_color; ?>;">
																	<font face="Arial, Helvetica, sans-serif" size="1" color="<?php echo $quotereq_sales_flag_color; ?>"> <a name="details_btn" id="g_btn<?php echo $g_id; ?>" onClick="show_g_details(<?php echo $g_id; ?>)" class="ex_col_btn">Expand Details</a>
											<!--<input type="button" name="details_btn" id="g_btn<?php //echo $g_id;
											?>" value="Expand Details" onClick="show_g_details(<?php //echo $g_id;
																											?>)">-->
																	</font>
																</td>
											<!-- <td style="background:<?php echo $quotereq_sales_flag_color; ?>;"><font face="Arial, Helvetica, sans-serif" size="1" color="<?php echo $quotereq_sales_flag_color; ?>">
                                    <div id="button_status<?php echo $g_data["quote_id"] ?>">
                                        <?php
										$chk_deny_query = "Select * from quote_request where quote_id=" . $g_data["quote_id"];
										$chk_deny_res = db_query($chk_deny_query);
										$deny_row = array_shift($chk_deny_res);
										if ($deny_row["deny_quote"] != "Yes") {
										?>
                                        <input type="button" name="deny_btn" id="<?php echo $g_data["quote_id"] ?>" value="Deny" onClick="deny_quote_req(<?php echo $b2bid; ?>,<?php echo $g_data["quote_id"] ?>)">
                                   
                                        <div id="hidden_deny_fields" name="hidden_deny_fields"></div>
                                    <?php
										} else {
											echo "<span style='color:#FF0000; font-size:11px; font-weight:bold;'>Denied - </span>";
									?>
                                    <a href='#' id='quotesdeny<?php echo $g_data["quote_id"] ?>' onclick="show_deny_info(<?php echo $g_data["quote_id"] ?>, <?php echo $b2bid; ?>); return false;">View</a>
                                    <?php
											//show_file_inviewer_pos   
										}
									?>
                                  </div>
                                    
								</font></td> -->
																<td style="background:<?php echo $quotereq_sales_flag_color; ?>;">
																	<font face="Arial, Helvetica, sans-serif" size="1" color="<?php echo $quotereq_sales_flag_color; ?>"> <img bgcolor="<?php echo $quotereq_sales_flag_color; ?>" src="images/edit.jpg" onClick="g_quote_edit(<?php echo $b2bid; ?>, <?php echo $g_id; ?>, <?php echo $quote_item ?>, <?php echo $client_dash_flg_g ?>)" style="cursor: pointer;"> </font>
																</td>
																<td style="background:<?php echo $quotereq_sales_flag_color; ?>;">
																	<font face="Arial, Helvetica, sans-serif" size="1" color="<?php echo $quotereq_sales_flag_color; ?>"> <img bgcolor="<?php echo $quotereq_sales_flag_color; ?>" src="images/del_img.png" onClick="g_quote_delete(<?php echo $g_id; ?>, <?php echo $quote_item ?>,<?php echo $b2bid; ?>)" style="cursor: pointer;"> </font>
																</td>
																<td style="background:<?php echo $quotereq_sales_flag_color; ?>;">
																	<font face="Arial, Helvetica, sans-serif" size="1" color="<?php echo $quotereq_sales_flag_color; ?>"> <img bgcolor="<?php echo $quotereq_sales_flag_color; ?>" src="images/move_img.png" onClick="g_quote_move(<?php echo $g_id; ?>, <?php echo $quote_item ?>,<?php echo $b2bid; ?>, <?php echo $client_dash_flg_g ?>)" style="cursor: pointer;"> </font>
																</td>
																<!-- <td style="background:<?php echo $quotereq_sales_flag_color; ?>;">
                                   <?php echo $g_quote_sent_status; ?>
                                </td> -->
																<td>
																	<font face="Arial, Helvetica, sans-serif" size="1">
																		<?php
																		if ($clientdash_flg == 0 && $g_data["client_dash_flg"] == 1) {
																			echo "Viewable on Boomerang Portal";
																		} else {
																			echo "NOT Viewable on Boomerang Portal";
																		}
																		?>
																	</font>
																</td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td>
														<div id="g_sub_table<?php echo $g_id; ?>" style="display: none;">
															<table width="100%" class="in_table_style">
																<tr bgcolor="<?php echo $subheading; ?>">
																	<td colspan="6"><strong>What Do They Buy?</strong></td>
																</tr>
																<tr bgcolor="<?php echo $rowcolor1; ?>">
																	<td> Item </td>
																	<td colspan="5"> Gaylord Totes </td>
																</tr>
																<tr bgcolor="<?php echo $rowcolor2; ?>">
																	<td>Ideal Size (in)</td>
																	<td width="130px">
																		<div class="size_align"> <span class="label_txt">L</span><br>
																			<?php echo $g_data["g_item_length"]; ?> </div>
																	</td>
																	<td width="20px" align="center">x</td>
																	<td width="130px">
																		<div class="size_align"> <span class="label_txt">W</span><br>
																			<?php echo $g_data["g_item_width"]; ?> </div>
																	</td>
																	<td width="20px" align="center">x</td>
																	<td width="130px">
																		<div class="size_align"> <span class="label_txt">H</span><br>
																			<?php echo $g_data["g_item_height"]; ?> </div>
																	</td>
																</tr>
																<tr bgcolor="<?php echo $rowcolor1; ?>">
																	<td> Quantity Requested </td>
																	<td colspan=5><?php echo $g_data["g_quantity_request"]; ?>
																		<?php
																		if ($g_data["g_quantity_request"] == "Other") {
																			echo "<br>" . $g_data["g_other_quantity"];
																		}
																		?>
																	</td>
																</tr>
																<tr bgcolor="<?php echo $rowcolor2; ?>">
																	<td> Frequency of Order </td>
																	<td colspan=5><?php echo $g_data["g_frequency_order"]; ?></td>
																</tr>
																<tr bgcolor="<?php echo $rowcolor2; ?>">
																	<td> Annual Appetite </td>
																	<td colspan=5><?php echo $g_data["g_how_many_order_per_yr"]; ?></td>
																</tr>
																<tr bgcolor="<?php echo $rowcolor1; ?>">
																	<td> What Used For? </td>
																	<td colspan=5><?php echo $g_data["g_what_used_for"]; ?></td>
																</tr>
																<!-- <tr bgcolor="<?php echo $rowcolor2; ?>">
								<td>
									Date Needed By?
								</td>
								<td colspan=5>
									<?php echo date("m/d/Y", strtotime($g_data["date_needed_by"])); ?>
								</td>
							</tr> -->
																<tr bgcolor="<?php echo $rowcolor1; ?>">
																	<td> Also Need Pallets? </td>
																	<td colspan=5><?php echo $g_data["need_pallets"]; ?></td>
																</tr>
																<tr bgcolor="<?php echo $rowcolor1; ?>">
																	<td>
																		Desired Price
																	</td>
																	<td colspan=5>
																		$<?php echo $g_data["sales_desired_price_g"]; ?>
																	</td>
																</tr>

																<tr bgcolor="<?php echo $rowcolor2; ?>">
																	<td> Notes </td>
																	<td colspan=5><?php echo $g_data["g_item_note"]; ?></td>
																</tr>

																<tr bgcolor="<?php echo $subheading2; ?>">
																	<td colspan="6"><strong>Sub Type</strong></td>
																</tr>
																<tr bgcolor="<?php echo $rowcolor2; ?>">
																	<td> Sub Type </td>
																	<td colspan=5><?php echo $g_item_sub_type; ?></td>
																</tr>

																<?php if ($g_data["g_item_sub_type"] == 11) { ?>
																	<tr bgcolor="<?php echo $subheading2; ?>">
																		<td colspan="6"><strong>Criteria of what they SHOULD be able to use:</strong></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor1; ?>">
																		<td><!-- align="right"-->
																			Height Flexibility </td>
																		<td><span class="label_txt">Min</span> <br>
																			<?php echo $g_data["g_item_min_height"]; ?></td>
																		<td align="center">-</td>
																		<td colspan="3"><span class="label_txt">Max</span> <?php echo $g_data["g_item_max_height"]; ?></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor2; ?>">
																		<td> Shape </td>
																		<td> Rectangular </td>
																		<td><?php
																			echo $g_data["g_shape_rectangular"];
																			?></td>
																		<td> Octagonal </td>
																		<td colspan="2"><?php
																						echo $g_data["g_shape_octagonal"];
																						?></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor1; ?>">
																		<td rowspan="5"> # of Walls </td>
																		<td> 1ply </td>
																		<td><?php
																			echo $g_data["g_wall_1"];
																			?></td>
																		<td> 6ply </td>
																		<td colspan="2"><?php
																						echo $g_data["g_wall_6"];
																						?></td>
																	</tr>

																	<tr bgcolor="<?php echo $rowcolor1; ?>">
																		<td> 2ply </td>
																		<td><?php
																			echo $g_data["g_wall_2"];
																			?></td>
																		<td> 7ply </td>
																		<td colspan="2"><?php
																						echo $g_data["g_wall_7"];
																						?></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor1; ?>">
																		<td> 3ply </td>
																		<td><?php
																			echo $g_data["g_wall_3"];
																			?></td>
																		<td> 8ply </td>
																		<td colspan="2"><?php
																						echo $g_data["g_wall_8"];
																						?></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor1; ?>">
																		<td> 4ply </td>
																		<td><?php
																			echo $g_data["g_wall_4"];
																			?></td>
																		<td> 9ply </td>
																		<td colspan="2"><?php
																						echo $g_data["g_wall_9"];
																						?></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor1; ?>">
																		<td> 5ply </td>
																		<td><?php
																			echo $g_data["g_wall_5"];
																			?></td>
																		<td> 10ply </td>
																		<td colspan="2"><?php
																						echo $g_data["g_wall_10"];
																						?></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor2; ?>">
																		<td rowspan="2"> Top Config </td>
																		<td> No Top </td>
																		<td><?php
																			echo $g_data["g_no_top"];
																			?></td>
																		<td> Lid Top </td>
																		<td colspan="2"><?php echo $g_data["g_lid_top"];
																						?></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor2; ?>">
																		<td> Partial Flap Top </td>
																		<td><?php echo $g_data["g_partial_flap_top"];
																			?></td>
																		<td> Full Flap Top </td>
																		<td colspan="2"><?php echo $g_data["g_full_flap_top"];
																						?></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor1; ?>">
																		<td rowspan="3"> Bottom Config </td>
																		<td> No Bottom </td>
																		<td><?php echo $g_data["g_no_bottom_config"];
																			?></td>
																		<td> Partial Flap w/ Slipsheet </td>
																		<td colspan="2"><?php echo $g_data["g_partial_flap_w"];
																						?></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor1; ?>">
																		<td> Tray Bottom </td>
																		<td><?php echo $g_data["g_tray_bottom"];
																			?></td>
																		<td> Full Flap Bottom </td>
																		<td colspan="2"><?php echo $g_data["g_full_flap_bottom"];
																						?></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor1; ?>">
																		<td> Partial Flap w/o SlipSheet </td>
																		<td colspan="4"><?php echo $g_data["g_partial_flap_wo"]; ?></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor2; ?>">
																		<td> Vents Okay? </td>
																		<td colspan=5><?php echo $g_data["g_vents_okay"];
																						?></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor2; ?>">
																		<td> High Value Opportunity </td>
																		<td colspan=5><?php if ($g_data["high_value_target"] == 1) {
																							echo "Yes";
																						} else {
																							echo "No";
																						}
																						?></td>
																	</tr>
																<?php  } ?>

																<tr bgcolor="<?php echo $rowcolor1; ?>">
																	<td colspan="6" align="right" style="padding: 4px;"> Created By:<?php echo $g_data['user_initials']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																		Date: <?php echo date("m/d/Y H:i:s", strtotime($g_data['quote_date'])); ?> &nbsp;&nbsp;&nbsp; </td>
																</tr>

																<?php //if($g_data["client_dash_flg"]==1) {  
																?>
																<tr bgcolor="<?php echo $rowcolor2; ?>">
																	<td colspan="6" align="right" style="padding: 4px;">
																		<a href="report_demand_alert_email.php?demandID=<?php echo $g_data["quote_id"]; ?>&miles=500&box_broker=1&post_industrial=1&btnsubmit=Submit&pallet_bro=1&pallet_pro=1" target="_blank">
																			<font face="Arial, Helvetica, sans-serif" size="1" color="#0000FF">Demand Alert Email List (CSV)</font>
																		</a>
																	</td>
																</tr>
																<?php //} 
																?>
															</table>
														</div>
													</td>
												</tr>
												<tr>
													<td style="background:#FFFFFF;">
														<!-- 
						<?php
										if ($clientdash_flg == 1) {
						?>
						    <a style='color:#0000FF;' id="lightbox_g<?php echo $g_id; ?>" href="javascript:void(0);" onClick="display_request_gaylords(<?php echo $b2bid; ?>,<?php echo $g_id; ?>, 1, 2, 1)"> 
						<?php
										} else {
						?>
								<a style='color:#0000FF;' id="lightbox_g<?php echo $g_id; ?>" href="javascript:void(0);" onClick="display_request_gaylords(<?php echo $b2bid; ?>,<?php echo $g_id; ?>, 1, 1, 0)">
						<?php
										}
						?>
						<font face="Arial, Helvetica, sans-serif" size="1" color="#0000FF">GAYLORD MATCHING TOOL</font></a> 
						<span id="req_gayloardtoolautoload1" name="req_gayloardtoolautoload1" style='color:red;'></span> 
						&nbsp; &nbsp; -->
														<!-- TEST GAYLORD SECTION START -->
														<?php
														//if($clientdash_flg==1)
														//{
														?>
														<!-- <a style='color:#0000FF;' id="lightbox_g<?php echo $g_id; ?>" href="javascript:void(0);" onClick="display_request_gaylords_test(<?php echo $b2bid; ?>,<?php echo $g_id; ?>, 1, 2, 1)"> -->
														<?php
														//}else{
														?>
														<a style='color:#0000FF;' id="lightbox_g<?php echo $g_id; ?>" href="javascript:void(0);" onClick="display_request_gaylords_test(<?php echo $b2bid; ?>,<?php echo $g_id; ?>, 1, 1, 0)">
															<font face="Arial, Helvetica, sans-serif" size="1" color="#0000FF">OLD GAYLORD MATCHING TOOL</font>
														</a>

														<a style='color:#0000FF;' id="lightbox_g<?php echo $g_id; ?>" href="javascript:void(0);" onClick="display_matching_tool_gaylords_v3(<?php echo $b2bid; ?>,<?php echo $g_id; ?>, 1, 1, 0)">
															<font face="Arial, Helvetica, sans-serif" size="1" color="#0000FF">GAYLORD MATCHING TOOL v3.0</font>
														</a>

														<a style='color:#0000FF;' id="lightbox_g<?php echo $g_data["quote_id"] ?>" href="javascript:void(0);" onClick="display_gaylords_load_inv(<?php echo $b2bid; ?>,<?php echo $g_data["quote_id"] ?>,'ginv_boxtype', 'Gaylord',<?php echo $g_data["g_item_sub_type"] ?>)">
															<font face="Arial, Helvetica, sans-serif" size="1" color="#0000FF">Browse Warehouse Inventory Tool</font>
														</a>

														<span id="req_gayloardtoolautoload1" name="req_gayloardtoolautoload1" style='color:red;'></span>
														<!-- TEST GAYLORD SECTION ENDS -->
														<br>
														<br>
														<?php
														if ($g_data["need_pallets"] == "Yes") { ?>

															<!-- TEST PALLET SECTION START -->
															<a style='color:#0000FF;' id="lightbox_req_pal<?php echo $g_id; ?>" href="javascript:void(0);" onClick="display_request_Pallet_tool_test(<?php echo $b2bid; ?>,2,1,0,0,<?php echo $g_data["g_item_length"]; ?>, <?php echo $g_data["g_item_width"]; ?>,<?php echo $g_id; ?>)">
																<font face="Arial, Helvetica, sans-serif" size="1" color="#0000FF">PALLET MATCHING TOOL</font>
															</a>
															<span id="req_paltoolautoload" name="req_paltoolautoload" style='color:red;'></span>

															<!-- TEST PALLET SECTION ENDS -->
															<br>
															<br>
														<?php
														}
														?>
													</td>
												</tr>
												<tr>
													<td style="background:#FFFFFF; height:4px;"></td>
												</tr>
												<?php
												//}//End if company check
												?>
											</table>
										</div>
										<!-- End div id -->
									<?php
									} //End While
									?>
								</div>
								<!-- Display 2 shipping boxes -->
								<div id="display_quote_request_ship">
									<?php
									$getrecquery2 = "Select * from quote_request INNER JOIN quote_shipping_boxes ON quote_request.quote_id = quote_shipping_boxes.quote_id where quote_item=2 order by quote_shipping_boxes.id asc";
									
									$g_res2 = db_query($getrecquery2);
									$chkinitials =  $_COOKIE['userinitials'];
									
									while ($sb_data = array_shift($g_res2)) {
										$sb_id = $sb_data["id"];
										$client_dash_flg_sb = $sb_data["client_dash_flg"];
										if ($client_dash_flg_sb == 1) {
											$subheading = "#e3e1bb";
											$rowcolor1 = "#e4e4e4";
											$rowcolor2 = "#ececec";
											//$buttonrow="#d5d5d5";
											$buttonrow = "#e3e1bb";
											$subheading2 = "#d5d5d5";
										} else {
											$subheading = "#ccd9e7";
											$rowcolor1 = "#e4e4e4";
											$rowcolor2 = "#ececec";
											//$buttonrow="#d5d5d5";
											$buttonrow = "#ccd9e7";
											$subheading2 = "#d5d5d5";
										}
										//
									?>
										<div id="sb<?php echo $sb_id; ?>">
											<table width="100%" class="table1" cellpadding="3" cellspacing="1">
												<?php
												//
												if ($sb_data["companyID"] == $b2bid) {
													$quote_item = $sb_data["quote_item"];
													//Get Item Name
													$getquotequery = db_query("Select * from quote_request_item where quote_rq_id=?", array("i"), array($quote_item));
													$quote_item_rs = array_shift($getquotequery);
													$quote_item_name = $quote_item_rs['item'];
													$quote_date = $sb_data["quote_date"];
													$sb_qut_id = $sb_data['quote_id'];
													db_b2b();
													$chk_quote_query1 = "Select * from quote where companyID=?";
													$chk_quote_res1 = db_query($chk_quote_query1, array("i"), array($sb_data['companyID']));
													$sb_no_of_quote_sent = '';
													$sb_no_of_quote_sent1 = "";
													$sb_quote_sent_status = '';

													$qtr = 0;
													while ($quote_rows1 = array_shift($chk_quote_res1)) {
														$quote_req = $quote_rows1["quoteRequest"];
														//if (strpos($quote_req, ',') !== false) {
														$quote_req_id = explode(",", $quote_req);
														$total_id = count($quote_req_id);
														// echo $total_id;

														for ($req = 0; $req < $total_id; $req++) {

															if ($quote_req_id[$req] == $sb_qut_id) {

																if ($quote_rows1["filename"] != "") {

																	$qtid = $quote_rows1["ID"];
																	$qtf = $quote_rows1["filename"];
																	//

																	$archeive_date = new DateTime(date("Y-m-d", strtotime($quotes_archive_date)));
																	$quote_date = new DateTime(date("Y-m-d", strtotime($quote_rows1["quoteDate"])));

																	if ($quote_date < $archeive_date) {
																		$link = "<a target='_blank' id='quotespdfs" . $qtid . "' href='https://usedcardboardboxes.sharepoint.com/:b:/r/sites/LoopsCRMEmailAttachments/Shared%20Documents/quotes/before_oct_18_2022/" . $qtf . "'>";
																	} else {
																		$link = "<a href='#' id='quotespdf" . $qtid . "' onclick=\"show_file_inviewer_pos('quotes/" . $qtf . "', 'Quote', 'quotespdf" . $qtid . "'); return false;\">";
																	}
																} else {
																	if ($quote_rows1["quoteType"] == "Quote") {
																		$link = "<a target='_blank' href='fullquote_mrg.php?ID=" . $quote_rows1["ID"] . "'>";
																	} elseif ($quote_rows1["quoteType"] == "Quote Select") {
																		$link = "<a href='http://b2b.usedcardboardboxes.com/b2b5/quoteselect.asp?ID=" . $quote_rows1["ID"] . "' target='_blank'>";
																	} else {
																		$link = "<a href='#'>";
																	}
																}

																//echo $quote_req_id[$req];
																$new_quote_id = ($quote_rows1["ID"] + 3770);
																$sb_no_of_quote_sent1 .= $link . $new_quote_id . "</a>, ";
																$qtr++;
																$sb_no_of_quote_sent = rtrim($sb_no_of_quote_sent1, ", ");
															}

															if ($qtr != 0) {
																$sb_quote_sent_status = "<span style='color:#004B03;'>QUOTE SENT</span> - " . $sb_no_of_quote_sent;
															} else {
																$sb_quote_sent_status = "<span style='color:#FF0000;'>STILL NEEDS QUOTE SENT</span>";
															}
														}

														//}//End str pos
													}

													//
													db();

													$sb_quotereq_sales_flag = "";
													$chk_deny_query = "Select * from quote_shipping_boxes where quote_id=?"; 
													$chk_deny_res = db_query($chk_deny_query, array("i"), array($sb_data['quote_id']));
													while ($deny_row = array_shift($chk_deny_res)) {
														$sb_quotereq_sales_flag = $deny_row["sb_quotereq_sales_flag"];
													}

													if ($client_dash_flg_sb == 1) {
														if ($sb_quotereq_sales_flag == "Yes") {
															$quotereq_sales_flag_color = "#e3e1bb";
														} else {
															$quotereq_sales_flag_color = "#e3e1bb";
														}
													} else {
														if ($sb_quotereq_sales_flag == "Yes") {
															$quotereq_sales_flag_color = "#D3FFB9";
														} else {
															$quotereq_sales_flag_color = "#C0CDDA";
														}
													}
												?>
													<tr>
														<td colspan="4" style="background:<?php echo $quotereq_sales_flag_color; ?>; padding:5px;">
															<table cellpadding="3">
																<tr>
																	<td style="background:<?php echo $quotereq_sales_flag_color; ?>;"><strong>Demand Entry ID: <?php echo $sb_data["quote_id"]; ?></strong></td>
																	<td width="200px" style="background:<?php echo $quotereq_sales_flag_color; ?>;"><strong>Sales Item: <?php echo $quote_item_name; ?></strong></td>
																	<td style="background:<?php echo $quotereq_sales_flag_color; ?>;">
																		<font face="Arial, Helvetica, sans-serif" size="1" color="<?php echo $quotereq_sales_flag_color; ?>"> <a name="details_btn" id="sb_btn<?php echo $sb_id; ?>" onClick="show_sb_details(<?php echo $sb_id; ?>)" class="ex_col_btn">Expand Details</a>
																			<!--<input type="button" name="details_btn" id="sb_btn<?php //echo $sb_id;
																																	?>" value="Expand Details" onClick="show_sb_details(<?php //echo $sb_id;
																																																			?>)">-->
																		</font>
																	</td>
																	<!-- <td style="background:<?php echo $quotereq_sales_flag_color; ?>;"><font face="Arial, Helvetica, sans-serif" size="1" color="<?php echo $quotereq_sales_flag_color; ?>">
                                    <div id="button_status<?php echo $sb_data["quote_id"] ?>">
                                        <?php
													$chk_deny_query = "Select * from quote_request where quote_id=" . $sb_data["quote_id"];
													$chk_deny_res = db_query($chk_deny_query);
													$deny_row = array_shift($chk_deny_res);
													if ($deny_row["deny_quote"] != "Yes") {
										?>
                                        <input type="button" name="deny_btn" id="<?php echo $sb_data["quote_id"] ?>" value="Deny" onClick="deny_quote_req(<?php echo $b2bid; ?>,<?php echo $sb_data["quote_id"] ?>)">
                                   
                                        <div id="hidden_deny_fields" name="hidden_deny_fields"></div>
                                    <?php
													} else {
														echo "<span style='color:#FF0000; font-size:11px; font-weight:bold;'>Denied - </span>";
									?>
                                    <a href='#' id='quotesdeny<?php echo $sb_data["quote_id"] ?>' onclick="show_deny_info(<?php echo $sb_data["quote_id"] ?>, <?php echo $b2bid; ?>); return false;">View</a>
                                    <?php
														//show_file_inviewer_pos   
													}
									?>
                                  </div>
                                    
								</font></td>-->

																	<td style="background:<?php echo $quotereq_sales_flag_color; ?>;">
																		<font face="Arial, Helvetica, sans-serif" size="1" color="<?php echo $quotereq_sales_flag_color; ?>"> <img bgcolor="<?php echo $quotereq_sales_flag_color; ?>" src="images/edit.jpg" onClick="sb_quote_edit(<?php echo $b2bid; ?>, <?php echo $sb_id; ?>, <?php echo $quote_item ?>, <?php echo $client_dash_flg_sb ?>)" style="cursor: pointer;"> </font>
																	</td>
																	<td style="background:<?php echo $quotereq_sales_flag_color; ?>;">
																		<font face="Arial, Helvetica, sans-serif" size="1" color="<?php echo $quotereq_sales_flag_color; ?>"> <img bgcolor="<?php echo $quotereq_sales_flag_color; ?>" src="images/del_img.png" onClick="sb_quote_delete(<?php echo $sb_id; ?>, <?php echo $quote_item ?>,<?php echo $b2bid; ?>)" style="cursor: pointer;"> </font>
																	</td>
																	<td style="background:<?php echo $quotereq_sales_flag_color; ?>;">
																		<font face="Arial, Helvetica, sans-serif" size="1" color="<?php echo $quotereq_sales_flag_color; ?>"> <img bgcolor="<?php echo $quotereq_sales_flag_color; ?>" src="images/move_img.png" onClick="sb_quote_move(<?php echo $sb_id; ?>, <?php echo $quote_item ?>,<?php echo $b2bid; ?>, <?php echo $client_dash_flg_sb ?>)" style="cursor: pointer;"> </font>
																	</td>
																	<!-- <td style="background:<?php echo $quotereq_sales_flag_color; ?>; padding-left: 30px;">
                                    <?php echo $sb_quote_sent_status;
									?>
                                </td>-->
																	<td>
																		<font face="Arial, Helvetica, sans-serif" size="1">
																			<?php
																			if ($sb_data["client_dash_flg"] == 1) {
																				echo "Viewable on Boomerang Portal";
																			} else {
																				echo "NOT Viewable on Boomerang Portal";
																			}
																			?>
																		</font>
																	</td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td>
															<div id="sb_sub_table<?php echo $sb_id; ?>" style="display: none;">
																<table width="100%" class="in_table_style">
																	<tr bgcolor="<?php echo $subheading; ?>">
																		<td colspan="6"><strong>What Do They Buy?</strong></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor1; ?>">
																		<td> Item </td>
																		<td colspan="5"> Shipping Boxes </td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor2; ?>">
																		<td>Ideal Size (in)</td>
																		<td width="130px"><span class="label_txt">L</span><br>
																			<?php echo $sb_data["sb_item_length"]; ?></td>
																		<td width="20px" align="center">x</td>
																		<td width="130px">
																			<div class="size_align"> <span class="label_txt">W</span><br>
																				<?php echo $sb_data["sb_item_width"]; ?> </div>
																		</td>
																		<td width="20px" align="center">x</td>
																		<td width="130px">
																			<div class="size_align"> <span class="label_txt">H</span><br>
																				<?php echo $sb_data["sb_item_height"]; ?> </div>
																		</td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor1; ?>">
																		<td> Quantity Requested </td>
																		<td colspan=5><?php echo $sb_data["sb_quantity_requested"]; ?>
																			<?php
																			if ($sb_data["sb_quantity_requested"] == "Other") {
																				echo "<br>" . $sb_data["sb_other_quantity"];
																			}
																			?></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor2; ?>">
																		<td> Frequency of Order </td>
																		<td colspan=5><?php echo $sb_data["sb_frequency_order"]; ?></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor2; ?>">
																		<td> Annual Appetite </td>
																		<td colspan=5><?php echo $sb_data["sb_how_many_order_per_yr"]; ?></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor1; ?>">
																		<td> What Used For? </td>
																		<td colspan=5><?php echo $sb_data["sb_what_used_for"]; ?></td>
																	</tr>
																	<!-- <tr bgcolor="<?php echo $rowcolor2; ?>">
								<td>
									Date Needed By?
								</td>
								<td colspan=5>
									<?php echo date("m/d/Y", strtotime($sb_data["sb_date_needed_by"])); ?>
								</td>
							</tr> -->
																	<tr bgcolor="<?php echo $rowcolor1; ?>">
																		<td> Also Need Pallets? </td>
																		<td colspan=5><?php echo $sb_data["sb_need_pallets"]; ?></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor1; ?>">
																		<td>
																			Desired Price
																		</td>
																		<td colspan=5>
																			$<?php echo $sb_data["sb_sales_desired_price"]; ?>
																		</td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor2; ?>">
																		<td> Notes </td>
																		<td colspan=5><?php echo $sb_data["sb_notes"]; ?></td>
																	</tr>
																	<tr bgcolor="<?php echo $subheading2; ?>">
																		<td colspan="6"><strong>Criteria of what they SHOULD be able to use:</strong></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor1; ?>">
																		<td colspan="6"><strong>Size Flexibility</strong></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor2; ?>">
																		<td><!-- align="right"-->
																			Length </td>
																		<td><span class="label_txt">Min</span> <br>
																			<?php echo $sb_data["sb_item_min_length"]; ?></td>
																		<td align="center">-</td>
																		<td colspan="3"><span class="label_txt">Max</span> <?php echo $sb_data["sb_item_max_length"]; ?></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor1; ?>">
																		<td><!-- align="right"-->
																			Width </td>
																		<td><span class="label_txt">Min</span> <br>
																			<?php echo $sb_data["sb_item_min_width"]; ?></td>
																		<td align="center">-</td>
																		<td colspan="3"><span class="label_txt">Max</span> <?php echo $sb_data["sb_item_max_width"]; ?></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor2; ?>">
																		<td><!-- align="right"-->
																			Height </td>
																		<td><span class="label_txt">Min</span> <br>
																			<?php echo $sb_data["sb_item_min_height"]; ?></td>
																		<td align="center">-</td>
																		<td colspan="3"><span class="label_txt">Max</span> <?php echo $sb_data["sb_item_max_height"]; ?></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor1; ?>">
																		<td><!-- align="right"-->
																			Cubic Footage </td>
																		<td><span class="label_txt">Min</span> <br>
																			<?php echo $sb_data["sb_cubic_footage_min"]; ?></td>
																		<td align="center">-</td>
																		<td colspan="3"><span class="label_txt">Max</span> <?php echo $sb_data["sb_cubic_footage_max"]; ?></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor2; ?>">
																		<td> # of Walls </td>
																		<td> 1ply </td>
																		<td><?php echo $sb_data["sb_wall_1"]; ?></td>
																		<td> 2ply </td>
																		<td colspan="2"><?php echo $sb_data["sb_wall_2"]; ?></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor1; ?>">
																		<td> Top Config </td>
																		<td> No Top </td>
																		<td><?php echo $sb_data["sb_no_top"]; ?></td>
																		<td> Full Flap Top </td>
																		<td colspan="2"><?php echo $sb_data["sb_full_flap_top"]; ?></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor1; ?>">
																		<td>&nbsp;</td>
																		<td> Partial Flap Top </td>
																		<td><?php echo $sb_data["sb_partial_flap_top"]; ?></td>
																		<td>&nbsp; </td>
																		<td colspan="2">&nbsp;</td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor2; ?>">
																		<td> Bottom Config </td>
																		<td> No Bottom </td>
																		<td><?php echo $sb_data["sb_no_bottom"]; ?></td>
																		<td> Full Flap Bottom </td>
																		<td colspan="2"><?php echo $sb_data["sb_full_flap_bottom"]; ?></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor2; ?>">
																		<td>&nbsp;</td>
																		<td> Partial Flap Bottom </td>
																		<td><?php echo $sb_data["sb_partial_flap_bottom"]; ?></td>
																		<td>&nbsp;</td>
																		<td colspan="2">&nbsp;</td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor1; ?>">
																		<td> Vents Okay? </td>
																		<td colspan=5><?php echo $sb_data["sb_vents_okay"]; ?></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor1; ?>">
																		<td> High Value Opportunity </td>
																		<td colspan=5><?php if ($sb_data["high_value_target"] == 1) {
																							echo "Yes";
																						} else {
																							echo "No";
																						}
																						?></td>
																	</tr>

																	<tr bgcolor="<?php echo $rowcolor2; ?>">
																		<td colspan="6" align="right" style="padding: 4px;"> Created By:<?php echo $sb_data['user_initials']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																			Date: <?php echo date("m/d/Y H:i:s", strtotime($sb_data['quote_date'])); ?> &nbsp;&nbsp;&nbsp; </td>
																	</tr>
																	<?php //if($sb_data["client_dash_flg"]==1) {  
																	?>
																	<tr bgcolor="<?php echo $rowcolor1; ?>">
																		<td colspan="6" align="right" style="padding: 4px;">
																			<a href="report_demand_alert_email.php?demandID=<?php echo $sb_data["quote_id"]; ?>&miles=500&box_broker=1&post_industrial=1&btnsubmit=Submit&pallet_bro=1&pallet_pro=1" target="_blank">
																				<font face="Arial, Helvetica, sans-serif" size="1" color="#0000FF">Demand Alert Email List (CSV)</font>
																			</a>
																		</td>
																	</tr>
																	<?php //} 
																	?>
																</table>
															</div>
														</td>
													</tr>
													<tr>
														<td bgcolor="#FFFFFF" style="background: #FFF;">
															<!--   <?php
																	if ($clientdash_flg == 1) {
																	?>
					   
                       		 <a style='color:#0000FF;' id="lightbox_req_shipping<?php echo $sb_id; ?>" href="javascript:void(0);" onClick="display_request_shipping_tool(<?php echo $b2bid; ?>,1,2,1,<?php echo $sb_id; ?>)"> 
							<?php
																	} else {
							?>
							<a style='color:#0000FF;' id="lightbox_req_shipping<?php echo $sb_id; ?>" href="javascript:void(0);" onClick="display_request_shipping_tool(<?php echo $b2bid; ?>,1,1,0,<?php echo $sb_id; ?>)"> 
							<?php
																	}
							?>	
						  
						  <font face="Arial, Helvetica, sans-serif" size="1" color="#0000FF">SHIPPING BOX MATCHING TOOL</font></a> <span id="req_shiptoolautoload1" name="req_shiptoolautoload1" style='color:red;'></span> 
						&nbsp; &nbsp; -->

															<!-- TEST SHIPPING SECTION START -->
															<a style='color:#0000FF;' id="lightbox_req_shipping<?php echo $sb_id; ?>" href="javascript:void(0);" onClick="display_request_shipping_tool_test(<?php echo $b2bid; ?>,1,1,0,<?php echo $sb_id; ?>)">
																<font face="Arial, Helvetica, sans-serif" size="1" color="#0000FF">SHIPPING BOX MATCHING TOOL</font>
															</a>

															<!-- SHIPPING INVENTORY -->
															<a style='color:#0000FF;' id="lightbox_g<?php echo $sb_data["quote_id"] ?>" href="javascript:void(0);" onClick="display_shipping_load_inv(<?php echo $b2bid; ?>,<?php echo $sb_data["quote_id"] ?>,'Shipping')">
																<font face="Arial, Helvetica, sans-serif" size="1" color="#0000FF">Browse Warehouse Inventory Tool</font>
															</a>

															<span id="req_shiptoolautoload1" name="req_shiptoolautoload1" style='color:red;'></span>
															<!-- TEST SHIPPING SECTION ENDS -->

															<br>
															<br>
															<?php
															if ($sb_data["sb_need_pallets"] == "Yes") { ?>

																<!-- 
						<?php
																if ($clientdash_flg == 1) {
						?>
							<a style='color:#0000FF;' id="lightbox_req_pal<?php echo $sb_id; ?>" href="javascript:void(0);" onClick="display_request_Pallet_tool(<?php echo $b2bid; ?>,1,2,1,0,<?php echo $sb_data["sb_item_length"]; ?>, <?php echo $sb_data["sb_item_width"]; ?>, <?php echo $sb_id; ?>)">
								<?php
																} else {
								?>
								<a style='color:#0000FF;' id="lightbox_req_pal<?php echo $sb_id; ?>" href="javascript:void(0);" onClick="display_request_Pallet_tool(<?php echo $b2bid; ?>,1,1,0,0,<?php echo $sb_data["sb_item_length"]; ?>, <?php echo $sb_data["sb_item_width"]; ?>, <?php echo $sb_id; ?>)">
								<?php
																}
								?>
								<font face="Arial, Helvetica, sans-serif" size="1" color="#0000FF">PALLET MATCHING TOOL</font></a> <span id="req_paltoolautoload" name="req_paltoolautoload" style='color:red;'></span> 
								&nbsp; &nbsp; -->

																<a style='color:#0000FF;' id="lightbox_req_pal<?php echo $sb_id; ?>" href="javascript:void(0);" onClick="display_request_Pallet_tool_test(<?php echo $b2bid; ?>,2,2,0,0,<?php echo $sb_data["sb_item_length"]; ?>, <?php echo $sb_data["sb_item_width"]; ?>, <?php echo $sb_id; ?>)">
																	<font face="Arial, Helvetica, sans-serif" size="1" color="#0000FF">PALLET MATCHING TOOL</font>
																</a>
																<span id="req_paltoolautoload" name="req_paltoolautoload" style='color:red;'></span>


																<br>
																<br>
															<?php
															}
															?>
														</td>
													</tr>
													<tr>
														<td style="background:#FFFFFF; height:4px;"></td>
													</tr>
												<?php
												} //End if company check
												?>
											</table>
										</div>
									<?php
									} //End While

									?>
								</div>
								<div id="display_quote_request_super">
									<!-- Display 3 -->
									<?php
									//if($clientdash_flg==1)
									//{
									$getrecquery3 = "Select * from quote_request INNER JOIN quote_supersacks ON quote_request.quote_id = quote_supersacks.quote_id where quote_item=3 order by quote_supersacks.id asc";
									/*}
			else{
				$getrecquery3 = "Select * from quote_request INNER JOIN quote_supersacks ON quote_request.quote_id = quote_supersacks.quote_id where quote_item=3 and client_dash_flg=0 order by quote_supersacks.id asc";
			}*/

									//
									$g_res3 = db_query($getrecquery3);
									//echo tep_db_num_rows($g_res);
									$chkinitials =  $_COOKIE['userinitials'];
									//

									while ($sup_data = array_shift($g_res3)) {
										//
										$client_dash_flg_sup = $sup_data["client_dash_flg"];
										//
										if ($client_dash_flg_sup == 1) {
											$subheading = "#e3e1bb";
											$rowcolor1 = "#e4e4e4";
											$rowcolor2 = "#ececec";
											//$buttonrow="#d5d5d5";
											$buttonrow = "#e3e1bb";
											$subheading2 = "#d5d5d5";
										} else {
											$subheading = "#ccd9e7";
											$rowcolor1 = "#e4e4e4";
											$rowcolor2 = "#ececec";
											//$buttonrow="#d5d5d5";
											$buttonrow = "#ccd9e7";
											$subheading2 = "#d5d5d5";
										}
										//
										//
										$sup_id = $sup_data["id"];
									?>
										<div id="sup<?php echo $sup_id; ?>">
											<table width="100%" class="table1" cellpadding="3" cellspacing="1">
												<?php
												if ($sup_data["companyID"] == $b2bid) {

													$quote_item = $sup_data["quote_item"];
													//Get Item Name
													$getquotequery = db_query("Select * from quote_request_item where quote_rq_id=?", array("i"), array($quote_item));
													$quote_item_rs = array_shift($getquotequery);
													$quote_item_name = $quote_item_rs['item'];
													$quote_date = $sup_data["quote_date"];
													$sup_qut_id = $sup_data['quote_id'];
													db_b2b();
													$chk_quote_query1 = "Select * from quote where companyID=?";
													$chk_quote_res1 = db_query($chk_quote_query1, array("i"), array($sup_data['companyID']));
													$sup_no_of_quote_sent = "";
													$sup_no_of_quote_sent1 = "";
													$sup_quote_sent_status = "";
													$qtr = 0;
													while ($quote_rows1 = array_shift($chk_quote_res1)) {
														$quote_req = $quote_rows1["quoteRequest"];
														$quote_req_id = explode(",", $quote_req);
														$total_id = count($quote_req_id);

														for ($req = 0; $req < $total_id; $req++) {

															if ($quote_req_id[$req] == $sup_qut_id) {

																if ($quote_rows1["filename"] != "") {

																	$qtid = $quote_rows1["ID"];
																	$qtf = $quote_rows1["filename"];
																	$archeive_date = new DateTime(date("Y-m-d", strtotime($quotes_archive_date)));
																	$quote_date = new DateTime(date("Y-m-d", strtotime($quote_rows1["quoteDate"])));

																	if ($quote_date < $archeive_date) {
																		$link = "<a target='_blank' id='quotespdfs" . $qtid . "' href='https://usedcardboardboxes.sharepoint.com/:b:/r/sites/LoopsCRMEmailAttachments/Shared%20Documents/quotes/before_oct_18_2022/" . $qtf . "'>";
																	} else {
																		$link = "<a href='#' id='quotespdf" . $qtid . "' onclick=\"show_file_inviewer_pos('quotes/" . $qtf . "', 'Quote', 'quotespdf" . $qtid . "'); return false;\">";
																	}
																} else {
																	if ($quote_rows1["quoteType"] == "Quote") {
																		$link = "<a target='_blank' href='fullquote_mrg.php?ID=" . $quote_rows1["ID"] . "'>";
																	} elseif ($quote_rows1["quoteType"] == "Quote Select") {
																		$link = "<a href='http://b2b.usedcardboardboxes.com/b2b5/quoteselect.asp?ID=" . $quote_rows1["ID"] . "' target='_blank'>";
																	} else {
																		$link = "<a href='#'>";
																	}
																}

																//echo $quote_req_id[$req];
																$sup_no_of_quote_sent1 .= $link . ($quote_rows1["ID"] + 3770) . "</a>, ";
																$qtr++;
																$sup_no_of_quote_sent = rtrim($sup_no_of_quote_sent1, ", ");
															}

															if ($qtr != 0) {
																$sup_quote_sent_status = "<span style='color:#004B03;'>QUOTE SENT</span> - " . $sup_no_of_quote_sent;
															} else {
																$sup_quote_sent_status = "<span style='color:#FF0000;'>STILL NEEDS QUOTE SENT</span>";
															}
														}

														//}//End str pos
													}

													//
													db();

													$sup_quotereq_sales_flag = "";
													$chk_deny_query = "Select * from quote_supersacks where quote_id=?"; 
													$chk_deny_res = db_query($chk_deny_query, array("i"), array($sup_data['quote_id']));
													while ($deny_row = array_shift($chk_deny_res)) {
														$sup_quotereq_sales_flag = $deny_row["sup_quotereq_sales_flag"];
													}

													if ($client_dash_flg_sup == 1) {
														if ($sup_quotereq_sales_flag == "Yes") {
															$quotereq_sales_flag_color = "#e3e1bb";
														} else {
															$quotereq_sales_flag_color = "#e3e1bb";
														}
													} else {
														if ($sup_quotereq_sales_flag == "Yes") {
															$quotereq_sales_flag_color = "#D3FFB9";
														} else {
															$quotereq_sales_flag_color = "#C0CDDA";
														}
													}
												?>
													<tr>
														<td colspan="4" style="background:<?php echo $quotereq_sales_flag_color; ?>; padding:5px;">
															<table cellpadding="3">
																<tr>
																	<td style="background:<?php echo $quotereq_sales_flag_color; ?>;"><strong>Demand Entry ID: <?php echo $sup_data["quote_id"]; ?></strong></td>
																	<td width="200px" style="background:<?php echo $quotereq_sales_flag_color; ?>;"><strong>Quote Item: <?php echo $quote_item_name; ?></strong></td>
																	<td style="background:<?php echo $quotereq_sales_flag_color; ?>;">
																		<font face="Arial, Helvetica, sans-serif" size="1" color="<?php echo $quotereq_sales_flag_color; ?>"> <a name="details_btn" id="sup_btn<?php echo $sup_id; ?>" onClick="show_sup_details(<?php echo $sup_id; ?>)" class="ex_col_btn">Expand Details</a>
																			<!--<input type="button" name="details_btn" id="sup_btn<?php //echo $sup_id;
																																	?>" value="Expand Details" onClick="show_sup_details(<?php //echo $sup_id;
																																																				?>)">-->
																		</font>
																	</td>
																	<!-- <td style="background:<?php echo $quotereq_sales_flag_color; ?>;"><font face="Arial, Helvetica, sans-serif" size="1" color="<?php echo $quotereq_sales_flag_color; ?>">
                                    <div id="button_status<?php echo $sup_data["quote_id"] ?>">
                                        <?php
													$chk_deny_query = "Select * from quote_request where quote_id=" . $sup_data["quote_id"];
													$chk_deny_res = db_query($chk_deny_query);
													$deny_row = array_shift($chk_deny_res);
													if ($deny_row["deny_quote"] != "Yes") {
										?>
                                        <input type="button" name="deny_btn" id="<?php echo $sup_data["quote_id"] ?>" value="Deny" onClick="deny_quote_req(<?php echo $b2bid; ?>,<?php echo $sup_data["quote_id"] ?>)">
                                   
                                        <div id="hidden_deny_fields" name="hidden_deny_fields"></div>
                                    <?php
													} else {
														echo "<span style='color:#FF0000; font-size:11px; font-weight:bold;'>Denied - </span>";
									?>
                                    <a href='#' id='quotesdeny<?php echo $sup_data["quote_id"] ?>' onclick="show_deny_info(<?php echo $sup_data["quote_id"] ?>, <?php echo $b2bid; ?>); return false;">View</a>
                                    <?php
														//show_file_inviewer_pos   
													}
									?>
                                  </div>
                                    
								</font></td>-->
																	<td style="background:<?php echo $quotereq_sales_flag_color; ?>;">
																		<font face="Arial, Helvetica, sans-serif" size="1" color="<?php echo $quotereq_sales_flag_color; ?>"> <img bgcolor="<?php echo $quotereq_sales_flag_color; ?>" src="images/edit.jpg" onClick="sup_quote_edit(<?php echo $b2bid; ?>, <?php echo $sup_id; ?>, <?php echo $quote_item ?>, <?php echo $client_dash_flg_sup ?>)" style="cursor: pointer;"> </font>
																	</td>
																	<td style="background:<?php echo $quotereq_sales_flag_color; ?>;">
																		<font face="Arial, Helvetica, sans-serif" size="1" color="<?php echo $quotereq_sales_flag_color; ?>"> <img bgcolor="<?php echo $quotereq_sales_flag_color; ?>" src="images/del_img.png" onClick="sup_quote_delete(<?php echo $sup_id; ?>, <?php echo $quote_item ?>,<?php echo $b2bid; ?>)" style="cursor: pointer;"> </font>
																	</td>
																	<td style="background:<?php echo $quotereq_sales_flag_color; ?>;">
																		<font face="Arial, Helvetica, sans-serif" size="1" color="<?php echo $quotereq_sales_flag_color; ?>"> <img bgcolor="<?php echo $quotereq_sales_flag_color; ?>" src="images/move_img.png" onClick="sup_quote_move(<?php echo $sup_id; ?>, <?php echo $quote_item ?>,<?php echo $b2bid; ?>, <?php echo $client_dash_flg_sup ?>)" style="cursor: pointer;"> </font>
																	</td>

									<!-- <td style="background:<?php echo $quotereq_sales_flag_color; ?>; padding-left: 30px;">
                                    <?php echo $sup_quote_sent_status;
									?>
									</td>-->

																	<td>
																		<font face="Arial, Helvetica, sans-serif" size="1">
																			<?php
																			if ($sup_data["client_dash_flg"] == 1) {
																				echo "Viewable on Boomerang Portal";
																			} else {
																				echo "NOT Viewable on Boomerang Portal";
																			}
																			?>
																		</font>
																	</td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td>
															<div id="sup_sub_table<?php echo $sup_id; ?>" style="display: none;">
																<table class="table_sup" width="100%">
																	<tr>
																		<td>
																			<table width="100%" class="in_table_style">
																				<tr bgcolor="<?php echo $subheading; ?>">
																					<td colspan="6"><strong>What Do They Buy?</strong></td>
																				</tr>
																				<tr bgcolor="<?php echo $rowcolor1; ?>">
																					<td> Item </td>
																					<td colspan="5"> Supersacks </td>
																				</tr>
																				<tr bgcolor="<?php echo $rowcolor2; ?>">
																					<td>Ideal Size (in)</td>
																					<td width="130px">
																						<div class="size_align"> <span class="label_txt">L</span><br>
																							<?php echo $sup_data["sup_item_length"]; ?> </div>
																					</td>
																					<td width="20px" align="center">x</td>
																					<td width="130px">
																						<div class="size_align"> <span class="label_txt">W</span><br>
																							<?php echo $sup_data["sup_item_width"]; ?> </div>
																					</td>
																					<td width="20px" align="center">x</td>
																					<td width="130px">
																						<div class="size_align"> <span class="label_txt">H</span><br>
																							<?php echo $sup_data["sup_item_height"]; ?> </div>
																					</td>
																				</tr>
																				<tr bgcolor="<?php echo $rowcolor1; ?>">
																					<td> Quantity Requested </td>
																					<td colspan=5><?php echo $sup_data["sup_quantity_requested"]; ?>
																						<?php
																						if ($sup_data["sup_quantity_requested"] == "Other") {
																							echo "<br>" . $sup_data["sup_other_quantity"];
																						}
																						?></td>
																				</tr>
																				<tr bgcolor="<?php echo $rowcolor2; ?>">
																					<td> Frequency of Order </td>
																					<td colspan=5><?php echo $sup_data["sup_frequency_order"]; ?></td>
																				</tr>
																				<tr bgcolor="<?php echo $rowcolor1; ?>">
																					<td> What Used For? </td>
																					<td colspan=5><?php echo $sup_data["sup_what_used_for"]; ?></td>
																				</tr>
																				
																				<tr bgcolor="<?php echo $rowcolor1; ?>">
																					<td> Also Need Pallets? </td>
																					<td colspan=5><?php echo $sup_data["sup_need_pallets"]; ?></td>
																				</tr>
																				<tr bgcolor="<?php echo $rowcolor1; ?>">
																					<td>
																						Desired Price
																					</td>
																					<td colspan=5>
																						$<?php echo $sup_data["sup_sales_desired_price"]; ?>
																					</td>
																				</tr>
																				<tr bgcolor="<?php echo $rowcolor2; ?>">
																					<td> Notes </td>
																					<td colspan=5><?php echo $sup_data["sup_notes"]; ?></td>
																				</tr>
																				<tr bgcolor="<?php echo $rowcolor2; ?>">
																					<td> High Value Opportunity </td>
																					<td colspan=5><?php if ($sup_data["high_value_target"] == 1) {
																										echo "Yes";
																									} else {
																										echo "No";
																									}
																									?></td>
																				</tr>

																				<tr bgcolor="<?php echo $rowcolor2; ?>">
																					<td colspan="6" align="right" style="padding: 4px;"> Created By:<?php echo $sup_data['user_initials']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																						Date: <?php echo date("m/d/Y H:i:s", strtotime($sup_data['quote_date'])); ?> &nbsp;&nbsp;&nbsp; </td>
																				</tr>
																				<?php //if($sup_data["client_dash_flg"]==1) {  
																				?>
																				<tr bgcolor="<?php echo $rowcolor1; ?>">
																					<td colspan="6" align="right" style="padding: 4px;">
																						<a href="report_demand_alert_email.php?demandID=<?php echo $sup_data["quote_id"]; ?>&miles=500&box_broker=1&post_industrial=1&btnsubmit=Submit&pallet_bro=1&pallet_pro=1" target="_blank">
																							<font face="Arial, Helvetica, sans-serif" size="1" color="#0000FF">Demand Alert Email List (CSV)</font>
																						</a>
																					</td>
																				</tr>
																				<?php //} 
																				?>

																			</table>
																		</td>
																	</tr>
																</table>
															</div>
														</td>
													</tr>
													<tr>
														<td style="background:#FFFFFF;">
															<!-- 
						  <?php
													if ($clientdash_flg == 1) {
							?>
						  <a style='color:#0000FF;' id="lightbox_req_supersacks<?php echo $sup_id; ?>" href="javascript:void(0);" onClick="display_request_supersacks_tool(<?php echo $b2bid; ?>,1,2,1,<?php echo $sup_id; ?>)">
						 <?php
													} else {
							?>
							  <a style='color:#0000FF;' id="lightbox_req_supersacks<?php echo $sup_id; ?>" href="javascript:void(0);" onClick="display_request_supersacks_tool(<?php echo $b2bid; ?>,1,1,0,<?php echo $sup_id; ?>)">
							 <?php
													}
								?>
						  <font face="Arial, Helvetica, sans-serif" size="1" color="#0000FF">SUPERSACKS MATCHING TOOL</font></a> <span id="req_supersackstoolautoload" name="req_supersackstoolautoload" style='color:red;'></span>
						&nbsp; &nbsp; -->
															<!-- TEST SUPERSACKS SECTION START -->
															<a style='color:#0000FF;' id="lightbox_req_supersacks<?php echo $sup_id; ?>" href="javascript:void(0);" onClick="display_request_supersacks_tool_test(<?php echo $b2bid; ?>,1,1,0,<?php echo $sup_id; ?>)">
																<font face="Arial, Helvetica, sans-serif" size="1" color="#0000FF">SUPERSACKS MATCHING TOOL</font>
															</a>

															<span id="req_supersackstoolautoload" name="req_supersackstoolautoload" style='color:red;'></span>
															<!-- TEST SUPERSACKS SECTION ENDS -->
															<br>
															<br>
															<?php
															if ($sup_data["sup_need_pallets"] == "Yes") {
																if ($clientdash_flg == 1) {
															?>
																	<!-- 
                        <a style='color:#0000FF;' id="lightbox_req_pal<?php echo $sup_id; ?>" href="javascript:void(0);" onClick="display_request_Pallet_tool(<?php echo $b2bid; ?>,1,2,1,0,<?php echo $sup_data["sup_item_length"]; ?>, <?php echo $sup_data["sup_item_width"]; ?>, <?php echo $sup_id; ?>)">
						<?php
																} else {
						?>
							 <a style='color:#0000FF;' id="lightbox_req_pal<?php echo $sup_id; ?>" href="javascript:void(0);" onClick="display_request_Pallet_tool(<?php echo $b2bid; ?>,1,1,0,0,<?php echo $sup_data["sup_item_length"]; ?>, <?php echo $sup_data["sup_item_width"]; ?>, <?php echo $sup_id; ?>)">
							<?php
																}
							?>
							
							<font face="Arial, Helvetica, sans-serif" size="1" color="#0000FF">PALLET MATCHING TOOL</font></a>
							  
							  <span id="req_paltoolautoload" name="req_paltoolautoload" style='color:red;'></span>
						&nbsp; &nbsp; -->

																	<!-- TEST PALLET SECTION START -->
																	<a style='color:#0000FF;' id="lightbox_req_pal<?php echo $sup_id; ?>" href="javascript:void(0);" onClick="display_request_Pallet_tool_test(<?php echo $b2bid; ?>,2,1,0,0,<?php echo $sup_data["sup_item_length"]; ?>, <?php echo $sup_data["sup_item_width"]; ?>, <?php echo $sup_id; ?>)">
																		<font face="Arial, Helvetica, sans-serif" size="1" color="#0000FF">PALLET MATCHING TOOL</font>
																	</a>
																	<span id="req_paltoolautoload" name="req_paltoolautoload" style='color:red;'></span>

																	<!-- TEST PALLET SECTION ENDS -->

																	<br>
																	<br>
																<?php
															}
																?>
														</td>
													</tr>
													<tr>
														<td colspan="4" style="background:#FFFFFF; height:4px;"></td>
													</tr>
												<?php
												} //End if company check
												?>
											</table>
										</div>
									<?php
									} //End While

									?>
								</div>
								<div id="display_quote_request_pallets">
									<!-- Display 4 -->
									<?php
									//if($clientdash_flg==1)
									//{

									$getrecquery2 = "Select * from quote_request INNER JOIN quote_pallets ON quote_request.quote_id = quote_pallets.quote_id where quote_item=4 order by quote_pallets.id asc";
									/*}
			else{
				$getrecquery2 = "Select * from quote_request INNER JOIN quote_pallets ON quote_request.quote_id = quote_pallets.quote_id where quote_item=4 and client_dash_flg=0 order by quote_pallets.id asc";
			}*/

									//
									$g_res2 = db_query($getrecquery2);
									//echo tep_db_num_rows($g_res);
									$chkinitials =  $_COOKIE['userinitials'];
									//
									while ($pal_data = array_shift($g_res2)) {
										$pal_id = $pal_data["id"];
										//
										$client_dash_flg_pal = $pal_data["client_dash_flg"];
										//
										if ($client_dash_flg_pal == 1) {
											$subheading = "#e3e1bb";
											$rowcolor1 = "#e4e4e4";
											$rowcolor2 = "#ececec";
											//$buttonrow="#d5d5d5";
											$buttonrow = "#e3e1bb";
											$subheading2 = "#d5d5d5";
										} else {
											$subheading = "#ccd9e7";
											$rowcolor1 = "#e4e4e4";
											$rowcolor2 = "#ececec";
											//$buttonrow="#d5d5d5";
											$buttonrow = "#ccd9e7";
											$subheading2 = "#d5d5d5";
										}
										//
									?>
										<div id="pal<?php echo $pal_id; ?>">
											<table width="100%" class="table1" cellpadding="3" cellspacing="1">
												<?php
												//
												if ($pal_data["companyID"] == $b2bid) {
													
													$quote_item = $pal_data["quote_item"];
													//Get Item Name
													$getquotequery = db_query("Select * from quote_request_item where quote_rq_id=?", array("i"), array($quote_item));
													$quote_item_rs = array_shift($getquotequery);
													$quote_item_name = $quote_item_rs['item'];
													$quote_date = $pal_data["quote_date"];
													$pal_qut_id = $pal_data['quote_id'];
													db_b2b();
													$chk_quote_query1 = "Select * from quote where companyID=?";
													$chk_quote_res1 = db_query($chk_quote_query1, array("i"), array($pal_data['companyID']));
													$pal_no_of_quote_sent = '';
													$pal_no_of_quote_sent1 = "";
													$pal_quote_sent_status = "";
													$qtr = 0;
													while ($quote_rows1 = array_shift($chk_quote_res1)) {
														$quote_req = $quote_rows1["quoteRequest"];
														$quote_req_id = explode(",", $quote_req);
														$total_id = count($quote_req_id);
														
														for ($req = 0; $req < $total_id; $req++) {

															if ($quote_req_id[$req] == $pal_qut_id) {

																if ($quote_rows1["filename"] != "") {

																	$qtid = $quote_rows1["ID"];
																	$qtf = $quote_rows1["filename"];
																	//
																	$archeive_date = new DateTime(date("Y-m-d", strtotime($quotes_archive_date)));
																	$quote_date = new DateTime(date("Y-m-d", strtotime($quote_rows1["quoteDate"])));

																	if ($quote_date < $archeive_date) {
																		$link = "<a target='_blank' id='quotespdfs" . $qtid . "' href='https://usedcardboardboxes.sharepoint.com/:b:/r/sites/LoopsCRMEmailAttachments/Shared%20Documents/quotes/before_oct_18_2022/" . $qtf . "'>";
																	} else {
																		$link = "<a href='#' id='quotespdf" . $qtid . "' onclick=\"show_file_inviewer_pos('quotes/" . $qtf . "', 'Quote', 'quotespdf" . $qtid . "'); return false;\">";
																	}
																} else {
																	if ($quote_rows1["quoteType"] == "Quote") {
																		$link = "<a target='_blank' href='fullquote_mrg.php?ID=" . $quote_rows1["ID"] . "'>";
																	} elseif ($quote_rows1["quoteType"] == "Quote Select") {
																		$link = "<a href='http://b2b.usedcardboardboxes.com/b2b5/quoteselect.asp?ID=" . $quote_rows1["ID"] . "' target='_blank'>";
																	} else {
																		$link = "<a href='#'>";
																	}
																}

																//echo $quote_req_id[$req];
																$pal_no_of_quote_sent1 .= $link . ($quote_rows1["ID"] + 3770) . "</a>, ";
																$qtr++;
																$pal_no_of_quote_sent = rtrim($pal_no_of_quote_sent1, ", ");
															}

															if ($qtr != 0) {
																$pal_quote_sent_status = "<span style='color:#004B03;'>QUOTE SENT</span> - " . $pal_no_of_quote_sent;
															} else {
																$pal_quote_sent_status = "<span style='color:#FF0000;'>STILL NEEDS QUOTE SENT</span>";
															}
														}

														//}//End str pos
													}

													//
													db();
													//

													$pal_quotereq_sales_flag = "";
													$chk_deny_query = "Select * from quote_pallets where quote_id= ?"; 
													$chk_deny_res = db_query($chk_deny_query, array("i"), array($pal_data['quote_id']));
													while ($deny_row = array_shift($chk_deny_res)) {
														$pal_quotereq_sales_flag = $deny_row["pal_quotereq_sales_flag"];
													}

													$pal_item_sub_type = "";
													$chk_deny_query = "SELECT sub_type_name FROM loop_boxes_sub_type_master where box_type = 'Pallets' and active_flg = 1 and unqid = '" . $pal_data["pal_item_sub_type"] . "' ORDER BY sub_type_name ASC";
													//echo $chk_deny_query . "<br>";
													$chk_deny_res = db_query($chk_deny_query);
													while ($deny_row = array_shift($chk_deny_res)) {
														$pal_item_sub_type = $deny_row["sub_type_name"];
													}

													if ($client_dash_flg_pal == 1) {
														if ($pal_quotereq_sales_flag == "Yes") {
															$quotereq_sales_flag_color = "#e3e1bb";
														} else {
															$quotereq_sales_flag_color = "#e3e1bb";
														}
													} else {
														if ($pal_quotereq_sales_flag == "Yes") {
															$quotereq_sales_flag_color = "#D3FFB9";
														} else {
															$quotereq_sales_flag_color = "#C0CDDA";
														}
													}
												?>
													<tr>
														<td colspan="4" style="background:<?php echo $quotereq_sales_flag_color; ?>; padding:5px;">
															<table cellpadding="3">
																<tr>
																	<td style="background:<?php echo $quotereq_sales_flag_color; ?>;"><strong>Demand Entry ID: <?php echo $pal_data["quote_id"]; ?></strong></td>
																	<td width="200px" style="background:<?php echo $quotereq_sales_flag_color; ?>;"><strong>Quote Item: <?php echo $quote_item_name; ?></strong></td>
																	<td style="background:<?php echo $quotereq_sales_flag_color; ?>;">
																		<font face="Arial, Helvetica, sans-serif" size="1" color="<?php echo $quotereq_sales_flag_color; ?>"> <a name="details_btn" id="pal_btn<?php echo $pal_id; ?>" onClick="show_pal_details(<?php echo $pal_id; ?>)" class="ex_col_btn">Expand Details</a>

																		</font>
																	</td>
																	<!-- <td style="background:<?php echo $quotereq_sales_flag_color; ?>;"><font face="Arial, Helvetica, sans-serif" size="1" color="<?php echo $quotereq_sales_flag_color; ?>">
                                    <div id="button_status<?php echo $pal_data["quote_id"] ?>">
                                        <?php
													$chk_deny_query = "Select * from quote_request where quote_id=" . $pal_data["quote_id"];
													$chk_deny_res = db_query($chk_deny_query);
													$deny_row = array_shift($chk_deny_res);
													if ($deny_row["deny_quote"] != "Yes") {
										?>
                                        <input type="button" name="deny_btn" id="<?php echo $pal_data["quote_id"] ?>" value="Deny" onClick="deny_quote_req(<?php echo $b2bid; ?>,<?php echo $pal_data["quote_id"] ?>)">
                                   
                                        <div id="hidden_deny_fields" name="hidden_deny_fields"></div>
                                    <?php
													} else {
														echo "<span style='color:#FF0000; font-size:11px; font-weight:bold;'>Denied - </span>";
									?>
                                    <a href='#' id='quotesdeny<?php echo $pal_data["quote_id"] ?>' onclick="show_deny_info(<?php echo $pal_data["quote_id"] ?>, <?php echo $b2bid; ?>); return false;">View</a>
                                    <?php
														//show_file_inviewer_pos   
													}
									?>
                                  </div>
                                    
								</font></td> -->
																	<td style="background:<?php echo $quotereq_sales_flag_color; ?>;">
																		<font face="Arial, Helvetica, sans-serif" size="1" color="<?php echo $quotereq_sales_flag_color; ?>"> <img bgcolor="<?php echo $quotereq_sales_flag_color; ?>" src="images/edit.jpg" onClick="pal_quote_edit(<?php echo $b2bid; ?>, <?php echo $pal_id; ?>, <?php echo $quote_item ?>, <?php echo $client_dash_flg_pal ?>)" style="cursor: pointer;"> </font>
																	</td>
																	<td style="background:<?php echo $quotereq_sales_flag_color; ?>;">
																		<font face="Arial, Helvetica, sans-serif" size="1" color="<?php echo $quotereq_sales_flag_color; ?>"> <img bgcolor="<?php echo $quotereq_sales_flag_color; ?>" src="images/del_img.png" onClick="pal_quote_delete(<?php echo $pal_id; ?>, <?php echo $quote_item ?>,<?php echo $b2bid; ?>)" style="cursor: pointer;"> </font>
																	</td>
																	<td style="background:<?php echo $quotereq_sales_flag_color; ?>;">
																		<font face="Arial, Helvetica, sans-serif" size="1" color="<?php echo $quotereq_sales_flag_color; ?>"> <img bgcolor="<?php echo $quotereq_sales_flag_color; ?>" src="images/move_img.png" onClick="pal_quote_move(<?php echo $pal_id; ?>, <?php echo $quote_item ?>,<?php echo $b2bid; ?>,<?php echo $client_dash_flg_pal ?>)" style="cursor: pointer;"> </font>
																	</td>
																	<!-- <td style="background:<?php echo $quotereq_sales_flag_color; ?>; padding-left: 30px;">
                                    <?php echo $pal_quote_sent_status;
									?>
									</td>-->

																	<td>
																		<font face="Arial, Helvetica, sans-serif" size="1">
																			<?php
																			if ($clientdash_flg == 0 && $pal_data["client_dash_flg"] == 1) {
																				echo "Viewable on Boomerang Portal";
																			} else {
																				echo "NOT Viewable on Boomerang Portal";
																			}
																			?>
																		</font>
																	</td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td>
															<div id="pal_sub_table<?php echo $pal_id; ?>" style="display: none;">
																<table width="100%" class="in_table_style">
																	<tr bgcolor="<?php echo $subheading; ?>">
																		<td colspan="7"><strong>What Do They Buy?</strong></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor1; ?>">
																		<td width="200px"> Item </td>
																		<td colspan="6"> Pallets </td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor2; ?>">
																		<td>Ideal Size (in)</td>
																		<td width="93px">
																			<div class="size_align"> <span class="label_txt">L</span><br>
																				<?php echo $pal_data["pal_item_length"]; ?> </div>
																		</td>
																		<td width="30px" align="center">x</td>
																		<td colspan="4">
																			<div class="size_align"> <span class="label_txt">W</span><br>
																				<?php echo $pal_data["pal_item_width"]; ?> </div>
																		</td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor1; ?>">
																		<td> Quantity Requested </td>
																		<td colspan=6><?php echo $pal_data["pal_quantity_requested"]; ?>
																			<?php
																			if ($pal_data["pal_quantity_requested"] == "Other") {
																				echo "<br>" . $pal_data["pal_other_quantity"];
																			}
																			?></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor2; ?>">
																		<td> Frequency of Order </td>
																		<td colspan=6><?php echo $pal_data["pal_frequency_order"]; ?></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor2; ?>">
																		<td> Annual Appetite </td>
																		<td colspan=6><?php echo $pal_data["pal_how_many_order_per_yr"]; ?></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor1; ?>">
																		<td> What Used For? </td>
																		<td colspan=6><?php echo $pal_data["pal_what_used_for"]; ?></td>
																	</tr>
																	<!-- <tr bgcolor="<?php echo $rowcolor2; ?>">
									<td>
										Date Needed By?
									</td>
									<td colspan=5>
										<?php echo date("m/d/Y", strtotime($pal_data["pal_date_needed_by"])); ?>
									</td>
								</tr> -->
																	<tr bgcolor="<?php echo $rowcolor1; ?>">
																		<td>
																			Desired Price
																		</td>
																		<td colspan=6>
																			$<?php echo $pal_data["pal_sales_desired_price"]; ?>
																		</td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor1; ?>">
																		<td> Notes </td>
																		<td colspan=6><?php echo $pal_data["pal_note"]; ?></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor1; ?>">
																		<td> High Value Opportunity </td>
																		<td colspan=5><?php if ($pal_data["high_value_target"] == 1) {
																							echo "Yes";
																						} else {
																							echo "No";
																						}
																						?></td>
																	</tr>

																	<tr bgcolor="#d5d5d5">
																		<td colspan="7"><strong>Sub Type</strong></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor1; ?>">
																		<td> Sub Type </td>
																		<td colspan=7><?php echo $pal_item_sub_type; ?></td>
																	</tr>

																	<?php if ($pal_item_sub_type == 17) { ?>
																		<tr bgcolor="#d5d5d5">
																			<td colspan="7"><strong>Criteria of what they SHOULD be able to use:</strong>
																			</td>
																		</tr>
																		<tr bgcolor="<?php echo $rowcolor1; ?>">
																			<td>Grade </td>
																			<td>A</td>
																			<td align="center"><?php echo $pal_data['pal_grade_a']; ?></td>
																			<td width="93px">B</td>
																			<td width="30px" align="center"><?php echo $pal_data['pal_grade_b']; ?></td>
																			<td width="93px">C</td>
																			<td align="center"><?php echo $pal_data['pal_grade_c']; ?></td>
																		</tr>
																		<tr bgcolor="<?php echo $rowcolor2; ?>">
																			<td>Material </td>
																			<td>Wooden</td>
																			<td align="center"><?php echo $pal_data['pal_material_wooden']; ?></td>
																			<td>Plastic</td>
																			<td align="center"><?php echo $pal_data['pal_material_plastic']; ?></td>
																			<td>Corrugate</td>
																			<td align="center"><?php echo $pal_data['pal_material_corrugate']; ?>
																			</td>
																		</tr>
																		<tr bgcolor="<?php echo $rowcolor1; ?>">
																			<td>Entry</td>
																			<td>2-way</td>
																			<td align="center"><?php echo $pal_data['pal_entry_2way']; ?>
																			</td>
																			<td>4-way</td>
																			<td colspan="3">&ensp;<?php echo $pal_data['pal_entry_4way']; ?>
																			</td>
																		</tr>
																		<tr bgcolor="<?php echo $rowcolor2; ?>">
																			<td>Structure</td>
																			<td>Stringer</td>
																			<td align="center"><?php echo $pal_data['pal_structure_stringer']; ?></td>
																			<td>Block</td>
																			<td colspan="3">&ensp;<?php echo $pal_data['pal_structure_block']; ?></td>
																		</tr>
																		<tr bgcolor="<?php echo $rowcolor1; ?>">
																			<td>Heat Treated</td>
																			<td colspan=6><?php echo $pal_data['pal_heat_treated']; ?></td>
																		</tr>
																	<?php  } ?>
																	<tr bgcolor="<?php echo $rowcolor2; ?>">
																		<td colspan="7" align="right" style="padding: 4px;"> Created By:<?php echo $pal_data['user_initials']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																			Date: <?php echo date("m/d/Y H:i:s", strtotime($pal_data['quote_date'])); ?> &nbsp;&nbsp;&nbsp; </td>
																	</tr>
																	<?php //if($pal_data["client_dash_flg"]==1) {  
																	?>
																	<tr bgcolor="<?php echo $rowcolor1; ?>">
																		<td colspan="6" align="right" style="padding: 4px;">
																			<a href="report_demand_alert_email.php?demandID=<?php echo $pal_data["quote_id"]; ?>&miles=500&box_broker=1&post_industrial=1&btnsubmit=Submit&pallet_bro=1&pallet_pro=1" target="_blank">
																				<font face="Arial, Helvetica, sans-serif" size="1" color="#0000FF">Demand Alert Email List (CSV)</font>
																			</a>
																		</td>
																	</tr>
																	<?php //} 
																	?>
																</table>
															</div>
														</td>
													</tr>
													<tr>
														<td style="background:#FFFFFF;">
															<!-- 
						   <?php
													if ($client_dash_flg_pal == 1) {
							?>
						  <a style='color:#0000FF;' id="lightbox_req_pal<?php echo $pal_id; ?>" href="javascript:void(0);" onClick="display_request_Pallet_tool(<?php echo $b2bid; ?>,1,2,1,<?php echo $pal_id; ?>)"> 
						  <?php
													} else {
							?>
						  <a style='color:#0000FF;' id="lightbox_req_pal<?php echo $pal_id; ?>" href="javascript:void(0);" onClick="display_request_Pallet_tool(<?php echo $b2bid; ?>,1,1,0,<?php echo $pal_id; ?>)"> 
						<?php
													}
						?>
							  <font face="Arial, Helvetica, sans-serif" size="1" color="#0000FF">PALLET MATCHING TOOL</font></a>
						  <span id="req_paltoolautoload" name="req_paltoolautoload" style='color:red;'></span> 
						&nbsp; &nbsp; -->

															<!-- TEST PALLET SECTION START -->
															<a style='color:#0000FF;' id="lightbox_req_pal<?php echo $pal_id; ?>" href="javascript:void(0);" onClick="display_request_Pallet_tool_test(<?php echo $b2bid; ?>,2,1,0,<?php echo $pal_id; ?>)">
																<font face="Arial, Helvetica, sans-serif" size="1" color="#0000FF">PALLET MATCHING TOOL</font>
															</a>
															<span id="req_paltoolautoload" name="req_paltoolautoload" style='color:red;'></span>

															<a style='color:#0000FF;' id="lightbox_req_pal_new<?php echo $pal_id; ?>" href="javascript:void(0);" onClick="display_request_Pallet_tool_v3(<?php echo $b2bid; ?>,2,1,0,<?php echo $pal_id; ?>)">
																<font face="Arial, Helvetica, sans-serif" size="1" color="#0000FF">TEST PALLET MATCHING TOOL V3</font>
															</a>
															<!-- TEST PALLET SECTION ENDS -->

															<a style='color:#0000FF;' id="lightbox_req_pal<?php echo $pal_id; ?>" href="javascript:void(0);" onClick="display_request_Pallet_tool_v4(<?php echo $b2bid; ?>,2,1,0,<?php echo $pal_id; ?>)">
																<font face="Arial, Helvetica, sans-serif" size="1" color="#0000FF">TEST PALLET MATCHING TOOL V4</font>
															</a>

															<br>
															<br>
														</td>
													</tr>
													<tr>
														<td colspan="4" style="background:#FFFFFF; height:4px;"></td>
													</tr>
												<?php
												} //End if company check
												?>
											</table>
										</div>
									<?php
									} //End While

									?>
								</div>
								<div id="display_quote_request_other">
									<!-- Display 7 -->
									<?php
									//if($clientdash_flg==1)
									//{
									$getrecquery2 = "Select * from quote_request INNER JOIN quote_other ON quote_request.quote_id = quote_other.quote_id where quote_item=7 order by quote_other.id asc";
									/*}
			else{
				$getrecquery2 = "Select * from quote_request INNER JOIN quote_other ON quote_request.quote_id = quote_other.quote_id where quote_item=7 and client_dash_flg=0 order by quote_other.id asc";
			}*/

									//
									$g_res2 = db_query($getrecquery2);
									//echo tep_db_num_rows($g_res);
									$chkinitials =  $_COOKIE['userinitials'];
									//
									while ($other_data = array_shift($g_res2)) {
										$other_id = $other_data["id"];
										//
										$client_dash_flg_other = $other_data["client_dash_flg"];
										//
										if ($client_dash_flg_other == 1) {
											$subheading = "#e3e1bb";
											$rowcolor1 = "#e4e4e4";
											$rowcolor2 = "#ececec";
											//$buttonrow="#d5d5d5";
											$buttonrow = "#e3e1bb";
											$subheading2 = "#d5d5d5";
										} else {
											$subheading = "#ccd9e7";
											$rowcolor1 = "#e4e4e4";
											$rowcolor2 = "#ececec";
											//$buttonrow="#d5d5d5";
											$buttonrow = "#ccd9e7";
											$subheading2 = "#d5d5d5";
										}
										//
										//
									?>
										<div id="other<?php echo $other_id; ?>">
											<table width="100%" class="table1" cellpadding="3" cellspacing="1">
												<?php
												//
												if ($other_data["companyID"] == $b2bid) {
													$quote_item = $other_data["quote_item"];
													//Get Item Name
													$getquotequery = db_query("Select * from quote_request_item where quote_rq_id= ?", array("i"), array($quote_item));
													$quote_item_rs = array_shift($getquotequery);
													$quote_item_name = $quote_item_rs['item'];
													//
													$quote_date = $other_data["quote_date"];
													//
													//---------------check quote send or not-------------------------------
													$other_qut_id = $other_data['quote_id'];
													db_b2b();
													$chk_quote_query1 = "Select * from quote where companyID= ?";
													$chk_quote_res1 = db_query($chk_quote_query1, array("i"), array($other_data['companyID']));
													$other_no_of_quote_sent = "";
													$other_no_of_quote_sent1 = "";
													$other_quote_sent_status = "";
													$qtr = 0;
													while ($quote_rows1 = array_shift($chk_quote_res1)) {
														$quote_req = $quote_rows1["quoteRequest"];
														//if (strpos($quote_req, ',') !== false) {
														$quote_req_id = explode(",", $quote_req);
														$total_id = count($quote_req_id);
														// echo $total_id;

														for ($req = 0; $req < $total_id; $req++) {

															if ($quote_req_id[$req] == $other_qut_id) {

																if ($quote_rows1["filename"] != "") {

																	$qtid = $quote_rows1["ID"];
																	$qtf = $quote_rows1["filename"];
																	//
																	$archeive_date = new DateTime(date("Y-m-d", strtotime($quotes_archive_date)));
																	$quote_date = new DateTime(date("Y-m-d", strtotime($quote_rows1["quoteDate"])));

																	if ($quote_date < $archeive_date) {
																		$link = "<a target='_blank' id='quotespdfs" . $qtid . "' href='https://usedcardboardboxes.sharepoint.com/:b:/r/sites/LoopsCRMEmailAttachments/Shared%20Documents/quotes/before_oct_18_2022/" . $qtf . "'>";
																	} else {
																		$link = "<a href='#' id='quotespdf" . $qtid . "' onclick=\"show_file_inviewer_pos('quotes/" . $qtf . "', 'Quote', 'quotespdf" . $qtid . "'); return false;\">";
																	}
																} else {
																	if ($quote_rows1["quoteType"] == "Quote") {
																		$link = "<a target='_blank' href='fullquote_mrg.php?ID=" . $quote_rows1["ID"] . "'>";
																	} elseif ($quote_rows1["quoteType"] == "Quote Select") {
																		$link = "<a href='http://b2b.usedcardboardboxes.com/b2b5/quoteselect.asp?ID=" . $quote_rows1["ID"] . "' target='_blank'>";
																	} else {
																		$link = "<a href='#'>";
																	}
																}

																//echo $quote_req_id[$req];
																$other_no_of_quote_sent1 .= $link . ($quote_rows1["ID"] + 3770) . "</a>, ";
																$qtr++;
																$other_no_of_quote_sent = rtrim($other_no_of_quote_sent1, ", ");
															}

															if ($qtr != 0) {
																$other_quote_sent_status = "<span style='color:#004B03;'>QUOTE SENT</span> - " . $other_no_of_quote_sent;
															} else {
																$other_quote_sent_status = "<span style='color:#FF0000;'>STILL NEEDS QUOTE SENT</span>";
															}
														}

														//}//End str pos
													}

													//
													db();
													//
													$other_quotereq_sales_flag = "";
													$chk_deny_query = "Select * from quote_other where quote_id= ?";
													$chk_deny_res = db_query($chk_deny_query, array("i"), array($other_data['quote_id']));
													while ($deny_row = array_shift($chk_deny_res)) {
														$other_quotereq_sales_flag = $deny_row["other_quotereq_sales_flag"];
													}

													if ($client_dash_flg_other == 1) {
														if ($other_quotereq_sales_flag == "Yes") {
															$quotereq_sales_flag_color = "#e3e1bb";
														} else {
															$quotereq_sales_flag_color = "#e3e1bb";
														}
													} else {
														if ($other_quotereq_sales_flag == "Yes") {
															$quotereq_sales_flag_color = "#D3FFB9";
														} else {
															$quotereq_sales_flag_color = "#C0CDDA";
														}
													}
												?>
													<tr>
														<td colspan="4" style="background:<?php echo $quotereq_sales_flag_color; ?>; padding:5px;">
															<table cellpadding="3">
																<tr>
																	<td style="background:<?php echo $quotereq_sales_flag_color; ?>;"><strong>Demand Entry ID: <?php echo $other_data["quote_id"]; ?></strong></td>
																	<td width="200px" style="background:<?php echo $quotereq_sales_flag_color; ?>;"><strong>Quote Item: <?php echo $quote_item_name; ?></strong></td>
																	<td style="background:<?php echo $quotereq_sales_flag_color; ?>;">
																		<font face="Arial, Helvetica, sans-serif" size="1" color="<?php echo $quotereq_sales_flag_color; ?>"> <a name="details_btn" id="other_btn<?php echo $other_id; ?>" onClick="show_other_details(<?php echo $other_id; ?>)" class="ex_col_btn">Expand Details</a>
																			<!--<input type="button" name="details_btn" id="other_btn<?php //echo $other_id;
																																		?>" value="Expand Details" onClick="show_other_details(<?php //echo $other_id;
																																																					?>)">-->
																		</font>
																	</td>
																	<!-- <td style="background:<?php echo $quotereq_sales_flag_color; ?>;"><font face="Arial, Helvetica, sans-serif" size="1" color="<?php echo $quotereq_sales_flag_color; ?>">
                                    <div id="button_status<?php echo $other_data["quote_id"] ?>">
                                        <?php
													$chk_deny_query = "Select * from quote_request where quote_id= ?"; 
													$chk_deny_res = db_query($chk_deny_query, array("i"), array($other_data['quote_id']));
													$deny_row = array_shift($chk_deny_res);
													if ($deny_row["deny_quote"] != "Yes") {
										?>
                                        <input type="button" name="deny_btn" id="<?php echo $other_data["quote_id"] ?>" value="Deny" onClick="deny_quote_req(<?php echo $b2bid; ?>,<?php echo $other_data["quote_id"] ?>)">
                                   
                                        <div id="hidden_deny_fields" name="hidden_deny_fields"></div>
                                    <?php
													} else {
														echo "<span style='color:#FF0000; font-size:11px; font-weight:bold;'>Denied - </span>";
									?>
                                    <a href='#' id='quotesdeny<?php echo $other_data["quote_id"] ?>' onclick="show_deny_info(<?php echo $other_data["quote_id"] ?>, <?php echo $b2bid; ?>); return false;">View</a>
                                    <?php
														//show_file_inviewer_pos   
													}
									?>
                                  </div>
                                    
								</font></td>-->

																	<td style="background:<?php echo $quotereq_sales_flag_color; ?>;"><img bgcolor="<?php echo $quotereq_sales_flag_color; ?>" src="images/edit.jpg" onClick="other_quote_edit(<?php echo $b2bid; ?>, <?php echo $other_id; ?>, <?php echo $quote_item ?>, <?php echo $client_dash_flg_other ?>)" style="cursor: pointer;"></td>
																	<td style="background:<?php echo $quotereq_sales_flag_color; ?>;">
																		<font face="Arial, Helvetica, sans-serif" size="1" color="<?php echo $quotereq_sales_flag_color; ?>"> <img bgcolor="<?php echo $quotereq_sales_flag_color; ?>" src="images/del_img.png" onClick="other_quote_delete(<?php echo $other_id; ?>, <?php echo $quote_item ?>,<?php echo $b2bid; ?>)" style="cursor: pointer;"> </font>
																	</td>
																	<td style="background:<?php echo $quotereq_sales_flag_color; ?>;">
																		<font face="Arial, Helvetica, sans-serif" size="1" color="<?php echo $quotereq_sales_flag_color; ?>"> <img bgcolor="<?php echo $quotereq_sales_flag_color; ?>" src="images/move_img.png" onClick="other_quote_move(<?php echo $other_id; ?>, <?php echo $quote_item ?>,<?php echo $b2bid; ?>, <?php echo $client_dash_flg_other ?>)" style="cursor: pointer;"> </font>
																	</td>
																	<!-- <td style="background:<?php echo $quotereq_sales_flag_color; ?>; padding-left: 30px;">
                                    <?php //echo $other_quote_sent_status;
									?>
                                </td>-->

																	<td>
																		<font face="Arial, Helvetica, sans-serif" size="1">
																			<?php
																			if ($other_data["client_dash_flg"] == 1) {
																				echo "Viewable on Boomerang Portal";
																			} else {
																				echo "NOT Viewable on Boomerang Portal";
																			}
																			?>
																		</font>
																	</td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td>
															<div id="other_sub_table<?php echo $other_id; ?>" style="display: none;">
																<table width="100%" class="in_table_style">
																	<tr bgcolor="<?php echo $subheading; ?>">
																		<td colspan="6"><strong>What Do They Buy?</strong></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor1; ?>">
																		<td> Quantity Requested </td>
																		<td colspan=5><?php echo $other_data["other_quantity_requested"]; ?>
																			<?php
																			if ($other_data["other_quantity_requested"] == "Other") {
																				echo "<br>" . $other_data["other_other_quantity"];
																			}
																			?></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor2; ?>">
																		<td> Frequency of Order </td>
																		<td colspan=5><?php echo $other_data["other_frequency_order"]; ?></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor1; ?>">
																		<td> What Used For? </td>
																		<td colspan=5><?php echo $other_data["other_what_used_for"]; ?></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor1; ?>">
																		<td> Also Need Pallets? </td>
																		<td colspan=5><?php echo $other_data["other_need_pallets"]; ?></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor2; ?>">
																		<td> Notes </td>
																		<td colspan=5><?php echo $other_data["other_note"]; ?></td>
																	</tr>
																	<tr bgcolor="<?php echo $rowcolor2; ?>">
																		<td> High Value Opportunity </td>
																		<td colspan=5><?php if ($other_data["high_value_target"] == 1) {
																							echo "Yes";
																						} else {
																							echo "No";
																						} ?></td>
																	</tr>

																	<tr bgcolor="<?php echo $rowcolor2; ?>">
																		<td colspan="6" align="right" style="padding: 4px;"> Created By:<?php echo $other_data['user_initials']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																			Date: <?php echo date("m/d/Y H:i:s", strtotime($other_data['quote_date'])); ?> &nbsp;&nbsp;&nbsp; </td>
																	</tr>
																	<?php //if($other_data["client_dash_flg"]==1) {  
																	?>
																	<tr bgcolor="<?php echo $rowcolor1; ?>">
																		<td colspan="6" align="right" style="padding: 4px;">
																			<a href="report_demand_alert_email.php?demandID=<?php echo $other_data["quote_id"]; ?>&miles=500&box_broker=1&post_industrial=1&btnsubmit=Submit&pallet_bro=1&pallet_pro=1" target="_blank">
																				<font face="Arial, Helvetica, sans-serif" size="1" color="#0000FF">Demand Alert Email List (CSV)</font>
																			</a>
																		</td>
																	</tr>
																	<?php //} 
																	?>
																</table>
															</div>
														</td>
													</tr>
													<tr>
														<td style="background:#FFFFFF;">
															<!-- TEST OTHER MATCHING SECTION STARTS -->
															<a style='color:#0000FF;' id="lightbox_req_other<?php echo $other_id; ?>" href="javascript:void(0);" onClick="display_request_other_tool_test(<?php echo $b2bid; ?>,1,1,0,<?php echo $other_id; ?>)">

																<font face="Arial, Helvetica, sans-serif" size="1" color="#0000FF">OTHER MATCHING TOOL</font>
															</a> <span id="req_othertoolautoload" name="req_othertoolautoload" style='color:red;'></span>
															<!-- TEST OTHER MATCHING SECTION ENDS -->
															<br>


														</td>
													</tr>
													<tr>
														<td colspan="4" style="background:#FFFFFF; height:4px;"></td>
													</tr>
												<?php
												} //End if company check
												?>
											</table>
										</div>
									<?php
									} //End While

									?>
								</div>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	<?php
} //End function
	?>

	</body>

	</html>