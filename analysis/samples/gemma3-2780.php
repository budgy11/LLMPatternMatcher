

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or service.
 * It includes error handling and basic input validation.
 *
 * @param string $productId The ID of the product or service being reviewed.
 * @param string $userName The name of the user submitting the review.
 * @param string $reviewText The text of the review.
 * @param int $rating   (Optional) The rating given by the user (1-5). Defaults to 0.
 * @return array|string Returns an array of reviews if successful, or an error message string if there's an issue.
 */
function storeUserReview(string $productId, string $userName, string $reviewText, int $rating = 0) {
    // Input Validation - Basic checks
    if (empty($productId)) {
        return "Error: Product ID cannot be empty.";
    }
    if (empty($userName)) {
        return "Error: User name cannot be empty.";
    }
    if (empty($reviewText)) {
        return "Error: Review text cannot be empty.";
    }
    if ($rating < 1 || $rating > 5) {
        return "Error: Rating must be between 1 and 5.";
    }


    //  Simulate storing the review in a database (replace with your actual database logic)
    //  This is a simplified example - use proper database queries for real applications.
    $review = [
        'productId' => $productId,
        'userName' => $userName,
        'reviewText' => $reviewText,
        'rating' => $rating,
        'timestamp' => time() // Add a timestamp for tracking
    ];

    // Store the review in an array (for this example)
    // In a real application, you'd insert this data into a database.
    $storedReviews = [
        'reviews' => [
            $review
        ]
    ];


    return $storedReviews;
}


/**
 * Display User Reviews
 *
 * This function retrieves and displays user reviews for a given product ID.
 *
 * @param array $reviews An array of reviews (returned from storeUserReview or loaded from database).
 * @return string HTML to display the reviews.
 */
function displayReviews(array $reviews) {
    if (empty($reviews['reviews'])) {
        return "<p>No reviews yet.</p>";
    }

    $html = "<h2>Reviews for Product ID: " . $reviews['reviews'][0]['productId'] . "</h2>"; // Display product ID

    foreach ($reviews['reviews'] as $review) {
        $html .= "<div class='review'>";
        $html .= "<p><strong>User:</strong> " . htmlspecialchars($review['userName']) . "</p>"; // Use htmlspecialchars for security
        $html .= "<p>" . htmlspecialchars($review['reviewText']) . "</p>";
        $html .= "<p><strong>Rating:</strong> " . $review['rating'] . "</p>";
        $html .= "<div class='timestamp'>" . date('Y-m-d H:i:s', $review['timestamp']) . "</div>";
        $html .= "</div>";
    }

    return $html;
}


// --- Example Usage ---

// Store a review
$reviewData = storeUserReview("123", "John Doe", "This is a great product!", 5);

if (is_array($reviewData)) {
    if (isset($reviewData['error'])) {
        echo "<p>Error: " . $reviewData['error'] . "</p>";
    } else {
        echo "Review stored successfully!";
        // Display the reviews
        $reviews = storeUserReview("123", "Jane Smith", "It's okay.", 3); //Another review

        echo displayReviews($reviews); //Display the reviews

    }
} else {
    echo "Error: " . $reviewData;
}

?>
