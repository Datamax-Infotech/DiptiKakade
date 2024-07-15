<?php
session_start();
if (isset($_SESSION['loginid']) && $_SESSION['loginid'] > 0) {
	echo "<script type=\"text/javascript\">";
	echo "window.location.href=\"dashboard.php" . "\";";
	echo "</script>";
	echo "<noscript>";
	echo "<meta http-equiv=\"refresh\" content=\"0;url=dashboard.php" . "\" />";
	echo "</noscript>";
	exit;
}

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>UCBZeroWaste</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Montserrat:300,400,500,700"
        rel="stylesheet">
    <link rel="shortcut icon" href="images/ucbzerowaste-icon.png" type="image/png">

    <link href="css/header-footer.css" rel="stylesheet">
    <link href="css/loginnew.css" rel="stylesheet">
    <link href="css/inner.css" rel="stylesheet">
    <link href="css/inner-table.css" rel="stylesheet">
    <link href="css/request-a-pickup-form.css" rel="stylesheet">
    <link href="css/request-a-pickup-form1.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Titillium+Web" rel="stylesheet">

    <!--Menu =========================================================================================================================-->
    <link rel="stylesheet" href="menu/demo.css">
    <link rel="stylesheet" href="menu/navigation-icons.css">
    <link rel="stylesheet" href="menu/slicknav/slicknav.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Fira+Sans+Condensed:200,300,400,500,600,700,800"
        rel="stylesheet">

    <script type="text/javascript">
    function chkform() {
        if (document.getElementById("txtemail").value == "") {
            alert("Please enter the User Name.");
            return false;
        }
        if (document.getElementById("txtpassword").value == "") {
            alert("Please enter the Password.");
            return false;
        }

        return true;
    }

    function btnsendemlclick_eml_p() {
        var verifyCallback = function(response) {};

        var full_name, company, phone, email, tmp_element2;

        full_name = document.getElementById("full_name").value;

        tmp_element2 = document.getElementById("make_money");

        company = document.getElementById("company").value;

        phone = document.getElementById("phone").value;

        email = document.getElementById("email").value;

        if (full_name == "") {
            alert("Please enter your name.");
            return false;
        }

        if (company == "") {
            alert("Please enter the company name.");
            return false;
        }

        if (phone == "") {
            alert("Please enter phone no.");
            return false;
        }
        if (email == "") {
            alert("Please enter email.");
            return false;
        } else {
            var reg = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/
            if (reg.test(email)) {

            } else {
                alert('Invalid Email Address');
                return false;
            }
        }

        document.getElementById("hidden_sendemail").value = "inemailmode";
        tmp_element2.submit();

    }

    function btnsendemlclick_eml_reg(frmno) {
        var reg_first_name, reg_last_name, reg_company, reg_position, reg_phone, reg_email, reg_city, reg_state,
            reg_zip, tmp_element2;

        if (frmno == 1) {
            reg_first_name = document.getElementById("reg_first_name").value;
            tmp_element2 = document.getElementById("ucb_regitration1");
            reg_last_name = document.getElementById("reg_last_name").value;
            reg_company = document.getElementById("reg_company").value;
            reg_position = document.getElementById("reg_position").value;
            reg_phone = document.getElementById("reg_phone").value;
            reg_email = document.getElementById("reg_email").value;
            reg_city = document.getElementById("reg_city").value;
            reg_state = document.getElementById("reg_state").value;
            reg_zip = document.getElementById("reg_zip").value;
        }
        if (frmno == 2) {
            tmp_element2 = document.getElementById("ucb_regitration2");
            reg_first_name = document.getElementById("reg_first_name2").value;
            reg_last_name = document.getElementById("reg_last_name2").value;
            reg_company = document.getElementById("reg_company2").value;
            reg_position = document.getElementById("reg_position2").value;
            reg_phone = document.getElementById("reg_phone2").value;
            reg_email = document.getElementById("reg_email2").value;
            reg_city = document.getElementById("reg_city2").value;
            reg_state = document.getElementById("reg_state2").value;
            reg_zip = document.getElementById("reg_zip2").value;
        }

        if (frmno == 2) {
            document.getElementById("frmname2").value = "regwater";
        }

        if (reg_first_name == "") {
            alert("Please enter your First Name.");
            return false;
        }

        if (reg_last_name == "") {
            alert("Please enter your Last Name.");
            return false;
        }

        if (reg_phone == "") {
            alert("Please enter phone no.");
            return false;
        }
        if (reg_email == "") {
            alert("Please enter email.");
            return false;
        } else {
            var reg = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/
            if (reg.test(reg_email)) {

            } else {
                alert('Invalid Email Address');
                return false;
            }
        }

        /*if (frmno == 1)
        {
        	response = grecaptcha.getResponse(widgetId2);
        	if (response == ""){
        		alert("Robot verification failed, please try again.");
        		return false;
        	}
        }
        
        if (frmno == 2)
        {
        	response = grecaptcha.getResponse(widgetId3);
        	if (response == ""){
        		alert("Robot verification failed, please try again.");
        		return false;
        	}
        }*/

        tmp_element2.submit();
    }
    </script>

