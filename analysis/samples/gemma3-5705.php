

<!DOCTYPE html>
<html>
<head>
<title>Simple Search Bar</title>
<style>
  .search-container {
    margin-bottom: 10px;
  }
  input[type="text"] {
    width: 300px;
    padding: 10px;
    border: 1px solid #ccc;
    box-sizing: border-box; /* Important for consistent width */
  }
</style>
</head>
<body>

<div class="search-container">
  <form method="get" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
