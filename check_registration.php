<?php

$page_title = "Check registration";

include 'mysqli_connect.php';

include 'includes/header.html';

include 'includes/navbar.html';

if (isset ($_SESSION['username'])){
	header('location: index.php');
}
else{
	
    $username = $_POST['username'];
    $password = $_POST['pass'];

    $sql1 = $connection->prepare('SELECT users_username, users_password FROM users WHERE users_username = ? AND users_password = ?');
    $sql1->bind_param('ss', $username, $password);

    $sql2 = $connection->prepare('INSERT INTO users (users_username, users_password) VALUES (?, ?)');
    $sql2->bind_param('ss', $username, $password);

    $sql1->execute();
    $result1 = $sql1->get_result();

    if (mysqli_num_rows ($result1) == 0){
        $sql2->execute();
        if ($sql2->affected_rows == 1){
            include 'includes/new_registration.php';
        }
        else{
            include 'includes/error.php';   
        }
    }
    else{
        include 'includes/notregistered.php';
    }
}

mysqli_close ($connection);

include 'includes/footer.html';

?>