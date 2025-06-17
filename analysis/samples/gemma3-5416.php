
<!DOCTYPE html>
<html>
<head>
  <title>Product Search</title>
</head>
<body>

  <h1>Product Search</h1>

  <form method="POST" action="">
    <input type="text" name="search" placeholder="Enter search term" value="<?php echo isset($_POST['search']) ? htmlspecialchars($search_term) : ''; ?>">
