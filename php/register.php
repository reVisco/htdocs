<!doctype html>
<?php
session_start();
?>
<html lang="en">
<head>
  <title>Registration Page</title>
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
                  <h3 class="mb-4">Register</h3>
                </div>
              </div>
              <?php if (isset($_GET['error'])): ?>
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_GET['error']); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <?php endif; ?>
              <?php if (isset($_GET['message'])): ?>
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_GET['message']); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <?php endif; ?>
              <form action="process/register_process.php" method="post" class="signin-form">  <div class="form-group mb-3">
                  <label class="label" for="first_name">First Name</label>
                  <input type="text" class="form-control <?php echo isset($_GET['error']) && strpos($_GET['error'], 'First name') !== false ? 'is-invalid' : ''; ?>" id="first_name" name="first_name" placeholder="First Name" value="<?php echo isset($_SESSION['form_data']['first_name']) ? htmlspecialchars($_SESSION['form_data']['first_name']) : ''; ?>" required>
                </div>
                <div class="form-group mb-3">
                  <label class="label" for="last_name">Last Name</label>
                  <input type="text" class="form-control <?php echo isset($_GET['error']) && strpos($_GET['error'], 'Last name') !== false ? 'is-invalid' : ''; ?>" id="last_name" name="last_name" placeholder="Last Name" value="<?php echo isset($_SESSION['form_data']['last_name']) ? htmlspecialchars($_SESSION['form_data']['last_name']) : ''; ?>" required>
                </div>
                <div class="form-group mb-3">
                  <label class="label" for="username">Username</label>
                  <input type="text" class="form-control <?php echo isset($_GET['error']) && strpos($_GET['error'], 'Username') !== false ? 'is-invalid' : ''; ?>" id="username" name="username" placeholder="Username" value="<?php echo isset($_SESSION['form_data']['username']) ? htmlspecialchars($_SESSION['form_data']['username']) : ''; ?>" required>
                </div>
                <div class="form-group mb-3">
                  <label class="label" for="email">Email</label>
                  <input type="email" class="form-control <?php echo isset($_GET['error']) && strpos($_GET['error'], 'Email') !== false ? 'is-invalid' : ''; ?>" id="email" name="email" placeholder="Email" value="<?php echo isset($_SESSION['form_data']['email']) ? htmlspecialchars($_SESSION['form_data']['email']) : ''; ?>" required>
                </div>
                <div class="form-group mb-3">
                  <label class="label" for="password">Password</label>
                  <input type="password" class="form-control <?php echo isset($_GET['error']) && strpos($_GET['error'], 'Password') !== false ? 'is-invalid' : ''; ?>" id="password" name="password" placeholder="Password" required>
                </div>
                <div class="form-group mb-3">
                  <label class="label" for="confirm_password">Confirm Password</label>
                  <input type="password" class="form-control <?php echo isset($_GET['error']) && (strpos($_GET['error'], 'Password') !== false || strpos($_GET['error'], 'match') !== false) ? 'is-invalid' : ''; ?>" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                </div>
                <div class="form-group">
                  <button type="submit" class="form-control btn btn-primary rounded submit px-3">Register</button>
                </div>
              </form>
              <p class="text-center">Already a member? <a href="../index.php">Login</a></p>   </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script src="../js/jquery.min.js"></script>
  <script src="../js/popper.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/main.js"></script>

  <script>
    // Clear form fields when page is unloaded
    window.addEventListener('beforeunload', function() {
      document.getElementById('first_name').value = '';
      document.getElementById('last_name').value = '';
      document.getElementById('username').value = '';
      document.getElementById('email').value = '';
      document.getElementById('password').value = '';
      document.getElementById('confirm_password').value = '';
    });
  </script>

</body>
</html>
