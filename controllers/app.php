<?php
require_once "../models/connection.php";
require_once "../models/utils.php";

$name = null;
$email = null;
$password = null;

$conn = connection($conn);

if (isset($_GET['email'])) {
    $emailGet = $_GET['email'];

    validateEmailForms($emailGet, $conn);
} else {
    if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
    }

    checkUsersTable($conn);
    insertUser($name, $email, $password, $conn);
}
?>