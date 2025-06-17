

<?php

/**
 * User Review Function
 *
 * This function takes a set of user reviews and generates a formatted summary.
 * It calculates average rating and provides a list of reviews.
 *
 * @param array $reviews An array of review objects. Each object should have:
 *                       - `user`: The user's name.
 *                       - `rating`: The rating (e.g., 1-5).
 *                       - `comment`:  The review comment (optional).
 * @return string A formatted summary of the reviews.
 */
function generateReviewSummary(array $reviews)
{
    if (empty($reviews)) {
        return "No reviews available.";
    }

    $totalRating = 0;
    $reviewCount = count($reviews);
    $validReviews = 0; // Keep track of valid reviews (rating between 1 and 5)

    foreach ($reviews as $review) {
        if (isset($review['rating']) && is_numeric($review['rating']) &&
            $review['rating'] >= 1 && $review['rating'] <= 5) {
            $totalRating += $review['rating'];
            $validReviews++;
        } else {
            // Handle invalid reviews (optional: log, discard, etc.)
            // You could also skip invalid reviews and continue calculation
            // or display a warning message.
            // Example:
            // error_log("Invalid review from " . $review['user'] . ": " . $review['rating']);
        }
    }

    $averageRating = ($validReviews > 0) ? round($totalRating / $validReviews, 2) : 0;

    $summary = "<h2>Review Summary</h2>";
    $summary .= "<h3>Average Rating: " . $averageRating . "</h3>";
    $summary .= "<ul>";

    foreach ($reviews as $review) {
        $summary .= "<li>";
        $summary .= "<strong>User:</strong> " . htmlspecialchars($review['user']) . "<br>";
        $summary .= "<strong>Rating:</strong> " . $review['rating'] . "<br>";
        $summary .= "<strong>Comment:</strong> " . htmlspecialchars($review['comment'] ?? "No comment provided.") . "<br>"; // Handle missing comments
        $summary .= "</li>";
    }

    $summary .= "</ul>";

    return $summary;
}

// Example Usage:

// Sample Reviews
$reviews = [
    ['user' => 'Alice', 'rating' => 4, 'comment' => 'Great product!'],
    ['user' => 'Bob', 'rating' => 5, 'comment' => 'Excellent value for money.'],
    ['user' => 'Charlie', 'rating' => 3, 'comment' => 'It\'s okay, could be better.'],
    ['user' => 'David', 'rating' => 1, 'comment' => 'Not happy with this.'], // Invalid review
    ['user' => 'Eve', 'rating' => 2.5, 'comment' => 'Could use some improvement.'],
];

// Generate the summary
$summary = generateReviewSummary($reviews);

// Output the summary
echo $summary;


?>
