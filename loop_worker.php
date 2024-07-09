<?php

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

if (isset($_REQUEST["warehouse_id"])) {
    $warehouse_id = $_REQUEST["warehouse_id"];
} else {
    $warehouse_id = 0;
}
?>
<!DOCTYPE HTML>

<html>

<head>
    <title>UCB Loop System - Employee Setup</title>

    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap"
        rel="stylesheet">

    <SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
    <SCRIPT LANGUAGE="JavaScript" SRC="inc/general.js"></SCRIPT>
    <script LANGUAGE="JavaScript">
    document.write(getCalendarStyles());
    </script>
    <script LANGUAGE="JavaScript">
    var cal1xx = new CalendarPopup("listdiv");
    cal1xx.showNavigationDropdowns();
    var cal2xx = new CalendarPopup("listdiv");
    cal2xx.showNavigationDropdowns();
    var cal3xx = new CalendarPopup("listdiv");
    cal3xx.showNavigationDropdowns();
    var cal4xx = new CalendarPopup("listdiv");
    cal4xx.showNavigationDropdowns();
    </script>

    <script type="text/javascript">
    function numbersonly(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        } else {
            // If the number field already has . then don't allow to enter . again.
            if (evt.target.value.search(/\./) > -1 && charCode == 46) {
                return false;
            }
            return true;
        }
    }

    /*
    function onclientchg_grpchg(groupid) {
    	document.getElementById("clientlist_add").value = groupid;
    	document.addnewfrm.submit(); 
    }*/


    function onclientchg_grpchg() {

        var skillsSelect = document.getElementById("worker_warehouse_name");
        var selectedText = skillsSelect.options[skillsSelect.selectedIndex].value;
        //alert(selectedText);
        document.getElementById("clientlist_add").value = selectedText;
        // frmloop.action = "loop_worker.php?posting=yes&warehouse_id="+selectedText+"&page="+page+"&reccount="+reccount+"&searchcrit="+searchcrit;
        frmloop.action = "loop_worker.php?posting=yes&warehouse_id=" + selectedText;
        //document.location="index.php?employeegrpid="+document.getElementById("empid").value+"&selectedgrpid_inedit="+selectedText+"&sort_order="+order_type+"&sorting=yes&page="+document.getElementById("pageno").value;	

        frmloop.submit();
    }
    </script>
    <style>
    .BUTTON_save {
        BORDER-RIGHT: gray 1px solid;
        BORDER-TOP: gray 1px solid;
        BORDER-LEFT: gray 1px solid;
        CURSOR: hand;
        BORDER-BOTTOM: gray 1px solid;
        width: 100px;
    }

    .main_data_css {
        margin: 0 auto;
        width: 100%;
        height: auto;
        clear: both !important;
        padding-top: 35px;
        margin-left: 10px;
        margin-right: 10px;
    }
    </style>
    <link rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>
</head>

