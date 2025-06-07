
<?php
// Connect to database
$host = 'localhost';
$dbUsername = 'username';
$dbPassword = 'password';
$dbName = 'reviews_db';

$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to display reviews
function displayReviews() {
    global $conn;
    
    $sql = "SELECT * FROM reviews ORDER BY review_id DESC";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div class='review'>";
            echo "<h3>" . htmlspecialchars($row['user_name']) . "</h3>";
            echo "<p>" . htmlspecialchars($row['review_text']) . "</p>";
            echo "<div class='rating'>Rating: " . $row['rating'] . " stars</div>";
            echo "<small>Posted on: " . $row['timestamp'] . "</small>";
            echo "</div>";
        }
    } else {
        echo "<div class='no-reviews'>No reviews available.</div>";
    }
}

// Function to add a new review
function addReview($userName, $reviewText, $rating) {
    global $conn;
    
    // Sanitize inputs
    $userName = htmlspecialchars(trim($userName));
    $reviewText = htmlspecialchars(trim($reviewText));
    $rating = intval($rating);
    
    if ($userName == "" || $reviewText == "") {
        return "Please fill in all required fields.";
    }
    
    if ($rating < 1 || $rating > 5) {
        return "Rating must be between 1 and 5 stars.";
    }
    
    // Insert into database
    $sql = "INSERT INTO reviews (user_name, review_text, rating, timestamp)
            VALUES (?, ?, ?, CURRENT_TIMESTAMP)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $userName, $reviewText, $rating);
    
    if ($stmt->execute()) {
        return "Review added successfully!";
    } else {
        return "Error adding review: " . $conn->error;
    }
}

// Function to calculate average rating
function getAverageRating() {
    global $conn;
    
    $sql = "SELECT AVG(rating) as average FROM reviews";
    $result = $conn->query($sql);
    
    if ($row = $result->fetch_assoc()) {
        return round($row['average'], 1); // Round to one decimal place
    }
    
    return 0;
}

// Close database connection
$conn->close();
?>


<?php
include 'review_functions.php';

$reviewStatus = addReview($_POST['user_name'], $_POST['review_text'], $_POST['rating']);

if ($reviewStatus == "Review added successfully!") {
    header("Location: reviews_page.php"); // Redirect back to reviews page
} else {
    echo $reviewStatus;
}
?>


<?php include 'review_functions.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Product Reviews</title>
    <style>
        .review {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 10px;
        }
        .rating {
            color: #ff9f00;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>Product Reviews</h2>
    
    <!-- Average Rating -->
    <div class="average-rating">
        <span>Average Rating:</span>
        <?php 
        $avgRating = getAverageRating();
        echo "<span class='rating'>$avgRating stars</span>";
        ?>
    </div>

    <!-- Review Form -->
    <?php include 'review_form.html'; ?>

    <!-- Display Reviews -->
    <?php displayReviews(); ?>
</body>
</html>


<?php
// submit_review.php

// Database connection details
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "reviews_db";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get product ID from URL or session (modify as needed)
$product_id = 1; // Example product ID

// Validate and sanitize input
$name = htmlspecialchars(trim($_POST['name']));
$email = htmlspecialchars(trim($_POST['email']));
$rating = intval($_POST['rating']);
$comment = htmlspecialchars(trim($_POST['comment']));

if ($name == "" || $email == "" || $comment == "") {
    die("All fields are required!");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format!");
}

if ($rating < 1 || $rating > 5) {
    die("Rating must be between 1 and 5!");
}

// Insert review into database
$sql = "INSERT INTO reviews (product_id, name, email, rating, comment)
VALUES ('$product_id', '$name', '$email', '$rating', '$comment')";

if (mysqli_query($conn, $sql)) {
    // Redirect to thank you page or show success message
    header("Location: review_success.php");
} else {
    die("Error inserting review: " . mysqli_error($conn));
}

mysqli_close($conn);
?>


<?php
// display_reviews.php

function fetch_reviews($product_id) {
    $servername = "localhost";
    $username = "username";
    $password = "password";
    $dbname = "reviews_db";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT * FROM reviews WHERE product_id = $product_id ORDER BY review_date DESC";
    $result = mysqli_query($conn, $sql);

    if ($result === FALSE) {
        die("Error fetching reviews: " . mysqli_error($conn));
    }

    $reviews = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $reviews[] = $row;
    }

    mysqli_close($conn);
    return $reviews;
}

function display_reviews() {
    global $product_id; // Make sure to set product_id appropriately

    $reviews = fetch_reviews($product_id);

    if (empty($reviews)) {
        echo "<p>No reviews available yet.</p>";
        return;
    }

    foreach ($reviews as $review) {
        $rating_stars = str_repeat("★", $review['rating']) . str_repeat("☆", 5 - $review['rating']);
        $date = date('F j, Y', strtotime($review['review_date']));

        echo <<<EOD
<div class="review">
    <h3>{$review['name']}</h3>
    <p>Rating: {$rating_stars}</p>
    <p>Email: {$review['email']}</p>
    <p>Comment: {$review['comment']}</p>
    <small>Reviewed on: $date</small>
</div><br>

EOD;
    }
}
?>


function calculate_average_rating($product_id) {
    $servername = "localhost";
    $username = "username";
    $password = "password";
    $dbname = "reviews_db";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT AVG(rating) as average FROM reviews WHERE product_id = $product_id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    $average_rating = round($row['average'], 1);
    echo "<h2>Average Rating: {$average_rating}/5</h2>";

    mysqli_close($conn);
}


<?php
// Database connection details
$host = 'localhost';
$username = 'username';
$password = 'password';
$dbname = 'reviews_db';

// Connect to database
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to create review table if it doesn't exist
function createReviewTable() {
    global $conn;
    
    $sql = "CREATE TABLE IF NOT EXISTS reviews (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        user_name VARCHAR(100) NOT NULL,
        user_email VARCHAR(100) NOT NULL,
        comment TEXT NOT NULL,
        rating INT NOT NULL,
        timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
    )";
    
    if (!mysqli_query($conn, $sql)) {
        echo "Error creating table: " . mysqli_error($conn);
    }
}

// Function to insert a new review into the database
function insertReview($productId, $userName, $userEmail, $comment, $rating) {
    global $conn;
    
    // Sanitize inputs
    $userName = htmlspecialchars(trim($userName));
    $userEmail = htmlspecialchars(trim($userEmail));
    $comment = htmlspecialchars(trim($comment));
    $rating = intval($rating);
    
    // Prepare SQL statement
    $stmt = mysqli_prepare($conn, "INSERT INTO reviews (product_id, user_name, user_email, comment, rating) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "issss", $productId, $userName, $userEmail, $comment, $rating);
    
    if (mysqli_stmt_execute($stmt)) {
        // Redirect to avoid form resubmission
        header("Location: product.php?id=$productId");
        exit();
    } else {
        echo "Error inserting review: " . mysqli_error($conn);
    }
    
    mysqli_stmt_close($stmt);
}

// Function to display reviews for a product
function displayReviews($productId) {
    global $conn;
    
    // Get all reviews for the specified product
    $sql = "SELECT * FROM reviews WHERE product_id = ? ORDER BY timestamp DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $productId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    // Calculate average rating
    $averageRating = calculateAverageRating($productId);
    
    echo "<div class='reviews-container'>";
        echo "<h2>Customer Reviews</h2>";
        echo "<p>Average Rating: " . number_format($averageRating, 2) . " / 5</p>";
        
        // Display star rating
        displayStarRating($averageRating);
        
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='review-item'>";
                    echo "<div class='user-details'>";
                        echo "<strong>" . $row['user_name'] . "</strong>";
                        displayStarRating($row['rating']);
                    echo "</div>";
                    echo "<p class='comment'>" . $row['comment'] . "</p>";
                    echo "<p class='timestamp'>Posted on: " . date('F j, Y', strtotime($row['timestamp'])) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No reviews yet.</p>";
        }
    echo "</div>";
    
    mysqli_stmt_close($stmt);
}

// Function to calculate average rating
function calculateAverageRating($productId) {
    global $conn;
    
    $sql = "SELECT AVG(rating) AS average FROM reviews WHERE product_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $productId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    
    return $row['average'] ?: 0;
}

// Function to display star rating
function displayStarRating($rating) {
    $fullStars = intval($rating);
    $hasHalfStar = ($rating - $fullStars) >= 0.5;
    
    echo "<div class='stars'>";
        for ($i = 1; $i <= $fullStars; $i++) {
            echo "<span class='star'>★</span>";
        }
        
        if ($hasHalfStar) {
            echo "<span class='star half'>⯨</span>";
        }
        
        // Fill remaining stars with empty
        for ($i = $fullStars + ($hasHalfStar ? 1 : 0); $i < 5; $i++) {
            echo "<span class='star'>☆</span>";
        }
    echo "</div>";
}

// Close database connection
mysqli_close($conn);
?>


<?php
function manageUserReviews($conn, $productId, $userId = null, $rating = null, $comment = null, $displayReviews = false) {
    // If all parameters are provided except displayReviews, assume we're submitting a review
    if ($userId !== null && $rating !== null && $comment !== null) {
        try {
            // Check if the user has already reviewed this product
            $checkReviewQuery = "SELECT COUNT(*) AS count FROM reviews WHERE product_id = ? AND user_id = ?";
            $stmt = mysqli_prepare($conn, $checkReviewQuery);
            mysqli_stmt_bind_param($stmt, "ii", $productId, $userId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);

            if ($row['count'] == 0) {
                // Insert the new review
                $insertReviewQuery = "INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $insertReviewQuery);
                mysqli_stmt_bind_param($stmt, "iiis", $productId, $userId, $rating, $comment);
                mysqli_stmt_execute($stmt);
                echo "Thank you for submitting your review!";
            } else {
                // Review already exists
                echo "You have already submitted a review for this product.";
            }
        } catch (mysqli_sql_exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    if ($displayReviews) {
        try {
            // Get all reviews for the specified product
            $getReviewsQuery = "
                SELECT r.rating, r.comment, u.full_name 
                FROM reviews r 
                LEFT JOIN users u ON r.user_id = u.id 
                WHERE r.product_id = ?
                ORDER BY r.review_date DESC";
            
            $stmt = mysqli_prepare($conn, $getReviewsQuery);
            mysqli_stmt_bind_param($stmt, "i", $productId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            // Calculate average rating
            $averageRating = 0;
            $reviewCount = 0;

            while ($row = mysqli_fetch_assoc($result)) {
                $averageRating += $row['rating'];
                $reviewCount++;
            }

            if ($reviewCount > 0) {
                $averageRating /= $reviewCount;
                // Reset result pointer to display reviews
                mysqli_data_seek($result, 0);
            } else {
                echo "No reviews available for this product.";
                return;
            }

            // Display average rating
            echo "<h3>Average Rating: " . number_format($averageRating, 1) . "/5</h3>";
            
            // Display each review
            while ($row = mysqli_fetch_assoc($result)) {
                $ratingStars = str_repeat("⭐", $row['rating']);
                $fullName = $row['full_name'] ?? 'Anonymous';
                echo "<div class='review'>";
                echo "<p><strong>$fullName</strong> gave " . $ratingStars . "</p>";
                echo "<p>" . htmlspecialchars($row['comment']) . "</p>";
                echo "</div>";
            }
        } catch (mysqli_sql_exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>


manageUserReviews($conn, 1, 5, 4, "Great product!");


manageUserReviews($conn, 1, null, null, null, true);


<?php
// Function to handle and display user reviews
function userReviewSystem() {
    // Review data array
    $reviews = [];
    
    // Check if review form has been submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Sanitize input data
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $rating = intval($_POST['rating']);
        $comment = htmlspecialchars($_POST['comment']);
        
        // Validate input
        if ($name == "" || $email == "" || $rating < 1 || $rating > 5 || $comment == "") {
            echo "Please fill in all fields with valid information!";
            exit;
        }
        
        // Create review array
        $review = [
            'name' => $name,
            'email' => $email,
            'rating' => $rating,
            'comment' => $comment,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        // Load existing reviews from file
        if (file_exists('reviews.json')) {
            $json_data = file_get_contents('reviews.json');
            $reviews = json_decode($json_data, true);
        }
        
        // Add new review
        array_unshift($reviews, $review); // Add to beginning of array
        
        // Save reviews back to file
        $json_output = json_encode($reviews, JSON_PRETTY_PRINT);
        file_put_contents('reviews.json', $json_output);
    }
    
    // Load existing reviews from file
    if (file_exists('reviews.json')) {
        $json_data = file_get_contents('reviews.json');
        $reviews = json_decode($json_data, true);
    }
    
    // Display review form
    ?>
    <div class="review-container">
        <h2>Submit a Review</h2>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name"><br><br>
            
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email"><br><br>
            
            <label for="rating">Rating:</label><br>
            <select id="rating" name="rating">
                <option value="5">5 Stars</option>
                <option value="4">4 Stars</option>
                <option value="3">3 Stars</option>
                <option value="2">2 Stars</option>
                <option value="1">1 Star</option>
            </select><br><br>
            
            <label for="comment">Comment:</label><br>
            <textarea id="comment" name="comment" rows="5"></textarea><br><br>
            
            <input type="submit" value="Submit Review">
        </form>
        
        <?php if (!empty($reviews)): ?>
            <h2>Reviews</h2>
            <div class="review-list">
                <?php foreach ($reviews as $review): ?>
                    <div class="review-item">
                        <div class="review-header">
                            <span class="review-name"><?php echo $review['name']; ?></span>
                            <div class="review-stars">
                                <?php 
                                for ($i = 1; $i <= $review['rating']; $i++) {
                                    echo '<span class="star">★</span>';
                                }
                                for ($i = $review['rating'] + 1; $i <= 5; $i++) {
                                    echo '<span class="star">☆</span>';
                                }
                                ?>
                            </div>
                        </div>
                        <p><?php echo $review['comment']; ?></p>
                        <small><?php echo $review['timestamp']; ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <style>
        .review-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            font-family: Arial, sans-serif;
        }
        
        h2 {
            color: #333;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }
        
        form {
            margin-bottom: 30px;
        }
        
        input[type="text"], input[type="email"], textarea, select {
            width: 100%;
            padding: 8px;
            margin: 5px 0 20px 0;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }
        
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            width: 100%;
        }
        
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        
        .review-list {
            margin-top: 20px;
        }
        
        .review-item {
            border-bottom: 1px solid #ddd;
            padding: 15px 0;
        }
        
        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .star {
            color: #FFD700;
            font-size: 24px;
        }
        
        small {
            color: #666;
            display: block;
            margin-top: 5px;
        }
    </style>
    <?php
}

// Call the review system function
userReviewSystem();
?>


<?php
// Initialize reviews array (you would typically use a database in a real application)
$reviews = array();

function handleReviews($action, $username = "", $rating = 0, $comment = "") {
    global $reviews;
    
    switch ($action) {
        case "submit":
            // Add new review to the array
            $review_id = count($reviews); // Simple ID system using count
            $new_review = array(
                "id" => $review_id,
                "username" => $username,
                "rating" => $rating,
                "comment" => $comment,
                "date" => date("Y-m-d H:i:s")
            );
            array_push($reviews, $new_review);
            break;
            
        case "display":
            // Display all reviews
            if (empty($reviews)) {
                echo "<p>No reviews yet!</p>";
            } else {
                echo "<div class='review-list'>";
                foreach ($reviews as $review) {
                    echo "<div class='review'>";
                    echo "<h4>Rating: {$review['rating']} stars</h4>";
                    echo "<p><strong>{$review['username']}</strong></p>";
                    echo "<p>{$review['comment']}</p>";
                    echo "<small>Posted on {$review['date']}</small>";
                    echo "</div>";
                }
                echo "</div>";
                
                // Display average rating
                $average_rating = calculateAverageRating();
                echo "<div class='average-rating'>";
                echo "Average Rating: $average_rating stars";
                echo "</div>";
            }
            break;
            
        case "delete":
            // Delete a review by username
            foreach ($reviews as $key => $review) {
                if ($review['username'] == $username) {
                    unset($reviews[$key]);
                    array_values($reviews); // Re-index the array
                }
            }
            break;
    }
}

function calculateAverageRating() {
    global $reviews;
    
    if (empty($reviews)) {
        return 0;
    } else {
        $total_ratings = array_sum(array_column($reviews, 'rating'));
        $average_rating = $total_ratings / count($reviews);
        return number_format($average_rating, 1); // Round to one decimal place
    }
}

// Example usage:
// Submit a new review
handleReviews("submit", "JohnDoe", 5, "Great product! Highly recommended.");

// Display all reviews with average rating
echo "<h2>Product Reviews</h2>";
handleReviews("display");

// Delete a review by username
handleReviews("delete", "JohnDoe");
?>



// Submit a new review
handleReviews("submit", "JaneSmith", 4, "Good product but could be better.");

// Display all reviews
echo "<h2>Product Reviews</h2>";
handleReviews("display");

// Delete a specific review
handleReviews("delete", "JaneSmith");


<?php
function createReview($product_id) {
    // Database connection parameters
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbname = "reviews_db";

    // Connect to database
    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get review data from POST request
    $review_name = trim($_POST['review_name']);
    $review_email = trim($_POST['review_email']);
    $rating = $_POST['rating'];
    $comments = trim($_POST['comments']);

    // Validate input
    if (empty($review_name) || empty($review_email) || empty($rating) || empty($comments)) {
        echo "Please fill in all required fields.";
        return;
    }

    if (!filter_var($review_email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        return;
    }

    // Insert review into database
    $sql = "INSERT INTO reviews (product_id, name, email, rating, comments)
            VALUES ('$product_id', '$review_name', '$review_email', '$rating', '$comments')";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>
                Thank you for your review!
              </div>";
        
        // Redirect to the same page after 2 seconds
        header("Refresh: 2; url=view_reviews.php?id=$product_id");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close database connection
    $conn->close();
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    createReview($_GET['id']);
}
?>


<?php
// Function to create user review for a product
function createReview($reviewText, $rating, $productId) {
    // Database connection parameters
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "my_store";

    try {
        // Create database connection
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Validate input
        if (empty($reviewText) || !isset($rating) || !isset($productId)) {
            return "Error: All fields must be filled out!";
        }

        // Sanitize inputs to prevent SQL injection
        $reviewText = htmlspecialchars(strip_tags(trim($reviewText)));
        $rating = intval($rating);
        $productId = intval($productId);

        // Check if rating is valid (assuming scale of 1-5)
        if ($rating < 1 || $rating > 5) {
            return "Error: Invalid rating!";
        }

        // Insert review into database
        $stmt = $conn->prepare("INSERT INTO reviews (
            product_id, 
            review_text,
            rating,
            created_at
        ) VALUES (
            :productId,
            :reviewText,
            :rating,
            NOW()
        )");

        // Bind parameters
        $stmt->bindParam(':productId', $productId);
        $stmt->bindParam(':reviewText', $reviewText);
        $stmt->bindParam(':rating', $rating);

        // Execute query
        $stmt->execute();

        // Return success message with review ID
        $lastInsertId = $conn->lastInsertId();
        return "Review submitted successfully! Review ID: $lastInsertId";

    } catch(PDOException $e) {
        // Return error message if something goes wrong
        return "Error: " . $e->getMessage();
    }
}

// Example usage:
$product_id = 123;
$user_review = "This is a great product!";
$rating = 5;

$result = createReview($user_review, $rating, $product_id);
echo $result;
?>


<?php
class UserReview {
    public $userName;
    public $comment;
    public $rating;

    function __construct($name, $comment, $rating) {
        $this->userName = $name;
        $this->comment = $comment;
        $this->rating = $rating;
    }
}

function displayReviews($reviews) {
    // Display each review
    foreach ($reviews as $review) {
        echo "<div class='review'>";
        echo "<h3>Reviewed by: " . htmlspecialchars($review->userName) . "</h3>";
        echo "<p>" . htmlspecialchars($review->comment) . "</p>";
        
        // Generate stars based on rating
        $stars = "";
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $review->rating) {
                $stars .= "★";
            } else {
                $stars .= "☆";
            }
        }
        echo "<div class='stars'>$stars</div>";
        echo "</div><br>";
    }

    // Calculate average rating
    if (!empty($reviews)) {
        $totalRating = array_sum(array_column($reviews, 'rating'));
        $averageRating = $totalRating / count($reviews);
        echo "<div class='average-rating'>";
        echo "Average Rating: " . number_format($averageRating, 1) . "/5";
        echo "</div>";
    } else {
        echo "No reviews available.";
    }
}

// Example usage:
$review1 = new UserReview("Alice", "Great product! Highly recommended.", 4);
$review2 = new UserReview("Bob", "Good quality but could be better.", 3);
$reviews = array($review1, $review2);

displayReviews($reviews);
?>


<?php
// Database connection details
$host = 'localhost';
$user = 'username';
$password = 'password';
$db_name = 'reviews_db';

// Create database connection
$conn = new mysqli($host, $user, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to submit a review
function submit_review($review_id, $user_id, $rating, $comment, $conn) {
    // Sanitize input
    $review_id = mysqli_real_escape_string($conn, $review_id);
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $rating = mysqli_real_escape_string($conn, $rating);
    $comment = mysqli_real_escape_string($conn, $comment);

    // Insert review into database
    $sql = "INSERT INTO reviews (review_id, user_id, rating, comment, review_date) 
            VALUES (?, ?, ?, ?, NOW())";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssis", $review_id, $user_id, $rating, $comment);
        $stmt->execute();
        $stmt->close();
        return true;
    } else {
        return false;
    }
}

// Function to display reviews
function display_reviews($item_id, $conn) {
    // Get all reviews for the item
    $sql = "SELECT r.review_id, u.user_name, r.rating, r.comment, r.review_date 
            FROM reviews r 
            JOIN users u ON r.user_id = u.user_id 
            WHERE r.item_id = ? 
            ORDER BY r.review_date DESC";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $item_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            // Display each review
            echo "<div class='review'>";
            echo "<h3>Review by: " . $row['user_name'] . "</h3>";
            echo "<p>Rating: " . str_repeat("★", $row['rating']) . "</p>";
            echo "<p>" . $row['comment'] . "</p>";
            echo "<small>Posted on: " . $row['review_date'] . "</small>";
            echo "</div>";
        }
        
        $stmt->close();
    }
}

// Function to calculate average rating
function calculate_average_rating($item_id, $conn) {
    // Calculate average rating
    $sql = "SELECT AVG(rating) as average 
            FROM reviews 
            WHERE item_id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $item_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            return round($row['average'], 1);
        }
        
        $stmt->close();
    }
    
    return 0;
}

// Example usage:
$item_id = "item123";

// Connect to database
$conn = new mysqli($host, $user, $password, $db_name);

// Display all reviews for the item
display_reviews($item_id, $conn);

// Calculate and display average rating
$average_rating = calculate_average_rating($item_id, $conn);
echo "<div class='average-rating'>";
echo "Average Rating: " . str_repeat("★", $average_rating) . " ($average_rating/5)";
echo "</div>";

// Close database connection
$conn->close();

?>


<?php
// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'reviews_db';

// Create database connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Function to add a new review
function add_review($user_id, $rating, $comment, $conn) {
    // Sanitize input
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $rating = mysqli_real_escape_string($conn, $rating);
    $comment = mysqli_real_escape_string($conn, $comment);
    $timestamp = date('Y-m-d H:i:s');

    // SQL query to insert review
    $sql = "INSERT INTO reviews (user_id, rating, comment, timestamp) 
            VALUES ('$user_id', '$rating', '$comment', '$timestamp')";
    
    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        return false;
    }
}

// Function to get all reviews
function get_reviews($conn) {
    // SQL query to fetch reviews
    $sql = "SELECT * FROM reviews ORDER BY timestamp DESC";
    $result = mysqli_query($conn, $sql);
    
    $reviews = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $reviews[] = $row;
    }
    
    return $reviews;
}

// Close database connection
mysqli_close($conn);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Review System</title>
    <style>
        .review-form { margin-bottom: 20px; }
        .review-stars { font-size: 24px; }
        .review-comment { margin-top: 10px; }
    </style>
</head>
<body>

<h2>Submit a Review</h2>
<div class="review-form">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="user_id" value="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'guest'; ?>">
        <label for="rating">Rating:</label><br>
        <select id="rating" name="rating">
            <option value="5">5 Stars</option>
            <option value="4">4 Stars</option>
            <option value="3">3 Stars</option>
            <option value="2">2 Stars</option>
            <option value="1">1 Star</option>
        </select><br><br>
        
        <label for="comment">Comment:</label><br>
        <textarea id="comment" name="comment" rows="4" cols="50"></textarea><br><br>
        
        <input type="submit" value="Submit Review">
    </form>
</div>

<?php
// Handle review submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    
    // Connect to database again for this operation
    $conn = mysqli_connect($host, $username, $password, $dbname);
    if (add_review($user_id, $rating, $comment, $conn)) {
        echo "<script>alert('Review submitted successfully!');</script>";
    } else {
        echo "<script>alert('Error submitting review. Please try again.');</script>";
    }
    mysqli_close($conn);
}

// Display existing reviews
$conn = mysqli_connect($host, $username, $password, $dbname);
$reviews = get_reviews($conn);
mysqli_close($conn);

if (!empty($reviews)) {
    echo "<h2>Customer Reviews</h2>";
    foreach ($reviews as $review) {
        echo "<div style='border: 1px solid #ddd; padding: 10px; margin-bottom: 10px;'>";
        echo "<p><strong>User ID:</strong> " . $review['user_id'] . "</p>";
        echo "<div class='review-stars'>";
        for ($i = 1; $i <= $review['rating']; $i++) {
            echo "★";
        }
        echo "</div>";
        echo "<p class='review-comment'>" . $review['comment'] . "</p>";
        echo "<small>Posted on: " . $review['timestamp'] . "</small>";
        echo "</div>";
    }
} else {
    echo "<p>No reviews available.</p>";
}
?>

</body>
</html>


<?php
// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$db_name = 'reviews_db';

$conn = mysqli_connect($host, $user, $password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to add a review
function add_review($product_id, $rating, $review_text, $user_id) {
    global $conn;
    
    // Check if the user is logged in (you should implement your own authentication system)
    if (!$user_id) {
        return "You must be logged in to post a review.";
    }
    
    // Validate input
    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        return "Rating must be between 1 and 5 stars.";
    }
    
    if (empty($review_text)) {
        return "Review text cannot be empty.";
    }
    
    // Escape special characters to prevent SQL injection
    $product_id = mysqli_real_escape_string($conn, $product_id);
    $rating = mysqli_real_escape_string($conn, $rating);
    $review_text = mysqli_real_escape_string($conn, $review_text);
    $user_id = mysqli_real_escape_string($conn, $user_id);
    
    // Insert review into database
    $sql = "INSERT INTO reviews (product_id, rating, review_text, user_id) 
            VALUES ($product_id, $rating, '$review_text', $user_id)";
            
    if (!mysqli_query($conn, $sql)) {
        return "Error: " . mysqli_error($conn);
    }
    
    return "Review added successfully!";
}

// Function to display reviews
function display_reviews($product_id) {
    global $conn;
    
    // Escape special characters to prevent SQL injection
    $product_id = mysqli_real_escape_string($conn, $product_id);
    
    // Get reviews from database
    $sql = "SELECT r.*, u.username 
            FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            WHERE product_id = $product_id 
            AND status = 'approved' 
            ORDER BY review_date DESC";
            
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        die("Error: " . mysqli_error($conn));
    }
    
    // Display reviews
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='review'>";
        echo "<h4>Review by: " . $row['username'] . "</h4>";
        echo "<p>Rating: " . $row['rating'] . "/5</p>";
        echo "<p>" . $row['review_text'] . "</p>";
        echo "<small>Posted on: " . $row['review_date'] . "</small>";
        echo "</div><br/>";
    }
}

// Function to edit a review
function edit_review($review_id, $rating, $review_text) {
    global $conn;
    
    // Check if the user is logged in (you should implement your own authentication system)
    $user_id = $_SESSION['user_id'];
    
    // Validate input
    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        return "Rating must be between 1 and 5 stars.";
    }
    
    if (empty($review_text)) {
        return "Review text cannot be empty.";
    }
    
    // Escape special characters to prevent SQL injection
    $review_id = mysqli_real_escape_string($conn, $review_id);
    $rating = mysqli_real_escape_string($conn, $rating);
    $review_text = mysqli_real_escape_string($conn, $review_text);
    $user_id = mysqli_real_escape_string($conn, $user_id);
    
    // Update review in database
    $sql = "UPDATE reviews 
            SET rating = $rating, 
                review_text = '$review_text', 
                review_date = NOW() 
            WHERE id = $review_id 
            AND user_id = $user_id";
            
    if (!mysqli_query($conn, $sql)) {
        return "Error: " . mysqli_error($conn);
    }
    
    return "Review updated successfully!";
}

// Function to delete a review
function delete_review($review_id) {
    global $conn;
    
    // Check if the user is logged in (you should implement your own authentication system)
    $user_id = $_SESSION['user_id'];
    
    // Escape special characters to prevent SQL injection
    $review_id = mysqli_real_escape_string($conn, $review_id);
    $user_id = mysqli_real_escape_string($conn, $user_id);
    
    // Delete review from database
    $sql = "DELETE FROM reviews 
            WHERE id = $review_id 
            AND user_id = $user_id";
            
    if (!mysqli_query($conn, $sql)) {
        return "Error: " . mysqli_error($conn);
    }
    
    return "Review deleted successfully!";
}

// Example usage:
if (isset($_POST['submit_review'])) {
    $result = add_review(1, $_POST['rating'], $_POST['review_text'], 1);
    echo $result;
}

display_reviews(1);

mysqli_close($conn);
?>


<?php
// Database configuration
$host = "localhost";
$username = "username";
$password = "password";
$db_name = "reviews_db";

