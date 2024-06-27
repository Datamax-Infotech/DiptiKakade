<?php
//require ("inc/header_session.php");

?>

<?php

require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");


?>
<!DOCTYPE HTML>

<html>

<head>
    <title>B2B Leaderboard - Unassigned List</title>

    <meta http-equiv="refresh" content="900" />
    <link rel="stylesheet" href="sorter/style_rep.css" />

    <style type="text/css">
    .txtstyle_color {
        font-family: arial;
        font-size: 12;
        height: 16px;
        background: #ABC5DF;
    }

    .txtstyle {
        font-family: arial;
        font-size: 12;
    }

    .style7 {
        font-size: xx-small;
        font-family: Arial, Helvetica, sans-serif;
        color: #333333;
        background-color: #FFCC66;
    }

    .style5 {
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
        text-align: center;
        background-color: #99FF99;
    }

    .style6 {
        text-align: center;
        background-color: #99FF99;
    }

    .style2 {
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
    }

    .style3 {
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
        color: #333333;
    }

    .style8 {
        text-align: left;
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
        color: #333333;
    }

    .style11 {
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
        color: #333333;
        text-align: center;
    }

    .style10 {
        text-align: left;
    }

    .style12 {
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
        color: #333333;
        text-align: right;
    }

    .style13 {
        font-family: Arial, Helvetica, sans-serif;
    }

    .style14 {
        font-size: x-small;
    }

    .style15 {
        font-size: small;
    }

    .style16 {
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
        background-color: #99FF99;
    }

    .style17 {
        background-color: #99FF99;
    }

    select,
    input {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 10px;
        color: #000000;
        font-weight: normal;
    }
    </style>
    <script type="text/javascript" src="sorter/jquery-latest.js"></script>
    <script type="text/javascript" src="sorter/jquery.tablesorter.js"></script>

    <SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
    <SCRIPT LANGUAGE="JavaScript" SRC="inc/general.js"></SCRIPT>
    <script LANGUAGE="JavaScript">
    document.write(getCalendarStyles());
    </script>
    <script LANGUAGE="JavaScript">
    var cal2xx = new CalendarPopup("listdiv");
    cal2xx.showNavigationDropdowns();

    function loadmainpg() {
        if (document.getElementById('date_from').value != "" && document.getElementById('date_to').value != "") {
            //document.frmactive.action = "adminpg.php";
        } else {
            alert("Please select date From/To.");
            return false;
        }
    }

    $(function() {
        $("table").tablesorter({
            debug: true
        })

    });
    </script>

</head>

<body>


    <br />
    <table border="0">
        <tr>
            <td width="700px" align="center" style="font-size:24pt;"><strong>Used Cardboard Boxes, Inc. <br />Unassigned
                    List</strong></td>
            <td width="200px" align="right"><img src="images/image001.jpg" width="70" height="70" /></td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
    </table>

    <!-- Load the page by default with old logic - do not apply date range-->
    <table border="0">
        <tr>
            <td valign="top">
                <table cellSpacing="1" cellPadding="1" border="0" width="500">
                    <tr>
                        <td class="txtstyle_color" align="center" style="font-size:14pt;"><strong>Unassigned
                                Company</strong></td>
                    </tr>
                </table>
                <table cellSpacing="1" cellPadding="1" border="0" width="500" id="table15" class="tablesorter">
                    <thead>
                        <tr>
                            <th width="170px" bgColor='#E4EAEB'><u>Company Name</u></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $sql = "SELECT ID, company FROM companyInfo where companyInfo.status = 0 and active = 1 order by dateCreated desc";
                        db_b2b();
                        $resulte = db_query($sql);

                        while ($row = array_shift($resulte)) {
                            echo "<tr><td bgColor='#E4EAEB'><a href='viewCompany.php?ID=" . $row["ID"] . "'>" . $row["company"] . "</a></td>";
                            echo "</tr>";
                        }

                        ?>
                    </tbody>
                </table>

            </td>
        </tr>
    </table>

</body>

</html>