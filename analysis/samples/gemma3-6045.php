
<!DOCTYPE html>
<html>
<head>
  <title>Search Example</title>
</head>
<body>

  <h1>Search Results</h1>

  <form method="get" action="">
    <input type="text" id="search" name="search" placeholder="Enter search term" value="<?php echo htmlspecialchars($search_term); ?>">
