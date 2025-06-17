

<?php

/**
 * User Review Function - Processes and displays user reviews.
 */
class UserReviewFunction
{
    /**
     * Processes a single user review.
     *
     * @param string $reviewText  The review text provided by the user.
     * @param int    $user_id     The ID of the user submitting the review.
     * @param int    $product_id  The ID of the product being reviewed.
     * @return array  An array containing the processed review data.
     */
    public function processReview(string $reviewText, int $user_id, int $product_id)
    {
        // Sanitize input (important for security)
        $reviewText = htmlspecialchars($reviewText);
        $reviewText = trim($reviewText); // Remove leading/trailing whitespace

        // Basic validation (you'd likely want more robust validation in a real application)
        if (empty($reviewText)) {
            return [
                'success' => false,
                'message' => 'Review cannot be empty.',
                'review' => null,
                'errors' => ['review' => 'Review cannot be empty.']
            ];
        }

        // Create a review object (could be more sophisticated if needed)
        $review = [
            'user_id' => $user_id,
            'product_id' => $product_id,
            'review_text' => $reviewText,
            'date_created' => date('Y-m-d H:i:s') // Add timestamp
        ];

        return [
            'success' => true,
            'message' => 'Review submitted successfully!',
            'review' => $review,
            'errors' => []
        ];
    }

    /**
     * Displays a list of reviews for a product.
     *
     * @param array $reviews  An array of review data.
     * @return string        HTML representation of the reviews.
     */
    public function displayReviews(array $reviews)
    {
        if (empty($reviews)) {
            return "<p>No reviews yet.</p>";
        }

        $html = '<ul>';
        foreach ($reviews as $review) {
            if ($review['success']) {
                $html .= '<li>';
                $html .= '<strong>' . htmlspecialchars($review['review_text']) . '</strong>' .  //Escape for HTML output
                         '<br>'.
                         'By User ID: ' . $review['user_id']; // Include user ID
                $html .= '</li>';
            } else {
                $html .= '<li>Error processing review: ' . htmlspecialchars($review['message']) . '</li>';
            }
        }
        $html .= '</ul>';
        return $html;
    }
}

// Example Usage (for testing)
$reviewFunction = new UserReviewFunction();

// Test 1: Successful Review
$reviewResult1 = $reviewFunction->processReview('This product is amazing!', 123, 456);
echo $reviewFunction->displayReviews($reviewResult1['review']); // Prints the HTML of the review

// Test 2: Empty Review
$reviewResult2 = $reviewFunction->processReview('', 789, 101);
echo $reviewFunction->displayReviews($reviewResult2['review']); // Shows "No reviews yet." or error message

// Test 3:  Error handling
$reviewResult3 = $reviewFunction->processReview('Invalid review', 999, 111);
echo $reviewFunction->displayReviews($reviewResult3['review']);  //Shows an error message.
?>
