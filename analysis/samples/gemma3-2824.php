

<?php

/**
 * Class UserReview
 *
 * Handles the creation, retrieval, and display of user reviews.
 */
class UserReview
{
    private $reviews = []; // Array to store reviews

    /**
     * Adds a new user review to the database (simulated).
     *
     * @param int $productId The ID of the product being reviewed.
     * @param string $username The username of the reviewer.
     * @param string $rating  The rating given by the user (e.g., 1-5).
     * @param string $comment The user's review comment.
     *
     * @return bool True on success, false on failure (e.g., invalid rating).
     */
    public function addReview(int $productId, string $username, string $rating, string $comment): bool
    {
        // Basic input validation - enhance this for production
        if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
            return false;
        }

        $review = [
            'productId' => $productId,
            'username' => $username,
            'rating' => $rating,
            'comment' => $comment,
            'date' => date('Y-m-d H:i:s') // Add timestamp for review creation
        ];

        $this->reviews[] = $review;
        return true;
    }

    /**
     * Retrieves all reviews for a given product ID.
     *
     * @param int $productId The ID of the product.
     *
     * @return array An array of review objects, or an empty array if no reviews are found.
     */
    public function getReviewsByProduct(int $productId): array
    {
        $productReviews = [];
        foreach ($this->reviews as $review) {
            if ($review['productId'] == $productId) {
                $productReviews[] = $review;
            }
        }
        return $productReviews;
    }


    /**
     * Retrieves a specific review by its ID (simulated - in a real system, this would use a database ID).
     *
     * @param int $reviewId The ID of the review.
     *
     * @return array|null The review object if found, or null if not found.
     */
    public function getReviewById(int $reviewId): ?array
    {
        foreach ($this->reviews as $review) {
            if ($review['date'] == $reviewId) { // Use 'date' for ID - change to your database key
                return $review;
            }
        }
        return null;
    }

    /**
     * Updates an existing review (simulated - in a real system, update the database).
     *
     * @param int $reviewId  The ID of the review to update.
     * @param string $newRating The new rating for the review.
     * @param string $newComment The new comment for the review.
     * @return bool True on success, false on failure (e.g., invalid rating).
     */
    public function updateReview(int $reviewId, string $newRating, string $newComment): bool
    {
        $reviewToUpdate = $this->getReviewById($reviewId);

        if (!$reviewToUpdate) {
            return false; // Review not found
        }

        if (!is_numeric($newRating) || $newRating < 1 || $newRating > 5) {
            return false;
        }

        $reviewToUpdate['rating'] = $newRating;
        $reviewToUpdate['comment'] = $newComment;
        return true;
    }


    /**
     * Deletes a review by its ID (simulated - in a real system, delete from the database).
     *
     * @param int $reviewId The ID of the review to delete.
     *
     * @return bool True on success, false if review not found.
     */
    public function deleteReview(int $reviewId): bool
    {
        foreach ($this->reviews as $key => $review) {
            if ($review['date'] == $reviewId) {
                unset($this->reviews[$key]);
                return true;
            }
        }
        return false;
    }

    /**
     * Returns all reviews.
     *
     * @return array
     */
    public function getAllReviews(): array
    {
        return $this->reviews;
    }
}


// Example Usage
$reviewSystem = new UserReview();

// Add some reviews
$reviewSystem->addReview(123, 'JohnDoe', 4, 'Great product, highly recommended!');
$reviewSystem->addReview(123, 'JaneSmith', 5, 'Excellent value for money.');
$reviewSystem->addReview(456, 'PeterJones', 3, 'It was okay, nothing special.');

// Get reviews for product 123
$reviews123 = $reviewSystem->getReviewsByProduct(123);
echo "Reviews for Product 123:
";
foreach ($reviews123 as $review) {
    echo "  - " . $review['username'] . ": " . $review['rating'] . " - " . $review['comment'] . "
";
}

// Get review by ID (simulated - using date as ID)
$reviewById = $reviewSystem->getReviewById('Y-m-d H:i:s'); //Replace with actual review ID
if ($reviewById) {
    echo "
Review by ID:
";
    echo "  - " . $reviewById['username'] . ": " . $reviewById['rating'] . " - " . $reviewById['comment'] . "
";
} else {
    echo "Review not found.
";
}

//Update a review
$updateResult = $reviewSystem->updateReview('Y-m-d H:i:s', 6, 'Updated Review Content');
if ($updateResult) {
    echo "
Review updated successfully!
";
} else {
    echo "
Failed to update review.
";
}

//Delete a review
$deleteResult = $reviewSystem->deleteReview('Y-m-d H:i:s');
if ($deleteResult) {
    echo "
Review deleted successfully!
";
} else {
    echo "
Failed to delete review.
";
}


<?php

/**
 * User Review Function
 *
 * This function allows you to collect and display user reviews.
 * It handles input validation, data storage (simplified for demonstration),
 * and basic output formatting.
 *
 * @param string $productId The ID of the product being reviewed.
 * @param string $reviewerName The name of the reviewer.
 * @param string $reviewText The review text submitted by the reviewer.
 * @param array $rating A numeric rating (e.g., 1-5).
 *
 * @return void  Prints the review to the console (for demonstration).  In a real
 *               application, you would typically store this data in a database.
 */
function createReview(string $productId, string $reviewerName, string $reviewText, array $rating) {

  // Input Validation (Important!)
  if (empty($productId)) {
    echo "Error: Product ID cannot be empty.
";
    return;
  }
  if (empty($reviewerName)) {
    echo "Error: Reviewer Name cannot be empty.
";
    return;
  }
  if (empty($reviewText)) {
    echo "Error: Review Text cannot be empty.
";
    return;
  }
  if (!is_array($rating) || count($rating) !== 1) {
    echo "Error: Rating must be a single numeric value (e.g., 1-5).
";
    return;
  }
  if (!is_numeric($rating[0])) {
    echo "Error: Rating must be a numeric value.
";
    return;
  }
  if ($rating[0] < 1 || $rating[0] > 5) {
    echo "Error: Rating must be between 1 and 5.
";
    return;
  }

  // Data Storage (Simplified - Replace with Database Integration)
  $review = [
    'productId' => $productId,
    'reviewerName' => $reviewerName,
    'reviewText' => $reviewText,
    'rating' => $rating[0]
  ];

  // Output/Display the review (replace with your desired presentation)
  echo "Review for Product ID: " . $productId . "
";
  echo "Reviewer: " . $reviewerName . "
";
  echo "Rating: " . $rating[0] . " / 5
";
  echo "Review Text: " . $reviewText . "
";
  echo "---
";
}


// Example Usage:
createReview("P123", "John Doe", "Great product!  Exactly what I needed.", [5]);
createReview("P456", "Jane Smith", "Could be better, but decent.", [3]);
createReview("P789", "Peter Jones", "Amazing!  Highly recommended.", [5]);
createReview("P101", "Alice Brown", "It's okay.", [2]);

// Example of invalid input:
createReview("", "Invalid Name", "Review Text", [4]); // Empty Product ID
createReview("P999", "", "Review Text", [6]); // Empty Reviewer Name
createReview("P111", "Test User", "Invalid Rating", [1, 2]); // Invalid rating format
createReview("P222", "User", "Review", ["abc"]); // Invalid rating type
?>
