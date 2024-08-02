<?php

require 'springService.php';

$springService = new springService();

$order = [
    'sender_company' => 'BaseLinker',
    'sender_fullname' => 'Jan Kowalski',
    'sender_address' => 'Kopernika 10',
    'sender_city' => 'Gdansk',
    'sender_postalcode' => '80208',
    'sender_email' => '',
    'sender_phone' => '666666666',

    'delivery_company' => 'Spring GDS',
    'delivery_fullname' => 'Maud Driant',
    'delivery_address' => 'Strada Foisorului, Nr. 16, Bąl. F11C, Sc. 1, Ap. 10',
    'delivery_city' => 'Bucuresti, Sector 3',
    'delivery_postalcode' => '031179',
    'delivery_country' => 'RO',
    'delivery_email' => 'john@doe.com',
    'delivery_phone' => '555555555',
];

$params = [
    'api_key' => 'f16753b55cac6c6e',
    'label_format' => 'PDF',
    'service' => 'PPTT',
];

try {
    $newPackageResponse = $springService->newPackage($order, $params);

    if (is_string($newPackageResponse)) {
        http_response_code(500);
        echo $newPackageResponse;
    }
} catch (Exception $e) {
    return $e->getMessage();
}