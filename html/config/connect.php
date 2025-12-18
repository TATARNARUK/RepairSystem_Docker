<?php
//ini_set('display_errors', 0);

$servername = "db";
$username = "root";
$password = "root"; //ถ้าไม่ได้ตั้งรหัสผ่านให้ลบ yourpassword ออก

try {
  $condb = new PDO("mysql:host=$servername;dbname=repairbncc_db;charset=utf8", $username, $password);
  // set the PDO error mode to exception
  $condb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //echo "Connected successfully";
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
?>