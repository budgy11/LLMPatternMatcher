

<?php

/**
 * Class Review
 *
 * This class provides a simple function to create and manage user reviews.
 */
class Review {

    /**
     * Adds a new review to the database (simulated here).
     *
     * @param int $productId The ID of the product being reviewed.
     * @param string $reviewerName The name of the reviewer.
     * @param string $comment The review comment.
     * @param int $rating The rating given (e.g., 1-5).
     *
     * @return bool True if the review was added successfully, false otherwise.
     */
    public static function addReview(int $productId, string $reviewerName, string $comment, int $rating) {
        // Simulate database insertion (replace with your database interaction logic)
        $review = [
            'product_id' => $productId,
            'reviewer_name' => $reviewerName,
            'comment' => $comment,
            'rating' => $rating,
            'date' => date('Y-m-d H:i:s') // Add a timestamp for record keeping
        ];

        // Check for required data
        if (empty($review['product_id']) || empty($review['reviewer_name']) || empty($review['comment']) || $rating < 1 || $rating > 5) {
            return false;
        }

        //  Simulated database save (replace with your actual database call)
        //  This is just to demonstrate the functionality.  You'll want to use
        //  PDO, MySQLi, or another database library in a real application.
        $review_id = self::saveReviewToDatabase($review);

        if ($review_id) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Saves the review to the database.  This is a placeholder.  Replace this
     * with your actual database interaction code.
     *
     * @param array $review The review data to save.
     * @return int|false The ID of the review if saved successfully, or false if not.
     */
    private static function saveReviewToDatabase(array $review) {
        // Placeholder - replace with your database insertion logic
        // This simulates saving to a database.  You should use
        // a database library (PDO, MySQLi) for actual implementation.

        //  Example using an array as a simple database (not recommended for production)
        $reviews = self::getReviews(); //Get reviews from the database, or an array.
        $review_id = count($reviews) + 1;

        $review['id'] = $review_id; // add ID to review

        $reviews[] = $review; //Append to reviews array

        return $review_id;
    }

    /**
     * Retrieves reviews for a specific product.
     *
     * @param int $productId The ID of the product.
     *
     * @return array An array of review objects or an empty array if no reviews are found.
     */
    public static function getReviewsForProduct(int $productId) {
        // Simulate fetching reviews from the database (replace with your logic)
        // This is just for demonstration purposes.  You should use a database query.
        $reviews = self::getReviews();
        $product_reviews = [];

        foreach ($reviews as $review) {
            if ($review['product_id'] == $productId) {
                $product_reviews[] = $review;
            }
        }

        return $product_reviews;
    }

    /**
     * Simulates getting reviews from the database.  Replace with your
     * database query logic.
     *
     * @return array  An array of review objects.
     */
    private static function getReviews() {
        //Simulated reviews.  Replace with actual database queries.
        return [
            [
                'id' => 1,
                'product_id' => 123,
                'reviewer_name' => 'John Doe',
                'comment' => 'Great product! I highly recommend it.',
                'rating' => 5,
                'date' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 2,
                'product_id' => 123,
                'reviewer_name' => 'Jane Smith',
                'comment' => 'Good value for the money.',
                'rating' => 4,
                'date' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 3,
                'product_id' => 456,
                'reviewer_name' => 'Peter Jones',
                'comment' => 'Not as expected.',
                'rating' => 2,
                'date' => date('Y-m-d H:i:s')
            ]
        ];
    }
}


// Example Usage:
$review = new Review();

// Add a review
$productId = 123;
$reviewer = 'Alice Brown';
$comment = 'Excellent product, fast shipping!';
$rating = 5;

if ($review->addReview($productId, $reviewer, $comment, $rating)) {
    echo "Review added successfully!
";
} else {
    echo "Failed to add review.
";
}

// Get reviews for product 123
$reviews = $review->getReviewsForProduct($productId);

echo "Reviews for product " . $productId . ":
";
if (empty($reviews)) {
    echo "No reviews found.
";
} else {
    foreach ($reviews as $review) {
        echo "- " . $review['reviewer_name'] . ": " . $review['comment'] . " (" . $review['rating'] . ")
";
    }
}

?>
