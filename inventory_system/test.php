<!doctype html>
<html lang="en">
<head>
  <title>Inventory System Registration</title>
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
            <div class="img" style="background-image: url(../images/bg-1.jpg);">
            </div>
            <div class="login-wrap p-4 p-md-5">
              <div class="d-flex">
                <div class="w-100">
                  <h3 class="mb-4">Register</h3>  </div>
              </div>
              <form action="../php/register_process.php" method="post" class="signin-form">  <div class="form-group mb-3">
                  <label class="label" for="username">Username</label>
                  <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                </div>
                <div class="form-group mb-3">
                  <label class="label" for="email">Email</label>
                  <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group mb-3">
                  <label class="label" for="password">Password</label>
                  <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                </div>
                <div class="form-group mb-3">
                  <label class="label" for="confirm_password">Confirm Password</label>
                  <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                </div>
                <div class="form-group">
                  <button type="submit" class="form-control btn btn-primary rounded submit px-3">Register</button>
                </div>
              </form>
              <p class="text-center">Already a member? <a href="index.php">Login</a></p>   </div>
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
