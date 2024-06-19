<?php
include 'config.php';
session_start();

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    $sql = "SELECT * FROM `products` WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
        } else {
            echo "Product not found!";
            exit();
        }
        $stmt->close();
    }
} else {
    echo "No product id provided!";
    exit();
}

function getTrailerUrl($productName) {
    $apiKey = '02359bb3023e7e44f2623b72d8b61f60'; // Remplacez par votre clé API TMDb
    $query = urlencode($productName);
    $apiUrl = "https://api.themoviedb.org/3/search/movie?api_key={$apiKey}&query={$query}";

    $response = file_get_contents($apiUrl);
    $data = json_decode($response, true);

    if (!empty($data['results'])) {
        $movieId = $data['results'][0]['id'];
        $videoApiUrl = "https://api.themoviedb.org/3/movie/{$movieId}/videos?api_key={$apiKey}";

        $videoResponse = file_get_contents($videoApiUrl);
        $videoData = json_decode($videoResponse, true);

        if (!empty($videoData['results'])) {
            foreach ($videoData['results'] as $video) {
                if ($video['type'] === 'Trailer' && $video['site'] === 'YouTube') {
                    return "https://www.youtube.com/embed/{$video['key']}";
                }
            }
        }
    }

    return false;
}

$trailer_url = getTrailerUrl($product['name']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgb(0,0,0); 
            background-color: rgba(0,0,0,0.4); 
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .iframe-container {
            position: relative;
            width: 100%;
            height: 0;
            padding-bottom: 56.25%;
            margin-bottom: 15px;
        }

        .iframe-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="product-details">
    <h1><?php echo htmlspecialchars($product['name']); ?></h1>
    <img src="uploaded_img/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
    <div class="category">Category: <?php echo htmlspecialchars($product['category']); ?></div>
    <div class="price">$<?php echo htmlspecialchars($product['price']); ?>/-</div>
    <div class="description"><?php echo htmlspecialchars($product['description']); ?></div>
    <div class="rating">Rating: <?php echo round($product['rating'], 2); ?> stars</div>
    <button id="openModalBtn" class="option-btn">Show Rating & Trailer</button>
    <a href="shop.php" class="option-btn">Go-Home</a>
</div>

<!-- Modal -->
<div id="myModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <div class="rating">Rating: <?php echo round($product['rating'], 2); ?> stars</div>
    <?php if ($trailer_url): ?>
        <div class="iframe-container">
            <iframe src="<?php echo htmlspecialchars($trailer_url); ?>" frameborder="0" allowfullscreen></iframe>
        </div>
    <?php endif; ?>
  </div>
</div>

<?php include 'footer.php'; ?>
<script src="js/script.js"></script>
<script>
    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the button that opens the modal
    var btn = document.getElementById("openModalBtn");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal 
    btn.onclick = function() {
      modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
      modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }
</script>
</body>
</html>
