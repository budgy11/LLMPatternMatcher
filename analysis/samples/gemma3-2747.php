

<?php

/**
 * User Review Function
 *
 * This function takes a user's review and associated data (like product ID, rating, etc.)
 * and stores it in a database.
 *
 * @param int $productId The ID of the product being reviewed.
 * @param string $reviewText The text of the user's review.
 * @param int $rating       The user's rating (e.g., 1-5).
 * @param string $userId     (Optional) The ID of the user leaving the review.
 * @param array  $extraData  (Optional)  Any other relevant data to store with the review.
 *
 * @return bool True if the review was successfully saved, false otherwise.
 */
function saveUserReview(
    int $productId,
    string $reviewText,
    int $rating,
    string $userId = null,
    array $extraData = []
) {
    // **IMPORTANT:  Replace this with your actual database connection and query logic**
    // This is a placeholder example.

    // Database connection (Replace with your connection details)
    $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');

    try {
        // 1. Sanitize input data - VERY IMPORTANT to prevent SQL injection
        $reviewText = trim($reviewText);  // Remove leading/trailing whitespace
        $reviewText = htmlspecialchars($reviewText); // Escape HTML entities
        $rating = intval($rating); // Ensure rating is an integer

        // 2. Build the SQL query
        $sql = "INSERT INTO reviews (product_id, user_id, review_text, rating, created_at) VALUES (:product_id, :user_id, :review_text, :rating, NOW())";

        // 3. Prepare the statement
        $stmt = $db->prepare($sql);

        // 4. Bind the parameters
        $stmt->bindParam(':product_id', $productId);
        if ($userId) {
            $stmt->bindParam(':user_id', $userId);
        }
        $stmt->bindParam(':review_text', $reviewText);
        $stmt->bindParam(':rating', $rating);

        // 5. Execute the statement
        $result = $stmt->execute();

        // 6. Check for success
        if ($result) {
            return true;
        } else {
            // Log the error for debugging.  Very important!
            error_log("Error saving review: " . $db->errorInfo()[2]);
            return false;
        }

    } catch (PDOException $e) {
        // Handle database connection errors
        error_log("Database error: " . $e->getMessage());
        return false;
    }
}


// **Example Usage:**

// Let's assume a product ID of 123 and a user ID of 456 (if available)
$productId = 123;
$reviewText = "This product is amazing!  It exceeded my expectations.";
$rating = 5;
$userId = 456;  // Optional user ID

if (saveUserReview($productId, $reviewText, $rating, $userId)) {
    echo "Review successfully saved!";
} else {
    echo "Failed to save review.";
}


?>
