

<?php

/**
 * User Review Function - Simple Implementation
 *
 * This function creates a basic user review object and saves it to a file.
 *  It's a starting point and can be expanded with database integration,
 *  more validation, and more sophisticated data handling.
 */

class Review {
    public $user_id;
    public $product_id;
    public $rating;
    public $comment;
    public $date;

    public function __construct($user_id, $product_id, $rating, $comment) {
        $this->user_id = $user_id;
        $this->product_id = $product_id;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->date = date('Y-m-d H:i:s'); // Add timestamp
    }

    public function getReviewData() {
        return [
            'user_id' => $this->user_id,
            'product_id' => $this->product_id,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'date' => $this->date
        ];
    }
}

/**
 * Function to create a new user review
 *
 * @param int $userId The ID of the user writing the review.
 * @param int $productId The ID of the product being reviewed.
 * @param int $rating The rating (e.g., 1-5).
 * @param string $comment The user's comment.
 *
 * @return Review|null Returns a Review object on success, or null on error.
 */
function createReview($userId, $productId, $rating, $comment) {

    // **Input Validation - CRITICAL!**  Always validate user input.
    if (!is_numeric($userId) || $userId <= 0) {
        error_log("Invalid userId: " . $userId); // Log for debugging
        return null;
    }
    if (!is_numeric($productId) || $productId <= 0) {
        error_log("Invalid productId: " . $productId);
        return null;
    }
    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        error_log("Invalid rating: " . $rating);
        return null;
    }
    if (empty($comment)) {
        error_log("Comment cannot be empty.");
        return null;
    }

    $review = new Review($userId, $productId, $rating, $comment);

    // **File Saving (Replace with Database Integration)**
    $filename = "reviews.txt";  // Simple file storage - use database in production
    $reviewData = $review->getReviewData();
    $reviewString = json_encode($reviewString);
    $result = file_put_contents($filename, $reviewString . "
", FILE_APPEND); // Append to file

    if ($result === false) {
        error_log("Error writing to file: " . $filename);
        return null;
    }

    return $review;
}


// **Example Usage:**
$newReview = createReview(123, 456, 4, "Great product!");

if ($newReview) {
    echo "Review created successfully!  User ID: " . $newReview->user_id . "
";
    echo "Review: " . json_encode($newReview->getReviewData()) . "
";
} else {
    echo "Review creation failed.
";
}


?>
