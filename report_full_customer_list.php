<?php

//report_full_customer_list.php



require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";
require("inc/functions_mysqli.php");



$sorturl = "dashboardnew_account_pipeline.php?show=fullistcustomer";




$count = 0;



if ($_REQUEST["mgrview"] == "yes" && $_REQUEST["inmgrview"] == "No") {
} else {
}





?>

<?php

if ($_REQUEST["mgrview"] == "yes" && $_REQUEST["inmgrview"] == "No") {

?>

<style>
.fullcust tr:hover td {
    background-color: #F6F6F6;
}
</style>

<link rel="stylesheet" type="text/css" href="css/new_header-dashboard.css" />

<div style="width:75%; float:left; margin-left:20px;">

    <?php if ($_REQUEST["mgrview"] == "yes") {		?>

    <table cellpadding="1" cellspacing="1" class="fullcust">

        <tr>

            <td align="middle">Select User:</td>

            <td>

                <select name="emp_selected" onchange="javascript:showfullcustomerlist(this.value,'ASC','', 'No');">

                    <option value="">All</option>

                    <?php

							$sql = "SELECT id, b2b_id, name from `loop_employees` where leaderboard = 1 order by b2b_id";

							db();
							$result = db_query($sql);

							foreach ($result as $urow) {

								echo '<option value="' . $urow['b2b_id'] . '" ' . (($urow['b2b_id'] == $_REQUEST["emp_selected"]) ? 'selected' : '') . ' > ' . $urow['name'] . ' </option>';
							}

							?>

            </td>

        </tr>

    </table> <br>

    <?php 	}	?>





    <?php



		$show_number = 250;





		if ($_REQUEST['emp_selected'] != "") {

			$x = "SELECT * from loop_employees WHERE b2b_id = '" . $_REQUEST['emp_selected'] . "'";
		} else {

			$x = "SELECT * from loop_employees WHERE b2b_id = '" . $_COOKIE["b2b_id"] . "'";
		}


		db();
		$viewres = db_query($x);

		$row = array_shift($viewres);

		$tmp_view = $row['views'];

		$viewin = explode(",", $tmp_view);



		//echo $viewin . " - " . $_REQUEST['emp_selected'] . " - " . $show_number; 

		showStatusesDashboard($viewin, $_REQUEST['emp_selected'], $show_number, '', 0, 'fullistcustomer');

		?>

</div>

<?php

}

?>