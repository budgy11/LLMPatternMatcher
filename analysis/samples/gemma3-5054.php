
<!DOCTYPE html>
<html>
<head>
    <title>Search Example</title>
</head>
<body>

    <h1>Search Products</h1>

    <form method="get">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search_term); ?>" placeholder="Enter search term">
