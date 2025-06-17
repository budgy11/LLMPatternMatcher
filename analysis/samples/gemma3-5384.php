
<!DOCTYPE html>
<html>
<head>
<title>Search Example</title>
</head>
<body>

<h1>Search Results</h1>

<form method="GET" action="">
    <input type="text" name="search" placeholder="Enter search term">
    <input type="submit" value="Search">
</form>

<?php if (!empty($results)): ?>
