<?php

namespace App\Traits;







trait ApiResponse
{
    /**
     * @param $result
     * @param $message
     * @param $code
     * @return mixed
     */
    public function sendResponse($result, $message, $code)
    {

        return response()->json(self::makeResponse($message, $result), $code);
    }

    /**
     * @param $error
     * @param int $code
     * @param array $data
     * @return mixed
     */
    public function sendError($error, $code = 400, $errorMessages = [])
    {
        return response()->json(self::makeError($error, $errorMessages), $code);
    }

    /**
     * @param string $message
     * @param mixed $data
     *
     * @return array
     */
    public static function makeResponse($message, $data)
    {
        return [
            'success' => true,
            'data' => $data,
            'message' => $message,
        ];
    }

    /**
     * @param string $message
     * @param array $data
     *
     * @return array
     */
    public static function makeError($message, array $data = [])
    {
        $result = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($data)) {
            $result['data'] = $data;
        }

        return $result;
    }
}