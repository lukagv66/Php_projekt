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
    if ($role !== 'admin') {
        die("Nemate ovlasti za ovu akciju.");
    }

    $ime       = trim($_POST['ime']);
    $prezime   = trim($_POST['prezime']);
    $br_dresa  = intval($_POST['br_dresa']);
    $golovi    = intval($_POST['golovi']);

    if (empty($ime)) { $errors[] = "Ime igrača je obavezno."; }
    if (empty($prezime)) { $errors[] = "Prezime igrača je obavezno."; }
    if ($br_dresa <= 0 || $br_dresa > 99) { $errors[] = "Broj dresa mora biti između 1 i 99."; }
    if ($golovi < 0) { $errors[] = "Broj golova ne može biti negativan."; }

    if (empty($errors)) {
        $sql = "INSERT INTO igraci (ime, prezime, br_dresa, golovi) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$ime, $prezime, $br_dresa, $golovi]);

        header("Location: igraci.php");
        exit;
    }
}

if (isset($_GET['delete_id'])) {
    if ($role !== 'admin') {
        die("Nemate ovlasti za ovu akciju.");
    }
    
    $delete_id = intval($_GET['delete_id']);

    $sql = "DELETE FROM igraci WHERE ID_igraca = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$delete_id]);
    
    header("Location: igraci.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM igraci ORDER BY golovi DESC"); 
$igraci = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Upravljanje Igračima - NK Klub</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .back-link { display: inline-block; margin-bottom: 15px; color: #007bff; text-decoration: none; }
        .container { background: white; padding: 20px; border-radius: 5px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #343a40; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .btn-delete { color: red; text-decoration: none; font-weight: bold; margin-left: 10px; }
        .btn-edit { color: orange; text-decoration: none; font-weight: bold; }
        .form-inline input { padding: 8px; margin-right: 10px; border: 1px solid #ddd; border-radius: 3px; }
        .btn-submit { background: #28a745; color: white; padding: 8px 15px; border: none; cursor: pointer; border-radius: 3px; }
        .error { color: red; margin-bottom: 15px; }
    </style>
</head>
<body>

<a href="dashboard.php" class="back-link">⬅ Natrag na Nadzornu ploču</a>

<?php if ($role === 'admin'): ?>
    <div class="container">
        <h3>Dodaj novog igrača u klub</h3>
        
        <?php if (!empty($errors)): ?>
            <div class="error"><?php foreach ($errors as $error): ?><p><?php echo $error; ?></p><?php endforeach; ?></div>
        <?php endif; ?>

        <form action="igraci.php" method="POST" class="form-inline">
            <input type="hidden" name="action" value="add">
            <input type="text" name="ime" placeholder="Ime igrača" required>
            <input type="text" name="prezime" placeholder="Prezime igrača" required>
            <input type="number" name="br_dresa" placeholder="Broj dresa" min="1" max="99" required>
            <input type="number" name="golovi" placeholder="Broj golova" min="0" required>
            <button type="submit" class="btn-submit">Spremi igrača</button>
        </form>
    </div>
<?php endif; ?>

<div class="container">
    <h3>Popis igrača i statistika golova</h3>
    <table>
        <thead>
            <tr>
                <th>Broj dresa</th>
                <th>Ime</th>
                <th>Prezime</th>
                <th>Golovi</th>
                <?php if ($role === 'admin'): ?><th>Akcije</th><?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($igraci)): ?>
                <tr>
                    <td colspan="<?php echo $role === 'admin' ? 5 : 4; ?>" style="text-align: center;">Trenutno nema igrača u bazi.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($igraci as $igrac): ?>
                    <tr>
                        <td><strong>#<?php echo htmlspecialchars($igrac['br_dresa']); ?></strong></td>
                        <td><?php echo htmlspecialchars($igrac['ime']); ?></td>
                        <td><?php echo htmlspecialchars($igrac['prezime']); ?></td>
                        <td>⚽ <?php echo htmlspecialchars($igrac['golovi']); ?></td>
                        
                        <?php if ($role === 'admin'): ?>
                            <td>
                                <a href="edit_igraca.php?id=<?php echo $igrac['ID_igraca']; ?>" class="btn-edit">Uredi</a>
                                <a href="igraci.php?delete_id=<?php echo $igrac['ID_igraca']; ?>" class="btn-delete" onclick="return confirm('Jeste li sigurni da želite obrisati ovog igrača?')">Obriši</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>