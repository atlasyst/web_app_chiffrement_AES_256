login : dédé
mdp : root

j'ai pas eu le temps de me faire de page de création de nouveau utilisateur donc je les ai mis dans config.php en dur

## Objectifs du projet
- Chiffrer des données avec un algo sérieux → j’ai pris **AES‑256‑GCM** (c’est fiable, OpenSSL le gère, et c'est la solution la plus populaire et éprouvé).
- Authentification basique avec user + mdp, parce qu’on ne peut pas faire mieux vu les contraintes.
- Le site doit marcher en HTTPS et on n’a pas accès aux permissions serveur, donc j’ai dû faire simple.

## Pourquoi AES‑256
- C’est sécurisé.
- Le mode GCM gère aussi l’intégrité.
- Disponible directement dans OpenSSL → plus rapide à coder.
- c'est largement utilisé donc éprouvé.
- aes-256 n'est pas colisionné et je l'ai choisis plutot que chacha20 car je me connais plus de choses sur aes-256
- j'ai prix aes-256 plutot qu'aes-128 parce que je n'ai pas de contrainte de rapidité et car aes-256 est encore plus fort qu'aes-128 et pour moi ça ne me coute pas plus a implémenter

## Sources et références utilisées
- PHP Manual (OpenSSL, password_hash, random_bytes…)
- OWASP Crypto CheatSheet (https://cheatsheetseries.owasp.org/cheatsheets/Cryptographic_Storage_Cheat_Sheet.html) ils disent exactement ce qu'il faut faire et ne pas faire donc top et en plus c'été plutot facile a lire.
- Quelques réponses sur Security.StackExchange
- RFC 5116 (juste pour confirmer l’IV de 12 bytes)

Alors voilà ce que j’ai utilisé pour m’aider, parfois pour vérifier un truc, parfois pour comprendre pourquoi ça marchait pas:

- PHP Manual (OpenSSL) → pour `openssl_encrypt` / `openssl_decrypt` (surtout les paramètres disont... 'originaux' du mode GCM).
- PHP Manual pour `password_hash` et `password_verify` → pour l’authentification.
- PHP Manual pour `hash_pbkdf2` → dérivation de clé.
- random_bytes → parce que visiblement `mt_rand()` c’est « non ».
- OWASP (Crypto Cheatsheet) → pour être sûr de pas faire un truc trop bancal.
- Quelques threads sur Security.StackExchange → pour la structure salt + iv + tag + ciphertext.
- RFC 5116 → je l’ai survolée, vraiment vite, juste pour voir si GCM demande un IV de 12 bytes (oui).

(Si j’en ai oublié un ou deux, c’est possible, j’ai cherché un peu dans tous les sens au début.)
J’ai consulté plusieurs ressources pour m’assurer que l’implémentation respecte les standards :

- PHP Manual – OpenSSL : documentation sur `openssl_encrypt`, `openssl_decrypt`, AES‑GCM, IV et tag.
- PHP Manual – password_hash / password_verify : mise en place de l’authentification.
- PHP Manual – hash_pbkdf2 : dérivation de clé basée sur PBKDF2‑HMAC‑SHA256.
- PHP Manual – random_bytes : génération sécurisée d’aléas.
- OWASP Cryptographic CheatSheet : bonnes pratiques pour le choix des algorithmes et la construction des payloads chiffrés.
- Security.StackExchange.com : discussions sur l’usage correct d’AES‑GCM (structure salt/iv/tag/ciphertext).
- RFC 5116 : spécification du mode AES‑GCM.

Ces ressources m’ont permis de vérifier :
- la longueur correcte des IV
- le format recommandé pour stocker les données chiffrées
- la manière de dériver une clé à partir d'une passphrase

## Structure du projet
Le projet contient les fichiers suivants :
- config.php — paramètres globaux, identifiant, mot de passe hashé
- index.php — redirection automatique selon l’état de connexion
- login.php — formulaire de connexion
- logout.php — déconnexion et destruction de session
- encrypt.php — interface de chiffrement
- decrypt.php — interface de déchiffrement
- utils.php — fonctions cryptographiques et utilitaires