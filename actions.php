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
  <title>User Actions</title>
  <link rel="stylesheet" href="style/actions.css" />
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

  <main class="actions-section">
    <div class="table-controls">
      <select name="action-filter" id="action-filter">
        <option value="all">All Actions</option>
        <option value="delete">Delete</option>
        <option value="download">Download</option>
        <option value="login">Login</option>
      </select>
      <button class="apply-btn">Apply</button>
    </div>

    <h2 class="heading">User Actions</h2>

    <div class="table-wrapper">
      <table class="actions-table">
        <thead>
          <tr>
            <th>User</th>
            <th>Date</th>
            <th>Time</th>
            <th>Action</th>
            <th>Content</th>
          </tr>
        </thead>
        <tbody>
          <!-- Rows will be dynamically inserted by JS -->
        </tbody>
      </table>
    </div>
  </main>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const tableBody = document.querySelector(".actions-table tbody");
      const filterSelect = document.getElementById("action-filter");
      const applyBtn = document.querySelector(".apply-btn");
    
      let allActions = [];
    
      function loadActions() {
        fetch("php/fetch_actions.php")
          .then(res => res.json())
          .then(data => {
            allActions = data;
            renderTable(data);
          });
      }
    
      function renderTable(data) {
        tableBody.innerHTML = "";
        data.forEach(action => {
          const row = document.createElement("tr");
          row.innerHTML = `
            <td>${action.username}</td>
            <td>${action.date}</td>
            <td>${action.time}</td>
            <td>${action.action}</td>
            <td>${action.content}</td>
          `;
          tableBody.appendChild(row);
        });
      }
    
      applyBtn.addEventListener("click", () => {
        const filter = filterSelect.value.toLowerCase();
        if (filter === "all") {
          renderTable(allActions);
        } else {
          const filtered = allActions.filter(a => a.action.toLowerCase() === filter);
          renderTable(filtered);
        }
      });
    
      loadActions();
    });
    </script>

</body>
</html>