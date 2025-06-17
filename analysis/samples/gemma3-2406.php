

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or item.
 *
 * @param string $product_id The unique ID of the product.
 * @param string $user_name The name of the user submitting the review.
 * @param string $review_text The text of the review.
 * @param int    $rating    The rating (e.g., 1-5 stars).
 * @param string $db_connection (Optional) - A database connection object. If not provided, 
 *                             a default connection is used.
 *
 * @return bool True on success, false on failure.
 */
function add_user_review(
    string $product_id,
    string $user_name,
    string $review_text,
    int    $rating,
    $db_connection = null
) {

    // Default database connection
    if (!$db_connection) {
        // Replace with your actual database connection setup here.
        // This is just a placeholder.  You'll need to connect to your database.
        $db_connection = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password"); 
    }

    try {
        $stmt = $db_connection->prepare(
            "INSERT INTO reviews (product_id, user_name, review_text, rating) 
             VALUES (:product_id, :user_name, :review_text, :rating)"
        );

        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':user_name', $user_name);
        $stmt->bindParam(':review_text', $review_text);
        $stmt->bindParam(':rating', $rating);

        $stmt->execute();
        return true; // Success
    } catch (PDOException $e) {
        // Handle database errors appropriately (logging, display error message to user)
        error_log("Error adding review: " . $e->getMessage());  
        return false; // Failure
    }
}


/**
 * Get User Reviews for a Product
 *
 * Retrieves all reviews for a given product.
 *
 * @param string $product_id The ID of the product.
 * @param $db_connection (Optional) - A database connection object.  If not provided, a default connection is used.
 *
 * @return array An array of review objects.  Each object will have properties like:
 *               - product_id
 *               - user_name
 *               - review_text
 *               - rating
 *               - timestamp (automatically generated)
 */
function get_user_reviews(string $product_id, $db_connection = null) {

    // Default database connection
    if (!$db_connection) {
        $db_connection = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");
    }

    try {
        $stmt = $db_connection->prepare(
            "SELECT product_id, user_name, review_text, rating, id FROM reviews WHERE product_id = :product_id"
        );
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all results as an associative array
        return $reviews;
    } catch (PDOException $e) {
        error_log("Error getting reviews: " . $e->getMessage());
        return []; // Return an empty array on error.
    }
}



// Example Usage (replace with your actual values and database setup)
$product_id = "123";
$user_name = "John Doe";
$review_text = "This product is amazing!";
$rating = 5;

if (add_user_review($product_id, $user_name, $review_text, $rating)) {
    echo "Review added successfully!
";
} else {
    echo "Failed to add review.
";
}

$reviews = get_user_reviews($product_id);

if ($reviews) {
    echo "Reviews for product " . $product_id . ":
";
    foreach ($reviews as $review) {
        echo "- " . $review['user_name'] . ": " . $review['review_text'] . " (" . $review['rating'] . " stars)
";
    }
} else {
    echo "No reviews found for product " . $product_id . "
";
}
?>
