

<?php

/**
 *  User Review Function
 *
 *  This function allows you to process user reviews,
 *  sanitize the input, and potentially store it.
 *
 *  @param array $reviewData An associative array containing the review data:
 *                            - 'username' (string): The username of the reviewer.
 *                            - 'rating' (int): The rating given by the reviewer (e.g., 1-5).
 *                            - 'comment' (string): The user's review comment.
 *
 *  @return array|string  Returns an array of processed review data or an error message string.
 */
function processReview(array $reviewData)
{
    // Validate input - This is crucial!
    $errors = [];

    // Username validation
    if (empty($reviewData['username'])) {
        $errors['username'] = 'Username cannot be empty.';
    }

    // Rating validation
    if (!is_numeric($reviewData['rating']) || $reviewData['rating'] < 1 || $reviewData['rating'] > 5) {
        $errors['rating'] = 'Rating must be a number between 1 and 5.';
    }

    // Comment validation
    if (empty($reviewData['comment'])) {
        $errors['comment'] = 'Comment cannot be empty.';
    }

    //  Sanitize Input (Prevent XSS and SQL Injection - Important!)
    $username = trim($reviewData['username']);
    $rating = (int)$reviewData['rating'];
    $comment = htmlspecialchars(trim($reviewData['comment']));  //Escape HTML special chars

    //  You could add more sanitization here, depending on where you're storing the review.

    //  If no errors, return the processed data
    if (empty($errors)) {
        return [
            'username' => $username,
            'rating' => $rating,
            'comment' => $comment
        ];
    } else {
        // Return an error message
        return $errors;
    }
}


// --- Example Usage ---
// Example 1: Valid Review
$review1 = ['username' => 'JohnDoe', 'rating' => 4, 'comment' => 'Great product!'];
$result1 = processReview($review1);

if (is_array($result1)) {
    if (empty($result1)) {
        echo "Review processed successfully!
";
    } else {
        print_r($result1); // Display the errors
    }
} else {
    echo "Review processed successfully!
";
}


// Example 2: Invalid Review (Missing Comment)
$review2 = ['username' => 'JaneSmith', 'rating' => 3];
$result2 = processReview($review2);

if (is_array($result2)) {
    if (empty($result2)) {
        echo "Review processed successfully!
";
    } else {
        print_r($result2); // Display the errors
    }
} else {
    echo "Review processed successfully!
";
}

// Example 3: Invalid Review (Invalid Rating)
$review3 = ['username' => 'PeterPan', 'rating' => 6, 'comment' => 'Awesome!'];
$result3 = processReview($review3);

if (is_array($result3)) {
    if (empty($result3)) {
        echo "Review processed successfully!
";
    } else {
        print_r($result3); // Display the errors
    }
} else {
    echo "Review processed successfully!
";
}

?>
