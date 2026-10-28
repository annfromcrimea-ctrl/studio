<?php
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate');

$file = __DIR__ . '/../data/data.json';
if (file_exists($file)) {
    echo file_get_contents($file);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Файл данных не найден']);
}
?>