

<?php

/**
 *  User Review Function
 *
 *  This function handles the creation, retrieval, and basic display of user reviews.
 *
 *  @param string $productName - The name of the product being reviewed.
 *  @param string $userId - The ID of the user submitting the review.
 *  @param string $reviewText - The user's review text.
 *  @param int $rating - The user's rating (1-5).
 *
 *  @return array|string -  An array containing the review details, or an error message.
 */
function createReview(string $productName, string $userId, string $reviewText, int $rating): array|string
{
    // Input Validation -  Important for security and data integrity
    if (empty($productName)) {
        return "Error: Product name cannot be empty.";
    }
    if (empty($userId)) {
        return "Error: User ID cannot be empty.";
    }
    if (empty($reviewText)) {
        return "Error: Review text cannot be empty.";
    }
    if ($rating < 1 || $rating > 5) {
        return "Error: Rating must be between 1 and 5.";
    }


    //  Simulate Saving the Review to a Database (Replace with your database logic)
    //  In a real application, you'd use a database connection here.
    $review = [
        'product_name' => $productName,
        'user_id' => $userId,
        'review_text' => $reviewText,
        'rating' => $rating,
        'timestamp' => time() // Add a timestamp for ordering and tracking
    ];

    //  Example: Store the review in an array (replace with database storage)
    //  In a real application, you'd add this review to your database.

    //  For demonstration purposes, let's store in an array.
    //  Consider using session storage or caching for larger applications.

    // Return the review details
    return $review;
}


/**
 * Retrieves reviews for a specific product.
 *
 * @param string $productName - The name of the product to search for.
 * @return array|string -  An array of review objects, or an error message.
 */
function getReviewsForProduct(string $productName): array|string {
    // Placeholder: This would fetch reviews from a database
    // Based on the product name.

    // Example for demonstration:
    if ($productName === 'Awesome Gadget') {
        return [
            [
                'product_name' => 'Awesome Gadget',
                'user_id' => 'user123',
                'review_text' => 'Great product!  Easy to use.',
                'rating' => 5,
                'timestamp' => time()
            ],
            [
                'product_name' => 'Awesome Gadget',
                'user_id' => 'user456',
                'review_text' => 'Pretty good, but could be better.',
                'rating' => 3,
                'timestamp' => time()
            ]
        ];
    } else {
        return "Error: No reviews found for product: " . $productName;
    }
}


// Example Usage:
$product = 'Awesome Gadget';
$userId = 'user123';
$reviewText = 'This gadget is amazing!  Highly recommended.';
$rating = 5;

$newReview = createReview($product, $userId, $reviewText, $rating);

if (is_array($newReview)) {
    echo "New Review Created: 
";
    print_r($newReview);

    // Retrieve reviews for the product
    $reviews = getReviewsForProduct($product);

    if (is_array($reviews)) {
        echo "
Reviews for " . $product . ":
";
        print_r($reviews);
    } else {
        echo "
Error retrieving reviews.
";
    }
} else {
    echo $newReview . "
"; // Display the error message
}


?>
