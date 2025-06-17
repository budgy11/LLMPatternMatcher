

<?php

/**
 * User Review Function - Basic Implementation
 *
 * This function takes a user ID, a review text, and an optional rating,
 * and saves the review to a database.
 *
 * @param int    $userId      The ID of the user submitting the review.
 * @param string $reviewText  The text of the review.
 * @param int    $rating      (Optional) The rating given for the review (e.g., 1-5).
 *                           Defaults to 0.
 * @return bool  True if the review was successfully saved, false otherwise.
 */
function saveUserReview(int $userId, string $reviewText, int $rating = 0) {
    // --- Database Connection (Replace with your actual connection details) ---
    // This is a placeholder.  Use PDO, MySQLi, or another database connector.
    $dbHost = 'localhost';
    $dbName = 'your_database_name';
    $dbUser = 'your_username';
    $dbPassword = 'your_password';

    try {
        $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        error_log("Database connection error: " . $e->getMessage()); // Log the error
        return false;
    }

    // --- Input Validation & Sanitization (IMPORTANT!) ---
    // Validate the input to prevent SQL injection and other issues.
    $reviewText = trim($reviewText); // Remove leading/trailing whitespace
    if (empty($reviewText)) {
        error_log("Empty review text.");  //Log empty review
        return false;
    }
    $rating = (int)$rating; // Ensure rating is an integer.
    if ($rating < 1 || $rating > 5) {
        error_log("Invalid rating ($rating). Must be between 1 and 5.");
        return false;
    }


    // --- SQL Query ---
    $sql = "INSERT INTO reviews (user_id, review_text, rating) VALUES (:userId, :reviewText, :rating)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':userId', $userId);
    $stmt->bindParam(':reviewText', $reviewText);
    $stmt->bindParam(':rating', $rating);

    try {
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        error_log("Database error saving review: " . $e->getMessage());
        return false;
    }
}

// --- Example Usage ---
// You would typically get this data from a form submission.

// Example 1: Successful save
$userId = 123;
$review = "This product is amazing!  I highly recommend it.";
$rating = 5;

if (saveUserReview($userId, $review, $rating)) {
    echo "Review saved successfully!";
} else {
    echo "Failed to save review.";
}

// Example 2: Save with default rating
$userId = 456;
$review = "Great service!";
if (saveUserReview($userId, $review)) {
    echo "Review saved successfully (default rating)!";
} else {
    echo "Failed to save review (default rating).";
}


//  ---  Dummy Review Table Schema (for testing) ---
/*
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    review_text TEXT NOT NULL,
    rating INT NOT NULL
);
*/
?>
