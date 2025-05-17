<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
  header("Location: login.html");
  exit();
}
// Connect to database
$conn = new mysqli("localhost", "u242115527_aavbalasuriya", "Akila#19525", "u242115527_nmodel_db");

// Initialize error message
$error = "";
$success = "";

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $newPassword = $_POST['new-password'];
    $confirmPassword = $_POST['confirm-password'];

    // Check if username exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $error = "Username does not exist.";
    } else {
        // Check if passwords match
        if ($newPassword !== $confirmPassword) {
            $error = "Passwords do not match.";
        } else {
            // Check password strength
            if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $newPassword)) {
                $error = "Password must be at least 8 characters and contain both letters and numbers.";
            } else {
                // Hash password and update
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $update = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
                $update->bind_param("ss", $hashedPassword, $username);
                if ($update->execute()) {
                    $success = "Password changed successfully!";
                } else {
                    $error = "Error updating password.";
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
  <title>Nmodel - Change Password</title>
  <link rel="stylesheet" href="style/changepass.css" />
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

  
  <main class="change-pass-container">
  <form action="changepass.php" method="post" class="change-pass-form">
    
    <!-- Move the message inside the form -->
    <?php if (!empty($error)): ?>
      <div class="message error"><?php echo $error; ?></div>
    <?php elseif (!empty($success)): ?>
      <div class="message success"><?php echo $success; ?></div>
    <?php endif; ?>

    <label for="username">Username</label>
    <input type="text" id="username" name="username" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" />

    <label for="new-password">New Password</label>
    <input type="password" id="new-password" name="new-password" required />

    <label for="confirm-password">Confirm Password</label>
    <input type="password" id="confirm-password" name="confirm-password" required />

    <button type="submit" class="change-pass-btn">Change Password</button>
  </form>
</main>
<script>
  // Fade out message after 3 seconds
  setTimeout(() => {
    const message = document.querySelector('.message');
    if (message) {
      message.style.transition = "opacity 1s"; // Smooth fade
      message.style.opacity = "0";             // Fade out
      setTimeout(() => {
        message.style.display = "none";        // Remove from layout after fade
      }, 1000); // Wait for fade to complete (1s)
    }
  }, 3000); // Start after 3 seconds
</script>
</body>
</html>