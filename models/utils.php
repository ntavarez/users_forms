<?php

$conn = null;
$id = null;
$name = null;
$email = null;
$password = null;
$updateInfo = null;
$edtOption = null;

function checkDatabase($conn, $db)
{
    $query = "SHOW DATABASES LIKE '$db'";
    mysqli_query($conn, $query);

    if (mysqli_affected_rows($conn) > 0) {
        return true;
    } else {
        return false;
    }
}

function checkUsersTable($conn)
{
    $query = "SHOW TABLES like 'usuarios'";
    mysqli_query($conn, $query);

    if (mysqli_affected_rows($conn) > 0) {
        return true;
    } else {
        return false;
    }
}

function validateEmailForms($email, $conn)
{
    if (checkUsersTable($conn)) {
        $query = "SELECT * FROM usuarios WHERE email = '$email'";
        mysqli_query($conn, $query);

        if (mysqli_affected_rows($conn) > 0) {
            echo json_encode(value: ['success' => true, 'message' => 'E-mail ja esta em uso!']);
        } else {
            echo json_encode(value: ['success' => false]);
        }
    } else {
        $query = "CREATE TABLE usuarios(id int NOT NULL AUTO_INCREMENT, nome varchar(255), email varchar(255) NOT NULL, senha varchar(255) NOT NULL, PRIMARY KEY (id))";
        mysqli_query($conn, $query);

        if (mysqli_affected_rows($conn) > 0) {
            return true;
        } else {
            return false;
        }
    }
}

function validatePasswordForms($password)
{
    if (strlen($password) < 6 || $password == "") {
        return true;
    } else {
        return false;
    }
}

function checkUsersEmail($conn, $email)
{
    $query = "SELECT * FROM usuarios WHERE email = '$email'";
    mysqli_query($conn, $query);

    if (mysqli_affected_rows($conn) > 0) {
        return true;
    } else {
        return false;
    }
}

function insertUser($name, $email, $password, $conn)
{
    if (checkUsersEmail($conn, $email) || validatePasswordForms($password)) {
        echo json_encode(['success' => false, 'message' => 'O e-mail ja esta em uso ou a senha tem menos de 6 caracteres!']);
    } else {
        $query = "INSERT into usuarios (nome, email, senha) values ('$name', '$email', '$password')";
        mysqli_query($conn, $query);

        if (mysqli_affected_rows($conn) > 0) {
            echo json_encode(['success' => true, 'message' => 'Usuario cadastrado com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Ops, parece que o usuario nao pode ser cadastrado!']);
        }
    }
}

function editUser($conn, $updateInfo, $edtOpt, $id)
{
    if ($edtOpt === '1') {
        $query = "UPDATE usuarios SET nome = '$updateInfo' WHERE id = '$id'";
        mysqli_query($conn, $query);

        if (mysqli_affected_rows($conn) > 0) {
            echo json_encode(value: ['success' => true, 'message' => 'Nome atualizado com sucesso!']);
        } else {
            echo json_encode(value: ['success' => false, 'message' => 'Nao foi possivel atualizar!']);
        }
    } elseif ($edtOpt === '2') {
        $query = "SELECT * FROM usuarios WHERE email = '$updateInfo' AND NOT id = '$id'";
        mysqli_query($conn, $query);

        if (mysqli_affected_rows($conn) > 0) {
            echo json_encode(value: ['success' => false, 'message' => 'E-mail ja esta sendo utilizado por outro usuario!']);
        } else {
            $query = "UPDATE usuarios SET email = '$updateInfo' WHERE id = '$id'";
            mysqli_query($conn, $query);

            if (mysqli_affected_rows($conn) > 0) {
                echo json_encode(value: ['success' => true, 'message' => 'E-mail atualizado com sucesso!']);
            } else {
                echo json_encode(value: ['success' => false, 'message' => 'Nao foi possível atualizar!']);
            }
        }
    } else {
        echo json_encode(value: ['success' => false, 'message' => 'Erro ao procesasar a requisiçao!']);
    }
}

function deleteUser($id, $conn)
{
    $query = "DELETE FROM usuarios WHERE id = '$id'";
    mysqli_query($conn, $query);

    if (mysqli_affected_rows($conn) > 0) {
        echo json_encode(['success' => true, 'message' => 'Usuario excluido com sucesso!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao excluir usuario!']);
    }
}
?>