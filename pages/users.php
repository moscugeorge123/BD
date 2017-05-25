<?php

    include('connection.php');

    $limit = 20;
    $page = 1;
    $search = '';

    if(isset($_GET['page'])) {
        $page = $_GET['page'];
    }

    if(isset($_GET['limit'])) {
        $limit = $_GET['limit'];
    }

    if(isset($_GET['search'])) {
        $search = $_GET['search'];
    }

    $prev_page = $page - 1;

    $stmt = $conn->prepare('SELECT count(*) as "COUNT" from users');
    $stmt->execute();
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['COUNT'] / $limit;

//    var_dump($count);

    $stmt = $conn->prepare("SELECT * FROM 
        (SELECT * FROM 
            (SELECT * FROM USERS where lower(FULL_NAME) like :search ORDER BY FULL_NAME ASC)
            WHERE ROWNUM <= :limit
        ORDER BY FULL_NAME DESC)
        WHERE ROWNUM <= :total");

    $stmt->execute(array(
        ':limit' => $page * $limit,
        ':total' => $limit,
        ':search' => "%".strtolower($search)."%"
    ));

//    var_dump(strtolower($search));

    $users = $stmt->fetchAll();
?>

    <div class="row">
        <form action="" action="POST">
            <div class="col-lg-12 form-group">
                <div class="col-lg-10">
                    <input type="text" name="search" placeholder="Search" class="form-control">
                </div>
                <div class="col-lg-2">
                    <input type="submit" value="Search" class="btn btn-success pull-right">
                </div>
            </div>
        </form>
    </div>

    <?php

    foreach($users as $key => $value) {
//        var_dump($value);
        ?>
        <div style="width:100%; height: 50px; background-color: #f5f5f5;margin-top:20px; border: 1px solid #999; border-radius: 10px;">
            <span class="pull-left" style="margin-top:10px;margin-left:10px; font-size: 18px"><?= $value['FULL_NAME']; ?></span>
            <div>
            <form action="pages/delete.php" method="post">
                <input type="hidden" name="id" value="<?php echo $value['ID']; ?>">
                <input type="submit" class="btn btn-danger btn-md pull-right" style="margin-left: 30px;margin-right:8px; margin-top:-8px" value="DELETE">
            </form>
            <form action="pages/updateForm.php" method="post">
                <input type="hidden" name="id" value="<?php echo $value['ID']; ?>">
                <input type="submit" class="btn btn-md btn-warning pull-right" style="margin-top:-8px; margin-right:8px" value="UPDATE">
            </form>
            </div>
        </div>
        <?php
    }
    ?>
    <?php if ($page > 1) { ?>
    <a href="?page=<?= $page - 1 ?>&limit=<?= $limit ?>&search=<?= $search ?>"><button class="btn btn-sm btn-success pull-left" style="margin-top: 20px;">&lt;</button></a>
    <?php } ?>
    <?php if ($page < $count) { ?>
    <a href="?page=<?= $page + 1 ?>&limit=<?= $limit ?>&search=<?= $search ?>"><button class="btn btn-sm btn-success pull-right" style="margin-top: 20px;">&gt;</button></a>
    <?php } ?>