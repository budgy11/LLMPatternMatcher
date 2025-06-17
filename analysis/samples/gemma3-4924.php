
</body>
</html>


<?php

// Assume you have a database connection established (e.g., mysqli)
// $conn = new mysqli("localhost", "username", "password", "database_name");
// if ($conn->connect_error) {
//   die("Connection failed: " . $conn->connect_error);
// }

// Example data (replace with your database query)
$data = [
    ["id" => 1, "name" => "Apple", "description" => "A delicious fruit."],
    ["id" => 2, "name" => "Banana", "description" => "A yellow fruit."],
    ["id" => 3, "name" => "Orange", "description" => "A citrus fruit."],
    ["id" => 4, "name" => "Grape", "description" => "Small, sweet fruit."],
];


// Get the search term from the form (or URL)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$searchTerm = htmlspecialchars($searchTerm); // Sanitize for security


// Perform the search
$searchResults = [];
if ($searchTerm) {
    foreach ($data as $row) {
        // Case-insensitive search on name and description
        if (stripos($row["name"], $searchTerm) !== false || stripos($row["description"], $searchTerm) !== false) {
            $searchResults[] = $row;
        }
    }
}


?>
