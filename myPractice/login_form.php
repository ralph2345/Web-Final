<?php
@include 'config.php';

session_start();

if(isset($_POST['submit'])){

   // Sanitize email input
   if(isset($_POST['email'])){
      $email = mysqli_real_escape_string($conn, $_POST['email']);
   } else {
      die("Email is required.");
   }

   // Sanitize and hash password input
   if(isset($_POST['password'])){
      $pass = md5(mysqli_real_escape_string($conn, $_POST['password']));
   } else {
      die("Password is required.");
   }

   // Query to check user credentials
   $select = "SELECT * FROM user_form WHERE email = '$email' AND password = '$pass'";

   $result = mysqli_query($conn, $select);

   if(mysqli_num_rows($result) > 0){
      $row = mysqli_fetch_array($result);

      if($row['user_type'] == 'admin'){
         $_SESSION['admin_name'] = $row['name'];
         header('Location: admin/admin_dashboard.php'); // Adjusted path
         exit(); // Ensure script stops after redirect

      } elseif($row['user_type'] == 'user'){
         $_SESSION['user_name'] = $row['name'];
         header('Location: user/user_homepage.php'); // Adjusted path
         exit(); // Ensure script stops after redirect
      }
   } else {
      $error[] = 'Incorrect email or password!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login Form</title>
   <link rel="stylesheet" href="style.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.7.2/font/bootstrap-icons.min.css">
   <style>
      .password-container {
         position: relative;
         width: 100%;
      }
      .password-container i {
         position: absolute;
         right: 25px;
         top: 50%;
         transform: translateY(-50%);
         cursor: pointer;
      }
   </style>
</head>
<body>
   
<div class="form-container">
   <form action="" method="post">
      <h3>Login Now</h3>
      <?php
      if(isset($error)){
         foreach($error as $error){
            echo '<span class="error-msg">'.$error.'</span>';
         }
      }
      ?>
      <input type="email" name="email" required placeholder="Enter your email">
      <div class="password-container">
         <input type="password" name="password" id="password" required placeholder="Enter your password">
         <i class="bi bi-eye" id="togglePassword"></i>
      </div>
      <input type="submit" name="submit" value="Login Now" class="form-btn">
      <p>Don't have an account? <a href="register_form.php">Register now</a></p>
   </form>
</div>

<script>
   document.getElementById('togglePassword').addEventListener('click', function (e) {
      const password = document.getElementById('password');
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      this.classList.toggle('bi-eye');
      this.classList.toggle('bi-eye-slash');
   });
</script>

</body>
</html>
