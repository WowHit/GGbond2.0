<?php
include('dbcon.php');
$con = $GLOBALS['con'];

class UserLogin
{
    private $con;

    public function __construct($con)
    {
        $this->con = $con;
    }

    public function processLogin()
    {
        $user_id = isset($_POST['user_id']) ? addslashes($_POST['user_id']) : '';
        $username = isset($_POST['username']) ? addslashes($_POST['username']) : '';
        $password = isset($_POST['password']) ? addslashes($_POST['password']) : '';

        if ($username == '' || $password == '' || $user_id == '') {
            $response = [
                'status' => 'empty',
                'message' => 'information is empty'
            ];
            $this->sendResponse($response);
        }

        $sql = "SELECT * from user WHERE username = '$username' and password = '$password' and user_id='$user_id'";
        $query = mysqli_query($this->con, $sql);

        if ($row = mysqli_fetch_array($query)) {
            session_start();
            $_SESSION['user_id'] = $row['user_id'];
            header('location: application.php');
        } else {
            $response = [
                'status' => 'failed',
                'message' => 'login failed'
            ];
            $this->sendResponse($response);
        }

        mysqli_close($this->con);
    }

    private function sendResponse($response)
    {
        echo json_encode($response);
        exit;
    }
}

$userLogin = new UserLogin($con);
$userLogin->processLogin();
?>