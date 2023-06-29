<?php include('functions.php') ?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Fae Fae | Login</title>
   <link rel="icon" href="logo.png?v=<?php echo time(); ?>" type="image/x-icon">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">

</head>
<body>
   
<div class="form-container">

   <form action="" method="post">
      <img src="logo.png" alt="faefae-logo">
      <h4>Fae-Fae Lechon Manok, Crispy Liempo &amp; Sisig</h4>
      <?php
      if(isset($error)){
         foreach($error as $error){
            echo '<span class="error-msg">'.$error.'</span>';
         }
      }
      ?>
      <input type="email" name="email" required placeholder="enter your email">
      <input type="password" name="password" required placeholder="enter your password">
      <input type="submit" name="login" value="Submit Information" class="form-btn">
   </form>

</div>
</body>
</html>