

<?php

/**
 *  User Review Function
 *
 *  This function takes a user ID, a product ID, and a review text,
 *  and saves the review to a database.
 *
 *  @param int $userId The ID of the user submitting the review.
 *  @param int $productId The ID of the product being reviewed.
 *  @param string $reviewText The text of the review.
 *  @return bool True if the review was successfully saved, false otherwise.
 */
function saveUserReview(int $userId, int $productId, string $reviewText): bool
{
    // Database connection details - Replace with your actual credentials
    $dbHost = 'localhost';
    $dbName = 'your_database_name';
    $dbUser = 'your_database_user';
    $dbPassword = 'your_database_password';

    try {
        // Create a PDO database connection
        $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPassword);

        // Set the PDO error mode to exception (to catch errors)
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare the SQL statement
        $stmt = $pdo->prepare("INSERT INTO reviews (user_id, product_id, review_text, created_at) VALUES (?, ?, ?, NOW())");

        // Bind the parameters
        $stmt->bindParam(1, $userId);
        $stmt->bindParam(2, $productId);
        $stmt->bindParam(3, $reviewText);

        // Execute the statement
        $stmt->execute();

        return true;  // Review saved successfully

    } catch (PDOException $e) {
        // Handle database errors - Log the error for debugging
        error_log("Database error: " . $e->getMessage());
        return false;  // Review not saved (due to error)
    }
}

// --- Example Usage ---

// Get user input (simulated)
$userId = 123;
$productId = 456;
$reviewText = "This is a fantastic product!  I highly recommend it.";

// Save the review
$success = saveUserReview($userId, $productId, $reviewText);

if ($success) {
    echo "Review saved successfully!";
} else {
    echo "Failed to save the review.  Please try again.";
}

?>
