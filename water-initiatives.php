<?php
session_start();
require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";
if (isset($_SESSION['loginid']) && $_SESSION['loginid'] > 0) {
	if (isset($_SESSION['waterUserLoginId']) && $_SESSION['waterUserLoginId'] > 0) {
		waterUserVisitedTo($_SESSION['waterUserLoginId'], 'water-initiatives');
	}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>UCBZeroWaste</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Montserrat:300,400,500,700"
        rel="stylesheet">

    <link rel="shortcut icon" href="images/logo.jpg" type="image/jpg">

    <link href="css/header-footer.css" rel="stylesheet">
    <link href="css/inner.css" rel="stylesheet">
    <link href="css/inner-table-new.css" rel="stylesheet">
    <link href="css/water-initiative-form.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Titillium+Web" rel="stylesheet">
    <!--Menu =========================================================================================================================-->
    <link rel="stylesheet" href="menu/demo.css">
    <link rel="stylesheet" href="menu/navigation-icons.css">
    <link rel="stylesheet" href="menu/slicknav/slicknav.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Fira+Sans" rel="stylesheet">

    <style type="text/css">
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
        height: 90%;
        padding: 16px;
        border: 1px solid gray;
        background-color: white;
        z-index: 1002;
        overflow: auto;
    }

    .row_style {
        background: #f2f2f2;
        border: 1px solid #c9c9c9 !important;
    }

    .row_style_alt {
        background: #ffffff;
        border: 1px solid #c9c9c9 !important;
    }
    </style>


    <script language="javascript">
    function openInNewTab(url) {
        var win = window.open(url, '_blank');
        win.focus();
    }

    function runreport_trailer() {
        stdt = document.getElementById('start_date').value;
        enddt = document.getElementById('end_date').value;

        openInNewTab("processedtrailerreport.php?start_date=" + stdt + "&end_date=" + enddt);
    }

    function runreport_byweight() {
        stdt = document.getElementById('start_date').value;
        enddt = document.getElementById('end_date').value;

        openInNewTab("processedtrailerreportweights.php?start_date=" + stdt + "&end_date=" + enddt);
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

    function sortdata(compid, colid, sortflg) {
        document.getElementById("active_table").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("active_table").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "water-initiatives-sort.php?compid=" + compid + "&colid=" + colid + "&sortflg=" + sortflg,
            true);
        xmlhttp.send();
    }

    function sortdata2(compid, colid, sortflg) {
        document.getElementById("complete_table").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
        //alert(colid);
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("complete_table").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "water-initiatives-sort.php?table=2&compid=" + compid + "&colid=" + colid + "&sortflg=" +
            sortflg, true);
        xmlhttp.send();
    }

    function showfiles(filename, formtype) {
        var selectobject = document.getElementById('yellowfilename');
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');

        filename = document.getElementById('yellowfilename').value;
        formtype = 'Yellow Sheet'
        document.getElementById("light").innerHTML =
            "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center>" +
            formtype + "</center><br/>  <object data='https://loops.usedcardboardboxes.com/files/" + filename +
            "' type='application/pdf' width='800' height='800'><p>Your web browser does not have a PDF plugin.Instead you can <a href='https://loops.usedcardboardboxes.com/files/" +
            filename + "'>click here to download the PDF file.</a></p></object>";


        document.getElementById('light').style.display = 'block';
        document.getElementById('fade').style.display = 'block';

        document.getElementById('light').style.left = n_left + 10 + 'px';
        document.getElementById('light').style.top = n_top + 10 + 'px';
    }
    //

    function add_newinitiavite() {

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("initiative_div").innerHTML = xmlhttp.responseText;

                alert("Data saved.");
            }
        }

        var compid = document.getElementById('init_companyID').value;
        var task_title = document.getElementById('task_title').value;
        var task_detail = document.getElementById('task_detail').value;
        var due_date = document.getElementById('due_date').value;
        var task_owner = document.getElementById('task_owner').value;
        var init_created_by = "";

        xmlhttp.open("GET", "water-initiavite-save.php?compid=" + compid + "&task_title=" + task_title +
            "&task_detail=" + task_detail + "&due_date=" + due_date + "&task_owner=" + task_owner +
            "&init_created_by=" + init_created_by, true);
        xmlhttp.send();
    }

    function init_markcomp(unqid, compid) {
        var completed_by = prompt("Please enter your name.", "");

        if (completed_by != "") {

            document.getElementById("initiative_div").innerHTML =
                "<br><br>Loading .....<img src='images/wait_animated.gif' />";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("initiative_div").innerHTML = xmlhttp.responseText;
                }
            }

            xmlhttp.open("GET", "water-initiavite-save.php?compid=" + compid + "&taskid=" + unqid +
                "&markcomp=1&completed_by=" + completed_by, true);
            xmlhttp.send();
        }
    }
    </script>

    <!-- Facebook Pixel Code -->
    <script>
    ! function(f, b, e, v, n, t, s) {
        if (f.fbq) return;
        n = f.fbq = function() {
            n.callMethod ?
                n.callMethod.apply(n, arguments) : n.queue.push(arguments)
        };
        if (!f._fbq) f._fbq = n;
        n.push = n;
        n.loaded = !0;
        n.version = '2.0';
        n.queue = [];
        t = b.createElement(e);
        t.async = !0;
        t.src = v;
        s = b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t, s)
    }(window, document, 'script',
        'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '1109377375928443');
    fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1" src="https://www.facebook.com/tr?id=1109377375928443&ev=PageView
	&noscript=1" />
    </noscript>
    <!-- End Facebook Pixel Code -->

    <script>
    function add_newinitiavite1() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("demo").innerHTML =
                    this.responseText;
            }
        };
        xhttp.open("GET", "ajax_info.txt", true);
        xhttp.send();
    }

    function saveNotes(taskid, compid, iniTitle) {
        var txtUcbzwNotes = document.getElementById('txtUcbzwNotes_' + taskid).value;
        var txtUcbzwNotes1 = document.getElementById('txtUcbzwNotes1_' + taskid).value;
        //var hdnCompanyId 	= document.getElementById('hdnCompanyId').value;
        //var hdnTaskId 		= document.getElementById('hdnTaskId').value;

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                //document.getElementById("initiative_div").innerHTML = xmlhttp.responseText;
                alert("Data Saved.");
            }
        }
        xmlhttp.open("GET", "water-initiavite-save-notes.php?compid=" + compid + "&taskid=" + taskid + "&task_notes=" +
            txtUcbzwNotes + "&task_notes1=" + txtUcbzwNotes1 + "&notesUpdate=1" + "&iniTitle=" + iniTitle, true);
        xmlhttp.send();
    }

    function getQueryParameters(value) {
        const queryString = window.location.search;
        const params = new URLSearchParams(queryString);
        params.set('filterid', value);
        return params.toString();
    }

    function filter_active_initiative_records(id) {
        const anchorLink = document.getElementById('filterhiddenlink');
        const existingQueryParams = getQueryParameters(id);
        const url = '?' + existingQueryParams;
        anchorLink.setAttribute('href', url);
        anchorLink.click();
    }
    </script>
</head>

<body>
    <div id="light" class="white_content"></div>
    <div id="fade" class="black_overlay"></div>

    <?php
		db();

		$companyid = 0;
		$warehouse_id = 0;
		$company_name = "";
		$company_logo = "";
		$parent_comp_flg = "";
		$parent_companyid = 0;
		$sql = "SELECT companyid FROM supplierdashboard_usermaster WHERE loginid=? and activate_deactivate = 1";
		//echo $sql . "<br>";
		$result = db_query($sql, array("i"), array($_SESSION['loginid']));
		while ($myrowsel = array_shift($result)) {

			$sql1 = "SELECT logo_image FROM supplierdashboard_details WHERE companyid=? ";
			$result1 = db_query($sql1, array("i"), array($myrowsel["companyid"]));
			while ($myrowsel1 = array_shift($result1)) {
				$company_logo = $myrowsel1["logo_image"];
			}

			$sql1 = "SELECT id, company_name FROM loop_warehouse WHERE b2bid=? ";
			$result1 = db_query($sql1, array("i"), array($myrowsel["companyid"]));
			while ($myrowsel1 = array_shift($result1)) {
				$warehouse_id = $myrowsel1["id"];
				$company_name = $myrowsel1["company_name"] . "'s";
			}

			$companyid = $myrowsel["companyid"];
			$parent_companyid = $myrowsel["companyid"];
		}

		/*to get company details*/
		$note2Title = '';
		db_b2b();
		$selCompDt = db_query("SELECT * FROM companyInfo WHERE ID = " . $companyid);
		$rowCompDt = array_shift($selCompDt);
		$note2Title = $rowCompDt['company'] . "-" . $rowCompDt['city'] . ", " . $rowCompDt['state'];
		$parent_comp_flg1 = $rowCompDt["parent_child"];
		db();
		$_SESSION['pgname'] = "water-initiatives";
		?>

    <?php require("mainfunctions/top-header.php");	?>


    <div class="top-title">
        <h1>Initiatives &nbsp; <i class="fa fa-tint"></i></h1>
    </div>

    <div class="main-inner-container">
        <div class="inner-container1">

            <!-- <div class="sub-title-container">
	<form name="initiative_frm" id="initiative_frm" method="post">
		<SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
		<script LANGUAGE="JavaScript">document.write(getCalendarStyles());</script>
		<script LANGUAGE="JavaScript">
			var cal1xx = new CalendarPopup("listdiv");
			cal1xx.showNavigationDropdowns();
			
		</script>
	<h3>ADD NEW INITIATIVE
	</h3>
		<div id="no-more-tables">
			<div id="enquiry_form-container">
				<div id="enquiry_form">
				  <div class="form_field">Title:</div>
					<div class="text_field">
						<textarea name="task_title" class="form_text" id="task_title"></textarea>
					</div>
				</div>
			
				<div id="enquiry_form">
				  <div class="form_field">Details:</div>
					<div class="text_field">
						<textarea name="task_detail" class="form_text" id="task_detail"></textarea>
					</div>
				</div>
			 
				<div id="enquiry_form">
					<div class="form_field">Due Date:</div>
					  <div class="text_field">
						  <input type="text" class="date_form_text" id="due_date" name="due_date" value=""> 
							<a style="float: left" href="#" onclick="cal1xx.select(document.initiative_frm.due_date,'anchor1xx','MM/dd/yyyy'); return false;" name="anchor1xx" id="anchor1xx">
								<img src="images/calendar.png" alt="calendar">
							</a> 
						  
					  </div>
				</div>
				<div id="enquiry_form">
					<div class="form_field">Task Owner:</div>
					  <div class="text_field">
						  <input name="task_owner" type="text" class="form_text" id="task_owner">
					  </div>
				</div>
				 <div id="enquiry_form">
					 <input type="hidden" name="init_companyID" id="init_companyID" value="<?php echo  $companyid; ?>">
					 <div class="form_field">&nbsp;</div>
					<div class="buttons1"><input type="button" class="logout-button2" value="Submit" onclick="add_newinitiavite()"></div>
				 </div>
			</div>
		</div>
		
		<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
	</form>
	</div>
