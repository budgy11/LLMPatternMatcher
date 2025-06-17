

<?php

/**
 * This class provides functionality to store and manage user reviews.
 */
class ReviewManager
{
    private $reviews = [];

    /**
     * Adds a new review to the system.
     *
     * @param int $productId The ID of the product the review is for.
     * @param string $userName The name of the user who wrote the review.
     * @param string $reviewText The text of the review.
     * @param int $rating The rating given for the product (e.g., 1-5).
     * @return bool True if the review was added successfully, false otherwise.
     */
    public function addReview(int $productId, string $userName, string $reviewText, int $rating): bool
    {
        if (!is_int($productId) || $productId <= 0) {
            return false; // Invalid product ID
        }
        if (!is_string($userName) || empty($userName)) {
            return false; // Invalid user name
        }
        if (!is_string($reviewText) || empty($reviewText)) {
            return false; // Invalid review text
        }
        if (!is_int($rating) || $rating < 1 || $rating > 5) {
            return false; // Invalid rating
        }

        $this->reviews[$productId][] = [
            'user' => $userName,
            'text' => $reviewText,
            'rating' => $rating,
            'timestamp' => time() // Add a timestamp for when the review was added
        ];

        return true;
    }

    /**
     * Retrieves all reviews for a specific product.
     *
     * @param int $productId The ID of the product to retrieve reviews for.
     * @return array An array of review objects for the product, or an empty array if no reviews exist.
     */
    public function getReviewsForProduct(int $productId): array
    {
        if (!is_int($productId) || $productId <= 0) {
            return []; // Invalid product ID
        }

        return $this->reviews[$productId] ?? [];  // Use null coalesce operator for concise handling.
    }

    /**
     * Retrieves all reviews.
     *
     * @return array All reviews stored in the system.
     */
    public function getAllReviews(): array
    {
        return $this->reviews;
    }

    /**
     * Deletes a specific review by product ID and user.
     *  This is a more complex operation, as we need to identify the *exact* review to delete.
     *  Since we only store the review as an array in the reviews array, a more robust solution
     *  might involve storing a unique ID for each review.  However, this implementation provides
     *  a basic approach.
     *
     * @param int $productId The ID of the product the review is for.
     * @param string $userName The name of the user who wrote the review.
     * @return bool True if the review was deleted, false otherwise.
     */
    public function deleteReview(int $productId, string $userName): bool
    {
        if (!is_int($productId) || $productId <= 0) {
            return false; // Invalid product ID
        }
        if (!is_string($userName) || empty($userName)) {
            return false; // Invalid user name
        }

        $productReviews = $this->getReviewsForProduct($productId);
        if (empty($productReviews)) {
            return false;
        }

        foreach ($productReviews as $key => $review) {
            if ($review['user'] === $userName) {
                unset($productReviews[$key]); // Remove the review
                return true; // Review found and deleted
            }
        }

        return false; // Review not found
    }

    /**
     * Calculates the average rating for a product.
     *
     * @param int $productId The ID of the product.
     * @return float|null The average rating, or null if no reviews exist for the product.
     */
    public function getAverageRatingForProduct(int $productId): ?float
    {
        $reviews = $this->getReviewsForProduct($productId);
        if (empty($reviews)) {
            return null;
        }

        $totalRating = 0;
        foreach ($reviews as $review) {
            $totalRating += $review['rating'];
        }

        return $totalRating / count($reviews);
    }
}



// Example Usage:
$reviewManager = new ReviewManager();

// Add reviews
$reviewManager->addReview(123, 'John Doe', 'Great product!', 5);
$reviewManager->addReview(123, 'Jane Smith', 'Could be better', 3);
$reviewManager->addReview(456, 'Peter Jones', 'Excellent value', 4);

// Get reviews for product 123
$reviews123 = $reviewManager->getReviewsForProduct(123);
print_r($reviews123);

// Get average rating for product 123
$averageRating = $reviewManager->getAverageRatingForProduct(123);
echo "Average rating for product 123: " . ($averageRating !== null ? $averageRating : "No reviews") . "
";

// Delete a review
$reviewManager->deleteReview(123, 'John Doe');

// Get reviews for product 123 again after deletion
$reviews123 = $reviewManager->getReviewsForProduct(123);
print_r($reviews123);


<?php

/**
 * Reviews function to handle user reviews.
 *
 * Allows adding, retrieving, and deleting reviews.
 */
class ReviewSystem
{
    private $reviews = [];
    private $reviewIdCounter = 1; // Used to generate unique IDs

    /**
     * Adds a new review.
     *
     * @param string $userId The ID of the user submitting the review.
     * @param string $comment The review text.
     * @return int|bool The ID of the newly created review on success, or false on failure (e.g., empty comment).
     */
    public function addReview(string $userId, string $comment)
    {
        if (empty($comment)) {
            return false; //  Don't allow empty comments
        }

        $reviewId = $this->generateReviewId();
        $this->reviews[$reviewId] = [
            'userId' => $userId,
            'comment' => $comment,
            'createdAt' => time() //Timestamp of when review was created
        ];

        return $reviewId;
    }

    /**
     * Retrieves all reviews.
     *
     * @return array An array of review objects.  Returns an empty array if no reviews exist.
     */
    public function getAllReviews()
    {
        return $this->reviews;
    }

    /**
     * Retrieves a specific review by its ID.
     *
     * @param int $reviewId The ID of the review to retrieve.
     * @return array|null The review object if found, or null if not found.
     */
    public function getReviewById(int $reviewId)
    {
        if (isset($this->reviews[$reviewId])) {
            return $this->reviews[$reviewId];
        }

        return null;
    }

    /**
     * Deletes a review by its ID.
     *
     * @param int $reviewId The ID of the review to delete.
     * @return bool True on success, false if the review doesn't exist.
     */
    public function deleteReview(int $reviewId)
    {
        if (isset($this->reviews[$reviewId])) {
            unset($this->reviews[$reviewId]);
            return true;
        }

        return false;
    }

    /**
     * Generates a unique review ID.
     *
     * @return int A unique integer.
     */
    private function generateReviewId(): int
    {
        return $this->reviewIdCounter++;
    }
}


// Example Usage:
$reviewSystem = new ReviewSystem();

// Add some reviews
$reviewId1 = $reviewSystem->addReview('user123', 'Great product!  Highly recommended.');
$reviewId2 = $reviewSystem->addReview('user456', 'Could be better, needs some improvements.');

if ($reviewId1) {
    echo "Review ID 1: " . $reviewId1 . "<br>";
}

if ($reviewId2) {
    echo "Review ID 2: " . $reviewId2 . "<br>";
}


// Get all reviews
$allReviews = $reviewSystem->getAllReviews();
echo "All Reviews:<br>";
echo "<pre>";
print_r($allReviews);
echo "</pre>";


// Get a specific review
$review = $reviewSystem->getReviewById($reviewId1);
if ($review) {
    echo "Review ID " . $reviewId1 . ":<br>";
    echo "User: " . $review['userId'] . "<br>";
    echo "Comment: " . $review['comment'] . "<br>";
    echo "Created At: " . date("Y-m-d H:i:s", $review['createdAt']) . "<br>";
} else {
    echo "Review not found.";
}

// Delete a review
$deleted = $reviewSystem->deleteReview($reviewId2);
if ($deleted) {
    echo "<br>Review ID 2 deleted successfully.";
} else {
    echo "<br>Failed to delete review.";
}

// Check for the deleted review
$remainingReviews = $reviewSystem->getAllReviews();
echo "<br>Remaining Reviews:<br>";
echo "<pre>";
print_r($remainingReviews);
echo "</pre>";

?>
