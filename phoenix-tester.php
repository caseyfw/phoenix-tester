<?php

// dependencies
require 'vendor/autoload.php';

// parse the list of searches
$config = json_decode(file_get_contents(__DIR__.'/config.json'), true);

// make dates?

// foreach search
foreach ($config['searches'] as $search) {

    $datetime = date('c');
    // perform the search
    $client = new Guzzle\Http\Client('', array('ssl.certificate_authority' => false));

    try {
        $response = $client->get($search['url'], array(), array('query' => $search['query']))->send();

        $availability = json_decode($response->getBody(), true);

        // check if there was an error, just keep the response body if there is
        if (!array_key_exists('bookingType', $availability)) {
            $result = array('error' => $response->getBody());
        } else {
            // collect data
            $allTrips = array_merge($availability['outboundTrips'], $availability['inboundTrips'], $availability['combinedTrips']);

            $result = array(
                'bookingType' => $availability['bookingType'],
                'outboundTrips' => count($availability['outboundTrips']),
                'inboundTrips' => count($availability['inboundTrips']),
                'combinedTrips' => count($availability['combinedTrips']),
                'galTrips' => array_reduce($allTrips, function ($count, $trip) {
                    return $count + ($trip['reservationSystem'] == 'GLL' ? 1 : 0);
                }),
                'jetstarTrips' => array_reduce($allTrips, function ($count, $trip) {
                    return $count + ($trip['reservationSystem'] == 'JETSTAR' ? 1 : 0);
                }),
            );
        }

        $result = array_merge(
            array(
                'datetime' => $datetime,
                'test' => $search['test'],
                'duration' => $response->getInfo('total_time'),
                'httpCode' => $response->getInfo('http_code'),
            ),
            $search['query'],
            $result
        );
    } catch (Exception $e) {
        $result = array_merge(
            array(
                'datetime' => $datetime,
                'test' => $search['test'],
                'error' => $e->getMessage(),
            ),
            $search['query']
        );
    }

    // write out to file
    file_put_contents(__DIR__.'/output.txt', json_encode($result)."\n", FILE_APPEND);
}
