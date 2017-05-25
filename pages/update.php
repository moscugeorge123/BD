<?php


    if(isset($_POST)) {
        include('connection.php');

        $password = $_POST['password'];
        $email = $_POST['email'];
        $full_name = $_POST['full-name'];
        $adress = $_POST['addres'];
        $phone = $_POST['phone'];
        $date = $_POST['birthday'];
        $id = $_POST['id'];

        $stmt = $conn->prepare("begin crud_user.updateUser( '$password' , '$email' , '$full_name' , '$adress' , '$phone' , to_date( '$date' , 'YYYY-MM-DD') , $id ); end;");
        $hel = $stmt->execute();

//        echo $hel;

        header("Location: ../index.php");
    }