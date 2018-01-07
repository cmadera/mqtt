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

// Get information from MQTT server

include("config.php");

$sql = "SELECT id_topic, nm_mqtt_server, nu_mqtt_port, nm_topic, nm_element, id_active FROM topic where id_active='Y'";
$result = $conn->query($sql);

$topics = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
		$idTopic =  $row["id_topic"];
		$nmTopic =  $row["nm_topic"];
		$nmElement =  $row["nm_element"];
		$nmServer =  $row["nm_mqtt_server"];
		$nuPort =  $row["nu_mqtt_port"];
        echo "	client$idTopic = new Paho.MQTT.Client('$nmServer', Number($nuPort), 'bigbankClient$idTopic-$nmElement'); \n";
        echo "	client$idTopic.onConnectionLost = onConnectionLost; \n";
        echo "	client$idTopic.onMessageArrived = onMessageArrived$idTopic; \n";
        echo "	client$idTopic.connect({onSuccess:onConnect$idTopic}); \n";
        echo "	function onConnect$idTopic() {	client$idTopic.subscribe('$nmTopic'); } \n";
        echo "	function onMessageArrived$idTopic(message) {  \n";
        echo "	   console.log('onMessageArrived:'+message.payloadString); \n";
        echo "	   document.getElementById('$nmElement').innerHTML = message.payloadString; \n    }\n\n";

    }
    echo "	function onConnectionLost(responseObject) {
	  if (responseObject.errorCode !== 0) {  console.log('onConnectionLost:'+responseObject.errorMessage); } }\n\n";

}
$conn->close();

// Get information from Saved History

include("config.php");

$sql = "SELECT DATE_FORMAT(CONVERT_TZ(dt_measure,'+00:00','-2:00'), '%d/%m %k:00') hora, 
				nm_topic,  min(vl_topic) mini, max(vl_topic) maxi, 
				format(avg(vl_topic),2) avgi 
		  FROM measure 
		 group by nm_topic, DATE_FORMAT(CONVERT_TZ(dt_measure,'+00:00','-2:00'), '%d/%m %k:00') ";

$result = $conn->query($sql);
$valorTemp = "";
$valorHumi = "";
$hora = "";
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
		$dthora =  $row["hora"];
		$nmTopic =  $row["nm_topic"];
		$avgi =  $row["avgi"];
		
        if ($nmTopic =="pi1/estacao/temperature") {
	        $hora = $hora ."'" . $dthora . "',";
        	$valorTemp = $valorTemp . $avgi . ",";
        } else
        	$valorHumi = $valorHumi . $avgi . ",";

    }
}
$conn->close();
?>
    var config = {
            type: 'line',
            data: {
                labels: [
                	<?php echo $hora; ?>
                ],
                datasets: [{
                    label: "Temperature",
                    backgroundColor: window.chartColors.red,
                    borderColor: window.chartColors.red,
                    data: [<?php echo $valorTemp; ?>],
                    fill: false,
                    pointRadius: 1,
		            yAxisID: "y-axis-1"
                }, {
                    label: "Humidity",
                    fill: false,
                    backgroundColor: window.chartColors.blue,
                    borderColor: window.chartColors.blue,
                    data: [<?php echo $valorHumi; ?>],
                    pointRadius: 1,
	                yAxisID: "y-axis-2"
                }],

            },
            options: {
                responsive: true, hoverMode: 'index', stacked: false,
                title:{ display:true,  text:'Comparative chart between Temperature and Humidity' },
				scales: {
                    yAxes: [{
                        type: "linear", 
                        display: true, position: "left", id: "y-axis-1",
                        scaleLabel: { display: true, labelString: 'Temperature'  }
                    }, {
                        type: "linear", 
                        display: true, position: "right", id: "y-axis-2",
                        scaleLabel: { display: true, labelString: 'Humidity' },
                        gridLines: { drawOnChartArea: false },
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
