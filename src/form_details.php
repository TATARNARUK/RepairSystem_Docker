<?php
include 'header.php';
include 'navbar.php';
include 'sidebar_menu.php';
include 'footer.php';

// --- ดึง id จาก URL ---
if (!isset($_GET['id'])) {
  echo "<script>alert('ไม่พบรหัสรายการซ่อม'); window.location='datatable.php';</script>";
  exit;
}
$id = (int)$_GET['id']; // แปลงให้เป็นตัวเลข

// --- ดึงข้อมูลของรายการซ่อมเฉพาะ id ที่ส่งมา ---
$sql = "SELECT * FROM tdl_form_repair WHERE id = :id";
$query = $condb->prepare($sql);
$query->bindParam(':id', $id, PDO::PARAM_INT);
$query->execute();
$fetch = $query->fetch(PDO::FETCH_ASSOC);

// … include header/navbar/sidebar แล้ว

if (($_POST['action'] ?? '') === 'update_status') {
  // อนุญาตเฉพาะ admin
  if (empty($MemberData['m_level']) || strtolower($MemberData['m_level']) !== 'admin') {
    die('ไม่อนุญาต');
  }

  $id  = (int)($_POST['id'] ?? 0);
  $new = trim($_POST['repair_status'] ?? '');

  // validate ค่าสถานะ
  $allowed = ['รอดำเนินการ', 'กำลังดำเนินการแก้ไข', 'สำเร็จเสร็จสิ้น', 'ไม่สามารถดำเนินการได้'];
  if ($id <= 0 || !in_array($new, $allowed, true)) {
    echo '<script>
      setTimeout(function() {
        swal({
          title: "เกิดข้อผิดพลาด",
          text: "ข้อมูลไม่ถูกต้อง หรือคุณไม่มีสิทธิ์",
          type: "error"
        }, function () {
          window.location = "datatable.php";
        });
      }, 300);
      </script>';
    exit;
    if (isset($_POST['btn_status'])) {
      $repair_status = $_POST['repair_status'];
      $id            = (int)$_POST['id'];
      $userId        = $_SESSION['user_id'];   // คนที่ล็อกอินอยู่

      $sql = "UPDATE tdl_form_repair
            SET repair_status      = ?,
                status_updated_by  = ?,
                status_updated_at  = NOW()
            WHERE id = ?";

      $stmt = $conn->prepare($sql);
      $stmt->bind_param("sii", $repair_status, $userId, $id);
      $stmt->execute();

      // แล้วค่อย redirect / ย้อนกลับ
      header("Location: datatable.php");
      exit;
    }

    // ✅ อัปเดตสถานะ + ผู้แก้ไข + วันที่เวลา
    $sql = "UPDATE tdl_form_repair 
               SET repair_status = :st,
                   status_updated_by = :uid,
                   status_updated_at = NOW()
             WHERE id = :id";

    $upd = $condb->prepare($sql);
    $upd->bindParam(':st', $new, PDO::PARAM_STR);
    $upd->bindParam(':id', $id, PDO::PARAM_INT);
    $upd->bindParam(':uid', $MemberData['id'], PDO::PARAM_INT);
    $upd->execute();

    // กลับไปหน้ารายละเอียดเดิม
    $back = 'form_details.php?act=details&id=' . $id;
    echo '<script>
      setTimeout(function() {
        swal({
          title: "อัปเดตสถานะสำเร็จ",
          type: "success"
        }, function () {
          window.location = "form_details.php?act=details&id=' . $id . '";
        });
      }, 300);
      </script>';
    exit;
  }

  // อัปเดตสถานะ
  $upd = $condb->prepare("UPDATE tdl_form_repair SET repair_status=:st WHERE id=:id");
  $upd->bindParam(':st', $new, PDO::PARAM_STR);
  $upd->bindParam(':id', $id, PDO::PARAM_INT);
  $upd->execute();

  // กลับมาหน้าปัจจุบัน (query string เดิม)
  $back = 'form_details.php?act=details&id=' . $id;
  echo '<script>
      setTimeout(function() {
        swal({
          title: "อัปเดตสถานะสำเร็จ",
          type: "success"
        }, function () {
          window.location = "form_details.php?act=details&id=' . $id . '";
        });
      }, 300);
      </script>';

  exit;
}


if (!isset($_GET['id'])) {
  echo "<script>alert('ไม่พบรหัสรายการ');location='datatable.php';</script>";
  exit;
}
$id = (int)$_GET['id'];

$sql = "
  SELECT r.*,
         m.title_name, m.name, m.surname
  FROM tdl_form_repair AS r
  LEFT JOIN tbl_member AS m ON m.id = r.member_id
  WHERE r.id = :id
";
$query = $condb->prepare($sql);
$query->bindParam(':id', $id, PDO::PARAM_INT);
$query->execute();
$fetch = $query->fetch(PDO::FETCH_ASSOC);