// Connect to the database
$conn = mysqli_connect($host, $username, $password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to sanitize input data
function sanitizeInput($data) {
    return htmlspecialchars(trim($data));
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form values
    $productId = $_POST['product_id'];
    $userName = $_POST['user_name'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    // Validate input data
    if (empty($productId) || empty($userName) || empty($rating) || empty($comment)) {
        echo "All fields are required!";
    } else {

        // Check if product_id is numeric
        if (!is_numeric($productId)) {
            die("Product ID must be a number.");
        }

        // Sanitize inputs
        $productId = sanitizeInput($productId);
        $userName = sanitizeInput($userName);
        $rating = sanitizeInput($rating);
        $comment = sanitizeInput($comment);

        // Check if rating is between 1 and 5
        if ($rating < 1 || $rating > 5) {
            die("Rating must be between 1 and 5.");
        }

        // Prepare SQL statement to insert review
        $stmt = $conn->prepare("INSERT INTO reviews (product_id, user_name, rating, comment) VALUES (?, ?, ?, ?)");
        
        if ($stmt === false) {
            die("Prepare failed: " . mysqli_error($conn));
        }

        // Bind parameters
        $stmt->bind_param("isdi", $productId, $userName, $rating, $comment);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Thank you for your review!";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    }
}

// Close database connection
mysqli_close($conn);
?>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'reviews';

// Create a connection to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to add a review
function add_review($product_id, $user_id, $rating, $comment) {
    global $conn;
    
    // Validate inputs
    if (!isset($product_id) || !is_numeric($product_id)) {
        return 'Invalid product ID';
    }
    if (!isset($user_id) || !is_numeric($user_id)) {
        return 'Invalid user ID';
    }
    if (!isset($rating) || !is_numeric($rating) || $rating < 1 || $rating > 5) {
        return 'Rating must be between 1 and 5';
    }
    if (empty($comment)) {
        return 'Comment cannot be empty';
    }
    
    // Sanitize inputs
    $product_id = mysqli_real_escape_string($conn, $product_id);
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $rating = mysqli_real_escape_string($conn, $rating);
    $comment = mysqli_real_escape_string($conn, $comment);
    
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        return 'Please log in to post a review';
    }
    
    // Insert review into database
    $sql = "INSERT INTO reviews (product_id, user_id, rating, comment, date) 
            VALUES ('$product_id', '$user_id', '$rating', '$comment', NOW())";
    
    if ($conn->query($sql)) {
        return 'Review added successfully';
    } else {
        return 'Error adding review: ' . $conn->error;
    }
}

// Function to display reviews for a product
function display_reviews($product_id) {
    global $conn;
    
    // Validate input
    if (!isset($product_id) || !is_numeric($product_id)) {
        return 'Invalid product ID';
    }
    
    // Get reviews from database
    $sql = "SELECT r.rating, r.comment, r.date, u.username 
            FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            WHERE r.product_id = '$product_id' 
            ORDER BY r.date DESC";
    
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $reviews = array();
        
        // Calculate average rating
        $average_rating = 0;
        $total_reviews = $result->num_rows;
        
        while ($row = $result->fetch_assoc()) {
            $average_rating += $row['rating'];
            $reviews[] = $row;
        }
        
        $average_rating /= $total_reviews;
        
        return array(
            'reviews' => $reviews,
            'average_rating' => $average_rating,
            'total_reviews' => $total_reviews
        );
    } else {
        return 'No reviews found for this product';
    }
}

// Example usage:
// Assuming user is logged in and has a session with user_id set

// Add a review
$review_result = add_review(1, $_SESSION['user_id'], 4, "Great product!");
echo $review_result;

// Display reviews for product ID 1
$reviews = display_reviews(1);
if (is_array($reviews)) {
    echo "<h3>Average Rating: " . number_format($reviews['average_rating'], 1) . "/5</h3>";
    echo "<p>Total Reviews: " . $reviews['total_reviews'] . "</p>";
    
    foreach ($reviews['reviews'] as $review) {
        echo "<div class='review'>";
        echo "<p><strong>" . $review['username'] . "</strong> - " . $review['rating'] . "/5</p>";
        echo "<p>" . $review['comment'] . "</p>";
        echo "<p>" . $review['date'] . "</p>";
        echo "</div>";
    }
} else {
    echo $reviews;
}

$conn->close();
?>


<?php
// Function to handle user reviews
function submitReview() {
    // Check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get review data from POST request
        $review_name = $_POST['review_name'];
        $review_email = $_POST['review_email'];
        $review_rating = $_POST['review_rating'];
        $review_text = $_POST['review_text'];

        // Validate input
        if (empty($review_name) || empty($review_email) || empty($review_rating) || empty($review_text)) {
            return "Please fill in all required fields.";
        }

        // Sanitize inputs to prevent SQL injection or XSS attacks
        $review_name = htmlspecialchars(strip_tags(trim($review_name)));
        $review_email = htmlspecialchars(strip_tags(trim($review_email)));
        $review_rating = intval($review_rating);
        $review_text = htmlspecialchars(trim($review_text));

        // Set up the review array
        $review = [
            'id' => time(), // Use timestamp as unique identifier
            'name' => $review_name,
            'email' => $review_email,
            'rating' => $review_rating,
            'text' => $review_text,
            'date' => date('Y-m-d H:i:s') // Current date and time
        ];

        // Load existing reviews from file or create a new array
        if (file_exists('reviews.txt')) {
            $reviews_data = file_get_contents('reviews.txt');
            $reviews = json_decode($reviews_data, true);
            
            if ($reviews === null) {
                $reviews = [];
            }
        } else {
            // Create the reviews file if it doesn't exist
            fopen('reviews.txt', 'w');
            $reviews = array();
        }

        // Add new review to the array
        $reviews[] = $review;

        // Save updated reviews to the file
        $success = file_put_contents(
            'reviews.txt',
            json_encode($reviews, JSON_PRETTY_PRINT)
        );

        if ($success !== false) {
            // Redirect to thank you page or display success message
            header('Location: thank-you.php');
            exit();
        } else {
            return "Error saving review. Please try again later.";
        }
    }

    // If not POST request, show error
    return "Invalid request method.";
}

// Call the function when form is submitted
if (isset($_POST['submit_review'])) {
    $result = submitReview();
    if ($result !== true) {
        echo '<div class="error">'.$result.'</div>';
    }
}
?>


<?php
header("Refresh: 3; url=index.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Thank You for Your Review</title>
</head>
<body>
    <h1>Thank You!</h1>
    <p>Your review has been submitted successfully. We appreciate your feedback!</p>
    <p>You will be redirected back to the main page in 3 seconds...</p>
</body>
</html>


<?php
// Function to add new review
function add_review($conn, $product_id, $user_name, $comment, $rating) {
    // Insert review into database
    $sql = "INSERT INTO product_reviews (product_id, user_name, comment, rating, post_date)
            VALUES (?, ?, ?, ?, NOW())";
    
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$product_id, $user_name, $comment, $rating]);
        
        return true;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

// Function to get average rating
function get_average_rating($conn, $product_id) {
    $sql = "SELECT AVG(rating) as average FROM product_reviews WHERE product_id = ?";
    
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$product_id]);
        $result = $stmt->fetch();
        
        return round($result['average'], 1);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return 0;
    }
}

// Function to display reviews
function display_reviews($conn, $product_id) {
    $sql = "SELECT * FROM product_reviews WHERE product_id = ? ORDER BY post_date DESC";
    
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$product_id]);
        $reviews = $stmt->fetchAll();
        
        // Calculate average rating
        $average_rating = get_average_rating($conn, $product_id);
        
        echo '<div class="review-container">';
        echo "<h3>Average Rating: $average_rating/5</h3>";
        
        if (count($reviews) > 0) {
            foreach ($reviews as $review) {
                $user_name = $review['user_name'];
                $comment = $review['comment'];
                $rating = $review['rating'];
                $post_date = date('d/m/Y', strtotime($review['post_date']));
                
                // Display star rating
                echo '<div class="review">';
                echo "<h4>$user_name</h4>";
                echo "<p>Rating: ";
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $rating) {
                        echo "★";
                    } else {
                        echo "☆";
                    }
                }
                echo "</p>";
                echo "<p>$comment</p>";
                echo "<small>Posted on: $post_date</small>";
                echo '</div>';
            }
        } else {
            echo '<p>No reviews yet. Be the first to review this product!</p>';
        }
        
        echo '</div>';
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>


<?php
// Connect to database
try {
    $conn = new PDO("mysql:host=localhost;dbname=your_database", "username", "password");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Display reviews for product ID 1
display_reviews($conn, 1);

// Add a new review
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = 1;
    $user_name = $_POST['username'];
    $comment = $_POST['comment'];
    $rating = $_POST['rating'];
    
    add_review($conn, $product_id, $user_name, comment, rating);
}

// Close connection
$conn = null;
?>


<?php
// Database connection details
$host = 'localhost';
$username = 'username';
$password = 'password';
$dbname = 'reviews_db';

// Create database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function user_review($action, $product_id = 0, $user_id = 0, $rating = 0, $comment = '') {
    global $conn;

    try {
        switch ($action) {
            // Submit a new review
            case 'submit':
                if (empty($product_id) || empty($user_id) || empty($rating)) {
                    throw new Exception("Missing required parameters");
                }

                // Validate rating between 1 and 5 stars
                if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
                    throw new Exception("Rating must be between 1 and 5");
                }

                // Sanitize input
                $product_id = (int)$conn->real_escape_string($product_id);
                $user_id = (int)$conn->real_escape_string($user_id);
                $rating = (int)$conn->real_escape_string($rating);
                $comment = $conn->real_escape_string($comment);

                // Insert review into database
                $sql = "INSERT INTO reviews (product_id, user_id, rating, comment, created_at) 
                        VALUES ($product_id, $user_id, $rating, '$comment', NOW())";
                
                if (!$conn->query($sql)) {
                    throw new Exception("Error submitting review: " . $conn->error);
                }

                return "Review submitted successfully!";
                break;

            // Get all reviews for a product
            case 'get_reviews':
                if (empty($product_id)) {
                    throw new Exception("Product ID is required");
                }

                $sql = "SELECT * FROM reviews 
                        WHERE product_id = $product_id 
                        ORDER BY created_at DESC";
                
                $result = $conn->query($sql);

                if (!$result) {
                    throw new Exception("Error fetching reviews: " . $conn->error);
                }

                // Return reviews as an array
                $reviews = array();
                while ($row = $result->fetch_assoc()) {
                    $reviews[] = $row;
                }

                return $reviews;
                break;

            // Get average rating for a product
            case 'get_average_rating':
                if (empty($product_id)) {
                    throw new Exception("Product ID is required");
                }

                $sql = "SELECT AVG(rating) as average FROM reviews WHERE product_id = $product_id";
                
                $result = $conn->query($sql);

                if (!$result) {
                    throw new Exception("Error calculating average rating: " . $conn->error);
                }

                $row = $result->fetch_assoc();
                return round($row['average'], 1); // Round to one decimal place
                break;

            // Submit a vote for a review
            case 'vote':
                if (empty($review_id) || empty($user_id) || empty($vote)) {
                    throw new Exception("Missing required parameters");
                }

                $vote = intval($vote);

                // Check if user has already voted on this review
                $sql = "SELECT * FROM votes WHERE review_id = $review_id AND user_id = $user_id";
                
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    throw new Exception("You have already voted for this review");
                }

                // Insert vote into database
                $sql = "INSERT INTO votes (review_id, user_id, vote) 
                        VALUES ($review_id, $user_id, $vote)";
                
                if (!$conn->query($sql)) {
                    throw new Exception("Error submitting vote: " . $conn->error);
                }

                // Update review vote count
                $vote_change = ($vote == 1) ? 'votes_up' : 'votes_down';
                $sql = "UPDATE reviews SET $vote_change = $vote_change + 1 WHERE id = $review_id";
                
                if (!$conn->query($sql)) {
                    throw new Exception("Error updating vote count: " . $conn->error);
                }

                return "Vote submitted successfully!";
                break;

            default:
                throw new Exception("Invalid action");
        }
    } catch (Exception $e) {
        return array('error' => $e->getMessage());
    }
}

// Example usage:

// Submit a review
$submit_review = user_review(
    'submit',
    product_id: 1,
    user_id: 1,
    rating: 4,
    comment: "Great product, highly recommend!"
);

// Get all reviews for product ID 1
$get_reviews = user_review('get_reviews', product_id: 1);

// Get average rating for product ID 1
$average_rating = user_review('get_average_rating', product_id: 1);

// Submit a vote (1 = upvote, -1 = downvote)
submit_vote = user_review(
    'vote',
    review_id: 1,
    user_id: 1,
    vote: 1
);

?>


<?php
// Database connection details
$host = 'localhost';
$user = 'username';
$password = 'password';
$db_name = 'reviews_db';

// Function to create a user review
function createReview($userId, $productId, $rating, $reviewText) {
    global $host, $user, $password, $db_name;
    
    // Connect to the database
    $conn = new mysqli($host, $user, $password, $db_name);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        return false;
    }
    
    // SQL query to insert review data
    $sql = "INSERT INTO reviews (user_id, product_id, rating, review_text, timestamp)
            VALUES (?, ?, ?, ?, NOW())";
            
    // Prepare and bind the statement
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
        return false;
    }
    
    $stmt->bind_param("iisi", $userId, $productId, $rating, $reviewText);
    
    // Execute the statement
    if ($stmt->execute()) {
        // Review created successfully
        $stmt->close();
        $conn->close();
        return true;
    } else {
        // Error creating review
        die("Error: " . $sql . "<br>" . $conn->error);
        $stmt->close();
        $conn->close();
        return false;
    }
}

// Example usage:
$userId = 1;          // Replace with actual user ID
$productId = 1;       // Replace with actual product ID
$rating = 5;          // Rating between 1-5
$reviewText = "This is a great product!"; 

if (createReview($userId, $productId, $rating, $reviewText)) {
    echo "Thank you for your review!";
} else {
    echo "Error submitting your review. Please try again.";
}
?>


<?php
// Include your createReview() function here

$userId = $_POST['user_id'];
$productId = $_POST['product_id'];
$rating = (int)$_POST['rating'];
$reviewText = htmlspecialchars($_POST['review_text'], ENT_QUOTES, 'UTF-8');

if ($rating < 1 || $rating > 5) {
    die("Invalid rating. Please choose a rating between 1 and 5.");
}

if (empty($reviewText)) {
    die("Please write your review.");
}

if (createReview($userId, $productId, $rating, $reviewText)) {
    echo "Thank you for your review!";
} else {
    echo "Error submitting your review. Please try again.";
}
?>


<?php
// Function to create and display reviews
function user_reviews() {
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $rating = $_POST['rating'];
        $comment = htmlspecialchars($_POST['comment']);

        // Validate input
        if (empty($name) || empty($email) || empty($rating) || empty($comment)) {
            echo "All fields must be filled out!";
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format!";
            exit();
        }

        // Store review in a file
        $review = array(
            'name' => $name,
            'email' => $email,
            'rating' => $rating,
            'comment' => $comment,
            'date' => date('Y-m-d H:i:s')
        );

        $reviews_file = 'reviews.txt';
        $reviews_content = serialize($review) . PHP_EOL;

        if (file_put_contents($reviews_file, $reviews_content, FILE_APPEND | LOCK_EX)) {
            echo "Thank you for your review!";
        } else {
            echo "Error storing review. Please try again later.";
        }
    }

    // Display existing reviews
    $reviews_file = 'reviews.txt';
    if (file_exists($reviews_file) && filesize($reviews_file) > 0) {
        $reviews_data = file_get_contents($reviews_file);
        $reviews = explode(PHP_EOL, $reviews_data);

        echo "<h2>Previous Reviews:</h2>";
        echo "<div class='reviews'>";

        $total_rating = 0;
        $review_count = 0;

        foreach ($reviews as $review_line) {
            if (trim($review_line) != '') {
                $review = unserialize($review_line);
                $total_rating += $review['rating'];
                $review_count++;

                echo "<div class='review'>";
                echo "<h3>" . $review['name'] . "</h3>";
                echo "<p>Rating: " . str_repeat("★", $review['rating']) . "</p>";
                echo "<p>" . nl2br($review['comment']) . "</p>";
                echo "<small>Posted on: " . $review['date'] . "</small>";
                echo "</div>";
            }
        }

        // Calculate and display average rating
        if ($review_count > 0) {
            $average_rating = $total_rating / $review_count;
            echo "<h3>Average Rating: " . round($average_rating, 1) . "/5</h3>";
        } else {
            echo "<p>No reviews available yet.</p>";
        }

        echo "</div>";
    } else {
        echo "<p>No reviews available yet.</p>";
    }
}

// Display the review form
function display_review_form() {
    echo <<<HTML
    <form method="post" action="">
        <h2>Submit a Review</h2>
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br>

        <label for="rating">Rating:</label><br>
        <select id="rating" name="rating" required>
            <option value="">Select Rating</option>
            <option value="1">1 Star</option>
            <option value="2">2 Stars</option>
            <option value="3">3 Stars</option>
            <option value="4">4 Stars</option>
            <option value="5">5 Stars</option>
        </select><br>

        <label for="comment">Comment:</label><br>
        <textarea id="comment" name="comment" rows="5" required></textarea><br>

        <input type="submit" value="Submit Review">
    </form>
HTML;
}
?>


<?php
// Database configuration
$host = 'localhost';
$dbname = 'reviews_db';
$username = 'root';
$password = '';

// Create database connection
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function insertReview($userId, $productId, $reviewText, $rating, $conn) {
    // Sanitize inputs
    $reviewText = mysqli_real_escape_string($conn, htmlspecialchars($reviewText));
    
    $sql = "INSERT INTO reviews (user_id, product_id, review_text, rating)
            VALUES ('$userId', '$productId', '$reviewText', '$rating')";
            
    if ($conn->query($sql)) {
        return true;
    } else {
        return false;
    }
}

function getReviews($productId, $page = 1, $itemsPerPage = 5, $conn) {
    // Calculate offset
    $offset = ($page - 1) * $itemsPerPage;
    
    $sql = "SELECT r.review_id, r.user_id, r.product_id, 
            r.review_text, r.rating, u.username,
            DATE_FORMAT(r.created_at, '%Y-%m-%d %H:%i') as created_at
            FROM reviews r
            JOIN users u ON r.user_id = u.user_id
            WHERE r.product_id = '$productId'
            ORDER BY r.created_at DESC
            LIMIT $itemsPerPage OFFSET $offset";
    
    $result = $conn->query($sql);
    $reviews = array();
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            // Sanitize output
            $row['review_text'] = htmlspecialchars($row['review_text']);
            $row['username'] = htmlspecialchars($row['username']);
            
            $reviews[] = $row;
        }
        return $reviews;
    } else {
        return false;
    }
}

function countReviews($productId, $conn) {
    $sql = "SELECT COUNT(*) as total_reviews FROM reviews WHERE product_id = '$productId'";
    
    $result = $conn->query($sql);
    
    if ($result) {
        $row = $result->fetch_assoc();
        return $row['total_reviews'];
    } else {
        return 0;
    }
}

// Example usage:

// Insert a new review
$userId = 1;
$productId = 1;
$reviewText = "This is an excellent product!";
$rating = 5;

if (insertReview($userId, $productId, $reviewText, $rating, $conn)) {
    echo "Review submitted successfully!";
} else {
    echo "Error submitting review: " . $conn->error;
}

// Get reviews for product
$page = 1;
$itemsPerPage = 5;
$reviews = getReviews($productId, $page, $itemsPerPage, $conn);

if ($reviews) {
    foreach ($reviews as $review) {
        echo "<div class='review'>";
        echo "<strong>Rating:</strong> " . $review['rating'] . "/5<br>";
        echo "<strong>Author:</strong> " . $review['username'] . "<br>";
        echo "<strong>Review:</strong> " . $review['review_text'] . "<br>";
        echo "<strong>Date:</strong> " . $review['created_at'] . "<br>";
        echo "</div>";
    }
} else {
    echo "No reviews found.";
}

// Close database connection
$conn->close();
?>


<?php
function submitUserReview($review, $rating) {
    // Validate inputs
    if (!is_string($review) || !ctype_digit((string)$rating)) {
        return false;
    }
    $rating = (int)$rating;
    if ($rating < 1 || $rating > 5) {
        return false;
    }

    // Database connection details
    $host = 'localhost';
    $dbUsername = 'root';
    $dbPassword = '';
    $dbName = 'your_database';

    // Connect to database
    $conn = mysqli_connect($host, $dbUsername, $dbPassword, $dbName);
    if (!$conn) {
        return false;
    }

    // Prepare SQL statement
    $username = $_SESSION['username']; // Ensure user is logged in and session exists
    $date = date('Y-m-d H:i:s');

    $stmt = mysqli_prepare($conn, "INSERT INTO user_reviews (username, review_text, rating, review_date) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        mysqli_close($conn);
        return false;
    }

    // Bind parameters
    mysqli_stmt_bind_param($stmt, 'ssii', $username, $review, $rating, $date);

    // Execute statement
    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return false;
    }

    // Close connections
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return true;
}
?>


<?php
// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'reviews';

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to display reviews
function display_reviews() {
    global $conn;
    
    // Retrieve reviews from database
    $sql = "SELECT * FROM reviews ORDER BY submission_date DESC";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='review'>";
            echo "<h3>" . htmlspecialchars($row['name'], ENT_QUOTES) . "</h3>";
            echo "<p>Rating: " . str_repeat("★", $row['rating']) . "</p>";
            echo "<p>" . htmlspecialchars($row['comment'], ENT_QUOTES) . "</p>";
            echo "<small>Submitted on: " . $row['submission_date'] . "</small>";
            echo "</div>";
        }
    } else {
        echo "<p>No reviews yet. Be the first to review!</p>";
    }
}

// Function to handle review submission
function submit_review() {
    global $conn;
    
    // Check if form was submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = mysqli_real_escape_string($conn, htmlspecialchars($_POST['name'], ENT_QUOTES));
        $rating = intval($_POST['rating']);
        $comment = mysqli_real_escape_string($conn, htmlspecialchars($_POST['comment'], ENT_QUOTES));
        
        // Validate input
        if (empty($name) || empty($comment)) {
            echo "<p class='error'>Please fill in all required fields.</p>";
            return;
        }
        
        if ($rating < 1 || $rating > 5) {
            echo "<p class='error'>Please select a valid rating between 1 and 5 stars.</p>";
            return;
        }
        
        // Insert review into database
        $sql = "INSERT INTO reviews (name, rating, comment, submission_date)
                VALUES ('$name', '$rating', '$comment', NOW())";
                
        if (!mysqli_query($conn, $sql)) {
            echo "<p class='error'>Error submitting review: " . mysqli_error($conn) . "</p>";
        } else {
            echo "<p class='success'>Thank you for your review!</p>";
            // Redirect to avoid form resubmission
            header("Location: ?submitted=1");
            exit();
        }
    }
}

// Main function to handle reviews
function main() {
    // Check if review was submitted
    if (isset($_GET['submitted'])) {
        echo "<div class='success-message'>Your review has been submitted successfully!</div>";
    }
    
    // Display review form
    echo "<form method='post' action='" . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES) . "'>";
    echo "<h2>Submit a Review</h2>";
    echo "<div class='form-group'>";
    echo "<label for='name'>Name:</label>";
    echo "<input type='text' id='name' name='name' required>";
    echo "</div>";
    
    echo "<div class='form-group'>";
    echo "<label for='rating'>Rating:</label>";
    echo "<select id='rating' name='rating' required>";
    echo "<option value='5'>★ ★ ★ ★ ★</option>";
    echo "<option value='4'>★ ★ ★ ★</option>";
    echo "<option value='3'>★ ★ ★</option>";
    echo "<option value='2'>★ ★</option>";
    echo "<option value='1'>★</option>";
    echo "</select>";
    echo "</div>";
    
    echo "<div class='form-group'>";
    echo "<label for='comment'>Comment:</label>";
    echo "<textarea id='comment' name='comment' rows='5' required></textarea>";
    echo "</div>";
    
    echo "<button type='submit'>Submit Review</button>";
    echo "</form>";
    
    // Display existing reviews
    echo "<h2>Reviews</h2>";
    display_reviews();
}

// Create the reviews table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS reviews (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        rating INT NOT NULL,
        comment TEXT NOT NULL,
        submission_date DATETIME DEFAULT CURRENT_TIMESTAMP
    )";

mysqli_query($conn, $sql);

// Run the main function
main();

// Close database connection
mysqli_close($conn);
?>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'reviews_db';

// Connect to database
$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to submit a review
function submit_review($user_id, $product_id, $rating, $comment) {
    global $conn;
    
    // Sanitize input data
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $product_id = mysqli_real_escape_string($conn, $product_id);
    $rating = mysqli_real_escape_string($conn, $rating);
    $comment = mysqli_real_escape_string($conn, $comment);
    
    // Get current timestamp
    $timestamp = time();
    
    // Insert review into database
    $sql = "INSERT INTO reviews (user_id, product_id, rating, comment, timestamp) 
            VALUES ('$user_id', '$product_id', '$rating', '$comment', '$timestamp')";
    
    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        return false;
    }
}

// Function to get reviews for a specific product
function get_reviews($product_id) {
    global $conn;
    
    // Sanitize input data
    $product_id = mysqli_real_escape_string($conn, $product_id);
    
    // Get all reviews for the specified product
    $sql = "SELECT * FROM reviews WHERE product_id = '$product_id' ORDER BY timestamp DESC";
    $result = mysqli_query($conn, $sql);
    
    $reviews = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $reviews[] = $row;
    }
    
    return $reviews;
}

// Example usage:

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $user_id = $_SESSION['user_id']; // Assuming user is logged in
    $product_id = $_GET['product_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    
    if (submit_review($user_id, $product_id, $rating, $comment)) {
        echo "Review submitted successfully!";
    } else {
        echo "Error submitting review: " . mysqli_error($conn);
    }
}

// Display reviews for a product
$product_id = 1; // Set your product ID here
$reviews = get_reviews($product_id);

if (!empty($reviews)) {
    foreach ($reviews as $review) {
        echo "<div class='review'>";
        echo "<div class='rating'>";
        for ($i = 0; $i < $review['rating']; $i++) {
            echo "<span class='star'>★</span>";
        }
        echo "</div>";
        echo "<p>" . $review['comment'] . "</p>";
        echo "<small>Posted on: " . date('F j, Y', $review['timestamp']) . "</small>";
        echo "</div>";
    }
} else {
    echo "No reviews available.";
}

// Close database connection
mysqli_close($conn);
?>


<?php
// Connect to the database
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "review_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to display reviews
function display_reviews() {
    global $conn;
    
    // SQL query to fetch all reviews
    $sql = "SELECT * FROM reviews ORDER BY review_id DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div class='review'>";
            echo "<h3>" . htmlspecialchars($row["user_name"]) . "</h3>";
            echo "<p>Rating: " . str_repeat("★", $row["rating"]) . "</p>";
            echo "<p>" . htmlspecialchars($row["comment"]) . "</p>";
            echo "<small>Posted on: " . $row["timestamp"] . "</small>";
            echo "</div>";
        }
    } else {
        echo "<p>No reviews available.</p>";
    }
}

// Function to add a new review
function add_review($user_name, $comment, $rating) {
    global $conn;
    
    // Sanitize inputs
    $user_name = htmlspecialchars(strip_tags(trim($user_name)));
    $comment = htmlspecialchars(strip_tags(trim($comment)));
    $rating = intval($rating);

    // Check if fields are not empty
    if ($user_name == "" || $comment == "" || $rating == 0) {
        return "Please fill in all required fields.";
    }

    // Insert review into database
    $sql = "INSERT INTO reviews (user_name, comment, rating)
    VALUES (?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $user_name, $comment, $rating);

    if ($stmt->execute()) {
        return true;
    } else {
        return "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Function to calculate average rating
function get_average_rating() {
    global $conn;
    
    $sql = "SELECT AVG(rating) as average FROM reviews";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return number_format((float)$row["average"], 1, '.', '');
    } else {
        return "No ratings yet.";
    }
}

// Close database connection
$conn->close();
?>


<?php
// Database configuration
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'review_system';

// Connect to database
$conn = mysqli_connect($host, $user, $password, $database);

// Function to sanitize input data
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $rating = intval($_POST['rating']);
    $comment = sanitizeInput($_POST['comment']);

    // Validate input data
    if ($name == '' || $email == '' || $rating < 1 || $rating > 5 || $comment == '') {
        echo "Please fill in all required fields!";
    } else {
        // Check email format
        if (!preg_match("/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/", $email)) {
            echo "Please enter a valid email address!";
        } else {
            // Insert review into database
            $sql = "INSERT INTO reviews (user_name, user_email, rating, comment) 
                    VALUES ('$name', '$email', '$rating', '$comment')";
            
            if (mysqli_query($conn, $sql)) {
                echo "Thank you for submitting your review!";
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
    }
}

// Function to display reviews
function displayReviews() {
    global $conn;
    $sql = "SELECT * FROM reviews ORDER BY submission_date DESC";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='review'>";
            echo "<h3>" . $row['user_name'] . "</h3>";
            echo "<p>Rating: " . str_repeat("★", $row['rating']) . "</p>";
            echo "<p>Email: " . $row['user_email'] . "</p>";
            echo "<p>Comment: " . $row['comment'] . "</p>";
            echo "<small>Submitted on: " . date('jS M Y', strtotime($row['submission_date'])) . "</small>";
            echo "</div>";
        }
    } else {
        echo "No reviews found!";
    }
}

// Close database connection
mysqli_close($conn);
?>


<?php
function submitUserReview($reviewText, $rating, $productId) {
    // Input validation
    if (empty($reviewText) || !is_numeric($rating) || $rating < 1 || $rating > 5 || !isset($productId)) {
        throw new InvalidArgumentException("Invalid review data");
    }

    // Database connection
    $conn = mysqli_connect("localhost", "username", "password", "database_name");
    if (!$conn) {
        throw new RuntimeException("Database connection failed: " . mysqli_error($conn));
    }

    try {
        // Sanitize inputs
        $reviewText = mysqli_real_escape_string($conn, $reviewText);
        $rating = intval($rating);
        $productId = intval($productId);

        // Insert into database
        $sql = "INSERT INTO reviews (product_id, review_text, rating, date_created) 
                VALUES ($productId, '$reviewText', $rating, NOW())";

        if (mysqli_query($conn, $sql)) {
            return true;
        } else {
            throw new RuntimeException("Error inserting review: " . mysqli_error($conn));
        }
    } catch (Exception $e) {
        // Log the error or handle as needed
        return false;
    } finally {
        mysqli_close($conn);
    }
}

// Example usage and testing
try {
    if (isset($_POST['submit'])) {
        $reviewText = $_POST['review_text'];
        $rating = $_POST['rating'];
        $productId = 1; // Replace with actual product ID

        if (submitUserReview($reviewText, $rating, $productId)) {
            echo "Thank you for your review!";
        } else {
            echo "Error submitting review. Please try again.";
        }
    }
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
}
?>


<?php
// Database connection parameters
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

// Connect to the database
$conn = mysqli_connect($host, $username, $password, $database);

// Check for connection errors
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to display the review form
function displayReviewForm() {
    ?>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="rating">Rating:</label>
            <select id="rating" name="rating" required>
                <option value="">Select a rating</option>
                <option value="5">5 Stars</option>
                <option value="4">4 Stars</option>
                <option value="3">3 Stars</option>
                <option value="2">2 Stars</option>
                <option value="1">1 Star</option>
            </select>
        </div>
        <div>
            <label for="comment">Comment:</label>
            <textarea id="comment" name="comment" rows="4" required></textarea>
        </div>
        <button type="submit" name="submit_review">Submit Review</button>
    </form>
    <?php
}

