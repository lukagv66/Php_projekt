<?php
require_once 'config/bazapod.php';
session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['korisnicko_ime']) ? trim($_POST['korisnicko_ime']) : '';
    $password = isset($_POST['lozinka']) ? $_POST['lozinka'] : '';
    
    if (empty($username) || empty($password)) {
        $errors[] = "Morate unijeti korisničko ime i lozinku.";
    }
    
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT * FROM korisnici WHERE korisnicko_ime = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['lozinka'])) {
            $_SESSION['user_id'] = $user['ID_korisnika']; 
            $_SESSION['username'] = $user['korisnicko_ime']; 
            $_SESSION['role'] = $user['uloga']; 
            
            header("Location: dashboard.php");
            exit;
        } else {
            $errors[] = "Pogrešno korisničko ime ili lozinka.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Prijava - NK Klub</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 50px; }
        .form-container { max-width: 400px; margin: 0 auto; background: white; padding: 20px; border-radius: 5px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group input { width: 100%; padding: 8px; box-sizing: border-box; }
        .btn { background-color: #007bff; color: white; padding: 10px; border: none; width: 100%; cursor: pointer; }
        .error { color: red; margin-bottom: 10px; }
        .success { color: green; margin-bottom: 10px; }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Prijava u NK Klub</h2>
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
    <?php endif; ?>
    <?php if (!empty($errors)): ?>
        <div class="error"><?php foreach ($errors as $error): ?><p><?php echo $error; ?></p><?php endforeach; ?></div>
    <?php endif; ?>
    <form action="login.php" method="POST">
        <div class="form-group">
            <label>Korisničko ime:</label>
            <input type="text" name="korisnicko_ime" required>
        </div>
        <div class="form-group">
            <label>Lozinka:</label>
            <input type="password" name="lozinka" required>
        </div>
        <button type="submit" class="btn">Prijavi se</button>
    </form>
    <p style="margin-top: 15px;">Nemate račun? <a href="register.php">Registrirajte se ovdje</a>.</p>
</div>
</body>
</html>