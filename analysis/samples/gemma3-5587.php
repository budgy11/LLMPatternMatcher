
<!DOCTYPE html>
<html>
<head>
    <title>Search Bar Example</title>
</head>
<body>

    <h1>Search Results</h1>

    <form method="get" action="">
        <input type="text" name="search" placeholder="Enter search term..." value="<?php echo htmlspecialchars($searchTerm); ?>">
