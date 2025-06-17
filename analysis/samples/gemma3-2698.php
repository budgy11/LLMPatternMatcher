

<?php

/**
 *  A User Review Function
 *
 *  This function allows you to store and display user reviews for a product or service.
 *
 *  @param string $productId The unique identifier for the product or service.
 *  @param string $reviewerName The name of the reviewer.
 *  @param string $reviewText The text of the review.
 *  @param int $rating (optional) A rating from 1 to 5, or null if no rating is provided.
 *  @return bool True if the review was successfully saved, false otherwise.
 */
function saveUserReview(string $productId, string $reviewerName, string $reviewText, ?int $rating = null): bool
{
    // Validation - Add more robust validation as needed.
    if (empty($productId)) {
        error_log("Error: Product ID cannot be empty.");
        return false;
    }
    if (empty($reviewerName)) {
        error_log("Error: Reviewer Name cannot be empty.");
        return false;
    }
    if (empty($reviewText)) {
        error_log("Error: Review Text cannot be empty.");
        return false;
    }

    // Data sanitization -  Important for security
    $reviewerName = htmlspecialchars($reviewerName);
    $reviewText = htmlspecialchars($reviewText);

    //  Database interaction -  Replace with your database connection and query logic.
    //  This is a simplified example.

    $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password"); // Replace with your DB details

    try {
        $stmt = $db->prepare("INSERT INTO reviews (productId, reviewerName, reviewText, rating) VALUES (:productId, :reviewerName, :reviewText, :rating)");
        $stmt->bindParam(':productId', $productId);
        $stmt->bindParam(':reviewerName', $reviewerName);
        $stmt->bindParam(':reviewText', $reviewText);
        $stmt->bindParam(':rating', $rating, PDO::PARAM_INT); // Ensure rating is an integer
        $stmt->execute();

        return true;

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return false;
    }
}


/**
 * Retrieves all reviews for a given product.
 *
 * @param string $productId The ID of the product to retrieve reviews for.
 * @return array An array of review objects, or an empty array if no reviews are found.
 */
function getReviewsForProduct(string $productId): array
{
    $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");  // Replace with your DB details
    $reviews = [];

    try {
        $stmt = $db->prepare("SELECT * FROM reviews WHERE productId = :productId");
        $stmt->bindParam(':productId', $productId);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $reviews[] = (object) [
                'id' => $row->id,
                'productId' => $row->productId,
                'reviewerName' => $row->reviewerName,
                'reviewText' => $row->reviewText,
                'rating' => $row->rating,
                'createdAt' => $row->createdAt // Assuming you have a createdAt column
            ];
        }

        return $reviews;

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return [];
    }
}



// Example Usage (Remember to replace placeholders with your actual data and database details)
//  This is just for demonstration.
$productId = "product123"; // Example product ID

// Save a review
if (saveUserReview($productId, "John Doe", "This is a great product!", 5)) {
    echo "Review saved successfully!
";
} else {
    echo "Error saving review.
";
}

// Get reviews for the product
$reviews = getReviewsForProduct($productId);

if (empty($reviews)) {
    echo "No reviews found for product $productId.
";
} else {
    echo "Reviews for product $productId:
";
    foreach ($reviews as $review) {
        echo "- " . $review->reviewerName . ": " . $review->reviewText . " (" . $review->rating . ")
";
    }
}

?>
