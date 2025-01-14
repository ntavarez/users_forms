<?php
require_once "../models/connection.php";

$conn = null;
$result = null;

$conn = connection($conn);

$stmt = mysqli_prepare($conn, 'SELECT id, nome, email FROM usuarios');
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0) {
    $users = [];
    
    while ($row = mysqli_fetch_array($result)) {
        $users[] = $row;
    }
    echo json_encode($users);
}

close($conn);
?>