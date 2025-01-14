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
    $query = "SHOW TABLES LIKE 'usuarios'";
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
        $stmt = mysqli_prepare($conn, 'SELECT * FROM usuarios WHERE email = ?');
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo json_encode(value: ['success' => true, 'message' => 'E-mail ja esta em uso!']);
        } else {
            echo json_encode(value: ['success' => false]);
        }
    } else {
        $stmt = mysqli_prepare($conn, 'CREATE TABLE usuarios(id int NOT NULL AUTO_INCREMENT, nome varchar(255), email varchar(255) NOT NULL, senha varchar(255) NOT NULL, PRIMARY KEY (id))');
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
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
    $stmt = mysqli_prepare($conn, 'SELECT * FROM usuarios WHERE email = ?');
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
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
        $stmt = mysqli_prepare($conn, 'INSERT into usuarios (nome, email, senha) values (?, ?, ?)');
        mysqli_stmt_bind_param($stmt, 'sss', $name, $email, $password);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo json_encode(['success' => true, 'message' => 'Usuario cadastrado com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Ops, parece que o usuario nao pode ser cadastrado!']);
        }
    }
}

function editUser($conn, $updateInfo, $edtOpt, $id)
{
    if ($edtOpt === '1') {
        $stmt = mysqli_prepare($conn, 'UPDATE usuarios SET nome = ? WHERE id = ?');
        mysqli_stmt_bind_param($stmt, 'si', $updateInfo, $id);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo json_encode(value: ['success' => true, 'message' => 'Nome atualizado com sucesso!']);
        } else {
            echo json_encode(value: ['success' => false, 'message' => 'Nao foi possivel atualizar!']);
        }
    } elseif ($edtOpt === '2') {
        $stmt = mysqli_prepare($conn, 'SELECT * FROM usuarios WHERE email = ? AND NOT id = ?');
        mysqli_stmt_bind_param($stmt, 'si', $updateInfo, $id);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo json_encode(value: ['success' => false, 'message' => 'E-mail ja esta sendo utilizado por outro usuario!']);
        } else {
            $stmt = mysqli_prepare($conn, 'UPDATE usuarios SET email = ? WHERE id = ?');
            mysqli_stmt_bind_param($stmt, 'si', $updateInfo, $id);
            mysqli_stmt_execute($stmt);

            if (mysqli_stmt_affected_rows($stmt) > 0) {
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
    $stmt = mysqli_prepare($conn, 'DELETE FROM usuarios WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo json_encode(['success' => true, 'message' => 'Usuario excluido com sucesso!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao excluir usuario!']);
    }
}
?>