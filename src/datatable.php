<?php
 include 'header.php';
 $STATUS_LIST = ['รอดำเนินการ','กำลังดำเนินการแก้ไข','สำเร็จเสร็จสิ้น','ไม่สามารถดำเนินการได้'];
// รับค่ากรองจาก GET
$scope  = $_GET['scope']  ?? 'mine'; // ค่าเริ่มต้น: ดูเฉพาะของฉัน
$status = $_GET['status'] ?? 'all';  // ค่าเริ่มต้น: ทุกสถานะ

// ตรวจสอบว่าเป็นแอดมินหรือไม่ (เช็คทั้ง $MemberData และ $_SESSION)
$isAdmin = false;

if (!empty($MemberData['m_level'])) {
    $isAdmin = (strtolower($MemberData['m_level']) === 'admin');
} elseif (!empty($_SESSION['m_level'])) {
    $isAdmin = (strtolower($_SESSION['m_level']) === 'admin');
}

// ถ้าไม่ใช่แอดมิน บังคับให้ scope = mine เสมอ
if (!$isAdmin) {
    $scope = 'mine';
}


 include 'navbar.php';
 include 'sidebar_menu.php';
 include 'main_content_table.php';
 include 'footer.php';
?>

   
  