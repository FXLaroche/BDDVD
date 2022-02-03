<?php

namespace App\Service;

class ApiAccess
{
    public function searchApi(string $search, string $apiKey, int $page = 1): ?string
    {
        $queryString = http_build_query([
            'apikey' => $apiKey,
            's' => $search,
            'page' => $page,
        ]);

        $curlHandle = curl_init(sprintf('%s?%s', 'https://www.omdbapi.com', $queryString));

        if ($curlHandle == true) {
            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curlHandle, CURLOPT_FOLLOWLOCATION, true);
            $apiResult = curl_exec($curlHandle);
            curl_close($curlHandle);
        }
        if (!isset($apiResult) || !is_string($apiResult)) {
            return "";
        }

        return $apiResult;
    }

    public function getApi(string $filmId, string $apiKey): ?string
    {
        $queryString = http_build_query([
            'apikey' => $apiKey,
            'i' => $filmId,
        ]);
        $curlHandle = curl_init(sprintf('%s?%s', 'https://www.omdbapi.com', $queryString));

        if ($curlHandle == true) {
            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curlHandle, CURLOPT_FOLLOWLOCATION, true);
            $apiResult = curl_exec($curlHandle);
            curl_close($curlHandle);
        }
        if (!isset($apiResult) || !is_string($apiResult)) {
            return "";
        }

        return $apiResult;
    }
}
