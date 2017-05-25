<html>

<head>
  <title>Applicatie BD</title>
  <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>

<body>
  <div class="container">
    <div class="row">
      <div class="col-lg-12" style="text-align:center">
        <h1>Login</h1></div>
      <form action="login.php" method="post">
        <div class="col-lg-6 col-lg-offset-3 form-group">
          <input type="text" name="username" placeholder="Username" class="form-control" required>
        </div>
        <div class="clear"></div>
        <div class="col-lg-6 col-lg-offset-3 form-group">
          <input type="password" name="password" placeholder="Password" class="form-control" required>
        </div>
        <div class="col-lg-6 col-lg-offset-3 form-group">
            <input type="submit" value="Login" class="btn btn-lg btn-success">
        </div>
    </div>
    </form>
  </div>
</body>

</html>