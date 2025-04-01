
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CarRental App</title>
    
    <link rel="stylesheet" href="/public/css/styles.css">
</head>
<body class="login-body">
    

    
  
   <div class="login-body-div">
        
          <main class="login-main">
       
               <div class="login-form-container">
       
                   <h3>Bienvenido a Car Rental Manager</h3>
       
                   <form class="login-form" method="POST" action="/login">

                   
       
                           <div class="login-form-data">
                               <label for="user">Usuario:</label>
                               <input type="text"  name="username" id="user"  value="<?php echo isset($_SESSION['try_username']) ? htmlspecialchars($_SESSION['try_username']) : ''; ?>">
                           </div>
                           <div class="login-form-data">
                               <label for="password">Contraseña:</label>
                               <input type="password" name="password" id="password">
                           </div>
                           <input class="boton boton-main" type="submit" value="Acceder">
                           <?php
                           //mensaje de error login
                           if (isset($_SESSION['error_message'])) {
                               echo '<p style="color:red;">' . htmlspecialchars($_SESSION['error_message']) . '</p>';
                               unset($_SESSION['error_message']); 
           }
                           ?>
                           
       
       
                   </form>
       
       
               </div>
          </main>
   </div>

   
   <?php 

include 'includes/footer.php';

?>