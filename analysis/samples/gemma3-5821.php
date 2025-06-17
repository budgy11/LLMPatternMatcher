
<!DOCTYPE html>
<html>
<head>
    <title>Search Bar Example</title>
</head>
<body>

    <h1>Search Fruit Names</h1>

    <form method="get">
        <input type="text" name="search" placeholder="Enter fruit name to search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
