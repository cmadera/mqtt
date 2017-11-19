#!/usr/bin/python
import sys
import os
import time
import Adafruit_DHT

while(True):
	humidity, temperature = Adafruit_DHT.read_retry(11, 4)
	if humidity is not None and temperature is not None:
		os.system('mosquitto_pub -h mqtt.bigbank.com.br -t bigbankcombr/pi1/temperature -m "{0:0.1f}"'.format(temperature))
		os.system('mosquitto_pub -h mqtt.bigbank.com.br -t bigbankcombr/pi1/humidity -m "{0:0.1f}"'.format(humidity))
#		print ('Temp={0:0.1f}*C  Humidity={1:0.1f}%'.format(temperature, humidity))
	else:
		print ('Failed to get reading. Try again!')
	time.sleep(5)
