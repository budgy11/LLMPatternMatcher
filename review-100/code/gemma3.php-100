
<?php

/**
 * This class provides functionality to store and manage user reviews.
 */
class ReviewManager
{
    private $reviews = [];

    /**
     * Adds a new review to the system.
     *
     * @param int $productId The ID of the product the review is for.
     * @param string $userName The name of the user who wrote the review.
     * @param string $reviewText The text of the review.
     * @param int $rating The rating given for the product (e.g., 1-5).
     * @return bool True if the review was added successfully, false otherwise.
     */
    public function addReview(int $productId, string $userName, string $reviewText, int $rating): bool
    {
        if (!is_int($productId) || $productId <= 0) {
            return false; // Invalid product ID
        }
        if (!is_string($userName) || empty($userName)) {
            return false; // Invalid user name
        }
        if (!is_string($reviewText) || empty($reviewText)) {
            return false; // Invalid review text
        }
        if (!is_int($rating) || $rating < 1 || $rating > 5) {
            return false; // Invalid rating
        }

        $this->reviews[$productId][] = [
            'user' => $userName,
            'text' => $reviewText,
            'rating' => $rating,
            'timestamp' => time() // Add a timestamp for when the review was added
        ];

        return true;
    }

    /**
     * Retrieves all reviews for a specific product.
     *
     * @param int $productId The ID of the product to retrieve reviews for.
     * @return array An array of review objects for the product, or an empty array if no reviews exist.
     */
    public function getReviewsForProduct(int $productId): array
    {
        if (!is_int($productId) || $productId <= 0) {
            return []; // Invalid product ID
        }

        return $this->reviews[$productId] ?? [];  // Use null coalesce operator for concise handling.
    }

    /**
     * Retrieves all reviews.
     *
     * @return array All reviews stored in the system.
     */
    public function getAllReviews(): array
    {
        return $this->reviews;
    }

    /**
     * Deletes a specific review by product ID and user.
     *  This is a more complex operation, as we need to identify the *exact* review to delete.
     *  Since we only store the review as an array in the reviews array, a more robust solution
     *  might involve storing a unique ID for each review.  However, this implementation provides
     *  a basic approach.
     *
     * @param int $productId The ID of the product the review is for.
     * @param string $userName The name of the user who wrote the review.
     * @return bool True if the review was deleted, false otherwise.
     */
    public function deleteReview(int $productId, string $userName): bool
    {
        if (!is_int($productId) || $productId <= 0) {
            return false; // Invalid product ID
        }
        if (!is_string($userName) || empty($userName)) {
            return false; // Invalid user name
        }

        $productReviews = $this->getReviewsForProduct($productId);
        if (empty($productReviews)) {
            return false;
        }

        foreach ($productReviews as $key => $review) {
            if ($review['user'] === $userName) {
                unset($productReviews[$key]); // Remove the review
                return true; // Review found and deleted
            }
        }

        return false; // Review not found
    }

    /**
     * Calculates the average rating for a product.
     *
     * @param int $productId The ID of the product.
     * @return float|null The average rating, or null if no reviews exist for the product.
     */
    public function getAverageRatingForProduct(int $productId): ?float
    {
        $reviews = $this->getReviewsForProduct($productId);
        if (empty($reviews)) {
            return null;
        }

        $totalRating = 0;
        foreach ($reviews as $review) {
            $totalRating += $review['rating'];
        }

        return $totalRating / count($reviews);
    }
}



// Example Usage:
$reviewManager = new ReviewManager();

// Add reviews
$reviewManager->addReview(123, 'John Doe', 'Great product!', 5);
$reviewManager->addReview(123, 'Jane Smith', 'Could be better', 3);
$reviewManager->addReview(456, 'Peter Jones', 'Excellent value', 4);

// Get reviews for product 123
$reviews123 = $reviewManager->getReviewsForProduct(123);
print_r($reviews123);

// Get average rating for product 123
$averageRating = $reviewManager->getAverageRatingForProduct(123);
echo "Average rating for product 123: " . ($averageRating !== null ? $averageRating : "No reviews") . "
";

// Delete a review
$reviewManager->deleteReview(123, 'John Doe');

// Get reviews for product 123 again after deletion
$reviews123 = $reviewManager->getReviewsForProduct(123);
print_r($reviews123);


<?php

/**
 * This function creates a user review object and handles basic validation.
 *
 * @param string $name The reviewer's name.
 * @param string $rating A numerical rating (e.g., 1-5).
 * @param string $comment The reviewer's comment.
 *
 * @return array|false An associative array representing the review if valid,
 *                     false otherwise.
 */
function createReview(string $name, string $rating, string $comment): array|false
{
    // Validate input
    if (empty($name)) {
        error_log("Review: Empty name provided.");  // Log for debugging
        return false;
    }

    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        error_log("Review: Invalid rating provided.  Must be a number between 1 and 5.");
        return false;
    }

    if (empty($comment)) {
        error_log("Review: Empty comment provided.");
        return false;
    }


    // Create the review object
    $review = [
        'name' => $name,
        'rating' => $rating,
        'comment' => $comment,
        'date' => date('Y-m-d H:i:s') // Add the date/time of the review.
    ];

    return $review;
}


/**
 * Example Usage
 */

// Valid review
$review1 = createReview("Alice Smith", 4, "Great product!  Highly recommend.");
if ($review1) {
    echo "Review 1:
";
    print_r($review1);
    echo "
";
} else {
    echo "Review 1 creation failed.
";
}

// Invalid review - empty name
$review2 = createReview("", 5, "Good!");
if ($review2) {
    echo "Review 2:
";
    print_r($review2);
    echo "
";
} else {
    echo "Review 2 creation failed.
";
}

// Invalid review - invalid rating
$review3 = createReview("Bob Johnson", 6, "Okay.");
if ($review3) {
    echo "Review 3:
";
    print_r($review3);
    echo "
";
} else {
    echo "Review 3 creation failed.
";
}

// Valid review with no comment
$review4 = createReview("Charlie Brown", 3, "");
if ($review4) {
    echo "Review 4:
";
    print_r($review4);
    echo "
";
} else {
    echo "Review 4 creation failed.
";
}
?>


<?php

/**
 * User Review Function
 *
 * This function allows you to create, display, and manage user reviews.
 * It's a basic implementation and can be expanded with more features
 * (e.g., moderation, rating scales, image uploads).
 *
 * @param string $productName The name of the product being reviewed.
 * @param string $userID The ID of the user submitting the review.
 * @param string $reviewText The text of the review.
 * @param int $rating (Optional) The rating given for the product (1-5).
 *
 * @return array An array containing the review ID, or an error message if the review could not be saved.
 */
function createReview(string $productName, string $userID, string $reviewText, int $rating = 1) {
    // --- Input Validation ---
    if (empty($productName)) {
        return ['error' => 'Product name cannot be empty.'];
    }
    if (empty($userID)) {
        return ['error' => 'User ID cannot be empty.'];
    }
    if (empty($reviewText)) {
        return ['error' => 'Review text cannot be empty.'];
    }

    if ($rating < 1 || $rating > 5) {
        return ['error' => 'Rating must be between 1 and 5.'];
    }

    // --- Data Sanitization & Escaping (IMPORTANT!) ---
    $productName = htmlspecialchars($productName);  // Escape HTML tags
    $reviewText = htmlspecialchars($reviewText);   // Escape HTML tags
    $userID = htmlspecialchars($userID);

    // --- Data Storage (Simplified - Replace with Database Logic) ---
    // In a real application, you would save this data to a database.
    // This example uses an in-memory array for demonstration purposes.
    $reviews = isset($_SESSION['reviews']) ? $_SESSION['reviews'] : []; // Load from session or initialize

    $reviewID = count($reviews) + 1; // Simple ID generation
    $review = [
        'reviewID' => $reviewID,
        'productName' => $productName,
        'userID' => $userID,
        'reviewText' => $reviewText,
        'rating' => $rating,
        'timestamp' => time()
    ];

    $reviews[$reviewID] = $review;
    $_SESSION['reviews'] = $reviews; // Save back to session

    return [
        'success' => true,
        'reviewID' => $reviewID
    ];
}

/**
 * Display a Single Review
 *
 * Displays a single review's details.
 *
 * @param array $review The review data.
 */
function displayReview(array $review) {
    echo "<h3>Review for: " . $review['productName'] . "</h3>";
    echo "<p><strong>User:</strong> " . $review['userID'] . "</p>";
    echo "<p><strong>Rating:</strong> " . $review['rating'] . "</p>";
    echo "<p><strong>Date:</strong> " . date("Y-m-d H:i:s", $review['timestamp']) . "</p>";
    echo "<p>" . $review['reviewText'] . "</p>";
}

/**
 * Display All Reviews for a Product
 *
 * Displays all reviews for a specific product.
 *
 * @param string $productName The product name.
 */
function displayProductReviews(string $productName) {
    // --- Retrieve Reviews (Replace with Database Query) ---
    $reviews = isset($_SESSION['reviews']) ? $_SESSION['reviews'] : [];

    $productReviews = [];
    foreach ($reviews as $review) {
        if ($review['productName'] == $productName) {
            $productReviews[] = $review;
        }
    }

    // --- Display Reviews ---
    if (empty($productReviews)) {
        echo "<p>No reviews found for this product.</p>";
    } else {
        echo "<h3>Reviews for " . $productName . "</h3>";
        foreach ($productReviews as $review) {
            displayReview($review);
        }
    }
}

// --- Example Usage (Demonstration) ---
// 1. Create a Review
session_start(); // Start the session

$reviewResult = createReview('Awesome Gadget', 'user123', 'This is a fantastic gadget!');
if (isset($reviewResult['error'])) {
    echo "<p style='color:red;'>Error creating review: " . $reviewResult['error'] . "</p>";
} else {
    echo "<p style='color:green;'>Review created successfully. Review ID: " . $reviewResult['reviewID'] . "</p>";
}

// 2. Display Reviews for the Product
displayProductReviews('Awesome Gadget');
?>


<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display user reviews.
 *
 * @param string $productId The ID of the product being reviewed.
 * @param string $reviewText The text of the review submitted by the user.
 * @param string $reviewerName The name of the user submitting the review.
 * @param int $rating (Optional)  A rating from 1-5. Defaults to 0.
 *
 * @return array  An array containing the review data, or an error message if the review fails.
 */
function submitReview(string $productId, string $reviewText, string $reviewerName, int $rating = 0) {

  // Input validation - basic checks
  if (empty($reviewText)) {
    return ['status' => 'error', 'message' => 'Review text cannot be empty.'];
  }
  if (empty($reviewerName)) {
    return ['status' => 'error', 'message' => 'Reviewer name cannot be empty.'];
  }

  // Validation for rating if provided
  if ($rating < 1 || $rating > 5) {
    return ['status' => 'error', 'message' => 'Rating must be between 1 and 5.'];
  }


  // Store the review (Simulating a database insert - replace with your database logic)
  $review = [
    'product_id' => $productId,
    'reviewer_name' => $reviewerName,
    'review_text' => $reviewText,
    'rating' => $rating,
    'timestamp' => time() // Add a timestamp
  ];

  //Simulate database insertion. In a real application, use a database query.
  //This example just returns the review.
  return $review;

}

/**
 * Display Reviews Function
 *
 * This function takes an array of reviews and displays them in a user-friendly format.
 *
 * @param array $reviews An array of review data (as returned by submitReview).
 */
function displayReviews(array $reviews) {
    echo "<h2>Reviews for Product ID: " . $reviews[0]['product_id'] . "</h2>"; // Assuming product ID is in the first element of the array.

    if (empty($reviews)) {
        echo "<p>No reviews yet.</p>";
        return;
    }

    echo "<ul>";
    foreach ($reviews as $review) {
        echo "<li>";
        echo "<strong>Reviewer:</strong> " . htmlspecialchars($review['reviewer_name']) . "<br>";
        echo "<strong>Rating:</strong> " . $review['rating'] . " / 5<br>";
        echo "<strong>Review:</strong> " . htmlspecialchars($review['review_text']) . "<br>";
        echo "<strong>Timestamp:</strong> " . date('Y-m-d H:i:s', $review['timestamp']) . "</li>";
    }
    echo "</ul>";
}

// Example Usage:
$product_id = "123";

// Submit a review
$review_data = submitReview($product_id, "This is a fantastic product!", "John Doe", 5);

if ($review_data['status'] === 'success') {
    echo "<h2>Review Submitted Successfully!</h2>";
    echo "<p>Review ID: " . $review_data['id'] . "</p>";  // Assuming you've added a unique ID
} else {
    echo "<p style='color: red;'>Error submitting review: " . $review_data['message'] . "</p>";
}

// Display the review
displayReviews($review_data);

// Example of submitting an invalid review
$invalid_review = submitReview($product_id, "", "Jane Smith");
if ($invalid_review['status'] === 'error') {
    echo "<p style='color: red;'>Error submitting review: " . $invalid_review['message'] . "</p>";
}
?>


<?php

/**
 * User Review Function
 *
 * This function allows you to collect and display user reviews for a given product or item.
 * It includes input validation, sanitization, and basic display functionality.
 *
 * @param string $productName The name of the product or item being reviewed.
 * @param array $reviews An array of user review objects.
 *                     Each review object should have 'user' and 'comment' properties.
 * @return string HTML formatted display of user reviews.  Returns an error message if input is invalid.
 */
function displayUserReviews(string $productName, array $reviews)
{
    // Input Validation and Sanitization (Crucial for Security!)
    if (empty($productName)) {
        return "<p style='color:red;'>Error: Product name cannot be empty.</p>";
    }

    if (!is_array($reviews)) {
        return "<p style='color:red;'>Error: Reviews must be an array.</p>";
    }

    foreach ($reviews as $review) {
        if (!is_object($review) || !isset($review->user) || !isset($review->comment)) {
            return "<p style='color:red;'>Error: Each review must be an object with 'user' and 'comment' properties.</p>";
        }
    }

    // HTML Formatting
    $html = "<div class='user-reviews'>";
    $html .= "<h2>Reviews for {$productName}</h2>";

    if (empty($reviews)) {
        $html .= "<p>No reviews yet. Be the first to review!</p>";
    } else {
        foreach ($reviews as $review) {
            $html .= "<div class='review'>";
            $html .= "<p class='review-user'><strong>User:</strong> {$review->user}</p>";
            $html .= "<p class='review-comment'><em>{$review->comment}</em></p>";
            $html .= "</div>";
        }
    }

    $html .= "</div>";

    return $html;
}


// Example Usage:

// Sample Reviews (Replace with actual data)
$reviews = [
    (object) [
        'user' => 'John Doe',
        'comment' => 'Great product!  Easy to use and works perfectly.'
    ],
    (object) [
        'user' => 'Jane Smith',
        'comment' => 'I love this!  Highly recommend.'
    ],
    (object) [
        'user' => 'Peter Jones',
        'comment' => 'Could be better, but overall a good value.'
    ]
];

// Call the function
$reviewHtml = displayUserReviews("Awesome Widget", $reviews);

// Output the HTML (for demonstration - you'd typically display this in a web page)
echo $reviewHtml;


// Example of an error case:
$errorHtml = displayUserReviews("", $reviews);
echo "<br><br>Error Example:<br>" . $errorHtml;
?>


<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or item.
 *
 * @param string $product_id The unique identifier of the product.
 * @param string $user_name The name of the user submitting the review.
 * @param string $review_text The text of the review.
 * @param int $rating (optional) A rating from 1 to 5 (default: null).
 * @param int $timestamp (optional) Unix timestamp of when the review was created (default: current timestamp).
 * @return array|string  Returns an array on success, or an error message string on failure.
 */
function storeUserReview(string $product_id, string $user_name, string $review_text, ?int $rating = null, ?int $timestamp = null) {
    // Validation - basic checks to prevent malicious input.  Expand as needed.
    if (empty($product_id) || empty($user_name) || empty($review_text)) {
        return "Error: Product ID, User Name, and Review Text cannot be empty.";
    }
    if ($rating === null) {
        $rating = null; // Allow null rating.
    }
    if ($rating < 1 || $rating > 5) {
        return "Error: Rating must be between 1 and 5.";
    }

    //  Database interaction -  This is a placeholder.  Replace with your actual database logic.
    //  This example demonstrates how to store the data in an array (simulating a database).

    $review = [
        'product_id' => $product_id,
        'user_name' => $user_name,
        'review_text' => $review_text,
        'rating' => $rating,
        'timestamp' => $timestamp ?? time() // Use current time if timestamp is not provided.
    ];

    //  In a real application, you'd insert this data into a database.

    // Example database storage (replace with your actual database code)
    //  e.g.,  $db->insert('reviews', $review);

    // Successful Storage
    return $review;
}


/**
 *  Example function to retrieve reviews for a product.  This is just an example; 
 *  adapt it to your database design and retrieval method.
 *
 * @param string $product_id The ID of the product to retrieve reviews for.
 * @return array|string Returns an array of reviews or an error message.
 */
function getProductReviews(string $product_id) {
    //  Example:  Retrieve reviews from a database table called 'reviews'
    //  Assuming the table has columns: product_id, user_name, review_text, rating, timestamp

    //  This is just an example; adapt it to your database design and retrieval method.

    $reviews = [];

    //  Replace this with your database query
    // Example using a simulated database (replace with actual query)
    // $result = $db->query("SELECT * FROM reviews WHERE product_id = ?", $product_id);
    // while ($row = $result->fetch_assoc()) {
    //     $reviews[] = $row;
    // }

    // Simulate a database result
    if ($product_id === '123') {
        $reviews = [
            ['product_id' => '123', 'user_name' => 'John Doe', 'review_text' => 'Great product!', 'rating' => 5, 'timestamp' => time()],
            ['product_id' => '123', 'user_name' => 'Jane Smith', 'review_text' => 'Good value for money.', 'rating' => 4, 'timestamp' => time() - 3600], // A review from an hour ago
        ];
    } else {
        $reviews = []; // No reviews found for this product ID
    }

    return $reviews;
}


// --- Example Usage ---

// Store a review
$review_data = storeUserReview('123', 'Alice', 'This is an amazing product!', 5);
if (is_array($review_data)) {
    echo "Review stored successfully: " . json_encode($review_data) . "
";
} else {
    echo "Error storing review: " . $review_data . "
";
}


// Get reviews for product '123'
$product_reviews = getProductReviews('123');
if (is_array($product_reviews)) {
    echo "Product Reviews:
";
    foreach ($product_reviews as $review) {
        echo "  - " . json_encode($review) . "
";
    }
} else {
    echo "No reviews found for product '123'
";
}


// Store another review with a timestamp
$review_data2 = storeUserReview('456', 'Bob', 'Okay product, could be better.', 4, time() - 7200); // Review from 2 hours ago.
if (is_array($review_data2)) {
    echo "Review stored successfully: " . json_encode($review_data2) . "
";
} else {
    echo "Error storing review: " . $review_data2 . "
";
}
?>


<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or item.
 *
 * @param string $product_id The ID of the product being reviewed.
 * @param string $user_name The name of the user submitting the review.
 * @param string $review_text The text of the review.
 * @param int $rating (Optional) The rating given by the user (1-5). Defaults to 0.
 * @return array|false Returns an array containing the review ID, or false if the review could not be saved.
 */
function saveUserReview(string $product_id, string $user_name, string $review_text, int $rating = 0) {
  // Validate inputs (you can expand this validation)
  if (empty($product_id) || empty($user_name) || empty($review_text)) {
    return false; // Return false for invalid input
  }

  if ($rating < 1 || $rating > 5) {
    $rating = 0; //  Or handle this differently, e.g., throw an exception.
  }

  //  Database connection (replace with your database setup)
  $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');

  try {
    // Prepare the SQL statement
    $stmt = $db->prepare(
      "INSERT INTO reviews (product_id, user_name, review_text, rating) 
       VALUES (:product_id, :user_name, :review_text, :rating)"
    );

    // Bind the parameters
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':user_name', $user_name);
    $stmt->bindParam(':review_text', $review_text);
    $stmt->bindParam(':rating', $rating);

    // Execute the statement
    $stmt->execute();

    // Get the last inserted ID
    $review_id = $db->lastInsertId();

    return ['id' => $review_id]; // Return the review ID
  } catch (PDOException $e) {
    // Handle database errors
    error_log("Database error: " . $e->getMessage());  // Log for debugging
    return false; // Return false on error
  }
}


/**
 * Display User Reviews
 *
 * This function retrieves and displays user reviews for a product.
 *
 * @param string $product_id The ID of the product.
 * @return array|false Returns an array of reviews or false if no reviews are found.
 */
function displayUserReviews(string $product_id) {
  // Database connection
  $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');

  try {
    // Prepare the SQL statement
    $stmt = $db->prepare("SELECT id, user_name, review_text, rating FROM reviews WHERE product_id = :product_id");
    $stmt->bindParam(':product_id', $product_id);
    $stmt->execute();

    $reviews = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $reviews[] = [
        'id' => $row['id'],
        'user_name' => $row['user_name'],
        'review_text' => $row['review_text'],
        'rating' => $row['rating']
      ];
    }

    return $reviews;

  } catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    return false;
  }
}

// Example Usage:
// 1. Save a Review
$review_data = saveUserReview('product123', 'John Doe', 'This is a great product!', 5);

if ($review_data) {
  echo "Review saved successfully!  Review ID: " . $review_data['id'] . "
";
} else {
  echo "Failed to save review.
";
}

// 2. Display Reviews for a Product
$reviews = displayUserReviews('product123');

if ($reviews) {
  echo "Reviews for product123:
";
  foreach ($reviews as $review) {
    echo "  User: " . $review['user_name'] . ", Rating: " . $review['rating'] . ", Text: " . $review['review_text'] . "
";
  }
} else {
  echo "No reviews found for product123.
";
}


?>


<?php

/**
 * User Review Function
 *
 * This function allows you to create, retrieve, update, and delete user reviews.
 * It demonstrates basic CRUD operations with a simplified database interaction.
 *
 * NOTE: This is a simplified example.  In a real-world application, you'd
 *       use prepared statements and proper error handling for security
 *       and robustness.  Also, consider using an ORM (Object-Relational Mapper)
 *       for easier database interactions.
 */

class Review {

    private $id;
    private $user_id;
    private $product_id;
    private $rating;
    private $comment;
    private $created_at;

    public function __construct($user_id, $product_id, $rating, $comment = "") {
        $this->user_id = $user_id;
        $this->product_id = $product_id;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->created_at = date("Y-m-d H:i:s"); // Set timestamp
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getUser_id() {
        return $this->user_id;
    }

    public function setProduct_id($product_id) {
        $this->product_id = $product_id;
    }

    public function getRating() {
        return $this->rating;
    }

    public function getComment() {
        return $this->comment;
    }

    public function getCreated_at() {
        return $this->created_at;
    }

    public function __toString() {
        return "ID: {$this->id}, User ID: {$this->user_id}, Product ID: {$this->product_id}, Rating: {$this->rating}, Comment: {$this->comment}, Created At: {$this->created_at}";
    }
}


// --- Example Usage (Demonstration) ---

// 1. Create a new review
$review1 = new Review(123, 456, 5, "Great product!");
echo "Created Review: " . $review1 . "
";

// 2. Retrieve a review by ID (Assume we have a database query to get the review)
// (This part would be replaced by your database query logic)
// For demonstration, we'll just return a mock review ID.
$reviewId = 1;
$review2 = new Review($user_id = 123, $product_id = 456, $rating = 4, $comment = "Good but could be better");
$review2->setId($reviewId);

echo "Retrieved Review ID: " . $review2 . "
";


// 3. Update a review (Simplified - in a real app, you'd likely have a more robust update process)
$review2->setComment("Amazing product - highly recommended!");
echo "Updated Review: " . $review2 . "
";

// 4. Delete a review (Simplified)
//  (In a real app, you'd delete from the database)
//  echo "Deleted Review (Simulated): " . $review2 . "
";


// ---  Simulated Database Interaction (Replace with your actual DB query) ---

//  This is a placeholder.  In a real application, you would use a database connector
//  (e.g., MySQLi, PDO) to interact with your database.

// Example of a simple create function:
/*
function createReview($user_id, $product_id, $rating, $comment) {
    // Your database connection code here (e.g., mysqli_connect)
    // ...
    // Example SQL:
    // "INSERT INTO reviews (user_id, product_id, rating, comment) VALUES (?, ?, ?, ?)"
    // $stmt = $conn->prepare("INSERT INTO reviews (user_id, product_id, rating, comment) VALUES (?, ?, ?, ?)");
    // $stmt->bind_param("isss", $user_id, $product_id, $rating, $comment);
    // $stmt->execute();
    // ...
}
*/

?>


<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews.
 * It includes features for saving reviews, retrieving reviews,
 * and displaying them.
 */

class UserReview {

    private $db_host = "localhost"; // Replace with your database connection details
    private $db_name = "your_database_name";
    private $db_user = "your_username";
    private $db_password = "your_password";

    public function __construct() {
        // Establish database connection
        $this->conn = new mysqli($this->db_host, $this->db_user, $this->db_password, $this->db_name);

        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    /**
     * Saves a new user review.
     *
     * @param string $username   The username of the reviewer.
     * @param string $reviewText The text of the review.
     * @return bool True on success, false on failure.
     */
    public function saveReview($username, $reviewText) {
        $reviewText = $this->conn->real_escape_string($reviewText); // Sanitize input

        $sql = "INSERT INTO reviews (username, review_text) VALUES ('$username', '$reviewText')";

        if ($this->conn->query($sql) === TRUE) {
            return true;
        } else {
            echo "Error: " . $sql . "<br>" . $this->conn->error;
            return false;
        }
    }

    /**
     * Retrieves all user reviews.
     *
     * @return array An array of review objects.  Returns an empty array if no reviews are found.
     */
    public function getReviews() {
        $sql = "SELECT id, username, review_text, created_at FROM reviews ORDER BY created_at DESC";
        $result = $this->conn->query($sql);

        $reviews = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $reviews[] = new ReviewObject($row);  // Pass the row data to the ReviewObject
            }
        }
        return $reviews;
    }


    /**
     *  Review Object -  A simple class to represent a review.
     */
    private function __construct(){} //Prevent instantiation


    // Nested class -  Allows instantiation of the review object.
    private static $instance = null; // Singleton pattern.

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

}


/**
 * Review Object -  A simple class to represent a review.
 */
class ReviewObject {
    public $id;
    public $username;
    public $review_text;
    public $created_at;

    public function __construct($row) {
        $this->id = $row["id"];
        $this->username = $row["username"];
        $this->review_text = $row["review_text"];
        $this->created_at = $row["created_at"];
    }
}



// Example usage (after creating the database table 'reviews')

// Create an instance of the Review class
$review = UserReview::getInstance();

// Save a review
if ($review->saveReview("JohnDoe", "This is a great product!")) {
    echo "Review saved successfully!";
} else {
    echo "Failed to save review.";
}

// Retrieve all reviews
$allReviews = $review->getReviews();

// Display reviews
echo "<br><br><h2>All Reviews:</h2>";
foreach ($allReviews as $review) {
    echo "<strong>Username:</strong> " . $review->username . "<br>";
    echo "<strong>Review:</strong> " . $review->review_text . "<br>";
    echo "<strong>Date:</strong> " . $review->created_at . "<br><br>";
}
?>


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


<?php

/**
 * User Review Function
 *
 * This function allows you to create, read, update, and delete user reviews.
 *
 * @param string $productName The name of the product the review is for.
 * @param string $userEmail The email address of the user submitting the review.
 * @param string $reviewText The text of the review.
 * @param array $reviewData (Optional) An associative array containing review data
 *                         (e.g., ['rating' => 5, 'timestamp' => time()])
 *
 * @return array An array containing the result of the operation.  Returns an error message
 *               on failure.  Returns a success message and review ID on success.
 */
function create_review(string $productName, string $userEmail, string $reviewText, array $reviewData = [])
{
  // Validate inputs (Basic Example - Expand for more robust validation)
  if (empty($productName)) {
    return ['status' => 'error', 'message' => 'Product name cannot be empty.'];
  }
  if (empty($userEmail)) {
    return ['status' => 'error', 'message' => 'User email cannot be empty.'];
  }
  if (empty($reviewText)) {
    return ['status' => 'error', 'message' => 'Review text cannot be empty.'];
  }

  // Sanitize inputs -  IMPORTANT:  Always sanitize user inputs!
  $productName = filter_var($productName, FILTER_SANITIZE_STRING);
  $reviewText = filter_var($reviewText, FILTER_SANITIZE_STRING);

  // Simulate a database insert.  Replace with your actual database logic.
  // This is just a placeholder example.
  $reviewId = generate_unique_id();  // Replace with your unique ID generation
  $timestamp = time();

  $review = [
    'product_name' => $productName,
    'user_email' => $userEmail,
    'review_text' => $reviewText,
    'rating' => isset($reviewData['rating']) ? $reviewData['rating'] : null,
    'timestamp' => $timestamp,
    'review_id' => $reviewId,
  ];

  // In a real application, you would save this data to a database.
  // For this example, we just log the review.
  log_review($review);

  return ['status' => 'success', 'message' => 'Review created successfully.', 'review_id' => $reviewId];
}


/**
 * Simulates generating a unique ID.
 *  Replace with your actual unique ID generation logic.
 * @return string
 */
function generate_unique_id() {
    return bin2hex(random_bytes(16)); // A simple way to generate a unique ID
}


/**
 * Simulates logging a review to a file.
 *  Replace this with your actual logging mechanism.
 * @param array $review
 */
function log_review(array $review) {
    // This is just a placeholder.  Replace with your logging code.
    file_put_contents('review_log.txt', "New Review: " . json_encode($review) . "
", FILE_APPEND);
}

// --- Example Usage ---
// Example 1: Create a review
$result = create_review('Awesome Gadget', 'test@example.com', 'Great product!');
print_r($result);

// Example 2: Create a review with a rating
$result = create_review('Another Product', 'user@email.net', 'Good but could be better.', ['rating' => 4]);
print_r($result);
?>


<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a given item.
 *
 * @param string $item_id The unique ID of the item being reviewed.
 * @param string $reviewer_name The name of the user submitting the review.
 * @param string $review_text The text of the review.
 * @param array $reviews (optional) An array of existing reviews to store and display.
 *
 * @return array An updated array of reviews, including the new review if successful.
 */
function create_and_display_review(string $item_id, string $reviewer_name, string $review_text, array &$reviews = []): array
{
  // Validation (basic - you might want more robust validation)
  if (empty($reviewer_name)) {
    return $reviews; // Return existing reviews if no name provided
  }
  if (empty($review_text)) {
    return $reviews; // Return existing reviews if no text provided
  }

  // Create a new review
  $new_review = [
    'item_id' => $item_id,
    'reviewer_name' => $reviewer_name,
    'review_text' => $review_text,
    'timestamp' => date('Y-m-d H:i:s')
  ];

  // Add the new review to the array
  $reviews[] = $new_review;

  // Sort reviews by timestamp (optional -  can be useful for display)
  usort($reviews, function($a, $b) {
    return $a['timestamp'] <=> $b['timestamp'];
  });

  return $reviews;
}


/**
 * Display Reviews Function (for demonstration purposes)
 *
 * This function formats and displays the reviews.
 *
 * @param array $reviews The array of reviews to display.
 */
function display_reviews(array $reviews) {
  echo "<h2>Reviews for Item ID: " . $reviews[0]['item_id'] . "</h2>"; // Access the first item's ID
  echo "<ul>";
  foreach ($reviews as $review) {
    echo "<li>";
    echo "<strong>Reviewer:</strong> " . htmlspecialchars($review['reviewer_name']) . "<br>";
    echo "<strong>Date:</strong> " . $review['timestamp'] . "<br>";
    echo "<strong>Rating:</strong> " . $review['review_text'] . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}



// Example Usage:

// Initialize an empty array of reviews.  Pass by reference (&) so the function can modify it.
$reviews = [];

// Create a review
$reviews = create_and_display_review('product123', 'John Doe', 'Great product!  I highly recommend it.');

// Create another review
$reviews = create_and_display_review('product456', 'Jane Smith', 'It was okay, nothing special.');

// Display the reviews
display_reviews($reviews);

// Show the final reviews array (for demonstration)
echo "<hr>";
echo "<h3>Final Reviews Array:</h3>";
print_r($reviews); // Output the array to see the results.
?>


<?php

/**
 * User Review Function
 *
 * This function allows you to generate a formatted HTML list of user reviews.
 *
 * @param array $reviews An array of review objects.  Each object should have:
 *                       - author: The name of the reviewer.
 *                       - rating: An integer representing the rating (e.g., 1-5).
 *                       - comment: The text of the review.
 * @param array $options (optional) An array of options to customize the output.
 *                       - maxRating:  The maximum rating value to display (default: 5).
 *                       - ratingDisplay: How to display the rating (e.g., 'stars', 'text').
 *                       - starIcon:  Path to the star icon image.
 *
 * @return string HTML string of the user reviews.
 */
function generateUserReviews(array $reviews, array $options = []) {
    // Default options
    $maxRating = $options['maxRating'] ?? 5;
    $ratingDisplay = $options['ratingDisplay'] ?? 'stars';
    $starIcon = $options['starIcon'] ?? '';

    if (empty($reviews)) {
        return "<p>No reviews yet.</p>";
    }

    $html = '<ul>';
    foreach ($reviews as $review) {
        $html .= '<li>';
        $html .= '<div class="review">';

        $html .= '<div class="review-author">' . htmlspecialchars($review['author']) . '</div>';

        if ($ratingDisplay === 'stars') {
            $html .= '<div class="review-rating">';
            for ($i = 1; $i <= $maxRating; $i++) {
                if ($i <= $review['rating']) {
                    $html .= '<img src="' . $starIcon . '" alt="Star" width="20" height="20">';
                } else {
                    $html .= '&nbsp;'; // Add space for empty stars
                }
            }
            $html .= '</div>';
        } else {
            $html .= '<div class="review-rating">' . $review['rating'] . '/' . $maxRating . '</div>';
        }

        $html .= '<div class="review-comment">' . htmlspecialchars($review['comment']) . '</div>';
        $html .= '</div>';
        $html .= '</li>';
    }
    $html .= '</ul>';

    return $html;
}

// Example usage:
$reviews = [
    ['author' => 'John Doe', 'rating' => 4, 'comment' => 'Great product!  Highly recommended.'],
    ['author' => 'Jane Smith', 'rating' => 5, 'comment' => 'Excellent value for money.'],
    ['author' => 'Peter Jones', 'rating' => 3, 'comment' => 'It was okay, but could be better.'],
];

// With default options
$reviewsHTML = generateUserReviews($reviews);
echo $reviewsHTML;

// With custom options
$customOptions = [
    'maxRating' => 10,
    'ratingDisplay' => 'text',
    'starIcon' => 'star.png' // Replace with the path to your star icon image
];
$customReviewsHTML = generateUserReviews($reviews, $customOptions);
echo "<hr>";
echo $customReviewsHTML;

?>


<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or item.
 * It includes basic validation and error handling.
 *
 * @param string $productId The unique identifier for the product or item.
 * @param string $reviewerName The name of the user submitting the review.
 * @param string $reviewText The text of the review.
 * @param int $rating (optional) The rating (e.g., 1-5). Defaults to 0.
 * @param string $dbConnection (optional)  A connection object to your database.  If not provided, a default is used.
 * @return array An array containing:
 *   - 'success': True if the review was saved successfully, false otherwise.
 *   - 'message':  A message indicating the result of the operation.
 *   - 'reviewId': The ID of the newly created review (if successful), or null.
 */
function saveUserReview(string $productId, string $reviewerName, string $reviewText, int $rating = 0, $dbConnection = null)
{
    $success = false;
    $message = '';
    $reviewId = null;

    // Database connection - Use a default if not provided
    if ($dbConnection === null) {
        // Replace this with your actual database connection setup
        $dbConnection = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");
    }

    try {
        // Validate inputs (Important!)
        if (empty($reviewerName)) {
            return ['success' => false, 'message' => 'Reviewer name cannot be empty.', 'reviewId' => null];
        }
        if (empty($reviewText)) {
            return ['success' => false, 'message' => 'Review text cannot be empty.', 'reviewId' => null];
        }

        // Sanitize inputs (important to prevent SQL injection!)
        $reviewerName = trim($reviewerName);
        $reviewText = trim($reviewText);

        // Escape data for safe database insertion
        $stmt = $dbConnection->prepare("INSERT INTO reviews (productId, reviewerName, reviewText, rating) VALUES (:productId, :reviewerName, :reviewText, :rating)");
        $stmt->bindParam(':productId', $productId);
        $stmt->bindParam(':reviewerName', $reviewerName);
        $stmt->bindParam(':reviewText', $reviewText);
        $stmt->bindParam(':rating', $rating);

        $stmt->execute();

        $reviewId = $dbConnection->lastInsertId();  // Get the ID of the newly inserted row

        $success = true;
        $message = 'Review saved successfully.';

    } catch (PDOException $e) {
        $message = 'Error saving review: ' . $e->getMessage();
        error_log($e->getMessage()); // Log the error for debugging
    }

    return ['success' => $success, 'message' => $message, 'reviewId' => $reviewId];
}


/**
 * Display User Reviews
 *
 * This function retrieves and displays user reviews for a given product.
 *
 * @param string $productId The unique identifier for the product.
 * @param PDO $dbConnection  A PDO database connection object.
 */
function displayUserReviews(string $productId, PDO $dbConnection)
{
    // Retrieve reviews for the product
    $stmt = $dbConnection->prepare("SELECT id, reviewerName, reviewText, rating FROM reviews WHERE productId = :productId");
    $stmt->bindParam(':productId', $productId);
    $stmt->execute();

    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($reviews)) {
        echo "<p>No reviews yet.</p>";
    } else {
        echo "<h2>Reviews for Product: " . $productId . "</h2>";
        echo "<ul>";
        foreach ($reviews as $review) {
            echo "<li>";
            echo "<strong>" . $review['reviewerName'] . ":</strong> " . htmlspecialchars($review['reviewText']) . " (Rating: " . $review['rating'] . ")";
            echo "</li>";
        }
        echo "</ul>";
    }
}


// Example usage (in a web environment, you would typically call these functions
// in response to a form submission and then render the results)

// Simulate a database connection (replace with your actual connection)
$dbConnection = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");


// Example 1: Save a review
$result = saveUserReview("product123", "John Doe", "This is a great product!", 5);
if ($result['success']) {
    echo "<p>Review saved successfully. Review ID: " . $result['reviewId'] . "</p>";
} else {
    echo "<p>Error saving review: " . $result['message'] . "</p>";
}


// Example 2: Display reviews for a product
displayUserReviews("product123", $dbConnection);

?>


<?php

/**
 * User Review Function
 *
 * This function allows you to create, read, update, and delete user reviews.
 * It includes basic validation and error handling.
 *
 * @param string $productId The ID of the product being reviewed.
 * @param string $userEmail The email address of the user submitting the review.
 * @param string $rating   The rating given by the user (e.g., 1-5).
 * @param string $comment  The user's review comment.
 * @param array $dbConnection  A database connection object. (Optional, for database interaction)
 *
 * @return array An array containing the results:
 *   - 'success': True if the operation was successful, False otherwise.
 *   - 'message': A message indicating the outcome of the operation.
 *   - 'data': The review data (if successful).
 */
function createReview(
    string $productId,
    string $userEmail,
    string $rating,
    string $comment,
    array $dbConnection = null // Allow passing in a database connection
) {
    // Basic Validation
    if (empty($productId) || empty($userEmail) || empty($rating) || empty($comment)) {
        return [
            'success' => false,
            'message' => 'All fields are required.',
            'data' => null,
        ];
    }

    if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        return [
            'success' => false,
            'message' => 'Invalid email format.',
            'data' => null,
        ];
    }

    if (!preg_match('/^[1-5]+$/', $rating)) {
        return [
            'success' => false,
            'message' => 'Rating must be a number between 1 and 5.',
            'data' => null,
        ];
    }

    // Example: Storing in a database (adjust to your database schema)
    try {
        $stmt = $dbConnection->prepare(
            "INSERT INTO reviews (product_id, user_email, rating, comment, created_at)
             VALUES (:product_id, :user_email, :rating, :comment, NOW())"
        );

        $stmt->bindParam(':product_id', $productId);
        $stmt->bindParam(':user_email', $userEmail);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment', $comment);

        $stmt->execute();
        $reviewId = $stmt->lastInsertId();

        return [
            'success' => true,
            'message' => 'Review created successfully.',
            'data' => [
                'review_id' => $reviewId,
                'product_id' => $productId,
                'user_email' => $userEmail,
                'rating' => $rating,
                'comment' => $comment,
                'created_at' => date('Y-m-d H:i:s'), //format for display
            ],
        ];

    } catch (Exception $e) {
        // Handle database errors
        return [
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage(),
            'data' => null,
        ];
    }
}


