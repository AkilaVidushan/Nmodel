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
  <title>Select Lecture Hall</title>
  <link rel="stylesheet" href="style/lectureselect.css" />
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

  <main class="hall-select-section">
    <form id="hallForm">
      <label for="hall">Select Lecture Hall</label>
      <select id="hall" name="hall" required>
        <option value="">Select Hall</option>
        <option value="l102">L102</option>
        <option value="005">005</option>
        <option value="008">008</option>
        <option value="006">006</option>
        <option value="007">007</option>
        <option value="l104">L104</option>
        <option value="l105">L105</option>
        <option value="l107">L107</option>
        <option value="l106">L106</option>
        <option value="l205">L205</option>
        <option value="l204">L204</option>
        <option value="l203">L203</option>
        <option value="l202">L202</option>
        <option value="106">106</option>
        <option value="004">004</option>
        <option value="009">009</option>
        <option value="002">002</option>
        <option value="105">105</option>
        <option value="l101">L101</option>
        <option value="003">003</option>
        <option value="103">103</option>
        <option value="l110">L110</option>
        <option value="l109">L109</option>
      </select>

      <button type="submit" class="select-btn">Select</button>
    </form>
  </main>

  <button class="hall-info-btn" onclick="location.href='info.php'">Hall Info</button>

  <script>
    document.getElementById("hallForm").addEventListener("submit", function (e) {
      e.preventDefault();
      const selectedHall = document.getElementById("hall").value;
      if (selectedHall) {
        window.location.href = `lechall.php?hall=${encodeURIComponent(selectedHall)}`;
      } else {
        alert("Please select a lecture hall.");
      }
    });
  </script>
</body>
</html>