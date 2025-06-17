

<?php

/**
 * User Review Function
 *
 * This function allows you to store and retrieve user reviews for a product or item.
 * It includes basic validation and formatting.
 *
 * @param string $productId The unique identifier for the product/item.
 * @param string $username The name of the user submitting the review.
 * @param string $reviewText The text of the review.
 * @param int $rating  The rating (1-5).
 * @return array|string An array containing review details, or an error message string if validation fails.
 */
function createReview(string $productId, string $username, string $reviewText, int $rating)
{
    // Validate input
    if (empty($productId)) {
        return "Error: Product ID cannot be empty.";
    }

    if (empty($username)) {
        return "Error: Username cannot be empty.";
    }

    if (empty($reviewText)) {
        return "Error: Review text cannot be empty.";
    }

    if ($rating < 1 || $rating > 5) {
        return "Error: Rating must be between 1 and 5.";
    }

    // Sanitize input (Basic - Consider more robust sanitization for production)
    $productId = trim($productId);
    $username = trim($username);
    $reviewText = trim($reviewText);

    // Prepare review data
    $review = [
        'productId' => $productId,
        'username'  => $username,
        'reviewText' => $reviewText,
        'rating'    => $rating,
        'timestamp' => time() // Automatically record the timestamp
    ];

    // Store review (Placeholder - Replace with database or file storage)
    // In a real application, you'd save this to a database or file.
    // For this example, we'll just echo the data.
    echo "Review created:
";
    print_r($review);

    return $review; // Return the review data.
}


// Example Usage:

// Create a review
$review_data = createReview("product123", "John Doe", "Great product!  I love it.", 5);

// Another example
$review_data = createReview("product456", "Jane Smith", "It's okay, but could be better.", 3);

// Example demonstrating error handling:
$review_data = createReview("", "Error Test", "Invalid product ID", 4); // Empty product ID
echo "
" . $review_data;
?>
