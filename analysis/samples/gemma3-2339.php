

<?php

/**
 * User Review Function
 *
 * This function allows you to store user reviews with various options.
 *
 * @param string $productId The ID of the product being reviewed.
 * @param string $username The username of the reviewer.
 * @param string $rating  The rating given by the user (e.g., 1-5).
 * @param string $comment  The user's comment on the product.
 * @param string $databaseConnection  A valid database connection object.
 *
 * @return bool True on success, false on failure.
 */
function storeUserReview(
    string $productId,
    string $username,
    string $rating,
    string $comment,
    $databaseConnection // Use $databaseConnection instead of passing it as a string
) {
    try {
        // Validate input (basic - expand for more robust validation)
        if (empty($productId) || empty($username) || empty($rating) || empty($comment)) {
            throw new Exception("All fields are required.");
        }

        if (!preg_match('/^[0-5][0-9]*$/', $rating)) {
            throw new Exception("Rating must be a number between 1 and 5.");
        }

        // Prepare the SQL statement
        $sql = "INSERT INTO reviews (product_id, user_name, rating, comment) 
                VALUES (?, ?, ?, ?)";

        // Prepare the statement
        $stmt = $databaseConnection->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("ssis", $productId, $username, $rating, $comment);

        // Execute the statement
        $stmt->execute();

        // Close the statement
        $stmt->close();

        return true;

    } catch (Exception $e) {
        // Handle errors (logging is recommended)
        error_log("Error storing review: " . $e->getMessage());  // Log the error
        return false;
    }
}

// Example Usage (assuming you have a database connection named $conn)

// $productId = "123";
// $username = "JohnDoe";
// $rating = "4";
// $comment = "Great product! Highly recommended.";

// if (storeUserReview($productId, $username, $rating, $comment, $conn)) {
//     echo "Review successfully stored!";
// } else {
//     echo "Failed to store review.";
// }

?>
