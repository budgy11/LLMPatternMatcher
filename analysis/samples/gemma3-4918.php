
<!DOCTYPE html>
<html>
<head>
    <title>Search Bar Example</title>
</head>
<body>

    <h1>Search</h1>

    <form method="GET" action="">
        <input type="text" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>">
