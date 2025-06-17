

<?php

/**
 * User Review Class
 *
 * This class provides a simple way to manage user reviews for a product.
 * It allows adding reviews, retrieving all reviews, and retrieving individual reviews.
 */
class UserReview
{
    private $reviews = [];

    /**
     * Adds a new user review.
     *
     * @param string $username The name of the user submitting the review.
     * @param string $comment The user's review comment.
     * @return bool True if the review was added successfully, false otherwise.
     */
    public function addReview(string $username, string $comment): bool
    {
        // Basic validation - you can expand this with more robust validation
        if (empty($username) || empty($comment)) {
            return false;
        }

        $this->reviews[] = ['username' => $username, 'comment' => $comment];
        return true;
    }

    /**
     * Retrieves all user reviews.
     *
     * @return array An array of review objects.  Returns an empty array if no reviews exist.
     */
    public function getAllReviews(): array
    {
        return $this->reviews;
    }

    /**
     * Retrieves a single user review by username.
     *
     * @param string $username The username of the review to retrieve.
     * @return array|null The review object if found, null otherwise.
     */
    public function getReviewByUsername(string $username): ?array
    {
        foreach ($this->reviews as $review) {
            if ($review['username'] === $username) {
                return $review;
            }
        }
        return null;
    }

    /**
     * Deletes a review by username.
     *
     * @param string $username The username of the review to delete.
     * @return bool True if the review was deleted successfully, false otherwise.
     */
    public function deleteReview(string $username): bool
    {
        foreach ($this->reviews as $key => $review) {
            if ($review['username'] === $username) {
                unset($this->reviews[$key]);
                return true;
            }
        }
        return false;
    }
}


/**
 *  Example Usage and User Review Function
 */

// Create a new review object
$reviewManager = new UserReview();

// Add some reviews
$reviewManager->addReview('JohnDoe', 'Great product!  Highly recommended.');
$reviewManager->addReview('JaneSmith', 'Excellent value for money.');
$reviewManager->addReview('PeterJones', 'Could be better, but decent.');

// Get all reviews
$allReviews = $reviewManager->getAllReviews();
echo "All Reviews:
";
print_r($allReviews);

// Get a specific review
$johnDoeReview = $reviewManager->getReviewByUsername('JohnDoe');
echo "
John Doe's Review:
";
print_r($johnDoeReview);

// Delete a review
$reviewManager->deleteReview('PeterJones');

// Get all reviews again to see the change
$allReviewsAfterDeletion = $reviewManager->getAllReviews();
echo "
All Reviews After Deletion:
";
print_r($allReviewsAfterDeletion);

?>
