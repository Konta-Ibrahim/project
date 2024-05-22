<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];
if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['add_to_cart'])){
   $product_id = $_POST['product_id'];
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];
   $product_category = $_POST['product_category'];

   // Check if the product_id exists in the products table
   $product_check_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$product_id'") or die('query failed');
   if(mysqli_num_rows($product_check_query) == 0){
      $message[] = 'Product does not exist!';
   } else {
      $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
      if(mysqli_num_rows($check_cart_numbers) > 0){
         $message[] = 'already added to cart!';
      }else{
         mysqli_query($conn, "INSERT INTO `cart`(product_id, user_id, name, price, quantity, image, category) VALUES('$product_id', '$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image', '$product_category')") or die('query failed');
         $message[] = 'product added to cart!';
      }
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

   <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'header.php'; ?>

<section class="home">
   <div class="content">
      <h3>Movies Recommended for you </h3>
      <p>The recommendation made by you and for you according to your preferences</p>
      <a href="about.php" class="white-btn">discover more</a>
   </div>
</section>

<section class="products">
   <h1 class="title">Recent movies add</h1>

   <div class="alphabet-buttons">
      <?php
         foreach(range('A', 'Z') as $char) {
            echo "<button class='alphabet-btn' onclick='filterByLetter(\"$char\")'>$char</button>";
         }
      ?>
   </div>

   <div class="box-container" id="box-container-products">
      <?php  
         $select_products = mysqli_query($conn, "SELECT * FROM `products` LIMIT 6") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
<form action="" method="post" class="box" style="max-width: 15rem; margin: 0;">
   <div style="max-height: 15rem; overflow: hidden;">
      <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="" style="width: 100%; height: auto;">
   </div>
   <div class="name" style="padding: .5rem 0; font-size: 1.5rem;"><?php echo $fetch_products['name']; ?></div>
   <div class="price" style="font-size: 1.8rem; position: absolute; top: 1rem; left: 1rem; border-radius: .5rem; padding: .8rem; color: var(--white); background-color: var(--red);">$<?php echo $fetch_products['price']; ?>/-</div>
   <div class="category" style="font-size: 1.5rem;"><?php echo $fetch_products['category']; ?></div>
   <input type="number" min="1" name="product_quantity" value="1" class="qty" style="width: 100%; padding: .6rem .7rem; border-radius: .5rem; border: var(--border); margin: .5rem 0; font-size: 1.5rem;">
   <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
   <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
   <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
   <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
   <input type="hidden" name="product_category" value="<?php echo $fetch_products['category']; ?>">
   <input type="submit" value="add to cart" name="add_to_cart" class="btn" style="font-size: 1.5rem; padding: .8rem;">
</form>
      <?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
      ?>
   </div>
   <div class="load-more" style="margin-top: 2rem; text-align:center">
      <a href="shop.php" class="option-btn">load more</a>
   </div>
</section>

<section class="movies">
   <h1 class="title">latest movies</h1>
   <div class="box-container" id="box-container-movies">
      <!-- Les films de l'API seront insérés ici -->
   </div>
</section>
<!---
<section class="about">
   <div class="flex">
      <div class="image">
         <img src="images/about-img.jpg" alt="">
      </div>
      <div class="content">
         <h3>about us</h3>
         <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Impedit quos enim minima ipsa dicta officia corporis ratione saepe sed adipisci?</p>
         <a href="about.php" class="btn">read more</a>
      </div>
   </div>
</section>

<section class="home-contact">
   <div class="content">
      <h3>have any questions?</h3>
      <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Atque cumque exercitationem repellendus, amet ullam voluptatibus?</p>
      <a href="contact.php" class="white-btn">contact us</a>
   </div>
</section>

--->

<?php include 'footer.php'; ?>

<script>
const apiKey = '02359bb3023e7e44f2623b72d8b61f60'; // Remplacez par votre clé API TMDb
const baseURL = 'https://api.themoviedb.org/3';
const imageBaseURL = 'https://image.tmdb.org/t/p/w500';

async function fetchMovies() {
    try {
        const response = await fetch(`${baseURL}/movie/popular?api_key=${apiKey}&language=fr-FR&page=1`);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        displayMovies(data.results);
    } catch (error) {
        console.error('Erreur lors de la récupération des films:', error);
    }
}

function displayMovies(movies) {
    const boxContainer = document.getElementById('box-container-movies');

    if (!boxContainer) {
        console.error('Le conteneur box-container-movies est introuvable.');
        return;
    }

    if (!movies || movies.length === 0) {
        console.log('Aucun film à afficher.');
        return;
    }

    movies.forEach(movie => {
        const movieElement = document.createElement('div');
        movieElement.classList.add('box');

        const movieImageContainer = document.createElement('div');
        movieImageContainer.style.maxHeight = '15rem';
        movieImageContainer.style.overflow = 'hidden';

        const movieImage = document.createElement('img');
        movieImage.classList.add('image');
        movieImage.src = movie.poster_path ? `${imageBaseURL}${movie.poster_path}` : 'images/placeholder.png';
        movieImage.alt = movie.title;
        movieImage.style.width = '100%';
        movieImage.style.height = 'auto';

        movieImageContainer.appendChild(movieImage);

        const movieName = document.createElement('div');
        movieName.classList.add('name');
        movieName.style.padding = '.5rem 0';
        movieName.style.fontSize = '1.5rem';
        movieName.textContent = movie.title;

        const movieReleaseDate = document.createElement('div');
        movieReleaseDate.classList.add('release-date');
        movieReleaseDate.style.fontSize = '1.2rem';
        movieReleaseDate.textContent = `Release Date: ${movie.release_date}`;

        const movieRating = document.createElement('div');
        movieRating.classList.add('rating');
        movieRating.style.fontSize = '1.2rem';
        movieRating.textContent = `Rating: ${movie.vote_average}`;

        movieElement.appendChild(movieImageContainer);
        movieElement.appendChild(movieName);
        movieElement.appendChild(movieReleaseDate);
        movieElement.appendChild(movieRating);

        boxContainer.appendChild(movieElement);
    });
}

fetchMovies();
</script>

<script src="js/script.js"></script>
</body>
</html>
