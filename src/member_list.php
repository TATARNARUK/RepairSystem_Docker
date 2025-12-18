<?php
include 'header.php';
include 'navbar.php';
include 'sidebar_menu.php';
require_once 'config/connect.php';
//คิวรี้ข้อมูลสมาชิก
$queryMember = $condb->prepare("SELECT * FROM tbl_member");
$queryMember->execute();
$rsMember = $queryMember->fetchAll();
//echo '<pre>';
//$queryMember->debugDumpParams();
//exit;

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>จัดการข้อมูลพนักงาน

            <a href="member.php?act=add" class="btn btn-primary">+ข้อมูล</a>

          </h1>
        </div>
      </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped table-sm">
                <thead>
                  <tr class="table-info">
                    <th width="5%" class="text-center">No.</th>
                    <th width="38%">ชื่อ-นามสกุล</th>
                    <th width="30%">อีเมล</th>
                    <th width="10%">level</th>
                    <th width="7%" class="text-center">แก้รหัส</th>
                    <th width="5%" class="text-center">แก้ไข</th>
                    <th width="5%" class="text-center">ลบ</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $i = 1; //start number
                  foreach ($rsMember as $row) { ?>
                    <tr>
                      <td align="center"> <?php echo $i++ ?> </td>
                      <td><?= $row['title_name'] . $row['name'] . ' ' . $row['surname']; ?></td>
                      <td><?= $row['username']; ?></td>
                      <td><?= $row['m_level']; ?></td>
                      <td align="center">
                        <a href="member.php?id=<?= $row['id']; ?>&act=editPwd" class="btn btn-info btn-sm">แก้รหัส</a>
                      </td>
                      <td align="center">
                        <a href="member.php?id=<?= $row['id']; ?>&act=edit" class="btn btn-warning btn-sm">แก้ไข</a>
                      </td>
                      <td align="center">
                        <a href="member.php?id=<?= $row['id']; ?>&act=delete" class="btn btn-danger btn-sm" onclick="return confirm('ยืนยันการลบข้อมูล??');">ลบ</a>
                      </td>
                    </tr>
                  <?php } ?>

                </tbody>
                <!-- <tfoot>
                  <tr>
                    <th>Rendering engine</th>
                    <th>Browser</th>
                    <th>Platform(s)</th>
                    <th>Engine version</th>
                    <th>CSS grade</th>
                  </tr>
                  </tfoot> -->
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->