
<!DOCTYPE html>
<html>
<head>
<title>Search Products</title>
</head>
<body>

<h2>Search Products</h2>

<form method="GET">
  <input type="text" name="search" placeholder="Enter search term" value="<?php echo htmlspecialchars($searchTerm); ?>">
