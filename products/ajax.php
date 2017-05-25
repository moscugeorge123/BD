<?php
/**
 * Created by PhpStorm.
 * User: Moscu George
 * Date: 5/20/2017
 * Time: 12:04 PM
 */

include_once "../classes/products.php";

$products = new Products();

//echo json_encode($_POST);

call_user_func_array(array($products, $_POST['action']), array());
