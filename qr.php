<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สร้าง QR Code</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <h2 class="text-center mb-4"><i class="fa-solid fa-qrcode"></i> สร้าง QR Code</h2>
            <form id="qrForm">
                <div class="mb-3">
                    <label for="text" class="form-label"><i class="fa-solid fa-edit"></i> กรอกข้อความ:</label>
                    <input type="text" name="text" id="text" class="form-control" required>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-wand-magic-sparkles"></i> สร้าง QR Code
                    </button>
                    <button type="button" id="refreshBtn" class="btn btn-secondary w-100">
                        <i class="fa-solid fa-arrows-rotate"></i> รีเฟรช
                    </button>
                </div>
            </form>

            <!-- พื้นที่แสดง QR Code -->
            <div class="text-center mt-4" id="qrResult" style="display: none;">
                <h5><i class="fa-solid fa-qrcode"></i> QR Code ของคุณ</h5>
                <img id="qrImage" class="img-fluid mt-2 shadow" alt="QR Code">
            </div>

            <!-- ปุ่มย้อนกลับและดาวน์โหลด -->
            <div class="text-center mt-4 d-flex justify-content-center gap-3">
                <a href="../index.php" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> ย้อนกลับ
                </a>
                <a href="#" id="downloadBtn" class="btn btn-success" style="display: none;">
                    <i class="fa-solid fa-download"></i> ดาวน์โหลด QR Code
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 & jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // เมื่อกดปุ่มสร้าง QR Code
            $("#qrForm").submit(function(event) {
                event.preventDefault(); // ป้องกันการโหลดหน้าใหม่

                let text = $("#text").val().trim();
                if (text === "") {
                    Swal.fire(" กรุณากรอกข้อความ!", "", "warning");
                    return;
                }

                // ส่งค่าไปยัง PHP ผ่าน AJAX
                $.ajax({
                    url: "generate_qr.php",
                    type: "POST",
                    data: { text: text },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            let timestamp = new Date().getTime(); // บังคับโหลดใหม่
                            $("#qrImage").attr("src", response.qr_code + "?t=" + timestamp);
                            $("#qrResult").fadeIn();
                            $("#downloadBtn").fadeIn(); // แสดงปุ่มดาวน์โหลด
                            
                            // กำหนดลิงก์ดาวน์โหลด
                            $("#downloadBtn").attr("href", response.qr_code);
                            $("#downloadBtn").attr("download", "QRCode_" + timestamp + ".png");
                            
                            Swal.fire({
                                icon: "success",
                                title: " สำเร็จ!",
                                text: "QR Code ถูกสร้างแล้ว",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire(" ไม่สามารถสร้าง QR Code ได้", response.message, "error");
                        }
                    },
                    error: function() {
                        Swal.fire(" เกิดข้อผิดพลาด!", "โปรดลองใหม่อีกครั้ง", "error");
                    }
                });
            });

            // เมื่อกดปุ่มรีเฟรช
            $("#refreshBtn").click(function() {
                $("#text").val(""); // ล้างข้อความใน input
                $("#qrResult").fadeOut(); // ซ่อน QR Code
                $("#downloadBtn").fadeOut(); // ซ่อนปุ่มดาวน์โหลด
            });
        });
    </script>
</body>
</html>