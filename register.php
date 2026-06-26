<?php
require_once 'config/bazapod.php';
session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['korisnicko_ime']) ? trim($_POST['korisnicko_ime']) : '';
    $email    = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['lozinka']) ? $_POST['lozinka'] : '';
    
    if (empty($username)) { $errors[] = "Korisničko ime je obavezno."; }
    if (empty($email)) { $errors[] = "E-adresa je obavezna."; }
    if (empty($password)) { $errors[] = "Lozinka je obavezna."; }
    
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT ID_korisnika FROM korisnici WHERE korisnicko_ime = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $errors[] = "Korisničko ime je već zauzeto.";
        }
    }
    
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $default_role = 'user';
        
        $sql = "INSERT INTO korisnici (korisnicko_ime, lozinka, email, uloga) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute([$username, $hashed_password, $email, $default_role])) {
            $_SESSION['success_message'] = "Registracija uspješna! Sada se možete prijaviti.";
            header("Location: login.php");
            exit;
        } else {
            $errors[] = "Dogodila se pogreška prilikom spremanja u bazu.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Registracija - NK Projekt</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 50px; }
        .form-container { max-width: 400px; margin: 0 auto; background: white; padding: 20px; border-radius: 5px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group input { width: 100%; padding: 8px; box-sizing: border-box; }
        .btn { background-color: #28a745; color: white; padding: 10px; border: none; width: 100%; cursor: pointer; }
        .error { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Registracija u NK Klub</h2>
    <?php if (!empty($errors)): ?>
        <div class="error"><?php foreach ($errors as $error): ?><p><?php echo $error; ?></p><?php endforeach; ?></div>
    <?php endif; ?>
    <form action="register.php" method="POST">
        <div class="form-group">
            <label>Korisničko ime:</label>
            <input type="text" name="korisnicko_ime" required>
        </div>
        <div class="form-group">
            <label>E-adresa:</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Lozinka:</label>
            <input type="password" name="lozinka" required>
        </div>
        <button type="submit" class="btn">Registriraj se</button>
    </form>
    <p style="margin-top: 15px;">Već imate račun? <a href="login.php">Prijavite se ovdje</a>.</p>
</div>
</body>
</html>