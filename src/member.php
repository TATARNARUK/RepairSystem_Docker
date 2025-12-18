<?php
 $act = (isset($_GET['act']) ? $_GET['act'] : '');

 //สร้างเงื่อนๆขในการเรียกใช้ไฟล์
if($act =='add'){
    include 'form_add.php';
}else if($act =='delete'){
    include 'delete.php';
}else if($act =='edit'){
    include 'form_edit.php';
}else if($act =='editPwd'){
    include 'form_edit_password.php';
}else if($act =='details'){
    include 'form_details.php';
}else{
    include 'member_list.php';
}

?>