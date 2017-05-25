<?php include "../header.php"; ?>

<div class="container">
    <div class="row">
        <div class="wrapper">
            <div class="col-lg-12"><h1 style="text-align: center">Register</h1></div>
                <div class="col-lg-6 col-lg-offset-3 form-group">
                    <input type="text" name="username" class="form-control" placeholder="Username" required id="username">
                </div>
                <div class="col-lg-6 col-lg-offset-3 form-group">
                    <input type="password" name="password" class="form-control" placeholder="Password" required id="password">
                </div>
                <div class="col-lg-6 col-lg-offset-3 form-group">
                    <input type="email" name="email" class="form-control" placeholder="Email" id="email">
                </div>
                <div class="col-lg-6 col-lg-offset-3 form-group">
                    <input type="text" name="full-name" class="form-control" placeholder="Full name" id="full-name">
                </div>
                <div class="col-lg-6 col-lg-offset-3 form-group">
                    <input type="text" name="addres" class="form-control" placeholder="Addres" id="addres">
                </div>
                <div class="col-lg-6 col-lg-offset-3 form-group">
                    <input type="text" name="phone" class="form-control" placeholder="Phone" id="phone">
                </div>
                <div class="col-lg-6 col-lg-offset-3 form-group">
                    <select name="gender" class="form-control" id="gender">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
                <div class="col-lg-6 col-lg-offset-3 form-group">
                    <input type="date" name="birthday" class="form-control" placeholder="Birthday" id="birthday">
                </div>
                <div class="col-lg-6 col-lg-offset-3">
                    <input type="submit" value="Register" class="btn btn-lg btn-success" id="register">
                </div>
        </div>
    </div>
</div>
