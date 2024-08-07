<!DOCTYPE HTML>

<html>

<head>
    <title>UCB Loop System - Sorting Warehouse Management</title>
    <script>
        function add_dock_door() {
            var cnt = document.getElementById("dim_cnt").value;
            cnt = Number(cnt) + 1;

            var sstr = "";
            sstr = "<table id='dock_door_tbl" + cnt + "'><tr><td><input type='text' name='dock_doors_names" + cnt +
                "' id='dock_doors_names" + cnt +
                "'></td><td>&nbsp;<input type='button' class='remove_btn' name='btn_remove_s' value='X' onclick='remove_dock_door_nm(" +
                cnt + ")'></td></tr></table>";


            var divctl = document.getElementById("data_row");
            divctl.insertAdjacentHTML('beforeend', sstr);

            document.getElementById("dim_cnt").value = cnt;
        }

        function remove_dock_door_nm(cnt) {
            //alert(cnt);
            document.getElementById("dock_door_tbl" + cnt).innerHTML = "";
        }
    </script>
</head>

<body>



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
    /*------------------------------------------------
END GLOBALS OFF SUPPORT
------------------------------------------------*/

    echo "<span class='font_family_Ariel font_size_1'>";

    /*---------------------------------------------------------------------------------
TURN DEBUG ON - THIS ALLOWS YOU TO VIEW ALL SQL STATEMENTS AS THEY ARE EXECUTED
$sql_debug_mode=0 --> OFF (NO SQL STATEMENTS WILL BE SHOWN)
$sql_debug_mode=1 --> ON (SQL STATEMENTS WILL BE SHOWN)
---------------------------------------------------------------------------------*/
    $sql_debug_mode = 0;



    error_reporting(E_WARNING | E_PARSE);
    $SCRIPT_NAME = "";
    if (isset($_SERVER['SCRIPT_NAME'])) {
        $SCRIPT_NAME = $_SERVER['SCRIPT_NAME'];
    } else {
        $SCRIPT_NAME = $_SERVER['PHP_SELF'];
    }
    //SET THESE VARIABLES TO CUSTOMIZE YOUR PAGE
    $thispage    = $SCRIPT_NAME; //SET THIS TO THE NAME OF THIS FILE
    $pagevars    = ""; //INSERT ANY "GET" VARIABLES HERE...



    /*----------------------------------------------------------------------
NOTE: THE FOLLOWING 4 "allow" VARIABLES SIMPLY DISABLE THE LINKS FOR 
ADD/EDIT/VIEW/DELETE.
----------------------------------------------------------------------*/
    $allowedit        = "yes"; //SET TO "no" IF YOU WANT TO DISABLE EDITING
    $allowaddnew    = "yes"; // SET TO "no" IF YOU WANT TO DISABLE NEW RECORDS
    $allowview        = "no"; //SET TO "no" IF YOU WANT TO DISABLE VIEWING RECORDS
    $allowdelete    = "yes"; //SET TO "no" IF YOU WANT TO DISABLE DELETING RECORDS



    $addl_select_crit = "AND rec_type='Sorting' ORDER BY company_name"; //ADDL CRITERIA FOR SQL STATEMENTS (ADD/UPD/DEL).
    $addl_update_crit = ""; //ADDITIONAL CRITERIA FOR UPDATE STATEMENTS.
    $addl_insert_crit = ""; //ADDITIONAL CRITERIA FOR INSERT STATEMENTS.
    $addl_insert_values = ""; //ADDITIONAL VALUES FOR INSERT STATEMENTS.

    /*----------------------------------------------------------------------
NOTE: SOME SERVERS RUN "MAGIC QUOTES" TO FIX STRINGS WHEN INSERTING/
		UPDATING RECORDS.  IF MAGIC QUOTES OPTION IS DISABLED, THE GENERATED
		CODE WILL AUTOMATICALLY FIX STRINGS TO HELP ELIMINATE ERRORS.
----------------------------------------------------------------------*/
    //if (get_magic_quotes_gpc()) { $addslash="no"; } //AUTO-ADD SLASHES IF MAGIC QUOTES IS OFF
    if (!empty($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) {
        $addslash = "no";
    } elseif (!empty($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false) {
        $addslash = "no";
    } else {
        //$addslash = "yes";
    }

    /*----------------------------------------------------------------------
FUNCTIONS
----------------------------------------------------------------------*/





    require("inc/header_session.php");
    require("../mainfunctions/database.php");
    require_once("../mainfunctions/general-functions.php");



    /*----------------------------------------------------------------------
END FUNCTIONS
----------------------------------------------------------------------*/


    /*----------------------------------------
ADD NEW LINK
----------------------------------------*/

    echo "<a href=\"index.php\">Home</a><br><br>";

    if (isset($proc) == "") {
        if ($allowaddnew == "yes") {
    ?>
            <a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">Add New Sorting Warehouse</a><br>
            <a href="<?php echo $thispage; ?>?posting=active_inactive&rec_type=Sorting">View all Active and Inactive
                Warehouse</a><br>
        <?php  }


        /*---------------------------------------------------------------------------------------
BEGIN SEARCH SECTION 9991
---------------------------------------------------------------------------------------*/
        /*-- SECTION: 9991FORM --*/
        ?>
        <!--	<form method="POST" action="<?php echo $thispage; ?>?posting=yes&<?php echo $pagevars; ?>">
  	<p><B>Search:</B> <input type="text" CLASS='TXT_BOX' name="searchcrit" size="20" value="<?php echo isset($searchcrit); ?>">
  	<INPUT CLASS="BUTTON" TYPE="SUBMIT" VALUE="Search!" NAME="B1"></P>
	</form> -->

        <?php

        /*----------------------------------------------------------------
IF THEY ARE POSTING TO THE SEARCH PAGE 
(SHOW SEARCH RESULTS)
----------------------------------------------------------------*/
        if (isset($posting) == "yes") {


            /*----------------------------------------------------------------
PAGING SETTINGS - THIS IS USED FOR NEXT/PREVIOUS X RECORDS
----------------------------------------------------------------*/
            $pagenorecords = 10;  //THIS IS THE PAGE SIZE
            //IF NO PAGE
            if (isset($page) == 0) {
                $myrecstart = 0;
            } else {
                $myrecstart = (isset($page) * $pagenorecords);
            }


            /*-- SECTION: 9991SQL --*/
            if (isset($searchcrit) == "") {
                $flag = "all";
                $sql = "SELECT * FROM loop_warehouse where Active = 1 ";
                $sqlcount = "select count(*) as reccount from loop_warehouse where Active = 1 ";
            } else {
                //IF THEY TYPED SEARCH WORDS
                $sqlcount = "select count(*) as reccount from loop_warehouse WHERE (";
                $searchcrit = $searchcrit ?? "";

                $sql = "SELECT * FROM loop_warehouse WHERE (";
                $sqlwhere = "

 company_name like '%$searchcrit%' OR 
 company_address1 like '%$searchcrit%' OR 
 company_address2 like '%$searchcrit%' OR 
 company_city like '%$searchcrit%' OR 
 company_state like '%$searchcrit%' OR 
 company_zip like '%$searchcrit%' OR 
 company_phone like '%$searchcrit%' OR 
 company_email like '%$searchcrit%' OR 
 company_terms like '%$searchcrit%' OR 
 company_contact like '%$searchcrit%' OR 
 warehouse_name like '%$searchcrit%' OR 
 warehouse_address1 like '%$searchcrit%' OR 
 warehouse_address2 like '%$searchcrit%' OR 
 warehouse_city like '%$searchcrit%' OR 
 warehouse_state like '%$searchcrit%' OR 
 warehouse_zip like '%$searchcrit%' OR 
 warehouse_contact like '%$searchcrit%' OR 
 warehouse_contact_phone like '%$searchcrit%' OR 
 warehouse_contact_email like '%$searchcrit%' OR 
 warehouse_manager like '%$searchcrit%' OR 
 warehouse_manager_phone like '%$searchcrit%' OR 
 warehouse_manager_email like '%$searchcrit%' OR 
 dock_details like '%$searchcrit%' OR 
 warehouse_notes like '%$searchcrit%' OR 
 other1 like '%$searchcrit%' OR 
 other2 like '%$searchcrit%' OR 
 other3 like '%$searchcrit%' 

		"; //FINISH SQL STRING

                //NOTE:CHECK ABOVE FOR AN EXTRA "OR":
                //IF YOU LEFT THE LAST 
                //FIELD IN THE FIELD NAME ENTRY FORM BLANK, YOU WILL
                //GET AN EXTRA "OR" ABOVE.  SIMPLY DELETE IT.

            } //END IF SEARCHCRIT = "";

            if (isset($flag) == "all") {
                $sql = $sql . " $addl_select_crit ";
                $sqlcount = $sqlcount  . " $addl_select_crit ";
            } else {
                $sqlcount = $sqlcount . isset($sqlwhere) . ") $addl_select_crit ";
                $sql = $sql . isset($sqlwhere) . ") $addl_select_crit ";
            }

            //echo "<BR>SQL: $sql<BR>"; 



            //SET PAGE
            if (isset($page) == 0) {
                $page = 1;
            } else {
                $page = (isset($page) + 1);
            }

            /*----------------------------------------------------------------
FIND OUT HOW MANY RECORDS WE HAVE
----------------------------------------------------------------*/

            if (isset($reccount) == 0) {
                db();
                //$resultcount = (db_query($sqlcount,db() )) OR DIE (ThrowError($err_type,$err_descr););
                db();
                $resultcount = (db_query($sqlcount)) or die(ThrowError("9991SQLresultcount", $sqlcount, 'Production'));
                if ($myrowcount = array_shift($resultcount)) {
                    $reccount = $myrowcount["reccount"];
                } //IF RECCOUNT = 0
            } //end if reccount

            //	echo "<DIV CLASS='NUM_RECS_FOUND'>$reccount Records Found</DIV>";
            //	echo "<DIV CLASS='CURR_PAGE'>Page $page</DIV>";
            echo "<!--<DIV CLASS='CURR_PAGE'>Page $page - $reccount Records Found</DIV>-->";

            /*----------------------------------------------------------------
PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORDS
----------------------------------------------------------------*/
            if (isset($reccount) > 10) {
                $ttlpages = ($reccount / 10);
                if ($page < $ttlpages) {
        ?>

                    <HR> <br>

                <?php
                } //END IF AT LAST PAGE
            } //END IF RECCOUNT > 10

            if ($page > 0) {
                $newpage = $page - 2;
            }
            if (isset($newpage) != -1) {
                ?>
                <br>

                <br>
                <?php

            } //IF NEWPAGE != -1
            /*----------------------------------------------------------------
END PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORDS
----------------------------------------------------------------*/
            //EXECUTE OUR SQL STRING FOR THE TABLE RECORDS
            db();
            $result = db_query($sql);
            if ($sql_debug_mode == 1) {
                echo "<BR>SQL: $sql<BR>";
            }
            if ($myrowsel = array_shift($result)) {
                $id = $myrowsel["id"];

                echo "<br><TABLE style='width:830px'>";
                echo "	<tr class='text_align_center'><td colspan='6' class='style24' style='height: 16px'><strong>SORTING WAREHOUSE SETUP</strong></td></tr>";
                echo "	<TR>";

                echo "		<TD><DIV CLASS='TBL_COL_HDR'>Company Name</DIV></TD>";
                /*
echo "		<TD><DIV CLASS='TBL_COL_HDR'>COMPANY_ADDRESS1</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>COMPANY_ADDRESS2</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>COMPANY_CITY</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>COMPANY_STATE</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>COMPANY_ZIP</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>COMPANY_PHONE</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>COMPANY_EMAIL</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>COMPANY_TERMS</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>COMPANY_CONTACT</DIV></TD>"; 
*/
                echo "		<TD><DIV CLASS='TBL_COL_HDR'>Warehouse Name</DIV></TD>";
                /*
echo "		<TD><DIV CLASS='TBL_COL_HDR'>WAREHOUSE_ADDRESS1</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>WAREHOUSE_ADDRESS2</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>WAREHOUSE_CITY</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>WAREHOUSE_STATE</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>WAREHOUSE_ZIP</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>WAREHOUSE_CONTACT</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>WAREHOUSE_CONTACT_PHONE</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>WAREHOUSE_CONTACT_EMAIL</DIV></TD>"; 
*/
                echo "		<TD><DIV CLASS='TBL_COL_HDR'>Warehouse Contact</DIV></TD>";
                echo "		<TD><DIV CLASS='TBL_COL_HDR'>Warehouse Contact Phone</DIV></TD>";
                echo "		<TD><DIV CLASS='TBL_COL_HDR'>Warehouse Contact Email</DIV></TD>";
                /*
echo "		<TD><DIV CLASS='TBL_COL_HDR'>DOCK_DETAILS</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>WAREHOUSE_NOTES</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>OTHER1</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>OTHER2</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>OTHER3</DIV></TD>"; 
*/
                echo "<TD><DIV CLASS='TBL_COL_HDR'>Options</DIV></TD>";
                echo "\n\n		</TR>";
                do {
                    //FORMAT THE OUTPUT OF THE SEARCH
                    $id = $myrowsel["id"];

                    //SWITCH ROW COLORS
                    switch (isset($shade)) {
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

                    /*----------------------------------------------------------------
VIEW SEARCH RESULTS BY PAGE
----------------------------------------------------------------*/

                    /*---------------------------------------
BEGIN RESULTS TABLE
---------------------------------------*/

                    echo "<TR>";
                ?>

                    <?php $company_name = $myrowsel["company_name"]; ?>
                    <TD CLASS='<?php echo $shade; ?>'>
                        <?php echo $company_name; ?>
                    </TD>
                    <!--			
				<?php $company_address1 = $myrowsel["company_address1"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $company_address1; ?> 
			</TD>
				<?php $company_address2 = $myrowsel["company_address2"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $company_address2; ?> 
			</TD>
				<?php $company_city = $myrowsel["company_city"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $company_city; ?> 
			</TD>
				<?php $company_state = $myrowsel["company_state"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $company_state; ?> 
			</TD>
				<?php $company_zip = $myrowsel["company_zip"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $company_zip; ?> 
			</TD>
				<?php $company_phone = $myrowsel["company_phone"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $company_phone; ?> 
			</TD>
				<?php $company_email = $myrowsel["company_email"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $company_email; ?> 
			</TD>
				<?php $company_terms = $myrowsel["company_terms"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $company_terms; ?> 
			</TD>
				<?php $company_contact = $myrowsel["company_contact"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $company_contact; ?> 
			</TD>
-->
                    <?php $warehouse_name = $myrowsel["warehouse_name"]; ?>
                    <TD CLASS='<?php echo $shade; ?>'>
                        <?php echo $warehouse_name; ?>
                    </TD>
                    <!--			
				<?php $warehouse_address1 = $myrowsel["warehouse_address1"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $warehouse_address1; ?> 
			</TD>
				<?php $warehouse_address2 = $myrowsel["warehouse_address2"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $warehouse_address2; ?> 
			</TD>
				<?php $warehouse_city = $myrowsel["warehouse_city"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $warehouse_city; ?> 
			</TD>
				<?php $warehouse_state = $myrowsel["warehouse_state"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $warehouse_state; ?> 
			</TD>
				<?php $warehouse_zip = $myrowsel["warehouse_zip"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $warehouse_zip; ?> 
			</TD>
-->
                    <?php $warehouse_contact = $myrowsel["warehouse_contact"]; ?>
                    <TD CLASS='<?php echo $shade; ?>'>
                        <?php echo $warehouse_contact; ?>
                    </TD>
                    <?php $warehouse_contact_phone = $myrowsel["warehouse_contact_phone"]; ?>
                    <TD CLASS='<?php echo $shade; ?>'>
                        <?php echo $warehouse_contact_phone; ?>
                    </TD>
                    <?php $warehouse_contact_email = $myrowsel["warehouse_contact_email"]; ?>
                    <TD CLASS='<?php echo $shade; ?>'>
                        <?php echo $warehouse_contact_email; ?>
                    </TD>
                    <!--
				<?php $warehouse_manager = $myrowsel["warehouse_manager"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $warehouse_manager; ?> 
			</TD>
				<?php $warehouse_manager_phone = $myrowsel["warehouse_manager_phone"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $warehouse_manager_phone; ?> 
			</TD>
				<?php $warehouse_manager_email = $myrowsel["warehouse_manager_email"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $warehouse_manager_email; ?> 
			</TD>

				<?php $dock_details = $myrowsel["dock_details"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $dock_details; ?> 
			</TD>
				<?php $warehouse_notes = $myrowsel["warehouse_notes"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $warehouse_notes; ?> 
			</TD>
				<?php $other1 = $myrowsel["other1"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $other1; ?> 
			</TD>
				<?php $other2 = $myrowsel["other2"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $other2; ?> 
			</TD>
				<?php $other3 = $myrowsel["other3"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $other3; ?> 
			</TD>
-->
                    <TD CLASS='<?php echo $shade; ?>'>
                        <DIV CLASS='PAGE_OPTIONS'>
                            <?php if ($allowview == "yes") { ?><a href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&proc=View&<?php echo $pagevars; ?>">View</a>
                                <?php  } ?><?php if ($allowedit == "yes") { ?>
                                <a href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&proc=Edit&<?php echo $pagevars; ?>">Edit</a>
                                <?php  } ?><?php if ($allowdelete == "yes") { ?>
                                <a href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&proc=Delete&<?php echo $pagevars; ?>">Delete</a>
                                <?php  } ?><?php if ($allowdelete == "yes") { ?>
                                <a href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&proc=inactive&<?php echo $pagevars; ?>">Inactive</a>
                            <?php  } ?>
                        </DIV>
                    </TD>
                    </TR>
                    <?php
                } while ($myrowsel = array_shift($result));
                echo "</TABLE>";
                /*----------------------------------------------------------------
PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORDS
----------------------------------------------------------------*/
                if ($reccount > 10) {
                    //IF THERE ARE MORE THAN 10 RECORDS PAGING
                    $ttlpages = ($reccount / 10);
                    if ($page < $ttlpages) {
                    ?>

                        <HR> <br>


                    <?php
                    } //END IF AT LAST PAGE
                } //END IF RECCOUNT > 10
                //PREVIOUS RECORDS LINK
                if ($page > 0) {
                    $newpage = $page - 2;
                }
                if (isset($newpage) != -1) {
                    ?>

                    <br>

                    <br>
            <?php
                } //IF NEWPAGE != -1
                /*----------------------------------------------------------------
END PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORDS
----------------------------------------------------------------*/
            } //END PROC == ""
        } //END IF POSTING = YES


        if (isset($posting) == "active_inactive") {


            /*----------------------------------------------------------------
PAGING SETTINGS - THIS IS USED FOR NEXT/PREVIOUS X RECORDS
----------------------------------------------------------------*/
            $pagenorecords = 10;  //THIS IS THE PAGE SIZE
            //IF NO PAGE
            if (isset($page) == 0) {
                $myrecstart = 0;
            } else {
                $myrecstart = (isset($page) * $pagenorecords);
            }


            /*-- SECTION: 9991SQL --*/
            if (isset($searchcrit) == "") {
                $flag = "all";
                $sql = "SELECT * FROM loop_warehouse";
                $sqlcount = "select count(*) as reccount from loop_warehouse ";
            } else {
                //IF THEY TYPED SEARCH WORDS
                $sqlcount = "select count(*) as reccount from loop_warehouse WHERE (";
                $sql = "SELECT * FROM loop_warehouse WHERE (";
                $searchcrit = $searchcrit ?? "";
                $sqlwhere = "

 company_name like '%$searchcrit%' OR 
 company_address1 like '%$searchcrit%' OR 
 company_address2 like '%$searchcrit%' OR 
 company_city like '%$searchcrit%' OR 
 company_state like '%$searchcrit%' OR 
 company_zip like '%$searchcrit%' OR 
 company_phone like '%$searchcrit%' OR 
 company_email like '%$searchcrit%' OR 
 company_terms like '%$searchcrit%' OR 
 company_contact like '%$searchcrit%' OR 
 warehouse_name like '%$searchcrit%' OR 
 warehouse_address1 like '%$searchcrit%' OR 
 warehouse_address2 like '%$searchcrit%' OR 
 warehouse_city like '%$searchcrit%' OR 
 warehouse_state like '%$searchcrit%' OR 
 warehouse_zip like '%$searchcrit%' OR 
 warehouse_contact like '%$searchcrit%' OR 
 warehouse_contact_phone like '%$searchcrit%' OR 
 warehouse_contact_email like '%$searchcrit%' OR 
 warehouse_manager like '%$searchcrit%' OR 
 warehouse_manager_phone like '%$searchcrit%' OR 
 warehouse_manager_email like '%$searchcrit%' OR 
 dock_details like '%$searchcrit%' OR 
 warehouse_notes like '%$searchcrit%' OR 
 other1 like '%$searchcrit%' OR 
 other2 like '%$searchcrit%' OR 
 other3 like '%$searchcrit%' 

		"; //FINISH SQL STRING

                //NOTE:CHECK ABOVE FOR AN EXTRA "OR":
                //IF YOU LEFT THE LAST 
                //FIELD IN THE FIELD NAME ENTRY FORM BLANK, YOU WILL
                //GET AN EXTRA "OR" ABOVE.  SIMPLY DELETE IT.

            } //END IF SEARCHCRIT = "";

            if (isset($flag) == "all") {
                $sql = $sql . " WHERE (1=1) $addl_select_crit";
                $sqlcount = $sqlcount  . " WHERE (1=1) $addl_select_crit";
            } else {
                $sqlcount = $sqlcount . isset($sqlwhere) . ") $addl_select_crit";
                $sql = $sql . isset($sqlwhere) . ") $addl_select_crit";
            }
            // if ($flag == "all") {
            //     $sql = $sql . " WHERE (1=1) $addl_select_crit LIMIT $myrecstart, $pagenorecords";
            //     $sqlcount = $sqlcount  . " WHERE (1=1) $addl_select_crit LIMIT $myrecstart, $pagenorecords";
            // } else {
            //     $sqlcount = $sqlcount . $sqlwhere . ") $addl_select_crit LIMIT $myrecstart, $pagenorecords";
            //     $sql = $sql . $sqlwhere . ") $addl_select_crit LIMIT $myrecstart, $pagenorecords";
            // }

            //  echo "<BR>SQL: $sql<BR>"; 



            //SET PAGE
            if (isset($page) == 0) {
                $page = 1;
            } else {
                $page = (isset($page) + 1);
            }

            /*----------------------------------------------------------------
FIND OUT HOW MANY RECORDS WE HAVE
----------------------------------------------------------------*/

            if (isset($reccount) == 0) {
                db();
                //$resultcount = (db_query($sqlcount,db() )) OR DIE (ThrowError($err_type,$err_descr););
                db();
                $resultcount = (db_query($sqlcount)) or die(ThrowError("9991SQLresultcount", $sqlcount, 'Production'));
                if ($myrowcount = array_shift($resultcount)) {
                    $reccount = $myrowcount["reccount"];
                } //IF RECCOUNT = 0
            } //end if reccount

            // echo "<DIV CLASS='NUM_RECS_FOUND'>$reccount Records Found</DIV>";
            //	echo "<DIV CLASS='CURR_PAGE'>Page $page</DIV>";
            echo "<!--<DIV CLASS='CURR_PAGE'>Page $page - $reccount Records Found</DIV>-->";

            /*----------------------------------------------------------------
PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORDS
----------------------------------------------------------------*/
            // if ($reccount > 10) {
            // $ttlpages = ($reccount / 10);
            // if ($page < $ttlpages) {
            ?>

            <!-- <HR>	<br> -->

            <?php
            // } //END IF AT LAST PAGE
            // } //END IF RECCOUNT > 10

            // if ($page > 0) { 
            // $newpage = $page - 2;
            // }
            // if ($newpage != -1) {
            ?>

            <?php

            // } //IF NEWPAGE != -1
            /*----------------------------------------------------------------
END PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORDS
----------------------------------------------------------------*/
            //EXECUTE OUR SQL STRING FOR THE TABLE RECORDS
            db();
            $result = db_query($sql);
            if ($sql_debug_mode == 1) {
                echo "<BR>SQL: $sql<BR>";
            }
            if ($myrowsel = array_shift($result)) {
                $id = $myrowsel["id"];

                echo "<br><TABLE style='width:850px'>";
                echo "	<tr class='text_align_center'><td colspan='7' class='style24' style='height: 16px'><strong>SORTING WAREHOUSE SETUP</strong></td></tr>";
                echo "	<TR>";

                echo "		<TD><DIV CLASS='TBL_COL_HDR'>Company Name</DIV></TD>";
                /*
echo "		<TD><DIV CLASS='TBL_COL_HDR'>COMPANY_ADDRESS1</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>COMPANY_ADDRESS2</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>COMPANY_CITY</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>COMPANY_STATE</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>COMPANY_ZIP</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>COMPANY_PHONE</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>COMPANY_EMAIL</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>COMPANY_TERMS</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>COMPANY_CONTACT</DIV></TD>"; 
*/
                echo "		<TD><DIV CLASS='TBL_COL_HDR'>Warehouse Name</DIV></TD>";
                /*
echo "		<TD><DIV CLASS='TBL_COL_HDR'>WAREHOUSE_ADDRESS1</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>WAREHOUSE_ADDRESS2</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>WAREHOUSE_CITY</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>WAREHOUSE_STATE</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>WAREHOUSE_ZIP</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>WAREHOUSE_CONTACT</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>WAREHOUSE_CONTACT_PHONE</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>WAREHOUSE_CONTACT_EMAIL</DIV></TD>"; 
*/
                echo "		<TD><DIV CLASS='TBL_COL_HDR'>Warehouse Contact</DIV></TD>";
                echo "		<TD><DIV CLASS='TBL_COL_HDR'>Warehouse Contact Phone</DIV></TD>";
                echo "		<TD><DIV CLASS='TBL_COL_HDR'>Warehouse Contact Email</DIV></TD>";
                echo "		<TD><DIV CLASS='TBL_COL_HDR'>Active Flag</DIV></TD>";
                /*
echo "		<TD><DIV CLASS='TBL_COL_HDR'>DOCK_DETAILS</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>WAREHOUSE_NOTES</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>OTHER1</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>OTHER2</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>OTHER3</DIV></TD>"; 
*/
                echo "<TD><DIV CLASS='TBL_COL_HDR'>Options</DIV></TD>";
                echo "\n\n		</TR>";
                do {
                    //FORMAT THE OUTPUT OF THE SEARCH
                    $id = $myrowsel["id"];

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

                    /*----------------------------------------------------------------
VIEW SEARCH RESULTS BY PAGE
----------------------------------------------------------------*/

                    /*---------------------------------------
BEGIN RESULTS TABLE
---------------------------------------*/

                    echo "<TR>";
            ?>

                    <?php $company_name = $myrowsel["company_name"]; ?>
                    <TD CLASS='<?php echo $shade; ?>'>
                        <?php echo $company_name; ?>
                    </TD>
                    <!--			
				<?php $company_address1 = $myrowsel["company_address1"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $company_address1; ?> 
			</TD>
				<?php $company_address2 = $myrowsel["company_address2"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $company_address2; ?> 
			</TD>
				<?php $company_city = $myrowsel["company_city"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $company_city; ?> 
			</TD>
				<?php $company_state = $myrowsel["company_state"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $company_state; ?> 
			</TD>
				<?php $company_zip = $myrowsel["company_zip"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $company_zip; ?> 
			</TD>
				<?php $company_phone = $myrowsel["company_phone"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $company_phone; ?> 
			</TD>
				<?php $company_email = $myrowsel["company_email"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $company_email; ?> 
			</TD>
				<?php $company_terms = $myrowsel["company_terms"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $company_terms; ?> 
			</TD>
				<?php $company_contact = $myrowsel["company_contact"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $company_contact; ?> 
			</TD>
-->
                    <?php $warehouse_name = $myrowsel["warehouse_name"]; ?>
                    <TD CLASS='<?php echo $shade; ?>'>
                        <?php echo $warehouse_name; ?>
                    </TD>
                    <!--			
				<?php $warehouse_address1 = $myrowsel["warehouse_address1"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $warehouse_address1; ?> 
			</TD>
				<?php $warehouse_address2 = $myrowsel["warehouse_address2"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $warehouse_address2; ?> 
			</TD>
				<?php $warehouse_city = $myrowsel["warehouse_city"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $warehouse_city; ?> 
			</TD>
				<?php $warehouse_state = $myrowsel["warehouse_state"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $warehouse_state; ?> 
			</TD>
				<?php $warehouse_zip = $myrowsel["warehouse_zip"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $warehouse_zip; ?> 
			</TD>
-->
                    <?php $warehouse_contact = $myrowsel["warehouse_contact"]; ?>
                    <TD CLASS='<?php echo $shade; ?>'>
                        <?php echo $warehouse_contact; ?>
                    </TD>
                    <?php $warehouse_contact_phone = $myrowsel["warehouse_contact_phone"]; ?>
                    <TD CLASS='<?php echo $shade; ?>'>
                        <?php echo $warehouse_contact_phone; ?>
                    </TD>
                    <?php $warehouse_contact_email = $myrowsel["warehouse_contact_email"]; ?>
                    <TD CLASS='<?php echo $shade; ?>'>
                        <?php echo $warehouse_contact_email; ?>
                    </TD>
                    <?php $inactive = $myrowsel["Active"]; ?>
                    <TD CLASS='<?php echo $shade; ?>'>
                        <?php echo ($inactive == 1) ? "YES" : "NO"; ?>
                    </TD>
                    <!--
				<?php $warehouse_manager = $myrowsel["warehouse_manager"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $warehouse_manager; ?> 
			</TD>
				<?php $warehouse_manager_phone = $myrowsel["warehouse_manager_phone"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $warehouse_manager_phone; ?> 
			</TD>
				<?php $warehouse_manager_email = $myrowsel["warehouse_manager_email"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $warehouse_manager_email; ?> 
			</TD>

				<?php $dock_details = $myrowsel["dock_details"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $dock_details; ?> 
			</TD>
				<?php $warehouse_notes = $myrowsel["warehouse_notes"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $warehouse_notes; ?> 
			</TD>
				<?php $other1 = $myrowsel["other1"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $other1; ?> 
			</TD>
				<?php $other2 = $myrowsel["other2"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $other2; ?> 
			</TD>
				<?php $other3 = $myrowsel["other3"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $other3; ?> 
			</TD>
			   <?php $inactive = $myrowsel["Active"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo ($inactive == 1) ? "YES" : "NO"; ?> 
			</TD>
-->
                    <TD CLASS='<?php echo $shade; ?>'>
                        <DIV CLASS='PAGE_OPTIONS'>
                            <?php if ($allowview == "yes") { ?><a href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&proc=View&<?php echo $pagevars; ?>">View</a>
                                <?php  } ?><?php if ($allowedit == "yes") { ?>
                                <a href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&proc=Edit&<?php echo $pagevars; ?>">Edit</a>
                                <?php  } ?><?php if ($allowdelete == "yes") { ?>
                                <a href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&proc=Delete&<?php echo $pagevars; ?>">Delete</a>
                                <?php  } ?><?php if ($allowdelete == "yes") { ?>
                                <a href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&proc=inactive&<?php echo $pagevars; ?>">Inactive</a>
                            <?php  } ?>
                        </DIV>
                    </TD>
                    </TR>
                <?php
                } while ($myrowsel = array_shift($result));
                echo "</TABLE>";
                /*----------------------------------------------------------------
PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORDS
----------------------------------------------------------------*/
                // 	if ($reccount > 10) {
                // //IF THERE ARE MORE THAN 10 RECORDS PAGING
                // 	$ttlpages = ($reccount / 10);
                // 	if ($page < $ttlpages) {
                ?>

                <!-- <HR>	<br>
<A HREF="<?php echo $thispage; ?>?posting=active_inactive&page=<?php echo $page; ?>&reccount=<?php echo $reccount; ?>&searchcrit=<?php echo $searchcrit; ?>&<?php echo $pagevars; ?>">Next <?php echo $pagenorecords; ?> Records >></a>	<br> -->

                <?php
                // } //END IF AT LAST PAGE
                // } //END IF RECCOUNT > 10
                // //PREVIOUS RECORDS LINK
                // if ($page > 0) { 
                // $newpage = $page - 2;
                // }
                // if ($newpage != -1) {
                ?>
                <!-- 
	<br>
	<A HREF="<?php echo $thispage; ?>?posting=active_inactive&page=<?php echo $newpage; ?>&reccount=<?php echo $reccount; ?>&searchcrit=<?php echo $searchcrit; ?>&<?php echo $pagevars; ?>"><< Previous <?php echo $pagenorecords; ?> Records</a>
	<br> -->
    <?php
                // } //IF NEWPAGE != -1
                /*----------------------------------------------------------------
END PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORDS
----------------------------------------------------------------*/
            } //END PROC == ""
        } //END IF POSTING = active_inactive



        /*---------------------------------------------------------------------------------
END SEARCH SECTION 9991
---------------------------------------------------------------------------------*/
    } // END IF PROC = ""

    ?>
    <?php

    /*----------------------------------------------------------------------
ADD NEW RECORDS SECTION
----------------------------------------------------------------------*/
    if ($proc == "New") {
    ?>
        <!-- <a href="<?php echo $thispage; ?>?proc=&<?php echo $pagevars; ?>">Search</a><br>  -->
        <?php
        if ($allowaddnew == "yes") {
        ?>
            <!--<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a>-->
            <?php  } //END OF IF ALLOW ADDNEW 
        /*-------------------------------------------------------------------------------
ADD NEW RECORD SECTION 9994
-------------------------------------------------------------------------------*/

        if ($proc == "New") {
            echo "<a href=\"manage_sortwh_mrg.php?posting=yes\">Back</a><br><br>";
            echo "<table style='width:900px; border:0'><tr><td class='table_valign_top'><DIV CLASS='PAGE_STATUS'>Add New Sorting Warehouse</DIV>";
            if ($post == "yes") {
                /*
WE ARE ADDING A NEW RECORD SUBMITTED FROM THE FORM
NOW LOOP THROUGH ALL OF THE RECORDS AND OUTPUT A STRING
*/

                /* FIX STRING */
                $company_name = FixString($company_name);
                $company_address1 = FixString($company_address1);
                $company_address2 = FixString($company_address2);
                $company_city = FixString($company_city);
                $company_state = FixString($company_state);
                $company_zip = FixString($company_zip);
                $company_phone = FixString($company_phone);
                $company_email = FixString($company_email);
                $company_terms = FixString($company_terms);
                $company_contact = FixString($company_contact);
                $warehouse_name = FixString($warehouse_name);
                $warehouse_address1 = FixString($warehouse_address1);
                $warehouse_address2 = FixString($warehouse_address2);
                $warehouse_city = FixString($warehouse_city);
                $warehouse_state = FixString($warehouse_state);
                $warehouse_zip = FixString($warehouse_zip);
                $warehouse_country = FixString($warehouse_country);
                $warehouse_contact = FixString($warehouse_contact);
                $warehouse_contact_phone = FixString($warehouse_contact_phone);
                $warehouse_contact_email = FixString($warehouse_contact_email);
                $warehouse_manager = FixString($warehouse_manager);
                $warehouse_manager_phone = FixString($warehouse_manager_phone);
                $warehouse_manager_email = FixString($warehouse_manager_email);
                $dock_details = FixString($dock_details);
                $sets_own_dock_appointment = FixString($sets_own_dock_appointment);
                $calendly_link = FixString($calendly_link);
                $warehouse_notes = FixString($warehouse_notes);
                $rec_type = FixString($rec_type);
                $bs_status = FixString($bs_status);
                $other1 = FixString($other1);
                $other2 = FixString($other2);
                $other3 = FixString($other3);
                //
                $noof_dock_doors = FixString($noof_dock_doors);
                //

                /*-- SECTION: 9994SQL --*/
                $sql = "INSERT INTO loop_warehouse (
company_name,
company_address1,
company_address2,
company_city,
company_state,
company_zip,
company_phone,
company_email,
company_terms,
company_contact,
warehouse_name,
warehouse_address1,
warehouse_address2,
warehouse_city,
warehouse_state,
warehouse_zip,
warehouse_country,
warehouse_contact,
warehouse_contact_phone,
warehouse_contact_email,
warehouse_manager,
warehouse_manager_phone,
warehouse_manager_email,
dock_details,
noof_dock_doors,
warehouse_notes,
rec_type,
bs_status,
other1,
other2,
other3,
pallet_space,
sets_own_dock_appointment,
calendly_link
 $addl_insert_crit ) VALUES ( '$company_name',
'$company_address1',
'$company_address2',
'$company_city',
'$company_state',
'$company_zip',
'$company_phone',
'$company_email',
'$company_terms',
'$company_contact',
'$warehouse_name',
'$warehouse_address1',
'$warehouse_address2',
'$warehouse_city',
'$warehouse_state',
'$warehouse_zip',
'$warehouse_country',
'$warehouse_contact',
'$warehouse_contact_phone',
'$warehouse_contact_email',
'$warehouse_manager',
'$warehouse_manager_phone',
'$warehouse_manager_email',
'$dock_details',
'$noof_dock_doors',
'$warehouse_notes',
'$rec_type',
'$bs_status',
'$other1',
'$other2',
'$other3',
'$pallet_space',
'$sets_own_dock_appointment',
'$calendly_link'
 $addl_insert_values )";
                if ($sql_debug_mode == 1) {
                    echo "<BR>SQL: $sql<BR>";
                }
                db();
                $result = db_query($sql);
                if (empty($result)) {
                    //
                    $wh_id = tep_db_insert_id();
                    $total = $_REQUEST["dim_cnt"];
                    for ($s = 0; $s <= $total; $s++) {
                        $dock_doors_names = $_REQUEST["dock_doors_names" . $s];
                        if ($dock_doors_names != "") {
                            $ddsql = "INSERT INTO loop_dock_doors_sortwh (srt_warehouse_id,dock_doors_names) VALUES ('$wh_id','$dock_doors_names')";
                            $res = db_query($ddsql);
                        }
                    }
                    //
                    echo "<br><DIV CLASS='SQL_RESULTS'>Sorting Warehouse Added.  <a href=\"manage_sortwh_mrg.php?posting=yes\">Continue</a></DIV>";

                    if (!headers_sent()) {    //If headers not sent yet... then do php redirect
                        header('Location: manage_sortwh_mrg.php?posting=yes');
                        exit;
                    } else {
                        echo "<script type=\"text/javascript\">";
                        echo "window.location.href=\"manage_sortwh_mrg.php?posting=yes\";";
                        echo "</script>";
                        echo "<noscript>";
                        echo "<meta http-equiv=\"refresh\" content=\"0;url=manage_sortwh_mrg.php?posting=yes\" />";
                        echo "</noscript>";
                        exit;
                    } //==== End -- Redirect	
                } else {
                    db();
                    echo ThrowError("9994SQL", $sql, 'Production');
                    echo "Error inserting record (9994SQL)";
                }
                //***** END INSERT SQL *****
            } //END IF POST = YES FOR ADDING NEW RECORDS
            /*-------------------------------------------------------------------------------
ADD NEW RECORD (CREATING)
-------------------------------------------------------------------------------*/
            if (!$post) { //THEN WE ARE ENTERING A NEW RECORD
                //SHOW THE ADD RECORD RECORD DATA INPUT FORM
                /*-- SECTION: 9994FORM --*/
            ?>
                <FORM METHOD="POST" name="frm" ACTION="<?php echo $thispage; ?>?proc=New&post=yes&<?php echo $pagevars; ?>">
                    <input type="hidden" name="rec_type" value="Sorting">
                    <input type="hidden" name="bs_status" value="Neither">
                    <TABLE class="text_align_left">
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>
                                <B>Company:</B>
                            </TD>
                            <TD>
                                <INPUT CLASS='TXT_BOX' type="text" NAME="company_name" SIZE="20">
                        </TR>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>
                                <B>Company Address One:</B>
                            </TD>
                            <TD>
                                <INPUT CLASS='TXT_BOX' type="text" NAME="company_address1" SIZE="20">
                        </TR>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>
                                <B>Company Address Two</B>
                            </TD>
                            <TD>
                                <INPUT CLASS='TXT_BOX' type="text" NAME="company_address2" SIZE="20">
                        </TR>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>
                                <B>Company City:</B>
                            </TD>
                            <TD>
                                <INPUT CLASS='TXT_BOX' type="text" NAME="company_city" SIZE="20">
                        </TR>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>
                                <B>Company State:</B>
                            </TD>
                            <TD>
                                <INPUT CLASS='TXT_BOX' type="text" NAME="company_state" SIZE="20">
                        </TR>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>
                                <B>Company Zip Code:</B>
                            </TD>
                            <TD>
                                <INPUT CLASS='TXT_BOX' type="text" NAME="company_zip" SIZE="20">
                        </TR>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>
                                <B>Company Contact Name:</B>
                            </TD>
                            <TD>
                                <INPUT CLASS='TXT_BOX' type="text" NAME="company_contact" SIZE="20">
                        </TR>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>
                                <B>Company Contact Phone:</B>
                            </TD>
                            <TD>
                                <INPUT CLASS='TXT_BOX' type="text" NAME="company_phone" SIZE="20">
                        </TR>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>
                                <B>Company Contact Email:</B>
                            </TD>
                            <TD>
                                <INPUT CLASS='TXT_BOX' type="text" NAME="company_email" SIZE="20">
                        </TR>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>
                                <B>Company Credit Terms:</B>
                            </TD>
                            <TD>
                                <INPUT CLASS='TXT_BOX' type="text" NAME="company_terms" SIZE="20">
                        </TR>

                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>
                                <B>Warehouse Name:</B>
                            </TD>
                            <TD>
                                <INPUT CLASS='TXT_BOX' type="text" NAME="warehouse_name" SIZE="20">
                        </TR>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>
                                <B>Warehouse Address One:</B>
                            </TD>
                            <TD>
                                <INPUT CLASS='TXT_BOX' type="text" NAME="warehouse_address1" SIZE="20">
                        </TR>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>
                                <B>Warehouse Address Two:</B>
                            </TD>
                            <TD>
                                <INPUT CLASS='TXT_BOX' type="text" NAME="warehouse_address2" SIZE="20">
                        </TR>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>
                                <B>Warehouse City:</B>
                            </TD>
                            <TD>
                                <INPUT CLASS='TXT_BOX' type="text" NAME="warehouse_city" SIZE="20">
                        </TR>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>
                                <B>Warehosue State:</B>
                            </TD>
                            <TD>
                                <INPUT CLASS='TXT_BOX' type="text" NAME="warehouse_state" SIZE="20">
                        </TR>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>
                                <B>Warehouse Zip Code:</B>
                            </TD>
                            <TD>
                                <INPUT CLASS='TXT_BOX' type="text" NAME="warehouse_zip" SIZE="20">
                            </TD>
                        </TR>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>
                                <B>Warehouse Country:</B>
                            </TD>
                            <TD>
                                <INPUT CLASS='TXT_BOX' type="text" NAME="warehouse_country" SIZE="20">
                            </TD>
                        </TR>

                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>
                                <B>Warehouse Contact Name:</B>
                            </TD>
                            <TD>
                                <INPUT CLASS='TXT_BOX' type="text" NAME="warehouse_contact" SIZE="20">
                        </TR>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>
                                <B>Warehouse Contact Phone:</B>
                            </TD>
                            <TD>
                                <INPUT CLASS='TXT_BOX' type="text" NAME="warehouse_contact_phone" SIZE="20">
                        </TR>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>
                                <B>Warehouse Contact Email:</B>
                            </TD>
                            <TD>
                                <INPUT CLASS='TXT_BOX' type="text" NAME="warehouse_contact_email" SIZE="20">
                        </TR>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>
                                <B>Warehouse Other Contact:</B>
                            </TD>
                            <TD>
                                <INPUT CLASS='TXT_BOX' type="text" NAME="warehouse_manager" SIZE="20">
                        </TR>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>
                                <B>Warehouse Other Contact Phone:</B>
                            </TD>
                            <TD>
                                <INPUT CLASS='TXT_BOX' type="text" NAME="warehouse_manager_phone" SIZE="20">
                        </TR>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>
                                <B>Warehouse Other Contact Email:</B>
                            </TD>
                            <TD>
                                <INPUT CLASS='TXT_BOX' type="text" NAME="warehouse_manager_email" SIZE="20">
                        </TR>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>
                                <B>Shipping/Receiving Hours:</B>
                            </TD>
                            <td>
                                <INPUT CLASS='TXT_BOX' type="text" NAME="dock_details" SIZE="40">
                            </td>
                        </TR>
                        <tr>
                            <td CLASS='TBL_ROW_HDR'>
                                <B># of Dock Doors</B>
                            </td>
                            <td>
                                <INPUT CLASS='TXT_BOX' type="text" NAME="noof_dock_doors" SIZE="40">
                            </td>
                        </tr>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR table_valign_top'>
                                <B> Dock Doors Names:</B>
                            </TD>
                            <td>
                                <div id="data_row">
                                    <?php $dim_cnt = 0; ?>
                                    <table id="dock_door_tbl">
                                        <tr>
                                            <td>
                                                <input type="text" name="dock_doors_names0" id="dock_doors_names0">
                                            </td>
                                        </tr>
                                    </table>

                                </div>
                                <div class="addmore_btn">
                                    <input type="hidden" name="dim_cnt" id="dim_cnt" value="<?php echo $dim_cnt; ?>">
                                    <input type="button" value="Add more" name="dockdoor_add_more" onClick="add_dock_door()">
                                </div>
                            </td>
                        </TR>

                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>
                                <B>Finished Goods Inventory Cubic Footage:</B>
                            </TD>
                            <TD>
                                <INPUT CLASS='TXT_BOX' type="text" NAME="pallet_space" SIZE="20">
                        </TR>

                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>
                                Customer Sets Own Dock Appointment
                            </TD>
                            <TD>
                                <input name="sets_own_dock_appointment" type="checkbox" value="yes">
                            </TD>
                        </TR>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>
                                Calendly Link
                            </TD>
                            <TD>
                                <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='calendly_link' size='40'>
                            </TD>
                        </TR>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>
                                <B>Warehouse Notes:</B>
                            </TD>
                            <TD>
                                <textarea CLASS='TXT_BOX' NAME="warehouse_notes" ROWS="4" cols="35"></TEXTAREA>
                        </TR>
                        <TR>
                            <TD>
                            </TD>
                            <TD>
                                <INPUT CLASS='BUTTON' TYPE="SUBMIT" VALUE="SAVE" NAME="SUBMIT">
                                <INPUT CLASS='BUTTON' TYPE="RESET" VALUE="RESET" NAME="RESET">
                            </TD>
                        </TR>
                    </TABLE>
                    <BR>
                </FORM>

    <?php
            } //END if post=""
            //***** END ADD NEW ENTRY FORM*****
            echo "</table>";
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
    if ($proc == "Edit") {
        echo "<a href=\"manage_sortwh_mrg.php?posting=yes\">Back</a><br><br>";
    ?>
        <!-- <a href="<?php echo $thispage; ?>?proc=&<?php echo $pagevars; ?>">Search</a><br> -->
        <?php
        if ($allowaddnew == "yes") {
        ?>
            <!-- <a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br> -->
        <?php  } //END OF IF ALLOW ADDNEW 

        /*----------------------------------------------------------------------
EDIT RECORDS SECTION
----------------------------------------------------------------------*/
        /*-------------------------------------------------------------------------------
EDIT RECORD SECTION 9993
-------------------------------------------------------------------------------*/

        ?>
        <!--<DIV CLASS='PAGE_OPTIONS'>
	<?php if ($allowview == "yes") { ?><a href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&proc=View&<?php echo $pagevars; ?>">View</a>
	<?php  } //END ALLOWVIEW 
    ?><?php if ($allowedit == "yes") { ?>
	<a href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&proc=Edit&<?php echo $pagevars; ?>">Edit</a>
	<?php  } //END ALLOWEDIT 
    ?><?php if ($allowdelete == "yes") { ?>
	<a href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&proc=Delete&<?php echo $pagevars; ?>">Delete</a>
	<?php  } //END ALLOWDELETE 
    ?></span><span class="font_family_Ariel font_size_2">
</DIV>
-->
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
                $company_name = FixString($company_name);
                $company_address1 = FixString($company_address1);
                $company_address2 = FixString($company_address2);
                $company_city = FixString($company_city);
                $company_state = FixString($company_state);
                $company_zip = FixString($company_zip);
                $company_phone = FixString($company_phone);
                $company_email = FixString($company_email);
                $company_terms = FixString($company_terms);
                $company_contact = FixString($company_contact);
                $warehouse_name = FixString($warehouse_name);
                $warehouse_address1 = FixString($warehouse_address1);
                $warehouse_address2 = FixString($warehouse_address2);
                $warehouse_city = FixString($warehouse_city);
                $warehouse_state = FixString($warehouse_state);
                $warehouse_zip = FixString($warehouse_zip);
                $warehouse_country = FixString($warehouse_country);
                $warehouse_contact = FixString($warehouse_contact);
                $warehouse_contact_phone = FixString($warehouse_contact_phone);
                $warehouse_contact_email = FixString($warehouse_contact_email);
                $warehouse_manager = FixString($warehouse_manager);
                $warehouse_manager_phone = FixString($warehouse_manager_phone);
                $warehouse_manager_email = FixString($warehouse_manager_email);
                $dock_details = FixString($dock_details);
                $sets_own_dock_appointment = FixString($sets_own_dock_appointment);
                $calendly_link = FixString($calendly_link);
                $warehouse_notes = FixString($warehouse_notes);
                $other1 = FixString($other1);
                $other2 = FixString($other2);
                $other3 = FixString($other3);
                //
                $noof_dock_doors = FixString($noof_dock_doors);
                //
                //SQL STRING
                /*-- SECTION: 9993SQLUPD --*/

                echo $sets_own_dock_appointment;

                $sql = "UPDATE loop_warehouse SET 
 company_name='$company_name',
 company_address1='$company_address1',
 company_address2='$company_address2',
 company_city='$company_city',
 company_state='$company_state',
 company_zip='$company_zip',
 company_phone='$company_phone',
 company_email='$company_email',
 company_terms='$company_terms',
 company_contact='$company_contact',
 warehouse_name='$warehouse_name',
 warehouse_address1='$warehouse_address1',
 warehouse_address2='$warehouse_address2',
 warehouse_city='$warehouse_city',
 warehouse_state='$warehouse_state',
 warehouse_zip='$warehouse_zip',
 warehouse_country = '$warehouse_country',
 warehouse_contact='$warehouse_contact',
 warehouse_contact_phone='$warehouse_contact_phone',
 warehouse_contact_email='$warehouse_contact_email',
 warehouse_manager='$warehouse_manager',
 warehouse_manager_phone='$warehouse_manager_phone',
 warehouse_manager_email='$warehouse_manager_email',
 dock_details='$dock_details',
 noof_dock_doors = '$noof_dock_doors',
 sets_own_dock_appointment = '$sets_own_dock_appointment',
 calendly_link = '$calendly_link',
 warehouse_notes='$warehouse_notes',
 pallet_space = '$pallet_space',
 other1='$other1',
 other2='$other2',
 other3='$other3'
$addl_update_crit 
	WHERE (id='$id') $addl_select_crit ";
                if ($sql_debug_mode == 1) {
                    echo "<BR>SQL: $sql<BR>";
                }
                db();
                $result = db_query($sql);
                if (empty($result)) {
                    //save dock door names
                    $wh_id = $id;
                    $total = $_REQUEST["dim_cnt"];
                    //
                    $ddsql = "delete from loop_dock_doors_sortwh where srt_warehouse_id=" . $wh_id;
                    $res = db_query($ddsql);
                    //
                    for ($s = 0; $s <= $total; $s++) {
                        $dock_doors_names = $_REQUEST["dock_doors_names" . $s];
                        $dd_name_id = $_REQUEST["dd_name_id" . $s];
                        //
                        if ($dock_doors_names != "") {
                            $ddsql = "INSERT INTO loop_dock_doors_sortwh (srt_warehouse_id,dock_doors_names) VALUES ('$wh_id','$dock_doors_names')";
                            $res = db_query($ddsql);
                        }
                    }
                    //
                    echo "<DIV CLASS='SQL_RESULTS'>Sorting Warehosue Updated.  <a href=\"manage_sortwh_mrg.php?posting=yes\">Continue</a></DIV>";

                    if (!headers_sent()) {    //If headers not sent yet... then do php redirect
                        header('Location: manage_sortwh_mrg.php?posting=yes');
                        exit;
                    } else {
                        echo "<script type=\"text/javascript\">";
                        echo "window.location.href=\"manage_sortwh_mrg.php?posting=yes\";";
                        echo "</script>";
                        echo "<noscript>";
                        echo "<meta http-equiv=\"refresh\" content=\"0;url=manage_sortwh_mrg.php?posting=yes\" />";
                        echo "</noscript>";
                        exit;
                    } //==== End -- Redirect
                } else {
                    echo "Error Updating Record (9993SQLUPD)";
                }
                //***** END UPDATE SQL *****
            } //END IF POST IS YES

            /*-------------------------------------------------------------------------------
EDIT RECORD (FORM) - SHOW THE EDIT RECORD RECORD DATA INPUT FORM
-------------------------------------------------------------------------------*/
            if ($post == "") { //THEN WE ARE EDITING A RECORD
                echo "<DIV CLASS='PAGE_STATUS'>Editing Record</DIV>";


                /*-- SECTION: 9993SQLGET --*/
                $sql = "SELECT * FROM loop_warehouse WHERE (id = '$id') $addl_select_crit ";
                if ($sql_debug_mode == 1) {
                    echo "<BR>SQL: $sql<BR>";
                }
                db();
                $result = db_query($sql) or die("Error Retrieving Records (9993SQLGET)");
                if ($myrow = array_shift($result)) {
                    do {

                        $company_name = $myrow["company_name"];
                        $company_name = preg_replace("(\n)", "<BR>", $company_name);
                        $company_address1 = $myrow["company_address1"];
                        $company_address1 = preg_replace("(\n)", "<BR>", $company_address1);
                        $company_address2 = $myrow["company_address2"];
                        $company_address2 = preg_replace("(\n)", "<BR>", $company_address2);
                        $company_city = $myrow["company_city"];
                        $company_city = preg_replace("(\n)", "<BR>", $company_city);
                        $company_state = $myrow["company_state"];
                        $company_state = preg_replace("(\n)", "<BR>", $company_state);
                        $company_zip = $myrow["company_zip"];
                        $company_zip = preg_replace("(\n)", "<BR>", $company_zip);
                        $company_phone = $myrow["company_phone"];
                        $company_phone = preg_replace("(\n)", "<BR>", $company_phone);
                        $company_email = $myrow["company_email"];
                        $company_email = preg_replace("(\n)", "<BR>", $company_email);
                        $company_terms = $myrow["company_terms"];
                        $company_terms = preg_replace("(\n)", "<BR>", $company_terms);
                        $company_contact = $myrow["company_contact"];
                        $company_contact = preg_replace("(\n)", "<BR>", $company_contact);
                        $warehouse_name = $myrow["warehouse_name"];
                        $warehouse_name = preg_replace("(\n)", "<BR>", $warehouse_name);
                        $warehouse_address1 = $myrow["warehouse_address1"];
                        $warehouse_address1 = preg_replace("(\n)", "<BR>", $warehouse_address1);
                        $warehouse_address2 = $myrow["warehouse_address2"];
                        $warehouse_address2 = preg_replace("(\n)", "<BR>", $warehouse_address2);
                        $warehouse_city = $myrow["warehouse_city"];
                        $warehouse_city = preg_replace("(\n)", "<BR>", $warehouse_city);
                        $warehouse_state = $myrow["warehouse_state"];
                        $warehouse_state = preg_replace("(\n)", "<BR>", $warehouse_state);
                        $warehouse_zip = $myrow["warehouse_zip"];
                        $warehouse_zip = preg_replace("(\n)", "<BR>", $warehouse_zip);
                        $warehouse_country = $myrow["warehouse_country"];
                        $warehouse_contact = $myrow["warehouse_contact"];
                        $warehouse_contact = preg_replace("(\n)", "<BR>", $warehouse_contact);
                        $warehouse_contact_phone = $myrow["warehouse_contact_phone"];
                        $warehouse_contact_phone = preg_replace("(\n)", "<BR>", $warehouse_contact_phone);
                        $warehouse_contact_email = $myrow["warehouse_contact_email"];
                        $warehouse_contact_email = preg_replace("(\n)", "<BR>", $warehouse_contact_email);
                        $warehouse_manager = $myrow["warehouse_manager"];
                        $warehouse_manager = preg_replace("(\n)", "<BR>", $warehouse_manager);
                        $warehouse_manager_phone = $myrow["warehouse_manager_phone"];
                        $warehouse_manager_phone = preg_replace("(\n)", "<BR>", $warehouse_manager_phone);
                        $warehouse_manager_email = $myrow["warehouse_manager_email"];
                        $warehouse_manager_email = preg_replace("(\n)", "<BR>", $warehouse_manager_email);
                        $dock_details = $myrow["dock_details"];
                        $dock_details = preg_replace("(\n)", "<BR>", $dock_details);
                        $pallet_space = $myrow["pallet_space"];
                        //
                        $sets_own_dock_appointment = $myrow["sets_own_dock_appointment"];
                        $calendly_link = $myrow["calendly_link"];
                        //
                        $noof_dock_doors = $myrow["noof_dock_doors"];
                        //
                        $warehouse_notes = $myrow["warehouse_notes"];
                        $warehouse_notes = preg_replace("(\n)", "<BR>", $warehouse_notes);
                        $other1 = $myrow["other1"];
                        $other1 = preg_replace("(\n)", "<BR>", $other1);
                        $other2 = $myrow["other2"];
                        $other2 = preg_replace("(\n)", "<BR>", $other2);
                        $other3 = $myrow["other3"];
                        $other3 = preg_replace("(\n)", "<BR>", $other3);
        ?>
                        <form method="post" action="<?php echo $thispage; ?>?proc=Edit&post=yes&<?php echo $pagevars; ?>">
                            <br>
                            <TABLE class="text_align_left">
                                <TR>
                                    <TD CLASS='TBL_ROW_HDR'>
                                        Company Name:
                                    </TD>
                                    <TD>
                                        <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='company_name' value='<?php echo $company_name; ?>' size='20'>
                                    </td>
                                </tr>
                                <TR>
                                    <TD CLASS='TBL_ROW_HDR'>
                                        Company Address One:
                                    </TD>
                                    <TD>
                                        <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='company_address1' value='<?php echo $company_address1; ?>' size='20'>
                                    </td>
                                </tr>
                                <TR>
                                    <TD CLASS='TBL_ROW_HDR'>
                                        Company Address Two:
                                    </TD>
                                    <TD>
                                        <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='company_address2' value='<?php echo $company_address2; ?>' size='20'>
                                    </td>
                                </tr>
                                <TR>
                                    <TD CLASS='TBL_ROW_HDR'>
                                        Company City:
                                    </TD>
                                    <TD>
                                        <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='company_city' value='<?php echo $company_city; ?>' size='20'>
                                    </td>
                                </tr>
                                <TR>
                                    <TD CLASS='TBL_ROW_HDR'>
                                        Company State:
                                    </TD>
                                    <TD>
                                        <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='company_state' value='<?php echo $company_state; ?>' size='20'>
                                    </td>
                                </tr>
                                <TR>
                                    <TD CLASS='TBL_ROW_HDR'>
                                        Company Zip Code:
                                    </TD>
                                    <TD>
                                        <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='company_zip' value='<?php echo $company_zip; ?>' size='20'>
                                    </td>
                                </tr>
                                <TR>
                                    <TD CLASS='TBL_ROW_HDR'>
                                        Company Contact Name:
                                    </TD>
                                    <TD>
                                        <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='company_contact' value='<?php echo $company_contact; ?>' size='20'>
                                    </td>
                                </tr>
                                <TR>
                                    <TD CLASS='TBL_ROW_HDR'>
                                        Company Contact Phone:
                                    </TD>
                                    <TD>
                                        <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='company_phone' value='<?php echo $company_phone; ?>' size='20'>
                                    </td>
                                </tr>
                                <TR>
                                    <TD CLASS='TBL_ROW_HDR'>
                                        Company Contact Email:
                                    </TD>
                                    <TD>
                                        <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='company_email' value='<?php echo $company_email; ?>' size='20'>
                                    </td>
                                </tr>
                                <TR>
                                    <TD CLASS='TBL_ROW_HDR'>
                                        Company Credit Terms:
                                    </TD>
                                    <TD>
                                        <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='company_terms' value='<?php echo $company_terms; ?>' size='20'>
                                    </td>
                                </tr>

                                <TR>
                                    <TD CLASS='TBL_ROW_HDR'>
                                        Warehouse Name:
                                    </TD>
                                    <TD>
                                        <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='warehouse_name' value='<?php echo $warehouse_name; ?>' size='20'>
                                    </td>
                                </tr>
                                <TR>
                                    <TD CLASS='TBL_ROW_HDR'>
                                        Warehouse Address One:
                                    </TD>
                                    <TD>
                                        <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='warehouse_address1' value='<?php echo $warehouse_address1; ?>' size='20'>
                                    </td>
                                </tr>
                                <TR>
                                    <TD CLASS='TBL_ROW_HDR'>
                                        Warehouse Address Two:
                                    </TD>
                                    <TD>
                                        <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='warehouse_address2' value='<?php echo $warehouse_address2; ?>' size='20'>
                                    </td>
                                </tr>
                                <TR>
                                    <TD CLASS='TBL_ROW_HDR'>
                                        Warehouse City:
                                    </TD>
                                    <TD>
                                        <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='warehouse_city' value='<?php echo $warehouse_city; ?>' size='20'>
                                    </td>
                                </tr>
                                <TR>
                                    <TD CLASS='TBL_ROW_HDR'>
                                        Warehouse State:
                                    </TD>
                                    <TD>
                                        <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='warehouse_state' value='<?php echo $warehouse_state; ?>' size='20'>
                                    </td>
                                </tr>
                                <TR>
                                    <TD CLASS='TBL_ROW_HDR'>
                                        Warehouse Zip Code:
                                    </TD>
                                    <TD>
                                        <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='warehouse_zip' value='<?php echo $warehouse_zip; ?>' size='20'>
                                    </td>
                                </tr>
                                <TR>
                                    <TD CLASS='TBL_ROW_HDR'>
                                        <B>Warehouse Country:</B>
                                    </TD>
                                    <TD>
                                        <INPUT CLASS='TXT_BOX' type="text" NAME="warehouse_country" value='<?php echo $warehouse_country; ?>' SIZE="20">
                                    </TD>
                                </TR>

                                <TR>
                                    <TD CLASS='TBL_ROW_HDR'>
                                        Warehouse Contact:
                                    </TD>
                                    <TD>
                                        <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='warehouse_contact' value='<?php echo $warehouse_contact; ?>' size='20'>
                                    </td>
                                </tr>
                                <TR>
                                    <TD CLASS='TBL_ROW_HDR'>
                                        Warehouse Contact Phone:
                                    </TD>
                                    <TD>
                                        <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='warehouse_contact_phone' value='<?php echo $warehouse_contact_phone; ?>' size='20'>
                                    </td>
                                </tr>
                                <TR>
                                    <TD CLASS='TBL_ROW_HDR'>
                                        Warehouse Contact Email:
                                    </TD>
                                    <TD>
                                        <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='warehouse_contact_email' value='<?php echo $warehouse_contact_email; ?>' size='20'>
                                    </td>
                                </tr>
                                <TR>
                                    <TD CLASS='TBL_ROW_HDR'>
                                        Warehouse Other Contact:
                                    </TD>
                                    <TD>
                                        <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='warehouse_manager' value='<?php echo $warehouse_manager; ?>' size='20'>
                                    </td>
                                </tr>
                                <TR>
                                    <TD CLASS='TBL_ROW_HDR'>
                                        Warehouse Other Contact Phone:
                                    </TD>
                                    <TD>
                                        <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='warehouse_manager_phone' value='<?php echo $warehouse_manager_phone; ?>' size='20'>
                                    </td>
                                </tr>
                                <TR>
                                    <TD CLASS='TBL_ROW_HDR'>
                                        Warehouse Other Contact Email:
                                    </TD>
                                    <TD>
                                        <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='warehouse_manager_email' value='<?php echo $warehouse_manager_email; ?>' size='20'>
                                    </td>
                                </tr>
                                <tr>
                                    <TD CLASS='TBL_ROW_HDR'>
                                        Shipping/Receiving Hours:
                                    </TD>
                                    <td>
                                        <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='dock_details' value='<?php echo $dock_details; ?>' size='40'>
                                    </td>
                                </tr>
                                <tr>
                                    <td CLASS='TBL_ROW_HDR'>
                                        <B># of Dock Doors</B>
                                    </td>
                                    <td>
                                        <INPUT CLASS='TXT_BOX' type="text" NAME="noof_dock_doors" SIZE="40" value="<?php echo $noof_dock_doors; ?>">
                                    </td>
                                </tr>
                                <TR>
                                    <TD CLASS='TBL_ROW_HDR table_valign_top'>
                                        <B> Dock Doors Names:</B>
                                    </TD>
                                    <td>
                                        <div id="data_row">
                                            <input type="hidden" name="wh_id" value="<?php echo isset($id); ?>">
                                            <?php $dim_cnt = 0; ?>
                                            <?php
                                            db();
                                            $dd_query = "select * from loop_dock_doors_sortwh where srt_warehouse_id=?";
                                            $dd_res = db_query($dd_query, array("i"), array($id));
                                            while ($dd_row = array_shift($dd_res)) {
                                            ?>
                                                <table id="dock_door_tbl<?php echo $dim_cnt; ?>">
                                                    <tr>
                                                        <td>
                                                            <input type="hidden" name="dd_name_id<?php echo $dim_cnt; ?>" value="<?php echo $dd_row["
                                        id"]; ?>">
                                                            <input type="text" name="dock_doors_names<?php echo $dim_cnt; ?>" id="dock_doors_names<?php echo $dim_cnt; ?>" value="<?php echo $dd_row["
                                        dock_doors_names"]; ?>">
                                                        </td>
                                                        <td>&nbsp;<input type='button' class='remove_btn' name='btn_remove_s' value='X' onclick='remove_dock_door_nm(<?php echo $dim_cnt; ?>)'></td>
                                                    </tr>
                                                </table>
                                            <?php
                                                $dim_cnt = $dim_cnt + 1;
                                            }
                                            ?>

                                        </div>
                                        <div class="addmore_btn">
                                            <input type="hidden" name="dim_cnt" id="dim_cnt" value="<?php echo $dim_cnt; ?>">
                                            <input type="button" value="Add more" name="dockdoor_add_more" onClick="add_dock_door()">
                                        </div>
                                    </td>
                                </TR>

                                <TR>
                                    <TD CLASS='TBL_ROW_HDR'>
                                        Finished Goods Inventory Cubic Footage:
                                    </TD>
                                    <TD>
                                        <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='pallet_space' value='<?php echo $pallet_space; ?>' size='20'>
                                    </td>
                                </TR>

                                <TR>
                                    <TD CLASS='TBL_ROW_HDR'>
                                        Customer Sets Own Dock Appointment
                                    </TD>
                                    <TD>
                                        <input name="sets_own_dock_appointment" type="checkbox" value="yes" <?php if ($sets_own_dock_appointment == "yes") { ?> checked="checked" <?php  } else {
                                                                                                                                                                                } ?>>
                                    </TD>
                                </TR>
                                <TR>
                                    <TD CLASS='TBL_ROW_HDR'>
                                        Calendly Link
                                    </TD>
                                    <TD>
                                        <INPUT TYPE='TEXT' CLASS='TXT_BOX' name='calendly_link' value='<?php echo $calendly_link; ?>' size='40'>
                                    </TD>
                                </TR>

                                <TR>
                                    <TD CLASS='TBL_ROW_HDR'>
                                        Warehouse Notes:
                                    </TD>
                                    <TD>
                                        <?php $warehouse_notes = preg_replace("(<BR>)", "", $warehouse_notes); ?>
                                        <textarea CLASS='TXT_BOX' name="warehouse_notes" rows="4" cols="35"><?php echo $warehouse_notes; ?></textarea>
                                    </TD>
                                </TR>
                                <tr>
                                    <td>
                                    </td>
                                    <td>
                                        <?php $id = $myrow["id"]; ?>
                                        <input type="hidden" value="<?php echo $id; ?>" name="id">
                                        <input CLASS="BUTTON" type="submit" value="Save" name="submit">
                                    </td>
                                </tr>
                            </table>
                            <center>
                                <BR>
                        </form>
    <?php
                    } while ($myrow = array_shift($result));
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
    /*-------------------------------------------------
SEARCH AND ADD-NEW LINKS
-------------------------------------------------*/
    if (isset($proc) == "View") {
    ?>
        <a href="<?php echo $thispage; ?>?proc=&<?php echo $pagevars; ?>">Search</a><br>
        <?php
        if ($allowaddnew == "yes") {
        ?>
            <a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>
            <?php  } //END OF IF ALLOW ADDNEW 

        /*----------------------------------------------------------------------
VIEW RECORDS SECTION - VIEW SINGLE RECORDS
----------------------------------------------------------------------*/

        /*-------------------------------------------------------------------------------
VIEW RECORD SECTION 9992
-------------------------------------------------------------------------------*/
        if ($proc == "View") {
            echo "<DIV CLASS='PAGE_STATUS'>Viewing Record</DIV>";
            //***** BEGIN SEARCH RESULTS ****************************************************
            //THEN WE ARE SHOWING THE RESULTS OF A SEARCH


            /*-- SECTION: 9992SQL --*/
            //IF NO SEARCH WORDS TYPED, SHOW ALL RECORDS
            $sql = "SELECT * FROM loop_warehouse WHERE id='$id' $addl_select_crit ";
            if ($sql_debug_mode == 1) {
                echo "<BR>SQL: $sql<BR>";
            }
            db();
            $result = db_query($sql);
            if ($myrowsel = array_shift($result)) {
                do {
                    $id = $myrowsel["id"];
                    $company_name = $myrowsel["company_name"];
                    $company_address1 = $myrowsel["company_address1"];
                    $company_address2 = $myrowsel["company_address2"];
                    $company_city = $myrowsel["company_city"];
                    $company_state = $myrowsel["company_state"];
                    $company_zip = $myrowsel["company_zip"];
                    $company_phone = $myrowsel["company_phone"];
                    $company_email = $myrowsel["company_email"];
                    $company_terms = $myrowsel["company_terms"];
                    $company_contact = $myrowsel["company_contact"];
                    $warehouse_name = $myrowsel["warehouse_name"];
                    $warehouse_address1 = $myrowsel["warehouse_address1"];
                    $warehouse_address2 = $myrowsel["warehouse_address2"];
                    $warehouse_city = $myrowsel["warehouse_city"];
                    $warehouse_state = $myrowsel["warehouse_state"];
                    $warehouse_zip = $myrowsel["warehouse_zip"];
                    $warehouse_country = $myrowsel["warehouse_country"];
                    $warehouse_contact = $myrowsel["warehouse_contact"];
                    $warehouse_contact_phone = $myrowsel["warehouse_contact_phone"];
                    $warehouse_contact_email = $myrowsel["warehouse_contact_email"];
                    $warehouse_manager = $myrowsel["warehouse_manager"];
                    $warehouse_manager_phone = $myrowsel["warehouse_manager_phone"];
                    $warehouse_manager_email = $myrowsel["warehouse_manager_email"];
                    $dock_details = $myrowsel["dock_details"];
                    $sets_own_dock_appointment = $myrowsel["sets_own_dock_appointment"];
                    $calendly_link = $myrowsel["calendly_link"];
                    $warehouse_notes = $myrowsel["warehouse_notes"];
                    $other1 = $myrowsel["other1"];
                    $other2 = $myrowsel["other2"];
                    $other3 = $myrowsel["other3"];
                    $pallet_space = $myrowsel["pallet_space"];
            ?>
                    <TABLE>
                        <DIV CLASS='PAGE_OPTIONS'>
                            <?php if ($allowview == "yes") { ?><a href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&proc=View&<?php echo $pagevars; ?>">View</a>
                                <?php  } //END ALLOWVIEW 
                                ?><?php if ($allowedit == "yes") { ?>
                                <a href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&proc=Edit&<?php echo $pagevars; ?>">Edit</a>
                                <?php  } //END ALLOWEDIT 
                                ?><?php if ($allowdelete == "yes") { ?>
                                <a href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&proc=Delete&<?php echo $pagevars; ?>">Delete</a>
                            <?php  } //END ALLOWDELETE 
                            ?><br></span><span class="font_family_Ariel font_size_2">
                        </DIV>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>COMPANY_NAME:</TD>
                            <TD>
                                <DIV CLASS='TBL_ROW_DATA'><?php echo $company_name; ?> </DIV>
                            </TD>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>COMPANY_ADDRESS1:</TD>
                            <TD>
                                <DIV CLASS='TBL_ROW_DATA'><?php echo $company_address1; ?> </DIV>
                            </TD>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>COMPANY_ADDRESS2:</TD>
                            <TD>
                                <DIV CLASS='TBL_ROW_DATA'><?php echo $company_address2; ?> </DIV>
                            </TD>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>COMPANY_CITY:</TD>
                            <TD>
                                <DIV CLASS='TBL_ROW_DATA'><?php echo $company_city; ?> </DIV>
                            </TD>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>COMPANY_STATE:</TD>
                            <TD>
                                <DIV CLASS='TBL_ROW_DATA'><?php echo $company_state; ?> </DIV>
                            </TD>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>COMPANY_ZIP:</TD>
                            <TD>
                                <DIV CLASS='TBL_ROW_DATA'><?php echo $company_zip; ?> </DIV>
                            </TD>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>COMPANY_PHONE:</TD>
                            <TD>
                                <DIV CLASS='TBL_ROW_DATA'><?php echo $company_phone; ?> </DIV>
                            </TD>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>COMPANY_EMAIL:</TD>
                            <TD>
                                <DIV CLASS='TBL_ROW_DATA'><?php echo $company_email; ?> </DIV>
                            </TD>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>COMPANY_TERMS:</TD>
                            <TD>
                                <DIV CLASS='TBL_ROW_DATA'><?php echo $company_terms; ?> </DIV>
                            </TD>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>COMPANY_CONTACT:</TD>
                            <TD>
                                <DIV CLASS='TBL_ROW_DATA'><?php echo $company_contact; ?> </DIV>
                            </TD>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>WAREHOUSE_NAME:</TD>
                            <TD>
                                <DIV CLASS='TBL_ROW_DATA'><?php echo $warehouse_name; ?> </DIV>
                            </TD>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>WAREHOUSE_ADDRESS1:</TD>
                            <TD>
                                <DIV CLASS='TBL_ROW_DATA'><?php echo $warehouse_address1; ?> </DIV>
                            </TD>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>WAREHOUSE_ADDRESS2:</TD>
                            <TD>
                                <DIV CLASS='TBL_ROW_DATA'><?php echo $warehouse_address2; ?> </DIV>
                            </TD>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>WAREHOUSE_CITY:</TD>
                            <TD>
                                <DIV CLASS='TBL_ROW_DATA'><?php echo $warehouse_city; ?> </DIV>
                            </TD>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>WAREHOUSE_STATE:</TD>
                            <TD>
                                <DIV CLASS='TBL_ROW_DATA'><?php echo $warehouse_state; ?> </DIV>
                            </TD>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>WAREHOUSE_ZIP:</TD>
                            <TD>
                                <DIV CLASS='TBL_ROW_DATA'><?php echo $warehouse_zip; ?> </DIV>
                            </TD>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>WAREHOUSE_CONTACT:</TD>
                            <TD>
                                <DIV CLASS='TBL_ROW_DATA'><?php echo $warehouse_contact; ?> </DIV>
                            </TD>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>WAREHOUSE_CONTACT_PHONE:</TD>
                            <TD>
                                <DIV CLASS='TBL_ROW_DATA'><?php echo $warehouse_contact_phone; ?> </DIV>
                            </TD>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>WAREHOUSE_CONTACT_EMAIL:</TD>
                            <TD>
                                <DIV CLASS='TBL_ROW_DATA'><?php echo $warehouse_contact_email; ?> </DIV>
                            </TD>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>WAREHOUSE_MANAGER:</TD>
                            <TD>
                                <DIV CLASS='TBL_ROW_DATA'><?php echo $warehouse_manager; ?> </DIV>
                            </TD>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>WAREHOUSE_MANAGER_PHONE:</TD>
                            <TD>
                                <DIV CLASS='TBL_ROW_DATA'><?php echo $warehouse_manager_phone; ?> </DIV>
                            </TD>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>WAREHOUSE_MANAGER_EMAIL:</TD>
                            <TD>
                                <DIV CLASS='TBL_ROW_DATA'><?php echo $warehouse_manager_email; ?> </DIV>
                            </TD>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>DOCK_DETAILS:</TD>
                            <TD>
                                <DIV CLASS='TBL_ROW_DATA'><?php echo $dock_details; ?> </DIV>
                            </TD>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>Finished Goods Inventory Cubic Footage:</TD>
                            <TD>
                                <DIV CLASS='TBL_ROW_DATA'><?php echo $pallet_space; ?> </DIV>
                            </TD>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>sets_own_dock_appointment:</TD>
                            <TD>
                                <DIV CLASS='TBL_ROW_DATA'><?php echo $sets_own_dock_appointment; ?> </DIV>
                            </TD>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>calendly_link :</TD>
                            <TD>
                                <DIV CLASS='TBL_ROW_DATA'><?php echo $calendly_link; ?> </DIV>
                            </TD>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>WAREHOUSE_NOTES:</TD>
                            <TD>
                                <DIV CLASS='TBL_ROW_DATA'><?php echo $warehouse_notes; ?> </DIV>
                            </TD>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>OTHER1:</TD>
                            <TD>
                                <DIV CLASS='TBL_ROW_DATA'><?php echo $other1; ?> </DIV>
                            </TD>
                        <TR>
                            <TD CLASS='TBL_ROW_HDR'>OTHER2:</TD>
                            <TD>
                                <DIV CLASS='TBL_ROW_DATA'><?php echo $other2; ?> </DIV>
                            </TD>
            <?php
                } while ($myrowsel = array_shift($result));
                echo "</TR>\n</TABLE>";
            } //IF RESULT

        } //END OF PROC VIEW

        /*-------------------------------------------------------------------------------
END VIEW RECORD SECTION 9992
-------------------------------------------------------------------------------*/
    } // END IF PROC = "VIEW"
            ?>
            <?php
            if (isset($proc) == "Delete") {
                echo "<a href=\"manage_sortwh_mrg.php?posting=yes\">Back</a><br><br>";
            ?>
                <!-- <a href="<?php echo $thispage; ?>?proc=&<?php echo $pagevars; ?>">Search</a><br>-->
                <?php

                if ($allowaddnew == "yes") {
                ?>

                    <!-- <a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>  -->
                <?php  } //END OF IF ALLOW ADDNEW 

                /*----------------------------------------------------------------------
DELETE RECORD SECTION - THIS SECTION WILL CONFIRM/PERFORM DELETIONS
----------------------------------------------------------------------*/

                /*-------------------------------------------------------------------------------
DELETE RECORD SECTION 9995
-------------------------------------------------------------------------------*/
                ?>
                <DIV CLASS='PAGE_STATUS'>Deleting Record</DIV>
                <?php
                /*-- SECTION: 9995CONFIRM --*/
                if (!$delete) {
                ?>
                    <DIV CLASS='PAGE_OPTIONS'>
                        <br><br>Are you sure you want to delete this Sorting Warehouse?<br><br>
                        <strong>THIS CANNOT BE UNDONE!</strong><BR><br>
                        <a href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&delete=yes&proc=Delete&<?php echo $pagevars; ?>">Yes</a>
                        <a href="<?php echo $thispage; ?>?posting=yes<?php echo $pagevars; ?>">No</a>
                    </DIV>
            <?php
                } //IF !DELETE

                if ($delete == "yes") {

                    /*-- SECTION: 9995SQL --*/
                    $sql = "DELETE FROM loop_warehouse WHERE id='$id' $addl_select_crit ";
                    if ($sql_debug_mode == 1) {
                        echo "<BR>SQL: $sql<BR>";
                    }
                    db();
                    $result = db_query($sql);
                    if (empty($result)) {
                        //
                        $ddsql = "delete from loop_dock_doors_sortwh where srt_warehouse_id=?";
                        $res = db_query($ddsql, array("i"), array($id));
                        //
                        echo "<DIV CLASS='SQL_RESULTS'>Successfully Deleted</DIV>";
                    } else {
                        echo "Error Deleting Record (9995SQL)";
                    }
                } //END IF $DELETE=YES
                /*-------------------------------------------------------------------------------
END DELETE RECORD SECTION 9995
-------------------------------------------------------------------------------*/
            } // END IF PROC = "DELETE"

            ?>

            <?php
            if (isset($proc) == "inactive") {
                echo "<a href=\"manage_sortwh_mrg.php?posting=yes\">Back</a><br><br>";
            ?>
                <!-- <a href="<?php echo $thispage; ?>?proc=&<?php echo $pagevars; ?>">Search</a><br>-->
                <?php

                if ($allowaddnew == "yes") {
                ?>

                    <!-- <a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>  -->
                <?php  } //END OF IF ALLOW ADDNEW 

                ?>
                <DIV CLASS='PAGE_STATUS'>Updating inactive flag</DIV>
                <?php
                /*-- SECTION: 9995CONFIRM --*/
                if (!isset($inactive)) {
                ?>
                    <DIV CLASS='PAGE_OPTIONS'>
                        <br><br>Are you sure you want to inactive this Sorting Warehouse?<br><br>
                        <strong>THIS CANNOT BE UNDONE!</strong><BR><br>
                        <a href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&inactive=yes&proc=inactive&<?php echo $pagevars; ?>">Yes</a>
                        <a href="<?php echo $thispage; ?>?posting=yes<?php echo $pagevars; ?>">No</a>
                    </DIV>
            <?php
                } //IF !inactive

                if ($inactive == "yes") {

                    /*-- SECTION: 9995SQL --*/
                    $sql = "update loop_warehouse set Active=0 WHERE id='$id' $addl_select_crit ";
                    if ($sql_debug_mode == 1) {
                        echo "<BR>SQL: $sql<BR>";
                    }
                    db();
                    $result = db_query($sql);
                    if (empty($result)) {
                        echo "<DIV CLASS='SQL_RESULTS'>Successfully Updated</DIV>";

                        if (!headers_sent()) {
                            header('Location: manage_sortwh_mrg.php?posting=yes&rec_type=Sorting');
                            exit;
                        } else {
                            echo "<script type=\"text/javascript\">";
                            echo "window.location.href=\"manage_sortwh_mrg.php?posting=yes\&rec_type=Sorting\";";
                            echo "</script>";
                            echo "<noscript>";
                            echo "<meta http-equiv=\"refresh\" content=\"0;url=manage_sortwh_mrg.php?posting=yes\&rec_type=Sorting\" />";
                            echo "</noscript>";
                            exit;
                        }
                    } else {
                        echo "Error Updating Record (9995SQL)";
                    }
                } //END IF $DELETE=YES
                /*-------------------------------------------------------------------------------
END DELETE RECORD SECTION 9995
-------------------------------------------------------------------------------*/
            }

            ?>


            <BR>

            <BR>
            </span>






</body>

</html>