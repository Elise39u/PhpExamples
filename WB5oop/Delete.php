<?php
// make sure our code knows about the Student class
require 'user.php';
require 'StudentCollection.php';

// make database connection
$dblink = mysqli_connect('localhost', 'root', '', 'student');
// Check if connection exists
if (!$dblink) {
    die("Connection error: " . mysqli_connect_error());
}

// Check of de id bestaat in de database
var_dump($_POST);
$id = (int) $_POST['id'];
if( $id > 0 ) {
    $sql = "DELETE FROM student WHERE id =" .$id;
    var_dump($id);
    var_dump($sql);
    var_dump($dblink);
    $dblink->query($sql);
    echo "Student Deleted";
}
else {
    echo "Nothing Has been found";
}
?>