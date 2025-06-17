

<?php

/**
 * User Review Function
 * 
 * This function allows users to submit and display user reviews for a specific item.
 *
 * @param string $item_id The unique identifier of the item being reviewed.
 * @param string $item_title The title of the item being reviewed.
 * @param string $review_text The user-submitted review text.
 * @param array $user_data An array containing user data (username, etc.).  Optional.
 * 
 * @return array An array containing the review data and potentially an error message.
 */
function submit_review(string $item_id, string $item_title, string $review_text, array $user_data = []) {
    // Validate inputs - basic example
    if (empty($item_id) || empty($review_text)) {
        return ['success' => false, 'message' => 'Item ID and review text are required fields.'];
    }

    // Sanitize inputs (important for security)
    $item_id = filter_var($item_id, FILTER_SANITIZE_STRING);
    $review_text = filter_var($review_text, FILTER_SANITIZE_STRING);

    //  You might add more sophisticated validation here:
    //  - Check review text length
    //  -  Basic spam detection (e.g., blacklists)

    //  Store the review data (In a real application, this would be a database)
    $review = [
        'item_id' => $item_id,
        'item_title' => $item_title,
        'review_text' => $review_text,
        'user_id' => isset($user_data['user_id']) ? $user_data['user_id'] : null, // Use user ID if available
        'created_at' => date('Y-m-d H:i:s'),
    ];

    //  In a real application, you would save this $review array to a database.
    //  For example:
    //  $db->insert('reviews', $review);

    return ['success' => true, 'review_data' => $review, 'message' => 'Review submitted successfully!'];
}


/**
 * Displays user reviews for a given item.
 *
 * @param string $item_id The ID of the item to display reviews for.
 * @param array $reviews An array of review data (returned from submit_review).
 *
 * @return string HTML representation of the reviews.
 */
function display_reviews(string $item_id, array $reviews) {
    if (empty($reviews)) {
        return "<p>No reviews yet.</p>";
    }

    $html = "<h2>Reviews for {$reviews[$item_id]['item_title']}</h2>";
    $html .= "<ul>";

    foreach ($reviews[$item_id] as $review) {
        $html .= "<li>";
        $html .= "<p><strong>" . $review['user_id'] . ":</strong> " . $review['review_text'] . "</p>";
        $html .= "<span class='created_at'>" . $review['created_at'] . "</span>";
        $html .= "</li>";
    }

    $html .= "</ul>";
    return $html;
}



// Example Usage (Demonstration)
// -------------------------------------------------------------------

// 1. Submit a review:
$review_result = submit_review('product_123', 'Awesome Widget', 'This widget is amazing!  It does everything I need.');

if ($review_result['success']) {
    echo "<h2>Review Submitted</h2>";
    echo "<p>Review ID: " . $review_result['review_data']['item_id'] . "</p>";
    echo "<p>Review Text: " . $review_result['review_data']['review_text'] . "</p>";
} else {
    echo "<h2>Error</h2>";
    echo "<p>Error submitting review: " . $review_result['message'] . "</p>";
}

// 2. Display the reviews (assuming you have some reviews)
//  For this to work, you would need to populate the $reviews array from a database or other source.
//  For demonstration, we'll create a sample array.
$sample_reviews = [
    'product_123' => [
        ['item_id' => 'product_123', 'review_text' => 'Great product!'],
        ['item_id' => 'product_123', 'review_text' => 'Works as expected.'],
    ],
    'product_456' => [
        ['item_id' => 'product_456', 'review_text' => 'Not bad, but overpriced.'],
    ],
];

$reviews_html = display_reviews('product_123', $sample_reviews);
echo $reviews_html;
?>
