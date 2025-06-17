

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product or item.
 *
 * @param string $productId The unique identifier for the product or item.
 * @param string $username The username of the reviewer.
 * @param string $rating A rating from 1 to 5.
 * @param string $comment The review text.
 *
 * @return array  An array containing the review data if successful, or an error message if not.
 */
function create_review(string $productId, string $username, string $rating, string $comment)
{
    // Input validation - crucial for security and data integrity
    if (empty($productId)) {
        return ['error' => 'Product ID cannot be empty.'];
    }

    if (empty($username)) {
        return ['error' => 'Username cannot be empty.'];
    }

    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        return ['error' => 'Username must contain only alphanumeric characters and underscores.'];
    }

    if (!preg_match('/^[1-5]$/', $rating)) {
        return ['error' => 'Rating must be a number between 1 and 5.'];
    }

    if (empty($comment)) {
        return ['error' => 'Comment cannot be empty.'];
    }

    if (!preg_match('/^\S+\s+.*$/', $comment)) {
        return ['error' => 'Comment must contain at least one non-whitespace character.'];
    }

    // In a real application, you would save this data to a database.
    // This is a placeholder for demonstration purposes.
    $review = [
        'productId' => $productId,
        'username' => $username,
        'rating' => (int)$rating, // Convert rating to integer
        'comment' => $comment,
        'timestamp' => time()
    ];

    // For demonstration, we just echo the review
    echo "Review submitted by: " . $username . " for product ID: " . $productId . "
";
    echo "Rating: " . $rating . "
";
    echo "Comment: " . $comment . "
";
    echo "Timestamp: " . date("Y-m-d H:i:s", $timestamp) . "
";


    return $review;
}

// Example Usage:
$review_data = create_review('product123', 'john_doe', 5, 'Great product, highly recommended!');
if (isset($review_data['error'])) {
    echo "Error: " . $review_data['error'] . "
";
} else {
    echo "Review successfully created.
";
}

$review_data = create_review('product456', 'jane_smith', 3, 'It was okay, but could be better.');
if (isset($review_data['error'])) {
    echo "Error: " . $review_data['error'] . "
";
} else {
    echo "Review successfully created.
";
}

// Example with invalid input
$invalid_review = create_review('', 'testuser', 1, 'Test');
if (isset($invalid_review['error'])) {
    echo "Error: " . $invalid_review['error'] . "
";
} else {
    echo "Review successfully created.
";
}


?>