// Function to save a review to the database
function saveReview($conn) {
    if (isset($_POST['submit_review'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $rating = intval($_POST['rating']);
        $comment = mysqli_real_escape_string($conn, $_POST['comment']);

        // Check if this is an edit or a new review
        if (isset($_GET['review_id'])) {
            $review_id = intval($_GET['review_id']);
            $sql = "UPDATE reviews SET name='$name', email='$email', rating='$rating', comment='$comment' WHERE id='$review_id'";
        } else {
            $sql = "INSERT INTO reviews (name, email, rating, comment) VALUES ('$name', '$email', '$rating', '$comment')";
        }

        if (mysqli_query($conn, $sql)) {
            echo "Review saved successfully!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

// Function to display all reviews
function displayReviews($conn) {
    $sql = "SELECT * FROM reviews ORDER BY date DESC";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="review">
                <h3><?php echo $row['name']; ?></h3>
                <p>Email: <?php echo $row['email']; ?></p>
                <p>Rating: <?php echo str_repeat("★", $row['rating']); ?></p>
                <p>Comment: <?php echo $row['comment']; ?></p>
                <small><?php echo date('j F Y, g:i a', strtotime($row['date'])); ?></small>
            </div>
            <?php
        }
    } else {
        echo "No reviews found.";
    }
}

// Main function to handle the review process
function handleReviews() {
    global $conn;
    
    // Check if we're editing an existing review
    if (isset($_GET['review_id'])) {
        $review_id = intval($_GET['review_id']);
        
        // Display edit form with existing data
        $sql = "SELECT * FROM reviews WHERE id='$review_id'";
        $result = mysqli_query($conn, $sql);
        
        if ($row = mysqli_fetch_assoc($result)) {
            ?>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?review_id=<?php echo $review_id; ?>">
                <div>
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo $row['name']; ?>" required>
                </div>
                <div>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo $row['email']; ?>" required>
                </div>
                <div>
                    <label for="rating">Rating:</label>
                    <select id="rating" name="rating" required>
                        <?php
                        $ratings = array(5, 4, 3, 2, 1);
                        foreach ($ratings as $rating) {
                            $selected = ($rating == $row['rating']) ? "selected='selected'" : "";
                            echo "<option value='$rating' $selected>$rating Stars</option>";
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <label for="comment">Comment:</label>
                    <textarea id="comment" name="comment" rows="4"><?php echo $row['comment']; ?></textarea>
                </div>
                <button type="submit" name="submit_review">Update Review</button>
            </form>
            <?php
        } else {
            echo "Review not found.";
        }
    } else {
        // Display the review form or all reviews
        displayReviewForm();
        displayReviews($conn);
    }
}

// Call the main function
handleReviews();

// Close database connection
mysqli_close($conn);
?>


<?php
function submit_review($user_id, $product_id, $rating, $comment) {
    // Validate input
    if (empty($user_id) || empty($product_id) || empty($rating)) {
        return "All fields must be filled out";
    }
    
    if ($rating < 1 || $rating > 5) {
        return "Rating must be between 1 and 5";
    }

    // Connect to database
    $db_host = 'localhost';
    $db_name = 'your_database';
    $db_user = 'your_username';
    $db_pass = 'your_password';

    $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
    
    if (!$conn) {
        return "Database connection failed: " . mysqli_connect_error();
    }

    // Prepare and execute SQL statement
    $sql = "INSERT INTO reviews (user_id, product_id, rating, comment, review_date)
            VALUES (?, ?, ?, ?, NOW())";
            
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        return "SQL error: " . mysqli_error($conn);
    }

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "iisi", $user_id, $product_id, $rating, $comment);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        $affected_rows = mysqli_stmt_affected_rows($stmt);
        
        if ($affected_rows == 1) {
            return "Thank you for your review!";
        } else {
            return "Review submission failed";
        }
    } else {
        return "Error: " . mysqli_error($conn);
    }

    // Close statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

// Example usage:
$user_id = 1;
$product_id = 5;
$rating = 4;
$comment = "Great product! Highly recommended.";

$result = submit_review($user_id, $product_id, $rating, $comment);
echo $result;
?>


<?php
// Database connection
$host = "localhost";
$username = "username";
$password = "password";
$database = "review_db";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to add a new review
function addReview($userId, $productId, $rating, $comment) {
    global $conn;
    
    // Sanitize input
    $rating = intval($rating);
    $comment = mysqli_real_escape_string($conn, $comment);
    
    if ($rating < 1 || $rating > 5) {
        return false; // Invalid rating
    }
    
    // Insert review into database
    $sql = "INSERT INTO reviews (user_id, product_id, rating, comment, timestamp)
            VALUES ('$userId', '$productId', '$rating', '$comment', NOW())";
            
    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        return false;
    }
}

// Function to display all reviews for a product
function displayReviews($productId) {
    global $conn;
    
    $sql = "SELECT * FROM reviews WHERE product_id = '$productId'
            ORDER BY timestamp DESC";
            
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='review'>";
            echo "<h3>Rating: " . str_repeat("★", $row['rating']) . "</h3>";
            echo "<p>" . $row['comment'] . "</p>";
            echo "<small>Posted on: " . date('F j, Y', strtotime($row['timestamp'])) . "</small>";
            echo "</div>";
        }
    } else {
        echo "<p>No reviews available for this product.</p>";
    }
}

// Example usage:
?>
<!DOCTYPE html>
<html>
<head>
    <title>Product Review System</title>
    <style>
        .review {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <?php
    // Display all reviews for product ID 1
    displayReviews(1);
    
    // Add a new review (example)
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $userId = 1; // Assume user is logged in
        $productId = $_POST['product_id'];
        $rating = $_POST['rating'];
        $comment = $_POST['comment'];
        
        if (!empty($rating) && !empty($comment)) {
            addReview($userId, $productId, $rating, $comment);
            echo "<script>window.location.href='?success=1'</script>";
        }
    }
    
    // Show success message
    if (isset($_GET['success'])) {
        echo "<p>Thank you for submitting your review!</p>";
    }
    ?>
    
    <!-- Review Form -->
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <input type="hidden" name="product_id" value="1">
        
        <div>
            <label for="rating">Rating:</label><br>
            <select id="rating" name="rating">
                <option value="">Choose a rating</option>
                <option value="5">★ ★ ★ ★ ★</option>
                <option value="4">★ ★ ★ ★</option>
                <option value="3">★ ★ ★</option>
                <option value="2">★ ★</option>
                <option value="1">★</option>
            </select>
        </div><br>

        <div>
            <label for="comment">Review:</label><br>
            <textarea id="comment" name="comment"></textarea>
        </div><br>

        <button type="submit">Submit Review</button>
    </form>
</body>
</html>


<?php
// Database configuration
$host = 'localhost';
$dbname = 'reviews_db';
$username = 'root';
$password = '';

// Connect to database
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

class ReviewSystem {
    private $conn;

    public function __construct($connection) {
        $this->conn = $connection;
    }

    // Submit a new review
    public function submitReview($productId, $name, $email, $rating, $comment) {
        // Sanitize inputs
        $name = htmlspecialchars($name);
        $email = htmlspecialchars($email);
        $comment = htmlspecialchars($comment);

        // Check if all fields are filled
        if (empty($name) || empty($email) || empty($rating) || empty($comment)) {
            return "Please fill in all required fields.";
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format.";
        }

        // Validate rating (1-5)
        if ($rating < 1 || $rating > 5) {
            return "Rating must be between 1 and 5 stars.";
        }

        // Insert review into database
        $stmt = $this->conn->prepare("INSERT INTO reviews 
                                    (product_id, name, email, rating, comment, submission_date)
                                    VALUES (?, ?, ?, ?, ?, NOW())");
        
        if (!$stmt) {
            return "Database error occurred while submitting review.";
        }

        $stmt->bind_param("isids", $productId, $name, $email, $rating, $comment);

        if ($stmt->execute()) {
            return true;
        } else {
            return "Error submitting review: " . $stmt->error;
        }
    }

    // Display reviews for a product
    public function displayReviews($productId) {
        $reviews = array();
        
        $stmt = $this->conn->prepare("SELECT * FROM reviews WHERE product_id = ?");
        $stmt->bind_param("i", $productId);
        $stmt->execute();

        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $reviews[] = array(
                'id' => $row['id'],
                'name' => $row['name'],
                'rating' => $row['rating'],
                'comment' => $row['comment'],
                'submission_date' => $row['submission_date']
            );
        }

        return $this->displayReviewsHTML($reviews);
    }

    // Display reviews HTML
    private function displayReviewsHTML($reviews) {
        if (empty($reviews)) {
            return "<p>No reviews available.</p>";
        }

        $output = '<div class="reviews">';
        
        foreach ($reviews as $review) {
            $output .= '<div class="review-item">';
            $output .= '<h3>' . $review['name'] . '</h3>';
            $output .= '<div class="rating">Rating: ' . str_repeat('★', $review['rating']) . '</div>';
            $output .= '<p>' . $review['comment'] . '</p>';
            $output .= '<small>Posted on: ' . $review['submission_date'] . '</small>';
            $output .= '</div>';
        }

        $averageRating = $this->calculateAverageRating($reviews);
        $output .= '<div class="average-rating">';
        $output .= '<h4>Average Rating:</h4>';
        $output .= '<div>' . str_repeat('★', $averageRating) . '</div>';
        $output .= '</div>';

        $output .= '</div>';

        return $output;
    }

    // Calculate average rating
    private function calculateAverageRating($reviews) {
        if (empty($reviews)) {
            return 0;
        }

        $total = array_sum(array_column($reviews, 'rating'));
        $average = $total / count($reviews);
        return round($average);
    }

    // Upvote a review
    public function upvoteReview($reviewId) {
        $stmt = $this->conn->prepare("UPDATE reviews SET votes = votes + 1 WHERE id = ?");
        $stmt->bind_param("i", $reviewId);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Downvote a review
    public function downvoteReview($reviewId) {
        $stmt = $this->conn->prepare("UPDATE reviews SET votes = votes - 1 WHERE id = ?");
        $stmt->bind_param("i", $reviewId);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}

// Example usage:
$reviewSystem = new ReviewSystem($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $result = $reviewSystem->submitReview($_POST['product_id'], $_POST['name'], $_POST['email'], $_POST['rating'], $_POST['comment']);
    
    if ($result === true) {
        echo "<div class='success'>Review submitted successfully!</div>";
    } else {
        echo "<div class='error'>$result</div>";
    }
}

echo $reviewSystem->displayReviews(1); // Display reviews for product ID 1

$conn->close();
?>


<?php
class ReviewManager {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Adds a new review
    public function addReview($user_id, $product_id, $rating, $title, $content) {
        try {
            // Validate inputs
            if (!$this->isValidUser($user_id)) return ['success' => false, 'error' => 'Invalid user ID'];
            if (!$this->isProductExists($product_id)) return ['success' => false, 'error' => 'Product not found'];
            if ($rating < 1 || $rating > 5) return ['success' => false, 'error' => 'Rating must be between 1 and 5'];
            if (empty(trim($title)) || empty(trim($content))) return ['success' => false, 'error' => 'Title and content cannot be empty'];

            // Sanitize inputs
            $title = htmlspecialchars(trim($title));
            $content = htmlspecialchars(trim($content));

            $stmt = $this->pdo->prepare("INSERT INTO reviews (user_id, product_id, rating, title, content, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            if (!$stmt->execute([$user_id, $product_id, $rating, $title, $content])) {
                return ['success' => false, 'error' => 'Failed to add review'];
            }

            return ['success' => true, 'message' => 'Review added successfully'];

        } catch (PDOException $e) {
            error_log("Error adding review: " . $e->getMessage());
            return ['success' => false, 'error' => 'Database error occurred'];
        }
    }

    // Updates an existing review
    public function updateReview($review_id, $new_rating = null, $new_title = null, $new_content = null) {
        try {
            if (!$this->isReviewExists($review_id)) return ['success' => false, 'error' => 'Review not found'];

            // Prepare fields to update
            $updateFields = [];
            $params = [];

            if ($new_rating !== null) {
                if ($new_rating < 1 || $new_rating > 5) return ['success' => false, 'error' => 'Rating must be between 1 and 5'];
                $updateFields[] = "rating = ?";
                $params[] = $new_rating;
            }

            if ($new_title !== null && !empty(trim($new_title))) {
                $new_title = htmlspecialchars(trim($new_title));
                $updateFields[] = "title = ?";
                $params[] = $new_title;
            }

            if ($new_content !== null && !empty(trim($new_content))) {
                $new_content = htmlspecialchars(trim($new_content));
                $updateFields[] = "content = ?";
                $params[] = $new_content;
            }

            if (empty($updateFields)) return ['success' => false, 'error' => 'No changes provided'];

            // Check if the user is authorized to update this review
            $stmtCheckUser = $this->pdo->prepare("SELECT user_id FROM reviews WHERE id = ?");
            $stmtCheckUser->execute([$review_id]);
            $row = $stmtCheckUser->fetch(PDO::FETCH_ASSOC);
            
            // Here, you would typically check if the current user has permission (e.g., admin or original poster)
            // For simplicity, assuming only the author can update their review
            // In a real application, you'd compare with the current user's ID
            $params[] = $review_id;
            $stmtUpdate = $this->pdo->prepare("UPDATE reviews SET " . implode(', ', $updateFields) . " WHERE id = ?");
            if (!$stmtUpdate->execute($params)) {
                return ['success' => false, 'error' => 'Failed to update review'];
            }

            return ['success' => true, 'message' => 'Review updated successfully'];

        } catch (PDOException $e) {
            error_log("Error updating review: " . $e->getMessage());
            return ['success' => false, 'error' => 'Database error occurred'];
        }
    }

    // Deletes a review
    public function deleteReview($review_id) {
        try {
            if (!$this->isReviewExists($review_id)) return ['success' => false, 'error' => 'Review not found'];

            // Check user permission (e.g., admin or the review's author)
            // For simplicity, assuming only admins can delete reviews
            $stmtCheckUser = $this->pdo->prepare("SELECT user_id FROM reviews WHERE id = ?");
            $stmtCheckUser->execute([$review_id]);
            $row = $stmtCheckUser->fetch(PDO::FETCH_ASSOC);

            // Here, you'd compare with the current user's role and ID
            // If not authorized, return error

            $stmtDelete = $this->pdo->prepare("DELETE FROM reviews WHERE id = ?");
            if (!$stmtDelete->execute([$review_id])) {
                return ['success' => false, 'error' => 'Failed to delete review'];
            }

            return ['success' => true, 'message' => 'Review deleted successfully'];

        } catch (PDOException $e) {
            error_log("Error deleting review: " . $e->getMessage());
            return ['success' => false, 'error' => 'Database error occurred'];
        }
    }

    // Retrieves reviews for a product
    public function getReviews($product_id, $page = 1, $per_page = 10) {
        try {
            if (!$this->isProductExists($product_id)) return ['success' => false, 'error' => 'Product not found'];

            $offset = ($page - 1) * $per_page;

            $stmt = $this->pdo->prepare("SELECT r.*, u.username FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.product_id = ? AND r.status = 'approved' ORDER BY r.created_at DESC LIMIT ? OFFSET ?");
            $stmt->execute([$product_id, $per_page, $offset]);

            $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return ['success' => true, 'data' => $reviews];

        } catch (PDOException $e) {
            error_log("Error retrieving reviews: " . $e->getMessage());
            return ['success' => false, 'error' => 'Database error occurred'];
        }
    }

    // Calculates average rating for a product
    public function calculateAverageRating($product_id) {
        try {
            if (!$this->isProductExists($product_id)) return ['success' => false, 'error' => 'Product not found'];

            $stmt = $this->pdo->prepare("SELECT AVG(rating) as average FROM reviews WHERE product_id = ?");
            $stmt->execute([$product_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return ['success' => true, 'data' => round($result['average'], 1)];

        } catch (PDOException $e) {
            error_log("Error calculating average rating: " . $e->getMessage());
            return ['success' => false, 'error' => 'Database error occurred'];
        }
    }

    // Helper methods
    private function isValidUser($user_id) {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        return $stmt->rowCount() > 0;
    }

    private function isProductExists($product_id) {
        $stmt = $this->pdo->prepare("SELECT id FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        return $stmt->rowCount() > 0;
    }

    private function isReviewExists($review_id) {
        $stmt = $this->pdo->prepare("SELECT id FROM reviews WHERE id = ?");
        $stmt->execute([$review_id]);
        return $stmt->rowCount() > 0;
    }
}

// Usage example
try {
    // Assuming $pdo is a valid PDO instance
    $reviewManager = new ReviewManager($pdo);

    // Adding a review
    $result = $reviewManager->addReview(1, 5, 4, "Great product!", "This product exceeded my expectations.");
    print_r($result);

    // Updating a review
    $updateResult = $reviewManager->updateReview(1, null, "Even better product!", null);
    print_r($updateResult);

    // Deleting a review
    $deleteResult = $reviewManager->deleteReview(1);
    print_r($deleteResult);

    // Retrieving reviews
    $reviews = $reviewManager->getReviews(5);
    print_r($reviews);

    // Calculating average rating
    $averageRating = $reviewManager->calculateAverageRating(5);
    print_r($averageRating);

} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
}


<?php
// Database connection details
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "review_db";

// Connect to database
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to display reviews
function display_reviews() {
    global $conn;
    
    // SQL query to fetch reviews
    $sql = "SELECT * FROM reviews ORDER BY review_id DESC";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='review'>";
            echo "<h3>" . htmlspecialchars($row['author']) . "</h3>";
            echo "<p>Rating: " . str_repeat("★", $row['rating']) . "</p>";
            echo "<p>Date: " . htmlspecialchars($row['date']) . "</p>";
            echo "<p>Comment: " . htmlspecialchars($row['comment']) . "</p>";
            echo "</div>";
        }
    } else {
        echo "No reviews found.";
    }
}

// Function to add new review
function add_review($author, $rating, $comment) {
    global $conn;
    
    // SQL injection prevention
    $author = mysqli_real_escape_string($conn, htmlspecialchars($author));
    $rating = intval($rating);
    $comment = mysqli_real_escape_string($conn, htmlspecialchars($comment));
    $date = date("Y-m-d H:i:s");
    
    // Insert into database
    $sql = "INSERT INTO reviews (author, rating, comment, date) VALUES ('$author', '$rating', '$comment', '$date')";
    
    if (mysqli_query($conn, $sql)) {
        echo "Thank you for your review!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $author = $_POST['author'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    
    // Validate input
    if (empty($author) || empty($comment)) {
        echo "Please fill in all required fields.";
    } else {
        add_review($author, $rating, $comment);
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Reviews</title>
    <style>
        .review {
            border: 1px solid #ddd;
            padding: 15px;
            margin: 10px 0;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            background-color: #fff;
            border-radius: 5px;
        }
        input, textarea {
            margin-bottom: 10px;
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <h2>Leave a Review</h2>
    
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="text" name="author" placeholder="Your Name" required><br>
        <select name="rating" required>
            <option value="">Select Rating</option>
            <option value="5">5 Stars</option>
            <option value="4">4 Stars</option>
            <option value="3">3 Stars</option>
            <option value="2">2 Stars</option>
            <option value="1">1 Star</option>
        </select><br>
        <textarea name="comment" placeholder="Your Review" rows="5" required></textarea><br>
        <input type="submit" value="Submit Review">
    </form>

    <?php display_reviews(); ?>
</body>
</html>


<?php
// Database connection
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to display review form
function display_review_form() {
    ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <h3>Submit a Review</h3>
        <div class="rating">
            <label for="stars">Rating:</label><br>
            <input type="radio" name="stars" value="5"> 5
            <input type="radio" name="stars" value="4"> 4
            <input type="radio" name="stars" value="3"> 3
            <input type="radio" name="stars" value="2"> 2
            <input type="radio" name="stars" value="1"> 1
        </div>
        <br>
        <label for="comment">Comment:</label><br>
        <textarea id="comment" name="comment" rows="4" cols="50"></textarea>
        <br>
        <input type="submit" name="submit_review" value="Submit">
    </form>
    <?php
}

// Function to submit review
function submit_review($conn) {
    if (isset($_POST['submit_review'])) {
        $stars = $_POST['stars'];
        $comment = $_POST['comment'];
        
        // Sanitize inputs
        $stars = mysqli_real_escape_string($conn, $stars);
        $comment = mysqli_real_escape_string($conn, $comment);
        
        // Validate inputs
        if ($stars < 1 || $stars > 5) {
            echo "Invalid rating!";
            return;
        }
        if (empty($comment)) {
            echo "Please write a comment!";
            return;
        }
        
        // Insert review into database
        $sql = "INSERT INTO reviews (stars, comment) VALUES ('$stars', '$comment')";
        if ($conn->query($sql) === TRUE) {
            echo "Review submitted successfully!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Function to display reviews
function display_reviews($conn) {
    $sql = "SELECT * FROM reviews ORDER BY review_id DESC";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $stars = str_repeat("⭐", $row["stars"]);
            echo "<div class='review'>";
            echo "<p>Rating: $stars</p>";
            echo "<p>Comment: " . $row["comment"] . "</p>";
            echo "<p>" . date('d/m/Y H:i', strtotime($row["timestamp"])) . "</p>";
            echo "</div><br>";
        }
    } else {
        echo "No reviews found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Reviews</title>
    <style>
        .review {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 5px 0;
        }
        .rating input[type="radio"] {
            display: inline-block;
            margin-right: 5px;
        }
    </style>
</head>
<body>

<?php
display_review_form();
submit_review($conn);
echo "<h3>Reviews:</h3>";
display_reviews($conn);

$conn->close();
?>
</body>
</html>


<?php
function handleReviews($db) {
    session_start();
    
    // Check if the action parameter is set
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    switch ($action) {
        case 'display':
            displayReviews($db);
            break;
            
        case 'submit':
            submitReview($db);
            break;
            
        case 'edit':
            editReview($db);
            break;
            
        case 'delete':
            deleteReview($db);
            break;
            
        default:
            echo "Invalid action";
            break;
    }
}

function displayReviews($db) {
    $query = "SELECT r.*, u.name 
              FROM reviews r 
              JOIN users u ON r.user_id = u.id 
              ORDER BY r.review_date DESC";
    
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($reviews as $review) {
        echo "<div class='review'>";
        echo "<h3>Reviewer: {$review['name']}</h3>";
        echo "<p>Rating: " . str_repeat("★", $review['rating']) . "</p>";
        echo "<p>Review: {$review['comment']}</p>";
        echo "<small>Posted on: {$review['review_date']}</small><br>";
        
        if ($_SESSION['user_id'] == $review['user_id']) {
            echo "<a href='edit_review.php?id={$review['id']}'>Edit</a> | ";
            echo "<a href='delete_review.php?id={$review['id']}'>Delete</a>";
        }
        
        echo "</div><hr>";
    }
}

function submitReview($db) {
    if (!isset($_SESSION['user_id'])) {
        die(json_encode(['status' => 'error', 'message' => 'You must be logged in to post a review!']));
    }

    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $rating = intval($_POST['rating']);
    $comment = htmlspecialchars($_POST['comment']);

    if (empty($name) || empty($email) || $rating == 0 || empty($comment)) {
        die(json_encode(['status' => 'error', 'message' => 'Please fill in all required fields!']));
    }

    $stmt = $db->prepare("INSERT INTO reviews 
                        SET user_id = :user_id,
                            name = :name,
                            email = :email,
                            rating = :rating,
                            comment = :comment,
                            review_date = NOW()");
    
    $result = $stmt->execute([
        'user_id' => $_SESSION['user_id'],
        'name' => $name,
        'email' => $email,
        'rating' => $rating,
        'comment' => $comment
    ]);
    
    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Review submitted successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error submitting review!']);
    }
}

function editReview($db) {
    $review_id = intval($_POST['id']);
    
    if (!isset($_SESSION['user_id'])) {
        die(json_encode(['status' => 'error', 'message' => 'You must be logged in to edit a review!']));
    }

    // Verify ownership of the review
    $stmt = $db->prepare("SELECT user_id FROM reviews WHERE id = :id");
    $stmt->execute(['id' => $review_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result['user_id'] != $_SESSION['user_id']) {
        die(json_encode(['status' => 'error', 'message' => 'You are not authorized to edit this review!']));
    }

    $rating = intval($_POST['rating']);
    $comment = htmlspecialchars($_POST['comment']);

    if ($rating == 0 || empty($comment)) {
        die(json_encode(['status' => 'error', 'message' => 'Please fill in all required fields!']));
    }

    $stmt = $db->prepare("UPDATE reviews 
                        SET rating = :rating,
                            comment = :comment
                        WHERE id = :id");
    
    $result = $stmt->execute([
        'id' => $review_id,
        'rating' => $rating,
        'comment' => $comment
    ]);
    
    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Review updated successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error updating review!']);
    }
}

function deleteReview($db) {
    $review_id = intval($_POST['id']);
    
    if (!isset($_SESSION['user_id'])) {
        die(json_encode(['status' => 'error', 'message' => 'You must be logged in to delete a review!']));
    }

    // Verify ownership of the review
    $stmt = $db->prepare("SELECT user_id FROM reviews WHERE id = :id");
    $stmt->execute(['id' => $review_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result['user_id'] != $_SESSION['user_id']) {
        die(json_encode(['status' => 'error', 'message' => 'You are not authorized to delete this review!']));
    }

    $stmt = $db->prepare("DELETE FROM reviews WHERE id = :id");
    $result = $stmt->execute(['id' => $review_id]);
    
    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Review deleted successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error deleting review!']);
    }
}
?>


<?php
require 'database.php'; // Include your PDO connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    handleReviews($db);
}
?>


<?php
// Database connection details
$host = 'localhost';
$username = 'username';
$password = 'password';
$dbname = 'reviews_db';

// Connect to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to display reviews
function displayReviews() {
    global $conn;
    
    // SQL query to fetch all reviews from the database
    $sql = "SELECT name, review FROM reviews ORDER BY id DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div class='review'>";
            echo "<h4>" . htmlspecialchars($row["name"]) . "</h4>";
            echo "<p>" . htmlspecialchars($row["review"]) . "</p>";
            echo "</div>";
        }
    } else {
        echo "No reviews available.";
    }
}

// Function to handle review submission
function submitReview() {
    global $conn;
    
    // Check if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get and sanitize input data
        $name = htmlspecialchars(trim($_POST['name']));
        $review = htmlspecialchars(trim($_POST['review']));
        
        // Validate inputs
        if (empty($name) || empty($review)) {
            echo "Please fill in all fields.";
            return;
        }
        
        // Prepare the SQL statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO reviews (name, review) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $review);
        
        if ($stmt->execute()) {
            echo "Thank you for your review!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        
        // Close the statement
        $stmt->close();
    }
}

// Display form for submitting reviews
function displayReviewForm() {
    echo "<div class='review-form'>";
    echo "<h2>Submit Your Review</h2>";
    echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
    echo "<input type='text' name='name' placeholder='Your Name' required><br>";
    echo "<textarea name='review' placeholder='Write your review here...' required></textarea><br>";
    echo "<button type='submit'>Submit Review</button>";
    echo "</form>";
    echo "</div>";
}

// Main function to run the application
function main() {
    // Display review form
    displayReviewForm();
    
    // Handle review submission if form was submitted
    submitReview();
    
    // Display all reviews
    displayReviews();
}

// Run the main function
main();

// Close database connection
$conn->close();
?>


<?php
// Function to handle user reviews
function submitReview($userId, $rating, $comment) {
    // Database connection details
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'reviews';

    try {
        // Connect to the database
        $conn = new mysqli($host, $username, $password, $database);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Validate inputs
        if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
            throw new Exception('Invalid rating. Please select a rating between 1 and 5.');
        }
        
        if (empty($comment)) {
            throw new Exception('Please enter your review comment.');
        }

        // Sanitize inputs
        $rating = intval($rating);
        $comment = htmlspecialchars(strip_tags($comment));

        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO reviews (user_id, rating, comment, date) VALUES (?, ?, ?, NOW())");
        if (!$stmt) {
            throw new Exception('Error preparing statement.');
        }

        // Bind parameters
        $stmt->bind_param("iis", $userId, $rating, $comment);

        // Execute the statement
        if (!$stmt->execute()) {
            throw new Exception('Error submitting review. Please try again later.');
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();

        return true;
    } catch (Exception $e) {
        // Handle any errors that occurred
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['rating'], $_POST['comment'])) {
        // Assuming user authentication is in place and $userId is defined
        $userId = 1; // Replace with actual user ID
        submitReview($userId, $_POST['rating'], $_POST['comment']);
    }
}
?>


function getReviews() {
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'reviews';

    try {
        $conn = new mysqli($host, $username, $password, $database);
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch reviews from the database
        $result = $conn->query("SELECT * FROM reviews ORDER BY date DESC");

        if (!$result) {
            throw new Exception('Error fetching reviews.');
        }

        $reviews = array();
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }

        $result->close();
        $conn->close();

        return $reviews;
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}

// Display reviews
$reviews = getReviews();
if ($reviews) {
    foreach ($reviews as $review) {
        // Display each review here
        echo '<div class="review">';
        echo '<div class="rating">';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $review['rating']) {
                echo '<span class="star-filled">★</span>';
            } else {
                echo '<span class="star-empty">★</span>';
            }
        }
        echo '</div>';
        echo '<p>' . $review['comment'] . '</p>';
        echo '<small>Posted by: ' . $review['user_id'] . ' on ' . date('F j, Y', strtotime($review['date'])) . '</small>';
        echo '</div>';
    }
}


<?php
function displayUserReviews() {
    // Database configuration
    $host = 'localhost';
    $db_user = 'root';
    $db_password = '';
    $db_name = 'reviews';

    // Connect to database
    $connection = new mysqli($host, $db_user, $db_password, $db_name);

    // Check connection
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // Fetch all reviews from the database
    $sql = "SELECT * FROM reviews ORDER BY review_id DESC";
    $result = $connection->query($sql);

    // Display existing reviews
    if ($result->num_rows > 0) {
        echo "<div class='reviews'>";
        while ($row = $result->fetch_assoc()) {
            echo "<div class='review'>";
            echo "<h3>By " . $row['author_name'] . "</h3>";
            echo "<p>" . $row['content'] . "</p>";
            echo "<small>Posted on: " . $row['date_posted'] . "</small>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<div class='no-reviews'>No reviews yet!</div>";
    }

    // Close the database connection
    $connection->close();

    // Display review submission form
    displayReviewForm();
}

function displayReviewForm() {
    // Display a simple HTML form for submitting reviews
    ?>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="review-form">
        <h2>Leave a Review</h2>
        <input type="text" name="author_name" placeholder="Your Name" required><br><br>
        <textarea name="content" placeholder="Write your review here..." rows="5" required></textarea><br><br>
        <button type="submit" name="submit_review">Submit Review</button>
    </form>

    <style>
        /* Basic CSS styling */
        .reviews {
            margin: 20px;
        }

        .review {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
        }

        .no-reviews {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 20px;
        }

        .review-form {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .review-form h2 {
            color: #333;
            margin-bottom: 15px;
        }

        .review-form input,
        .review-form textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
    <?php
}

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    // Database configuration
    $host = 'localhost';
    $db_user = 'root';
    $db_password = '';
    $db_name = 'reviews';

    try {
        // Connect to database
        $connection = new mysqli($host, $db_user, $db_password, $db_name);

        // Check connection
        if ($connection->connect_error) {
            throw new Exception("Connection failed: " . $connection->connect_error);
        }

        // Prepare and bind the insert statement
        $stmt = $connection->prepare("INSERT INTO reviews (author_name, content, date_posted) VALUES (?, ?, NOW())");
        $stmt->bind_param("ss", $_POST['author_name'], $_POST['content']);

        if ($stmt->execute()) {
            // Redirect back to the page after submission
            header("Location: " . htmlspecialchars($_SERVER['PHP_SELF']));
            exit();
        } else {
            echo "Error submitting review: " . $stmt->error;
        }

        // Close the statement and connection
        $stmt->close();
        $connection->close();

    } catch (Exception $e) {
        die("An error occurred: " . $e->getMessage());
    }
}

// Call the display function
displayUserReviews();
?>


<?php
function submit_review($review_text, $user_id, $product_id, $rating = null) {
    // Database connection details
    $db_host = "localhost";
    $db_username = "username";
    $db_password = "password";
    $db_name = "database_name";

    // Connect to database
    $mysqli = new mysqli($db_host, $db_username, $db_password, $db_name);

    // Check connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Sanitize input data (prevent SQL injection)
    $review_text = htmlspecialchars($review_text);
    $user_id = intval($user_id);
    $product_id = intval($product_id);
    $rating = ($rating !== null) ? intval($rating) : null;

    // Prepare and execute the SQL query
    if ($rating !== null) {
        $sql = "INSERT INTO reviews (review_text, user_id, product_id, rating, created_at)
                VALUES (?, ?, ?, ?, NOW())";
    } else {
        $sql = "INSERT INTO reviews (review_text, user_id, product_id, created_at)
                VALUES (?, ?, ?, NOW())";
    }

    // Use prepared statement to prevent SQL injection
    if ($rating !== null) {
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("siii", $review_text, $user_id, $product_id, $rating);
    } else {
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("siis", $review_text, $user_id, $product_id);
    }

    if ($stmt->execute()) {
        echo "Review submitted successfully!";
        return true;
    } else {
        echo "Error submitting review: " . $stmt->error;
        return false;
    }

    // Close database connection
    $mysqli->close();
}

// Example usage:
// submit_review("This is a great product!", 1, 5, 5);
?>


<?php
class Review {
    private $id;
    private $author;
    private $email;
    private $rating;
    private $comment;
    private $date;

    public function __construct($id, $author, $email, $rating, $comment) {
        $this->id = $id;
        $this->author = $author;
        $this->email = $email;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->date = date('Y-m-d H:i:s');
    }

    public function getId() {
        return $this->id;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function getEmail() {
        return $this->email;
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
}

class ReviewManager {
    private $reviews = array();
    private $filePath;

    public function __construct($filePath) {
        $this->filePath = $filePath;
        if (file_exists($filePath)) {
            $data = file_get_contents($filePath);
            $this->reviews = unserialize($data);
        }
    }

    public function addReview($id, $author, $email, $rating, $comment) {
        // Validate input
        if (empty($author) || empty($email) || empty($rating) || empty($comment)) {
            return false;
        }

        $review = new Review($id, $author, $email, $rating, $comment);
        array_push($this->reviews, $review);

        // Save to file
        $this->save();

        return true;
    }

    public function getReviews() {
        return $this->reviews;
    }

    private function save() {
        $data = serialize($this->reviews);
        file_put_contents($this->filePath, $data);
    }

    public function displayReviews() {
        foreach ($this->getReviews() as $review) {
            echo "<div class='review'>";
            echo "<h3>Review by: " . $review->getAuthor() . "</h3>";
            echo "<p>Rating: " . str_repeat("★", $review->getRating()) . "</p>";
            echo "<p>" . $review->getComment() . "</p>";
            echo "<small>Posted on: " . $review->getDate() . "</small>";
            echo "</div>";
        }
    }
}

// Example usage:
$filePath = 'reviews.txt';
$reviewManager = new ReviewManager($filePath);

// Add a new review
$id = uniqid();
$author = "John Doe";
$email = "john@example.com";
$rating = 5;
$comment = "Great product!";
$success = $reviewManager->addReview($id, $author, $email, $rating, $comment);
if ($success) {
    echo "Review added successfully!";
} else {
    echo "Error adding review.";
}

// Display all reviews
echo "<h2>All Reviews:</h2>";
$reviewManager->displayReviews();
?>


<?php
// Function to handle review submission
function submitReview($name, $email, $rating, $comment) {
    // Validate inputs
    if (empty($name) || empty($email) || empty($rating) || empty($comment)) {
        return "All fields are required!";
    }

    if (!preg_match("/^[\S.]+@[\S.]+\.[\S.]+$/", $email)) {
        return "Invalid email format!";
    }

    if ($rating < 1 || $rating > 5) {
        return "Rating must be between 1 and 5!";
    }

    // Sanitize inputs
    $name = htmlspecialchars($name);
    $email = htmlspecialchars($email);
    $comment = htmlspecialchars($comment);

    // Create review array
    $review = [
        'name' => $name,
        'email' => $email,
        'rating' => $rating,
        'comment' => $comment,
        'date' => date('Y-m-d H:i:s')
    ];

    // Load existing reviews
    if (file_exists('reviews.json')) {
        $reviews = json_decode(file_get_contents('reviews.json'), true);
    } else {
        $reviews = [];
    }

    // Add new review
    array_unshift($reviews, $review);

    // Save reviews back to file
    file_put_contents('reviews.json', json_encode($reviews));

    return "Review submitted successfully!";
}

// Function to display reviews
function displayReviews() {
    if (!file_exists('reviews.json')) {
        echo "<p>No reviews yet!</p>";
        return;
    }

    $reviews = json_decode(file_get_contents('reviews.json'), true);

    echo "<div class='reviews'>";
    foreach ($reviews as $review) {
        // Display review details
        echo "<div class='review'>";
        echo "<h3>{$review['name']}</h3>";
        echo "<p>Email: " . str_replace('@', ' at ', $review['email']) . "</p>"; // Obfuscate email
        echo "<div class='rating'>";
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $review['rating']) {
                echo "<span class='star'>★</span>";
            } else {
                echo "<span class='star'>☆</span>";
            }
        }
        echo "</div>";
        echo "<p>{$review['comment']}</p>";
        echo "<small>Posted on {$review['date']}</small>";
        echo "</div>";
    }
    echo "</div>";
}

// Example usage:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $result = submitReview($_POST['name'], $_POST['email'], $_POST['rating'], $_POST['comment']);
    echo $result;
} else {
    displayReviews();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Reviews</title>
    <style>
        .star { color: #gold; }
        .review { margin: 20px; padding: 15px; border: 1px solid #ddd; }
        .reviews { max-width: 800px; margin: 0 auto; }
    </style>
</head>
<body>
    <h1>Product Reviews</h1>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        displayReviews();
    }
    ?>

    <form method="post" action="">
        <input type="text" name="name" placeholder="Your Name" required><br>
        <input type="email" name="email" placeholder="Your Email" required><br>
        <select name="rating" required>
            <option value="">Select Rating</option>
            <option value="1">★☆☆☆☆</option>
            <option value="2">★★☆☆☆</option>
            <option value="3">★★★☆☆</option>
            <option value="4">★★★★☆</option>
            <option value="5">★★★★★</option>
        </select><br>
        <textarea name="comment" placeholder="Your Review" required></textarea><br>
        <button type="submit">Submit Review</button>
    </form>
</body>
</html>


<?php
// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'review_system';

// Create database connection
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to insert a review into the database
function insertReview($userId, $productId, $rating, $comment) {
    global $conn;
    
    // Sanitize inputs
    $userId = mysqli_real_escape_string($conn, $userId);
    $productId = mysqli_real_escape_string($conn, $productId);
    $rating = intval($rating);
    $comment = mysqli_real_escape_string($conn, $comment);
    
    // Check if rating is valid (1-5)
    if ($rating < 1 || $rating > 5) {
        return "Invalid rating. Please choose a rating between 1 and 5.";
    }
    
    // Insert review into database
    $sql = "INSERT INTO reviews (user_id, product_id, rating, comment, review_date) 
            VALUES ($userId, $productId, $rating, '$comment', NOW())";
            
    if ($conn->query($sql)) {
        return "Review submitted successfully!";
    } else {
        return "Error submitting review: " . $conn->error;
    }
}

// Function to get reviews for a product
function getReviews($productId) {
    global $conn;
    
    // Sanitize input
    $productId = mysqli_real_escape_string($conn, $productId);
    
    // Get all reviews for the specified product
    $sql = "SELECT r.review_id, u.username, r.rating, 
            r.comment, r.review_date 
            FROM reviews r 
            JOIN users u ON r.user_id = u.user_id 
            WHERE r.product_id = $productId 
            ORDER BY r.review_date DESC";
            
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        return $result;
    } else {
        return "No reviews found for this product.";
    }
}

// Function to display reviews
function displayReviews($productId) {
    global $conn;
    
    // Get reviews
    $reviews = getReviews($ productId);
    
    if (is_string($reviews)) {
        echo "<p>" . $reviews . "</p>";
        return;
    }
    
    // Display each review
    while ($row = $reviews->fetch_assoc()) {
        echo "<div class='review'>";
        echo "<h3>Review by: " . $row['username'] . "</h3>";
        echo "<p>" . $row['comment'] . "</p>";
        echo "<div class='rating'>";
        
        // Display stars
        for ($i = 1; $i <= $row['rating']; $i++) {
            echo "<span class='star'>★</span>";
        }
        
        for ($i = $row['rating'] + 1; $i <= 5; $i++) {
            echo "<span class='star'>☆</span>";
        }
        
        echo "</div>";
        echo "<p>Reviewed on: " . $row['review_date'] . "</p>";
        echo "</div><br>";
    }
}

// Example usage:
if (isset($_POST['submit_review'])) {
    // Assuming these values are coming from a form
    $userId = $_SESSION['user_id'];
    $productId = $_GET['product_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['review_comment'];
    
    // Insert the review
    $result = insertReview($userId, $productId, $rating, $comment);
    echo $result;
}

// Display reviews for a product
$product_id = 1; // Example product ID
displayReviews($product_id);

$conn->close();
?>


<?php
// Database configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Assuming user authentication is handled elsewhere
    session_start();
    if (!isset($_SESSION['user_id'])) {
        die(json_encode(['error' => 'Please log in to submit a review.']));
    }

    $productId = 1; // Replace with actual product ID handling
    $userId = $_SESSION['user_id'];
    $rating = intval($_POST['rating']);
    $comment = htmlspecialchars($_POST['comment'], ENT_QUOTES);

    if ($rating < 1 || $rating > 5) {
        die(json_encode(['error' => 'Please select a valid rating.']));
    }

    // Insert review
    $stmt = $conn->prepare("INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
    $stmt->execute([$productId, $userId, $rating, $comment]);

    // Generate response HTML
    $response = "<div class='review'>";
    $response .= "<div class='stars'>";
    for ($i = 1; $i <= $rating; $i++) {
        $response .= "<span class='star'>★</span>";
    }
    $response .= str_repeat("<span class='star' style='color: #cccccc;'>★</span>", 5 - $rating);
    $response .= "</div>";
    $response .= "<p>".$comment."</p>";
    $response .= "<small>Posted by User ".date('F j, Y')."</small>";
    $response .= "</div>";

    echo json_encode(['success' => true, 'html' => $response]);

} catch(PDOException $e) {
    die(json_encode(['error' => 'Error submitting review: '.$e->getMessage()]));
}
?>


<?php
// Database configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Replace with actual product ID
    $productId = 1;

    // Fetch reviews
    $stmt = $conn->query("SELECT * FROM reviews WHERE product_id = $productId ORDER BY created_at DESC");
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($reviews)) {
        echo '<p>No reviews available yet.</p>';
        exit;
    }

    foreach ($reviews as $review) {
        $response .= "<div class='review'>";
        $response .= "<div class='stars'>";
        for ($i = 1; $i <= $review['rating']; $i++) {
            $response .= "<span class='star'>★</span>";
        }
        $response .= str_repeat("<span class='star' style='color: #cccccc;'>★</span>", 5 - $review['rating']);
        $response .= "</div>";
        $response .= "<p>".$review['comment']."</p>";
        $response .= "<small>Posted by User ".date('F j, Y', strtotime($review['created_at']))."</small>";
        $response .= "</div>";
    }

    echo $response;

} catch(PDOException $e) {
    die('Error loading reviews: '.$e->getMessage());
}
?>


// In your login script:
session_start();
$_SESSION['user_id'] = $userId;


<?php
// Function to display user reviews
function display_reviews($product_id) {
    // Connect to database
    $connection = mysqli_connect("localhost", "username", "password", "database");
    
    // Check connection
    if(mysqli_connect_errno()) {
        echo "Failed to connect: " . mysqli_connect_error();
        return;
    }
    
    // Get reviews from database
    $query = "SELECT * FROM reviews WHERE product_id = ? ORDER BY review_date DESC";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    // Display reviews
    while($row = mysqli_fetch_assoc($result)) {
        echo "<div class='review'>";
        echo "<div class='review-header'>";
        echo "<span class='review-author'>" . htmlspecialchars($row['author'], ENT_QUOTES) . "</span>";
        echo "<div class='review-rating'>";
        
        // Display stars based on rating
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $row['rating']) {
                echo "<span class='star'>★</span>";
            } else {
                echo "<span class='star'>☆</span>";
            }
        }
        
        echo "</div>";
        echo "</div>";
        echo "<p class='review-comment'>" . htmlspecialchars($row['comment'], ENT_QUOTES) . "</p>";
        echo "<div class='review-date'>" . date('F j, Y', strtotime($row['review_date'])) . "</div>";
        echo "</div>";
    }
    
    // Close database connection
    mysqli_close($connection);
}

// Function to handle review submission
function submit_review() {
    // Check if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $product_id = $_POST['product_id'];
        $author = trim($_POST['author']);
        $email = trim($_POST['email']);
        $rating = intval($_POST['rating']);
        $comment = htmlspecialchars(trim($_POST['comment']), ENT_QUOTES);
        
        // Validate inputs
        if ($author == "" || $email == "" || $rating < 1 || $rating > 5) {
            die("Please fill in all required fields.");
        }
        
        // Connect to database
        $connection = mysqli_connect("localhost", "username", "password", "database");
        
        // Check connection
        if(mysqli_connect_errno()) {
            echo "Failed to connect: " . mysqli_connect_error();
            return;
        }
        
        // Prevent SQL injection and XSS attacks
        $author = mysqli_real_escape_string($connection, $author);
        $email = mysqli_real_escape_string($connection, $email);
        $comment = mysqli_real_escape_string($connection, $comment);
        
        // Insert review into database
        $query = "INSERT INTO reviews (product_id, author, email, rating, comment, review_date) VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "isidi", $product_id, $author, $email, $rating, $comment);
        
        if(mysqli_stmt_execute($stmt)) {
            echo "<div class='success-message'>";
            echo "Thank you for submitting your review!";
            echo "</div>";
        } else {
            echo "<div class='error-message'>";
            echo "Error: Review could not be submitted. Please try again.";
            echo "</div>";
        }
        
        // Close database connection
        mysqli_close($connection);
    }
}
?>


<?php
// Function to display user reviews
function displayUserReviews($product_id) {
    // Database connection
    $conn = mysqli_connect("localhost", "username", "password", "database_name");
    
    // SQL query to fetch reviews
    $sql = "SELECT * FROM reviews WHERE product_id = $product_id ORDER BY review_date DESC";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        // Calculate average rating
        $average_rating_sql = "SELECT AVG(rating) AS avg_rating FROM reviews WHERE product_id = $product_id";
        $average_rating_result = mysqli_query($conn, $average_rating_sql);
        $average_rating_row = mysqli_fetch_assoc($average_rating_result);
        $average_rating = number_format((float)$average_rating_row['avg_rating'], 1, '.', '');
        
        echo "<div class='reviews-container'>";
        echo "<h3>Customer Reviews</h3>";
        echo "<p>Average Rating: $average_rating/5</p>";
        
        while ($row = mysqli_fetch_assoc($result)) {
            // Review details
            $user_id = $row['user_id'];
            $rating = $row['rating'];
            $comment = $row['review_comment'];
            $date = $row['review_date'];
            
            // Get user info
            $user_sql = "SELECT username, email FROM users WHERE id = $user_id";
            $user_result = mysqli_query($conn, $user_sql);
            $user_row = mysqli_fetch_assoc($user_result);
            $username = $user_row['username'];
            $email = $user_row['email'];
            
            // Display review
            echo "<div class='review'>";
            echo "<div class='review-header'>";
            echo "<span class='username'>$username</span>";
            echo "<span class='date'>($date)</span>";
            echo "</div>";
            
            // Display rating stars
            echo "<div class='rating'>";
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= $rating) {
                    echo "<span class='star'>★</span>";
                } else {
                    echo "<span class='star'>☆</span>";
                }
            }
            echo "</div>";
            
