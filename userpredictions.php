<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>User Predictions</title>
  <link rel="stylesheet" href="style/userpredictions.css" />
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

  <main class="predictions-section">
    <h2 class="heading">User Predictions</h2>

    <div class="table-container">
      <table class="predictions-table">
        <thead>
          <tr>
            <th>User</th>
            <th>Predict Date</th>
            <th>Predict Time</th>
            <th>Allocated Date</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Prediction</th>
            <th>Capacity</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="prediction-table-body">
          <!-- Real data will be loaded here -->
        </tbody>
      </table>
    </div>
  </main>

  <script>
    function loadPredictions() {
      fetch("php/fetch_records.php")
        .then(res => res.json())
        .then(data => {
          const tbody = document.getElementById("prediction-table-body");
          tbody.innerHTML = ""; // Clear old rows
  
          data.forEach(record => {
            const row = document.createElement("tr");
            row.innerHTML = `
              <td>${record.user}</td>
              <td>${record.p_date}</td>
              <td>${record.p_time}</td>
              <td>${record.date}</td>
              <td>${record.st}</td>
              <td>${record.et}</td>
              <td>${record.pred}</td>
              <td>${record.students}</td>
              <td><button class="delete-btn" onclick="deleteRecord(${record.rid})">Delete</button></td>
            `;
            tbody.appendChild(row);
          });
        });
    }
  
    function deleteRecord(rid) {
      if (confirm("Are you sure you want to delete this record?")) {
        fetch('php/delete_record.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `rid=${rid}`
        })
        .then(res => res.text())
        .then(response => {
          alert(response);
          loadPredictions(); // Reload table after deleting
        })
        .catch(error => {
          alert("Error deleting record.");
        });
      }
    }
  
    // Load predictions on page load
    loadPredictions();
  </script>

</body>
</html>