<?php
require_once __DIR__ . '/utils.php';
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(attempt_login($_POST['user'],$_POST['password'])){
        session_regenerate_id(true);
        $_SESSION['logged']=true;
        $_SESSION['last_activity']=time();
        header('Location: encrypt.php'); exit;
    } else $msg='Identifiants incorrects';
}
?>
<!doctype html><html lang="fr"><head>
    <meta charset="utf-8"><title>Login</title>
    <style>body{font-family:Arial;max-width:800px;margin:2rem auto}</style>
</head><body>
<h1>Connexion</h1>
<?php if($msg): ?><p style="color:red"><?=$msg?></p><?php endif; ?>
<form method="post">
    Utilisateur<br><input name="user" required><br><br>
    Mot de passe<br><input type="password" name="password" required><br><br>
    <button>Connexion</button>
</form>
</body></html>