
<!DOCTYPE html>
<html>
<head>
    <title>Search Example</title>
</head>
<body>

    <h1>Search</h1>

    <form method="get" action="">  <!--  method="get" means the search term will be sent in the URL.  method="post" would send it as a POST request. -->
        <input type="text" name="search" value="<?php echo htmlspecialchars($search_term); ?>" placeholder="Enter search term">
