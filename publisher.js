var sensor = require('node-dht-sensor');
var mqtt = require('mqtt');

// Don't forget to update accessToken constant with your device access token
const thingsboardHost = "thingsboardHost";
const accessToken = "accessToken";

// Initialization of mqtt client using Thingsboard host and device access token
console.log('Connecting to: %s using access token: %s', thingsboardHost, accessToken);
var client  = mqtt.connect('mqtt://'+ thingsboardHost, { username: accessToken });

// Triggers when client is successfully connected to the Thingsboard server
client.on('connect', function () {
    console.log('Client connected!');
    // Uploads firmware version and serial number as device attributes using 'v1/devices/me/attributes' MQTT topic
    client.publish('v1/devices/me/attributes', JSON.stringify({"firmware_version":"1.0.1", "serial_number":"SN-001"}));
    // Schedules telemetry data upload once per second
    console.log('Uploading temperature and humidity data once per second...');
    setInterval(publishTelemetry, 1000);
});

// Uploads telemetry data using 'v1/devices/me/telemetry' MQTT topic
function publishTelemetry() {
	var data = {  temperature: 0,  humidity: 0 };
	sensor.read(11, 4, function(err, temperature, humidity) {
	    if (!err) {
		data.temperature = temperature.toFixed(1);
		data.humidity = humidity.toFixed(1) ;
		client.publish('v1/devices/me/telemetry', JSON.stringify(data));
	        //console.log('temp: ' + temperature.toFixed(1) + '°C, humidity: ' + humidity.toFixed(1) + '%');
	    }
	});
}

// Uploads telemetry data using 'v1/devices/me/telemetry' MQTT topic
function publishTelemetry() {
	sensor.read(11, 4, function(err, temperature, humidity) {
	    if (!err) {
		vtemperature =  temperature.toFixed(1);
		vhumidity =humidity.toFixed(1);
		data.temperature = vtemperature ;
		data.humidity = vhumidity ;
		client.publish('v1/devices/me/telemetry', JSON.stringify(data));
	        //console.log('temp: ' + temperature.toFixed(1) + '°C, humidity: ' + humidity.toFixed(1) + '%');
	    }
	});
}


//Catches ctrl+c event
process.on('SIGINT', function () {
    console.log();
    console.log('Disconnecting...');
    client.end();
    console.log('Exited!');
    process.exit(2);
});

//Catches uncaught exceptions
process.on('uncaughtException', function(e) {
    console.log('Uncaught Exception...');
    console.log(e.stack);
    process.exit(99);
});
