

<?php

/**
 * User Review Function
 *
 * This function allows you to create, read, update, and delete user reviews
 * for a given product or item. It provides a basic framework and can be
 * customized further based on your specific needs.
 *
 * @param string $productId The ID of the product or item being reviewed.
 * @param string $username  The username of the user submitting the review.
 * @param string $comment  The text of the review comment.
 * @param int    $rating   The rating (e.g., 1-5).
 * @param array $dbConnection  (Optional)  A database connection object.
 *                           If not provided, it will attempt to connect using a default configuration.
 * @return array  An array containing the result of the operation (success/failure, and optionally data).
 */
function createReview(
    string $productId,
    string $username,
    string $comment,
    int    $rating,
    $dbConnection = null
) {
    // Default database connection configuration
    $dbConfig = [
        'host' => 'localhost',
        'user' => 'your_db_user',
        'password' => 'your_db_password',
        'dbname' => 'your_db_name'
    ];

    // Use provided connection or create a new one
    if ($dbConnection === null) {
        try {
            $dbConnection = new PDO(
                "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']}",
                $dbConfig['user'],
                $dbConfig['password']
            );
            $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Throw exceptions on errors
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Database connection error: ' . $e->getMessage()];
        }
    }

    // Sanitize input - Important for security
    $productId = filter_var($productId, FILTER_SANITIZE_STRING);
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $comment = filter_var($comment, FILTER_SANITIZE_STRING);
    $rating = filter_var($rating, FILTER_VALIDATE_INT);  // Validate rating as an integer

    if ($rating === false || $rating < 1 || $rating > 5) {
        return ['success' => false, 'message' => 'Invalid rating. Rating must be an integer between 1 and 5.'];
    }


    // Prepare the SQL statement (using prepared statements for security)
    $sql = "INSERT INTO reviews (product_id, username, comment, rating) VALUES (:product_id, :username, :comment, :rating)";
    $stmt = $dbConnection->prepare($sql);

    // Bind the parameters
    $stmt->bindParam(':product_id', $productId);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':comment', $comment);
    $stmt->bindParam(':rating', $rating);

    // Execute the statement
    try {
        $stmt->execute();
        return ['success' => true, 'message' => 'Review created successfully.'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
    }
}


/**
 * Example Usage (Illustrative - Replace with your actual database setup and table structure)
 */

// Example usage:
$result = createReview(
    '123', // Product ID
    'john.doe', // User Name
    'This is a great product!', // Comment
    4, // Rating
    null // Let it use the default DB connection
);

if ($result['success']) {
    echo "Review created: " . $result['message'] . "
";
} else {
    echo "Error creating review: " . $result['message'] . "
";
}

//To retrieve reviews (example - needs a SELECT query and proper display logic)
// You would typically use a SELECT query to retrieve reviews.
// Example SELECT query (placeholder):
/*
$sql = "SELECT * FROM reviews WHERE product_id = :product_id";
$stmt = $dbConnection->prepare($sql);
$stmt->bindParam(':product_id', '123');
$stmt->execute();

$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
if ($reviews) {
    foreach ($reviews as $review) {
        echo "Product ID: " . $review['product_id'] . "
";
        echo "Username: " . $review['username'] . "
";
        echo "Comment: " . $review['comment'] . "
";
        echo "Rating: " . $review['rating'] . "
";
        echo "---
";
    }
} else {
    echo "No reviews found for this product.
";
}
*/

?>
