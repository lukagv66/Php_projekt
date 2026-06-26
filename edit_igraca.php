<?php

require_once 'config/bazapod.php';
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Nemate ovlasti za pristup ovoj stranici.");
}

$errors = [];


if (!isset($_GET['id'])) {
    header("Location: igraci.php");
    exit;
}

$id = intval($_GET['id']);

$stmt = $pdo->prepare("SELECT * FROM igraci WHERE ID_igraca = ?");
$stmt->execute([$id]);
$igrac = $stmt->fetch();

if (!$igrac) {
    header("Location: igraci.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ime       = trim($_POST['ime']);
    $prezime   = trim($_POST['prezime']);
    $br_dresa  = intval($_POST['br_dresa']);
    $golovi    = intval($_POST['golovi']);

    if (empty($ime)) { $errors[] = "Ime je obavezno."; }
    if (empty($prezime)) { $errors[] = "Prezime je obavezno."; }
    if ($br_dresa <= 0 || $br_dresa > 99) { $errors[] = "Broj dresa mora biti između 1 i 99."; }
    if ($golovi < 0) { $errors[] = "Broj golova ne može biti negativan."; }

    if (empty($errors)) {
        $sql = "UPDATE igraci SET ime = ?, prezime = ?, br_dresa = ?, golovi = ? WHERE ID_igraca = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$ime, $prezime, $br_dresa, $golovi, $id]);
        
        header("Location: igraci.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Uredi Igrača - NK Klub</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 50px; }
        .form-container { max-width: 400px; margin: 0 auto; background: white; padding: 20px; border-radius: 5px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group input { width: 100%; padding: 8px; box-sizing: border-box; }
        .btn { background-color: #007bff; color: white; padding: 10px; border: none; width: 100%; cursor: pointer; border-radius: 3px; }
        .btn-cancel { background-color: #6c757d; color: white; text-decoration: none; padding: 9px 15px; display: inline-block; text-align: center; border-radius: 3px; margin-left: 10px; }
        .error { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Uredi podatke igrača</h2>
    
    <?php if (!empty($errors)): ?>
        <div class="error"><?php foreach ($errors as $error): ?><p><?php echo $error; ?></p><?php endforeach; ?></div>
    <?php endif; ?>

    <form action="edit_igraca.php?id=<?php echo $id; ?>" method="POST">
        <div class="form-group">
            <label>Ime:</label>
            <input type="text" name="ime" value="<?php echo htmlspecialchars($igrac['ime']); ?>" required>
        </div>
        <div class="form-group">
            <label>Prezime:</label>
            <input type="text" name="prezime" value="<?php echo htmlspecialchars($igrac['prezime']); ?>" required>
        </div>
        <div class="form-group">
            <label>Broj dresa:</label>
            <input type="number" name="br_dresa" value="<?php echo htmlspecialchars($igrac['br_dresa']); ?>" min="1" max="99" required>
        </div>
        <div class="form-group">
            <label>Broj golova:</label>
            <input type="number" name="golovi" value="<?php echo htmlspecialchars($igrac['golovi']); ?>" min="0" required>
        </div>
        
        <button type="submit" class="btn">Spremi izmjene</button>
        <a href="igraci.php" class="btn-cancel">Odustani</a>
    </form>
</div>

</body>
</html>