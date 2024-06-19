<?php

require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");


db();

if (isset($_REQUEST["bol_received_ignore"])) {

    if ($_REQUEST["bol_received_ignore"] == 1) {

        $qry = "Update loop_bol_files set `bol_received_ignore` = 1, `bol_received_ignore_date` = ?, `bol_received_ignore_by` = ? WHERE trans_rec_id = ?";

        $res1 = db_query($qry, array("s", "s", "i"), array(date("Y-m-d H:i:s"), $_COOKIE['userinitials'], $_REQUEST["rec_id"]));

        $sql = "SELECT * FROM loop_bol_files WHERE trans_rec_id=" . $_REQUEST["rec_id"];

        db();
        $so_view_res = db_query($sql);
        $so_view_row = array_shift($so_view_res);

?>

<font size="1" Face="arial">
    <?php
            if ($so_view_row["bol_received_ignore"] == 1) {
                echo "Step is ignore by " . $so_view_row["bol_received_ignore_by"] . " on " . date("m/d/Y H:i:s", strtotime($so_view_row["bol_received_ignore_date"])) . " CT";
            } else {
            ?>
    <input style="cursor:pointer;" id="btnpo_ignore" type="button" value="Ignore this Step"
        onclick="parent.bol_ignore('bol_received_ignore', <?php echo $_REQUEST["rec_id"]; ?>,<?php echo $_REQUEST["warehouse_id"]; ?>);">
    <?php
            }
            ?>
</font>
<?php

    }
}

?>