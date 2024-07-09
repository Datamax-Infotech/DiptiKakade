<?php

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

db();
// function getnickname($warehouse_name, $b2bid)
// {
//     $nickname = "";
//     if ($b2bid > 0) {
//         db_b2b();
//         $sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = ?";
//         $result_comp = db_query($sql, array("i"), array($b2bid));
//         while ($row_comp = array_shift($result_comp)) {
//             if ($row_comp["nickname"] != "") {
//                 $nickname = $row_comp["nickname"];
//             } else {
//                 $tmppos_1 = strpos($row_comp["company"], "-");
//                 if ($tmppos_1 != false) {
//                     $nickname = $row_comp["company"];
//                 } else {
//                     if ($row_comp["shipCity"] <> "" || $row_comp["shipState"] <> "") {
//                         $nickname = $row_comp["company"] . " - " . $row_comp["shipCity"] . ", " . $row_comp["shipState"];
//                     } else {
//                         $nickname = $row_comp["company"];
//                     }
//                 }
//             }
//         }
//         db();
//     } else {
//         $nickname = $warehouse_name;
//     }
//     return $nickname;
// }
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Untitled Document</title>
    <style>
    .th_style {
        font-size: xx-small;
        background-color: #FF9900;
        font-family: Arial, Helvetica, sans-serif;
        color: #333333;
    }

    .style12_n {
        font-size: xx-small;
        font-family: Arial, Helvetica, sans-serif;
        color: #333333;
        text-align: left !important;
    }

    .style12_num {
        font-size: xx-small;
        font-family: Arial, Helvetica, sans-serif;
        color: #333333;
        text-align: right !important;
    }

    .style12_tot {
        font-size: xx-small;
        font-family: Arial, Helvetica, sans-serif;
        color: #333333;
        font-weight: bold;
        text-align: right !important;
    }
    </style>
</head>

<body>
    <table cellpadding="3">
        <tr>
            <th class="th_style">
                ID
            </th>
            <th class="th_style">
                Company Name
            </th>
            <th class="th_style">
                Email Sent Date
            </th>
        </tr>
        <?php

        $date_from = $_REQUEST["date_from_val"];
        $date_to = $_REQUEST["date_to_val"];
        $date_from_val = date("Y-m-d", strtotime($date_from));
        $date_to_val_org = date("Y-m-d", strtotime($date_to));
        $date_to_val = date("Y-m-d", strtotime('+1 day', strtotime($date_to)));

        //
        //$sql = "SELECT *,loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.id AS I from loop_transaction_buyer INNER JOIN loop_transaction_buyer_truck_felloff on loop_transaction_buyer.id=loop_transaction_buyer_truck_felloff.trans_rec_id where `ignore` = 0 and customer_flg=1 or customer_flg=2 and (email_sendon between '" . $date_from_val. "' AND '" . $date_to_val. "')";
        $sql = "Select *, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.id AS I From loop_transaction_buyer_truck_felloff INNER JOIN loop_transaction_buyer on loop_transaction_buyer.id=loop_transaction_buyer_truck_felloff.trans_rec_id where `ignore` = 0 and (customer_flg=1 or customer_flg=2) and (email_sendon >='" . $date_from_val . "') AND (email_sendon <= '" . $date_to_val . " 23:59:59')";

        //echo $dt_view_qry;
        db();
        $dt_view_res = db_query($sql);
        while ($fb_rec = array_shift($dt_view_res)) {

            $wqry = "select * from loop_warehouse where id=" . $fb_rec["D"];
            db();
            $wres = db_query($wqry);
            $wrow = array_shift($wres);
            $comp_id = $wrow["b2bid"];
            $comp_name = $wrow["company_name"];
            $company_name = getnickname($comp_name, $comp_id);
            $rec_display = "buyer_ship";

        ?>
        <tr>
            <td bgColor="#e4e4e4" class="style12_n">
                <u><a target="_blank"
                        href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $comp_id; ?>&show=transactions&warehouse_id=<?php echo $fb_rec["D"]; ?>&proc=View&searchcrit=&rec_type=Supplier&id=<?php echo $fb_rec["D"]; ?>&rec_id=<?php echo $fb_rec['I']; ?>&display=<?php echo $rec_display; ?>"><?php echo $fb_rec['I']; ?></a></u>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <a target="_blank"
                    href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $comp_id; ?>&show=transactions&warehouse_id=<?php echo $fb_rec["D"]; ?>&proc=View&searchcrit=&rec_type=Supplier&id=<?php echo $fb_rec["D"]; ?>&rec_id=<?php echo $fb_rec['I']; ?>&display=<?php echo $rec_display; ?>">
                    <?php
                        echo $company_name;
                        ?>
                </a>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php
                    $email_sendon = date("m/d/Y", strtotime($fb_rec['email_sendon']));
                    echo $email_sendon; ?>
            </td>
        </tr>

        <?php
        }
        ?>
    </table>
</body>

</html>