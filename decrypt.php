<?php
require_once __DIR__.'/utils.php'; check_auth();
$plain='';$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    [$ok,$res]=decrypt_message($_POST['payload'],$_POST['passphrase']);
    if($ok) $plain=$res; else $err=$res;
}
?>
<!doctype html><html><head>
    <meta charset="utf-8"><title>Déchiffrer</title>
    <style>body{font-family:Arial;max-width:900px;margin:2rem auto}textarea{width:100%;height:160px}</style>
</head><body>
<p><a href="logout.php">Déconnexion</a> | <a href="encrypt.php">Chiffrer</a></p>
<h1>Déchiffrer</h1>
<?php if($err): ?><p style="color:red"><?=$err?></p><?php endif; ?>
<form method="post">
    Payload<br><textarea name="payload" required><?=htmlspecialchars($_POST['payload']??'')?></textarea><br><br>
    Passphrase<br><input name="passphrase" required><br><br>
    <button>Déchiffrer</button>
</form>
<?php if($plain): ?><h2>Message déchiffré</h2><textarea readonly><?=htmlspecialchars($plain)?></textarea><?php endif; ?>
</body></html>