            // Display comment
            echo "<p class='comment'>$comment</p>";
            
            // Review actions
            echo "<div class='review-actions'>";
            echo "<button class='helpful'>Helpful</button>";
            if (isset($_SESSION['admin'])) {
                echo "<button onclick='approveReview($row[review_id])'>Approve</button>";
            }
            echo "</div>";
            echo "</div>";
        }
        
        // Close database connection
        mysqli_close($conn);
        echo "</div>";
    } else {
        echo "<p>No reviews available for this product.</p>";
    }
}

// Example usage:
displayUserReviews(1);
?>


<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    // Sanitize inputs
    $rating = filter_var($_POST['rating'], FILTER_SANITIZE_NUMBER_INT);
    $comment = htmlspecialchars(trim($_POST['comment']));
    $product_id = filter_var($_POST['product_id'], FILTER_SANITIZE_NUMBER_INT);
    
    // Database connection
    $conn = mysqli_connect("localhost", "username", "password", "database_name");
    
    // Insert review into database
    $sql = "INSERT INTO reviews (user_id, product_id, rating, review_comment) 
            VALUES ($_SESSION[user_id], $product_id, $rating, '$comment')";
            
    if (mysqli_query($conn, $sql)) {
        echo "<div class='success-message'>Thank you for your review!</div>";
    } else {
        echo "<div class='error-message'>Error submitting review. Please try again.</div>";
    }
    
    // Close connection
    mysqli_close($conn);
}
?>


<?php
// Database connection details
$host = "localhost";
$username = "username";
$password = "password";
$db_name = "reviews_db";

// Connect to database
$conn = new mysqli($host, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function processReview($conn) {
    try {
        // Check if form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Sanitize input data
            $user_name = htmlspecialchars($_POST['username']);
            $rating = intval($_POST['rating']);
            $comment = htmlspecialchars($_POST['comment']);

            // Validate input data
            if ($user_name == "" || $comment == "" || $rating < 1) {
                throw new Exception("Please fill in all required fields and select a rating.");
            }

            // Insert review into database
            $stmt = $conn->prepare("INSERT INTO reviews (username, rating, comment, submission_date) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("sii", $user_name, $rating, $comment);

            if ($stmt->execute()) {
                echo "<script>alert('Thank you for submitting your review!');</script>";
            } else {
                throw new Exception("Error submitting review: " . $conn->error);
            }

            $stmt->close();
        }
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
}

function displayReviews($conn) {
    try {
        // Fetch all reviews from database
        $result = $conn->query("SELECT * FROM reviews ORDER BY submission_date DESC");

        if ($result->num_rows > 0) {
            echo "<h3>Previous Reviews:</h3>";
            while ($row = $result->fetch_assoc()) {
                $rating_display = str_repeat("★", $row['rating']) . str_repeat("☆", 5 - $row['rating']);
                $date = date("F j, Y g:i a", strtotime($row['submission_date']));

                echo "<div class='review'>";
                echo "<h4>" . htmlspecialchars($row['username']) . "</h4>";
                echo "<p>" . htmlspecialchars($row['comment']) . "</p>";
                echo "<div class='rating'>" . $rating_display . " (" . $date . ")</div>";
                echo "</div><br>";
            }
        } else {
            echo "<p>No reviews available.</p>";
        }
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Reviews</title>
    <style>
        .review {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
        }
        .rating {
            color: #gold;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <h1>Submit a Review</h1>
    
    <!-- Review submission form -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div>
            <label for="username">Name:</label><br>
            <input type="text" id="username" name="username" required><br>
            
            <label for="rating">Rating:</label><br>
            <select id="rating" name="rating" required>
                <option value="">Select Rating</option>
                <option value="1">1 Star</option>
                <option value="2">2 Stars</option>
                <option value="3">3 Stars</option>
                <option value="4">4 Stars</option>
                <option value="5">5 Stars</option>
            </select><br>
            
            <label for="comment">Comment:</label><br>
            <textarea id="comment" name="comment" rows="5" cols="30" required></textarea><br>
        </div><br>
        
        <input type="submit" value="Submit Review">
    </form>

    <?php
    // Process the review if form is submitted
    processReview($conn);
    
    // Display existing reviews
    displayReviews($conn);
    ?>

</body>
</html>


<?php
// Configuration
$reviewsFile = 'reviews.txt';

// Error message initialization
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $username = trim($_POST['username']);
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $comment = trim($_POST['comment']);

    if (empty($username)) {
        $error = 'Please enter your name.';
    } elseif ($rating < 1 || $rating > 5) {
        $error = 'Please select a rating between 1 and 5 stars.';
    } elseif (empty($comment)) {
        $error = 'Please write a comment.';
    } else {
        // Sanitize input
        $username = htmlspecialchars($username, ENT_QUOTES);
        $comment = htmlspecialchars($comment, ENT_QUOTES);

        // Create review data array
        $reviewData = [
            'username' => $username,
            'rating' => $rating,
            'comment' => $comment,
            'date' => date('Y-m-d H:i:s')
        ];

        try {
            // Open file for writing
            $fileHandle = fopen($reviewsFile, 'a');
            if (flock($fileHandle, LOCK_EX)) { // Lock file to prevent concurrent writes
                // Write review data as JSON string
                fwrite($fileHandle, json_encode($reviewData) . "
");
                flock($fileHandle, LOCK_UN); // Release lock
            }
            fclose($fileHandle);

            // Redirect back after successful submission
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        } catch (Exception $e) {
            $error = 'Error saving review. Please try again later.';
            error_log('Review submission failed: ' . $e->getMessage());
        }
    }
}

// Function to load reviews from file
function loadReviews($file) {
    if (!file_exists($file)) {
        return [];
    }

    $reviews = array();
    $lines = file($file);
    foreach ($lines as $line) {
        $line = trim($line);
        if (!empty($line)) {
            $reviewData = json_decode($line, true);
            if (is_array($reviewData)) {
                $reviews[] = $reviewData;
            }
        }
    }

    return $reviews;
}

// Load existing reviews
$reviews = loadReviews($reviewsFile);

// Function to display star rating
function displayStars($rating) {
    $stars = str_repeat('★', $rating);
    $emptyStars = str_repeat('☆', 5 - $rating);
    return $stars . $emptyStars;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Reviews</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .review-form {
            margin-bottom: 30px;
        }
        .star-rating {
            font-size: 24px;
            color: #ffd700;
        }
    </style>
</head>
<body>
    <h1>Product Reviews</h1>

    <?php if (!empty($error)): ?>
        <div style="color: red; margin-bottom: 20px;">
            Error: <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <!-- Review submission form -->
    <form class="review-form" method="post">
        <div>
            <label for="username">Name:</label><br>
            <input type="text" id="username" name="username" required>
        </div>

        <div style="margin-top: 10px;">
            <label>Rating:</label><br>
            <select id="rating" name="rating" required>
                <option value="">Select Rating</option>
                <option value="5">★★★★★ (5)</option>
                <option value="4">★★★★☆ (4)</option>
                <option value="3">★★★☆☆ (3)</option>
                <option value="2">★★☆☆☆ (2)</option>
                <option value="1">★☆☆☆☆ (1)</option>
            </select>
        </div>

        <div style="margin-top: 10px;">
            <label for="comment">Comment:</label><br>
            <textarea id="comment" name="comment" rows="4" cols="50" required></textarea>
        </div>

        <button type="submit" style="margin-top: 10px;">Submit Review</button>
    </form>

    <?php if (!empty($reviews)): ?>
        <h2>Latest Reviews:</h2>
        <?php foreach ($reviews as $review): 
            // Limit comment length to prevent long comments from breaking layout
            $comment = substr($review['comment'], 0, 300); 
            if (strlen($review['comment']) > 300) {
                $comment .= '...';
            }
        ?>
            <div style="border-bottom: 1px solid #ddd; padding-bottom: 15px; margin-bottom: 15px;">
                <div>
                    <strong><?php echo htmlspecialchars($review['username']); ?></strong>
                    <span class="star-rating" style="margin-left: 10px;">
                        <?php echo displayStars($review['rating']); ?>
                    </span>
                </div>
                <p><?php echo $comment; ?></p>
                <small style="color: #666;">
                    <?php echo date('j M Y, H:i', strtotime($review['date'])); ?>
                </small>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No reviews available. Be the first to review this product!</p>
    <?php endif; ?>

</body>
</html>


<?php
// Database configuration
$host = 'localhost';
$user = 'username';
$password = 'password';
$db_name = 'reviews_db';

// Connect to the database
$conn = mysqli_connect($host, $user, $password, $db_name);

// Create reviews table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(255),
    product_id INT,
    rating INT,
    comment TEXT,
    timestamp DATETIME
)";

mysqli_query($conn, $sql);

// Function to submit a review
function submitReview() {
    global $conn;
    
    // Check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Sanitize inputs
        $user_name = mysqli_real_escape_string($conn, $_POST['user_name']);
        $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
        $rating = intval($_POST['rating']);
        $comment = mysqli_real_escape_string($conn, $_POST['comment']);
        
        // Insert review into database
        $sql = "INSERT INTO reviews (user_name, product_id, rating, comment, timestamp)
                VALUES ('$user_name', '$product_id', '$rating', '$comment', NOW())";
        
        if (!mysqli_query($conn, $sql)) {
            die('Error submitting review: ' . mysqli_error($conn));
        }
    }
}

// Function to display reviews
function displayReviews($product_id) {
    global $conn;
    
    // Get reviews for product
    $sql = "SELECT * FROM reviews WHERE product_id = '$product_id' ORDER BY timestamp DESC";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        echo '<div class="reviews">';
        
        // Calculate average rating
        $sum_ratings = 0;
        $count = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $sum_ratings += $row['rating'];
            $count++;
        }
        $average_rating = number_format(($sum_ratings / $count), 1);
        
        echo '<div class="average-rating">';
        echo '<h3>Average Rating:</h3>';
        echo '<p>' . $average_rating . '/5</p>';
        echo '</div>';
        
        // Reset result pointer
        mysqli_data_seek($result, 0);
        
        while ($row = mysqli_fetch_assoc($result)) {
            $user_name = htmlspecialchars($row['user_name']);
            $rating = $row['rating'];
            $comment = htmlspecialchars($row['comment']);
            $timestamp = date('M j, Y g:i a', strtotime($row['timestamp']));
            
            echo '<div class="review">';
            echo '<div class="review-header">';
            echo '<span class="user-name">' . $user_name . '</span>';
            echo '<div class="rating">';
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= $rating) {
                    echo '<span class="star">★</span>';
                } else {
                    echo '<span class="star">☆</span>';
                }
            }
            echo '</div>';
            echo '</div>';
            echo '<p class="comment">' . $comment . '</p>';
            echo '<small class="timestamp">' . $timestamp . '</small>';
            echo '</div>';
        }
        
        echo '</div>';
    } else {
        echo 'No reviews yet.';
    }
}

// Close database connection
mysqli_close($conn);
?>


<?php
// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$db_name = 'reviews_db';

// Create database connection
$conn = mysqli_connect($host, $username, $password, $db_name);

// Check if connection failed
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to submit a review
function submit_review() {
    global $conn;

    // Sanitize input data
    $user_name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $rating = (int)$_POST['rating'];
    $comment = htmlspecialchars($_POST['comment']);

    // Get current date and time
    $date = date('Y-m-d H:i:s');

    // SQL query to insert review into database
    $sql = "INSERT INTO reviews (name, email, rating, comment, date) 
            VALUES ('$user_name', '$email', $rating, '$comment', '$date')";

    if (mysqli_query($conn, $sql)) {
        echo "Review submitted successfully!";
        header("Refresh:2; url=reviews.php"); // Redirect after 2 seconds
    } else {
        echo "Error submitting review: " . mysqli_error($conn);
    }
}

// Function to display reviews
function display_reviews() {
    global $conn;

    // SQL query to fetch all reviews
    $sql = "SELECT * FROM reviews ORDER BY date DESC";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='review'>";
            echo "<h3>" . $row['name'] . "</h3>";
            echo "<p>Email: " . $row['email'] . "</p>";
            echo "<p>Rating: " . str_repeat("★", $row['rating']) . str_repeat("☆", 5 - $row['rating']) . "</p>";
            echo "<p>Comment: " . $row['comment'] . "</p>";
            echo "<small>" . $row['date'] . "</small>";
            echo "</div><br>";
        }
    } else {
        echo "No reviews found.";
    }
}

// Close database connection
mysqli_close($conn);
?>

<!-- HTML Form for submitting reviews -->
<!DOCTYPE html>
<html>
<head>
    <title>Submit Review</title>
    <style>
        .review {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 5px;
        }
        .stars {
            color: #ffd700;
        }
    </style>
