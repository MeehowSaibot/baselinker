<?php

class springService
{
    private string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = 'https://mtapi.net/?testMode=1';
    }

    /**
     * Hit 'OrderShipment' endpoint and returną
     *
     * @param  array  $order
     * @param  array  $params
     * @return array|string
     */
    public function newPackage(array $order, array $params): array|string
    {
        $command = 'OrderShipment';

        $requestBody = $this->createNewPackageRequestBody($params, $order);

        $endPoint = "$this->apiUrl/$command";

        try {
            $response = $this->post($endPoint, $requestBody);

            return $this->handleResponse($response);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Create cURL POST request
     *
     * @throws Exception
     */
    private function post(string $endpoint, array $requestBody): array
    {
        $ch = curl_init($endpoint);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: text/json',
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody));

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //SSL off
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //SSL off

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception('Request Error: ' . curl_error($ch));
        }

        curl_close($ch);

        return json_decode($response, true);
    }


    /**
     * Handle response from endpoint request and return proper information
     *
     * @param  array  $response
     * @return array|string
     */
    private function handleResponse(array $response): array|string
    {
        return match ($response['ErrorLevel']) {
            0, 1 => $this->prepareAndReturnPdf($response),
            10 => 'Failure during shipment creation. Message: ' . $response['Error'] . '.',
            default => 'Unknown ErrorLevel returned. Please contact the administrator.',
        };
    }

    /**
     * Creates a request body for 'OrderShipment' endpoint request
     *
     * @param  array  $params
     * @param  array  $data
     * @return array
     */
    private function createNewPackageRequestBody(array $params, array $data): array
    {
        $requestBody = [];

        $requestBody['Apikey'] = $params['api_key'];
        $requestBody['Command'] = 'OrderShipment';
        $requestBody['Shipment'] = [
            'LabelFormat' => $params['label_format'],
            'Service' => $params['service'],

            'ConsignorAddress' => [
                'Name' => $data['sender_fullname'],
                'Company' => $data['sender_company'],
                'AddressLine1' => $data['sender_address'],
                'City' => $data['sender_city'],
                'Zip' => $data['sender_postalcode'],
                'Email' => $data['sender_email'],
                'Phone' => $data['sender_phone'],
            ],

            'ConsigneeAddress' => [
                'Name' => $data['delivery_fullname'],
                'Company' => $data['delivery_company'],
                'AddressLine1' => $data['delivery_address'],
                'City' => $data['delivery_city'],
                'Zip' => $data['delivery_postalcode'],
                'Email' => $data['delivery_email'],
                'Phone' => $data['delivery_phone'],
                'Country' => $data['delivery_country'],
            ],
        ];

        return $requestBody;
    }

    /**
     * Handles returned base64-encoded-string and returns pdf to the browser
     *
     * @param  array  $response
     * @return string
     */
    private function prepareAndReturnPDF(array $response): string
    {
        $base64String = $response['Shipment']['LabelImage'];
        $filename = $response['Shipment']['CarrierTrackingNumber'] . '.pdf';

        $fileContent = base64_decode($base64String);

        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . basename($filename) . '"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . strlen($fileContent));

        return $fileContent;
    }
}




