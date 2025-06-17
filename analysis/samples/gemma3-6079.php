
<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
</head>
<body>

  <h1>Search</h1>

  <form method="GET" action="">
    <input type="text" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>" placeholder="Enter search term">