</head>
<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        submit_review();
    } else {
        // Display review form
        echo "<h2>Submit Your Review</h2>";
        echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
        echo "<label for='name'>Name:</label><br>";
        echo "<input type='text' id='name' name='name' required><br><br>";
        
        echo "<label for='email'>Email:</label><br>";
        echo "<input type='email' id='email' name='email' required><br><br>";
        
        echo "<label for='rating'>Rating:</label><br>";
        echo "<select id='rating' name='rating'>";
        for ($i = 1; $i <= 5; $i++) {
            echo "<option value='$i'>$i stars</option>";
        }
        echo "</select><br><br>";
        
        echo "<label for='comment'>Comment:</label><br>";
        echo "<textarea id='comment' name='comment' rows='4' cols='50' required></textarea><br><br>";
        
        echo "<input type='submit' value='Submit Review'>";
        echo "</form>";
    }
    
    // Display existing reviews
    echo "<h2>Reviews</h2>";
    display_reviews();
    ?>
</body>
</html>


<?php
// Database connection
$host = "localhost";
$username = "username";
$password = "password";
$dbname = "reviews_db";

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to submit a new review
function submitReview() {
    global $conn;
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $rating = intval($_POST['rating']);
        $comment = mysqli_real_escape_string($conn, $_POST['comment']);

        // Insert into database
        $sql = "INSERT INTO reviews (user_name, user_email, rating, comment, review_date) 
                VALUES ('$name', '$email', $rating, '$comment', NOW())";

        if (mysqli_query($conn, $sql)) {
            echo "Review submitted successfully!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

// Function to display reviews
function displayReviews() {
    global $conn;
    
    // Get all reviews from database
    $sql = "SELECT r.*, u.user_name AS username 
            FROM reviews r 
            LEFT JOIN users u ON r.user_email = u.user_email 
            ORDER BY review_date DESC";
            
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        // Calculate average rating
        $total_ratings = 0;
        $average_rating = 0;
        
        while ($row = mysqli_fetch_assoc($result)) {
            $total_ratings += $row['rating'];
            echo "<div class='review'>";
            echo "<h3>Review by " . $row['username'] . "</h3>";
            echo "<p><strong>Rating:</strong> " . $row['rating'] . "/5</p>";
            echo "<p><strong>Comment:</strong> " . $row['comment'] . "</p>";
            echo "<p><em>Posted on: " . $row['review_date'] . "</em></p>";
            echo "</div>";
        }
        
        // Calculate average
        $average_rating = ($total_ratings / mysqli_num_rows($result)) * 1;
        $rounded_average = round($average_rating, 2);
        
        echo "<div class='average-rating'>";
        echo "Average Rating: " . $rounded_average . "/5";
        echo "</div>";
    } else {
        echo "No reviews found.";
    }
}

// Close database connection
mysqli_close($conn);
?>


<?php
displayReviews();
?>


<?php
// Function to create a new review
function createReview($userId, $productId, $rating, $comment = "") {
    // Database connection details
    $servername = "localhost";
    $username = "your_username";
    $password = "your_password";
    $dbname = "your_database";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Sanitize inputs to prevent SQL injection
    $userId = mysqli_real_escape_string($conn, $userId);
    $productId = mysqli_real_escape_string($conn, $productId);
    $rating = intval($rating); // Ensure rating is an integer
    $comment = mysqli_real_escape_string($conn, $comment);

    // Validate rating (assuming ratings are between 1 and 5)
    if ($rating < 1 || $rating > 5) {
        return "Invalid rating. Please provide a rating between 1 and 5.";
    }

    // SQL query to insert the review
    $sql = "INSERT INTO reviews (user_id, product_id, rating, comment, created_at)
            VALUES ('$userId', '$productId', '$rating', '" . ($comment ? "'$comment'" : "NULL") . "', NOW())";

    if (mysqli_query($conn, $sql)) {
        // Return success message
        return "Review submitted successfully!";
    } else {
        // Return error message
        return "Error: " . mysqli_error($conn);
    }

    // Close database connection
    mysqli_close($conn);
}

// Function to get reviews for a product
function getReviewsByProduct($productId) {
    // Database connection details
    $servername = "localhost";
    $username = "your_username";
    $password = "your_password";
    $dbname = "your_database";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Sanitize input to prevent SQL injection
    $productId = mysqli_real_escape_string($conn, $productId);

    // SQL query to fetch reviews for the product
    $sql = "SELECT * FROM reviews WHERE product_id = '$productId' ORDER BY created_at DESC";
    
    // Optional: Limit the number of reviews returned
    $limit = 5; // Change this value as needed
    $sql .= " LIMIT $limit";

    $result = mysqli_query($conn, $sql);
    $reviews = array();

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $review = array(
                'id' => $row['id'],
                'user_id' => $row['user_id'],
                'product_id' => $row['product_id'],
                'rating' => $row['rating'],
                'comment' => $row['comment'],
                'created_at' => $row['created_at']
            );
            array_push($reviews, $review);
        }
    } else {
        // Return error message
        return "Error: " . mysqli_error($conn);
    }

    // Close database connection
    mysqli_close($conn);

    return $reviews;
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['user_id'];
    $productId = $_POST['product_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    // Create a new review
    $reviewResult = createReview($userId, $productId, $rating, $comment);
    echo $reviewResult;
}

// Get reviews for a specific product
$productReviews = getReviewsByProduct(1); // Replace 1 with the actual product ID

// Calculate average rating and display reviews
if (is_array($productReviews)) {
    $totalRating = 0;
    foreach ($productReviews as $review) {
        $totalRating += $review['rating'];
    }
    $averageRating = $totalRating / count($productReviews);

    echo "<h3>Product Reviews</h3>";
    echo "<p>Average Rating: " . number_format($averageRating, 1) . "/5</p>";

    foreach ($productReviews as $review) {
        // Display review details
        echo "<div class='review'>";
        echo "<p><strong>" . str_repeat("★", $review['rating']) . "</strong></p>";
        if (!empty($review['comment'])) {
            echo "<p>" . $review['comment'] . "</p>";
        }
        echo "</div>";
    }
}
?>


<?php
// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'reviews_db';

$conn = mysqli_connect($host, $username, $password, $dbname);

// Create reviews table if not exists
$sql = "CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(100),
    user_email VARCHAR(100),
    rating INT,
    comment TEXT,
    timestamp DATETIME
)";

mysqli_query($conn, $sql);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $rating = (int)$_POST['rating'];
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    // Insert into database
    $sql = "INSERT INTO reviews (user_name, user_email, rating, comment, timestamp) 
            VALUES ('$name', '$email', $rating, '$comment', NOW())";

    if (mysqli_query($conn, $sql)) {
        header("Location: $_SERVER[PHP_SELF]");
        exit();
    } else {
        echo "Error submitting review: " . mysqli_error($conn);
    }
}

// Get all reviews
$sql = "SELECT * FROM reviews ORDER BY timestamp DESC";
$result = mysqli_query($conn, $sql);
$reviews = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Calculate average rating
$total_ratings = 0;
$count = count($reviews);
if ($count > 0) {
    foreach ($reviews as $review) {
        $total_ratings += $review['rating'];
    }
    $average_rating = $total_ratings / $count;
} else {
    $average_rating = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Reviews</title>
    <style>
        .review-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .rating-stars {
            color: #ffd700;
        }
        .review-item {
            border-bottom: 1px solid #ddd;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="review-container">
        <?php if (!empty($average_rating)) { ?>
            <h2>Average Rating: <?php echo number_format($average_rating, 1); ?> stars</h2>
        <?php } ?>

        <h2>Leave a Review</h2>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div>
                <label for="name">Name:</label><br>
                <input type="text" id="name" name="name" required>
            </div>

            <div>
                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email" required>
            </div>

            <div>
                <label for="rating">Rating:</label><br>
                <select id="rating" name="rating" required>
                    <option value="">Choose a rating</option>
                    <option value="5">5 stars</option>
                    <option value="4">4 stars</option>
                    <option value="3">3 stars</option>
                    <option value="2">2 stars</option>
                    <option value="1">1 star</option>
                </select>
            </div>

            <div>
                <label for="comment">Comment:</label><br>
                <textarea id="comment" name="comment" rows="5" required></textarea>
            </div>

            <button type="submit">Submit Review</button>
        </form>

        <?php if (!empty($reviews)) { ?>
            <h2>Reviews</h2>
            <?php foreach ($reviews as $review) { ?>
                <div class="review-item">
                    <h3><?php echo htmlspecialchars($review['user_name']); ?></h3>
                    <p><?php echo htmlspecialchars($review['user_email']); ?></p>
                    <div class="rating-stars">
                        <?php 
                            $stars = str_repeat('★ ', $review['rating']);
                            echo $stars;
                        ?>
                    </div>
                    <p><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                    <small>Posted on: <?php echo date('F j, Y', strtotime($review['timestamp'])); ?></small>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p>No reviews yet. Be the first to leave a review!</p>
        <?php } ?>
    </div>
</body>
</html>


<?php
function display_reviews($reviews, $product_id) {
    // Initialize an empty array to hold matching reviews
    $matching_reviews = array();

    // Loop through each review to find those that match the product ID
    foreach ($reviews as $review) {
        if ($review['product_id'] == $product_id) {
            $matching_reviews[] = $review;
        }
    }

    // If there are no matching reviews, display a message
    if (empty($matching_reviews)) {
        echo "<div class='no-reviews'>No reviews yet.</div>";
        return;
    }

    // Display the reviews in an ordered list
    echo "<ol class='reviews'>";
    
    foreach ($matching_reviews as $review) {
        // Start each review item
        echo "<li class='review'>";
        
        // Display the author and content of the review
        echo "<div class='review-content'>";
        echo "<p><strong>" . htmlspecialchars($review['author']) . "</strong></p>";
        echo "<p>" . nl2br(htmlspecialchars($review['content'])) . "</p>";
        echo "</div>";

        // Display the star rating
        $stars = "";
        for ($i = 0; $i < $review['rating']; $i++) {
            $stars .= "★";
        }
        echo "<div class='rating'>";
        echo "<span class='stars'>" . $stars . "</span>";
        echo "</div>";

        // Display the review date
        echo "<div class='date'>";
        echo "<small>" . date('F j, Y', strtotime($review['date'])) . "</small>";
        echo "</div>";
        
        // End of this review item
        echo "</li>";
    }
    
    // Close the ordered list
    echo "</ol>";
}
?>


$reviews = array(
    array(
        'product_id' => 1,
        'author' => 'John Doe',
        'content' => "This is a great product! I highly recommend it.",
        'rating' => 5,
        'date' => '2023-07-20'
    ),
    array(
        'product_id' => 1,
        'author' => 'Jane Smith',
        'content' => "Okay but nothing special. It works but could be better.",
        'rating' => 3,
        'date' => '2023-07-19'
    ),
);

// Display reviews for product ID 1
display_reviews($reviews, 1);


<?php
// Connect to the database
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to add a review
function addReview($name, $review) {
    global $conn;
    
    // Sanitize the inputs
    $name = mysqli_real_escape_string($conn, htmlspecialchars($name));
    $review = mysqli_real_escape_string($conn, htmlspecialchars($review));
    
    // Insert into database
    $sql = "INSERT INTO reviews (name, review) VALUES ('$name', '$review')";
    
    if ($conn->query($sql)) {
        echo "Thank you for your review!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Function to display reviews
function displayReviews() {
    global $conn;
    
    // Retrieve all reviews from the database
    $sql = "SELECT * FROM reviews ORDER BY id DESC";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div class='review'>";
            echo "<h3>" . htmlspecialchars($row["name"]) . "</h3>";
            echo "<p>" . htmlspecialchars($row["review"]) . "</p>";
            echo "<small>Posted on: " . $row["timestamp"] . "</small>";
            echo "</div>";
        }
    } else {
        echo "No reviews yet!";
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the values from the form
    $name = $_POST['name'];
    $review = $_POST['review'];
    
    // Validate input
    if (empty($name) || empty($review)) {
        echo "Please fill in all fields!";
    } else {
        addReview($name, $review);
    }
}

// Close the database connection
$conn->close();
?>


<?php
// Database connection
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "reviews_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process review submission
if (isset($_POST['submit_review'])) {
    // Sanitize and validate inputs
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $rating = intval($_POST['rating']);
    $comment = htmlspecialchars(trim($_POST['comment']));

    // Validation errors
    $errors = array();

    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required.";
    }
    if ($rating < 1 || $rating > 5) {
        $errors[] = "Rating must be between 1 and 5 stars.";
    }
    if (empty($comment)) {
        $errors[] = "Comment is required.";
    }

    // If no errors, insert into database
    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("INSERT INTO reviews (name, email, rating, comment) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssis", $name, $email, $rating, $comment);
            $stmt->execute();
            $stmt->close();

            // Redirect after successful submission
            header("Location: review.php?success=1");
            exit();
        } catch (mysqli_sql_exception $e) {
            echo "Error submitting review: " . $e->getMessage();
        }
    } else {
        foreach ($errors as $error) {
            echo "<p class='error'>$error</p>";
        }
    }
}

// Fetch reviews from database
$reviews = array();
$result = $conn->query("SELECT * FROM reviews ORDER BY submission_date DESC");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
} else {
    echo "No reviews found.";
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Review</title>
    <style>
        .review-form { margin: 20px; padding: 15px; border: 1px solid #ddd; }
        .rating-stars input[type="radio"] { display: none; }
        .rating-stars label { color: #ddd; cursor: pointer; }
        .rating-stars label:before { content: "★ "; }
        .rating-stars input[type="radio"]:checked ~ label { color: #ffd700; }
        .review-list { margin-top: 20px; }
        .review-item { margin-bottom: 15px; padding: 10px; border: 1px solid #ddd; }
        .star-rated { color: #ffd700; }
    </style>
</head>
<body>
    <h1>Product Review System</h1>

    <!-- Review Form -->
    <div class="review-form">
        <h2>Leave a Review</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <p>
                <label for="name">Name:</label><br>
                <input type="text" id="name" name="name" required>
            </p>
            <p>
                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email" required>
            </p>
            <p>
                <label>Rating:</label><br>
                <div class="rating-stars">
                    <input type="radio" id="5-star" name="rating" value="5">
                    <label for="5-star">5</label>
                    <input type="radio" id="4-star" name="rating" value="4">
                    <label for="4-star">4</label>
                    <input type="radio" id="3-star" name="rating" value="3">
                    <label for="3-star">3</label>
                    <input type="radio" id="2-star" name="rating" value="2">
                    <label for="2-star">2</label>
                    <input type="radio" id="1-star" name="rating" value="1">
                    <label for="1-star">1</label>
                </div>
            </p>
            <p>
                <label for="comment">Comment:</label><br>
                <textarea id="comment" name="comment" rows="5" cols="30" required></textarea>
            </p>
            <input type="submit" name="submit_review" value="Submit Review">
        </form>
    </div>

    <!-- Display Reviews -->
    <div class="review-list">
        <h2>Customer Reviews</h2>
        <?php foreach ($reviews as $review): ?>
            <div class="review-item">
                <p><strong><?php echo htmlspecialchars($review['name']); ?></strong></p>
                <p class="star-rated"><?php display_stars($review['rating']); ?> (<?php echo $review['rating']; ?> stars)</p>
                <p><?php echo htmlspecialchars($review['comment']); ?></p>
                <small>Submitted on: <?php echo $review['submission_date']; ?></small>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Star Display Helper Function -->
    <?php
    function display_stars($rating) {
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                echo "★ ";
            } else {
                echo "☆ ";
            }
        }
    }
    ?>
</body>
</html>


<?php
// Database connection configuration
$host = "localhost";
$user = "root";
$password = "";
$db_name = "reviews_db";

// Connect to database
$conn = mysqli_connect($host, $user, $password, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to fetch and display reviews
function getReviews($product_id) {
    global $conn;

    // Get all reviews for a product
    $sql = "SELECT * FROM reviews WHERE product_id = ? ORDER BY review_date DESC";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        
        $result = $stmt->get_result();

        // Display reviews
        while ($row = $result->fetch_assoc()) {
            $rating = $row['rating'];
            $comment = $row['comment'];
            $author = $row['author'];
            $review_date = date('jS M Y, g:i a', strtotime($row['review_date']));

            // Display review content
            echo "<div class='review'>";
                echo "<div class='review-header'>";
                    // Display rating stars
                    echo "<div class='rating'>";
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $rating) {
                                echo "<span class='star'>⭐️</span>";
                            } else {
                                echo "<span class='star' style='opacity:0.3'>⭐️</span>";
                            }
                        }
                    echo "</div>";
                    echo "<span class='review-date'>$review_date</span>";
                echo "</div>";
                
                // Display review comment and author
                echo "<p>$comment</p>";
                echo "<p class='author'>- $author</p>";
            echo "</div>";
        }
    }

    // Calculate average rating
    $sql_avg = "SELECT AVG(rating) as avg_rating FROM reviews WHERE product_id = ?";
    
    if ($stmt = $conn->prepare($sql_avg)) {
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        
        $avg_result = $stmt->get_result();
        $row = $avg_result->fetch_assoc();
        $average_rating = number_format($row['avg_rating'], 1);

        // Display average rating
        echo "<div class='average-rating'>";
            echo "Average Rating: <span>$average_rating/5</span>";
        echo "</div>";
    }

    $stmt->close();
}

// Function to handle review submission
function submitReview() {
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get form data
        $author = $_POST['author'];
        $email = $_POST['email'];
        $rating = $_POST['rating'];
        $comment = $_POST['comment'];
        $product_id = $_POST['product_id'];

        // Validate input
        if (empty($author) || empty($email) || empty($rating) || empty($comment)) {
            die("Please fill in all required fields.");
        }

        // Check rating is between 1 and 5
        if ($rating < 1 || $rating > 5) {
            die("Rating must be between 1 and 5 stars.");
        }

        // Sanitize input data
        $author = mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($author))));
        $email = mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($email))));
        $comment = mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($comment))));

        // Insert review into database
        $sql = "INSERT INTO reviews (product_id, author, email, rating, comment) 
                VALUES (?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("isiii", $product_id, $author, $email, $rating, $comment);
            $stmt->execute();
            
            // Close statement
            $stmt->close();

            // Redirect back to the product page or review section
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }
    }
}

// Function to handle helpfulness voting
function voteReview($review_id, $vote) {
    global $conn;

    if ($vote == 'up' || $vote == 'down') {
        // Update helpfulness count
        $sql = "UPDATE reviews SET helpful_$vote = helpful_$vote + 1 WHERE id = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $review_id);
            $stmt->execute();
            $stmt->close();

            // Get updated helpfulness counts
            $sql_counts = "SELECT helpful_up, helpful_down FROM reviews WHERE id = ?";
            
            if ($stmt = $conn->prepare($sql_counts)) {
                $stmt->bind_param("i", $review_id);
                $stmt->execute();
                
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();

                // Output helpfulness counts
                echo "Up: " . $row['helpful_up'] . ", Down: " . $row['helpful_down'];
            }
        }
    }
}
?>


<?php
// Display reviews for a specific product
$product_id = 1; // Replace with actual product ID
getReviews($product_id);
?>


<?php
function user_review($product_id) {
    // Database configuration
    $host = 'localhost';
    $db_username = 'root';
    $db_password = '';
    $db_name = 'reviews_db';

    // Connect to the database
    $conn = mysqli_connect($host, $db_username, $db_password, $db_name);
    
    if (!$conn) {
        die('Connection failed: ' . mysqli_connect_error());
    }

    // Check for review submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $comment = $_POST['comment'];
        $rating = $_POST['rating'];

        // Validate input
        if (empty($name) || empty($comment)) {
            die('Name and comment are required.');
        }

        if (!isset($rating) || $rating < 1 || $rating > 5) {
            die('Rating must be between 1 and 5.');
        }

        // Prevent multiple submissions from the same user短时间内
        if (isset($_SESSION['last_review_submission'])) {
            if (time() - $_SESSION['last_review_submission'] < 30) { // Minimum 30 seconds between reviews
                die('Please wait before submitting another review.');
            }
        } else {
            $_SESSION['last_review_submission'] = time();
        }

        // Insert new review into the database
        $stmt = $conn->prepare("INSERT INTO reviews (product_id, name, comment, rating, date) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("isii", $product_id, $name, $comment, $rating);
        
        if ($stmt->execute() === TRUE) {
            // Redirect to avoid form resubmission
            header('Location: ' . $_SERVER['PHP_SELF'] . '?product_id=' . $product_id);
            exit();
        } else {
            die('Error submitting review.');
        }
    }

    // Retrieve reviews for the specified product
    $stmt = $conn->prepare("SELECT * FROM reviews WHERE product_id = ? ORDER BY date DESC");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Display each review
        while ($row = $result->fetch_assoc()) {
            echo <<<EOD
<div class="review">
    <div class="review-header">
        <span class="review-author">{$row['name']}</span>
        <span class="review-rating">{$row['rating']} stars</span>
    </div>
    <div class="review-body">
        {$row['comment']}
    </div>
    <div class="review-date">
        Posted on {$row['date']}
    </div>
</div>
EOD;
        }
    } else {
        // No reviews found
        echo '<p>No reviews have been submitted yet.</p>';
    }

    // Display review submission form
    if ($result->num_rows == 0 || isset($_GET['submit'])) {
        echo <<<FORM
<form action="" method="post">
    <div class="review-form">
        <h2>Submit Your Review</h2>
        <input type="text" name="name" placeholder="Your Name" required>
        <textarea name="comment" placeholder="Write your review here..." required></textarea>
        <label for="rating">Rating:</label>
        <select id="rating" name="rating">
            <option value="">Choose a rating</option>
            <option value="5">5 Stars</option>
            <option value="4">4 Stars</option>
            <option value="3">3 Stars</option>
            <option value="2">2 Stars</option>
            <option value="1">1 Star</option>
        </select>
        <input type="submit" value="Submit Review">
    </div>
</form>
FORM;
    }

    // Close the database connection
    $conn->close();
}
?>


<?php
// Database connection parameters
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$dbname = 'your_database';

// Create database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to create a review
function createReview($userId, $productId, $rating, $comment) {
    global $conn;
    
    // Escape special characters to prevent SQL injection
    $userId = mysqli_real_escape_string($conn, $userId);
    $productId = mysqli_real_escape_string($conn, $productId);
    $rating = mysqli_real_escape_string($conn, $rating);
    $comment = mysqli_real_escape_string($conn, $comment);
    
    // Insert review into database
    $sql = "INSERT INTO reviews (user_id, product_id, rating, comment, created_at) 
            VALUES ('$userId', '$productId', '$rating', '$comment', NOW())";
            
    if ($conn->query($sql)) {
        return true;
    } else {
        return false;
    }
}

// Function to get all reviews for a product
function getProductReviews($productId) {
    global $conn;
    
    // Escape special characters to prevent SQL injection
    $productId = mysqli_real_escape_string($conn, $productId);
    
    // Get reviews from database
    $sql = "SELECT r.*, u.username 
            FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            WHERE product_id = '$productId' 
            ORDER BY created_at DESC";
            
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        return $result;
    } else {
        return false;
    }
}

// Function to get average rating for a product
function getProductRating($productId) {
    global $conn;
    
    // Escape special characters to prevent SQL injection
    $productId = mysqli_real_escape_string($conn, $productId);
    
    // Get average rating from database
    $sql = "SELECT AVG(rating) as average_rating 
            FROM reviews 
            WHERE product_id = '$productId'";
            
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return round($row['average_rating'], 1); // Round to one decimal place
    } else {
        return 0;
    }
}

// Example usage:
$productId = 123; // Replace with actual product ID

// Create a review
if (createReview(456, $productId, 5, "Great product!")) {
    echo "Review created successfully!";
} else {
    echo "Error creating review.";
}

// Get and display reviews for product
$reviews = getProductReviews($productId);
if ($reviews) {
    while ($row = $reviews->fetch_assoc()) {
        echo "<div>";
        echo "<strong>" . $row['username'] . "</strong> - ";
        echo "<em>" . $row['rating'] . " stars</em><br>";
        echo $row['comment'];
        echo "</div>";
    }
} else {
    echo "No reviews available.";
}

// Get and display average rating
$averageRating = getProductRating($productId);
echo "<p>Average Rating: " . $averageRating . " stars</p>";

// Close database connection
$conn->close();
?>


<?php
// Database connection settings
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

// Connect to database
$conn = mysqli_connect($host, $username, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to add a new review
function add_review($user_id, $product_id, $rating, $comment, $conn) {
    // Sanitize input
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $product_id = mysqli_real_escape_string($conn, $product_id);
    $rating = intval($rating); // Ensure rating is an integer
    $comment = mysqli_real_escape_string($conn, $comment);

    // Check if rating is valid (1-5)
    if ($rating < 1 || $rating > 5) {
        return false;
    }

    // Insert review into database
    $query = "INSERT INTO reviews (user_id, product_id, rating, comment, timestamp)
              VALUES (?, ?, ?, ?, NOW())";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iiss", $user_id, $product_id, $rating, $comment);

    if (mysqli_stmt_execute($stmt)) {
        return true;
    } else {
        return false;
    }
}

// Function to display reviews
function display_reviews($product_id, $conn) {
    // Sanitize input
    $product_id = mysqli_real_escape_string($conn, $product_id);

    // Get all reviews for the product
    $query = "SELECT r.review_id, u.user_name, r.rating, r.comment, r.timestamp
              FROM reviews r
              JOIN users u ON r.user_id = u.user_id
              WHERE r.product_id = ?
              ORDER BY r.timestamp DESC";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $product_id);

    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);

        // Start output
        echo "<div class='reviews'>";
        while ($row = mysqli_fetch_assoc($result)) {
            $user_name = $row['user_name'];
            $rating = $row['rating'];
            $comment = $row['comment'];
            $timestamp = date('F j, Y g:i a', strtotime($row['timestamp']));

            // Display review
            echo "<div class='review'>";
            echo "<h4> Reviewed by " . htmlspecialchars($user_name) . "</h4>";
            echo "<p><strong>Rating:</strong> ";
            for ($i = 0; $i < $rating; $i++) {
                echo "&#9733;";
            }
            echo "</p>";
            echo "<p>" . htmlspecialchars($comment) . "</p>";
            echo "<p class='timestamp'>" . $timestamp . "</p>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        // Handle error
        echo "Error displaying reviews: " . mysqli_error($conn);
    }
}

// Function to calculate average rating
function get_average_rating($product_id, $conn) {
    $product_id = mysqli_real_escape_string($conn, $product_id);

    $query = "SELECT AVG(rating) as average FROM reviews WHERE product_id = ?";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $product_id);

    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        return round($row['average'], 1); // Round to one decimal place
    } else {
        return 0;
    }
}

// Close database connection
mysqli_close($conn);

?>


<?php
// Function to handle review submission
function submitReview() {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitize input data
        $name = htmlspecialchars(trim($_POST['name']));
        $email = htmlspecialchars(trim($_POST['email']));
        $rating = (int)trim($_POST['rating']);
        $comment = htmlspecialchars(trim($_POST['comment']));

        // Validate input
        if (empty($name) || empty($email) || empty($rating) || empty($comment)) {
            echo "Please fill in all fields!";
            return;
        }

        if ($rating < 1 || $rating > 5) {
            echo "Rating must be between 1 and 5 stars!";
            return;
        }

        // Store the review
        $review = array(
            'name' => $name,
            'email' => $email,
            'rating' => $rating,
            'comment' => $comment,
            'date' => date('Y-m-d H:i:s')
        );

        // Save to file (you might want to use a database in production)
        $reviewsFile = fopen('reviews.txt', 'a');
        fwrite($reviewsFile, serialize($review) . "
");
        fclose($reviewsFile);

        echo "Thank you for submitting your review!";
    }
}

// Function to display reviews
function displayReviews() {
    // Get all reviews from file
    $reviewsData = @file_get_contents('reviews.txt');
    if ($reviewsData === false) {
        return;
    }

    $reviews = explode("
", $reviewsData);
    foreach ($reviews as $review) {
        if (trim($review) == '') continue;

        $review = unserialize($review);
        if ($review !== false) {
            // Display each review
            echo "<div class='review'>";
            echo "<h3>{$review['name']}</h3>";
            echo "<p>{$review['email']}</p>";
            echo "<div class='stars'>";
            for ($i = 1; $i <= $review['rating']; $i++) {
                echo "<span class='star'>★</span>";
            }
            echo "</div>";
            echo "<p>{$review['comment']}</p>";
            echo "<small>Posted on {$review['date']}</small>";
            echo "</div>";
        }
    }
}

// Function to calculate average rating
function getAverageRating() {
    $ratings = array();
    $reviewsData = @file_get_contents('reviews.txt');
    if ($reviewsData === false) {
        return 0;
    }

    $reviews = explode("
", $reviewsData);
    foreach ($reviews as $review) {
        if (trim($review) == '') continue;

        $review = unserialize($review);
        if ($review !== false) {
            $ratings[] = $review['rating'];
        }
    }

    if (count($ratings) > 0) {
        return array_sum($ratings) / count($ratings);
    } else {
        return 0;
    }
}
?>


<?php displayReviews(); ?>


Average Rating: <?php echo number_format(getAverageRating(), 1); ?> stars


<?php
// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'reviews_db';

// Create database connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to add a review
function add_review($user_id, $product_id, $rating, $comment) {
    global $conn;
    
    // Validate inputs
    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        return "Invalid rating value";
    }
    
    if (strlen($comment) == 0) {
        return "Comment cannot be empty";
    }
    
    // Check if user exists
    $check_user = "SELECT id FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $check_user);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) == 0) {
        return "User does not exist";
    }
    
    // Insert review
    $insert_review = "INSERT INTO reviews (user_id, product_id, rating, comment, created_at)
                     VALUES (?, ?, ?, ?, NOW())";
    $stmt = mysqli_prepare($conn, $insert_review);
    mysqli_stmt_bind_param($stmt, "iisi", $user_id, $product_id, $rating, $comment);
    
    if (mysqli_stmt_execute($stmt)) {
        return true;
    } else {
        return "Error adding review: " . mysqli_error($conn);
    }
}

// Function to fetch reviews for a product
function get_reviews($product_id, $limit = 10, $order_by = 'created_at', $sort = 'DESC') {
    global $conn;
    
    // Validate parameters
    if (!is_numeric($product_id)) {
        return false;
    }
    
    // Build the query
    $query = "SELECT r.id AS review_id,
                    u.username,
                    r.rating,
                    r.comment,
                    r.created_at
             FROM reviews r
             JOIN users u ON r.user_id = u.id
             WHERE r.product_id = ?
             ORDER BY $order_by $sort
             LIMIT ?";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $product_id, $limit);
    
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        return $result;
    } else {
        return false;
    }
}