-->
            <div id="initiative_div">
                <div class="sub-title-container">
                    <h3>Active Initiatives</h3>
                </div>
                <div id="active_table">
                    <div id="no-more-tables_init">
                        <?php
							db_b2b();
							if ($parent_comp_flg1 == "Parent") {
								$vcsql = "select ID, nickname, company from companyInfo where haveNeed = 'Have Boxes' and parent_comp_id=" . $parent_companyid . " and loopid<>0";
							} else {
								$vcsql = "Select ID, nickname, company from companyInfo where ID=" . $parent_companyid . "";
							}

							$vcresult = db_query($vcsql);
							while ($vcrow = array_shift($vcresult)) {
								$companyid = $vcrow["ID"];

							?>
                        <div class="sub-title-container">
                            <h3><?php if ($vcrow["nickname"] != "") {
											echo $vcrow["nickname"];
										} else {
											echo $vcrow["company"];
										} ?></h3>
                        </div>

                        <?php
								$imgasc  = '<img src="images/sort_asc.jpg" width="5px;" height="10px;">';
								$imgdesc = '<img src="images/sort_desc.jpg" width="5px;" height="10px;">';
								$sorturl = "?tblname=active&filterid=" . $_REQUEST['filterid'] . "&sort_order=";
								?>
                        <table>
                            <thead>
                                <tr>

                                    <th class="text-align1">Six Step Proven Process
                                        <a href="<?php echo  $sorturl; ?>ASC&sort=1"><?php echo  $imgasc; ?></a>
                                        <a href="<?php echo  $sorturl; ?>DESC&sort=1"><?php echo  $imgdesc; ?></a>
                                    </th>

                                    <th class="text-align1">Proven Process Status
                                        <a href="<?php echo  $sorturl; ?>ASC&sort=2"><?php echo  $imgasc; ?></a>
                                        <a href="<?php echo  $sorturl; ?>DESC&sort=2"><?php echo  $imgdesc; ?></a>
                                    </th>

                                    <th class="text-align1">Initiative Title
                                        <a href="<?php echo  $sorturl; ?>ASC&sort=3"><?php echo  $imgasc; ?></a>
                                        <a href="<?php echo  $sorturl; ?>DESC&sort=3"><?php echo  $imgdesc; ?></a>
                                    </th>

                                    <th class="text-align1">Initiative Details
                                        <a href="<?php echo  $sorturl; ?>ASC&sort=4"><?php echo  $imgasc; ?></a>
                                        <a href="<?php echo  $sorturl; ?>DESC&sort=4"><?php echo  $imgdesc; ?></a>
                                    </th>

                                    <th class="text-align1">Annual Savings Opportunity (Gross Value)
                                        <a href="<?php echo  $sorturl; ?>ASC&sort=5"><?php echo  $imgasc; ?></a>
                                        <a href="<?php echo  $sorturl; ?>DESC&sort=5"><?php echo  $imgdesc; ?></a>
                                    </th>

                                    <th class="text-align1">Potential % Increase in Total Landfill Diversion
                                        <a href="<?php echo  $sorturl; ?>ASC&sort=6"><?php echo  $imgasc; ?></a>
                                        <a href="<?php echo  $sorturl; ?>DESC&sort=6"><?php echo  $imgdesc; ?></a>
                                    </th>

                                    <th class="text-align1">Initiative Status
                                        <!-- 
							<a href="<?php echo  $sorturl; ?>ASC&sort=7"><?php echo  $imgasc; ?></a>
							<a href="<?php echo  $sorturl; ?>DESC&sort=7"><?php echo  $imgdesc; ?></a>
							-->
                                        <a href="#" id="filterhiddenlink" style="display:none;">hidden</a>
                                        <select onchange="filter_active_initiative_records(this.value)"
                                            class="form_component" style="width: 90px;">
                                            <option value="">Select</option>
                                            <?php
													$int_task_qry = "SELECT * FROM water_task_status WHERE id <> 4";
													db();
													$int_task_res = db_query($int_task_qry);
													while ($int_task_rows = array_shift($int_task_res)) {
													?>
                                            <option value="<?php echo $int_task_rows["id"]; ?>" <?php if (isset($_REQUEST["filterid"]) && $_REQUEST["filterid"] == $int_task_rows["id"]) {
																												echo "selected";
																											} else {
																											} ?>><?php echo $int_task_rows["task_status"]; ?></option>
                                            <?php
													} ?>
                                        </select>
                                    </th>

                                    <th class="text-align1">Due Date
                                        <a href="<?php echo  $sorturl; ?>ASC&sort=8"><?php echo  $imgasc; ?></a>
                                        <a href="<?php echo  $sorturl; ?>DESC&sort=8"><?php echo  $imgdesc; ?></a>
                                    </th>

                                    <th class="text-align1">Task Owner
                                        <a href="<?php echo  $sorturl; ?>ASC&sort=9"><?php echo  $imgasc; ?></a>
                                        <a href="<?php echo  $sorturl; ?>DESC&sort=9"><?php echo  $imgdesc; ?></a>
                                    </th>

                                    <th class="text-align1">Date Created
                                        <a href="<?php echo  $sorturl; ?>ASC&sort=10"><?php echo  $imgasc; ?></a>
                                        <a href="<?php echo  $sorturl; ?>DESC&sort=10"><?php echo  $imgdesc; ?></a>
                                    </th>

                                    <th width="90px" class="text-align1"
                                        style="white-space: normal; word-wrap: break-word;">UCBZW Notes
                                        <a href="<?php echo  $sorturl; ?>ASC&sort=11"><?php echo  $imgasc; ?></a>
                                        <a href="<?php echo  $sorturl; ?>DESC&sort=11"><?php echo  $imgdesc; ?></a>
                                    </th>

                                    <th width="90px" class="text-align1"
                                        style="white-space: normal; word-wrap: break-word;"><?php echo  $note2Title ?>
                                        <a href="<?php echo  $sorturl; ?>ASC&sort=12"><?php echo  $imgasc; ?></a>
                                        <a href="<?php echo  $sorturl; ?>DESC&sort=12"><?php echo  $imgdesc; ?></a>
                                    </th>

                                    <th width="90px" class="text-align1"
                                        style="white-space: normal; word-wrap: break-word;">&nbsp;</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php

										$annual_saving_opportunity_total = 0;
										$annual_saving_implemented_total = 0;
										$annual_saving_implement_total = 0;
										$annual_saving_rejected_total = 0;
										$per_landfill_total = 0;
										$landfill_implemented_total = 0;
										$landfill_implement_total = 0;
										$landfill_rejected_total = 0;
										$all_row = 0;
										$all_row_cnt = 0;
										$all_row_cnt_incomplete = 0;

										if (isset($_REQUEST['tblname']) && $_REQUEST['tblname'] == "active") {
											if (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "1") {
												if ($_REQUEST['sort_order'] == "ASC") {
													$orderby = "ORDER BY initiative_step ASC";
												} else {
													$orderby = "ORDER BY initiative_step DESC";
												}
											} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "2") {
												if ($_REQUEST['sort_order'] == "ASC") {
													$orderby = "ORDER BY initiative_step_status ASC";
												} else {
													$orderby = "ORDER BY initiative_step_status DESC";
												}
											} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "3") {
												if ($_REQUEST['sort_order'] == "ASC") {
													$orderby = "ORDER BY task_sub_id ASC";
												} else {
													$orderby = "ORDER BY task_sub_id DESC";
												}
											} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "4") {
												if ($_REQUEST['sort_order'] == "ASC") {
													$orderby = "ORDER BY task_details ASC";
												} else {
													$orderby = "ORDER BY task_details DESC";
												}
											} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "5") {
												if ($_REQUEST['sort_order'] == "ASC") {
													$orderby = "ORDER BY annual_saving_opportunity ASC";
												} else {
													$orderby = "ORDER BY annual_saving_opportunity DESC";
												}
											} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "6") {
												if ($_REQUEST['sort_order'] == "ASC") {
													$orderby = "ORDER BY per_in_landfill_total ASC";
												} else {
													$orderby = "ORDER BY per_in_landfill_total DESC";
												}
												//}elseif(isset($_REQUEST['sort']) && $_REQUEST['sort'] == "7"){
												//	if($_REQUEST['sort_order'] == "ASC"){
												//		$orderby = "ORDER BY task_status ASC";
												//	}else{
												//		$orderby = "ORDER BY task_status DESC";
												//	}
											} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "8") {
												if ($_REQUEST['sort_order'] == "ASC") {
													$orderby = "ORDER BY due_date ASC";
												} else {
													$orderby = "ORDER BY due_date DESC";
												}
											} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "9") {
												if ($_REQUEST['sort_order'] == "ASC") {
													$orderby = "ORDER BY task_owner ASC";
												} else {
													$orderby = "ORDER BY task_owner DESC";
												}
											} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "10") {
												if ($_REQUEST['sort_order'] == "ASC") {
													$orderby = "ORDER BY task_added_on ASC";
												} else {
													$orderby = "ORDER BY task_added_on DESC";
												}
											} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "11") {
												if ($_REQUEST['sort_order'] == "ASC") {
													$orderby = "ORDER BY task_notes ASC";
												} else {
													$orderby = "ORDER BY task_notes DESC";
												}
											} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "12") {
												if ($_REQUEST['sort_order'] == "ASC") {
													$orderby = "ORDER BY task_notes1 ASC";
												} else {
													$orderby = "ORDER BY task_notes1 DESC";
												}
											}
											$orderby = $orderby ?? "";
											$query = "SELECT * FROM water_initiatives where companyid = " . $companyid . " and status = 1 and task_status <> 4 $orderby";

											$res = db_query($query);
											$total_steps = tep_db_num_rows($res);
											//echo $rw_color."--steps-".$total_steps;
											if (isset($alt) == 1) {
												$bgcolor = "#f2f2f2";
												$table_row_css = "row_style";
											} else {
												$bgcolor = "#ffffff";
												$table_row_css = "row_style_alt";
											}
										?>

                                <?php
											$rw_color = 0;
											echo "<pre>";
											while ($row = array_shift($res)) {
												$rw_color = $rw_color + 1;
												//echo $rw_color."<br>";
												$date1 = new DateTime($row["due_date"]);
												$date2 = new DateTime();

												$days = (strtotime($row["due_date"]) - strtotime(date("Y-m-d"))) / (60 * 60 * 24);

												$task_status = "";
												$int_task_qry = "select * from water_task_status where id = '" . $row["task_status"] . "'";
												db();
												$int_task_res = db_query($int_task_qry);
												while ($int_task_rows = array_shift($int_task_res)) {
													$task_status = $int_task_rows["task_status"];
												}

												$task_status_bgcolor = "";
												if ($row["task_status"] == 4) {
													$task_status_bgcolor = "style='background-color:#AAFFAA'";
												} else if ($row["task_status"] == 1) {
													$task_status_bgcolor = "style='background-color:#FFFFAA'";
												} else if ($row["task_status"] == "") {
													$task_status_bgcolor = "";
												} else {
													$task_status_bgcolor = "style='background-color:#FFAAAA'";
												}
											?>
                                <tr id="init_step_row<?php echo $row["initiative_step"]; ?>">
                                    <td align="left" valign="top" class="display_table <?php echo $table_row_css; ?>">
                                        <?php
														$int_step_qry = "select * from water_initiative_steps where unique_key='" . $row["initiative_step"] . "'";
														db();
														$int_step_res = db_query($int_step_qry);
														$int_step_rows = array_shift($int_step_res);
														echo $int_step_rows["initiative_step"];
														?>
                                    </td>
                                    <td class="display_table <?php echo $table_row_css; ?>" align="left" valign="top">
                                        <?php echo $row["initiative_step_status"]; ?>
                                    </td>
                                    <!-- <td rowspan="<?php echo  $total_steps ?>" class="display_table <?php echo $table_row_css; ?>" align="left" valign="top">
								<?php if ($row["step_due_date"] != "" && $row["step_due_date"] != "0000-00-00") {
													echo isset($step_rows["step_due_date"]);
												} ?>
							</td> -->

                                    <td class="<?php echo $table_row_css; ?>" align="left">
                                        <table>
                                            <tr>
                                                <td valign="top" style="width: 15px;">
                                                    <?php echo  $row["task_sub_id"] . ". " ?></td>
                                                <td align="left"><?php echo $row["task_title"]; ?></td>
                                            </tr>
                                        </table>
                                    </td>

                                    <td class="<?php echo $table_row_css; ?>">
                                        <?php echo $row["task_details"]; ?>
                                    </td>
                                    <td class="<?php echo  $table_row_css; ?>">
                                        <?php
														$all_row_cnt_incomplete++;
														echo "<table><tr><td class='display_table'>Annual Savings Opportunity (Gross Value):</td><td class='display_table' align='right'>$" . number_format($row["annual_saving_opportunity"], 0) . "</td></tr>";

														echo "<tr><td style='text-align: center;font-size: 12px;'><a style='color: blue;' href='javascript:void(0)' onclick='collapse_div(this)'>Show</a>
								</td></tr>";

														echo "<tr style='display:none'><td class='display_table'>Annual Savings Implemented:</td><td class='display_table'  align='right'>$" . number_format($row["annual_saving_implemented"], 0) . "</td></tr>";
														echo "<tr style='display:none'><td class='display_table'>Annual Savings to Implement:</td><td class='display_table'  align='right'>$" . number_format($row["annual_saving_implement"], 0) . "</td></tr>";
														echo "<tr style='display:none'><td class='display_table'>Annual Savings Rejected:</td><td class='display_table' align='right'>$" . number_format($row["annual_saving_rejected"], 0) . "</td></tr></table>";

														$annual_saving_opportunity_total = $annual_saving_opportunity_total + str_replace(",", "", number_format($row["annual_saving_opportunity"], 0));
														$annual_saving_implemented_total = $annual_saving_implemented_total + str_replace(",", "", number_format($row["annual_saving_implemented"], 0));
														$annual_saving_implement_total = $annual_saving_implement_total + str_replace(",", "", number_format($row["annual_saving_implement"], 0));
														$annual_saving_rejected_total = $annual_saving_rejected_total + str_replace(",", "", number_format($row["annual_saving_rejected"], 0));

														?>
                                    </td>
                                    <td class="<?php echo  $table_row_css; ?>">
                                        <?php

														echo "<table>";
														echo "<tr><td class='display_table'>Potential % Increase in Total Landfill Diversion:</td><td class='display_table'  align='right'>" . number_format($row["per_in_landfill_total"], 0) . "%</td></tr>";

														echo "<tr><td style='text-align: center;font-size: 12px;'><a style='color: blue;' href='javascript:void(0)' onclick='collapse_div(this)'>Show</a>
							</td></tr>";

														echo "<tr style='display:none'><td class='display_table'>Landfill Diversion Implemented:</td><td class='display_table'  align='right'>" . number_format($row["landfill_diversion_implemented"], 0) . "%</td></tr>";
														echo "<tr style='display:none'><td class='display_table'>Landfill Diversion to Implement:</td><td class='display_table'  align='right'>" . number_format($row["landfill_diversion_implement"], 0) . "%</td></tr>";
														echo "<tr style='display:none'><td class='display_table'>Landfill Diversion Rejected:</td><td class='display_table'  align='right'>" . number_format($row["landfill_diversion_rejected"], 0) . "%</td></tr></table>";

														$per_landfill_total = $per_landfill_total + str_replace(",", "", number_format($row["per_in_landfill_total"], 0));
														$landfill_implemented_total = $landfill_implemented_total + str_replace(",", "", number_format($row["landfill_diversion_implemented"], 0));
														$landfill_implement_total = $landfill_implement_total + str_replace(",", "", number_format($row["landfill_diversion_implement"], 0));
														$landfill_rejected_total = $landfill_rejected_total + str_replace(",", "", number_format($row["landfill_diversion_rejected"], 0));

														?>
                                    </td>
                                    <td <?php echo  $task_status_bgcolor; ?> class="<?php echo $table_row_css; ?>">
                                        <?php echo $task_status; ?>
                                    </td>
                                    <!--<td class="text-align1"><?php echo date("m/d/Y", strtotime($row["due_date"])) . " CT"; ?></td>-->

                                    <?php if ($days == 0 || $task_status == "Completed") { ?>
                                    <td bgcolor="green">
                                        <?php echo date("m/d/Y", strtotime($row["due_date"])) . " CT"; ?></td>
                                    <?php }

													if ($days > 0) { ?>
                                    <td bgcolor="#E4E4E4">
                                        <?php echo date("m/d/Y", strtotime($row["due_date"])) . " CT"; ?></td>
                                    <?php }

													if ($days < 0 && $task_status != "Completed") { ?>
                                    <td bgcolor="red"><?php echo date("m/d/Y", strtotime($row["due_date"])) . " CT"; ?>
                                    </td>
                                    <?php } ?>
                                    <td class="text-align1 <?php echo $table_row_css; ?>">
                                        <?php echo  $row["task_owner"] ?></td>

                                    <td class="text-align1 <?php echo $table_row_css; ?>">
                                        <?php echo  date("m/d/Y", strtotime($row["task_added_on"])) ?></td>
                                    <!-- <td class="<?php echo $table_row_css; ?>" align="left"><?php echo  $row["task_notes"] ?></td> -->
                                    <td class="<?php echo $table_row_css; ?>" align="left">
                                        <textarea name="txtUcbzwNotes_<?php echo  $row["taskid"] ?>"
                                            id="txtUcbzwNotes_<?php echo  $row["taskid"] ?>" cols="8"
                                            class="form_component"><?php echo $row["task_notes"] ?></textarea>
                                    </td>
                                    <td class="<?php echo $table_row_css; ?>" align="left">
                                        <textarea name="txtUcbzwNotes1_<?php echo  $row["taskid"] ?>"
                                            id="txtUcbzwNotes1_<?php echo  $row["taskid"] ?>" cols="8"
                                            class="form_component"><?php echo $row["task_notes1"] ?></textarea>
                                    </td>
                                    <td class="<?php echo $table_row_css; ?>" align="left">
                                        <?php
														if (empty($row["task_notes"]) && empty($row["task_notes1"])) {
															$btnText = 'Save Notes';
														} else {
															$btnText = 'Save Notes';
														}
														?>
                                        <input type="button" name="btnNotes" value="<?php echo  $btnText ?>"
                                            onclick="saveNotes(<?php echo  $row["taskid"]; ?>, <?php echo  $companyid; ?>,'<?php echo  $row["task_sub_id"] . ". " . $row["task_title"]; ?>')">
                                    </td>
                                    <!-- <td class="text-align1">
								<input type="button" value="Mark Complete" name="init_markcompl" id="init_markcompl" onclick="init_markcomp(<?php echo  $row["taskid"]; ?>, <?php echo $companyid; ?>)">
							</td> -->
                                </tr>
                                <?php
											}

											if ($rw_color == $total_steps) {
												if (isset($alt) == 0) {
													$alt = 1;
												} else {
													$alt = 0;
												}
											}
										} else {
											$orderby = "ORDER BY initiative_step ASC, taskid ASC";

											//Split the code, it is repeated, but when sort is done then line items need to be shown
											if (isset($_REQUEST["filterid"]) && $_REQUEST["filterid"] != "") {
												$sql = "SELECT *, sum(annual_saving_opportunity) AS aso, sum(per_in_landfill_total) as pilt FROM water_initiatives where companyid = " . $companyid . " and status = 1 and task_status ='" . $_REQUEST["filterid"] . "' group by initiative_step  $orderby";
											} else {
												$sql = "SELECT *, sum(annual_saving_opportunity) AS aso, sum(per_in_landfill_total) as pilt FROM water_initiatives where companyid = " . $companyid . " and status = 1 and task_status <> 4 group by initiative_step $orderby";
											}

											//echo $sql . "<br>";
											db();
											$result = db_query($sql);
											if (tep_db_num_rows($result) > 0) {
												$cnt = 0;
												$alt = 0;
												while ($step_rows = array_shift($result)) {
													if (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "1") {
														if ($_REQUEST['sort_order'] == "ASC") {
															$orderby2 = "ORDER BY initiative_step ASC";
														} else {
															$orderby2 = "ORDER BY initiative_step DESC";
														}
													} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "2") {
														if ($_REQUEST['sort_order'] == "ASC") {
															$orderby2 = "ORDER BY initiative_step_status ASC";
														} else {
															$orderby2 = "ORDER BY initiative_step_status DESC";
														}
													} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "3") {
														if ($_REQUEST['sort_order'] == "ASC") {
															$orderby2 = "ORDER BY task_sub_id ASC";
														} else {
															$orderby2 = "ORDER BY task_sub_id DESC";
														}
													} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "4") {
														if ($_REQUEST['sort_order'] == "ASC") {
															$orderby2 = "ORDER BY task_details ASC";
														} else {
															$orderby2 = "ORDER BY task_details DESC";
														}
													} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "5") {
														if ($_REQUEST['sort_order'] == "ASC") {
															$orderby2 = "ORDER BY annual_saving_opportunity ASC";
														} else {
															$orderby2 = "ORDER BY annual_saving_opportunity DESC";
														}
													} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "6") {
														if ($_REQUEST['sort_order'] == "ASC") {
															$orderby2 = "ORDER BY per_in_landfill_total ASC";
														} else {
															$orderby2 = "ORDER BY per_in_landfill_total DESC";
														}
													} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "7") {
														if ($_REQUEST['sort_order'] == "ASC") {
															$orderby2 = "ORDER BY task_status ASC";
														} else {
															$orderby2 = "ORDER BY task_status DESC";
														}
													} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "8") {
														if ($_REQUEST['sort_order'] == "ASC") {
															$orderby2 = "ORDER BY due_date ASC";
														} else {
															$orderby2 = "ORDER BY due_date DESC";
														}
													} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "9") {
														if ($_REQUEST['sort_order'] == "ASC") {
															$orderby2 = "ORDER BY task_owner ASC";
														} else {
															$orderby2 = "ORDER BY task_owner DESC";
														}
													} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "10") {
														if ($_REQUEST['sort_order'] == "ASC") {
															$orderby2 = "ORDER BY task_added_on ASC";
														} else {
															$orderby2 = "ORDER BY task_added_on DESC";
														}
													} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "11") {
														if ($_REQUEST['sort_order'] == "ASC") {
															$orderby2 = "ORDER BY task_notes ASC";
														} else {
															$orderby2 = "ORDER BY task_notes DESC";
														}
													} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "12") {
														if ($_REQUEST['sort_order'] == "ASC") {
															$orderby2 = "ORDER BY task_notes1 ASC";
														} else {
															$orderby2 = "ORDER BY task_notes1 DESC";
														}
													} else {
														$orderby2 = "ORDER BY task_sub_id ASC, due_date DESC";
													}

													$query = "SELECT * FROM water_initiatives where companyid = " . $companyid . " and status = 1 and task_status <> 4 and initiative_step= '" . $step_rows["initiative_step"] . "' $orderby2";

													$res = db_query($query);
													$total_steps = tep_db_num_rows($res);
													//echo $rw_color."--steps-".$total_steps;
													if ($alt == 1) {
														$bgcolor = "#f2f2f2";
														$table_row_css = "row_style";
													} else {
														$bgcolor = "#ffffff";
														$table_row_css = "row_style_alt";
													}
												?>
                                <tr id="init_step_row<?php echo $step_rows["initiative_step"]; ?>">
                                    <td rowspan="<?php echo  $total_steps ?>" align="left" valign="top"
                                        class="display_table <?php echo $table_row_css; ?>">
                                        <?php
															$int_step_qry = "select * from water_initiative_steps where unique_key='" . $step_rows["initiative_step"] . "'";
															db();
															$int_step_res = db_query($int_step_qry);
															$int_step_rows = array_shift($int_step_res);
															echo $int_step_rows["initiative_step"];
															?>
                                    </td>
                                    <td rowspan="<?php echo  $total_steps ?>"
                                        class="display_table <?php echo $table_row_css; ?>" align="left" valign="top">
                                        <?php echo $step_rows["initiative_step_status"]; ?>
                                    </td>
                                    <!-- <td rowspan="<?php echo  $total_steps ?>" class="display_table <?php echo $table_row_css; ?>" align="left" valign="top">
												<?php if ($step_rows["step_due_date"] != "" && $step_rows["step_due_date"] != "0000-00-00") {
														echo $step_rows["step_due_date"];
													} ?>
											</td> -->

                                    <?php
														$rw_color = 0;
														echo "<pre>";
														// print_r($res);
														// print_r($_SESSION);
														while ($row = array_shift($res)) {
															$rw_color = $rw_color + 1;
															//echo $rw_color."<br>";
															$date1 = new DateTime($row["due_date"]);
															$date2 = new DateTime();

															$days = (strtotime($row["due_date"]) - strtotime(date("Y-m-d"))) / (60 * 60 * 24);

															$task_status = "";
															$int_task_qry = "select * from water_task_status where id = '" . $row["task_status"] . "'";
															db();
															$int_task_res = db_query($int_task_qry);
															while ($int_task_rows = array_shift($int_task_res)) {
																$task_status = $int_task_rows["task_status"];
															}

															$task_status_bgcolor = "";
															if ($row["task_status"] == 4) {
																$task_status_bgcolor = "style='background-color:#AAFFAA'";
															} else if ($row["task_status"] == 1) {
																$task_status_bgcolor = "style='background-color:#FFFFAA'";
															} else if ($row["task_status"] == "") {
																$task_status_bgcolor = "";
															} else {
																$task_status_bgcolor = "style='background-color:#FFAAAA'";
															}
														?>
                                    <td class="<?php echo $table_row_css; ?>" align="left">
                                        <table>
                                            <tr>
                                                <td valign="top" style="width: 15px;">
                                                    <?php echo  $row["task_sub_id"] . ". " ?></td>
                                                <td align="left"><?php echo $row["task_title"]; ?></td>
                                            </tr>
                                        </table>
                                    </td>

                                    <td class="<?php echo $table_row_css; ?>">
                                        <?php echo $row["task_details"]; ?>
                                    </td>
                                    <td class="<?php echo  $table_row_css; ?>">
                                        <?php
																$all_row_cnt_incomplete++;
																echo "<table><tr><td class='display_table'>Annual Savings Opportunity (Gross Value):</td><td class='display_table' align='right'>$" . number_format($row["annual_saving_opportunity"], 0) . "</td></tr>";

																echo "<tr><td style='text-align: center;font-size: 12px;'><a style='color: blue;' href='javascript:void(0)' onclick='collapse_div(this)'>Show</a>
									</td></tr>";

																echo "<tr style='display:none'><td class='display_table'>Annual Savings Implemented:</td><td class='display_table'  align='right'>$" . number_format($row["annual_saving_implemented"], 0) . "</td></tr>";
																echo "<tr style='display:none'><td class='display_table'>Annual Savings to Implement:</td><td class='display_table'  align='right'>$" . number_format($row["annual_saving_implement"], 0) . "</td></tr>";
																echo "<tr style='display:none'><td class='display_table'>Annual Savings Rejected:</td><td class='display_table' align='right'>$" . number_format($row["annual_saving_rejected"], 0) . "</td></tr></table>";

																$annual_saving_opportunity_total = $annual_saving_opportunity_total + str_replace(",", "", number_format($row["annual_saving_opportunity"], 0));
																$annual_saving_implemented_total = $annual_saving_implemented_total + str_replace(",", "", number_format($row["annual_saving_implemented"], 0));
																$annual_saving_implement_total = $annual_saving_implement_total + str_replace(",", "", number_format($row["annual_saving_implement"], 0));
																$annual_saving_rejected_total = $annual_saving_rejected_total + str_replace(",", "", number_format($row["annual_saving_rejected"], 0));

																?>
                                    </td>
                                    <td class="<?php echo  $table_row_css; ?>">
                                        <?php

																echo "<table>";
																echo "<tr><td class='display_table'>Potential % Increase in Total Landfill Diversion:</td><td class='display_table'  align='right'>" . number_format($row["per_in_landfill_total"], 0) . "%</td></tr>";

																echo "<tr><td style='text-align: center;font-size: 12px;'><a style='color: blue;' href='javascript:void(0)' onclick='collapse_div(this)'>Show</a>
								</td></tr>";

																echo "<tr style='display:none'><td class='display_table'>Landfill Diversion Implemented:</td><td class='display_table'  align='right'>" . number_format($row["landfill_diversion_implemented"], 0) . "%</td></tr>";
																echo "<tr style='display:none'><td class='display_table'>Landfill Diversion to Implement:</td><td class='display_table'  align='right'>" . number_format($row["landfill_diversion_implement"], 0) . "%</td></tr>";
																echo "<tr style='display:none'><td class='display_table'>Landfill Diversion Rejected:</td><td class='display_table'  align='right'>" . number_format($row["landfill_diversion_rejected"], 0) . "%</td></tr></table>";

																$per_landfill_total = $per_landfill_total + str_replace(",", "", number_format($row["per_in_landfill_total"], 0));
																$landfill_implemented_total = $landfill_implemented_total + str_replace(",", "", number_format($row["landfill_diversion_implemented"], 0));
																$landfill_implement_total = $landfill_implement_total + str_replace(",", "", number_format($row["landfill_diversion_implement"], 0));
																$landfill_rejected_total = $landfill_rejected_total + str_replace(",", "", number_format($row["landfill_diversion_rejected"], 0));

																?>
                                    </td>
                                    <td <?php echo  $task_status_bgcolor; ?> class="<?php echo $table_row_css; ?>">
                                        <?php echo $task_status; ?>
                                    </td>
                                    <!--<td class="text-align1"><?php echo date("m/d/Y", strtotime($row["due_date"])) . " CT"; ?></td>-->

                                    <?php if ($days == 0 || $task_status == "Completed") { ?>
                                    <td bgcolor="green">
                                        <?php echo date("m/d/Y", strtotime($row["due_date"])) . " CT"; ?></td>
                                    <?php }

															if ($days > 0) { ?>
                                    <td bgcolor="#E4E4E4">
                                        <?php echo date("m/d/Y", strtotime($row["due_date"])) . " CT"; ?></td>
                                    <?php }

															if ($days < 0 && $task_status != "Completed") { ?>
                                    <td bgcolor="red"><?php echo date("m/d/Y", strtotime($row["due_date"])) . " CT"; ?>
                                    </td>
                                    <?php } ?>
                                    <td class="text-align1 <?php echo $table_row_css; ?>">
                                        <?php echo  $row["task_owner"] ?></td>

                                    <td class="text-align1 <?php echo $table_row_css; ?>">
                                        <?php echo  date("m/d/Y", strtotime($row["task_added_on"])) ?></td>
                                    <!-- <td class="<?php echo $table_row_css; ?>" align="left"><?php echo  $row["task_notes"] ?></td> -->
                                    <td class="<?php echo $table_row_css; ?>" align="left">
                                        <textarea name="txtUcbzwNotes_<?php echo  $row["taskid"] ?>"
                                            id="txtUcbzwNotes_<?php echo  $row["taskid"] ?>" cols="8"
                                            class="form_component"><?php echo $row["task_notes"] ?></textarea>
                                    </td>
                                    <td class="<?php echo $table_row_css; ?>" align="left">
                                        <textarea name="txtUcbzwNotes1_<?php echo  $row["taskid"] ?>"
                                            id="txtUcbzwNotes1_<?php echo  $row["taskid"] ?>" cols="8"
                                            class="form_component"><?php echo $row["task_notes1"] ?></textarea>
                                    </td>
                                    <td class="<?php echo $table_row_css; ?>" align="left">
                                        <?php
																if (empty($row["task_notes"]) && empty($row["task_notes1"])) {
																	$btnText = 'Save Notes';
																} else {
																	$btnText = 'Save Notes';
																}
																?>
                                        <input type="button" name="btnNotes" value="<?php echo  $btnText ?>"
                                            onclick="saveNotes(<?php echo  $row["taskid"]; ?>, <?php echo  $companyid; ?>,'<?php echo  $row["task_sub_id"] . ". " . $row["task_title"]; ?>')">
                                    </td>
                                    <!-- <td class="text-align1">
									<input type="button" value="Mark Complete" name="init_markcompl" id="init_markcompl" onclick="init_markcomp(<?php echo  $row["taskid"]; ?>, <?php echo $companyid; ?>)">
								</td> -->
                                </tr>
                                <?php

														}
														if ($rw_color == $total_steps) {
															if ($alt == 0) {
																$alt = 1;
															} else {
																$alt = 0;
															}
														}
													} //end step row
												}
											}


									?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="<?php echo isset($table_row_css); ?>" colspan="4"
                                        style="text-align:right; font-weight:bold">Total:&nbsp;</td>
                                    <td class="<?php echo isset($table_row_css); ?>">
                                        <table>
                                            <tr>
                                                <td class='display_table'>TOTAL SUM for Annual Savings Opportunity:</td>
                                                <td class='display_table' align='right'>
                                                    <b>$<?php echo number_format($annual_saving_opportunity_total, 2); ?></b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class='display_table'>TOTAL SUM for Annual Savings Implemented:</td>
                                                <td class='display_table' align='right'>
                                                    <b>$<?php echo number_format($annual_saving_implemented_total, 2); ?></b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class='display_table'>TOTAL SUM for Annual Savings to Implement:
                                                </td>
                                                <td class='display_table' align='right'>
                                                    <b>$<?php echo number_format($annual_saving_implement_total, 2); ?></b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class='display_table'>TOTAL SUM for Annual Savings Rejected:</td>
                                                <td class='display_table' align='right'>
                                                    <b>$<?php echo number_format($annual_saving_rejected_total, 2); ?></b>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="<?php echo isset($table_row_css); ?>" align="right">
                                        <table>
                                            <tr>
                                                <td class='display_table'>TOTAL SUM for Potential % Increase in Total
                                                    Landfill Diversion:</td>
                                                <td class='display_table' align='right'><b>
                                                        <?php
																if ($all_row_cnt_incomplete > 0) {

																	echo number_format($per_landfill_total / $all_row_cnt_incomplete, 2);
																} else {
																	echo "0";
																}

																?>
                                                        %</b></td>
                                            </tr>
                                            <tr>
                                                <td class='display_table'>TOTAL SUM for Landfill Diversion Implemented:
                                                </td>
                                                <td class='display_table' align='right'>
                                                    <b><?php echo number_format($landfill_implemented_total, 2); ?>%</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class='display_table'>TOTAL SUM for Landfill Diversion to Implement:
                                                </td>
                                                <td class='display_table' align='right'>
                                                    <b><?php echo number_format($landfill_implement_total, 2); ?>%</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class='display_table'>TOTAL SUM for Landfill Diversion Rejected:
                                                </td>
                                                <td class='display_table' align='right'>
                                                    <b><?php echo number_format($landfill_rejected_total, 2); ?>%</b>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="<?php echo isset($table_row_css); ?>" colspan="7">&nbsp;</td>
                                </tr>
                            </tfoot>
                        </table>

                        <?php
							}
							?>

                    </div>
                </div>

                <div class="sub-title-container">
                    <h3>Completed Initiatives</h3>
                </div>
                <div id="complete_table">
                    <div id="no-more-tables_init">
                        <?php
							db_b2b();
							if ($parent_comp_flg1 == "Parent") {
								$vcsql = "select ID, nickname, company from companyInfo where haveNeed = 'Have Boxes' and parent_comp_id=" . $parent_companyid . " and loopid<>0";
							} else {
								$vcsql = "Select ID, nickname, company from companyInfo where ID=" . $parent_companyid . "";
							}

							$vcresult = db_query($vcsql);
							while ($vcrow = array_shift($vcresult)) {
								$companyid = $vcrow["ID"];

							?>
                        <div class="sub-title-container">
                            <h3><?php if ($vcrow["nickname"] != "") {
											echo $vcrow["nickname"];
										} else {
											echo $vcrow["company"];
										} ?></h3>
                        </div>
                        <?php
								$imgasc  = '<img src="images/sort_asc.jpg" width="5px;" height="10px;">';
								$imgdesc = '<img src="images/sort_desc.jpg" width="5px;" height="10px;">';
								$sorturl2 = "?tblname=completed&sort_order=";
								?>
                        <table>
                            <thead>
                                <tr>
                                    <th width="5%" class="text-align1">Six Step Proven Process
                                        <a href="<?php echo  $sorturl2; ?>ASC&sort=1"><?php echo  $imgasc; ?></a>
                                        <a href="<?php echo  $sorturl2; ?>DESC&sort=1"><?php echo  $imgdesc; ?></a>
                                    </th>

                                    <th width="5%" class="text-align1">Proven Process Status
                                        <a href="<?php echo  $sorturl2; ?>ASC&sort=2"><?php echo  $imgasc; ?></a>
                                        <a href="<?php echo  $sorturl2; ?>DESC&sort=2"><?php echo  $imgdesc; ?></a>
                                    </th>

                                    <th class="text-align1">Initiative Title
                                        <a href="<?php echo  $sorturl2; ?>ASC&sort=3"><?php echo  $imgasc; ?></a>
                                        <a href="<?php echo  $sorturl2; ?>DESC&sort=3"><?php echo  $imgdesc; ?></a>
                                    </th>

                                    <th class="text-align1">Initiative Details
                                        <a href="<?php echo  $sorturl2; ?>ASC&sort=4"><?php echo  $imgasc; ?></a>
                                        <a href="<?php echo  $sorturl2; ?>DESC&sort=4"><?php echo  $imgdesc; ?></a>
                                    </th>

                                    <th class="text-align1">Annual Savings Opportunity (Gross Value)
                                        <a href="<?php echo  $sorturl2; ?>ASC&sort=5"><?php echo  $imgasc; ?></a>
                                        <a href="<?php echo  $sorturl2; ?>DESC&sort=5"><?php echo  $imgdesc; ?></a>
                                    </th>

                                    <th class="text-align1">Potential % Increase in Total Landfill Diversion
                                        <a href="<?php echo  $sorturl2; ?>ASC&sort=6"><?php echo  $imgasc; ?></a>
                                        <a href="<?php echo  $sorturl2; ?>DESC&sort=6"><?php echo  $imgdesc; ?></a>
                                    </th>

                                    <th width="10%" class="text-align1">Initiative Status
                                        <a href="<?php echo  $sorturl2; ?>ASC&sort=7"><?php echo  $imgasc; ?></a>
                                        <a href="<?php echo  $sorturl2; ?>DESC&sort=7"><?php echo  $imgdesc; ?></a>
                                    </th>

                                    <th width="5%" class="text-align1">Task Owner
                                        <a href="<?php echo  $sorturl2; ?>ASC&sort=9"><?php echo  $imgasc; ?></a>
                                        <a href="<?php echo  $sorturl2; ?>DESC&sort=9"><?php echo  $imgdesc; ?></a>
                                    </th>

                                    <th width="5%" class="text-align1">Date Created
                                        <a href="<?php echo  $sorturl2; ?>ASC&sort=10"><?php echo  $imgasc; ?></a>
                                        <a href="<?php echo  $sorturl2; ?>DESC&sort=10"><?php echo  $imgdesc; ?></a>
                                    </th>

                                    <th width="10%" class="text-align1"
                                        style="white-space: normal; word-wrap: break-word;">UCBZW
                                        <a href="<?php echo  $sorturl2; ?>ASC&sort=11"><?php echo  $imgasc; ?></a>
                                        <a href="<?php echo  $sorturl2; ?>DESC&sort=11"><?php echo  $imgdesc; ?></a>
                                    </th>

                                    <th width="10%" class="text-align1"
                                        style="white-space: normal; word-wrap: break-word;"><?php echo  $note2Title ?>
                                        <a href="<?php echo  $sorturl2; ?>ASC&sort=12"><?php echo  $imgasc; ?></a>
                                        <a href="<?php echo  $sorturl2; ?>DESC&sort=12"><?php echo  $imgdesc; ?></a>
                                    </th>

                                    <th width="10%" class="text-align1">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
										$complete_opportunity_total = 0;
										$complete_implemented_total = 0;
										$complete_implement_total = 0;
										$complete_rejected_total = 0;
										$complete_per_total = 0;
										$complete_landfill_implemented_total = 0;
										$complete_landfill_implement_total = 0;
										$complete_landfill_rejected_total = 0;
										$all_row = 0;
										$all_row_cnt = 0;

										if (isset($_REQUEST['tblname']) && $_REQUEST['tblname'] == "completed") {
											if (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "1") {
												if ($_REQUEST['sort_order'] == "ASC") {
													$orderby = "ORDER BY initiative_step ASC";
												} else {
													$orderby = "ORDER BY initiative_step DESC";
												}
											} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "2") {
												if ($_REQUEST['sort_order'] == "ASC") {
													$orderby = "ORDER BY initiative_step_status ASC";
												} else {
													$orderby = "ORDER BY initiative_step_status DESC";
												}
											} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "3") {
												if ($_REQUEST['sort_order'] == "ASC") {
													$orderby = "ORDER BY task_sub_id ASC";
												} else {
													$orderby = "ORDER BY task_sub_id DESC";
												}
											} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "4") {
												if ($_REQUEST['sort_order'] == "ASC") {
													$orderby = "ORDER BY task_details ASC";
												} else {
													$orderby = "ORDER BY task_details DESC";
												}
											} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "5") {
												if ($_REQUEST['sort_order'] == "ASC") {
													$orderby = "ORDER BY annual_saving_opportunity ASC";
												} else {
													$orderby = "ORDER BY annual_saving_opportunity DESC";
												}
											} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "6") {
												if ($_REQUEST['sort_order'] == "ASC") {
													$orderby = "ORDER BY per_in_landfill_total ASC";
												} else {
													$orderby = "ORDER BY per_in_landfill_total DESC";
												}
											} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "7") {
												if ($_REQUEST['sort_order'] == "ASC") {
													$orderby = "ORDER BY task_status ASC";
												} else {
													$orderby = "ORDER BY task_status DESC";
												}
											} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "9") {
												if ($_REQUEST['sort_order'] == "ASC") {
													$orderby = "ORDER BY task_owner ASC";
												} else {
													$orderby = "ORDER BY task_owner DESC";
												}
											} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "10") {
												if ($_REQUEST['sort_order'] == "ASC") {
													$orderby = "ORDER BY task_added_on ASC";
												} else {
													$orderby = "ORDER BY task_added_on DESC";
												}
											} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "11") {
												if ($_REQUEST['sort_order'] == "ASC") {
													$orderby = "ORDER BY task_notes ASC";
												} else {
													$orderby = "ORDER BY task_notes DESC";
												}
											} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "12") {
												if ($_REQUEST['sort_order'] == "ASC") {
													$orderby = "ORDER BY task_notes1 ASC";
												} else {
													$orderby = "ORDER BY task_notes1 DESC";
												}
											}
											$orderby = $orderby ?? "";
											$query = "SELECT * FROM water_initiatives where companyid = " . $companyid . " and task_status = 4 $orderby";
											//echo $query . "<br>";
											db();
											$res = db_query($query);
											$total_steps = tep_db_num_rows($res);
											//echo $rw_color."--steps-".$total_steps;
											if (isset($alt) == 1) {
												$bgcolor = "#f2f2f2";
												$table_row_css = "row_style";
											} else {
												$bgcolor = "#ffffff";
												$table_row_css = "row_style_alt";
											}
											$rw_color = 0;

											while ($row = array_shift($res)) {
												$rw_color = $rw_color + 1;
												$date1 = new DateTime($row["due_date"]);
												$date2 = new DateTime();

												$days = (strtotime($row["due_date"]) - strtotime(date("Y-m-d"))) / (60 * 60 * 24);

												$task_status = "";
												$int_task_qry = "select * from water_task_status where id = '" . $row["task_status"] . "'";
												db();
												$int_task_res = db_query($int_task_qry);
												while ($int_task_rows = array_shift($int_task_res)) {
													$task_status = $int_task_rows["task_status"];
												}
										?>
                                <tr>
                                    <td valign="top" class="<?php echo $table_row_css; ?>">
                                        <?php
														$int_step_qry = "select * from water_initiative_steps where unique_key='" . $row["initiative_step"] . "'";
														db();
														$int_step_res = db_query($int_step_qry);
														$int_step_rows = array_shift($int_step_res);
														echo $int_step_rows["initiative_step"];
														?>
                                    </td>
                                    <td valign="top" class="<?php echo $table_row_css; ?>">
                                        <?php echo $row["initiative_step_status"]; ?>
                                    </td>
                                    <!-- <td rowspan="<?php echo  $total_steps ?>" valign="top" class="<?php echo $table_row_css; ?>">
									<?php if ($row["step_due_date"] != "" && $row["step_due_date"] != "0000-00-00") {
													echo $row["step_due_date"];
												} ?>
								</td> -->

                                    <td class="<?php echo $table_row_css; ?>" align="left">
                                        <table>
                                            <tr>
                                                <td valign="top" style="width: 15px;">
                                                    <?php echo  $row["task_sub_id"] . ". " ?></td>
                                                <td align="left"><?php echo $row["task_title"]; ?></td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="<?php echo $table_row_css; ?>">
                                        <?php echo $row["task_details"]; ?>
                                    </td>

                                    <td class="<?php echo  $table_row_css; ?>">
                                        <?php
														$all_row_cnt++;
														echo "<table><tr><td class='display_table'>Annual Savings Opportunity (Gross Value):</td><td class='display_table' align='right'>$" . number_format($row["annual_saving_opportunity"], 0) . "</td></tr>";

														echo "<tr><td style='text-align: center;font-size: 12px;'><a style='color: blue;' href='javascript:void(0)' onclick='collapse_div(this)'>Show</a>
								</td></tr>";

														echo "<tr style='display: none';><td class='display_table'>Annual Savings Implemented:</td><td class='display_table'  align='right'>$" . number_format($row["annual_saving_implemented"], 0) . "</td></tr>";
														echo "<tr style='display: none';><td class='display_table'>Annual Savings to Implement:</td><td class='display_table'  align='right'>$" . number_format($row["annual_saving_implement"], 0) . "</td></tr>";
														echo "<tr style='display: none';><td class='display_table'>Annual Savings Rejected:</td><td class='display_table' align='right'>$" . number_format($row["annual_saving_rejected"], 0) . "</td></tr></table>";

														$complete_opportunity_total = $complete_opportunity_total + str_replace(",", "", number_format($row["annual_saving_opportunity"], 0));
														$complete_implemented_total = $complete_implemented_total + str_replace(",", "", number_format($row["annual_saving_implemented"], 0));
														$complete_implement_total = $complete_implement_total + str_replace(",", "", number_format($row["annual_saving_implement"], 0));
														$complete_rejected_total = $complete_rejected_total + str_replace(",", "", number_format($row["annual_saving_rejected"], 0));

														?>
                                    </td>
                                    <td class="<?php echo  $table_row_css; ?>">
                                        <?php

														echo "<table>";
														echo "<tr><td class='display_table'>Potential % Increase in Total Landfill Diversion:</td><td class='display_table'  align='right'>" . number_format($row["per_in_landfill_total"], 0) . "%</td></tr>";

														echo "<tr><td style='text-align: center;font-size: 12px;'><a style='color: blue;' href='javascript:void(0)' onclick='collapse_div(this)'>Show</a>
							</td></tr>";

														echo "<tr style='display: none;'><td class='display_table'>Landfill Diversion Implemented:</td><td class='display_table'  align='right'>" . number_format($row["landfill_diversion_implemented"], 0) . "%</td></tr>";
														echo "<tr style='display: none;'><td class='display_table'>Landfill Diversion to Implement:</td><td class='display_table'  align='right'>" . number_format($row["landfill_diversion_implement"], 0) . "%</td></tr>";
														echo "<tr style='display: none;'><td class='display_table'>Landfill Diversion Rejected:</td><td class='display_table'  align='right'>" . number_format($row["landfill_diversion_rejected"], 0) . "%</td></tr></table>";

														$complete_per_total = $complete_per_total + str_replace(",", "", number_format($row["per_in_landfill_total"], 0));
														$complete_landfill_implemented_total = $complete_landfill_implemented_total + str_replace(",", "", number_format($row["landfill_diversion_implemented"], 0));
														$complete_landfill_implement_total = $complete_landfill_implement_total + str_replace(",", "", number_format($row["landfill_diversion_implement"], 0));
														$complete_landfill_rejected_total = $complete_landfill_rejected_total + str_replace(",", "", number_format($row["landfill_diversion_rejected"], 0));

														?>
                                    </td>

                                    <td class="<?php echo $table_row_css; ?>">
                                        <?php echo $task_status; ?>
                                    </td>

                                    <!-- <td class="<?php echo $table_row_css; ?>" ><?php echo date("m/d/Y", strtotime($row["due_date"])) . " CT"; ?></td> -->

                                    <td class="text-align1 <?php echo $table_row_css; ?>">
                                        <?php echo  $row["task_owner"] ?></td>

                                    <td class="text-align1 <?php echo $table_row_css; ?>">
                                        <?php echo  date("m/d/Y", strtotime($row["task_added_on"])) ?></td>
                                    <!-- <td class="<?php echo $table_row_css; ?>" align="left"><?php echo  $row["task_notes"] ?></td> -->
                                    <td class="<?php echo $table_row_css; ?>" align="left">
                                        <textarea name="txtUcbzwNotes_<?php echo  $row["taskid"] ?>"
                                            id="txtUcbzwNotes_<?php echo  $row["taskid"] ?>" cols="8"
                                            class="form_component"><?php echo $row["task_notes"] ?></textarea>
                                    </td>
                                    <td class="<?php echo $table_row_css; ?>" align="left">
                                        <textarea name="txtUcbzwNotes1_<?php echo  $row["taskid"] ?>"
                                            id="txtUcbzwNotes1_<?php echo  $row["taskid"] ?>" cols="8"
                                            class="form_component"><?php echo $row["task_notes1"] ?></textarea>
                                    </td>
                                    <td class="<?php echo $table_row_css; ?>" align="left">
                                        <?php
														if (empty($row["task_notes"]) && empty($row["task_notes1"])) {
															$btnText = 'Save Notes';
														} else {
															$btnText = 'Save Notes';
														}
														?>
                                        <input type="button" name="btnNotes" value="<?php echo  $btnText ?>"
                                            onclick="saveNotes(<?php echo  $row["taskid"]; ?>, <?php echo $companyid; ?>,'<?php echo  $row["task_sub_id"] . ". " . $row["task_title"]; ?>')">
                                    </td>

                                </tr>
                                <?php
											}

											if ($rw_color == $total_steps) {
												if (isset($alt) == 0) {
													$alt = 1;
												} else {
													$alt = 0;
												}
											}
										} else {
											$orderby = "ORDER BY initiative_step ASC, taskid ASC";

											$sql = "SELECT *, sum(annual_saving_opportunity) AS aso, sum(per_in_landfill_total) as pilt FROM water_initiatives where companyid = " . $companyid . " and task_status = 4 group by initiative_step $orderby";
											//echo $sql . "<br>";
											db();
											$result = db_query($sql);
											if (tep_db_num_rows($result) > 0) {
												$cnt = 0;
												$alt = 0;
												while ($step_rows = array_shift($result)) {
													if (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "1") {
														if ($_REQUEST['sort_order'] == "ASC") {
															$orderby2 = "ORDER BY initiative_step ASC";
														} else {
															$orderby2 = "ORDER BY initiative_step DESC";
														}
													} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "2") {
														if ($_REQUEST['sort_order'] == "ASC") {
															$orderby2 = "ORDER BY initiative_step_status ASC";
														} else {
															$orderby2 = "ORDER BY initiative_step_status DESC";
														}
													} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "3") {
														if ($_REQUEST['sort_order'] == "ASC") {
															$orderby2 = "ORDER BY task_sub_id ASC";
														} else {
															$orderby2 = "ORDER BY task_sub_id DESC";
														}
													} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "4") {
														if ($_REQUEST['sort_order'] == "ASC") {
															$orderby2 = "ORDER BY task_details ASC";
														} else {
															$orderby2 = "ORDER BY task_details DESC";
														}
													} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "5") {
														if ($_REQUEST['sort_order'] == "ASC") {
															$orderby2 = "ORDER BY annual_saving_opportunity ASC";
														} else {
															$orderby2 = "ORDER BY annual_saving_opportunity DESC";
														}
													} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "6") {
														if ($_REQUEST['sort_order'] == "ASC") {
															$orderby2 = "ORDER BY per_in_landfill_total ASC";
														} else {
															$orderby2 = "ORDER BY per_in_landfill_total DESC";
														}
													} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "7") {
														if ($_REQUEST['sort_order'] == "ASC") {
															$orderby2 = "ORDER BY task_status ASC";
														} else {
															$orderby2 = "ORDER BY task_status DESC";
														}
													} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "9") {
														if ($_REQUEST['sort_order'] == "ASC") {
															$orderby2 = "ORDER BY task_owner ASC";
														} else {
															$orderby2 = "ORDER BY task_owner DESC";
														}
													} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "10") {
														if ($_REQUEST['sort_order'] == "ASC") {
															$orderby2 = "ORDER BY task_added_on ASC";
														} else {
															$orderby2 = "ORDER BY task_added_on DESC";
														}
													} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "11") {
														if ($_REQUEST['sort_order'] == "ASC") {
															$orderby2 = "ORDER BY task_notes ASC";
														} else {
															$orderby2 = "ORDER BY task_notes DESC";
														}
													} elseif (isset($_REQUEST['sort']) && $_REQUEST['sort'] == "12") {
														if ($_REQUEST['sort_order'] == "ASC") {
															$orderby2 = "ORDER BY task_notes1 ASC";
														} else {
															$orderby2 = "ORDER BY task_notes1 DESC";
														}
													} else {
														$orderby2 = "ORDER BY task_sub_id ASC, due_date DESC";
													}
													$query = "SELECT * FROM water_initiatives where companyid = " . $companyid . " and task_status = 4 and initiative_step= '" . $step_rows["initiative_step"] . "' order by task_sub_id ASC, completed_date desc";
													//echo $query . "<br>";
													$res = db_query($query);
													$total_steps = tep_db_num_rows($res);
													//echo $rw_color."--steps-".$total_steps;
													if ($alt == 1) {
														$bgcolor = "#f2f2f2";
														$table_row_css = "row_style";
													} else {
														$bgcolor = "#ffffff";
														$table_row_css = "row_style_alt";
													}
												?>
                                <tr>
                                    <td rowspan="<?php echo  $total_steps ?>" valign="top"
                                        class="<?php echo $table_row_css; ?>">
                                        <?php
															$int_step_qry = "select * from water_initiative_steps where unique_key='" . $step_rows["initiative_step"] . "'";
															db();
															$int_step_res = db_query($int_step_qry);
															$int_step_rows = array_shift($int_step_res);
															echo $int_step_rows["initiative_step"];
															?>
                                    </td>
                                    <td rowspan="<?php echo  $total_steps ?>" valign="top"
                                        class="<?php echo $table_row_css; ?>">
                                        <?php echo $step_rows["initiative_step_status"]; ?>
                                    </td>
                                    <!-- <td rowspan="<?php echo  $total_steps ?>" valign="top" class="<?php echo $table_row_css; ?>">
												<?php if ($step_rows["step_due_date"] != "" && $step_rows["step_due_date"] != "0000-00-00") {
														echo $step_rows["step_due_date"];
													} ?>
											</td> -->
                                    <?php
														$rw_color = 0;

														while ($row = array_shift($res)) {
															$rw_color = $rw_color + 1;
															$date1 = new DateTime($row["due_date"]);
															$date2 = new DateTime();

															$days = (strtotime($row["due_date"]) - strtotime(date("Y-m-d"))) / (60 * 60 * 24);

															$task_status = "";
															$int_task_qry = "select * from water_task_status where id = '" . $row["task_status"] . "'";
															db();
															$int_task_res = db_query($int_task_qry);
															while ($int_task_rows = array_shift($int_task_res)) {
																$task_status = $int_task_rows["task_status"];
															}
														?>
                                    <td class="<?php echo $table_row_css; ?>" align="left">
                                        <table>
                                            <tr>
                                                <td valign="top" style="width: 15px;">
                                                    <?php echo  $row["task_sub_id"] . ". " ?></td>
                                                <td align="left"><?php echo $row["task_title"]; ?></td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="<?php echo $table_row_css; ?>">
                                        <?php echo $row["task_details"]; ?>
                                    </td>

                                    <td class="<?php echo  $table_row_css; ?>">
                                        <?php
																$all_row_cnt++;
																echo "<table><tr><td class='display_table'>Annual Savings Opportunity (Gross Value):</td><td class='display_table' align='right'>$" . number_format($row["annual_saving_opportunity"], 0) . "</td></tr>";

																echo "<tr><td style='text-align: center;font-size: 12px;'><a style='color: blue;' href='javascript:void(0)' onclick='collapse_div(this)'>Show</a>
								</td></tr>";

																echo "<tr style='display: none';><td class='display_table'>Annual Savings Implemented:</td><td class='display_table'  align='right'>$" . number_format($row["annual_saving_implemented"], 0) . "</td></tr>";
																echo "<tr style='display: none';><td class='display_table'>Annual Savings to Implement:</td><td class='display_table'  align='right'>$" . number_format($row["annual_saving_implement"], 0) . "</td></tr>";
																echo "<tr style='display: none';><td class='display_table'>Annual Savings Rejected:</td><td class='display_table' align='right'>$" . number_format($row["annual_saving_rejected"], 0) . "</td></tr></table>";

																$complete_opportunity_total = $complete_opportunity_total + str_replace(",", "", number_format($row["annual_saving_opportunity"], 0));
																$complete_implemented_total = $complete_implemented_total + str_replace(",", "", number_format($row["annual_saving_implemented"], 0));
																$complete_implement_total = $complete_implement_total + str_replace(",", "", number_format($row["annual_saving_implement"], 0));
																$complete_rejected_total = $complete_rejected_total + str_replace(",", "", number_format($row["annual_saving_rejected"], 0));

																?>
                                    </td>
                                    <td class="<?php echo  $table_row_css; ?>">
                                        <?php

																echo "<table>";
																echo "<tr><td class='display_table'>Potential % Increase in Total Landfill Diversion:</td><td class='display_table'  align='right'>" . number_format($row["per_in_landfill_total"], 0) . "%</td></tr>";

																echo "<tr><td style='text-align: center;font-size: 12px;'><a style='color: blue;' href='javascript:void(0)' onclick='collapse_div(this)'>Show</a>
							</td></tr>";

																echo "<tr style='display: none;'><td class='display_table'>Landfill Diversion Implemented:</td><td class='display_table'  align='right'>" . number_format($row["landfill_diversion_implemented"], 0) . "%</td></tr>";
																echo "<tr style='display: none;'><td class='display_table'>Landfill Diversion to Implement:</td><td class='display_table'  align='right'>" . number_format($row["landfill_diversion_implement"], 0) . "%</td></tr>";
																echo "<tr style='display: none;'><td class='display_table'>Landfill Diversion Rejected:</td><td class='display_table'  align='right'>" . number_format($row["landfill_diversion_rejected"], 0) . "%</td></tr></table>";

																$complete_per_total = $complete_per_total + str_replace(",", "", number_format($row["per_in_landfill_total"], 0));
																$complete_landfill_implemented_total = $complete_landfill_implemented_total + str_replace(",", "", number_format($row["landfill_diversion_implemented"], 0));
																$complete_landfill_implement_total = $complete_landfill_implement_total + str_replace(",", "", number_format($row["landfill_diversion_implement"], 0));
																$complete_landfill_rejected_total = $complete_landfill_rejected_total + str_replace(",", "", number_format($row["landfill_diversion_rejected"], 0));

																?>
                                    </td>

                                    <td class="<?php echo $table_row_css; ?>">
                                        <?php echo $task_status; ?>
                                    </td>

                                    <!-- <td class="<?php echo $table_row_css; ?>" ><?php echo date("m/d/Y", strtotime($row["due_date"])) . " CT"; ?></td> -->

                                    <td class="text-align1 <?php echo $table_row_css; ?>">
                                        <?php echo  $row["task_owner"] ?></td>

                                    <td class="text-align1 <?php echo $table_row_css; ?>">
                                        <?php echo  date("m/d/Y", strtotime($row["task_added_on"])) ?></td>
                                    <!-- <td class="<?php echo $table_row_css; ?>" align="left"><?php echo  $row["task_notes"] ?></td> -->
                                    <td class="<?php echo $table_row_css; ?>" align="left">
                                        <textarea name="txtUcbzwNotes_<?php echo  $row["taskid"] ?>"
                                            id="txtUcbzwNotes_<?php echo  $row["taskid"] ?>" cols="8"
                                            class="form_component"><?php echo $row["task_notes"] ?></textarea>
                                    </td>
                                    <td class="<?php echo $table_row_css; ?>" align="left">
                                        <textarea name="txtUcbzwNotes1_<?php echo  $row["taskid"] ?>"
                                            id="txtUcbzwNotes1_<?php echo  $row["taskid"] ?>" cols="8"
                                            class="form_component"><?php echo $row["task_notes1"] ?></textarea>
                                    </td>
                                    <td class="<?php echo $table_row_css; ?>" align="left">
                                        <?php
																if (empty($row["task_notes"]) && empty($row["task_notes1"])) {
																	$btnText = 'Save Notes';
																} else {
																	$btnText = 'Save Notes';
																}
																?>
                                        <input type="button" name="btnNotes" value="<?php echo  $btnText ?>"
                                            onclick="saveNotes(<?php echo  $row["taskid"]; ?>, <?php echo $companyid; ?>,'<?php echo  $row["task_sub_id"] . ". " . $row["task_title"]; ?>')">
                                    </td>

                                </tr>
                                <?php
														}
														if ($rw_color == $total_steps) {
															if ($alt == 0) {
																$alt = 1;
															} else {
																$alt = 0;
															}
														}
													}
												}
											}
									?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="<?php echo isset($table_row_css); ?>" colspan="4"
                                        style="text-align:right; font-weight:bold">Total:&nbsp;</td>
                                    <td class="<?php echo isset($table_row_css); ?>">
                                        <table>
                                            <tr>
                                                <td class='display_table'>TOTAL SUM for Annual Savings Opportunity:</td>
                                                <td class='display_table' align='right'>
                                                    <b>$<?php echo number_format($complete_opportunity_total, 2); ?></b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class='display_table'>TOTAL SUM for Annual Savings Implemented:</td>
                                                <td class='display_table' align='right'>
                                                    <b>$<?php echo number_format($complete_implemented_total, 2); ?></b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class='display_table'>TOTAL SUM for Annual Savings to Implement:
                                                </td>
                                                <td class='display_table' align='right'>
                                                    <b>$<?php echo number_format($complete_implement_total, 2); ?></b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class='display_table'>TOTAL SUM for Annual Savings Rejected:</td>
                                                <td class='display_table' align='right'>
                                                    <b>$<?php echo number_format($complete_rejected_total, 2); ?></b>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="<?php echo isset($table_row_css); ?>" align="right">
                                        <table>
                                            <tr>
                                                <td class='display_table'>TOTAL SUM for Potential % Increase in Total
                                                    Landfill Diversion:</td>
                                                <td class='display_table' align='right'><b>
                                                        <?php
																if ($all_row_cnt > 0) {

																	echo number_format($complete_per_total / $all_row_cnt, 2);
																} else {
																	echo "0";
																}

																?>
                                                        %</b></td>
                                            </tr>
                                            <tr>
                                                <td class='display_table'>TOTAL SUM for Landfill Diversion Implemented:
                                                </td>
                                                <td class='display_table' align='right'>
                                                    <b><?php echo number_format($complete_landfill_implemented_total, 2); ?>%</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class='display_table'>TOTAL SUM for Landfill Diversion to Implement:
                                                </td>
                                                <td class='display_table' align='right'>
                                                    <b><?php echo number_format($complete_landfill_implement_total, 2); ?>%</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class='display_table'>TOTAL SUM for Landfill Diversion Rejected:
                                                </td>
                                                <td class='display_table' align='right'>
                                                    <b><?php echo number_format($complete_landfill_rejected_total, 2); ?>%</b>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="<?php echo isset($table_row_css); ?>" colspan="6">&nbsp;</td>
                                </tr>

                                <!-- Grand Total -->

                                <tr>
                                    <td class="<?php echo isset($table_row_css); ?>" colspan="4"
                                        style="text-align:right; font-weight:bold">Grand Total:&nbsp;</td>
                                    <td class="<?php echo isset($table_row_css); ?>">
                                        <table>
                                            <tr>
                                                <td class='display_table'>TOTAL SUM for Annual Savings Opportunity:</td>
                                                <td class='display_table' align='right'>
                                                    <b>$<?php echo number_format($annual_saving_opportunity_total + $complete_opportunity_total, 2); ?></b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class='display_table'>TOTAL SUM for Annual Savings Implemented:</td>
                                                <td class='display_table' align='right'>
                                                    <b>$<?php echo number_format($annual_saving_implemented_total + $complete_implemented_total, 2); ?></b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class='display_table'>TOTAL SUM for Annual Savings to Implement:
                                                </td>
                                                <td class='display_table' align='right'>
                                                    <b>$<?php echo number_format($annual_saving_implement_total + $complete_implement_total, 2); ?></b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class='display_table'>TOTAL SUM for Annual Savings Rejected:</td>
                                                <td class='display_table' align='right'>
                                                    <b>$<?php echo number_format($annual_saving_rejected_total + $complete_rejected_total, 2); ?></b>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="<?php echo isset($table_row_css); ?>" align="right">
                                        <table>
                                            <tr>
                                                <td class='display_table'>TOTAL SUM for Potential % Increase in Total
                                                    Landfill Diversion:</td>
                                                <td class='display_table' align='right'><b>
                                                        <?php
																$total_potential_per = 0;
																if (isset($all_row_cnt_incomplete) > 0) {
																	$total_potential_per = number_format($per_landfill_total / $all_row_cnt_incomplete, 2);
																}

																if ($all_row_cnt > 0) {

																	echo number_format(($complete_per_total / $all_row_cnt) + $total_potential_per, 2);
																} else {
																	echo $total_potential_per;
																}

																?>
                                                        %</b></td>
                                            </tr>
                                            <tr>
                                                <td class='display_table'>TOTAL SUM for Landfill Diversion Implemented:
                                                </td>
                                                <td class='display_table' align='right'>
                                                    <b><?php echo number_format($landfill_implemented_total + $complete_landfill_implemented_total, 2); ?>%</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class='display_table'>TOTAL SUM for Landfill Diversion to Implement:
                                                </td>
                                                <td class='display_table' align='right'>
                                                    <b><?php echo number_format($landfill_implement_total + $complete_landfill_implement_total, 2); ?>%</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class='display_table'>TOTAL SUM for Landfill Diversion Rejected:
                                                </td>
                                                <td class='display_table' align='right'>
                                                    <b><?php echo number_format($landfill_rejected_total + $complete_landfill_rejected_total, 2); ?>%</b>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="<?php echo isset($table_row_css); ?>" colspan="6">&nbsp;</td>
                                </tr>

                            </tfoot>
                        </table>
                        <?php
							}
							?>
                    </div>
                </div>
            </div>
            <div class="footer1">
                <?php require("mainfunctions/footerContent.php");	?>
            </div>

        </div>
    </div>


    <!-- JavaScript Libraries -->
    <script src="lib/jquery/jquery.min.js"></script>
    <script src="lib/superfish/superfish.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <!-- Contact Form JavaScript File -->

    <!-- Template Main Javascript File -->

    <script src="js/main.js"></script>
    <script src="menu/slicknav/jquery.slicknav.min.js"></script>

    <script>
    $(function() {
        $('.menu-navigation-icons').slicknav();
    });
    </script>
    <script>
    function collapse_div(e) {
        if (e.innerHTML !== 'Show') {
            e.innerHTML = "Show";
            e.closest('tbody').querySelector('tr:nth-last-child(1)').style = 'display:none';
            e.closest('tbody').querySelector('tr:nth-last-child(2)').style = 'display:none';
            e.closest('tbody').querySelector('tr:nth-last-child(3)').style = 'display:none';
        } else {
            e.innerHTML = "Hide";
            e.closest('tbody').querySelector('tr:nth-last-child(1)').style = 'display:table-row';
            e.closest('tbody').querySelector('tr:nth-last-child(2)').style = 'display:table-row';
            e.closest('tbody').querySelector('tr:nth-last-child(3)').style = 'display:table-row';
        }

        console.log(e.closest('tbody').querySelector('tr:nth-last-child(1)'));
    }
    </script>

</body>

</html>
<?php

} else {
	echo "<script type=\"text/javascript\">";
	echo "window.location.href=\"index.php" . "\";";
	echo "</script>";
	echo "<noscript>";
	echo "<meta http-equiv=\"refresh\" content=\"0;url=index.php" . "\" />";
	echo "</noscript>";
	exit;
}
?>