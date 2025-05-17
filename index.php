<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    // User is not logged in
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Nmodel</title>
  <link rel="stylesheet" href="style/modelstyle.css" />
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

  <main class="model-form-container">
    <div class="model-layout">
      <!-- Form -->
      <form id="predict-form" action="php/predict.php" method="post" class="model-form">

        <label for="date">Enter Date</label>
        <input type="date" id="date" name="date" required />

        <label for="students">Enter Number of Students</label>
        <input type="number" id="students" name="students" placeholder="Value" min="1" max="600" step="1" required />

        <label for="projectors">Projectors</label>
        <input type="number" id="projectors" name="projectors" placeholder="Value" min="0" max="2" step="1" required />

        <label for="computers">Computers</label>
        <input type="number" id="computers" name="computers" placeholder="Value" min="0" max="50" step="1" required />

        <label for="start-time">Start Time</label>
        <input type="time" id="start-time" name="start-time" min="09:00" max="16:00" step="1800" required />

        <label for="end-time">End Time</label>
        <input type="time" id="end-time" name="end-time" min="10:00" max="17:00" step="1800" required />

        <div class="actions-row">
          <button type="submit" class="predict-btn">Predict</button>
        </div>

      </form>

      <!-- Recent Records Table -->
      <div class="recent-table">
            <?php if (isset($_GET['prediction'])): ?>
            <div id="prediction-result" class="prediction-box" style="display: block;">
                <strong>Allocation:</strong> <span id="predicted-value"><?php echo htmlspecialchars($_GET['prediction']); ?></span>
            </div>
                <?php elseif (isset($_GET['error'])): ?>
                    <div id="prediction-result" class="prediction-box" style="display: block; background-color: rgba(255, 0, 0, 0.2); border: 2px solid red;">
                        <strong>Error:</strong> <span id="predicted-value"><?php echo htmlspecialchars($_GET['error']); ?></span>
                    </div>
                <?php else: ?>
                    <div id="prediction-result" class="prediction-box" style="display: none;">
                        <strong>Allocation:</strong> <span id="predicted-value"></span>
                    </div>
                <?php endif; ?>
        <h3>Recent Records</h3>
        <table>
          <thead>
            <tr>
              <th>Date</th>
              <th>Students</th>
              <th>Projectors</th>
              <th>Computers</th>
              <th>Start Time</th>
              <th>End Time</th>
              <th>Allocation</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
                  <?php
                  // Connect to Database 
                  $servername = "localhost";
                  $username = "u242115527_aavbalasuriya";
                  $password = "Akila#19525";
                  $dbname = "u242115527_nmodel_db";

                  $conn = new mysqli($servername, $username, $password, $dbname);
                  if ($conn->connect_error) {
                      die("Connection failed: " . $conn->connect_error);
                  }

                  // Get the latest 5 records for this user
                  $user = $_SESSION['username'];
                  $sql = "SELECT * FROM record WHERE user = ? ORDER BY rid DESC LIMIT 5";

                  $stmt = $conn->prepare($sql);
                  $stmt->bind_param("s", $user);
                  $stmt->execute();
                  $result = $stmt->get_result();

                  while ($row = $result->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                        <td><?php echo htmlspecialchars($row['students']); ?></td>
                        <td><?php echo htmlspecialchars($row['proj']); ?></td>
                        <td><?php echo htmlspecialchars($row['com']); ?></td>
                        <td><?php echo htmlspecialchars($row['st']); ?></td>
                        <td><?php echo htmlspecialchars($row['et']); ?></td>
                        <td><?php echo htmlspecialchars($row['pred']); ?></td>
                        <td>
                            <button type="button" class="delete-btn" data-rid="<?php echo $row['rid']; ?>">Delete</button>
                        </td>
                      </tr>
                  <?php endwhile; 

                  $stmt->close();
                  $conn->close();
                  ?>
                </tbody>
        </table>
        <div class="view-prediction-btn-container">
           <a href="viewpredictions.php" class="view-link">View Allocations</a>
        </div>
      </div>
    </div>
  </main>
  <script>
      document.addEventListener('DOMContentLoaded', function() {
        // Attach click event to all delete buttons
        document.querySelectorAll('.delete-btn').forEach(function(button) {
          button.addEventListener('click', function() {
            if (confirm("Are you sure you want to delete this record?")) {
              const rid = this.getAttribute('data-rid');
              const row = this.closest('tr');

              fetch('php/delete_record.php', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'rid=' + encodeURIComponent(rid)
              })
              .then(response => response.text())
              .then(data => {
                console.log(data);
                // Remove row from the table
                row.remove();
              })
              .catch(error => {
                console.error('Error:', error);
                alert('Failed to delete record.');
              });
            }
          });
        });
      });
</script>

<script>
document.getElementById("predict-form").addEventListener("submit", function (e) {
  const start = document.getElementById("start-time").value;
  const end = document.getElementById("end-time").value;

  if (!start || !end) return;

  const [sh, sm] = start.split(":").map(Number);
  const [eh, em] = end.split(":").map(Number);

  const startMinutes = sh * 60 + sm;
  const endMinutes = eh * 60 + em;
  const gap = endMinutes - startMinutes;

  if (gap < 60) {
    e.preventDefault();
    alert("The time gap must be at least 1 hour.");
    return;
  }

  if (gap > 240) {
    e.preventDefault();
    alert("The time gap must not exceed 4 hours.");
    return;
  }

  if (endMinutes <= startMinutes) {
    e.preventDefault();
    alert("End time must be greater than start time.");
    return;
  }
});
</script>

</body>
</html>