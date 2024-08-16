<?php

namespace Untek\Framework\WebTest\Helpers;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class TestHttpRequestHelper
{

    public static function prepareHeaderKeys(array $headers): array {
        $result = [];
        foreach ($headers as $headerKey => $headerValue) {
            $headerKey = self::prepareHeaderKey($headerKey);
            $result[$headerKey] = $headerValue;
        }
        return $result;
    }

    protected static function prepareHeaderKey(string $name): string {
        return strtr(strtoupper($name), '-', '_');
    }

    /**
     * Extract the file uploads from the given data array.
     *
     * @param array $data
     * @return array
     */
    public static function extractFilesFromDataArray(array &$data): array
    {
        $files = [];

        foreach ($data as $key => $value) {
            if ($value instanceof UploadedFile) {
                $files[$key] = $value;

                unset($data[$key]);
            }

            if (is_array($value)) {
                $files[$key] = self::extractFilesFromDataArray($value);

                $data[$key] = $value;
            }
        }

        return $files;
    }

    /**
     * Transform headers array to array of $_SERVER vars with HTTP_* format.
     *
     * @param  array  $headers
     * @return array
     */
    public static function transformHeadersToServerVars(array $headers): array
    {
        $headers = self::prepareHeaderKeys($headers);

        $result = [];
        foreach ($headers as $headerKey => $headerValue) {
            $headerKey = self::formatServerHeaderKey($headerKey);
            $result[$headerKey] = $headerValue;
        }
        
        return $result;
    }

    /**
     * Format the header name for the server array.
     *
     * @param string $name
     * @return string
     */
    protected static function formatServerHeaderKey(string $name): string
    {
        if (! str_starts_with($name, 'HTTP_') && $name !== 'CONTENT_TYPE' && $name !== 'REMOTE_ADDR') {
            return 'HTTP_'.$name;
        }

        return $name;
    }

}
