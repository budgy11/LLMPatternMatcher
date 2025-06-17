

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a given item.
 *
 * @param string $item_id The unique identifier for the item being reviewed.
 * @param string $user_name The name of the user submitting the review.
 * @param string $review_text The text content of the review.
 * @param int $rating      The rating given by the user (e.g., 1-5).
 * @param string $database_connection  Optional.  A database connection object.  If not provided, 
 *                                      it will attempt to connect to a database based on default settings.
 *
 * @return array  An array containing:
 *               - 'success': True if the review was successfully saved, false otherwise.
 *               - 'message': A message indicating the outcome of the operation (e.g., success, error).
 */
function saveReview(
    string $item_id,
    string $user_name,
    string $review_text,
    int $rating,
    string $database_connection = null
): array {

    // Input Validation (important for security and data integrity)
    if (empty($item_id) || empty($user_name) || empty($review_text)) {
        return [
            'success' => false,
            'message' => 'Error: Item ID, User Name, and Review Text are required.'
        ];
    }

    if ($rating < 1 || $rating > 5) {
        return [
            'success' => false,
            'message' => 'Error: Rating must be between 1 and 5.'
        ];
    }

    // Prepare the SQL statement
    $sql = "INSERT INTO reviews (item_id, user_name, review_text, rating) VALUES (?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $database_connection->prepare($sql);

    if ($stmt === false) {
        // Handle the error.  This is crucial for debugging and production.
        return [
            'success' => false,
            'message' => 'Error: Database preparation failed. Check database connection and SQL syntax.'
        ];
    }

    // Bind the parameters
    $stmt->bind_param("sss", $item_id, $user_name, $review_text);

    // Execute the statement
    if ($stmt->execute() === false) {
        // Handle the error.  Crucial for debugging.
        return [
            'success' => false,
            'message' => 'Error: Review save failed. Check database connection, SQL syntax, and permissions.'
        ];
    }

    // Clean up
    $stmt->close();

    return [
        'success' => true,
        'message' => 'Review saved successfully!'
    ];
}

/**
 * Get User Reviews
 *
 *  Fetches reviews for a specific item.
 *
 * @param string $item_id  The unique identifier for the item.
 * @param string $database_connection A database connection object.
 *
 * @return array An array containing:
 *              - 'reviews':  An array of review objects, or an empty array if no reviews exist.
 *              - 'success': True if the operation was successful, false otherwise.
 *              - 'message': A message indicating the outcome.
 */
function getReviews(string $item_id, string $database_connection): array {
    $sql = "SELECT * FROM reviews WHERE item_id = ? ORDER BY rating DESC";  // Order by rating for best reviews
    $stmt = $database_connection->prepare($sql);

    if ($stmt === false) {
        return [
            'reviews' => [],
            'success' => false,
            'message' => 'Error: Database preparation failed.'
        ];
    }

    $stmt->bind_param("s", $item_id);

    $stmt->execute();

    if ($stmt === false) {
        return [
            'reviews' => [],
            'success' => false,
            'message' => 'Error: Query execution failed.'
        ];
    }

    $result = $stmt->get_result();

    if ($result === false) {
        return [
            'reviews' => [],
            'success' => false,
            'message' => 'Error: Getting result set failed.'
        ];
    }

    $reviews = [];
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }

    $stmt->close();
    $result->close(); // Important to close the result set.

    return [
        'reviews' => $reviews,
        'success' => true,
        'message' => 'Reviews retrieved successfully.'
    ];
}


// Example Usage (assuming you have a database connection set up)
//  This example is illustrative and doesn't include actual database setup.
//  You'll need to adapt it to your specific database setup.

//  Example 1: Saving a review
//  $review_data = saveReview('product123', 'John Doe', 'This is a great product!', 5);
//  print_r($review_data);

//  Example 2: Getting reviews for a product
// $reviews_data = getReviews('product123');
// print_r($reviews_data);

?>
