<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory System Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" integrity="sha384-0svjQEgQWvzfUJBWolznvEuYzIZqwbleNKI6ON/ymNqcwE9YKxpGCaTSXyMcyzFCE" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">  </head>
<body>
    <div class="container mt-5">
        <div class="card mx-auto" style="width: 300px;">
            <div class="card-header">
                <h4>Login</h4>
            </div>
            <div class="card-body">
                <?php
                // Display error message if login fails (explained later)
                if (isset($_GET['error'])) {
                    echo "<div class='alert alert-danger' role='alert'>" . $_GET['error'] . "</div>";
                }
                ?>
                <form action="login_process.php" method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
