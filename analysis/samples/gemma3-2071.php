

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews.
 *
 * @param string $productId  The ID of the product being reviewed.
 * @param string $reviewText The user's review text.
 * @param int    $rating     The rating (1-5).
 * @param string $username   The user's username.  Optional.
 *
 * @return array An array containing:
 *              - 'success': True if the review was successfully submitted, false otherwise.
 *              - 'message': A success or error message.
 *              - 'review': The submitted review data (for display or saving).
 */
function submitReview(string $productId, string $reviewText, int $rating, string $username = null)
{
    // Input validation
    if (empty($reviewText)) {
        return [
            'success' => false,
            'message' => 'Review text cannot be empty.',
            'review' => null
        ];
    }

    if ($rating < 1 || $rating > 5) {
        return [
            'success' => false,
            'message' => 'Rating must be between 1 and 5.',
            'review' => null
        ];
    }

    // Simulate saving to a database (replace with your database logic)
    // In a real application, you would insert this data into a database table.
    $review = [
        'product_id' => $productId,
        'review_text' => $reviewText,
        'rating' => $rating,
        'username' => $username,
        'timestamp' => time()
    ];

    // Simulate success
    return [
        'success' => true,
        'message' => 'Review submitted successfully!',
        'review' => $review
    ];
}


/**
 * Displays reviews for a given product.
 *
 * @param string $productId The ID of the product.
 * @return string HTML markup for displaying the reviews.
 */
function displayReviews(string $productId)
{
    // Simulate fetching reviews from a database.  Replace this with your data access code.
    $reviews = [
        'product123' => [
            ['username' => 'Alice', 'review_text' => 'Great product!', 'rating' => 5, 'timestamp' => time() - 86400 * 30], // 30 days ago
            ['username' => 'Bob', 'review_text' => 'Good value.', 'rating' => 4, 'timestamp' => time() - 86400 * 15], // 15 days ago
            ['username' => 'Charlie', 'review_text' => 'Could be better.', 'rating' => 3, 'timestamp' => time() - 86400 * 60]  // 60 days ago
        ],
        'product456' => [
            ['username' => 'David', 'review_text' => 'Excellent quality!', 'rating' => 5, 'timestamp' => time() - 86400 * 72]
        ]
    ];

    if (isset($reviews[$productId])) {
        $review_html = '<ul>';
        foreach ($reviews[$productId] as $review) {
            $username = isset($review['username']) ? $review['username'] : 'Anonymous';
            $review_html .= '<li><strong>' . $username . ':</strong> ' . htmlspecialchars($review['review_text']) . ' (' . $review['rating'] . '/5) - ' . date('Y-m-d H:i:s', $review['timestamp']) . '</li>';
        }
        $review_html .= '</ul>';
        return $review_html;
    } else {
        return '<p>No reviews yet.</p>';
    }
}



// Example Usage:

// 1. Submitting a review:
$result = submitReview('product123', 'This is a fantastic product!', 5, 'Eve');
if ($result['success']) {
    echo '<p>' . $result['message'] . '</p>';
    print_r($result['review']); // Display the submitted review data.
} else {
    echo '<p>Error: ' . $result['message'] . '</p>';
}

// 2. Displaying reviews for product 'product123':
$reviews_html = displayReviews('product123');
echo $reviews_html;

// 3.  Displaying reviews for product 'product456':
$reviews_html = displayReviews('product456');
echo $reviews_html;
?>
