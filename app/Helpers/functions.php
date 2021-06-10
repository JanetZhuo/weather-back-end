<?php

function respond($status, $message, $data = null)
{
    if ($data == null) {
        $data = new class {
        };
    }
    return response()->json(['status' => $status, 'message' => $message, 'data' => $data]);
}

function succeed($message, $data = null)
{
    return respond(1, $message, $data);
}

function failed($message, $data = null, $status = 0)
{
    return respond($status, $message, $data);
}

function curlGet($url)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//这个是重点。
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $output = curl_exec($ch);
    curl_close($ch);
    return json_decode($output, true);
}
