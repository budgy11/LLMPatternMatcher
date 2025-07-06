
<?php
// Connect to database
$conn = new mysqli("localhost", "username", "password", "database");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the product ID from the URL
$product_id = $_GET["id"];

// Get the product details from the database and render them on the product page
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);

if (!$stmt->execute()) {
    echo "Error fetching product: " . $conn->error;
} else {
    $product = $stmt->get_result()->fetch_assoc();
    
    // Create a simple product display using HTML and PHP
    echo "<h2>" . $product["name"] . "</h2>";
    echo "<p>$" . $product["price"] . "</p>";
    echo "<p>" . $product["description"] . "</p>";
    
    // Get all reviews for this product from the database and render them on the review page
    $sql = "SELECT * FROM reviews WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);

    if (!$stmt->execute()) {
        echo "Error fetching reviews: " . $conn->error;
    } else {
        $reviews = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        // Create a simple review display using HTML and PHP
        foreach ($reviews as $review) {
            echo "<div class='review'>";
            echo "<p>Rating: <strong>$review[rating]/5</strong></p>";
            echo "<p>$review[user_name]: $review[review_text]</p>";
            echo "</div>";
        }
    }
}
?>
