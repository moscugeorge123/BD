<?php

/**
 * User: Moscu George
 * Date: 5/13/2017
 * Time: 6:40 PM
 */

include_once "../config/connection.php";
session_start();
class Products {
    public $conn;

    function __construct() {
        $this->conn = new PDO('oci:dbname=localhost:1521/xe',DB_USER,DB_PASS);
    }

    public function get_products() {
        $limit = 20;
        $page = 1;
        $search = '';

        if(isset($_POST['page'])) {
            $page = $_POST['page'];
        }

        if(isset($_POST['limit'])) {
            $limit = $_POST['limit'];
        }

        if(isset($_POST['search'])) {
            $search = $_POST['search'];
        }

        $prev_page = $page - 1;

        $stmt = $this->conn->prepare('SELECT count(*) as "COUNT" from PRODUCTS');
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['COUNT'] / $limit;

        $stmt = $this->conn->prepare("SELECT * FROM 
        (SELECT * FROM 
            (SELECT * FROM PRODUCTS where lower(NAME) like LOWER(:search) ORDER BY NAME ASC)
            WHERE ROWNUM <= :limit
        ORDER BY NAME DESC)
        WHERE ROWNUM <= :total");

        $stmt->execute(array(
            ':limit' => $page * $limit,
            ':total' => $limit,
            ':search' => "%".strtolower($search)."%"
        ));

        $products = $stmt->fetchAll();

        echo json_encode(array(
            'products' => json_encode($products),
            'prev_page' => $prev_page,
            'next_page' => $page + 1,
            'pages' => $count
        ));

    }

    public function get_product() {
        if(isset($_POST['product_id'])) {
            $product_id = $_POST['product_id'];
            $stmt = $this->conn->prepare("SELECT p.ID, p.name, p.description, p.stock, p.price, p.IMAGESOURCE, r.VALUE FROM PRODUCTS p
                                                      JOIN PRODUCTRATINGS r ON p.ID = r.PRODUCT_ID
                                                    WHERE p.ID = :product_id");
            $stmt->execute(array(
                ':product_id' => $product_id
            ));

            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            echo json_encode($product);
        }
    }

    function add_to_cart() {
        if(isset($_POST['product_id'])) {
            $product_id = $_POST['product_id'];
            $user_id = $_SESSION['user_id'];
            $stmt = $this->conn->prepare("INSERT INTO CART(USER_ID, PRODUCT_ID, QUANTITY) values(:user_id, :product_id, 1); COMMIT ");
            $stmt->execute(array(
                ':product_id' => $product_id,
                ':user_id' => $user_id
            ));

            echo json_encode(array(
                'success' => 1,
                'user_id' => $user_id,
                'product_id' => $product_id
            ));
        }
    }

    function delete() {
        if(isset($_POST['product_id'])) {
            $product_id = $_POST['product_id'];
            $stmt = $this->conn->prepare("DELETE FROM PRODUCTS WHERE ID = :product_id");
            $stmt->execute(array(
                ':product_id' => $product_id
            ));

            echo json_encode(array(
                'success' => 1,
                'product_id' => $product_id
            ));
        }
    }
}