<body>
    <?php include("inc/header.php"); ?>

    <div class="main_data_css">
        <div class="dashboard_heading" style="float: left;">
            <div style="float: left;">
                Employee Timeclock Database (Loops)
                <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                    <span class="tooltiptext">
                        This database is where all of the data for timeclock employees are saved. This is not to be
                        confused with the office employee database table. If an employee needs a loops login as well as
                        a timeclock, then they will need to be setup in both databases.
                    </span>
                </div>
                <div style="height: 13px;">&nbsp;</div>
            </div>
        </div>

        <?php
        echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >";
        /*------------------------------------------------
THE FOLLOWING ALLOWS GLOBALS = OFF SUPPORT
------------------------------------------------*/
        // $_GET VARIABLES
        foreach ($_GET as $a => $b) {
            $$a = $b;
        }

        // $_POST VARIABLES
        foreach ($_POST as $a => $b) {
            $$a = $b;
        }
        echo "<Font Face='arial' size='2'>";

        error_reporting(E_ERROR | E_PARSE);

        $thispage    = isset($SCRIPT_NAME); //SET THIS TO THE NAME OF THIS FILE
        $pagevars    = ""; //INSERT ANY "GET" VARIABLES HERE...

        $allowedit        = "yes"; //SET TO "no" IF YOU WANT TO DISABLE EDITING
        $allowaddnew    = "yes"; // SET TO "no" IF YOU WANT TO DISABLE NEW RECORDS
        $allowview        = "no"; //SET TO "no" IF YOU WANT TO DISABLE VIEWING RECORDS
        $allowdelete    = "yes"; //SET TO "no" IF YOU WANT TO DISABLE DELETING RECORDS

        $addl_select_crit = ""; //ADDL CRITERIA FOR SQL STATEMENTS (ADD/UPD/DEL).
        $addl_update_crit = ""; //ADDITIONAL CRITERIA FOR UPDATE STATEMENTS.
        $addl_insert_crit = ""; //ADDITIONAL CRITERIA FOR INSERT STATEMENTS.
        $addl_insert_values = ""; //ADDITIONAL VALUES FOR INSERT STATEMENTS.

        if (get_magic_quotes_gpc()) {
            $addslash = "no";
        }

        //require ("inc/database.php");

        $fileuploaded_flg = "no";
        $sql = "SELECT * FROM tblvariable where variablename = 'upload_file_type_emp'";
        $filetype = "pdf,mp3,jpg,jpeg,tif,tiff,png,gif";
        db();
        $result = db_query($sql);
        while ($myrowsel = array_shift($result)) {
            $filetype = $myrowsel["variablevalue"];
        }
        $allow_ext = explode(",", $filetype);

        // function FixString($strtofix)
        // { //THIS FUNCTION ESCAPES SPECIAL CHARACTERS FOR INSERTING INTO SQL
        // 	if (get_magic_quotes_gpc()) { $addslash="no"; } else { $addslash="yes"; }
        // 	if ($addslash == "yes") {  $strtofix = addslashes($strtofix); }
        // 	$strtofix = preg_replace(  "/</", "&#60;", $strtofix );
        // 	$strtofix = preg_replace(  "/'/", "&#39;", $strtofix );
        // 	$strtofix = preg_replace(  "(\n)", "<BR>", $strtofix );
        // 	return $strtofix;
        // }//end FixString

        // function ThrowError($err_type,$err_descr)
        // { //THIS FUNCTION PROVIDES ERROR DESCRIPTIONS
        // 	$err_text = "";
        // 	switch ($err_type)
        // 	{
        // 		case "9991SQLresultcount":
        // 			return "<BR><B>ERROR:  --> " . mysqli_error() ."</b>
        // 					<BR>LOCATION: $err_type 
        // 					<BR>DESCRIPTION: Unable to execute SQL statement.
        // 					<BR>Please Check the Following:
        // 					<BR>- Ensure the Table(s) Exists in database $dbname
        // 					<BR>- Ensure All Fields Exist in the Table(s)

        // 					<BR>- SQL STATEMENT: $err_descr<BR>
        // 					<BR>- MYSQL ERROR: " . mysqli_error();
        // 			break;
        // 		case "9994SQL":
        // 			return "<BR><B>ERROR:  --> " . mysqli_error() ."</b>
        // 					<BR>LOCATION: $err_type 
        // 					<BR>DESCRIPTION: Unable to execute SQL statement.
        // 					<BR>Please Check the Following:
        // 					<BR>- Ensure the Table(s) Exists in database $dbname
        // 					<BR>- Ensure All Fields Exist in the Table(s)

        // 					<BR>- SQL STATEMENT: $err_descr<BR>
        // 					<BR>- MYSQL ERROR: " . mysqli_error();
        // 			break;
        // 		default:
        // 			return "UNKNOWN ERROR TYPE: $err_type 
        // 					<BR>DESCR: $err_descr<BR>";
        // 			break;
        // 	} //end switch

        // 	return $err_text;
        // } //end ThrowError

        if (isset($proc) == "") {

            if ($allowaddnew == "yes") {
        ?>
        <a href="<?php echo $thispage; ?>?proc=New&warehouse_id=<?php echo $warehouse_id; ?>&<?php echo $pagevars; ?>">Add
            a New Employee in Loop Worker</a><br><br>
        <?php }
            $toggle_status = "1";

            if ($_GET['toggle_status'] == "1") {
                $toggle_status = "0";
            } else {
                $toggle_status = "1";
            } ?>

        <a
            href='loop_worker.php?posting=yes&warehouse_id=<?php echo $warehouse_id; ?>&toggle_status=<?php echo $toggle_status; ?>'>Active/Inactive</a>
        <br><br>

        <!--	<form method="POST" action="<?php echo $thispage; ?>?posting=yes&<?php echo $pagevars; ?>">
  	<p><B>Search:</B> <input type="text" CLASS='TXT_BOX' name="searchcrit" size="20" value="<?php echo isset($searchcrit); ?>">
  	<INPUT CLASS="BUTTON" TYPE="SUBMIT" VALUE="Search!" NAME="B1"></P>
	</form>  -->

        <?php

            if (isset($posting) == "yes") {

                $pagenorecords = 50;  //THIS IS THE PAGE SIZE
                //IF NO PAGE
                /*if ($page == 0) {
	$myrecstart = 1;
	} else {
	$myrecstart = ($page * $pagenorecords);
	}
	$limit = 5; */                                //how many items to show per page

                $page = ($_GET['page']);
                if ($page)
                    $myrecstart = ($page - 1) * $pagenorecords;             //first item to display on this page
                else
                    $myrecstart = 0;

            ?>
        <form method='POST' name="frmloop" id="frmloop">
            <b>Warehouse name: </b>
            <select name='worker_warehouse_name' id='worker_warehouse_name'>
                <?php
                        $query = "SELECT loop_warehouse.id, loop_warehouse.b2bid, loop_warehouse.company_name FROM loop_warehouse inner join loop_workers on loop_workers.warehouse_id = loop_warehouse.id where loop_warehouse.company_name != '' 
group by loop_workers.warehouse_id order by loop_warehouse.company_name ";
                        db();
                        $result = db_query($query);

                        echo "<option value='0'>Please Select</option>";
                        db();
                        $result = db_query($query);
                        while ($myrowsel = array_shift($result)) {
                            $nickname = get_nickname_val($myrowsel["company_name"], $myrowsel["b2bid"]);

                            echo "<option value=" . $myrowsel["id"] . " ";
                            if ($warehouse_id) {
                                if ($myrowsel["id"] == $warehouse_id) echo " selected ";
                            }
                            echo " >";
                            echo  $nickname;
                            echo "</option>";
                            //$myrowsel['company_name']

                            /*if ($myrowsel["id"] == 556){
		echo  "UCB - HA - Production";
		echo "</option>";

		echo "<option value='HAOffice'";
		if (($warehouse_id == 'HAOffice') ) echo " selected ";
		echo ">UCB - HA - Office</option>";
	}else if ($myrowsel["id"] == 18){
		echo  "UCB - HV - Production";
		echo "</option>";

		echo "<option value='HVOffice'"; 
		if (($warehouse_id == 'HVOffice') ) echo " selected ";
		echo ">UCB - HV - Office</option>";
	}else if ($myrowsel["id"] == 2563){
		echo  "UCB - ML - Production";
		echo "</option>";
		
		echo "<option value='MLOffice'";
		if (($warehouse_id == 'MLOffice') ) echo " selected ";
		echo ">UCB - ML - Office</option>";
	}else{	
		echo  $myrowsel['company_name'];
		echo "</option>";
	}	
	*/
                        }
                        ?>
            </select>
            <input type='button' name='btn_show' value='Display' onclick="onclientchg_grpchg()">


            <input type='hidden' name='clientlist_add' id='clientlist_add'>
            <?php
                    if ($warehouse_id != "") {

                        $where_str = "";
                        /*if ($warehouse_id == 556){
		$where_str = " and loop_workers.location_address = 'Hannibal Production' ";
	}else if ($warehouse_id == 18){
		$where_str = " and loop_workers.location_address = 'Hunt Valley Production' ";
	}else if ($warehouse_id == 2563){
		$where_str = " and loop_workers.location_address = 'Milwaukee Production' ";
	}else if ($warehouse_id == 'HAOffice'){
		$where_str = " and loop_workers.location_address = 'Hannibal Office' ";
		$warehouse_id = 556;
	}else if ($warehouse_id == 'HVOffice'){
		$where_str = " and loop_workers.location_address = 'Hunt Valley Office' ";
		$warehouse_id = 18;
	}else if ($warehouse_id == 'MLOffice'){
		$where_str = " and loop_workers.location_address = 'Milwaukee Office' ";
		$warehouse_id = 2563;
	}	*/

                        if (isset($_REQUEST["sorting"]) == 'yes') {
                            $sqlcount = "SELECT count(*) as reccount,loop_workers.*,loop_warehouse.company_name FROM loop_workers";
                            $sqlcount .= " left join loop_warehouse on loop_workers.warehouse_id = loop_warehouse.id";
                            $sqlcount .= " WHERE loop_workers.warehouse_id=" . $warehouse_id . $where_str . " and loop_workers.active=" . $toggle_status . " order by " . $_REQUEST["sort"] . " " . $_REQUEST["sort_order_pre"] . "  "; //LIMIT $myrecstart, $pagenorecords

                            $sql = "SELECT loop_workers.id,loop_workers.name,CAST(loop_workers.rate_cost AS Decimal(5,1))as rate_cost,CAST(loop_workers.rate_revenue AS Decimal(5,1))as rate_revenue,loop_workers.active,loop_workers.employee_type,loop_warehouse.company_name FROM loop_workers";
                            $sql .= " left join loop_warehouse on loop_workers.warehouse_id = loop_warehouse.id";
                            $sql .= " WHERE loop_workers.warehouse_id=" . $warehouse_id . $where_str . " and loop_workers.active=" . $toggle_status . " order by " . $_REQUEST["sort"] . " " . $_REQUEST["sort_order_pre"] . " "; //LIMIT $myrecstart, $pagenorecords 


                        } else {
                            $sqlcount = "SELECT count(*) as reccount FROM loop_workers";
                            $sqlcount .= " left join loop_warehouse on loop_workers.warehouse_id = loop_warehouse.id";
                            $sqlcount .= " WHERE loop_workers.warehouse_id=" . $warehouse_id . $where_str . " and loop_workers.active=" . $toggle_status . " "; //LIMIT $myrecstart, $pagenorecords

                            $sql = "SELECT  loop_workers.title, loop_workers.location_address, loop_workers.supervisor_name, loop_workers.Date_of_Initial_Start, loop_workers.id,loop_workers.name,CAST(loop_workers.rate_cost AS Decimal(5,1))as rate_cost,CAST(loop_workers.rate_revenue AS Decimal(5,1))as rate_revenue,loop_workers.active,loop_workers.employee_type,loop_warehouse.company_name FROM loop_workers";
                            $sql .= " left join loop_warehouse on loop_workers.warehouse_id = loop_warehouse.id";
                            $sql .= " WHERE loop_workers.warehouse_id=" . $warehouse_id . $where_str . " and loop_workers.active=" . $toggle_status . " "; //LIMIT $myrecstart, $pagenorecords

                        }
                        //echo $sql;
                        //SET PAGE
                        if ($page == 0)
                            $page = 1;                    //if no page var is given, default to 1.
                        $next = $page + 1;

                        if (isset($reccount) == 0) {
                            //$resultcount = (db_query($sqlcount,db() )) OR DIE (ThrowError($err_type,$err_descr););
                            db();
                            $resultcount = db_query($sqlcount);
                            if ($myrowcount = array_shift($resultcount)) {
                                $reccount = $myrowcount["reccount"];
                            } //IF RECCOUNT = 0
                        } //end if reccount

                        //echo "<!--<DIV CLASS='CURR_PAGE'>Page $page - $reccount Records Found</DIV>-->";

                        if (isset($reccount) > 50) {
                            $ttlpages = (isset($reccount) / 50);

                            //if ($page < $ttlpages) {
                            //echo $ttlpages;

                    ?>

            <?php
                            //} //END IF AT LAST PAGE
                        } //END IF RECCOUNT > 10

                        //if ($page > 1) { 
                        //$newpage = $page - 1;	
                        ?>
            <?php

                        //} //IF NEWPAGE != -1

                        /*----------------------------------------------------------------
END PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORDS
----------------------------------------------------------------*/
                        //EXECUTE OUR SQL STRING FOR THE TABLE RECORDS
                        db();
                        $result = db_query($sql);
                        //if ($sql_debug_mode==1) { echo "<BR>SQL: $sql<BR>"; }
                        if ($myrowsel = array_shift($result)) {
                            $id = $myrowsel["id"];

                            $sort_order_pre = "ASC";
                            if ($_GET['sort_order_pre'] == "ASC") {
                                $sort_order_pre = "DESC";
                            } else {
                                $sort_order_pre = "ASC";
                            }
                            $active_inactive = "";
                            if ($toggle_status == "1") {
                                $active_inactive = "(Active users)";
                            } else {
                                $active_inactive = "(InActive users)";
                            }

                            echo "<br><TABLE WIDTH='900'>";
                            echo "	<tr align='middle'><td colspan='10' class='style24' style='height: 16px'><strong>LOOP WORKER $active_inactive</strong></td></tr>";
                            echo "	<TR>";

                        ?>


            <TD>
                <DIV CLASS='TBL_COL_HDR'><a
                        href='loop_worker.php?posting=yes&warehouse_id=<?php echo $warehouse_id; ?>&sorting=yes&sort=name&sort_order_pre=<?php echo $sort_order_pre; ?>&page=<?php echo $page; ?>&toggle_status=<?php echo $_REQUEST['toggle_status']; ?>'>Employee
                        Name</a></DIV>
            </TD>

            <TD>
                <DIV CLASS='TBL_COL_HDR'><a
                        href='loop_worker.php?posting=yes&warehouse_id=<?php echo $warehouse_id; ?>&sorting=yes&sort=title&sort_order_pre=<?php echo $sort_order_pre; ?>&page=<?php echo $page; ?>&toggle_status=<?php echo $_REQUEST['toggle_status']; ?>'>Title</a>
                </DIV>
            </TD>
            <TD>
                <DIV CLASS='TBL_COL_HDR'><a
                        href='loop_worker.php?posting=yes&warehouse_id=<?php echo $warehouse_id; ?>&sorting=yes&sort=location_address&sort_order_pre=<?php echo $sort_order_pre; ?>&page=<?php echo $page; ?>&toggle_status=<?php echo $_REQUEST['toggle_status']; ?>'>Work
                        Location</a></DIV>
            </TD>
            <TD>
                <DIV CLASS='TBL_COL_HDR'><a
                        href='loop_worker.php?posting=yes&warehouse_id=<?php echo $warehouse_id; ?>&sorting=yes&sort=Reports_To&sort_order_pre=<?php echo $sort_order_pre; ?>&page=<?php echo $page; ?>&toggle_status=<?php echo $_REQUEST['toggle_status']; ?>'>Reports
                        To</a></DIV>
            </TD>
            <TD>
                <DIV CLASS='TBL_COL_HDR'><a
                        href='loop_worker.php?posting=yes&warehouse_id=<?php echo $warehouse_id; ?>&sorting=yes&sort=Date_of_Initial_Start&sort_order_pre=<?php echo $sort_order_pre; ?>&page=<?php echo $page; ?>&toggle_status=<?php echo $_REQUEST['toggle_status']; ?>'>Date
                        of Initials Start</a></DIV>
            </TD>

            <TD>
                <DIV CLASS='TBL_COL_HDR'><a
                        href='loop_worker.php?posting=yes&warehouse_id=<?php echo $warehouse_id; ?>&sorting=yes&sort=rate_cost&sort_order_pre=<?php echo $sort_order_pre; ?>&page=<?php echo $_REQUEST['page']; ?>&toggle_status=<?php echo $_REQUEST['toggle_status']; ?>'>Pay
                        Rate</a></DIV>
            </TD>
            <TD>
                <DIV CLASS='TBL_COL_HDR'><a
                        href='loop_worker.php?posting=yes&warehouse_id=<?php echo $warehouse_id; ?>&sorting=yes&sort=employee_type&sort_order_pre=<?php echo $sort_order_pre; ?>&page=<?php echo $_REQUEST['page']; ?>&toggle_status=<?php echo $_REQUEST['toggle_status']; ?>'>Employee
                        Type</a></DIV>
            </TD>

            <TD>
                <DIV CLASS='TBL_COL_HDR'>Options</DIV>
            </TD>

            <?php echo "\n\n		</TR>";
                            do {
                                //FORMAT THE OUTPUT OF THE SEARCH
                                $id = $myrowsel["id"];
                                $shade = $shade ?? "";
                                //SWITCH ROW COLORS
                                switch ($shade) {
                                    case "TBL_ROW_DATA_LIGHT":
                                        $shade = "TBL_ROW_DATA_DRK";
                                        break;
                                    case "TBL_ROW_DATA_DRK":
                                        $shade = "TBL_ROW_DATA_LIGHT";
                                        break;
                                    default:
                                        $shade = "TBL_ROW_DATA_DRK";
                                        break;
                                } //end switch shade

                                echo "<TR>";


                            ?>


            <?php $name = $myrowsel["name"]; ?>
            <TD CLASS='<?php echo $shade; ?>'>
                <?php echo $name; ?>
            </TD>

            <TD CLASS='<?php echo $shade; ?>'>
                <?php echo $myrowsel["title"]; ?>
            </TD>
            <TD CLASS='<?php echo $shade; ?>'>
                <?php echo $myrowsel["location_address"]; ?>
            </TD>
            <TD CLASS='<?php echo $shade; ?>'>
                <?php
                                    $sup_query = "SELECT id, name FROM loop_workers WHERE is_supervisor='Yes' and id ='" . $myrowsel["supervisor_name"] . "'";
                                    db();
                                    $sup_result = db_query($sup_query);
                                    while ($sup_row = array_shift($sup_result)) {
                                        echo $sup_row["name"];
                                    }

                                    $sup_query = "SELECT id, name FROM loop_employees WHERE is_supervisor='Yes' and id ='" . $myrowsel["supervisor_name"] . "'";
                                    db();
                                    $sup_result = db_query($sup_query);
                                    while ($sup_row = array_shift($sup_result)) {
                                        echo $sup_row["name"];
                                    }
                                    ?>
            </TD>
            <TD CLASS='<?php echo $shade; ?>'>
                <?php echo $myrowsel["Date_of_Initial_Start"]; ?>
            </TD>

            <?php $rate_cost = $myrowsel["rate_cost"]; ?>
            <TD CLASS='<?php echo $shade; ?>'>
                <?php echo (float)$rate_cost; ?>
            </TD>

            <?php $employee_type = $myrowsel["employee_type"]; ?>
            <TD CLASS='<?php echo $shade; ?>'>
                <?php echo $employee_type; ?>
            </TD>

            <TD CLASS='<?php echo $shade; ?>'>
                <DIV CLASS='PAGE_OPTIONS'>
                    <?php if ($allowview == "yes") { ?><a
                        href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&proc=View&<?php echo $pagevars; ?>">View</a>
                    <?php } ?><?php if ($allowedit == "yes") { ?>
                    <a
                        href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&warehouse_id=<?php echo $warehouse_id; ?>&proc=Edit&<?php echo $pagevars; ?>">Edit</a>
                    <?php } ?><?php if ($allowdelete == "yes") { ?>
                    <a
                        href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&warehouse_id=<?php echo $warehouse_id; ?>&proc=Delete&<?php echo $pagevars; ?>">Mark
                        As InActive</a>
                    <?php } ?>
                </DIV>
            </TD>
            </TR>
            <?php
                            } while ($myrowsel = array_shift($result));
                            echo "</TABLE></form>";


                            ?>


            <?php
                            //} //END IF AT LAST PAGE
                            //} //END IF RECCOUNT > 10
                            //PREVIOUS RECORDS LINK

                            //if ($page > 1) { 
                            //$newpage = $page - 1;	
                            ?>

            <?php

                            //} //IF NEWPAGE != -1
                            /*----------------------------------------------------------------
END PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORDS
----------------------------------------------------------------*/
                        } //END PROC == ""
                    } //END IF POSTING = YES
                } //END OF THE $_REQUEST["clientlist_add"]
                /*---------------------------------------------------------------------------------
END SEARCH SECTION 9991
---------------------------------------------------------------------------------*/
            } // END IF PROC = ""

            //VIEW mode
            ?>

            <?php

            if (isset($proc) == "View") {

                echo "<DIV CLASS='PAGE_STATUS'>Edit Employee</DIV>";
                $b2b2_empid = 0;
                $id = $id ?? 0;
                /*-- SECTION: 9993SQLGET --*/
                $sql = "SELECT * FROM loop_workers WHERE (id = $id) $addl_select_crit ";
                //echo $sql;
                if (isset($sql_debug_mode) == 1) {
                    echo "<BR>SQL: $sql<BR>";
                }
                db();
                $result = db_query($sql) or die("Error Retrieving Records (9993SQLGET)");
                if ($myrow = array_shift($result)) {
                    do {

                        /*$username = $myrow["username"];
$username = preg_replace("(\n)", "<BR>", $username);
$password = $myrow["password"];
$password = preg_replace("(\n)", "<BR>", $password);*/
                        $user_pwd = $myrow["user_pwd"];
                        $emp_id = $myrow["emp_id"];
                        $emp_id = preg_replace("(\n)", "<BR>", $emp_id);
                        $name = $myrow["name"];
                        $name = preg_replace("(\n)", "<BR>", $name);
                        $initials = $myrow["initials"];
                        $initials = preg_replace("(\n)", "<BR>", $initials);
                        $email = $myrow["email"];
                        $email = preg_replace("(\n)", "<BR>", $email);
                        $phone = $myrow["phone"];
                        $phone = preg_replace("(\n)", "<BR>", $phone);
                        $phoneext = $myrow["phoneext"];
                        $phoneext = preg_replace("(\n)", "<BR>", $phoneext);
                        //$direct = $myrow["direct"];
                        //$direct = preg_replace("(\n)", "<BR>", $direct);
                        $fax = $myrow["fax"];
                        $fax = preg_replace("(\n)", "<BR>", $fax);
                        $personal_address = $myrow["personal_address"];
                        $personal_address = preg_replace("(\n)", "<BR>", $personal_address);
                        $location_address = $myrow["location_address"];
                        $location_address = preg_replace("(\n)", "<BR>", $location_address);
                        $Reports_To = $myrow["Reports_To"];
                        $Reports_To = preg_replace("(\n)", "<BR>", $Reports_To);
                        //$Temp_Agency = $myrow["Temp_Agency"];
                        //$Temp_Agency = preg_replace("(\n)", "<BR>", $Temp_Agency);
                        //$skype_username = $myrow["skype_username"];
                        //$skype_username = preg_replace("(\n)", "<BR>", $skype_username);
                        $Skype_Number = $myrow["Skype_Number"];
                        $Skype_Number = preg_replace("(\n)", "<BR>", $Skype_Number);
                        //$Skype_Account_Password = $myrow["Skype_Account_Password"];
                        //$Skype_Account_Password = preg_replace("(\n)", "<BR>", $Skype_Account_Password);
                        $Date_of_Initial_Start = $myrow["Date_of_Initial_Start"];
                        $Date_of_Initial_Start = preg_replace("(\n)", "<BR>", $Date_of_Initial_Start);
                        $Official_Start_Date = $myrow["Official_Start_Date"];
                        $Official_Start_Date = preg_replace("(\n)", "<BR>", $Official_Start_Date);
                        $Day_Date_90 = $myrow["Day_Date_90"];
                        $Day_Date_90 = preg_replace("(\n)", "<BR>", $Day_Date_90);
                        $personal_emailadd =  $myrow["personal_emailadd"];
                        $personal_emailadd = preg_replace("(\n)", "<BR>", $personal_emailadd);
                        $Box_Buck_Code = $myrow["Box_Buck_Code"];
                        $Box_Buck_Code = preg_replace("(\n)", "<BR>", $Box_Buck_Code);
                        $mobile = $myrow["mobile"];
                        $mobile = preg_replace("(\n)", "<BR>", $mobile);
                        $birthdate = $myrow["birthdate"];
                        $birthdate = preg_replace("(\n)", "<BR>", $birthdate);
                        $Forklift_Certification_Info = $myrow["Forklift_Certification_Info"];
                        $Forklift_Certification_Info = preg_replace("(\n)", "<BR>", $Forklift_Certification_Info);
                        $Additional_Notes = $myrow["Additional_Notes"];
                        $Additional_Notes = preg_replace("(\n)", "<BR>", $Additional_Notes);
                        $Referred_By = $myrow["Referred_By"];
                        $Referred_By = preg_replace("(\n)", "<BR>", $Referred_By);
                        //$Fantasy =  $myrow["Fantasy"];
                        //$Fantasy = preg_replace("(\n)", "<BR>", $Fantasy);
                        $Headshot = $myrow["Headshot"];
                        $Headshot = preg_replace("(\n)", "<BR>", $Headshot);
                        $title = $myrow["title"];
                        $title = preg_replace("(\n)", "<BR>", $title);
                        $rate_cost = $myrow["rate_cost"];
                        $rate_cost = preg_replace("(\n)", "<BR>", $rate_cost);
                        //$rate_revenue = $myrow["rate_revenue"];
                        //$rate_revenue = preg_replace("(\n)", "<BR>", $rate_revenue);
                        $bill_rate = $myrow["bill_rate"];
                        $bill_rate = preg_replace("(\n)", "<BR>", $bill_rate);
                        $employee_type = $myrow["employee_type"];
                        $employee_type = preg_replace("(\n)", "<BR>", $employee_type);
                        $status = $myrow["active"];
                        $status = preg_replace("(\n)", "<BR>", $status);
                    } while ($myrow = array_shift($result));


                    $warehouse_name = '';
                    $query = "SELECT company_name FROM loop_warehouse where loop_warehouse.id =" . $warehouse_id;
                    db();
                    $result = db_query($query);
                    //echo $query;
                    while ($myrow = array_shift($result)) {
                        $warehouse_name = $myrow['company_name'];
                    }


                    function matchstr(string $orgstr, string $inpstr): string
                    {
                        $tmppos = strpos($orgstr, ",");

                        if ($tmppos != false) {
                            $str_array = explode(",", $orgstr);
                            foreach ($str_array as $newar) {
                                if ($newar == $inpstr) {
                                    return "true";
                                    break;
                                }
                            }
                        } else {
                            if ($orgstr == $inpstr) {
                                return "true";
                            } else {
                                return "false";
                            }
                        }
                        return "false";
                    }

            ?>

            <?php

                } //END IF RESULTS
            } //END IF POST IS "" (THIS IS THE END OF EDITING A RECORD)
            ?>
            <?php
            // VIEW MODE

            /*----------------------------------------------------------------------
ADD NEW RECORDS SECTION
----------------------------------------------------------------------*/
            if (isset($proc) == "New") {
                echo "<a href=\"loop_worker.php?posting=yes&warehouse_id=" . $warehouse_id . "\">Back</a><br><br>";
            ?>
            <!--<a href="<?php echo $thispage; ?>?proc=&<?php echo $pagevars; ?>">Search</a><br>-->
            <?php
                if ($allowaddnew == "yes") {
                ?>
            <!--<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>-->
            <?php } //END OF IF ALLOW ADDNEW 
                /*-------------------------------------------------------------------------------
ADD NEW RECORD SECTION 9994
-------------------------------------------------------------------------------*/

                if ($proc == "New") {
                    echo "<DIV CLASS='PAGE_STATUS'>Adding Employee in Loop worker</DIV>";
                    if (isset($post) == "yes") {

                        /* FIX STRING */
                        //$username = FixString($_REQUEST["username"]);
                        //$password = FixString($_REQUEST["password"]);
                        $emp_id = FixString($_REQUEST["emp_id"]);
                        $name = FixString($_REQUEST["name"]);
                        $initials = FixString($_REQUEST["initials"]);
                        $user_pwd = FixString($_REQUEST["user_pwd"]);
                        $title = FixString($_REQUEST["title"]);
                        $phone = FixString($_REQUEST["phone"]);
                        $phoneext = FixString($_REQUEST["phoneext"]);
                        $personal_address = FixString($_REQUEST["personal_address"]);
                        $location_address = FixString($_REQUEST["location_address"]);
                        $Reports_To = FixString($_REQUEST["Reports_To"]);
                        //$Temp_Agency = FixString($_REQUEST["Temp_Agency"]);
                        $fax = FixString($_REQUEST["fax"]);
                        //$direct = FixString($_REQUEST["direct"]);
                        //$skype_username = FixString($_REQUEST["skype_username"]);
                        $Skype_Number = FixString($_REQUEST["Skype_Number"]);
                        //$Skype_Account_Password = FixString($_REQUEST["Skype_Account_Password"]);
                        $email = FixString($_REQUEST["email"]);
                        if ($_REQUEST["Date_of_Initial_Start"] == "") {
                            $Date_of_Initial_Start = 'NULL';
                        } else {
                            $Date_of_Initial_Start = ("'" . $_REQUEST["Date_of_Initial_Start"] . "'");
                        }

                        if ($_REQUEST["Official_Start_Date"] == "") {
                            $Official_Start_Date = 'NULL';
                        } else {
                            $Official_Start_Date = ("'" . $_REQUEST["Official_Start_Date"] . "'");
                        }

                        if ($_REQUEST["Day_Date_90"] == "") {
                            $Day_Date_90 = 'NULL';
                        } else {
                            $Day_Date_90 = ("'" . $_REQUEST["Day_Date_90"] . "'");
                        }

                        $personal_emailadd = FixString($_REQUEST["personal_emailadd"]);
                        $Box_Buck_Code = FixString($_REQUEST["Box_Buck_Code"]);
                        $mobile = FixString($_REQUEST["mobile"]);

                        if ($_REQUEST["birthdate"] == "") {
                            $birthdate = 'NULL';
                        } else {
                            $birthdate = ("'" . $_REQUEST["birthdate"] . "'");
                        }

                        $Forklift_Certification_Info = FixString($_REQUEST["Forklift_Certification_Info"]);
                        $Additional_Notes = FixString($_REQUEST["Additional_Notes"]);
                        $Referred_By = FixString($_REQUEST["Referred_By"]);
                        //$Fantasy = FixString($_REQUEST["Fantasy"]);
                        $ext = pathinfo($_FILES["Headshot"]["name"], PATHINFO_EXTENSION);
                        if (in_array(strtolower($ext), $allow_ext)) {
                            $Headshot = $_FILES["Headshot"]["name"];
                        } else {
                            $Headshot = "";
                        }
                        $rate_cost = $rate_cost ?? "";
                        $rate_cost = FixString($rate_cost);
                        //$rate_cost = preg_replace('/.(?=.*.)/', '', $rate_cost);
                        $bill_rate = $bill_rate ?? "";
                        //echo $rate_cost;
                        $bill_rate = FixString($bill_rate);
                        $employee_type = $employee_type ?? "";
                        //$rate_revenue = FixString($rate_revenue);
                        $employee_type = FixString($employee_type);
                        $warehouse_selected_id = $_REQUEST['worker_warehouse_name'];

                        $status = $status ?? "";
                        $status = FixString($status);
                        $emp_tier = $emp_tier ?? "";
                        $is_supervisor = $is_supervisor ?? "";
                        $supervisor_rec_tbl = $supervisor_rec_tbl ?? "";
                        $supervisor_name = $supervisor_name ?? "";
                        /*-- SECTION: 9994SQL --*/
                        $sql = "INSERT INTO loop_workers (
emp_id,
warehouse_id,
rate_cost,
employee_type,
active,
name,
initials,
user_pwd,
personal_address,
location_address,
Reports_To,
fax,
Skype_Number,
email,
Date_of_Initial_Start,
Official_Start_Date,
Day_Date_90,
personal_emailadd,
Box_Buck_Code,
mobile,
birthdate,
Forklift_Certification_Info,
Additional_Notes,
Referred_By,
Headshot,
title,
phone,
phoneext,
bill_rate,
is_supervisor,
supervisor_name,
supervisor_rec_tbl,
emp_tier
$addl_insert_crit ) VALUES (
 $emp_id,
 $warehouse_selected_id,
'$rate_cost',
'$employee_type',
'$status',
'$name',
'$initials',
'$user_pwd',
'$personal_address',
'$location_address',
'$Reports_To',
'$fax',
'$Skype_Number',
'$email',
 $Date_of_Initial_Start,
 $Official_Start_Date,
 $Day_Date_90,
'$personal_emailadd',
'$Box_Buck_Code',
'$mobile',
 $birthdate,
'$Forklift_Certification_Info',
'$Additional_Notes',
'$Referred_By',
'$Headshot',
'$title',
'$phone',
'$phoneext',
'$bill_rate',
'$is_supervisor',
'$supervisor_name',
'$supervisor_rec_tbl',
'$emp_tier'
)";

                        if (isset($sql_debug_mode) == 1) {
                            echo "<BR>SQL: $sql<BR>";
                        }
                        //echo $sql;
                        db();
                        $result = db_query($sql);

                        if ($_FILES["Headshot"]["error"] > 0) {
                            echo "Return Code: " . $_FILES["Headshot"]["error"] . "<br />";
                        } else {
                            $ext = pathinfo($_FILES["Headshot"]["name"], PATHINFO_EXTENSION);
                            if (in_array(strtolower($ext), $allow_ext)) {
                                move_uploaded_file($_FILES["Headshot"]["tmp_name"], "emp_images/" . $_FILES["Headshot"]["name"]);
                                $fileuploaded_flg = "yes";
                            } else {
                                $fileuploaded_flg = "err";
                                echo "<font color=red>" . $_FILES["Headshot"]["name"] . " file not uploaded, this file type is restricted.</font>";
                                echo "<script>alert('" . $_FILES["Headshot"]["name"] . " file not uploaded, this file type is restricted.');</script>";
                            }
                        }

                        if (empty($result)) {
                            echo "<DIV CLASS='SQL_RESULTS'>Record Inserted</DIV>";
                            echo "<script type=\"text/javascript\">";
                            echo "window.location.href=\"loop_worker.php?posting=yes&warehouse_id=" . $warehouse_id . "\";";
                            echo "</script>";
                            echo "<noscript>";
                            echo "<meta http-equiv=\"refresh\" content=\"0;url=loop_worker.php?posting=yes&warehouse_id=" . $warehouse_id . "\" />";
                            echo "</noscript>";
                            exit;
                        } else {
                            //echo ThrowError("9994SQL",$sql);
                            echo "Error inserting record (9994SQL)";
                        }


                        //***** END INSERT SQL *****
                    } //END IF POST = YES FOR ADDING NEW RECORDS
                    /*-------------------------------------------------------------------------------
ADD NEW RECORD (CREATING)
-------------------------------------------------------------------------------*/
                    if (!isset($post)) { //THEN WE ARE ENTERING A NEW RECORD
                        //SHOW THE ADD RECORD RECORD DATA INPUT FORM
                        /*-- SECTION: 9994FORM --*/
                    ?>
            <script type="text/javascript">
            function assign() {
                obj = document.newpep;
                str = "";
                for (i = 0; i < obj.permission.length; i++) {
                    if (obj.permission[i].checked)
                        str = (str == "") ? obj.permission[i].value : str + ',' + obj.permission[i].value;
                }
                obj.chkPermission.value = str;
            }

            function assign1() {
                obj = document.newpep;
                str = "";
                for (i = 0; i < obj.ppermission.length; i++) {
                    if (obj.ppermission[i].checked)
                        str = (str == "") ? obj.ppermission[i].value : str + ',' + obj.ppermission[i].value;
                }
                obj.chkPPerm.value = str;
            }

            function isNumberKey(evt) {
                var charCode = (evt.which) ? evt.which : event.keyCode

                if (charCode > 31 && (charCode < 48 || charCode > 57) && (charCode < 96 || charCode > 105)) {
                    if (charCode != 46 && charCode != 37) {
                        return false;
                    }
                    return true;
                }

            }

            window.onload = function init() {
                document.newpep.username.focus();
                assign();
                assign1();
            }


            function Validate() {
                if (document.getElementById("worker_warehouse_name").value == 0) {
                    alert("Must select a Warehouse Name before saving a new employee.");
                    document.getElementById("worker_warehouse_name").focus();
                    return false;
                }
            }
            </script>
            <FORM METHOD="POST" name="newpep"
                ACTION="<?php echo $thispage; ?>?proc=New&post=yes&<?php echo $pagevars; ?>"
                onSubmit="javascript:return Validate()" enctype="multipart/form-data">

                <TABLE ALIGN='LEFT'>
                    <TR>
                        <TD CLASS='TBL_ROW_HDR'>
                            <B>Warehouse Name:</B>&nbsp;<font color=red>*</font>
                        </TD>
                        <TD>
                            <select name='worker_warehouse_name' id='worker_warehouse_name'>
                                <?php
                                            $query = "SELECT loop_warehouse.id, loop_warehouse.b2bid, loop_warehouse.company_name FROM loop_warehouse inner join loop_workers on loop_workers.warehouse_id = loop_warehouse.id where loop_warehouse.company_name != '' group by loop_workers.warehouse_id order by loop_warehouse.company_name ";
                                            db();
                                            $result = db_query($query);

                                            echo "<option value='0'>Please Select</option>";
                                            db();
                                            $result = db_query($query);
                                            while ($myrowsel = array_shift($result)) {
                                                $nickname = get_nickname_val($myrowsel["company_name"], $myrowsel["b2bid"]);

                                                echo "<option value=" . $myrowsel["id"] . " ";
                                                if ($warehouse_id) {
                                                    if ($myrowsel["id"] == $warehouse_id) echo " selected ";
                                                }

                                                echo " >";
                                                echo  $nickname;
                                                echo "</option>";

                                                /*if ($myrowsel["id"] == 556){
			echo  "UCB - HA - Production";
			echo "</option>";

			echo "<option value='HAOffice'";
			if (($warehouse_id == 'HAOffice') ) echo " selected ";
			echo ">UCB - HA - Office</option>";
		}else if ($myrowsel["id"] == 18){
			echo  "UCB - HV - Production";
			echo "</option>";

			echo "<option value='HVOffice'"; 
			if (($warehouse_id == 'HVOffice') ) echo " selected ";
			echo ">UCB - HV - Office</option>";
		}else if ($myrowsel["id"] == 2563){
			echo  "UCB - ML - Production";
			echo "</option>";
			
			echo "<option value='MLOffice'";
			if (($warehouse_id == 'MLOffice') ) echo " selected ";
			echo ">UCB - ML - Office</option>";
		}else{	
			echo  $myrowsel['company_name'];
			echo "</option>";
		}	
		*/
                                            }
                                            ?>
                            </select>

                    </TR>
                    <TR>
                        <TD CLASS='TBL_ROW_HDR'>
                            <B>Employee ID:</B>
                        </TD>
                        <TD>
                            <INPUT CLASS='TXT_BOX' type="text" NAME="emp_id" size='36' value="0"
                                onkeypress="return numbersonly(event)">
                        </td>
                        <td colspan="3">&nbsp;</td>
                    </TR>
                    <TR>
                        <TD CLASS='TBL_ROW_HDR'>
                            <B>Employee Name:</B>
                        </TD>
                        <TD>
                            <INPUT CLASS='TXT_BOX' type="text" NAME="name" size='36'>
                        </td>
                        <td>&nbsp;</td>
                        <TD CLASS='TBL_ROW_HDR'>&nbsp;

                        </TD>
                        <TD>&nbsp;

                        </td>
                    </TR>
                    <TR>
                        <TD CLASS='TBL_ROW_HDR'>
                            <B>Title:</B>
                        </TD>
                        <TD>
                            <INPUT CLASS='TXT_BOX' type="text" NAME="title" size='36'>
                        </td>
                        <td>&nbsp;</td>
                        <TD CLASS='TBL_ROW_HDR'>&nbsp;

                        </TD>
                        <TD>&nbsp;

                        </td>

                    </TR>
                    <TR>
                        <TD CLASS='TBL_ROW_HDR'>
                            <B>Title:</B>
                        </TD>
                        <TD>
                            <INPUT CLASS='TXT_BOX' type="text" NAME="title" size='36'>
                        </td>
                        <td>&nbsp;</td>
                        <TD CLASS='TBL_ROW_HDR'>Employee Tier
                        </TD>
                        <td>
                            <select CLASS='TXT_BOX' name="emp_tier">
                                <?php
                                            $qry = "select * from loop_worker_tier";
                                            $res = db_query($qry);
                                            while ($wrow = array_shift($res)) {
                                            ?>
                                <option value="<?php echo $wrow["tier"]; ?>">
                                    <?php echo $wrow["tier"] . " (" . $wrow["tier_year"] . ")"; ?></option>
                                <?php
                                            }
                                            ?>
                            </select>
                        </td>

                    </TR>
                    <TR>
                        <TD CLASS='TBL_ROW_HDR'>
                            <B>Work Location:</B>
                        </TD>
                        <TD>
                            <!--<INPUT CLASS='TXT_BOX' type="text" NAME="location_address" size='36'>-->
                            <select CLASS='TXT_BOX' name="location_address" id="location_address">
                                <option value="" selected>Select Location</option>
                                <option value="Los Angeles Office">Los Angeles Office</option>
                                <option value="Los Angeles Office (Remote)">Los Angeles Office (Remote)</option>
                                <option value="Hannibal Office">Hannibal Office</option>
                                <option value="Hannibal Office (Remote)">Hannibal Office (Remote)</option>
                                <option value="Hannibal Production">Hannibal Production</option>
                                <option value="Milwaukee Office">Milwaukee Office</option>
                                <option value="Milwaukee Production">Milwaukee Production</option>
                                <option value="Hunt Valley Office">Hunt Valley Office</option>
                                <option value="Hunt Valley Production">Hunt Valley Production</option>
                                <option value="McCormick HVP Onsite">McCormick HVP Onsite</option>
                            </select>
                        </td>
                        <td>&nbsp;</td>
                        <TD CLASS='TBL_ROW_HDR'><B>Is Supervisor?</B></TD>
                        <td><input type="checkbox" name="is_supervisor" id="is_supervisor" value="Yes"></td>
                    </TR>
                    <TR>
                        <TD CLASS='TBL_ROW_HDR'>&nbsp;

                        </TD>
                        <TD>&nbsp;

                        </td>
                        <td>&nbsp;</td>
                        <TD CLASS='TBL_ROW_HDR'><B>Supervisor:</B></TD>
                        <td><select name="supervisor_name" id="supervisor_name">
                                <option value="">Select</option>
                                <?php
                                            $sup_query = "SELECT id, name, supervisor_name FROM loop_workers WHERE is_supervisor='Yes' UNION ALL SELECT id, name, supervisor_name FROM loop_employees WHERE is_supervisor='Yes' order by name";
                                            db();
                                            $sup_result = db_query($sup_query);
                                            while ($sup_row = array_shift($sup_result)) {
                                            ?>
                                <option value="<?php echo $sup_row["id"]; ?>|lw"><?php echo $sup_row["name"]; ?>
                                </option>
                                <?php
                                            }
                                            ?>
                            </select></td>
                    </TR>

                    <TR>
                        <TD CLASS='TBL_ROW_HDR'>
                            <B>Personal Address:</B>
                        </TD>
                        <TD>
                            <INPUT CLASS='TXT_BOX' type="text" NAME="personal_address" size='36'>
                        </td>
                        <td>&nbsp;</td>
                        <TD CLASS='TBL_ROW_HDR'>&nbsp;</TD>
                        <TD>&nbsp;</td>
                    </TR>
                    <TR>
                        <TD CLASS='TBL_ROW_HDR'>
                            <B>Box Buck Code:</B>
                        </TD>
                        <TD>
                            <INPUT CLASS='TXT_BOX' type="text" NAME="Box_Buck_Code" size='36'>
                        </td>
                        <td>&nbsp;</td>
                        <TD CLASS='TBL_ROW_HDR'><B>Date of Initial Start:</B></TD>
                        <TD><input type="text" name="Date_of_Initial_Start" size="8" readonly>
                            <a href="#"
                                onclick="cal1xx.select(document.newpep.Date_of_Initial_Start,'dtanchor1xx','yyyy-MM-dd'); return false;"
                                name="dtanchor1xx" id="dtanchor1xx"><img border="0" src="images/calendar.jpg"></a>
                            <div ID="listdiv"
                                STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                            </div>
                        </td>
                    </TR>
                    <TR>
                        <TD CLASS='TBL_ROW_HDR'>
                            <B>Personal Email:</B>
                        </TD>
                        <TD>
                            <INPUT CLASS='TXT_BOX' type="text" NAME="personal_emailadd" size='36'>
                        </td>
                        <td>&nbsp;</td>
                        <TD CLASS='TBL_ROW_HDR'><B>90-Day Date:</B></TD>
                        <TD><input type="text" name="Day_Date_90" size="8" readonly>
                            <a href="#"
                                onclick="cal3xx.select(document.newpep.Day_Date_90,'dtanchor3xx','yyyy-MM-dd'); return false;"
                                name="dtanchor3xx" id="dtanchor3xx"><img border="0" src="images/calendar.jpg"></a>
                            <div ID="listdiv"
                                STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                            </div>
                        </td>
                    </TR>
                    <TR>
                        <TD CLASS='TBL_ROW_HDR'>
                            <B>Mobile(personal use only/not for customers):</B>
                        </TD>
                        <TD>
                            <INPUT CLASS='TXT_BOX' type="text" NAME="mobile" size='36'>
                        </td>
                        <td>&nbsp;</td>
                        <TD CLASS='TBL_ROW_HDR'><B>Official Start Date:</B></TD>
                        <TD><input type="text" name="Official_Start_Date" size="8" readonly>
                            <a href="#"
                                onclick="cal2xx.select(document.newpep.Official_Start_Date,'dtanchor2xx','yyyy-MM-dd'); return false;"
                                name="dtanchor2xx" id="dtanchor2xx"><img border="0" src="images/calendar.jpg"></a>
                            <div ID="listdiv"
                                STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                            </div>
                        </td>
                    </TR>
                    <TR>
                        <TD CLASS='TBL_ROW_HDR'>
                            <B>Forklift Certification Info (dates, etc.):</B>
                        </TD>
                        <TD>
                            <INPUT CLASS='TXT_BOX' type="text" NAME="Forklift_Certification_Info" size='36'>
                        </td>
                        <td>&nbsp;</td>
                        <TD CLASS='TBL_ROW_HDR'><B>Birth Date</B></TD>
                        <TD><input type="text" name="birthdate" size="8" readonly>
                            <a href="#"
                                onclick="cal4xx.select(document.newpep.birthdate,'dtanchor4xx','yyyy-MM-dd'); return false;"
                                name="dtanchor4xx" id="dtanchor4xx"><img border="0" src="images/calendar.jpg"></a>
                            <div ID="listdiv"
                                STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <TD CLASS='TBL_ROW_HDR'>
                            <B>Referred By:</B>
                        </TD>
                        <TD>
                            <INPUT CLASS='TXT_BOX' type="text" NAME="Referred_By" size='36'>
                        </td>
                        <td>&nbsp;</td>
                        <TD CLASS='TBL_ROW_HDR'><B>Additional Notes:</B></TD>
                        <TD><INPUT CLASS='TXT_BOX' type="text" NAME="Additional_Notes" size='36'></td>
                    </tr>
                    <tr>
                        <TD CLASS='TBL_ROW_HDR'>
                            <B>Pay Rate:</B>
                        </TD>
                        <TD>
                            <INPUT CLASS='TXT_BOX' type="text" NAME="rate_cost" SIZE="20"
                                onkeypress="return numbersonly(event)">
                        </td>
                        <td>&nbsp;</td>
                        <TD CLASS='TBL_ROW_HDR'><B>Headshot:</B></TD>
                        <TD><INPUT CLASS='TXT_BOX' type="file" NAME="Headshot"></td>
                    </tr>

                    <TR>
                        <TD CLASS='TBL_ROW_HDR'>
                            <B>Employee Type:</B>
                        </TD>
                        <TD>
                            <SELECT CLASS='TXT_BOX' NAME="employee_type" SIZE="1">
                                <option value="UCB Employee">UCB Employee</option>
                                <option value="Temp">Temp</option>
                            </SELECT>
                        </td>
                        <td>&nbsp;</td>
                        <TD CLASS='TBL_ROW_HDR'><B>Bill Rate:</B></TD>
                        <TD><INPUT CLASS='TXT_BOX' type="text" NAME="bill_rate" SIZE="20"
                                onkeypress="return numbersonly(event)"></td>
                    </TR>

                    <TR>
                        <TD CLASS='TBL_ROW_HDR'>
                            <B>Status:</B>
                        </TD>
                        <TD>
                            <SELECT CLASS='TXT_BOX' NAME="status" SIZE="1">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </SELECT>
                        </td>
                        <td>&nbsp;</td>
                        <TD CLASS='TBL_ROW_HDR'><B>Clock In/Out Pin:</B></TD>
                        <TD><INPUT CLASS='TXT_BOX' type="password" NAME="user_pwd" SIZE="20"
                                onkeypress="return numbersonly(event)"></td>
                    </TR>

                    <TR>

                    </TR>

                    <TR>
                        <td colspan="5">
                            <font color=red>*</font>
                            <font size=1>= Required on the page.</font>
                            &nbsp;
                        </td>
                    </TR>

                    <TR>
                        <td>&nbsp;</td>
                        <TD colspan="3">
                            <input type="hidden" value="<?php echo $warehouse_id; ?>" name="warehouse_id"
                                id="warehouse_id">
                            <INPUT CLASS='BUTTON' TYPE="SUBMIT" VALUE="Submit" NAME="SUBMIT">
                            <INPUT CLASS='BUTTON' TYPE="RESET" VALUE="Reset" NAME="RESET">
                        </TD>
                        <td>&nbsp;</td>
                    </TR>
                </TABLE>
                <BR>
            </FORM>

            <?php
                    } //END if post=""
                    //***** END ADD NEW ENTRY FORM*****
                } //END PROC == NEW


                /*---------------------------------------------------------------------------------
END ADD SECTION 9994
---------------------------------------------------------------------------------*/
            } // END IF PROC = "NEW"
            ?>
            <?php
            /*-------------------------------------------------
SEARCH AND ADD-NEW LINKS
-------------------------------------------------*/

            if (isset($proc) == "Edit") {
            ?>
            <!--<a href="<?php echo $thispage; ?>?proc=&<?php echo $pagevars; ?>">Search</a><br>-->
            <?php
                if ($allowaddnew == "yes") {
                ?>
            <!--<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>-->
            <?php } //END OF IF ALLOW ADDNEW 

                /*----------------------------------------------------------------------
EDIT RECORDS SECTION
----------------------------------------------------------------------*/
                /*-------------------------------------------------------------------------------
EDIT RECORD SECTION 9993
-------------------------------------------------------------------------------*/

                echo "<a href=\"loop_worker.php?posting=yes&warehouse_id=" . $warehouse_id . "\">Back</a><br><br>";
                ?>
            <!--
<DIV CLASS='PAGE_OPTIONS'>
	<?php if ($allowview == "yes") { ?><a href="<?php echo $thispage; ?>?id=<?php echo isset($id); ?>&proc=View&<?php echo $pagevars; ?>">View</a>
	<?php } //END ALLOWVIEW 
    ?><?php if ($allowedit == "yes") { ?>
	<a href="<?php echo $thispage; ?>?id=<?php echo isset($id); ?>&proc=Edit&<?php echo $pagevars; ?>">Edit</a>
	<?php } //END ALLOWEDIT 
    ?><?php if ($allowdelete == "yes") { ?>
	<a href="<?php echo $thispage; ?>?id=<?php echo isset($id); ?>&proc=Delete&<?php echo $pagevars; ?>">Delete</a>
	<?php } //END ALLOWDELETE 
    ?></font><font face="arial" size="2">
</DIV>-->

            <?php

                if ($proc == "Edit") {
                    //SHOW THE EDIT RECORD RECORD PAGE
                    //******************************************************************//
                    if (isset($post) == "yes") {

                        //THEN WE ARE POSTING UPDATES TO A RECORD
                        //***** BEGIN UPDATE SQL*****

                        //REPLACE THE FIELD CONTENTS SO THEY DON'T MESS UP YOUR QUERY
                        //NOW LOOP THROUGH ALL OF THE RECORDS AND OUTPUT A STRING

                        /* FIX STRING */
                        //$username = FixString($_REQUEST["username"]);
                        //$password = FixString($_REQUEST["password"]);
                        $emp_id = FixString($_REQUEST["emp_id"]);
                        $name = FixString($_REQUEST["name"]);
                        $initials = FixString($_REQUEST["initials"]);
                        $user_pwd = FixString($_REQUEST["user_pwd"]);
                        $title = FixString($_REQUEST["title"]);
                        $phone = FixString($_REQUEST["phone"]);
                        $phoneext = FixString($_REQUEST["phoneext"]);
                        $personal_address = FixString($_REQUEST["personal_address"]);
                        $location_address = FixString($_REQUEST["location_address"]);
                        $emp_tier = FixString($_REQUEST["emp_tier"]);
                        $Reports_To = FixString($_REQUEST["Reports_To"]);
                        //$Temp_Agency = FixString($_REQUEST["Temp_Agency"]);
                        $fax = FixString($_REQUEST["fax"]);
                        //$direct = FixString($_REQUEST["direct"]);
                        //$skype_username = FixString($_REQUEST["skype_username"]);
                        $Skype_Number = FixString($_REQUEST["Skype_Number"]);
                        //$Skype_Account_Password = FixString($_REQUEST["Skype_Account_Password"]);
                        $email = FixString($_REQUEST["email"]);
                        if ($_REQUEST["Date_of_Initial_Start"] == "") {
                            $Date_of_Initial_Start = 'NULL';
                        } else {
                            $Date_of_Initial_Start = ("'" . $_REQUEST["Date_of_Initial_Start"] . "'");
                        }

                        if ($_REQUEST["Official_Start_Date"] == "") {
                            $Official_Start_Date = 'NULL';
                        } else {
                            $Official_Start_Date = ("'" . $_REQUEST["Official_Start_Date"] . "'");
                        }

                        if ($_REQUEST["Day_Date_90"] == "") {
                            $Day_Date_90 = 'NULL';
                        } else {
                            $Day_Date_90 = ("'" . $_REQUEST["Day_Date_90"] . "'");
                        }

                        $personal_emailadd = FixString($_REQUEST["personal_emailadd"]);
                        $Facebook_Account_which_if_Fan_of_UCB = FixString($_REQUEST["Facebook_Account_which_if_Fan_of_UCB"]);
                        $Twitter_Account_Following_UCB = FixString($_REQUEST["Twitter_Account_Following_UCB"]);
                        $LinkedIn_Account = FixString($_REQUEST["LinkedIn_Account"]);
                        $Box_Buck_Code = FixString($_REQUEST["Box_Buck_Code"]);
                        $Craigslist_B2C_Cities = FixString($_REQUEST["Craigslist_B2C_Cities"]);
                        $Craigslist_B2B_Cities = FixString($_REQUEST["Craigslist_B2B_Cities"]);
                        $Outreach_Sources = FixString($_REQUEST["Outreach_Sources"]);
                        $mobile = FixString($_REQUEST["mobile"]);
                        $Mobile_Voicemail = FixString($_REQUEST["Mobile_Voicemail"]);

                        if ($_REQUEST["birthdate"] == "") {
                            $birthdate = 'NULL';
                        } else {
                            $birthdate = ("'" . $_REQUEST["birthdate"] . "'");
                        }

                        $Agreement_Keys = FixString($_REQUEST["Agreement_Keys"]);
                        $Key_Agreement_Facility = FixString($_REQUEST["Key_Agreement_Facility"]);
                        $Forklift_Certification_Info = FixString($_REQUEST["Forklift_Certification_Info"]);
                        $Additional_Notes = FixString($_REQUEST["Additional_Notes"]);
                        $Referred_By = FixString($_REQUEST["Referred_By"]);
                        $Fantasy = FixString($_REQUEST["Fantasy"]);
                        $Headshot_qry = "";
                        if ($_FILES["Headshot"]["name"] != "") {
                            $ext = pathinfo($_FILES["Headshot"]["name"], PATHINFO_EXTENSION);
                            if (in_array(strtolower($ext), $allow_ext)) {
                                $Headshot = $_FILES["Headshot"]["name"];
                                $Headshot_qry = "Headshot = '$Headshot' , ";
                            }
                        }

                        $is_supervisor = FixString($_REQUEST["is_supervisor"]);
                        $supervisor_name_array = explode('|', $_REQUEST["supervisor_name"]);
                        $supervisor_name = $supervisor_name_array[0];
                        $supervisor_rec_tbl = $supervisor_name_array[1];

                        $rate_cost = FixString($_REQUEST["rate_cost"]);
                        //$rate_revenue = FixString($_REQUEST["rate_revenue"]);
                        $employee_type = FixString($_REQUEST["employee_type"]);
                        $status = FixString($_REQUEST["status"]);
                        $worker_warehouse_name_edit = FixString($_REQUEST["worker_warehouse_name_edit"]);


                        //SQL STRING
                        /*-- SECTION: 9993SQLUPD --*/

                        $id = $id ?? "";
                        $bill_rate = $bill_rate ?? "";

                        $sql = "UPDATE loop_workers SET 
warehouse_id='$worker_warehouse_name_edit',
emp_id='$emp_id',
name = '$name',
initials = '$initials',
user_pwd = '$user_pwd',
title = '$title',
personal_address = '$personal_address',
location_address = '$location_address',
emp_tier = '$emp_tier',
Reports_To = '$Reports_To',
fax = '$fax',
Skype_Number = '$Skype_Number',
email = '$email',
Date_of_Initial_Start = $Date_of_Initial_Start,
Official_Start_Date = $Official_Start_Date,
Day_Date_90 = $Day_Date_90,
personal_emailadd = '$personal_emailadd',
Box_Buck_Code = '$Box_Buck_Code',
mobile = '$mobile',
birthdate = $birthdate,
Forklift_Certification_Info = '$Forklift_Certification_Info',
Additional_Notes = '$Additional_Notes',
Referred_By = '$Referred_By',
$Headshot_qry
rate_cost='$rate_cost',
employee_type='$employee_type',
active='$status',
phone='$phone',
phoneext='$phoneext',
bill_rate='$bill_rate',
is_supervisor = '$is_supervisor',
supervisor_name = '$supervisor_name',
supervisor_rec_tbl = '$supervisor_rec_tbl' 

 $addl_update_crit 
	WHERE (id=$id) $addl_select_crit ";
                        echo $sql;

                        if (isset($sql_debug_mode) == 1) {
                            echo "<BR>SQL: $sql<BR>";
                        }
                        db();
                        $result = db_query($sql);

                        if ($_FILES["Headshot"]["error"] > 0) {
                            echo "Return Code: " . $_FILES["Headshot"]["error"] . "<br />";
                        } else {
                            $ext = pathinfo($_FILES["Headshot"]["name"], PATHINFO_EXTENSION);
                            if (in_array(strtolower($ext), $allow_ext)) {
                                move_uploaded_file($_FILES["Headshot"]["tmp_name"], "emp_images/" . $_FILES["Headshot"]["name"]);
                            } else {
                                $fileuploaded_flg = "err";
                                echo "<font color=red>" . $_FILES["Headshot"]["name"] . " file not uploaded, this file type is restricted.</font>";
                                echo "<script>alert('" . $_FILES["Headshot"]["name"] . " file not uploaded, this file type is restricted.');</script>";
                            }
                        }

                        /*
if ($level == "1")
{ $tmp_is_admin = "Y";} else { $tmp_is_admin = "N";} 

$sql = "UPDATE employees SET 
 username='$username',
 password='$password',
 name='$name',
 initials='$initials',
 email='$email',
 mobile='$mobile',
 status='$status',
 title='$title',
 is_admin='$tmp_is_admin',
 sperm='$chkPermission',
 perm='$chkPPerm'
	WHERE (employeeid= $b2b2_empid) ";
*/
                        //if ($sql_debug_mode==1) { echo "<BR>SQL: $sql<BR>"; }
                        //echo $sql;
                        //$result = db_query($sql,db() );
                        if (empty($result)) {
                            echo "<DIV CLASS='SQL_RESULTS'>Updated</DIV>";
                            //header('Location: employee.php?posting=yes');

                            echo "<script type=\"text/javascript\">";
                            echo "window.location.href=\"loop_worker.php?posting=yes&warehouse_id=" . $warehouse_id . "\";";
                            echo "</script>";
                            echo "<noscript>";
                            echo "<meta http-equiv=\"refresh\" content=\"0;url=loop_worker.php?posting=yes&warehouse_id=" . $warehouse_id . "\" />";
                            echo "</noscript>";
                            exit;
                        } else {
                            echo "Error Updating Record (9993SQLUPD)";
                        }

                        //***** END UPDATE SQL *****
                    } //END IF POST IS YES

                    /*-------------------------------------------------------------------------------
EDIT RECORD (FORM) - SHOW THE EDIT RECORD RECORD DATA INPUT FORM
-------------------------------------------------------------------------------*/
                    if (isset($post) == "") { //THEN WE ARE EDITING A RECORD
                        echo "<DIV CLASS='PAGE_STATUS'>Edit Employee</DIV>";
                        $b2b2_empid = 0;
                        $id = $id ?? "";
                        /*-- SECTION: 9993SQLGET --*/
                        $sql = "SELECT * FROM loop_workers WHERE (id = $id) $addl_select_crit ";
                        //echo $sql;
                        if (isset($sql_debug_mode) == 1) {
                            echo "<BR>SQL: $sql<BR>";
                        }
                        db();
                        $result = db_query($sql) or die("Error Retrieving Records (9993SQLGET)");
                        if ($myrow = array_shift($result)) {
                            do {

                                /*$username = $myrow["username"];
$username = preg_replace("(\n)", "<BR>", $username);
$password = $myrow["password"];
$password = preg_replace("(\n)", "<BR>", $password);
*/
                                $emp_id = $myrow["emp_id"];
                                $emp_id = preg_replace("(\n)", "<BR>", $emp_id);
                                $name = $myrow["name"];
                                $name = preg_replace("(\n)", "<BR>", $name);
                                $initials = $myrow["initials"];
                                $initials = preg_replace("(\n)", "<BR>", $initials);
                                $user_pwd = $myrow["user_pwd"];
                                $email = $myrow["email"];
                                $email = preg_replace("(\n)", "<BR>", $email);
                                $phone = $myrow["phone"];
                                $phone = preg_replace("(\n)", "<BR>", $phone);
                                $phoneext = $myrow["phoneext"];
                                $phoneext = preg_replace("(\n)", "<BR>", $phoneext);

                                $is_supervisor = $myrow["is_supervisor"];
                                $supervisor_name = $myrow["supervisor_name"];
                                $supervisor_rec_tbl = $myrow["supervisor_rec_tbl"];

                                //$direct = $myrow["direct"];
                                //$direct = preg_replace("(\n)", "<BR>", $direct);
                                $fax = $myrow["fax"];
                                $fax = preg_replace("(\n)", "<BR>", $fax);
                                $personal_address = $myrow["personal_address"];
                                $personal_address = preg_replace("(\n)", "<BR>", $personal_address);
                                $location_address = $myrow["location_address"];
                                $location_address = preg_replace("(\n)", "<BR>", $location_address);
                                $emp_tier = $myrow["emp_tier"];
                                $emp_tier = preg_replace("(\n)", "<BR>", $emp_tier);
                                $Reports_To = $myrow["Reports_To"];
                                $Reports_To = preg_replace("(\n)", "<BR>", $Reports_To);
                                //$Temp_Agency = $myrow["Temp_Agency"];
                                //$Temp_Agency = preg_replace("(\n)", "<BR>", $Temp_Agency);
                                //$skype_username = $myrow["skype_username"];
                                //$skype_username = preg_replace("(\n)", "<BR>", $skype_username);
                                $Skype_Number = $myrow["Skype_Number"];
                                $Skype_Number = preg_replace("(\n)", "<BR>", $Skype_Number);
                                //$Skype_Account_Password = $myrow["Skype_Account_Password"];
                                //$Skype_Account_Password = preg_replace("(\n)", "<BR>", $Skype_Account_Password);
                                $Date_of_Initial_Start =  $myrow["Date_of_Initial_Start"];
                                $Date_of_Initial_Start = preg_replace("(\n)", "<BR>", $Date_of_Initial_Start);
                                $Official_Start_Date =  $myrow["Official_Start_Date"];
                                $Official_Start_Date = preg_replace("(\n)", "<BR>", $Official_Start_Date);
                                $Day_Date_90 = $myrow["Day_Date_90"];
                                $Day_Date_90 = preg_replace("(\n)", "<BR>", $Day_Date_90);
                                $personal_emailadd =  $myrow["personal_emailadd"];
                                $personal_emailadd = preg_replace("(\n)", "<BR>", $personal_emailadd);
                                $Box_Buck_Code = $myrow["Box_Buck_Code"];
                                $Box_Buck_Code = preg_replace("(\n)", "<BR>", $Box_Buck_Code);
                                $mobile = $myrow["mobile"];
                                $mobile = preg_replace("(\n)", "<BR>", $mobile);
                                $Mobile_Voicemail = $myrow["Mobile_Voicemail"];
                                $Mobile_Voicemail = preg_replace("(\n)", "<BR>", $Mobile_Voicemail);
                                $birthdate = $myrow["birthdate"];
                                $birthdate = preg_replace("(\n)", "<BR>", $birthdate);
                                $Forklift_Certification_Info = $myrow["Forklift_Certification_Info"];
                                $Forklift_Certification_Info = preg_replace("(\n)", "<BR>", $Forklift_Certification_Info);
                                $Additional_Notes = $myrow["Additional_Notes"];
                                $Additional_Notes = preg_replace("(\n)", "<BR>", $Additional_Notes);
                                $Referred_By = $myrow["Referred_By"];
                                $Referred_By = preg_replace("(\n)", "<BR>", $Referred_By);
                                //$Fantasy =  $myrow["Fantasy"];
                                //$Fantasy = preg_replace("(\n)", "<BR>", $Fantasy);
                                $Headshot = $myrow["Headshot"];
                                $Headshot = preg_replace("(\n)", "<BR>", $Headshot);
                                $title = $myrow["title"];
                                $title = preg_replace("(\n)", "<BR>", $title);
                                $rate_cost = $myrow["rate_cost"];
                                $rate_cost = preg_replace("(\n)", "<BR>", $rate_cost);
                                //$rate_revenue = $myrow["rate_revenue"];
                                //$rate_revenue = preg_replace("(\n)", "<BR>", $rate_revenue);
                                $employee_type = $myrow["employee_type"];
                                $employee_type = preg_replace("(\n)", "<BR>", $employee_type);
                                $status = $myrow["active"];
                                $status = preg_replace("(\n)", "<BR>", $status);
                                $bill_rate = $myrow["bill_rate"];
                                $bill_rate = preg_replace("(\n)", "<BR>", $bill_rate);
                            } while ($myrow = array_shift($result));

                            /*
$sql = "SELECT * FROM loop_worker WHERE (employeeID = $b2b2_empid) $addl_select_crit ";
//if ($sql_debug_mode==1) { echo "<BR>SQL: $sql<BR>"; }
$result = db_query($sql, db_b2b() ) or die ("Error Retrieving Records (9993SQLGET)");
if ($myrow = array_shift($result)) {
	$mobile = $myrow["mobile"];
	$title_nm = $myrow["title"];
	$tmp_sperm = $myrow["sperm"];
	$tmp_perm = $myrow["perm"];
}
*/
                            $warehouse_name = '';
                            $query = "SELECT company_name FROM loop_warehouse where loop_warehouse.id =" . $warehouse_id;
                            db();
                            $result = db_query($query);
                            //echo $query;
                            while ($myrow = array_shift($result)) {
                                $warehouse_name = $myrow['company_name'];
                            }

                            /*if ($warehouse_id == 556){
		if ($location_address == "Hannibal Office"){
			$warehouse_name = "UCB - HA - Office";
		}else{
			$warehouse_name = "UCB - HA - Production";
		}
	}else if ($warehouse_id == 18){
		if ($location_address == "Hunt Valley Office"){
			$warehouse_name = "UCB - HV - Office";
		}else{
			$warehouse_name = "UCB - HV - Production";
		}
	}else if ($warehouse_id == 2563){
		if ($location_address == "Milwaukee Office"){
			$warehouse_name = "UCB - ML - Office";
		}else{
			$warehouse_name = "UCB - ML - Production";
		}
	}	*/


                            function matchstr(string $orgstr, string $inpstr): string
                            {
                                $tmppos = strpos($orgstr, ",");

                                if ($tmppos != false) {
                                    $str_array = explode(",", $orgstr);
                                    foreach ($str_array as $newar) {
                                        if ($newar == $inpstr) {
                                            return "true";
                                            break;
                                        }
                                    }
                                } else {
                                    if ($orgstr == $inpstr) {
                                        return "true";
                                    } else {
                                        return "false";
                                    }
                                }
                                return "false";
                            }

                ?>
            <script language="javascript">
            function isNumberKey(evt) {
                var charCode = (evt.which) ? evt.which : event.keyCode

                if (charCode > 31 && (charCode < 48 || charCode > 57) && (charCode < 96 || charCode > 105)) {
                    if (charCode != 46 && charCode != 37) {
                        return false;
                    }
                    return true;
                }

            }

            function assign() {
                obj = document.editfrm;
                str = "";
                for (i = 0; i < obj.permission.length; i++) {
                    if (obj.permission[i].checked)
                        str = (str == "") ? obj.permission[i].value : str + ',' + obj.permission[i].value;
                }
                obj.chkPermission.value = str;
            }

            function assign1() {
                obj = document.editfrm;
                str = "";
                for (i = 0; i < obj.ppermission.length; i++) {
                    if (obj.ppermission[i].checked)
                        str = (str == "") ? obj.ppermission[i].value : str + ',' + obj.ppermission[i].value;
                }
                obj.chkPPerm.value = str;
            }

            function Validate_edit() {
                if (document.getElementById("worker_warehouse_name_edit").value == 0) {
                    alert("Must select a Warehouse Name before saving a new employee.");
                    document.getElementById("worker_warehouse_name_edit").focus();
                    return false;
                }
            }
            </script>

            <form method="post" name="editfrm"
                action="<?php echo $thispage; ?>?proc=Edit&post=yes&<?php echo $pagevars; ?>"
                onSubmit="javascript:return Validate_edit()" enctype="multipart/form-data">
                <br>
                <TABLE ALIGN='LEFT'>

                    <TR>
                        <TD CLASS='TBL_ROW_HDR'>
                            <B>Warehouse Name:</B>&nbsp;<font color=red>*</font>
                        </TD>
                        <TD style="FONT-FAMILY: Arial; FONT-WEIGHT: normal; FONT-SIZE: 11px;">
                            <select name='worker_warehouse_name_edit' id='worker_warehouse_name_edit'>
                                <?php
                                                $query = "SELECT loop_warehouse.id, loop_warehouse.b2bid, loop_warehouse.company_name FROM loop_warehouse left join loop_workers on loop_workers.warehouse_id = loop_warehouse.id where loop_warehouse.company_name != '' group by loop_workers.warehouse_id order by loop_warehouse.company_name ";
                                                db();
                                                $result = db_query($query);

                                                echo "<option value='0'>Please Select</option>";
                                                db();
                                                $result = db_query($query);
                                                while ($myrowsel = array_shift($result)) {
                                                    $nickname = get_nickname_val($myrowsel["company_name"], $myrowsel["b2bid"]);

                                                    echo "<option value=" . $myrowsel["id"] . " ";
                                                    if ($warehouse_id) {
                                                        if ($myrowsel["id"] == $warehouse_id) echo " selected ";
                                                    }
                                                    echo " >";
                                                    echo  $nickname;
                                                    echo "</option>";
                                                }
                                                ?>
                            </select>
                        </td>
                    </tr>
                    <TR>
                        <TD CLASS='TBL_ROW_HDR'>
                            Employee ID:
                        </TD>
                        <TD>
                            <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='emp_id' onkeypress="return numbersonly(event)"
                                value='<?php echo $emp_id; ?>' size='36'>
                        </td>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <TR>
                        <TD CLASS='TBL_ROW_HDR'>
                            Employee Name:
                        </TD>
                        <TD>
                            <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='name' value='<?php echo $name; ?>' size='36'>
                        </td>
                        <td>&nbsp;</td>
                        <TD CLASS='TBL_ROW_HDR'>&nbsp;

                        </TD>
                        <TD>&nbsp;

                        </td>
                    </tr>
                    <TR>
                        <TD CLASS='TBL_ROW_HDR'>
                            Title:
                        </TD>
                        <TD>
                            <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='title' value='<?php echo $title; ?>' size='36'>
                        </td>
                        <td>&nbsp;</td>
                        <TD CLASS='TBL_ROW_HDR'>Employee Tier
                        </TD>
                        <td>
                            <select CLASS='TXT_BOX' name="emp_tier">
                                <?php
                                                $qry = "select * from loop_worker_tier";
                                                $res = db_query($qry);
                                                while ($wrow = array_shift($res)) {
                                                ?>
                                <option value="<?php echo $wrow["tier"]; ?>" <?php if ($wrow["tier"] == $emp_tier) {
                                                                                                        echo "selected";
                                                                                                    } ?>>
                                    <?php echo $wrow["tier"] . " (" . $wrow["tier_year"] . ")"; ?></option>
                                <?php
                                                }
                                                ?>
                            </select>
                        </td>
                    </tr>
                    <TR>
                        <TD CLASS='TBL_ROW_HDR'>
                            Title:
                        </TD>
                        <TD>
                            <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='title' value='<?php echo $title; ?>' size='36'>
                        </td>
                        <td>&nbsp;</td>
                        <TD CLASS='TBL_ROW_HDR'>&nbsp;

                        </TD>
                        <TD>&nbsp;

                        </td>
                    </tr>

                    <TR>
                        <TD CLASS='TBL_ROW_HDR'>
                            Work Location:
                        </TD>
                        <TD>
                            <!--<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='location_address' value='<?php //echo $location_address; 
                                                                                                                    ?>' size='36'>-->
                            <select CLASS='TXT_BOX' name="location_address">
                                <option value="" selected>Select Location</option>

                                <option value="Los Angeles Office" <?php if ($location_address == "Los Angeles Office") {
                                                                                        echo "selected";
                                                                                    } ?>>Los Angeles Office</option>
                                <option value="Los Angeles Office (Remote)" <?php if ($location_address == "Los Angeles Office (Remote)") {
                                                                                                echo "selected";
                                                                                            } ?>>Los Angeles Office
                                    (Remote)</option>
                                <option value="Hannibal Office" <?php if ($location_address == "Hannibal Office") {
                                                                                    echo "selected";
                                                                                } ?>>Hannibal Office</option>
                                <option value="Hannibal Office (Remote)" <?php if ($location_address == "Hannibal Office (Remote)") {
                                                                                                echo "selected";
                                                                                            } ?>>Hannibal Office
                                    (Remote)</option>
                                <option value="Hannibal Production" <?php if ($location_address == "Hannibal Production") {
                                                                                        echo "selected";
                                                                                    } ?>>Hannibal Production</option>
                                <option value="Milwaukee Office" <?php if ($location_address == "Milwaukee Office") {
                                                                                        echo "selected";
                                                                                    } ?>>Milwaukee Office</option>
                                <option value="Milwaukee Production" <?php if ($location_address == "Milwaukee Production") {
                                                                                            echo "selected";
                                                                                        } ?>>Milwaukee Production
                                </option>
                                <option value="Hunt Valley Office" <?php if ($location_address == "Hunt Valley Office") {
                                                                                        echo "selected";
                                                                                    } ?>>Hunt Valley Office</option>
                                <option value="Hunt Valley Production" <?php if ($location_address == "Hunt Valley Production") {
                                                                                            echo "selected";
                                                                                        } ?>>Hunt Valley Production
                                </option>
                                <option value="McCormick HVP Onsite" <?php if ($location_address == "McCormick HVP Onsite") {
                                                                                            echo "selected";
                                                                                        } ?>>McCormick HVP Onsite
                                </option>
                            </select>
                        </td>
                        <td>&nbsp;</td>
                        <TD CLASS='TBL_ROW_HDR'>&nbsp;

                        </TD>
                        <TD>&nbsp;

                        </td>
                    </tr>
                    <tr>
                        <TD CLASS='TBL_ROW_HDR'><B>Is Supervisor?</B></TD>
                        <td><input name="is_supervisor" type="checkbox" id="is_supervisor" value="Yes"
                                <?php if ($is_supervisor == "Yes") {
                                                                                                                            echo "checked";
                                                                                                                        } else {
                                                                                                                        } ?>></td>
                        <td>&nbsp;</td>
                        <TD CLASS='TBL_ROW_HDR'><B>Supervisor:</B></TD>
                        <td>
                            <select name="supervisor_name" id="supervisor_name">
                                <option value="">Select</option>
                                <?php
                                                $sup_query = "SELECT id, name, supervisor_name FROM loop_workers WHERE is_supervisor='Yes' UNION ALL SELECT id, name, supervisor_name FROM loop_employees WHERE is_supervisor='Yes' order by name";
                                                db();
                                                $sup_result = db_query($sup_query);
                                                while ($sup_row = array_shift($sup_result)) {
                                                ?>
                                <option value="<?php echo $sup_row["id"]; ?>|lw" <?php if (($sup_row["id"] == $supervisor_name) && ($supervisor_rec_tbl = 'lw')) {
                                                                                                            echo "selected";
                                                                                                        } ?>>
                                    <?php echo $sup_row["name"]; ?></option>
                                <?php
                                                }
                                                ?>

                            </select>
                        </td>
                    </tr>

                    <TR>
                        <TD CLASS='TBL_ROW_HDR'>
                            Personal Address:
                        </TD>
                        <TD>
                            <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='personal_address'
                                value='<?php echo $personal_address; ?>' size='36'>
                        </td>
                        <td>&nbsp;</td>
                        <TD CLASS='TBL_ROW_HDR'>
                            Date of Initial Start:
                        </TD>
                        <TD>
                            <input type="text" name="Date_of_Initial_Start" size="8"
                                value='<?php echo $Date_of_Initial_Start; ?>' readonly>
                            <a href="#"
                                onclick="cal1xx.select(document.editfrm.Date_of_Initial_Start,'dtanchor1xx','yyyy-MM-dd'); return false;"
                                name="dtanchor1xx" id="dtanchor1xx"><img border="0" src="images/calendar.jpg"></a>
                            <div ID="listdiv"
                                STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                            </div>
                        </td>
                    </tr>

                    <TR>
                        <TD CLASS='TBL_ROW_HDR'>
                            Box Buck Code:
                        </TD>
                        <TD>
                            <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='Box_Buck_Code'
                                value='<?php echo $Box_Buck_Code; ?>' size='36'>
                        </td>
                        <td>&nbsp;</td>
                        <TD CLASS='TBL_ROW_HDR'>
                            90-Day Date:
                        </TD>
                        <TD>
                            <input type="text" name="Day_Date_90" size="8" value='<?php echo $Day_Date_90; ?>' readonly>
                            <a href="#"
                                onclick="cal3xx.select(document.editfrm.Day_Date_90,'dtanchor3xx','yyyy-MM-dd'); return false;"
                                name="dtanchor3xx" id="dtanchor3xx"><img border="0" src="images/calendar.jpg"></a>
                            <div ID="listdiv"
                                STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                            </div>
                        </td>
                    </tr>

                    <TR>
                        <TD CLASS='TBL_ROW_HDR'>
                            Personal Email:
                        </TD>
                        <TD>
                            <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='personal_emailadd'
                                value='<?php echo $personal_emailadd; ?>' size='36'>
                        </td>
                        <td>&nbsp;</td>
                        <TD CLASS='TBL_ROW_HDR'>
                            Official Start Date:
                        </TD>
                        <TD>
                            <input type="text" name="Official_Start_Date" size="8"
                                value='<?php echo $Official_Start_Date; ?>' readonly>
                            <a href="#"
                                onclick="cal2xx.select(document.editfrm.Official_Start_Date,'dtanchor2xx','yyyy-MM-dd'); return false;"
                                name="dtanchor2xx" id="dtanchor2xx"><img border="0" src="images/calendar.jpg"></a>
                            <div ID="listdiv"
                                STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <TD CLASS='TBL_ROW_HDR'>
                            Mobile(personal use only/not for customers):
                        </TD>
                        <TD>
                            <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='mobile' value='<?php echo $mobile; ?>' size='36'>
                        </td>
                        <td>&nbsp;</td>
                        <TD CLASS='TBL_ROW_HDR'>
                            Birth Date:
                        </TD>
                        <TD>
                            <input name="birthdate" size="8" value='<?php echo $birthdate; ?>' readonly>
                            <a href="#"
                                onclick="cal4xx.select(document.editfrm.birthdate,'dtanchor4xx','yyyy-MM-dd'); return false;"
                                name="dtanchor4xx" id="dtanchor4xx"><img border="0" src="images/calendar.jpg"></a>
                            <div ID="listdiv"
                                STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                            </div>
                        </td>
                    </tr>

                    <TR>
                        <TD CLASS='TBL_ROW_HDR'>
                            Forklift Certification Info (dates, etc.):
                        </TD>
                        <TD>
                            <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='Forklift_Certification_Info'
                                value='<?php echo $Forklift_Certification_Info; ?>' size='36'>
                        </td>
                        <td>&nbsp;</td>
                        <TD CLASS='TBL_ROW_HDR'>
                            Additional Notes:
                        </TD>
                        <TD>
                            <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='Additional_Notes'
                                value='<?php echo $Additional_Notes; ?>' size='36'>
                        </td>
                    </tr>

                    <tr>

                        <TD CLASS='TBL_ROW_HDR'>
                            Referred By:
                        </TD>
                        <TD>
                            <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='Referred_By' value='<?php echo $Referred_By; ?>'
                                size='36'>
                        </td>
                        <td>&nbsp;</td>
                        <TD CLASS='TBL_ROW_HDR'>
                            Headshot:
                        </TD>
                        <TD>
                            <INPUT TYPE='file' CLASS='TXT_BOX' name='Headshot'>
                            <?php if ($Headshot != '') {
                                            ?>
                            <br>
                            <img src="emp_images/<?php echo $Headshot; ?>" width="100" height="100" align="middle"
                                style="object-fit: cover;">
                            <?php } ?>

                        </td>

                    </tr>

                    <tr>
                        <TD CLASS='TBL_ROW_HDR'>
                            Pay Rate:
                        </TD>
                        <TD>
                            <INPUT onkeypress="return numbersonly(event)" TYPE='TEXT' CLASS='TXT_BOX' name='rate_cost'
                                value='<?php echo $rate_cost; ?>' size='20'>
                        </td>
                        <td>&nbsp;</td>
                        <TD CLASS='TBL_ROW_HDR'>
                            Bill Rate:
                        </TD>
                        <TD>
                            <INPUT onkeypress="return numbersonly(event)" TYPE='TEXT' CLASS='TXT_BOX' name='bill_rate'
                                value='<?php echo $bill_rate; ?>' size='20'>
                        </td>
                    </tr>


                    <TR>
                        <TD CLASS='TBL_ROW_HDR'>
                            Employee Type:
                        </TD>
                        <TD>
                            <select name="employee_type" CLASS='TXT_BOX' size="1">
                                <option value="UCB Employee" <?php if ($employee_type == "UCB Employee") {
                                                                                    echo " selected ";
                                                                                } ?>>UCB Employee</option>
                                <option value="Temp" <?php if ($employee_type == "Temp") {
                                                                            echo " selected ";
                                                                        } ?>>Temp</option>
                            </select>
                        </td>
                        <td>&nbsp;</td>
                        <TD CLASS='TBL_ROW_HDR'>
                            Clock In/Out Pin:
                        </TD>
                        <TD>
                            <INPUT onkeypress="return numbersonly(event)" TYPE='password' CLASS='TXT_BOX'
                                name='user_pwd' value='<?php echo $user_pwd; ?>' size='20'>
                        </td>
                    </tr>

                    <TR>
                        <TD CLASS='TBL_ROW_HDR'>
                            Status:
                        </TD>
                        <TD colspan="4">
                            <select name="status" CLASS='TXT_BOX' size="1">
                                <option value="1" <?php if ($status == "1") {
                                                                        echo " selected ";
                                                                    } ?>>Active</option>
                                <option value="0" <?php if ($status == "0") {
                                                                        echo " selected ";
                                                                    } ?>>Inactive</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            <font color=red>*</font>
                            <font size=1>= Required on the page.</font>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td colspan="4">
                            <input type="hidden" value="<?php echo isset($id); ?>" name="id">
                            <input type="hidden" value="<?php echo $warehouse_id; ?>" name="warehouse_id">
                            <input CLASS="BUTTON_save" type="submit" value="Save" name="submit">
                        </td>
                    </tr>
                </table>
                <center>
                    <BR>
            </form>
            <?php

                        } //END IF RESULTS
                    } //END IF POST IS "" (THIS IS THE END OF EDITING A RECORD)
                    //***** END EDIT FORM*****
                } //END PROC == EDIT

                /*-------------------------------------------------------------------------------
END EDIT RECORD SECTION 9993
-------------------------------------------------------------------------------*/
            } // END IF PROC = "EDIT"
            ?>

            <?php


            if (isset($proc) == "Delete") {
                echo "<a href=\"loop_worker.php?posting=yes&warehouse_id=" . $warehouse_id . "\">Back</a><br><br>";
            ?>
            <!--<a href="<?php echo $thispage; ?>?proc=&<?php echo $pagevars; ?>">Search</a><br>-->
            <?php

                if ($allowaddnew == "yes") {
                ?>

            <!--<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>-->
            <?php } //END OF IF ALLOW ADDNEW 

                /*----------------------------------------------------------------------
DELETE RECORD SECTION - THIS SECTION WILL CONFIRM/PERFORM DELETIONS
----------------------------------------------------------------------*/

                /*-------------------------------------------------------------------------------
DELETE RECORD SECTION 9995
-------------------------------------------------------------------------------*/
                ?>
            <DIV CLASS='PAGE_STATUS'>Mark as In Active</DIV>
            <?php
                /*-- SECTION: 9995CONFIRM --*/
                if (!isset($delete)) {
                ?>
            <DIV CLASS='PAGE_OPTIONS'>
                Are you sure you want to mark as In Active?<BR>
                <a
                    href="<?php echo $thispage; ?>?id=<?php echo isset($id); ?>&warehouse_id=<?php echo $warehouse_id; ?>&delete=yes&proc=Delete&<?php echo $pagevars; ?>">Yes</a>
                <a href="<?php echo $thispage; ?>?<?php /* echo $pagevars; */ ?>posting=yes">No</a>
            </DIV>
            <?php
                } //IF !DELETE

                if (isset($delete) == "yes") {

                    /*-- SECTION: 9995SQL --*/
                    /*$sql = "SELECT * FROM loop_employees where id = " . $id . " ";
	$result = db_query($sql,db() );
	$b2b_empid = 0;
	while ($myrowsel = array_shift($result)) {
		$b2b_empid = $myrowsel["b2b_id"];
	}*/
                    $id = $id ?? "";
                    $sql = "Update loop_workers set `active` = 0 WHERE id='$id'";
                    //if ($sql_debug_mode==1) { echo "<BR>SQL: $sql<BR>"; }
                    db();
                    $result = db_query($sql);
                    if (empty($result)) {
                        /*if ($b2b_empid > 0)
		{
			$sql = "DELETE FROM loop_dashboards WHERE employee_id= " . b2b_empid;
			$result = db_query($sql, db() );		

			$sql = "DELETE FROM employees WHERE employeeID= " . b2b_empid;
			$result = db_query($sql, db_b2b() );		
		}*/
                        echo "<DIV CLASS='SQL_RESULTS'>Successfully In-Activated</DIV>";
                        //header('Location: employee.php?posting=yes');		

                        echo "<script type=\"text/javascript\">";
                        echo "window.location.href=\"loop_worker.php?posting=yes&warehouse_id=" . $warehouse_id . "\";";
                        echo "</script>";
                        echo "<noscript>";
                        echo "<meta http-equiv=\"refresh\" content=\"0;url=loop_worker.php?posting=yes&warehouse_id=" . $warehouse_id . "\" />";
                        echo "</noscript>";
                        exit;
                    } else {
                        echo "Error Deleting Record (9995SQL)";
                    }
                } //END IF $DELETE=YES
                /*-------------------------------------------------------------------------------
END DELETE RECORD SECTION 9995
-------------------------------------------------------------------------------*/
            } // END IF PROC = "DELETE"




            ?>
            <BR>

            <BR>
            </Font>

    </div>

</body>

</html>