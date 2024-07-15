<?php

session_start();
//if (isset($_SESSION['loginid']) && $_SESSION['loginid'] > 0) {

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=devidev-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Change Password</title>
    <link rel="icon" href="images/logo5.png" type="image/png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>
    jQuery(window).load(function() {
        jQuery(".hameid-loader-overlay").fadeOut(500);
        $(".cdev").circlos();
        $('.counter_1').each(function() {
            var $this = $(this),
                countTo = $this.attr('data-count');

            $({
                countNum: $this.text()
            }).animate({
                    countNum: countTo
                },

                {
                    duration: 1000,
                    easing: 'linear',
                    step: function() {
                        $this.text(commaSeparateNumber(Math.floor(this.countNum)));
                    },
                    complete: function() {
                        $this.text(commaSeparateNumber(this.countNum));
                        //alert('finished');
                    }
                }
            );

        });

        function commaSeparateNumber(val) {
            while (/(\d+)(\d{3})/.test(val.toString())) {
                val = val.toString().replace(/(\d+)(\d{3})/, '$1' + ',' + '$2');
            }
            return val;
        }

        counts2(132.323);
        counts3(52453);

    });
    </script>
    <style>
    .hameid-loader-overlay {
        width: 100%;
        height: 100%;
        background: url('images/Preloader_1.gif') center no-repeat #FFF;
        z-index: 99999;
        position: fixed;
    }
    </style>
    <link href="css/header-footer.css" rel="stylesheet">
    <link href="css/water.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Work+Sans" rel="stylesheet">
    <!--Menu =========================================================================================================================-->
    <link rel="stylesheet" type="text/css" href="menu-new/styles.css">
    <script src="menu-new/script.js"></script>
    <script type="text/javascript" src="menu-new/js/jquery.js"></script>

    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
    <!--End Menu ====================================================================================================================-->
    <!--Counter Files =========================================================================================================================-->
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
	-->
    <link rel="stylesheet" href="counter-files/counter-style.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

    <!--End Counter Files  ====================================================================================================================-->
</head>

