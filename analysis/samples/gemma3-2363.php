

<?php

/**
 * Function to create, retrieve, update, and delete user reviews.
 *
 * This function demonstrates a basic user review system. 
 * You'll likely want to adapt this for a real-world application 
 * with database integration, security measures, and more robust validation.
 */

class ReviewSystem {

    private $reviews = [];  // Store reviews (for simplicity, in memory)

    /**
     * Adds a new review.
     *
     * @param int $productId The ID of the product the review is for.
     * @param string $username The username of the reviewer.
     * @param string $rating The rating given (e.g., 1-5).
     * @param string $comment The user's review comment.
     * @return int|null The ID of the newly created review, or null on failure.
     */
    public function addReview(int $productId, string $username, string $rating, string $comment) {
        // Input Validation (VERY IMPORTANT!)
        if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
            return null; // Invalid rating
        }

        if (empty($username) || empty($comment)) {
            return null; // Missing username or comment
        }

        $reviewId = count($this->reviews) + 1; // Simple ID generation
        $review = [
            'id' => $reviewId,
            'productId' => $productId,
            'username' => $username,
            'rating' => $rating,
            'comment' => $comment,
            'date' => date('Y-m-d H:i:s') // Add timestamp for record keeping
        ];

        $this->reviews[] = $review;
        return $reviewId;
    }

    /**
     * Retrieves a review by ID.
     *
     * @param int $reviewId The ID of the review to retrieve.
     * @return array|null The review data, or null if not found.
     */
    public function getReview(int $reviewId) {
        foreach ($this->reviews as $review) {
            if ($review['id'] == $reviewId) {
                return $review;
            }
        }
        return null;
    }

    /**
     * Updates an existing review.
     *
     * @param int $reviewId The ID of the review to update.
     * @param int $productId The new product ID (if updating).
     * @param string $username The new username.
     * @param string $rating The new rating.
     * @param string $comment The new comment.
     * @return bool True if updated successfully, false otherwise.
     */
    public function updateReview(int $reviewId, int $productId = null, string $username = null, string $rating = null, string $comment = null) {
        $review = $this->getReview($reviewId);

        if (!$review) {
            return false; // Review not found
        }

        // Perform validation here (similar to addReview)

        if ($productId !== null) {
            $review['productId'] = $productId;
        }
        if ($username !== null) {
            $review['username'] = $username;
        }
        if ($rating !== null) {
            if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
                return false; // Invalid rating
            }
            $review['rating'] = $rating;
        }
        if ($comment !== null) {
            $review['comment'] = $comment;
        }

        return true;
    }

    /**
     * Deletes a review by ID.
     *
     * @param int $reviewId The ID of the review to delete.
     * @return bool True if deleted successfully, false otherwise.
     */
    public function deleteReview(int $reviewId) {
        foreach ($this->reviews as $key => $review) {
            if ($review['id'] == $reviewId) {
                unset($this->reviews[$key]);
                return true;
            }
        }
        return false;
    }

    /**
     * Retrieves all reviews for a specific product.
     *
     * @param int $productId The ID of the product.
     * @return array An array of review objects.
     */
    public function getReviewsForProduct(int $productId) {
        $reviews = [];
        foreach ($this->reviews as $review) {
            if ($review['productId'] == $productId) {
                $reviews[] = $review;
            }
        }
        return $reviews;
    }
}


// Example Usage:
$reviewSystem = new ReviewSystem();

// Add a review
$reviewId = $reviewSystem->addReview(123, 'JohnDoe', 4, 'Great product, highly recommended!');

if ($reviewId) {
    echo "Review added with ID: " . $reviewId . "
";
} else {
    echo "Failed to add review.
";
}

// Get the review by ID
$review = $reviewSystem->getReview($reviewId);
if ($review) {
    echo "Review details: " . print_r($review, true) . "
";
} else {
    echo "Review not found.
";
}

// Update the review
$updateResult = $reviewSystem->updateReview($reviewId, 5, 'JaneSmith', 5, 'Excellent!');
if ($updateResult) {
    echo "Review updated successfully.
";
} else {
    echo "Failed to update review.
";
}

// Delete the review
$deleteResult = $reviewSystem->deleteReview($reviewId);
if ($deleteResult) {
    echo "Review deleted successfully.
";
} else {
    echo "Failed to delete review.
";
}
?>
