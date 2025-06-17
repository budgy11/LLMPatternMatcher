

<?php

/**
 * User Review Function
 *
 * This function allows you to create, read, update, and delete user reviews.
 *
 * @param string $product_id The ID of the product the review is for.
 * @param string $username The username of the reviewer.
 * @param string $rating  The rating given by the reviewer (e.g., 1-5).
 * @param string $comment The user's review comment.
 * @param string $db_connection  A database connection object.
 *
 * @return array An array containing the response data:
 *              - 'success': true if the operation was successful, false otherwise.
 *              - 'message': A message describing the result.
 *              - 'review_id': The ID of the newly created review (if successful).
 *              - 'review': The full review data (if successful).
 */
function create_user_review(
    string $product_id,
    string $username,
    string $rating,
    string $comment,
    $db_connection
) {
    // Validate input (add more validation as needed)
    if (empty($product_id) || empty($username) || empty($rating) || empty($comment)) {
        return [
            'success' => false,
            'message' => 'Missing required fields.',
        ];
    }

    if (!is_numeric($rating) || (int)$rating < 1 || (int)$rating > 5) {
        return [
            'success' => false,
            'message' => 'Invalid rating. Rating must be a number between 1 and 5.',
        ];
    }

    // Prepare the SQL statement
    $sql = "INSERT INTO reviews (product_id, username, rating, comment)
            VALUES ('$product_id', '$username', '$rating', '$comment')";

    // Execute the query
    $result = $db_connection->query($sql);

    if ($result) {
        // Get the ID of the newly created review
        $review_id = $db_connection->insert_id;

        // Get the full review data
        $review = [
            'review_id' => $review_id,
            'product_id' => $product_id,
            'username' => $username,
            'rating' => $rating,
            'comment' => $comment,
        ];

        return [
            'success' => true,
            'message' => 'Review created successfully.',
            'review_id' => $review_id,
            'review' => $review,
        ];
    } else {
        // Handle the error
        $error_message = $db_connection->error;
        return [
            'success' => false,
            'message' => 'Error creating review: ' . $error_message,
        ];
    }
}

// Example usage (replace with your actual database connection)
// Assuming you have a database connection named $conn
// $conn = new mysqli("localhost", "username", "password", "database_name");
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }


// $review_data = create_user_review(
//     '123',  // Product ID
//     'john.doe', // Username
//     '4',   // Rating
//     'This is a great product!', // Comment
//     $conn
// );

// print_r($review_data);


// Example: Reading Reviews
/**
 * Read Reviews
 *
 * Retrieves all reviews for a given product.
 *
 * @param string $product_id The ID of the product.
 * @param $db_connection Database connection object.
 *
 * @return array An array containing the review data, or an empty array if no reviews are found.
 */
function read_user_reviews(string $product_id, $db_connection) {
    $sql = "SELECT * FROM reviews WHERE product_id = '$product_id'";
    $result = $db_connection->query($sql);

    if ($result->num_rows > 0) {
        $reviews = [];
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
        return $reviews;
    } else {
        return []; // Return an empty array if no reviews are found.
    }
}


// Example of reading reviews:
// $product_id = '123';
// $reviews = read_user_reviews($product_id, $conn);
// print_r($reviews);
?>
