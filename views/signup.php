<?php
session_start();

$host = 'localhost';
$dbname = 'securite';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error_message = "Les mots de passe ne correspondent pas.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $error_message = "Cet email est deja enregistrer.";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (email, password, role) VALUES (:email, :password, :role)");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':role', $role);

            $role = 'user';

            if ($stmt->execute()) {
                $user_id = $conn->lastInsertId();

                $stmt = $conn->prepare("INSERT INTO user_details (user_id, first_name, last_name, phone_number) 
                                        VALUES (:user_id, :first_name, :last_name, :phone_number)");
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':first_name', $first_name);
                $stmt->bindParam(':last_name', $last_name);
                $stmt->bindParam(':phone_number', $phone_number);

                if ($stmt->execute()) {
                    header("Location: login.php");
                    exit();
                } else {
                    $error_message = "Une erreur est survenue lors de l'inscription des détails de l'utilisateur.";
                }
            } else {
                $error_message = "Une erreur est survenue lors de l'inscription.";
            }
        }
    }
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
        <input type="text" name="email" class="input-field" placeholder="Email" required>
        <i class="bx bx-envelope"></i>
    </div>
    <div class="input-box">
        <input type="text" name="phone_number" class="input-field" placeholder="Phone Number" required>
        <i class="bx bx-phone"></i>
    </div>
    <div class="input-box">
        <input type="password" name="password" class="input-field" placeholder="Password" required>
        <i class="bx bx-lock-alt"></i>
    </div>
    <div class="input-box">
        <input type="password" name="confirm_password" class="input-field" placeholder="Confirm Password" required>
        <i class="bx bx-lock-alt"></i>
    </div>
    <div class="input-box">
        <input type="submit" class="submit" value="Register">
    </div>
</form>

<?php if (!empty($error_message)) : ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
<?php endif; ?>

            </div>
        </div>
    </div>
</body>

</html>