<?php

class ApiClient {
    private string $baseUrl;
    private array $headers;

    public function __construct(string $baseUrl, array $headers = []) {
        // Trim trailing slashes from the base URL
        $this->baseUrl = rtrim($baseUrl, '/');
        // Set default headers for REST interactions
        $this->headers = array_merge([
            'Content-Type: application/json',
            'Accept: application/json'
        ], $headers);
    }

    public function get(string $endpoint, array $queryParams = []): array {
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');
        if (!empty($queryParams)) {
            $url .= '?' . http_build_query($queryParams);
        }
        return $this->request('GET', $url);
    }

    public function post(string $endpoint, array $data = []): array {
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');
        return $this->request('POST', $url, $data);
    }

    public function put(string $endpoint, array $data = []): array {
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');
        return $this->request('PUT', $url, $data);
    }

    public function delete(string $endpoint): array {
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');
        return $this->request('DELETE', $url);
    }

    private function request(string $method, string $url, array $data = []): array {
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);

        if (in_array($method, ['POST', 'PUT']) && !empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            return ['error' => true, 'message' => $error];
        }

        curl_close($ch);
        
        $decoded = json_decode($response, true);
        return [
            'status_code' => $httpCode,
            'data' => json_last_error() === JSON_ERROR_NONE ? $decoded : $response
        ];
    }
}
