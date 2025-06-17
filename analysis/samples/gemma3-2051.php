

<?php

/**
 * Function to create and display a user review.
 *
 * @param string $username The username of the reviewer.
 * @param string $rating  The rating (e.g., 1-5).
 * @param string $comment The user's review comment.
 * @return string The HTML for the review.
 */
function createReview(string $username, string $rating, string $comment)
{
    // Input Validation (Basic - Expand this for production)
    if (empty($username) || empty($rating) || empty($comment)) {
        return "<p class='error'>Please fill in all fields.</p>";
    }

    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        return "<p class='error'>Rating must be a number between 1 and 5.</p>";
    }

    // Create the review HTML
    $review_html = "<div class='review'>";
    $review_html .= "<div class='review-header'>";
    $review_html .= "<span class='review-username'>" . htmlspecialchars($username) . "</span>";  // Escape for security
    $review_html .= "<span class='review-rating'> - " . $rating . "</span>";
    $review_html .= "</div>";
    $review_html .= "<div class='review-content'>" . htmlspecialchars($comment) . "</div>";
    $review_html .= "</div>";

    return $review_html;
}


/**
 * Example usage and demonstrating how to use the function
 *
 */

// Example data
$username = "JohnDoe";
$rating = 4;
$comment = "Great product, highly recommended!";

// Create the review
$review = createReview($username, $rating, $comment);

// Output the review (for demonstration)
echo $review;


// Example with invalid input:
$invalid_rating = 0;
$invalid_comment = "";

$invalid_review = createReview("JaneSmith", $invalid_rating, $invalid_comment);
echo $invalid_review;
?>
