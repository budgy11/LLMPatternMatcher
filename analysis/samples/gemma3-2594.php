

<?php

/**
 * This function processes user reviews, validates the data, and stores
 * them in a database (or an array for a simpler implementation).
 *
 * @param array $reviewData  An associative array containing review data:
 *                           - 'user': User's name or ID
 *                           - 'rating': Integer from 1 to 5
 *                           - 'comment': User's comment (optional)
 * @param string $dbConnection  (Optional) Database connection object.
 *                               If not provided, the function will store
 *                               the review in an in-memory array.
 *
 * @return bool  True if the review was successfully processed, false otherwise.
 */
function processUserReview(array $reviewData, string $dbConnection = null)
{
    // 1. Input Validation
    $errors = [];

    if (!isset($reviewData['user']) || empty($reviewData['user'])) {
        $errors[] = 'User name/ID is required.';
    }

    if (!isset($reviewData['rating']) || !is_int($reviewData['rating']) || $reviewData['rating'] < 1 || $reviewData['rating'] > 5) {
        $errors[] = 'Rating must be an integer between 1 and 5.';
    }

    if (!isset($reviewData['comment']) || empty($reviewData['comment'])) {
        // Comment is optional, so we don't require it.
    }


    // 2. Data Sanitization (IMPORTANT: Sanitize to prevent SQL Injection)
    $user = trim($reviewData['user']);
    $rating = $reviewData['rating'];
    $comment = trim($reviewData['comment'] ?? '');  // Use null coalesce operator

    // 3. Store the Review

    if (empty($errors)) {
        // Store in Database (Example using PDO)
        try {
            $stmt = $dbConnection->prepare(
                "INSERT INTO reviews (user, rating, comment) VALUES (:user, :rating, :comment)"
            );
            $stmt->bindParam(':user', $user);
            $stmt->bindParam(':rating', $rating);
            $stmt->bindParam(':comment', $comment);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            // Handle database errors (logging is highly recommended)
            error_log("Error processing review: " . $e->getMessage());
            return false;
        }

    } else {
        // Return false if there were errors
        return false;
    }
}


// Example Usage (Illustrative - Replace with your actual database connection)
// Create a dummy database connection for demonstration
$db = new PDO('mysql:host=localhost;dbname=my_database', 'username', 'password');


// Sample review data
$review1 = ['user' => 'John Doe', 'rating' => 4, 'comment' => 'Great product!'];
$review2 = ['user' => 'Jane Smith', 'rating' => 5];
$review3 = ['user' => 'Peter Jones', 'rating' => 2, 'comment' => 'Not so great...'];


// Process the reviews
$result1 = processUserReview($review1, $db);
$result2 = processUserReview($review2, $db);
$result3 = processUserReview($review3, $db);

if ($result1) {
    echo "Review 1 processed successfully!
";
} else {
    echo "Review 1 processing failed.
";
}

if ($result2) {
    echo "Review 2 processed successfully!
";
} else {
    echo "Review 2 processing failed.
";
}

if ($result3) {
    echo "Review 3 processed successfully!
";
} else {
    echo "Review 3 processing failed.
";
}


// Example storing in an array (for a simpler version)
$reviews = [];

$result4 = processUserReview(['user' => 'Alice', 'rating' => 3], null); // No DB connection

if ($result4) {
    $reviews[] = ['user' => 'Alice', 'rating' => 3];
    echo "Review 4 processed and added to array.
";
} else {
    echo "Review 4 processing failed.
";
}

print_r($reviews);  // Output the array of reviews
?>
