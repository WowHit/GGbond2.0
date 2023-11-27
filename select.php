<?php
include('dbcon.php');
$con = $GLOBALS['con'];
session_start();
$user_id = $_SESSION['user_id'];

class UserRecords
{
    private $con;
    private $user_id;

    public function __construct($con, $user_id)
    {
        $this->con = $con;
        $this->user_id = $user_id;
    }

    public function fetchRecords()
    {
        $sql = "SELECT * FROM application WHERE user_id = '$this->user_id' ORDER BY app_id DESC";
        $result = mysqli_query($this->con, $sql);

        if (mysqli_num_rows($result) > 0) {
            $userRecords = [];

            while ($row = mysqli_fetch_assoc($result)) {
                $userRecords[] = $row;
            }

            $response = [
                'status' => 'success',
                'data' => $userRecords
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'No records found for this user'
            ];
        }

        if ($result) {
            mysqli_free_result($result);
        }

        echo json_encode($response);
        exit;
    }
}

$userRecords = new UserRecords($con, $user_id);
$userRecords->fetchRecords();
?>