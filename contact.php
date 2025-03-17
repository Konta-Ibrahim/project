<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['send'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $number = $_POST['number'];
   $msg = mysqli_real_escape_string($conn, $_POST['message']);

   $select_message = mysqli_query($conn, "SELECT * FROM `message` WHERE name = '$name' AND email = '$email' AND number = '$number' AND message = '$msg'") or die('query failed');

   if(mysqli_num_rows($select_message) > 0){
      $message[] = 'message sent already!';
   }else{
      mysqli_query($conn, "INSERT INTO `message`(user_id, name, email, number, message) VALUES('$user_id', '$name', '$email', '$number', '$msg')") or die('query failed');
      $message[] = 'message sent successfully!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>contact</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <style>
/* Styling for the contact section */
.contact-container {
    display: flex;
    flex-direction: row;
    align-items: flex-start;
    justify-content: space-between;
    width: 100%;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.contact-container .heading {
    flex: 1;
    text-align: left;
    padding: 10px 20px;
}

.contact-container .heading h3 {
    font-size: 2.5rem;
    color: #333;
    margin: 0;
    padding: 0;
    text-transform: uppercase;
}

.contact-container .contact-form {
    flex: 1;
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.contact-container .contact-form form {
    display: flex;
    flex-direction: column;
}

.contact-container .contact-form form .box {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 1rem;
}

.contact-container .contact-form form textarea {
    resize: none;
}

.contact-container .contact-form form .btn {
    padding: 10px;
    background-color: #007BFF;
    color: white;
    font-size: 1rem;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.contact-container .contact-form form .btn:hover {
    background-color: #0056b3;
}
</style>

</head>
<body>
   
<?php include 'header.php'; ?>



<section class="contact-container">
    <div class="heading">
        <h3>Contact Us</h3>
        <div class="contact-form">
        <form action="" method="post">
            <h3>Message</h3>
            <input type="text" name="name" required placeholder="Enter your name" class="box">
            <input type="email" name="email" required placeholder="Enter your email" class="box">
            <input type="number" name="number" required placeholder="Enter your number" class="box">
            <textarea name="message" class="box" placeholder="Enter your message" cols="30" rows="10"></textarea>
            <input type="submit" value="Send Message" name="send" class="btn">
        </form>
    </div>
        
    </div>

  
</section>








<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>