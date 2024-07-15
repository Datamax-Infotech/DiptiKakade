<?php

$date_of_expiry = time() + 8000000;
setcookie("donotshowmsg", "yes", $date_of_expiry);