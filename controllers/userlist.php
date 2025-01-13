<?php
require_once "../models/connection.php";
require_once "../models/utils.php";

$id = null;
$updateInfo = null;
$edtOption = null;
$conn = null;

$conn = connection($conn);

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    deleteUser($id, $conn);
} else {
    if (isset($_POST['updateInfo']) && isset($_POST['edtOption']) && isset($_POST['userId'])) {
        $updateInfo = $_POST['updateInfo'];
        $edtOption = $_POST['edtOption'];
        $id = $_POST['userId'];
    
        editUser($conn, $updateInfo,$edtOption, $id);
    }
}

close($conn);
?>