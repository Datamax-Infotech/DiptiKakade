<script>
	function btnsendemlclick_eml_p() {
		var tmp_element1, tmp_element2, tmp_element3, tmp_element4, tmp_element5;

		tmp_element1 = document.getElementById("txtemailto").value;

		tmp_element2 = document.getElementById("email_reminder_sch_p");

		tmp_element3 = document.getElementById("txtemailcc").value;

		tmp_element4 = document.getElementById("txtemailsubject").value;

		tmp_element5 = document.getElementById("hidden_reply_eml");

		if (tmp_element1.value == "") {
			alert("Please enter the To Email address.");
			return false;
		}

		if (tmp_element4.value == "") {
			alert("Please enter the Email Subject.");
			return false;
		}

		if (tmp_element3.value == "") {
			alert("Please enter the Cc Email address.");
			return false;
		}


		var inst = FCKeditorAPI.GetInstance("txtemailbody");
		var emailtext = inst.GetHTML();

		tmp_element5.value = emailtext;
		//alert(tmp_element5.value);
		document.getElementById("hidden_sendemail").value = "inemailmode";
		tmp_element2.submit();

	}


	function delivery_financials_reload(compid, rec_id, warehouse_id, rec_type) {
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				document.getElementById("div_iframe_delivery_financials").innerHTML = xmlhttp.responseText;
			}
		}

		xmlhttp.open("POST", "loop_shipbubble_delivery_financials_update.php?ID=" + compid + "&rec_id=" + rec_id + "&warehouse_id=" + warehouse_id + "&rec_type=" + rec_type, true);
		xmlhttp.send();
	}

	function po_ignore(po_ignore_flg, compid, rec_id, warehouse_id, rec_type) {
		if (po_ignore_flg == 'posendemail_ignore') {
			document.getElementById("tbl_po_send_email").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";
		}
		if (po_ignore_flg == 'po_ignore') {
			document.getElementById("table_po_display").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";
		}
		if (po_ignore_flg == 'virtual_ignore') {
			document.getElementById("table_virtual_display").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";
		}
		if (po_ignore_flg == 'Shipper_eml_ignore') {
			document.getElementById("table_Shipper_eml_display").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";
		}

		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				if (po_ignore_flg == 'posendemail_ignore') {
					document.getElementById("tbl_po_send_email").innerHTML = xmlhttp.responseText;
				}
				if (po_ignore_flg == 'po_ignore') {
					document.getElementById("table_po_display").innerHTML = xmlhttp.responseText;
				}
				if (po_ignore_flg == 'virtual_ignore') {
					document.getElementById("table_virtual_display").innerHTML = xmlhttp.responseText;
				}
				if (po_ignore_flg == 'Shipper_eml_ignore') {
					document.getElementById("table_Shipper_eml_display").innerHTML = xmlhttp.responseText;
				}

			}
		}

		if (po_ignore_flg == 'posendemail_ignore') {
			xmlhttp.open("POST", "po_order_bubble_actions.php?po_posendemail_ignore=1&ID=" + compid + "&rec_id=" + rec_id + "&warehouse_id=" + warehouse_id + "&rec_type=" + rec_type, true);
		}
		if (po_ignore_flg == 'po_ignore') {
			xmlhttp.open("POST", "po_order_bubble_actions.php?po_ignore=1&ID=" + compid + "&rec_id=" + rec_id + "&warehouse_id=" + warehouse_id + "&rec_type=" + rec_type, true);
		}
		if (po_ignore_flg == 'virtual_ignore') {
			xmlhttp.open("POST", "po_order_bubble_actions.php?virtual_ignore=1&ID=" + compid + "&rec_id=" + rec_id + "&warehouse_id=" + warehouse_id + "&rec_type=" + rec_type, true);
		}
		if (po_ignore_flg == 'Shipper_eml_ignore') {
			xmlhttp.open("POST", "po_order_bubble_actions.php?Shipper_eml_ignore=1&ID=" + compid + "&rec_id=" + rec_id + "&warehouse_id=" + warehouse_id + "&rec_type=" + rec_type, true);
		}

		xmlhttp.send();
	}

	function TroublewithShipper_eml_load(compid, rec_id, warehouse_id, rec_type) {
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				selectobject = document.getElementById("btnTroublewithShipper_eml");
				n_left = f_getPosition(selectobject, 'Left');
				n_top = f_getPosition(selectobject, 'Top');

				document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
				document.getElementById('light_reminder').style.display = 'block';

				document.getElementById('light_reminder').style.left = (n_left + 50) + 'px';
				document.getElementById('light_reminder').style.top = n_top + 40 + 'px';
				document.getElementById('light_reminder').style.width = 1100 + 'px';

			}
		}

		xmlhttp.open("POST", "sendemail_trouble_with_shipper.php?compid=" + compid + "&rec_id=" + rec_id + "&warehouse_id=" + warehouse_id + "&rec_type=" + rec_type, true);
		xmlhttp.send();
	}

	function reminder_popup_set(compid, rec_id, warehouse_id, rec_type) {
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				selectobject = document.getElementById("btnposendemail");
				n_left = f_getPosition(selectobject, 'Left');
				n_top = f_getPosition(selectobject, 'Top');

				document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
				document.getElementById('light_reminder').style.display = 'block';

				document.getElementById('light_reminder').style.left = (n_left - 100) + 'px';
				document.getElementById('light_reminder').style.top = n_top - 40 + 'px';
				document.getElementById('light_reminder').style.width = 1100 + 'px';
			}
		}

		xmlhttp.open("POST", "sendemail_add_po.php?compid=" + compid + "&rec_id=" + rec_id + "&warehouse_id=" + warehouse_id + "&rec_type=" + rec_type, true);
		xmlhttp.send();
	}

	function reminder_popup_set_new(compid, rec_id, warehouse_id, rec_type) {
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				//selectobject = document.getElementById("viewdiv"+rec_id);
				selectobject = document.getElementById("iframe_po_display");

				n_left = f_getPosition(selectobject, 'Left');
				n_top = f_getPosition(selectobject, 'Top');

				document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
				document.getElementById('light_reminder').style.display = 'block';

				document.getElementById('light_reminder').style.left = (n_left + 50) + 'px';
				document.getElementById('light_reminder').style.top = n_top + 200 + 'px';
				document.getElementById('light_reminder').style.width = 1100 + 'px';
			}
		}

		xmlhttp.open("POST", "sendemail_add_po.php?compid=" + compid + "&rec_id=" + rec_id + "&warehouse_id=" + warehouse_id + "&rec_type=" + rec_type, true);
		xmlhttp.send();
	}

	function readytoship_email(compid, rec_id, warehouse_id, rec_type) {
		var iframe0 = document.getElementById("iframe_good_to_ship");
		var iframe0document = iframe0.contentDocument || iframe0.contentWindow.document;
		var txtnotesforfreight = iframe0document.getElementById("txtnotesforfreight").value;
		//var txtnotesforfreight = document.getElementById("txtnotesforfreight");
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				selectobject = document.getElementById("viewdiv_r" + rec_id);
				n_left = f_getPosition(selectobject, 'Left');
				n_top = f_getPosition(selectobject, 'Top');

				document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
				document.getElementById('light_reminder').style.display = 'block';

				document.getElementById('light_reminder').style.left = (n_left + 50) + 'px';
				document.getElementById('light_reminder').style.top = n_top + 50 + 'px';
				document.getElementById('light_reminder').style.width = 1100 + 'px';
			}
		}

		xmlhttp.open("POST", "sendemail_readytoship.php?compid=" + compid + "&rec_id=" + rec_id + "&warehouse_id=" + warehouse_id + "&rec_type=" + rec_type + "&txtnotesforfreight=" + txtnotesforfreight, true);
		xmlhttp.send();
	}

	function readytoship_email_cpu(compid, rec_id, warehouse_id, rec_type) {
		var iframe0 = document.getElementById("iframe_good_to_ship");
		var iframe0document = iframe0.contentDocument || iframe0.contentWindow.document;
		var txtnotesforfreight = iframe0document.getElementById("txtnotesforfreight").value;
		//var txtnotesforfreight = document.getElementById("txtnotesforfreight");
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				selectobject = document.getElementById("viewdiv_r" + rec_id);
				n_left = f_getPosition(selectobject, 'Left');
				n_top = f_getPosition(selectobject, 'Top');

				document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
				document.getElementById('light_reminder').style.display = 'block';

				document.getElementById('light_reminder').style.left = (n_left + 50) + 'px';
				document.getElementById('light_reminder').style.top = n_top + 50 + 'px';
				document.getElementById('light_reminder').style.width = 1100 + 'px';
			}
		}

		xmlhttp.open("POST", "sendemail_readytoship_cpu.php?compid=" + compid + "&rec_id=" + rec_id + "&warehouse_id=" + warehouse_id + "&rec_type=" + rec_type + "&txtnotesforfreight=" + txtnotesforfreight, true);
		xmlhttp.send();
	}
