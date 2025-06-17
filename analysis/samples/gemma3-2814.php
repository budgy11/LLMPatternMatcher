

<?php

/**
 * User Review Function
 *
 * This function allows you to process and display user reviews.
 * It includes input validation, data sanitization, and formatting.
 */

class UserReview {

    /**
     * Validates review input.
     *
     * @param array $reviewData Associative array containing the review data.
     * @return bool True if the review is valid, false otherwise.
     */
    public static function validateReview(array $reviewData) {
        // Required fields
        $requiredFields = ['user_name', 'review_text'];

        // Check for required fields
        foreach ($requiredFields as $field) {
            if (!isset($reviewData[$field]) || empty($reviewData[$field])) {
                return false;
            }
        }

        // Perform basic validation (e.g., string length, non-HTML characters)
        $userName = trim($reviewData['user_name']);
        $reviewText = trim($reviewData['review_text']);

        if (strlen($userName) > 100) {
            return false; // Limit username length
        }
        if (strlen($reviewText) > 1000) {
            return false; // Limit review text length
        }
        
        // You can add more specific validation here, such as:
        // - Allowing only alphanumeric characters (or specific allowed chars)
        // - Checking for prohibited words
        

        return true;
    }

    /**
     * Sanitizes review text to prevent XSS attacks.
     *
     * @param string $reviewText The review text to sanitize.
     * @return string Sanitized review text.
     */
    public static function sanitizeReviewText(string $reviewText) {
        // Use htmlspecialchars to escape potentially malicious characters
        return htmlspecialchars($reviewText, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Formats the review for display.
     *
     * @param array $reviewData Associative array containing the review data.
     * @return string Formatted review string.
     */
    public static function formatReview(array $reviewData) {
        $sanitizedReviewText = self::sanitizeReviewText($reviewData['review_text']);
        return "<div class='review'><strong>" . $reviewData['user_name'] . ":</strong> " . $sanitizedReviewText . "</div>";
    }
}

// Example Usage:

// 1. Get Review Data (e.g., from a form submission)
$reviewData = [
    'user_name' => 'John Doe',
    'review_text' => "<p>This product is <strong>amazing</strong>!</p>" // This is potentially unsafe if not sanitized.
];

// 2. Validate the Review
if (UserReview::validateReview($reviewData)) {
    // 3. Sanitize the Review Text
    $sanitizedReviewText = UserReview::sanitizeReviewText($reviewData['review_text']);

    // 4. Format the Review
    $formattedReview = UserReview::formatReview($reviewData);

    // 5. Display the Review
    echo $formattedReview;
} else {
    echo "<p>Invalid review. Please check your input.</p>";
}


?>
