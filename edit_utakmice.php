<?php
require_once 'config/bazapod.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { die("Nemate ovlasti."); }

$id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT * FROM utakmice WHERE ID_utakmice = ?");
$stmt->execute([$id]);
$u = $stmt->fetch();

if (!$u) { header("Location: utakmice.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $domacin = trim($_POST['domacin']);
    $gost    = trim($_POST['gost']);
    $datum   = $_POST['datum_vrijeme'];
    $lokacija = trim($_POST['lokacija']);

    $sql = "UPDATE utakmice SET domacin = ?, gost = ?, datum_vrijeme = ?, lokacija = ? WHERE ID_utakmice = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$domacin, $gost, $datum, $lokacija, $id]);
    header("Location: utakmice.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Uredi Utakmicu</title>
</head>
<body style="font-family: Arial; padding: 50px;">
    <div style="max-width: 400px; margin: 0 auto; background: white; padding: 20px; border: 1px solid #ddd;">
        <h2>Uredi utakmicu</h2>
        <form action="edit_utakmice.php?id=<?php echo $id; ?>" method="POST">
            <p>Domaćin: <br><input type="text" name="domacin" value="<?php echo htmlspecialchars($u['domacin']); ?>" required></p>
            <p>Gost: <br><input type="text" name="gost" value="<?php echo htmlspecialchars($u['gost']); ?>" required></p>
            <p>Datum i vrijeme: <br><input type="datetime-local" name="datum_vrijeme" value="<?php echo date('Y-m-d\TH:i', strtotime($u['datum_vrijeme'])); ?>" required></p>
            <p>Lokacija: <br><input type="text" name="lokacija" value="<?php echo htmlspecialchars($u['lokacija']); ?>" required></p>
            <button type="submit">Spremi izmjene</button>
            <a href="utakmice.php">Odustani</a>
        </form>
    </div>
</body>
</html>