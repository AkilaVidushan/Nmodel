<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: login.html");
  exit();
}

include('php/analysis.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Analysis</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="style/analysis.css" />
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
    

  <div id="totalBox" style="background-color: rgba(177, 238, 175, 0.15); 
            color: rgb(102, 253, 99); 
            padding: 20px 40px; 
            border-radius: 10px; 
            font-size: 26px;
            font-weight: bold; 
            width: fit-content; 
            margin: 50px 0 50px 13.5%;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            display: flex; 
            align-items: center;
            gap: 10px;">
    <span style="opacity: 0.8;">Total Students Allocated:</span> 
    <span id="totalStudents" style="font-size: 36px; color: rgb(173, 123, 255);"> <?php echo $total_students; ?> </span>
</div>

    <div style="width: 100%; max-width: 1100px; margin: 30px auto 100px auto;">
    <h2 style="text-align: left; margin-left: 0%;">Morning Session</h2>
      <canvas id="morningChart"></canvas>
    </div>

    <div style="width: 100%; max-width: 1100px; margin: 30px auto 100px auto;">
      <h2 style="text-align: left; margin-left: 0%;">Evening Session</h2>
      <canvas id="eveningChart"></canvas>
    </div>

    <div style="width: 100%; max-width: 1100px; margin: 30px auto 100px auto;">
        <h2 style="text-align: left; margin-left: 0%;">Average Students per Hall</h2>
        <canvas id="averageChart"></canvas>
    </div>
    
    <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 50px; max-width: 1100px; margin: 30px auto 100px auto;">
  <div style="flex: 1 1 45%; max-width: 500px; text-align: center;">
    <h2 style="margin-bottom: 10px;">Morning vs Evening Load</h2>
    <canvas id="loadPieChart"></canvas>
  </div>

  <div style="flex: 1 1 45%; max-width: 500px; text-align: center;">
    <h2 style="margin-bottom: 10px;">Top 5 Most Used Halls</h2>
    <canvas id="topHallsChart"></canvas>
  </div>
</div>

  <script>
    // Morning Chart
    const morningCtx = document.getElementById('morningChart').getContext('2d');
    const morningChart = new Chart(morningCtx, {
      type: 'bar',
      data: {
        labels: <?php echo json_encode(array_column($morning_data, 'date')); ?>,
        datasets: [{
          label: 'Students in Morning',
          data: <?php echo json_encode(array_column($morning_data, 'total_students')); ?>,
          backgroundColor: 'rgba(54, 162, 235, 0.6)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: { beginAtZero: true }
        }
      }
    });

    // Evening Chart
    const eveningCtx = document.getElementById('eveningChart').getContext('2d');
    const eveningChart = new Chart(eveningCtx, {
      type: 'bar',
      data: {
        labels: <?php echo json_encode(array_column($evening_data, 'date')); ?>,
        datasets: [{
          label: 'Students in Evening',
          data: <?php echo json_encode(array_column($evening_data, 'total_students')); ?>,
          backgroundColor: 'rgba(255, 99, 132, 0.6)',
          borderColor: 'rgba(255, 99, 132, 1)',
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: { beginAtZero: true }
        }
      }
    });
  </script>
  <script>
  // Average Students per Hall Chart
  const averageCtx = document.getElementById('averageChart').getContext('2d');
  const averageChart = new Chart(averageCtx, {
    type: 'bar',
    data: {
      labels: <?php echo json_encode(array_column($average_data, 'pred')); ?>,
      datasets: [{
        label: 'Average Students',
        data: <?php echo json_encode(array_map(function($x) { return round($x['avg_students'], 2); }, $average_data)); ?>,
        backgroundColor: 'rgba(78, 228, 76, 0.6)',
        borderColor: 'rgb(125, 251, 94)',
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: { beginAtZero: true }
      }
    }
  });
</script>


<script>
document.addEventListener("DOMContentLoaded", function() {
    const counter = document.getElementById("totalStudents");
    const target = parseInt(counter.innerText);
    let count = 0;
    const speed = 20; // Smaller is faster

    const updateCounter = () => {
        const increment = Math.ceil(target / 100); // 100 steps
        if (count < target) {
            count += increment;
            if (count > target) count = target;
            counter.innerText = count;
            setTimeout(updateCounter, speed);
        } else {
            counter.innerText = target; // Ensure it ends exactly on the number
        }
    };

    counter.innerText = '0'; // Start from 0
    updateCounter();
});
</script>
<style>
@keyframes slideInFromLeft {
  0% {
    opacity: 0;
    transform: translateX(-100px);
  }
  100% {
    opacity: 1;
    transform: translateX(0);
  }
}

#totalBox {
  animation: slideInFromLeft 1s ease-out;
}
</style>
<script>
  // Pie Chart - Morning vs Evening
  const loadPieCtx = document.getElementById('loadPieChart').getContext('2d');
  const loadPieChart = new Chart(loadPieCtx, {
    type: 'pie',
    data: {
      labels: ['Morning', 'Evening'],
      datasets: [{
        label: 'Student Load',
        data: [
          <?php echo $load_data['morning_total'] ?? 0; ?>,
          <?php echo $load_data['evening_total'] ?? 0; ?>
        ],
        backgroundColor: ['rgba(54, 162, 235, 0.7)', 'rgba(255, 99, 132, 0.7)'],
        borderColor: ['#36A2EB', '#FF6384'],
        borderWidth: 1
      }]
    }
  });

  // Pie Chart - Top 5 Most Used Halls
  const topHallsCtx = document.getElementById('topHallsChart').getContext('2d');
  const topHallsChart = new Chart(topHallsCtx, {
    type: 'pie',
    data: {
      labels: <?php echo json_encode(array_column($top_halls, 'pred')); ?>,
      datasets: [{
        label: 'Usage Count',
        data: <?php echo json_encode(array_column($top_halls, 'usage_count')); ?>,
        backgroundColor: [
          'rgba(255, 99, 132, 0.7)',
          'rgba(54, 162, 235, 0.7)',
          'rgba(255, 206, 86, 0.7)',
          'rgba(75, 192, 192, 0.7)',
          'rgba(153, 102, 255, 0.7)'
        ],
        borderColor: ['#fff', '#fff', '#fff', '#fff', '#fff'],
        borderWidth: 1
      }]
    }
  });
</script>


</body>
</html>