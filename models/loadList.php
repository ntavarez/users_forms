<?php
require_once "../models/connection.php";

$conn = null;
$result = null;

$conn = connection($conn);

$query = "SELECT id, nome, email FROM usuarios";
$result = mysqli_query($conn, $query);

if (mysqli_affected_rows($conn) > 0) {
    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
    echo json_encode(value: $users);
}
?>