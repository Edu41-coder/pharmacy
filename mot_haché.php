<?php
$password = 'duedue';
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
echo "Mot de passe haché : " . $hashedPassword;