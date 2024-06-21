<?php


require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");


$today = date("m/d/Y");
$todayfile = date("m-d-Y");
$today_crm = date("Ymd");
$warehouse_id = $_POST["warehouse_id"];
$id = $_POST["bol_id"];
$rec_type = $_POST["rec_type"];
$user = $_COOKIE['userinitials'];
$usr_file = $_POST["file"];
$usr_employee = $user;
$usr_date = $today;
$rec_id = $_POST["rec_id"];
$recipient = $_POST["recipient"];
srand((int) ((float) microtime() * 1000000));
$random_number = rand();

if ($_FILES["file"]["size"] < 10000000) {

    if ($_FILES["file"]["error"] > 0) {

        echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    } else {

        echo "UCB Loop System <br><br>";
        echo "Upload: " . $_FILES["file"]["name"] . "<br />";
        echo "Type: " . $_FILES["file"]["type"] . "<br />";
        echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
        echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";
        if (file_exists("signedbols/" . $todayfile . "_" . $random_number . "_" . $_FILES["file"]["name"])) {

            echo $_FILES["file"]["name"] . " already exists. ";
        } else {

            $chk_if_file_pdf = "no";
            if ($_FILES["file"]["type"] == "application/pdf") {

                $chk_if_file_pdf = "yes";
            }

            if ($chk_if_file_pdf == "no") {

                //check if its image file
                if (!getimagesize($_FILES['file']['tmp_name'])) {
                    echo "<font color=red>" . $_FILES["file"]["name"] . " file not uploaded, this file type is restricted.</font>";
                    echo "<script>alert('" . $_FILES["file"]["name"] . " file not uploaded, this file type is restricted.');</script>";
                    redirect($_SERVER['HTTP_REFERER']);
                }
            }

            $blacklist = array(".php", ".phtml", ".php3", ".php5", ".php4", ".js", ".shtml", ".pl", ".py");

            foreach ($blacklist as $file) {
                if (preg_match("/$file\$/i", $_FILES['file']['name'])) {
                    echo "<font color=red>" . $_FILES["file"]["name"] . " file not uploaded, this file type is restricted.</font>";
                    echo "<script>alert('" . $_FILES["file"]["name"] . " file not uploaded, this file type is restricted.');</script>";
                    redirect($_SERVER['HTTP_REFERER']);
                }
            }

            move_uploaded_file(
                $_FILES["file"]["tmp_name"],
                "signedbols/" . $todayfile . "_" . $random_number . "_" .  $_FILES["file"]["name"]
            );
            echo "Stored in: " . "signedbols/" . $_FILES["file"]["name"];
        }
    }
} else {

    echo $_FILES["file"]["name"] . " file size is too big, upload failed.";
    exit;
}

$sql = "UPDATE loop_bol_files SET bol_signed_file_name = '" . $todayfile . "_" . $random_number . "_" .  $_FILES["file"]["name"] . "', bol_signed_employee = '" . $usr_employee . "', bol_signed_date = '" . $usr_date . "' WHERE id = '" . $id . "'";

db();
$result = db_query($sql);

$sql = "UPDATE loop_transaction_buyer SET bol_signed_uploaded = '1' WHERE id = '" . $rec_id . "'";

db();
$result = db_query($sql);

$message = "<strong>Note for Transaction # ";
$message .=  $rec_id;
$message .= "</strong>: ";
$message .=  $usr_employee;
$message .= " Uploaded a Sort Report: ";
$message .= $_FILES["file"]["name"];

$sql_crm = "INSERT INTO loop_crm  ( warehouse_id, message_date, employee, comm_type, message) VALUES ( '" . $warehouse_id . "', '" . $today_crm . "', '" . $usr_employee . "', '5', '" . $message . "')";

db();
$result_crm = db_query($sql_crm);

redirect($_SERVER['HTTP_REFERER']);