// Function to display reviews
function display_reviews($product_id) {
    // Get reviews for the product
    $reviews_result = get_reviews($product_id);
    
    if ($reviews_result && mysqli_num_rows($reviews_result) > 0) {
        echo '<div class="reviews-container">';
        
        while ($review = mysqli_fetch_assoc($reviews_result)) {
            echo '<div class="review">';
                echo '<div class="review-header">';
                    echo '<span class="username">' . htmlspecialchars($review['username']) . '</span>';
                    // Display rating stars
                    echo '<div class="rating">';
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $review['rating']) {
                                echo '<span class="star">★</span>';
                            } else {
                                echo '<span class="star">☆</span>';
                            }
                        }
                    echo '</div>';
                echo '</div>';
                
                echo '<p class="comment">' . htmlspecialchars($review['comment']) . '</p>';
                
                // Display helpful vote button
                echo '<div class="vote-container">';
                    echo '<button class="helpful-vote">Was this helpful?</button>';
                echo '</div>';
                
                echo '<small class="date">' . date('F j, Y', strtotime($review['created_at'])) . '</small>';
            echo '</div>';
        }
        
        echo '</div>';
    } else {
        echo 'No reviews available for this product.';
    }
}

// Example usage:
if (isset($_POST['submit_review'])) {
    $user_id = 1; // Replace with actual user ID
    $product_id = 1; // Replace with actual product ID
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    
    $result = add_review($user_id, $product_id, $rating, $comment);
    if ($result === true) {
        echo "Review added successfully!";
    } else {
        echo $result;
    }
}

// Display reviews for product ID 1
display_reviews(1);

?>


<?php
// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'reviews_db';

// Connect to MySQL database
$conn = mysqli_connect($host, $username, $password, $database);

// Check if connection failed
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to submit a new review
function submitReview($author, $rating, $review_text, $conn) {
    // Sanitize inputs
    $author = mysqli_real_escape_string($conn, htmlspecialchars($author));
    $rating = intval($rating);
    $review_text = mysqli_real_escape_string($conn, htmlspecialchars($review_text));

    // Validate inputs
    if ($author == "" || $rating < 1 || $rating > 5 || $review_text == "") {
        return false;
    }

    // Insert review into database
    $sql = "INSERT INTO reviews (author, rating, review_text) 
            VALUES ('$author', '$rating', '$review_text')";
    
    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        return false;
    }
}

// Function to display all reviews
function displayReviews($conn) {
    // Get reviews from database
    $sql = "SELECT * FROM reviews ORDER BY timestamp DESC";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='review'>";
            echo "<h3>Review by " . htmlspecialchars($row['author']) . "</h3>";
            echo "<p>Rating: ";
            // Display stars
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= $row['rating']) {
                    echo "<span class='star'>★</span>";
                } else {
                    echo "<span class='star'>☆</span>";
                }
            }
            echo "</p>";
            echo "<p>" . htmlspecialchars($row['review_text']) . "</p>";
            echo "<small>Submitted on: " . date('F j, Y', $row['timestamp']) . "</small>";
            echo "</div>";
        }
    } else {
        echo "<p>No reviews yet!</p>";
    }
}

// Close database connection
mysqli_close($conn);
?>


<?php
include 'review_functions.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $author = $_POST['author'];
    $rating = $_POST['rating'];
    $review_text = $_POST['review_text'];
    
    // Submit the review
    if (submitReview($author, $rating, $review_text, $conn)) {
        echo "<script>alert('Thank you for submitting your review!');</script>";
    } else {
        echo "<script>alert('Error submitting review. Please try again.');history.go(-1);</script>";
    }
}

// Close database connection
mysqli_close($conn);
?>


<?php
function submitUserReview($userId, $reviewText, $rating) {
    // Database connection parameters
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'reviews_db';

    try {
        // Validate input
        if (empty(trim($reviewText)) || !is_numeric($rating) || $rating < 1 || $rating > 5) {
            return "Invalid review or rating. Please provide a valid review and rating between 1 and 5.";
        }

        // Connect to database
        $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare and execute the SQL query to insert review
        $stmt = $conn->prepare("
            INSERT INTO reviews (user_id, review_text, rating, review_date)
            VALUES (:userId, :reviewText, :rating, NOW())
        ");
        
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':reviewText', $reviewText);
        $stmt->bindParam(':rating', $rating);

        $stmt->execute();

        // Calculate and return average rating after submission
        $avgRatingQuery = $conn->prepare("
            SELECT AVG(rating) as average_rating FROM reviews WHERE user_id = :userId
        ");
        
        $avgRatingQuery->bindParam(':userId', $userId);
        $avgRatingQuery->execute();
        $result = $avgRatingQuery->fetch(PDO::FETCH_ASSOC);

        return "Review submitted successfully! Current average rating: " . number_format($result['average_rating'], 1, '.', '');

    } catch (PDOException $e) {
        // Handle database errors
        return "Error submitting review: " . $e->getMessage();
    }
}

// Example usage:
$userId = 1;
$userReview = "Great experience!";
$userRating = 5;

echo submitUserReview($userId, $userReview, $userRating);
?>


<?php
// Database configuration
$host = "localhost";
$username = "username";
$password = "password";
$dbname = "reviews_db";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to create a user review
function create_review($user_id, $product_id, $rating, $comment) {
    global $conn;
    
    // Validate inputs
    if (
        empty($user_id) ||
        empty($product_id) ||
        !is_numeric($rating) || 
        $rating < 1 || 
        $rating > 5 ||
        empty($comment)
    ) {
        return "Error: Invalid input";
    }

    // Sanitize inputs
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $product_id = mysqli_real_escape_string($conn, $product_id);
    $rating = intval($rating);
    $comment = mysqli_real_escape_string($conn, $comment);

    // SQL query to insert review into database
    $sql = "INSERT INTO reviews (user_id, product_id, rating, comment, created_at) 
            VALUES ('$user_id', '$product_id', '$rating', '$comment', CURRENT_TIMESTAMP)";

    if ($conn->query($sql) === TRUE) {
        return array(
            "status" => "success",
            "message" => "Review submitted successfully",
            "review_id" => $conn->insert_id
        );
    } else {
        return "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Example usage:
$user_id = 123; // Replace with actual user ID
$product_id = 456; // Replace with actual product ID
$rating = 4; // Rating from 1 to 5
$comment = "This is a great product!"; // Review comment

$result = create_review($user_id, $product_id, $rating, $comment);

// Display result
if (is_array($result)) {
    echo "<pre>";
    print_r($result);
    echo "</pre>";
} else {
    echo $result;
}

$conn->close();
?>


<?php
function add_review($user_id, $product_id, $rating, $comment) {
    // Database connection details
    $host = 'localhost';
    $db_username = 'username';
    $db_password = 'password';
    $database = 'reviews_db';

    // Create database connection
    $conn = new mysqli($host, $db_username, $db_password, $database);

    // Check for connection errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        return false;
    }

    // Sanitize inputs to prevent SQL injection
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $product_id = mysqli_real_escape_string($conn, $product_id);
    $rating = mysqli_real_escape_string($conn, $rating);
    $comment = mysqli_real_escape_string($conn, $comment);

    // Prepare and execute SQL query
    $sql = "INSERT INTO reviews (user_id, product_id, rating, comment) 
            VALUES ('$user_id', '$product_id', '$rating', '$comment')";
            
    if ($conn->query($sql)) {
        // Review added successfully
        $conn->close();
        return true;
    } else {
        // Error adding review
        $conn->close();
        return false;
    }
}
?>


// Example usage:
$user_id = 1;
$product_id = 5;
$rating = 4;
$comment = "Great product!";

if (add_review($user_id, $product_id, $rating, $comment)) {
    echo "Thank you for your review!";
} else {
    echo "Error adding review. Please try again.";
}


<?php
// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'reviews_db';

// Create database connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to handle reviews submission and display
function handleReviews() {
    global $conn;

    // Check if form was submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Sanitize input data
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $rating = (int)$_POST['rating'];
        $review_text = mysqli_real_escape_string($conn, $_POST['review']);

        // Validate input
        if ($name == '' || $email == '' || $review_text == '') {
            echo "Please fill in all required fields.";
            exit();
        }

        // Insert review into database
        $sql = "INSERT INTO reviews (name, email, rating, review_text)
                VALUES ('$name', '$email', $rating, '$review_text')";
        
        if (!mysqli_query($conn, $sql)) {
            echo "Error: " . mysqli_error($conn);
        }

        // Redirect back to the page
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }

    // Retrieve reviews from database
    $sql = "SELECT * FROM reviews ORDER BY timestamp DESC";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='review'>";
            echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
            echo "<p>Email: " . htmlspecialchars($row['email']) . "</p>";
            echo "<div class='rating'>Rating: ";
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= $row['rating']) {
                    echo "<span class='star'>★</span>";
                } else {
                    echo "<span class='star'>☆</span>";
                }
            }
            echo "</div>";
            echo "<p>" . htmlspecialchars($row['review_text']) . "</p>";
            echo "<small>Posted on: " . date('F j, Y g:i a', $row['timestamp']) . "</small>";
            echo "</div>";
        }
    } else {
        echo "<p>No reviews available.</p>";
    }

    // Close database connection
    mysqli_close($conn);
}

// Call the function to display reviews
handleReviews();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Reviews</title>
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .review-form {
            background-color: #f5f5f5;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        .rating-stars span {
            color: gold;
        }
        .review {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Review submission form -->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="review-form">
            <h2>Submit a Review</h2>
            <p>Please fill in your details and review below:</p>
            <input type="text" name="name" placeholder="Your Name" required><br><br>
            <input type="email" name="email" placeholder="Email Address" required><br><br>
            <div class="rating-stars">
                Rating: 
                <select name="rating">
                    <?php for ($i = 1; $i <=5; $i++) { ?>
                        <option value="<?php echo $i ?>"><?php echo $i ?> Stars</option>
                    <?php } ?>
                </select><br><br>
            </div>
            <textarea name="review" placeholder="Write your review here..." rows="4" cols="50" required></textarea><br><br>
            <input type="submit" value="Submit Review">
        </form>

        <!-- Display reviews -->
        <?php handleReviews(); ?>
    </div>
</body>
</html>


<?php
// This function handles adding new reviews and displaying existing ones
function userReviewSystem() {
    // Database connection parameters
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'reviews';

    try {
        // Create a database connection
        $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if the form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize and validate input
            $name = htmlspecialchars(trim($_POST['name']));
            $rating = intval($_POST['rating']);
            $comment = htmlspecialchars(trim($_POST['comment']));

            // Basic validation
            if ($name == '' || $comment == '') {
                throw new Exception('Please fill in all fields');
            }

            if ($rating < 1 || $rating > 5) {
                throw new Exception('Rating must be between 1 and 5 stars');
            }

            // Insert the review into the database
            $stmt = $conn->prepare("INSERT INTO reviews (name, rating, comment, timestamp) VALUES (:name, :rating, :comment, NOW())");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':rating', $rating);
            $stmt->bindParam(':comment', $comment);

            $stmt->execute();

            // Redirect back to the page after submission
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        }

        // Get all reviews from the database
        $stmt = $conn->query("SELECT * FROM reviews ORDER BY timestamp DESC");
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (Exception $e) {
        die('Error: ' . $e->getMessage());
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Review System</title>
    <style>
        /* Add some basic styling */
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .review-form {
            background-color: #f5f5f5;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .rating-stars {
            display: flex;
            gap: 5px;
        }

        .star {
            font-size: 24px;
        }

        .review-list {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
        }

        .review-item {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <h1>Product Review System</h1>

    <!-- Review Form -->
    <div class="review-form">
        <h2>Submit Your Review</h2>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div>
                <label for="name">Name:</label><br>
                <input type="text" id="name" name="name" required>
            </div>

            <div>
                <label>Rating:</label><br>
                <div class="rating-stars">
                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                        <input type="radio" name="rating" value="<?php echo $i; ?>" class="star">★
                    <?php } ?>
                </div>
            </div>

            <div>
                <label for="comment">Comment:</label><br>
                <textarea id="comment" name="comment" rows="4" required></textarea>
            </div>

            <button type="submit">Submit Review</button>
        </form>
    </div>

    <!-- Display Reviews -->
    <div class="review-list">
        <?php if (!empty($reviews)) { ?>
            <h2>Customer Reviews</h2>
            <?php foreach ($reviews as $review) { ?>
                <div class="review-item">
                    <div class="rating-stars">
                        <?php for ($i = 1; $i <= $review['rating']; $i++) { ?>
                            <span class="star">★</span>
                        <?php } ?>
                    </div>
                    <h3><?php echo $review['name']; ?></h3>
                    <p><?php echo $review['comment']; ?></p>
                    <small><?php echo date('F j, Y', strtotime($review['timestamp'])); ?></small>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p>No reviews yet. Be the first to review this product!</p>
        <?php } ?>
    </div>

</body>
</html>

<?php
} // End of userReviewSystem function

// Call the function
userReviewSystem();
?>


<?php
function user_review($name = '', $review = '', $rating = 0, $email = '') {
    // File where reviews will be stored
    $filename = 'reviews.txt';
    
    // If all parameters are empty, display existing reviews
    if (empty($name) && empty($review) && empty($rating) && empty($email)) {
        // Read reviews from file and display them
        if (file_exists($filename)) {
            $reviews = unserialize(file_get_contents($filename));
            
            echo '<div class="reviews">';
            foreach ($reviews as $review_data) {
                echo '<div class="review-card">';
                    echo '<h3>' . htmlspecialchars($review_data['name']) . '</h3>';
                    if (!empty($review_data['email'])) {
                        echo '<p>' . htmlspecialchars($review_data['email']) . '</p>';
                    }
                    echo '<p>Rating: ' . $review_data['rating'] . '/5</p>';
                    echo '<p>' . nl2br(htmlspecialchars($review_data['review'])) . '</p>';
                    // Add delete button
                    echo '<a href="?id=' . htmlspecialchars($review_data['id']) . '" class="delete-btn">Delete</a>';
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo 'No reviews yet!';
        }
        
        return;
    }
    
    // Validate input
    if (empty($name) || $rating < 1 || $rating > 5) {
        die('Please fill in all required fields!');
    }
    
    // Check if review file exists
    if (!file_exists($filename)) {
        touch($filename);
    }
    
    // Generate unique ID for the review
    $id = uniqid();
    
    // Create array of review data
    $review_data = [
        'id' => $id,
        'name' => htmlspecialchars($name),
        'email' => htmlspecialchars($email),
        'rating' => (int)$rating,
        'review' => htmlspecialchars($review),
        'timestamp' => time()
    ];
    
    // Read existing reviews from file
    $reviews = array();
    if (file_exists($filename)) {
        $contents = file_get_contents($filename);
        $reviews = unserialize($contents);
    }
    
    // Add new review to the beginning of the array
    array_unshift($reviews, $review_data);
    
    // Save reviews back to file
    $success = file_put_contents($filename, serialize($reviews));
    
    if ($success === false) {
        die('Error saving review!');
    }
    
    // Display success message
    echo '<div class="success-message">Review submitted successfully!</div>';
}

// Example usage:
if (isset($_GET['id'])) {
    // Delete review with specified ID
    $filename = 'reviews.txt';
    if (file_exists($filename)) {
        $reviews = unserialize(file_get_contents($filename));
        foreach ($reviews as $key => $review_data) {
            if ($review_data['id'] == $_GET['id']) {
                unset($reviews[$key]);
                break;
            }
        }
        file_put_contents($filename, serialize(array_values($reviews)));
    }
}

// Display form for submitting reviews
echo '<form method="post" action="">';
echo '<h2>Submit a Review</h2>';
echo '<input type="text" name="name" placeholder="Your Name" required><br>';
echo '<textarea name="review" placeholder="Write your review..." required></textarea><br>';
echo '<select name="rating">';
for ($i = 1; $i <= 5; $i++) {
    echo '<option value="' . $i . '">' . $i . ' Star' . ($i != 1 ? 's' : '') . '</option>';
}
echo '</select><br>';
echo '<input type="email" name="email" placeholder="Your Email (optional)">';
echo '<button type="submit">Submit Review</button>';
echo '</form>';

// Display existing reviews
user_review();
?>


<?php
function submit_review($user_id, $product_id, $rating, $comment) {
    // Database connection parameters
    $host = "localhost";
    $db_username = "root";
    $db_password = "";
    $database = "reviews_db";

    // Connect to the database
    $conn = mysqli_connect($host, $db_username, $db_password, $database);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if rating is between 1 and 5
    if ($rating < 1 || $rating > 5) {
        return "Rating must be between 1 and 5!";
    }

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO reviews_products (user_id, product_id, rating, comment, date_created)
                            VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)");

    if ($stmt === false) {
        return "Error: " . mysqli_error($conn);
    }

    // Bind parameters to the statement
    $stmt->bind_param("iiss", $user_id, $product_id, $rating, $comment);

    // Execute the statement
    if ($stmt->execute()) {
        // Get the new average rating for the product
        $average_rating = get_average_rating($product_id);
        
        // Close the statement and connection
        $stmt->close();
        $conn->close();

        return array(
            'status' => 'success',
            'message' => 'Review submitted successfully!',
            'average_rating' => $average_rating
        );
    } else {
        // Return error if execution failed
        $error = "Error: " . $stmt->error;
        $stmt->close();
        $conn->close();
        return array(
            'status' => 'error',
            'message' => $error
        );
    }
}

// Helper function to get average rating for a product
function get_average_rating($product_id) {
    $host = "localhost";
    $db_username = "root";
    $db_password = "";
    $database = "reviews_db";

    // Connect to the database
    $conn = mysqli_connect($host, $db_username, $db_password, $database);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $stmt = $conn->prepare("SELECT AVG(rating) as average FROM reviews_products WHERE product_id = ?");
    
    if ($stmt === false) {
        return "Error: " . mysqli_error($conn);
    }

    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $average_rating = round($row['average'], 2); // Round to two decimal places
        return $average_rating;
    }

    $stmt->close();
    $conn->close();
}

// Example usage:
$user_id = 1;
$product_id = 1;
$rating = 5;
$comment = "Great product!";

$result = submit_review($user_id, $product_id, $rating, $comment);

if ($result['status'] == 'success') {
    echo "Review submitted successfully!";
    echo "New average rating: " . $result['average_rating'];
} else {
    echo "Error submitting review: " . $result['message'];
}
?>


<?php
// Function to handle and display reviews
function user_review() {
    // Database connection parameters
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "reviews_db";

    // Create database connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Process form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get and sanitize user input
        $user_name = htmlspecialchars($_POST['name']);
        $rating = intval($_POST['rating']);
        $comment = htmlspecialchars($_POST['comment']);

        // Validate input
        if ($rating < 1 || $rating > 5) {
            die("Invalid rating! Rating must be between 1 and 5.");
        }

        if (strlen($comment) == 0) {
            die("Comment cannot be empty!");
        }

        // Insert review into database
        $sql = "INSERT INTO reviews (user_name, rating, comment, review_date)
                VALUES ('$user_name', '$rating', '$comment', NOW())";

        if ($conn->query($sql) === TRUE) {
            echo "<p>Review submitted successfully!</p>";
        } else {
            die("Error submitting review: " . $conn->error);
        }
    }

    // Display existing reviews
    $sql = "SELECT * FROM reviews ORDER BY review_date DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='review'>";
            echo "<h4>Rating: " . str_repeat("★", $row['rating']) . "</h4>";
            echo "<p><strong>" . $row['user_name'] . "</strong></p>";
            echo "<p>" . $row['comment'] . "</p>";
            echo "<small>" . $row['review_date'] . "</small>";
            echo "</div>";
        }
    } else {
        echo "<p>No reviews yet. Be the first to review!</p>";
    }

    // Close database connection
    $conn->close();
}
?>


<?php
// Database connection
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "reviews_db";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to submit a review
function submitReview($user_id, $product_id, $rating, $review_text) {
    global $conn;
    
    // Sanitize input
    $rating = intval($rating);
    $review_text = htmlspecialchars(strip_tags($review_text));
    
    // Check if all required fields are filled
    if ($rating < 1 || $rating > 5 || empty($review_text)) {
        return "Please fill in all required fields.";
    }
    
    // Insert review into database
    $sql = "INSERT INTO reviews (user_id, product_id, rating, review_text, review_date) 
            VALUES (?, ?, ?, ?, NOW())";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "iiss", $user_id, $product_id, $rating, $review_text);
        
        if (mysqli_stmt_execute($stmt)) {
            return "Review submitted successfully!";
        } else {
            return "Error submitting review: " . mysqli_error($conn);
        }
    }
}

// Function to display reviews
function displayReviews($product_id) {
    global $conn;
    
    // Get all reviews for the product
    $sql = "SELECT r.review_text, r.rating, r.review_date, u.user_name 
            FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            WHERE r.product_id = ? 
            ORDER BY r.review_date DESC";
            
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $product_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='review'>";
            echo "<h4>By " . $row['user_name'] . " - " . date('M j, Y', strtotime($row['review_date'])) . "</h4>";
            echo "<p>" . str_repeat("★", $row['rating']) . "</p>";
            echo "<p>" . $row['review_text'] . "</p>";
            echo "</div>";
        }
    } else {
        echo "Error displaying reviews: " . mysqli_error($conn);
    }
}

// Function to calculate average rating
function getAverageRating($product_id) {
    global $conn;
    
    // Calculate average rating
    $sql = "SELECT AVG(rating) as average_rating 
            FROM reviews 
            WHERE product_id = ?";
            
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $product_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        while ($row = mysqli_fetch_assoc($result)) {
            return number_format((float)$row['average_rating'], 1, '.', '');
        }
    } else {
        return 0;
    }
}

// Example usage:
// Submit a review
$user_id = 1;
$product_id = 1;
$rating = 5;
$review_text = "This is an amazing product!";
echo submitReview($user_id, $product_id, $rating, $review_text);

// Display reviews for product ID 1
echo "<h2>Product Reviews</h2>";
displayReviews(1);

// Get average rating
echo "<p>Average Rating: " . getAverageRating(1) . "/5</p>";

// Close database connection
mysqli_close($conn);
?>


<?php
function user_review() {
    // Database connection parameters
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "reviews";

    // Create database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Handle review submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $rating = $_POST['rating'];
        $comment = trim($_POST['comment']);

        // Validate input
        if (empty($username) || empty($email) || empty($rating) || empty($comment)) {
            echo "Please fill in all required fields.";
        } else {
            // Sanitize inputs to prevent SQL injection
            $username = mysqli_real_escape_string($conn, $username);
            $email = mysqli_real_escape_string($conn, $email);
            $rating = mysqli_real_escape_string($conn, $rating);
            $comment = mysqli_real_escape_string($conn, $comment);

            // Insert review into database
            $sql = "INSERT INTO reviews (username, email, rating, comment)
                    VALUES ('$username', '$email', '$rating', '$comment')";
            
            if ($conn->query($sql) === TRUE) {
                echo "<p style='color: green;'>Thank you for your review!</p>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

    // Display existing reviews
    $sql = "SELECT * FROM reviews";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div style='border: 1px solid #ddd; padding: 15px; margin-bottom: 15px;'>";
            echo "<h3>" . $row["username"] . "</h3>";
            echo "<p>Rating: " . $row["rating"] . " stars</p>";
            echo "<p>" . $row["comment"] . "</p>";
            echo "<p style='color: #666;'>Posted on: " . $row["date"] . "</p>";
            echo "</div>";
        }
    } else {
        echo "No reviews available.";
    }

    // Close database connection
    $conn->close();
}

// Display the review form
echo "<h2>Submit a Review</h2>
      <form method='post' action='" . $_SERVER['PHP_SELF'] . "'>
          <label for='username'>Username:</label><br>
          <input type='text' id='username' name='username'><br>

          <label for='email'>Email:</label><br>
          <input type='email' id='email' name='email'><br>

          <label for='rating'>Rating:</label><br>";
          
// Create rating radio buttons
for ($i = 1; $i <= 5; $i++) {
    echo "<input type='radio' name='rating' value='$i'>$i stars<br>";
}

echo "<label for='comment'>Comment:</label><br>
      <textarea id='comment' name='comment' rows='4'></textarea><br>
      <input type='submit' value='Submit Review'>
      </form>";

// Call the review function
user_review();
?>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'your_database';

// Connect to database
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to save review to database
function saveReview($product_id, $name, $email, $rating, $comment, $conn) {
    // Sanitize inputs
    $name = htmlspecialchars(strip_tags(trim($name)));
    $email = htmlspecialchars(strip_tags(trim($email)));
    $rating = intval($rating);
    $comment = htmlspecialchars(strip_tags(trim($comment)));

    // Check if all fields are filled
    if (empty($name) || empty($email) || empty($rating) || empty($comment)) {
        return false;
    }

    // Prepare and bind statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO reviews (product_id, name, email, rating, comment) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isiii", $product_id, $name, $email, $rating, $comment);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }

    $stmt->close();
}

// Function to display reviews
function displayReviews($product_id, $conn) {
    $reviews = array();

    // Get reviews from database
    $sql = "SELECT * FROM reviews WHERE product_id = ? ORDER BY timestamp DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $review = array(
            'id' => $row['id'],
            'name' => $row['name'],
            'rating' => $row['rating'],
            'comment' => $row['comment'],
            'timestamp' => $row['timestamp']
        );
        $reviews[] = $review;
    }

    $stmt->close();

    return $reviews;
}
?>


<?php
// Database connection
$host = 'localhost';
$username = 'username';
$password = 'password';
$database = 'reviews_db';

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to submit a review
function submitReview($userId, $productId, $rating, $comment) {
    global $conn;
    
    // Sanitize inputs
    $rating = intval($rating);
    $comment = htmlspecialchars(trim($comment));
    
    // Check if rating is valid (1-5)
    if ($rating < 1 || $rating > 5) {
        return false;
    }
    
    // Prepare and bind statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO reviews (user_id, product_id, rating, comment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $userId, $productId, $rating, $comment);
    
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Function to get all reviews for a product
function getProductReviews($productId) {
    global $conn;
    
    $reviews = array();
    
    // Get reviews from database
    $result = mysqli_query($conn, "SELECT r.*, u.username FROM reviews r 
                                  JOIN users u ON r.user_id = u.id 
                                  WHERE r.product_id = $productId ORDER BY r.review_date DESC");
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $reviews[] = array(
                'id' => $row['id'],
                'user_id' => $row['user_id'],
                'username' => $row['username'],
                'rating' => $row['rating'],
                'comment' => $row['comment'],
                'review_date' => $row['review_date']
            );
        }
    }
    
    return $reviews;
}

// Function to calculate average rating for a product
function getAverageRating($productId) {
    global $conn;
    
    $result = mysqli_query($conn, "SELECT AVG(rating) as average_rating FROM reviews WHERE product_id = $productId");
    $row = mysqli_fetch_assoc($result);
    
    return round($row['average_rating'], 1); // Round to one decimal place
}

// Function to display review form
function displayReviewForm() {
    if (isLoggedIn()) { // Assuming you have a login system
        ?>
        <form method="post" action="submit_review.php">
            <div class="rating-container">
                <label for="rating">Rating:</label>
                <select name="rating" id="rating">
                    <option value="1">1 Star</option>
                    <option value="2">2 Stars</option>
                    <option value="3">3 Stars</option>
                    <option value="4">4 Stars</option>
                    <option value="5">5 Stars</option>
                </select>
            </div>
            
            <div class="comment-container">
                <label for="comment">Comment:</label>
                <textarea name="comment" id="comment" rows="4"></textarea>
            </div>
            
            <button type="submit" name="submit_review">Submit Review</button>
        </form>
        <?php
    } else {
        echo "Please log in to submit a review.";
    }
}

// Function to display reviews for a product
function displayProductReviews($productId) {
    $reviews = getProductReviews($productId);
    $averageRating = getAverageRating($productId);
    
    if (!empty($reviews)) {
        ?>
        <div class="product-reviews">
            <h3>Product Reviews</h3>
            <p>Average Rating: <?php echo $averageRating; ?> stars</p>
            
            <?php foreach ($reviews as $review) { ?>
                <div class="review-item">
                    <div class="review-header">
                        <span class="username"><?php echo $review['username']; ?></span>
                        <span class="rating"><?php echo str_repeat("★", $review['rating']); ?></span>
                    </div>
                    <p class="comment"><?php echo $review['comment']; ?></p>
                </div>
            <?php } ?>
        </div>
        <?php
    } else {
        echo "No reviews available for this product.";
    }
}

// Function to check if user is logged in (example)
function isLoggedIn() {
    // Assuming you have a session-based login system
    return isset($_SESSION['user_id']);
}
?>


<?php
// Connect to database
$host = "localhost";
$username = "username";
$password = "password";
$dbname = "reviews";

$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle form submission
if (isset($_POST['submit'])) {
    // Sanitize inputs
    $rating = htmlspecialchars($_POST['rating']);
    $review = htmlspecialchars($_POST['review']);

    // Validate input lengths
    if ($rating < 1 || $rating > 5) {
        echo "Invalid rating!";
    } elseif (strlen($review) < 1 || strlen($review) > 500) {
        echo "Review must be between 1 and 500 characters!";
    } else {
        // Insert review into database
        $sql = "INSERT INTO reviews (rating, review_text)
                VALUES (?, ?)";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "is", $rating, $review);
            mysqli_stmt_execute($stmt);
            echo "Thank you for submitting your review!";
            // Reset form
            header("refresh:2;url=reviews.php");
        }
    }
}

// Get reviews from database
$sql = "SELECT rating, review_text, timestamp FROM reviews ORDER BY timestamp DESC";
$result = mysqli_query($conn, $sql);

// Calculate average rating
$average_rating = 0;
$total_ratings = 0;

