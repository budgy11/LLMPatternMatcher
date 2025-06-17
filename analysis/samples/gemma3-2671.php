

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product.
 *
 * @param string $productId The ID of the product being reviewed.
 * @param string $username The username of the reviewer.
 * @param string $reviewText The text of the review.
 * @param array $reviews Array of existing reviews (optional).
 *
 * @return array An array containing:
 *   - 'reviews': An array of review objects (or null if no reviews exist).
 *   - 'newReview': The new review object that was submitted (if any).
 */
function createAndDisplayReviews(string $productId, string $username, string $reviewText, array $reviews = []): array
{
    // Validate inputs (basic validation - expand as needed)
    if (empty($username)) {
        return ['reviews' => $reviews, 'newReview' => null, 'error' => 'Username cannot be empty.'];
    }
    if (empty($reviewText)) {
        return ['reviews' => $reviews, 'newReview' => null, 'error' => 'Review text cannot be empty.'];
    }

    // Create a new review object
    $newReview = [
        'productId' => $productId,
        'username' => $username,
        'reviewText' => $reviewText,
        'timestamp' => time(), // Add a timestamp for tracking
    ];

    // Add the new review to the array
    $reviews[] = $newReview;

    return ['reviews' => $reviews, 'newReview' => $newReview];
}


// Example Usage:
$productId = '123';
$username = 'JohnDoe';
$reviewText = 'This product is fantastic! I highly recommend it.';

// Create a review
$result = createAndDisplayReviews($productId, $username, $reviewText);

if (isset($result['error'])) {
    echo "<p style='color: red;'>Error: " . $result['error'] . "</p>";
} else {
    // Display existing reviews
    echo "<h2>Existing Reviews for Product ID: " . $productId . "</h2>";
    if (empty($result['reviews'])) {
        echo "<p>No reviews yet.</p>";
    } else {
        foreach ($result['reviews'] as $review) {
            echo "<div class='review'>";
            echo "<p><strong>Username:</strong> " . $review['username'] . "</p>";
            echo "<p><strong>Review:</strong> " . htmlspecialchars($review['reviewText']) . "</p>";
            echo "<p><strong>Timestamp:</strong> " . date('Y-m-d H:i:s', $review['timestamp']) . "</p>";
            echo "</div>";
        }
    }

    // Display the new review
    if ($result['newReview']) {
        echo "<div class='new-review'>";
        echo "<p><strong>New Review:</strong> " . htmlspecialchars($result['newReview']['reviewText']) . "</p>";
        echo "</div>";
    }
}

?>
