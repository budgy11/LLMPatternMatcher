

<?php

/**
 * Class Review
 *
 * Handles the creation, retrieval, and display of user reviews.
 */
class Review {

    private $reviews = []; // Array to store reviews (simplified for this example)

    /**
     * Adds a new review to the database (simulated).
     *
     * @param int $productId The ID of the product being reviewed.
     * @param string $name The reviewer's name.
     * @param string $comment The review comment.
     * @param int $rating The rating (1-5).
     * @return bool True on success, false on failure.
     */
    public function addReview(int $productId, string $name, string $comment, int $rating): bool
    {
        // Basic input validation - improve this in a real application!
        if (!$productId || !$name || !$comment || $rating < 1 || $rating > 5) {
            return false;
        }

        $review = [
            'productId' => $productId,
            'name' => $name,
            'comment' => $comment,
            'rating' => $rating,
            'date' => date('Y-m-d H:i:s') // Add timestamp for review date
        ];

        $this->reviews[] = $review;
        return true;
    }

    /**
     * Retrieves all reviews for a specific product.
     *
     * @param int $productId The ID of the product.
     * @return array An array of reviews for the product, or an empty array if none are found.
     */
    public function getReviewsByProduct(int $productId): array
    {
        return array_filter($this->reviews, function($review) => $review['productId'] === $productId);
    }

    /**
     * Retrieves a single review by its ID (simulated).  In a real database, you'd use a unique ID.
     *
     * @param int $reviewId The ID of the review to retrieve.
     * @return array|null The review if found, null otherwise.
     */
    public function getReviewById(int $reviewId): ?array
    {
        foreach ($this->reviews as $review) {
            if ($review['productId'] === $reviewId) {  //  Consider using a real unique ID
                return $review;
            }
        }
        return null;
    }


    /**
     * Displays reviews for a product.
     *
     * @param int $productId The ID of the product.
     */
    public function displayReviews(int $productId)
    {
        $reviews = $this->getReviewsByProduct($productId);

        if (empty($reviews)) {
            echo "<p>No reviews found for this product.</p>";
        } else {
            echo "<h2>Reviews for Product ID: " . $productId . "</h2>";
            echo "<ul>";
            foreach ($reviews as $review) {
                echo "<li>";
                echo "<strong>Name:</strong> " . htmlspecialchars($review['name']) . "<br>";
                echo "<strong>Comment:</strong> " . htmlspecialchars($review['comment']) . "<br>";
                echo "<strong>Rating:</strong> " . $review['rating'] . " / 5<br>";
                echo "<strong>Date:</strong> " . $review['date'] . "</li>";
            }
            echo "</ul>";
        }
    }
}


// Example Usage:
$reviewSystem = new Review();

// Add some reviews
$reviewSystem->addReview(123, "John Doe", "Great product!", 5);
$reviewSystem->addReview(123, "Jane Smith", "Could be better.", 3);
$reviewSystem->addReview(456, "Peter Jones", "Excellent value!", 4);

// Display reviews for product 123
$reviewSystem->displayReviews(123);

// Display reviews for product 456
$reviewSystem->displayReviews(456);

//Get a review by ID (simulated)
$review = $reviewSystem->getReviewById(1);
if ($review) {
    echo "<p>Found Review: " . htmlspecialchars($review['name']) . " - " . htmlspecialchars($review['comment']) . "</p>";
} else {
    echo "<p>Review not found.</p>";
}

?>
