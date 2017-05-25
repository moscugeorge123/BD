<?php

session_start();

if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email'])) {
    include('connection.php');

    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $full_name = $_POST['full-name'];
    $addres = $_POST['addres'];
    $gender = $_POST['gender'];
    $birthday = $_POST['birthday'];
    $phone = $_POST['phone'];


    try {

        $sql = "select * from TABLE(crud_user.findUser('$username', '$password'))";
        foreach ($conn->query($sql) as $row) {
            if ($row['USERNAME'] == $username) {
                header("Location: ../index.php?register=false");
            }
        }

        $conn->query("begin
            crud_user.insertUser('$username','$password','$email','$full_name','$addres',
            '$phone','$gender',to_date('$birthday', 'YYYY-MM-DD'),'0.0.0.0');
        end;");

        foreach ($conn->query($sql) as $row) {
            if ($row['USERNAME'] == $username) {
                header("Location: ../index.php?register=true");
            } else {
                header("Location: ../index.php?register=false");
            }
        }

    } catch( PDOEXception $e ) {
        echo $e->getMessage(); // display error
        exit();
    }

}