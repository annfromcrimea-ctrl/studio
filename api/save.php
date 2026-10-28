<?php
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate');

$file = dirname(__FILE__) . '/../data/data.json';
$dir = dirname($file);
$log = dirname(__FILE__) . '/save_log.txt';

$raw = file_get_contents('php://input');
$input = json_decode($raw, true);

if (!is_array($input) || empty($input['adminCode']) || !isset($input['data']) || !is_array($input['data'])) {
    http_response_code(400);
    echo json_encode(array('error' => 'Некорректный запрос'), JSON_UNESCAPED_UNICODE);
    exit;
}

$expected = '';
if (file_exists($file)) {
    $cur = json_decode(file_get_contents($file), true);
    if (is_array($cur) && isset($cur['adminCode'])) {
        $expected = (string)$cur['adminCode'];
    }
}

if ($expected === '' || $expected !== (string)$input['adminCode']) {
    @file_put_contents($log, date('Y-m-d H:i:s') . " FAIL sent=" . $input['adminCode'] . " expected=" . $expected . "\n", FILE_APPEND);
    http_response_code(403);
    echo json_encode(array('error' => 'Неверный код администратора'), JSON_UNESCAPED_UNICODE);
    exit;
}

if (!is_writable($dir)) {
    http_response_code(500);
    echo json_encode(array('error' => 'Папка data недоступна для записи'), JSON_UNESCAPED_UNICODE);
    exit;
}

$json = json_encode($input['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

if ($json === false || file_put_contents($file, $json) === false) {
    http_response_code(500);
    echo json_encode(array('error' => 'Не удалось записать data.json'), JSON_UNESCAPED_UNICODE);
    exit;
}

@file_put_contents($log, date('Y-m-d H:i:s') . " OK\n", FILE_APPEND);
echo json_encode(array('success' => true), JSON_UNESCAPED_UNICODE);