// Example Usage (Simulated Database Connection)
// This example assumes you have a database connection object named $dbConnection
// In a real application, you would obtain this connection from your framework or connection library.

//Simulated database connection for testing
//$dbConnection = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");

// Example 1: Successful Review Creation
$reviewData = createReview(
    '123',
    'test@example.com',
    '4',
    'Great product!',
    $dbConnection // Pass the database connection
);

print_r($reviewData);

// Example 2: Error - Invalid Email Format
$reviewData = createReview(
    '456',
    'invalid-email',
    '3',
    'Bad review',
    $dbConnection
);

print_r($reviewData);
?>


<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product.
 * It includes basic input validation and error handling.
 *
 * @param string $product_id The ID of the product the review is for.
 * @param string $username The username of the reviewer.
 * @param string $rating  The rating given by the user (e.g., 1-5 stars).
 * @param string $comment The user's review comment.
 * @param string $db_connection A valid database connection object.
 *
 * @return bool True on success, false on failure.
 */
function storeUserReview(string $product_id, string $username, string $rating, string $comment, PDO $db_connection) {
  // Input Validation -  Crucial for security and data integrity
  if (empty($product_id) || empty($username) || empty($rating) || empty($comment)) {
    error_log("Error: Missing required fields for review."); // Log for debugging - prevent info display
    return false;
  }

  if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    error_log("Error: Invalid username - only alphanumeric characters and underscores allowed.");
    return false;
  }

  if ($rating < 1 || $rating > 5) {
    error_log("Error: Invalid rating - must be between 1 and 5.");
    return false;
  }
  
  // Sanitize inputs -  Important!  Prevent SQL injection
  $product_id = $db_connection->quote($product_id);
  $username = $db_connection->quote($username);
  $rating = (int)$rating;  //Cast to integer to ensure proper storage
  $comment = $db_connection->quote($comment);

  // SQL Query -  Use prepared statements to prevent SQL injection
  $sql = "INSERT INTO reviews (product_id, username, rating, comment) VALUES (:product_id, :username, :rating, :comment)";
  $stmt = $db_connection->prepare($sql);

  if ($stmt->execute([
    ':product_id' => $product_id,
    ':username' => $username,
    ':rating' => $rating,
    ':comment' => $comment
  ]) === false) {
    error_log("Error: Failed to insert review: " . print_r($stmt->errorInfo(), true)); //Detailed error logging
    return false;
  }

  return true; // Success
}



/**
 * Displays a list of user reviews for a product.
 *
 * @param PDO $db_connection A valid database connection object.
 * @param int $product_id The ID of the product to retrieve reviews for.
 */
function displayUserReviews(PDO $db_connection, int $product_id) {
  // Query to get reviews for a product
  $sql = "SELECT * FROM reviews WHERE product_id = :product_id";
  $stmt = $db_connection->prepare($sql);
  $stmt->bindParam(':product_id', $product_id);
  $stmt->execute();

  $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if (empty($reviews)) {
    echo "<p>No reviews yet.</p>";
  } else {
    echo "<h2>Reviews for Product ID: " . $product_id . "</h2>";
    echo "<table>";
    echo "<thead><tr><th>Username</th><th>Rating</th><th>Comment</th></tr></thead>";
    echo "<tbody>";
    foreach ($reviews as $review) {
      echo "<tr>";
      echo "<td>" . htmlspecialchars($review['username']) . "</td>";  // Escape output for security
      echo "<td>" . $review['rating'] . "</td>";
      echo "<td>" . htmlspecialchars($review['comment']) . "</td>";
      echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
  }
}

// Example Usage (Illustrative - Replace with your database setup)

// Create a dummy PDO connection (replace with your actual connection)
$db_connection = new PDO("mysql:host=localhost;dbname=your_database_name", "username", "password");

// Example Product ID
$product_id = 123;

// Simulate a user review submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $rating = $_POST["rating"];
  $comment = $_POST["comment"];

  if (storeUserReview($product_id, $username, $rating, $comment, $db_connection)) {
    echo "<p>Review submitted successfully!</p>";
  } else {
    echo "<p>Error submitting review.</p>";
  }
}

//Display reviews for the product
displayUserReviews($db_connection, $product_id);

?>


<?php

/**
 * Reviews Class - Handles user reviews.
 */
class Reviews {

  private $reviews = []; // Stores all reviews
  private $dbConnection = null; // Connection to the database (for persistence)

  /**
   * Constructor
   *
   * Establishes the database connection (if available).
   */
  public function __construct($dbConfig = null) {
    // Attempt to connect to the database (e.g., MySQL, PostgreSQL)
    // You'll need to adapt this part to your specific database setup.
    if ($dbConfig) {
      $this->dbConnection = new PDO(
        "mysql:host=" . $dbConfig['host'] . ";dbname=" . $dbConfig['dbname'] . ";charset=utf8",
        $dbConfig['user'],
        $dbConfig['password']
      );
    }
  }


  /**
   * Add a new review
   *
   * @param int $productId The ID of the product the review is for.
   * @param string $userName The name of the user who wrote the review.
   * @param string $reviewText The text of the review.
   * @param int $rating The rating (e.g., 1-5).
   *
   * @return bool True on success, false on failure.
   */
  public function addReview(int $productId, string $userName, string $reviewText, int $rating) {
    // Basic validation
    if (!$productId || !$userName || !$reviewText || $rating < 1 || $rating > 5) {
      return false;
    }

    $review = [
      'productId' => $productId,
      'userName' => $userName,
      'reviewText' => $reviewText,
      'rating' => $rating,
      'createdAt' => date('Y-m-d H:i:s') // Timestamp for creation
    ];

    $this->reviews[] = $review;

    // Optionally save to database here
    if ($this->dbConnection) {
      $this->saveReviewToDatabase($review);
    }
    return true;
  }

  /**
   * Get all reviews for a product
   *
   * @param int $productId The ID of the product.
   *
   * @return array An array of reviews, or an empty array if none found.
   */
  public function getReviewsByProduct(int $productId) {
    $productReviews = [];
    foreach ($this->reviews as $review) {
      if ($review['productId'] == $productId) {
        $productReviews[] = $review;
      }
    }
    return $productReviews;
  }

  /**
   * Save review to database (using PDO)
   */
  private function saveReviewToDatabase(array $review) {
    $sql = "INSERT INTO reviews (productId, userName, reviewText, rating, createdAt)
            VALUES (:productId, :userName, :reviewText, :rating, :createdAt)";

    $stmt = $this->dbConnection->prepare($sql);
    $stmt->bindParam(':productId', $review['productId']);
    $stmt->bindParam(':userName', $review['userName']);
    $stmt->bindParam(':reviewText', $review['reviewText']);
    $stmt->bindParam(':rating', $review['rating']);
    $stmt->bindParam(':createdAt', $review['createdAt']);

    try {
      $stmt->execute();
    } catch (PDOException $e) {
      // Handle database errors appropriately (logging, display error message)
      error_log("Database error saving review: " . $e->getMessage()); //Log for debugging
      // Optionally display an error message to the user (if appropriate)
    }
  }
}


// Example Usage:
// Assuming you have a database setup and a table named 'reviews'
// with columns: productId (int), userName (string), reviewText (text), rating (int), createdAt (datetime)

// Initialize Reviews object (replace with your database config)
$reviews = new Reviews([
  'host' => 'localhost',
  'dbname' => 'your_database_name',
  'user' => 'your_database_user',
  'password' => 'your_database_password'
]);

// Add a review
if ($reviews->addReview(123, "John Doe", "Great product!", 5)) {
  echo "Review added successfully!";
} else {
  echo "Failed to add review.";
}

// Get reviews for product 123
$reviewsForProduct123 = $reviews->getReviewsByProduct(123);

// Display the reviews
echo "<pre>";
print_r($reviewsForProduct123);
echo "</pre>";


?>


<?php

/**
 * User Review Function - Basic Implementation
 *
 * This function takes a user's review text, a rating (1-5), and an optional user ID.
 * It stores the review in a simple array (for demonstration purposes).
 *
 * In a real application, you'd integrate this with a database.
 *
 * @param string $reviewText The text of the user's review.
 * @param int $rating The rating given by the user (1-5).
 * @param string|null $userId The ID of the user submitting the review.
 *
 * @return array An array containing the review details.
 */
function createReview(string $reviewText, int $rating, ?string $userId = null): array
{
    // Basic validation - important for security and data integrity.
    if (empty($reviewText)) {
        throw new InvalidArgumentException("Review text cannot be empty.");
    }

    if ($rating < 1 || $rating > 5) {
        throw new InvalidArgumentException("Rating must be between 1 and 5.");
    }

    $review = [
        'text' => $reviewText,
        'rating' => $rating,
        'userId' => $userId, // Store user ID for later use
        'date' => date('Y-m-d H:i:s'), // Add a timestamp for review creation
    ];

    return $review;
}

// --- Example Usage ---

try {
    $review1 = createReview("This product is amazing! I highly recommend it.", 5, "user123");
    echo "Review 1:
";
    print_r($review1);
    echo "

";

    $review2 = createReview("It was okay, nothing special.", 3);
    echo "Review 2:
";
    print_r($review2);
    echo "

";

    // Example of error handling - Invalid rating
    try {
        $invalidReview = createReview("Bad review", 6);
    } catch (InvalidArgumentException $e) {
        echo "Error creating review: " . $e->getMessage() . "
";
    }

} catch (InvalidArgumentException $e) {
    // Catch any errors that might be thrown by createReview()
    echo "An error occurred: " . $e->getMessage() . "
";
}
?>


<?php
// Example of sanitization (important for security)
$sanitizedReviewText = htmlspecialchars($reviewText, ENT_QUOTES, 'UTF-8');

// ... (Database query using prepared statements to prevent SQL injection)
?>


<?php

/**
 * User Review Class
 *
 * This class allows you to store and display user reviews for a product or item.
 */
class UserReview {

    private $reviewId;
    private $userId;
    private $rating;
    private $comment;
    private $date;

    /**
     * Constructor
     *
     * Initializes a new UserReview object.
     *
     * @param int $reviewId  (Optional)  The unique ID for the review.  If not provided, auto-generated.
     * @param int $userId   The ID of the user who wrote the review.
     * @param int $rating   The rating given by the user (e.g., 1-5).
     * @param string $comment The user's comment about the product/item.
     */
    public function __construct($reviewId = null, $userId, $rating, $comment) {
        $this->reviewId = $reviewId;
        $this->userId = $userId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->date = date('Y-m-d H:i:s'); // Set current timestamp for date
    }

    /**
     * Getters for each property.
     *
     * @return mixed  The value of the property.
     */
    public function getReviewId() {
        return $this->reviewId;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getRating() {
        return $this->rating;
    }

    public function getComment() {
        return $this->comment;
    }

    public function getDate() {
        return $this->date;
    }

    /**
     * Display the review in a formatted string.
     *
     * @return string  A string containing the review details.
     */
    public function displayReview() {
        return "Review ID: " . $this->getReviewId() . "
" .
               "User ID: " . $this->getUserId() . "
" .
               "Rating: " . $this->getRating() . "
" .
               "Comment: " . $this->getComment() . "
" .
               "Date: " . $this->getDate();
    }
}

/**
 * User Review Function (Example Usage)
 */
function processUserReview($userId, $rating, $comment) {
    // Create a new UserReview object
    $review = new UserReview($userId, $userId, $rating, $comment);

    // Display the review
    echo $review->displayReview() . "
";

    // Optionally, you could save this review to a database here...
    // This is just a demonstration.
}


// Example Usage:
processUserReview(123, 5, "Excellent product! Highly recommended.");
processUserReview(456, 3, "It's okay, but could be better.");
?>


<?php

/**
 * User Review Function
 *
 * This function handles the creation and display of user reviews.
 *
 * @param string $productId The ID of the product being reviewed.
 * @param string $userName The name of the user writing the review.
 * @param string $reviewText The text of the review.
 * @param int $rating  The rating given by the user (1-5).
 *
 * @return array An array containing success/failure status and optionally the review ID.
 */
function createReview(string $productId, string $userName, string $reviewText, int $rating): array
{
    // Sanitize inputs - Important for security!
    $productId = filter_var($productId, FILTER_SANITIZE_STRING);
    $userName = filter_var($userName, FILTER_SANITIZE_STRING);
    $reviewText = filter_var($reviewText, FILTER_SANITIZE_STRING);
    $rating = intval($rating);  // Ensure rating is an integer

    // Validate rating
    if ($rating < 1 || $rating > 5) {
        return ['success' => false, 'message' => 'Invalid rating. Rating must be between 1 and 5.'];
    }

    // **In a real application, you would connect to a database here.**
    // This is a placeholder for demonstration.

    // Simulate storing the review (replace with your database logic)
    $reviewId = generateUniqueId(); // Use a function to generate a unique ID
    $reviewData = [
        'product_id' => $productId,
        'user_name' => $userName,
        'review_text' => $reviewText,
        'rating' => $rating,
        'created_at' => date('Y-m-d H:i:s') // Add timestamp for tracking
    ];


    // Store the review in a data structure (replace with database insertion)
    // Example: $reviews[$productId][] = $reviewData;


    return ['success' => true, 'review_id' => $reviewId];
}

/**
 * Generate a unique ID for a review
 *  (Replace with a more robust solution like UUID)
 * @return string
 */
function generateUniqueId(): string
{
    return uniqid();
}



// -------------------- Example Usage --------------------

// 1. Create a review
$productId = 'product123';
$userName = 'John Doe';
$reviewText = 'This is a great product! I highly recommend it.';
$rating = 5;

$result = createReview($productId, $userName, $reviewText, $rating);

if ($result['success']) {
    echo "Review created successfully! Review ID: " . $result['review_id'] . "
";
} else {
    echo "Error creating review: " . $result['message'] . "
";
}


// 2. Example with invalid rating
$result = createReview($productId, $userName, $reviewText, 0);
if ($result['success']) {
   echo "Review created successfully! Review ID: " . $result['review_id'] . "
";
} else {
   echo "Error creating review: " . $result['message'] . "
";
}


?>


<?php

/**
 * This function creates a user review object with basic information.
 *
 * @param string $username  The name of the user submitting the review.
 * @param string $rating    The rating given by the user (e.g., 1-5).
 * @param string $comment   The user's written comment about the item/service.
 *
 * @return array An array containing the user review data.
 */
function createReview(string $username, string $rating, string $comment): array
{
  // Validate input (add more validation as needed for your application)
  if (empty($username)) {
    throw new InvalidArgumentException("Username cannot be empty.");
  }

  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    throw new InvalidArgumentException("Rating must be a number between 1 and 5.");
  }

  if (empty($comment)) {
    throw new InvalidArgumentException("Comment cannot be empty.");
  }


  return [
    'username' => $username,
    'rating' => $rating,
    'comment' => $comment,
    'timestamp' => date('Y-m-d H:i:s'), // Add timestamp for tracking
  ];
}


// Example Usage:
try {
  $review1 = createReview("JohnDoe", 4, "Great product!  Works exactly as advertised.");
  echo "Review 1: " . print_r($review1, true) . PHP_EOL;

  $review2 = createReview("JaneSmith", 5, "Excellent service, highly recommend!");
  echo "Review 2: " . print_r($review2, true) . PHP_EOL;

  // Example of an invalid review (to demonstrate validation)
  // $invalidReview = createReview("", 3, "This is a comment.");  // Throws an exception

} catch (InvalidArgumentException $e) {
  echo "Error creating review: " . $e->getMessage() . PHP_EOL;
}

?>


<?php

/**
 * Reviews class to handle user reviews for a product or service.
 */
class Review {

    private $productId;
    private $userId;
    private $rating;
    private $comment;
    private $date;
    private $reviewId;  // Unique identifier for the review.

    /**
     * Constructor
     * 
     * @param int $productId The ID of the product or service being reviewed.
     * @param int $userId The ID of the user submitting the review.
     * @param int $rating The rating given by the user (e.g., 1-5).
     * @param string $comment The user's comment/feedback.
     */
    public function __construct(int $productId, int $userId, int $rating, string $comment) {
        $this->productId = $productId;
        $this->userId = $userId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->date = date('Y-m-d H:i:s'); // Timestamp of the review.
        $this->reviewId = uniqid(); // Generate a unique ID for the review.
    }

    /**
     * Getters for each review attribute.
     * 
     * @return mixed
     */
    public function getProductId() {
        return $this->productId;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getRating() {
        return $this->rating;
    }

    public function getComment() {
        return $this->comment;
    }

    public function getDate() {
        return $this->date;
    }

    public function getReviewId() {
        return $this->reviewId;
    }

    /**
     *  Display the review in a user-friendly format.
     *
     * @return string
     */
    public function displayReview() {
        return "Review ID: " . $this->reviewId . "
" .
               "Product ID: " . $this->productId . "
" .
               "User ID: " . $this->userId . "
" .
               "Rating: " . $this->rating . "
" .
               "Comment: " . $this->comment . "
" .
               "Date: " . $this->date;
    }
}

/**
 * Function to process user reviews (example usage)
 */
function processReview(Review $review) {
    echo "New Review Received:
";
    echo $review->displayReview() . "
";

    // In a real application, you'd likely save this review to a database.
    //  This is a placeholder for that functionality.
    echo "Review processed (placeholder - saving to database would happen here).
";
}


// --- Example Usage ---

// Create some reviews
$review1 = new Review(123, 45, 5, "Excellent product!  Highly recommended.");
$review2 = new Review(456, 78, 4, "Good value for the money.");
$review3 = new Review(123, 90, 3, "It's okay, but could be better.");

// Process the reviews
processReview($review1);
processReview($review2);
processReview($review3);

?>


<?php

/**
 * User Review Function
 *
 * This function takes a user ID, a review text, and an optional rating,
 * and saves the review to a database.
 *
 * @param int $userId The ID of the user submitting the review.
 * @param string $reviewText The text of the review.
 * @param int $rating (Optional) The rating given for the review (e.g., 1-5).
 *                    Defaults to 0 if not provided.
 * @return bool True if the review was saved successfully, false otherwise.
 */
function saveReview(int $userId, string $reviewText, int $rating = 0) {
  // **Important:  Replace this with your actual database connection code.**
  // This is a placeholder for demonstration.
  $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');

  // Sanitize and validate inputs to prevent SQL injection and data issues.
  $reviewText = trim($reviewText); // Remove leading/trailing whitespace.
  if (empty($reviewText)) {
    return false; // Don't save empty reviews.
  }
  $rating = intval($rating); // Ensure rating is an integer.  Handles cases where $rating is a string.
  if ($rating < 1 || $rating > 5) {
    $rating = 0; // Default to 0 if rating is outside the valid range.
  }


  try {
    $stmt = $db->prepare("INSERT INTO reviews (user_id, review_text, rating) VALUES (?, ?, ?)");
    $stmt->execute([$userId, $reviewText, $rating]);
    return true;
  } catch (PDOException $e) {
    // Handle database errors gracefully.  Log the error for debugging.
    error_log("Error saving review: " . $e->getMessage());
    return false;
  }
}


// Example Usage:
//  This is just example data - replace with your actual application logic.

$userId = 123;
$review = "This product is fantastic!  Highly recommended.";
$rating = 5;

if (saveReview($userId, $review, $rating)) {
  echo "Review saved successfully!
";
} else {
  echo "Error saving review.
";
}

// Example with no rating
$userId = 456;
$review = "Okay product.";
if (saveReview($userId, $review)) {
  echo "Review saved without rating.
";
} else {
    echo "Error saving review without rating.
";
}

?>


<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product or service.
 *
 * @param string $productName The name of the product or service being reviewed.
 * @param string $reviewText The text of the review submitted by the user.
 * @param string $username The username of the user submitting the review.
 * @param string $rating (optional) A rating from 1 to 5 (default 5).
 *
 * @return array An array containing:
 *   - 'success': true if the review was successfully submitted, false otherwise.
 *   - 'message': A message to display to the user (e.g., success or error message).
 *   - 'review': The submitted review data (product, username, rating, review text).
 */
function submitReview(string $productName, string $reviewText, string $username, string $rating = '5'): array
{
  // Basic input validation - This is CRUCIAL in a real application
  if (empty($productName)) {
    return ['success' => false, 'message' => 'Product name cannot be empty.', 'review' => []];
  }
  if (empty($reviewText)) {
    return ['success' => false, 'message' => 'Review text cannot be empty.', 'review' => []];
  }
  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    return ['success' => false, 'message' => 'Rating must be a number between 1 and 5.', 'review' => []];
  }

  //  In a real application, you would:
  //  1. Save the review to a database.
  //  2. Generate a unique ID for the review.
  //  3.  Store the ID, product name, username, rating, and review text in the database.

  // Simulate database saving for demonstration purposes
  $reviewId = generateUniqueId(); // Replace with your actual ID generation function
  $reviewData = [
    'product_name' => $productName,
    'username' => $username,
    'rating' => $rating,
    'review_text' => $reviewText,
    'id' => $reviewId
  ];

  return ['success' => true, 'message' => 'Review submitted successfully!', 'review' => $reviewData];
}


/**
 * Generates a unique ID (Placeholder - Replace with a robust ID generation mechanism)
 *
 * This is a very simple example.  In a production environment, use something like UUIDs.
 */
function generateUniqueId(): string
{
    return uniqid(); // Returns a unique ID based on timestamp and random data
}

// ------------------- Example Usage -------------------
// Example 1: Successful submission
$reviewResult = submitReview('Awesome Widget', 'This widget is amazing!', 'JohnDoe', '4');
print_r($reviewResult);

// Example 2: Invalid rating
$reviewResult = submitReview('Another Product', 'Good product.', 'JaneSmith', '6');
print_r($reviewResult);

// Example 3: Empty review text
$reviewResult = submitReview('Something', '', 'PeterPan');
print_r($reviewResult);
?>


<?php

/**
 * User Review Function
 * 
 * This function allows you to store and display user reviews for a given item.
 * It includes basic input validation and error handling.
 * 
 * @param string $item_id The unique identifier for the item being reviewed.
 * @param string $username The username of the reviewer.
 * @param string $rating  The rating given by the user (e.g., 1-5).
 * @param string $comment  The user's review comment.
 * @param array $db_connection A database connection object (e.g., mysqli).
 * 
 * @return bool True if the review was successfully added, false otherwise.
 */
function add_user_review(string $item_id, string $username, string $rating, string $comment, array $db_connection) {
  // Input Validation - Basic example, expand as needed
  if (empty($item_id) || empty($username) || empty($rating) || empty($comment)) {
    error_log("Missing required fields in user review."); // Log for debugging
    return false;
  }

  if (!preg_match('/^[0-5][0-9]*$/', $rating)) {
    error_log("Invalid rating format. Rating must be between 1 and 5.");
    return false;
  }

  // Sanitize Input - Important for security
  $item_id = filter_var($item_id, FILTER_SANITIZE_STRING);
  $username = filter_var($username, FILTER_SANITIZE_STRING);
  $rating = (int) filter_var($rating, FILTER_SANITIZE_NUMBER_INT);  // Convert to integer
  $comment = filter_var($comment, FILTER_SANITIZE_STRING);

  // Prepare the SQL query - Use prepared statements to prevent SQL injection
  $sql = "INSERT INTO reviews (item_id, username, rating, comment) 
          VALUES (?, ?, ?, ?)";

  $stmt = $db_connection->prepare($sql);

  if ($stmt === false) {
    error_log("Error preparing SQL statement: " . $db_connection->error); // Log the error
    return false;
  }

  // Bind parameters
  $stmt->bind_param("ssii", $item_id, $username, $rating, $comment);

  // Execute the query
  if (!$stmt->execute()) {
    error_log("Error executing SQL query: " . $stmt->error); // Log the error
    $stmt->close();
    return false;
  }

  // Close the statement
  $stmt->close();

  return true;
}

/**
 *  Example function to display reviews for a given item ID
 *  This is just a conceptual example, you'll need to adapt it to your specific database schema and presentation layer.
 *
 * @param string $item_id The ID of the item to retrieve reviews for
 * @param array $db_connection  Your database connection object
 * @return array An array of review objects, or an empty array if no reviews are found.
 */
function get_reviews_for_item(string $item_id, array $db_connection) {
    $sql = "SELECT * FROM reviews WHERE item_id = ?";
    $stmt = $db_connection->prepare($sql);

    if ($stmt === false) {
        error_log("Error preparing SQL statement: " . $db_connection->error);
        return [];
    }

    $stmt->bind_param("s", $item_id);

    $stmt->execute();

    if ($stmt === false) {
        error_log("Error executing SQL query: " . $stmt->error);
        return [];
    }

    $result = $stmt->get_result();

    $reviews = [];
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }

    $stmt->close();
    return $reviews;
}


// Example Usage (Illustrative - requires a database connection setup)
//  Remember to replace with your actual database connection details!

// Sample database connection (Replace with your actual connection)
//$db_connection = new mysqli("localhost", "your_username", "your_password", "your_database");

//if ($db_connection->connect_error) {
//  die("Connection failed: " . $db_connection->connect_error);
//}


// Add a review
//$item_id = "product123";
//$username = "john.doe";
//$rating = 3;
//$comment = "Great product, would recommend!";

//if (add_user_review($item_id, $username, $rating, $comment, $db_connection)) {
//    echo "Review added successfully!
";
//} else {
//    echo "Failed to add review.
";
//}

// Get and display reviews for the item
//  Assuming a table named 'reviews' with columns: item_id, username, rating, comment
//  and that the item_id is 'product123'
//  This is just conceptual - you'll need to adapt the output to display in your interface.
//  $reviews = get_reviews_for_item("product123", $db_connection);

//if (!empty($reviews)) {
//    echo "<h2>Reviews for product123:</h2>
";
//    foreach ($reviews as $review) {
//        echo "<div>
";
//        echo "  <b>Username:</b> " . $review['username'] . "<br>
";
//        echo "  <b>Rating:</b> " . $review['rating'] . "<br>
";
//        echo "  <b>Comment:</b> " . $review['comment'] . "
";
//        echo "  </div>
";
//    }
//} else {
//    echo "No reviews found for this item.
";
//}

// Close the database connection
//$db_connection->close();

?>


<?php

/**
 * User Review Function
 *
 * This function allows you to create, read, update, and delete user reviews.
 * It includes basic validation and demonstrates how to interact with a 
 * hypothetical database.  You'll need to adapt the database interaction part
 * to your specific database system (MySQL, PostgreSQL, etc.).
 */

class Review
{
    private $db; // Database connection

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Create a new review.
     *
     * @param int $productId The ID of the product the review is for.
     * @param string $user  The name of the user submitting the review.
     * @param string $comment The review text.
     * @return int|false  The ID of the new review if successful, false otherwise.
     */
    public function createReview(int $productId, string $user, string $comment)
    {
        // Input Validation - Basic
        if (!$productId || !$user || !$comment) {
            return false;
        }

        // Sanitize inputs (important for security - prevents SQL injection)
        $productId = $this->db->real_escape_string($productId);
        $user = $this->db->real_escape_string($user);
        $comment = $this->db->real_escape_string($comment);

        // SQL Query (Adapt to your database system)
        $query = "INSERT INTO reviews (product_id, user, comment) VALUES ('$productId', '$user', '$comment')";

        if ($this->db->query($query) === TRUE) {
            return $this->db->insert_id; // Returns the ID of the newly inserted row
        } else {
            echo "Error: " . $this->db->error . "<br>"; // Handle database errors
            return false;
        }
    }

    /**
     * Get a review by ID.
     *
     * @param int $reviewId The ID of the review to retrieve.
     * @return array|false  An array containing review data if found, false otherwise.
     */
    public function getReview(int $reviewId)
    {
        $reviewId = $this->db->real_escape_string($reviewId);

        $query = "SELECT * FROM reviews WHERE id = '$reviewId'";
        $result = $this->db->query($query);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Update a review.
     *
     * @param int $reviewId The ID of the review to update.
     * @param string $newComment The new review text.
     * @return bool True on success, false on failure.
     */
    public function updateReview(int $reviewId, string $newComment)
    {
        $reviewId = $this->db->real_escape_string($reviewId);
        $newComment = $this->db->real_escape_string($newComment);

        $query = "UPDATE reviews SET comment = '$newComment' WHERE id = '$reviewId'";

        if ($this->db->query($query) === TRUE) {
            return true;
        } else {
            echo "Error: " . $this->db->error . "<br>";
            return false;
        }
    }

    /**
     * Delete a review.
     *
     * @param int $reviewId The ID of the review to delete.
     * @return bool True on success, false on failure.
     */
    public function deleteReview(int $reviewId)
    {
        $reviewId = $this->db->real_escape_string($reviewId);

        $query = "DELETE FROM reviews WHERE id = '$reviewId'";

        if ($this->db->query($query) === TRUE) {
            return true;
        } else {
            echo "Error: " . $this->db->error . "<br>";
            return false;
        }
    }
}

// Example Usage (This needs a database connection)
// Replace with your database connection details
$db = new mysqli("localhost", "username", "password", "database_name");

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$reviewManager = new Review($db);

// Create a new review
$newReviewId = $reviewManager->createReview(1, "John Doe", "This is a great product!");

if ($newReviewId) {
    echo "New review created with ID: " . $newReviewId . "<br>";
}

// Get the review
$review = $reviewManager->getReview($newReviewId);
if ($review) {
    echo "Review: " . $review['comment'] . "<br>";
}

// Update the review
$reviewManager->updateReview($newReviewId, "Updated Review Comment");

// Delete the review
$reviewManager->deleteReview($newReviewId);

$db->close(); // Always close the database connection

?>


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


<?php

/**
 * User Review Function
 * 
 * This function allows you to store and display user reviews for a given item.
 *
 * @param string $itemId The unique identifier of the item being reviewed.
 * @param string $userName The name of the user submitting the review.
 * @param string $reviewText The text of the review.
 * @param int $rating The rating (e.g., 1-5).
 * @param array $dbConnection  A database connection object.
 * @return array  An array containing:
 *               - 'success' => true if the review was saved successfully, false otherwise.
 *               - 'message' => A message describing the result (e.g., "Review saved!", "Error saving review").
 */
function saveUserReview(string $itemId, string $userName, string $reviewText, int $rating, PDO $dbConnection) {
    // Input validation - Important!  Don't allow arbitrary input!
    if (empty($itemId) || empty($userName) || empty($reviewText) || $rating < 1 || $rating > 5) {
        return ['success' => false, 'message' => 'Invalid input.  Please ensure all fields are filled and the rating is between 1 and 5.'];
    }

    try {
        // Prepare the SQL statement - use prepared statements to prevent SQL injection
        $sql = "INSERT INTO reviews (item_id, user_name, review_text, rating)
                VALUES (:item_id, :user_name, :review_text, :rating)";

        $stmt = $dbConnection->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(':item_id', $itemId);
        $stmt->bindParam(':user_name', $userName);
        $stmt->bindParam(':review_text', $reviewText);
        $stmt->bindParam(':rating', $rating);

        // Execute the statement
        $stmt->execute();

        return ['success' => true, 'message' => 'Review saved!'];

    } catch (PDOException $e) {
        // Handle database errors - Log the error!  Don't expose the raw error.
        error_log("Database error saving review: " . $e->getMessage()); // Log for debugging
        return ['success' => false, 'message' => 'Error saving review. Please try again later.'];
    }
}

/**
 * Retrieve Reviews for an Item
 *
 * This function retrieves all reviews for a given item from the database.
 *
 * @param string $itemId The unique identifier of the item.
 * @param PDO $dbConnection  A database connection object.
 * @return array An array containing:
 *               - 'reviews' => An array of review objects, or an empty array if no reviews exist.
 *               - 'success' => true if reviews were retrieved successfully, false otherwise.
 */
function getReviewsForItem(string $itemId, PDO $dbConnection) {
    try {
        $sql = "SELECT * FROM reviews WHERE item_id = :item_id";
        $stmt = $dbConnection->prepare($sql);
        $stmt->bindParam(':item_id', $itemId);
        $stmt->execute();

        $reviews = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $reviews[] = $row;
        }

        return ['reviews' => $reviews, 'success' => true];

    } catch (PDOException $e) {
        error_log("Database error retrieving reviews: " . $e->getMessage());
        return ['reviews' => [], 'success' => false, 'message' => 'Error retrieving reviews. Please try again later.'];
    }
}



