

<?php

/**
 * User Review Function - Basic Implementation
 *
 * This function takes a user's review text, a rating (1-5), and an optional user ID.
 * It stores the review in a simple array (for demonstration purposes).
 *
 * In a real application, you'd integrate this with a database.
 *
 * @param string $reviewText The text of the user's review.
 * @param int $rating The rating given by the user (1-5).
 * @param string|null $userId The ID of the user submitting the review.
 *
 * @return array An array containing the review details.
 */
function createReview(string $reviewText, int $rating, ?string $userId = null): array
{
    // Basic validation - important for security and data integrity.
    if (empty($reviewText)) {
        throw new InvalidArgumentException("Review text cannot be empty.");
    }

    if ($rating < 1 || $rating > 5) {
        throw new InvalidArgumentException("Rating must be between 1 and 5.");
    }

    $review = [
        'text' => $reviewText,
        'rating' => $rating,
        'userId' => $userId, // Store user ID for later use
        'date' => date('Y-m-d H:i:s'), // Add a timestamp for review creation
    ];

    return $review;
}

// --- Example Usage ---

try {
    $review1 = createReview("This product is amazing! I highly recommend it.", 5, "user123");
    echo "Review 1:
";
    print_r($review1);
    echo "

";

    $review2 = createReview("It was okay, nothing special.", 3);
    echo "Review 2:
";
    print_r($review2);
    echo "

";

    // Example of error handling - Invalid rating
    try {
        $invalidReview = createReview("Bad review", 6);
    } catch (InvalidArgumentException $e) {
        echo "Error creating review: " . $e->getMessage() . "
";
    }

} catch (InvalidArgumentException $e) {
    // Catch any errors that might be thrown by createReview()
    echo "An error occurred: " . $e->getMessage() . "
";
}
?>
