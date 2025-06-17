

<?php

/**
 * User Review Function
 *
 * This function allows users to submit reviews for a product or service.
 * It handles input validation, sanitization, and basic data storage.
 *
 * @param string $product_id The ID of the product or service being reviewed.
 * @param string $author The name of the reviewer.
 * @param string $rating  A rating from 1 to 5.
 * @param string $comment The reviewer's comments.
 * @param string $db_connection  A database connection object.  (You'll need to establish this connection elsewhere)
 *
 * @return bool True on success, false on failure.
 */
function submit_review(string $product_id, string $author, string $rating, string $comment, $db_connection) {

    // Input Validation
    if (empty($product_id) || empty($author) || empty($rating) || empty($comment)) {
        error_log("Empty field(s) submitted for review ID: " . $product_id);
        return false;
    }

    if (!preg_match('/^[0-5][0-9]*$/', $rating)) { // Check if rating is a number between 1 and 5
        error_log("Invalid rating format: " . $rating);
        return false;
    }

    // Sanitize Input (Prevent SQL Injection) - IMPORTANT!
    $product_id = $db_connection->real_escape_string($product_id); // Use your database connection's escape function
    $author = $db_connection->real_escape_string($author);
    $comment = $db_connection->real_escape_string($comment);


    // Prepare and Execute the SQL Query
    $query = "INSERT INTO reviews (product_id, author, rating, comment) VALUES (" .
             "'$product_id', '$author', '$rating', '$comment')";

    $result = $db_connection->query($query);

    if (!$result) {
        error_log("Error submitting review: " . $db_connection->error);
        return false;
    }

    return true;
}



// Example Usage (Illustrative - requires database setup)
//  This is NOT a complete, executable example.  It demonstrates how you'd use the function.
//  You'll need a database connection, a 'reviews' table with the appropriate columns, and error handling.
/*
$db = new mysqli("localhost", "username", "password", "database_name"); // Replace with your DB details

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$product_id = "123";
$author = "John Doe";
$rating = "4";
$comment = "Great product! Would recommend.";

if (submit_review($product_id, $author, $rating, $comment, $db)) {
    echo "Review submitted successfully!";
} else {
    echo "Failed to submit review.";
}

$db->close(); // Close the database connection
*/



// Example Review Table Structure (MySQL)
/*
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    rating INT NOT NULL,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
*/


?>
