<?php
include('dbcon.php');
$con=$GLOBALS['con'];
session_start();
$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM application WHERE user_id = '$user_id' ORDER BY app_id DESC";
$result = mysqli_query($con, $sql);

if (mysqli_num_rows($result) > 0) {
    // 创建一个数组来存储用户的记录
    $user_records = array();

    while ($row = mysqli_fetch_assoc($result)) {
        // 将每行记录添加到数组中
        $user_records[] = $row;
    }

    // 返回用户的所有记录
    $response = [
        'status' => 'success',
        'data' => $user_records
    ];
} else {
    $response = [
        'status' => 'error',
        'message' => 'No records found for this user'
    ];
}
//释放结果集
if($result) mysqli_free_result($result);
echo json_encode($response);
exit;
