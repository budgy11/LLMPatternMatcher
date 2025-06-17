

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product or service.
 * It includes input validation and basic sanitization.
 *
 * @param string $productName The name of the product or service being reviewed.
 * @param string $reviewText The review text submitted by the user.
 * @param array $allowedExtensions (Optional) An array of allowed file extensions for image uploads. Defaults to an empty array.
 * @return array An array containing success/failure status and any relevant messages.
 */
function submit_review(string $productName, string $reviewText, array $allowedExtensions = [])
{
    // Input Validation & Sanitization
    if (empty($productName)) {
        return ['success' => false, 'message' => 'Product name cannot be empty.'];
    }
    if (empty($reviewText)) {
        return ['success' => false, 'message' => 'Review text cannot be empty.'];
    }

    // Sanitize Review Text (Basic - can be expanded)
    $reviewText = trim($reviewText); // Remove leading/trailing whitespace
    $reviewText = filter_var($reviewText, FILTER_SANITIZE_STRING); // Basic sanitization, removes HTML tags
    if (strlen($reviewText) > 10000) { // Limit review text length
        return ['success' => false, 'message' => 'Review text exceeds the maximum allowed length (10000 characters).'];
    }


    // ---  (Optional) Image Handling  ---
    $imageFile = $_FILES['image'] ?? null;  // Using null coalesce operator for null safety
    if ($imageFile && !empty($imageFile['name'])) {
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        $fileExtension = strtolower(pathinfo($imageFile['name'], PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowedTypes)) {
            return ['success' => false, 'message' => 'Invalid image file type.  Allowed types: ' . implode(',', $allowedTypes)];
        }

        // --- Image Processing (Simple Example - You'd likely use a library like GD or ImageMagick) ---
        $uploadDirectory = 'uploads/';
        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0777, true); // Create directory if it doesn't exist
        }

        $uniqueName = uniqid() . '.' . $fileExtension;
        $destination = $uploadDirectory . $uniqueName;
        if (move_uploaded_file($imageFile['tmp_name'], $destination)) {
            $imageURL = $destination; // Update imageURL
        } else {
            return ['success' => false, 'message' => 'Failed to upload image.'];
        }

    } else {
        $imageURL = null;  // No image uploaded
    }



    // ---  Store Review Data (Example - Replace with your database logic) ---
    // This is a simplified example, and you'd likely use a database query.
    $review = [
        'product_name' => $productName,
        'review_text' => $reviewText,
        'image_url' => $imageURL,
        'submission_timestamp' => time(),
    ];

    // Save to database (Example)
    // $result = save_to_database($review);  // Replace with your database function
    // if ($result === true) {
    //    return ['success' => true, 'message' => 'Review submitted successfully!'];
    // } else {
    //   return ['success' => false, 'message' => 'Failed to submit review to database. Error: ' . $result];
    // }


    // If everything goes well:
    return ['success' => true, 'message' => 'Review submitted successfully!'];
}

/**
 * Example Database Save Function (Placeholder - Replace with your actual DB code)
 *
 * @param array $review Data for the review.
 * @return bool|string True on success, error message on failure.
 */
function save_to_database(array $review) {
    // Replace with your database logic here.
    // This is just a placeholder for demonstration.
    //  For example:
    //  $db = new DatabaseConnection();
    //  $sql = "INSERT INTO reviews (product_name, review_text, image_url, submission_timestamp) VALUES (:product_name, :review_text, :image_url, :timestamp)";
    //  $stmt = $db->prepare($sql);
    //  $stmt->execute([
    //      ':product_name' => $review['product_name'],
    //      ':review_text' => $review['review_text'],
    //      ':image_url' => $review['image_url'],
    //      ':timestamp' => $review['submission_timestamp'],
    //  ]);
    //  return $stmt->rowCount() > 0 ? true : "Error inserting into database.  Error: " . $db->lastError();

    // Simulate success
    return true;
}


// --- Example Usage ---
$product = "Awesome Gadget X";
$review_text = "This gadget is fantastic!  I love it!";
$allowed_image_types = ['jpg', 'png'];

$result = submit_review($product, $review_text, $allowed_image_types);

if ($result['success']) {
    echo "<p>Review submitted successfully: " . $result['message'] . "</p>";
} else {
    echo "<p>Error submitting review: " . $result['message'] . "</p>";
}


?>
