<?php


function xmlResponse($code, $data = [])
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

function jsonResponse($code, $data = [])
{
    return response()->json(
        [
            'data' => $data,
            'code' => $code,
        ],
        200
    );
}
