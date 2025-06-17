

<?php

/**
 * User Review Function
 *
 * This function processes user reviews, sanitizes the input,
 * stores it in a database (or an array for testing),
 * and potentially performs some basic validation.
 *
 * @param string $name The user's name.
 * @param string $comment The user's review comment.
 * @param string $rating A numeric rating (e.g., 1-5).
 * @param string $email The user's email address (optional, for confirmation).
 * @param string $db_connection (optional) - Connection to your database.
 * @return array|string  Returns an array of review data or an error message string.
 */
function process_user_review(string $name, string $comment, string $rating, string $email = null, $db_connection = null)
{
    // Sanitize Input - IMPORTANT for security!
    $name = htmlspecialchars(trim($name));
    $comment = htmlspecialchars(trim($comment));
    $rating = (int) $rating; // Cast to integer for validation.  Handles invalid input gracefully.

    // Validation - Basic examples
    if (empty($name) || empty($comment)) {
        return "Error: Name and comment cannot be empty.";
    }
    if ($rating < 1 || $rating > 5) {
        return "Error: Rating must be between 1 and 5.";
    }

    // Create Review Data (for database storage or temporary storage)
    $review_data = [
        'name' => $name,
        'comment' => $comment,
        'rating' => $rating,
        'date' => date('Y-m-d H:i:s'), // Add a timestamp for each review
        'email' => $email,
    ];

    // --- Database Interaction (Example - Adjust to your database setup) ---
    if ($db_connection) {
        try {
            // Assuming you have a database connection object called $db_connection
            //  This is a very basic example - adapt to your database schema.
            //  Error handling is crucial in a production environment.

            // Example using prepared statements (highly recommended)
            $sql = "INSERT INTO reviews (name, comment, rating, date, email) VALUES (?, ?, ?, ?, ?)";
            $stmt = $db_connection->prepare($sql);
            $stmt->execute([$name, $comment, $rating, date('Y-m-d H:i:s'), $email]);

            return ['success' => true, 'review_id' => $db_connection->lastInsertId()];  // Return review ID

        } catch (Exception $e) {
            return "Error: Database error - " . $e->getMessage();
        }

    } else {
        // Store in an array (for testing without a database)
        // You could also use a file, cache, etc.
        //  Example:
        //  $reviews[] = $review_data;
        return $review_data; // Return review data directly
    }
}



// --- Example Usage ---

// 1. Testing without a database:
$review = process_user_review("John Doe", "Great product!", 5);
print_r($review);

// 2.  With a database connection (replace with your actual connection details)
//  This assumes a database connection named `$db_connection`
//  You'll need to establish the connection first.
// $db_connection = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');

// $review = process_user_review("Jane Smith", "Could be better.", 3, "jane.smith@example.com");

// if ($review === ['success' => true]) {
//     echo "Review submitted successfully! Review ID: " . $review['review_id'] . "
";
// } else {
//     echo "Error: " . $review;
// }


?>
