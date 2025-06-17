

<?php

/**
 * User Review Function
 *
 * This function allows users to submit reviews for a product or service.
 * It includes validation and handling of user input.
 *
 * @param string $product_id The ID of the product being reviewed.
 * @param string $reviewer_name The name of the user submitting the review.
 * @param string $rating  The rating (e.g., 1-5 stars).
 * @param string $comment The user's review comment.
 * @return array  An array containing a success message or an error message.
 */
function submit_review(string $product_id, string $reviewer_name, string $rating, string $comment)
{
    // **Input Validation - Crucial for Security & Data Integrity**
    $errors = [];

    // Check for required fields
    if (empty($reviewer_name)) {
        $errors['reviewer_name'] = 'Reviewer name is required.';
    }

    if (empty($rating)) {
        $errors['rating'] = 'Rating is required.';
    }

    // Validate rating (e.g., numeric and within range)
    if (!is_numeric($rating)) {
        $errors['rating'] = 'Rating must be a number.';
    }

    if (!is_int($rating)) {
        $errors['rating'] = 'Rating must be an integer.';
    }

    if ($rating < 1 || $rating > 5) {
        $errors['rating'] = 'Rating must be between 1 and 5.';
    }

    if (empty($comment)) {
        $errors['comment'] = 'Review comment is required.';
    }

    // **Basic Sanitization (Important for preventing XSS attacks)**
    $reviewer_name = htmlspecialchars($reviewer_name);
    $comment = htmlspecialchars($comment);


    // **If no errors, process the review**
    if (empty($errors)) {
        // **Here you would typically save the review to a database.**
        // For demonstration, we'll just log it to the console.

        echo "Review submitted successfully!
";
        echo "Product ID: " . $product_id . "
";
        echo "Reviewer: " . $reviewer_name . "
";
        echo "Rating: " . $rating . "
";
        echo "Comment: " . $comment . "
";

        // Example database saving (Requires database connection and setup)
        /*
        $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');
        $stmt = $db->prepare("INSERT INTO reviews (product_id, reviewer_name, rating, comment) VALUES (?, ?, ?, ?)");
        $stmt->execute([$product_id, $reviewer_name, $rating, $comment]);
        */


        return ['success' => true, 'message' => 'Review submitted successfully!'];
    } else {
        // Return an array of errors.
        return ['success' => false, 'errors' => $errors];
    }
}

// **Example Usage**

// Test 1: Successful submission
$review_result = submit_review('123', 'John Doe', 5, 'Great product! I love it.');
if ($review_result['success']) {
    echo "Review submitted successfully!
";
} else {
    print_r($review_result['errors']);
}

// Test 2:  Invalid rating
$review_result = submit_review('456', 'Jane Smith', 6, 'Excellent value.');
if ($review_result['success']) {
    echo "Review submitted successfully!
";
} else {
    print_r($review_result['errors']);
}

// Test 3: Missing required field
$review_result = submit_review('789', '', 4, 'It\'s okay.');
if ($review_result['success']) {
    echo "Review submitted successfully!
";
} else {
    print_r($review_result['errors']);
}
?>
