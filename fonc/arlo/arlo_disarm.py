#!/usr/bin/env python
from Arlo import Arlo
import sys

USERNAME = sys.argv[1]
PASSWORD = sys.argv[2]

try:
	arlo = Arlo(USERNAME, PASSWORD)
	# At this point you're logged into Arlo.

	# Get the list of devices and filter on device type to only get the basestation.
	# This will return an array which includes all of the basestation's associated metadata.
	basestation = [ device for device in arlo.GetDevices() if device['deviceType'] == 'basestation' ]

	# Disarm Arlo.
	arlo.Disarm(basestation[0]['deviceId'], basestation[0]['xCloudId'])

except Exception as e:
    print e
