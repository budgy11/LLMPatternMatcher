

<?php

/**
 * User Review Class
 * 
 * This class allows users to submit and view reviews for a product or service.
 * 
 * @author Your Name
 * @version 1.0
 */
class UserReview {

    /**
     * @var array Reviews array
     */
    private $reviews = [];

    /**
     * Adds a new review.
     *
     * @param string $username The username of the reviewer.
     * @param string $comment The review text.
     * @return bool True if the review was successfully added, false otherwise.
     */
    public function addReview(string $username, string $comment) {
        if (empty($username) || empty($comment)) {
            return false; // Invalid input
        }
        $this->reviews[] = ['username' => $username, 'comment' => $comment];
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
     * Retrieves reviews for a specific product/service (implementation placeholder).
     * 
     *  This function can be expanded with database integration or filtering logic.
     *
     * @param string $productName The name of the product/service.
     * @return array  An array of review objects for the specified product.  Returns empty array if not found.
     */
    public function getReviewsByProduct(string $productName) {
        // Placeholder for database integration or other filtering
        $filteredReviews = [];
        foreach ($this->getAllReviews() as $review) {
            if (strpos($review['comment'], $productName) !== false) { //Simple string match
                $filteredReviews[] = $review;
            }
        }
        return $filteredReviews;
    }

    /**
     * Calculates the average rating.
     *  
     * @return float|null Average rating (if reviews exist) or null if no reviews.
     */
    public function getAverageRating() {
        if (empty($this->getAllReviews())) {
            return null;
        }

        $totalRating = 0;
        $numReviews = count($this->getAllReviews());

        foreach ($this->getAllReviews() as $review) {
            $rating = $review['rating'];
            if (is_numeric($rating)) { //Validate that rating is a number
                $totalRating += $rating;
            } else {
                //Handle invalid rating values, maybe log an error or skip
                continue; 
            }
        }
        return $totalRating / $numReviews;
    }
}


// Example Usage:

// Create a review object
$reviewObject = new UserReview();

// Add some reviews
$reviewObject->addReview('JohnDoe', 'Great product, highly recommended!');
$reviewObject->addReview('JaneSmith', 'Good value for money.');
$reviewObject->addReview('PeterJones', 'Needs some improvements.');
$reviewObject->addReview('AliceBrown', 'Excellent customer service.');


// Get all reviews
$allReviews = $reviewObject->getAllReviews();
echo "<h2>All Reviews:</h2>";
foreach ($allReviews as $review) {
    echo "<strong>Username:</strong> " . $review['username'] . "<br>";
    echo "<strong>Comment:</strong> " . $review['comment'] . "<br>";
    echo "<hr>";
}

// Get reviews for a specific product (e.g., "Laptop")
$laptopReviews = $reviewObject->getReviewsByProduct("Laptop");

echo "<h2>Reviews for Laptop:</h2>";
if (empty($laptopReviews)) {
    echo "<p>No reviews found for Laptop.</p>";
} else {
    foreach ($laptopReviews as $review) {
        echo "<strong>Username:</strong> " . $review['username'] . "<br>";
        echo "<strong>Comment:</strong> " . $review['comment'] . "<br>";
        echo "<hr>";
    }
}

// Calculate the average rating
$averageRating = $reviewObject->getAverageRating();
echo "<h2>Average Rating:</h2>";
if ($averageRating !== null) {
    echo "<p>Average Rating: " . $averageRating . "</p>";
} else {
    echo "<p>No reviews yet to calculate an average rating.</p>";
}

?>
