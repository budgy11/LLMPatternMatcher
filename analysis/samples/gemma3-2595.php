

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product or service.
 *
 * @param string $productId The unique identifier for the product or service being reviewed.
 * @param string $productName The name of the product or service.
 * @param string $reviewText The review text entered by the user.
 * @param int $rating The rating given by the user (1-5).
 * @param int $userId (Optional) The ID of the user submitting the review.  Useful for tracking.
 * @return array  An array containing the review data and an error message (if any).
 */
function submitReview(
    string $productId,
    string $productName,
    string $reviewText,
    int $rating,
    int $userId = null  // Optional: User ID
) {

    // Input Validation - Crucial for security and data integrity
    if (empty($reviewText)) {
        return ['success' => false, 'message' => "Review text cannot be empty."];
    }

    if ($rating < 1 || $rating > 5) {
        return ['success' => false, 'message' => "Rating must be between 1 and 5."];
    }


    //  **IMPORTANT SECURITY MEASURE: Sanitize and Validate Input**
    //  This is extremely important to prevent XSS and SQL injection attacks.
    //  This example uses basic sanitization - for production, use a robust library.
    $reviewText = htmlspecialchars($reviewText, ENT_QUOTES, 'UTF-8');  // Escape HTML special chars
    //  Add more sanitization/validation here depending on your requirements,
    //  e.g., length checks, character restrictions, etc.


    // **Data Storage (Simulated for demonstration)**
    // In a real application, you would save this data to a database.
    $review = [
        'productId' => $productId,
        'productName' => $productName,
        'reviewText' => $reviewText,
        'rating' => $rating,
        'userId' => $userId,
        'submissionDate' => date('Y-m-d H:i:s') // Add timestamp
    ];

    // **Logging (Good Practice)** - For debugging and analysis.
    // error_log("New review submitted: " . json_encode($review));

    return ['success' => true, 'review' => $review, 'message' => "Review submitted successfully!"];
}



/**
 * Display Reviews Function
 *
 * This function retrieves and displays reviews for a given product.
 *
 * @param string $productId The unique identifier for the product.
 * @return array An array containing the reviews (if any) and a message.
 */
function displayReviews(string $productId) {
    // **Simulated Data Retrieval (Replace with database query)**
    //  In a real application, you would query a database to get reviews.
    $reviews = [
        'review1' => [
            'productId' => 'prod123',
            'productName' => 'Awesome Widget',
            'reviewText' => 'This widget is amazing!  Great value.',
            'rating' => 5,
            'userId' => 1,
            'submissionDate' => '2023-10-27 10:00:00'
        ],
        'review2' => [
            'productId' => 'prod123',
            'productName' => 'Awesome Widget',
            'reviewText' => 'It\'s okay, but could be better.',
            'rating' => 3,
            'userId' => 2,
            'submissionDate' => '2023-10-26 14:30:00'
        ],
    ];

    // Filter reviews for the given product ID
    $productReviews = [];
    foreach ($reviews as $key => $review) {
        if ($review['productId'] == $productId) {
            $productReviews[$key] = $review;
        }
    }


    return ['reviews' => $productReviews, 'message' => "Reviews for " . $productId . " loaded."];
}


// ------------------- Example Usage -------------------

// Submit a review
$result = submitReview('prod123', 'Awesome Widget', 'This is the best widget ever!', 5, 1);

if ($result['success']) {
    echo "Review submitted successfully!<br>";
    print_r($result['review']);
} else {
    echo "Error submitting review: " . $result['message'] . "<br>";
}


// Display reviews for the product
$reviewResults = displayReviews('prod123');

if ($reviewResults['success']) {
    echo "<h3>Reviews for Awesome Widget:</h3><br>";
    if (empty($reviewResults['reviews'])) {
        echo "No reviews yet.";
    } else {
        echo "<ul>";
        foreach ($reviewResults['reviews'] as $review) {
            echo "<li>";
            echo "<strong>Product:</strong> " . $review['productName'] . "<br>";
            echo "<strong>Rating:</strong> " . $review['rating'] . "/5<br>";
            echo "<strong>Review:</strong> " . $review['reviewText'] . "<br>";
            echo "<strong>Submitted by:</strong> User " . $review['userId'] . "<br>";
            echo "<strong>Date:</strong> " . $review['submissionDate'] . "</li>";
        }
        echo "</ul>";
    }
} else {
    echo "Error loading reviews: " . $reviewResults['message'];
}

?>
