<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Block non-admin users
if ($_SESSION['username'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost","u242115527_aavbalasuriya", "Akila#19525", "u242115527_nmodel_db");

// Initialize variables
$error = "";
$success = "";
$username_value = ""; // To manage the username input field

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $adminPass = $_POST['admin-pass'];
    $usernameToRemove = trim($_POST['username']);
    $username_value = htmlspecialchars($usernameToRemove); // Store username for form input

    // 1. Check admin's password from database
    $adminQuery = $conn->prepare("SELECT password FROM users WHERE username = 'admin'");
    $adminQuery->execute();
    $adminResult = $adminQuery->get_result();

    if ($adminResult->num_rows === 0) {
        $error = "Admin account not found.";
    } else {
        $adminData = $adminResult->fetch_assoc();
        $adminPasswordInDB = $adminData['password'];

        // 2. Compare entered password with database password
        if ($adminPass !== $adminPasswordInDB) {
            $error = "Incorrect admin password.";
        } else {
            // Admin password is correct, now continue to remove user

            // 3. Check if username exists
            $check = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $check->bind_param("s", $usernameToRemove);
            $check->execute();
            $result = $check->get_result();

            if ($result->num_rows === 0) {
                $error = "Username does not exist.";
            } else {
                // Prevent removing admin itself
                if (strtolower($usernameToRemove) === 'admin') {
                    $error = "Admin user cannot be removed.";
                } else {
                    // Delete the user
                    $delete = $conn->prepare("DELETE FROM users WHERE username = ?");
                    $delete->bind_param("s", $usernameToRemove);
                    if ($delete->execute()) {
                        $success = "User removed successfully!";
                        $username_value = "";
                    } else {
                        $error = "Failed to remove user!";
                    }
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Nmodel - Remove User</title>
  <link rel="stylesheet" href="style/removeuser.css" />
  <link rel="icon" type="image/png" href="images/icon.png" />
</head>
<body class="model-page">
  <header class="top-bar">
    <img src="images/logo.png" alt="LN Model Logo" class="logo" />
    <nav class="nav-links">
      <?php if (isset($_SESSION['username']) && $_SESSION['username'] === 'admin'): ?>
           <a href="admin.php">Admin</a>
      <?php endif; ?>
          <a href="index.php">Model</a>
          <a href="history.php">History</a>
          <a href="info.php">Halls</a>
          <a href="analysis.php">Analysis</a>
          <a href="login.html">Login</a>
    </nav>
  </header>

  <main class="remove-user-container">
    <img src="images/removeuser.png" alt="Remove User Icon" class="remove-user-icon" />

    <!-- 🚨 Message Area -->
    <?php if (!empty($error)): ?>
      <div class="form-alert error-alert" id="alertBox"><?php echo $error; ?></div>
    <?php elseif (!empty($success)): ?>
      <div class="form-alert success-alert" id="alertBox"><?php echo $success; ?></div>
    <?php else: ?>
      <div class="form-alert hidden-alert" id="alertBox"></div>
    <?php endif; ?>

    <form action="removeuser.php" method="post" class="remove-user-form">
          <label for="admin-pass">Admin Password</label>
      <input type="password" id="admin-pass" name="admin-pass" required value="" />

      <label for="username">Username</label>
      <input type="text" id="username" name="username" required 
      value="<?php echo (isset($form_cleared) && $form_cleared) ? '' : (isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''); ?>" />

      <button type="submit" class="remove-user-btn">Remove User</button>
    </form>
  </main>

  <script>
    // Fade and remove alert after 4 seconds
    setTimeout(() => {
      const alertBox = document.getElementById('alertBox');
      if (alertBox && alertBox.textContent.trim() !== '') {
        alertBox.style.transition = 'opacity 0.5s ease';
        alertBox.style.opacity = '0';
        setTimeout(() => alertBox.remove(), 500);
      }
    }, 4000);
  </script>
</body>
</html>