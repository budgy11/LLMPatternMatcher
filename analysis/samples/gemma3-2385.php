

<?php

/**
 * Class Review
 *
 * This class provides functionality to manage user reviews.
 */
class Review {

    /**
     * @var array Array of reviews.
     */
    private $reviews = [];

    /**
     * Adds a new review to the reviews array.
     *
     * @param string $user  The name of the user who submitted the review.
     * @param string $comment The content of the review.
     * @param int $rating The rating (1-5).
     * @return bool True on success, false on failure (e.g., invalid rating).
     */
    public function addReview(string $user, string $comment, int $rating): bool {
        // Validate rating
        if ($rating < 1 || $rating > 5) {
            return false;
        }

        $this->reviews[] = [
            'user' => $user,
            'comment' => $comment,
            'rating' => $rating
        ];

        return true;
    }

    /**
     * Gets all reviews.
     *
     * @return array An array of all reviews.
     */
    public function getAllReviews(): array {
        return $this->reviews;
    }

    /**
     * Gets reviews for a specific item (e.g., product, service).
     *
     * @param mixed $item  The item to get reviews for.  This allows you to extend
     *                    this class to handle different types of items.
     * @return array An array of reviews for the item.
     */
    public function getReviewsForItem(mixed $item): array {
        //This is a basic implementation.  You would likely need to extend
        //this based on how you store the item data.

        return $this->getAllReviews(); //For now, return all reviews - you'll need a more sophisticated system.
    }

    /**
     * Calculates the average rating for a specific item.
     *
     * @param mixed $item The item to calculate the average rating for.
     * @return float|null The average rating, or null if no reviews exist.
     */
    public function getAverageRatingForItem(mixed $item) {
        $reviews = $this->getReviewsForItem($item);

        if (empty($reviews)) {
            return null;
        }

        $totalRating = 0;
        foreach ($reviews as $review) {
            $totalRating += $review['rating'];
        }

        return $totalRating / count($reviews);
    }


    /**
     * Formats a review for display.
     *
     * @param array $review
     * @return string The formatted review string.
     */
    public function formatReview(array $review): string {
        return "User: " . $review['user'] . "
" .
               "Comment: " . $review['comment'] . "
" .
               "Rating: " . $review['rating'];
    }
}

// Example Usage:
$reviewSystem = new Review();

// Add some reviews
$reviewSystem->addReview("Alice", "Great product!", 5);
$reviewSystem->addReview("Bob", "It was okay.", 3);
$reviewSystem->addReview("Charlie", "Could be better.", 2);

// Get all reviews
$allReviews = $reviewSystem->getAllReviews();
echo "All Reviews:
";
foreach ($allReviews as $review) {
    echo $review['user'] . ": " . $review['comment'] . " (" . $review['rating'] . ")
";
}

// Get reviews for a specific item (e.g., a product)
$productReviews = $reviewSystem->getReviewsForItem('Product X');
echo "
Reviews for Product X:
";
foreach ($productReviews as $review) {
    echo $review['user'] . ": " . $review['comment'] . " (" . $review['rating'] . ")
";
}

// Get average rating for Product X
$averageRating = $reviewSystem->getAverageRatingForItem('Product X');
echo "
Average Rating for Product X: " . ($averageRating !== null ? $averageRating : "No reviews yet.") . "
";

// Format a specific review
$formattedReview = $reviewSystem->formatReview($allReviews[0]);
echo "
Formatted Review:
" . $formattedReview;
?>
