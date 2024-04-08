<!doctype html>
<html lang="en">
<head>
  <title>Reset Password</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

  <link rel="stylesheet" href="../css/style.css">

</head>
<body>
  <section class="ftco-section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-12 col-lg-10">
          <div class="wrap d-md-flex">
            <div class="img" style="background-image: url(../images/bg-1.png);">
            </div>
            <div class="login-wrap p-4 p-md-5">
              <div class="d-flex">
                <div class="w-100">
                  <h3 class="mb-4">Reset your password</h3>
                </div>

                <!-- <div class="w-100">
                  <p class="social-media d-flex justify-content-end">
                    <a href="#" class="social-icon d-flex align-items-center justify-content-center"><span class="fa fa-facebook"></span></a>
                    <a href="#" class="social-icon d-flex align-items-center justify-content-center"><span class="fa fa-twitter"></span></a>
                  </p>
                </div> -->
              </div>
              <p>An e-mail will be sent to you with instructions on how to reset your password.</p>
              <form action="php/reset_password_process.php" method="post" class="signin-form">  
                <div class="form-group mb-3">
                  <label class="label" for="email">E-mail</label>
                  <input type="text" class="form-control" id="email" name="email" placeholder="Enter your e-mail address" required>
                </div>
                <!-- <div class="form-group mb-3">
                  <label class="label" for="password">Password</label>
                  <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                </div> -->
                <div class="form-group">
                  <button type="submit" name="submit" class="form-control btn btn-primary rounded submit px-3">Send email</button>
                </div>
                <!-- <div class="form-group d-md-flex">
                  <div class="w-50 text-left">
                    <label class="checkbox-wrap checkbox-primary mb-0">Remember Me
                      <input type="checkbox" checked>
                      <span class="checkmark"></span>
                    </label>
                  </div>
                  <div class="w-50 text-md-right">
                    <a href="php/forgot_password.php">Forgot Password</a>
                  </div>
                </div> -->
              </form>
              <p class="text-center">Remember Password? <a href="../index.php">Back to Login</a></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script src="../js/jquery.min.js"></script>
  <script src="../js/popper.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/main.js"></script>

</body>
</html>