// Example Usage (Illustrative - Replace with your actual database setup)

// Create a dummy database connection (for demonstration)
$db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');


// Save a review
$reviewResult = saveUserReview('product123', 'John Doe', 'Great product!', 5, $db);
print_r($reviewResult);
// Example: Retrieve Reviews for 'product123'
$reviews = getReviewsForItem('product123', $db);
print_r($reviews);


// Close the database connection (important!)
$db = null; // Disconnect the PDO object

?>


<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or item.
 * It includes basic validation and output formatting.
 *
 * @param string $productId The unique identifier of the product/item.
 * @param string $username The username of the reviewing user.
 * @param string $rating  The rating given by the user (e.g., 1-5).  Must be numeric.
 * @param string $comment  The user's review comment.
 *
 * @return bool True on successful saving, False on failure (e.g., invalid input).
 */
function saveUserReview(string $productId, string $username, string $rating, string $comment) {
    // --- Input Validation ---
    if (empty($productId)) {
        error_log("Error: Product ID cannot be empty.");  // Log for debugging
        return false;
    }

    if (empty($username)) {
        error_log("Error: Username cannot be empty.");
        return false;
    }

    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        error_log("Error: Rating must be a number between 1 and 5.");
        return false;
    }

    if (empty($comment)) {
        error_log("Error: Comment cannot be empty.");
        return false;
    }

    // --- Data Sanitization (Important for security) ---
    $productId = filter_var($productId, FILTER_SANITIZE_STRING);  // Escape HTML and other characters
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $rating = (int)$rating; // Cast to integer for safe storage
    $comment = filter_var($comment, FILTER_SANITIZE_STRING);

    // --- Data Storage (Example - Using a simple array.  In a real app, use a database.) ---
    $reviews = loadReviews($productId);  // Assuming a function to load reviews
    if ($reviews) {
        $review = [
            'productId' => $productId,
            'username' => $username,
            'rating' => $rating,
            'comment' => $comment,
            'timestamp' => time() // Add a timestamp for ordering/management
        ];
        $reviews[] = $review;
    } else {
        $reviews = [$review];
    }


    // --- Save the review ---
    saveReviews($reviews); // Assuming a function to save reviews to storage.

    return true;
}


/**
 * Placeholder Functions (Replace with your actual implementation)
 */
function loadReviews(string $productId) {
    // Replace this with your logic to retrieve reviews for the product.
    // This is just a dummy implementation.
    // Ideally, you'd fetch this from a database.
    return []; // Return an empty array
}

function saveReviews(array $reviews) {
    // Replace this with your logic to save the reviews to your storage.
    // This is just a dummy implementation.
    // In a real application, you would likely save this data to a database.
    echo "Saving reviews: 
";
    print_r($reviews);
}


// --- Example Usage ---
$productID = "prod123";
$userName = "JohnDoe";
$rating = 4;
$reviewText = "Great product, would recommend!";

if (saveUserReview($productID, $userName, $rating, $reviewText)) {
    echo "Review submitted successfully!
";
} else {
    echo "Error submitting review.
";
}

// Example with invalid rating:
$invalidRating = 6;
$result = saveUserReview($productID, $userName, $invalidRating, $reviewText);
if(!$result){
    echo "Invalid rating test passed
";
}
?>


<?php

/**
 * User Review Function - Allows users to submit and display reviews.
 *
 * This function provides a basic interface for submitting and viewing user reviews.
 * It includes input validation and simple display formatting.
 *
 * @param array $reviews An array to store user reviews.  Defaults to an empty array.
 * @param string $username The username of the user submitting the review.
 * @param string $reviewText The text of the review.
 * @return array The updated array of reviews.
 */
function submitUserReview(array &$reviews, string $username, string $reviewText) {

  // Input validation - very basic for demonstration.  Enhance this for production.
  if (empty($reviewText)) {
    echo "Error: Review text cannot be empty.
";
    return $reviews;
  }

  // Format the review
  $formattedReview = "User: $username - Review: $reviewText";

  // Add the review to the array
  $reviews[] = $formattedReview;

  return $reviews;
}


/**
 * Displays all user reviews.
 *
 * This function iterates through the review array and prints each review.
 *
 * @param array $reviews An array of user reviews.
 */
function displayReviews(array $reviews) {
  echo "<h2>User Reviews:</h2>
";
  if (empty($reviews)) {
    echo "<p>No reviews submitted yet.</p>
";
    return;
  }
  echo "<ul>
";
  foreach ($reviews as $review) {
    echo "  <li>$review</li>
";
  }
  echo "</ul>
";
}


// Example Usage:
$review_data = []; // Initialize an empty array to store reviews

// Submit some reviews
submitUserReview($review_data, "Alice", "This product is amazing!");
submitUserReview($review_data, "Bob", "I'm very happy with my purchase.");
submitUserReview($review_data, "Charlie", "It's okay, could be better.");
submitUserReview($review_data, "David", "Excellent value for money!");


// Display the reviews
displayReviews($review_data);


//Demonstration of input validation
submitUserReview($review_data, "Eve", ""); // Attempt to submit an empty review
displayReviews($review_data); // Display the reviews after the invalid attempt
?>


<?php

/**
 * User Review Class
 */
class UserReview {

    private $reviewId;
    private $userId;
    private $productId;
    private $rating;
    private $comment;
    private $date;

    /**
     * Constructor
     *
     * @param int $reviewId (optional, auto-incrementing ID)
     * @param int $userId
     * @param int $productId
     * @param int $rating (1-5)
     * @param string $comment
     */
    public function __construct($reviewId = null, $userId, $productId, $rating, $comment) {
        $this->reviewId = $reviewId;
        $this->userId = $userId;
        $this->productId = $productId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->date = date('Y-m-d H:i:s'); // Set current timestamp
    }

    /**
     * Getters
     */
    public function getReviewId() {
        return $this->reviewId;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getProductId() {
        return $this->productId;
    }

    public function getRating() {
        return $this->rating;
    }

    public function getComment() {
        return $this->comment;
    }

    public function getDate() {
        return $this->date;
    }


    /**
     * Setters (Use with caution - validate data)
     */
    public function setRating($rating) {
        if ($rating >= 1 && $rating <= 5) {
            $this->rating = $rating;
        } else {
            throw new Exception("Rating must be between 1 and 5.");
        }
    }

    // Add setters for other properties as needed.  Validate data!
}


/**
 * User Review Review Function
 */
function reviewUserReview($review) {
  // This function is designed to provide a simple review of a UserReview object.
  // You can extend this to include more sophisticated validation and output formatting.

  // Validate the review object
  if (!is_object($review) || !($review instanceof UserReview)) {
    throw new Exception("Invalid review object. Must be an instance of UserReview.");
  }

  $userId = $review->getUserId();
  $rating = $review->getRating();
  $comment = $review->getComment();
  $productId = $review->getProductId();

  // Basic validation - improve this!
  if (empty($comment)) {
    throw new Exception("Comment cannot be empty.");
  }

  // Construct a review string
  $reviewString = "Review ID: " . ($review->getReviewId() ? $review->getReviewId() : "N/A") . "
" .
                  "User ID: " . $userId . "
" .
                  "Product ID: " . $productId . "
" .
                  "Rating: " . $rating . "
" .
                  "Comment: " . $comment . "
" .
                  "Date: " . $review->getDate();


  return $reviewString;
}



// --- Example Usage ---

// Create a review object
$review = new UserReview(null, 123, 456, 5, "This product is amazing!");

try {
    // Review the review
    $reviewString = reviewUserReview($review);
    echo $reviewString . PHP_EOL; // Output the review
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}


// Example of invalid review (empty comment)
$review2 = new UserReview(null, 456, 789, 3, "");

try {
    $reviewString = reviewUserReview($review2);
    echo $reviewString . PHP_EOL;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL; // Output: Error: Comment cannot be empty.
}


?>


<?php

/**
 * User Review Function
 *
 * This function handles the creation, retrieval, updating, and deletion of user reviews.
 *
 * @param string $productId The ID of the product the review is for.
 * @param string $userId The ID of the user submitting the review.
 * @param string $rating  The rating given by the user (e.g., 1-5).
 * @param string $comment The user's review comment.
 * @param int $id Optional: The ID of the review to update. If not provided, a new review is created.
 *
 * @return array Returns an array with status and details.
 *               - 'status' => 'success' or 'error'
 *               - 'message' =>  A descriptive message.
 *               - 'review' => (if success) an associative array containing the review data (id, productId, userId, rating, comment, creationDate)
 *
 */
function createOrUpdateReview(string $productId, string $userId, string $rating, string $comment, ?int $id = null): array {
  // Validate inputs - Basic examples, extend as needed for your application
  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    return ['status' => 'error', 'message' => 'Invalid rating. Rating must be a number between 1 and 5.'];
  }
  if (empty($comment)) {
    return ['status' => 'error', 'message' => 'Review comment cannot be empty.'];
  }
  // Ideally, you would also validate $userId - check against user database
  // For this example, we assume it's valid.

  // Create a new review
  if (is_null($id)) {
    $review = [
      'productId' => $productId,
      'userId' => $userId,
      'rating' => $rating,
      'comment' => $comment,
      'creationDate' => date('Y-m-d H:i:s')  // Use appropriate timestamp format
    ];
  } else {
    // Update an existing review
    // In a real application, you'd query a database here to update the review
    // This is a placeholder for demonstration.
    $review = [
      'id' => $id,
      'productId' => $productId,
      'userId' => $userId,
      'rating' => $rating,
      'comment' => $comment,
      'creationDate' => date('Y-m-d H:i:s')
    ];
  }


  // In a real application, you'd save the $review data to a database here.
  // For this example, we're just returning the review data.

  return ['status' => 'success', 'message' => 'Review created/updated successfully.', 'review' => $review];
}


// Example Usage
$productId = '123';
$userId = 'user123';
$rating = 4;
$comment = 'Great product!  I highly recommend it.';

$result = createOrUpdateReview($productId, $userId, $rating, $comment);

if ($result['status'] === 'success') {
  echo "Review created/updated successfully: " . PHP_EOL;
  print_r($result['review']);
} else {
  echo "Error creating/updating review: " . PHP_EOL;
  echo $result['message'] . PHP_EOL;
}

// Example of an error
$result = createOrUpdateReview($productId, $userId, 6, 'Bad Rating'); // Invalid Rating
?>


<?php

/**
 * User Review Function
 * 
 * This function allows you to process user-submitted reviews.
 * It includes input validation, sanitization, and storage logic.
 * 
 * @param string $name The user's name.
 * @param string $comment The user's review comment.
 * @param string $rating The user's rating (e.g., 1-5).
 * @param string $item_id (Optional) The ID of the item the review is for.
 * @return bool True if the review was successfully saved, false otherwise.
 */
function saveUserReview(string $name, string $comment, string $rating, string $item_id = "") {
    // 1. Input Validation and Sanitization
    $name = sanitizeString($name); // Use a function to sanitize
    $comment = sanitizeString($comment);
    $rating = intval($rating); // Convert rating to an integer

    if (empty($name) || empty($comment) || $rating <= 0 || $rating > 5) {
        error_log("Invalid review data: " . print_r($_POST, true)); // Log the entire POST data for debugging
        return false;
    }

    // 2. Data Preparation
    $review_data = [
        'name' => $name,
        'comment' => $comment,
        'rating' => $rating,
        'item_id' => $item_id,
        'timestamp' => date("Y-m-d H:i:s") // Add a timestamp for record keeping
    ];

    // 3. Storage (Example: Using a simple file)
    $filename = "reviews.txt";
    $review_string = json_encode($review_data) . "
";

    if (file_put_contents($filename, $review_string, FILE_APPEND) !== false) {
        return true;
    } else {
        error_log("Failed to save review to file: " . $filename);
        return false;
    }

    // **Alternative:  Storing in a database (Recommended for production)**
    //  You would typically connect to your database (MySQL, PostgreSQL, etc.)
    //  and use prepared statements to prevent SQL injection vulnerabilities.
    //  Example (Conceptual - Requires database connection setup):
    //
    //  $db = new DatabaseConnection(); // Assuming you have a DatabaseConnection class
    //  $stmt = $db->prepare("INSERT INTO reviews (name, comment, rating, item_id, timestamp) VALUES (?, ?, ?, ?, ?)");
    //  $stmt->bind_param("sss", $name, $comment, $rating, $item_id);
    //  $stmt->execute();
    //  $stmt->close();
    //  return true;
}



/**
 * Sanitize String Function
 * 
 * Simple function to sanitize a string.
 *  (More robust sanitization is often needed in real-world scenarios)
 *
 * @param string $string The string to sanitize.
 * @return string The sanitized string.
 */
function sanitizeString(string $string): string {
    //Remove HTML tags
    $string = strip_tags($string);
    // Remove unwanted characters (example - customize this as needed)
    $string = str_replace([';', ':', '-', '_'], '', $string);
    return trim($string); //Trim leading/trailing whitespace
}

// Example Usage:
// Simulate receiving the review data from a form submission.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $rating = $_POST["rating"];
    $item_id = $_POST["item_id"]; //Optional
    
    if (saveUserReview($name, $comment, $rating, $item_id)) {
        echo "Review submitted successfully!";
    } else {
        echo "Error submitting review.";
    }
}
?>

<!-- HTML Form for Submitting Reviews -->
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br><br>

    <label for="comment">Review:</label><br>
    <textarea id="comment" name="comment" rows="4" cols="50" required></textarea><br><br>

    <label for="rating">Rating (1-5):</label>
    <input type="number" id="rating" name="rating" min="1" max="5" required><br><br>

    <label for="item_id">Item ID (Optional):</label>
    <input type="text" id="item_id" name="item_id"><br><br>

    <input type="submit" value="Submit Review">
</form>


<?php

/**
 * User Review Function
 *
 * This function allows you to create, read, update, and delete user reviews.
 *
 * @param string $productId The ID of the product the review is for.
 * @param string $userId The ID of the user submitting the review.
 * @param string $rating  The rating given by the user (e.g., 1-5).
 * @param string $comment The user's written review comment.
 * @param int $reviewId (Optional) The ID of the review to update or delete. If not provided, a new review is created.
 *
 * @return array An array containing:
 *   - 'success': true if the operation was successful, false otherwise.
 *   - 'message': A message describing the result of the operation.
 *   - 'reviewId': The ID of the newly created or updated review.
 */
function create_review(string $productId, string $userId, string $rating, string $comment, int $reviewId = 0) {
  // **Validation (IMPORTANT)** -  Add more robust validation here!
  if (empty($productId)) {
    return ['success' => false, 'message' => 'Product ID cannot be empty.', 'reviewId' => 0];
  }
  if (empty($userId)) {
    return ['success' => false, 'message' => 'User ID cannot be empty.', 'reviewId' => 0];
  }
  if (empty($rating)) {
    return ['success' => false, 'message' => 'Rating cannot be empty.', 'reviewId' => 0];
  }
  if (empty($comment)) {
    return ['success' => false, 'message' => 'Comment cannot be empty.', 'reviewId' => 0];
  }

  // Ensure Rating is an integer between 1 and 5.  This is crucial.
  $rating = intval($rating); // Convert to integer
  if ($rating < 1 || $rating > 5) {
    return ['success' => false, 'message' => 'Rating must be between 1 and 5.', 'reviewId' => 0];
  }



  // **Database Interaction - Replace with your database connection logic**
  // This is a placeholder.  You'll need to replace this with
  // your actual database connection and query logic.

  // Example using a hypothetical database connection:
  $db = new PDO("mysql:host=localhost;dbname=your_database", "your_username", "your_password");

  try {
    $stmt = $db->prepare(
      "INSERT INTO reviews (productId, userId, rating, comment) VALUES (?, ?, ?, ?)"
    );
    $stmt->execute([$productId, $userId, $rating, $comment]);

    $reviewId = $db->lastInsertId(); // Get the ID of the newly inserted row

    return ['success' => true, 'message' => 'Review created successfully.', 'reviewId' => $reviewId];

  } catch (PDOException $e) {
    // Handle database errors appropriately (logging, etc.)
    return ['success' => false, 'message' => 'Database error: ' . $e->getMessage(), 'reviewId' => 0];
  }
}


/**
 * Example Usage:
 */
// Create a new review
$result = create_review("123", "user123", "5", "Great product! Highly recommended.");
print_r($result);

// Update an existing review (assuming $reviewId = 1)
$result = create_review("456", "user456", "4", "Good, but could be better.", 1);
print_r($result);

// Error handling examples:
$result = create_review("", "user1", "3", "Review", 2); // Empty Product ID
print_r($result);
?>


<?php

/**
 * User Review Function
 *
 * This function allows users to submit and view reviews for a product or service.
 *
 * @param string $productName  The name of the product or service being reviewed.
 * @param string $userId      The ID of the user submitting the review. (Can be user ID, username, etc.)
 * @param string $reviewText  The text of the review.
 * @param array  $reviews     (Optional)  Existing reviews to add to.  Defaults to an empty array.
 *
 * @return array An array containing:
 *   - 'reviews': The updated array of reviews.
 *   - 'success':  True if the review was added successfully, False otherwise.
 *   - 'error':   An error message if the review could not be added.
 */
function create_review(string $productName, string $userId, string $reviewText, array &$reviews = []) {

    // **Validation (Important!)**
    if (empty($reviewText)) {
        return ['reviews' => $reviews, 'success' => false, 'error' => 'Review text cannot be empty.'];
    }

    // **Data Sanitization - VERY IMPORTANT**
    // In a real application, you'd want to sanitize the reviewText more robustly,
    // potentially using htmlspecialchars() or escaping functions appropriate for
    // your database and application.
    $sanitizedReviewText = htmlspecialchars($reviewText); // Simple example - improve for production

    // **Review Data**
    $newReview = [
        'userId' => $userId,
        'reviewText' => $sanitizedReviewText,
        'timestamp' => time() // Use the current timestamp
    ];

    // **Add Review**
    $reviews[] = $newReview;

    return ['reviews' => $reviews, 'success' => true];
}


/**
 * Display User Reviews
 *
 * This function takes an array of reviews and formats them for display.
 *
 * @param array $reviews  An array of review objects.
 *
 * @return string HTML formatted review output.
 */
function display_reviews(array $reviews) {
    $output = "<h2>Reviews for {$reviews[0]['userId']}</h2>"; // Assume first review's user ID is correct for display.

    if (empty($reviews)) {
        $output .= "<p>No reviews yet.</p>";
    } else {
        foreach ($reviews as $review) {
            $output .= "<div class='review'>";
            $output .= "<p><strong>User:</strong> " . htmlspecialchars($review['userId']) . "</p>";  //Escape for display too
            $output .= "<p>" . htmlspecialchars($review['reviewText']) . "</p>";
            $output .= "<p><strong>Date:</strong> " . date('Y-m-d H:i:s', strtotime($review['timestamp'])) . "</p>";
            $output .= "</div>";
        }
    }
    return $output;
}


// **Example Usage:**

// 1.  Create a Review
$product = "Awesome Widget";
$user = "john_doe";
$comment = "This widget is amazing!  I love it!";

$newReviews = create_review($product, $user, $comment);

if ($newReviews['success']) {
    echo "Review created successfully!<br>";
    print_r($newReviews['reviews']); // Print the updated reviews array
} else {
    echo "Error creating review: " . $newReviews['error'] . "<br>";
}

// 2. Display the Reviews
$existingReviews = [
    ['userId' => 'jane_smith', 'reviewText' => 'Good product, but a bit pricey.', 'timestamp' => time() - 3600],
    ['userId' => 'john_doe', 'reviewText' => 'Fantastic!', 'timestamp' => time() - 7200]
];

echo "<hr>";
echo display_reviews($existingReviews);

?>


<?php

/**
 * User Review Class
 *
 * This class allows users to submit and view reviews for a product or service.
 */
class UserReview {

    private $productId;
    private $reviews = [];

    /**
     * Constructor
     *
     * @param int $productId The ID of the product or service being reviewed.
     */
    public function __construct(int $productId) {
        $this->productId = $productId;
    }

    /**
     * Add a new review.
     *
     * @param string $name The user's name.
     * @param string $comment The review text.
     * @param int $rating  The rating from 1 to 5.
     * @return bool True on success, false on failure (e.g., invalid rating).
     */
    public function addReview(string $name, string $comment, int $rating): bool {
        if (!isset($name, $comment) || empty($name) || empty($comment)) {
            return false; // Require name and comment
        }

        if ($rating < 1 || $rating > 5) {
            return false; // Rating must be between 1 and 5
        }

        $this->reviews[] = [
            'name' => $name,
            'comment' => $comment,
            'rating' => $rating
        ];
        return true;
    }

    /**
     * Get all reviews.
     *
     * @return array An array of review objects.
     */
    public function getAllReviews(): array {
        return $this->reviews;
    }

    /**
     * Get the average rating for this product.
     *
     * @return float|null The average rating, or null if no reviews exist.
     */
    public function getAverageRating(): ?float {
        if (empty($this->reviews)) {
            return null;
        }

        $totalRating = 0;
        $numReviews = count($this->reviews);

        foreach ($this->reviews as $review) {
            $totalRating += $review['rating'];
        }

        return $totalRating / $numReviews;
    }

    /**
     * Get a single review by its index.
     *
     * @param int $index The index of the review to retrieve (0-based).
     * @return array|null The review object at the given index, or null if the index is invalid.
     */
    public function getReview(int $index): ?array {
        if ($index >= count($this->reviews)) {
            return null;
        }
        return $this->reviews[$index];
    }

}


/**
 * Example Usage
 */

// Create a review object for a product
$productReview = new UserReview(123);

// Add some reviews
$productReview->addReview("John Doe", "Great product!  I love it.", 5);
$productReview->addReview("Jane Smith", "Could be better.", 3);
$productReview->addReview("Peter Jones", "Excellent value for the money.", 4);

// Get all reviews
$allReviews = $productReview->getAllReviews();
echo "All Reviews:
";
foreach ($allReviews as $review) {
    echo "  Name: " . $review['name'] . "
";
    echo "  Comment: " . $review['comment'] . "
";
    echo "  Rating: " . $review['rating'] . "
";
    echo "  --- 
";
}

// Get the average rating
$averageRating = $productReview->getAverageRating();
echo "Average Rating: " . ($averageRating !== null ? $averageRating : "No reviews yet") . "
";

// Get a specific review
$specificReview = $productReview->getReview(1);
if ($specificReview) {
    echo "Specific Review:
";
    echo "  Name: " . $specificReview['name'] . "
";
    echo "  Comment: " . $specificReview['comment'] . "
";
    echo "  Rating: " . $specificReview['rating'] . "
";
} else {
    echo "Review not found.
";
}
?>


<?php

/**
 * User Review Class
 * 
 * This class allows you to store and manage user reviews for a product or item.
 */
class UserReview {
    private $product_id;
    private $user_id;
    private $rating;
    private $comment;
    private $date_submitted;

    /**
     * Constructor
     * 
     * Initializes a new UserReview object.
     * 
     * @param int $product_id The ID of the product being reviewed.
     * @param int $user_id The ID of the user submitting the review.
     * @param int $rating The rating given (e.g., 1-5).
     * @param string $comment The user's comment.
     */
    public function __construct($product_id, $user_id, $rating, $comment) {
        $this->product_id = $product_id;
        $this->user_id = $user_id;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->date_submitted = date('Y-m-d H:i:s'); // Get current timestamp
    }

    /**
     * Getters
     */
    public function getProductId() {
        return $this->product_id;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function getRating() {
        return $this->rating;
    }

    public function getComment() {
        return $this->comment;
    }

    public function getDateSubmitted() {
        return $this->date_submitted;
    }

    /**
     * Display Review (for debugging or display purposes)
     * 
     * Returns a formatted string representing the review.
     * @return string
     */
    public function displayReview() {
        return "Product ID: " . $this->getProductId() . "
"
               . "User ID: " . $this->getUserId() . "
"
               . "Rating: " . $this->getRating() . "
"
               . "Comment: " . $this->getComment() . "
"
               . "Date Submitted: " . $this->getDateSubmitted();
    }
}


/**
 * User Review Function
 *
 * This function takes a user-submitted review and stores it.
 * 
 * @param array $reviewData An associative array containing the review data:
 *                            - product_id => int (Product ID)
 *                            - user_id => int (User ID)
 *                            - rating => int (Rating)
 *                            - comment => string (Comment)
 * @return UserReview|null A UserReview object if the review was successfully created, or null if there was an error.
 */
function storeUserReview(array $reviewData) {
    // Validation (Add more robust validation as needed)
    if (!isset($reviewData['product_id'], $reviewData['user_id'], $reviewData['rating'], $reviewData['comment'])) {
        error_log("Missing review data: " . print_r($reviewData, true));
        return null;
    }

    if (!is_int($reviewData['product_id']) || !is_int($reviewData['user_id']) || !is_int($reviewData['rating'])) {
        error_log("Invalid review data types: " . print_r($reviewData, true));
        return null;
    }

    if ($reviewData['rating'] < 1 || $reviewData['rating'] > 5) {
        error_log("Invalid rating value: " . $reviewData['rating']);
        return null;
    }

    // In a real application, you'd save this to a database
    // For this example, we'll just create a UserReview object.
    $review = new UserReview($reviewData['product_id'], $reviewData['user_id'], $reviewData['rating'], $reviewData['comment']);
    return $review;
}


// Example Usage:
$reviewData = [
    'product_id' => 123,
    'user_id' => 456,
    'rating' => 4,
    'comment' => 'Great product! Highly recommended.'
];

$review = storeUserReview($reviewData);

if ($review) {
    echo "Review created successfully:
";
    echo $review->displayReview() . "
";
} else {
    echo "Error creating review.
";
}


?>


<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or service.
 * It includes error handling and basic input validation.
 *
 * @param string $productId The ID of the product or service being reviewed.
 * @param string $userName The name of the user submitting the review.
 * @param string $reviewText The text of the review.
 * @param int $rating   (Optional) The rating given by the user (1-5). Defaults to 0.
 * @return array|string Returns an array of reviews if successful, or an error message string if there's an issue.
 */
function storeUserReview(string $productId, string $userName, string $reviewText, int $rating = 0) {
    // Input Validation - Basic checks
    if (empty($productId)) {
        return "Error: Product ID cannot be empty.";
    }
    if (empty($userName)) {
        return "Error: User name cannot be empty.";
    }
    if (empty($reviewText)) {
        return "Error: Review text cannot be empty.";
    }
    if ($rating < 1 || $rating > 5) {
        return "Error: Rating must be between 1 and 5.";
    }


    //  Simulate storing the review in a database (replace with your actual database logic)
    //  This is a simplified example - use proper database queries for real applications.
    $review = [
        'productId' => $productId,
        'userName' => $userName,
        'reviewText' => $reviewText,
        'rating' => $rating,
        'timestamp' => time() // Add a timestamp for tracking
    ];

    // Store the review in an array (for this example)
    // In a real application, you'd insert this data into a database.
    $storedReviews = [
        'reviews' => [
            $review
        ]
    ];


    return $storedReviews;
}


/**
 * Display User Reviews
 *
 * This function retrieves and displays user reviews for a given product ID.
 *
 * @param array $reviews An array of reviews (returned from storeUserReview or loaded from database).
 * @return string HTML to display the reviews.
 */
function displayReviews(array $reviews) {
    if (empty($reviews['reviews'])) {
        return "<p>No reviews yet.</p>";
    }

    $html = "<h2>Reviews for Product ID: " . $reviews['reviews'][0]['productId'] . "</h2>"; // Display product ID

    foreach ($reviews['reviews'] as $review) {
        $html .= "<div class='review'>";
        $html .= "<p><strong>User:</strong> " . htmlspecialchars($review['userName']) . "</p>"; // Use htmlspecialchars for security
        $html .= "<p>" . htmlspecialchars($review['reviewText']) . "</p>";
        $html .= "<p><strong>Rating:</strong> " . $review['rating'] . "</p>";
        $html .= "<div class='timestamp'>" . date('Y-m-d H:i:s', $review['timestamp']) . "</div>";
        $html .= "</div>";
    }

    return $html;
}


// --- Example Usage ---

// Store a review
$reviewData = storeUserReview("123", "John Doe", "This is a great product!", 5);

if (is_array($reviewData)) {
    if (isset($reviewData['error'])) {
        echo "<p>Error: " . $reviewData['error'] . "</p>";
    } else {
        echo "Review stored successfully!";
        // Display the reviews
        $reviews = storeUserReview("123", "Jane Smith", "It's okay.", 3); //Another review

        echo displayReviews($reviews); //Display the reviews

    }
} else {
    echo "Error: " . $reviewData;
}

?>


<?php

/**
 * Reviews class to manage user reviews for a product.
 */
class Review {

  private $productId;
  private $userId;
  private $rating;
  private $comment;

  /**
   * Constructor for the Review class.
   *
   * @param int $productId The ID of the product the review is for.
   * @param int $userId The ID of the user submitting the review.
   * @param int $rating The rating given by the user (e.g., 1-5).
   * @param string $comment The user's written review.
   */
  public function __construct(int $productId, int $userId, int $rating, string $comment) {
    $this->productId = $productId;
    $this->userId = $userId;
    $this->rating = $rating;
    $this->comment = $comment;
  }

  /**
   * Getters for the review properties.
   *
   * @return mixed
   */
  public function getProductId() {
    return $this->productId;
  }

  public function getUserId() {
    return $this->userId;
  }

  public function getRating() {
    return $this->rating;
  }

  public function getComment() {
    return $this->comment;
  }

  /**
   * Validates the review data.
   *
   * @return bool True if the review data is valid, false otherwise.
   */
  public function isValid(): bool {
    // Add validation logic here.  For example:
    // - Rating must be between 1 and 5
    // - Comment cannot be empty
    return $this->rating >= 1 && $this->rating <= 5 && !empty($this->comment);
  }

  /**
   *  Outputs the review in a formatted string
   * @return string A formatted string representation of the review.
   */
  public function __toString(): string {
    if (!$this->isValid()) {
      return "Invalid Review Data";
    }
    return "Product ID: " . $this->productId .
           "
User ID: " . $this->userId .
           "
Rating: " . $this->rating .
           "
Comment: " . $this->comment;
  }

}

/**
 *  User review function
 */
function generateReviewUserInterface() {
    echo "<!DOCTYPE html>";
    echo "<html>";
    echo "<head>";
    echo "<title>User Review Form</title>";
    echo "</head>";
    echo "<body>";
    echo "<h1>User Review Form</h1>";

    echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
    echo "<label for='productId'>Product ID:</label><br>";
    echo "<input type='number' id='productId' name='productId' required><br><br>";

    echo "<label for='userId'>User ID:</label><br>";
    echo "<input type='number' id='userId' name='userId' required><br><br>";

    echo "<label for='rating'>Rating (1-5):</label><br>";
    echo "<input type='number' id='rating' name='rating' min='1' max='5' required><br><br>";

    echo "<label for='comment'>Comment:</label><br>";
    echo "<textarea id='comment' name='comment' rows='4' cols='50' required></textarea><br><br>";

    echo "<input type='submit' value='Submit Review'>";
    echo "</form>";
    echo "</body>";
    echo "</html>";
}


// Example usage:
// Create a review object
$review = new Review(123, 456, 4, "Great product!");

// Print the review
echo $review; // Output the formatted review string

// Generate HTML for user review form
generateReviewUserInterface();

?>


<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product or service.
 * It includes input validation and basic sanitization.
 *
 * @param string $productId The ID of the product or service being reviewed.
 * @param string $reviewText The review text submitted by the user.
 * @param string $username The username of the user submitting the review.
 * @return array An array containing:
 *   - 'success': True if the review was successfully submitted, False otherwise.
 *   - 'message': A message indicating the status of the review submission.
 *   - 'review': The submitted review data (success only).
 */
function submitReview(string $productId, string $reviewText, string $username): array
{
    // Input Validation
    if (empty($productId)) {
        return [
            'success' => false,
            'message' => 'Product ID cannot be empty.',
            'review' => null,
        ];
    }

    if (empty($reviewText)) {
        return [
            'success' => false,
            'message' => 'Review text cannot be empty.',
            'review' => null,
        ];
    }

    // Sanitize input (basic example - can be expanded)
    $reviewText = htmlspecialchars($reviewText);  // Prevents XSS attacks

    // TODO:  Add more robust sanitization and validation here
    // For example, limit review length, allow only certain characters, etc.

    // Store the review (Placeholder - Replace with your database logic)
    // This is just a simulation - in a real application, you'd save this data to a database.
    $reviewData = [
        'productId' => $productId,
        'username' => $username,
        'reviewText' => $reviewText,
        'timestamp' => time()  // Add timestamp for ordering
    ];

    // Simulate saving to database
    //saveReviewToDatabase($reviewData);  // Replace with your actual database saving function

    // Return successful response
    return [
        'success' => true,
        'message' => 'Review submitted successfully!',
        'review' => $reviewData,
    ];
}


/**
 * Function to display the review based on product ID.  This is just a placeholder.
 *
 * @param string $productId The ID of the product or service.
 * @return string The HTML display of the review.
 */
function displayReview(string $productId): string
{
    // Replace with your database query to retrieve the review for the given product ID
    // This is just a demo - using a hardcoded review for this example.

    $reviews = [
        'product1' => [
            'username' => 'John Doe',
            'reviewText' => 'Great product!  I highly recommend it.',
            'timestamp' => time()
        ],
        'product2' => [
            'username' => 'Jane Smith',
            'reviewText' => 'The service was excellent. Very helpful staff.',
            'timestamp' => time()
        ]
    ];

    if (isset($reviews[$productId])) {
        return sprintf(
            '<b>Username:</b> %s<br>
            <b>Review:</b> %s<br>
            <b>Timestamp:</b> %s',
            $reviews[$productId]['username'],
            $reviews[$productId]['reviewText'],
            $reviews[$productId]['timestamp']
        );
    } else {
        return 'No reviews found for this product.';
    }
}

// Example Usage:
$productId = 'product1';  // Or 'product2'
$reviewText = 'This is a fantastic review!';
$username = 'Test User';

$result = submitReview($productId, $reviewText, $username);

if ($result['success']) {
    echo "Review submitted successfully!<br>";
    echo "Review Data: " . print_r($result['review'], true) . "<br>";
    echo displayReview($productId); // Display the review
} else {
    echo "Error submitting review: " . $result['message'] . "<br>";
}


?>


<?php

/**
 * Class Review
 *
 * Handles the creation, storage, and retrieval of user reviews.
 */
class Review {

