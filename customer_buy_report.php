<?php

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

?>
<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8">
    <title>Heat Map: Where B2C Customer Buy</title>
    <link rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap"
        rel="stylesheet">
    <style>
    body,
    html {
        margin: 0;
        padding: 0;
        height: 100%;
    }

    body {
        font-family: sans-serif;
    }

    body * {
        font-weight: 200;
    }

    h1 {
        position: absolute;
        background: white;
        padding: 10px;
    }

    #map {
        height: 100%;
    }

    .leaflet-container {
        background: rgba(0, 0, 0, .8) !important;
    }

    h1 {
        position: absolute;
        background: black;
        color: white;
        padding: 10px;
        font-weight: 200;
        z-index: 10000;
    }

    #all-examples-info {
        position: absolute;
        background: white;
        font-size: 16px;
        padding: 20px;
        top: 100px;
        width: 350px;
        line-height: 150%;
        border: 1px solid rgba(0, 0, 0, .2);
    }

    .title_css {
        font-size: 22px;
        margin-top: 8px;
        margin-bottom: 8px;
        font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
        text-align: center;
        font-weight: bold;
    }
    </style>
    <link rel="stylesheet" href="heatmap_test/leaflet.css" />
    <script src="heatmap_test/leaflet.js"></script>
    <script src="heatmap_test/build/heatmap.js"></script>
    <script src="heatmap_test/plugins/leaflet-heatmap/leaflet-heatmap.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>

