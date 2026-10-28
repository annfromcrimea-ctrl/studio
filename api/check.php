<?php
header('Content-Type: text/plain; charset=utf-8');

$file = dirname(__FILE__) . '/../data/data.json';
$dir = dirname($file);

echo "file: " . $file . "\n";
echo "dir: " . $dir . "\n";
echo "file_exists: " . (file_exists($file) ? "yes" : "no") . "\n";
echo "dir_writable: " . (is_writable($dir) ? "yes" : "no") . "\n";

if (file_exists($file)) {
    echo "file_readable: " . (is_readable($file) ? "yes" : "no") . "\n";
    echo "file_writable: " . (is_writable($file) ? "yes" : "no") . "\n";

    $raw = file_get_contents($file);
    $data = json_decode($raw, true);

    if (!is_array($data)) {
        echo "json_error: " . json_last_error_msg() . "\n";
    } else {
        if (isset($data['adminCode'])) {
            $c = (string)$data['adminCode'];
            echo "adminCode_masked: " . substr($c, 0, 2) . "***" . substr($c, -2) . "\n";
        }

        if (isset($data['products']) && is_array($data['products'])) {
            echo "products_count: " . count($data['products']) . "\n";

            $last = end($data['products']);
            if (is_array($last) && isset($last['name'])) {
                echo "last_product: " . $last['name'] . "\n";
            }
        }
    }
}