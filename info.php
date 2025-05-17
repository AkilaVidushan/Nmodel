<?php
session_start();

// Redirect to login page if not logged in
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
  <title>Lecture Hall Info</title>
  <link rel="stylesheet" href="style/info.css" />
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
          <a href="login.php">Login</a>
    </nav>
  </header>

  <main class="info-section">
    <h2 class="info-heading">Lecture Hall Information</h2>
    <div class="table-wrapper">
      <table class="hall-table">
        <thead>
          <tr>
            <th>Hall</th>
            <th>Capacity</th>
            <th>Projectors</th>
            <th>Computers</th>
            <th>Type</th>
            <th>Cooling</th>
            <th>Purpose</th>
          </tr>
        </thead>
        <tbody>
          <tr><td>L102</td><td>170</td><td>2</td><td>0</td><td>lec</td><td>ac</td><td>lectures</td></tr>
          <tr><td>L005</td><td>50</td><td>1</td><td>0</td><td>lec</td><td>fan</td><td>cssl</td></tr>
          <tr><td>L008</td><td>50</td><td>1</td><td>50</td><td>lab</td><td>ac</td><td>lab/lectures</td></tr>
          <tr><td>L006</td><td>50</td><td>1</td><td>0</td><td>lec</td><td>fan</td><td>IEEE</td></tr>
          <tr><td>L007</td><td>50</td><td>1</td><td>50</td><td>lab</td><td>ac</td><td>lab/lectures</td></tr>
          <tr><td>L104</td><td>50</td><td>1</td><td>0</td><td>lec</td><td>fan</td><td>chess</td></tr>
          <tr><td>L105</td><td>50</td><td>1</td><td>0</td><td>lec</td><td>fan</td><td>foss</td></tr>
          <tr><td>L107</td><td>50</td><td>1</td><td>50</td><td>lab</td><td>ac</td><td>lab/lectures</td></tr>
          <tr><td>L106</td><td>50</td><td>1</td><td>50</td><td>lab</td><td>ac</td><td>lab/lectures</td></tr>
          <tr><td>L205</td><td>50</td><td>1</td><td>50</td><td>lab</td><td>ac</td><td>lab/lectures</td></tr>
          <tr><td>L204</td><td>50</td><td>1</td><td>50</td><td>lab</td><td>ac</td><td>lab/lectures</td></tr>
          <tr><td>L203</td><td>50</td><td>1</td><td>0</td><td>lec</td><td>fan</td><td>isaca</td></tr>
          <tr><td>L202</td><td>50</td><td>1</td><td>0</td><td>lec</td><td>fan</td><td>lectures</td></tr>
          <tr><td>106</td><td>80</td><td>1</td><td>0</td><td>lec</td><td>ac</td><td>iot_lab</td></tr>
          <tr><td>004</td><td>504</td><td>1</td><td>0</td><td>lec</td><td>ac</td><td>lectures</td></tr>
          <tr><td>009</td><td>386</td><td>1</td><td>0</td><td>lec</td><td>ac</td><td>lectures</td></tr>
          <tr><td>002</td><td>267</td><td>2</td><td>0</td><td>lec</td><td>ac</td><td>lectures</td></tr>
          <tr><td>105</td><td>174</td><td>2</td><td>0</td><td>lec</td><td>ac</td><td>lectures</td></tr>
          <tr><td>L101</td><td>180</td><td>2</td><td>0</td><td>lec</td><td>ac</td><td>lectures</td></tr>
          <tr><td>003</td><td>116</td><td>2</td><td>0</td><td>lec</td><td>ac</td><td>lectures</td></tr>
          <tr><td>103</td><td>48</td><td>1</td><td>48</td><td>lec</td><td>ac</td><td>lectures</td></tr>
          <tr><td>L110</td><td>218</td><td>1</td><td>0</td><td>lec</td><td>ac</td><td>lectures</td></tr>
          <tr><td>L109</td><td>48</td><td>1</td><td>0</td><td>lab</td><td>ac</td><td>net_lab</td></tr>
        </tbody>
      </table>
    </div>
  </main>
</body>
</html>