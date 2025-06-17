  <button type="submit">Search</button>
</form>


<!DOCTYPE html>
<html>
<head>
<title>Simple Search Bar</title>
<style>
  .search-container {
    margin-bottom: 10px;
  }
  input[type="text"] {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    width: 300px;
    font-size: 16px;
  }
  button {
    padding: 8px 12px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
  }
  button:hover {
    background-color: #3e8e41;
  }
</style>
</head>
<body>

<div class="search-container">
  <input type="text" id="searchInput" placeholder="Search...">
  <button onclick="search()">Search</button>
</div>

<div id="searchResults">
  <!-- Search results will be displayed here -->
</div>


<script>
  function search() {
    var searchTerm = document.getElementById("searchInput").value.toLowerCase();
    var searchResultsDiv = document.getElementById("searchResults");
    searchResultsDiv.innerHTML = ""; // Clear previous results

    // **Replace this with your actual data source and search logic**
    // This is just a placeholder for demonstration
    var data = [
      "Apple",
      "Banana",
      "Orange",
      "Grape",
      "Strawberry",
      "Pineapple",
      "Mango",
      "Kiwi"
    ];

    for (var i = 0; i < data.length; i++) {
      if (data[i].toLowerCase().indexOf(searchTerm) > -1) {
        var resultItem = document.createElement("p");
        resultItem.textContent = data[i];
        resultItem.style.marginBottom = "5px"; // Add some spacing
        resultItem.style.border = "1px solid #ddd";
        resultItem.style.padding = "5px";
        resultItem.style.backgroundColor = "#f9f9f9";
        searchResultsDiv.appendChild(resultItem);
      }
    }

    if (searchResultsDiv.innerHTML === "") {
      searchResultsDiv.innerHTML = "<p>No results found.</p>";
    }
  }
</script>

</body>
</html>


<?php

// Assuming you have a form to input the search term
?>
