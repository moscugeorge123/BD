<?php

/**
 * Created by PhpStorm.
 * User: Moscu George
 * Date: 5/22/2017
 * Time: 8:43 PM
 */
include "../config/connection.php";

session_start();
class Cart {
    public $conn;

    function __construct() {
        $this->conn = new PDO('oci:dbname=localhost:1521/xe',DB_USER,DB_PASS);
    }

    function empty() {
        $user_id = $_SESSION['user_id'];

        $stmt = $this->conn->prepare("DELETE FROM CART WHERE USER_ID = :id");
        $stmt->execute(array(':id' => $user_id));

        json_encode('{"success":1}');
    }

    function delete_product(){
        $product_id = $_POST['product_id'];
        $user_id = $_SESSION['user_id'];

        $stmt = $this->conn->prepare("DELETE FROM CART WHERE USER_ID = :id AND PRODUCT_ID = :p_id");
        $stmt->execute(array(
           ':id' => $user_id,
            ':p_id' => $product_id
        ));

        json_encode('{"success": 1}');
    }

    function change_product_quantity() {
        $product_id = $_POST['product_id'];
        $user_id = $_SESSION['user_id'];
        $quantity = $_POST['quantity'];

        $stmt = $this->conn->prepare("UPDATE CART SET QUANTITY = :quan WHERE USER_ID = :id AND PRODUCT_ID = :p_id");
        $stmt->execute(array(
            ':id' => $user_id,
            ':p_id' => $product_id,
            ':quan' => $quantity
        ));

        json_encode('{"success": 1}');
    }

    function get_products() {
        $user_id = $_SESSION['user_id'];

        $stmt = $this->conn->prepare("SELECT p.NAME, c.QUANTITY, p.ID FROM PRODUCTS P 
                                               JOIN CART c on P.ID = c.PRODUCT_ID
                                               WHERE c.USER_ID = :id");

        $stmt->execute(array(':id' => $user_id));

        echo json_encode($stmt->fetchAll());
    }
}