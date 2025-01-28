<?php
require_once __DIR__ . '/../../vendor/autoload.php'; // Autoloading de Composer
require_once __DIR__ . '/../../App/helpers/auth_helpers.php'; // Include the auth helper

use App\Services\Authentification;

checkAuthentication(); // Check if the user is authenticated

// Récupérer les informations de l'utilisateur connecté
$user_id = $_SESSION['user_id'];
$authService = new Authentification();
$user = $authService->getUserById($user_id);

if (!$user) {
    error_log("Utilisateur avec ID $user_id non trouvé");
    header('Location: login.php');
    exit();
}

$role = $user['role_id'];
error_log("Utilisateur connecté: " . print_r($user, true));

// Déterminer le message de salutation en fonction de l'heure
function getGreeting() {
    $hour = date('H');
    if ($hour >= 6 && $hour < 18) {
        return "Bonjour";
    } elseif ($hour >= 18 && $hour < 22) {
        return "Bonsoir";
    } else {
        return "Bonne nuit";
    }
}

$greeting = getGreeting();
?>

<?php include '../../App/views/includes/header.php'; ?>

<head>
    <link rel="stylesheet" href="../../public/css/styles.css"> <!-- Inclure le fichier CSS -->
</head>

<body class="index-page">
    <main>
        <h2><?php echo $greeting; ?>, <?php echo htmlspecialchars($user['prenom']); ?>!</h2>
        <p>Utilisez le menu ci-dessus pour naviguer dans l'application.</p>
    </main>

<?php include '../../App/views/includes/footer.php'; ?>