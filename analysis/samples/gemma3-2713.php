

<?php

/**
 * User Review Function
 *
 * This function allows you to process user reviews, sanitize input,
 * and store them in a database (or other storage mechanism).
 *
 * @param string $username The user submitting the review.
 * @param string $reviewText The text of the review.
 * @param string $rating A numerical rating (e.g., 1-5).
 * @param string $itemId The ID of the item the review is for.
 * @param string $databaseConnection A database connection object (e.g., mysqli).
 *
 * @return bool True on success, false on failure.
 */
function processUserReview(string $username, string $reviewText, string $rating, string $itemId, $databaseConnection)
{
    // **Input Validation & Sanitization - VERY IMPORTANT**
    // Prevent SQL injection and other vulnerabilities.

    // 1. Validate the rating
    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        error_log("Invalid rating provided: " . $rating);
        return false;
    }

    // 2. Sanitize the review text (Escape special characters)
    $reviewText = htmlspecialchars($reviewText);

    // **Database Interaction**
    try {
        // Prepare the SQL statement to prevent SQL injection.
        $sql = "INSERT INTO reviews (user_name, review_text, rating, item_id)
                VALUES (?, ?, ?, ?)";

        // Use prepared statements for security.
        $stmt = $databaseConnection->prepare($sql);

        if ($stmt === false) {
            throw new Exception("Error preparing statement.");
        }

        $stmt->bind_param("sss", $username, $reviewText, $rating);

        if (!$stmt->execute()) {
            throw new Exception("Error executing statement: " . $stmt->error);
        }

        $stmt->close();  // Important to close the statement
        return true;

    } catch (Exception $e) {
        error_log("Error processing review: " . $e->getMessage());
        return false;
    }
}


// **Example Usage (Illustrative - Replace with your actual database connection)**
//  --- DO NOT USE THIS DIRECTLY - MODIFY TO FIT YOUR SETUP ---

/*
$db_host = "localhost";
$db_user = "your_user";
$db_password = "your_password";
$db_name = "your_database";

$connection = new mysqli($db_host, $db_user, $db_password, $db_name);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}


$username = "JohnDoe";
$reviewText = "This product is amazing!  I love it.";
$rating = 4;
$itemId = 123;

if (processUserReview($username, $reviewText, $rating, $itemId, $connection)) {
    echo "Review submitted successfully!";
} else {
    echo "Error submitting review.";
}

$connection->close(); // Close the database connection
*/

?>
