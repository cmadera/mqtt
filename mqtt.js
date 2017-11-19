
var mysql = require('mysql');

var con = mysql.createConnection({
  host: "localhost",
  user: "mqtt",
  password: "password",
  database: "mqtt"
});

con.connect(function(err) {
  if (err) throw err;
  console.log("Connected!");
  var sql = "select nm_topic from sensors where id_active='Y'";
  con.query(sql, function (err, sensors, fields) {
    if (err) throw err;

    if (sensors.length>0) {
      console.log(sensors.length + "active sensors");
      var mqtt = require('mqtt')
      var client  = mqtt.connect('mqtt://mqtt.bigbank.com.br')
 
      client.on('connect', function () {
        for(var i = 0; i < sensors.length; i++) {
          console.log("Subscribing to: " + sensors[i].nm_topic);
          client.subscribe(sensors[i].nm_topic);
        }
      });
 
      client.on('message', function (topic, message) {
        var sql = "INSERT INTO measures(nm_topic, vl_message, dt) VALUES ('"+topic+"',"+message+",now())";
        con.query(sql, function (err, result, fields) {
          if (err) throw err;
            //console.log("Result: " + result.insertId);
        });
      });
    } else {
      console.log("0 active sensors");
      process.exit();
    }
  })
})
