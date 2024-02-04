<?php


function successResponse($code, $data = [])
{
    return response()->xml([
        'status' => "Thành công",
        'data' => $data,
        'code' => $code,
    ], 200);
}

function errorResponse($code, $data = [])
{
    return response()->xml([
        'status' => "Thất bại",
        'data' => $data,
        'code' => $code,
    ], 200);
}
