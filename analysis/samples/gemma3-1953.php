

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product or service.
 *
 * @param string $productName The name of the product or service being reviewed.
 * @param string $reviewText The user's review text.
 * @param int $rating (Optional) The rating given by the user (1-5). Defaults to 0.
 * @return array An array containing the review details and the HTML for display.
 */
function createReview(string $productName, string $reviewText, int $rating = 0) {
    // Validation - Basic checks.  Expand these as needed for production.
    if (empty($productName)) {
        return ['error' => 'Product name cannot be empty.'];
    }
    if (empty($reviewText)) {
        return ['error' => 'Review text cannot be empty.'];
    }

    // Validate rating (if provided)
    if ($rating < 1 || $rating > 5) {
        return ['error' => 'Rating must be between 1 and 5.'];
    }


    // Create the review data.
    $reviewData = [
        'productName' => $productName,
        'reviewText' => $reviewText,
        'rating' => $rating,
        'timestamp' => date('Y-m-d H:i:s'),  // Add a timestamp
    ];

    // Format the review for HTML display.
    $reviewHtml = '<div class="review">';
    $reviewHtml .= '<p class="review-title">Review for: ' . htmlspecialchars($productName) . '</p>'; //Escape to prevent XSS
    $reviewHtml .= '<p class="review-text">' . htmlspecialchars($reviewText) . '</p>'; // Escape to prevent XSS
    $reviewHtml .= '<p class="review-rating">Rating: ' . htmlspecialchars($rating) . '</p>'; // Escape to prevent XSS
    $reviewHtml .= '<p class="review-timestamp">Submitted on: ' . htmlspecialchars(date('Y-m-d H:i:s', strtotime($reviewData['timestamp']))) . '</p>'; //Escape to prevent XSS
    $reviewHtml .= '</div>';

    return [
        'data' => $reviewData,
        'html' => $reviewHtml,
    ];
}



// Example Usage:

// 1. Create a review and display it.
$reviewResult = createReview("Awesome Widget", "This widget is fantastic!  It's exactly what I needed.");

if (isset($reviewResult['error'])) {
    echo "Error creating review: " . $reviewResult['error'] . "<br>";
} else {
    echo "Review Details: <pre>";
    print_r($reviewResult['data']);
    echo "</pre>";
    echo "<hr>";
    echo $reviewResult['html'];
}


// 2. Create another review with a rating.
$reviewResult2 = createReview("Super Gadget", "Great product, would recommend!", 5);

if (isset($reviewResult2['error'])) {
    echo "Error creating review: " . $reviewResult2['error'] . "<br>";
} else {
    echo "Review Details: <pre>";
    print_r($reviewResult2['data']);
    echo "</pre>";
    echo "<hr>";
    echo $reviewResult2['html'];
}



// 3.  Demonstrate an error case.
$reviewResult3 = createReview("", "Bad review");
if (isset($reviewResult3['error'])) {
    echo "Error creating review: " . $reviewResult3['error'] . "<br>";
} else {
    echo "Review Details: <pre>";
    print_r($reviewResult3['data']);
    echo "</pre>";
    echo "<hr>";
    echo $reviewResult3['html'];
}

?>
