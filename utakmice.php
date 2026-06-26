<?php
require_once 'config/bazapod.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    if ($role !== 'admin') { die("Nemate ovlasti."); }

    $domacin = trim($_POST['domacin']);
    $gost    = trim($_POST['gost']);
    $datum   = $_POST['datum_vrijeme'];
    $lokacija = trim($_POST['lokacija']);

    if (empty($domacin) || empty($gost) || empty($datum) || empty($lokacija)) {
        $errors[] = "Sva polja su obavezna.";
    }

    if (empty($errors)) {
        $sql = "INSERT INTO utakmice (domacin, gost, datum_vrijeme, lokacija) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$domacin, $gost, $datum, $lokacija]);
        header("Location: utakmice.php");
        exit;
    }
}

if (isset($_GET['delete_id'])) {
    if ($role !== 'admin') { die("Nemate ovlasti."); }
    $stmt = $pdo->prepare("DELETE FROM utakmice WHERE ID_utakmice = ?");
    $stmt->execute([intval($_GET['delete_id'])]);
    header("Location: utakmice.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM utakmice ORDER BY datum_vrijeme ASC");
$utakmice = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Raspored Utakmica</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { background: white; padding: 20px; border-radius: 5px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #343a40; color: white; }
        .btn-delete { color: red; text-decoration: none; font-weight: bold; margin-left: 10px; }
        .btn-edit { color: orange; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

<a href="dashboard.php">⬅ Natrag na Nadzornu ploču</a>

<?php if ($role === 'admin'): ?>
    <div class="container" style="margin-top: 15px;">
        <h3>Dodaj novu utakmicu</h3>
        <form action="utakmice.php" method="POST">
            <input type="hidden" name="action" value="add">
            <input type="text" name="domacin" placeholder="Domaćin" required>
            <input type="text" name="gost" placeholder="Gost" required>
            <input type="datetime-local" name="datum_vrijeme" required>
            <input type="text" name="lokacija" placeholder="Stadion/Lokacija" required>
            <button type="submit" style="background:#28a745; color:white; border:none; padding:8px;">Dodaj</button>
        </form>
    </div>
<?php endif; ?>

<div class="container">
    <h3>Raspored sljedećih utakmica</h3>
    <table>
        <thead>
            <tr>
                <th>Utakmica</th>
                <th>Datum i vrijeme</th>
                <th>Lokacija</th>
                <?php if ($role === 'admin'): ?><th>Akcije</th><?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($utakmice as $u): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($u['domacin']); ?></strong> - <strong><?php echo htmlspecialchars($u['gost']); ?></strong></td>
                    <td><?php echo date('d.m.Y. H:i', strtotime($u['datum_vrijeme'])); ?>h</td>
                    <td>📍 <?php echo htmlspecialchars($u['lokacija']); ?></td>
                    <?php if ($role === 'admin'): ?>
                        <td>
                            <a href="edit_utakmice.php?id=<?php echo $u['ID_utakmice']; ?>" class="btn-edit">Uredi</a>
                            <a href="utakmice.php?delete_id=<?php echo $u['ID_utakmice']; ?>" class="btn-delete" onclick="return confirm('Obriši utakmicu?')">Obriši</a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>