$sql_avg = "SELECT AVG(rating) as avg_rating FROM reviews";
$result_avg = mysqli_query($conn, $sql_avg);
if ($row_avg = mysqli_fetch_assoc($result_avg)) {
    $average_rating = round($row_avg['avg_rating'], 1);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Reviews</title>
    <style>
        .stars {
            font-size: 24px;
        }
        .review-box {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <h1>Product Reviews</h1>
    
    <!-- Review form -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <div class="stars">
            Rating:<br>
            <select name="rating">
                <option value="">Select rating</option>
                <option value="5">★★★★★</option>
                <option value="4">★★★★☆</option>
                <option value="3">★★☆☆☆</option>
                <option value="2">★☆☆☆☆</option>
                <option value="1">☆☆☆☆☆</option>
            </select>
        </div><br>

        <div>
            Review:<br>
            <textarea name="review" rows="4" cols="50"></textarea>
        </div><br>

        <input type="submit" name="submit" value="Submit">
    </form>

    <!-- Display reviews -->
    <h3>Customer Reviews</h3>
    <p>Average Rating: <?php echo $average_rating; ?> stars</p>

    <?php
    while ($row = mysqli_fetch_assoc($result)) {
        // Convert rating to stars
        $stars = str_repeat("★", $row['rating']) . str_repeat("☆", 5 - $row['rating']);
        
        echo "<div class='review-box'>";
        echo "<p><strong>Rating:</strong> " . $stars . "</p>";
        echo "<p><strong>Review:</strong> " . $row['review_text'] . "</p>";
        echo "<p><em>Posted on: " . $row['timestamp'] . "</em></p>";
        echo "</div>";
    }
    ?>

</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>


<?php
// Initialize some sample data (you would typically retrieve this from a database)
$data = array(
    'john_doe' => array(
        'reviews' => array(
            array(
                'id' => 1,
                'text' => 'This is a great product!',
                'rating' => 5,
                'timestamp' => strtotime('2023-01-01')
            ),
            array(
                'id' => 2,
                'text' => 'Good quality, but could be better.',
                'rating' => 4,
                'timestamp' => strtotime('2023-01-02')
            )
        )
    ),
    'jane_smith' => array(
        'reviews' => array(
            array(
                'id' => 3,
                'text' => 'I love this product!',
                'rating' => 5,
                'timestamp' => strtotime('2023-01-03')
            )
        )
    )
);

// Function to add a new review
function addReview($username, $reviewText, $rating) {
    global $data;
    
    // Initialize the user if they don't exist
    if (!isset($data[$username])) {
        $data[$username] = array('reviews' => array());
    }
    
    // Create a new review ID (you could use UUID or database auto-increment)
    $newReviewId = uniqid();
    
    $newReview = array(
        'id' => $newReviewId,
        'text' => $reviewText,
        'rating' => $rating,
        'timestamp' => time()
    );
    
    // Add the new review to the user's reviews
    $data[$username]['reviews'][] = $newReview;
    
    return true;
}

// Function to get all reviews for a user
function getReviews($username) {
    global $data;
    
    if (!isset($data[$username])) {
        return array();
    }
    
    return $data[$username]['reviews'];
}

// Function to delete a review
function deleteReview($username, $reviewId) {
    global $data;
    
    if (!isset($data[$username])) {
        return false;
    }
    
    // Loop through reviews and find the matching ID
    foreach ($data[$username]['reviews'] as $key => $review) {
        if ($review['id'] == $reviewId) {
            unset($data[$username]['reviews'][$key]);
            return true;
        }
    }
    
    return false;
}

// Function to update a review
function updateReview($username, $reviewId, $newText, $newRating) {
    global $data;
    
    if (!isset($data[$username])) {
        return false;
    }
    
    // Loop through reviews and find the matching ID
    foreach ($data[$username]['reviews'] as &$review) {
        if ($review['id'] == $reviewId) {
            $review['text'] = $newText;
            $review['rating'] = $newRating;
            $review['timestamp'] = time(); // Update timestamp
            return true;
        }
    }
    
    return false;
}

// Function to calculate average rating for a user
function getAverageRating($username) {
    global $data;
    
    if (!isset($data[$username]) || empty($data[$username]['reviews'])) {
        return 0;
    }
    
    $total = array_sum(array_column($data[$username]['reviews'], 'rating'));
    $average = $total / count($data[$username]['reviews']);
    
    return round($average, 1);
}

// Example usage:
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'add_review':
            addReview('john_doe', $_POST['review_text'], $_POST['rating']);
            break;
        
        case 'delete_review':
            deleteReview('john_doe', $_POST['review_id']);
            break;
        
        case 'update_review':
            updateReview('john_doe', $_POST['review_id'], $_POST['new_text'], $_POST['new_rating']);
            break;
    }
}

// Display all reviews for a user
$reviews = getReviews('john_doe');
foreach ($reviews as $review) {
    echo "<div class='review'>";
    echo "<h3>Rating: {$review['rating']}</h3>";
    echo "<p>{$review['text']}</p>";
    echo "<small>".date('Y-m-d H:i:s', $review['timestamp'])."</small>";
    echo "</div>";
}

// Display average rating
echo "Average Rating: ".getAverageRating('john_doe');
?>


<?php
// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'reviews_db';

// Connect to MySQL database
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to submit a new review
function submit_review($product_id, $user_name, $user_email, $rating, $comment) {
    global $conn;

    // Sanitize input data
    $product_id = mysqli_real_escape_string($conn, $product_id);
    $user_name = mysqli_real_escape_string($conn, $user_name);
    $user_email = mysqli_real_escape_string($conn, $user_email);
    $rating = intval($rating);
    $comment = mysqli_real_escape_string($conn, $comment);

    // Insert review into database
    $sql = "INSERT INTO reviews (product_id, user_name, user_email, rating, comment) 
            VALUES ('$product_id', '$user_name', '$user_email', '$rating', '$comment')";
    
    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        return false;
    }
}

// Function to display reviews for a product
function display_reviews($product_id) {
    global $conn;

    // Get all reviews for this product
    $sql = "SELECT * FROM reviews WHERE product_id = '$product_id' ORDER BY review_date DESC";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Calculate average rating
        $total_rating = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $total_rating += $row['rating'];
        }
        $average_rating = number_format(($total_rating / mysqli_num_rows($result)), 1, '.', '');

        echo "<div class='reviews'>";
        echo "<h3>Product Reviews</h3>";
        echo "<p>Average Rating: $average_rating stars</p>";

        // Reset result pointer to display reviews
        mysqli_data_seek($result, 0);

        while ($row = mysqli_fetch_assoc($result)) {
            $user_name = $row['user_name'];
            $rating = $row['rating'];
            $comment = $row['comment'];
            $date = date('F j, Y', strtotime($row['review_date']));

            echo "<div class='review'>";
            echo "<h4>$user_name</h4>";
            echo "<p>Rating: $rating stars</p>";
            echo "<p>$comment</p>";
            echo "<small>Posted on $date</small>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "No reviews available for this product.";
    }
}

// Function to display review form
function display_review_form($product_id) {
    echo "<form method='post' action='submit_review.php'>";
    echo "<input type='hidden' name='product_id' value='$product_id'>";
    echo "<div class='form-group'>";
    echo "<label for='user_name'>Name:</label>";
    echo "<input type='text' id='user_name' name='user_name' required>";
    echo "</div>";
    
    echo "<div class='form-group'>";
    echo "<label for='user_email'>Email:</label>";
    echo "<input type='email' id='user_email' name='user_email' required>";
    echo "</div>";
    
    echo "<div class='form-group'>";
    echo "<label for='rating'>Rating:</label><br>";
    echo "<input type='radio' name='rating' value='5' required> 5 stars<br>";
    echo "<input type='radio' name='rating' value='4'> 4 stars<br>";
    echo "<input type='radio' name='rating' value='3'> 3 stars<br>";
    echo "<input type='radio' name='rating' value='2'> 2 stars<br>";
    echo "<input type='radio' name='rating' value='1'> 1 star";
    echo "</div>";
    
    echo "<div class='form-group'>";
    echo "<label for='comment'>Comment:</label>";
    echo "<textarea id='comment' name='comment' rows='5' required></textarea>";
    echo "</div>";
    
    echo "<button type='submit' name='submit_review'>Submit Review</button>";
    echo "</form>";
}

// Close database connection
mysqli_close($conn);
?>



display_review_form($product_id);


display_reviews($product_id);


<?php
// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'reviews';

// Connect to database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to display reviews
function displayReviews() {
    global $conn;
    
    // Query to get all reviews
    $sql = "SELECT * FROM reviews ORDER BY id DESC";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='review'>";
                echo "<h3>" . htmlspecialchars($row['username']) . "</h3>";
                echo "<p>Rating: " . $row['rating'] . " stars</p>";
                echo "<p>Comment: " . htmlspecialchars($row['comment']) . "</p>";
            echo "</div>";
        }
    } else {
        echo "No reviews found!";
    }
}

// Function to handle review submission
function submitReview() {
    global $conn;
    
    // Check if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $rating = $_POST['rating'];
        $comment = $_POST['comment'];
        
        // Validate input
        if (empty($username) || empty($rating) || empty($comment)) {
            die("Please fill in all required fields!");
        }
        
        if (!preg_match("/^[0-9]+$/", $rating) || $rating < 1 || $rating > 5) {
            die("Rating must be between 1 and 5 stars!");
        }
        
        // Sanitize input
        $username = htmlspecialchars(strip_tags($username));
        $comment = htmlspecialchars(strip_tags($comment));
        
        // Insert review into database
        $sql = "INSERT INTO reviews (username, rating, comment) VALUES ('$username', '$rating', '$comment')";
        
        if ($conn->query($sql)) {
            echo "Thank you for your review!";
            displayReviews();
        } else {
            die("Error: " . $sql . "<br>" . $conn->error);
        }
    }
}

// Main function to handle both submission and display
function userReview() {
    // Create reviews table if not exists
    global $conn;
    
    $sql = "CREATE TABLE IF NOT EXISTS reviews (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) NOT NULL,
            rating INT NOT NULL,
            comment TEXT NOT NULL,
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
          )";
          
    if (!$conn->query($sql)) {
        die("Error creating table: " . $conn->error);
    }
    
    // Display review form
    echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
        echo "<div>";
            echo "<label for='username'>Name:</label>";
            echo "<input type='text' id='username' name='username' required><br>";
            
            echo "<label for='rating'>Rating (1-5):</label>";
            echo "<input type='number' id='rating' name='rating' min='1' max='5' required><br>";
            
            echo "<label for='comment'>Comment:</label>";
            echo "<textarea id='comment' name='comment' rows='4' cols='50' required></textarea><br>";
            
            echo "<input type='submit' value='Submit Review'>";
        echo "</div>";
    echo "</form>";
    
    // Submit review if form was submitted
    submitReview();
    
    // Display existing reviews
    displayReviews();
}

// Call the main function
userReview();

// Close database connection
$conn->close();
?>


<?php
function submit_review($product_id, $user_id, $rating = 0, $review_text = '') {
    // Database connection parameters
    $host = 'localhost';
    $db_username = 'username';
    $db_password = 'password';
    $database = 'your_database';

    // Connect to database
    $conn = mysqli_connect($host, $db_username, $db_password, $database);

    if (!$conn) {
        die('Connection failed: ' . mysqli_connect_error());
    }

    // Check if the user has already submitted a review for this product
    $check_review_query = "SELECT id FROM reviews WHERE product_id = $product_id AND user_id = $user_id";
    $result = mysqli_query($conn, $check_review_query);

    if (mysqli_num_rows($result) > 0) {
        echo "You have already submitted a review for this product!";
        return;
    }

    // Insert the new review into the database
    $insert_query = "
        INSERT INTO reviews (
            product_id,
            user_id,
            rating,
            review_text,
            submission_date
        ) VALUES (
            $product_id,
            $user_id,
            $rating,
            '$review_text',
            CURRENT_TIMESTAMP
        )
    ";

    if (mysqli_query($conn, $insert_query)) {
        // Update the product's average rating
        $update_avg_rating = "
            UPDATE products 
            SET avg_rating = (
                SELECT AVG(rating) 
                FROM reviews 
                WHERE product_id = $product_id
            ), review_count = (
                SELECT COUNT(*) 
                FROM reviews 
                WHERE product_id = $product_id
            )
            WHERE id = $product_id
        ";
        
        if (mysqli_query($conn, $update_avg_rating)) {
            echo "Thank you for your review!";
        } else {
            echo "Error updating average rating: " . mysqli_error($conn);
        }
    } else {
        echo "Error submitting review: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
}

// Example usage:
$product_id = 1;
$user_id = 5;
$rating = 4;
$review_text = "Great product! Highly recommended.";

submit_review($product_id, $user_id, $rating, $review_text);
?>


<?php
// Function to display and handle product reviews
function display_reviews($product_id) {
    // Database connection parameters
    $host = "localhost";
    $username = "root";
    $password = "";
    $database_name = "reviews_db";

    // Connect to the database
    $conn = mysqli_connect($host, $username, $password, $database_name);

    // Check if the connection is successful
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // SQL query to fetch all reviews for a specific product
    $sql = "SELECT user_name, review_text, review_date FROM reviews WHERE product_id = ?";
    
    // Prepare and bind parameters
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $product_id);

    // Execute the query
    mysqli_stmt_execute($stmt);

    // Get result set
    $result = mysqli_stmt_get_result($stmt);

    // Check if there are any reviews
    if (mysqli_num_rows($result) == 0) {
        echo "<p>No reviews yet.</p>";
    } else {
        // Display existing reviews
        while ($row = mysqli_fetch_assoc($result)) {
            $review_date = date('d/m/Y H:i', strtotime($row['review_date']));
            echo "
                <div class='review'>
                    <h3>Review by " . htmlspecialchars($row['user_name']) . "</h3>
                    <p>" . htmlspecialchars($row['review_text']) . "</p>
                    <small>Posted on: $review_date</small>
                </div>
            ";
        }
    }

    // Close the statement
    mysqli_stmt_close($stmt);

    // Display review form for new reviews
    echo "
        <form method='post' action='submit_review.php'>
            <input type='hidden' name='product_id' value='" . $product_id . "'>
            <div class='form-group'>
                <label>Your Name:</label>
                <input type='text' name='user_name' required>
            </div>
            <div class='form-group'>
                <label>Review:</label>
                <textarea name='review_text' required></textarea>
            </div>
            <button type='submit'>Submit Review</button>
        </form>
    ";

    // Close database connection
    mysqli_close($conn);
}
?>


<?php 
$product_id = 1; // Replace with actual product ID
display_reviews($product_id);
?>


<?php
// Handle review submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $user_name = mysqli_real_escape_string($conn, $_POST['user_name']);
    $review_text = mysqli_real_escape_string($conn, $_POST['review_text']);

    // Insert into database
    $sql = "INSERT INTO reviews (product_id, user_name, review_text, review_date) 
            VALUES ($product_id, '$user_name', '$review_text', NOW())";

    if (mysqli_query($conn, $sql)) {
        header("Location: product.php?id=$product_id");
    } else {
        echo "Error submitting review: " . mysqli_error($conn);
    }
}
?>


<?php
function handleUserReview($db_connection, $user_id, $item_id, $rating, $comment, $review_id = null, $action = 'add') {
    // Validate inputs
    if (!is_numeric($user_id) || !is_numeric($item_id) || !is_numeric($rating)) {
        return array('status' => 'error', 'message' => 'Invalid input data');
    }

    // Check if the user exists
    $stmt = $db_connection->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->execute(array($user_id));
    $result = $stmt->fetch();
    if (!$result) {
        return array('status' => 'error', 'message' => 'Invalid User ID');
    }

    // Check if the review exists (for update action)
    if ($action == 'update') {
        $stmt = $db_connection->prepare("SELECT id FROM reviews WHERE id = ? AND user_id = ?");
        $stmt->execute(array($review_id, $user_id));
        $result = $stmt->fetch();
        if (!$result) {
            return array('status' => 'error', 'message' => 'Invalid Review ID');
        }
    }

    // Validate rating
    if ($rating < 1 || $rating > 5) {
        return array('status' => 'error', 'message' => 'Rating must be between 1 and 5');
    }

    try {
        if ($action == 'add') {
            // Add new review
            $stmt = $db_connection->prepare("INSERT INTO reviews (user_id, item_id, rating, comment) VALUES (?, ?, ?, ?)");
            $stmt->execute(array($user_id, $item_id, $rating, $comment));
            
        } elseif ($action == 'update') {
            // Update existing review
            $stmt = $db_connection->prepare("UPDATE reviews SET rating = ?, comment = ? WHERE id = ?");
            $stmt->execute(array($rating, $comment, $review_id));
        }

        // Calculate and update average rating for the item
        $stmt = $db_connection->prepare("SELECT AVG(rating) as avg_rating FROM reviews WHERE item_id = ?");
        $stmt->execute(array($item_id));
        $avg_result = $stmt->fetch();
        
        $stmt = $db_connection->prepare("UPDATE items SET average_rating = ? WHERE id = ?");
        $stmt->execute(array($avg_result['avg_rating'], $item_id));

        return array('status' => 'success', 'message' => ($action == 'add') ? 'Review added successfully!' : 'Review updated successfully!');

    } catch(PDOException $e) {
        // Handle any database errors
        error_log("Database error: " . $e->getMessage());
        return array('status' => 'error', 'message' => 'An error occurred while processing your review. Please try again later.');
    }
}
?>


// Add a new review
$result = handleUserReview(
    $db_connection,
    123, // User ID
    456, // Item ID
    5,   // Rating (1-5)
    "Great product!", // Comment
    null, // Review ID (not needed for add)
    'add'
);

// Update an existing review
$result = handleUserReview(
    $db_connection,
    123, // User ID
    456, // Item ID
    4,   // New rating
    "Great product but could be better!", // New comment
    789, // Review ID
    'update'
);

// Check the result
if ($result['status'] == 'success') {
    echo $result['message'];
} else {
    echo $result['message'];
}


<?php
// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'reviews_db';

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function submitReview() {
    // Sanitize and validate input
    if (isset($_POST['submit'])) {
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $rating = intval($_POST['rating']);
        $comment = htmlspecialchars($_POST['comment']);

        // Basic validation
        if ($name == "" || $email == "" || $rating == 0 || $comment == "") {
            echo "Please fill in all required fields.";
            return;
        }

        // Email format validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Please enter a valid email address.";
            return;
        }

        // Database query to insert review
        global $conn;

        $stmt = mysqli_prepare($conn, "
            INSERT INTO reviews (name, email, rating, comment)
            VALUES (?, ?, ?, ?)
        ");

        mysqli_stmt_bind_param($stmt, "ssis", $name, $email, $rating, $comment);

        if (mysqli_stmt_execute($stmt)) {
            echo "Thank you for submitting your review!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    }
}

// Display existing reviews
function displayReviews() {
    global $conn;

    $query = "SELECT * FROM reviews ORDER BY timestamp DESC";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='review'>";
            echo "<h3>" . $row['name'] . "</h3>";
            echo "<p>Rating: " . $row['rating'] . "/5</p>";
            echo "<p>Email: " . $row['email'] . "</p>";
            echo "<p>Comment: " . $row['comment'] . "</p>";
            echo "<small>" . date('F j, Y', strtotime($row['timestamp'])) . "</small>";
            echo "</div>";
        }
    } else {
        echo "No reviews available.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Reviews</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .review-form {
            background-color: #f5f5f5;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .review {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class='review-form'>
    <h2>Submit a Review</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" required><br>

        <label for="rating">Rating:</label><br>
        <select id="rating" name="rating">
            <option value="">Choose a rating</option>
            <option value="1">1 Star</option>
            <option value="2">2 Stars</option>
            <option value="3">3 Stars</option>
            <option value="4">4 Stars</option>
            <option value="5">5 Stars</option>
        </select><br>

        <label for="comment">Comment:</label><br>
        <textarea id="comment" name="comment" rows="5" required></textarea><br>

        <input type="submit" name="submit" value="Submit Review">
    </form>
</div>

<?php
submitReview();
displayReviews();
?>

</body>
</html>


<?php
// Database connection
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create reviews table if not exists
$sql = "CREATE TABLE IF NOT EXISTS reviews (
id INT AUTO_INCREMENT PRIMARY KEY,
user_id VARCHAR(255),
product_id INT,
review_text TEXT,
rating INT,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql)) {
    // echo "Table created successfully";
} else {
    die("Error creating table: " . mysqli_error($conn));
}

// Function to add a review
function add_review($user_id, $product_id, $review_text, $rating) {
    global $conn;
    
    // Sanitize input
    $review_text = htmlspecialchars(mysqli_real_escape_string($conn, $review_text));
    $rating = intval($rating);
    
    $sql = "INSERT INTO reviews (user_id, product_id, review_text, rating)
    VALUES ('$user_id', '$product_id', '$review_text', '$rating')";
    
    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        die("Error adding review: " . mysqli_error($conn));
    }
}

// Function to get reviews for a product
function get_reviews($product_id) {
    global $conn;
    
    $sql = "SELECT * FROM reviews WHERE product_id = '$product_id' ORDER BY created_at DESC";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        return $result;
    } else {
        return false;
    }
}

// Function to calculate average rating
function get_average_rating($product_id) {
    global $conn;
    
    $sql = "SELECT AVG(rating) as average FROM reviews WHERE product_id = '$product_id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    
    return round($row['average'], 1);
}

// Close database connection
mysqli_close($conn);
?>


// Add a new review
if (isset($_POST['submit_review'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_GET['product_id'];
    $review_text = $_POST['review_text'];
    $rating = $_POST['rating'];

    if (add_review($user_id, $product_id, $review_text, $rating)) {
        echo "Thank you for your review!";
    }
}

// Display reviews
$product_id = 1; // Replace with actual product ID
$reviews = get_reviews($product_id);

if ($reviews) {
    while ($row = mysqli_fetch_assoc($reviews)) {
        echo "<div class='review'>";
        echo "<p>Rating: " . $row['rating'] . "/5</p>";
        echo "<p>" . $row['review_text'] . "</p>";
        echo "<small>Posted on: " . $row['created_at'] . "</small>";
        echo "</div>";
    }
}

// Display average rating
echo "Average Rating: " . get_average_rating($product_id) . "/5";


<?php
function submit_review($user_id, $feedback) {
    // Validate input
    if (empty($user_id) || empty($feedback)) {
        return "Please fill in all required fields";
    }
    
    // Sanitize inputs
    $user_id = htmlspecialchars(trim($user_id));
    $feedback = htmlspecialchars(trim($feedback));
    
    // Database connection details
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'reviews_db';
    
    // Connect to database
    $conn = mysqli_connect($host, $username, $password, $database);
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    // Insert review into database
    $sql = "INSERT INTO reviews (user_id, feedback, date_submitted)
            VALUES (?, ?, NOW())";
            
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "is", $user_id, $feedback);
    
    if (mysqli_stmt_execute($stmt)) {
        return "Thank you for your review!";
    } else {
        error_log("Review submission failed: " . mysqli_error($conn));
        return "Error submitting your review. Please try again later.";
    }
    
    // Close database connection
    mysqli_close($conn);
}

// Example usage:
$user_id = 123;
$feedback = "This is a great product!";
$result = submit_review($user_id, $feedback);
echo $result;
?>


<?php
// Function to create or update user reviews
function handleUserReview($userId, $reviewText, $rating) {
    // Validate input
    if (!is_numeric($userId)) {
        return "Error: User ID must be a number.";
    }
    
    if (empty($reviewText)) {
        return "Error: Review text cannot be empty.";
    }
    
    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        return "Error: Rating must be between 1 and 5.";
    }

    // Database connection parameters
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'reviews_db';

    // Connect to database
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    try {
        // Check if user exists
        $checkUserQuery = "SELECT id FROM users WHERE id = ?";
        $stmt = $conn->prepare($checkUserQuery);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return "Error: User not found.";
        }

        // Insert review
        $insertReviewQuery = "INSERT INTO reviews (user_id, review_text, rating) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertReviewQuery);
        $stmt->bind_param('isd', $userId, $reviewText, $rating);

        if ($stmt->execute()) {
            return "Review submitted successfully!";
        } else {
            return "Error: Review could not be submitted. Please try again later.";
        }
    } catch (mysqli_sql_exception $e) {
        return "Database Error: " . $e->getMessage();
    } finally {
        // Close the database connection
        $conn->close();
    }
}

// Example usage:
$userId = 1;
$reviewText = "This is a great product!";
$rating = 5;

$result = handleUserReview($userId, $reviewText, $rating);
echo $result;
?>


<?php
function userReview($productId) {
    // Database connection details
    $host = "localhost";
    $username = "root";
    $password = "";
    $dbname = "reviews_db";

    // Connect to database
    $conn = new mysqli($host, $username, $password, $dbname);
    
    // Check for database connection errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Handle review submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $userId = $_SESSION['user_id']; // Assuming user is logged in
        $rating = $_POST['rating'];
        $comment = $_POST['comment'];

        // Insert review into database
        $sql = "INSERT INTO reviews (product_id, user_id, rating, comment)
                VALUES ($productId, $userId, $rating, '$comment')";
        
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Thank you for your review!');</script>";
            header("Refresh:0");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Display existing reviews
    $sql = "SELECT users.username, reviews.rating, reviews.comment, reviews.submit_date 
            FROM reviews 
            JOIN users ON reviews.user_id = users.id 
            WHERE product_id = $productId 
            ORDER BY submit_date DESC";
    
    $result = $conn->query($sql);

    // Calculate average rating
    $averageRatingSql = "SELECT AVG(rating) as avg_rating FROM reviews WHERE product_id = $productId";
    $averageResult = $conn->query($averageRatingSql);
    $row = $averageResult->fetch_assoc();
    $avgRating = round($row['avg_rating'], 1);

    // Display average rating and reviews
    echo "<div class='review-section'>";
    echo "<h3>Average Rating: " . ($avgRating !== null ? $avgRating : 0) . "/5</h3>";
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div class='review'>";
            echo "<p><strong>" . $row['username'] . "</strong></p>";
            echo "<p>Rating: " . str_repeat("★", $row['rating']) . str_repeat("☆", 5 - $row['rating']) . "</p>";
            echo "<p>" . $row['comment'] . "</p>";
            echo "<p class='review-date'>" . $row['submit_date'] . "</p>";
            echo "</div>";
        }
    } else {
        echo "<p>No reviews yet.</p>";
    }

    // Display review form
    if (isset($_SESSION['user_id'])) { // Only logged in users can leave reviews
        echo "<form method='post'>";
        echo "<h3>Leave a Review</h3>";
        echo "<input type='radio' name='rating' value='5'> ★★★★★<br>";
        echo "<input type='radio' name='rating' value='4'> ★★★★☆<br>";
        echo "<input type='radio' name='rating' value='3'> ★★★☆☆<br>";
        echo "<input type='radio' name='rating' value='2'> ★★☆☆☆<br>";
        echo "<input type='radio' name='rating' value='1'> ★☆☆☆☆<br>";
        echo "<textarea name='comment' placeholder='Write your review here...'></textarea><br>";
        echo "<input type='submit' value='Submit Review'>";
        echo "</form>";
    } else {
        echo "<p>Please login to leave a review.</p>";
    }

    echo "</div>";

    // Close database connection
    $conn->close();
}
?>


<?php
session_start();
// Include the function definition
require_once 'review_function.php';

// Display reviews for product ID 1
userReview(1);
?>


<?php
// Database configuration
$host = 'localhost';
$dbname = 'reviews_db';
$username = 'root';
$password = '';

try {
    // Create database connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

function display_reviews($product_id) {
    global $conn;
    
    // SQL query to fetch reviews
    $sql = "SELECT review_id, user_name, review_text, rating, review_date 
            FROM reviews 
            WHERE product_id = :product_id 
            ORDER BY review_date DESC";
            
    try {
        // Prepare and execute the statement
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        
        // Check if there are any reviews
        if ($stmt->rowCount() == 0) {
            echo "<div class='no-reviews'>No reviews yet!</div>";
            return;
        }
        
        // Display each review
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $user_name = htmlspecialchars($row['user_name']);
            $review_text = htmlspecialchars($row['review_text']);
            $rating = $row['rating'];
            $review_date = date('F j, Y', strtotime($row['review_date']));
            
            echo "<div class='review'>";
                echo "<h3>$user_name</h3>";
                echo "<p>Rating: " . get_stars($rating) . "</p>";
                echo "<p>$review_text</p>";
                echo "<small>Posted on $review_date</small>";
            echo "</div>";
        }
        
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}

function get_stars($rating = 0) {
    // Maximum rating
    $max_rating = 5;
    
    $full_star = "<span class='star'>★</span>";
    $half_star = "<span class='star'>⯨</span>"; // Half star
    $empty_star = "<span class='star'>☆</span>";
    
    $stars_html = '';
    
    if ($rating > 0 && $rating <= $max_rating) {
        for ($i = 1; $i <= $max_rating; $i++) {
            if ($rating >= $i + 0.5) {
                $stars_html .= $full_star;
            } elseif ($rating >= $i - 0.5) {
                $stars_html .= $half_star;
            } else {
                $stars_html .= $empty_star;
            }
        }
    } else {
        // Show empty stars if no rating
        for ($i = 1; $i <= $max_rating; $i++) {
            $stars_html .= $empty_star;
        }
    }
    
    return $stars_html;
}

// Example usage:
if (isset($_GET['product_id']) && is_numeric($_GET['product_id'])) {
    display_reviews($_GET['product_id']);
} else {
    echo "Invalid product ID.";
}
?>


<?php
function submit_review($user_id, $product_id, $rating, $review_text) {
    // Database connection details
    $db_host = "localhost";
    $db_username = "username";
    $db_password = "password";
    $db_name = "reviews_db";

    // Connect to database
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL statement to insert review into database
    $sql = "INSERT INTO reviews (user_id, product_id, rating, review_text, review_date)
            VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)";

    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters to the prepared statement
        $stmt->bind_param("iiss", $user_id, $product_id, $rating, $review_text);

        if ($stmt->execute()) {
            // Update product average rating
            $update_avg_rating_sql = "UPDATE products SET 
                                        avg_rating = (SELECT AVG(rating) FROM reviews WHERE product_id = ?)
                                      WHERE id = ?";
            
            $update_stmt = $conn->prepare($update_avg_rating_sql);
            $update_stmt->bind_param("ii", $product_id, $product_id);
            $update_stmt->execute();
            $update_stmt->close();

            // Close statement and connection
            $stmt->close();
            $conn->close();

            return "Review submitted successfully!";
        } else {
            // Close statement and connection
            $stmt->close();
            $conn->close();
            
            return "Error submitting review: " . $stmt->error;
        }
    } else {
        // Close connection
        $conn->close();
        
        return "Error preparing review submission: " . $conn->error;
    }
}
?>


