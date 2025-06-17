

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews.
 * It includes basic functionality for creating, reading, updating, and deleting reviews.
 * 
 * @param string $productName The name of the product the review is for.
 * @param string $reviewText The content of the review.
 * @param string $userEmail The email address of the user submitting the review.
 * @param string $rating A rating from 1 to 5.
 * @param int $userId (Optional) The ID of the user submitting the review.  Used for updates.
 *
 * @return array An array containing the result of the operation.  
 *               Returns an error message on failure.
 */
function create_review(string $productName, string $reviewText, string $userEmail, string $rating, int $userId = null) {
    // Input Validation - Basic example, expand as needed
    if (empty($productName)) {
        return ['error' => 'Product name cannot be empty.'];
    }
    if (empty($reviewText)) {
        return ['error' => 'Review text cannot be empty.'];
    }
    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        return ['error' => 'Rating must be a number between 1 and 5.'];
    }
    if ($userId <= 0) {
        return ['error' => 'User ID must be a positive integer.'];
    }

    // In a real application, you'd store this in a database.  This is a simplified example.
    $review = [
        'product_name' => $productName,
        'review_text' => $reviewText,
        'user_email' => $userEmail,
        'rating' => $rating,
        'created_at' => date('Y-m-d H:i:s'),
        'user_id' => $userId // Store the user ID for updating later
    ];

    // Simulate saving to a database (replace with your database connection code)
    // In this example, we store in an array called $reviews
    global $reviews; // Accessing the global $reviews array
    if (isset($reviews[$userId])) {
        //Update existing review
        $reviews[$userId] = $review;
    } else {
        $reviews[$userId] = $review;
    }



    return ['success' => true, 'review' => $review];
}


// Example Usage:

// Initialize an array to hold reviews
$reviews = [];  // Important: Create the global $reviews array

// Create a new review
$result = create_review('Awesome Product', 'Great product!', 'user@example.com', 5);

if ($result['success']) {
    echo "Review created successfully!
";
    print_r($result['review']); // Display the created review
} else {
    echo "Error creating review: " . $result['error'] . "
";
}


//Create another review
$result = create_review('Another Product', 'Pretty good!', 'another@example.com', 4, 1);

if ($result['success']) {
    echo "Review created successfully!
";
    print_r($result['review']); // Display the created review
} else {
    echo "Error creating review: " . $result['error'] . "
";
}

//Example update review
$result = create_review('Awesome Product', 'Updated review text!', 'user@example.com', 3, 1);
if ($result['success']) {
    echo "Review updated successfully!
";
    print_r($result['review']);
} else {
    echo "Error updating review: " . $result['error'] . "
";
}

// Display all reviews (simulated database retrieval)
echo "
All Reviews:
";
foreach ($reviews as $userId => $review) {
    echo "User ID: " . $userId . "
";
    print_r($review);
    echo "
---";
}

?>
