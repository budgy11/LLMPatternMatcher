
<!DOCTYPE html>
<html>
<head>
    <title>Simple Search Bar</title>
</head>
<body>

    <h1>Search Results</h1>

    <form method="GET" action="">
        <input type="text" name="search" placeholder="Enter search term">
        <button type="submit">Search</button>
    </form>

    <?php if (empty($searchResults)): ?>
