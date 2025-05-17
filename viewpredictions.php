<?php
session_start();

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
  <title>View Predictions</title>
  <link rel="stylesheet" href="style/viewpredictions.css" />
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

  <main class="predictions-container">
    <h2 class="page-heading">Filter Predictions</h2>
    <div class="form-wrapper">
    <form class="prediction-form" method="GET" action="filtered.php">
      <label for="predict-date">Predict Date</label>
      <input type="date" id="predict-date" name="predict-date" />

      <label for="start-date">Start Date</label>
      <input type="date" id="start-date" name="start-date" />

      <label for="end-date">End Date</label>
      <input type="date" id="end-date" name="end-date" />

      <label for="lecturehall">Lecture Hall</label>
      <select id="lecturehall" name="lecturehall">
        <option value="all">All</option>
        <option value="l102">L102</option>
        <option value="5">005</option>
        <option value="8">008</option>
        <option value="6">006</option>
        <option value="7">007</option>
        <option value="l104">L104</option>
        <option value="l105">L105</option>
        <option value="l107">L107</option>
        <option value="l106">L106</option>
        <option value="l205">L205</option>
        <option value="l204">L204</option>
        <option value="l203">L203</option>
        <option value="l202">L202</option>
        <option value="106">106</option>
        <option value="4">004</option>
        <option value="9">009</option>
        <option value="2">002</option>
        <option value="105">105</option>
        <option value="l101">L101</option>
        <option value="3">003</option>
        <option value="103">103</option>
        <option value="l110">L110</option>
        <option value="l109">L109</option>
      </select>

      <button type="submit" class="filter-btn">Apply Filter</button>
    </form>
    </div>
  </main>
</body>
</html>