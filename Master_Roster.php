<?php
ini_set("display_errors", "1");

error_reporting(E_ERROR);

?>

<html>

<head>
    <title>Master Roster</title>
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap"
        rel="stylesheet">
    <style>
    table.tablestyle {
        border-collapse: collapse;
    }

    table.tablestyle th,
    table.tablestyle td {
        text-align: left;
        padding: 8px;
        border: 1px solid #FFFFFF;
        font-size: 11px;
    }

    .tablestyle td.namecol {
        white-space: nowrap;
    }

    .img-s {
        vertical-align: middle;
        margin-right: 8px;
    }

    .tablestyle tr:nth-child(odd) {
        background-color: gainsboro;
    }

    .nowrap_style {
        white-space: nowrap;
    }
    </style>
    <link rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>
</head>

<body>
    <?php

    require "../mainfunctions/database.php";
    require "../mainfunctions/general-functions.php";
    //
    include("inc/header.php"); ?>

    <div class="main_data_css">
        <div class="dashboard_heading" style="float: left;">
            <div style="float: left;">
                Master Roster Report
                <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                    <span class="tooltiptext">
                        This report shows the user all active employees and contractors at UCB.
                    </span>
                </div>
                <div style="height: 13px;">&nbsp;</div>
            </div>
        </div>
        <?php

        $sort_order_pre = "ASC";
        /*if($_GET['sort_order_pre'] == "ASC")
	{
		$sort_order_pre = "DESC";
	}else{
		$sort_order_pre = "ASC";
	}*/

        $eid = $_COOKIE['b2b_id']; // this is the b2b.employees ID number, not the loop_employees ID number
        if ($eid == 0) {
            $eid = 36;
        }

        $sql = "SELECT * from loop_employees WHERE  emp_id > 0 and b2b_id = " . $eid;
        db();
        $viewres = db_query($sql);
        $row = array_shift($viewres);
        $issuperuser = "no";
        $edit_str = "";
        if ($row['level']  == 2) {
            $issuperuser = "yes";
            $edit_str = "<TD><DIV CLASS='TBL_COL_HDR'>Edit details</DIV></TD>";
        }
        //


        /*while($srow=array_shift($result1))
{
	echo $srow["name"];
	$sql2="select * from loop_employees where emp_id > 0 and supervisor_name=".$srow["id"];
	$result2 = db_query($sql2,db());
	while($srow1=array_shift($result2))
	{
		echo $srow1["name"]."<br>";
	}
}
*/
        //
        $sql = "select * from loop_employees where emp_id > 0";
        db();
        $result = db_query($sql);

        while ($myrowsel = array_shift($result)) {

            if ($myrowsel["Official_Start_Date"] == "") {
                $Official_Start_Date = 'NULL';
            } else {
                $Official_Start_Date = ("'" . $myrowsel["Official_Start_Date"] . "'");
            }

            if ($myrowsel["birthdate"] == "") {
                $birthdate = 'NULL';
            } else {
                $birthdate = ("'" . $myrowsel["birthdate"] . "'");
            }


            $sql1 = "SELECT mobile FROM employees WHERE employeeID =" . $myrowsel["b2b_id"];
            //echo $sql1."<br>" ;
            $mobile = "";
            db_b2b();
            $result1 = db_query($sql1);
            while ($myrow = array_shift($result1)) {
                $mobile = $myrow["mobile"];
            }

            $sql_loop = "INSERT IGNORE INTO master_roster (uid, type, employee_name, title, initials, company_name, Reports_To, phoneext, skype_username, Skype_Number, Direct_Line, email, Official_Start_Date, personal_emailadd, mobile, worker_name, employee_type, birthdate, empid, warehouse_id, status, Box_Buck_Code, Headshot, is_supervisor, supervisor_name, supervisor_rec_tbl)
                VALUES (" . $myrowsel['id'] . ", 'Loop employee', '" . $myrowsel['name'] . "', '" . $myrowsel['title'] . "', '" . $myrowsel['initials'] . "', '" . $myrowsel['location_address'] . "', '" . $myrowsel['Reports_To'] . "', 
                '" . $myrowsel['phoneext'] . "', '" . $myrowsel['skype_username'] . "',
                '" . $myrowsel['Skype_Number'] . "', '" . $myrowsel['direct'] . "', '" . $myrowsel['email'] . "', " . $Official_Start_Date . ", '" . $myrowsel['personal_emailadd'] . "',
                '" . $mobile . "', '" . $myrowsel['worker_name'] . "', '" . $myrowsel['Temp_Agency'] . "',
                " . $birthdate . ", " . $myrowsel['id'] . ", 0, '" . $myrowsel['status'] . "', '" . $myrowsel['Box_Buck_Code'] . "', '" . $myrowsel['Headshot'] . "', '" . $myrowsel['is_supervisor'] . "', '" . $myrowsel['supervisor_name'] . "', '" . $myrowsel['supervisor_rec_tbl'] . "')";

            db();
            $result_loop = db_query($sql_loop);

            //echo $sql_loop ."<br><br>";
        }



        //first table loop worker
        $qry = "select loop_workers.*,loop_warehouse.company_name from loop_workers inner join loop_warehouse on loop_warehouse.id = loop_workers.warehouse_id where loop_workers.emp_id > 0";
        //echo $qry;
        db();
        $res = db_query($qry);

        while ($myrow = array_shift($res)) {

            if ($myrow["Official_Start_Date"] == "") {
                $Official_Start_Date = 'NULL';
            } else {
                $Official_Start_Date = ("'" . $myrow["Official_Start_Date"] . "'");
            }

            if ($myrow["birthdate"] == "") {
                $birthdate = 'NULL';
            } else {
                $birthdate = ("'" . $myrow["birthdate"] . "'");
            }

            //echo $myrow['name']."<br>";
            $sql_wrk = "insert into master_roster (type,employee_name,title,initials,company_name,Reports_To,phoneext,skype_username,
		Skype_Number,Direct_Line,email,Official_Start_Date,personal_emailadd,mobile,worker_name,employee_type,birthdate,empid, warehouse_id,status,Box_Buck_Code, Headshot, is_supervisor, supervisor_name, supervisor_rec_tbl)
		values('Loop worker','" . $myrow['name'] . "','" . $myrow['title'] . "', '-', '" . $myrow['company_name'] . "', '" . $myrow['Reports_To'] . "', 
		'" . $myrow['phoneext'] . "', '" . $myrow['Skype_Username'] . "'
		, '" . $myrow['Skype_Number'] . "', '-', '" . $myrow['email'] . "', " . $Official_Start_Date . ", '" . $myrow['personal_emailadd'] . "'
		, '" . $myrow["mobile"] . "', '" . $myrow['worker_name'] . "', '" . $myrow['employee_type'] . "'
		, " . $birthdate . ", " . $myrow['id'] . ", " . $myrow['warehouse_id'] . ",'" . $myrow['active'] . "','" . $myrow['Box_Buck_Code'] . "', '" . $myrow['Headshot'] . "', '" . $myrow['is_supervisor'] . "', '" . $myrow['supervisor_name'] . "', '" . $myrow['supervisor_rec_tbl'] . "')";

            db();
            $result_wrk = db_query($sql_wrk);
            //echo $sql_wrk ."<br><br>";
        }

        if (isset($_REQUEST["sorting"]) == 'yes') {
            $sql_main = "select type,company_name,initials,employee_name,title,Reports_To,employee_type,phoneext,skype_username,Skype_Number,email,personal_emailadd,mobile,Direct_Line,";
            $sql_main .= "Official_Start_Date,Box_Buck_Code,birthdate, empid, Headshot from master_roster where (status = 'Active' or status = '1') order by " . $_REQUEST["sort"] . " " . $_REQUEST["sort_order_pre"] . "";
        } else {

            $sql_main = "select type,company_name,initials,employee_name,title,Reports_To,employee_type,phoneext,skype_username,Skype_Number,email,personal_emailadd,mobile,";
            $sql_main .= "Official_Start_Date,Box_Buck_Code,birthdate, empid, Direct_Line,Headshot from master_roster where (status = 'Active' or status = '1') order by company_name, employee_name";
        }

        if ($_REQUEST['posting'] == "active_inactive") {
            if (isset($_REQUEST["sorting"]) == 'yes') {
                $sql_main = "select type,company_name,initials,employee_name,title,Reports_To,employee_type,phoneext,skype_username,Skype_Number,email,personal_emailadd,mobile,Direct_Line,";
                $sql_main .= "Official_Start_Date,Box_Buck_Code,birthdate, empid, Headshot from master_roster order by " . $_REQUEST["sort"] . " " . $_REQUEST["sort_order_pre"] . "";
            } else {
                $sql_main = "select type,company_name,initials,employee_name,title,Reports_To,employee_type,phoneext,skype_username,Skype_Number,email,personal_emailadd,mobile,Direct_Line,";
                $sql_main .= "Official_Start_Date, Box_Buck_Code, birthdate, empid, Headshot from master_roster order by employee_name";
            }
        }

        //echo $sql_main;
        db();
        $result_main = db_query($sql_main);
        $numrows = tep_db_num_rows($result_main);
        //
        ?>
        <table border=1 class="tablestyle">
            <LINK rel='stylesheet' type='text/css' href='one_style.css'>
            <?php if ($_REQUEST['posting'] == "yes") { ?>
            <a href="<?php echo isset($thispage); ?>?posting=active_inactive">View all Active and Inactive
                Records</a><br>
            <?php } ?>
            <?php if ((isset($_REQUEST['sorting'])) && ($_REQUEST['sorting'] == "yes")) { ?>
            <a href="Master_Roster.php">Default View</a><br>
            <?php } ?>
            <?php if ($_REQUEST['posting'] == "active_inactive") { ?>
            <a href="<?php echo isset($thispage); ?>?posting=yes">Back</a><br>
            <?php } ?>
            <br>

            <?php
            if ($_REQUEST['posting'] == "active_inactive") {
            ?>
            <tr align='middle'>

                <td colspan='16' align="center" class='style24' style='height: 16px; text-align: center;'>
                    <strong>Master Roster Report (All <?php echo $numrows; ?> Active and Inactive Records)<strong>
                </td>
            </tr>

            <tr CLASS='TBL_COL_HDR'>
                <?php
                    if ((isset($_REQUEST["sorting"])) && ($_REQUEST["sorting"] == 'yes')) {
                    ?>
                <TD>
                    <DIV CLASS='TBL_COL_HDR'>Photo</DIV>
                </TD>
                <?php
                    }
                    ?>
                <TD>
                    <DIV CLASS='TBL_COL_HDR'>
                        #
                    </DIV>
                </TD>
                <TD>
                    <DIV CLASS='TBL_COL_HDR'>
                        Name
                        <a
                            href="Master_Roster.php?posting=active_inactive&sorting=yes&sort=employee_name&sort_order_pre=ASC"><img
                                src='images/sort_asc.png' width='6px;' height='12px;'></a>
                        <a
                            href="Master_Roster.php?posting=active_inactive&sorting=yes&sort=employee_name&sort_order_pre=DESC"><img
                                src='images/sort_desc.png' width='6px;' height='12px;'></a>
                    </DIV>
                </TD>
                <TD>
                    <DIV CLASS='TBL_COL_HDR'>
                        Initials
                        <a
                            href="Master_Roster.php?posting=active_inactive&sorting=yes&sort=initials&sort_order_pre=ASC"><img
                                src='images/sort_asc.png' width='6px;' height='12px;'>
                        </a>
                        <a
                            href="Master_Roster.php?posting=active_inactive&sorting=yes&sort=initials&sort_order_pre=DESC"><img
                                src='images/sort_desc.png' width='6px;' height='12px;'>
                        </a>
                    </DIV>
                </TD>
                <TD>
                    <DIV CLASS='TBL_COL_HDR'>
                        Title
                        <a href="Master_Roster.php?posting=active_inactive&sorting=yes&sort=title&sort_order_pre=ASC"><img
                                src='images/sort_asc.png' width='6px;' height='12px;'>
                        </a>
                        <a href="Master_Roster.php?posting=active_inactive&sorting=yes&sort=title&sort_order_pre=DESC"><img
                                src='images/sort_desc.png' width='6px;' height='12px;'>
                        </a>
                    </DIV>
                </TD>
                <TD>
                    <DIV CLASS='TBL_COL_HDR'>
                        Work Location
                        <a
                            href="Master_Roster.php?posting=active_inactive&sorting=yes&sort=company_name&sort_order_pre=ASC"><img
                                src='images/sort_asc.png' width='6px;' height='12px;'></a>
                        <a
                            href="Master_Roster.php?posting=active_inactive&sorting=yes&sort=company_name&sort_order_pre=DESC"><img
                                src='images/sort_desc.png' width='6px;' height='12px;'></a>
                    </DIV>
                </TD>

                <TD>
                    <DIV CLASS='TBL_COL_HDR'>Extension
                        <a
                            href="Master_Roster.php?posting=active_inactive&sorting=yes&sort=phoneext&sort_order_pre=ASC"><img
                                src='images/sort_asc.png' width='6px;' height='12px;'></a>
                        <a
                            href="Master_Roster.php?posting=active_inactive&sorting=yes&sort=phoneext&sort_order_pre=DESC"><img
                                src='images/sort_desc.png' width='6px;' height='12px;'></a>
                    </DIV>
                </TD>
                <!--<TD><DIV CLASS='TBL_COL_HDR'>
	Skype Username
	<a href="Master_Roster.php?posting=active_inactive&sorting=yes&sort=skype_username&sort_order_pre=ASC"><img src='images/sort_asc.png' width='6px;' height='12px;'></a>
	<a href="Master_Roster.php?posting=active_inactive&sorting=yes&sort=skype_username&sort_order_pre=DESC"><img src='images/sort_desc.png' width='6px;' height='12px;'></a>
	</DIV>
</TD>-->
                <TD>
                    <DIV CLASS='TBL_COL_HDR'>
                        Teams Direct Number
                        <a
                            href="Master_Roster.php?posting=active_inactive&sorting=yes&sort=Direct_Line&sort_order_pre=ASC"><img
                                src='images/sort_asc.png' width='6px;' height='12px;'></a>
                        <a
                            href="Master_Roster.php?posting=active_inactive&sorting=yes&sort=Direct_Line&sort_order_pre=DESC"><img
                                src='images/sort_desc.png' width='6px;' height='12px;'></a>
                    </DIV>
                </TD>
                <TD>
                    <DIV CLASS='TBL_COL_HDR'>
                        UCB Email Address
                        <a href="Master_Roster.php?posting=active_inactive&sorting=yes&sort=email&sort_order_pre=ASC"><img
                                src='images/sort_asc.png' width='6px;' height='12px;'></a>
                        <a href="Master_Roster.php?posting=active_inactive&sorting=yes&sort=email&sort_order_pre=DESC"><img
                                src='images/sort_desc.png' width='6px;' height='12px;'></a>
                    </DIV>
                </TD>
                <TD>
                    <DIV CLASS='TBL_COL_HDR'>
                        Personal Email
                        <a
                            href="Master_Roster.php?posting=active_inactive&sorting=yes&sort=personal_emailadd&sort_order_pre=ASC"><img
                                src='images/sort_asc.png' width='6px;' height='12px;'></a>
                        <a href="Master_Roster.php?posting=active_inactive&sorting=yes&sort=mobile&sort_order_pre=DESC"><img
                                src='images/sort_desc.png' width='6px;' height='12px;'></a>
                    </DIV>
                </TD>
                <TD>
                    <DIV CLASS='TBL_COL_HDR'>
                        Mobile Number
                        <a href="Master_Roster.php?posting=active_inactive&sorting=yes&sort=mobile&sort_order_pre=ASC"><img
                                src='images/sort_asc.png' width='6px;' height='12px;'></a>
                        <a href="Master_Roster.php?posting=active_inactive&sorting=yes&sort=mobile&sort_order_pre=DESC"><img
                                src='images/sort_desc.png' width='6px;' height='12px;'></a>
                    </DIV>
                </TD>
                <TD>
                    <DIV CLASS='TBL_COL_HDR'>
                        First Official Day
                        <a
                            href="Master_Roster.php?posting=active_inactive&sorting=yes&sort=Official_Start_Date&sort_order_pre=ASC"><img
                                src='images/sort_asc.png' width='6px;' height='12px;'></a>
                        <a
                            href="Master_Roster.php?posting=active_inactive&sorting=yes&sort=Official_Start_Date&sort_order_pre=DESC"><img
                                src='images/sort_desc.png' width='6px;' height='12px;'></a>
                    </DIV>
                </TD>
                <TD>
                    <DIV CLASS='TBL_COL_HDR'>
                        Box Buck Code
                        <a
                            href="Master_Roster.php?posting=active_inactive&sorting=yes&sort=Box_Buck_Code&sort_order_pre=ASC"><img
                                src='images/sort_asc.png' width='6px;' height='12px;'></a>
                        <a
                            href="Master_Roster.php?posting=active_inactive&sorting=yes&sort=Box_Buck_Code&sort_order_pre=DESC"><img
                                src='images/sort_desc.png' width='6px;' height='12px;'></a>
                    </DIV>
                </TD>
                <!-- <TD>
	<DIV CLASS='TBL_COL_HDR'>
		Birth Date
		<a href="Master_Roster.php?posting=active_inactive&sorting=yes&sort=birthdate&sort_order_pre=ASC"><img src='images/sort_asc.png' width='6px;' height='12px;'></a>
		<a href="Master_Roster.php?posting=active_inactive&sorting=yes&sort=birthdate&sort_order_pre=DESC"><img src='images/sort_desc.png' width='6px;' height='12px;'></a>
	</DIV>
</TD> -->
                <?php echo $edit_str; ?>
            </tr>
            <?php } else { ?>
            <tr align='middle'>
                <td colspan='16' align="center" class='style24' style='height: 16px; text-align: center;'>
                    <strong>Master Roster Report (All <?php echo $numrows; ?> Active Records)</strong>
                </td>
            </tr>

            <tr class="TBL_COL_HDR nowrap_style">
                <?php
                    if ((isset($_REQUEST["sorting"])) && ($_REQUEST["sorting"] == 'yes')) {
                    ?>
                <TD>
                    <DIV CLASS='TBL_COL_HDR'>Photo</DIV>
                </TD>
                <?php
                    }
                    ?>
                <TD>
                    <DIV CLASS='TBL_COL_HDR'>
                        #
                    </DIV>
                </TD>
                <TD>
                    <DIV CLASS='TBL_COL_HDR'>
                        Name
                        <a href="Master_Roster.php?sorting=yes&sort=employee_name&sort_order_pre=ASC"><img
                                src='images/sort_asc.png' width='6px;' height='12px;'></a>
                        <a href="Master_Roster.php?sorting=yes&sort=employee_name&sort_order_pre=DESC"><img
                                src='images/sort_desc.png' width='6px;' height='12px;'></a>
                    </DIV>
                </TD>
                <TD>
                    <DIV CLASS='TBL_COL_HDR'>
                        Initials
                        <a href="Master_Roster.php?sorting=yes&sort=initials&sort_order_pre=ASC"><img
                                src='images/sort_asc.png' width='6px;' height='12px;'>
                        </a>
                        <a href="Master_Roster.php?sorting=yes&sort=initials&sort_order_pre=DESC"><img
                                src='images/sort_desc.png' width='6px;' height='12px;'>
                        </a>
                        </a>
                    </DIV>
                </TD>
                <TD>
                    Title
                    <a href="Master_Roster.php?sorting=yes&sort=title&sort_order_pre=ASC"><img src='images/sort_asc.png'
                            width='6px;' height='12px;'>
                    </a>
                    <a href="Master_Roster.php?sorting=yes&sort=title&sort_order_pre=DESC"><img
                            src='images/sort_desc.png' width='6px;' height='12px;'>
                    </a>

                </TD>
                <TD>
                    <DIV CLASS='TBL_COL_HDR'>
                        Work Location
                        <a href="Master_Roster.php?sorting=yes&sort=company_name&sort_order_pre=ASC"><img
                                src='images/sort_asc.png' width='6px;' height='12px;'></a>
                        <a href="Master_Roster.php?sorting=yes&sort=company_name&sort_order_pre=DESC"><img
                                src='images/sort_desc.png' width='6px;' height='12px;'></a>
                    </DIV>
                </TD>

                <TD>
                    <DIV CLASS='TBL_COL_HDR'>Extension
                        <a href="Master_Roster.php?sorting=yes&sort=phoneext&sort_order_pre=ASC"><img
                                src='images/sort_asc.png' width='6px;' height='12px;'></a>
                        <a href="Master_Roster.php?sorting=yes&sort=phoneext&sort_order_pre=DESC"><img
                                src='images/sort_desc.png' width='6px;' height='12px;'></a>
                    </DIV>
                </TD>
                <!--<TD><DIV CLASS='TBL_COL_HDR'>
	Skype Username
	<a href="Master_Roster.php?sorting=yes&sort=skype_username&sort_order_pre=ASC"><img src='images/sort_asc.png' width='6px;' height='12px;'></a>
	<a href="Master_Roster.php?sorting=yes&sort=skype_username&sort_order_pre=DESC"><img src='images/sort_desc.png' width='6px;' height='12px;'></a>
	</DIV>
</TD>-->
                <TD>
                    <DIV CLASS='TBL_COL_HDR'>
                        Teams Direct Number
                        <a href="Master_Roster.php?sorting=yes&sort=Direct_Line&sort_order_pre=ASC"><img
                                src='images/sort_asc.png' width='6px;' height='12px;'></a>
                        <a href="Master_Roster.php?sorting=yes&sort=Direct_Line&sort_order_pre=DESC"><img
                                src='images/sort_desc.png' width='6px;' height='12px;'></a>
                    </DIV>
                </TD>
                <TD>
                    <DIV CLASS='TBL_COL_HDR'>
                        UCB Email Address
                        <a href="Master_Roster.php?sorting=yes&sort=email&sort_order_pre=ASC"><img
                                src='images/sort_asc.png' width='6px;' height='12px;'></a>
                        <a href="Master_Roster.php?sorting=yes&sort=email&sort_order_pre=DESC"><img
                                src='images/sort_desc.png' width='6px;' height='12px;'></a>
                    </DIV>
                </TD>
                <TD>
                    <DIV CLASS='TBL_COL_HDR'>
                        Personal Email
                        <a href="Master_Roster.php?sorting=yes&sort=personal_emailadd&sort_order_pre=ASC"><img
                                src='images/sort_asc.png' width='6px;' height='12px;'></a>
                        <a href="Master_Roster.php?sorting=yes&sort=mobile&sort_order_pre=DESC"><img
                                src='images/sort_desc.png' width='6px;' height='12px;'></a>
                    </DIV>
                </TD>
                <TD>
                    <DIV CLASS='TBL_COL_HDR'>
                        Mobile Number
                        <a href="Master_Roster.php?sorting=yes&sort=mobile&sort_order_pre=ASC"><img
                                src='images/sort_asc.png' width='6px;' height='12px;'></a>
                        <a href="Master_Roster.php?sorting=yes&sort=mobile&sort_order_pre=DESC"><img
                                src='images/sort_desc.png' width='6px;' height='12px;'></a>
                    </DIV>
                </TD>
                <TD>
                    <DIV CLASS='TBL_COL_HDR'>
                        First Official Day
                        <a href="Master_Roster.php?sorting=yes&sort=Official_Start_Date&sort_order_pre=ASC"><img
                                src='images/sort_asc.png' width='6px;' height='12px;'></a>
                        <a href="Master_Roster.php?sorting=yes&sort=Official_Start_Date&sort_order_pre=DESC"><img
                                src='images/sort_desc.png' width='6px;' height='12px;'></a>
                    </DIV>
                </TD>
                <TD>
                    <DIV CLASS='TBL_COL_HDR'>
                        Box Buck Code
                        <a href="Master_Roster.php?sorting=yes&sort=Box_Buck_Code&sort_order_pre=ASC"><img
                                src='images/sort_asc.png' width='6px;' height='12px;'></a>
                        <a href="Master_Roster.php?sorting=yes&sort=Box_Buck_Code&sort_order_pre=DESC"><img
                                src='images/sort_desc.png' width='6px;' height='12px;'></a>
                    </DIV>
                </TD>
                <!-- <TD>
	<DIV CLASS='TBL_COL_HDR'>
		Birth Date
		<a href="Master_Roster.php?sorting=yes&sort=birthdate&sort_order_pre=ASC"><img src='images/sort_asc.png' width='6px;' height='12px;'></a>
		<a href="Master_Roster.php?sorting=yes&sort=birthdate&sort_order_pre=DESC"><img src='images/sort_desc.png' width='6px;' height='12px;'></a>
	</DIV>
</TD> -->
                <?php echo $edit_str; ?>
            </tr>

            <?php

            }
            if (!isset($_REQUEST["sorting"])) {
                // $tyqry=db_query("select DISTINCT type from master_roster", db());
                //while($tyrow=array_shift($tyqry))
                //{ 
                $showrec = "";
                categoryTree(0, '', '', $showrec);
                //}

            } //end if not sort

            //
            $emp_count = 0;
            function categoryTree(int $parent_id, string $supervisor_rec_tbl, string $type, string $sub_mark): void
            {
                //
                global $emp_count, $last_parent_rec;

                $eid = $_COOKIE['b2b_id']; // this is the b2b.employees ID number, not the loop_employees ID number
                if ($eid == 0) {
                    $eid = 36;
                }

                $sql = "SELECT * from loop_employees WHERE  emp_id > 0 and b2b_id = " . $eid;
                db();
                $viewres = db_query($sql);
                $row = array_shift($viewres);
                $issuperuser = "no";
                $edit_str = "";
                if ($row['level']  == 2) {
                    $issuperuser = "yes";
                    $edit_str = "<TD><DIV CLASS='TBL_COL_HDR'>Edit details</DIV></TD>";
                }
                //

                if ($_REQUEST['posting'] == "active_inactive") {
                    //or supervisor_rec_tbl = '" . $supervisor_rec_tbl . "')
                    // and type='".$type."'
                    $sql1 = "select * from master_roster where supervisor_name=" . $parent_id . "  order by type";
                    //echo $sql1."<br>";
                } else {
                    //if ($supervisor_rec_tbl != "") {
                    //$sql1="select * from master_roster where (status = 'Active' or status = '1') and supervisor_name=".$parent_id." and supervisor_rec_tbl = '" . $supervisor_rec_tbl . "' order by type";  
                    //and type='".$type."'
                    $sql1 = "select * from master_roster where (status = 'Active' or status = '1') and supervisor_name=" . $parent_id . "  order by type";
                }
                //echo $sql1."<br>";
                //
                db();
                $result1 = db_query($sql1);
                if (tep_db_num_rows($result1) > 0) {
                    while ($srow = array_shift($result1)) {
                        $emp_count = $emp_count + 1;
                        echo "<tr>";
                        echo "<td class='namecol'>" . $emp_count . "</td>";
                        echo "<td class='namecol'>";
                        $imgs = '<img src="images/no_image.jpg" width="40px" height="40px" class="img-s" />';
                        if ($srow['Headshot'] != "") {
                            $emp_img = "images/employees/" . $srow['Headshot'];
                            $loop_worker_emp_img = "emp_images/" . $srow['Headshot'];
                            if (file_exists($emp_img)) {
                                $imgs = "<img src='images/employees/" . $srow['Headshot'] . "' width='40px' height='40px' class='img-s' />";
                            } elseif (file_exists($loop_worker_emp_img)) {
                                $imgs = "<img src='emp_images/" . $srow['Headshot'] . "' width='40px' height='40px' class='img-s' />";
                            }
                        }
                        echo '' . $sub_mark . $imgs . $srow['employee_name'] . '</td>';
                        //}
                        $shade = $shade ?? "";
                ?>

            <td CLASS='<?php echo $shade; ?>' style='text-align: center!important;'>
                <?php echo $srow['initials']; ?>
            </td>
            <td CLASS='<?php echo $shade; ?>'><?php echo $srow['title']; ?></td>
            <td CLASS='<?php echo $shade; ?>'><?php echo $srow['company_name']; ?></td>

            <td CLASS='<?php echo $shade; ?>'><?php echo $srow['phoneext']; ?></td>
            <!--<td CLASS='<?php echo $shade; ?>'><?php echo $srow['skype_username']; ?></td>-->
            <td CLASS='<?php echo $shade; ?>'><?php echo $srow['Direct_Line']; ?></td>
            <td CLASS='<?php echo $shade; ?>'><?php echo $srow['email']; ?></td>
            <td CLASS='<?php echo $shade; ?>'><?php echo $srow['personal_emailadd']; ?></td>
            <td CLASS='<?php echo $shade; ?>'><?php echo $srow['mobile']; ?></td>
            <td CLASS='<?php echo $shade; ?>'><?php echo $srow['Official_Start_Date']; ?></td>
            <td CLASS='<?php echo $shade; ?>'><?php echo $srow['Box_Buck_Code']; ?></td>
            <!-- commented as per tech req #817 <td CLASS='<?php echo $shade; ?>'><?php echo $srow['birthdate']; ?></td> -->
            <?php if ($issuperuser == 'yes') { ?>
            <td CLASS='<?php echo $shade; ?>'><?php
                                                                if ($srow['type'] == 'Loop employee') {
                                                                    echo "<a href='employee.php?id=" . $srow['empid'] . "&proc=Edit&'>Edit Details</a>";
                                                                } else {
                                                                    echo "<a href='loop_worker.php?id=" . $srow['empid'] . "&warehouse_id=" . $srow['warehouse_id'] . "&proc=Edit&'>Edit Details</a>";
                                                                }
                                                                ?></td>

            <?php     } ?>

            <?php
                        echo '</tr>';

                        categoryTree($srow['empid'], $srow['supervisor_rec_tbl'], '', $sub_mark . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
                    }
                }
            }
            //

            if ((isset($_REQUEST["sorting"])) && ($_REQUEST["sorting"] == 'yes')) {


                $emp_cnt = 0;
                if ($_REQUEST['posting'] == "active_inactive") {

                    $sql_main = "select type,company_name, initials, employee_name,title,Reports_To,employee_type,phoneext,skype_username, Direct_Line,email,personal_emailadd,mobile,";
                    $sql_main .= "Official_Start_Date,Box_Buck_Code,birthdate, empid, Headshot from master_roster order by " . $_REQUEST["sort"] . " " . $_REQUEST["sort_order_pre"] . "";
                } else {
                    $sql_main = "select type, company_name, initials, employee_name, title, Reports_To, employee_type, phoneext, skype_username, Skype_Number, Direct_Line, email,personal_emailadd,mobile,";
                    $sql_main .= "Official_Start_Date,Box_Buck_Code,birthdate, empid, Headshot from master_roster where (status = 'Active' or status = '1') order by " . $_REQUEST["sort"] . " " . $_REQUEST["sort_order_pre"] . "";
                }
                db();
                $result_main = db_query($sql_main);
                $numrows = tep_db_num_rows($result_main);
                //
                //
                while ($row = array_shift($result_main)) {
                    $emp_cnt = $emp_cnt + 1;
                    $shade = $shade ?? "";
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

                    ?>
            <tr>
                <td CLASS='<?php echo $shade; ?>'>
                    <?php
                            $imgs = '<img src="images/no_image.jpg" width="40px" height="40px" class="img-s" />';
                            if ($row['Headshot'] != "") {
                                $emp_img = "images/employees/" . $row['Headshot'];
                                $loop_worker_emp_img = "emp_images/" . $row['Headshot'];

                                if (file_exists($emp_img)) {
                                    $imgs = "<img src='images/employees/" . $row['Headshot'] . "' width='40px' height='40px' class='img-s' />";
                                } elseif (file_exists($loop_worker_emp_img)) {
                                    $imgs = "<img src='emp_images/" . $row['Headshot'] . "' width='40px' height='40px' class='img-s' />";
                                }
                            }
                            echo $imgs;
                            ?>
                </td>
                <td CLASS='<?php echo $shade; ?>'>
                    <?php echo $emp_cnt; ?></td>
                <td CLASS='<?php echo $shade; ?>'>
                    <?php echo $row['employee_name']; ?></td>
                <td CLASS='<?php echo $shade; ?>' style='text-align: center!important;'><?php echo $row['initials']; ?>
                </td>
                <td CLASS='<?php echo $shade; ?>'><?php echo $row['title']; ?></td>
                <td CLASS='<?php echo $shade; ?>'><?php echo $row['company_name']; ?></td>
                <!--<td CLASS='<?php //echo $shade; 
                                        ?>'><?php //echo $row['Reports_To'];
                                            ?></td>
<td CLASS='<?php //echo $shade; 
            ?>'><?php //echo $row['employee_type'];
                ?></td>-->
                <td CLASS='<?php echo $shade; ?>'><?php echo $row['phoneext']; ?></td>
                <!--<td CLASS='<?php echo $shade; ?>'><?php echo $row['skype_username']; ?></td>-->
                <td CLASS='<?php echo $shade; ?>'><?php echo $row['Direct_Line']; ?></td>
                <td CLASS='<?php echo $shade; ?>'><?php echo $row['email']; ?></td>
                <td CLASS='<?php echo $shade; ?>'><?php echo $row['personal_emailadd']; ?></td>
                <td CLASS='<?php echo $shade; ?>'><?php echo $row['mobile']; ?></td>
                <td CLASS='<?php echo $shade; ?>'><?php echo $row['Official_Start_Date']; ?></td>
                <td CLASS='<?php echo $shade; ?>'><?php echo $row['Box_Buck_Code']; ?></td>
                <!-- commented as per tech req #817 <td CLASS='<?php echo $shade; ?>'><?php echo $row['birthdate']; ?></td> -->
                <?php if ($issuperuser == 'yes') { ?>

                <td CLASS='<?php echo $shade; ?>'><?php
                                                                if ($row['type'] == 'Loop employee') {
                                                                    echo "<a href='employee.php?id=" . $row['empid'] . "&proc=Edit&'>Edit Details</a>";
                                                                } else {
                                                                    echo "<a href='loop_worker.php?id=" . $row['empid'] . "&warehouse_id=" . $row['warehouse_id'] . "&proc=Edit&'>Edit Details</a>";
                                                                }
                                                                ?></td>
                <?php     } ?>
            </tr>
            <?php

                }
                //
            } //end if sort

            ?>
        </table>
        <?php

        /* $del = "delete FROM master_roster";
$result_del = db_query($del,db());*/

        /* $tree = buildTree($rows); 
   
    
  function buildTree(array $elements, $parentId = 0) {
    $branch = array();

    foreach ($elements as $element) {
        if ($element['parent_id'] == $parentId) {
            $children = buildTree($elements, $element['id']);
            if ($children) {
                $element['children'] = $children;
            }
            $branch[] = $element;
        }
    }

    return $branch;
}

 */
        $del = "delete FROM master_roster";

        db();
        $result_del = db_query($del);
        ?>

        </form>
        <?php //="<br>Total: " . $numrows . " records.";
        ?>
    </div>
</body>

</html>