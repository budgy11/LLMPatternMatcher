

<?php

/**
 * This function creates a user review object based on input data.
 * 
 * @param string $username The name of the user submitting the review.
 * @param string $rating  The rating given by the user (e.g., 1-5).
 * @param string $comment  The user's written comment.
 * 
 * @return Review|null A Review object if the input is valid, null otherwise.
 */
class Review
{
    public string $username;
    public int $rating;
    public string $comment;

    public function __construct(string $username, int $rating, string $comment)
    {
        // Validate input - crucial for security and data integrity
        if (empty($username)) {
            throw new InvalidArgumentException("Username cannot be empty.");
        }
        if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
            throw new InvalidArgumentException("Rating must be a number between 1 and 5.");
        }
        if (empty($comment)) {
            throw new InvalidArgumentException("Comment cannot be empty.");
        }

        $this->username = $username;
        $this->rating = $rating;
        $this->comment = $comment;
    }
}


/**
 *  Creates a user review using the Review class.
 *
 * @param array $reviewData An associative array containing the review data.
 *  - 'username' => 'User Name'
 *  - 'rating'  => 'Rating (1-5)'
 *  - 'comment' => 'Review Comment'
 *
 * @return Review|null  A Review object if the data is valid, null otherwise.
 */
function createReview(array $reviewData)
{
    try {
        // Validate that required keys are present
        if (!isset($reviewData['username'], $reviewData['rating'], $reviewData['comment'])) {
            throw new InvalidArgumentException("Missing required review data.");
        }

        //  Type validation and data casting
        $username = trim($reviewData['username']);
        $rating = (int) $reviewData['rating']; // Cast to integer
        $comment = trim($reviewData['comment']); //Trim whitespace

        return new Review($username, $rating, $comment);

    } catch (InvalidArgumentException $e) {
        // Handle exceptions (e.g., log the error, return null, display an error message)
        error_log("Review creation failed: " . $e->getMessage()); // Log the error.  Important for debugging.
        return null;
    }
}

// Example Usage:

// Valid Review
$reviewData = [
    'username' => 'JohnDoe',
    'rating' => 4,
    'comment' => 'Great product!  Easy to use.'
];

$review = createReview($reviewData);

if ($review) {
    echo "Review created successfully:
";
    echo "Username: " . $review->username . "
";
    echo "Rating: " . $review->rating . "
";
    echo "Comment: " . $review->comment . "
";
} else {
    echo "Review creation failed.
";
}


// Invalid Review Examples:
$invalidData1 = [
    'username' => '',
    'rating' => 6,
    'comment' => 'This is a test.'
];

$review2 = createReview($invalidData1);
if (!$review2) {
    echo "Invalid review data detected (example 1).
";
}

$invalidData2 = [
    'username' => 'JaneSmith',
    'rating' => '5', // String instead of integer
    'comment' => ''
];

$review3 = createReview($invalidData2);
if (!$review3) {
    echo "Invalid review data detected (example 2).
";
}

?>
