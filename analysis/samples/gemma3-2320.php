

<?php

/**
 * User Review Function
 *
 * This function takes a user ID, a product ID, and a review text,
 * and stores the review in a database.  It also includes basic
 * input validation and error handling.
 *
 * @param int    $userId       The ID of the user submitting the review.
 * @param int    $productId    The ID of the product being reviewed.
 * @param string $reviewText   The text of the review submitted by the user.
 * @return bool   True on success, false on failure.
 */
function storeUserReview(int $userId, int $productId, string $reviewText): bool
{
    // Input Validation - Important for security and data integrity
    if (!$userId || !$productId || !$reviewText) {
        error_log("Missing required fields in user review."); // Log for debugging
        return false;
    }

    if (!is_numeric($userId) || !is_numeric($productId)) {
        error_log("Invalid user or product ID provided.");
        return false;
    }

    if (empty($reviewText)) {
        error_log("Review text cannot be empty.");
        return false;
    }

    // Sanitize input - Essential for preventing XSS attacks
    $reviewText = htmlspecialchars($reviewText, ENT_QUOTES, 'UTF-8'); 

    // Database connection details (replace with your actual credentials)
    $dbHost = 'localhost';
    $dbUser = 'your_db_user';
    $dbPass = 'your_db_password';
    $dbName = 'your_db_name';

    try {
        // Connect to the database
        $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // For better error handling

        // Prepare the SQL statement
        $stmt = $pdo->prepare("INSERT INTO reviews (userId, productId, reviewText, reviewDate) VALUES (?, ?, ?, NOW())"); 

        // Bind the parameters - prevents SQL injection
        $stmt->bindParam(1, $userId);
        $stmt->bindParam(2, $productId);
        $stmt->bindParam(3, $reviewText);

        // Execute the statement
        $stmt->execute();

        // Return true on success
        return true;

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());  // Log the error
        return false;
    }
}


// Example Usage:
// $success = storeUserReview(123, 456, "This product is great!");
// if ($success) {
//     echo "Review submitted successfully!";
// } else {
//     echo "Failed to submit review.";
// }

?>
