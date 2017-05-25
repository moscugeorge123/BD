<?php
/**
 * Created by PhpStorm.
 * User: Moscu George
 * Date: 5/20/2017
 * Time: 12:04 PM
 */

include_once "../classes/user.php";

$products = new User();

//echo json_encode($_POST);

call_user_func_array(array($products, $_POST['action']), array());
