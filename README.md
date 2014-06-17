Phoenix Tester
==============

A simple script to test Phoenix that can be configured to run a series of simple searches. The results are output to file as single line json objects to the file `output.txt`. For example:

```json
{
  "datetime": "2014-06-17T13:40:11+10:00",
  "test": "abnel78 dom 100",
  "duration": 26.376855,
  "httpCode": 200,
  "departDate": "20141216",
  "returnDate": "20141223",
  "origin": "BNE",
  "destination": "SYD",
  "adultCount": 1,
  "childCount": 0,
  "infantCount": 0,
  "cabinClass": "ECONOMY",
  "bookingType": "DOMESTIC",
  "outboundTrips": 231,
  "inboundTrips": 233,
  "combinedTrips": 0,
  "galTrips": 431,
  "jetstarTrips": 33
}
```

Here you can see a domestic search was performed against abnel78 that took 26 seconds to come back. The number of trips are shown, as well as the total number of GLL and JETSTAR trips.

Searches that result in a non-200 response will result in the a line being written to `output.txt` like the following:

```json
{
  "datetime": "2014-06-17T13:44:43+10:00",
  "test": "abnel78 tt 111",
  "error": "Server error response\n[status code] 500\n[reason phrase] Internal Server Error\n[url] http://abnel78.au.fcl.internal:17680/phoenix/CTWEB/FLGHTCNTR/flights?departDate=20141216&returnDate=20141223&origin=MEL&destination=AKL&adultCount=1&childCount=1&infantCount=1&cabinClass=ECONOMY",
  "departDate": "20141216",
  "returnDate": "20141223",
  "origin": "MEL",
  "destination": "AKL",
  "adultCount": 1,
  "childCount": 1,
  "infantCount": 1,
  "cabinClass": "ECONOMY"
}
```

A set of sample results can be found in the `output.txt.sample` file.