if (!$fetch) {
  echo "<script>alert('ไม่พบข้อมูลรายการนี้');location='datatable.php';</script>";
  exit;
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>รายละเอียดแจ้งซ่อม</h1>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-outline card-info">
          <div class="card-body">
            <div class="card card-primary">
              <form action="" method="post">
                <div class="card-body">
                  <div class="form-group row">
                    <label class="col-sm-2">ชื่อผู้แจ้งซ่อม</label>
                    <div class="col-sm-4">
                      <p>
                        <?= htmlspecialchars(
                          trim(($fetch['title_name'] ?? '') . ($fetch['name'] ?? '') . ' ' . ($fetch['surname'] ?? ''))
                        ) ?: '-' ?>
                      </p>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2">เบอร์โทรศัพท์</label>
                    <div class="col-sm-4">
                      <p><?= htmlspecialchars($fetch['phone_number']) ?></p>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2">สถานที่</label>
                    <div class="col-sm-4">
                      <p><?= htmlspecialchars($fetch['location']) ?></p>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2">ชื่ออุปกรณ์/รุ่น</label>
                    <div class="col-sm-4">
                      <p><?= htmlspecialchars($fetch['repair_equipment']) ?></p>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2">ปัญหา/อาการเสีย</label>
                    <div class="col-sm-4">
                      <p><?= htmlspecialchars($fetch['description']) ?></p>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2">รูปภาพที่แนบ</label>
                    <div class="col-sm-4">
                      <?php
                      // path ที่บันทึกใน DB เช่น "image/1740914115_Screenshot.png"
                      $imgRel = $fetch['image_path'] ?? '';

                      if (!empty($imgRel)) {
                        // สร้าง path บนดิสก์สำหรับเช็คมีไฟล์จริง (ป้องกันรูปหาย)
                        $imgDisk = __DIR__ . '/' . $imgRel;

                        if (is_file($imgDisk)) {
                          // แสดง thumbnail และคลิกดูรูปใหญ่ได้
                          $imgUrl = htmlspecialchars($imgRel, ENT_QUOTES, 'UTF-8');
                          echo '<a href="' . $imgUrl . '" target="_blank" rel="noopener">';
                          echo '<img src="' . $imgUrl . '" alt="แนบรูปแจ้งซ่อม" style="max-width:300px; height:auto; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,.15)">';
                          echo '</a>';
                          echo '<div><small class="text-muted">คลิกที่รูปเพื่อดูขนาดเต็ม</small></div>';
                        } else {
                          echo '<span class="text-muted">ไม่พบไฟล์รูปภาพบนเซิร์ฟเวอร์</span>';
                        }
                      } else {
                        echo '-';
                      }
                      ?>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2">สถานะการซ่อม</label>
                    <div class="col-sm-4">
                      <?php
                      $status = htmlspecialchars($fetch['repair_status'] ?? 'รอดำเนินการ');
                      $statusClass = [
                        'รอดำเนินการ' => 'badge-secondary',
                        'กำลังดำเนินการแก้ไข' => 'badge-warning',
                        'สำเร็จเสร็จสิ้น' => 'badge-success',
                        'ไม่สามารถดำเนินการได้' => 'badge-danger'
                      ];
                      $class = $statusClass[$status] ?? 'badge-secondary';
                      ?>
                      <span class="badge <?= $class ?>" style="font-size:16px; padding:8px 12px;">
                        <?= $status ?>
                      </span>
                    </div>
                  </div>

                  <!-- 🕒 เวลาที่อัปเดตสถานะล่าสุด -->
                  <div class="form-group row">
                    <label class="col-sm-2">อัปเดตสถานะล่าสุดเมื่อ</label>
                    <div class="col-sm-4">
                      <?php
                      // ตรวจสอบว่ามีข้อมูลวันที่อัปเดตหรือไม่
                      if (!empty($fetch['status_updated_at'])) {
                        $updatedAt = date("d/m/Y H:i:s", strtotime($fetch['status_updated_at']));
                        echo "<p>{$updatedAt}</p>";
                      } else {
                        echo "<p>-</p>"; // ถ้ายังไม่เคยอัปเดต
                      }
                      ?>
                    </div>
                  </div>
                  <?php
                  // แสดงฟอร์มอัปเดตสถานะเฉพาะ admin
                  if (!empty($MemberData['m_level']) && strtolower($MemberData['m_level']) === 'admin'):
                    // รายการสถานะที่อนุญาต
                    $statuses = ['รอดำเนินการ', 'กำลังดำเนินการแก้ไข', 'สำเร็จเสร็จสิ้น', 'ไม่สามารถดำเนินการได้'];
                    // ค่าสถานะปัจจุบันจาก DB
                    $current = $fetch['repair_status'] ?? 'รอดำเนินการ';
                  ?>
                    <hr>
                    <form method="post" class="form-inline">
                      <input type="hidden" name="action" value="update_status">
                      <input type="hidden" name="id" value="<?= (int)$fetch['id']; ?>">

                      <div class="form-group mr-2">
                        <label class="mr-2">อัปเดตสถานะ</label>
                        <select name="repair_status" class="form-control" required>
                          <?php foreach ($statuses as $st): ?>
                            <option value="<?= htmlspecialchars($st); ?>" <?= $st === $current ? 'selected' : ''; ?>>
                              <?= htmlspecialchars($st); ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <button type="submit" class="btn btn-primary ml-2">บันทึกสถานะ</button>
                    </form>
                  <?php endif; ?>
                  <div class="form-group row">
                    <label class="col-sm-2"></label>
                    <div class="col-sm-4">
                      <a href="datatable.php" class="btn btn-danger">ย้อนกลับ</a>
                    </div>
                  </div>

                </div>
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>