    private $db;  // Database connection

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Creates a new review.
     *
     * @param int $productId The ID of the product the review is for.
     * @param string $user  The username of the reviewer.
     * @param string $rating The rating (e.g., 1-5 stars).
     * @param string $comment The review comment.
     *
     * @return int|false The ID of the newly created review on success, false on failure.
     */
    public function createReview(int $productId, string $user, string $rating, string $comment) {
        // Input validation (important!)
        if (!$productId || !$user || !$rating || !$comment) {
            return false;
        }

        if (!is_numeric($rating) || (int)$rating < 1 || (int)$rating > 5) {
            return false; // Rating must be 1-5
        }

        // Sanitize inputs (prevent SQL injection)
        $productId = $this->db->real_escape_string($productId);
        $user = $this->db->real_escape_string($user);
        $rating = (int)$rating;
        $comment = $this->db->real_escape_string($comment);

        // Construct SQL query
        $query = "INSERT INTO reviews (product_id, user, rating, comment) VALUES ('$productId', '$user', '$rating', '$comment')";

        // Execute the query
        return $this->db->query($query);
    }


    /**
     * Retrieves reviews for a specific product.
     *
     * @param int $productId The ID of the product.
     *
     * @return array An array of review objects, or an empty array if no reviews exist.
     */
    public function getReviewsByProduct(int $productId) {
        $productId = $this->db->real_escape_string($productId);

        $query = "SELECT * FROM reviews WHERE product_id = '$productId'";
        $result = $this->db->query($query);

        $reviews = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $reviews[] = new ReviewObject($row); // Create ReviewObject instances
            }
        }

        return $reviews;
    }

    /**
     *  Review Object (Helper Class) - Makes retrieving review data easier
     */
    private function __construct($data) {
        $this->id = $data['id'];
        $this->product_id = $data['product_id'];
        $this->user = $data['user'];
        $this->rating = $data['rating'];
        $this->comment = $data['comment'];
        $this->created_at = $data['created_at'];
    }

}


/**
 * ReviewObject - A class representing a single review.  This keeps things cleaner than returning
 * arrays of associative arrays.
 */
class ReviewObject {
    public $id;
    public $product_id;
    public $user;
    public $rating;
    public $comment;
    public $created_at;

    public function __construct($data) {
        $this->id = $data['id'];
        $this->product_id = $data['product_id'];
        $this->user = $data['user'];
        $this->rating = $data['rating'];
        $this->comment = $data['comment'];
        $this->created_at = $data['created_at'];
    }
}



// Example usage (assuming you have a database connection $db):
//  (This is just for demonstration, you'll need to adapt to your specific setup)

// Assuming $db is your database connection object (e.g., mysqli)

// 1. Create a review
$review = new Review($db);
$reviewId = $review->createReview(123, "JohnDoe", 4, "Great product!");

if ($reviewId) {
    echo "Review created successfully with ID: " . $reviewId . "
";
} else {
    echo "Failed to create review.
";
}


// 2. Get reviews for product 123
$reviews = $review->getReviewsByProduct(123);

if (count($reviews) > 0) {
    echo "Reviews for product 123:
";
    foreach ($reviews as $review) {
        echo "- User: " . $review->user . ", Rating: " . $review->rating . ", Comment: " . $review->comment . "
";
    }
} else {
    echo "No reviews found for product 123.
";
}
?>


<?php

/**
 * User Review Function
 *
 * This function allows you to collect and display user reviews.
 * It handles input validation, data storage (simplified for demonstration),
 * and basic output formatting.
 *
 * @param string $productId The ID of the product being reviewed.
 * @param string $reviewerName The name of the reviewer.
 * @param string $reviewText The review text submitted by the reviewer.
 * @param array $rating A numeric rating (e.g., 1-5).
 *
 * @return void  Prints the review to the console (for demonstration).  In a real
 *               application, you would typically store this data in a database.
 */
function createReview(string $productId, string $reviewerName, string $reviewText, array $rating) {

  // Input Validation (Important!)
  if (empty($productId)) {
    echo "Error: Product ID cannot be empty.
";
    return;
  }
  if (empty($reviewerName)) {
    echo "Error: Reviewer Name cannot be empty.
";
    return;
  }
  if (empty($reviewText)) {
    echo "Error: Review Text cannot be empty.
";
    return;
  }
  if (!is_array($rating) || count($rating) !== 1) {
    echo "Error: Rating must be a single numeric value (e.g., 1-5).
";
    return;
  }
  if (!is_numeric($rating[0])) {
    echo "Error: Rating must be a numeric value.
";
    return;
  }
  if ($rating[0] < 1 || $rating[0] > 5) {
    echo "Error: Rating must be between 1 and 5.
";
    return;
  }

  // Data Storage (Simplified - Replace with Database Integration)
  $review = [
    'productId' => $productId,
    'reviewerName' => $reviewerName,
    'reviewText' => $reviewText,
    'rating' => $rating[0]
  ];

  // Output/Display the review (replace with your desired presentation)
  echo "Review for Product ID: " . $productId . "
";
  echo "Reviewer: " . $reviewerName . "
";
  echo "Rating: " . $rating[0] . " / 5
";
  echo "Review Text: " . $reviewText . "
";
  echo "---
";
}


// Example Usage:
createReview("P123", "John Doe", "Great product!  Exactly what I needed.", [5]);
createReview("P456", "Jane Smith", "Could be better, but decent.", [3]);
createReview("P789", "Peter Jones", "Amazing!  Highly recommended.", [5]);
createReview("P101", "Alice Brown", "It's okay.", [2]);

// Example of invalid input:
createReview("", "Invalid Name", "Review Text", [4]); // Empty Product ID
createReview("P999", "", "Review Text", [6]); // Empty Reviewer Name
createReview("P111", "Test User", "Invalid Rating", [1, 2]); // Invalid rating format
createReview("P222", "User", "Review", ["abc"]); // Invalid rating type
?>


<?php

/**
 * User Review Function
 *
 * This function allows you to collect and display user reviews for a specific item.
 *
 * @param string $itemId The ID of the item being reviewed.
 * @param int $maxReviews The maximum number of reviews to display (optional, defaults to 5).
 * @return array An array containing:
 *              - 'reviews': An array of review objects.
 *              - 'totalReviews': The total number of reviews for the item.
 */
function getReviews(string $itemId, int $maxReviews = 5) {
  // Simulate a database or data source for reviews.  Replace this with your actual data source.
  $reviewsData = [
    'item123' => [
      ['user' => 'John Doe', 'rating' => 4, 'comment' => 'Great product!  Highly recommended.'],
      ['user' => 'Jane Smith', 'rating' => 5, 'comment' => 'Excellent quality and fast delivery.'],
      ['user' => 'Peter Jones', 'rating' => 3, 'comment' => 'Good value for the price.'],
    ],
    'item456' => [
      ['user' => 'Alice Brown', 'rating' => 2, 'comment' => 'Not as good as I expected.'],
      ['user' => 'Bob Williams', 'rating' => 3, 'comment' => 'Decent, but could be better.'],
    ],
  ];

  // Check if the item has any reviews
  if (!isset($reviewsData[$itemId])) {
    return ['reviews' => [], 'totalReviews' => 0];
  }

  $reviews = $reviewsData[$itemId];

  // Limit the number of reviews
  $reviews = array_slice($reviews, 0, $maxReviews, true);

  // Calculate the total number of reviews
  $totalReviews = count($reviews);

  return ['reviews' => $reviews, 'totalReviews' => $totalReviews];
}


/**
 * Display Reviews Function (Example)
 *
 * This function takes the array of reviews and formats it for display.
 *
 * @param array $reviews The array of review objects returned by getReviews().
 */
function displayReviews(array $reviews) {
  echo "<div class='review-container'>";
  if (empty($reviews['reviews'])) {
    echo "<p>No reviews yet.</p>";
  } else {
    echo "<h2>Reviews for Item " . $reviews['itemId'] . "</h2>"; // Assuming you pass $itemId to this function
    echo "<ul>";
    foreach ($reviews['reviews'] as $review) {
      echo "<li>";
      echo "<p><strong>User:</strong> " . $review['user'] . "</p>";
      echo "<p><strong>Rating:</strong> " . $review['rating'] . " / 5</p>";
      echo "<p>" . $review['comment'] . "</p>";
      echo "</li>";
    }
    echo "</ul>";

    if (count($reviews['reviews']) < $reviews['totalReviews']) {
      echo "<p>Showing " . count($reviews['reviews']) . " of " . $reviews['totalReviews'] . " reviews.</p>";
    }
  }
  echo "</div>";
}



// Example Usage:

// Get reviews for item 'item123'
$reviewsForItem123 = getReviews('item123');
displayReviews($reviewsForItem123);

// Get reviews for item 'item456'
$reviewsForItem456 = getReviews('item456');
displayReviews($reviewsForItem456);

// Get reviews for a non-existent item
$reviewsForNonExistentItem = getReviews('item999');
displayReviews($reviewsForNonExistentItem);

?>


<?php

/**
 * User Review Function
 *
 * This function allows you to store user reviews with various options.
 *
 * @param string $productId The ID of the product being reviewed.
 * @param string $username The username of the reviewer.
 * @param string $rating  The rating given by the user (e.g., 1-5).
 * @param string $comment  The user's comment on the product.
 * @param string $databaseConnection  A valid database connection object.
 *
 * @return bool True on success, false on failure.
 */
function storeUserReview(
    string $productId,
    string $username,
    string $rating,
    string $comment,
    $databaseConnection // Use $databaseConnection instead of passing it as a string
) {
    try {
        // Validate input (basic - expand for more robust validation)
        if (empty($productId) || empty($username) || empty($rating) || empty($comment)) {
            throw new Exception("All fields are required.");
        }

        if (!preg_match('/^[0-5][0-9]*$/', $rating)) {
            throw new Exception("Rating must be a number between 1 and 5.");
        }

        // Prepare the SQL statement
        $sql = "INSERT INTO reviews (product_id, user_name, rating, comment) 
                VALUES (?, ?, ?, ?)";

        // Prepare the statement
        $stmt = $databaseConnection->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("ssis", $productId, $username, $rating, $comment);

        // Execute the statement
        $stmt->execute();

        // Close the statement
        $stmt->close();

        return true;

    } catch (Exception $e) {
        // Handle errors (logging is recommended)
        error_log("Error storing review: " . $e->getMessage());  // Log the error
        return false;
    }
}

// Example Usage (assuming you have a database connection named $conn)

// $productId = "123";
// $username = "JohnDoe";
// $rating = "4";
// $comment = "Great product! Highly recommended.";

// if (storeUserReview($productId, $username, $rating, $comment, $conn)) {
//     echo "Review successfully stored!";
// } else {
//     echo "Failed to store review.";
// }

?>


<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or service.
 *
 * @param string $productName - The name of the product or service being reviewed.
 * @param string $userId - The ID of the user writing the review.  Can be null for anonymous reviews.
 * @param string $reviewText - The text of the review.
 * @param int $rating - The rating given by the user (e.g., 1-5 stars).
 * @param PDO $db - A PDO database connection object.
 *
 * @return array - An array containing the review data or an error message.
 */
function create_review(string $productName, string $userId, string $reviewText, int $rating, PDO $db) {
    // Input Validation (Basic - Expand as needed)
    if (empty($productName)) {
        return ['error' => 'Product name cannot be empty.'];
    }
    if (empty($reviewText)) {
        return ['error' => 'Review text cannot be empty.'];
    }
    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        return ['error' => 'Rating must be a number between 1 and 5.'];
    }

    // SQL query -  Protect against SQL injection!  Use prepared statements.
    $sql = "INSERT INTO reviews (product_name, user_id, review_text, rating)
            VALUES (:product_name, :user_id, :review_text, :rating)";

    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':product_name', $productName);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':review_text', $reviewText);
        $stmt->bindParam(':rating', $rating);

        $result = $stmt->execute();

        if ($result) {
            return ['success' => 'Review created successfully.'];
        } else {
            return ['error' => 'Failed to create review. Database error.'];
        }
    } catch (PDOException $e) {
        return ['error' => 'Database error: ' . $e->getMessage()];
    }
}


/**
 * Function to display all reviews for a product.
 *
 * @param string $productName - The name of the product to retrieve reviews for.
 * @param PDO $db - A PDO database connection object.
 *
 * @return array - An array containing the review data.
 */
function get_reviews(string $productName, PDO $db) {
    $sql = "SELECT * FROM reviews WHERE product_name = :product_name";

    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':product_name', $productName);
        $stmt->execute();

        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $reviews;
    } catch (PDOException $e) {
        return ['error' => 'Database error: ' . $e->getMessage()];
    }
}



// --- Example Usage (For demonstration -  replace with your actual database setup) ---

// **IMPORTANT: Replace these with your database connection details!**
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';

try {
    $db = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}



// Example of creating a review:
$review_data = create_review('Awesome Product', 'user123', 'This is a fantastic product!', 5, $db);
print_r($review_data); // Output the result of the create_review function.
// Example: Displaying reviews for a product
$product_name = 'Awesome Product';
$reviews = get_reviews($product_name, $db);
print_r($reviews); // Display the reviews.

// ---  End of Example Usage ---



// ---  Database Table Structure (Example)  ---
/*
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(255) NOT NULL,
    user_id VARCHAR(255) NULL,  // Can be NULL for anonymous reviews
    review_text TEXT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5)
);
*/


<?php

/**
 * User Review Class
 */
class UserReview {

    private $review_id;
    private $user_id;
    private $product_id;
    private $rating;
    private $comment;
    private $review_date;

    /**
     * Constructor for the UserReview class
     *
     * @param int $review_id (Optional) - Unique ID for the review.  If not provided, one will be generated.
     * @param int $user_id The ID of the user who submitted the review.
     * @param int $product_id The ID of the product being reviewed.
     * @param int $rating The rating given by the user (e.g., 1-5).
     * @param string $comment The user's comment on the product.
     * @param string $review_date The date the review was submitted (e.g., "Y-m-d H:i:s").
     */
    public function __construct($review_id = null, $user_id, $product_id, $rating, $comment, $review_date) {
        $this->review_id = $review_id;
        $this->user_id = $user_id;
        $this->product_id = $product_id;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->review_date = $review_date;
    }

    /**
     * Getters and Setters (for encapsulation)
     */

    public function getReviewId() {
        return $this->review_id;
    }

    public function setReviewId($review_id) {
        $this->review_id = $review_id;
    }


    public function getUserId() {
        return $this->user_id;
    }

    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }

    public function getProductId() {
        return $this->product_id;
    }

    public function setProductId($product_id) {
        $this->product_id = $product_id;
    }

    public function getRating() {
        return $this->rating;
    }

    public function setRating($rating) {
        $this->rating = $rating;
    }

    public function getComment() {
        return $this->comment;
    }

    public function setComment($comment) {
        $this->comment = $comment;
    }

    public function getReviewDate() {
        return $this->review_date;
    }

    public function setReviewDate($review_date) {
        $this->review_date = $review_date;
    }

    /**
     * Display the review information in a readable format.
     *
     * @return string  A formatted string representing the review.
     */
    public function displayReview() {
        return "Review ID: " . $this->getReviewId() .
               "
User ID: " . $this->getUserId() .
               "
Product ID: " . $this->getProductId() .
               "
Rating: " . $this->getRating() .
               "
Comment: " . $this->getComment() .
               "
Date: " . $this->getReviewDate();
    }

}


/**
 * User Review Function (Illustrative Example -  A Basic Review "Function")
 *
 * This is a simplified example demonstrating how you might *use* a UserReview object.
 *  In a real-world scenario, you'd likely integrate this with a database.
 */
function processUserReview($review_id, $user_id, $product_id, $rating, $comment, $review_date) {
    // Create a UserReview object
    $review = new UserReview($review_id, $user_id, $product_id, $rating, $comment, $review_date);

    // Basic validation (you'd want more robust validation in a real application)
    if ($review->getRating() < 1 || $review->getRating() > 5) {
        echo "Invalid rating. Rating must be between 1 and 5.
";
        return false;
    }

    // Display the review
    echo "Review Submitted:
" . $review->displayReview() . "

";

    // In a real application, you'd save this review to a database.

    return true; // Indicate success
}


// Example Usage
processUserReview(123, 45, 67, 4, "Great product!", "2023-10-27 10:00:00");
processUserReview(456, 78, 90, 5, "Excellent value!", "2023-10-27 11:30:00");
processUserReview(789, 10, 12, 3, "Okay", "2023-10-27 13:00:00"); //  Demonstrating a 3-star review


<?php

/**
 * User Review Function
 *
 * This function allows you to create, retrieve, update, and delete user reviews.
 * It includes basic validation and error handling.  This is a simplified
 * example and can be expanded upon significantly for a production environment.
 *
 * @param string $productId The ID of the product the review is for.
 * @param string $username  The username of the reviewer.
 * @param string $rating   The rating given (e.g., 1-5).
 * @param string $comment  The review text.
 *
 * @return array An array containing the response data:
 *               - 'success': True if the operation was successful, false otherwise.
 *               - 'message': A message describing the result.
 *               - 'data':  The review data if successful, or an empty array.
 */
function create_user_review(string $productId, string $username, string $rating, string $comment) {
  // Input Validation (Basic)
  if (empty($productId) || empty($username) || empty($rating) || empty($comment)) {
    return [
      'success' => false,
      'message' => "All fields are required.",
      'data' => []
    ];
  }

  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    return [
      'success' => false,
      'message' => "Rating must be a number between 1 and 5.",
      'data' => []
    ];
  }

  // *** In a real application, you would: ***
  // 1. Connect to a database to store the review.
  // 2. Sanitize and validate the input thoroughly.
  // 3.  Handle database errors properly.
  // 4.  Potentially check for existing reviews for the same user and product.
  // 5.  Implement a more robust security mechanism (e.g., escaping user input).

  // For demonstration purposes, we'll just simulate a successful review creation.
  $review_id = time(); // Generate a simple unique ID
  $review = [
    'review_id' => $review_id,
    'product_id' => $productId,
    'username' => $username,
    'rating' => $rating,
    'comment' => $comment,
    'date_created' => date('Y-m-d H:i:s')
  ];

  return [
    'success' => true,
    'message' => "Review created successfully.",
    'data' => $review
  ];
}


/**
 *  Example Usage
 */

// Create a review
$response = create_user_review('product123', 'JohnDoe', 4, 'Great product! Highly recommended.');

echo "<pre>";
print_r($response);
echo "</pre>";

// Example of an invalid response
$response = create_user_review('', 'JaneSmith', 3, 'Terrible.');
echo "<pre>";
print_r($response);
echo "</pre>";
?>


<?php

/**
 * User Review Function
 * 
 * This function allows users to submit and display user reviews for a specific item.
 *
 * @param string $item_id The unique identifier of the item being reviewed.
 * @param string $item_title The title of the item being reviewed.
 * @param string $review_text The user-submitted review text.
 * @param array $user_data An array containing user data (username, etc.).  Optional.
 * 
 * @return array An array containing the review data and potentially an error message.
 */
function submit_review(string $item_id, string $item_title, string $review_text, array $user_data = []) {
    // Validate inputs - basic example
    if (empty($item_id) || empty($review_text)) {
        return ['success' => false, 'message' => 'Item ID and review text are required fields.'];
    }

    // Sanitize inputs (important for security)
    $item_id = filter_var($item_id, FILTER_SANITIZE_STRING);
    $review_text = filter_var($review_text, FILTER_SANITIZE_STRING);

    //  You might add more sophisticated validation here:
    //  - Check review text length
    //  -  Basic spam detection (e.g., blacklists)

    //  Store the review data (In a real application, this would be a database)
    $review = [
        'item_id' => $item_id,
        'item_title' => $item_title,
        'review_text' => $review_text,
        'user_id' => isset($user_data['user_id']) ? $user_data['user_id'] : null, // Use user ID if available
        'created_at' => date('Y-m-d H:i:s'),
    ];

    //  In a real application, you would save this $review array to a database.
    //  For example:
    //  $db->insert('reviews', $review);

    return ['success' => true, 'review_data' => $review, 'message' => 'Review submitted successfully!'];
}


/**
 * Displays user reviews for a given item.
 *
 * @param string $item_id The ID of the item to display reviews for.
 * @param array $reviews An array of review data (returned from submit_review).
 *
 * @return string HTML representation of the reviews.
 */
function display_reviews(string $item_id, array $reviews) {
    if (empty($reviews)) {
        return "<p>No reviews yet.</p>";
    }

    $html = "<h2>Reviews for {$reviews[$item_id]['item_title']}</h2>";
    $html .= "<ul>";

    foreach ($reviews[$item_id] as $review) {
        $html .= "<li>";
        $html .= "<p><strong>" . $review['user_id'] . ":</strong> " . $review['review_text'] . "</p>";
        $html .= "<span class='created_at'>" . $review['created_at'] . "</span>";
        $html .= "</li>";
    }

    $html .= "</ul>";
    return $html;
}



// Example Usage (Demonstration)
// -------------------------------------------------------------------

// 1. Submit a review:
$review_result = submit_review('product_123', 'Awesome Widget', 'This widget is amazing!  It does everything I need.');

if ($review_result['success']) {
    echo "<h2>Review Submitted</h2>";
    echo "<p>Review ID: " . $review_result['review_data']['item_id'] . "</p>";
    echo "<p>Review Text: " . $review_result['review_data']['review_text'] . "</p>";
} else {
    echo "<h2>Error</h2>";
    echo "<p>Error submitting review: " . $review_result['message'] . "</p>";
}

// 2. Display the reviews (assuming you have some reviews)
//  For this to work, you would need to populate the $reviews array from a database or other source.
//  For demonstration, we'll create a sample array.
$sample_reviews = [
    'product_123' => [
        ['item_id' => 'product_123', 'review_text' => 'Great product!'],
        ['item_id' => 'product_123', 'review_text' => 'Works as expected.'],
    ],
    'product_456' => [
        ['item_id' => 'product_456', 'review_text' => 'Not bad, but overpriced.'],
    ],
];

$reviews_html = display_reviews('product_123', $sample_reviews);
echo $reviews_html;
?>


<?php

/**
 * Class UserReview
 *
 * This class provides functionality to create, retrieve, update, and delete user reviews.
 * It includes basic validation and error handling.
 */
class UserReview
{
    private $db; // Database connection object (replace with your actual database setup)

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Creates a new user review.
     *
     * @param int $productId The ID of the product the review is for.
     * @param string $username The username of the reviewer.
     * @param string $rating The rating (e.g., 1-5).
     * @param string $comment The review text.
     *
     * @return int|false The ID of the newly created review on success, false on failure.
     */
    public function createReview($productId, $username, $rating, $comment)
    {
        // Validation (add more as needed)
        if (!$productId || !$username || !$rating || !$comment) {
            return false;
        }

        if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
            return false;
        }

        // Sanitize input (important for security)
        $productId = (int)$productId; // Cast to integer
        $username = trim($username);
        $rating = (int)$rating;
        $comment = trim($comment);

        // Prepare and execute the SQL query
        $sql = "INSERT INTO reviews (product_id, username, rating, comment) 
                VALUES ($productId, '$username', $rating, '$comment')";

        $result = $this->db->query($sql);

        if ($result) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    /**
     * Retrieves a review by its ID.
     *
     * @param int $reviewId The ID of the review to retrieve.
     *
     * @return array|false An array containing the review details (product_id, username, rating, comment)
     *                   or false if the review does not exist.
     */
    public function getReview($reviewId)
    {
        if (!is_numeric($reviewId)) {
            return false;
        }

        $reviewId = (int)$reviewId;

        $sql = "SELECT product_id, username, rating, comment 
                FROM reviews 
                WHERE id = $reviewId";

        $result = $this->db->query($sql);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

    /**
     * Updates an existing review.
     *
     * @param int $reviewId The ID of the review to update.
     * @param int $productId The new ID of the product.
     * @param string $username The new username.
     * @param string $rating The new rating.
     * @param string $comment The new comment.
     *
     * @return bool True on success, false on failure.
     */
    public function updateReview($reviewId, $productId, $username, $rating, $comment)
    {
        if (!is_numeric($reviewId) || !$productId || !$username || !$rating || !$comment) {
            return false;
        }

        if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
            return false;
        }

        // Sanitize input
        $productId = (int)$productId;
        $username = trim($username);
        $rating = (int)$rating;
        $comment = trim($comment);


        // Prepare SQL
        $sql = "UPDATE reviews 
                SET product_id = $productId, 
                    username = '$username', 
                    rating = $rating, 
                    comment = '$comment' 
                WHERE id = $reviewId";

        $result = $this->db->query($sql);

        return $result;
    }

    /**
     * Deletes a review.
     *
     * @param int $reviewId The ID of the review to delete.
     *
     * @return bool True on success, false on failure.
     */
    public function deleteReview($reviewId)
    {
        if (!is_numeric($reviewId)) {
            return false;
        }

        $reviewId = (int)$reviewId;

        $sql = "DELETE FROM reviews WHERE id = $reviewId";

        $result = $this->db->query($sql);

        return $result;
    }
}

// Example Usage (assuming you have a database connection object $db)
//  -  Replace this with your actual database setup and connection.
//  -  The example assumes you have a table named 'reviews' with columns: id (INT, PRIMARY KEY), product_id (INT), username (VARCHAR), rating (INT), comment (TEXT)
//  -  You'll need to adapt this to your specific database structure.

//  $db = new PDO("mysql:host=localhost;dbname=your_database", "your_user", "your_password");

// $review = new UserReview($db);

// // Create a review
// $reviewId = $review->createReview(1, "JohnDoe", 4, "Great product!");

// if ($reviewId) {
//     echo "Review created with ID: " . $reviewId . "<br>";

//     // Get the review
//     $review = $review->getReview($reviewId);
//     if ($review) {
//         echo "Review details: " . json_encode($review);
//     } else {
//         echo "Review not found.";
//     }

//     // Update the review
//     $updateResult = $review->updateReview($reviewId, 2, "JaneSmith", 5, "Excellent!");
//     if ($updateResult) {
//         echo "<br>Review updated successfully.";
//     } else {
//         echo "<br>Failed to update review.";
//     }

//     // Delete the review
//     $deleteResult = $review->deleteReview($reviewId);
//     if ($deleteResult) {
//         echo "<br>Review deleted successfully.";
//     } else {
//         echo "<br>Failed to delete review.";
//     }

// } else {
//     echo "Failed to create review.";
// }
?>


<?php

/**
 *  User Review Function
 *
 *  This function handles creating, retrieving, updating, and deleting user reviews.
 *  It demonstrates basic CRUD (Create, Read, Update, Delete) operations
 *  for a review system.
 *
 *  Example Usage:
 *  $reviewSystem = new ReviewSystem();
 *
 *  // Create a new review
 *  $review = $reviewSystem->createReview(['user_id' => 1, 'product_id' => 101, 'rating' => 5, 'comment' => 'Great product!']);
 *
 *  // Retrieve a review by ID
 *  $review = $reviewSystem->getReview(1);
 *
 *  // Update a review
 *  $review = $reviewSystem->updateReview(1, ['rating' => 4, 'comment' => 'Good, but could be better.']);
 *
 *  // Delete a review
 *  $reviewSystem->deleteReview(1);
 *
 * @param array $data  An associative array containing the review data.
 *                       Required keys: 'user_id', 'product_id', 'rating', 'comment'.
 * @return array|null  The newly created review object if creation was successful,
 *                      or the review object if update was successful,
 *                      or null if update or delete was successful.
 */
class ReviewSystem
{
    private $reviews = []; // In-memory storage for simplicity.  Use a database in a real application.
    private $nextReviewId = 1;

    public function createReview(array $data)
    {
        // Validation (basic)
        if (!isset($data['user_id'], $data['product_id'], $data['rating'], $data['comment'])) {
            return null; // Required fields are missing
        }

        if (!is_numeric($data['user_id']) || !is_numeric($data['product_id'])) {
            return null;  // Invalid IDs
        }
        if (!is_int($data['rating']) || $data['rating'] < 1 || $data['rating'] > 5) {
            return null; // Rating must be an integer between 1 and 5
        }

        $review = [
            'id' => $this->nextReviewId++,
            'user_id' => $data['user_id'],
            'product_id' => $data['product_id'],
            'rating' => $data['rating'],
            'comment' => $data['comment'],
            'created_at' => time(),
            'updated_at' => time()
        ];

        $this->reviews[] = $review;

        return $review;
    }

    public function getReview(int $id)
    {
        foreach ($this->reviews as $review) {
            if ($review['id'] == $id) {
                return $review;
            }
        }

        return null; // Review not found
    }

    public function updateReview(int $id, array $data)
    {
        $review = $this->getReview($id);

        if (!$review) {
            return null; // Review not found
        }

        // Validate the update data
        if (!isset($data['rating'], $data['comment'])) {
            return null; //Required fields are missing
        }

        if (!is_int($data['rating']) || $data['rating'] < 1 || $data['rating'] > 5) {
            return null; // Invalid rating
        }
        
        $review['rating'] = $data['rating'];
        $review['comment'] = $data['comment'];
        $review['updated_at'] = time();

        return $review;
    }

    public function deleteReview(int $id)
    {
        foreach ($this->reviews as $key => $review) {
            if ($review['id'] == $id) {
                unset($this->reviews[$key]);
                return true; // Successfully deleted
            }
        }

        return false; // Review not found
    }
}


// Example Usage:
$reviewSystem = new ReviewSystem();

// Create a new review
$review = $reviewSystem->createReview(['user_id' => 1, 'product_id' => 101, 'rating' => 5, 'comment' => 'Great product!']);

if ($review) {
    echo "New Review Created:
";
    print_r($review);
    echo "
";
}

// Retrieve a review by ID
$review = $reviewSystem->getReview(1);
if ($review) {
    echo "Review ID 1:
";
    print_r($review);
    echo "
";
}

// Update a review
$review = $reviewSystem->updateReview(1, ['rating' => 4, 'comment' => 'Good, but could be better.']);
if ($review) {
    echo "Review ID 1 Updated:
";
    print_r($review);
    echo "
";
}

// Delete a review
$reviewSystem->deleteReview(1);

// Try to retrieve the deleted review
$review = $reviewSystem->getReview(1);
if (!$review) {
    echo "Review ID 1 deleted successfully.
";
} else {
    echo "Review ID 1 still exists.
";
}

?>


<?php

/**
 * User Review Function
 *
 * This function allows you to save user reviews with various metadata.
 *
 * @param string $user_id      The ID of the user submitting the review.
 * @param string $product_id   The ID of the product being reviewed.
 * @param string $rating       The rating given by the user (e.g., 1-5).
 * @param string $comment      The user's written review.
 * @param string $timestamp   (Optional) Timestamp for the review. Defaults to current timestamp.
 *
 * @return bool|string True on success, error message string on failure.
 */
