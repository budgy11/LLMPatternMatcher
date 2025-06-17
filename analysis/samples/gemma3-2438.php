

<?php

/**
 * User Review Function
 * 
 * This function allows you to process and store user reviews.
 * It handles input validation, sanitization, and basic formatting.
 *
 * @param string $username The username of the reviewer.
 * @param string $rating A numeric rating (e.g., 1-5).
 * @param string $comment The user's review comment.
 * @param int $product_id The ID of the product being reviewed.
 * 
 * @return bool|string  Returns true on success, or an error message string on failure.
 */
function processReview(string $username, string $rating, string $comment, int $product_id) {
    // Input Validation and Sanitization
    if (empty($username)) {
        return "Error: Username cannot be empty.";
    }
    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        return "Error: Rating must be a number between 1 and 5.";
    }
    if (empty($comment)) {
        return "Error: Comment cannot be empty.";
    }
    if (!is_int($product_id) || $product_id <= 0) {
        return "Error: Product ID must be a positive integer.";
    }

    // Sanitize input (more robust sanitization would be needed in a real application)
    $username = trim($username);
    $comment = trim($comment);

    // Format the review (example - you can customize this)
    $formatted_review = "Review by: " . $username . "<br>" .
                         "Rating: " . $rating . "<br>" .
                         "Comment: " . $comment . "<br>";

    // Save the review to a database (example)
    // Replace this with your actual database code
    try {
        // Assuming you have a database connection established
        // (e.g., using PDO or mysqli)
        // $db = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
        // $stmt = $db->prepare("INSERT INTO reviews (product_id, username, rating, comment) VALUES (?, ?, ?, ?)");
        // $stmt->execute([$product_id, $username, $rating, $comment]);

        // Example placeholder -  Replace this with your database interaction
        $reviews = json_encode([
          "username" => $username,
          "rating" => $rating,
          "comment" => $comment,
          "product_id" => $product_id
        ]);
        
        return true; // Indicate success
    } catch (Exception $e) {
        return "Error saving review: " . $e->getMessage();
    }
}


// Example usage:
$username = "JohnDoe";
$rating = 4;
$comment = "Great product!  I really enjoyed it.";
$product_id = 123;

$result = processReview($username, $rating, $comment, $product_id);

if ($result === true) {
    echo "Review submitted successfully!";
} else {
    echo "Error: " . $result;
}

?>
