
<!DOCTYPE html>
<html>
<head>
    <title>Search Items</title>
</head>
<body>

    <h1>Search Items</h1>

    <form method="GET">
        <input type="text" name="search" placeholder="Enter search term" value="<?php echo isset($search_term) ? htmlspecialchars($search_term) : ''; ?>">
