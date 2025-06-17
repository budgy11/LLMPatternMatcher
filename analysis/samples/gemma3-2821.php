

<?php

/**
 * Class Review
 *
 * Provides functionality to store, retrieve, and display user reviews.
 */
class Review {

    private $reviews = [];

    /**
     * Adds a new review to the database.
     *
     * @param int $productId The ID of the product being reviewed.
     * @param string $username The username of the reviewer.
     * @param string $rating The rating given by the reviewer (e.g., 1-5).
     * @param string $comment The comment provided by the reviewer.
     *
     * @return bool True on success, false on failure (e.g., invalid rating).
     */
    public function addReview(int $productId, string $username, string $rating, string $comment): bool {
        // Validate rating
        if (!preg_match('/^\d+$/', $rating)) {
            return false; // Invalid rating
        }

        // Create a review object
        $review = [
            'productId' => $productId,
            'username' => $username,
            'rating' => $rating,
            'comment' => $comment,
            'timestamp' => time()
        ];

        // Store the review
        $this->reviews[] = $review;
        return true;
    }

    /**
     * Retrieves all reviews for a specific product.
     *
     * @param int $productId The ID of the product.
     *
     * @return array An array of review objects for the product, or an empty array if no reviews exist.
     */
    public function getReviewsForProduct(int $productId): array {
        return array_filter($this->reviews, function ($review) use ($productId) {
            return $review['productId'] == $productId;
        });
    }

    /**
     * Retrieves the average rating for a specific product.
     *
     * @param int $productId The ID of the product.
     *
     * @return float|null The average rating, or null if no reviews exist for the product.
     */
    public function getAverageRatingForProduct(int $productId): ?float {
        $reviews = $this->getReviewsForProduct($productId);

        if (empty($reviews)) {
            return null;
        }

        $totalRating = 0;
        foreach ($reviews as $review) {
            $totalRating += (int)$review['rating'];
        }

        return $totalRating / count($reviews);
    }


    /**
     * Displays reviews for a product in a user-friendly format.
     *
     * @param int $productId The ID of the product.
     *
     * @return string The HTML representation of the reviews, or a message if no reviews exist.
     */
    public function displayReviews(int $productId): string {
        $reviews = $this->getReviewsForProduct($productId);

        if (empty($reviews)) {
            return "<p>No reviews for this product.</p>";
        }

        $html = "<h2>Reviews for Product ID: " . $productId . "</h2>";
        $html .= "<ul>";
        foreach ($reviews as $review) {
            $html .= "<li>";
            $html .= "<strong>" . $review['username'] . "</strong> - Rating: " . $review['rating'] . " - " . $review['comment'] . "</li>";
            $html .= "</li>";
        }
        $html .= "</ul>";

        return $html;
    }
}



// Example Usage:

// Create a Review object
$reviewSystem = new Review();

// Add some reviews
$reviewSystem->addReview(123, "JohnDoe", 5, "Excellent product!  Highly recommended.");
$reviewSystem->addReview(123, "JaneSmith", 4, "Good product, but could be better.");
$reviewSystem->addReview(456, "PeterJones", 3, "Okay, does the job.");


// Get reviews for product 123
$reviews = $reviewSystem->getReviewsForProduct(123);
echo $reviewSystem->displayReviews(123); // Display the reviews for product 123

// Get the average rating for product 123
$averageRating = $reviewSystem->getAverageRatingForProduct(123);
echo "<br>Average Rating for Product 123: " . ($averageRating !== null ? $averageRating : "No reviews yet.");

?>
