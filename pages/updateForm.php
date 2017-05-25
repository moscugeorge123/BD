<?php
    $user;
    $id;
    if(isset($_POST['id'])) {
        include('connection.php');

        $id = $_POST['id'];

        $stmt = $conn->prepare("SELECT * FROM USERS WHERE ID = :id");
        $stmt->execute(array(
            ':id' => $id
        ));

        $user = $stmt->fetchAll();
        if(count($user) >= 1) {
            $user = $user[0];
        }
    }

?>
<link rel="stylesheet" href="../css/bootstrap.min.css">
<div class="container">
    <form action="update.php" method="post">
        <div class="col-lg-6 col-lg-offset-3 form-group">
            <input type="password" name="password" class="form-control" placeholder="Password" value="<?php echo $user['PASSWORD']; ?>">
        </div>
        <div class="col-lg-6 col-lg-offset-3 form-group">
            <input type="email" name="email" class="form-control" placeholder="Email" value="<?php echo $user['EMAIL']; ?>">
        </div>
        <div class="col-lg-6 col-lg-offset-3 form-group">
            <input type="text" name="full-name" class="form-control" placeholder="Full name" value="<?php echo $user['FULL_NAME']; ?>">
        </div>
        <div class="col-lg-6 col-lg-offset-3 form-group">
            <input type="text" name="addres" class="form-control" placeholder="Addres" value="<?php echo $user['ADRESS']; ?>">
        </div>
        <div class="col-lg-6 col-lg-offset-3 form-group">
            <input type="text" name="phone" class="form-control" placeholder="Phone" value="<?php echo $user['PHONE']; ?>">
        </div>
        <div class="col-lg-6 col-lg-offset-3 form-group" >
            <input type="date" name="birthday" class="form-control" placeholder="Birthday" value="<?php echo $user['BIRTH_DATE']; ?>">
        </div>
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <div class="col-lg-6 col-lg-offset-3">
            <input type="submit" value="Update" class="btn btn-lg btn-success">
        </div>
    </form>
</div>
