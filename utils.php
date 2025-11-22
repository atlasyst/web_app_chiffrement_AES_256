<?php
require_once __DIR__ . '/config.php';

function check_auth() {
    if (empty($_SESSION['logged'])) {
        header('Location: login.php'); exit;
    }
    if (time() - ($_SESSION['last_activity'] ?? 0) > SESSION_TIMEOUT) {
        session_unset(); session_destroy();
        header('Location: login.php?msg=timeout'); exit;
    }
    $_SESSION['last_activity'] = time();
}

function attempt_login($u, $p) {
    return ($u === APP_USER && password_verify($p, APP_PW_HASH));
}

function derive_key($pass, $salt) {
    return hash_pbkdf2('sha256', $pass, $salt, 200000, 32, true);
}

function encrypt_message($plaintext, $passphrase) {
    $salt = random_bytes(16);
    $key  = derive_key($passphrase, $salt);
    $iv   = random_bytes(12);

    $tag = '';
    $cipher = openssl_encrypt(
        $plaintext,
        'aes-256-gcm',
        $key,
        OPENSSL_RAW_DATA,
        $iv,
        $tag,
        ''
    );

    return base64_encode(json_encode([
        'v'    => 1,
        'salt' => base64_encode($salt),
        'iv'   => base64_encode($iv),
        'tag'  => base64_encode($tag),
        'ct'   => base64_encode($cipher)
    ]));
}

function decrypt_message($b64, $passphrase) {
    $data = json_decode(base64_decode($b64), true);
    if (!is_array($data)) return [false, 'Format invalide'];

    $salt = base64_decode($data['salt']);
    $iv   = base64_decode($data['iv']);
    $tag  = base64_decode($data['tag']);
    $ct   = base64_decode($data['ct']);

    $key = derive_key($passphrase, $salt);

    $plain = openssl_decrypt(
        $ct,
        'aes-256-gcm',
        $key,
        OPENSSL_RAW_DATA,
        $iv,
        $tag,
        ''
    );

    return $plain !== false ? [true, $plain] : [false, 'Échec du déchiffrement'];
}
?>
