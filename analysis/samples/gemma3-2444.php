

<?php

/**
 * User Review Function
 *
 * This function allows you to create, retrieve, update, and delete user reviews.
 * It uses a simple in-memory array to store reviews.  For a production environment,
 * you would likely use a database.
 */

class ReviewManager {

    private $reviews = [];
    private $reviewIdCounter = 1; // To ensure unique IDs

    /**
     * Creates a new user review.
     *
     * @param int $productId The ID of the product the review is for.
     * @param string $username The username of the reviewer.
     * @param string $rating The rating given (e.g., 1-5).
     * @param string $comment The review comment.
     *
     * @return int The ID of the newly created review.
     * @throws InvalidArgumentException if any required input is missing or invalid.
     */
    public function createReview(int $productId, string $username, string $rating, string $comment): int
    {
        // Input validation
        if (!$productId || !$username || !$rating || !$comment) {
            throw new InvalidArgumentException("Missing required input.");
        }
        if (!is_numeric($rating) || (int)$rating < 1 || (int)$rating > 5) {
            throw new InvalidArgumentException("Invalid rating. Rating must be a number between 1 and 5.");
        }

        $reviewId = $this->reviewIdCounter++;
        $this->reviews[$reviewId] = [
            'productId' => $productId,
            'username' => $username,
            'rating' => $rating,
            'comment' => $comment,
            'date' => date('Y-m-d H:i:s') // Add timestamp for review date
        ];

        return $reviewId;
    }


    /**
     * Retrieves a review by its ID.
     *
     * @param int $reviewId The ID of the review to retrieve.
     *
     * @return array The review data if found, or null if not found.
     */
    public function getReview(int $reviewId): ?array
    {
        if (isset($this->reviews[$reviewId])) {
            return $this->reviews[$reviewId];
        }
        return null;
    }


    /**
     * Updates an existing review.
     *
     * @param int $reviewId The ID of the review to update.
     * @param int $productId The new ID of the product (optional).
     * @param string $username The new username (optional).
     * @param string $rating The new rating (optional).
     * @param string $comment The new comment (optional).
     *
     * @return bool True if the review was updated successfully, false otherwise.
     */
    public function updateReview(int $reviewId, int $productId = null, string $username = null, string $rating = null, string $comment = null): bool
    {
        if (!isset($this->reviews[$reviewId])) {
            return false; // Review not found
        }

        $originalReview = $this->reviews[$reviewId];
        $updatedReview = [];

        if ($productId !== null) {
            $updatedReview['productId'] = $productId;
        }
        if ($username !== null) {
            $updatedReview['username'] = $username;
        }
        if ($rating !== null) {
            if (!is_numeric($rating) || (int)$rating < 1 || (int)$rating > 5) {
                return false;  // Invalid rating
            }
            $updatedReview['rating'] = $rating;
        }
        if ($comment !== null) {
            $updatedReview['comment'] = $comment;
        }

        $this->reviews[$reviewId] = $updatedReview;
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
        if (isset($this->reviews[$reviewId])) {
            unset($this->reviews[$reviewId]);
            return true;
        }
        return false;
    }


    /**
     * Retrieves all reviews.
     *
     * @return array An array of all review data.
     */
    public function getAllReviews(): array
    {
        return $this->reviews;
    }
}

// Example Usage:
$reviewManager = new ReviewManager();

// Create a review
$reviewId = $reviewManager->createReview(123, 'JohnDoe', 4, 'Great product, would recommend!');

// Get the review
$review = $reviewManager->getReview($reviewId);
if ($review) {
    echo "Review ID: " . $review['productId'] . "
";
    echo "Username: " . $review['username'] . "
";
    echo "Rating: " . $review['rating'] . "
";
    echo "Comment: " . $review['comment'] . "
";
}

// Update the review
$reviewManager->updateReview($reviewId, 124, 'JaneSmith', 5, 'Excellent value!');

// Get the updated review
$updatedReview = $reviewManager->getReview($reviewId);
if ($updatedReview) {
    echo "
Updated Review:
";
    echo "Username: " . $updatedReview['username'] . "
";
    echo "Rating: " . $updatedReview['rating'] . "
";
    echo "Comment: " . $updatedReview['comment'] . "
";
}

// Delete the review
$reviewManager->deleteReview($reviewId);

// Get all reviews
$allReviews = $reviewManager->getAllReviews();
echo "
All Reviews:
";
print_r($allReviews);

?>
