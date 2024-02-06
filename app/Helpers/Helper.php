<?php


function successResponse($code, $data = [])
{
    // return response()->xml($data, $status = 200, array $headers = [], $xmlRoot = 'response', $encoding = null);
    return response()->xml(
        [
            'status' => "Thành công",
            'data' => $data,
            'code' => $code,
        ],
        200,
        [
            'Content-Type' => 'application/xml',
        ],
        'response',
        'utf-8'
    );
}

function errorResponse($code, $data = [])
{
    // return response()->xml($data, $status = 200, array $headers = [], $xmlRoot = 'response', $encoding = null);
    return response()->xml(
        [
            'status' => "Thất bại",
            'data' => $data,
            'code' => $code,
        ],
        200,
        [
            'Content-Type' => 'application/xml',
        ],
        'response',
        'utf-8'
    );
}