function saveUserReview(
    string $user_id,
    string $product_id,
    string $rating,
    string $comment,
    string $timestamp = null // Optional timestamp
) {
    // Input Validation (Important!)
    if (empty($user_id) || empty($product_id) || empty($rating) || empty($comment)) {
        return "Error: All fields (User ID, Product ID, Rating, Comment) are required.";
    }

    if (!is_numeric($rating) || intval($rating) < 1 || intval($rating) > 5) {
        return "Error: Rating must be a number between 1 and 5.";
    }

    // Sanitize inputs (Important for security - prevents XSS, SQL injection)
    $user_id = filter_var($user_id, FILTER_SANITIZE_STRING);
    $product_id = filter_var($product_id, FILTER_SANITIZE_STRING);
    $rating = intval($rating);  // Ensure rating is an integer
    $comment = filter_var($comment, FILTER_SANITIZE_STRING);

    // Database Interaction (Example - Replace with your actual database logic)
    try {
        // Assuming you have a database connection established
        // $db = new PDO(/* your database connection details */);
        // Example using a placeholder for demonstration - REPLACE with your connection

        $stmt = $db->prepare("INSERT INTO reviews (user_id, product_id, rating, comment, created_at) VALUES (:user_id, :product_id, :rating, :comment, :created_at)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment', $comment);
        $stmt->bindParam(':created_at', date('Y-m-d H:i:s')); //Using timestamp as string

        $result = $stmt->execute();

        if ($result) {
            return true; // Success
        } else {
            // Log the error for debugging
            error_log("Error saving review: " . print_r($stmt->errorInfo(), true));
            return "Error: Could not save review to database. Check error logs.";
        }

    } catch (PDOException $e) {
        // Handle database connection or query errors
        error_log("Database Error: " . $e->getMessage());
        return "Error: Database error.  Check database connection and query syntax.  " . $e->getMessage();
    }
}

// Example usage:
$user_id = "user123";
$product_id = "product456";
$rating = 4;
$comment = "Great product! Highly recommended.";

$result = saveUserReview($user_id, $product_id, $rating, $comment);

if ($result === true) {
    echo "Review saved successfully!";
} else {
    echo "Error: " . $result;
}
?>


<?php

/**
 * Function to handle user reviews.
 *
 * This function allows users to submit reviews, display existing reviews,
 * and handles simple moderation (e.g., flagging inappropriate content).
 *
 * @param string $review_text The review text submitted by the user.
 * @param int   $product_id  The ID of the product being reviewed.
 * @param array $review_data (Optional) Array of existing reviews for the product.
 *
 * @return array An array containing:
 *   - 'reviews': An array of existing reviews (if any).
 *   - 'new_review': The submitted review text.
 *   - 'error':  Error message if the review submission failed, otherwise null.
 */
function handleUserReview(string $review_text, int $product_id, array $review_data = []): array
{
    // Validation - Basic checks (can be expanded)
    if (empty($review_text)) {
        return ['reviews' => $review_data, 'new_review' => $review_text, 'error' => 'Review text cannot be empty.'];
    }

    //  Consider adding more robust validation here like:
    // - Length limits for review text
    // - Profanity filtering
    // -  Checking for malicious code

    // Add the new review to the existing data.
    $new_review = ['text' => $review_text, 'timestamp' => time()];
    $updated_reviews = array_merge($review_data, [$new_review]);

    return ['reviews' => $updated_reviews, 'new_review' => $review_text, 'error' => null];
}

// --- Example Usage ---

// Initialize some review data (simulating a database)
$productReviews = [
    ['text' => 'Great product!', 'timestamp' => 1678886400],
    ['text' => 'Could be better.', 'timestamp' => 1678886460]
];

// 1. Submit a new review:
$reviewText = 'Excellent value for the price.';
$result = handleUserReview($reviewText, 123); // Assuming product ID 123

if ($result['error'] === null) {
    echo "New Review Submitted:
";
    print_r($result['reviews']);
} else {
    echo "Error submitting review: " . $result['error'] . "
";
}

echo "
";


// 2. Submit another review:
$reviewText2 = 'This is fantastic!  I highly recommend it.';
$result2 = handleUserReview($reviewText2, 123);

if ($result2['error'] === null) {
    echo "New Review Submitted:
";
    print_r($result2['reviews']);
} else {
    echo "Error submitting review: " . $result2['error'] . "
";
}

echo "
";

// 3. Example of error handling:
$emptyReview = handleUserReview("", 456);
if ($emptyReview['error'] !== null) {
    echo "Error submitting empty review: " . $emptyReview['error'] . "
";
}

?>


<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or item.
 *
 * @param string $product_id The unique identifier for the product.
 * @param string $user_name The name of the user submitting the review.
 * @param string $review_text The text content of the review.
 * @param int $rating (optional) A rating from 1 to 5. Defaults to null.
 * @param string $timestamp (optional)  A timestamp for when the review was created. Defaults to current timestamp.
 *
 * @return bool True on successful submission, false on failure.
 */
function submit_review(string $product_id, string $user_name, string $review_text, int $rating = null, string $timestamp = null)
{
    // **Important Security Measures:**  Always validate and sanitize user input!
    // This is a basic example and needs significant improvement for production.

    // Sanitize input - VERY IMPORTANT
    $product_id = filter_var($product_id, FILTER_SANITIZE_STRING);
    $user_name = filter_var($user_name, FILTER_SANITIZE_STRING);
    $review_text = filter_var($review_text, FILTER_SANITIZE_STRING);

    // Validation - crucial to prevent abuse and errors
    if (empty($product_id) || empty($user_name) || empty($review_text)) {
        error_log("Review submission failed: Missing required fields.");
        return false;
    }

    if ($rating !== null && !is_numeric($rating) || $rating < 1 || $rating > 5) {
        error_log("Review submission failed: Invalid rating.");
        return false;
    }


    // **Data Storage - Replace with a database connection**
    // This is a placeholder - you'll need to connect to your database.
    $review_data = [
        'product_id' => $product_id,
        'user_name' => $user_name,
        'review_text' => $review_text,
        'rating' => $rating,
        'timestamp' => $timestamp ?? time(), // Use current timestamp if none provided
    ];

    // **Example:  Simulated database insertion (replace with your database code)**
    // In a real application, you would use a database query.
    // For demonstration purposes, we'll just store it in a simple array.
    $reviews = json_encode($review_data); //Encode for storage if needed
    file_put_contents('reviews.json', $reviews . "
", FILE_APPEND); // Append to a file

    //Success
    error_log("Review submitted successfully for product ID: " . $product_id);
    return true;
}



/**
 * Get Reviews for a Product
 *
 * Retrieves all reviews for a given product.
 *
 * @param string $product_id The unique identifier for the product.
 * @return array|null An array of review objects, or null if no reviews are found.
 */
function get_reviews(string $product_id) {
    // **Important:  Fetch from your database here!**
    // This is just a placeholder - use your database connection.
    // Example (replace with your database query):
    $reviews = json_decode(file_get_contents('reviews.json'), true); //decode JSON

    //Filter reviews by product_id
    $filteredReviews = [];
    foreach($reviews as $review){
        if($review['product_id'] == $product_id){
            $filteredReviews[] = $review;
        }
    }

    return empty($filteredReviews) ? null : $filteredReviews;
}



/**
 * Display Reviews
 *
 * Displays reviews for a product.
 *
 * @param array $reviews An array of review objects.
 */
function display_reviews(array $reviews) {
    if (empty($reviews)) {
        echo "<p>No reviews yet.</p>";
        return;
    }

    echo "<h2>Reviews for Product ID: " . $product_id . "</h2>";
    echo "<ul>";
    foreach ($reviews as $review) {
        echo "<li>";
        echo "<strong>User:</strong> " . htmlspecialchars($review['user_name']) . "<br>";
        echo "<strong>Rating:</strong> " . $review['rating'] . " / 5<br>";
        echo "<strong>Review:</strong> " . htmlspecialchars($review['review_text']) . "<br>";
        echo "<strong>Timestamp:</strong> " . date("Y-m-d H:i:s", $review['timestamp']) . "</li>";
    }
    echo "</ul>";
}


// Example Usage (For testing purposes):
$product_id = "12345";

// Submit a review
if (submit_review($product_id, "John Doe", "This is a great product!", 5)) {
    echo "<p>Review submitted successfully!</p>";
} else {
    echo "<p>Review submission failed.</p>";
}

// Get and display reviews
$reviews = get_reviews($product_id);
display_reviews($reviews);

?>


<?php

/**
 * User Review Class
 * 
 * This class provides functionality to create, store, and retrieve user reviews.
 */
class UserReview {

    private $reviewId;
    private $userId;
    private $productId;
    private $rating;
    private $comment;
    private $timestamp;

    /**
     * Constructor
     *
     * @param int $userId      The ID of the user submitting the review.
     * @param int $productId   The ID of the product being reviewed.
     * @param int $rating       The rating given by the user (e.g., 1-5).
     * @param string $comment   The user's comment about the product.
     */
    public function __construct($userId, $productId, $rating, $comment) {
        $this->userId = $userId;
        $this->productId = $productId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->timestamp = time(); // Get the current timestamp
    }

    /**
     * Getters
     */
    public function getReviewId() {
        return $this->reviewId;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getProductId() {
        return $this->productId;
    }

    public function getRating() {
        return $this->rating;
    }

    public function getComment() {
        return $this->comment;
    }

    public function getTimestamp() {
        return $this->timestamp;
    }


    /**
     * Convert review data into an associative array for easy display/export.
     *
     * @return array An associative array containing the review data.
     */
    public function toArray() {
        return [
            'reviewId' => $this->getReviewId(),
            'userId' => $this->getUserId(),
            'productId' => $this->getProductId(),
            'rating' => $this->getRating(),
            'comment' => $this->getComment(),
            'timestamp' => $this->getTimestamp()
        ];
    }

    /**
     *  This is a basic example.  In a real application, you'd store this data in a database.
     *  This demonstrates how to store the review data.
     *  @param string $dbConnection The database connection string.  Replace with your connection details.
     */
    public function save($dbConnection) {
        // This is a placeholder.  In a real application, you would use a database query to save the review.
        // Example using PDO:
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("INSERT INTO reviews (userId, productId, rating, comment, timestamp) 
                                    VALUES (:userId, :productId, :rating, :comment, :timestamp)");
            $stmt->bindParam(':userId', $this->userId);
            $stmt->bindParam(':productId', $this->productId);
            $stmt->bindParam(':rating', $this->rating);
            $stmt->bindParam(':comment', $this->comment);
            $stmt->bindParam(':timestamp', $this->timestamp);

            $stmt->execute();
            $this->reviewId = $pdo->lastInsertId();
        } catch (PDOException $e) {
            // Handle database errors here.  Log the error, display a user-friendly message, etc.
            echo "Database error: " . $e->getMessage();
        }
    }

}



/**
 * User Review Review Function (Example Usage)
 */
function displayReview($review) {
    if ($review) {
        echo "<h2>Review for Product ID: " . $review->getProductId() . "</h2>";
        echo "<p><strong>User:</strong> " . $review->getUserId() . "</p>";
        echo "<p><strong>Rating:</strong> " . $review->getRating() . "</p>";
        echo "<p><strong>Comment:</strong> " . $review->getComment() . "</p>";
        echo "<p><strong>Date:</strong> " . date("Y-m-d H:i:s", $review->getTimestamp()) . "</p>";
    } else {
        echo "<p>No reviews found.</p>";
    }
}



// Example usage:
$review1 = new UserReview(123, 456, 5, "Excellent product, highly recommended!");
$review1->save("localhost", "root", "password"); // Replace with your DB details.
displayReview($review1);

$review2 = new UserReview(456, 456, 3, "Okay, but could be better.");
$review2->save("localhost", "root", "password"); // Replace with your DB details.
displayReview($review2);

?>


<?php

/**
 * User Review Function
 *
 * This function allows you to generate a simple user review display based on
 * a list of reviews.  It provides basic formatting for display.
 *
 * @param array $reviews An array of review objects.  Each object should have:
 *                       - 'user': The username of the reviewer.
 *                       - 'rating': The rating (e.g., 1-5).
 *                       - 'comment': The review text.
 * @param int $limit The maximum number of reviews to display. Defaults to 5.
 *
 * @return string HTML markup for displaying the reviews.
 */
function displayUserReviews(array $reviews, $limit = 5)
{
  if (empty($reviews)) {
    return "<p>No reviews yet.</p>";
  }

  $reviewsToDisplay = array_slice($reviews, min($limit, count($reviews))); // Limit the number of reviews

  $html = '<div class="user-reviews">';
  foreach ($reviewsToDisplay as $review) {
    $html .= '<div class="review">';
    $html .= '<p class="user"><strong>' . htmlspecialchars($review['user']) . '</strong></p>';
    $html .= '<p class="rating">Rating: ' . $review['rating'] . '</p>';
    $html .= '<p class="comment">' . nl2br(htmlspecialchars($review['comment'])) . '</p>'; // nl2br for line breaks
    $html .= '</div>';
  }
  $html .= '</div>';

  return $html;
}


/**
 * Example Usage (Demonstration)
 */

// Sample Reviews
$reviews = [
  ['user' => 'Alice', 'rating' => 5, 'comment' => "This product is amazing! I highly recommend it."],
  ['user' => 'Bob', 'rating' => 4, 'comment' => "Good product, but could be a little better."],
  ['user' => 'Charlie', 'rating' => 3, 'comment' => "It's okay.  Nothing special."],
  ['user' => 'David', 'rating' => 5, 'comment' => "Excellent value for the price."],
  ['user' => 'Eve', 'rating' => 2, 'comment' => "Not what I expected."],
  ['user' => 'Frank', 'rating' => 4, 'comment' => "A solid choice."],
  ['user' => 'Grace', 'rating' => 1, 'comment' => "Very disappointing."],
];


// Display the first 3 reviews
$reviewHTML = displayUserReviews($reviews, 3);
echo $reviewHTML;
?>


   $reviewHTML = displayUserReviews($yourReviewsArray, $maximumNumberOfReviews);
   echo $reviewHTML;
   
   * Replace `$yourReviewsArray` with the array of review objects you have.
   * Adjust the `$maximumNumberOfReviews` if you want to show more or fewer reviews.
3. **Style with CSS:** Add CSS rules to style the `user-reviews`, `review`, `user`, `rating`, and `comment` classes to customize the appearance of the reviews.

Example CSS:



<?php

/**
 * Reviews Class
 *
 * This class allows you to manage user reviews for a specific item (e.g., a product, movie, etc.).
 * It provides functionality to add, display, and potentially moderate reviews.
 */
class Reviews {

    private $reviews = []; // Array to store reviews
    private $db = null; // Database connection (optional)

    /**
     * Constructor
     *
     * Initializes the Reviews class.  Allows for database connection setup.
     *
     * @param PDO $db (Optional) PDO database connection object.
     */
    public function __construct(PDO $db = null) {
        $this->db = $db;
    }

    /**
     * Add a Review
     *
     * Adds a new review to the $reviews array.
     *
     * @param int $itemId The ID of the item the review is for.
     * @param string $user The name or ID of the user submitting the review.
     * @param string $comment The review text.
     * @return bool True on success, false on failure (e.g., invalid data).
     */
    public function addReview(int $itemId, string $user, string $comment) {
        // Basic validation - improve this for production
        if (!is_numeric($itemId)) {
            return false;
        }
        if (empty($user)) {
            return false;
        }
        if (empty($comment)) {
            return false;
        }

        $this->reviews[] = [
            'itemId' => $itemId,
            'user' => $user,
            'comment' => $comment,
            'date' => date('Y-m-d H:i:s') // Add a timestamp
        ];
        return true;
    }

    /**
     * Get All Reviews for an Item
     *
     * Retrieves all reviews associated with a specific item ID.
     *
     * @param int $itemId The ID of the item.
     * @return array An array of review objects, or an empty array if no reviews exist.
     */
    public function getReviewsForItem(int $itemId) {
        //Filtering based on item ID
        $reviews = [];
        foreach($this->reviews as $review) {
            if ($review['itemId'] == $itemId) {
                $reviews[] = $review;
            }
        }
        return $reviews;
    }

    /**
     * Display Reviews
     *
     * Formats and displays the reviews.
     *
     * @return string  A formatted string containing the reviews.
     */
    public function displayReviews() {
        $output = "<h2>Reviews for Item ID: " . implode(",", $this->getReviewsForItem($this->getLatestItemId())) . "</h2>";
        $reviews = $this->getReviewsForItem($this->getLatestItemId());

        if (empty($reviews)) {
            $output .= "<p>No reviews yet.</p>";
        } else {
            $output .= "<ul>";
            foreach ($reviews as $review) {
                $output .= "<li><strong>User:</strong> " . htmlspecialchars($review['user']) . "<br>";
                $output .= "<em>Rating:</em> " . htmlspecialchars($review['comment']) . "<br>";
                $output .= "<em>Date:</em> " . htmlspecialchars($review['date']) . "</li>";
            }
            $output .= "</ul>";
        }
        return $output;
    }

    /**
     *  Helper to get the latest itemId to retrieve reviews from
     */
    private function getLatestItemId(){
        if (empty($this->reviews)) return 0;
        return array_key_last($this->reviews, 'itemId');
    }
}

// --- Example Usage ---
// Create a database connection (replace with your actual credentials)
$db = new PDO('mysql:host=localhost;dbname=my_reviews_db', 'user', 'password');


// Create a Reviews object
$reviews = new Reviews($db);

// Add some reviews
$reviews->addReview(1, "John Doe", "Great product! I love it.");
$reviews->addReview(1, "Jane Smith", "Could be better, but good overall.");
$reviews->addReview(2, "Peter Jones", "Excellent value for money.");

// Get reviews for item 1
$reviewsForItem1 = $reviews->getReviewsForItem(1);

// Display the reviews
echo $reviews->displayReviews();

?>


<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a given product.
 *
 * @param string $productId The unique identifier of the product.
 * @param string $productName The name of the product.
 * @param string $reviewerName The name of the reviewer.
 * @param string $reviewText The review text.
 * @param array $reviews An optional array of existing reviews to display.
 *
 * @return array An array containing the existing reviews and the new review.
 */
function createAndDisplayReviews(string $productId, string $productName, string $reviewerName, string $reviewText, array &$reviews = []) {

  // Validate input (basic) - Enhance as needed for production environments
  if (empty($productId) || empty($productName) || empty($reviewerName) || empty($reviewText)) {
    return ['error' => 'All fields are required.'];
  }

  // Create the new review
  $newReview = [
    'productId' => $productId,
    'reviewerName' => $reviewerName,
    'reviewText' => $reviewText,
    'timestamp' => date('Y-m-d H:i:s')  // Add a timestamp for each review
  ];

  // Add the new review to the array
  $reviews[] = $newReview;

  // Sort reviews by timestamp (most recent first) - optional
  usort($reviews, function($a, $b) {
    return $b['timestamp'] <=> $a['timestamp'];
  });

  return $reviews;
}


// --- Example Usage ---

// Example 1:  Creating a new review
$productID = '12345';
$productName = 'Awesome Gadget';
$reviewer = 'John Doe';
$review = 'This gadget is fantastic!  I love it.';

$updatedReviews = createAndDisplayReviews($productID, $productName, $reviewer, $review);

if (isset($updatedReviews['error'])) {
  echo "Error: " . $updatedReviews['error'] . "<br>";
} else {
  echo "Product: " . $productName . "<br>";
  echo "Reviews:<br>";
  foreach ($updatedReviews as $review) {
    echo "Reviewer: " . $review['reviewerName'] . "<br>";
    echo "Review Text: " . $review['reviewText'] . "<br>";
    echo "Timestamp: " . $review['timestamp'] . "<br><br>";
  }
}


echo "<br>--- Example 2: Empty Review ---";
$productID = '67890';
$productName = 'Another Product';
$reviewer = 'Jane Smith';
$review = ''; // Empty review

$updatedReviews = createAndDisplayReviews($productID, $productName, $reviewer, $review);

if (isset($updatedReviews['error'])) {
  echo "Error: " . $updatedReviews['error'] . "<br>";
} else {
  echo "Product: " . $productName . "<br>";
  echo "Reviews:<br>";
  foreach ($updatedReviews as $review) {
    echo "Reviewer: " . $review['reviewerName'] . "<br>";
    echo "Review Text: " . $review['reviewText'] . "<br>";
    echo "Timestamp: " . $review['timestamp'] . "<br><br>";
  }
}
?>


<?php

/**
 * User Review Function
 *
 * This function allows you to create and display user reviews for a product or item.
 * It handles data validation, database interaction (if needed), and basic display.
 *
 * @param string $productId  The ID of the product/item being reviewed.
 * @param string $username   The username of the user submitting the review.
 * @param string $rating    The rating given by the user (e.g., 1-5).
 * @param string $comment    The user's review comment.
 * @param PDO    $pdo      Optional PDO database connection object.  If not provided, assumes a global $db connection.
 *
 * @return array  An array containing:
 *               - 'success': True if the review was successfully created, false otherwise.
 *               - 'message': A message indicating success or the error message.
 */
function createReview($productId, $username, $rating, $comment, $pdo = null) {
  // Data Validation
  if (empty($productId)) {
    return ['success' => false, 'message' => 'Product ID cannot be empty.'];
  }

  if (empty($username)) {
    return ['success' => false, 'message' => 'Username cannot be empty.'];
  }

  if (empty($rating)) {
    return ['success' => false, 'message' => 'Rating cannot be empty.'];
  }

  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    return ['success' => false, 'message' => 'Rating must be a number between 1 and 5.'];
  }

  if (empty($comment)) {
    return ['success' => false, 'message' => 'Comment cannot be empty.'];
  }

  // Database Interaction (using PDO - best practice)
  try {
    $sql = "INSERT INTO reviews (product_id, username, rating, comment) VALUES (:product_id, :username, :rating, :comment)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':product_id', $productId);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':comment', $comment);
    $stmt->execute();

    return ['success' => true, 'message' => 'Review created successfully!'];

  } catch (PDOException $e) {
    // Handle database errors gracefully -  Don't expose the full error to the user.
    return ['success' => false, 'message' => 'Error creating review: ' . $e->getMessage()];
  }
}


/**
 * Displays a single review.
 *
 * @param array $review  An array representing a single review (e.g., returned by createReview).
 */
function displayReview($review) {
  if ($review['success']) {
    echo "<p><strong>Rating:</strong> " . $review['message'] . "</p>";
  } else {
    echo "<p style='color:red;'>Error: " . $review['message'] . "</p>";
  }
}


/**
 * Example Usage (For demonstration purposes)
 */
// Example 1: Successful Review
$reviewData = createReview('123', 'JohnDoe', 4, 'Great product! I highly recommend it.', $db);
displayReview($reviewData);

// Example 2:  Invalid Rating
$reviewData = createReview('456', 'JaneSmith', 6, 'Awesome!', $db);
displayReview($reviewData);

// Example 3:  Empty Comment
$reviewData = createReview('789', 'PeterPan', 3, '', $db);
displayReview($reviewData);

?>


<?php

/**
 * User Review Function
 *
 * This function allows you to store user reviews with basic validation and formatting.
 *
 * @param string $username The name of the user submitting the review.
 * @param string $review_text The text of the review.
 * @param int $product_id The ID of the product the review is for.
 *
 * @return bool True if the review was successfully saved, false otherwise.
 */
function saveReview(string $username, string $review_text, int $product_id)
{
    // Validation
    if (empty($username)) {
        error_log("Error: Username cannot be empty."); // Log error for admin review
        return false;
    }

    if (empty($review_text)) {
        error_log("Error: Review text cannot be empty."); // Log error for admin review
        return false;
    }

    if (!is_numeric($product_id)) {
        error_log("Error: Product ID must be a number."); // Log error for admin review
        return false;
    }

    // Sanitize the review text (optional, but recommended)
    $review_text = htmlspecialchars($review_text, ENT_QUOTES, 'UTF-8');

    // Store the review (replace with your database interaction code)
    // This is a placeholder - you'll need to connect to your database
    // and insert the review data.

    $db_connection = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");

    try {
        $stmt = $db_connection->prepare("INSERT INTO reviews (username, review_text, product_id) VALUES (:username, :review_text, :product_id)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':review_text', $review_text);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
        return true;

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());  // Log for debugging
        return false;
    }

}


// Example Usage (to demonstrate the function)
// Save a review:
$username = "John Doe";
$review_text = "This product is amazing!  I love it!";
$product_id = 123;

if (saveReview($username, $review_text, $product_id)) {
    echo "Review saved successfully!";
} else {
    echo "Failed to save review.";
}


// ---  Example of displaying a review (assuming a simple HTML display) ---
//  This is just for demonstration purposes.  You'll need to adapt this
//  to match your application's styling and database structure.

function displayReview(int $review_id) {
    // Retrieve review details from the database
    $db_connection = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");
    $stmt = $db_connection->prepare("SELECT username, review_text, product_id FROM reviews WHERE id = :id");
    $stmt->bindParam(':id', $review_id);
    $stmt->execute();
    $review = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($review) {
        echo "<div class='review'>";
        echo "<strong>Username:</strong> " . htmlspecialchars($review['username']) . "<br>"; //Sanitize output too!
        echo "<strong>Review:</strong> " . htmlspecialchars($review['review_text']) . "<br>";
        echo "Product ID: " . $review['product_id'];
        echo "</div>";
    } else {
        echo "<p>No review found for this ID.</p>";
    }
}

// Example of displaying a review with ID 1:
displayReview(1);
?>


<?php

/**
 * User Review Function
 *
 * This function handles adding, retrieving, and displaying user reviews.
 * It includes basic validation and error handling.
 */
class UserReview
{
    private $db; // Database connection object (replace with your actual DB connection)

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Adds a new user review to the database.
     *
     * @param int $productId The ID of the product the review is for.
     * @param string $username The username of the reviewer.
     * @param string $rating The rating (e.g., 1-5).
     * @param string $comment The user's review comment.
     *
     * @return bool True on success, false on failure.
     */
    public function addReview($productId, $username, $rating, $comment)
    {
        // Validate inputs - Add more robust validation as needed
        if (!$productId || !$username || !$rating || !$comment) {
            return false;
        }
        if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
            return false;
        }

        // Sanitize inputs (important to prevent SQL injection)
        $productId = (int)$productId; // Ensure productId is an integer
        $username = trim($username);
        $rating = (int)$rating;
        $comment = trim($comment);


        // Prepare SQL query (using prepared statements - VERY IMPORTANT)
        $sql = "INSERT INTO reviews (product_id, username, rating, comment) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql); //Prepare the statement

        if (!$stmt) {
            return false; //Handle error
        }

        // Bind parameters (prevent SQL injection)
        $stmt->bind_param("sii", $username, $rating, $comment);

        // Execute the query
        if (!$stmt->execute()) {
            error_log("Error adding review: " . $stmt->error); //Log the error for debugging
            return false;
        }

        // Close statement
        $stmt->close();

        return true;
    }

    /**
     * Retrieves all reviews for a specific product.
     *
     * @param int $productId The ID of the product.
     *
     * @return array An array of review objects, or an empty array if no reviews are found.
     */
    public function getReviewsByProduct($productId)
    {
        if (!is_numeric($productId)) {
            return [];
        }

        $productId = (int)$productId;

        $sql = "SELECT * FROM reviews WHERE product_id = ?";
        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("i", $productId);

        if (!$stmt->execute()) {
            error_log("Error fetching reviews: " . $stmt->error);
            return [];
        }

        $result = $stmt->get_result();
        $reviews = [];

        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }

        $stmt->close();
        return $reviews;
    }

    /**
     * Displays reviews for a product.
     *
     * @param array $reviews An array of review objects.
     *
     * @return string HTML representation of the reviews.
     */
    public function displayReviews(array $reviews)
    {
        if (empty($reviews)) {
            return "<p>No reviews yet.</p>";
        }

        $html = "<ul>";
        foreach ($reviews as $review) {
            $html .= "<li>";
            $html .= "<strong>" . $review['username'] . "</strong> - Rating: " . $review['rating'] . " - " . $review['comment'];
            $html .= "</li>";
        }
        $html .= "</ul>";
        return $html;
    }
}


// Example Usage (replace with your database connection)
// Assuming you have a database connection object called $db

// Create an instance of the UserReview class
$reviewManager = new UserReview($db);

// Add a review
if ($reviewManager->addReview(123, "JohnDoe", 4, "Great product!")) {
    echo "Review added successfully!";
} else {
    echo "Error adding review.";
}

// Get reviews for product ID 123
$reviews = $reviewManager->getReviewsByProduct(123);

// Display the reviews
echo $reviewManager->displayReviews($reviews);

?>


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


<?php

/**
 * User Review Function - Basic Implementation
 *
 * This function takes a user ID, a review text, and an optional rating,
 * and saves the review to a database.
 *
 * @param int    $userId      The ID of the user submitting the review.
 * @param string $reviewText  The text of the review.
 * @param int    $rating      (Optional) The rating given for the review (e.g., 1-5).
 *                           Defaults to 0.
 * @return bool  True if the review was successfully saved, false otherwise.
 */
function saveUserReview(int $userId, string $reviewText, int $rating = 0) {
    // --- Database Connection (Replace with your actual connection details) ---
    // This is a placeholder.  Use PDO, MySQLi, or another database connector.
    $dbHost = 'localhost';
    $dbName = 'your_database_name';
    $dbUser = 'your_username';
    $dbPassword = 'your_password';

    try {
        $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        error_log("Database connection error: " . $e->getMessage()); // Log the error
        return false;
    }

    // --- Input Validation & Sanitization (IMPORTANT!) ---
    // Validate the input to prevent SQL injection and other issues.
    $reviewText = trim($reviewText); // Remove leading/trailing whitespace
    if (empty($reviewText)) {
        error_log("Empty review text.");  //Log empty review
        return false;
    }
    $rating = (int)$rating; // Ensure rating is an integer.
    if ($rating < 1 || $rating > 5) {
        error_log("Invalid rating ($rating). Must be between 1 and 5.");
        return false;
    }


    // --- SQL Query ---
    $sql = "INSERT INTO reviews (user_id, review_text, rating) VALUES (:userId, :reviewText, :rating)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':userId', $userId);
    $stmt->bindParam(':reviewText', $reviewText);
    $stmt->bindParam(':rating', $rating);

    try {
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        error_log("Database error saving review: " . $e->getMessage());
        return false;
    }
}

// --- Example Usage ---
// You would typically get this data from a form submission.

// Example 1: Successful save
$userId = 123;
$review = "This product is amazing!  I highly recommend it.";
$rating = 5;

if (saveUserReview($userId, $review, $rating)) {
    echo "Review saved successfully!";
} else {
    echo "Failed to save review.";
}

// Example 2: Save with default rating
$userId = 456;
$review = "Great service!";
if (saveUserReview($userId, $review)) {
    echo "Review saved successfully (default rating)!";
} else {
    echo "Failed to save review (default rating).";
}


//  ---  Dummy Review Table Schema (for testing) ---
/*
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    review_text TEXT NOT NULL,
    rating INT NOT NULL
);
*/
?>


<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a given product.
 * It includes basic validation and data sanitization.
 *
 * @param string $product_id The unique identifier of the product.
 * @param string $username The username of the reviewer.
 * @param string $rating  The rating given by the user (e.g., 1-5).
 * @param string $comment The user's review comment.
 *
 * @return bool True on success, false on failure.
 */
function storeUserReview(string $product_id, string $username, string $rating, string $comment): bool
{
    // Input validation and sanitization
    if (empty($product_id) || empty($username) || empty($rating) || empty($comment)) {
        error_log("Error: Product ID, username, rating, and comment cannot be empty.");
        return false;
    }

    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        error_log("Error: Username must only contain alphanumeric characters and underscores.");
        return false;
    }

    if (!preg_match('/^[1-5][0-9]*$/', $rating)) {
        error_log("Error: Rating must be a number between 1 and 5.");
        return false;
    }

    if (strlen($comment) > 500) { // Limit comment length
        error_log("Error: Comment exceeds the maximum length (500 characters).");
        return false;
    }


    // Database interaction (example using a simple array for demonstration)
    $review = [
        'product_id' => $product_id,
        'username' => $username,
        'rating' => $rating,
        'comment' => $comment,
        'date' => date('Y-m-d H:i:s'), // Add timestamp for better organization
    ];

    // You would typically replace this with your database insertion logic
    $reviews = loadReviewsFromDatabase($product_id); // Assuming a function to load reviews
    $reviews[] = $review;

    // Save the review to the database (replace with your actual database insertion)
    if (!saveReviewToDatabase($review)) {
        error_log("Error: Failed to save review to database.");
        return false;
    }

    return true;
}


/**
 * Placeholder functions for database interaction
 */
function loadReviewsFromDatabase(string $product_id) {
    //  In a real application, this would query the database
    //  and return the existing reviews for that product.
    return [];
}

function saveReviewToDatabase(array $review) {
    // In a real application, this would insert the review into the database.
    //  This is a placeholder to demonstrate the integration.
    //  You would use your database connection and query to insert the review.
    //  For example:
    //  $db = new DatabaseConnection();
    //  $stmt = $db->prepare("INSERT INTO reviews (product_id, username, rating, comment, date) VALUES (?, ?, ?, ?, ?)");
    //  $stmt->bind_param("ssisss", $review['product_id'], $review['username'], $review['rating'], $review['comment'], $review['date']);
    //  $stmt->execute();
    //  $stmt->close();
    return true; // Placeholder, always return true
}


// Example Usage
$productId = "P123";
$reviewerName = "JohnDoe";
$rating = 4;
$commentText = "Great product!  I would definitely recommend it.";

if (storeUserReview($productId, $reviewerName, $rating, $commentText)) {
    echo "Review submitted successfully!";
} else {
    echo "Review submission failed. Check the error log for details.";
}

?>


<?php

/**
 * User Review Function
 *
 * This function allows users to submit and view user reviews for a product or service.
 *
 * @param string $productName The name of the product or service being reviewed.
 * @param string $reviewText The text of the review.
 * @param string $username  The username of the user submitting the review.
 * @param array  $reviews (Optional) An array of existing reviews.  Defaults to an empty array.
 *
 * @return array An array containing the updated reviews array with the new review added.
 */
function add_review(string $productName, string $reviewText, string $username, array &$reviews = []): array {
  // Basic input validation - Add more robust validation as needed for production.
  if (empty($productName)) {
    return $reviews; // Return existing reviews if product name is empty.
  }
  if (empty($reviewText)) {
    return $reviews; // Return existing reviews if review text is empty.
  }

  // Create a new review object
  $review = [
    'username' => $username,
    'review'   => $reviewText,
    'date'     => date("Y-m-d H:i:s") // Timestamp for date
  ];

  // Add the review to the array
  $reviews[] = $review;

  return $reviews;
}

// --- Example Usage and Demonstration ---

// Initialize an empty array to store reviews. Note the use of &$reviews
// to modify the array directly, avoiding unnecessary copying.
$product = "Awesome Gadget X";
$reviews = [];

// Add some initial reviews
$reviews = add_review($product, "This gadget is fantastic!  Highly recommended.", "JohnDoe");
$reviews = add_review($product, "Great value for the money.", "JaneSmith");
$reviews = add_review($product, "Could be better, but it works.", "PeterJones");

// Display the reviews
echo "<h2>Reviews for " . $product . "</h2>";
if (empty($reviews)) {
  echo "<p>No reviews yet.</p>";
} else {
  echo "<ul>";
  foreach ($reviews as $review) {
    echo "<li><strong>" . $review['username'] . ":</strong> " . $review['review'] . " - " . $review['date'] . "</li>";
  }
  echo "</ul>";
}

// Example of adding a new review
$reviews = add_review($product, "Amazing features and excellent customer support!", "AliceBrown");


<?php

/**
 * User Review Function
 * 
 * This function allows you to store and display user reviews for a product.
 * It includes basic validation and data sanitization.
 *
 * @param string $product_id The ID of the product the review is for.
 * @param string $user_name The name of the user submitting the review.
 * @param string $review_text The text of the review.
 * @param string $rating  The rating given by the user (e.g., 1-5).
 * 
 * @return array  An array containing review data if successful, or an error message if not.
 */
function createReview(string $product_id, string $user_name, string $review_text, string $rating) {
  // Validate inputs
  if (empty($product_id) || empty($user_name) || empty($review_text) || empty($rating)) {
    return ['error' => 'All fields are required.'];
  }

  // Sanitize inputs (basic - more robust sanitization needed for production)
  $product_id = filter_var($product_id, FILTER_SANITIZE_STRING);
  $user_name = filter_var($user_name, FILTER_SANITIZE_STRING);
  $review_text = filter_var($review_text, FILTER_SANITIZE_STRING);
  $rating = filter_var($rating, FILTER_SANITIZE_NUMBER_INT, FILTER_VALIDATE_INT); // Ensure rating is an integer

  if ($rating === false) {
    return ['error' => 'Invalid rating. Please enter a number between 1 and 5.'];
  }
  if ($rating < 1 || $rating > 5) {
    return ['error' => 'Invalid rating. Please enter a number between 1 and 5.'];
  }

  // Prepare review data (For database storage - adjust to your database structure)
  $review_data = [
    'product_id' => $product_id,
    'user_name' => $user_name,
    'review_text' => $review_text,
    'rating' => $rating,
    'created_at' => date('Y-m-d H:i:s') // Add timestamp for tracking
  ];

  // In a real application, you would insert $review_data into a database.
  // For this example, we just return the data.

  // Example of how you might save to the database:
  // $db = new DatabaseConnection();
  // $result = $db->insert('reviews', $review_data);

  return $review_data;
}


/**
 * Display User Reviews
 *
 *  Fetches and displays user reviews based on product_id
 *
 * @param string $product_id The product ID to fetch reviews for.
 *
 * @return array  An array of review data, or an error message if no reviews found.
 */
function displayReviews(string $product_id) {
    // In a real application, you would query your database here.
    // This is a placeholder for demonstration.

    // Mock database retrieval
    if ($product_id == "123") {
        $reviews = [
            ['product_id' => '123', 'user_name' => 'John Doe', 'review_text' => 'Great product! Highly recommended.', 'rating' => 5, 'created_at' => '2023-10-27 10:00:00'],
            ['product_id' => '123', 'user_name' => 'Jane Smith', 'review_text' => 'Good value for money.', 'rating' => 4, 'created_at' => '2023-10-26 14:30:00']
        ];
        return $reviews;
    } else {
      return ['error' => 'No reviews found for this product.'];
    }
}


// Example usage:
// Creating a review
$reviewData = createReview('123', 'Alice Brown', 'This is a fantastic product!', '5');

if (isset($reviewData['error'])) {
    echo "Error: " . $reviewData['error'] . "<br>";
} else {
    echo "Review created successfully!<br>";
    print_r($reviewData); // Display the review data
}


// Displaying reviews
$reviews = displayReviews('123');

if (isset($reviews['error'])) {
    echo "Error: " . $reviews['error'] . "<br>";
} else {
    echo "<h2>Reviews for Product 123:</h2>";
    echo "<ul>";
    foreach ($reviews as $review) {
        echo "<li><strong>User:</strong> " . $review['user_name'] . "<br>";
        echo "<strong>Rating:</strong> " . $review['rating'] . "<br>";
        echo "<strong>Review:</strong> " . $review['review_text'] . "<br>";
        echo "<strong>Date:</strong> " . $review['created_at'] . "</li>";
    }
    echo "</ul>";
}
?>


