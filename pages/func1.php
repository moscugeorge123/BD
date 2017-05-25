<?php
include('connection.php');


$stmt = $conn->prepare("select * from TABLE (member_functions.best_support_user())");

$stmt->execute();

$result = $stmt->fetchAll()[0];

?>


<div class="well text-center"><?php echo $result['FULL_NAME']; ?></div>
