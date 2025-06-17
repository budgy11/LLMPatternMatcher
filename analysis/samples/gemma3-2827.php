

<?php

/**
 * Class Review
 *
 * This class provides functionality to store and manage user reviews.
 *
 * Features:
 * - Stores reviews in a simple array.
 * - Allows adding new reviews.
 * - Allows retrieving all reviews.
 * - Allows retrieving a review by its ID.
 * - Allows deleting a review by its ID.
 */
class Review {

    private $reviews = [];
    private $reviewIdCounter = 1; // To ensure unique IDs

    /**
     * Adds a new review to the reviews array.
     *
     * @param string $author  The name of the reviewer.
     * @param string $comment The text of the review.
     * @return int|null The ID of the newly created review, or null if there was an error.
     */
    public function addReview(string $author, string $comment) {
        $reviewId = $this->reviewIdCounter;
        $review = [
            'id' => $reviewId,
            'author' => $author,
            'comment' => $comment,
            'date' => date('Y-m-d H:i:s') // Add timestamp for date
        ];
        $this->reviews[] = $review;
        $this->reviewIdCounter++;
        return $reviewId;
    }

    /**
     * Retrieves all reviews.
     *
     * @return array An array containing all reviews.
     */
    public function getAllReviews() {
        return $this->reviews;
    }

    /**
     * Retrieves a review by its ID.
     *
     * @param int $reviewId The ID of the review to retrieve.
     * @return array|null The review object if found, or null if not found.
     */
    public function getReviewById(int $reviewId) {
        foreach ($this->reviews as $review) {
            if ($review['id'] == $reviewId) {
                return $review;
            }
        }
        return null;
    }

    /**
     * Deletes a review by its ID.
     *
     * @param int $reviewId The ID of the review to delete.
     * @return bool True if the review was deleted, false if not found.
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
}


/**
 * Example Usage - Demonstrates how to use the Review class.
 */
$reviewManager = new Review();

// Add some reviews
$reviewId1 = $reviewManager->addReview('Alice', 'Great product! Highly recommended.');
$reviewId2 = $reviewManager->addReview('Bob', 'It was okay, but a bit expensive.');
$reviewId3 = $reviewManager->addReview('Charlie', 'Excellent service and fast delivery.');

// Get all reviews
$allReviews = $reviewManager->getAllReviews();
echo "All Reviews:
";
print_r($allReviews);

// Get a specific review
$review = $reviewManager->getReviewById($reviewId2);
echo "
Review ID " . $reviewId2 . ":
";
print_r($review);

// Delete a review
$deleted = $reviewManager->deleteReview($reviewId1);
echo "
Deleted review with ID " . $reviewId1 . ": " . ($deleted ? 'Success' : 'Failed') . "
";

// Get all reviews again to see the changes
$allReviews = $reviewManager->getAllReviews();
echo "
All Reviews After Deletion:
";
print_r($allReviews);


?>
