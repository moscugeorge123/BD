<?php
include('connection.php');


$stmt = $conn->prepare("select * from TABLE (member_functions.users_product_choice())");

$stmt->execute();

$result = $stmt->fetch(PDO::FETCH_ASSOC);
//var_dump($result);

?>


<div class="well text-center"><?php echo $result['TYPE']; ?></div>
