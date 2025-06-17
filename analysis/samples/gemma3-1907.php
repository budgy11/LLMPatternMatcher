

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or service.
 *
 * @param string $product_id The unique ID of the product or service being reviewed.
 * @param string $user_name The name of the user submitting the review.
 * @param string $review_text The text content of the review.
 * @param int    $rating    The rating given by the user (e.g., 1-5).
 * @param string $db_connection (Optional) - A database connection object. If not provided,
 *                                     the function will create a temporary connection.
 *
 * @return array An array containing:
 *              - 'success': True if the review was successfully saved, False otherwise.
 *              - 'message': A message describing the outcome of the operation.
 *              - 'review_id': The ID of the newly created review, or the ID of the updated review.
 */
function saveReview(
    string $product_id,
    string $user_name,
    string $review_text,
    int    $rating,
    string $db_connection = null
): array {

    // Validate inputs (simple example, add more robust validation as needed)
    if (empty($product_id) || empty($user_name) || empty($review_text) || $rating < 1 || $rating > 5) {
        return [
            'success' => false,
            'message' => 'Invalid review data. Please check your input values.',
        ];
    }

    // Database connection (if not provided, create a temporary one)
    if ($db_connection === null) {
        // Replace with your actual database connection setup here
        $db_connection = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');
    }


    try {
        // Prepare the SQL statement
        $sql = "INSERT INTO reviews (product_id, user_name, review_text, rating)
                VALUES (:product_id, :user_name, :review_text, :rating)";

        $stmt = $db_connection->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':user_name', $user_name);
        $stmt->bindParam(':review_text', $review_text);
        $stmt->bindParam(':rating', $rating);

        // Execute the statement
        $stmt->execute();

        // Get the ID of the newly inserted review
        $review_id = $db_connection->lastInsertId();

        return [
            'success' => true,
            'message' => 'Review saved successfully.',
            'review_id' => $review_id,
        ];

    } catch (PDOException $e) {
        // Handle database errors
        return [
            'success' => false,
            'message' => 'Error saving review: ' . $e->getMessage(),
        ];
    }
}


// Example Usage:
// Assuming you have a database table named 'reviews' with columns:
// - id (INT, PRIMARY KEY, AUTO_INCREMENT)
// - product_id (VARCHAR)
// - user_name (VARCHAR)
// - review_text (TEXT)
// - rating (INT)

// $result = saveReview('123', 'John Doe', 'Great product!', 5);
// print_r($result);

// Example output:
// Array
// (
//     [success] => true
//     [message] => Review saved successfully.
//     [review_id] => 1
// )


//  To display the review (fetch from the database):
/*
$sql = "SELECT * FROM reviews WHERE product_id = '123' ORDER BY rating DESC LIMIT 1";
$stmt = $db_connection->prepare($sql);
$stmt->execute();
$review = $stmt->fetch(PDO::FETCH_ASSOC);

if ($review) {
    echo "Product ID: " . $review['product_id'] . "<br>";
    echo "User: " . $review['user_name'] . "<br>";
    echo "Review: " . $review['review_text'] . "<br>";
    echo "Rating: " . $review['rating'] . "<br>";
} else {
    echo "No reviews found for this product.";
}
*/
?>
