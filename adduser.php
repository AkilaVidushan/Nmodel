<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
  header("Location: login.html");
  exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Nmodel - Add User</title>
  <link rel="stylesheet" href="style/adduser.css" />
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


  <main class="add-user-container">

<img src="images/adduser.png" alt="Add User Icon" class="add-user-icon" />

<!-- 🚨 ALERT AREA -->
<div class="alert-container">
  <?php if (isset($_GET['error'])): ?>
    <div class="form-alert error-alert" id="alertBox">
      <?php echo htmlspecialchars($_GET['error']); ?>
    </div>
  <?php elseif (isset($_GET['success'])): ?>
    <div class="form-alert success-alert" id="alertBox">
      <?php echo htmlspecialchars($_GET['success']); ?>
    </div>
  <?php else: ?>
    <div class="form-alert hidden-alert" id="alertBox">
    </div>
  <?php endif; ?>
</div>

<form action="php/add_user.php" method="post" class="add-user-form">
  <label for="role">Role</label>
  <input type="text" id="role" name="role" required />

  <label for="username">Enter Username</label>
  <input type="text" id="username" name="username" required />

  <label for="password">Enter Password</label>
  <input type="password" id="password" name="password" required />

  <button type="submit" class="add-user-btn">Add User</button>
</form>

</main>

<script>
setTimeout(() => {
  const alertBox = document.getElementById('alertBox');
  if (alertBox) {
    alertBox.style.transition = 'opacity 0.5s ease';
    alertBox.style.opacity = '0';
    setTimeout(() => alertBox.remove(), 500); // Fully remove
  }
}, 4000); // 4 seconds
</script>

</body>
</html>