</script>

<style>
	.style12_new {
		font-size: x-small;
		font-family: Arial, Helvetica, sans-serif;
	}
</style>

<Font Face='arial' size='2'><br><br>

	<div id="fade" class="black_overlay"></div>
	<div id="light_reminder" class="white_content_reminder"></div>

	<br>
	<span id="viewdiv<?php echo $_REQUEST["rec_id"];?>">

		<iframe frameborder="0" onload="iframePoLoaded()" scrolling="auto" id="iframe_po_display" name="iframe_po_display" src="loop_sales_iframe_po_display.php?rec_id=<?php echo $_REQUEST['rec_id'];?>&ID=<?php echo $_REQUEST["ID"];?>&warehouse_id=<?php echo $_REQUEST["warehouse_id"];?>&rec_type='<?php echo $_REQUEST["rec_type"];?>'" style="width:600px;">

		</iframe>

		<script>
			function iframePoLoaded() {
				ifrmaeobj = document.getElementById("iframe_po_display");
				var objheight = ifrmaeobj.contentWindow.document.body.offsetHeight;
				objheight = objheight + 50;
				ifrmaeobj.style.height = objheight + 'px';
				//ifrmaeobj.style.width = '600px';	
			}
		</script>

		<?php 
		db();
		$trouble_with_shipper_ignore_dt = "";
		$trouble_with_shipper_ignore_user = "";
		$trouble_with_shipper_ignore = 0;
		$ops_delivery_dt = "";
		$payment_type = "";
		$po_delivery_dt = "";
		$sql = "SELECT `trouble_with_shipper_ignore`, po_payment_method, trouble_with_shipper_ignore_dt, ops_delivery_date, po_delivery_dt, trouble_with_shipper_ignore_user FROM loop_transaction_buyer WHERE id = ?";
		$sql_res = db_query($sql, array("i"), array($_REQUEST["rec_id"]));
		while ($row = array_shift($sql_res)) {
			$trouble_with_shipper_ignore = $row["trouble_with_shipper_ignore"];
			$trouble_with_shipper_ignore_dt = $row["trouble_with_shipper_ignore_dt"];
			$trouble_with_shipper_ignore_user = $row["trouble_with_shipper_ignore_user"];
			$ops_delivery_dt = $row["ops_delivery_date"];
			$payment_type = $row["po_payment_method"];
			if ($row["po_delivery_dt"] <> "") {
				$po_delivery_dt = date("m/d/Y", strtotime($row["po_delivery_dt"]));
			} else {
				$po_delivery_dt = "";
			}
		}

		$trouble_with_shipper_rec_found = "n";
		$trouble_with_shipper_eml_sendon = "";
		$trouble_with_shipper_eml_sendby = "";
		$getdata = db_query("Select trouble_with_shipper_email_sendon, trouble_with_shipper_email_sendby From loop_transaction_buyer_poeml where trans_rec_id = " . $_REQUEST["rec_id"] . " and trouble_with_shipper_email_sendon is not null limit 1");
		while ($getdata_row = array_shift($getdata)) {
			if ($getdata_row["trouble_with_shipper_email_sendon"] != "") {
				$trouble_with_shipper_rec_found = "y";
			}
			$trouble_with_shipper_eml_sendon = $getdata_row["trouble_with_shipper_email_sendon"];
			$trouble_with_shipper_eml_sendby = $getdata_row["trouble_with_shipper_email_sendby"];
		}

		
		if ($payment_type == "Credit Card") {
		?>
			<br>
			<iframe frameborder="0" onload="iframeLoaded_cc_details()" width="600px" height="150" scrolling="auto" id="iframe_cc_details" src="loop_orderbubble_cc_details.php?rec_id=<?php echo $_REQUEST['rec_id'];?>&ID=<?php echo $_REQUEST["ID"];?>&rec_id=<?php echo $_REQUEST["rec_id"];?>&warehouse_id=<?php echo $_REQUEST["warehouse_id"];?>&rec_type='<?php echo $_REQUEST["rec_type"];?>'">
			</iframe>

			<script>
				function iframeLoaded_cc_details() {
					ifrmaeobj = document.getElementById("iframe_cc_details");
					var objheight = ifrmaeobj.contentWindow.document.body.offsetHeight;
					objheight = objheight + 50;
					ifrmaeobj.style.height = objheight + 'px';
					//ifrmaeobj.style.width = '600px';		
				}
			</script>
			<br>
		<?php 
		}
		?>
		
		<?php if ($quote_number > 0) { ?>
			<table cellSpacing="1" cellPadding="1" border="0" style="width: 400px; font-size:10;" id="table14">
				<tr>
					<td colspan="7" bgColor="#e4e4e4"><b>Quote Details</b></td>
				</tr>
				<tr bgColor="#e4e4e4">
					<td>Quantity</td>
					<td>Description</td>
					<td>Vendor</td>
					<td>Ship From</td>
					<td>Price</td>
					<td>Total</td>
					<td>Min FOB Met?</td>
				</tr>
				<?php 
				db_b2b();
				$vender_nm = "";
				$cid = $_REQUEST["quote_id"];
				$sql = "SELECT * FROM quote_to_item WHERE quote_id= '" . $quote_number . "' ORDER BY sort_order ASC";
				$boxsql = db_query($sql);
				$x = 0;
				while ($bx = array_shift($boxsql)) {
					$x = $x + 1;
					$boxstr = "";

					//echo $c;
					db_b2b();
					$d = "select * from boxes where ID = ?";
					$itemSql = db_query($d, array("i"), array($bx["item_id"]));
					$item = array_shift($itemSql);

					$loop_id_chk = "";
					db();
					$sql_forbox = "SELECT id FROM loop_boxes WHERE b2b_id = ?";
					$box_data = db_query($sql_forbox, array("i"), array($item["inventoryID"]));
					while ($bx_row = array_shift($box_data)) {
						$loop_id_chk = $bx_row["id"];
					}

					//echo $d;
				?>
					<tr bgColor="#e4e4e4">
						<td> <?php echo $item['quantity'];?> </td>

						<td valign=top>
							<a target="_blank" href="manage_box_b2bloop.php?id=<?php echo $loop_id_chk;?>&proc=View">
								<font size="1">
									<?php 
									if ($item['inventoryID'] == 0) {
										db();
										$lbq = "SELECT * from loop_boxes WHERE b2b_id = ?";

										$lb_res = db_query($lbq, array("i"), array($item["box_id"]));
										$lbrow = array_shift($lb_res);

										if ($item['item'] == "Boxes") {
											echo  $item['lengthInch'] . " x " . $item['widthInch'] . " x " . $item['depthInch'] . " ";
										}
										echo  $item['description'];
									} else {

										db_b2b();
										$z = "select * from inventory Where ID = ?";
										$boxSql = db_query($z, array("i"), array($item['inventoryID']));
										$objInv = array_shift($boxSql);

										$q1 = "SELECT * FROM vendors where id = ?"; 
										$query = db_query($q1, array("i"), array($objInv["vendor"]));
										$vender_nm = "";

										while ($fetch = array_shift($query)) {
											$vender_nm = $fetch['Name'];
										}

										$b2b_location_city = $objInv["location_city"];
										$b2b_location_st = $objInv["location_state"];
										$b2b_location_zip = $objInv["location_zip"];

										echo  $objInv['lengthInch'] . " x " . $objInv['widthInch'] . " x " . $objInv['depthInch'] . " " . $objInv['description'];

										$b2b_ulineDollar = round($objInv["ulineDollar"]);
										$b2b_ulineCents = $objInv["ulineCents"];
										$min_fob = $b2b_ulineDollar + $b2b_ulineCents;
									}

									echo " 
  </font></a></td>";

									$min_fob_val = 0;
									if ($min_fob == 0) {
										$min_fob_val = $item["ulinePrice"];
									} else {
										$min_fob_val = $min_fob;
									}

									
									?>
						<td><?php echo $vender_nm;?></td>
						<td><?php echo $b2b_location_city . " " . $b2b_location_st . " " .	$b2b_location_zip;?></td>
						<td>$<?php echo number_format($item['salePrice'], 2);?></td>
						<td>$<?php echo number_format($item['salePrice'] * $item['quantity'], 2);  ?></td>
						<td>
							<?php 
							

							if ($min_fob_val > 0 && $item["shipfinal"] > 0 && $item["quantity"] > 0) {
								$min_delv_cost = $min_fob_val + ($item["shipfinal"] / $item["quantity"]);
								$min_delv_cost = number_format($min_delv_cost, 2);
							} elseif ($min_fob_val > 0) {
								$min_delv_cost = $min_fob_val;
								$min_delv_cost = number_format($min_delv_cost, 2);
							}
							if ($item["salePrice"] >= $min_delv_cost) { ?>
								<font face="Arial, Helvetica, sans-serif" size="1" color='green'>$<?php echo $min_delv_cost;?></font>
							<?php } else { ?>
								<font face="Arial, Helvetica, sans-serif" size="1" color='red'>$<?php echo $min_delv_cost;?></font>
							<?php   }
							?>
						</td>
					</tr>
				<?php 
				}
				?>
			</table>
		<?php } ?>
		<div style="clear: both;">&nbsp;</div>
		<!--------------------------------Create Sales Order----------------------------------------------------------------->
		<iframe frameborder="0" onload="iframeLoaded_sales_order()" scrolling="auto" id="iframe_sales_order_display" src="loop_sales_iframe_sales_order.php?rec_id=<?php echo $_REQUEST['rec_id'];?>&ID=<?php echo $_REQUEST["ID"];?>&rec_id=<?php echo $_REQUEST["rec_id"];?>&warehouse_id=<?php echo $_REQUEST["warehouse_id"];?>&rec_type='<?php echo $_REQUEST["rec_type"];?>'" style="width:800px">

		</iframe>

		<script>
			function iframeLoaded_sales_order() {
				ifrmaeobj = document.getElementById("iframe_sales_order_display");
				
				var objheight = ifrmaeobj.contentWindow.document.body.offsetHeight;
				objheight = objheight + 50;
				ifrmaeobj.style.height = objheight + 'px';
				//ifrmaeobj.style.width = '800px';		
			}
		</script>

		<!--------------------------------Create Virtual Inventory ----------------------------------------------------------------->
		<br>
		<iframe frameborder="0" width="600px" height="100" onload="iframeLoaded()" scrolling="auto" id="iframe_virtual_display" src="loop_sales_iframe_virtual_display.php?rec_id=<?php echo $_REQUEST['rec_id'];?>&ID=<?php echo $_REQUEST["ID"];?>&rec_id=<?php echo $_REQUEST["rec_id"];?>&warehouse_id=<?php echo $_REQUEST["warehouse_id"];?>&rec_type='<?php echo $_REQUEST["rec_type"];?>'" style="width:100%">

		</iframe>

		<script>
			function iframeLoaded() {
				ifrmaeobj = document.getElementById("iframe_virtual_display");
				var objheight = ifrmaeobj.contentWindow.document.body.offsetHeight;
				objheight = objheight + 50;
				ifrmaeobj.style.height = objheight + 'px';
			}
		</script>

		<br>
	<?php 


		$shipper_get_loop_id_query = db_query("Select loop_salesorders.box_id from loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = '" .  $_REQUEST["rec_id"] . "'");
		$shipper_get_loop_id_data = array_shift($shipper_get_loop_id_query);

		$running_loops_id = $shipper_get_loop_id_data['box_id'];

		if (isset($running_loops_id)) {
			db_b2b();
			$require_externalSOPO_sql_query = "SELECT require_externalSOPO,externalSOPO_shipper_input FROM `inventory` WHERE loops_id = '" . $running_loops_id . "'";
			$require_externalSOPO_fetch = db_query($require_externalSOPO_sql_query);
			$require_externalSOPO_data = array_shift($require_externalSOPO_fetch);

			$external_SOPO_table_show = $require_externalSOPO_data['require_externalSOPO'];
			$shipper_txt = $require_externalSOPO_data['externalSOPO_shipper_input'];

			$thcolor = isset($external_SOPO_table_show) && $external_SOPO_table_show === 1 && isset($shipper_txt) && $shipper_txt != '' ? '#99FF99' : '#fb8a8a';

			if (isset($external_SOPO_table_show) && $external_SOPO_table_show === 1) {

		?>

				<iframe frameborder="0" scrolling="auto" id="iframe_supplier_ship_date" src="loop_orderbubble_shipper_so_po.php?rec_id=<?php echo $_REQUEST['rec_id'];?>&ID=<?php echo $_REQUEST["ID"];?>&rec_id=<?php echo $_REQUEST["rec_id"];?>&warehouse_id=<?php echo $_REQUEST["warehouse_id"];?>&rec_type='<?php echo $_REQUEST["rec_type"];?>'" style="width:100%">
				</iframe>

		<?php }
		} ?>

		<br>

		<iframe frameborder="0" onload="iframeLoaded_payment_or_terms()" scrolling="auto" id="iframe_payment_or_terms" src="loop_orderbubble_payment_or_terms.php?rec_id=<?php echo $_REQUEST['rec_id'];?>&ID=<?php echo $_REQUEST["ID"];?>&rec_id=<?php echo $_REQUEST["rec_id"];?>&warehouse_id=<?php echo $_REQUEST["warehouse_id"];?>&rec_type='<?php echo $_REQUEST["rec_type"];?>'">

		</iframe>

		<script>
			function iframeLoaded_payment_or_terms() {
				ifrmaeobj = document.getElementById("iframe_payment_or_terms");
				var objheight = ifrmaeobj.contentWindow.document.body.offsetHeight;
				objheight = objheight + 20;
				ifrmaeobj.style.height = objheight + 'px';
				ifrmaeobj.style.width = '600px';		
			}
		</script>
		<br>
		<iframe frameborder="0" onload="iframeLoaded_add_correct()" scrolling="auto" id="iframe_add_correct" src="loop_orderbubble_add_confirm.php?rec_id=<?php echo $_REQUEST['rec_id'];?>&ID=<?php echo $_REQUEST["ID"];?>&rec_id=<?php echo $_REQUEST["rec_id"];?>&warehouse_id=<?php echo $_REQUEST["warehouse_id"];?>&rec_type='<?php echo $_REQUEST["rec_type"];?>'">

		</iframe>

		<script>
			function iframeLoaded_add_correct() {
				ifrmaeobj = document.getElementById("iframe_add_correct");
				var objheight = ifrmaeobj.contentWindow.document.body.offsetHeight;
				objheight = objheight + 20;
				ifrmaeobj.style.height = objheight + 'px';
				ifrmaeobj.style.width = '600px';		
			}
		</script>
		<br>

		<div id="div_iframe_delivery_financials">
			<iframe frameborder="0" onload="iframeLoaded_delivery_financials()" scrolling="auto" id="iframe_delivery_financials" src="loop_orderbubble_delivery_financials.php?rec_id=<?php echo $_REQUEST['rec_id'];?>&ID=<?php echo $_REQUEST["ID"];?>&rec_id=<?php echo $_REQUEST["rec_id"];?>&warehouse_id=<?php echo $_REQUEST["warehouse_id"];?>&rec_type='<?php echo $_REQUEST["rec_type"];?>'">

			</iframe>

			<script>
				function iframeLoaded_delivery_financials() {
					ifrmaeobj = document.getElementById("iframe_delivery_financials");
					var objheight = ifrmaeobj.contentWindow.document.body.offsetHeight;
					objheight = objheight + 20;
					ifrmaeobj.style.height = objheight + 'px';
					ifrmaeobj.style.width = '600px';		
				}
			</script>
		</div>
		<br>


		<iframe frameborder="0" width="600px" height="200" scrolling="auto" id="iframe_sent_to_supplier" src="loop_orderbubble_sent_to_supplier.php?rec_id=<?php echo $_REQUEST['rec_id'];?>&ID=<?php echo $_REQUEST["ID"];?>&rec_id=<?php echo $_REQUEST["rec_id"];?>&warehouse_id=<?php echo $_REQUEST["warehouse_id"];?>&rec_type='<?php echo $_REQUEST["rec_type"];?>'">
		</iframe>

		<br>

		<iframe frameborder="0" onload="iframeLoaded_supplier_shipdate()" scrolling="auto" id="iframe_supplier_ship_date" src="loop_orderbubble_supplier_ship_date.php?rec_id=<?php echo $_REQUEST['rec_id'];?>&ID=<?php echo $_REQUEST["ID"];?>&rec_id=<?php echo $_REQUEST["rec_id"];?>&warehouse_id=<?php echo $_REQUEST["warehouse_id"];?>&rec_type='<?php echo $_REQUEST["rec_type"];?>'">
		</iframe>
		<script>
			function iframeLoaded_supplier_shipdate() {
				ifrmaeobj = document.getElementById("iframe_supplier_ship_date");
				var objheight = ifrmaeobj.contentWindow.document.body.offsetHeight;
				objheight = objheight + 20;
				ifrmaeobj.style.height = objheight + 'px';
				ifrmaeobj.style.width = '600px';		

			}
		</script>
		<br>


		<div id="table_Shipper_eml_display">
			<form action="loop_orderbubble_sent_to_supplier.php" method="post">
				<input type="hidden" name="warehouse_id" value="<?php echo $_REQUEST["warehouse_id"];?>" />
				<input type="hidden" name="ID" value="<?php echo $_REQUEST["ID"];?>" />
				<input type="hidden" name="rec_type" value="<?php echo $_REQUEST["rec_type"];?>" />
				<input type="hidden" value="<?php echo $_COOKIE['userinitials'] ?>" name="employee" />
				<input type="hidden" name="rec_id" value="<?php echo $_REQUEST["rec_id"];?>" />
				<table cellSpacing="1" cellPadding="1" border="0" style="width: 444px">
					<tr align="middle">
						<?php if ($trouble_with_shipper_rec_found == "y" || $trouble_with_shipper_ignore == 1) { ?>
							<td bgColor="#99FF99">
							<?php } else { ?>
								<?php //if (trouble_with_shipper_rec_found == "n" || $trouble_with_shipper_ignore == 0){ 
								?>
							<td bgColor="#c0cdda">
							<?php } ?>
							<font size="1"><?php echo strtoupper("Notify Customer of Delay with Shipper?");?></font>
							</td>
					</tr>

					<tr bgColor="#e4e4e4">
						<td align="center" height="13" style="width: 235px" class="style1">
							<?php if ($trouble_with_shipper_rec_found == "n") { ?>
								<input type="button" value="Send Email" id="btnTroublewithShipper_eml" name="btnTroublewithShipper_eml" onclick="TroublewithShipper_eml_load(<?php echo $_REQUEST["ID"];?>, <?php echo $_REQUEST["rec_id"];?>, <?php echo $_REQUEST["warehouse_id"];?>, '<?php echo $_REQUEST["rec_type"];?>' );">
							<?php } else { ?>
								<input type="button" value="Re-Send Email" id="btnTroublewithShipper_eml" name="btnTroublewithShipper_eml" onclick="TroublewithShipper_eml_load(<?php echo $_REQUEST["ID"];?>, <?php echo $_REQUEST["rec_id"];?>, <?php echo $_REQUEST["warehouse_id"];?>, '<?php echo $_REQUEST["rec_type"];?>' );">
								<font size="1">Email sent by <?php echo $trouble_with_shipper_eml_sendby;?> on <?php echo date("m/d/Y H:i:s", strtotime($trouble_with_shipper_eml_sendon)) . " CT";?></font>
							<?php } ?>

							&nbsp;&nbsp;
							<?php if ($trouble_with_shipper_ignore == 0) { ?>
								<input id="btnTroublewithShipper_eml_ignore" type="button" value="Ignore this Step" onclick="po_ignore('Shipper_eml_ignore', <?php echo $_REQUEST["ID"];?>,<?php echo $_REQUEST["rec_id"];?>,<?php echo $_REQUEST["warehouse_id"];?>,'<?php echo $_REQUEST["rec_type"];?>');">
							<?php } else { ?>
								<font size="1">This Step in ignore by <?php echo $trouble_with_shipper_ignore_user;?> on <?php echo date("m/d/Y H:i:s", strtotime($trouble_with_shipper_ignore_dt)) . " CT";?></font>
							<?php } ?>
						</td>
					</tr>
				</table>
			</form>
		</div>
		<br>
	
		<span id="viewdiv_r<?php echo $_REQUEST["rec_id"];?>" />
		<br>
		<?php 

		db();

		$prepay = "No";
		$get_sales_order = db_query("Select loop_boxes.prepay From loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE loop_boxes.prepay = 1 and trans_rec_id = '" .  $_REQUEST["rec_id"] . "'");
		while ($boxes = array_shift($get_sales_order)) {
			$prepay = "Yes";
		}

		if ($prepay == "Yes") {
		?>
			<iframe frameborder="0" width="600px" height="100" scrolling="auto" id="iframe_orderbubble_prepay" src="loop_orderbubble_prepay.php?rec_id=<?php echo $_REQUEST['rec_id'];?>&ID=<?php echo $_REQUEST["ID"];?>&rec_id=<?php echo $_REQUEST["rec_id"];?>&warehouse_id=<?php echo $_REQUEST["warehouse_id"];?>&rec_type='<?php echo $_REQUEST["rec_type"];?>'">
			</iframe>
		<?php } ?>

		<iframe frameborder="0" width="600px" height="250px" scrolling="auto" id="iframe_good_to_ship" src="loop_orderbubble_good_to_ship.php?rec_id=<?php echo $_REQUEST['rec_id'];?>&ID=<?php echo $_REQUEST["ID"];?>&rec_id=<?php echo $_REQUEST["rec_id"];?>&warehouse_id=<?php echo $_REQUEST["warehouse_id"];?>&rec_type='<?php echo $_REQUEST["rec_type"];?>'">

		</iframe>

</font>