

<?php

/**
 * User Review Class
 *
 * This class allows users to submit and manage reviews.
 */
class UserReview
{
    private $reviewId;
    private $userId;
    private $productName;
    private $rating;
    private $comment;
    private $dateSubmitted;

    /**
     * Constructor
     *
     * Initializes a new UserReview object.
     */
    public function __construct($userId, $productName, $rating, $comment)
    {
        $this->reviewId = uniqid(); // Generate a unique review ID
        $this->userId = $userId;
        $this->productName = $productName;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->dateSubmitted = date('Y-m-d H:i:s');
    }

    /**
     * Getters for each property
     *
     * @return mixed
     */
    public function getReviewId()
    {
        return $this->reviewId;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getProductName()
    {
        return $this->productName;
    }

    public function getRating()
    {
        return $this->rating;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function getDateSubmitted()
    {
        return $this->dateSubmitted;
    }

    /**
     *  Methods for saving, updating, or deleting reviews would go here.
     *  This is just a basic implementation.  For a real application,
     *  you would likely save this data to a database.
     */
}


/**
 * User Review Function (Simplified Review Submission)
 *
 * This function simulates the submission and display of a user review.
 *
 * @param array $userData  An associative array containing user data:
 *                         'userId' => int,
 *                         'productName' => string,
 *                         'rating' => int (1-5),
 *                         'comment' => string
 * @return array An associative array containing the review data if successful,
 *              or an error message if something went wrong.
 */
function submitUserReview(array $userData)
{
    // Validate input
    if (!isset($userData['userId']) || !is_int($userData['userId']) || $userData['userId'] <= 0) {
        return ['error' => 'Invalid user ID. User ID must be a positive integer.'];
    }
    if (!isset($userData['productName']) || !is_string($userData['productName']) || empty($userData['productName'])) {
        return ['error' => 'Product name is required and must be a non-empty string.'];
    }
    if (!isset($userData['rating']) || !is_int($userData['rating']) || $userData['rating'] < 1 || $userData['rating'] > 5) {
        return ['error' => 'Rating must be an integer between 1 and 5.'];
    }
    if (!isset($userData['comment']) || !is_string($userData['comment']) || empty($userData['comment'])) {
        return ['error' => 'Comment is required and must be a non-empty string.'];
    }


    // Create a new review object
    $review = new UserReview($userData['userId'], $userData['productName'], $userData['rating'], $userData['comment']);

    // Simulate saving the review (in a real application, you'd save to a database)
    //  For demonstration purposes, we just return the review data.
    return $review->getReviewId() . " - " . $review->getProductName() . " - Rating: " . $review->getRating() . " - Comment: " . $review->getComment();
}


// Example Usage:
$reviewData = [
    'userId' => 123,
    'productName' => 'Awesome Widget',
    'rating' => 5,
    'comment' => 'This widget is amazing!  Highly recommended.'
];

$reviewResult = submitUserReview($reviewData);

if (isset($reviewResult['error'])) {
    echo "Error: " . $reviewResult['error'] . "
";
} else {
    echo "Review submitted successfully: " . $reviewResult . "
";
}


// Example of handling an error:
$invalidReviewData = [
    'userId' => 'abc', // Invalid user ID (string)
    'productName' => 'Bad Product',
    'rating' => 6, // Invalid rating
    'comment' => '' // Empty comment
];

$errorResult = submitUserReview($errorResult);

if (isset($errorResult['error'])) {
    echo "Error: " . $errorResult['error'] . "
";
} else {
    echo "Review submitted successfully: " . $errorResult['error'] . "
";
}

?>
