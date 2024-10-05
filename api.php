<?php
header('Content-Type: application/json');

$csvFile = './record.csv';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // 讀取 CSV 文件並返回 JSON
    $data = [];
    if (($handle = fopen($csvFile, 'r')) !== FALSE) {
        while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
            $data[] = [
                'Date' => $row[0],
                'BPH' => $row[1],
                'BPL' => $row[2],
                'Beat' => $row[3],
            ];
        }
        fclose($handle);
    }
    echo json_encode($data);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 接收 JSON 數據並寫入 CSV 文件
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (json_last_error() === JSON_ERROR_NONE) {
        $handle = fopen($csvFile, 'w');
        foreach ($data as $row) {
            fputcsv($handle, $row);
        }
        fclose($handle);
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid JSON']);
    }
}
?>
