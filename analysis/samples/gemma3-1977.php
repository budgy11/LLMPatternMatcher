

<?php

/**
 * User Review Function
 *
 * This function takes a user's review, rating, and optionally, their ID,
 * and stores it in a database.
 *
 * @param string $review The user's review text.
 * @param int $rating The user's rating (e.g., 1-5 stars).
 * @param int|null $userId The user's ID (optional). If null, a new user is assumed.
 * @return bool True if the review was successfully saved, false otherwise.
 */
function saveUserReview(string $review, int $rating, ?int $userId = null): bool
{
    // **Database Connection & Configuration**
    // Replace these with your actual database credentials.
    $host = 'localhost';
    $username = 'your_db_username';
    $password = 'your_db_password';
    $database = 'your_db_name';

    // **Error Handling and Validation**
    if (empty($review)) {
        error_log("Error: Review cannot be empty.");
        return false;
    }

    if ($rating < 1 || $rating > 5) {
        error_log("Error: Rating must be between 1 and 5.");
        return false;
    }

    // **Database Interaction**
    try {
        // Establish connection
        $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable error reporting

        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO reviews (user_id, review, rating) VALUES (:userId, :review, :rating)");

        // Bind parameters
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':review', $review);
        $stmt->bindParam(':rating', $rating);

        // Execute the statement
        $stmt->execute();

        // Close the connection
        $conn = null;

        return true;

    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage()); // Log the error for debugging
        return false;
    }
}


// **Example Usage:**

// Save a review from a logged-in user (assuming user ID is 123):
$reviewResult = saveUserReview("This product is amazing!", 5, 123);
if ($reviewResult) {
    echo "Review saved successfully!";
} else {
    echo "Failed to save review.";
}


// Save a review from a guest user (will create a new user):
$reviewResult = saveUserReview("Great service!", 4);
if ($reviewResult) {
    echo "Review saved successfully (new user)!";
} else {
    echo "Failed to save review (new user).";
}

?>
