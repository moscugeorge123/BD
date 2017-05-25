<?php

    if (isset($_POST['id'])) {
        session_start();
        include('connection.php');

        $id = $_POST['id'];

        $stmt = $conn->prepare("begin crud_user.deleteUser(to_number($id)); end;");
        $check = $stmt->execute();

        var_dump($check);

        if($check == true) {
            $_SESSION['deleted'] = true;
            header("Location: ../index.php");
        } else {
            $_SESSION['deleted'] = false;
            header("Location: ../index.php");
        }
    }