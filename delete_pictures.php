<?php

$page_title = "Delete pictures";

include 'mysqli_connect.php';

include 'includes/header.html';

include 'includes/navbar.html';

if (!isset ($_SESSION ['username'])){
	header('location: index.php');
}
else{
    if (isset($_POST['pictures_name'])) {

        $pictures_name = basename($_POST['pictures_name']);

        // On vérifie que l'utilisateur possède bien cette image
        $sql_check = $connection->prepare("SELECT pictures_name FROM pictures INNER JOIN users ON pictures.id_users = users.users_id WHERE pictures_name = ? AND users.users_username = ?");
        $sql_check->bind_param("ss", $pictures_name, $_SESSION['username']);
        $sql_check->execute();
        $result_check = $sql_check->get_result();

        if ($result_check->num_rows > 0) {
            // L'utilisateur possède l'image, on peut supprimer
            $sql2 = $connection->prepare("DELETE FROM pictures WHERE pictures_name = ?");
            $sql2->bind_param("s", $pictures_name);

            if ($sql2->execute()) {
                $path = "uploads/" . $pictures_name;

                if (unlink($path)) {
                    echo "Removed picture " . $path . "<br>";
                    echo "Removed picture " . $pictures_name . ", continue with <a href=''>deleting pictures</a>";
                }
            }
            $sql2->close();
        }
        $sql_check->close();
    }
    
    $sql1 = "SELECT users.users_username, pictures.pictures_name FROM pictures INNER JOIN users ON pictures.id_users = users.users_id";
    $result = mysqli_query ($connection, $sql1) or die (mysqli_error ($connection));
    
    echo "<form action='' method='POST'>";
    echo "<select name='pictures_name'>";
    if (mysqli_num_rows ($result) > 0){
        while ($row = mysqli_fetch_assoc ($result)){
            if($row['users_username'] == $_SESSION['username']){
                echo "<option value='" . $row['pictures_name'] . "'>" . $row['pictures_name'] . "</option>";
            }
        }
    }
    else {
        echo "Error 2";
    }
    echo "</select>";
    echo "<input type='submit' value='Delete picture'>";
    echo "</form>";

    include 'includes/footer.html';

    mysqli_close ($connection);
    unset($connection);
}

?>

