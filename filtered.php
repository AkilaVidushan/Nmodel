<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: login.html");
  exit();
}

// Connect to DB
$conn = new mysqli("localhost", "u242115527_aavbalasuriya", "Akila#19525", "u242115527_nmodel_db");
if ($conn->connect_error) {
  die("Database connection failed: " . $conn->connect_error);
}

// Get filter values from GET
$predictDate = $_GET['predict-date'] ?? '';
$startDate = $_GET['start-date'] ?? '';
$endDate = $_GET['end-date'] ?? '';
$lectureHall = $_GET['lecturehall'] ?? '';

// Build dynamic SQL query
$sql = "SELECT * FROM record WHERE 1=1";
$params = [];
$types = "";

if (!empty($predictDate)) {
  $sql .= " AND p_date = ?";
  $params[] = $predictDate;
  $types .= "s";
}

if (!empty($startDate)) {
  $sql .= " AND date >= ?";
  $params[] = $startDate;
  $types .= "s";
}

if (!empty($endDate)) {
  $sql .= " AND date <= ?";
  $params[] = $endDate;
  $types .= "s";
}

if (!empty($lectureHall) && $lectureHall !== 'all') {
  $sql .= " AND pred = ?";
  $params[] = $lectureHall;
  $types .= "s";
}

$stmt = $conn->prepare($sql);
if (!empty($params)) {
  $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Filtered Predictions</title>
  <link rel="stylesheet" href="style/filtered.css" />
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

  <main class="filtered-section">
    <div class="table-header">
      <h2 class="heading">Filtered Prediction Results</h2>
      <button class="download-btn" id="downloadFilteredBtn">Download CSV</button>
    </div>

    <table class="filtered-table">
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
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['date']) ?></td>
              <td><?= htmlspecialchars($row['students']) ?></td>
              <td><?= htmlspecialchars($row['proj']) ?></td>
              <td><?= htmlspecialchars($row['com']) ?></td>
              <td><?= htmlspecialchars($row['st']) ?></td>
              <td><?= htmlspecialchars($row['et']) ?></td>
              <td><?= htmlspecialchars($row['pred']) ?></td>
              <td>
                <form method="POST" action="php/delete_record.php" onsubmit="return confirm('Delete this record?')">
                  <input type="hidden" name="rid" value="<?= $row['rid'] ?>">
                  <button class="delete-btn">Delete</button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="8">No records found for the given filters.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
    <img src="images/back.png" alt="Back" class="back-btn" onclick="window.location.href='viewpredictions.php'" />
  </main>

  <script>
document.getElementById("downloadFilteredBtn").addEventListener("click", function () {
  const predictDate = "<?= urlencode($predictDate) ?>";
  const startDate = "<?= urlencode($startDate) ?>";
  const endDate = "<?= urlencode($endDate) ?>";
  const lecturehall = "<?= urlencode($lectureHall) ?>";

  // Construct the URL with query parameters
  const downloadUrl = `php/download_filtered.php?predict-date=${predictDate}&start-date=${startDate}&end-date=${endDate}&lecturehall=${lecturehall}`;

  // Create a hidden link and click it
  const link = document.createElement('a');
  link.href = downloadUrl;
  link.download = "filtered_records.csv"; // optional: suggested filename
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
});
</script>

</body>
</html>