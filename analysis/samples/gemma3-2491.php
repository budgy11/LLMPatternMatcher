

<?php

/**
 * User Review Function
 *
 * This function processes user reviews, validates them, and stores them
 * (in this example, just prints them to the console).  It can be easily adapted
 * to store the reviews in a database or other persistent storage.
 *
 * @param array $reviews An array of user review objects.
 *                      Each review object should have 'user', 'rating', and 'comment' properties.
 * @return bool True if reviews were processed successfully, false otherwise.
 */
function processUserReviews(array $reviews)
{
    $success = true;

    foreach ($reviews as $review) {
        // Validate the review
        if (!$review) {
            $success = false;
            error_log("Invalid review object encountered.  Review is empty.");
            continue;
        }

        if (!is_object($review)) {
            $success = false;
            error_log("Review is not an object. Review: " . print_r($review, true));
            continue;
        }

        // Check required properties
        $required_properties = ['user', 'rating', 'comment'];
        foreach ($required_properties as $prop) {
            if (!property_exists($review, $prop)) {
                $success = false;
                error_log("Missing property '$prop' in review object.");
                break;
            }
        }

        // Validate rating (numeric)
        if (!is_numeric($review->rating)) {
            $success = false;
            error_log("Rating must be a number.  Received: " . $review->rating);
        }

        // Validate rating range (e.g., 1-5) - You can customize this
        if ($review->rating < 1 || $review->rating > 5) {
            $success = false;
            error_log("Rating must be between 1 and 5. Received: " . $review->rating);
        }

        // Validate comment (optional, you can add more complex validation here)
        if (empty($review->comment)) {
            //  You could allow empty comments or require them.  Here, we allow
            //  and log a message.
            error_log("Comment is empty for review: " . $review->user);
        }

        // Process the review (e.g., store it)
        // In a real application, you would do something here like:
        // $this->storeReview($review);  // Assuming you have a storeReview() method
        echo "Review processed: User: " . $review->user . ", Rating: " . $review->rating . ", Comment: " . $review->comment . "
";
    }

    return $success;
}

// Example Usage:

// Create some sample review objects (simulate getting reviews from a form or API)
$reviews = [
    (object) ['user' => 'Alice', 'rating' => 4, 'comment' => 'Great product!'],
    (object) ['user' => 'Bob', 'rating' => 2, 'comment' => 'Could be better.'],
    (object) ['user' => 'Charlie', 'rating' => 5, 'comment' => 'Excellent value.'],
    (object) ['user' => 'David', 'rating' => 1, 'comment' => 'Very disappointing.'],
    (object) ['user' => 'Eve', 'rating' => 3, 'comment' => 'Okay.'],
    // Example of an invalid review (missing property)
    // (object) ['user' => 'Frank', 'rating' => 4],
    // Example of an invalid review (non-numeric rating)
    // (object) ['user' => 'George', 'rating' => 'bad', 'comment' => 'Terrible!']
];

$result = processUserReviews($reviews);

if ($result) {
    echo "All reviews processed successfully.
";
} else {
    echo "There were errors processing some reviews.
";
}

?>
