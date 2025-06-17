

<?php

/**
 * Function to create and store user reviews.
 *
 * This function handles:
 * - Validating input data.
 * - Generating a unique review ID.
 * - Storing the review in a database (using a basic example, you'd adapt this to your database setup).
 * - Returning the review ID.
 *
 * @param string $product_id The ID of the product the review is for.
 * @param string $user_id The ID of the user leaving the review.
 * @string $rating The rating (e.g., 1-5).
 * @string $comment The user's review comment.
 * @return int|false The ID of the newly created review on success, or false on failure.
 */
function createReview(string $product_id, string $user_id, string $rating, string $comment)
{
    // **Input Validation - Important!**
    if (empty($product_id) || empty($user_id) || empty($rating) || empty($comment)) {
        error_log("Error: Missing review data.  product_id, user_id, rating, and comment are required."); // Log the error for debugging.
        return false;
    }

    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        error_log("Error: Invalid rating.  Rating must be a number between 1 and 5.");
        return false;
    }

    // **Database Interaction (Example - Adapt to your DB setup)**
    try {
        // Assuming you have a database connection established as $db
        $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password"); // Replace with your database credentials
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling for errors

        // Prepare the SQL statement
        $stmt = $db->prepare("INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");

        // Bind the parameters
        $stmt->bindParam(1, $product_id);
        $stmt->bindParam(2, $user_id);
        $stmt->bindParam(3, $rating);
        $stmt->bindParam(4, $comment);

        // Execute the statement
        $stmt->execute();

        // Get the last inserted ID
        $review_id = $db->lastInsertId();

        return (int)$review_id; // Cast to integer for consistency

    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage()); // Log the full error message
        return false;
    }
}


/**
 * Example Usage:
 */

// Sample Review Data
$product_id = 'product123';
$user_id = 'user456';
$rating = 4;
$comment = 'Great product!  Highly recommend.';

// Create the review
$review_id = createReview($product_id, $user_id, $rating, $comment);

if ($review_id) {
    echo "Review created successfully! Review ID: " . $review_id . "
";
} else {
    echo "Error creating review.
";
}

// Example of invalid input
$invalid_review_id = createReview("", "user1", 3, "Test comment");
if($invalid_review_id){
    echo "Invalid review created successfully! Review ID: " . $invalid_review_id . "
";
}else{
    echo "Error creating review.
";
}
?>
