

<?php

/**
 * User Review Function - Allows users to submit and view reviews.
 */

class ReviewSystem {

    private $reviews = [];

    /**
     * Adds a new review.
     *
     * @param string $username The username of the reviewer.
     * @param string $reviewText The text of the review.
     * @return bool True if the review was added successfully, false otherwise.
     */
    public function addReview(string $username, string $reviewText) {
        if (empty($username) || empty($reviewText)) {
            return false; // Invalid input
        }
        $this->reviews[] = ['username' => $username, 'review' => $reviewText, 'date' => date('Y-m-d H:i:s')];
        return true;
    }

    /**
     * Retrieves all reviews.
     *
     * @return array An array of review objects.
     */
    public function getAllReviews() {
        return $this->reviews;
    }

    /**
     * Retrieves a specific review by its ID. (Assumes IDs are sequential)
     *
     * @param int $reviewId The ID of the review to retrieve.
     * @return array|null The review object if found, null otherwise.
     */
    public function getReviewById(int $reviewId) {
        if ($reviewId < 0 || !is_numeric($reviewId)) {
            return null; // Invalid ID
        }

        // Sequential ID assumption.  This is a *very* basic implementation.
        // Consider using a database and proper ID generation for a real application.
        $index = $reviewId - 1; 

        if ($index >= 0 && $index < count($this->reviews)) {
            return $this->reviews[$index];
        }

        return null;
    }


    /**
     * Deletes a review by its ID.
     *
     * @param int $reviewId The ID of the review to delete.
     * @return bool True if the review was deleted successfully, false otherwise.
     */
    public function deleteReview(int $reviewId) {
        if ($reviewId < 0 || !is_numeric($reviewId)) {
            return false; // Invalid ID
        }

        $index = $reviewId - 1;

        if ($index >= 0 && $index < count($this->reviews)) {
            unset($this->reviews[$index]);
            return true;
        }

        return false;
    }

    /**
     * Gets all reviews sorted by date (most recent first).
     *
     * @return array An array of review objects, sorted by date.
     */
    public function getReviewsSortedByDate() {
        return $this->sortByDate($this->getAllReviews());
    }


    /**
     * Helper function to sort reviews by date.
     *
     * @param array $reviews  The array of review objects.
     * @return array The sorted array of review objects.
     */
    private function sortByDate(array $reviews) {
        usort($reviews, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        return $reviews;
    }
}


// Example Usage:
$reviewSystem = new ReviewSystem();

// Add some reviews
$reviewSystem->addReview('JohnDoe', 'Great product! Highly recommended.');
$reviewSystem->addReview('JaneSmith', 'Could be better, but decent.');
$reviewSystem->addReview('PeterJones', 'Excellent value for money.');

// Get all reviews
$allReviews = $reviewSystem->getAllReviews();
echo "All Reviews:
";
print_r($allReviews);

// Get a specific review
$specificReview = $reviewSystem->getReviewById(2);
echo "
Review ID 2:
";
print_r($specificReview);

// Get reviews sorted by date
$sortedReviews = $reviewSystem->getReviewsSortedByDate();
echo "
Sorted Reviews by Date:
";
print_r($sortedReviews);

// Delete a review
$reviewSystem->deleteReview(1);

// Verify deletion
$allReviewsAfterDeletion = $reviewSystem->getAllReviews();
echo "
Reviews after deleting ID 1:
";
print_r($allReviewsAfterDeletion);
?>
