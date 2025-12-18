<?php
$where = [];
$params = [];

// ถ้าดูเฉพาะของฉัน
if ($scope === 'mine') {
  $where[] = 'member_id = :uid';
  $params[':uid'] = (int)$MemberData['id'];
}

// ถ้ามีเลือกสถานะ
if ($status !== 'all') {
  $where[] = 'repair_status = :st';
  $params[':st'] = $status;
}

$sql = "SELECT * FROM tdl_form_repair";
if ($where) {
  $sql .= " WHERE " . implode(' AND ', $where);
}
$sql .= " ORDER BY id DESC";

$stmt = $condb->prepare($sql);
foreach ($params as $k => $v) {
  $stmt->bindValue($k, $v, $k === ':uid' ? PDO::PARAM_INT : PDO::PARAM_STR);
}
$stmt->execute();
$rsMember = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>ตารางแจ้งซ่อม

            <a href="form_repair.php" class="btn btn-primary">+แจ้งปัญหา</a>

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
              <form method="get" class="form-inline mb-3">
                <!-- เลือกขอบเขต -->
                <div class="form-group mr-2">
                  <label class="mr-2">แสดง</label>
                  <select name="scope" class="form-control form-control-sm" <?= $isAdmin ? '' : 'disabled' ?>>
                    <option value="mine" <?= $scope === 'mine' ? 'selected' : ''; ?>>เฉพาะของฉัน</option>
                    <option value="all" <?= $scope === 'all' ? 'selected' : ''; ?>>ทั้งหมด</option>
                  </select>
                  <?php if (!$isAdmin): ?>
                    <input type="hidden" name="scope" value="mine">
                  <?php endif; ?>

                </div>

                <!-- เลือกสถานะ -->
                <div class="form-group mr-2">
                  <label class="mr-2">สถานะ</label>
                  <select name="status" class="form-control form-control-sm">
                    <option value="all" <?= $status === 'all' ? 'selected' : ''; ?>>ทุกสถานะ</option>
                    <?php foreach ($STATUS_LIST as $st): ?>
                      <option value="<?= htmlspecialchars($st) ?>" <?= $status === $st ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($st) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <button type="submit" class="btn btn-primary btn-sm mr-2">กรอง</button>
                <a class="btn btn-light btn-sm" href="datatable.php">ล้างค่า</a>
              </form>
              <table id="example1" class="table table-bordered table-striped table-sm">
                <thead>
                  <tr class="bg-primary">
                    <th width="5%" class="text-center">No.</th>
                    <th width="20%">สถานการซ่อม</th>
                    <th width="20%">ชื่ออุปกรณ์แจ้งซ่อม</th>
                    <th width="17%">สถานที่</th>
                    <th width="10%">เบอร์โทรศัพท์</th>
                    <th width="13%" class="text-center">เวลาการแจ้งซ่อม</th>
                    <th width="15%" class="text-center">รายละเอียด</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $i = 1; //start number
                  foreach ($rsMember as $stmtformrepair) { ?>
                    <tr>
                      <td class="text-center"> <?php echo $i++ ?> </td>
                      <td><?php echo $stmtformrepair['repair_status'] ?> </td>
                      <td><?= $stmtformrepair['description']; ?></td>
                      <td><?= $stmtformrepair['location']; ?></td>
                      <td><?= $stmtformrepair['phone_number']; ?></td>
                      <td><?= $stmtformrepair['created_at']; ?></td>
                      <td class="text-center">
                        <a href="form_details.php?act=details&id=<?= $stmtformrepair['id'] ?>" class="btn bg-warning btn-sm">รายละเอียด</a>
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