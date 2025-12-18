<?php
// ให้แน่ใจว่า session พร้อมใช้
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// ดึง level จาก $MemberData ถ้าไม่มีให้ fallback เป็น $_SESSION
$level = '';
if (isset($MemberData['m_level'])) {
    $level = $MemberData['m_level'];
} elseif (!empty($_SESSION['m_level'])) {
    $level = $_SESSION['m_level'];
}

// normalize เพื่อตัดช่องว่าง/ตัวพิมพ์
$level = strtolower(trim((string)$level));
?>

<?php
  //คิวลี่ข้อมูลของคนที่ผ่านการ login มาแล้ว
  $MemberDetail = $condb->prepare("SELECT * FROM tbl_member WHERE id=:id");
   //bindParam
  $MemberDetail->bindParam(':id', $_SESSION['id'] , PDO::PARAM_INT);
  $MemberDetail->execute();
  $MemberData = $MemberDetail->fetch(PDO::FETCH_ASSOC);
 
  ?>
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color:rgba(0, 89, 255, 1);">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
      <img src="assets/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Hi <?=$MemberData['name'];?> </span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="dashbord.php" class="nav-link text-white">
            <i class="fas fa-home nav-icon"></i>
              <p>
                หน้าหลัก
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="form_repair.php" class="nav-link text-white">
              <i class="nav-icon far fa-edit"></i>
              <p>
              หน้าแจ้งซ่อม
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="datatable.php" class="nav-link text-white">
              <i class="nav-icon fas fa-list"></i>
              <p>
              ตารางแจ้งซ่อม
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="form_edit.php" class="nav-link text-white">
              <i class="nav-icon fas fa-user"></i>
              <p>
                แก้ไขโปรไฟล์
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="form_edit_password.php" class="nav-link text-white">
              <i class="nav-icon fa fa-wrench"></i>
              <p>
              แก้ไขรหัสผ่า่น
              </p>
            </a>
          </li>
            <?php if (in_array($level, ['admin'], true)): ?>
              <li class="nav-item">
                <a href="member_list.php" class="nav-link text-white">
                  <i class="nav-icon fas fa-users"></i>
                  <p>จัดการข้อมูลพนักงาน</p>
                </a>
              </li>
            <?php endif; ?>
          <li class="nav-item">
            <a href="login.php" class="nav-link text-white">
              <i class="nav-icon fas fa-lock"></i>
              <p>
                ออกจาากระบบ
              </p>
            </a>
          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>