<?php
require_once __DIR__.'/utils.php'; check_auth();
$result='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $p=$_POST['plaintext']??'';
    $pw=$_POST['passphrase']??'';
    if($p===''||$pw==='') $result='Données manquantes';
    else $result = encrypt_message($p,$pw);
}
?>
<!doctype html><html><head>
    <meta charset="utf-8"><title>Chiffrer</title>
    <style>body{font-family:Arial;max-width:900px;margin:2rem auto}textarea{width:100%;height:160px}</style>
</head><body>
<p><a href="logout.php">Déconnexion</a> | <a href="decrypt.php">Déchiffrer</a></p>
<h1>Chiffrer</h1>
<form method="post">
    Message<br><textarea name="plaintext" required><?=htmlspecialchars($_POST['plaintext']??'')?></textarea><br><br>
    Passphrase<br><input name="passphrase" required><br><br>
    <button>Chiffrer</button>
</form>
<?php if($result): ?><h2>Résultat</h2><textarea readonly><?=htmlspecialchars($result)?></textarea><?php endif; ?>
</body></html>