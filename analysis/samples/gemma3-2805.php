

<?php

/**
 * User Review Function
 *
 * This function allows you to process user reviews,
 * including saving them to a database or displaying them.
 *
 * @param string $username     The username of the reviewer.
 * @param string $review_text  The text of the review.
 * @param int    $rating       The rating (e.g., 1-5).
 * @param int    $product_id  The ID of the product being reviewed.
 * @param array $options (Optional) Array of options to customize the function.
 *                       - 'save_to_db' (bool):  Whether to save to the database (default: true)
 *                       - 'db_host'        (string): Database host (e.g., 'localhost')
 *                       - 'db_name'        (string): Database name
 *                       - 'db_user'        (string): Database username
 *                       - 'db_pass'        (string): Database password
 *
 * @return array  An array containing the review details or an error message if saving failed.
 */
function create_user_review(
    string $username,
    string $review_text,
    int $rating,
    int $product_id,
    array $options = []
): array
{
    // Validate inputs (basic example - expand for production)
    if (empty($username)) {
        return ['error' => 'Username cannot be empty.'];
    }
    if (empty($review_text)) {
        return ['error' => 'Review text cannot be empty.'];
    }
    if ($rating < 1 || $rating > 5) {
        return ['error' => 'Rating must be between 1 and 5.'];
    }

    // Default options
    $options = array_merge([
        'save_to_db' => true,
        'db_host' => 'localhost',
        'db_name' => 'your_database_name',
        'db_user' => 'your_database_user',
        'db_pass' => 'your_database_password',
    ], $options); // Merge with provided options

    // Save to Database (Example - Adapt for your database)
    $review_data = [
        'username' => $username,
        'review_text' => $review_text,
        'rating' => $rating,
        'product_id' => $product_id,
        'created_at' => date('Y-m-d H:i:s'),
    ];

    try {
        // Connect to the database
        $conn = new mysqli($options['db_host'], $options['db_user'], $options['db_pass'], $options['db_name']);

        // Check connection
        if ($conn->connect_error) {
            throw new Exception('Connection failed: ' . $conn->connect_error);
        }

        // Execute the query
        $sql = "INSERT INTO reviews (username, review_text, rating, product_id, created_at) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssis", $review_data['username'], $review_data['review_text'], $review_data['rating'], $review_data['product_id']);
        $stmt->execute();

        // Check if the query was successful
        if ($stmt->affected_rows === 0) {
           throw new Exception('Failed to insert review into database.');
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();

        return ['success' => 'Review created successfully.'];

    } catch (Exception $e) {
        return ['error' => 'Error creating review: ' . $e->getMessage()];
    }
}


// Example Usage:
$review = create_user_review(
    'john_doe',
    'This product is amazing!',
    5,
    123,
    [
        'save_to_db' => false, // Don't save to the database
        'db_host' => 'localhost',
        'db_name' => 'my_reviews',
    ]
);

print_r($review); // Output the review details or error message
?>
