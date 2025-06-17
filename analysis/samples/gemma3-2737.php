

<?php

/**
 * User Review Function
 *
 * This function takes a product ID, a user ID, a rating (1-5), and a review text, 
 * and adds a new review to a database table.
 *
 * @param int    $product_id   The ID of the product being reviewed.
 * @param int    $user_id      The ID of the user submitting the review.
 * @param int    $rating       The rating given by the user (1-5).
 * @param string $review_text  The text of the review.
 * @return bool   True if the review was successfully added, false otherwise.
 */
function add_user_review(int $product_id, int $user_id, int $rating, string $review_text): bool
{
    // Database connection details (replace with your actual values)
    $db_host = 'localhost';
    $db_name = 'your_database_name';
    $db_user = 'your_database_user';
    $db_password = 'your_database_password';

    try {
        // Connect to the database
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_password);

        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare the SQL statement
        $stmt = $pdo->prepare("INSERT INTO reviews (product_id, user_id, rating, review_text) VALUES (?, ?, ?, ?)");

        // Bind parameters to the statement
        $stmt->bindParam(1, $product_id);
        $stmt->bindParam(2, $user_id);
        $stmt->bindParam(3, $rating);
        $stmt->bindParam(4, $review_text);

        // Execute the statement
        $stmt->execute();

        // Return true if successful
        return true;

    } catch (PDOException $e) {
        // Handle database errors
        error_log("Database error: " . $e->getMessage()); // Log the error
        return false;
    }
}


/**
 *  Example Usage (Demonstration - not part of the core function)
 */

// Example product ID
$product_id = 123;

// Example user ID (replace with an actual user ID)
$user_id = 456;

// Example rating
$rating = 5;

// Example review text
$review_text = "This product is amazing! I highly recommend it.";

// Add the review
if (add_user_review($product_id, $user_id, $rating, $review_text)) {
    echo "Review added successfully!";
} else {
    echo "Failed to add review.";
}



//  Example of getting reviews for a product (Illustrative - Requires a SELECT query)
// This part is not part of the add_user_review function, but demonstrates how you might retrieve them.

/*
function get_product_reviews(int $product_id)
{
    // ... (SQL SELECT query to retrieve reviews for the product)
    // ...
}
*/
?>
