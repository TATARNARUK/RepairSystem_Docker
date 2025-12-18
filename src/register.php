<style>
  .form-row-inline {
    display: flex;
    align-items: center;
    gap: 10px;        /* ระยะห่างระหว่าง label กับ select */
    margin-bottom: 10px;
}

.form-row-inline label {
    margin: 0;
    font-weight: 600;
    min-width: 100px;   /* กำหนดความกว้างของคำว่า "คำนำหน้า" */
}

.form-row-inline select {
    flex: 1;            /* ทำให้ select ขยายเต็มที่ */
}

  .topbar-bncc {
  width: 100%;
  background-color: #ffffff;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  position: fixed;       /* ให้อยู่ติดด้านบน */
  top: 0;
  left: 0;
  z-index: 999;
}

.topbar-bncc-inner {
  max-width: 1200px;
  margin: 0 auto;
  padding: 8px 20px;
}

.topbar-bncc-title {
  font-size: 24px;
  font-weight: 700;
  color: #000;
  line-height: 1.2;
}
.topbar-bncc {
  width: 100%;
  background-color: #ffffff;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  position: fixed;
  top: 0;
  left: 0;
  z-index: 999;
}

/* ขยายแถบให้ใหญ่ขึ้น */
.topbar-bncc-inner {
  max-width: 1300px;
  margin: 0 auto;
  padding: 20px 25px;   /* 👈 เพิ่มจาก 8px → 20px */
  display: flex;
  align-items: center;
  gap: 18px;
}

/* ขยายโลโก้ให้พอดีกับแถบใหม่ */
.topbar-bncc-logo {
  width: 75px;          /* เดิม 60px */
  height: 75px;
  object-fit: contain;
}


/* ดันกล่องล็อกอินลงล่างเพราะมี topbar */
.login-box {
  margin-top: 170px !important;   /* เดิม 130px */
}

.topbar-bncc-subtitle {
  font-size: 14px;
  color: #777777;
}

.login-box {
  margin-top: 120px;   /* ดันกล่องล็อกอินลงมาหน่อย เพราะมี topbar */
}

  body {
    background: url('image/your_background.jpg') no-repeat center center fixed;
    background-size: cover;
    font-family: "Prompt", sans-serif;
  }

  .login-container {
    background: rgba(255, 255, 255, 0.15); /* โปร่งใส */
    backdrop-filter: blur(10px); /* เบลอพื้นหลัง */
    -webkit-backdrop-filter: blur(10px);
    border-radius: 15px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
    padding: 30px;
    width: 350px;
    margin: 60px auto;
    color: #fff;
  }

  .login-container h1 {
    background: rgba(0, 0, 0, 0.4);
    display: inline-block;
    padding: 8px 20px;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
  }

  .login-box {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(15px);
    border-radius: 15px;
    padding: 30px;
    color: #000;
  }

  .login-box input {
    border-radius: 10px;
    border: none;
    padding: 10px;
  }

  .btn-primary {
    border-radius: 10px;
    background-color: #007bff;
    border: none;
  }

  .btn-danger {
    border-radius: 10px;
  }
</style>

<?php
//ไฟล์เชื่อมต่อฐานข้อมูล
require_once 'config/connect.php';

?>
<?php

