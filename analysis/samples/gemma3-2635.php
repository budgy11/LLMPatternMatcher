

<?php

/**
 * Class UserReview
 *
 * This class handles the creation, retrieval, and display of user reviews.
 */
class UserReview
{
    private $reviews = [];

    /**
     * Adds a new review to the database.
     *
     * @param int $productId The ID of the product the review is for.
     * @param string $userName The name of the user writing the review.
     * @param string $reviewText The text of the review.
     * @param int $rating   The rating (1-5).
     *
     * @return bool True on success, false on failure (e.g., invalid rating).
     */
    public function addReview(int $productId, string $userName, string $reviewText, int $rating): bool
    {
        if (!isset($productId, $userName, $reviewText)) {
            return false; // Required parameters are missing
        }

        if ($rating < 1 || $rating > 5) {
            return false; // Invalid rating
        }

        $this->reviews[] = [
            'productId' => $productId,
            'userName' => $userName,
            'reviewText' => $reviewText,
            'rating' => $rating,
            'timestamp' => time() // Add a timestamp for sorting/display
        ];

        return true;
    }

    /**
     * Retrieves all reviews for a given product ID.
     *
     * @param int $productId The ID of the product.
     *
     * @return array An array of review objects, or an empty array if no reviews exist.
     */
    public function getReviewsByProductId(int $productId): array
    {
        $productReviews = [];
        foreach ($this->reviews as $review) {
            if ($review['productId'] == $productId) {
                $productReviews[] = $review;
            }
        }
        return $productReviews;
    }

    /**
     * Retrieves all reviews.
     *
     * @return array  An array of all reviews
     */
    public function getAllReviews(): array {
        return $this->reviews;
    }


    /**
     * Displays reviews for a product ID.
     *
     * @param int $productId The ID of the product.
     */
    public function displayReviews(int $productId)
    {
        $reviews = $this->getReviewsByProductId($productId);

        if (empty($reviews)) {
            echo "<p>No reviews found for this product.</p>";
            return;
        }

        echo "<h2>Reviews for Product ID: " . $productId . "</h2>";
        echo "<ul>";
        foreach ($reviews as $review) {
            echo "<li>";
            echo "<strong>" . $review['userName'] . ":</strong> " . $review['reviewText'] . " (Rating: " . $review['rating'] . ") - " . date("Y-m-d H:i:s", $review['timestamp']) . "</li>";
        }
        echo "</ul>";
    }
}


// Example Usage:
$reviewSystem = new UserReview();

// Add some reviews
$reviewSystem->addReview(123, "John Doe", "Great product!  Highly recommended.", 5);
$reviewSystem->addReview(123, "Jane Smith", "Good value for money.", 4);
$reviewSystem->addReview(456, "Peter Jones", "Disappointed with the quality.", 2);

// Display reviews for product 123
$reviewSystem->displayReviews(123);

// Display all reviews
// echo "<h2>All Reviews</h2>";
// echo $reviewSystem->getAllReviews();


?>
