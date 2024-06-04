<?php


require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");


echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >";

$img_res = "";
if ($_REQUEST['delete'] == "yes") {

	db();

	$img_qry2 = "Select * from order_issue_pictures Where id=" . $_REQUEST["order_img_id"];
	$img_res2 = db_query($img_qry2);
	$img_row2 = array_shift($img_res2);
	$del_img_name = $img_row2["order_img"];
	//
	$del_qry = "DELETE FROM order_issue_pictures WHERE id=" . $_REQUEST["order_img_id"];
	$del_result = db_query($del_qry);
	//
	$img_qry = "Select * from order_issue_pictures Where trans_id = " . $_REQUEST["rec_id"];
	$img_res = db_query($img_qry);
	$total_rec = tep_db_num_rows($img_res);

	if ($total_rec == 0) {
		$trans_query = db_query('Update loop_transaction_buyer set order_issue_pictures = 0, order_issue_pic_on= "", order_issue_pic_by="" where id = ?', array("i"), array($_REQUEST["rec_id"]));
	}
	//
	$msg_trans = "System generated log - Action taken on 'Order Issue Pictures' - image ($del_img_name) has been deleted on " . date("m/d/Y H:i:s") . " by " . $_COOKIE['userinitials'] . "<br>";

	$query1 = db_query('Insert into loop_transaction_notes (company_id, rec_type, rec_id, message, employee_id) select ?, ?, ?, ?, ?', array("i", "s", "i", "s", "s"), array($_REQUEST["warehouse_id"], $_REQUEST["rec_type"], $_REQUEST["rec_id"], $msg_trans, $_COOKIE['employeeid']));
	//

}
$img_qry = "Select * from order_issue_pictures Where trans_id = " . $_REQUEST["rec_id"] . " ORDER by id ASC";
$img_res1 = db_query($img_qry);
if (tep_db_num_rows($img_res1) > 0) {
?>
<table class="orderissue-style" border="0" style="width: 400px">

    <?php


		while ($img_row = array_shift($img_res)) {
		?>
    <tr bgColor="#e4e4e4" id="orderissue<?php echo $img_row["id"]; ?>">
        <td align="center" style="padding: 4px;" width="220px">
            <a href="#"
                onclick="view_orderissue_img('<?php echo 'orderissuepic/' . $img_row["order_img"]; ?>', <?php echo $img_row["id"]; ?>); return;">
                <img src="orderissuepic/<?php echo $img_row["order_img"]; ?>" width="50" height="auto">
            </a>
        </td>
        <td align="left" style="padding: 4px; padding-left: 12px;"><a
                style='color:#E00003; text-decoration: none; font-weight: 600;' href="javascript:void(0);"
                onclick="order_issue_img_delete(<?php echo $img_row["id"]; ?>,<?php echo $_REQUEST["rec_id"]; ?>,<?php echo $_REQUEST["warehouse_id"]; ?>,<?php echo $_REQUEST["ID"]; ?>)">
                X</a></td>
    </tr>
    <?php
		}
		?>
</table>
<?php
}
?>