
<!DOCTYPE html>
<html>
<head>
  <title>Search Bar</title>
  <style>
    /* Basic styling for the search bar */
    input[type="text"] {
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      width: 300px; /* Adjust width as needed */
      box-sizing: border-box; /* Ensures padding doesn't add to width */
    }

    button {
      padding: 8px 15px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    button:hover {
      background-color: #3e8e41;
    }
  </style>
</head>
<body>

  <h1>Search</h1>

  <form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
