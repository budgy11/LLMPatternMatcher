

<?php

/**
 * User Review Function
 *
 * This function handles the creation and display of user reviews for a given product.
 *
 * @param string $productId The ID of the product for which to display reviews.
 * @param string $username The username of the user submitting the review.
 * @param string $reviewText The text of the review.
 * @param array $reviews Array of existing reviews for the product (optional).  If not provided, a new array is created.
 *
 * @return array An array containing all reviews for the product.  Returns an empty array if the product
 *              doesn't exist or if an error occurs during saving.
 */
function createAndDisplayUserReview(string $productId, string $username, string $reviewText, array $reviews = []): array
{
    // Validate input (add more validation as needed)
    if (empty($productId) || empty($username) || empty($reviewText)) {
        return []; // Return empty array for invalid input
    }

    // Add the new review to the array
    $reviews[] = [
        'productId' => $productId,
        'username' => $username,
        'reviewText' => $reviewText,
        'timestamp' => date('Y-m-d H:i:s') // Add timestamp for better ordering and tracking
    ];

    // Sort the reviews by timestamp (newest first)
    usort($reviews, function ($a, $b) {
        return $b['timestamp'] <=> $a['timestamp'];
    });

    // Display the reviews (optional - can be moved to a separate function)
    echo "<p><b>New Review:</b> " . htmlspecialchars($reviewText) . "</p>";
    echo "<p><b>Posted by:</b> " . htmlspecialchars($username) . "</p>";
    echo "<p><b>Timestamp:</b> " . htmlspecialchars($reviews[count($reviews) - 1]['timestamp']) . "</p>";
    echo "<hr>";

    return $reviews;
}



// Example Usage (Demonstration)
$productID = '123';  // Replace with a real product ID

// Create some initial reviews for demonstration
$reviews = [
    ['productId' => '123', 'username' => 'John Doe', 'reviewText' => 'Great product!', 'timestamp' => date('Y-m-d H:i:s')],
    ['productId' => '123', 'username' => 'Jane Smith', 'reviewText' => 'Excellent value for money.', 'timestamp' => date('Y-m-d H:i:s')],
];

// Create a new review
$newReview = createAndDisplayUserReview($productID, 'Peter Jones', 'This is fantastic!  I highly recommend it.');

// Add another review
$newReview2 = createAndDisplayUserReview($productID, 'Alice Brown', 'It works perfectly!');

// Display all reviews
echo "<h2>All Reviews for Product " . $productID . ":</h2>";
echo "<ul>";
foreach ($reviews as $review) {
    echo "<li>" . htmlspecialchars($review['username']) . ": " . htmlspecialchars($review['reviewText']) . " - " . htmlspecialchars($review['timestamp']) . "</li>";
}
echo "</ul>";



?>
