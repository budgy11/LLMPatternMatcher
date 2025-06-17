
<!DOCTYPE html>
<html>
<head>
    <title>Search Bar Example</title>
</head>
<body>

    <h1>Search Bar Example</h1>

    <form method="get" action="">
        <input type="text" name="search" placeholder="Enter search term">
        <button type="submit">Search</button>
    </form>

    <?php if (!empty($searchResults)): ?>
