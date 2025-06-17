

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product or service.
 * It includes input validation and basic sanitization.
 *
 * @param string $productId The ID of the product or service being reviewed.
 * @param string $reviewText The review text submitted by the user.
 * @param string $username The username of the user submitting the review.
 * @return array An array containing:
 *   - 'success': True if the review was successfully submitted, False otherwise.
 *   - 'message': A message indicating the status of the review submission.
 *   - 'review': The submitted review data (success only).
 */
function submitReview(string $productId, string $reviewText, string $username): array
{
    // Input Validation
    if (empty($productId)) {
        return [
            'success' => false,
            'message' => 'Product ID cannot be empty.',
            'review' => null,
        ];
    }

    if (empty($reviewText)) {
        return [
            'success' => false,
            'message' => 'Review text cannot be empty.',
            'review' => null,
        ];
    }

    // Sanitize input (basic example - can be expanded)
    $reviewText = htmlspecialchars($reviewText);  // Prevents XSS attacks

    // TODO:  Add more robust sanitization and validation here
    // For example, limit review length, allow only certain characters, etc.

    // Store the review (Placeholder - Replace with your database logic)
    // This is just a simulation - in a real application, you'd save this data to a database.
    $reviewData = [
        'productId' => $productId,
        'username' => $username,
        'reviewText' => $reviewText,
        'timestamp' => time()  // Add timestamp for ordering
    ];

    // Simulate saving to database
    //saveReviewToDatabase($reviewData);  // Replace with your actual database saving function

    // Return successful response
    return [
        'success' => true,
        'message' => 'Review submitted successfully!',
        'review' => $reviewData,
    ];
}


/**
 * Function to display the review based on product ID.  This is just a placeholder.
 *
 * @param string $productId The ID of the product or service.
 * @return string The HTML display of the review.
 */
function displayReview(string $productId): string
{
    // Replace with your database query to retrieve the review for the given product ID
    // This is just a demo - using a hardcoded review for this example.

    $reviews = [
        'product1' => [
            'username' => 'John Doe',
            'reviewText' => 'Great product!  I highly recommend it.',
            'timestamp' => time()
        ],
        'product2' => [
            'username' => 'Jane Smith',
            'reviewText' => 'The service was excellent. Very helpful staff.',
            'timestamp' => time()
        ]
    ];

    if (isset($reviews[$productId])) {
        return sprintf(
            '<b>Username:</b> %s<br>
            <b>Review:</b> %s<br>
            <b>Timestamp:</b> %s',
            $reviews[$productId]['username'],
            $reviews[$productId]['reviewText'],
            $reviews[$productId]['timestamp']
        );
    } else {
        return 'No reviews found for this product.';
    }
}

// Example Usage:
$productId = 'product1';  // Or 'product2'
$reviewText = 'This is a fantastic review!';
$username = 'Test User';

$result = submitReview($productId, $reviewText, $username);

if ($result['success']) {
    echo "Review submitted successfully!<br>";
    echo "Review Data: " . print_r($result['review'], true) . "<br>";
    echo displayReview($productId); // Display the review
} else {
    echo "Error submitting review: " . $result['message'] . "<br>";
}


?>
