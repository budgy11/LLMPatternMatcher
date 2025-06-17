
</body>
</html>


// Using mysqli
$conn = mysqli_connect("localhost", "your_username", "your_password", "your_database");
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

$searchTerm = htmlspecialchars(trim($_GET['search']));

$sql = "SELECT id, name, description FROM fruits WHERE name LIKE '%" . $searchTerm . "%' OR description LIKE '%" . $searchTerm . "%'";

$result = mysqli_query($conn, $sql);

// Process the results
// ... (Display the results as in the HTML example)

mysqli_close($conn);


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      margin: 20px;
    }
    input[type="text"] {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      width: 300px;
      font-size: 16px;
    }
    button {
      padding: 10px 20px;
      background-color: #4CAF50; /* Green */
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <h2>Search:</h2>
    <input type="text" id="searchInput" placeholder="Enter search term">
    <button onclick="search()">Search</button>
  </div>

  <div id="searchResults">
    <!-- Results will be displayed here -->
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value.toLowerCase();
      var resultsDiv = document.getElementById("searchResults");

      // Clear previous results
      resultsDiv.innerHTML = "";

      // **Replace this with your actual data retrieval logic**
      // This is just a placeholder example
      var data = [
        { title: "Apple", description: "A delicious fruit" },
        { title: "Banana", description: "Another tasty fruit" },
        { title: "Orange", description: "A citrus fruit" },
        { title: "Grape", description: "Small and sweet" }
      ];

      for (var i = 0; i < data.length; i++) {
        var item = data[i];
        if (item.title.toLowerCase().includes(searchTerm) || item.description.toLowerCase().includes(searchTerm)) {
          var resultDiv = document.createElement("div");
          resultDiv.innerHTML = "<strong>" + item.title + "</strong>: " + item.description;
          resultsDiv.appendChild(resultDiv);
        }
      }

      if (resultsDiv.innerHTML === "") {
        resultsDiv.innerHTML = "<p>No results found.</p>";
      }
    }
  </script>

</body>
</html>


<?php
// Assuming you've retrieved data from a database
$results = [
    ['title' => 'Apple', 'description' => 'A delicious fruit'],
    ['title' => 'Banana', 'description' => 'A tasty fruit'],
    // ... more results ...
];

// (Inside your JavaScript, where you initialize the data)
var data = <?php echo json_encode($results); ?>;
