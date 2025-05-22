<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Les Boissons Restaurant</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
      margin: 0;
    }
    .login-section {
      display: flex;
      min-height: 100vh;
    }
    .login-image {
      flex: 1;
      background: url('blogo.png') center center / contain no-repeat;
      background-color: #212529;
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .login-form-container {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
      background-color: white;
    }
    .form-box {
      width: 100%;
      max-width: 400px;
    }
    .btn-login {
      background-color: #7C828B;
      color: white;
      border: none;
      border-radius: 0.5rem;
      padding: 0.75rem;
      font-weight: 600;
      transition: background-color 0.3s;
    }
    .btn-login:hover {
      background-color: #636a74;
    }
  </style>
</head>
<body>

<section class="login-section">
  <div class="login-image"></div>
  <div class="login-form-container">
    <div class="form-box">
      <div class="text-center mb-4">
        <h3>Login to Les Boissons Restaurant</h3>
        <p class="text-muted">Welcome back, you've been missed!</p>
      </div>

      <?php
        session_start();
        include 'db_conn.php'; // Assumes this file sets $conn

        if (isset($_POST['login'])) {
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $password = mysqli_real_escape_string($conn, $_POST['password']);

            // Query to search by email
            $query = "SELECT * FROM admin WHERE email = '$email'";
            $result = mysqli_query($conn, $query);

            if (!$result) {
                echo "<p class='text-danger text-center'>Database error: " . mysqli_error($conn) . "</p>";
            } elseif (mysqli_num_rows($result) == 1) {
                $user = mysqli_fetch_assoc($result);

                // Plaintext password comparison (replace with password_verify if using hashes)
                if ($password === $user['password']) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];  // or use email
                    $_SESSION['role'] = 'admin';
                    header('Location: tablepage.php');
                    exit();
                } else {
                    $error_message = "Invalid email or password.";
                }
            } else {
                $error_message = "User not found.";
            }
        }
      ?>

      <form method="POST" action="">
        <div class="form-floating mb-3">
          <input type="email" class="form-control" name="email" placeholder="Email" required />
          <label for="email">Email</label>
        </div>
        <div class="form-floating mb-3">
          <input type="password" class="form-control" name="password" placeholder="Password" required />
          <label for="password">Password</label>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember_me" />
            <label class="form-check-label">Remember Me</label>
          </div>
          <a href="forgotpass.php" class="text-muted small">Forgot Password?</a>
        </div>
        <button type="submit" name="login" class="btn btn-login w-100 mb-2">Login</button>
        <a href="register.php" class="btn btn-outline-secondary w-100">Register</a>
        <?php if (isset($error_message)) { echo "<p class='text-danger text-center mt-3'>$error_message</p>"; } ?>
      </form>
    </div>
  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
