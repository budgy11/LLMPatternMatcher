
<!DOCTYPE html>
<html>
<head>
  <title>Search Products</title>
</head>
<body>

  <h1>Search Products</h1>

  <form method="GET">
    <input type="text" name="search" placeholder="Enter product name" value="<?php echo htmlspecialchars($searchTerm); ?>" />
