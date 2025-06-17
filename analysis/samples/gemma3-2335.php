

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or service.
 * It includes basic validation and sanitization.
 *
 * @param string $product_id The ID of the product or service being reviewed.
 * @param string $username The username of the reviewer.
 * @param string $rating A rating from 1 to 5 (inclusive).
 * @param string $comment The user's review comment.
 * @return bool True if the review was successfully saved, false otherwise.
 */
function save_review(string $product_id, string $username, string $rating, string $comment): bool
{
    // Input Validation - Basic
    if (empty($product_id) || empty($username) || empty($rating) || empty($comment)) {
        error_log("Invalid review data: Product ID, username, rating, and comment cannot be empty.");
        return false;
    }

    if (!preg_match('/^[a-zA-Z0-9\s]+$/', $username)) {
        error_log("Invalid username:  Username must contain only alphanumeric characters and spaces.");
        return false;
    }

    if ($rating < 1 || $rating > 5) {
        error_log("Invalid rating: Rating must be between 1 and 5.");
        return false;
    }

    // Sanitize Input -  Very basic.  More robust sanitization is recommended for production.
    $product_id = trim($product_id);
    $username = trim($username);
    $rating = (int)$rating; // Cast to integer for numerical comparison
    $comment = strip_tags($comment); // Remove HTML tags
    $comment = trim($comment);


    // Database Interaction (Example - Replace with your actual database logic)
    // This is a placeholder and should be adapted to your database setup.
    try {
        // Connect to your database (replace with your connection details)
        // $pdo = new PDO("mysql:host=localhost;dbname=your_database", "username", "password");

        // Prepare the SQL query
        $sql = "INSERT INTO reviews (product_id, username, rating, comment) 
                VALUES (:product_id, :username, :rating, :comment)";

        // Prepare the statement
        $stmt = $pdo->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment', $comment);

        // Execute the query
        $stmt->execute();

        // Get the last inserted ID (useful for display)
        $review_id = $pdo->lastInsertId();

        return true;

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return false;
    }
}


// Example Usage (For testing - remove or adjust for your application)
// Create a dummy database connection for demonstration
$pdo = null;

try {
    $pdo = new PDO("mysql:host=localhost;dbname=test_reviews", "root", ""); // Replace with your details
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage() . "
";
    exit;
}

// Test case 1 - Successful save
$result = save_review("123", "JohnDoe", 4, "Great product, highly recommended!");
if ($result) {
    echo "Review saved successfully!
";
} else {
    echo "Review failed to save.
";
}

// Test case 2 - Invalid rating
$result = save_review("456", "JaneSmith", 6, "Excellent!");
if ($result) {
    echo "Review saved successfully!
";
} else {
    echo "Review failed to save (invalid rating).
";
}

// Test case 3 - Empty input
$result = save_review("", "PeterPan", 3, "Superb!");
if ($result) {
    echo "Review saved successfully!
";
} else {
    echo "Review failed to save (empty data).
";
}
?>