if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['action']) && $_POST['action'] == 'register') 
{
  //echo 'ถูกเงื่อนไข ส่งข้อมูลมาได้';

  try {



    //ประกาศตัวแปลรับค่าจากฟอร์ม
    $username = htmlspecialchars($_POST['username']);
    $password = sha1($_POST['password']);
    $title_name = $_POST['title_name'];
    $name = htmlspecialchars($_POST['name']);
    $surname = htmlspecialchars($_POST['surname']);

    
    //เช็ก username ซ่ำ
    //singte row query แสดงแค่ 1 รายการ
    $stmtMemberDetail = $condb->prepare("SELECT username FROM tbl_member WHERE username=:username");
    //bindParam
    $stmtMemberDetail->bindParam(':username', $username, PDO::PARAM_STR);
    $stmtMemberDetail->execute();
    $row = $stmtMemberDetail->fetch(PDO::FETCH_ASSOC);

    //นับจำนวนการคิวรี่ ถ้าได้ 1 คือ username ซ่ำ
    //echo $stmtMemberDetail->rowCount();
    //echo '<hr>';
    if ($stmtMemberDetail->rowCount() == 1) {
      //echo 'username ซ่ำ';
      echo '<script>
                    setTimeout(function() {
                    swal({
                        title: "username ซ้ำ !!",
                        text: "สมัครสมาชิกใหม่อีกครั้ง",
                        type: "error"
                    }, function() {
                        window.location = "register.php"; //หน้าที่ต้องการให้กระโดดไป
                    });
                    }, 1000);
                </script>';
    } else {
      //echo 'ไม่มี username ซ่ำ';
      //sql insert
      $stmtInserMember = $condb->prepare("INSERT INTO tbl_member 
      (username,password,title_name,name,surname,m_level)
      VALUES 
      (:username,'$password',:title_name,:name,:surname,'member')
      ");
      
      //bindParam
      $stmtInserMember->bindParam(':username', $username, PDO::PARAM_STR);
      $stmtInserMember->bindParam(':title_name', $title_name, PDO::PARAM_STR);
      $stmtInserMember->bindParam(':name', $name, PDO::PARAM_STR);
      $stmtInserMember->bindParam(':surname', $surname , PDO::PARAM_STR);
      $result = $stmtInserMember->execute();
      $condb = null; //close connect db

      if ($result) {
        echo '<script>
                    setTimeout(function() {
                    swal({
                        title: "สมัครสมาชิกสำเร็จ",
                        type: "success"
                    }, function() {
                        window.location = "login.php"; //หน้าที่ต้องการให้กระโดดไป
                    });
                    }, 1000);
                </script>';
      }
    } //เช็คข้อมูลซ้ำ


  } // close try
  catch (Exception $e) {
    // echo 'Message:'.$e->getMessage();
    // exit;
    echo '<script>
              setTimeout(function() {
              swal({
                  title: "เกิดข้อผิดพลาด",
                  type: "error"
              }, function() {
                  window.location = "register.php"; //หน้าที่ต้องการให้กระโดดไป
              });
              }, 1000);
          </script>';
  } // close catch
} //isset
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
  <!-- summernote -->
  <link rel="stylesheet" href="assets/plugins/summernote/summernote-bs4.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

  <!-- sweet alert -->
  <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">

</head>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register</title>
</head>

<body class="hold-transition login-page">
<style>
body {
  background-image: url('วิทยาลัยพณิชยการบางนา.jpg');
  background-repeat: no-repeat;
    background-attachment: fixed;  
  background-size: cover;
}
</style>
<body class="hold-transition login-page">
<style>
body {
  background-image: url('วิทยาลัยพณิชยการบางนา.jpg');
  background-repeat: no-repeat;
  background-attachment: fixed;  
  background-size: cover;
}
</style>
<!-- แถบชื่อวิทยาลัย -->
<div class="topbar-bncc">
  <div class="topbar-bncc-inner">
    
    <!-- โลโก้วิทยาลัย -->
    <img src="LOGO-BNCC.png" class="topbar-bncc-logo" alt="BNCC Logo">

    <!-- ชื่อวิทยาลัย -->
    <div class="topbar-bncc-text">
      <div class="topbar-bncc-title">REPIR NOTIFICATION SYSTEM BNCC</div>
      <div class="topbar-bncc-subtitle">ระบบแจ้งซ่อมวิทยาลัยพณิชยการบางนา</div>
    </div>

  </div>
</div>
  <div class="login-box">
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg"style="color: #000000;">สมัครสมาชิกเพื่อเข้าใช้งาน</p>

        <form action="" method="post">
          <div class="input-group mb-3">
            <input type="email" name="username" class="form-control" required placeholder="อีเมล/ชื่อผู้ใช้">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" required placeholder="รหัสผ่าน">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="form-row-inline">
              <label style="color:#000000;">คำนำหน้า</label>
              <select name="title_name" class="form-control">
                  <option value="">เลือกข้อมูล</option>
                  <option value="นาย">นาย</option>
                  <option value="นาง">นาง</option>
                  <option value="นางสาว">นางสาว</option>
              </select>
          </div>

          <div class="input-group mb-3">
            <input type="text" name="name" class="form-control" required placeholder="ชื่อ">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="text" name="surname" class="form-control" required placeholder="นามสกุล">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>

          <div class="row">
            <!-- /.col -->
            <div class="col-12 mb-3">
              <button type="submit" name="action" value="register" class="btn btn-primary btn-block">สมัครสมาชิก</button>
              <a href="index.php" class="btn btn-danger btn-block">เข้าสู่ระบบ</a>
            </div>
            <!-- /.col -->
          </div>
        </form>

      </div>
    </div>
    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <!-- jQuery -->
  <script src="assets/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- Summernote -->
  <script src="assets/plugins/summernote/summernote-bs4.min.js"></script>
  <!-- Page specific script -->
  <script>
    $(function() {
      // Summernote
      $('#summernote').summernote()
    })
  </script>
  <!-- AdminLTE App -->
  <script src="assets/dist/js/adminlte.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="assets/dist/js/pages/dashboard.js"></script>
  <!-- DataTables  & assets/plugins -->
  <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
  <script src="assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
  <script src="assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
  <script src="assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
  <script src="assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
  <script src="assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
  <script src="assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
  <!-- Page specific script -->
  <script>
    $(function() {
      $("#example1").DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": false,
        "aaSorting": [
          [0, "desc"]
        ],
        //"buttons": ["copy","excel","print"] //["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
      });
    });
  </script>
</body>

</html>