<body>

    <?php include("inc/header.php"); ?>
    <div class="main_data_css">
        <div class="dashboard_heading" style="float: left;">
            <div style="float: left;">
                Heat Map: Where B2C Customer Buy
            </div>

            &nbsp;<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                <span class="tooltiptext">This heat map shows the user where all B2C customers are located, over the
                    lifetime of UCB.</span>
            </div><br>
            <div style="height: 13px;">&nbsp;</div>
        </div>
        <?php

        //$sql_del_z = db_query("delete from customer_buy_heatmap_tbl",db());
        //
        /*$sql = "SELECT delivery_postcode, COUNT(*) as cnt from orders where delivery_postcode > 75904 GROUP BY delivery_postcode ";
    $result = db_query($sql,db());
    while ( $row = array_shift($result) ) {
		$zip_cnt=$row["cnt"];
        $zipcode = $row["delivery_postcode"];
		$zip_sql = "SELECT latitude, longitude from ZipCodes where zip='".$zipcode."'";
		$z_result = db_query($zip_sql,db_b2b());
    	while ($ziprow = array_shift($z_result) ) {
			//$zip_data[]= $ziprow.$zip_cnt;
			$sql_n="INSERT INTO `customer_buy_heatmap_tbl` (`zip`, `latitude`, `longitude`, `cnt`) VALUES ('".$zipcode."', '".$ziprow["latitude"]."', '".$ziprow["longitude"]."', '".$zip_cnt."')";
			$results = db_query($sql_n,db());
		}
		
   }*/

        $sql_z = "SELECT latitude, longitude, cnt from customer_buy_heatmap_tbl";
        db();
        $resultz = db_query($sql_z);
        //echo tep_db_num_rows($resultz);
        while ($row_z = array_shift($resultz)) {
            $zip_data[] = $row_z;
        }

        $zip_data = $zip_data ?? ""; 
        //print_r($zip_data);
        $data_json = json_encode($zip_data);
        ?>
        <div class="title_css"><strong>B2C Map to show where customers buy</strong></div>
        <!--<h1>B2C Map to show where customers buy</h1>-->

        <div id="map"></div>
        <div id="heatmap" name="heatmap"></div>

        <script>
        window.onload = function() {

            var testData = {
                max: 8,
                data: <?php echo $data_json; ?>
            };

            var baseLayer = L.tileLayer(
                'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery Â© <a href="http://cloudmade.com">CloudMade</a>',
                    maxZoom: 18
                }
            );
            //

            //
            var cfg = {
                // radius should be small ONLY if scaleRadius is true (or small radius is intended)
                "radius": 2,
                "maxOpacity": .8,
                // scales the radius based on map zoom
                "scaleRadius": true,
                // if set to false the heatmap uses the global maximum for colorization
                // if activated: uses the data maximum within the current map boundaries 
                //   (there will always be a red spot with useLocalExtremas true)
                "useLocalExtrema": true,
                // which field name in your data represents the latitude - default "lat"
                latField: 'latitude',
                // which field name in your data represents the longitude - default "lng"
                lngField: 'longitude',
                // which field name in your data represents the data value - default "value"
                valueField: 'cnt'
            };

            var heatmapLayer = new HeatmapOverlay(cfg);

            var map = new L.Map('map', {
                center: new L.LatLng(37.090240, -95.712891),
                zoom: 4,
                layers: [baseLayer, heatmapLayer]
            });

            heatmapLayer.setData(testData);

            // make accessible for debugging
            layer = heatmapLayer;

            /*  start legend code */
            // we want to display the gradient, so we have to draw it
            var legendCanvas = document.createElement('canvas');
            legendCanvas.width = 100;
            legendCanvas.height = 10;
            var min = document.querySelector('#min');
            var max = document.querySelector('#max');
            var gradientImg = document.querySelector('#gradient');
            var legendCtx = legendCanvas.getContext('2d');
            var gradientCfg = {};

            function updateLegend(data) {
                // the onExtremaChange callback gives us min, max, and the gradientConfig
                // so we can update the legend
                min.innerHTML = data.min;
                max.innerHTML = data.max;
                // regenerate gradient image
                if (data.gradient != gradientCfg) {
                    gradientCfg = data.gradient;
                    var gradient = legendCtx.createLinearGradient(0, 0, 100, 1);
                    for (var key in gradientCfg) {
                        gradient.addColorStop(key, gradientCfg[key]);
                    }
                    legendCtx.fillStyle = gradient;
                    legendCtx.fillRect(0, 0, 100, 10);
                    gradientImg.src = legendCanvas.toDataURL();
                }
            };

            /* legend code end */
            var heatmapInstance = h337.create({
                container: document.querySelector('.heatmap'),
                // onExtremaChange will be called whenever there's a new maximum or minimum
                onExtremaChange: function(data) {
                    updateLegend(data);
                }
            });
        };
        </script>

        <style>
        #no-more-tables {
            float: left;
            width: 20%;
            overflow-x: auto;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        #no-more-tables table {
            width: 96%;
            margin: 0px auto;
            padding: 0;
            border-spacing: 1;
            border-collapse: collapse;
        }

        tr:nth-child(even) {
            background-color: #efefef;
        }

        tr:nth-child(odd) {
            background-color: #ffffff;
        }

        #no-more-tables table th {
            text-align: left;
            background-color: #c9c9c9;
            font-size: 16px;
            color: #4a4a4a;
            line-height: 22px;
            border-right: 1px solid #cfcfcf;
            font-weight: normal;
            padding: 11px 0px 11px 10px;
        }

        #no-more-tables table th:last-of-type {
            border-right: none;
        }

        #no-more-tables table td:last-of-type {
            border-right: none;
        }

        #no-more-tables table td {
            text-align: left;
            color: #4a4a4a;
            line-height: 22px;
            font-size: 16px;
            border-right: 1px solid #cfcfcf;
            padding: 8px 0px 8px 10px;
        }

        .text-align1 {
            text-align: center !important;
        }
        </style>

        <?php

        $sql = "SELECT variablevalue from tblvariable where variablename = 'heatmap_b2c_cron_run_date'";
        db();
        $result = db_query($sql);
        while ($row = array_shift($result)) {
            echo "<br>Cron Job run date - " . $row["variablevalue"] . "<br>";
        }
        ?>

        <div id="no-more-tables">
            <table>
                <thead>
                    <th>Zip Code</th>
                    <th class="text-align1">Count</th>
                </thead>
                <?php

                $sql_z = "SELECT zip, cnt from customer_buy_heatmap_tbl order by cnt desc";
                db();
                $resultz = db_query($sql_z);
                while ($row_z = array_shift($resultz)) {
                    echo "<tr><td class='text-align1'>" . $row_z["zip"] . " </td>";
                    echo "<td class='text-align1'>" . $row_z["cnt"] . " </td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
</body>

</html>