<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header('location:login.php');
    exit();
}

function getSimilarProducts($product_id, $category, $conn) {
    $similar_products = array();

    $sql = "
        SELECT p.* 
        FROM products p
        LEFT JOIN ratings r ON p.id = r.product_id
        WHERE p.category = ? 
          AND p.id != ?
          AND (r.rating IS NULL OR r.rating >= 3)
        GROUP BY p.id";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("si", $category, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $similar_products[] = $row;
        }
        $stmt->close();
    }

    return $similar_products;
}

function updateMissingData($conn) {
    $sql = "SELECT * FROM `cart` WHERE `category` IS NULL OR `rating` IS NULL";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $cartId = $row['id'];
            $productId = $row['product_id'];
            $category = $row['category'];
            $averageRating = $row['rating'];

            if (is_null($category)) {
                $productSql = "SELECT `category` FROM `products` WHERE `id` = ?";
                $stmtProduct = $conn->prepare($productSql);
                $stmtProduct->bind_param("i", $productId);
                $stmtProduct->execute();
                $stmtProduct->bind_result($category);
                $stmtProduct->fetch();
                $stmtProduct->close();
            }

            if (is_null($averageRating)) {
                $ratingSql = "SELECT AVG(`rating`) as averageRating FROM `ratings` WHERE `product_id` = ?";
                $stmtRating = $conn->prepare($ratingSql);
                $stmtRating->bind_param("i", $productId);
                $stmtRating->execute();
                $stmtRating->bind_result($averageRating);
                $stmtRating->fetch();
                $stmtRating->close();

                if (is_null($averageRating)) {
                    $averageRating = 0;
                }
            }

            $updateSql = "UPDATE `cart` SET `category` = ?, `rating` = ? WHERE `id` = ?";
            $stmt = $conn->prepare($updateSql);
            $stmt->bind_param("sdi", $category, $averageRating, $cartId);
            $stmt->execute();
            $stmt->close();
        }
    }
}

updateMissingData($conn);

if (isset($_POST['update_cart'])) {
    $cart_id = $_POST['cart_id'];
    $cart_quantity = $_POST['cart_quantity'];
    
    if ($stmt = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?")) {
        $stmt->bind_param("ii", $cart_quantity, $cart_id);
        $stmt->execute();
        $stmt->close();
        $message[] = 'Cart quantity updated!';
    } else {
        $message[] = 'Failed to update cart quantity!';
    }
}

if (isset($_POST['submit_rating'])) {
    $product_id = $_POST['product_id'];
    $rating = $_POST['rating'];
    
    if ($stmt = $conn->prepare("SELECT * FROM `ratings` WHERE user_id = ? AND product_id = ?")) {
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            if ($update_stmt = $conn->prepare("UPDATE `ratings` SET rating = ? WHERE user_id = ? AND product_id = ?")) {
                $update_stmt->bind_param("iii", $rating, $user_id, $product_id);
                $update_stmt->execute();
                $update_stmt->close();
                $message[] = 'Product rating updated!';
            }
        } else {
            if ($insert_stmt = $conn->prepare("INSERT INTO `ratings` (user_id, product_id, rating) VALUES (?, ?, ?)")) {
                $insert_stmt->bind_param("iii", $user_id, $product_id, $rating);
                $insert_stmt->execute();
                $insert_stmt->close();
                $message[] = 'Product rating submitted!';
            }
        }
        $stmt->close();
    } else {
        $message[] = 'Failed to submit rating!';
    }
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    if ($stmt = $conn->prepare("DELETE FROM `cart` WHERE id = ?")) {
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        $stmt->close();
        header('location:cart.php');
        exit();
    } else {
        $message[] = 'Failed to delete item from cart!';
    }
}

