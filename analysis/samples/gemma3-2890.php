

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or item.
 * It includes basic validation and sanitization.  For production environments,
 * consider adding more robust validation and sanitization.
 *
 * @param string $product_id The unique identifier for the product/item.
 * @param string $user_name The name of the user submitting the review.
 * @param string $review_text The review text submitted by the user.
 * @param int $rating The rating given by the user (e.g., 1-5).
 * @param string $db_connection  (Optional) A database connection object.
 *                             If not provided, it will attempt to connect to a 'reviews' database.
 * @return bool True if the review was saved successfully, false otherwise.
 */
function saveUserReview(string $product_id, string $user_name, string $review_text, int $rating, $db_connection = null): bool
{
    // Basic validation - Adjust as needed for your application
    if (empty($product_id)) {
        error_log("Error: Product ID cannot be empty.");
        return false;
    }

    if (empty($user_name)) {
        error_log("Error: User name cannot be empty.");
        return false;
    }

    if (empty($review_text)) {
        error_log("Error: Review text cannot be empty.");
        return false;
    }

    if ($rating < 1 || $rating > 5) {
        error_log("Error: Rating must be between 1 and 5.");
        return false;
    }

    // Sanitize input (Important for security - this is a basic example)
    $review_text = htmlspecialchars($review_text); // Prevents XSS attacks
    $user_name = htmlspecialchars($user_name);

    // Database connection - If not provided, attempt to connect
    if (!$db_connection) {
        try {
            $db_connection = new PDO("mysql:host=localhost;dbname=reviews", "root", "");  // Replace credentials
            $db_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log("Database connection error: " . $e->getMessage());
            return false;
        }
    }


    // Prepare and execute the SQL query
    try {
        $stmt = $db_connection->prepare(
            "INSERT INTO reviews (product_id, user_name, review_text, rating) VALUES (:product_id, :user_name, :review_text, :rating)"
        );

        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':user_name', $user_name);
        $stmt->bindParam(':review_text', $review_text);
        $stmt->bindParam(':rating', $rating);

        $stmt->execute();

        return true;

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return false;
    }
}


/**
 * Display User Reviews for a product
 *
 * Retrieves and displays reviews associated with a given product ID.
 *
 * @param string $product_id The product ID.
 * @param PDO $db_connection  A PDO database connection object.
 * @return array An array of review objects (or an empty array if no reviews are found).
 */
function displayUserReviews(string $product_id, PDO $db_connection): array
{
    try {
        $stmt = $db_connection->prepare("SELECT * FROM reviews WHERE product_id = :product_id");
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();

        $reviews = $stmt->fetchAll(PDO::FETCH_OBJ); // Fetch as objects for easier access

        return $reviews;

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return []; // Return an empty array on error
    }
}


// Example Usage (Illustrative - replace with your actual data)
/*
$product_id = "123";
$user_name = "John Doe";
$review_text = "Great product, highly recommended!";
$rating = 4;

if (saveUserReview($product_id, $user_name, $review_text, $rating)) {
    echo "Review saved successfully!";
} else {
    echo "Failed to save review.";
}

$reviews = displayUserReviews($product_id, $db_connection);

if ($reviews) {
    echo "<br><h2>Reviews:</h2>";
    foreach ($reviews as $review) {
        echo "<strong>" . $review->user_name . "</strong>: " . $review->review_text . " (Rating: " . $review->rating . ")
";
    }
} else {
    echo "<br>No reviews found for this product.";
}
*/

?>
