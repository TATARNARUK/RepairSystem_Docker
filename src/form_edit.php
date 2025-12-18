<?php
include 'header.php';
include 'navbar.php';
include 'sidebar_menu.php';
if (isset($_GET['id']) && $_GET['act'] == 'edit') {


  $stmtMemberDetail = $condb->prepare("SELECT* FROM tbl_member WHERE id=?");
  $stmtMemberDetail->execute([$_GET['id']]);
  $row = $stmtMemberDetail->fetch(PDO::FETCH_ASSOC);




  //ถ้าคิวรี่ผิดพลาดให้กลับไปหน้า index
  if ($stmtMemberDetail->rowCount() != 1) {
    exit();
  }
} //isset
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>แก้ไขโปรไฟล์</h1>
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
              <form action="" method="post">
                <div class="card-body">

                  <div class="form-group row">
                    <label class="col-sm-2">อีเมล</label>
                    <div class="col-sm-4">
                      <p><?php echo $MemberData['username']; ?></p>
                    </div>
                  </div>


                  <div class="form-group row">
                    <label class="col-sm-2">คำนำหน้า</label>
                    <div class="col-sm-2">
                      <select name="title_name" class="form-control" required>
                        <option value="<?php echo $MemberData['title_name']; ?>"><?php echo $MemberData['title_name']; ?></option>
                        <option disabled>เลือกข้อมูลใหม่</option>
                        <option value="นาย">นาย</option>
                        <option value="นาง">นาง</option>
                        <option value="นางสาว">นางสาว</option>
                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2">ชื่อ</label>
                    <div class="col-sm-4">
                      <input type="text" name="name" class="form-control" required placeholder="ชื่อ" value="<?php echo $MemberData['name']; ?>">
                    </div>
                  </div>


                  <div class="form-group row">
                    <label class="col-sm-2">นามสกุล</label>
                    <div class="col-sm-4">
                      <input type="text" name="surname" class="form-control" required placeholder="นามสกุล" value="<?php echo $MemberData['surname']; ?>">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2"></label>
                    <div class="col-sm-4">
                      <input type="hidden" name="id" value="<?php echo $MemberData['id']; ?>">
                      <button type="submit" class="btn btn-primary ">แก้ไขข้อมูล</button>
                      <a href="member.php" class="btn btn-danger">ยกเลิก</a>

                    </div>
                  </div>

                </div><!-- /.card-body -->
              </form>

              <?php

              //echo '<pre>';
              //print_r($_POST);
              //exit;

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

<?php
//echo '<pre>';
//print_r($_POST);
//exit;
// ก่อน if (isset($_POST['id'] ...)) อาจจะมีอยู่แล้ว แต่ย้ำให้มีแน่ ๆ
$stm = $condb->prepare("SELECT * FROM tbl_member WHERE id=:id");
$stm->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
$stm->execute();
$Target = $stm->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['surname'])) {

  //echo 'เข้ามาในเงื่อนไขได้';
  //exit;
  try {
    //ประกาศตัวแปลรับค่าจากฟอร์ม
    $id = $_POST['id'];
    $title_name = $_POST['title_name'];
    $name = htmlspecialchars($_POST['name']);
    $surname = htmlspecialchars($_POST['surname']);
    $m_level = $_POST['m_level'];
    //sql update
    $stmtUpdate = $condb->prepare(
      "UPDATE  tbl_member SET 
                  title_name=:title_name,
                  name=:name, 
                  surname=:surname,
                  m_level=:m_level 
                  WHERE id=:id"
    );

    $allowed = ['admin', 'staff', 'member']; // รายชื่อ level ที่อนุญาต
    if (isset($_POST['m_level']) && in_array($_POST['m_level'], $allowed, true)) {
      $m_level = $_POST['m_level'];
    } else {
      $m_level = $Target['m_level']; // ใช้ของเดิมถ้าไม่ได้ส่งมา
    }

    $loginRole = $MemberData['m_level'] ?? '';
    if ($loginRole !== 'admin') {
      // ถ้าไม่ใช่ admin ให้บังคับใช้ค่าของเดิม
      $m_level = $Target['m_level'];
    }

    //bindParam
    $stmtUpdate->bindParam(':id', $id, PDO::PARAM_INT);
    $stmtUpdate->bindParam(':title_name', $title_name, PDO::PARAM_STR);
    $stmtUpdate->bindParam(':name', $name, PDO::PARAM_STR);
    $stmtUpdate->bindParam(':surname', $surname, PDO::PARAM_STR);
    $stmtUpdate->bindParam(':m_level', $m_level, PDO::PARAM_STR);

    $result = $stmtUpdate->execute();

    $condb = null; //close connect db

    if ($result) {
      echo '<script>
                         setTimeout(function() {
                          swal({
                              title: "แก้ไขข้อมูลสำเร็จ",
                              type: "success"
                          }, function() {
                              window.location = "member.php"; //หน้าที่ต้องการให้กระโดดไป
                          });
                        }, 1000);
                    </script>';
    }
  } //try
  catch (Exception $e) {
    echo '<script>
              setTimeout(function() {
              swal({
                  title: "เกิดข้อผิดพลาด",
                  type: "error"
              }, function() {
                  window.location = "member.php"; //หน้าที่ต้องการให้กระโดดไป
              });
              }, 1000);
          </script>';
  } //catch
} //isset
include_once 'footer.php';
?>