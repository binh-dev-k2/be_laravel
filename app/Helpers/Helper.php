<?php


function successResponse($message, $data = [], $status = 200)
{
    return response()->xml([
        'type' => 'success',
        'title' => $message,
        'content' => $data
    ], $status);
}

function errorResponse($message, $data = [], $status = 400)
{
    return response()->xml([
        'type' => 'error',
        'title' => $message,
        'content' => $data,
    ], $status);
}

function notFoundResponse($message, $data = [], $status = 404)
{
    return response()->xml([
        'type' => 'not_found',
        'title' => $message,
        'content' => $data,
    ], $status);
}
