<?php
session_start();
include 'db_conn.php'; // This must define $conn (your MySQLi connection)

$success = "";
$error = "";

if (isset($_GET['registered']) && $_GET['registered'] == 1) {
    $success = "Registration successful! You may now log in.";
}

if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $check = mysqli_query($conn, "SELECT * FROM admin WHERE email = '$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Email already registered.";
        } else {
            $insert = "INSERT INTO admin (username, email, password) VALUES ('$name', '$email', '$password')";
            if (mysqli_query($conn, $insert)) {
                header("Location: register.php?registered=1");
                exit();
            } else {
                $error = "Error: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Register - Les Boissons Restaurant</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
      margin: 0;
    }
    .register-section {
      display: flex;
      min-height: 100vh;
    }
    .register-image {
      flex: 1;
      background: url('blogo.png') center center / contain no-repeat;
      background-color: #212529;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .register-form-container {
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
    .btn-register {
      background-color: #7C828B;
      color: white;
      border: none;
      border-radius: 0.5rem;
      padding: 0.75rem;
      font-weight: 600;
      transition: background-color 0.3s;
    }
    .btn-register:hover {
      background-color: #636a74;
    }
  </style>
</head>
<body>

<section class="register-section">
  <div class="register-image"></div>
  <div class="register-form-container">
    <div class="form-box">
      <div class="text-center mb-4">
        <h3>Create an Account</h3>
        <p class="text-muted">Register as a staff member</p>
      </div>

      <!-- Success and Error Alerts -->
      <?php if (!empty($success)) : ?>
        <div class="alert alert-success text-center"><?php echo $success; ?></div>
      <?php endif; ?>

      <?php if (!empty($error)) : ?>
        <div class="alert alert-danger text-center"><?php echo $error; ?></div>
      <?php endif; ?>

      <form method="POST" action="">
        <div class="form-floating mb-3">
          <input type="text" class="form-control" name="name" id="name" placeholder="Full Name" required />
          <label for="name">Full Name</label>
        </div>
        <div class="form-floating mb-3">
          <input type="email" class="form-control" name="email" id="email" placeholder="Email" required />
          <label for="email">Email</label>
        </div>
        <div class="form-floating mb-3">
          <input type="password" class="form-control" name="password" id="password" placeholder="Password" required />
          <label for="password">Password</label>
        </div>
        <div class="form-floating mb-3">
          <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required />
          <label for="confirm_password">Confirm Password</label>
        </div>
        <button type="submit" name="register" class="btn btn-register w-100 mb-2">Register</button>
        <a href="loginpage.php" class="btn btn-outline-secondary w-100">Back to Login</a>
      </form>
    </div>
  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
