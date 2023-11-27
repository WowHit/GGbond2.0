<?php
include('dbcon.php');
$con = $GLOBALS['con'];
session_start();

class LeaveApplication
{
    private $con;

    public function __construct($con)
    {
        $this->con = $con;
    }

    public function processApplication()
    {
        $isLoggedIn = isset($_SESSION['user_id']);

        if ($isLoggedIn) {
            header('Location: application.html');
        } else {
            header('Location: index.html');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->isValidDate($_POST['leaveDate'])) {
                $leaveDate = $_POST['leaveDate'];
            } else {
                $response = [
                    'status' => 'date error',
                    'message' => 'go back'
                ];
                $this->sendResponse($response);
            }

            $reason = isset($_POST['reason']) ? addslashes($_POST['reason']) : '';
            $user_id = isset($_SESSION['user_id']) ? addslashes($_SESSION['user_id']) : '';

            if ($this->isApplicationExist($user_id, $leaveDate)) {
                $this->updateApplication($user_id, $leaveDate, $reason);
                $response = [
                    'status' => 'success',
                    'message' => 'Data updated successfully'
                ];
            } else {
                $this->insertApplication($user_id, $leaveDate, $reason);
                $response = [
                    'status' => 'success',
                    'message' => 'Data inserted successfully'
                ];
            }

            $this->sendResponse($response);
        }
    }

    private function isValidDate($date)
    {
        return ($date > date('Y-m-d')) && (date('N', strtotime($date)) == 3);
    }

    private function isApplicationExist($user_id, $leaveDate)
    {
        $sql = "SELECT * from application WHERE user_id='$user_id' and time='$leaveDate'";
        $query = mysqli_query($this->con, $sql) or die('SQL语句执行失败' . mysqli_error($this->con));
        return mysqli_fetch_array($query);
    }

    private function insertApplication($user_id, $leaveDate, $reason)
    {
        $sql = "INSERT INTO application (time, reason, user_id) VALUES ('$leaveDate','$reason','$user_id')";
        mysqli_query($this->con, $sql);
    }

    private function updateApplication($user_id, $leaveDate, $reason)
    {
        $sql = "UPDATE application SET reason='$reason' WHERE user_id='$user_id' and time='$leaveDate'";
        mysqli_query($this->con, $sql);
    }

    private function sendResponse($response)
    {
        echo json_encode($response);
        exit;
    }
}

$leaveApp = new LeaveApplication($con);
$leaveApp->processApplication();
?>