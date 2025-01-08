<?php
require_once '../config/database.php';

class Signup
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function registerUser($name, $email, $password, $confirm_password)
    {
       
        if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
            echo "remplir tout les champs";
            return;
        }

        if ($password !== $confirm_password) {
            echo "les deux mots de pass ne sont pas correspendants";
            return;
        }

        try {

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $this->conn->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->execute();
        } 
        
        catch(PDOException $e) {
            
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $signup = new Signup();

    $signup->registerUser(
        $_POST['username'],
        $_POST['email'],
        $_POST['password'],
        $_POST['confirm_password']
    );



}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../style/Login-Signup.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <div class="wrapper">
        <div class="form-box">
            <div class="register-container" id="register">
                <div class="top">
                    <span>Have an account? <a href="login.php">Login</a></span>
                    <header>Sign Up</header>
                </div>
                <form action="" method="POST">
                    <div class="input-box">
                        <input type="text" name="username" class="input-field" placeholder="Username" required>
                        <i class="bx bx-user"></i>
                    </div>
                    <div class="input-box">
                        <input type="text" name="email" class="input-field" placeholder="Email" required>
                        <i class="bx bx-envelope"></i>
                    </div>
                    <div class="input-box">
                        <input type="password" name="password" class="input-field" placeholder="Password" required>
                        <i class="bx bx-lock-alt"></i>
                    </div>
                    <div class="input-box">
                        <input type="password" name="confirm_password" class="input-field"
                            placeholder="Confirm Password" required>
                        <i class="bx bx-lock-alt"></i>
                    </div>
                    <div class="input-box">
                        <input type="submit" class="submit" value="Register">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>