<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a given product or item.
 *
 * @param string $productId The unique identifier for the product or item.
 * @param string $userName The name of the user submitting the review.
 * @param string $reviewText The text content of the review.
 * @param array $reviews  (Optional) An array of existing reviews.  If not provided, an empty array is created.
 * @return array An array containing the updated array of reviews, with the new review added.
 */
function addReview(string $productId, string $userName, string $reviewText, array $reviews = []): array
{
  // Input validation - basic checks, can be expanded
  if (empty($productId)) {
    throw new InvalidArgumentException("Product ID cannot be empty.");
  }
  if (empty($userName)) {
    throw new InvalidArgumentException("User name cannot be empty.");
  }
  if (empty($reviewText)) {
    throw new InvalidArgumentException("Review text cannot be empty.");
  }

  // Create a new review object.  You might want to use a more sophisticated object.
  $review = [
    'productId' => $productId,
    'userName' => $userName,
    'reviewText' => $reviewText,
    'timestamp' => time() // Add a timestamp for organization
  ];

  // Add the review to the array
  $reviews[] = $review;

  return $reviews;
}


/**
 * Function to display reviews for a given product.
 *
 * @param string $productId The product ID.
 * @param array $reviews An array of review objects.
 */
function displayReviews(string $productId, array $reviews)
{
  echo "<h2>Reviews for Product ID: " . $productId . "</h2>";

  if (empty($reviews)) {
    echo "<p>No reviews yet.</p>";
    return;
  }

  echo "<ul>";
  foreach ($reviews as $review) {
    echo "<li>";
    echo "<strong>" . $review['userName'] . ":</strong> " . htmlspecialchars($review['reviewText']) . "<br>";
    echo "Timestamp: " . date('Y-m-d H:i:s', $review['timestamp']);
    echo "</li>";
  }
  echo "</ul>";
}



// --- Example Usage ---

// Initial Reviews (Start with an empty array)
$productReviews = [];

// Add some reviews
try {
    $productReviews = addReview("PROD123", "Alice", "Great product, highly recommended!");
    $productReviews = addReview("PROD123", "Bob", "Works as expected.", $productReviews);
    $productReviews = addReview("PROD456", "Charlie", "Could be better, but decent.", $productReviews);
} catch (InvalidArgumentException $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}


// Display the reviews
displayReviews("PROD123", $productReviews);
displayReviews("PROD456", $productReviews);
?>


<?php

/**
 * Reviews class for managing user reviews.
 */
class Reviews {

    private $reviews = [];

    /**
     * Adds a new review.
     *
     * @param string $user  The user who wrote the review.
     * @param string $comment The content of the review.
     * @return bool True if the review was added successfully, false otherwise.
     */
    public function addReview(string $user, string $comment) {
        $id = uniqid(); // Generate a unique ID for the review
        $this->reviews[$id] = [
            'user' => $user,
            'comment' => $comment,
            'date' => date('Y-m-d H:i:s') // Add timestamp for review date
        ];
        return true;
    }

    /**
     * Retrieves all reviews.
     *
     * @return array An array of all reviews.  Returns an empty array if none exist.
     */
    public function getAllReviews() {
        return $this->reviews;
    }

    /**
     * Retrieves a specific review by its unique ID.
     *
     * @param string $id The unique ID of the review.
     * @return array|null The review object if found, null otherwise.
     */
    public function getReviewById(string $id) {
        if (isset($this->reviews[$id])) {
            return $this->reviews[$id];
        }
        return null;
    }

    /**
     * Deletes a review by its unique ID.
     *
     * @param string $id The unique ID of the review to delete.
     * @return bool True if the review was deleted successfully, false otherwise.
     */
    public function deleteReview(string $id) {
        if (isset($this->reviews[$id])) {
            unset($this->reviews[$id]);
            return true;
        }
        return false;
    }

    /**
     * Updates an existing review.
     *
     * @param string $id The unique ID of the review to update.
     * @param string $newComment The new content of the review.
     * @return bool True if the review was updated successfully, false otherwise.
     */
    public function updateReview(string $id, string $newComment) {
        if (isset($this->reviews[$id])) {
            $this->reviews[$id]['comment'] = $newComment;
            return true;
        }
        return false;
    }
}


/**
 * Example Usage:
 */

// Instantiate the Reviews class
$reviews = new Reviews();

// Add some reviews
$reviews->addReview('John Doe', 'Great product! I highly recommend it.');
$reviews->addReview('Jane Smith', 'Could be better, but overall good.');
$reviews->addReview('Peter Jones', 'Excellent value for money.');

// Get all reviews
$allReviews = $reviews->getAllReviews();
echo "All Reviews:
";
print_r($allReviews);

// Get a specific review
$specificReview = $reviews->getReviewById('unique_id_1');
echo "
Specific Review (Unique ID: unique_id_1):
";
print_r($specificReview);

// Update a review
$reviews->updateReview('unique_id_1', 'Fantastic product, even better than expected!');

// Get the updated review
$updatedReview = $reviews->getReviewById('unique_id_1');
echo "
Updated Review:
";
print_r($updatedReview);

// Delete a review
$reviews->deleteReview('unique_id_2');

// Get all reviews again to see the deleted review is gone
$allReviews = $reviews->getAllReviews();
echo "
All Reviews after deletion:
";
print_r($allReviews);

?>


<?php

/**
 * User Review Function
 *
 * This function allows you to collect and display user reviews for a product or item.
 * It includes validation, sanitization, and basic display formatting.
 *
 * @param string $productId The unique identifier for the product/item.
 * @param array $reviews An array of review objects, each with:
 *                       - $name:  The user's name.
 *                       - $rating:  A number from 1 to 5.
 *                       - $comment: The user's review text.
 * @return string A formatted string containing the reviews.
 */
function displayUserReviews(string $productId, array $reviews): string
{
  // Validate input - basic check for review array
  if (empty($reviews)) {
    return "<p>No reviews yet.</p>";
  }

  $output = "<div class='user-reviews'>";
  $output .= "<h3>Reviews for Product ID: " . htmlspecialchars($productId) . "</h3>";

  foreach ($reviews as $review) {
    // Validate review data - more robust validation is recommended in production
    if (
      !is_string($review['name']) ||
      !is_int($review['rating']) ||
      !is_string($review['comment']) ||
      $review['rating'] < 1 || $review['rating'] > 5
    ) {
      continue; // Skip invalid reviews.  Log errors for debugging.
    }

    $output .= "<div class='review'>";
    $output .= "<p><strong>" . htmlspecialchars($review['name']) . ":</strong> " . htmlspecialchars($review['comment']) . "</p>";
    $output .= "<p>Rating: " . htmlspecialchars($review['rating']) . "</p>";
    $output .= "</div>";
  }

  $output .= "</div>";
  return $output;
}


// Example Usage:
// Simulate some reviews
$reviews = [
  [
    'name' => 'John Doe',
    'rating' => 4,
    'comment' => 'Great product!  Easy to use and works perfectly.',
  ],
  [
    'name' => 'Jane Smith',
    'rating' => 5,
    'comment' => 'Excellent value for the price.  Highly recommended!',
  ],
  [
    'name' => 'Peter Jones',
    'rating' => 3,
    'comment' => 'It was okay, but could be better. The instructions were unclear.',
  ],
];

// Display the reviews
$reviewOutput = displayUserReviews('PROD-123', $reviews);
echo $reviewOutput;

?>


<?php

/**
 * Class UserReview
 *
 * This class provides functionality for creating, retrieving, updating, and deleting user reviews.
 */
class UserReview
{
    private $db; // Database connection (implementation depends on your database)

    /**
     * Constructor
     *
     * Initializes the UserReview class with a database connection.
     *
     * @param PDO $db A PDO database connection object.
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Create a new review.
     *
     * @param int $productId The ID of the product the review is for.
     * @param string $user  The name or identifier of the user.
     * @param string $rating The rating given (e.g., 1-5).
     * @param string $comment The user's review comment.
     * @return int|null The ID of the newly created review, or null on failure.
     */
    public function createReview(int $productId, string $user, string $rating, string $comment)
    {
        // Validate inputs (add more robust validation here)
        if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
            return null;
        }

        $sql = "INSERT INTO reviews (product_id, user, rating, comment)
                VALUES (:product_id, :user, :rating, :comment)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':product_id', $productId);
        $stmt->bindParam(':user', $user);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment', $comment);

        try {
            $stmt->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            // Handle database errors appropriately (logging, error messages)
            error_log("Error creating review: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get a review by ID.
     *
     * @param int $reviewId The ID of the review to retrieve.
     * @return array|null An associative array containing the review data, or null if not found.
     */
    public function getReview(int $reviewId)
    {
        $sql = "SELECT * FROM reviews WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $reviewId);
        $stmt->execute();

        $review = $stmt->fetch(PDO::FETCH_ASSOC);
        return $review;
    }

    /**
     * Update an existing review.
     *
     * @param int $reviewId The ID of the review to update.
     * @param string $user  The new user name or identifier.
     * @param string $rating The new rating.
     * @param string $comment The new review comment.
     * @return bool True on success, false on failure.
     */
    public function updateReview(int $reviewId, string $user, string $rating, string $comment)
    {
        // Validate inputs (add more robust validation here)
        if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
            return false;
        }

        $sql = "UPDATE reviews
                SET user = :user,
                    rating = :rating,
                    comment = :comment
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $reviewId);
        $stmt->bindParam(':user', $user);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment', $comment);

        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log("Error updating review: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a review.
     *
     * @param int $reviewId The ID of the review to delete.
     * @return bool True on success, false on failure.
     */
    public function deleteReview(int $reviewId)
    {
        $sql = "DELETE FROM reviews WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $reviewId);

        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log("Error deleting review: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all reviews for a product.
     *
     * @param int $productId The ID of the product.
     * @return array An array of associative arrays, each representing a review.
     */
    public function getReviewsByProduct(int $productId)
    {
        $sql = "SELECT * FROM reviews WHERE product_id = :product_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':product_id', $productId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


// Example Usage (Illustrative - replace with your database connection)
// Assuming you have a PDO database connection object named $db

// Create a database connection (replace with your actual connection details)
$db = new PDO("mysql:host=localhost;dbname=your_database", "your_user", "your_password");


// Create a UserReview object
$reviewManager = new UserReview($db);

// Create a new review
$newReviewId = $reviewManager->createReview(1, "John Doe", 5, "Great product!");

if ($newReviewId) {
    echo "New review created with ID: " . $newReviewId . "
";
} else {
    echo "Failed to create review.
";
}

// Get the review by ID
$review = $reviewManager->getReview($newReviewId);
if ($review) {
    echo "Review:
";
    print_r($review);
} else {
    echo "Review not found.
";
}

// Get all reviews for product ID 1
$reviews = $reviewManager->getReviewsByProduct(1);
echo "
Reviews for product 1:
";
print_r($reviews);

//Update review - example
$updateResult = $reviewManager->updateReview($newReviewId, "Jane Smith", 4, "Improved!");
if ($updateResult) {
    echo "
Review updated successfully.
";
} else {
    echo "
Failed to update review.
";
}

//Delete review - example
$deleteResult = $reviewManager->deleteReview($newReviewId);
if ($deleteResult) {
    echo "
Review deleted successfully.
";
} else {
    echo "
Failed to delete review.
";
}
?>


<?php

/**
 * Function to create, retrieve, update, and delete user reviews.
 *
 * This function demonstrates a basic user review system. 
 * You'll likely want to adapt this for a real-world application 
 * with database integration, security measures, and more robust validation.
 */

class ReviewSystem {

    private $reviews = [];  // Store reviews (for simplicity, in memory)

    /**
     * Adds a new review.
     *
     * @param int $productId The ID of the product the review is for.
     * @param string $username The username of the reviewer.
     * @param string $rating The rating given (e.g., 1-5).
     * @param string $comment The user's review comment.
     * @return int|null The ID of the newly created review, or null on failure.
     */
    public function addReview(int $productId, string $username, string $rating, string $comment) {
        // Input Validation (VERY IMPORTANT!)
        if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
            return null; // Invalid rating
        }

        if (empty($username) || empty($comment)) {
            return null; // Missing username or comment
        }

        $reviewId = count($this->reviews) + 1; // Simple ID generation
        $review = [
            'id' => $reviewId,
            'productId' => $productId,
            'username' => $username,
            'rating' => $rating,
            'comment' => $comment,
            'date' => date('Y-m-d H:i:s') // Add timestamp for record keeping
        ];

        $this->reviews[] = $review;
        return $reviewId;
    }

    /**
     * Retrieves a review by ID.
     *
     * @param int $reviewId The ID of the review to retrieve.
     * @return array|null The review data, or null if not found.
     */
    public function getReview(int $reviewId) {
        foreach ($this->reviews as $review) {
            if ($review['id'] == $reviewId) {
                return $review;
            }
        }
        return null;
    }

    /**
     * Updates an existing review.
     *
     * @param int $reviewId The ID of the review to update.
     * @param int $productId The new product ID (if updating).
     * @param string $username The new username.
     * @param string $rating The new rating.
     * @param string $comment The new comment.
     * @return bool True if updated successfully, false otherwise.
     */
    public function updateReview(int $reviewId, int $productId = null, string $username = null, string $rating = null, string $comment = null) {
        $review = $this->getReview($reviewId);

        if (!$review) {
            return false; // Review not found
        }

        // Perform validation here (similar to addReview)

        if ($productId !== null) {
            $review['productId'] = $productId;
        }
        if ($username !== null) {
            $review['username'] = $username;
        }
        if ($rating !== null) {
            if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
                return false; // Invalid rating
            }
            $review['rating'] = $rating;
        }
        if ($comment !== null) {
            $review['comment'] = $comment;
        }

        return true;
    }

    /**
     * Deletes a review by ID.
     *
     * @param int $reviewId The ID of the review to delete.
     * @return bool True if deleted successfully, false otherwise.
     */
    public function deleteReview(int $reviewId) {
        foreach ($this->reviews as $key => $review) {
            if ($review['id'] == $reviewId) {
                unset($this->reviews[$key]);
                return true;
            }
        }
        return false;
    }

    /**
     * Retrieves all reviews for a specific product.
     *
     * @param int $productId The ID of the product.
     * @return array An array of review objects.
     */
    public function getReviewsForProduct(int $productId) {
        $reviews = [];
        foreach ($this->reviews as $review) {
            if ($review['productId'] == $productId) {
                $reviews[] = $review;
            }
        }
        return $reviews;
    }
}


// Example Usage:
$reviewSystem = new ReviewSystem();

// Add a review
$reviewId = $reviewSystem->addReview(123, 'JohnDoe', 4, 'Great product, highly recommended!');

if ($reviewId) {
    echo "Review added with ID: " . $reviewId . "
";
} else {
    echo "Failed to add review.
";
}

// Get the review by ID
$review = $reviewSystem->getReview($reviewId);
if ($review) {
    echo "Review details: " . print_r($review, true) . "
";
} else {
    echo "Review not found.
";
}

// Update the review
$updateResult = $reviewSystem->updateReview($reviewId, 5, 'JaneSmith', 5, 'Excellent!');
if ($updateResult) {
    echo "Review updated successfully.
";
} else {
    echo "Failed to update review.
";
}

// Delete the review
$deleteResult = $reviewSystem->deleteReview($reviewId);
if ($deleteResult) {
    echo "Review deleted successfully.
";
} else {
    echo "Failed to delete review.
";
}
?>


<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display user reviews for a given item.
 *
 * @param string $item_name The name of the item being reviewed.
 * @param string $review_text The text of the user's review.
 * @param int $user_id (Optional) The ID of the user submitting the review.  Defaults to 0.
 * @param int $rating (Optional) The rating given to the item (1-5). Defaults to 0.
 *
 * @return array  An array containing review data:
 *               - 'review_id' (int): Unique ID of the review.
 *               - 'user_id' (int):  ID of the user.
 *               - 'item_name' (string): Name of the item.
 *               - 'review_text' (string): Review text.
 *               - 'rating' (int): Rating.
 *               - 'timestamp' (string):  Timestamp of the review.
 */
function create_user_review(string $item_name, string $review_text, int $user_id = 0, int $rating = 0)
{
    //  Basic validation - you'd likely want more robust validation in a real application.
    if (empty($review_text)) {
        return ['error' => 'Review text cannot be empty.'];
    }

    if ($rating < 1 || $rating > 5) {
        return ['error' => 'Rating must be between 1 and 5.'];
    }

    // Generate a unique ID (replace with a database-generated ID in a real app)
    $review_id = time(); 

    // Get the current timestamp
    $timestamp = date("Y-m-d H:i:s");

    // Return the review data
    return [
        'review_id' => $review_id,
        'user_id' => $user_id,
        'item_name' => $item_name,
        'review_text' => $review_text,
        'rating' => $rating,
        'timestamp' => $timestamp
    ];
}


// --- Example Usage ---

// Create a review
$review = create_user_review("Awesome Product", "This product is amazing!  I highly recommend it.");

if (isset($review['error'])) {
    echo "Error creating review: " . $review['error'] . "<br>";
} else {
    echo "Review ID: " . $review['review_id'] . "<br>";
    echo "User ID: " . $review['user_id'] . "<br>";
    echo "Item Name: " . $review['item_name'] . "<br>";
    echo "Review Text: " . $review['review_text'] . "<br>";
    echo "Rating: " . $review['rating'] . "<br>";
    echo "Timestamp: " . $review['timestamp'] . "<br>";
}


// Example with user ID and rating
$review2 = create_user_review("Another Product", "Not bad.", 123, 4);

if (isset($review2['error'])) {
    echo "Error creating review: " . $review2['error'] . "<br>";
} else {
    echo "Review ID: " . $review2['review_id'] . "<br>";
    echo "User ID: " . $review2['user_id'] . "<br>";
    echo "Item Name: " . $review2['item_name'] . "<br>";
    echo "Review Text: " . $review2['review_text'] . "<br>";
    echo "Rating: " . $review2['rating'] . "<br>";
    echo "Timestamp: " . $review2['timestamp'] . "<br>";
}


//Example with invalid input
$review_error = create_user_review("", "Review", 1, 6);
if(isset($review_error['error'])){
    echo "Error creating review: " . $review_error['error'] . "<br>";
}
?>


<?php

/**
 * User Review Function
 *
 * This function allows users to submit and view reviews for a product.
 *
 * @param string $product_id The ID of the product being reviewed.
 * @param string $name The name of the reviewer.
 * @param string $comment The review comment.
 * @param int    $rating   The rating given by the user (1-5).
 *
 * @return array An array containing the review data, or an error message if invalid input is provided.
 */
function create_review(string $product_id, string $name, string $comment, int $rating) {
    // Input validation
    if (empty($product_id)) {
        return ['error' => 'Product ID cannot be empty.'];
    }
    if (empty($name)) {
        return ['error' => 'Reviewer name cannot be empty.'];
    }
    if (empty($comment)) {
        return ['error' => 'Review comment cannot be empty.'];
    }
    if ($rating < 1 || $rating > 5) {
        return ['error' => 'Rating must be between 1 and 5.'];
    }

    // Sanitize input (important for security) - this is a basic example, adapt for your needs
    $product_id = filter_var($product_id, FILTER_SANITIZE_STRING);
    $name       = filter_var($name, FILTER_SANITIZE_STRING);
    $comment    = filter_var($comment, FILTER_SANITIZE_STRING);


    // Store the review data (replace with database storage in a real application)
    $review = [
        'product_id' => $product_id,
        'name'       => $name,
        'comment'    => $comment,
        'rating'     => $rating,
        'date'       => date('Y-m-d H:i:s'), // Add a timestamp
    ];


    return $review;
}


/**
 * Display Reviews for a Product
 *
 * This function retrieves and displays reviews for a given product ID.
 *
 * @param string $product_id The ID of the product to retrieve reviews for.
 *
 * @return void  Displays the reviews to the browser.
 */
function display_reviews(string $product_id) {
  // Placeholder for retrieving reviews from a database.
  // In a real application, you would query your database here.

  // Example:  Simulate fetching reviews from a database
  $reviews = [
      ['product_id' => '123', 'name' => 'John Doe', 'comment' => 'Great product!', 'rating' => 5, 'date' => '2023-10-26'],
      ['product_id' => '123', 'name' => 'Jane Smith', 'comment' => 'Could be better', 'rating' => 3, 'date' => '2023-10-25'],
      ['product_id' => '456', 'name' => 'Peter Jones', 'comment' => 'Excellent value', 'rating' => 4, 'date' => '2023-10-24']
  ];

  echo "<h2>Reviews for Product ID: " . $product_id . "</h2>";

  if (empty($reviews)) {
      echo "<p>No reviews yet.</p>";
      return;
  }

  echo "<ul>";
  foreach ($reviews as $review) {
      echo "<li>";
      echo "<strong>" . $review['name'] . "</strong> - " . $review['comment'] . " (" . $review['rating'] . "/5) - " . $review['date'];
      echo "</li>";
  }
  echo "</ul>";
}

// Example Usage (Demonstration)

// Create a review
$new_review = create_review('123', 'Alice Brown', 'This is a fantastic product!', 5);

if (isset($new_review['error'])) {
    echo "<p style='color: red;'>Error creating review: " . $new_review['error'] . "</p>";
} else {
    echo "<p>Review created successfully: " . print_r($new_review, true) . "</p>";
}


// Display reviews for product ID '123'
display_reviews('123');
?>


<?php

/**
 * Function to create a user review.
 *
 * @param string $username The username of the reviewer.
 * @param string $rating  The rating (e.g., 1-5).
 * @param string $comment The reviewer's comments.
 * @param string $product_id The ID of the product being reviewed.
 *
 * @return bool True on success, false on failure.
 */
function createReview(string $username, string $rating, string $comment, string $product_id) {

    // Input validation (Important for security and data integrity)
    if (empty($username) || empty($rating) || empty($comment) || empty($product_id)) {
        error_log("Error: Missing required fields for review creation."); // Log for debugging
        return false;
    }

    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        error_log("Error: Invalid rating. Rating must be a number between 1 and 5.");
        return false;
    }

    // Database connection (Replace with your actual database connection)
    $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password"); // Replace placeholders

    try {
        // Prepare the SQL query (Important for security - prevents SQL injection)
        $stmt = $db->prepare("INSERT INTO reviews (user_name, product_id, rating, comment) VALUES (?, ?, ?, ?)");

        // Bind the parameters (Safely insert the data into the query)
        $stmt->bindParam(1, $username);
        $stmt->bindParam(2, $product_id);
        $stmt->bindParam(3, $rating);
        $stmt->bindParam(4, $comment);

        // Execute the query
        $stmt->execute();

        return true; // Success!

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage()); // Log the error
        return false;
    }
}

// Example Usage:
$username = "JohnDoe";
$rating = 4;
$comment = "Great product!  Works as expected.";
$product_id = "123";

if (createReview($username, $rating, $comment, $product_id)) {
    echo "Review created successfully!";
} else {
    echo "Failed to create review.";
}


// Example to demonstrate error handling:
$username = ""; // Empty username
$rating = 3;
$comment = "Test review";
$product_id = "456";

if (createReview($username, $rating, $comment, $product_id)) {
    echo "Review created successfully!";
} else {
    echo "Failed to create review (due to error).";
}

?>


<?php

/**
 * User Review Function
 *
 * This function creates a simple user review system.  It allows users to
 * submit reviews, displays them, and provides basic functionality for
 * displaying average rating.
 */

class ReviewSystem
{
    private $reviews = []; // Store reviews - key is ID, value is review data

    /**
     * Adds a new review.
     *
     * @param int    $userId   The ID of the user submitting the review.
     * @param string $comment  The review comment.
     * @param int    $rating   The rating (1-5).
     * @return int|false The ID of the newly created review, or false on failure.
     */
    public function addReview(int $userId, string $comment, int $rating)
    {
        if ($rating < 1 || $rating > 5) {
            return false; // Invalid rating
        }

        $reviewId = count($this->reviews) + 1;
        $this->reviews[$reviewId] = [
            'user_id' => $userId,
            'comment' => $comment,
            'rating' => $rating,
            'date' => date('Y-m-d H:i:s') // Store timestamp for sorting/filtering
        ];

        return $reviewId;
    }

    /**
     * Retrieves all reviews.
     *
     * @return array An array of review data.
     */
    public function getAllReviews()
    {
        return $this->reviews;
    }

    /**
     * Retrieves a specific review by its ID.
     *
     * @param int $reviewId The ID of the review.
     * @return array|null Review data if found, null otherwise.
     */
    public function getReviewById(int $reviewId)
    {
        if (isset($this->reviews[$reviewId])) {
            return $this->reviews[$reviewId];
        }
        return null;
    }

    /**
     * Calculates the average rating.
     *
     * @return float|null The average rating, or null if no reviews exist.
     */
    public function getAverageRating()
    {
        $totalRating = 0;
        $reviewCount = count($this->reviews);

        if ($reviewCount > 0) {
            foreach ($this->reviews as $review) {
                $totalRating += $review['rating'];
            }
            return round($totalRating / $reviewCount, 2); // Round to 2 decimal places
        }
        return null;
    }

    /**
     * Displays all reviews.
     */
    public function displayReviews()
    {
        echo "<h2>All Reviews</h2>";
        if (count($this->reviews) == 0) {
            echo "<p>No reviews yet.</p>";
            return;
        }

        echo "<ul>";
        foreach ($this->reviews as $review) {
            echo "<li>";
            echo "<strong>User ID:</strong> " . $review['user_id'] . "<br>";
            echo "<strong>Comment:</strong> " . htmlspecialchars($review['comment']) . "<br>";  // Use htmlspecialchars for security
            echo "<strong>Rating:</strong> " . $review['rating'] . " / 5<br>";
            echo "<strong>Date:</strong> " . $review['date'] . "<br>";
            echo "</li>";
        }
        echo "</ul>";
    }
}


// Example Usage:
$reviewSystem = new ReviewSystem();

// Add some reviews
$reviewId1 = $reviewSystem->addReview(123, "Great product!", 5);
$reviewId2 = $reviewSystem->addReview(456, "Could be better", 3);
$reviewId3 = $reviewSystem->addReview(789, "Amazing!", 5);


// Display all reviews
echo "<h2>Reviews Displayed:</h2>";
$reviewSystem->displayReviews();

// Get average rating
$averageRating = $reviewSystem->getAverageRating();
echo "<br><h2>Average Rating:</h2>";
if ($averageRating !== null) {
    echo "<p>Average Rating: " . $averageRating . "</p>";
} else {
    echo "<p>No reviews to calculate average rating.</p>";
}


// Get a specific review
$review = $reviewSystem->getReviewById(2);
if ($review) {
    echo "<br><h2>Specific Review (ID 2):</h2>";
    echo "<p>User ID: " . $review['user_id'] . "</p>";
    echo "<p>Comment: " . htmlspecialchars($review['comment']) . "</p>";
    echo "<p>Rating: " . $review['rating'] . "</p>";
} else {
    echo "<p>Review not found.</p>";
}

?>


<?php

/**
 * User Review Function
 *
 * This function allows you to create, read, update, and delete user reviews.
 * It includes basic data sanitization and validation to prevent common issues.
 *
 * @param string $productId The ID of the product the review is for.
 * @param string $rating      The rating given by the user (1-5).
 * @param string $comment     The review comment.
 * @param int    $userId      The ID of the user submitting the review.
 *
 * @return array An array containing:
 *   - 'success': True if the review was created/updated successfully, false otherwise.
 *   - 'message':  A message describing the result of the operation.
 *   - 'review': The newly created or updated review object (if successful).
 */
function create_review(string $productId, string $rating, string $comment, int $userId): array
{
    // Sanitize and Validate Inputs
    $productId = filter_var($productId, FILTER_SANITIZE_STRING, FILTER_STRIP); // Prevent XSS
    $rating = filter_var($rating, FILTER_VALIDATE_INT, array("min" => 1, "max" => 5));
    $comment = filter_var($comment, FILTER_SANITIZE_STRING, FILTER_STRIP);

    if(empty($productId) || empty($rating) || empty($comment) || $rating === null || $rating < 1 || $rating > 5) {
        return [
            'success' => false,
            'message' => 'Invalid input.  Please check your input values.',
            'review' => null
        ];
    }

    // --- Database Logic - REPLACE WITH YOUR OWN DATABASE CONNECTION ---
    // Example using a mock database
    $reviews = []; // Simulate a database

    // Generate a unique review ID (for demonstration only - use a real unique ID)
    $reviewId = uniqid();

    // Create a review object (you can customize this)
    $review = [
        'reviewId' => $reviewId,
        'productId' => $productId,
        'userId' => $userId,
        'rating' => $rating,
        'comment' => $comment,
        'timestamp' => time()
    ];

    // Add the review to the reviews array
    $reviews[$reviewId] = $review;

    // --- End Database Logic ---


    return [
        'success' => true,
        'message' => 'Review created successfully!',
        'review' => $review
    ];
}


// --- Example Usage ---
// Create a review
$result = create_review('product123', 4, 'Great product!', 123);

if ($result['success']) {
    echo "Review created successfully:<br>";
    echo "Review ID: " . $result['review']['reviewId'] . "<br>";
    echo "Product ID: " . $result['review']['productId'] . "<br>";
    echo "Rating: " . $result['review']['rating'] . "<br>";
    echo "Comment: " . $result['review']['comment'] . "<br>";
} else {
    echo "Error creating review: " . $result['message'] . "<br>";
}

// Example with invalid input
$result = create_review('', 3, 'Bad product!', 123);
if ($result['success']) {
    echo "Review created successfully:<br>";
} else {
    echo "Error creating review: " . $result['message'] . "<br>";
}
?>


<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or item.
 *
 * @param string $productId The unique identifier for the product/item.
 * @param string $reviewerName The name of the user submitting the review.
 * @param string $reviewText The text of the review.
 * @param int    $rating    The rating given by the user (e.g., 1-5 stars).
 * @param int    $userId    The user's ID (optional, for database integration).
 *
 * @return array An array containing review data if successful, or an error message string if not.
 */
function createReview(string $productId, string $reviewerName, string $reviewText, int $rating, int $userId = null)
{
    // Input Validation - Important for security and data integrity
    if (empty($productId)) {
        return ["error" => "Product ID cannot be empty."];
    }
    if (empty($reviewerName)) {
        return ["error" => "Reviewer Name cannot be empty."];
    }
    if (empty($reviewText)) {
        return ["error" => "Review Text cannot be empty."];
    }
    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        return ["error" => "Rating must be a number between 1 and 5."];
    }

    // In a real application, you would save this data to a database
    // For this example, we'll simulate storage using an array.

    $review = [
        "productId" => $productId,
        "reviewerName" => $reviewerName,
        "reviewText" => $reviewText,
        "rating" => $rating,
        "timestamp" => date("Y-m-d H:i:s"), // Add timestamp for tracking
        "userId" => $userId
    ];

    // Simulate saving to a database (replace with your database logic)
    //  Example:  $db->insert("reviews", $review);

    return $review;
}

/**
 * Displays a user review
 *
 * @param array $review  The review data (as returned by createReview).
 * @return string HTML markup to display the review.
 */
function displayReview(array $review)
{
    if (empty($review)) {
        return "<p>No reviews available for this product.</p>";
    }

    $html = "<div class='review'>";
    $html .= "<p><strong>Product ID:</strong> " . htmlspecialchars($review['productId']) . "</p>";
    $html .= "<p><strong>Reviewer:</strong> " . htmlspecialchars($review['reviewerName']) . "</p>";
    $html .= "<p><strong>Rating:</strong> " . htmlspecialchars($review['rating']) . " stars</p>";
    $html .= "<p><strong>Review:</strong> " . nl2br(htmlspecialchars($review['reviewText'])) . "</p>"; // nl2br for line breaks
    $html .= "<p><strong>Timestamp:</strong> " . $review['timestamp'] . "</p>";
    $html .= "</div>";
    return $html;
}

// Example Usage:
$newReview = createReview("product_123", "John Doe", "This product is amazing! I highly recommend it.", 5, 123);

if (isset($newReview['error'])) {
    echo "<p style='color:red;'>Error: " . $newReview['error'] . "</p>";
} else {
    echo "New Review Created Successfully!<br>";
    echo "<pre>";
    print_r($newReview);
    echo "</pre>";
    echo "<br>";

    // Display the review
    echo displayReview($newReview);
}

?>


<?php

/**
 * Class UserReview
 *
 * This class allows users to submit and view reviews for a product or service.
 */
class UserReview
{
    private $reviews = [];

    /**
     * Adds a new review to the system.
     *
     * @param string $userId    The ID of the user submitting the review.
     * @param string $rating    The rating given by the user (e.g., 1-5).
     * @param string $comment  The user's comment about the product/service.
     *
     * @return bool True if the review was added successfully, false otherwise.
     */
    public function addReview(string $userId, string $rating, string $comment)
    {
        // Validation -  Add more validation as needed (e.g., numeric rating check, comment length limits)
        if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
            return false;
        }

        $review = [
            'userId' => $userId,
            'rating' => $rating,
            'comment' => $comment,
            'date' => date('Y-m-d H:i:s') // Timestamp of when the review was added
        ];

        $this->reviews[] = $review;
        return true;
    }

    /**
     * Retrieves all reviews.
     *
     * @return array An array of all reviews.
     */
    public function getAllReviews()
    {
        return $this->reviews;
    }

    /**
     * Retrieves reviews by user ID.
     *
     * @param string $userId The user ID to filter by.
     *
     * @return array An array of reviews for the specified user.
     */
    public function getReviewsByUserId(string $userId)
    {
        $userReviews = [];
        foreach ($this->reviews as $review) {
            if ($review['userId'] === $userId) {
                $userReviews[] = $review;
            }
        }
        return $userReviews;
    }

    /**
     * Calculates the average rating for a product/service.
     *
     * @return float|null The average rating, or null if no reviews are available.
     */
    public function calculateAverageRating()
    {
        if (empty($this->reviews)) {
            return null;
        }

        $totalRating = 0;
        foreach ($this->reviews as $review) {
            $totalRating += floatval($review['rating']);
        }
        return round($totalRating / count($this->reviews), 2); // Round to 2 decimal places
    }

    /**
     * Clears all reviews.
     */
    public function clearReviews()
    {
        $this->reviews = [];
    }
}

// --- Example Usage ---
// Create a UserReview object
$reviewSystem = new UserReview();

// Add some reviews
$reviewSystem->addReview('user123', 4, 'Great product!  Easy to use.');
$reviewSystem->addReview('user456', 5, 'Excellent service and fast delivery.');
$reviewSystem->addReview('user123', 3, 'It was okay, nothing special.');

// Get all reviews
$allReviews = $reviewSystem->getAllReviews();
echo "All Reviews:
";
print_r($allReviews);

// Get reviews for user123
$userReviews = $reviewSystem->getReviewsByUserId('user123');
echo "
Reviews for user123:
";
print_r($userReviews);

// Calculate the average rating
$averageRating = $reviewSystem->calculateAverageRating();
echo "
Average Rating: " . ($averageRating !== null ? $averageRating : 'No reviews available') . "
";

// Clear the reviews
$reviewSystem->clearReviews();
echo "
Reviews cleared.
";

// Check if reviews are empty
$allReviews = $reviewSystem->getAllReviews();
echo "Reviews after clearing: " . count($allReviews) . "
";
?>


<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or item.
 * It provides basic functionality for adding, retrieving, and displaying reviews.
 *
 * @param string $productId The unique identifier for the product/item.
 * @param string $username The name of the user submitting the review.
 * @param string $reviewText The text content of the review.
 * @param int $rating   The rating given by the user (1-5).
 *
 * @return array An array containing:
 *   - 'success': True if the review was successfully added.
 *   - 'message': A message indicating the status of the review addition.
 *   - 'reviews': An array of all reviews for the product.
 */
