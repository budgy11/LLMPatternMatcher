

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product or service.
 *
 * @param string $productName The name of the product or service being reviewed.
 * @param string $reviewText The text of the review submitted by the user.
 * @param string $reviewerName The name of the reviewer.
 * @return array An array containing the review data and a success/failure status.
 */
function createAndDisplayReview(string $productName, string $reviewText, string $reviewerName) {
    // Input Validation - This is crucial for security and data integrity
    if (empty($productName)) {
        return ['success' => false, 'message' => 'Product name cannot be empty.', 'review' => null];
    }
    if (empty($reviewText)) {
        return ['success' => false, 'message' => 'Review text cannot be empty.', 'review' => null];
    }
    if (empty($reviewerName)) {
        return ['success' => false, 'message' => 'Reviewer name cannot be empty.', 'review' => null];
    }

    //  You'll typically store reviews in a database here, but for this example, we'll
    //  simulate storing them in an array.
    $reviews = []; //  This is a placeholder -  in a real application, you'd use a database.

    // Create the review data
    $review = [
        'product_name' => $productName,
        'reviewer_name' => $reviewerName,
        'review_text' => $reviewText,
        'created_at' => date('Y-m-d H:i:s') // Add a timestamp for organization
    ];

    // Add the review to the array (simulating database insertion)
    $reviews[] = $review;


    // Return the review data and a success status
    return ['success' => true, 'review' => $review, 'message' => 'Review submitted successfully!'];
}


/**
 * Display Reviews Function
 *
 * This function retrieves and displays reviews for a given product or service.
 *
 * @param string $productName The name of the product or service for which to display reviews.
 * @return array An array containing the reviews and a success/failure status.
 */
function displayReviews(string $productName) {
    // Simulate retrieving reviews from a database.  Replace this with your database query.
    // For demonstration, we'll return some pre-defined reviews.

    if ($productName === 'Awesome Product') {
        $reviews = [
            ['product_name' => 'Awesome Product', 'reviewer_name' => 'John Doe', 'review_text' => 'This product is amazing!  I highly recommend it.', 'created_at' => '2023-10-27 10:00:00'],
            ['product_name' => 'Awesome Product', 'reviewer_name' => 'Jane Smith', 'review_text' => 'Great value for the price.', 'created_at' => '2023-10-26 14:30:00'],
        ];
    } else {
        $reviews = [];  // No reviews for this product.
    }

    return ['success' => true, 'reviews' => $reviews, 'message' => 'Reviews loaded successfully!'];
}



// --- Example Usage ---

// 1. Create a Review
$reviewResult = createAndDisplayReview('Awesome Product', 'This is a fantastic product!', 'Alice Brown');

if ($reviewResult['success']) {
    echo "Review created successfully: " . $reviewResult['message'] . PHP_EOL;
    print_r($reviewResult['review']); // Display the newly created review
} else {
    echo "Error creating review: " . $reviewResult['message'] . PHP_EOL;
}


// 2. Display Reviews for the same product
$displayResult = displayReviews('Awesome Product');

if ($displayResult['success']) {
    echo "Reviews for Awesome Product:" . PHP_EOL;
    print_r($displayResult['reviews']);
} else {
    echo "Error loading reviews: " . $displayResult['message'] . PHP_EOL;
}



?>
