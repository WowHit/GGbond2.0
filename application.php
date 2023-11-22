<?php
include('dbcon.php');
$con=$GLOBALS['con'];
session_start();
$checkData = $_POST['leaveDate']>date('Y-m-d')
&& (date('N', strtotime($_POST['leaveDate'])) == 3);

$isLoggedIn = false;
if (isset($_SESSION['user_id'])) $isLoggedIn = true;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if($checkData) {
        $leaveDate = $_POST['leaveDate'];
    }
    else {
        $response = [
            'status' => 'date error',
            'message' => 'go back'
        ];
    }
    $reason = isset($_POST['reason'])?addslashes($_POST['reason']):'';;
    $user_id = isset($_SESSION['user_id'])?addslashes($_SESSION['user_id']):'';
    $sql1 = "INSERT INTO application (time, reason,user_id) VALUES ('$leaveDate','$reason','$user_id')";
    $sql2 = "UPDATE application SET time='$leaveDate', reason='$reason' WHERE user_id='$user_id' and time='$leaveDate'";
    $sql3 = "SELECT * from application WHERE user_id='$user_id' and time='$leaveDate'";
    $query=mysqli_query($con,$sql3) or die('SQL语句执行失败'.mysqli_error($con));
    if (mysqli_fetch_array($query) && mysqli_query($con, $sql2)) {
        $response = [
            'status' => 'success',
            'message' => 'Data updated successfully'
        ];
    } else if (!mysqli_fetch_array($query) && mysqli_query($con, $sql1)) {
        $response = [
            'status' => 'success',
            'message' => 'Data inserted successfully'
        ];
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Error inserting data: ' . mysqli_error($con)
        ];
    }
    echo json_encode($response);
    exit;
}
if ($isLoggedIn) header('Location:application.html');
else header('Location:index.html');
?>