function store_user_review(string $productId, string $username, string $reviewText, int $rating): array
{
    // Sanitize input (important for security!)
    $productId = filter_var($productId, FILTER_SANITIZE_STRING);
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $reviewText = filter_var($reviewText, FILTER_SANITIZE_STRING);
    $rating = intval($rating); // Ensure rating is an integer

    // Validation (essential for data integrity)
    if (empty($productId) || empty($username) || empty($reviewText) || $rating < 1 || $rating > 5) {
        return ['success' => false, 'message' => 'Invalid review data.  Please ensure all fields are filled and ratings are between 1 and 5.', 'reviews' => []];
    }

    // Example: Store reviews in an array (replace with a database storage)
    $reviews = [];
    if (isset($_SESSION['reviews'][$productId])) {
        $reviews = $_SESSION['reviews'][$productId];
    }

    $newReview = [
        'username' => $username,
        'review' => $reviewText,
        'rating' => $rating,
        'timestamp' => time() // Add timestamp for ordering/filtering
    ];

    $reviews[] = $newReview;
    $_SESSION['reviews'][$productId] = $reviews; // Store in session (for simplicity)

    return ['success' => true, 'message' => 'Review submitted successfully!', 'reviews' => $reviews];
}


// --- Example Usage (for demonstration) ---

// Initialize session (important for storing reviews)
session_start();

// Simulate a product ID
$productId = 'product-123';

// Example 1: Successful submission
$reviewData = store_user_review($productId, 'John Doe', 'This product is amazing!', 5);
print_r($reviewData);

// Example 2: Invalid input (rating outside range)
$invalidReviewData = store_user_review($productId, 'Jane Smith', 'Good product', 7);
print_r($invalidReviewData);

// ---  Displaying Reviews (Example) ---
if (isset($_SESSION['reviews'][$productId])) {
    echo "<h2>Reviews for Product " . $productId . "</h2>";
    echo "<ul>";
    foreach ($_SESSION['reviews'][$productId] as $review) {
        echo "<li><strong>" . $review['username'] . ":</strong> " . htmlspecialchars($review['review']) . " (" . $review['rating'] . "/5) - " . date('Y-m-d H:i:s', $review['timestamp']) . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No reviews yet.</p>";
}
?>


<?php

/**
 * User Review Function
 *
 * This function takes a product ID, user data, and a review text,
 * and saves the review to a database.  It also includes basic validation
 * and error handling.
 *
 * @param int $productId The ID of the product the review is for.
 * @param array $userData  An associative array containing user data:
 *                      - 'username' => string - The user's username.
 *                      - 'rating' => int - The rating given (e.g., 1-5).
 *                      - 'review' => string - The text of the review.
 * @param PDO $pdo        A PDO database connection object.
 *
 * @return bool True on success, false on failure.
 */
function saveUserReview(int $productId, array $userData, PDO $pdo)
{
    // Validate input data
    $username = isset($userData['username']) ? trim($userData['username']) : '';
    $rating = isset($userData['rating']) ? (int)$userData['rating'] : 0; // Ensure rating is an integer
    $review = isset($userData['review']) ? trim($userData['review']) : '';

    if (empty($username) || empty($review)) {
        error_log("Invalid review data: Username and review text are required."); // Log the error
        return false;
    }

    if ($rating < 1 || $rating > 5) {
        error_log("Invalid rating: Rating must be between 1 and 5.");
        return false;
    }

    // Prepare the SQL query
    $sql = "INSERT INTO reviews (product_id, username, rating, review)
            VALUES (:product_id, :username, :rating, :review)";

    try {
        // Prepare the statement
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':product_id', $productId);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':review', $review);

        // Execute the query
        $stmt->execute();

        return true;

    } catch (PDOException $e) {
        // Handle database errors
        error_log("Database error: " . $e->getMessage()); // Log the error
        return false;
    }
}


// Example Usage (Assuming you have a database connection established)

// **Important: Replace with your actual database connection details**
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}


// Sample User Data
$userData = [
    'username' => 'john.doe',
    'rating' => 4,
    'review' => 'Great product!  I really liked it.',
];

// Save the review
if (saveUserReview(123, $userData, $pdo)) {
    echo "Review saved successfully!";
} else {
    echo "Failed to save review.  Check the error logs.";
}

?>


<?php

/**
 * User Review Function
 *
 * This function takes a user ID, a product ID, and a review text,
 * and saves the review to a database.  It also handles basic input validation.
 *
 * @param int $userId The ID of the user submitting the review.
 * @param int $productId The ID of the product being reviewed.
 * @param string $reviewText The text of the review.
 * @return bool True if the review was saved successfully, false otherwise.
 */
function saveUserReview(int $userId, int $productId, string $reviewText): bool
{
    // Input Validation -  Crucial to prevent SQL injection and bad data
    if (!$userId || !$productId || !$reviewText) {
        error_log("Error: Missing required parameters for review."); // Log error
        return false;
    }

    // Sanitize the review text -  Important!
    $reviewText = htmlspecialchars($reviewText, ENT_QUOTES, 'UTF-8');

    //  You'll need a database connection here.  This is just a placeholder.
    //  Replace this with your actual database connection code.
    $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");

    try {
        // Prepare the SQL statement to prevent SQL injection
        $stmt = $db->prepare("INSERT INTO reviews (user_id, product_id, review_text, rating) VALUES (:userId, :productId, :reviewText, :rating)");

        // Bind the parameters - ensures data is properly escaped
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':productId', $productId);
        $stmt->bindParam(':reviewText', $reviewText);
        $stmt->bindParam(':rating', 0); // default rating - you might want to allow users to set this

        // Execute the statement
        $result = $stmt->execute();

        // Check if the query was successful
        if ($result) {
            return true;
        } else {
            // Log the error
            error_log("Error saving review: " . print_r($stmt->errorInfo(), true));
            return false;
        }
    } catch (PDOException $e) {
        // Log the exception -  Very important for debugging
        error_log("PDO Exception: " . $e->getMessage());
        return false;
    }
}


// Example Usage (Illustrative - Replace with your actual data)
// $userId = 123;
// $productId = 456;
// $reviewText = "This product is amazing!  I really love it!";
//
// if (saveUserReview($userId, $productId, $reviewText)) {
//     echo "Review saved successfully!";
// } else {
//     echo "Error saving review.";
// }



//  Important Considerations and Best Practices:

// 1. Database Connection: This example uses a placeholder database connection.  You *must* replace the example connection with your actual database connection code using PDO or MySQLi.

// 2. Error Handling:  The `try...catch` block handles potential database errors, preventing your script from crashing and providing valuable debugging information. Always log errors.

// 3. Input Validation:  The  `if (!$userId || ...)` block is crucial. Validate that all required parameters are provided and of the correct type.  This prevents unexpected behavior and potential security vulnerabilities.  You can extend this validation to check for things like review length limits, disallowed characters, etc.

// 4. SQL Injection Prevention: The code *must* use prepared statements with parameterized queries (using `$db->prepare()` and `$stmt->bindParam()`).  This is the *only* secure way to handle user input in SQL queries. Never directly concatenate user input into your SQL strings.

// 5. Sanitization: `htmlspecialchars()`  sanitizes the review text to prevent cross-site scripting (XSS) attacks.  It converts special characters (like <, >, &, etc.) into their HTML entities, preventing them from being interpreted as HTML tags.

// 6.  Database Schema: The code assumes a database schema with tables like `reviews` with columns `user_id`, `product_id`, `review_text`, and `rating`.  Adjust this to match your actual database structure.

// 7.  Rating:  The code includes a `rating` column. You can extend this function to allow users to set a rating (e.g., a star rating).

// 8.  Logging:  Using `error_log()` is vital for debugging. It's better than `echo`ing errors because it doesn't expose them to users.

// 9.  Security: Keep your database credentials secure. Don't hardcode them into your scripts; use environment variables or a configuration file.

// 10.  Database Transactions (For Complex Operations):  If you're performing multiple database operations (e.g., updating several tables), consider using database transactions to ensure that either all operations succeed or none of them do, maintaining data integrity.


<?php

/**
 * User Review Function
 *
 * This function allows you to create, retrieve, update, and delete user reviews.
 * It uses a simple database (for demonstration) and includes basic input validation.
 * 
 * **Important:** This is a basic example and should be adapted to your specific database,
 * schema, and security requirements.  Consider using an ORM (Object-Relational Mapper) 
 * for more complex applications.
 *
 * @param string $productId The ID of the product being reviewed.
 * @param string $username The username of the reviewer.
 * @param string $rating The rating given by the reviewer (e.g., 1-5).
 * @param string $comment The comment left by the reviewer.
 * @param string $dbHost The database host.
 * @param string $dbName The database name.
 * @param string $dbUser The database username.
 * @param string $dbPassword The database password.
 *
 * @return array  An array containing:
 *                - 'success': True if the operation was successful, False otherwise.
 *                - 'message':  A message describing the result.
 *                - 'reviewId': The ID of the newly created review (if successful).
 *                              Returns NULL if the review was not created.
 */
function createReview(
    string $productId,
    string $username,
    string $rating,
    string $comment,
    string $dbHost,
    string $dbName,
    string $dbUser,
    string $dbPassword
) {
    // Input Validation - Basic Example
    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        return [
            'success' => false,
            'message' => 'Invalid rating. Rating must be a number between 1 and 5.',
            'reviewId' => null,
        ];
    }

    // Database connection (Use a real database connection class for production)
    $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

    if ($conn->connect_error) {
        return [
            'success' => false,
            'message' => "Failed to connect to database: " . $conn->connect_error,
            'reviewId' => null,
        ];
    }

    // Prepare the SQL statement
    $sql = "INSERT INTO reviews (productId, username, rating, comment) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        return [
            'success' => false,
            'message' => "Failed to prepare SQL statement: " . $conn->error,
            'reviewId' => null,
        ];
    }

    // Bind parameters
    $stmt->bind_param("ssis", $productId, $username, $rating, $comment);

    // Execute the statement
    if ($stmt->execute() === false) {
        return [
            'success' => false,
            'message' => "Failed to execute SQL: " . $stmt->error,
            'reviewId' => null,
        ];
    }

    // Get the last inserted ID
    $reviewId = $conn->insert_id;

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    return [
        'success' => true,
        'message' => 'Review created successfully.',
        'reviewId' => $reviewId,
    ];
}

// Example Usage (Replace with your database details)
// $product_id = '123';
// $username = 'john.doe';
// $rating = 4;
// $comment = 'Great product!';

// $result = createReview($product_id, $username, $rating, $comment, 'localhost', 'your_db_name', 'your_db_user', 'your_db_password');

// print_r($result); // Display the result
?>


<?php

/**
 * Function to create and store user reviews.
 *
 * This function handles:
 * - Validating input data.
 * - Generating a unique review ID.
 * - Storing the review in a database (using a basic example, you'd adapt this to your database setup).
 * - Returning the review ID.
 *
 * @param string $product_id The ID of the product the review is for.
 * @param string $user_id The ID of the user leaving the review.
 * @string $rating The rating (e.g., 1-5).
 * @string $comment The user's review comment.
 * @return int|false The ID of the newly created review on success, or false on failure.
 */
function createReview(string $product_id, string $user_id, string $rating, string $comment)
{
    // **Input Validation - Important!**
    if (empty($product_id) || empty($user_id) || empty($rating) || empty($comment)) {
        error_log("Error: Missing review data.  product_id, user_id, rating, and comment are required."); // Log the error for debugging.
        return false;
    }

    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        error_log("Error: Invalid rating.  Rating must be a number between 1 and 5.");
        return false;
    }

    // **Database Interaction (Example - Adapt to your DB setup)**
    try {
        // Assuming you have a database connection established as $db
        $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password"); // Replace with your database credentials
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling for errors

        // Prepare the SQL statement
        $stmt = $db->prepare("INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");

        // Bind the parameters
        $stmt->bindParam(1, $product_id);
        $stmt->bindParam(2, $user_id);
        $stmt->bindParam(3, $rating);
        $stmt->bindParam(4, $comment);

        // Execute the statement
        $stmt->execute();

        // Get the last inserted ID
        $review_id = $db->lastInsertId();

        return (int)$review_id; // Cast to integer for consistency

    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage()); // Log the full error message
        return false;
    }
}


/**
 * Example Usage:
 */

// Sample Review Data
$product_id = 'product123';
$user_id = 'user456';
$rating = 4;
$comment = 'Great product!  Highly recommend.';

// Create the review
$review_id = createReview($product_id, $user_id, $rating, $comment);

if ($review_id) {
    echo "Review created successfully! Review ID: " . $review_id . "
";
} else {
    echo "Error creating review.
";
}

// Example of invalid input
$invalid_review_id = createReview("", "user1", 3, "Test comment");
if($invalid_review_id){
    echo "Invalid review created successfully! Review ID: " . $invalid_review_id . "
";
}else{
    echo "Error creating review.
";
}
?>


<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product.
 * It handles input validation, sanitization, and basic storage.
 *
 * @param string $productId The unique identifier for the product.
 * @param string $userName The name of the user submitting the review.
 * @param string $reviewText The user's review text.
 * @param string $dbConnection PDO connection object to the database.
 *
 * @return array An array containing the result of the review submission:
 *              - 'success':  True if the review was successfully saved, false otherwise.
 *              - 'message':  A message indicating the status of the review.
 *              - 'reviewId': The ID of the newly created review if successful, or null otherwise.
 */
function submitReview(string $productId, string $userName, string $reviewText, PDO $dbConnection) {

  // Input Validation and Sanitization - IMPORTANT!  Prevent SQL Injection!
  $productId = filter_var($productId, FILTER_SANITIZE_STRING);  // Sanitize product ID
  $userName = filter_var($userName, FILTER_SANITIZE_STRING); // Sanitize user name
  $reviewText = filter_var($reviewText, FILTER_SANITIZE_STRING); // Sanitize review text

  //  Check if the product exists (basic check - improve for real-world use)
  $stmt = $dbConnection->prepare("SELECT id FROM products WHERE id = :product_id");
  $stmt->bindParam(':product_id', $productId);
  $stmt->execute();
  $product = $stmt->fetch(PDO::FETCH_OBJ);

  if (!$product) {
    return [
      'success' => false,
      'message' => "Product with ID '$productId' not found.",
      'reviewId' => null
    ];
  }

  // Sanitize review text to prevent XSS.  Consider using HTML escaping.
  $reviewText = htmlspecialchars($reviewText, ENT_QUOTES, 'UTF-8');


  // Prepare and execute the insert statement
  $stmt = $dbConnection->prepare("INSERT INTO reviews (product_id, user_name, review_text) VALUES (:product_id, :user_name, :review_text)");
  $stmt->bindParam(':product_id', $productId);
  $stmt->bindParam(':user_name', $userName);
  $stmt->bindParam(':review_text', $reviewText);
  $stmt->execute();

  $reviewId = $dbConnection->lastInsertId();

  return [
    'success' => true,
    'message' => "Review submitted successfully!",
    'reviewId' => $reviewId
  ];
}

// Example Usage (assuming you have a database connection object $dbConnection)
//
// $result = submitReview('123', 'John Doe', 'This is a great product!', $dbConnection);
//
// if ($result['success']) {
//   echo "Review submitted successfully! Review ID: " . $result['reviewId'];
// } else {
//   echo "Error submitting review: " . $result['message'];
// }


/**
 * Function to display reviews for a product.
 *
 * @param string $productId The unique identifier for the product.
 * @param PDO $dbConnection PDO connection object to the database.
 */
function displayReviews(string $productId, PDO $dbConnection) {
  $query = "SELECT id, user_name, review_text, created_at FROM reviews WHERE product_id = :product_id ORDER BY created_at DESC";
  $stmt = $dbConnection->prepare($query);
  $stmt->bindParam(':product_id', $productId);
  $stmt->execute();

  $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if (empty($reviews)) {
    echo "<p>No reviews yet.</p>";
  } else {
    echo "<h2>Reviews for Product ID: " . $productId . "</h2>";
    echo "<ul>";
    foreach ($reviews as $review) {
      echo "<li>";
      echo "<p><strong>User:</strong> " . htmlspecialchars($review['user_name']) . "</p>";
      echo "<p>" . htmlspecialchars($review['review_text']) . "</p>";
      echo "<p>Date: " . date("Y-m-d H:i:s", strtotime($review['created_at'])) . "</p>";
      echo "</li>";
    }
    echo "</ul>";
  }
}



?>


<?php

/**
 * Class UserReview
 *
 * Handles the creation, retrieval, and display of user reviews.
 */
class UserReview
{
    private $reviews = []; // Array to store reviews
    private $db; // Database connection (using PDO for example)

    /**
     * Constructor: Initializes the UserReview class.
     *
     * @param PDO $db PDO database connection.  Defaults to a dummy connection.
     */
    public function __construct(PDO $db = null)
    {
        $this->db = $db ?? new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password'); // Replace with your database credentials.  Use a dummy PDO for testing.
        $this->reviews = [];
    }


    /**
     * Add a new review.
     *
     * @param string $user_id The ID of the user writing the review.
     * @param string $product_id The ID of the product being reviewed.
     * @param string $rating The rating (e.g., 1-5).
     * @param string $comment The review comment.
     * @return bool True if the review was added successfully, false otherwise.
     */
    public function addReview(string $user_id, string $product_id, string $rating, string $comment): bool
    {
        $rating = (int)$rating; // Ensure rating is an integer
        if ($rating < 1 || $rating > 5) {
            return false; // Invalid rating
        }

        try {
            $stmt = $this->db->prepare("INSERT INTO reviews (user_id, product_id, rating, comment) VALUES (:user_id, :product_id, :rating, :comment)");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':product_id', $product_id);
            $stmt->bindParam(':rating', $rating);
            $stmt->bindParam(':comment', $comment);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            // Handle database errors (log them, etc.)
            error_log("Error adding review: " . $e->getMessage());
            return false;
        }
    }



    /**
     * Retrieve all reviews for a specific product.
     *
     * @param string $product_id The ID of the product.
     * @return array An array of review objects, or an empty array if no reviews are found.
     */
    public function getReviewsByProduct(string $product_id): array
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM reviews WHERE product_id = :product_id");
            $stmt->bindParam(':product_id', $product_id);
            $stmt->execute();
            $reviews = $stmt->fetchAll(PDO::FETCH_OBJ); // Fetch results as objects for easier access.

            return $reviews;
        } catch (PDOException $e) {
            // Handle database errors.
            error_log("Error retrieving reviews: " . $e->getMessage());
            return [];
        }
    }



    /**
     * Retrieve a single review by its ID.
     *
     * @param int $review_id The ID of the review to retrieve.
     * @return object|null A review object if found, null otherwise.
     */
    public function getReviewById(int $review_id): ?object
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM reviews WHERE id = :review_id");
            $stmt->bindParam(':review_id', $review_id);
            $stmt->execute();
            $review = $stmt->fetch(PDO::FETCH_OBJ);
            return $review;
        } catch (PDOException $e) {
            // Handle database errors.
            error_log("Error retrieving review: " . $e->getMessage());
            return null;
        }
    }


    /**
     * Update an existing review.  Requires the review_id.
     *
     * @param int $review_id The ID of the review to update.
     * @param string $new_rating The new rating.
     * @param string $new_comment The new review comment.
     * @return bool True if the review was updated successfully, false otherwise.
     */
    public function updateReview(int $review_id, string $new_rating, string $new_comment): bool
    {
        try {
            $rating = (int)$new_rating;
            if ($rating < 1 || $rating > 5) {
                return false; // Invalid rating
            }

            $sql = "UPDATE reviews SET rating = :rating, comment = :comment WHERE id = :review_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':review_id', $review_id);
            $stmt->bindParam(':rating', $rating);
            $stmt->bindParam(':comment', $new_comment);
            $stmt->execute();
            return true;

        } catch (PDOException $e) {
            // Handle database errors.
            error_log("Error updating review: " . $e->getMessage());
            return false;
        }
    }


    /**
     * Delete a review by its ID.
     *
     * @param int $review_id The ID of the review to delete.
     * @return bool True if the review was deleted successfully, false otherwise.
     */
    public function deleteReview(int $review_id): bool
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM reviews WHERE id = :review_id");
            $stmt->bindParam(':review_id', $review_id);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            // Handle database errors.
            error_log("Error deleting review: " . $e->getMessage());
            return false;
        }
    }



    //  Methods to display the reviews (for demonstration purposes)
    public function displayReviewsByProduct(string $product_id)
    {
        $reviews = $this->getReviewsByProduct($product_id);
        if ($reviews) {
            echo "<h2>Reviews for Product ID: " . $product_id . "</h2>";
            foreach ($reviews as $review) {
                echo "<p><strong>User:</strong> " . $review->user_id . "<br>";
                echo "<strong>Rating:</strong> " . $review->rating . "<br>";
                echo "<strong>Comment:</strong> " . $review->comment . "</p>";
            }
        } else {
            echo "<p>No reviews found for this product.</p>";
        }
    }
}



// Example Usage:

// Create a dummy database connection (replace with your actual database credentials)
$db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');

$reviewSystem = new UserReview($db);


// Add a review
if ($reviewSystem->addReview(123, 456, 4, "Great product!")) {
    echo "<p>Review added successfully.</p>";
} else {
    echo "<p>Failed to add review.</p>";
}


// Retrieve reviews for a product
$reviewSystem->displayReviewsByProduct(456); // Displays reviews for product ID 456


//Update a review
if ($reviewSystem->updateReview(1, 5, "Excellent! I highly recommend this.") ) {
    echo "<p>Review updated successfully.</p>";
} else {
    echo "<p>Failed to update review.</p>";
}


//Delete a review
if($reviewSystem->deleteReview(1)){
    echo "<p>Review deleted successfully</p>";
} else {
    echo "<p>Failed to delete review</p>";
}
?>


<?php

/**
 * User Review Class
 */
class UserReview {

  private $review_id;
  private $user_id;
  private $product_id;
  private $rating;
  private $comment;
  private $date;

  /**
   * Constructor
   * 
   * @param int $review_id (optional) - Unique ID for the review.  If not provided, generates one.
   * @param int $user_id  - The ID of the user who wrote the review.
   * @param int $product_id - The ID of the product being reviewed.
   * @param int $rating  -  The rating (e.g., 1-5).
   * @param string $comment - The user's comment about the product.
   */
  public function __construct(
    int $user_id,
    int $product_id,
    int $rating,
    string $comment = ""  // Default comment to empty string
  ) {
    $this->generateReviewId();
    $this->user_id = $user_id;
    $this->product_id = $product_id;
    $this->rating = $rating;
    $this->comment = $comment;
    $this->date = date("Y-m-d H:i:s"); // Get current timestamp
  }

  /**
   * Generates a unique review ID.
   */
  private function generateReviewId(): void {
    // You could use database sequence or a unique string generation method.
    // For simplicity, using a timestamp and a counter.
    static $counter = 0;
    $this->review_id = $counter++;
  }

  /**
   * Getters
   */
  public function getReviewId(): int {
    return $this->review_id;
  }

  public function getUser(): int {
    return $this->user_id;
  }

  public function getProduct(): int {
    return $this->product_id;
  }

  public function getRating(): int {
    return $this->rating;
  }

  public function getComment(): string {
    return $this->comment;
  }

  public function getDate(): string {
    return $this->date;
  }
  
  /**
   * Returns the review as an array. Useful for database insertion.
   * @return array
   */
  public function toArray(): array {
    return [
      'review_id' => $this->review_id,
      'user_id' => $this->user_id,
      'product_id' => $this->product_id,
      'rating' => $this->rating,
      'comment' => $this->comment,
      'date' => $this->date
    ];
  }
}


/**
 * User Review Function (Example)
 */
function displayReviewDetails(UserReview $review): void {
  echo "Review ID: " . $review->getReviewId() . "<br>";
  echo "User ID: " . $review->getUser() . "<br>";
  echo "Product ID: " . $review->getProduct() . "<br>";
  echo "Rating: " . $review->getRating() . "<br>";
  echo "Comment: " . $review->getComment() . "<br>";
  echo "Date: " . $review->getDate() . "<br>";
}


// Example Usage:

// Create a new review object
$review1 = new UserReview(123, 456, 5, "Great product!  I highly recommend it.");
$review2 = new UserReview(789, 101, 3, "Okay, but could be better.");
$review3 = new UserReview(101, 102); //uses default empty comment.


// Display the review details
displayReviewDetails($review1);
displayReviewDetails($review2);
displayReviewDetails($review3);

// Demonstrate the toArray method:
$review_data = $review1->toArray();
print_r($review_data);  // Output will be an array.



//  Considerations for a Real Application:

// 1. Database Integration:  The code above is a basic example. In a real application,
//    you would store the reviews in a database (e.g., MySQL, PostgreSQL).

// 2. Validation: Add input validation to ensure that the rating is within a valid range (e.g., 1-5) and that required fields are not empty.

// 3. Error Handling: Implement error handling to gracefully handle potential errors, such as database connection errors.

// 4. Security:  Sanitize user input to prevent security vulnerabilities (e.g., SQL injection).

// 5.  More Complex Interactions: Consider features like pagination for displaying reviews, sorting reviews, and searching for reviews.
?>


<?php

/**
 * User Review Function
 *
 * This function takes user input and generates a basic user review.
 * It can be customized to add more features like rating, sentiment analysis,
 * or integration with a database.
 *
 * @param string $username The username of the reviewer.
 * @param string $reviewText The text of the review.
 * @return string A formatted user review string.
 */
function generateUserReview(string $username, string $reviewText): string
{
    // Validate inputs (Optional, but recommended)
    if (empty($username)) {
        return "Error: Username cannot be empty.";
    }
    if (empty($reviewText)) {
        return "Error: Review text cannot be empty.";
    }

    // Format the review
    $review = "Review by: " . $username . "
";
    $review .= "Review Text: " . $reviewText . "
";
    $review .= "--- End of Review ---";

    return $review;
}


// Example Usage:
$username = "JohnDoe";
$reviewText = "This product is amazing! I highly recommend it.";
$review = generateUserReview($username, $reviewText);
echo $review . PHP_EOL;

$username = "JaneSmith";
$reviewText = "The service was slow, but the staff were friendly.";
$review = generateUserReview($username, $reviewText);
echo $review . PHP_EOL;


//Example with invalid input
$username = "";
$reviewText = "This is a review.";
$review = generateUserReview($username, $reviewText);
echo $review . PHP_EOL; // This will output the error message.

?>


<?php

/**
 * User Review Function
 *
 * This function allows you to capture user reviews, sanitize input,
 * and optionally store them in a database.
 *
 * @param string $productName The name of the product being reviewed.
 * @param string $userId      The ID of the user submitting the review.  Can be NULL for guest reviews.
 * @param string $reviewText  The text of the user's review.
 * @param string $rating      (Optional) The rating given by the user (e.g., 1-5).  Defaults to null.
 * @param string $dbHost       (Optional) Database host.  Required if storing reviews in a database.
 * @param string $dbUser       (Optional) Database user.  Required if storing reviews in a database.
 * @param string $dbPassword  (Optional) Database password.  Required if storing reviews in a database.
 * @param string $dbName       (Optional) Database name.  Required if storing reviews in a database.
 * @param string $table        (Optional) Name of the table to store the reviews in (if using database).
 *
 * @return array An array containing the review data or an error message if invalid input is detected.
 */
function createReview(
    string $productName,
    string $userId,
    string $reviewText,
    string $rating = null,
    string $dbHost = null,
    string $dbUser = null,
    string $dbPassword = null,
    string $dbName = null,
    string $table = null
) {
    // Input Validation - Basic Check
    if (empty($productName)) {
        return ['error' => 'Product name cannot be empty.'];
    }
    if (empty($reviewText)) {
        return ['error' => 'Review text cannot be empty.'];
    }

    // Sanitize input (Important for security)
    $reviewText = trim($reviewText);
    $reviewText = htmlspecialchars($reviewText); // Prevent XSS attacks

    // Validation for rating (if provided)
    if ($rating !== null) {
        if (!is_numeric($rating)) {
            return ['error' => 'Rating must be a number.'];
        }
        if ($rating < 1 || $rating > 5) {
            return ['error' => 'Rating must be between 1 and 5.'];
        }
    }

    // Construct the review data
    $reviewData = [
        'product_name' => $productName,
        'user_id'      => $userId,
        'review_text'  => $reviewText,
        'rating'       => $rating,
    ];

    // Store in Database (Optional)
    if ($dbHost !== null && $dbUser !== null && $dbPassword !== null && $dbName !== null && $table !== null) {
        // Implement database connection and insertion logic here.
        // This is just a placeholder.  You'll need to adapt it to your database system.

        try {
            // Example (MySQL) - Replace with your connection details and query
            $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

            if ($conn->connect_error) {
                throw new Exception("Connection failed: " . $conn->connect_error);
            }

            $query = "INSERT INTO `$table` (product_name, user_id, review_text, rating) VALUES ('" . $conn->real_escape_string($productName) . "', '" . $conn->real_escape_string($userId) . "', '" . $conn->real_escape_string($reviewText) . "', '" . $conn->real_escape_string($rating) . "')";
            $conn->query($query);

            $conn->close();

        } catch (Exception $e) {
            return ['error' => 'Database error: ' . $e->getMessage()];
        }
    }

    return $reviewData;
}


// Example Usage:
// Simulate a review submission
$review = createReview(
    'Awesome Widget',
    'user123',
    'This widget is fantastic!  I love it.',
    5,
    'localhost',
    'myuser',
    'secretpassword',
    'my_database',
    'product_reviews'
);

if (isset($review['error'])) {
    echo "<p style='color: red;'>Error: " . $review['error'] . "</p>";
} else {
    echo "<p>Review submitted successfully! Data: " . print_r($review, true) . "</p>";
}


?>


<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product or service.
 *
 * @param string $productName The name of the product or service being reviewed.
 * @param string $reviewText The user's review text.
 * @param int $userId (Optional) The ID of the user submitting the review.  If not provided, a default/anonymous user is used.
 * @param string $reviewerName (Optional) The name to display for the reviewer. If not provided, a default name is used.
 *
 * @return array An array containing review details (success/failure, review text, reviewer details).
 */
function submitReview(string $productName, string $reviewText, $userId = null, $reviewerName = null) {
  // Input Validation - crucial for security and data integrity
  if (empty($productName)) {
    return ['success' => false, 'message' => 'Product name cannot be empty.'];
  }
  if (empty($reviewText)) {
    return ['success' => false, 'message' => 'Review text cannot be empty.'];
  }

  // Handle User ID (if provided) -  This is a simplified example.  In a real application,
  // you would typically authenticate and validate the user.
  if ($userId === null) {
    $userId = 1; // Default user ID.  Change this for a real system.
  }

  // Handle Reviewer Name - Default if not provided
  if ($reviewerName === null) {
    $reviewerName = 'Anonymous User';
  }

  //  Simulate saving the review to a database or file.  In a real application,
  //  replace this with your database interaction logic.
  $reviewId = time(); // Generate a unique ID for the review.
  $reviewData = [
    'reviewId' => $reviewId,
    'productId' => $productName,
    'reviewText' => $reviewText,
    'userId' => $userId,
    'reviewerName' => $reviewerName,
    'dateSubmitted' => date('Y-m-d H:i:s')
  ];

  // Simulate saving the review to a file (for demonstration)
  file_put_contents('reviews.txt', $reviewData . PHP_EOL, FILE_APPEND);


  return ['success' => true, 'review' => $reviewData];
}


/**
 * Display a Review
 *
 * This function retrieves and displays a review based on its ID.
 *
 * @param string $reviewId The ID of the review to display.
 *
 * @return array An array containing review details (success/failure, review details).
 */
function displayReview(string $reviewId) {
  // Simulate retrieving the review from a database or file.
  // Replace this with your database query logic.
  $reviews = [];
  if (file_exists('reviews.txt')) {
      $lines = file('reviews.txt', FILE_IGNORE_NEW_LINES);
      foreach ($lines as $line) {
          $reviews[] = explode(',', $line);
      }
  }

  foreach ($reviews as $review) {
    if ($review[0] == $reviewId) {
      return ['success' => true, 'review' => $review];
    }
  }

  return ['success' => false, 'message' => 'Review not found.'];
}



// Example Usage:
$product = 'Awesome Gadget X';

// Submit a review
$reviewResult = submitReview($product, 'This gadget is amazing!  I highly recommend it.', 123, 'John Doe');

if ($reviewResult['success']) {
  echo "Review submitted successfully!
";
  echo "Review ID: " . $reviewResult['review']['reviewId'] . "
";
  echo "Reviewer: " . $reviewResult['review']['reviewerName'] . "
";
  echo "Review Text: " . $reviewResult['review']['reviewText'] . "
";
} else {
  echo "Error submitting review: " . $reviewResult['message'] . "
";
}

// Display the review
$reviewDisplayResult = displayReview($reviewResult['review']['reviewId']);

if ($reviewDisplayResult['success']) {
  echo "
--- Displaying Review ---
";
  echo "Review ID: " . $reviewDisplayResult['review']['reviewId'] . "
";
  echo "Reviewer: " . $reviewDisplayResult['review']['reviewerName'] . "
";
  echo "Review Text: " . $reviewDisplayResult['review']['reviewText'] . "
";
  echo "Date Submitted: " . $reviewDisplayResult['review']['dateSubmitted'] . "
";
} else {
  echo "Error displaying review: " . $reviewDisplayResult['message'] . "
";
}
?>


<?php

/**
 * User Review Function
 *
 * This function allows users to submit reviews for a product or item.
 * It validates the input, sanitizes it, and saves it to a database.
 *
 * @param string $productId The ID of the product being reviewed.
 * @param string $rating  The rating given by the user (e.g., 1-5).
 * @param string $comment The user's review comment.
 * @param string $dbHost    The database hostname (e.g., localhost).
 * @param string $dbUser    The database username.
 * @param string $dbPassword The database password.
 * @param string $dbName    The database name.
 *
 * @return bool True if the review was successfully submitted, false otherwise.
 */
function submitReview(
    string $productId,
    string $rating,
    string $comment,
    string $dbHost,
    string $dbUser,
    string $dbPassword,
    string $dbName
) {
    // Input validation and sanitization
    if (empty($productId) || empty($rating) || empty($comment)) {
        error_log("Missing required fields in review submission.");
        return false;
    }

    if (!preg_match('/^[1-5][0-9]*$/', $rating)) {
        error_log("Invalid rating format.  Please enter a number between 1 and 5.");
        return false;
    }

    if (strlen($comment) > 500) {  // Example: Limit comment length
        error_log("Comment exceeds maximum length (500 characters).");
        return false;
    }

    // Database connection (using a simple example - consider using PDO for better security)
    $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

    if ($conn->connect_error) {
        error_log("Failed to connect to database: " . $conn->connect_error);
        return false;
    }

    // Prepare the SQL statement (using prepared statements for security!)
    $sql = "INSERT INTO reviews (product_id, rating, comment) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        error_log("Error preparing SQL statement: " . $conn->error);
        $stmt->close();
        $conn->close();
        return false;
    }

    // Bind parameters to prevent SQL injection
    $stmt->bind_param("siii", $productId, $rating, $comment);

    // Execute the statement
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return true;
    } else {
        error_log("Error executing SQL statement: " . $stmt->error);
        $stmt->close();
        $conn->close();
        return false;
    }
}

// Example Usage:
// Assuming you have a database set up with a table named 'reviews'
// with columns: product_id (INT), rating (INT), comment (TEXT)

