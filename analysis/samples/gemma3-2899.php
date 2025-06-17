

<?php

/**
 *  User Review Function
 *
 *  This function allows you to create, read, update, and delete user reviews.
 *
 *  @param string $productId The ID of the product the review is for.
 *  @param string $username The username of the reviewer.
 *  @param string $rating  The rating given (e.g., 1-5).  Validate this in your application.
 *  @param string $comment The review text.
 *  @param string $dbHost The database host.
 *  @param string $dbName The database name.
 *  @param string $dbUser The database username.
 *  @param string $dbPassword The database password.
 *
 *  @return bool True on success, false on failure.
 */
function create_user_review(
    $productId,
    $username,
    $rating,
    $comment,
    $dbHost,
    $dbName,
    $dbUser,
    $dbPassword
) {
    // Validate inputs -  CRUCIAL for security!  Expand this as needed.
    if (empty($productId) || empty($username) || empty($rating) || empty($comment)) {
        error_log("Missing required review fields."); // Log for debugging
        return false;
    }

    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        error_log("Invalid rating provided. Rating must be a number between 1 and 5.");
        return false;
    }

    // Database connection (using PDO - recommended)
    try {
        $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Throw exceptions on errors
    } catch (PDOException $e) {
        error_log("Database connection error: " . $e->getMessage());
        return false;
    }

    // Prepare and execute the SQL statement
    $sql = "INSERT INTO reviews (product_id, username, rating, comment) VALUES (:product_id, :username, :rating, :comment)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':product_id', $productId);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':comment', $comment);

    if ($stmt->execute()) {
        return true;
    } else {
        error_log("Error executing review insert query: " . print_r($stmt->errorInfo(), true)); // Detailed error logging
        return false;
    }
}

// Example Usage (Illustrative - replace with your data)
// $product_id = '123';
// $username = 'john.doe';
// $rating = 4;
// $comment = 'Great product!  Easy to use.';
//
// if (create_user_review($product_id, $username, $rating, $comment, 'localhost', 'my_database', 'my_user', 'my_password')) {
//     echo "Review created successfully!";
// } else {
//     echo "Failed to create review.";
// }


/*  Example Database Table Structure (MySQL)
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL,
    rating INT NOT NULL,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
*/
?>
