<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Nmodel - History</title>
  <link rel="stylesheet" href="style/history.css" />
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

  <main class="history-section">
    <div class="history-header">
      <h2 class="history-heading">Prediction History</h2>
      <div class="buttons-container">
        <button class="erase-btn" onclick="eraseTable()">Erase Table</button>
        <button class="download-btn" onclick="downloadCSV()">Download Table</button>
      </div>
    </div>

    <table class="history-table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Students</th>
          <th>Projectors</th>
          <th>Computers</th>
          <th>Start Time</th>
          <th>End Time</th>
          <th>Prediction</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody id="history-body">
        <!-- Rows will be populated by JS -->
      </tbody>
    </table>

    <div class="pagination">
      <!-- Page buttons will be dynamically created here by JavaScript -->
  </main>

  <script>
    let allRecords = []; // Store all fetched records
    const recordsPerPage = 20; // 20 records per page
    let currentPage = 1;
    
    // Fetch all records initially
    function loadHistory() {
          fetch("php/fetch_history.php")
            .then(res => res.json())
            .then(data => {
              // Sort data by date ASCENDING (oldest first)
              data.sort((a, b) => {
                  const dateA = new Date(a.date);
                  const dateB = new Date(b.date);
                  return dateA - dateB; // Oldest dates first
             });

              allRecords = data; // Save sorted records
              renderTable();     // Render first page
              setupPagination(); // Setup pagination buttons
            });
          }
    
    // Render table for current page
    function renderTable() {
      const tbody = document.getElementById("history-body");
      tbody.innerHTML = "";
    
      const start = (currentPage - 1) * recordsPerPage;
      const end = start + recordsPerPage;
      const recordsToShow = allRecords.slice(start, end);
    
      recordsToShow.forEach(record => {
        const row = document.createElement("tr");
        row.innerHTML = `
          <td>${record.date}</td>
          <td>${record.students}</td>
          <td>${record.proj}</td>
          <td>${record.com}</td>
          <td>${record.st}</td>
          <td>${record.et}</td>
          <td>${record.pred}</td>
          <td><button class="delete-btn" onclick="deleteRow(${record.rid})">Delete</button></td>
        `;
        tbody.appendChild(row);
      });
    }
    
    // Handle deleting a row
    function deleteRow(id) {
      if (confirm("Are you sure you want to delete this record?")) {
        fetch('php/delete_record.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `rid=${id}`
        })
        .then(res => res.text())
        .then(response => {
          alert(response);
          loadHistory(); // Reload after deleting
        })
        .catch(error => {
          alert("Failed to delete the record.");
        });
      }
    }
    
    // Handle erasing all records
    function eraseTable() {
      if (confirm("This will delete all records. Are you sure?")) {
        fetch('php/erase_all_records.php', { method: 'POST' })
          .then(res => res.text())
          .then(response => {
            alert(response);
            loadHistory(); // Reload after erasing
          })
          .catch(error => {
            alert("Failed to erase the table.");
          });
      }
    }
    
  function downloadCSV() {
  const downloadBtn = document.querySelector('.download-btn');
  downloadBtn.disabled = true;
  downloadBtn.textContent = 'Downloading...';

  fetch('php/log_download.php', {
    method: 'POST'
  })
  .then(res => res.text())
  .then(response => {
    console.log(response);
    window.location.href = "php/download_records.php";
    setTimeout(() => {
      downloadBtn.disabled = false;
      downloadBtn.textContent = 'Download Table';
    }, 3000); // Wait 3s before enabling again
  })
  .catch(error => {
    alert("Failed to log download action.");
    downloadBtn.disabled = false;
    downloadBtn.textContent = 'Download Table';
  });
}
    
    // Setup pagination dynamically
    function setupPagination() {
      const pagination = document.querySelector(".pagination");
      pagination.innerHTML = "";
    
      const totalPages = Math.ceil(allRecords.length / recordsPerPage);
    
      for (let i = 1; i <= totalPages; i++) {
        const btn = document.createElement("button");
        btn.className = "page-btn";
        btn.textContent = i;
    
        if (i === currentPage) {
          btn.classList.add("active");
        }
    
        btn.addEventListener("click", function () {
          currentPage = i;
          renderTable();
          setupPagination(); // Refresh active button
        });
    
        pagination.appendChild(btn);
      }
    }
    
    // Load records initially
    loadHistory();
    </script>
</body>
</html>