<html>
<head>
    <link rel="stylesheet" href="http://www.localhost:4200/css/bootstrap.min.css">
    <link rel="stylesheet" href="http://www.localhost:4200/css/default.css">
    <script src="http://localhost:4200/js/jquery.js"></script>
    <script src="http://localhost:4200/js/ajax.js"></script>
</head>
<body>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="http://www.localhost:4200" onclick="getProds()">Home</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="http://www.localhost:4200/view/login.php">Login</a></li>
                <li><a href="http://www.localhost:4200/view/register.php">Register</a></li>
                <li><a href="#">Cart</a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
