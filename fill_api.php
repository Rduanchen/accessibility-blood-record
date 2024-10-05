<?php
// 檢查請求方法是否為GET
$filePath = 'record.csv';
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // 檢查所需的參數是否存在
    if (isset($_GET['BPH']) && isset($_GET['BPL']) && isset($_GET['Pulse'])) {
        $Date = $_GET['Date'];
        $BPH = $_GET['BPH'];
        $BPL = $_GET['BPL'];
        $Pulse = $_GET['Pulse'];
        
        // 檢查數值是否為數字
        if (is_numeric($BPH) && is_numeric($BPL) && is_numeric($Pulse)) {
            // 將數據寫入record.csv
            $file = fopen('record.csv', 'a'); // 以追加模式打開文件
            $data = array($Date, $BPH, $BPL, $Pulse);
            $existingData = [];
            if (($file = fopen($filePath, 'r')) !== FALSE) {
                while (($row = fgetcsv($file)) !== FALSE) {
                    $existingData[] = $row;
                }
                fclose($file);
            }else{
                echo json_encode(array('status' => 'error', 'message' => 'Failed to open file.'));
            }
            array_unshift($existingData, $data);
            if (($file = fopen($filePath, 'w')) !== FALSE) {
                foreach ($existingData as $row) {
                    fputcsv($file, $row);
                }
                fclose($file);
                echo json_encode(array('status' => 'success', 'message' => 'Record saved successfully.'));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Failed to open file.'));
            }

        } else {
            // 無效的輸入
            echo json_encode(array('status' => 'error', 'message' => 'Invalid input. Please enter numeric values.'));
        }
    } else {
        // 缺少必要參數
        echo json_encode(array('status' => 'error', 'message' => 'Missing required parameters.'));
    }
} else {
    // 非GET請求
    echo json_encode(array('status' => 'error', 'message' => 'Invalid request method.'));
}
?>