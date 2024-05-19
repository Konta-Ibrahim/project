<?php

// Database connection details (replace with your actual credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "movie_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get username and category from request
$username = $_POST['username'];
$categoryId = $_POST['category'];

// Validate user existence (optional)
$userExists = validateUser($conn, $username);
if (!$userExists) {
  echo json_encode([]); // Empty array if user not found
  exit();
}

// Function to validate user existence (implementation example)
function validateUser($conn, $username) {
  $sql = "SELECT user_id FROM users WHERE username = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();
  return $result->num_rows > 0;
}

// Function to fetch user ratings (replace with actual query)
function fetchUserRatings($conn, $username) {
  $sql = "SELECT r.movie_id, m.title, r.rating, m.category
          FROM ratings r
          INNER JOIN movies m ON r.movie_id = m.movie_id
          INNER JOIN users u ON r.user_id = u.user_id
          WHERE u.username = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();
  $ratings = [];
  while ($row = $result->fetch_assoc()) {
    $ratings[] = $row;
  }
  return $ratings;
}

// Function to find nearest neighbors (replace with actual logic based on similarity scores)
function findNearestNeighbors($conn, $username) {
  // Simulate fetching nearest neighbors from database (replace with actual logic)
  $neighbors = [
    ['user_id' => 2, 'similarity' => 0.8], // Sample neighbors with similarity scores
    ['user_id' => 3, 'similarity' => 0.7],
  ];
  return $neighbors;
}

// Function to fetch movie titles (optional, can be done within the loops above)
function fetchMovieTitles($conn, $movieIds) {
  $placeholders = implode(',', array_fill(0, count($movieIds), '?'));
  $sql = "SELECT movie_id, movie_title FROM movies WHERE movie_id IN ($placeholders)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param(str_repeat("i", count($movieIds)), ...$movieIds); // Bind multiple integers
  $stmt->execute();
  $result = $stmt->get_result();
  $titles = [];
  while ($row = $result->fetch_assoc()) {
    $titles[$row['movie_id']] = $row['movie_title'];
  }
  return $titles;
}

// ... (rest of the code calculating recommendations and returning JSON response)

// Close connection
$conn->close();
