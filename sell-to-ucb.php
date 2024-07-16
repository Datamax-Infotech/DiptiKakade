<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=devidev-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Sell to UCBZeroWaste</title>
    <link rel="icon" href="images/logo5-new.png" type="image/png">
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
    <link href="css/header-footer-new.css" rel="stylesheet">
    <link href="css/sell.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Work+Sans" rel="stylesheet">
    <!--Menu =========================================================================================================================-->
    <link rel="stylesheet" type="text/css" href="menu-new/styles.css">
    <script src="menu-new/script.js"></script>
    <script type="text/javascript" src="menu-new/js/jquery.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
    <!--End Menu ====================================================================================================================-->
    <!--Counter Files =========================================================================================================================-->
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
-->
    <link rel="stylesheet" href="counter-files/counter-style.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

    <!--End Counter Files  ====================================================================================================================-->
    <script>
    var widgetId1;
    var onloadCallback = function() {
        widgetId1 = grecaptcha.render('g_captcha_id', {
            'sitekey': '6LcvAF0fAAAAAN2Qb4XUS9kZQ76WBT1YGA_9D6Bo',
            'theme': 'light'
        });
    };

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

    /*----------------Registration form code-----------------------*/
    function btnsendemlclick_eml_sellto() {
        var first_name, last_name, company, position, phone, email, city, state, zip, tmp_element2;

        first_name = document.getElementById("reg_first_name").value;

        tmp_element2 = document.getElementById("reg_sellto_UCB");

        last_name = document.getElementById("reg_last_name").value;

        company = document.getElementById("reg_company").value;

        position = document.getElementById("reg_position").value;

        phone = document.getElementById("reg_phone").value;

        email = document.getElementById("reg_email").value;

        city = document.getElementById("reg_city").value;

        state = document.getElementById("reg_state").value;
        zip = document.getElementById("reg_zip").value;


        if (first_name == "") {
            alert("Please enter your First Name.");
            return false;
        }

        if (last_name == "") {
            alert("Please enter your Last Name.");
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

        response = grecaptcha.getResponse(widgetId1);
        if (response == "") {
            alert("Robot verification failed, please try again.");
            return false;
        }

        tmp_element2.submit();
    }
    /*----------------End Registration form code-----------------------*/
    </script>


    <!-- Facebook Pixel Code -->
    <script>
    ! function(f, b, e, v, n, t, s) {
        if (f.fbq) return;
        n = f.fbq = function() {
            n.callMethod ?
                n.callMethod.apply(n, arguments) : n.queue.push(arguments)
        };
        if (!f._fbq) f._fbq = n;
        n.push = n;
        n.loaded = !0;
        n.version = '2.0';
        n.queue = [];
        t = b.createElement(e);
        t.async = !0;
        t.src = v;
        s = b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t, s)
    }(window, document, 'script',
        'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '1109377375928443');
    fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1" src="https://www.facebook.com/tr?id=1109377375928443&ev=PageView
	&noscript=1" />
    </noscript>
    <!-- End Facebook Pixel Code -->


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

            <div id="logo" class="pull-left">
                <a href="index.php"><img src="images/UCB-logo-new.jpg" alt="" title="" /></a>
            </div>

            <div class="navbar-client-logo1">
                <div class="login1"><a href="login.php">Login to W.A.T.E.R.</a></div>
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
                        <li class="active"><a href="sell-to-ucb.php">
                                <div class="icon"><img src="images/icon-02.png" /></div>Sell to UCBZeroWaste
                            </a></li>
                        <li><a href="about-us.php">
                                <div class="icon"><img src="images/icon-05.png" /></div>About Us
                            </a></li>
                    </ul>
                </div>
            </div>
            <?php
			require "../mainfunctions/database.php";
			require "../mainfunctions/general-functions.php";

			db();

			$_SESSION['pgname'] = "";

			$loginchk = "";
			if (isset($_REQUEST["txtemail"])) {
				$rec_found = "no";
				$eml = $_REQUEST["txtemail"];

				$sql = "SELECT loginid, companyid, loginkey FROM supplierdashboard_usermaster WHERE user_name=? and password=? and activate_deactivate = 1";
				$result = db_query($sql, array("s", "s"), array($eml, $_REQUEST["txtpassword"]));
				while ($myrowsel = array_shift($result)) {
					$rec_found = "yes";

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

            <div class="navbar-client-logo">
                <div class="login-form">
                    <?php
					if ((isset($loginchk)) && ($loginchk == '1')) {
						echo ('<p style=color:red;>User name/password incorrect, please check.</p>');
					}
					?>
                    <form class="loginfrm" action="sell-to-ucb.php" method="post" onsubmit="return chkform();">
                        <div class="nav-bar-box1">
                            <div class="login">Login to W.A.T.E.R.</div>
                        </div>

                        <div class="nav-bar-box2">
                            <div class="formlabel"><input type="text" value="Username" class="form_text"></div>
                            <div class="formlabel"><input type="password" value="Password" class="form_text"></div>
                            <div class="forgot_password"><a href="forgot-password.php">forgot password or user name?</a>
                            </div>
                        </div>

                        <div class="nav-bar-box3">
                            <div class="login-bottom"><input type="submit" class="login-button" value="Log In"></div>
                        </div>
                    </form>

                </div>
            </div>


        </div>
    </header>

    <div class="slider">
        <div class="video-wrapper">
            <div class="full-video-container">
                <div class="video-container">

                    <img src="images/waste-banner.jpg" alt="">
                    <div class="overlay-desc">
                        <div id="messageBox">
                            <div class="video-content">
                                <h1>Sell to UCBZeroWaste</h1>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- #section1 -->
    <div class="first-container">
        <div class="section1-box1">

            <div class="section1-box1-img"><img src="images/buy-products.jpg" alt="" /></div>
            <h2>Start Making Money from your Waste Stream </h2>
            <p>We buy used cardboard boxes, gaylord totes, pallets, supersaks, barrels, drums, IBC Totes and Dry and Wet
                Organic Waste for more than recycling rates.</p>

        </div>

        <div class="section1-box2">
            <form name="reg_sellto_UCB" id="reg_sellto_UCB" action="sendemail_registration_save.php" method="post">
                <input type="hidden" name="frmname" id="frmname" value="selltoucb" />
                <div class="text_field"><input name="reg_first_name" type="text" class="form_text1" id="reg_first_name"
                        placeholder="First Name"></div>
                <div class="text_field"><input name="reg_last_name" type="text" class="form_text1" id="reg_last_name"
                        placeholder="Last Name"></div>
                <div class="text_field"><input name="reg_company" type="text" class="form_text1" id="reg_company"
                        placeholder="Company"></div>
                <div class="text_field"><input name="reg_position" type="text" class="form_text1" id="reg_position"
                        placeholder="Position"></div>
                <div class="text_field"><input name="reg_phone" type="text" class="form_text1" id="reg_phone"
                        placeholder="Phone"></div>
                <div class="text_field"><input name="reg_email" type="text" class="form_text1" id="reg_email"
                        placeholder="Email"></div>
                <div class="text_field1"><textarea class="form_text2" name="reg_address" id="reg_address"
                        placeholder="Address"></textarea></div>
                <div class="text_field"><input name="reg_city" type="text" class="form_text1" id="reg_city"
                        placeholder="City"></div>
                <div class="text_field"><select name="reg_state" id="reg_state" class="form_text5">
                        <option value="state" selected="selected">[Select your State]</option>
                        <option value="AL">Alabama</option>
                        <option value="AK">Alaska</option>
                        <option value="AZ">Arizona</option>
                        <option value="AR">Arkansas</option>
                        <option value="CA">California</option>
                        <option value="CO">Colorado</option>
                        <option value="CT">Connecticut</option>
                        <option value="DE">Delaware</option>
                        <option value="DC">District of Columbia</option>
                        <option value="FL">Florida</option>
                        <option value="GA">Georgia</option>
                        <option value="HI">Hawaii</option>
                        <option value="ID">Idaho</option>
                        <option value="IL">Illinois</option>
                        <option value="IN">Indiana</option>
                        <option value="IA">Iowa</option>
                        <option value="KS">Kansas</option>
                        <option value="KY">Kentucky</option>
                        <option value="LA">Louisiana</option>
                        <option value="ME">Maine</option>
                        <option value="MD">Maryland</option>
                        <option value="MA">Massachusetts</option>
                        <option value="MI">Michigan</option>
                        <option value="MN">Minnesota</option>
                        <option value="MS">Mississippi</option>
                        <option value="MO">Missouri</option>
                        <option value="MT">Montana</option>
                        <option value="NE">Nebraska</option>
                        <option value="NV">Nevada</option>
                        <option value="NH">New Hampshire</option>
                        <option value="NJ">New Jersey</option>
                        <option value="NM">New Mexico</option>
                        <option value="NY">New York</option>
                        <option value="NC">North Carolina</option>
                        <option value="ND">North Dakota</option>
                        <option value="OH">Ohio</option>
                        <option value="OK">Oklahoma</option>
                        <option value="OR">Oregon</option>
                        <option value="PA">Pennsylvania</option>
                        <option value="RI">Rhode Island</option>
                        <option value="SC">South Carolina</option>
                        <option value="SD">South Dakota</option>
                        <option value="TN">Tennessee</option>
                        <option value="TX">Texas</option>
                        <option value="UT">Utah</option>
                        <option value="VT">Vermont</option>
                        <option value="VA">Virginia</option>
                        <option value="WA">Washington</option>
                        <option value="WV">West Virginia</option>
                        <option value="WI">Wisconsin</option>
                        <option value="WY">Wyoming</option>
                    </select></div>
                <div class="text_field" style="text-align: left; float: left; clear: left;"><input name="reg_zip"
                        type="text" class="form_text1" id="reg_zip" placeholder="Zip"></div>

                <div class="text_field1" id="g_captcha_id"></div>

                <div class="section1-box3">
                    <!--<a href="thank-you.php">-->
                    <input type="button" class="button3" value="Submit" onclick="btnsendemlclick_eml_sellto()">
                    <!--</a>-->
                </div>
            </form>

        </div>

    </div>
    <!-- #section1 -->





    <div class="footer">
        <?php require("mainfunctions/footerContent.php");	?>
    </div>

    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer>
    </script>

</body>

</html>