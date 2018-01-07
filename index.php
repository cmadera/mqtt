<?php
//echo "hello";
?>
<!doctype html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="text/html; charset=UTF-8; X-Content-Type-Options=nosniff" http-equiv="Content-Type" />
    <meta name="description" content="IoT Bigbank.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="IoT Home control">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta name="author" content="Carlos Madera">
    <link rel="icon" href="favicon.ico">
    <link rel="icon" sizes="128x128" href="128x128bigbank.png">
    <link rel="apple-touch-icon" sizes="128x128" href="128x128bigbank.png">
    <title>IoT Bigbank</title>
        <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="192x192" href="128x128bigbank.png">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Bigbank">

    <!-- Tile icon for Win8 (144x144 + tile color) -->
    <meta name="msapplication-TileImage" content="images/touch/ms-touch-icon-144x144-precomposed.png">
    <meta name="msapplication-TileColor" content="#3372DF">

    <!-- SEO: If your mobile URL is different from the desktop URL, add a canonical link to the desktop page https://developers.google.com/webmasters/smartphone-sites/feature-phones -->
    <!--
    <link rel="canonical" href="http://www.example.com/">
    -->

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.cyan-light_blue.min.css">
    <link rel="stylesheet" href="https://getmdl.io/templates/dashboard/styles.css">

    <script src="http://www.chartjs.org/dist/2.7.1/Chart.bundle.js"></script>
    <script src="http://www.chartjs.org/samples/latest/utils.js"></script>
    <style>
    canvas{
        -moz-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
    }
    </style>
    
    <style>
    #view-source {
      position: fixed;
      display: block;
      right: 0;
      bottom: 0;
      margin-right: 40px;
      margin-bottom: 40px;
      z-index: 900;
    }
    </style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.js" type="text/javascript"></script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-33102679-1']);
  _gaq.push(['_setDomainName', 'bigbank.com.br']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>    
<script>
<?php

include("config.php");

$sql = "SELECT id_topic, nm_topic, nm_element, id_active FROM topic where id_active='Y'";
$result = $conn->query($sql);

$topics = [];

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
		$idTopic =  $row["id_topic"];
		$nmTopic =  $row["nm_topic"];
		$nmElement =  $row["nm_element"];
        echo "	client$idTopic = new Paho.MQTT.Client('mqtt.bigbank.com.br', Number(8000), 'bigbankClient$idTopic-$nmElement'); \n";
        echo "	client$idTopic.onConnectionLost = onConnectionLost; \n";
        echo "	client$idTopic.onMessageArrived = onMessageArrived$idTopic; \n";
        echo "	client$idTopic.connect({onSuccess:onConnect$idTopic}); \n";
        echo "	function onConnect$idTopic() {	client$idTopic.subscribe('$nmTopic'); } \n";
        echo "	function onMessageArrived$idTopic(message) {  \n";
        echo "	   console.log('onMessageArrived:'+message.payloadString); \n";
        echo "	   document.getElementById('$nmElement').innerHTML = message.payloadString; \n    }\n\n";

    }
}
$conn->close();
?>

	function onConnectionLost(responseObject) {
	  if (responseObject.errorCode !== 0) {
	    console.log("onConnectionLost:"+responseObject.errorMessage);
	  }
	}

    var config = {
            type: 'line',
            data: {
                labels: [
"1910","1911","1912","1913","1914","1915","1916","1917","1918","1919","1920","1921","1922","1923",
"2000","2001","2002","2003","2004","2017","2018","2019","2020","2021","2022","2023",
"2100","2101","2102","2103","2104","2105","2106","2107","2108","2109","2110","2111","2112","2113","2114","2115","2116","2117","2118","2119","2120","2121","2122","2123",
"2200","2201","2202","2203","2204","2205","2206","2207","2208","2209","2210","2211","2212","2213","2214","2215","2216","2217","2218","2219","2220","2221","2222","2223",
"2300","2301","2302","2303","2304","2305","2306","2307","2308","2309","2310","2311","2312","2313","2314","2315","2316","2317","2318","2319","2320","2321","2322","2323",
"2400","2401","2402","2403","2404","2405","2406","2407","2408","2409","2410","2411","2412","2413","2414","2415","2416","2417","2418","2419","2420","2421","2422","2423",
"2500","2501","2502","2503","2504","2505","2506","2507","2508","2509","2510","2511","2512","2513","2514","2515","2516","2517","2518"],
                datasets: [{
                    label: "Temperature",
                    backgroundColor: window.chartColors.red,
                    borderColor: window.chartColors.red,
                    data: [
23.44,23.26,23.16,23.53,23.16,23.02,22.93,23.47,22.94,21.88,22.28,22.01,21.72,21.92,22.56,22.19,21.86,21.79,23.00,
20.99,20.94,20.48,20.50,20.64,20.95,20.95,21.20,21.62,21.62,21.61,21.63,22.06,22.28,21.79,22.32,21.98,21.95,22.03,
21.51,21.27,21.32,21.37,21.46,21.53,21.49,21.41,21.34,21.40,21.39,21.46,21.45,21.48,21.46,21.49,21.43,21.52,21.69,
21.40,21.44,22.06,22.53,22.40,21.83,21.74,21.42,21.40,21.46,21.12,20.71,20.67,20.66,20.84,20.72,20.64,20.50,20.77,
20.55,20.70,20.97,20.68,20.98,21.27,21.38,21.30,21.55,21.46,21.43,21.50,21.59,21.33,21.41,21.37,21.29,21.47,21.40,
20.98,21.00,20.46,20.46,20.44,20.32,20.79,21.43,26.32,26.06,23.63,22.55,22.42,22.56,22.10,21.37,21.40,21.35,21.28,
21.28,21.29,21.14,21.25,21.32,21.24,21.14,21.09,20.75,20.55,20.48,21.31,22.11,28.65,29.21,24.95,23.41,22.77,22.20,
22.64,23.19,23.86,23.19,22.48,22.25,22.89,23.07    ],
                    fill: false,
                    pointRadius: 1,
		            yAxisID: "y-axis-1"
                }, {
                    label: "Humidity",
                    fill: false,
                    backgroundColor: window.chartColors.blue,
                    borderColor: window.chartColors.blue,
                    data: [
83.45,85.24,86.13,86.73,87.72,89.12,89.34,89.19,91.34,88.37,92.80,93.02,92.91,92.31,92.00,92.00,91.99,92.46,92.00,
92.97,92.49,93.25,93.43,93.32,92.73,92.83,92.01,92.06,92.35,92.25,92.06,90.57,88.88,90.41,89.25,89.33,88.92,89.36,
89.38,92.11,92.06,92.39,91.90,92.08,92.04,92.00,92.19,92.00,92.00,92.00,92.00,92.28,92.00,92.00,91.85,92.00,92.19,
92.18,92.18,92.00,91.59,90.54,91.86,92.15,92.03,92.00,92.00,92.26,92.48,91.98,90.35,86.54,85.26,84.14,83.71,84.78,
83.12,80.05,76.41,77.74,79.28,75.16,72.29,70.58,71.61,74.99,73.74,72.45,72.17,76.81,76.42,75.72,80.59,75.64,68.01,
64.51,64.26,71.60,72.42,69.57,69.29,70.26,65.74,52.26,53.00,63.51,66.35,71.50,72.62,74.64,80.46,81.03,82.32,83.88,
86.55,88.00,88.20,89.37,89.99,90.07,90.73,90.34,91.46,91.83,91.50,87.50,85.07,62.22,61.83,73.08,80.57,83.27,84.86,
84.03,85.27,82.44,86.93,89.54,90.12,89.33,89.16 ],
                    pointRadius: 1,
	                yAxisID: "y-axis-2"
                }],

            },
            options: {
                responsive: true,
                hoverMode: 'index',
                stacked: false,
                title:{
                    display:true,
                    text:'Comparative chart between Temperature and Humidity'
                },
				scales: {
                    yAxes: [{
                        type: "linear", // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                        display: true,
                        position: "left",
                        id: "y-axis-1",
                        scaleLabel: {
                            display: true,
                            labelString: 'Temperature'
                        }
                    }, {
                        type: "linear", // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                        display: true,
                        position: "right",
                        id: "y-axis-2",
                        scaleLabel: {
                            display: true,
                            labelString: 'Humidity'
                        },
                        // grid line settings
                        gridLines: {
                            drawOnChartArea: false, // only want the grid lines for one axis to show up
                        },
                    }],
                }
            }
        };

        window.onload = function() {
            var ctx = document.getElementById("canvas").getContext("2d");
            window.myLine = new Chart(ctx, config);
        };


        var colorNames = Object.keys(window.chartColors);
    </script>
	

  </head>
 <body>
    <div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
      <header class="demo-header mdl-layout__header mdl-color--grey-100 mdl-color-text--grey-600">
        <div class="mdl-layout__header-row">
          <span class="mdl-layout-title">IoT Bigbank</span>

        </div>
      </header>
      <div class="demo-drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
        <nav class="demo-navigation mdl-navigation mdl-color--blue-grey-800">
          <a class="mdl-navigation__link" href=""><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">home</i>Home</a>
          <a class="mdl-navigation__link" href=""><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">report</i>Reports</a>
          <div class="mdl-layout-spacer"></div>
          <a class="mdl-navigation__link" href=""><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">help_outline</i><span class="visuallyhidden">Help</span></a>
        </nav>
      </div>
      <main class="mdl-layout__content mdl-color--grey-100">
        <div class="mdl-grid demo-content">
          <div class="demo-charts mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-grid">
            <svg fill="currentColor" width="150px" height="150px" viewBox="0 0 1 1" class="demo-chart mdl-cell mdl-cell--4-col mdl-cell--2-col-desktop">
              <use xlink:href="#piechart" mask="url(#piemask)" />
              <text id="temperature" x="0.5" y="0.5" font-family="Roboto" font-size="0.25" fill="#888" text-anchor="middle" dy="0.1"></text>
            </svg>
            <svg fill="currentColor" width="150px" height="150px" viewBox="0 0 1 1" class="demo-chart mdl-cell mdl-cell--4-col mdl-cell--2-col-desktop">
              <use xlink:href="#piechart" mask="url(#piemask)" />
              <text id="humidity" x="0.5" y="0.5" font-family="Roboto" font-size="0.25" fill="#888" text-anchor="middle" dy="0.1"></text>
            </svg>
            <svg fill="currentColor" width="150px" height="150px" viewBox="0 0 1 1" class="demo-chart mdl-cell mdl-cell--4-col mdl-cell--2-col-desktop">
              <use xlink:href="#piechart" mask="url(#piemask)" />
              <text id="light" x="0.5" y="0.5" font-family="Roboto" font-size="0.25" fill="#888" text-anchor="middle" dy="0.1">TBD</text>
            </svg>
            <svg fill="currentColor" width="150px" height="150px" viewBox="0 0 1 1" class="demo-chart mdl-cell mdl-cell--4-col mdl-cell--2-col-desktop">
              <use xlink:href="#piechart" mask="url(#piemask)" />
              <text id="light" x="0.5" y="0.5" font-family="Roboto" font-size="0.25" fill="#888" text-anchor="middle" dy="0.1">TBD</text>
            </svg>
            <svg fill="currentColor" width="150px" height="150px" viewBox="0 0 1 1" class="demo-chart mdl-cell mdl-cell--4-col mdl-cell--2-col-desktop">
              <use xlink:href="#piechart" mask="url(#piemask)" />
              <text id="light" x="0.5" y="0.5" font-family="Roboto" font-size="0.25" fill="#888" text-anchor="middle" dy="0.1">TBD</text>
            </svg>
          </div>
        </div>
	    <div style="width:95%;">
	        <canvas id="canvas"></canvas>
	    </div>
    
      </main>
      
    </div>
      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" style="position: fixed; left: -1000px; height: -1000px;">
        <defs>
          <mask id="piemask" maskContentUnits="objectBoundingBox">
            <circle cx=0.5 cy=0.5 r=0.49 fill="white" />
            <circle cx=0.5 cy=0.5 r=0.40 fill="black" />
          </mask>
          <g id="piechart">
            <circle cx=0.5 cy=0.5 r=0.5 />
            <path d="M 0.5 0.5 0.5 0 A 0.5 0.5 0 0 1 0.95 0.28 z" stroke="none" fill="rgba(255, 255, 255, 0.75)" />
          </g>
        </defs>
      </svg>
      
    <script src="https://code.getmdl.io/1.3.0/material.min.js"></script>
  </body>
</html>

<!-- https://getmdl.io/templates/dashboard/index.html -->
