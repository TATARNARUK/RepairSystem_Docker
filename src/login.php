<?php
session_start(); //เริ่มต้นการใช้ เซสชั่น

require_once 'config/connect.php';  //เรียกใช้การเชื่อมต่อฐานข้อมูล

//สร้างเงื่อนไขตรวจสอบ input ที่ส่งมาจากฟอร์ม
if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['action']) && $_POST['action'] == 'iogin') {

  //check username  & password
  $stmtLogin = $condb->prepare("SELECT id, m_level FROM tbl_member WHERE username = :username AND password = :password");
  //bindparam STR , INT
  $stmtLogin->bindValue(':username', $_POST['username'], PDO::PARAM_STR);
  $stmtLogin->bindValue(':password', sha1($_POST['password']), PDO::PARAM_STR);
  $stmtLogin->execute();

  //ตรวจสอบมีข้อมูลไหม
  if ($stmtLogin->rowCount() == 1) {
    //fetch เพื่อเรียกคอลัมภ์ที่ต้องการไปสร้างตัวแปร session
    $row = $stmtLogin->fetch(PDO::FETCH_ASSOC);
    //สร้างตัวแปร session
    $_SESSION['id'] = $row['id'];
    $_SESSION['m_level'] = $row['m_level'];

    header('location: dashbord.php');
  } else { //ถ้า username or password ไม่ถูกต้อง

    echo '<script>
                   setTimeout(function() {
                    swal({
                        title: "เกิดข้อผิดพลาด",
                         text: "Username หรือ Password ไม่ถูกต้อง ลองใหม่อีกครั้ง",
                        type: "warning"
                    }, function() {
                        window.location = "login.php"; //หน้าที่ต้องการให้กระโดดไป
                    });
                  }, 1000);
              </script>';
  } //else
} //isset
?>
<style>
  .topbar-bncc {
    width: 100%;
    background-color: #ffffff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    position: fixed;
    /* ให้อยู่ติดด้านบน */
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
    padding: 20px 25px;
    /* 👈 เพิ่มจาก 8px → 20px */
    display: flex;
    align-items: center;
    gap: 18px;
  }

  /* ขยายโลโก้ให้พอดีกับแถบใหม่ */
  .topbar-bncc-logo {
    width: 75px;
    /* เดิม 60px */
    height: 75px;
    object-fit: contain;
  }


  /* ดันกล่องล็อกอินลงล่างเพราะมี topbar */
  .login-box {
    margin-top: 170px !important;
    /* เดิม 130px */
  }

  .topbar-bncc-subtitle {
    font-size: 14px;
    color: #777777;
  }

  .login-box {
    margin-top: 120px;
    /* ดันกล่องล็อกอินลงมาหน่อย เพราะมี topbar */
  }

  body {
    background: url('image/your_background.jpg') no-repeat center center fixed;
    background-size: cover;
    font-family: "Prompt", sans-serif;
  }

  .login-container {
    background: rgba(255, 255, 255, 0.15);
    /* โปร่งใส */
    backdrop-filter: blur(10px);
    /* เบลอพื้นหลัง */
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
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
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


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="assets/dist/css/adminlte.min.css?v=3.2.0">
  <!-- sweet alert -->
  <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">

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
        <p class="login-box-msg" style="color: #000000;">เข้าสู่ระบบเพื่อเข้าใช้งาน</p>


        <form action="" method="post">
          <div class="input-group mb-3">
            <input type="email" name="username" class="form-control" placeholder="อีเมล/ชื่อผู้ใช้" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="รหัสผ่าน" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <!-- /.col -->
            <div class="col-12 mb-2">
              <button type="submit" name="action" value="iogin" class="btn btn-primary btn-block">เข้าสู่ระบบ</button>
              <a href="register.php" class="btn btn-danger btn-block">สมัครสมาชิก</a>
            </div>
            <!-- /.col -->
          </div>
        </form>
      </div>
    </div>
</body>

</html>