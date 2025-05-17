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
  <title>Nmodel - Admin</title>
  <link rel="stylesheet" href="style/admin.css" />
  <link rel="icon" type="image/png" href="images/icon.png" />
  <style>
    .admin-layout {
      display: flex;
      justify-content: center;
      align-items: flex-start;
      gap: 40px;
      padding: 40px;
    }

    .admin-controls,
    .admin-controls-extra {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .admin-btn {
      padding: 30px 50px;
      background-color: #0a1f91;
      color: white;
      border: 1px solid #5a8cf2;
      border-radius: 10px;
      font-size: 20px;
      cursor: pointer;
      transition: background-color 0.3s, transform 0.2s;
      width: 300px;
      text-align: center;
    }

    .admin-btn:hover {
      background-color: #1a33d5;
      transform: scale(1.03);
    }
  </style>
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

  <main class="admin-container">
    <!-- Left button column -->
    <div class="admin-column">
      <button class="admin-btn" onclick="window.location.href='changepass.php'">Change Password</button>
      <button class="admin-btn" onclick="window.location.href='adduser.php'">Add User</button>
      <button class="admin-btn" onclick="window.location.href='removeuser.php'">Remove User</button>
      <button class="admin-btn" onclick="window.location.href='users.php'">Users</button>
    </div>
  
    <!-- Right button column -->
    <div class="admin-column">
      <button class="admin-btn" onclick="window.location.href='userpredictions.php'">User Predictions</button>
      <button class="admin-btn" onclick="window.location.href='actions.php'">Actions</button>
    </div>
  </main>
</body>
</html>