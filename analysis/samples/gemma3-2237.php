

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and view reviews for a product or service.
 *
 * @param string $productName  The name of the product or service being reviewed.
 * @param string $userId      The ID of the user submitting the review. (Can be user ID, username, etc.)
 * @param string $reviewText  The text of the review.
 * @param array  $reviews     (Optional)  Existing reviews to add to.  Defaults to an empty array.
 *
 * @return array An array containing:
 *   - 'reviews': The updated array of reviews.
 *   - 'success':  True if the review was added successfully, False otherwise.
 *   - 'error':   An error message if the review could not be added.
 */
function create_review(string $productName, string $userId, string $reviewText, array &$reviews = []) {

    // **Validation (Important!)**
    if (empty($reviewText)) {
        return ['reviews' => $reviews, 'success' => false, 'error' => 'Review text cannot be empty.'];
    }

    // **Data Sanitization - VERY IMPORTANT**
    // In a real application, you'd want to sanitize the reviewText more robustly,
    // potentially using htmlspecialchars() or escaping functions appropriate for
    // your database and application.
    $sanitizedReviewText = htmlspecialchars($reviewText); // Simple example - improve for production

    // **Review Data**
    $newReview = [
        'userId' => $userId,
        'reviewText' => $sanitizedReviewText,
        'timestamp' => time() // Use the current timestamp
    ];

    // **Add Review**
    $reviews[] = $newReview;

    return ['reviews' => $reviews, 'success' => true];
}


/**
 * Display User Reviews
 *
 * This function takes an array of reviews and formats them for display.
 *
 * @param array $reviews  An array of review objects.
 *
 * @return string HTML formatted review output.
 */
function display_reviews(array $reviews) {
    $output = "<h2>Reviews for {$reviews[0]['userId']}</h2>"; // Assume first review's user ID is correct for display.

    if (empty($reviews)) {
        $output .= "<p>No reviews yet.</p>";
    } else {
        foreach ($reviews as $review) {
            $output .= "<div class='review'>";
            $output .= "<p><strong>User:</strong> " . htmlspecialchars($review['userId']) . "</p>";  //Escape for display too
            $output .= "<p>" . htmlspecialchars($review['reviewText']) . "</p>";
            $output .= "<p><strong>Date:</strong> " . date('Y-m-d H:i:s', strtotime($review['timestamp'])) . "</p>";
            $output .= "</div>";
        }
    }
    return $output;
}


// **Example Usage:**

// 1.  Create a Review
$product = "Awesome Widget";
$user = "john_doe";
$comment = "This widget is amazing!  I love it!";

$newReviews = create_review($product, $user, $comment);

if ($newReviews['success']) {
    echo "Review created successfully!<br>";
    print_r($newReviews['reviews']); // Print the updated reviews array
} else {
    echo "Error creating review: " . $newReviews['error'] . "<br>";
}

// 2. Display the Reviews
$existingReviews = [
    ['userId' => 'jane_smith', 'reviewText' => 'Good product, but a bit pricey.', 'timestamp' => time() - 3600],
    ['userId' => 'john_doe', 'reviewText' => 'Fantastic!', 'timestamp' => time() - 7200]
];

echo "<hr>";
echo display_reviews($existingReviews);

?>
