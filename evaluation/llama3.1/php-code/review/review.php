
<?php
// Connect to database
$conn = new mysqli("localhost", "username", "password", "database");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the product ID from the URL
$product_id = $_GET["id"];

// Check if the user is submitting a review
if (isset($_POST["submit"])) {
    // Get the submitted data and sanitize it to prevent SQL injection
    $review_text = trim($_POST["review"]);
    $rating = intval(trim($_POST["rating"]));
    
    // Insert the review into the database
    $sql = "INSERT INTO reviews (product_id, user_name, review_text, rating) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $product_id, $_SESSION["username"], $review_text, $rating);
    
    if (!$stmt->execute()) {
        echo "Error submitting review: " . $conn->error;
    } else {
        // Redirect the user back to the product page
        header("Location: product.php?id=$product_id");
        exit();
    }
}

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
?>