<body>

    <!-- [ LOADERs ]
	================================================================================================================================-->

    <div class="hameid-loader-overlay"></div>
    <!-- [ /PRELOADER ]
	=============================================================================================================================-->
    <!--==========================
	    Header
	  ============================-->
    <header id="header" class="header-scrolled">
        <div class="container-fluid">

            <div id="logo" class="pull-left"> <a href="https://www.ucbzerowaste.com/"><img src="images/UCB-logo.jpg"
                        alt="" title="" /></a>
            </div>

            <div class="menu-container">
                <div id="cssmenu">
                    <ul>
                        <li><a href="index.php#section_2">
                                <div class="icon"><img src="images/icon-01.png" /></div>Go Zero Waste
                            </a></li>
                        <li><a href="index.php#section_8">
                                <div class="icon"><img src="images/icon-04.png" /></div>Track Your Waste
                            </a></li>
                        <li><a href="sell-to-ucb.php">
                                <div class="icon"><img src="images/icon-02.png" /></div>Sell to UCBZeroWaste
                            </a></li>
                        <li><a href="about-us.php">
                                <div class="icon"><img src="images/icon-05.png" /></div>About Us
                            </a></li>
                    </ul>
                </div>
            </div>

            <div class="navbar-client-logo">
                <div class="login-form">

                </div>
            </div>


        </div>
    </header>

    <div class="video-wrapper">
        <div class="full-video-container">
            <div class="video-container">
                <video id="video" preload="auto" poster="images/video_img.jpg"
                    style="opacity: 1; position: absolute; min-width: 100%; min-height: 890px; left: 0px; top: -339px;"
                    class="full-video" autoplay loop muted width="1583" height="890">
                    <source src="images/file.mp4" type="video/mp4">
                </video>

            </div>

        </div>
    </div>

    <?php

	$_SESSION['pgname'] = "change-password";

	require "../mainfunctions/database.php";
	require "../mainfunctions/general-functions.php";

	db();

	$rec_found = "";

	$email_send = "no";
	$pwd_blank = "";
	if (isset($_REQUEST["hd_change_upd"])) {
		if ($_REQUEST["hd_change_upd"] == "yes") {

			if ($_REQUEST["txtpassword_new"] != "") {
				$user_id = 0;
				$sql = "Select * from supplierdashboard_usermaster where user_name = ? and password = ?";
				$result = db_query($sql, array("s", "s"), array($_REQUEST["txtusername"], $_REQUEST["txtpassword_old"]));
				$rec_found = "no";
				$tempstr = "";
				while ($rq = array_shift($result)) {
					$rec_found = "yes";
					$user_id = $rq["loginid"];
				}

				if ($rec_found == "yes") {
					db_query("Update supplierdashboard_usermaster set password = ? where loginid = ?", array("s", "i"), array($_REQUEST["txtpassword_new"], $user_id));
				}
			} else {
				$pwd_blank = "yes";
			}
		}
	}
	?>

    <!-- #section1 -->
    <div class="first-container">
        <form name="form1" method="post" action="change-password.php">
            <div class="forgot-box1">
                <img src="images/about/forgot-password.jpg" alt="" />
                <h2>Update password</h2>
                <?php if ($rec_found == "no") { ?>
                <h3>
                    <font color=red>Entered user name and password not found in our database. Please check.</font>
                </h3>
                <?php } elseif ($rec_found == "yes") { ?>
                <h3>
                    <font color=green>Password updated successfully.</font>
                </h3>
                <?php } elseif ($pwd_blank == "yes") { ?>
                <h3>
                    <font color=red>Please enter the new password.</font>
                </h3>
                <?php } else { ?>
                <h3>Enter your user name and password.</h3>
                <?php } ?>

                <div class="forgot-box2">
                    <input type="hidden" name="hd_change_upd" id="hd_change_upd" value="yes" />
                    <table border="0" align="center" width="100%" style="margin-left: 200px;">
                        <tbody>
                            <tr>
                                <td width="20%" align="left"><label for="user_name">Enter user name : </label></td>
                                <td width="70%" align="left"> <input name="txtusername" type="text" class="form_text1"
                                        id="txtusername" placeholder="Enter user name">
                                </td>
                            </tr>
                            <tr>
                                <td width="20%" align="left"><label for="user_name">Enter existing password : </label>
                                </td>
                                <td width="70%" align="left"> <input name="txtpassword_old" type="password"
                                        class="form_text1" id="txtpassword_old" placeholder="Enter Password">
                                </td>
                            </tr>
                            <tr>
                                <td width="20%" align="left"><label for="user_name">Enter new password : </label></td>
                                <td width="70%" align="left"><input name="txtpassword_new" type="password"
                                        class="form_text1" id="txtpassword_new" placeholder="Enter New Password">
                                </td>
                            </tr>
                            <tr>
                                <td width="20%" align="left">&nbsp;</td>
                                <td width="70%" align="left"><input type="submit" class="button4"
                                        value="Update Password"> </td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- <div class="text_field">						
							<input name="txtusername" type="text" class="form_text1" id="txtusername" placeholder="Enter user name">
						</div>
						<div class="text_field">
							<input name="txtpassword_old" type="password" class="form_text1" id="txtpassword_old" placeholder="Enter Password">
						</div>
						<div class="text_field">
							<input name="txtpassword_new" type="password" class="form_text1" id="txtpassword_new" placeholder="Enter New Password">
						</div>
						<div class="text_field"><input type="submit" class="button4" value="Update Password"> </div> -->
                </div>

            </div>

        </form>
    </div>
    <!-- #section1 -->

    <div class="footer">
        <?php require("mainfunctions/footerContent.php");	?>
    </div>
</body>

</html>
<?php

/*}else{
	echo "<script type=\"text/javascript\">";
	echo "window.location.href=\"index.php" . "\";";
	echo "</script>";
	echo "<noscript>";
	echo "<meta http-equiv=\"refresh\" content=\"0;url=index.php" . "\" />";
	echo "</noscript>"; exit;
}*/
?>