

<?php

/**
 * Class UserReview
 *
 * Handles the creation, retrieval, and basic manipulation of user reviews.
 */
class UserReview {

    private $reviews = [];
    private $reviewIdCounter = 1;

    /**
     * Creates a new user review.
     *
     * @param int $userId The ID of the user submitting the review.
     * @param string $productName The name of the product the review is for.
     * @param string $rating The rating given (e.g., 1-5).
     * @param string $comment The user's comment about the product.
     *
     * @return int The ID of the newly created review.
     * @throws InvalidArgumentException If the rating is invalid.
     */
    public function createReview(int $userId, string $productName, string $rating, string $comment): int
    {
        // Validate the rating
        $rating = (int)$rating;
        if ($rating < 1 || $rating > 5) {
            throw new InvalidArgumentException("Invalid rating. Rating must be between 1 and 5.");
        }

        $review = [
            'reviewId' => $this->reviewIdCounter++,
            'userId' => $userId,
            'productName' => $productName,
            'rating' => $rating,
            'comment' => $comment,
            'dateCreated' => date('Y-m-d H:i:s') // Add timestamp for date creation
        ];

        $this->reviews[] = $review;
        return $review['reviewId'];
    }

    /**
     * Retrieves a review by its ID.
     *
     * @param int $reviewId The ID of the review to retrieve.
     *
     * @return array|null The review object if found, null otherwise.
     */
    public function getReview(int $reviewId): ?array
    {
        foreach ($this->reviews as $review) {
            if ($review['reviewId'] === $reviewId) {
                return $review;
            }
        }
        return null;
    }

    /**
     * Retrieves all reviews for a given product.
     *
     * @param string $productName The name of the product to filter by.
     *
     * @return array An array of review objects for the specified product.
     */
    public function getReviewsByProduct(string $productName): array
    {
        $reviews = [];
        foreach ($this->reviews as $review) {
            if ($review['productName'] === $productName) {
                $reviews[] = $review;
            }
        }
        return $reviews;
    }

    /**
     * Updates an existing review.
     *
     * @param int $reviewId The ID of the review to update.
     * @param int $userId The new user ID (optional, if changing user).
     * @param string $productName The new product name (optional, if changing product).
     * @param string $rating The new rating.
     * @param string $comment The new comment.
     *
     * @return bool True if the review was updated successfully, false otherwise.
     */
    public function updateReview(int $reviewId, int $userId = null, string $productName = null, string $rating = null, string $comment = null): bool
    {
        $review = $this->getReview($reviewId);
        if (!$review) {
            return false;
        }

        $review['userId'] = $userId !== null ? $userId : $review['userId'];
        $review['productName'] = $productName !== null ? $productName : $review['productName'];
        $review['rating'] = $rating !== null ? $rating : $review['rating'];
        $review['comment'] = $comment !== null ? $comment : $review['comment'];
        $review['dateCreated'] = date('Y-m-d H:i:s');  //Re-set the date on update

        return true;
    }

    /**
     * Deletes a review by its ID.
     *
     * @param int $reviewId The ID of the review to delete.
     *
     * @return bool True if the review was deleted successfully, false otherwise.
     */
    public function deleteReview(int $reviewId): bool
    {
        foreach ($this->reviews as $key => $review) {
            if ($review['reviewId'] === $reviewId) {
                unset($this->reviews[$key]);
                return true;
            }
        }
        return false;
    }

    /**
     * Returns all reviews.
     *
     * @return array An array of review objects.
     */
    public function getAllReviews(): array
    {
        return $this->reviews;
    }

    // Add other methods as needed, like sorting, filtering, etc.
}


// Example Usage:
try {
    $reviews = new UserReview();

    // Create a review
    $reviewId = $reviews->createReview(1, 'Laptop X1', 4, 'Great laptop, fast and reliable!');

    // Retrieve the review
    $review = $reviews->getReview($reviewId);
    print_r($review);

    // Get reviews for a specific product
    $laptopReviews = $reviews->getReviewsByProduct('Laptop X1');
    print_r($laptopReviews);

    //Update a review
    $reviews->updateReview($reviewId, 2, 'Laptop X1', 5, 'Absolutely amazing!');

    // Get the updated review
    $updatedReview = $reviews->getReview($reviewId);
    print_r($updatedReview);

    //Delete a review
    $reviews->deleteReview($reviewId);

    // Get all reviews
    $allReviews = $reviews->getAllReviews();
    print_r($allReviews);


} catch (InvalidArgumentException $e) {
    echo "Error: " . $e->getMessage() . "
";
}
?>