</head>

<body>
    <?php

	db();

	$_SESSION['pgname'] = "login";

	$loginchk = "";
	if (isset($_REQUEST["txtemail"])) {
		$rec_found = "no";
		$eml = $_REQUEST["txtemail"];

		$sql = "SELECT loginid, companyid, loginkey FROM supplierdashboard_usermaster WHERE user_name=? and password=? and activate_deactivate = 1";
		//echo $sql . "<br>";
		//echo $eml . " " . $_REQUEST["txtpassword"] . "<br>";
		$result = db_query($sql, array("s", "s"), array($eml, $_REQUEST["txtpassword"]));
		while ($myrowsel = array_shift($result)) {
			$rec_found = "yes";

			//echo "Chk " . $_REQUEST["txtpassword"] . "<br>";
			$warehouse_id = 0;
			$sql1 = "SELECT id FROM loop_warehouse WHERE b2bid=? ";
			$result1 = db_query($sql1, array("i"), array($myrowsel["company_id"]));
			while ($myrowsel1 = array_shift($result1)) {
				$warehouse_id = $myrowsel1["id"];
			}

			session_start();
			$_SESSION['loginid'] = $myrowsel["loginid"];
			$_SESSION['companyid'] = $myrowsel["company_id"];

			echo "<script type=\"text/javascript\">";
			echo "window.location.href=\"dashboard.php" . "\";";
			echo "</script>";
			echo "<noscript>";
			echo "<meta http-equiv=\"refresh\" content=\"0;url=dashboard.php" . "\" />";
			echo "</noscript>";
			exit;
		}

		if ($rec_found == "no") {
			$loginchk = "1";
		}
	}
	?>

    <header id="header" style="display:flex;">
        <div class="container-fluid" style="display:flex;align-self:center;">
            <div id="logo" class="pull-left" style="display:flex;align-self:center;">
                <a href="index.php"><img src="images/UCBZeroWaste_logo.png" height="60" alt="" title="" /></a>
            </div>

        </div>
    </header><!-- #header -->

    <!--Page title display here-->
    <div class="top-title">
        <h1>Login to W.A.T.E.R.</h1>
    </div>
    <!--End Page title-->
    <!--Main Content-->
    <div class="main-inner-container">
        <div class="inner-container1">
            <!--Code for Most recent pickup -->
            <div id="no-more-tables">
                <div class="login-bg-image-main">
                    <div class="login-bg-image">
                        <div id="form-parent">
                            <div id="form-main">
                                <div class="login-form-container login">
                                    <div class="login-form">
                                        <?php
										if ($loginchk == '1') {
											echo ('<p style=color:red;>User name and password not correct, please check.</p>');
										}
										?>

                                        <form class="loginfrm" action="login.php" method="post"
                                            onsubmit="return chkform();">
                                            <div class="formlabel">Username: <input type="text" name="txtemail"
                                                    id="txtemail" value="" placeholder="Enter User name"></div>
                                            <div class="formlabel">Password: &nbsp;<input type="password"
                                                    name="txtpassword" id="txtpassword" value=""
                                                    placeholder="Enter Password"></div>
                                            <div><input type="submit" class="login-button" value="Log In"></div>
                                        </form>
                                        <div class="forgot_password">
                                            <a href="forgot_username.php" style="text-decoration: underline;">Forgot
                                                Password?</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--End Table-->
            <!--End Code for Most recent pickup -->

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

</body>

</html>