<?php

include('connection.php');

$prod_name = 'War';

$stmt = $conn->prepare("SELECT * from products where name like '%$prod_name%'");
$stmt->execute();

var_dump($stmt->fetchAll());