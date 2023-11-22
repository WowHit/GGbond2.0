<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location:index.html');
}

if (isset($_POST['Submit'])) {
    include('dbcon.php');
    $con=$GLOBALS['con'];
    $user_id= $_SESSION['user_id'];
    $cur_pass = mysqli_real_escape_string($con,$_POST['cur_pass']);
    $pass = mysqli_real_escape_string($con,$_POST['pass']);
    $checkPasswordSql = "SELECT * FROM user WHERE user_id = '$user_id' AND password = '$cur_pass'";
    $checkPasswordResult = mysqli_query($con, $checkPasswordSql);
    if (mysqli_num_rows($checkPasswordResult) == 1) {
        // 当前密码正确，执行更新操作
        $updatePasswordSql = "UPDATE user SET password = '$pass' WHERE user_id = '$user_id'";
        if (mysqli_query($con, $updatePasswordSql)) {
            $response = [
                'status' => 'success',
                'message' => 'Password updated successfully'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Error updating password: ' . mysqli_error($con)
            ];
        }
    } else {
        // 当前密码不正确
        $response = [
            'status' => 'error',
            'message' => 'Current password is incorrect'
        ];
    }

    echo json_encode($response);
    exit;
}
?>