// $success = submitReview("123", "4", "This product is amazing!", "localhost", "user", "password", "mydatabase");

// if ($success) {
//     echo "Review submitted successfully!";
// } else {
//     echo "Error submitting review.";
// }
?>


<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or item.
 * It includes basic validation and sanitization.  For production environments,
 * consider adding more robust validation and sanitization.
 *
 * @param string $product_id The unique identifier for the product/item.
 * @param string $user_name The name of the user submitting the review.
 * @param string $review_text The review text submitted by the user.
 * @param int $rating The rating given by the user (e.g., 1-5).
 * @param string $db_connection  (Optional) A database connection object.
 *                             If not provided, it will attempt to connect to a 'reviews' database.
 * @return bool True if the review was saved successfully, false otherwise.
 */
function saveUserReview(string $product_id, string $user_name, string $review_text, int $rating, $db_connection = null): bool
{
    // Basic validation - Adjust as needed for your application
    if (empty($product_id)) {
        error_log("Error: Product ID cannot be empty.");
        return false;
    }

    if (empty($user_name)) {
        error_log("Error: User name cannot be empty.");
        return false;
    }

    if (empty($review_text)) {
        error_log("Error: Review text cannot be empty.");
        return false;
    }

    if ($rating < 1 || $rating > 5) {
        error_log("Error: Rating must be between 1 and 5.");
        return false;
    }

    // Sanitize input (Important for security - this is a basic example)
    $review_text = htmlspecialchars($review_text); // Prevents XSS attacks
    $user_name = htmlspecialchars($user_name);

    // Database connection - If not provided, attempt to connect
    if (!$db_connection) {
        try {
            $db_connection = new PDO("mysql:host=localhost;dbname=reviews", "root", "");  // Replace credentials
            $db_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log("Database connection error: " . $e->getMessage());
            return false;
        }
    }


    // Prepare and execute the SQL query
    try {
        $stmt = $db_connection->prepare(
            "INSERT INTO reviews (product_id, user_name, review_text, rating) VALUES (:product_id, :user_name, :review_text, :rating)"
        );

        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':user_name', $user_name);
        $stmt->bindParam(':review_text', $review_text);
        $stmt->bindParam(':rating', $rating);

        $stmt->execute();

        return true;

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return false;
    }
}


/**
 * Display User Reviews for a product
 *
 * Retrieves and displays reviews associated with a given product ID.
 *
 * @param string $product_id The product ID.
 * @param PDO $db_connection  A PDO database connection object.
 * @return array An array of review objects (or an empty array if no reviews are found).
 */
function displayUserReviews(string $product_id, PDO $db_connection): array
{
    try {
        $stmt = $db_connection->prepare("SELECT * FROM reviews WHERE product_id = :product_id");
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();

        $reviews = $stmt->fetchAll(PDO::FETCH_OBJ); // Fetch as objects for easier access

        return $reviews;

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return []; // Return an empty array on error
    }
}


// Example Usage (Illustrative - replace with your actual data)
/*
$product_id = "123";
$user_name = "John Doe";
$review_text = "Great product, highly recommended!";
$rating = 4;

if (saveUserReview($product_id, $user_name, $review_text, $rating)) {
    echo "Review saved successfully!";
} else {
    echo "Failed to save review.";
}

$reviews = displayUserReviews($product_id, $db_connection);

if ($reviews) {
    echo "<br><h2>Reviews:</h2>";
    foreach ($reviews as $review) {
        echo "<strong>" . $review->user_name . "</strong>: " . $review->review_text . " (Rating: " . $review->rating . ")
";
    }
} else {
    echo "<br>No reviews found for this product.";
}
*/

?>


<?php

/**
 * User Review Function
 * 
 * This function allows users to submit and display user reviews for a given item.
 * 
 * @param string $itemName The name of the item being reviewed.
 * @param string $itemDescription  A brief description of the item (optional).
 * @param array $reviews An array of existing reviews (optional).
 * @return array An array containing the updated reviews, including the new review if submitted.
 */
function handleUserReviews(string $itemName, string $itemDescription = '', array $reviews = []) {
    // Simulate database interaction for demonstration purposes.  Replace this with 
    // actual database queries in a real application.
    $newReview = "";
    if (isset($_POST['review_text'])) {
        $newReview = $_POST['review_text'];
    }


    $reviews = [...reviews, ['text' => $newReview, 'date' => date('Y-m-d H:i:s')]]; // Add new review to the array.  Includes date.

    // Sort reviews by date (most recent first) - optional but recommended
    usort($reviews, function($a, $b) {
        return $b['date'] <=> $a['date'];
    });


    return $reviews;
}



// Example Usage (Demonstration)

// Initialize an empty reviews array
$reviews = [];

// Simulate a form submission
if (isset($_POST['submit_review'])) {
    $reviews = handleUserReviews('My Awesome Product', 'A great product!', $reviews);
}

// Display the reviews
echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<title>User Reviews</title>";
echo "</head>";
echo "<body>";

echo "<h1>User Reviews for " . $itemName = 'My Awesome Product' . "</h1>";

if (count($reviews) > 0) {
    echo "<p><strong>Reviews:</strong></p>";
    echo "<table border='1'>";
    echo "<tr><th>Date</th><th>Review</th></tr>";
    foreach ($reviews as $review) {
        echo "<tr>";
        echo "<td>" . $review['date'] . "</td>";
        echo "<td>" . $review['text'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No reviews yet!</p>";
}


echo "<form method='post'>";
echo "<h2>Submit a Review</h2>";
echo "<label for='review_text'>Your Review:</label><br>";
echo "<textarea id='review_text' name='review_text' rows='4' cols='50'></textarea><br><br>";
echo "<input type='submit' value='Submit Review'>";
echo "</form>";

echo "</body>";
echo "</html>";

?>


<?php

/**
 * Reviews class for managing user reviews.
 */
class Review {

    private $reviews = [];
    private $db_connection = null; // For database interaction (optional)

    /**
     * Constructor: Initializes the review system.
     *
     * @param mysqli $db_connection  (Optional) Database connection object.
     */
    public function __construct($db_connection = null) {
        $this->db_connection = $db_connection; // Assign the db connection
    }

    /**
     * Adds a new review.
     *
     * @param string $username       The username of the reviewer.
     * @param string $review_text    The text of the review.
     * @param int   $rating         The rating (e.g., 1-5).
     * @return int|false  The ID of the newly created review, or false on failure.
     */
    public function addReview(string $username, string $review_text, int $rating) {
        if (!$this->db_connection) {
            // If no database connection, store review in-memory
            $review_id = count($this->reviews) + 1;
            $this->reviews[$review_id] = [
                'username' => $username,
                'review_text' => $review_text,
                'rating' => $rating,
                'date' => date('Y-m-d H:i:s') // Timestamp for review creation
            ];
            return $review_id;
        } else {
            // Database interaction
            $sql = "INSERT INTO reviews (username, review_text, rating, created_at) VALUES (?, ?, ?, ?)";
            $stmt = $this->db_connection->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("sss", $username, $review_text, $rating);
                if ($stmt->execute()) {
                    $review_id = $this->db_connection->insert_id; // Get the auto-incremented ID
                    $stmt->close();
                    return $review_id;
                } else {
                    $error_message = $this->db_connection->error;
                    $stmt->close();
                    error_log("Error adding review: " . $error_message);  // Log the error
                    return false;
                }
            } else {
                error_log("Error preparing database statement for adding review");
                return false;
            }
        }
    }



    /**
     * Retrieves all reviews.
     *
     * @return array  An array of review objects.
     */
    public function getAllReviews() {
        if($this->db_connection) {
            $results = $this->db_connection->query("SELECT * FROM reviews");
        } else {
            $results = $this->reviews;
        }
        $review_objects = [];
        if($results) {
            while ($row = $results->fetch_assoc()) {
                $review_objects[] = $this->convertRowToReviewObject($row);
            }
        }
        return $review_objects;
    }


    /**
     * Converts a database row to a Review object.
     *
     * @param array $row  A database row.
     * @return Review  A Review object.
     */
    private function convertRowToReviewObject(array $row) {
        return new Review([
            'id' => $row['id'],
            'username' => $row['username'],
            'review_text' => $row['review_text'],
            'rating' => $row['rating'],
            'date' => $row['created_at']
        ]);
    }



    /**
     * Retrieves a specific review by its ID.
     *
     * @param int $id The ID of the review.
     * @return Review|false A Review object, or false if not found.
     */
    public function getReviewById(int $id) {
        if($this->db_connection) {
            $result = $this->db_connection->query("SELECT * FROM reviews WHERE id = ?");
            if ($result && $result->fetch_assoc()) {
                return $this->convertRowToReviewObject($result->fetch_assoc());
            } else {
                return false;
            }
        } else {
            // In-memory retrieval
            if (isset($this->reviews[$id])) {
                return $this->reviews[$id];
            } else {
                return false;
            }
        }
    }


    /**
     * Updates an existing review.
     * @param int $id The ID of the review to update.
     * @param string $new_review_text The new text of the review.
     * @param int   $new_rating     The new rating.
     * @return bool True if the update was successful, false otherwise.
     */
    public function updateReview(int $id, string $new_review_text, int $new_rating) {
        if($this->db_connection) {
            $sql = "UPDATE reviews SET review_text = ?, rating = ?, created_at = NOW() WHERE id = ?";
            $stmt = $this->db_connection->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("sss", $new_review_text, $new_rating, $id);
                if ($stmt->execute()) {
                    $stmt->close();
                    return true;
                } else {
                    $error_message = $this->db_connection->error;
                    $stmt->close();
                    error_log("Error updating review: " . $error_message);
                    return false;
                }
            } else {
                error_log("Error preparing database statement for updating review");
                return false;
            }
        } else {
            // In-memory update (if no database connection)
            if (isset($this->reviews[$id])) {
                $this->reviews[$id] = [
                    'username' => $this->reviews[$id]['username'], // Keep username
                    'review_text' => $new_review_text,
                    'rating' => $new_rating,
                    'date' => date('Y-m-d H:i:s')
                ];
                return true;
            } else {
                return false;
            }
        }
    }


    /**
     * Deletes a review.
     *
     * @param int $id The ID of the review to delete.
     * @return bool True if the deletion was successful, false otherwise.
     */
    public function deleteReview(int $id) {
        if($this->db_connection) {
            $sql = "DELETE FROM reviews WHERE id = ?";
            $stmt = $this->db_connection->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("i", $id);
                if ($stmt->execute()) {
                    $stmt->close();
                    return true;
                } else {
                    $error_message = $this->db_connection->error;
                    $stmt->close();
                    error_log("Error deleting review: " . $error_message);
                    return false;
                }
            } else {
                error_log("Error preparing database statement for deleting review");
                return false;
            }
        } else {
            // In-memory deletion
            if (isset($this->reviews[$id])) {
                unset($this->reviews[$id]);
                return true;
            } else {
                return false;
            }
        }
    }
}


// Example Usage (Illustrative - Requires database setup)
// $db = new mysqli("localhost", "username", "password", "database_name");

// if ($db->connect_error) {
//   die("Connection failed: " . $db->connect_error);
// }

// $review = new Review($db);

// // Add a review
// $review_id = $review->addReview("John Doe", "This is a great product!", 5);
// if ($review_id) {
//     echo "Review added with ID: " . $review_id . "
";
// }

// // Get all reviews
// $all_reviews = $review->getAllReviews();
// echo "All Reviews:
";
// foreach ($all_reviews as $review) {
//     echo "- Username: " . $review['username'] . ", Rating: " . $review['rating'] . ", Review: " . $review['review_text'] . "
";
// }

// // Get a specific review
// $specific_review = $review->getReviewById($review_id);
// if ($specific_review) {
//     echo "
Specific Review (ID " . $review_id . "): " . json_encode($specific_review) . "
";
// }

// //Update a review
// $update_success = $review->updateReview($review_id, "Updated Review Text", 4);
// if ($update_success) {
//     echo "
Review updated successfully
";
// }

// //Delete a review
// $delete_success = $review->deleteReview($review_id);
// if ($delete_success) {
//     echo "
Review deleted successfully
";
// }

?>


<?php

/**
 * Class Review
 *
 * This class provides functionality to manage user reviews.
 */
class Review {

    /**
     * @var array Array of reviews.
     */
    private $reviews = [];

    /**
     * Adds a new review to the reviews array.
     *
     * @param string $user  The name of the user who submitted the review.
     * @param string $comment The content of the review.
     * @param int $rating The rating (1-5).
     * @return bool True on success, false on failure (e.g., invalid rating).
     */
    public function addReview(string $user, string $comment, int $rating): bool {
        // Validate rating
        if ($rating < 1 || $rating > 5) {
            return false;
        }

        $this->reviews[] = [
            'user' => $user,
            'comment' => $comment,
            'rating' => $rating
        ];

        return true;
    }

    /**
     * Gets all reviews.
     *
     * @return array An array of all reviews.
     */
    public function getAllReviews(): array {
        return $this->reviews;
    }

    /**
     * Gets reviews for a specific item (e.g., product, service).
     *
     * @param mixed $item  The item to get reviews for.  This allows you to extend
     *                    this class to handle different types of items.
     * @return array An array of reviews for the item.
     */
    public function getReviewsForItem(mixed $item): array {
        //This is a basic implementation.  You would likely need to extend
        //this based on how you store the item data.

        return $this->getAllReviews(); //For now, return all reviews - you'll need a more sophisticated system.
    }

    /**
     * Calculates the average rating for a specific item.
     *
     * @param mixed $item The item to calculate the average rating for.
     * @return float|null The average rating, or null if no reviews exist.
     */
    public function getAverageRatingForItem(mixed $item) {
        $reviews = $this->getReviewsForItem($item);

        if (empty($reviews)) {
            return null;
        }

        $totalRating = 0;
        foreach ($reviews as $review) {
            $totalRating += $review['rating'];
        }

        return $totalRating / count($reviews);
    }


    /**
     * Formats a review for display.
     *
     * @param array $review
     * @return string The formatted review string.
     */
    public function formatReview(array $review): string {
        return "User: " . $review['user'] . "
" .
               "Comment: " . $review['comment'] . "
" .
               "Rating: " . $review['rating'];
    }
}

// Example Usage:
$reviewSystem = new Review();

// Add some reviews
$reviewSystem->addReview("Alice", "Great product!", 5);
$reviewSystem->addReview("Bob", "It was okay.", 3);
$reviewSystem->addReview("Charlie", "Could be better.", 2);

// Get all reviews
$allReviews = $reviewSystem->getAllReviews();
echo "All Reviews:
";
foreach ($allReviews as $review) {
    echo $review['user'] . ": " . $review['comment'] . " (" . $review['rating'] . ")
";
}

// Get reviews for a specific item (e.g., a product)
$productReviews = $reviewSystem->getReviewsForItem('Product X');
echo "
Reviews for Product X:
";
foreach ($productReviews as $review) {
    echo $review['user'] . ": " . $review['comment'] . " (" . $review['rating'] . ")
";
}

// Get average rating for Product X
$averageRating = $reviewSystem->getAverageRatingForItem('Product X');
echo "
Average Rating for Product X: " . ($averageRating !== null ? $averageRating : "No reviews yet.") . "
";

// Format a specific review
$formattedReview = $reviewSystem->formatReview($allReviews[0]);
echo "
Formatted Review:
" . $formattedReview;
?>


<?php

/**
 * User Review Function - Simple Implementation
 *
 * This function allows you to create and display user reviews for a given product.
 * It handles basic data sanitization and validation.
 *
 * @param string $product_id The ID of the product the review is for.
 * @param string $name The reviewer's name.
 * @param string $comment The review text.
 *
 * @return array|string An array containing the review data if successful,
 *                     or an error message string if there's an issue.
 */
function createReview(string $product_id, string $name, string $comment) {
  // Validate input - Basic sanity checks
  if (empty($product_id)) {
    return "Error: Product ID cannot be empty.";
  }
  if (empty($name)) {
    return "Error: Reviewer name cannot be empty.";
  }
  if (empty($comment)) {
    return "Error: Review comment cannot be empty.";
  }

  // Sanitize input -  Escape HTML to prevent XSS
  $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
  $comment = htmlspecialchars($comment, ENT_QUOTES, 'UTF-8');


  //  You would normally store this in a database here.  For demonstration, 
  //  we'll just store it in an array.
  $review = [
    'product_id' => $product_id,
    'name' => $name,
    'comment' => $comment,
    'timestamp' => time() // Add a timestamp
  ];

  return $review;
}



/**
 * Function to display a single review
 * @param array $review The review data
 */
function displayReview(array $review) {
    echo "<p><strong>Product ID:</strong> " . $review['product_id'] . "</p>";
    echo "<p><strong>Reviewer:</strong> " . $review['name'] . "</p>";
    echo "<p><strong>Comment:</strong> " . $review['comment'] . "</p>";
    echo "<p><strong>Timestamp:</strong> " . date("Y-m-d H:i:s", strtotime($review['timestamp'])) . "</p>";
}



// --- Example Usage ---

// Create a review
$newReview = createReview("123", "John Doe", "This is a fantastic product!");

if (is_array($newReview)) {
  // Review was created successfully
  echo "<h2>New Review Created:</h2>";
  displayReview($newReview);

  // Example: Display all reviews for product 123 (Assuming you have a function to fetch them from a database)
  // You would replace this with your actual database query.
  //$reviews = fetchReviewsFromDatabase($product_id);
  //displayReviews($reviews);
} else {
  // There was an error creating the review
  echo "<p>Error: " . $newReview . "</p>";
}



/**
 * Placeholder function to simulate fetching reviews from a database.
 * Replace this with your actual database query.
 *
 * @param string $product_id
 * @return array
 */
function fetchReviewsFromDatabase(string $product_id) {
  //  In a real application, this would query your database.
  //  For demonstration, we'll just return some dummy reviews.
  if ($product_id == "123") {
    return [
      [
        'product_id' => '123',
        'name' => 'John Doe',
        'comment' => 'This is a fantastic product!',
        'timestamp' => time()
      ],
      [
        'product_id' => '123',
        'name' => 'Jane Smith',
        'comment' => 'Great value for the price.',
        'timestamp' => time() - 3600 // One hour ago
      ]
    ];
  } else {
    return []; // Return an empty array if the product ID is not found.
  }
}


/**
 * Placeholder function to display a list of reviews
 * @param array $reviews
 */
function displayReviews(array $reviews) {
    echo "<h3>All Reviews for Product ID " . $reviews[0]['product_id'] . ":</h3>";
    echo "<ul>";
    foreach ($reviews as $review) {
        displayReview($review);
    }
    echo "</ul>";
}

?>


<?php

/**
 * User Review Function
 *
 * This function allows you to create and display user reviews for a product.
 *
 * @param string $productId The unique identifier of the product.
 * @param string $username The name of the user submitting the review.
 * @param string $reviewText The content of the review.
 * @param int $rating (Optional) Rating from 1-5. Defaults to 0 if not provided.
 *
 * @return array An array containing the review data (success/failure, review ID, review text, rating, and timestamp).
 *               Returns an error message if the review creation fails.
 */
function createReview(string $productId, string $username, string $reviewText, int $rating = 0) {
  // Validation (basic - expand as needed)
  if (empty($productId)) {
    return ['success' => false, 'message' => 'Product ID cannot be empty.'];
  }
  if (empty($username)) {
    return ['success' => false, 'message' => 'Username cannot be empty.'];
  }
  if (empty($reviewText)) {
    return ['success' => false, 'message' => 'Review text cannot be empty.'];
  }

  //  You would typically store reviews in a database here.
  //  This is a simplified example.

  $reviewId = uniqid(); // Generate a unique ID for the review
  $timestamp = time();

  // Simulate saving to a database (replace with your actual database interaction)
  $review = [
    'reviewId' => $reviewId,
    'productId' => $productId,
    'username' => $username,
    'reviewText' => $reviewText,
    'rating' => $rating,
    'timestamp' => $timestamp
  ];

  // Store the review in a data structure (for demonstration)
  //  In a real application, you would save this to a database.
  //  Example:
  //  saveReviewToDatabase($review);
  
  return ['success' => true, 'reviewId' => $reviewId, 'reviewText' => $reviewText, 'rating' => $rating, 'timestamp' => $timestamp];
}

/**
 *  Example usage:
 */

// Create a review
$review = createReview('product123', 'John Doe', 'This is a great product!');
if ($review['success']) {
  echo "Review created successfully! Review ID: " . $review['reviewId'] . "<br>";
  echo "Review Text: " . $review['reviewText'] . "<br>";
  echo "Rating: " . $review['rating'] . "<br>";
  echo "Timestamp: " . $review['timestamp'] . "<br>";
} else {
  echo "Error creating review: " . $review['message'] . "<br>";
}

// Create a review with a rating
$review2 = createReview('product456', 'Jane Smith', 'Excellent value for money!', 5);
if ($review2['success']) {
  echo "Review created successfully! Review ID: " . $review2['reviewId'] . "<br>";
} else {
  echo "Error creating review: " . $review2['message'] . "<br>";
}


// Example with error handling
$invalidReview = createReview('', 'Test User', 'Invalid review');
if(!$invalidReview['success']) {
    echo "Error creating review: " . $invalidReview['message'] . "<br>";
}
?>


<?php

/**
 * User Review Class
 *
 * This class allows you to store and manage user reviews for a product or item.
 */
class UserReview
{
    private $reviewId;
    private $userId;
    private $productId;
    private $rating;
    private $comment;
    private $date;

    /**
     * Constructor
     *
     * Initializes a new UserReview object.
     *
     * @param int $reviewId (optional) - Unique ID for the review.  Auto-generated if not provided.
     * @param int $userId  The ID of the user submitting the review.
     * @param int $productId The ID of the product being reviewed.
     * @param int $rating  The rating given by the user (e.g., 1-5).
     * @param string $comment The user's comment about the product.
     */
    public function __construct(int $userId, int $productId, int $rating, string $comment = "")
    {
        $this->reviewId = null; // Will be auto-generated
        $this->userId = $userId;
        $this->productId = $productId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->date = date("Y-m-d H:i:s"); // Timestamp of when the review was created
    }

    /**
     * Getters and Setters
     */

    public function getReviewId(): int
    {
        return $this->reviewId;
    }

    public function setReviewId(int $reviewId)
    {
        $this->reviewId = $reviewId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId)
    {
        $this->userId = $userId;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function setProductId(int $productId)
    {
        $this->productId = $productId;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function setRating(int $rating)
    {
        $this->rating = $rating;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function setComment(string $comment)
    {
        $this->comment = $comment;
    }

    public function getDate(): string
    {
        return $this->date;
    }
}


/**
 * User Review Function -  Simple Example
 */
function displayUserReview(UserReview $review)
{
    echo "Review ID: " . $review->getReviewId() . "<br>";
    echo "User ID: " . $review->getUserId() . "<br>";
    echo "Product ID: " . $review->getProductId() . "<br>";
    echo "Rating: " . $review->getRating() . " stars<br>";
    echo "Comment: " . $review->getComment() . "<br>";
    echo "Date: " . $review->getDate() . "<br>";
}


// Example Usage:
$review1 = new UserReview(123, 456, 5, "Great product!  I highly recommend it.");
displayUserReview($review1);

$review2 = new UserReview(789, 456, 3, "It was okay, but could be better.");
displayUserReview($review2);

?>


<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or item.
 *
 * @param string $productId  The unique identifier for the product.
 * @param string $username   The user's name (for display).
 * @param string $rating     The user's rating (e.g., 1-5 stars).
 * @param string $comment    The user's review text.
 * @param array  $reviews   (Optional) An array to store existing reviews (for persistence).
 *
 * @return array  An updated array of reviews.
 */
function storeUserReview(string $productId, string $username, string $rating, string $comment, array &$reviews = []) {
  // Validate inputs (basic example - you might want more robust validation)
  if (empty($productId)) {
    return $reviews; // Or throw an exception - depends on your error handling
  }
  if (empty($username)) {
    $username = 'Anonymous';
  }
  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    $rating = 3; // Default rating if invalid
  }
  if (empty($comment)) {
    $comment = 'No comment provided.';
  }

  // Create a review object (you can adapt this to your data structure)
  $review = [
    'productId' => $productId,
    'username' => $username,
    'rating' => $rating,
    'comment' => $comment,
    'timestamp' => time() // Add a timestamp for sorting and tracking
  ];

  // Add the review to the array
  $reviews[] = $review;

  // Sort reviews by timestamp (most recent first) - optional
  usort($reviews, function($a, $b) {
    return $b['timestamp'] - $a['timestamp'];
  });

  return $reviews;
}



/**
 * Display User Reviews
 *
 * This function displays a list of user reviews for a given product.
 *
 * @param array $reviews An array of reviews to display.
 */
function displayReviews(array $reviews) {
  echo "<h2>Reviews for Product ID: " .  implode(",", array_map('getKey', $reviews)) . "</h2>";
  echo "<ul>";

  foreach ($reviews as $review) {
    echo "<li>";
    echo "<strong>" . $review['username'] . ":</strong> " . $review['comment'] . " (" . $review['rating'] . "/5)";
    echo "</li>";
  }

  echo "</ul>";
}


// ------------------- Example Usage -------------------

// Initialize an empty array to store reviews
$productReviews = [];

// Store some reviews
$productReviews = storeUserReview('P123', 'John Doe', 4, 'Great product, works as expected!', $productReviews);
$productReviews = storeUserReview('P123', 'Jane Smith', 5, 'Excellent quality and fast shipping!', $productReviews);
$productReviews = storeUserReview('P456', 'Peter Jones', 2, 'It was okay, but not great.', $productReviews);
$productReviews = storeUserReview('P123', 'Alice Brown', 1, 'Not what I expected.', $productReviews);


// Display the reviews
displayReviews($productReviews);

?>


<?php

/**
 * Class UserReview
 *
 * Handles the creation, retrieval, and display of user reviews.
 */
class UserReview
{
    private $reviews = []; // Store reviews in an array
    private $db; // Database connection object (optional - for persistent storage)

    /**
     * Constructor
     *
     * Initializes the UserReview object.  You can optionally initialize a database connection here.
     *
     * @param PDO|null $db  An optional PDO database connection object.
     */
    public function __construct(PDO|null $db = null)
    {
        $this->db = $db;
    }

    /**
     * Create a new user review.
     *
     * @param int $productId The ID of the product being reviewed.
     * @param string $userName The name of the user submitting the review.
     * @param string $reviewText The text of the review.
     * @return int|false The ID of the newly created review, or false on failure.
     */
    public function createReview(int $productId, string $userName, string $reviewText)
    {
        $reviewId = $this->db ? $this->db->query("INSERT INTO reviews (product_id, user_name, review_text) VALUES (:product_id, :user_name, :review_text) 
                                      SELECT LAST_INSERT_ID()")->fetchColumn() : (int)count($this->reviews) + 1;

        $review = [
            'productId' => $productId,
            'userName' => $userName,
            'reviewText' => $reviewText,
            'reviewId' => $review, // Added reviewId for easy retrieval
            'dateCreated' => date('Y-m-d H:i:s')
        ];

        $this->reviews[] = $review;
        return $review['reviewId'];
    }

    /**
     * Get a review by its ID.
     *
     * @param int $reviewId The ID of the review to retrieve.
     * @return array|null The review object, or null if not found.
     */
    public function getReview(int $reviewId)
    {
        foreach ($this->reviews as $review) {
            if ($review['reviewId'] == $reviewId) {
                return $review;
            }
        }
        return null;
    }

    /**
     * Get all reviews for a product.
     *
     * @param int $productId The ID of the product.
     * @return array An array of review objects.
     */
    public function getReviewsByProduct(int $productId)
    {
        $reviews = [];
        foreach ($this->reviews as $review) {
            if ($review['productId'] == $productId) {
                $reviews[] = $review;
            }
        }
        return $reviews;
    }


    /**
     * Update a review.  (Implement logic for updating reviews - e.g., allow moderation)
     *
     * @param int $reviewId The ID of the review to update.
     * @param string $newReviewText The new text of the review.
     * @return bool True on successful update, false on failure.
     */
    public function updateReview(int $reviewId, string $newReviewText)
    {
        $review = $this->getReview($reviewId);
        if ($review) {
            $review['reviewText'] = $newReviewText;
            return true;
        }
        return false;
    }


    /**
     * Delete a review. (Implement moderation logic)
     *
     * @param int $reviewId The ID of the review to delete.
     * @return bool True on successful deletion, false on failure.
     */
    public function deleteReview(int $reviewId)
    {
        $review = $this->getReview($reviewId);
        if ($review) {
            foreach ($this->reviews as $key => $r) {
                if ($r['reviewId'] == $reviewId) {
                    unset($this->reviews[$key]);
                    return true;
                }
            }
            return false;
        }
        return false;
    }


    /**
     * Display all reviews. (For demonstration purposes - typically you'd render this in a template)
     *
     * @return void
     */
    public function displayReviews()
    {
        echo "<h2>Reviews:</h2>";
        foreach ($this->reviews as $review) {
            echo "<h3>Review ID: " . $review['reviewId'] . "</h3>";
            echo "<p>Product ID: " . $review['productId'] . "</p>";
            echo "<p>User: " . $review['userName'] . "</p>";
            echo "<p>Review: " . $review['reviewText'] . "</p>";
            echo "<p>Date Created: " . $review['dateCreated'] . "</p>";
            echo "<hr>";
        }
    }
}

// Example Usage (Illustrative - you'd likely integrate this into a web application):
// Assume you have a PDO connection established.
// $db = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');

// $reviewSystem = new UserReview($db); // Pass the database connection

// // Create a review
// $reviewId = $reviewSystem->createReview(1, "John Doe", "Great product!");

// // Get a review
// $review = $reviewSystem->getReview($reviewId);
// if ($review) {
//     echo "<h2>Review Details:</h2>";
//     echo "<p>Review Text: " . $review['reviewText'] . "</p>";
// }

// // Display all reviews for product 1
// echo "<h2>Reviews for Product 1:</h2>";
// $reviewSystem->displayReviews();

// // Update a review
// $reviewSystem->updateReview($reviewId, "Excellent product - highly recommended!");

// // Delete a review
// $reviewSystem->deleteReview($reviewId);
?>


<?php

/**
 *  User Review Function
 *
 *  This function allows you to create, read, update, and delete user reviews.
 *
 *  @param string $productId The ID of the product the review is for.
 *  @param string $username The username of the reviewer.
 *  @param string $rating  The rating given (e.g., 1-5).  Validate this in your application.
 *  @param string $comment The review text.
 *  @param string $dbHost The database host.
 *  @param string $dbName The database name.
 *  @param string $dbUser The database username.
 *  @param string $dbPassword The database password.
 *
 *  @return bool True on success, false on failure.
 */
function create_user_review(
    $productId,
    $username,
    $rating,
    $comment,
    $dbHost,
    $dbName,
    $dbUser,
    $dbPassword
) {
    // Validate inputs -  CRUCIAL for security!  Expand this as needed.
    if (empty($productId) || empty($username) || empty($rating) || empty($comment)) {
        error_log("Missing required review fields."); // Log for debugging
        return false;
    }

    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        error_log("Invalid rating provided. Rating must be a number between 1 and 5.");
        return false;
    }

    // Database connection (using PDO - recommended)
    try {
        $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Throw exceptions on errors
    } catch (PDOException $e) {
        error_log("Database connection error: " . $e->getMessage());
        return false;
    }

    // Prepare and execute the SQL statement
    $sql = "INSERT INTO reviews (product_id, username, rating, comment) VALUES (:product_id, :username, :rating, :comment)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':product_id', $productId);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':comment', $comment);

    if ($stmt->execute()) {
        return true;
    } else {
        error_log("Error executing review insert query: " . print_r($stmt->errorInfo(), true)); // Detailed error logging
        return false;
    }
}

// Example Usage (Illustrative - replace with your data)
// $product_id = '123';
// $username = 'john.doe';
// $rating = 4;
// $comment = 'Great product!  Easy to use.';
//
// if (create_user_review($product_id, $username, $rating, $comment, 'localhost', 'my_database', 'my_user', 'my_password')) {
//     echo "Review created successfully!";
// } else {
//     echo "Failed to create review.";
// }


/*  Example Database Table Structure (MySQL)
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL,
    rating INT NOT NULL,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
*/
?>


<?php

/**
 * User Review Function
 *
 * This function allows you to store, retrieve, and display user reviews for a given item.
 *
 * @param string $item_id The unique identifier for the item being reviewed.
 * @param string $user_name The name of the user submitting the review.
 * @param string $review_text The text of the review.
 * @param int $rating  The rating provided by the user (e.g., 1-5).
 * @param string $db_connection  The established database connection object.  Crucial for database interaction.
 *
 * @return array  An array containing success/failure status and an optional list of reviews.
 *                 Returns an empty array on error.
 */
function store_user_review(string $item_id, string $user_name, string $review_text, int $rating, $db_connection) {
  // Validate input (important for security and data integrity)
  if (empty($item_id) || empty($user_name) || empty($review_text) || $rating < 1 || $rating > 5) {
    return ['success' => false, 'message' => 'Invalid input data.  Item ID, user name, and review text must be filled. Rating must be between 1 and 5.'];
  }

  // Sanitize input (essential for security - prevent SQL injection)
  $item_id = mysqli_real_escape_string($db_connection, $item_id);
  $user_name = mysqli_real_escape_string($db_connection, $user_name);
  $review_text = mysqli_real_escape_string($db_connection, $review_text);
  $rating = (int)$rating; // Cast rating to integer

  // SQL query (using prepared statements - the best practice)
  $sql = "INSERT INTO reviews (item_id, user_name, review_text, rating) VALUES (?, ?, ?, ?)";
  $stmt = mysqli_prepare($db_connection, $sql);

  if ($stmt === false) {
    // Handle the error
    return ['success' => false, 'message' => 'Error preparing statement: ' . mysqli_error($db_connection)];
  }

  mysqli_stmt_bind_param($stmt, "ssis", $item_id, $user_name, $rating); // 'ssis' - string, string, integer, string

  if (mysqli_stmt_execute($stmt) === false) {
    // Handle the error
    mysqli_stmt_close($stmt);
    return ['success' => false, 'message' => 'Error executing statement: ' . mysqli_error($db_connection)];
  }

  mysqli_stmt_close($stmt);

  // Optionally, fetch the newly created review (for confirmation)
  $result = mysqli_query($db_connection, "SELECT * FROM reviews WHERE item_id = '$item_id' ORDER BY review_id DESC LIMIT 1");
  if ($result) {
    $review = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
  } else {
     // Handle error in fetching.  This is unlikely but good to check.
     return ['success' => false, 'message' => 'Error fetching review after insertion: ' . mysqli_error($db_connection)];
  }

  return ['success' => true, 'review' => $review];
}


// --- Example Usage (requires a database connection, assume 'db_connection' is already established) ---
// (Important:  Replace this with your actual database connection details.)

// Assuming you have a database connection named $db_connection

// $review_data = store_user_review("product_123", "John Doe", "Great product!", 5, $db_connection);

// if ($review_data['success']) {
//   echo "Review submitted successfully! Review ID: " . $review_data['review']['review_id'];
// } else {
//   echo "Error submitting review: " . $review_data['message'];
// }


// --- Example Database Table Creation (MySQL) ---
/*
CREATE TABLE reviews (
  review_id INT AUTO_INCREMENT PRIMARY KEY,
  item_id VARCHAR(255) NOT NULL,
  user_name VARCHAR(255) NOT NULL,
  review_text TEXT NOT NULL,
  rating INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
*/


?>

