<?php

$prompt = $_GET['prompt'] ?? '';
if ($prompt === '') {
    echo json_encode(['error' => 'Empty prompt!']);
    exit();
}

$prompt = rawurldecode($prompt);
$settingPath = 'settings.txt';
if (file_exists($settingPath) === false) {
    echo json_encode(['error' => "$settingPath file is not found!"]);
    exit();
}

$settings = file_get_contents('settings.txt');
$arrSetting = explode(PHP_EOL, $settings);
if (count($arrSetting) !== 3) {
    echo json_encode(['error' => 'setting file format is invalid!']);
    exit();
}

$accountId = $arrSetting[0];
$token = $arrSetting[1];

$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => "https://api.cloudflare.com/client/v4/accounts/$accountId/ai/run/@cf/lykon/dreamshaper-8-lcm",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode(['prompt' => 'string']),
  CURLOPT_HTTPHEADER => [
    "Authorization: Bearer $token",
    "Content-Type: application/json",
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err !== '') {
    echo json_encode(['error' => $err]);
    exit();
}

echo json_encode(['result' => base64_encode($response)]);
