<?php

// require ("inc/header_session.php");

?>
<iframe frameborder="0" onload="iframeLoaded_delivery_financials()" scrolling="auto" id="iframe_delivery_financials"
    src="loop_orderbubble_delivery_financials.php?rec_id=<?php echo $_REQUEST['rec_id']; ?>&ID=<?php echo $_REQUEST["ID"]; ?>&rec_id=<?php echo $_REQUEST["rec_id"]; ?>&warehouse_id=<?php echo $_REQUEST["warehouse_id"]; ?>&rec_type=<?php echo $_REQUEST["rec_type"]; ?>">

</iframe>

<script>
function iframeLoaded_delivery_financials() {

    ifrmaeobj = document.getElementById("iframe_delivery_financials");
    var objheight = ifrmaeobj.contentWindow.document.body.offsetHeight;
    objheight = objheight + 20;
    ifrmaeobj.style.height = objheight + 'px';
    ifrmaeobj.style.width = '600px';
}
</script>