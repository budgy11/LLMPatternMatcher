

<?php

// Define the search term
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security)
$searchTerm = htmlspecialchars($searchTerm);

// Handle the search action -  This is where the searching logic would go.
// For now, we'll just display the search term for demonstration.

?>
