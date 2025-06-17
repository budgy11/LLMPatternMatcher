

<?php

/**
 * Reviews class to handle user reviews.
 */
class Review
{
    /**
     * @var array Reviews data (id, author, rating, comment)
     */
    private $reviews = [];

    /**
     * Adds a new review.
     *
     * @param int $reviewId The unique ID of the review.
     * @param string $author The name of the user who wrote the review.
     * @param int $rating The rating given (e.g., 1-5).
     * @param string $comment The user's comment about the item.
     *
     * @return bool True on success, false on failure (e.g., invalid rating).
     */
    public function addReview(int $reviewId, string $author, int $rating, string $comment): bool
    {
        // Validate input
        if (!is_int($reviewId) || $reviewId <= 0) {
            return false;
        }
        if (!is_string($author) || empty($author)) {
            return false;
        }
        if (!is_int($rating) || $rating < 1 || $rating > 5) {
            return false;
        }
        if (!is_string($comment) || empty($comment)) {
            return false;
        }

        $this->reviews[] = [
            'id' => $reviewId,
            'author' => $author,
            'rating' => $rating,
            'comment' => $comment,
        ];

        return true;
    }


    /**
     * Retrieves a review by its ID.
     *
     * @param int $reviewId The ID of the review to retrieve.
     *
     * @return array|null The review data if found, null otherwise.
     */
    public function getReview(int $reviewId): ?array
    {
        foreach ($this->reviews as $review) {
            if ($review['id'] === $reviewId) {
                return $review;
            }
        }

        return null;
    }

    /**
     * Retrieves all reviews.
     *
     * @return array An array of all reviews.
     */
    public function getAllReviews(): array
    {
        return $this->reviews;
    }

    /**
     * Deletes a review by ID.
     *
     * @param int $reviewId The ID of the review to delete.
     *
     * @return bool True on success, false if the review isn't found.
     */
    public function deleteReview(int $reviewId): bool
    {
        $reviewIndex = -1;
        foreach ($this->reviews as $key => $review) {
            if ($review['id'] === $reviewId) {
                $reviewIndex = $key;
                break;
            }
        }

        if ($reviewIndex !== -1) {
            unset($this->reviews[$reviewIndex]);
            return true;
        }

        return false;
    }

    /**
     * Updates a review by ID.
     *
     * @param int $reviewId The ID of the review to update.
     * @param string $newAuthor The new author name.
     * @param int $newRating The new rating.
     * @param string $newComment The new comment.
     *
     * @return bool True on success, false if the review isn't found or if any validation fails.
     */
    public function updateReview(int $reviewId, string $newAuthor, int $newRating, string $newComment): bool
    {
        $review = $this->getReview($reviewId);
        if (!$review) {
            return false;
        }

        // Validate new inputs
        if (!is_string($newAuthor) || empty($newAuthor)) {
            return false;
        }
        if (!is_int($newRating) || $newRating < 1 || $newRating > 5) {
            return false;
        }
        if (!is_string($newComment) || empty($newComment)) {
            return false;
        }

        $review['author'] = $newAuthor;
        $review['rating'] = $newRating;
        $review['comment'] = $newComment;

        return true;
    }
}



// Example usage:
$reviewSystem = new Review();

// Add reviews
$reviewSystem->addReview(1, "John Doe", 4, "Great product!");
$reviewSystem->addReview(2, "Jane Smith", 5, "Excellent value for money.");
$reviewSystem->addReview(3, "Peter Jones", 3, "Could be better.");

// Get all reviews
$allReviews = $reviewSystem->getAllReviews();
print_r($allReviews);

// Get a specific review
$review = $reviewSystem->getReview(2);
print_r($review);

// Update a review
$updateResult = $reviewSystem->updateReview(2, "Alice Brown", 5, "Fantastic!");
if ($updateResult) {
    print_r($review); // The review will have been updated.
} else {
    echo "Failed to update review.
";
}

// Delete a review
$deleteResult = $reviewSystem->deleteReview(1);
if ($deleteResult) {
    echo "Review deleted successfully.
";
} else {
    echo "Failed to delete review.
";
}

//Try to get deleted review
$deletedReview = $reviewSystem->getReview(1);
if (!$deletedReview) {
    echo "Review with ID 1 no longer exists.
";
}
?>
