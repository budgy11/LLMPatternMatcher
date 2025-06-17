

<?php

/**
 *  User Review Function - A basic implementation.
 *
 *  This function allows users to submit and view reviews for a product or item.
 *  It includes basic input validation and output formatting.
 *
 *  Future improvements could include:
 *      - Secure database storage
 *      - Image uploading
 *      - More robust validation and sanitization
 *      - Rating system
 *      - Moderation features
 *
 * @param string $productName The name of the product being reviewed.
 * @param string $reviewText The review text submitted by the user.
 * @return array An array containing:
 *               - 'success': True if the review was submitted successfully.
 *               - 'message': A message indicating the status of the review.
 *               - 'review': The submitted review text.
 */
function submitReview(string $productName, string $reviewText) {
    // Basic input validation -  ensure review is not empty
    if (empty($reviewText)) {
        return [
            'success' => false,
            'message' => 'Review text cannot be empty.',
            'review' => $reviewText // Return the input to show what was entered.
        ];
    }

    // Add your database interaction here.  This is just a placeholder.
    // In a real application, you would connect to a database and insert the review.
    // For demonstration purposes, we'll just store it in a session variable.

    if (session_status() === 'closed') {
        session_start();
    }

    $review = $reviewText;
    $_SESSION['reviews'][$productName] = $review;


    return [
        'success' => true,
        'message' => 'Review submitted successfully!',
        'review' => $reviewText
    ];
}



/**
 * Displays existing reviews for a given product.
 *
 * @param string $productName The name of the product to display reviews for.
 */
function displayReviews(string $productName)
{
    if (session_status() === 'closed') {
        session_start();
    }

    // Check if reviews exist for this product
    if (isset($_SESSION['reviews'][$productName])) {
        echo "<h2>Reviews for " . $productName . "</h2>";
        echo "<ul>";
        foreach ($_SESSION['reviews'][$productName] as $review) {
            echo "<li>" . $review . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No reviews yet for " . $productName . ".</p>";
    }
}

// Example Usage (Demonstration)

// 1. Submit a review
$reviewResult = submitReview('Awesome Widget', 'This widget is amazing!  It does exactly what I need.');

if ($reviewResult['success']) {
    echo '<p>' . $reviewResult['message'] . '</p>';
    echo '<p>Review: ' . $reviewResult['review'] . '</p>';
} else {
    echo '<p>' . $reviewResult['message'] . '</p>';
}

// 2. Display the reviews
displayReviews('Awesome Widget');

?>
