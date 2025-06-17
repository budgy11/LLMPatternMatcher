
<!DOCTYPE html>
<html>
<head>
    <title>Search Bar Example</title>
</head>
<body>

    <h1>Search Products</h1>

    <form method="get" action="">  <!-- Form action should be the same as the PHP script's file name (e.g., search_script.php) -->
        <input type="text" name="search" value="<?php echo $searchTerm; ?>">
