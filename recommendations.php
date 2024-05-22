<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];
if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['add_to_cart'])){
   $product_id = $_POST['product_id']; // Ajout de cette ligne
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];
   $product_category = $_POST['product_category'];

   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
   if(mysqli_num_rows($check_cart_numbers) > 0){
      $message[] = 'already added to cart!';
   }else{
      mysqli_query($conn, "INSERT INTO `cart`(product_id, user_id, name, price, quantity, image, category) VALUES('$product_id', '$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image', '$product_category')") or die('query failed');
      $message[] = 'product added to cart!';
   }
}

if(isset($_POST['rate_product'])){
   $product_id = $_POST['product_id'];
   $rating = $_POST['rating'];

   $check_rating = mysqli_query($conn, "SELECT * FROM `ratings` WHERE user_id = '$user_id' AND product_id ='$product_id'") or die('query failed');

   if(mysqli_num_rows($check_rating) > 0){
      mysqli_query($conn, "UPDATE `ratings` SET rating = '$rating' WHERE user_id = '$user_id' AND product_id = '$product_id'") or die('query failed');
      $message[] = 'rating updated!';
   } else {
      mysqli_query($conn, "INSERT INTO `ratings`(user_id, product_id, rating) VALUES('$user_id', '$product_id', '$rating')") or die('query failed');
      $message[] = 'product rated!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shop</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'header.php'; ?>

<div class="heading">
   <h3>All Recommend movies</h3>
</div>

<section class="products">
   <div class="box-container">
      <?php  
         $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
               $product_id = $fetch_products['id'];
               $rating_query = mysqli_query($conn, "SELECT AVG(rating) as avg_rating FROM `ratings` WHERE product_id = '$product_id'") or die('query failed');
               $rating_result = mysqli_fetch_assoc($rating_query);
               $avg_rating = round($rating_result['avg_rating'], 1);
      ?>
<form action="" method="post" class="box">
   <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
   <div class="name"><?php echo $fetch_products['name']; ?></div>
   <div class="price">$<?php echo $fetch_products['price']; ?>/-</div>
   <div class="category"><?php echo $fetch_products['category']; ?></div>
   <input type="number" min="1" name="product_quantity" value="1" class="qty">
   <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>"> <!-- Ajout de cette ligne -->
   <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
   <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
   <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
   <input type="hidden" name="product_category" value="<?php echo $fetch_products['category']; ?>">
   <input type="submit" value="add to cart" name="add_to_cart" class="btn">
</form>
     <form action="" method="post" class="box">
      <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
      <label for="rating">Rate this product:</label>
      <select name="rating" id="rating">
         <option value="1">1</option>
         <option value="2">2</option>
         <option value="3">3</option>
         <option value="4">4</option>
         <option value="5">5</option>
      </select>
      <input type="submit" value="Rate" name="rate_product" class="btn">
     </form>
      <?php
         }
      } else {
         echo '<p class="empty">no products added yet!</p>';
      }
      ?>
   </div>
</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>
</body>
</html>