<?php
function get_user_reviews($item_id, $sort_by = 'recent', $page = 1, $items_per_page = 5, $db_connection) {
    // Calculate offset for pagination
    $offset = ($page - 1) * $items_per_page;

    // Get total number of reviews for this item
    $total_reviews_query = "SELECT COUNT(*) as total FROM reviews WHERE item_id = ?";
    $stmt = mysqli_prepare($db_connection, $total_reviews_query);
    mysqli_stmt_bind_param($stmt, "i", $item_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    $total_reviews = $row['total'];

    // Determine sorting order
    switch ($sort_by) {
        case 'highest-rated':
            $order_by = "rating DESC, review_date DESC";
            break;
        case 'lowest-rated':
            $order_by = "rating ASC, review_date DESC";
            break;
        default: // Most recent by date
            $order_by = "review_date DESC";
    }

    // Get reviews for this item
    $reviews_query = "
        SELECT 
            u.username,
            r.rating,
            r.comment,
            r.review_date
        FROM reviews r
        JOIN users u ON r.user_id = u.user_id
        WHERE r.item_id = ?
        ORDER BY $order_by
        LIMIT ? OFFSET ?";
    
    $stmt = mysqli_prepare($db_connection, $reviews_query);
    mysqli_stmt_bind_param($stmt, "iii", $item_id, $items_per_page, $offset);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Fetch reviews into an array
    $reviews = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $review_date_formatted = date('Y-m-d', strtotime($row['review_date']));
        $reviews[] = [
            'username' => $row['username'],
            'rating' => $row['rating'],
            'comment' => $row['comment'],
            'date' => $review_date_formatted
        ];
    }

    // Return the results and pagination info
    return [
        'reviews' => $reviews,
        'total_reviews' => $total_reviews,
        'current_page' => $page,
        'items_per_page' => $items_per_page
    ];
}

// Example usage:
$db_connection = mysqli_connect("localhost", "username", "password", "database");

if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}

$reviews_data = get_user_reviews(
    item_id: 1,
    sort_by: 'highest-rated',
    page: 1,
    items_per_page: 5,
    db_connection: $db_connection
);

// Display reviews
foreach ($reviews_data['reviews'] as $review) {
    echo "<div class='review'>";
    echo "<h3>{$review['username']}</h3>";
    echo "<p>Rating: {$review['rating']} stars</p>";
    echo "<p>{$review['comment']}</p>";
    echo "<small>{$review['date']}</small>";
    echo "</div>";
}

// Display pagination
$total_pages = ceil($reviews_data['total_reviews'] / $reviews_data['items_per_page']);
for ($i = 1; $i <= $total_pages; $i++) {
    echo "<a href='?page=$i'>Page $i</a> ";
}

mysqli_close($db_connection);
?>


<?php
// Function to display user reviews for a product
function get_user_reviews($product_id) {
    // Database connection parameters
    $host = 'localhost';
    $user = 'username';
    $password = 'password';
    $database = 'reviews_db';

    // Create database connection
    $conn = new mysqli($host, $user, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to fetch reviews for the specified product
    $sql = "SELECT user_name, rating, review_text, review_date 
            FROM reviews 
            WHERE product_id = ? 
            ORDER BY review_date DESC";

    // Prepare statement
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameter
    $stmt->bind_param('i', $product_id);

    // Execute query
    $stmt->execute();

    // Get result
    $result = $stmt->get_result();

    // Generate HTML for reviews
    $reviews_html = '';
    while ($row = $result->fetch_assoc()) {
        $reviews_html .= '<div class="review">';
        $reviews_html .= '<div class="review-header">';
        $reviews_html .= '<span class="user-name">' . htmlspecialchars($row['user_name']) . '</span>';
        $reviews_html .= '<div class="rating">';
        
        // Display stars based on rating
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $row['rating']) {
                $reviews_html .= '★';
            } else {
                $reviews_html .= '☆';
            }
        }
        
        $reviews_html .= '</div>';
        $reviews_html .= '</div>';
        $reviews_html .= '<div class="review-body">';
        $reviews_html .= htmlspecialchars($row['review_text']);
        $reviews_html .= '</div>';
        $reviews_html .= '<div class="review-footer">';
        $reviews_html .= 'Date: ' . $row['review_date'];
        $reviews_html .= '</div>';
        $reviews_html .= '</div>';
    }

    // Close database connection
    $stmt->close();
    $conn->close();

    return $reviews_html;
}

// Example usage:
$product_id = 1; // Replace with the actual product ID
$reviews = get_user_reviews($product_id);
echo $reviews;
?>


function handleUserReview($conn, $action, $user_id, $product_id, $comment = "", $rating = 0, $review_id = "") {
    // Sanitize input data
    $comment = mysqli_real_escape_string($conn, $comment);
    
    switch ($action) {
        case 'add':
            // Add a new review
            $sql = "INSERT INTO reviews (user_id, product_id, comment, rating, created_at) 
                    VALUES (?, ?, ?, ?, NOW())";
            
            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "iiss", $user_id, $product_id, $comment, $rating);
                
                if (mysqli_stmt_execute($stmt)) {
                    // Get the last inserted review ID
                    $review_id = mysqli_insert_id($conn);
                    return array(
                        'status' => 'success',
                        'message' => 'Review added successfully!',
                        'review_id' => $review_id
                    );
                } else {
                    return array(
                        'status' => 'error',
                        'message' => 'Error adding review: ' . mysqli_stmt_error($stmt)
                    );
                }
                mysqli_stmt_close($stmt);
            }
            break;
            
        case 'update':
            // Update an existing review
            $sql = "UPDATE reviews SET comment=?, rating=? WHERE id=? AND user_id=?";
            
            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "sisi", $comment, $rating, $review_id, $user_id);
                
                if (mysqli_stmt_execute($stmt)) {
                    return array(
                        'status' => 'success',
                        'message' => 'Review updated successfully!'
                    );
                } else {
                    return array(
                        'status' => 'error',
                        'message' => 'Error updating review: ' . mysqli_stmt_error($stmt)
                    );
                }
                mysqli_stmt_close($stmt);
            }
            break;
            
        case 'delete':
            // Delete a review
            $sql = "DELETE FROM reviews WHERE id=? AND user_id=?";
            
            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "ii", $review_id, $user_id);
                
                if (mysqli_stmt_execute($stmt)) {
                    return array(
                        'status' => 'success',
                        'message' => 'Review deleted successfully!'
                    );
                } else {
                    return array(
                        'status' => 'error',
                        'message' => 'Error deleting review: ' . mysqli_stmt_error($stmt)
                    );
                }
                mysqli_stmt_close($stmt);
            }
            break;
            
        default:
            // Invalid action
            return array(
                'status' => 'error',
                'message' => 'Invalid action specified!'
            );
    }
}


$result = handleUserReview($conn, 'add', $user_id, $product_id, $comment, $rating);


$result = handleUserReview($conn, 'update', $user_id, $product_id, $new_comment, $new_rating, $review_id);


$result = handleUserReview($conn, 'delete', $user_id, $product_id, "", 0, $review_id);


<?php
// Initialize reviews array (you might want to use a database in a real application)
$reviews = array();

// Function to handle reviews
function user_reviews() {
    global $reviews;
    
    // Review submission form
    if (!isset($_POST['submit_review'])) {
        display_review_form();
    } else {
        // Process submitted review
        $name = htmlspecialchars(trim($_POST['review_name']));
        $rating = (int)$_POST['review_rating'];
        
        // Validate input
        if ($name == "" || $rating < 1 || $rating > 5) {
            echo "Please enter a valid name and rating between 1-5 stars!";
        } else {
            // Store the review (in memory - not persistent)
            $review_id = count($reviews) + 1;
            $reviews[] = array(
                'id' => $review_id,
                'name' => $name,
                'rating' => $rating,
                'date' => date('Y-m-d H:i:s')
            );
            
            // Redirect to view reviews
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }
}

// Function to display review form
function display_review_form() {
    global $reviews;
    
    ?>
    <html>
    <head>
        <title>Product Review</title>
        <style>
            .stars {
                color: gold;
            }
            .error { color: red; }
        </style>
    </head>
    <body>
        <?php if (!empty($reviews)) { 
            // Display existing reviews
            echo "<h2>Reviews:</h2>";
            foreach ($reviews as $review) {
                $stars = str_repeat("★", $review['rating']) . str_repeat("☆", 5 - $review['rating']);
                echo "<div style='border: 1px solid #ddd; padding: 10px; margin-bottom: 10px;'>";
                echo "<h3>Reviewer: {$review['name']}</h3>";
                echo "<p class='stars'>{$stars}</p>";
                echo "<small>Posted on: {$review['date']}</small>";
                echo "</div>";
            }
        } ?>

        <!-- Review Form -->
        <div style="margin-top: 20px;">
            <?php if (isset($_GET['error'])) { 
                // Display error message
                echo "<div class='error'>Error: Please fill in all required fields!</div>";
            } ?>
            
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <h2>Submit Your Review</h2>
                <p>Name:</p>
                <input type="text" name="review_name" required><br>
                
                <p>Rating (1-5 stars):</p>
                <?php for ($i = 1; $i <= 5; $i++) { 
                    // Display rating options
                    echo "<input type='radio' name='review_rating' value='{$i}' checked {$i == 5 ? 'checked' : ''}>{$i} stars <br>";
                } ?>

                <input type="submit" name="submit_review" value="Submit Review">
            </form>
        </div>
    </body>
    </html>
    <?php
}

// Call the user_reviews function when this script is loaded
if (isset($_GET['action']) && $_GET['action'] == 'reviews') {
    user_reviews();
} else {
    // Main content of your page
    echo "<h1>Welcome to Our Product Page</h1>";
    echo "<a href='".$_SERVER['PHP_SELF']."?action=reviews'>View and Submit Reviews</a>";
}
?>


<?php
// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$db_name = 'reviews_db';

$conn = mysqli_connect($host, $username, $password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to display reviews
function display_reviews() {
    global $conn;
    
    $query = "SELECT * FROM reviews ORDER BY id DESC";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='review'>";
            echo "<h3>Review by " . htmlspecialchars($row['author']) . "</h3>";
            echo "<p>" . nl2br(htmlspecialchars($row['content'])) . "</p>";
            echo "<p>Rating: " . $row['rating'] . "/5</p>";
            echo "<p>Submitted on: " . date('F j, Y', strtotime($row['timestamp'])) . "</p>";
            echo "</div>";
        }
    } else {
        echo "<p>No reviews available yet.</p>";
    }
}

// Function to submit a new review
function submit_review($author, $content, $rating) {
    global $conn;
    
    // Validate input
    if (empty($author) || empty($content) || !is_numeric($rating)) {
        return false;
    }
    
    $rating = intval($rating);
    
    if ($rating < 1 || $rating > 5) {
        return false;
    }
    
    // Sanitize input
    $author = htmlspecialchars(mysqli_real_escape_string($conn, $author), ENT_QUOTES);
    $content = htmlspecialchars(mysqli_real_escape_string($conn, $content), ENT_QUOTES);
    
    // Insert into database
    $query = "INSERT INTO reviews (author, content, rating, timestamp) 
              VALUES ('$author', '$content', '$rating', NOW())";
              
    if (!mysqli_query($conn, $query)) {
        return false;
    }
    
    return true;
}

// Function to add a rating
function add_rating($id, $rating) {
    global $conn;
    
    // Validate input
    if (!is_numeric($id) || !is_numeric($rating)) {
        return false;
    }
    
    $id = intval($id);
    $rating = intval($rating);
    
    if ($rating < 1 || $rating > 5) {
        return false;
    }
    
    // Update rating in database
    $query = "UPDATE reviews SET rating = '$rating' WHERE id = '$id'";
    
    if (!mysqli_query($conn, $query)) {
        return false;
    }
    
    return true;
}

// Function to validate review input
function validate_review($author, $content, $rating) {
    $errors = array();
    
    if (empty($author)) {
        $errors[] = "Please enter your name.";
    }
    
    if (empty($content)) {
        $errors[] = "Please write a review.";
    }
    
    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        $errors[] = "Please select a valid rating between 1 and 5.";
    }
    
    return $errors;
}
?>

<!-- Sample HTML form -->
<!DOCTYPE html>
<html>
<head>
    <title>Review System</title>
    <style>
        .review {
            margin: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 10px;
        }
        input, textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<?php
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $author = $_POST['author'];
    $content = $_POST['content'];
    $rating = $_POST['rating'];
    
    $errors = validate_review($author, $content, $rating);
    
    if (empty($errors)) {
        if (submit_review($author, $content, $rating)) {
            echo "<p>Thank you for your review!</p>";
        } else {
            echo "<p>Error submitting review. Please try again.</p>";
        }
    } else {
        foreach ($errors as $error) {
            echo "<p style='color: red'>" . $error . "</p>";
        }
    }
}
?>

<h1>Leave a Review</h1>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <div class="form-group">
        <label for="author">Name:</label>
        <input type="text" id="author" name="author" required>
    </div>
    
    <div class="form-group">
        <label for="content">Review:</label>
        <textarea id="content" name="content" rows="5" required></textarea>
    </div>
    
    <div class="form-group">
        <label>Rating:</label><br>
        <input type="radio" name="rating" value="5" required> 5
        <input type="radio" name="rating" value="4"> 4
        <input type="radio" name="rating" value="3"> 3
        <input type="radio" name="rating" value="2"> 2
        <input type="radio" name="rating" value="1"> 1
    </div>
    
    <button type="submit">Submit Review</button>
</form>

<h2>Reviews</h2>
<?php display_reviews(); ?>

</body>
</html>


<?php
function submit_review($user_id, $rating, $comment = null) {
    try {
        // Validate inputs
        if (!is_numeric($user_id) || $user_id <= 0) {
            throw new Exception("Invalid user ID");
        }
        
        if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
            throw new Exception("Rating must be between 1 and 5");
        }
        
        // Database connection (assuming $conn is a mysqli object)
        global $conn;
        
        // Prepare and execute the SQL statement
        $sql = "INSERT INTO reviews (user_id, rating, comment) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if ($comment === null) {
            $stmt->bind_param("iis", $user_id, $rating, NULL);
        } else {
            $stmt->bind_param("iis", $user_id, $rating, $comment);
        }
        
        if (!$stmt->execute()) {
            throw new Exception("Error adding review: " . $stmt->error);
        }
        
        return true; // Review added successfully
        
    } catch (Exception $e) {
        // Log the error or handle it as needed
        echo "Error submitting review: " . $e->getMessage();
        return false;
    }
}
?>


// Example usage:
$user_id = 123;
$rating = 5;
$comment = "Great product!";

if (submit_review($user_id, $rating, $comment)) {
    echo "Review submitted successfully!";
}


<?php
// Database connection
$host = 'localhost';
$dbname = 'reviews_db';
$user = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Create review table
$sql = "CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_id INT,
    rating INT,
    comment TEXT,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    votes INT DEFAULT 0
)";

try {
    $conn->exec($sql);
} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}

// Function to submit a review
function submitReview($product_id, $user_id, $rating, $comment) {
    global $conn;
    
    // Sanitize inputs
    $product_id = intval($product_id);
    $user_id = intval($user_id);
    $rating = intval($rating);
    $comment = htmlspecialchars(strip_tags($comment));
    
    try {
        $stmt = $conn->prepare("INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
        $stmt->execute([$product_id, $user_id, $rating, $comment]);
        
        return true;
    } catch (PDOException $e) {
        echo "Error submitting review: " . $e->getMessage();
        return false;
    }
}

// Function to display reviews
function displayReviews($product_id) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("SELECT r.*, u.name AS username FROM reviews r JOIN users u ON r.user_id = u.id WHERE product_id = ? ORDER BY timestamp DESC");
        $stmt->execute([$product_id]);
        
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($reviews)) {
            echo "No reviews available for this product.";
            return;
        }
        
        foreach ($reviews as $review) {
            echo "<div class='review'>";
                echo "<h3>Review by: " . htmlspecialchars($review['username']) . "</h3>";
                echo "<p>Rating: " . str_repeat("★", $review['rating']) . "</p>";
                echo "<p>Comment: " . htmlspecialchars($review['comment']) . "</p>";
                echo "<div class='vote-section'>";
                    echo "<button onclick='voteReview(" . $review['id'] . ", 1)'>↑ Upvote</button>";
                    echo "<span>Votes: " . $review['votes'] . "</span>";
                    echo "<button onclick='voteReview(" . $review['id'] . ", -1)'>↓ Downvote</button>";
                echo "</div>";
                echo "<small>Posted on: " . $review['timestamp'] . "</small>";
            echo "</div>";
        }
    } catch (PDOException $e) {
        echo "Error displaying reviews: " . $e->getMessage();
    }
}

// Function to vote for a review
function voteReview($review_id, $vote_type) {
    global $conn;
    
    // Check if user is logged in
    session_start();
    if (!isset($_SESSION['user_id'])) {
        die("Please login to vote!");
    }
    
    try {
        $current_votes = 0;
        
        // Get current votes
        $stmt = $conn->prepare("SELECT votes FROM reviews WHERE id = ?");
        $stmt->execute([$review_id]);
        if ($row = $stmt->fetch()) {
            $current_votes = $row['votes'];
        }
        
        // Update votes
        $new_votes = $current_votes + $vote_type;
        $new_votes = max($new_votes, 0); // Don't allow negative votes
        
        $stmt = $conn->prepare("UPDATE reviews SET votes = ? WHERE id = ?");
        $stmt->execute([$new_votes, $review_id]);
        
        return true;
    } catch (PDOException $e) {
        echo "Error voting: " . $e->getMessage();
        return false;
    }
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['submit_review'])) {
        // Check if user is logged in
        session_start();
        if (!isset($_SESSION['user_id'])) {
            die("Please login to submit a review!");
        }
        
        $product_id = $_POST['product_id'];
        $user_id = $_SESSION['user_id'];
        $rating = $_POST['rating'];
        $comment = $_POST['comment'];
        
        if (submitReview($product_id, $user_id, $rating, $comment)) {
            echo "Thank you for submitting your review!";
        }
    }
}

// Display login form
function displayLoginForm() {
    echo "<form action='login.php' method='post'>";
        echo "<input type='email' name='email' placeholder='Email' required>";
        echo "<input type='password' name='password' placeholder='Password' required>";
        echo "<button type='submit'>Login</button>";
    echo "</form>";
}

// Example login script
if (isset($_POST['login'])) {
    $email = htmlspecialchars(strip_tags($_POST['email']));
    $password = $_POST['password'];
    
    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($row = $stmt->fetch()) {
            if (password_verify($password, $row['password'])) {
                session_start();
                $_SESSION['user_id'] = $row['id'];
                header('Location: review_page.php');
            } else {
                echo "Invalid credentials!";
            }
        } else {
            echo "User not found!";
        }
    } catch (PDOException $e) {
        echo "Error logging in: " . $e->getMessage();
    }
}
?>


<?php
function submit_review($user_id, $product_id, $rating, $comment) {
    // Database connection details
    $servername = "localhost";
    $username = "username";
    $password = "password";
    $dbname = "reviews_db";

    try {
        // Create database connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        
        // Check connection
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        // Prepare SQL statement to insert review
        $sql = "INSERT INTO reviews (user_id, product_id, rating, comment, timestamp)
                VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)";
        
        // Prepare and bind parameters
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("iisi", $user_id, $product_id, $rating, $comment);

        // Execute the statement
        if ($stmt->execute()) {
            return true; // Review submitted successfully
        } else {
            throw new Exception("Error submitting review: " . $stmt->error);
        }

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    } finally {
        // Close statement and connection if they exist
        if ($stmt) {
            $stmt->close();
        }
        if ($conn) {
            $conn->close();
        }
    }
}

// Example usage:
try {
    $user_id = 123; // Replace with actual user ID
    $product_id = 456; // Replace with actual product ID
    $rating = 5; // Rating between 1 and 5
    $comment = "This is a great product!"; // Review comment

    if (submit_review($user_id, $product_id, $rating, $comment)) {
        echo "Review submitted successfully!";
    }
} catch (Exception $e) {
    echo "Error submitting review: " . $e->getMessage();
}
?>


<?php
// Database configuration
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle review submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $rating = intval($_POST['rating']);
    $review = htmlspecialchars(trim($_POST['review']));

    // Basic validation
    if (empty($name) || empty($email) || empty($rating) || empty($review)) {
        $error = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } elseif ($rating < 1 || $rating > 5) {
        $error = "Rating must be between 1 and 5!";
    } else {
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO reviews (name, email, rating, review, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssii", $name, $email, $rating, $review);
        
        if ($stmt->execute()) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $error = "Error submitting review!";
        }
    }
}

// Retrieve reviews
$reviews = array();
$sql = "SELECT * FROM reviews ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reviews[] = array(
            'id' => $row['id'],
            'name' => $row['name'],
            'email' => $row['email'],
            'rating' => $row['rating'],
            'review' => $row['review'],
            'created_at' => $row['created_at']
        );
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Reviews</title>
    <style>
        .stars {
            display: inline-block;
            margin-right: 10px;
        }

        .star {
            color: #FFD700;
            font-size: 24px;
        }

        .review-form, .review-list {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .review-item {
            border-bottom: 1px solid #ddd;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="review-form">
        <?php if (isset($error)) { ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php } ?>

        <h2>Submit a Review</h2>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div>
                <label for="name">Name:</label><br>
                <input type="text" id="name" name="name">
            </div>

            <div>
                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email">
            </div>

            <div>
                <label for="rating">Rating:</label><br>
                <select id="rating" name="rating">
                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?> Stars</option>
                    <?php } ?>
                </select>
            </div>

            <div>
                <label for="review">Review:</label><br>
                <textarea id="review" name="review" rows="5"></textarea>
            </div>

            <button type="submit">Submit Review</button>
        </form>
    </div>

    <div class="review-list">
        <h2>Reviews</h2>
        <?php if (empty($reviews)) { ?>
            <p>No reviews yet!</p>
        <?php } else { ?>
            <?php foreach ($reviews as $review) { ?>
                <div class="review-item">
                    <div class="stars">
                        <?php for ($i = 1; $i <= $review['rating']; $i++) { ?>
                            <span class="star">★</span>
                        <?php } ?>
                    </div>
                    <h3><?php echo $review['name']; ?></h3>
                    <p><?php echo $review['email']; ?></p>
                    <p><?php echo $review['review']; ?></p>
                    <small><?php echo date('F j, Y', strtotime($review['created_at'])); ?></small>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</body>
</html>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'reviews_db';

// Connect to database
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input data
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get product ID from hidden field
    $product_id = $_POST['product_id'];
    
    // Validate and sanitize inputs
    $user_name = sanitizeInput($_POST['user_name']);
    $user_email = sanitizeInput($_POST['user_email']);
    $rating = intval($_POST['rating']);
    $review_text = sanitizeInput($_POST['review_text']);

    // Check for empty fields
    if (empty($user_name) || empty($user_email) || empty($rating) || empty($review_text)) {
        die("All fields are required!");
    }

    // Validate email format
    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format!");
    }

    // Validate rating (1-5)
    if ($rating < 1 || $rating > 5) {
        die("Rating must be between 1 and 5 stars!");
    }

    // Insert review into database
    $stmt = $conn->prepare("INSERT INTO reviews (product_id, user_name, user_email, rating, review_text) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isiii", $product_id, $user_name, $user_email, $rating, $review_text);

    if ($stmt->execute()) {
        echo "Thank you for submitting your review!";
    } else {
        die("Error: " . $stmt->error);
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    die("Invalid request method!");
}
?>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'reviews_db';

// Connect to database
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to fetch and display reviews for a product
function getReviewsByProduct($product_id) {
    global $conn;

    $reviews = array();
    
    // Fetch all reviews for the given product ID
    $stmt = $conn->prepare("SELECT * FROM reviews WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();

    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $reviews[] = array(
            'id' => $row['id'],
            'user_name' => $row['user_name'],
            'rating' => $row['rating'],
            'review_text' => $row['review_text'],
            'created_at' => $row['created_at']
        );
    }

    // Close statement and connection
    $stmt->close();

    return $reviews;
}

// Example usage:
$product_id = 1; // Replace with your product ID
$reviews = getReviewsByProduct($product_id);

foreach ($reviews as $review) {
    echo "<div class='review'>";
    echo "<h3>" . $review['user_name'] . "</h3>";
    echo "<p>Rating: " . str_repeat("★", $review['rating']) . "</p>";
    echo "<p>" . $review['review_text'] . "</p>";
    echo "<small>" . date('F j, Y', strtotime($review['created_at'])) . "</small>";
    echo "</div>";
}

$conn->close();
?>


<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'reviews_db';

// Create database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to submit a review
function submitReview($user_id, $rating, $review_text) {
    global $conn;
    
    // Sanitize input
    $user_id = mysqli_real_escape_string($conn, trim($user_id));
    $rating = intval($rating);
    $review_text = mysqli_real_escape_string($conn, trim($review_text));
    
    // Check if rating is valid (1-5)
    if ($rating < 1 || $rating > 5) {
        return "Invalid rating. Please choose a rating between 1 and 5.";
    }
    
    // Insert review into database
    $sql = "INSERT INTO reviews (user_id, rating, review_text, review_date) 
            VALUES ('$user_id', '$rating', '$review_text', NOW())";
    
    if ($conn->query($sql)) {
        return "Review submitted successfully!";
    } else {
        return "Error submitting review: " . $conn->error;
    }
}

// Function to display reviews
function displayReviews() {
    global $conn;
    
    // Get all reviews from database
    $sql = "SELECT * FROM reviews ORDER BY review_date DESC";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='review'>";
            echo "<h3>Rating: " . str_repeat("★", $row['rating']) . "</h3>";
            echo "<p>Review: " . htmlspecialchars($row['review_text']) . "</p>";
            echo "<small>Posted by: " . htmlspecialchars($row['user_id']) . " on " . date('F j, Y g:i a', strtotime($row['review_date'])) . "</small>";
            echo "</div>";
        }
    } else {
        echo "No reviews found.";
    }
}

// Function to calculate average rating
function getAverageRating() {
    global $conn;
    
    // Get all ratings from database
    $sql = "SELECT AVG(rating) as average FROM reviews";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return round($row['average'], 1); // Round to one decimal place
    }
    return 0;
}

// Function to display star rating
function displayStars($rating) {
    $full_stars = intval($rating);
    $has_half_star = ($rating - $full_stars) >= 0.5;
    
    echo "<div class='stars'>";
    for ($i = 0; $i < $full_stars; $i++) {
        echo "★ ";
    }
    if ($has_half_star) {
        echo "⯨ "; // Half star
    }
    echo "</div>";
}

// Main script
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $review_result = submitReview($_POST['username'], $_POST['rating'], $_POST['review']);
    echo "<p>" . $review_result . "</p>";
}

// Display average rating
echo "<h2>Rating: ";
displayStars(getAverageRating());
echo "</h2>";

// Review form
?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="text" name="username" placeholder="Enter your username" required><br>
    <select name="rating" required>
        <option value="">Choose a rating</option>
        <option value="1">1 Star</option>
        <option value="2">2 Stars</option>
        <option value="3">3 Stars</option>
        <option value="4">4 Stars</option>
        <option value="5">5 Stars</option>
    </select><br>
    <textarea name="review" placeholder="Write your review..." required></textarea><br>
    <input type="submit" value="Submit Review">
</form>

<?php
// Display all reviews
displayReviews();

// Close database connection
$conn->close();
?>


<?php
// Database connection settings
$host = 'localhost';
$username = 'username';
$password = 'password';
$dbname = 'reviews_db';

// Create database connection
$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to handle review submission
function submitReview($user_name, $rating, $comment, $conn) {
    // Sanitize inputs to prevent SQL injection and XSS
    $user_name = mysqli_real_escape_string($conn, htmlspecialchars($user_name));
    $rating = intval(mysqli_real_escape_string($conn, $rating));
    $comment = mysqli_real_escape_string($conn, htmlspecialchars($comment));
    
    // Check for valid rating (1-5)
    if ($rating < 1 || $rating > 5) {
        return false;
    }
    
    // Insert into database
    $sql = "INSERT INTO reviews (user_name, rating, comment, date, status) 
            VALUES ('$user_name', '$rating', '$comment', NOW(), 'pending')";
    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        return false;
    }
}

// Function to fetch and display approved reviews
function displayReviews($conn) {
    // Fetch all approved reviews ordered by date
    $sql = "SELECT * FROM reviews WHERE status='approved' ORDER BY date DESC";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Display each review
            echo "<div class='review'>";
            echo "<h3>" . htmlspecialchars_decode($row['user_name']) . "</h3>";
            echo "<p>Rating: " . str_repeat("★", $row['rating']) . str_repeat("☆", 5 - $row['rating']) . "</p>";
            echo "<p>" . htmlspecialchars_decode($row['comment']) . "</p>";
            echo "<small>" . date('F j, Y', strtotime($row['date'])) . "</small>";
            echo "</div>";
        }
    } else {
        // If no reviews yet
        echo "<p>No reviews available. Be the first to review!</p>";
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = $_POST['user_name'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    
    // Submit the review and show feedback
    if (submitReview($user_name, $rating, $comment, $conn)) {
        echo "<div class='success'>Thank you for your review!</div>";
    } else {
        echo "<div class='error'>Error submitting review. Please try again.</div>";
    }
}

// Display existing reviews
displayReviews($conn);

// Close database connection
mysqli_close($conn);
?>


<?php
// Include database configuration file
include 'db_config.php';

// Function to validate input data
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to add review
function add_review($con) {
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get values from form
        $name = test_input($_POST['name']);
        $email = test_input($_POST['email']);
        $rating = $_POST['rating'];
        $comment = test_input($_POST['comment']);

        // Validate input data
        $errors = array();

        if (empty($name)) {
            $errors['name'] = "Name is required";
        }

        if (empty($email)) {
            $errors['email'] = "Email is required";
        } else {
            // Check email format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Invalid email format";
            }
        }

        if (empty($rating) || !ctype_digit($rating) || $rating < 1 || $rating > 5) {
            $errors['rating'] = "Rating must be between 1 and 5";
        }

        if (empty($comment)) {
            $errors['comment'] = "Comment is required";
        } else {
            // Check comment length
            if (strlen($comment) < 5) {
                $errors['comment'] = "Comment must be at least 5 characters long";
            }
        }

        // If no errors, insert into database
        if (empty($errors)) {
            try {
                // Prepare and execute the query
                $stmt = $con->prepare("INSERT INTO reviews (name, email, rating, comment) 
                    VALUES (:name, :email, :rating, :comment)");
                $stmt->execute(array(
                    ':name' => $name,
                    ':email' => $email,
                    ':rating' => $rating,
                    ':comment' => $comment
                ));
                
                // Success message
                echo "Thank you for your review!";
                return;
            } catch(PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        } else {
            // Display error messages
            foreach ($errors as $key => $value) {
                echo "<div class='alert alert-danger'>{$value}</div>";
            }
        }
    }
}

// Function to display reviews
function display_reviews($con) {
    try {
        // Prepare and execute the query
        $stmt = $con->query("SELECT * FROM reviews ORDER BY id DESC");
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Display each review
        foreach ($reviews as $review) {
            echo "<div class='review'>";
            echo "<h3>" . $review['name'] . "</h3>";
            echo "<p>Rating: " . str_repeat("★", $review['rating']) . "</p>";
            echo "<p>Comment: " . $review['comment'] . "</p>";
            echo "<small>Posted on: " . date('F j, Y', strtotime($review['created_at'])) . "</small>";
            echo "</div>";
        }
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
?>

