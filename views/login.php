<?php
require_once '../config/database.php';
session_start(); 
class Login
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function authenticateUser($email, $password)
    {
        try {
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);

            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                session_regenerate_id(true);  
                $_SESSION['email'] = $user['email'];
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];

                echo $user['role'];
                if ($user['role'] === 'manager') {
                    header("Location: manager_dashboard.php");
                } else {
                    header("Location: member_dashboard.php");
                }
                exit;  
            } else {
                echo "Identifiants incorrects.";
            }
        } catch (PDOException $e) {
            error_log("Erreur de connexion : " . $e->getMessage());
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $login = new Login();
    $login->authenticateUser($email, $password);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../style/Login-Signup.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <div class="wrapper">
    <div class="form-box">
            <div class="login-container" id="login">
                <div class="top">
                    <span>Don't have an account? <a href="signup.php">Sign Up</a></span>
                    <header>Login</header>
                </div>
                 <form action="" method="POST">
                    <div class="input-box">
                        <input type="text" name="email" class="input-field" placeholder="Username or Email" required>
                        <i class="bx bx-user"></i>
                    </div>
                    <div class="input-box">
                        <input type="password" name="password" class="input-field" placeholder="Password" required>
                        <i class="bx bx-lock-alt"></i>
                    </div>
                    <div class="input-box">
                        <input type="submit" class="submit" value="Login">
                    </div>
                </form>
                </div>
           </div>
           </div>
   </body>

</html>