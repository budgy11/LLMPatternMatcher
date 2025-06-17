
<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
</head>
<body>

  <h1>Search Fruits</h1>

  <form method="get" action="">
    <input type="text" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>" placeholder="Enter a fruit...">
