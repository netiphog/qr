<?php
include 'phpqrcode/qrlib.php';

// ตรวจสอบว่ามีค่าที่ส่งมาหรือไม่
if (isset($_POST['text']) && !empty($_POST['text'])) {
    $text = $_POST['text'];
    $filename = 'qrcodes/' . md5($text) . '.png';

    // ตรวจสอบว่ามีโฟลเดอร์ qrcodes หรือไม่ ถ้าไม่มีให้สร้าง
    if (!file_exists('qrcodes')) {
        mkdir('qrcodes', 0777, true);
    }

    // สร้าง QR Code ใหม่
    QRcode::png($text, $filename, QR_ECLEVEL_L, 10, 2);

    // ตรวจสอบว่าไฟล์ถูกสร้างขึ้นสำเร็จหรือไม่
    if (file_exists($filename)) {
        echo json_encode(["success" => true, "qr_code" => $filename]);
    } else {
        echo json_encode(["success" => false, "message" => "เกิดข้อผิดพลาดในการสร้าง QR Code"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "กรุณากรอกข้อความ"]);
}
?>
