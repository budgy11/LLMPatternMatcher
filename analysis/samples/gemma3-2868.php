

<?php

/**
 * Reviews class for managing user reviews.
 */
class Reviews {

    private $reviews = [];

    /**
     * Adds a new review.
     *
     * @param string $user  The user who wrote the review.
     * @param string $comment The content of the review.
     * @return bool True if the review was added successfully, false otherwise.
     */
    public function addReview(string $user, string $comment) {
        $id = uniqid(); // Generate a unique ID for the review
        $this->reviews[$id] = [
            'user' => $user,
            'comment' => $comment,
            'date' => date('Y-m-d H:i:s') // Add timestamp for review date
        ];
        return true;
    }

    /**
     * Retrieves all reviews.
     *
     * @return array An array of all reviews.  Returns an empty array if none exist.
     */
    public function getAllReviews() {
        return $this->reviews;
    }

    /**
     * Retrieves a specific review by its unique ID.
     *
     * @param string $id The unique ID of the review.
     * @return array|null The review object if found, null otherwise.
     */
    public function getReviewById(string $id) {
        if (isset($this->reviews[$id])) {
            return $this->reviews[$id];
        }
        return null;
    }

    /**
     * Deletes a review by its unique ID.
     *
     * @param string $id The unique ID of the review to delete.
     * @return bool True if the review was deleted successfully, false otherwise.
     */
    public function deleteReview(string $id) {
        if (isset($this->reviews[$id])) {
            unset($this->reviews[$id]);
            return true;
        }
        return false;
    }

    /**
     * Updates an existing review.
     *
     * @param string $id The unique ID of the review to update.
     * @param string $newComment The new content of the review.
     * @return bool True if the review was updated successfully, false otherwise.
     */
    public function updateReview(string $id, string $newComment) {
        if (isset($this->reviews[$id])) {
            $this->reviews[$id]['comment'] = $newComment;
            return true;
        }
        return false;
    }
}


/**
 * Example Usage:
 */

// Instantiate the Reviews class
$reviews = new Reviews();

// Add some reviews
$reviews->addReview('John Doe', 'Great product! I highly recommend it.');
$reviews->addReview('Jane Smith', 'Could be better, but overall good.');
$reviews->addReview('Peter Jones', 'Excellent value for money.');

// Get all reviews
$allReviews = $reviews->getAllReviews();
echo "All Reviews:
";
print_r($allReviews);

// Get a specific review
$specificReview = $reviews->getReviewById('unique_id_1');
echo "
Specific Review (Unique ID: unique_id_1):
";
print_r($specificReview);

// Update a review
$reviews->updateReview('unique_id_1', 'Fantastic product, even better than expected!');

// Get the updated review
$updatedReview = $reviews->getReviewById('unique_id_1');
echo "
Updated Review:
";
print_r($updatedReview);

// Delete a review
$reviews->deleteReview('unique_id_2');

// Get all reviews again to see the deleted review is gone
$allReviews = $reviews->getAllReviews();
echo "
All Reviews after deletion:
";
print_r($allReviews);

?>
