
<!DOCTYPE html>
<html>
<head>
    <title>Search Bar Example</title>
</head>
<body>

    <h1>Search for Fruits</h1>

    <form method="GET" action="">
        <input type="text" name="search" placeholder="Enter fruit name" value="<?php echo htmlspecialchars($search_term); ?>" />
