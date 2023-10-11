<?php
// Start the session
session_start();


// When the user is logged in, go to the user page
if (isset($_SESSION['logged-in']) && $_SESSION['logged-in'] == TRUE) {
    die(header('Location: transaction'));
}


// Place username of your bot here
define('BOT_USERNAME', 'DmMeCash_Bot');
?>
<!doctype html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>DMME</title>
      <link rel="icon" href="images/favicons/favicon.ico">
      <link rel="apple-touch-icon" href="images/favicons/apple-touch-icon.png">
      <link rel="apple-touch-icon" sizes="72x72" href="images/favicons/apple-touch-icon-72x72.png">
      <link rel="apple-touch-icon" sizes="114x114" href="images/favicons/apple-touch-icon-114x114.png">
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
      <link href="css/custom.css" rel="stylesheet">
   </head>
   <body class="login d-flex align-items-center py-4">
      <main class="w-100 m-auto">
      <div class="container">
         <div class="row align-items-center justify-content-center">
            <div class="col-lg-3 text-center text-lg-start mb-3 mb-lg-0">
               <img src="https://ik.imagekit.io/n2uyibw2q/mobile.gif" class="mobile img-fluid">
            </div>
            <div class="col-lg-auto text-center text-lg-start">
               <div class="mb-3 text-center">
                  <img src="images/logo-black.png" width="64">
               </div>
               <script async src="https://telegram.org/js/telegram-widget.js" data-telegram-login="<?= BOT_USERNAME ?>" data-size="large" data-auth-url="auth.php"></script>
            </div>
         </div>
      </div>
   </main>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
   </body>
</html>