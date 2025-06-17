

<?php

/**
 * User Review Function
 *
 * This function allows you to handle user reviews, including saving them to a database
 * or other storage method.  It includes basic validation and error handling.
 *
 * @param string $username       The name of the user submitting the review.
 * @param string $review_text    The text of the review.
 * @param string $product_id     The ID of the product the review is for.
 * @param string $rating         The rating (e.g., 1-5).
 *
 * @return bool True if the review was successfully saved, false otherwise.
 */
function saveUserReview(string $username, string $review_text, string $product_id, string $rating): bool
{
    // **Input Validation (Important!)**
    if (empty($username)) {
        error_log("Invalid username provided.");
        return false;
    }
    if (empty($review_text)) {
        error_log("Review text cannot be empty.");
        return false;
    }
    if (empty($product_id)) {
        error_log("Product ID cannot be empty.");
        return false;
    }
    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        error_log("Invalid rating.  Please enter a number between 1 and 5.");
        return false;
    }

    // **Here's where you would connect to your database and save the review.**
    // This is a simplified example using a hypothetical 'reviews' table.
    // Adapt this to your database schema and technology.

    try {
        // Database connection (replace with your actual connection details)
        // $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");

        // Assuming you have a 'reviews' table with columns:
        // - id (INT, PRIMARY KEY, AUTO_INCREMENT)
        // - username (VARCHAR)
        // - product_id (INT)
        // - review_text (TEXT)
        // - rating (INT)
        // - created_at (TIMESTAMP)

        $db = new PDO("mysql:host=localhost;dbname=reviews_db", "user", "password");

        // Prepare the SQL statement
        $stmt = $db->prepare(
            "INSERT INTO reviews (username, product_id, review_text, rating, created_at)
             VALUES (:username, :product_id, :review_text, :rating, NOW())"
        );

        // Bind the parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':review_text', $review_text);
        $stmt->bindParam(':rating', $rating);

        // Execute the statement
        $stmt->execute();

        return true; // Review saved successfully

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return false; // Error saving review
    }
}


// **Example Usage:**
$username = "JohnDoe";
$reviewText = "This product is amazing!  I highly recommend it.";
$productId = 123;
$rating = 5;

if (saveUserReview($username, $reviewText, $productId, $rating)) {
    echo "Review submitted successfully!";
} else {
    echo "Error submitting review.";
}

?>
