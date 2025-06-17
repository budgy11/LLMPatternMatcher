

<?php
// Assuming you have a $conn mysqli object
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchTerm = isset($_POST["search_term"]) ? trim($_POST["search_term"]) : "";

    $query = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

    // Example using prepared statements to prevent SQL injection
    // $stmt = $this->mysqli->prepare($query);
    // $stmt->bind_param("s", $searchTerm);  // "s" indicates a string parameter
    // $stmt->execute();
    // $result = $stmt->get_result();


    // ... (rest of the code for displaying results)

}
?>
