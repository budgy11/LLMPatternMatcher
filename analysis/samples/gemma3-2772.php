

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a given product or item.
 *
 * @param string $product_id The unique ID of the product or item.
 * @param string $user_id The unique ID of the user submitting the review.
 * @param string $rating  The rating given by the user (e.g., 1-5).  Must be an integer.
 * @param string $comment The user's review comment.
 * @param string $db_connection  A database connection object.
 *
 * @return array An array containing:
 *   - 'success' => true if the review was successfully saved, false otherwise.
 *   - 'message' => A success or error message.
 *   - 'review_id' => The ID of the newly created review (if successful).
 */
function saveUserReview(string $product_id, string $user_id, string $rating, string $comment, PDO $db_connection) {
    // Input Validation - VERY IMPORTANT!  Prevent SQL Injection and data errors.
    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        return ['success' => false, 'message' => 'Invalid rating. Rating must be an integer between 1 and 5.', 'review_id' => null];
    }
    if (empty($comment)) {
        return ['success' => false, 'message' => 'Comment cannot be empty.', 'review_id' => null];
    }
    
    try {
        // Prepare the SQL statement
        $sql = "INSERT INTO reviews (product_id, user_id, rating, comment) 
                VALUES (:product_id, :user_id, :rating, :comment)";
        $stmt = $db_connection->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment', $comment);

        // Execute the statement
        $stmt->execute();

        // Get the last inserted ID
        $review_id = $db_connection->lastInsertId();

        return ['success' => true, 'message' => 'Review saved successfully.', 'review_id' => $review_id];

    } catch (PDOException $e) {
        // Handle database errors - critical for debugging and security
        return ['success' => false, 'message' => 'Database error: ' . $e->getMessage(), 'review_id' => null];
    }
}

/**
 * Function to retrieve reviews for a product.
 *
 * @param string $product_id The ID of the product to retrieve reviews for.
 * @param PDO $db_connection PDO database connection object.
 *
 * @return array An array of review objects, or an empty array if no reviews are found.
 */
function getReviewsForProduct(string $product_id, PDO $db_connection) {
    try {
        $sql = "SELECT * FROM reviews WHERE product_id = :product_id";
        $stmt = $db_connection->prepare($sql);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();

        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Fetch as an array of associative arrays

        return $reviews;

    } catch (PDOException $e) {
        // Handle database errors
        return [];
    }
}



// --- Example Usage (Demonstration -  Requires Database Setup) ---

// *** IMPORTANT:  Replace with your actual database credentials! ***
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';


try {
    // Create a PDO database connection
    $db_connection = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $db_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exceptions for error handling

    // --- Save a Review ---
    $product_id = '123';
    $user_id = 'user123';
    $rating = 4;
    $comment = 'Great product!  Works as advertised.';

    $result = saveUserReview($product_id, $user_id, $rating, $comment, $db_connection);

    if ($result['success']) {
        echo "Review saved successfully!  Review ID: " . $result['review_id'] . "<br>";
    } else {
        echo "Error saving review: " . $result['message'] . "<br>";
    }

    // --- Retrieve Reviews for the Product ---
    $reviews = getReviewsForProduct($product_id, $db_connection);

    if (!empty($reviews)) {
        echo "<h3>Reviews for Product " . $product_id . ":</h3>";
        echo "<ul>";
        foreach ($reviews as $review) {
            echo "<li><b>User:</b> " . $review['user_id'] . "<br>";
            echo "<b>Rating:</b> " . $review['rating'] . "<br>";
            echo "<b>Comment:</b> " . $review['comment'] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "No reviews found for product " . $product_id . ".";
    }

} catch (PDOException $e) {
    echo "Database connection error: " . $e->getMessage();
} finally {
    // Close the database connection - VERY IMPORTANT!
    $db_connection = null;
}

?>
