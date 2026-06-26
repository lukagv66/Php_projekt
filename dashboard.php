<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$role     = $_SESSION['role']; 
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Nadzorna Ploča - NK Klub</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
        .navbar { background-color: #343a40; color: white; padding: 15px; display: flex; justify-content: space-between; border-radius: 5px; }
        .navbar a { color: white; text-decoration: none; margin-left: 15px; }
        .container { margin-top: 20px; background: white; padding: 20px; border-radius: 5px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        .welcome-msg { color: #007bff; }
        .badge { padding: 5px 10px; border-radius: 3px; font-weight: bold; color: white; }
        .badge-admin { background-color: #dc3545; } 
        .badge-user { background-color: #28a745; }   
        .card-container { display: flex; gap: 20px; margin-top: 20px; }
        .card { flex: 1; padding: 15px; border: 1px solid #ddd; border-radius: 5px; background: #fafafa; }
        .btn { display: inline-block; background: #007bff; color: white; padding: 8px 12px; text-decoration: none; border-radius: 3px; margin-top: 10px; }
    </style>
</head>
<body>

<div class="navbar">
    <div><strong>NK Klub Panel</strong></div>
    <div>
        Prijavljeni ste kao: <strong><?php echo htmlspecialchars($username); ?></strong> 
        <a href="logout.php" style="color: #ffc107;">Odjava</a>
    </div>
</div>

<div class="container">
    <h2 class="welcome-msg">Dobrodošli natrag, <?php echo htmlspecialchars($username); ?>!</h2>
    
    <p>Vaša uloga u sustavu: 
        <?php if ($role === 'admin'): ?>
            <span class="badge badge-admin">Trener</span>
        <?php else: ?>
            <span class="badge badge-user">Navijač</span>
        <?php endif; ?>
    </p>

    <hr>

    <h3>Pregled aktivnosti kluba</h3>
    
    <div class="card-container">
        
        <div class="card">
            <h4>📅 Raspored utakmica</h4>
                <p>Pogledajte kada i gdje igra naš klub sljedeći susret.</p>
             <a href="utakmice.php" class="btn">Otvori raspored</a>
        </div>

        <?php if ($role === 'admin'): ?>
            <div class="card" style="border-left: 5px solid #dc3545;">
                <h4>🛠️ Upravljanje Klubom (Admin panel)</h4>
                <p>Imate pune ovlasti za upravljanje igračima.</p>
                <a href="igraci.php" class="btn">Upravljaj igračima</a>
            </div>
        <?php else: ?>
            <div class="card" style="border-left: 5px solid #28a745;">
                <h4>🏆 Zona za navijače</h4>
                <p>Pogledajte trenutni popis igrača i njihovu statistiku golova.</p>
                <a href="igraci.php" class="btn">Pregledaj igrače</a>
            </div>
        <?php endif; ?>

    </div>
</div>

</body>
</html>