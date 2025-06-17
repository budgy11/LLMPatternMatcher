
</body>
</html>


// Example SQL Query (adapt to your database table)
$query = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";  //Use LIKE for partial matches
$result = mysqli_query($conn, $query);
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}


<?php

// Assuming you have a form to capture the search input
// This example uses a simple HTML form, but you can adapt it 
// to your specific form structure.

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the search term from the form
    $searchTerm = htmlspecialchars($_POST["search_term"]);  // Use htmlspecialchars for security

    // Sanitize and validate the search term (important!)
    $searchTerm = trim($searchTerm);  // Remove leading/trailing whitespace
    if (empty($searchTerm)) {
        $searchResults = []; // Empty result set if search term is empty
    } else {
        // Perform your database query or other search logic here
        // Replace this placeholder with your actual search code
        $searchResults = performSearch($searchTerm);
    }


} else {
    // If the form hasn't been submitted, initialize the search term
    $searchTerm = "";
    $searchResults = [];
}


?>