if (isset($_GET['delete_all'])) {
    if ($stmt = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?")) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
        header('location:cart.php');
        exit();
    } else {
        $message[] = 'Failed to delete all items from cart!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .message {
            padding: 10px;
            background-color: #f2f2f2;
            margin-bottom: 15px;
            border: 1px solid #ddd;
        }
        .message span {
            margin-right: 10px;
        }
        .message .fas {
            cursor: pointer;
        }
        .box-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .box {
            border: 1px solid #ddd;
            padding: 15px;
            width: 300px;
            position: relative;
        }
        .box img {
            max-width: 100%;
            height: auto;
        }
        .box .name, .box .category, .box .price, .box .rating, .box .sub-total, .box .average-rating {
            margin-bottom: 10px;
        }
        .box form {
            margin-bottom: 10px;
       
        }
        .option-btn {
            background-color: #ff6b6b;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
        .option-btn:hover {
            background-color: #ff4b4b;
        }
        .delete-btn {
            background-color: #ff4444;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            margin-top: 10px;
        }
        .delete-btn:hover {
            background-color: #ff0000;
        }
        .delete-btn.disabled {
            background-color: #ddd;
            pointer-events: none;
        }
        .btn {
            background-color: #4caf50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            margin-top: 10px;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .btn.disabled {
            background-color: #ddd;
            pointer-events: none;
        }
        .similar-products-container {
            margin-top: 20px;
        }
        .similar-products-container h2 {
            margin-bottom: 10px;
        }
        .similar-products {
            display: flex;
            gap: 10px;
            overflow-x: auto;
        }
        .similar-product {
            border: 1px solid #ddd;
            padding: 10px;
            width: 200px;
        }
        .similar-product img {
            max-width: 100%;
            height: auto;
        }
        .similar-product .name, .similar-product .price {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<?php
if (isset($message) && is_array($message)) {
    foreach ($message as $msg) {
        echo '<div class="message"><span>' . $msg . '</span> <i class="fas fa-times" onclick="this.parentElement.style.display=\'none\';"></i></div>';
    }
}
?>

<div class="heading">
    <h3>Movies & Recommendations</h3>
</div>

<section class="shopping-cart">
    <h1 class="title">Movies Added</h1>
    <div class="box-container">
        <?php
        $grand_total = 0;
        if ($stmt = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?")) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($fetch_cart = $result->fetch_assoc()) {
                    $sub_total = $fetch_cart['quantity'] * $fetch_cart['price'];
        ?>



<div class="box">
    <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('Delete this from cart?');"></a>
    <img src="uploaded_img/<?php echo $fetch_cart['image']; ?>" alt="">
    <div class="name"><?php echo $fetch_cart['name']; ?></div>
    <div class="category">Category: <?php echo $fetch_cart['category']; ?></div>
    <div class="price" style="display: none;">$<?php echo $fetch_cart['price']; ?>/-</div>
    <div class="rating">Rating: <?php echo round($fetch_cart['rating'], 2); ?> stars</div>
    <form action="" method="post">
        <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
        <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>">
        <input type="submit" name="update_cart" value="Update" class="option-btn">
    </form>

    <div class="sub-total" style="display: none;"> Sub Total: <span>$<?php echo $sub_total; ?>/-</span> </div>

    <form action="" method="post">
        <input type="hidden" name="product_id" value="<?php echo $fetch_cart['product_id']; ?>">
        <label for="rating">Rate this product:</label>
        <select name="rating" id="rating" required>
            <option value="">Select</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>
        <input type="submit" name="submit_rating" value="Rate" class="option-btn">
    </form>
    <div class="average-rating">
        <?php
        if ($avg_stmt = $conn->prepare("SELECT AVG(rating) as avg_rating FROM `ratings` WHERE product_id = ?")) {
            $avg_stmt->bind_param("i", $fetch_cart['product_id']);
            $avg_stmt->execute();
            $avg_result = $avg_stmt->get_result();
            $avg_rating = $avg_result->fetch_assoc()['avg_rating'];
            echo $avg_rating ? 'Average Rating: ' . round($avg_rating, 2) : 'No ratings yet';
            $avg_stmt->close();
        }
        ?>
    </div>
</div>


        <?php
                        $grand_total += $sub_total;
                    }
                } else {
                    echo '<p class="empty">Not yet recommendations</p>';
                }
                $stmt->close();
            } else {
                echo '<p class="empty">Failed to fetch recommendations items</p>';
            }
        ?>
    </div>
    <div style="margin-top: 2rem; text-align:center;">
        <a href="cart.php?delete_all" class="delete-btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>" onclick="return confirm('Delete all from cart?');">Delete All</a>
    </div>
    <div class="cart-total">
        <!-- Section for Similar Products -->
<section class="similar-products-section">
    <h2>Similar movies</h2>
    <div class="box-container similar-products-container">
        <?php 
        $result = $conn->query("SELECT * FROM `cart` WHERE user_id = '$user_id'");
        if ($result->num_rows > 0) {
            while ($fetch_cart = $result->fetch_assoc()) {
                $similar_products = getSimilarProducts($fetch_cart['id'], $fetch_cart['category'], $conn);
                foreach ($similar_products as $product) {
                    ?>
                    <div class="box similar-product">
                        <img src="uploaded_img/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                        <div class="name"><?php echo $product['name']; ?></div>
                        <div class="price">$<?php echo $product['price']; ?> /-</div>
                    </div>
                <?php 
                }
            }
        } else {
            echo '<p class="empty">No similar movies found.</p>';
        } 
        ?>
    </div>
</section> 
        <p style="display: none;">Grand Total: <span>$<?php echo $grand_total; ?>/-</span></p>

        <div class="flex">
            <a href="shop.php" class="option-btn">Go-Home</a>
          
            <a href="checkout.php"  style="display: none;" class="btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>">Proceed to Checkout</a>
   
        </div>
    </div>
</section>

<!-- Section for Similar Products 
<section class="similar-products-section">
    <h2>Similar movies</h2>
    <div class="box-container similar-products-container">
        <?php 
        $result = $conn->query("SELECT * FROM `cart` WHERE user_id = '$user_id'");
        if ($result->num_rows > 0) {
            while ($fetch_cart = $result->fetch_assoc()) {
                $similar_products = getSimilarProducts($fetch_cart['id'], $fetch_cart['category'], $conn);
                foreach ($similar_products as $product) {
                    ?>
                    <div class="box similar-product">
                        <img src="uploaded_img/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                        <div class="name"><?php echo $product['name']; ?></div>
                        <div class="price">$<?php echo $product['price']; ?> /-</div>
                    </div>
                <?php 
                }
            }
        } else {
            echo '<p class="empty">No similar movies found.</p>';
        } 
        ?>
    </div>
    <a href="shop.php" class="option-btn">Go-Home</a>

</section> 
-->
<?php include 'footer.php'; ?>
<!-- End of Similar Products Section -->
                    <script>
                        document.querySelectorAll('.similar-products').forEach(container => {
                            container.addEventListener('wheel', (e) => {
                                if (e.deltaY !== 0) {
                                    e.preventDefault();
                                    container.scrollBy({
                                        left: e.deltaY < 0 ? -300 : 300,
                                        behavior: 'smooth'
                                    });
                                }
                            });
                        });
                    </script>
<script src="js/script.js"></script>
</body>
</html>