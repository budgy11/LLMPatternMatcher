
</body>
</html>


<?php

// Define the search term (this could come from a database or an external source)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (IMPORTANT for security - prevent XSS)
$searchTerm = htmlspecialchars($searchTerm);

// You can perform more complex sanitization here, depending on your use case.
// For example, if you're searching database fields, you might want to escape
// those specific fields individually.

//  Example:  Whitelist allowed characters (more restrictive)
//  $allowedChars = "/a-zA-Z0-9\s/";  //  Allows letters, numbers, and spaces.
//  $searchTerm = preg_replace("/[^" . $allowedChars . "]/", '', $searchTerm);

//  Alternatively, use a blacklisting approach (generally less secure)
//  $searchTerm = str_replace(['<', '>', ';', '"', ']', '[', '\\', '|', '`', '#', '$', '%', '^', '&', '*', '(', ')', '-', '_', '+', '=', '!?', ':']); $searchTerm;

//  Or, use a more robust sanitization library.


// Perform the search (replace this with your actual search logic)
$searchResults = [];

// Simple example: Search in a hardcoded array
$items = [
    'apple',
    'banana',
    'orange',
    'grape',
    'pineapple'
];

$searchTerm = strtolower($searchTerm); // Convert to lowercase for case-insensitive search

foreach ($items as $item) {
    if (stristr($item, $searchTerm)) { // Case-insensitive search using stristr
        $searchResults[] = $item;
    }
}

?>
