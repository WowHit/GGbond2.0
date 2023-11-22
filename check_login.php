<?php
include('dbcon.php');
$con=$GLOBALS['con'];
$user_id = isset($_POST['user_id'])?addslashes($_POST['user_id']):'';
$username = isset($_POST['username'])?addslashes($_POST['username']):'';
$password = isset($_POST['password'])?addslashes($_POST['password']):'';
$sql = "select * from user where username = '$username' and password = '$password' and user_id='$user_id'";
$query = mysqli_query($con,$sql);
if ($username == ''||$password == ''||$user_id == '') {
    $response = [
        'status' => 'empty',
        'message' => 'information is empty'
    ];
    exit;
}

if($row = mysqli_fetch_array($query))
{
    session_start();
    $_SESSION['user_id'] = $row['user_id'];
    header('location:application.php');
}
else {
    $response = [
        'status' => 'failed',
        'message' => 'login failed'
    ];
    echo json_encode($response);
}
mysqli_close($con);
?>