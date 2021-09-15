<?php

const HEART_RADIUS = 6371000;

/**
 * Parse the give path as array json and return an array or array
 * 
 * @param string  $path
 * @param bool  $associative
 * 
 * @return array
 */
if (! function_exists('parse_file_as_json')) {
    function parse_file_as_json(string $path, bool $associative = true) {
        // mapping lines into an array
        return array_map(function ($line) use ($associative) {
            $json = json_decode($line, $associative);

            if (JSON_ERROR_NONE !== json_last_error()) {
                throw new \Exception(json_last_error_msg(), json_last_error());
            }

            return $json;
        }, file($path));
    }
}

/**
 * Parse the give path as array json and return an array or array
 * 
 * @param float  $latFrom
 * @param float  $lngFrom
 * @param float  $latTo
 * @param float  $lngTo
 * @param int  $radius
 * 
 * @return array
 */
if (! function_exists('great_circle_distance')) {
    function great_circle_distance(float $latFrom, float $lngFrom, float $latTo, float $lngTo, int $radius = HEART_RADIUS) {
        $latFrom = deg2rad($latFrom);
        $lngFrom = deg2rad($lngFrom);

        $latTo = deg2rad($latTo);
        $lngTo = deg2rad($lngTo);

        $latDelta = $latTo - $latFrom;
        $lngDelta = $lngTo - $lngFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lngDelta / 2), 2)));
        
        return $angle * $radius;
    }
}