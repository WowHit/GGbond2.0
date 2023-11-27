<?php
include('dbcon.php');
$con = $GLOBALS['con'];
session_start();

class PasswordUpdate
{
    private $con;

    public function __construct($con)
    {
        $this->con = $con;
    }

    public function updatePassword()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.html');
        }

        if (isset($_POST['Submit'])) {
            $user_id = $_SESSION['user_id'];
            $cur_pass = mysqli_real_escape_string($this->con, $_POST['cur_pass']);
            $pass = mysqli_real_escape_string($this->con, $_POST['pass']);

            $checkPasswordSql = "SELECT * FROM user WHERE user_id = '$user_id' AND password = '$cur_pass'";
            $checkPasswordResult = mysqli_query($this->con, $checkPasswordSql);

            if (mysqli_num_rows($checkPasswordResult) == 1) {
                $updatePasswordSql = "UPDATE user SET password = '$pass' WHERE user_id = '$user_id'";

                if (mysqli_query($this->con, $updatePasswordSql)) {
                    $response = [
                        'status' => 'success',
                        'message' => 'Password updated successfully'
                    ];
                } else {
                    $response = [
                        'status' => 'error',
                        'message' => 'Error updating password: ' . mysqli_error($this->con)
                    ];
                }
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Current password is incorrect'
                ];
            }

            echo json_encode($response);
            exit;
        }
    }
}

$passwordUpdate = new PasswordUpdate($con);
$passwordUpdate->updatePassword();
?>