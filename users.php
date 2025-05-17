<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Nmodel - Users</title>
  <link rel="stylesheet" href="style/users.css" />
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

  <main class="user-section">
    <div class="table-wrapper">
      <h2 class="user-heading">Users</h2>
      <table class="user-table">
        <thead>
          <tr>
            <th>Role</th>
            <th>Username</th>
            <th>Password</th>
          </tr>
        </thead>
        <tbody id="user-table-body">
          <!-- Data will be loaded here -->
        </tbody>
      </table>
    </div>
  </main>

  <script>
    fetch("php/fetch_users.php")
      .then(res => res.json())
      .then(data => {
        const tbody = document.getElementById("user-table-body");
        data.forEach(user => {
          const row = document.createElement("tr");
          row.innerHTML = `
            <td>${user.role}</td>
            <td>${user.username}</td>
            <td>${user.password}</td>
          `;
          tbody.appendChild(row);
        });
      });
  </script>
</body>
</html>