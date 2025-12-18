<?php
include 'header.php';
include 'navbar.php';
include 'sidebar_menu.php';
include 'footer.php';
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>ฟอร์มแจ้งซ่อม</h1>
                </div>
            </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-body">
                        <div class="card card-primary">
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="card-body">

                                    <div class="form-group row">
                                        <label class="col-sm-2">ชื่อผู้แจ้งซ่อม</label>
                                        <div class="col-sm-4">
                                            <p> <?php echo $MemberData['title_name'] . $MemberData['name'] . " " . $MemberData['surname']; ?> </p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2">เบอร์โทรศัพท์</label>
                                        <div class="col-sm-4">
                                            <input type="phone_number" name="phone_number" class="form-control" required placeholder="เบอร์โทรศัพท์">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2">เลือกประเภท</label>
                                        <div class="col-sm-4">
                                            <select name="category" class="form-control" required>
                                                <option>เลือกประเภท</option>
                                                <option value="ไฟฟ้า">ไฟฟ้า</option>
                                                <option value="ปะปา">ปะปา</option>
                                                <option value="อุปการณ์อิเล็กทรอนิกส์">อุปกรณ์อิเล็กทรอนิกส์</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2">ชื่ออุปกรณ์/รุ่น</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="repair_equipment" class="form-control" required placeholder="ชื่ออุปกรณ์/รุ่น">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2">ปัญหา/อาการเสีย/</label>
                                        <div class="col-sm-2">
                                            <textarea name="description" required rows="4" cols="54"></textarea><br>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2">สถานที่</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="location" class="form-control" required placeholder="สถานที่">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2">แนบรูปภาพ</label>
                                        <div class="col-sm-4">
                                            <input type="file" name="image_path" class="form-control" accept="image/*">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2"></label>
                                    <div class="col-sm-4">
                                        <button type="submit" class="btn btn-primary ">เพื่มข้อมูล</button>
                                        <a href="member.php" class="btn btn-danger">ยกเลิก</a>

                                    </div>
                                </div>

                        </div><!-- /.card-body -->
                        </form>
                        <?php
                        //เช็ค input ที่า่งมาจากฟอร์ม
                        //echo '<pre>';
                        //print_r($_POST);     
                        //exit;

                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            if (isset($_POST['phone_number']) && isset($_POST['category']) && isset($_POST['repair_equipment']) && isset($_POST['description'])) {

                                // ตั้งค่าตัวแปรสำหรับเก็บ path ของรูปภาพ
                                $image_path = null;

                                // ตรวจสอบว่ามีการอัปโหลดไฟล์หรือไม่
                                if (!empty($_FILES['image_path']['name'])) {
                                    $upload_dir = "image/"; // โฟลเดอร์เก็บรูปภาพ
                                    $image_name = time() . '_' . basename($_FILES['image_path']['name']); // เปลี่ยนชื่อไฟล์ให้ไม่ซ้ำ
                                    $target_file = $upload_dir . $image_name;

                                    // ตรวจสอบประเภทไฟล์ที่อนุญาต
                                    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                                    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

                                    if (in_array($imageFileType, $allowed_types)) {
                                        // ย้ายไฟล์ที่อัปโหลดไปยังโฟลเดอร์ที่ต้องการ
                                        if (move_uploaded_file($_FILES['image_path']['tmp_name'], $target_file)) {
                                            $image_path = $target_file; // บันทึก path ของไฟล์ที่ถูกอัปโหลด
                                        } else {
                                            throw new Exception("ไม่สามารถอัปโหลดไฟล์ได้");
                                        }
                                    } else {
                                        throw new Exception("อนุญาตเฉพาะไฟล์ประเภท JPG, JPEG, PNG, GIF เท่านั้น");
                                    }
                                }
                            }
                        }


                        if (isset($_POST['phone_number'], $_POST['category'], $_POST['repair_equipment'], $_POST['description'])) {
                            try {

                                $member_id = (int)($MemberData['id'] ?? $_SESSION['user_id'] ?? $_SESSION['id'] ?? 0);

                                if ($member_id === 0) {
                                    throw new Exception('ไม่พบรหัสผู้ใช้งาน (member_id) กรุณาเข้าสู่ระบบใหม่');
                                }

                                // รับค่าจากฟอร์ม
                                $repair_status    = "รอดำเนินการ";
                                $phone_number     = $_POST['phone_number'];
                                $category         = $_POST['category'];
                                $repair_equipment = $_POST['repair_equipment'];
                                $description      = $_POST['description'];
                                $location         = $_POST['location'];
                                $image_path       = $image_path ?? null; // ถ้าไม่ได้อัปโหลดรูปให้เป็น null

                                // คำสั่ง SQL
                                $sql = "INSERT INTO tdl_form_repair
                                            (member_id, repair_status, phone_number, category, repair_equipment, description, location, image_path, created_at)
                                            VALUES
                                            (:member_id, :repair_status, :phone_number, :category, :repair_equipment, :description, :location, :image_path, NOW())";
                                $stmtformrepair = $condb->prepare($sql);

                                // Bind Parameters (ยกเว้น image_path)
                                $stmtformrepair->bindParam(':member_id',        $member_id,        PDO::PARAM_INT);
                                $stmtformrepair->bindParam(':repair_status',    $repair_status,    PDO::PARAM_STR);
                                $stmtformrepair->bindParam(':phone_number',     $phone_number,     PDO::PARAM_STR);
                                $stmtformrepair->bindParam(':category',         $category,         PDO::PARAM_STR);
                                $stmtformrepair->bindParam(':repair_equipment', $repair_equipment, PDO::PARAM_STR);
                                $stmtformrepair->bindParam(':description',      $description,      PDO::PARAM_STR);
                                $stmtformrepair->bindParam(':location',         $location,         PDO::PARAM_STR);

                                // ✅ จัดการ image_path ให้รองรับ NULL จริง ๆ (อย่ามี bindParam ของ image_path ก่อนหน้านี้)
                                if ($image_path === null || $image_path === '') {
                                    $stmtformrepair->bindValue(':image_path', null, PDO::PARAM_NULL);
                                } else {
                                    $stmtformrepair->bindValue(':image_path', $image_path, PDO::PARAM_STR);
                                }
                                $result = $stmtformrepair->execute();
                                $condb = null; // ปิดการเชื่อมต่อฐานข้อมูล

                                if ($result) {
                                    echo '<script>
                                setTimeout(function() {
                                    swal({
                                        title: "เพิ่มข้อมูลสำเร็จ",
                                        type: "success"
                                    }, function() {
                                        window.location = "datatable.php";
                                    });
                                }, 1000);
                            </script>';
                                }
                            } catch (Exception $e) {
                                echo '<script>
                            setTimeout(function() {
                                swal({
                                    title: "เกิดข้อผิดพลาด",
                                    text: "' . $e->getMessage() . '",
                                    type: "error"
                                }, function() {
                                    window.location = "member_form_repair.php";
                                });
                            }, 1000);
                        </script>';
                            }
                        }
                        ?>


                    </div>
                </div>
            </div>
        </div>
        <!-- /.col-->
</div>
<!-- ./row -->
<!-- ./row -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->