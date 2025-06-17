

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product or service.
 * It includes basic input validation and sanitization to prevent common security vulnerabilities.
 *
 * @param string $productId The ID of the product or service being reviewed.
 * @param string $reviewText The text of the review submitted by the user.
 * @param string $reviewerName The name of the reviewer.
 * @return array An array containing the review details (success, message, review) or an error message.
 */
function submitReview(string $productId, string $reviewText, string $reviewerName): array
{
    // Input Validation and Sanitization
    $productId = filter_var($productId, FILTER_SANITIZE_STRING);
    $reviewText = filter_var($reviewText, FILTER_SANITIZE_STRING);
    $reviewerName = filter_var($reviewerName, FILTER_SANITIZE_STRING);

    // Validate required fields
    if (empty($productId) || empty($reviewText) || empty($reviewerName)) {
        return ['success' => false, 'message' => 'All fields are required.', 'review' => null];
    }

    // Basic Review Sanitization (more sophisticated sanitization might be needed)
    $reviewText = strip_tags($reviewText); // Remove HTML tags

    // **Important:**  In a real application, you would typically:
    // 1. Store the review in a database.
    // 2. Implement user authentication to track who submitted the review.
    // 3. Add more robust security measures like escaping data before displaying it.

    // Simulate storing the review (replace with database interaction)
    $review = [
        'productId' => $productId,
        'reviewText' => $reviewText,
        'reviewerName' => $reviewerName,
        'submissionTime' => date('Y-m-d H:i:s')
    ];

    return ['success' => true, 'message' => 'Review submitted successfully!', 'review' => $review];
}

/**
 * Displays Reviews for a Product
 *
 * This function retrieves and displays reviews for a given product ID.
 *
 * @param string $productId The ID of the product for which to display reviews.
 * @return string HTML representation of the reviews.  Returns an error message if no reviews are found.
 */
function displayReviews(string $productId): string
{
    // Simulate retrieving reviews from a database or other data source.
    // Replace this with your actual data retrieval logic.
    $reviews = [
        'product123' => [
            ['reviewerName' => 'John Doe', 'reviewText' => 'Great product! Highly recommended.', 'submissionTime' => '2023-10-27 10:00:00'],
            ['reviewerName' => 'Jane Smith', 'reviewText' => 'Good value for money.', 'submissionTime' => '2023-10-26 14:30:00'],
        ],
        'product456' => [
            ['reviewerName' => 'Peter Jones', 'reviewText' => 'Could be better.', 'submissionTime' => '2023-10-25 09:15:00'],
        ],
    ];

    if (isset($reviews[$productId])) {
        $html = '<ul>';
        foreach ($reviews[$productId] as $review) {
            $html .= '<li><strong>' . htmlspecialchars($review['reviewerName']) . ':</strong> ' . htmlspecialchars($review['reviewText']) . ' (' . $review['submissionTime'] . ')</li>';
        }
        $html .= '</ul>';
        return $html;
    } else {
        return "<p>No reviews found for this product.</p>";
    }
}


// Example Usage:
$productId = 'product123';

// Submit a review
$reviewResult = submitReview($productId, "This is a fantastic product! I highly recommend it.", "Alice Brown");

if ($reviewResult['success']) {
    echo "<h3>Review submitted:</h3>";
    echo "<pre>";
    print_r($reviewResult['review']); // Display the submitted review details.
    echo "</pre>";
} else {
    echo "<p style='color:red;'>Error submitting review: " . $reviewResult['message'] . "</p>";
}

// Display the reviews
echo "<h3>Reviews for " . htmlspecialchars($productId) . ":</h3>";
echo displayReviews($productId);



?>
