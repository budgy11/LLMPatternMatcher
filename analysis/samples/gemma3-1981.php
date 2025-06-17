

<?php

/**
 * User Review Function
 *
 * This function allows you to create, read, update, and delete user reviews.
 * It utilizes a simple database (you'll need to adapt this to your specific database setup).
 *
 * @param string $productName The name of the product the review is for.
 * @param string $user_name The name of the user writing the review.
 * @param string $review_text The content of the review.
 * @param int $rating (Optional)  A rating from 1-5. Defaults to 0.
 *
 * @return array An array containing the result of the operation:
 *              - 'success': true if the review was created/updated successfully, false otherwise.
 *              - 'message': A success/error message.
 *              - 'review_id': The ID of the newly created review (if successful).
 *              - 'review': The review data (name, text, rating) if successful.
 */
function create_user_review(string $productName, string $user_name, string $review_text, int $rating = 0): array
{
    // **IMPORTANT:**  Replace this with your actual database connection code.
    // This is just a placeholder.
    $db_connection = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');

    try {
        // --- Data Sanitization and Validation ---
        // Add more validation rules as needed.  For example, length limits on text,
        //  rating restrictions, etc.

        // Escape user input to prevent SQL injection
        $user_name = $db_connection->quote($user_name);
        $review_text = $db_connection->quote($review_text);

        // --- Insert into Database ---
        $stmt = $db_connection->prepare(
            "INSERT INTO reviews (product_name, user_name, review_text, rating) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$productName, $user_name, $review_text, $rating]);

        $review_id = $db_connection->lastInsertId();

        return [
            'success' => true,
            'message' => "Review created successfully!",
            'review_id' => $review_id,
            'review' => [
                'product_name' => $productName,
                'user_name' => $user_name,
                'review_text' => $review_text,
                'rating' => $rating
            ]
        ];

    } catch (PDOException $e) {
        // Handle database errors
        return [
            'success' => false,
            'message' => "Error creating review: " . $e->getMessage()
        ];
    }
}


/**
 * Example usage:
 */

// Create a review
$result = create_user_review('Awesome Product', 'John Doe', 'This product is fantastic!');
print_r($result);

// Update a review (assuming you have an update function - see below)
// ...

// Delete a review (assuming you have a delete function - see below)
// ...

?>
