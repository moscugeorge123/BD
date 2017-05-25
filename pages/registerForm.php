<html>
    <head>
        <title> Register </title>
        <link rel="stylesheet" href="../css/bootstrap.min.css">
    </head>

    <body>
        <div class="container">
            <div class="row">
                <div class="col-lg-12"><h1 style="text-align: center">Register</h1></div>
                <form action="register.php" method="post">
                    <div class="col-lg-6 col-lg-offset-3 form-group">
                        <input type="text" name="username" class="form-control" placeholder="Username" required>
                    </div>
                    <div class="col-lg-6 col-lg-offset-3 form-group">
                        <input type="password" name="password" class="form-control" placeholder="Password" ewquired>
                    </div>
                    <div class="col-lg-6 col-lg-offset-3 form-group">
                        <input type="email" name="email" class="form-control" placeholder="Email">
                    </div>
                    <div class="col-lg-6 col-lg-offset-3 form-group">
                        <input type="text" name="full-name" class="form-control" placeholder="Full name">
                    </div>
                    <div class="col-lg-6 col-lg-offset-3 form-group">
                        <input type="text" name="addres" class="form-control" placeholder="Addres">
                    </div>
                    <div class="col-lg-6 col-lg-offset-3 form-group">
                        <input type="text" name="phone" class="form-control" placeholder="Phone">
                    </div>
                    <div class="col-lg-6 col-lg-offset-3 form-group">
                        <select name="gender" class="form-control">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div class="col-lg-6 col-lg-offset-3 form-group">
                        <input type="date" name="birthday" class="form-control" placeholder="Birthday">
                    </div>
                    <div class="col-lg-6 col-lg-offset-3">
                        <input type="submit" value="Register" class="btn btn-lg btn-success">
                    </div>
                </form>
            </div>
        </div>
    </body>

</html>