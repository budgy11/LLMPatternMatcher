

<?php

/**
 * Function to create a user review.
 *
 * @param string $username The username of the reviewer.
 * @param string $rating  The rating (e.g., 1-5).
 * @param string $comment The reviewer's comments.
 * @param string $product_id The ID of the product being reviewed.
 *
 * @return bool True on success, false on failure.
 */
function createReview(string $username, string $rating, string $comment, string $product_id) {

    // Input validation (Important for security and data integrity)
    if (empty($username) || empty($rating) || empty($comment) || empty($product_id)) {
        error_log("Error: Missing required fields for review creation."); // Log for debugging
        return false;
    }

    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        error_log("Error: Invalid rating. Rating must be a number between 1 and 5.");
        return false;
    }

    // Database connection (Replace with your actual database connection)
    $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password"); // Replace placeholders

    try {
        // Prepare the SQL query (Important for security - prevents SQL injection)
        $stmt = $db->prepare("INSERT INTO reviews (user_name, product_id, rating, comment) VALUES (?, ?, ?, ?)");

        // Bind the parameters (Safely insert the data into the query)
        $stmt->bindParam(1, $username);
        $stmt->bindParam(2, $product_id);
        $stmt->bindParam(3, $rating);
        $stmt->bindParam(4, $comment);

        // Execute the query
        $stmt->execute();

        return true; // Success!

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage()); // Log the error
        return false;
    }
}

// Example Usage:
$username = "JohnDoe";
$rating = 4;
$comment = "Great product!  Works as expected.";
$product_id = "123";

if (createReview($username, $rating, $comment, $product_id)) {
    echo "Review created successfully!";
} else {
    echo "Failed to create review.";
}


// Example to demonstrate error handling:
$username = ""; // Empty username
$rating = 3;
$comment = "Test review";
$product_id = "456";

if (createReview($username, $rating, $comment, $product_id)) {
    echo "Review created successfully!";
} else {
    echo "Failed to create review (due to error).";
}

?>
