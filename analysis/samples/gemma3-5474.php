
<!DOCTYPE html>
<html>
<head>
    <title>Search Bar Example</title>
</head>
<body>

    <h1>Search Items</h1>

    <form method="GET">
        <input type="text" name="search" placeholder="Enter search term" value="<?php echo htmlspecialchars($searchTerm); ?>">
