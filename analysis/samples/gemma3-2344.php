

<?php

/**
 *  User Review Function
 *
 *  This function handles creating, retrieving, updating, and deleting user reviews.
 *  It demonstrates basic CRUD (Create, Read, Update, Delete) operations
 *  for a review system.
 *
 *  Example Usage:
 *  $reviewSystem = new ReviewSystem();
 *
 *  // Create a new review
 *  $review = $reviewSystem->createReview(['user_id' => 1, 'product_id' => 101, 'rating' => 5, 'comment' => 'Great product!']);
 *
 *  // Retrieve a review by ID
 *  $review = $reviewSystem->getReview(1);
 *
 *  // Update a review
 *  $review = $reviewSystem->updateReview(1, ['rating' => 4, 'comment' => 'Good, but could be better.']);
 *
 *  // Delete a review
 *  $reviewSystem->deleteReview(1);
 *
 * @param array $data  An associative array containing the review data.
 *                       Required keys: 'user_id', 'product_id', 'rating', 'comment'.
 * @return array|null  The newly created review object if creation was successful,
 *                      or the review object if update was successful,
 *                      or null if update or delete was successful.
 */
class ReviewSystem
{
    private $reviews = []; // In-memory storage for simplicity.  Use a database in a real application.
    private $nextReviewId = 1;

    public function createReview(array $data)
    {
        // Validation (basic)
        if (!isset($data['user_id'], $data['product_id'], $data['rating'], $data['comment'])) {
            return null; // Required fields are missing
        }

        if (!is_numeric($data['user_id']) || !is_numeric($data['product_id'])) {
            return null;  // Invalid IDs
        }
        if (!is_int($data['rating']) || $data['rating'] < 1 || $data['rating'] > 5) {
            return null; // Rating must be an integer between 1 and 5
        }

        $review = [
            'id' => $this->nextReviewId++,
            'user_id' => $data['user_id'],
            'product_id' => $data['product_id'],
            'rating' => $data['rating'],
            'comment' => $data['comment'],
            'created_at' => time(),
            'updated_at' => time()
        ];

        $this->reviews[] = $review;

        return $review;
    }

    public function getReview(int $id)
    {
        foreach ($this->reviews as $review) {
            if ($review['id'] == $id) {
                return $review;
            }
        }

        return null; // Review not found
    }

    public function updateReview(int $id, array $data)
    {
        $review = $this->getReview($id);

        if (!$review) {
            return null; // Review not found
        }

        // Validate the update data
        if (!isset($data['rating'], $data['comment'])) {
            return null; //Required fields are missing
        }

        if (!is_int($data['rating']) || $data['rating'] < 1 || $data['rating'] > 5) {
            return null; // Invalid rating
        }
        
        $review['rating'] = $data['rating'];
        $review['comment'] = $data['comment'];
        $review['updated_at'] = time();

        return $review;
    }

    public function deleteReview(int $id)
    {
        foreach ($this->reviews as $key => $review) {
            if ($review['id'] == $id) {
                unset($this->reviews[$key]);
                return true; // Successfully deleted
            }
        }

        return false; // Review not found
    }
}


// Example Usage:
$reviewSystem = new ReviewSystem();

// Create a new review
$review = $reviewSystem->createReview(['user_id' => 1, 'product_id' => 101, 'rating' => 5, 'comment' => 'Great product!']);

if ($review) {
    echo "New Review Created:
";
    print_r($review);
    echo "
";
}

// Retrieve a review by ID
$review = $reviewSystem->getReview(1);
if ($review) {
    echo "Review ID 1:
";
    print_r($review);
    echo "
";
}

// Update a review
$review = $reviewSystem->updateReview(1, ['rating' => 4, 'comment' => 'Good, but could be better.']);
if ($review) {
    echo "Review ID 1 Updated:
";
    print_r($review);
    echo "
";
}

// Delete a review
$reviewSystem->deleteReview(1);

// Try to retrieve the deleted review
$review = $reviewSystem->getReview(1);
if (!$review) {
    echo "Review ID 1 deleted successfully.
";
} else {
    echo "Review ID 1 still exists.
";
}

?>
