<?php    
// Example using prepared statements (stronger security)
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_name LIKE ? OR category LIKE ?");
    $searchTerm = "%" . $searchTerm . "%"; // Escape properly
    $stmt->bind_param("s", $searchTerm, $searchTerm); // "s" for string
    $stmt->execute();
    //  Fetch results as usual
?>
