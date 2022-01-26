<?php

include_once 'Request.php';
include_once 'Router.php';
include_once 'API.php';
$router = new Router(new Request);

$router->get('/', function() {
    return <<<HTML
        <style>
            p {
                background-color: #e5e5e5;
                padding: 15px 20px;
            }
        </style>
        <h1>Warner Bros PHP Test!</h1>
        <div>
            <p>Service Run: php -S 127.0.0.1:8000</p>
            <p>Database: test</p>
            <p>Table Name: crime_data_from_2020_to_present_csv</p>
        </div>
        <div>
            <h2>a. Report the number of crimes from a given area. (Ex, if I want the number of crimes in the SouthWest area, it should return the number.</h2>
            <p>endpoint: /api/get_crime_count_by_area_code
            /api/get_crime_count_by_area_name
            </p>
        </div>
        <div>
            <h2>b.  Show the Addresses (Street City Zip) for a given crime type. (Ex, if you are looking for Battery – Simple Assault, it should return all the addresses).</h2>
            <p>endpoint: /api/get_addresses_page_by_crime_type</p>
        </div>
        <div>
            <h2>c.  Report the number of crimes for a given crime.</h2>
            <p>endpoint: /api/get_crime_count_by_crime_type</p>
        </div>
        <div>
            <h2>d.  Please use pagination methods. If the results are in the thousands, please return the first hundred with options to get the next page.</h2>
            <p>endpoint: /api/get_addresses_page_by_crime_type</p>
        </div>
        <div>
            <h2>e.  Please also assume that the user may want to inject SQL statements into your database, or delete information outright. Provide code to protect your data.</h2>
            <p>used real_escape_string function in query</p>
        </div>
        HTML;
});

$router->post('/api/get_crime_count_by_area_code', function($request) {
    $data = $request->getBody();

    $api =  API::getInstance();

    $num = $api->getCrimesNumberByAreaCode($data['area_code']);
    unset($api);

    return json_encode($num);
});

$router->post('/api/get_crime_count_by_area_name', function($request) {
    $data = $request->getBody();

    $api =  API::getInstance();

    $num = $api->getCrimesNumberByAreaName($data['area_name']);
    unset($api);

    return json_encode($num);
});

$router->post('/api/get_crime_count_by_crime_type', function($request) {
    $data = $request->getBody();

    $api =  API::getInstance();

    $num = $api->getCrimesNumberByCrimeType($data['crime_type']);
    unset($api);

    return json_encode($num);
});

$router->post('/api/get_addresses_by_crime_type', function($request) {
    $data = $request->getBody();

    $api =  API::getInstance();

    $result = $api->getAddressByCrimeType($data['crime_type']);
    unset($api);

    return json_encode($result);
});

$router->post('/api/get_addresses_page_by_crime_type', function($request) {
    $data = $request->getBody();

    $api =  API::getInstance();

    if(array_key_exists('page_num', $data)) {
        $result = $api->getAddressPageByCrimeType($data['crime_type'], $data['page_num']);    
    }
    else {
        $result = $api->getAddressPageByCrimeType($data['crime_type']);
    }
    
    unset($api);

    return json_encode($result);
});