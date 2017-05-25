<?php
/**
 * User: Moscu George
 * Date: 5/13/2017
 * Time: 10:29 AM
 */
include_once("../config/connection.php");
session_start();
class user {
    public $conn;
    function __construct() {
        $this->conn = new PDO('oci:dbname=localhost:1521/xe',DB_USER,DB_PASS);
    }

    public function login() {
        $stmt = $this->conn->prepare("SELECT * from users where username = :username and password = :password");
        $username = $_POST['username'];
        $password = $_POST['password'];

        $stmt->execute(array(
            ':username' => $username,
            ':password' => $password
        ));

        $user = $stmt->fetchAll();
        if(count($user) > 1) {
            echo json_encode(array('error' => 1, 'message' => 'There are multiple users with that username'));
            return false;
        }

        if(count($user) < 1) {
            echo json_encode(array('error' => 1, 'message' => 'There is no user with that username'));
            return false;
        }

        if(count($user) == 1) {
            echo json_encode(array('error' => 0, 'message' => 'You logged in successfully'));
            $_SESSION['user_id'] = $user[0]['ID'];
            return true;
        }
    }

    public function register()
    {
        if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email'])) {
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
                foreach ($this->conn->query($sql) as $row) {
                    if ($row['USERNAME'] == $username) {
                        echo json_encode(array('error' => 1, 'message' => 'There is another user with that username'));
                        return false;
                    }
                }

                $this->conn->query("begin crud_user.insertUser('$username','$password','$email','$full_name','$addres', '$phone','$gender',to_date('$birthday', 'YYYY-MM-DD'),'0.0.0.0'); end;");

                foreach ($this->conn->query($sql) as $row) {
                    if ($row['USERNAME'] == $username) {
                        echo json_encode(array('error' => 0, 'message' => 'You registered successfully'));
                        return true;
                    } else {
                        echo json_encode(array('error' => 1, 'message' => 'There was an error. Please try later'));
                        return false;
                    }
                }

            } catch (PDOEXception $e) {
                echo $e->getMessage(); // display error
                exit();
            }
        }
    }

    public function delete() {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];

            $stmt = $this->conn->prepare("begin crud_user.deleteUser(to_number($id)); end;");
            $check = $stmt->execute();

            if($check == true) {
                echo json_encode(array('error' => 0, 'message' => 'User deleted'));
                return true;
            } else {
                echo json_encode(array('error' => 1, 'message' => 'There was am error in deleting user'));
                return false;
            }
        }
    }

    public function update() {
        if(isset($_POST)) {
            $password = $_POST['password'];
            $email = $_POST['email'];
            $full_name = $_POST['full-name'];
            $adress = $_POST['addres'];
            $phone = $_POST['phone'];
            $date = $_POST['birthday'];
            $id = $_POST['id'];

            $stmt = $this->conn->prepare("begin crud_user.updateUser( '$password' , '$email' , '$full_name' , '$adress' , '$phone' , to_date( '$date' , 'YYYY-MM-DD') , $id ); end;");
            if($stmt->execute()) {
                echo json_encode(array('error' => 0, 'message' => 'User updated'));
                return true;
            } else {
                echo json_encode(array('error' => 1, 'message' => 'There was an error in updating user'));
                return false;
            }
        }
    }
}