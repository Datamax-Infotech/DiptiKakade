<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=devidev-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>About Us</title>
    <link rel="icon" href="images/logo5-new.png" type="image/png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>
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
    <link href="css/inner-new.css" rel="stylesheet">
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

            <div id="logo" class="pull-left"> <a href="index.php"><img src="images/UCB-logo-new.jpg" alt=""
                        title="" /></a>
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
                        <li><a href="sell-to-ucb.php">
                                <div class="icon"><img src="images/icon-02.png" /></div>Sell to UCBZeroWaste
                            </a></li>
                        <li class="active"><a href="about-us.php">
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

                    <form class="loginfrm" action="about-us.php" method="post" onsubmit="return chkform();">
                        <div class="nav-bar-box1">
                            <div class="login">Login to W.A.T.E.R.</div>
                        </div>

                        <div class="nav-bar-box2">
                            <div class="formlabel"><input type="text" name="txtemail" id="txtemail" class="form_text"
                                    value="" placeholder="Enter User name"></div>
                            <div class="formlabel"><input type="password" name="txtpassword" id="txtpassword"
                                    class="form_text" value="" placeholder="Enter Password"></div>
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

                    <img src="images/about-go-zero-waste-banner.jpg" alt="">
                    <div class="overlay-desc">
                        <div id="messageBox">
                            <div class="video-content">
                                <h1>About Us</h1>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- #section1 -->
    <div class="first-sect-main">

        <div class="first-sect">
            <div class="first-sect-box1">
                <img src="images/about/icone-01.jpg" alt="" />
                <h2>Corporate Headquarters</h2>
                <p>4032 Wilshire Blvd, Los Angeles, CA 90010, 323-724-2500</p>
            </div>

            <div class="first-sect-box2">
                <img src="images/about/icone-02.jpg" alt="" />
                <h2>Call Us</h2>
                <p>1-888-BOXES-88 <br>
                    (1-888-269-3788)</p>
            </div>

        </div>

        <div class="second-sect">
            <div class="second-sect1">
                <p>UCBZeroWaste is here to help your company achieve</p>
                <h2>Zero-Waste-to-Landfill</h2>
            </div>
        </div>

        <div class="third-sect">
            <ul>
                <li><a href="#services"><img src="images/about/about-02.png" alt="" /><br>
                        Services</a></li>
                <li><a href="#clients"><img src="images/about/about-03.png" alt="" /><br>
                        Clients</a></li>
                <li><a href="#operation"><img src="images/about/about-04.png" alt="" /><br>
                        Area of Operation</a></li>
            </ul>
        </div>
    </div>

    <div class="forth-sect">
        <div class="forth-sect1"><a name="services" class="anchor01"></a>
            <h2>A Complete Approach</h2>
            <p>UCBZeroWaste designs and manages sustainability programs to help companies achieve
                Zero-Waste-to-Landfill status, through the implementation of Return, Reuse, Resell, Recycle, Composting
                and Waste-to-Energy programs. Our programs are designed to reduce customers trash (cost) and increase
                reuse and recycling (revenue), while implementing and integrating best waste management practices at all
                local levels, and simultaneously reporting across all commodities and all locations. This way, the
                client companies can focus on core business, and we focus on taking care of their waste.</p>
        </div>
    </div>

    <div class="fifth-sect">
        <div class="fifth-sect1">
            <h2>Our Story</h2>
            <div class="fifth-sect-box1">
                <p>When launching in 2006, UCBZeroWaste built technology and processes that enabled buying used/empty
                    boxes and gaylord totes from large US food, beverage, and CPG manufacturers - for more than
                    recycling rates. These are the shipping boxes and the gaylord totes that companies receive from
                    their vendors, often carrying bulk ingredients, plastic bottles & plastic caps, etc.
                </p>

                <p>UCBZeroWaste is able to pay more for the boxes than any recycler across the country, because of the
                    fact that the boxes are not being recycled - they are being resold for reuse purposes, mainly to
                    major retailers for distribution and reverse logistics right here in the US.</p>

                <p>As one of the largest buyer & seller of quality used boxes and gaylord totes in the country,
                    UCBZeroWaste has been able to create a tremendous amount of value at many large companies just by
                    our box programs. Proudly, the business has expanded and now we manage almost any byproduct of
                    food/bev/cpg manufacturing or distribution. In other words, UCBZeroWaste buys gaylord totes, boxes,
                    pallets, supersaks, barrels and drums for more than recycling rates and resell them for less than
                    new ones.</p>
            </div>

            <div class="fifth-sect-box2">
                <p>As a closed-loop sustainability program, UCBZeroWaste return millions of boxes right back to the
                    original vendor for reuse. Apart from shipping boxes and gaylord totes, UCBZeroWaste helps separate
                    other valuable commodities to reduce waste and to find the highest paying outlet for each commodity.
                </p>

                <p>One of the things that separates UCBZeroWaste from "local box brokers" and surely "traditional
                    recyclers" and even "waste consultants" is the online, real-time tracking software that report down
                    to SKU-level.</p>

                <p>UCBZeroWaste’s proprietary software called WATER: Waste Analytics and Tracking for Environmental
                    Reporting, enables users to keep track on waste volumes, financials and landfill diversion rates. It
                    identifies cost and revenue fluctuations and detects additional and often hidden fees. Offers an
                    ability to create professional sustainability reports for internal and third party
                    accounting/promotion. And allows users to request materials pickup and generate bill-of-ladings in
                    one click.
                </p>
            </div>
            <img src="images/about/about-05.jpg" alt="" />
        </div>

        <div class="six-sect1">
            <div class="six-sect1-box"><a name="clients" class="anchor01"></a>
                <h2>Who We Serve</h2>
                <p>UCBZeroWaste serves medium and large businesses in different industries, such as food, beverage, CPG,
                    pharmaceutical, logistics, and electronics.<br />We also partner with recyclers and other companies
                    across the United States and Canada.</p>
            </div>
        </div>

        <div class="seventh-sect1"><a name="operation" class="anchor01"></a>
            <h2>Where We Operate</h2>
            <p>UCBZeroWaste serves companies located in United States and Canada.</p>
            <img src="images/about/map.jpg" alt="" />
        </div>
    </div>




    <div class="footer">
        <?php require("mainfunctions/footerContent.php");	?>
    </div>

</body>

</html>