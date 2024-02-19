<?php


function xmlSuccessResponse($code, $data = [])
{
    // return response()->xml($data, $status = 200, array $headers = [], $xmlRoot = 'response', $encoding = null);
    return response()->xml(
        [
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

function xmlErrorResponse($code, $data = [])
{
    // return response()->xml($data, $status = 200, array $headers = [], $xmlRoot = 'response', $encoding = null);
    return response()->xml(
        [
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

function successResponse($code, $data = [])
{
    return response()->json(
        [
            'data' => $data,
            'code' => $code,
        ],
        200
    );
}

function errorResponse($code, $data = [])
{
    return response()->json(
        [
            'data' => $data,
            'code' => $code,
        ],
        200
    );
}
