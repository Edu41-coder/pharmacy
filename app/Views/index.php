<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Gestion de Pharmacie</title>
</head>

<body>
    <h1>Bienvenue dans la Gestion de Pharmacie</h1>

    <?php if ($isLoggedIn) : ?>
        <p>Vous êtes connecté.</p>
        <a href="/logout">Se déconnecter</a>
    <?php else : ?>
        <p>Vous n'êtes pas connecté.</p>
        <a href="/login">Se connecter</a> ou <a href="/register">S'inscrire</a>
    <?php endif; ?>

    <h2>Fonctionnalités</h2>
    <ul>
        <li><a href="/products">Gestion des produits</a></li>
        <li><a href="/inventory">Gestion des stocks</a></li>
        <li><a href="/sales">Gestion des ventes</a></li>
        <?php if ($isLoggedIn && isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1) : ?>
            <li><a href="/users">Gestion des utilisateurs</a></li>
        <?php endif; ?>
    </ul>
</body>

</html>