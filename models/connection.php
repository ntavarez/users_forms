<?php
require_once('../models/utils.php');

$connection = null;

function connection($connection)
{
    $server = 'localhost';
    $username = 'root';
    $password = '';
    $db = 'chromasoft';

    if ($connection === null) {
        $connection = mysqli_connect($server, $username, $password);

        if (!checkDatabase($connection, $db)) {
            $query = "CREATE DATABASE $db";
            mysqli_query($connection, $query);

            if (mysqli_affected_rows($connection) > 0) {
                return $connection = mysqli_connect($server, $username, $password, $db);
            } else {
                return;
            }
        } else {
            return $connection = mysqli_connect($server, $username, $password, $db);
        }

        if (mysqli_connect_error()) {
            die("A conexão falhou: " + mysqli_connect_error());
        }
    }
}
function close($connection)
{
    if ($connection !== null) {
        $connection = mysqli_close($connection);
    }

    return $connection;
}
?>