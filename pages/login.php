<?php
if(isset($_POST['username']) && isset($_POST['password'])){
    session_start();
    include('connection.php');

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * from users where username = :username and password = :password");



    $stmt->execute(array(
        ':username' => $username,
        ':password' => $password
    ));

    while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (isset($result['USERNAME'])){
            echo $result['FULL_NAME'];
            $_SESSION['NAME'] = $result['FULL_NAME'];
            break;
        }
    }
    

     header('Location: ../index.php');
}

?>