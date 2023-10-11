<?php

// Start the session
session_start();

// When the user is logged in, go to the user page
if (isset($_SESSION['logged-in']) && $_SESSION['logged-in'] == TRUE) {
    die(header('Location: transaction.php'));
}

// Place bot token of your bot here
define('BOT_TOKEN', '6077207736:AAFuyLydCLST8Kt8qM5gZg_My8s5kRA7SLY');

// The Telegram hash is required to authorize
if (!isset($_GET['hash'])) {
    die('Telegram hash not found');
}

// Official Telegram authorization - function
function checkTelegramAuthorization($auth_data)
{
    $check_hash = $auth_data['hash'];
    unset($auth_data['hash']);
    $data_check_arr = [];
    foreach ($auth_data as $key => $value) {
        $data_check_arr[] = $key . '=' . $value;
    }
    sort($data_check_arr);
    $data_check_string = implode("\n", $data_check_arr);
    $secret_key = hash('sha256', BOT_TOKEN, true);
    $hash = hash_hmac('sha256', $data_check_string, $secret_key);
    if (strcmp($hash, $check_hash) !== 0) {
        throw new Exception('Data is NOT from Telegram');
    }
    if ((time() - $auth_data['auth_date']) > 86400) {
        throw new Exception('Data is outdated');
    }
    return $auth_data;
}

// User authentication - function
function userAuthentication($db, $auth_data)
{
    
    // Create logged in user session
    $_SESSION = [
        'logged-in' => TRUE,
        'telegram_id' => $auth_data['id']
    ];

    print_r($auth_data);die();
    
}


// Start the process
try {
    // Get the authorized user data from Telegram widget
    $auth_data = checkTelegramAuthorization($_GET);
    print_r($auth_data);die();
    // Authenticate the user
    userAuthentication($db, $auth_data);
} catch (Exception $e) {
    // Display errors
    die($e->getMessage());
}

// Go to the user page
die(header('Location: transaction'));
