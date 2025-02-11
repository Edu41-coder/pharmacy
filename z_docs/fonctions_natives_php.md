# Fonctions Natives PHP Courantes

## Manipulation de Chaînes

### strlen() - Longueur d'une chaîne
$texte = "Bonjour";
$longueur = strlen($texte); // Retourne 7

### str_replace() - Remplacer dans une chaîne
$texte = "Bonjour le monde";
$nouveau = str_replace("monde", "PHP", $texte); // "Bonjour le PHP"

### substr() - Extraire une partie de chaîne
$texte = "Bonjour";
$partie = substr($texte, 0, 3); // "Bon"

### explode() - Convertir une chaîne en tableau
$liste = "pomme,poire,banane";
$fruits = explode(",", $liste); // ["pomme", "poire", "banane"]

### implode() ou join() - Convertir un tableau en chaîne
$fruits = ["pomme", "poire", "banane"];
$liste = implode(", ", $fruits); // "pomme, poire, banane"

## Manipulation de Tableaux

### count() - Compter les éléments
$fruits = ["pomme", "poire", "banane"];
$nombre = count($fruits); // 3

### array_push() - Ajouter à la fin
$fruits = ["pomme"];
array_push($fruits, "poire"); // ["pomme", "poire"]

### array_pop() - Retirer le dernier
$fruits = ["pomme", "poire"];
$dernier = array_pop($fruits); // $dernier = "poire", $fruits = ["pomme"]

### array_map() - Transformer chaque élément
$nombres = [1, 2, 3];
$doubles = array_map(function($n) { return $n * 2; }, $nombres); // [2, 4, 6]

### array_filter() - Filtrer les éléments
$nombres = [1, 2, 3, 4];
$pairs = array_filter($nombres, function($n) { return $n % 2 == 0; }); // [2, 4]

## Vérification de Types

### isset() - Vérifier si une variable existe
$nom = "Jean";
if (isset($nom)) { // true
    echo "La variable existe";
}

### empty() - Vérifier si vide
$tableau = [];
if (empty($tableau)) { // true
    echo "Le tableau est vide";
}

### is_array() - Vérifier si tableau
$var = ["test"];
if (is_array($var)) { // true
    echo "C'est un tableau";
}

## Fichiers et Dossiers

### file_exists() - Vérifier si un fichier existe
if (file_exists("config.php")) {
    include "config.php";
}

### file_get_contents() - Lire un fichier
$contenu = file_get_contents("fichier.txt");

### file_put_contents() - Écrire dans un fichier
file_put_contents("log.txt", "Une erreur est survenue", FILE_APPEND);

## Date et Temps

### time() - Timestamp actuel
$maintenant = time(); // Secondes depuis 1970

### date() - Formater une date
$date = date("Y-m-d H:i:s"); // "2024-01-20 15:30:45"

### strtotime() - Convertir texte en timestamp
$futur = strtotime("+1 week"); // Timestamp dans une semaine

## Débogage

### var_dump() - Afficher le contenu d'une variable
$user = ["nom" => "Jean", "age" => 25];
var_dump($user);

### print_r() - Afficher un tableau lisiblement
$fruits = ["pomme", "poire"];
print_r($fruits);

### die() ou exit() - Arrêter l'exécution
if ($erreur) {
    die("Une erreur est survenue");
}

## Sécurité

### password_hash() - Hasher un mot de passe
$hash = password_hash("motdepasse123", PASSWORD_DEFAULT);

### password_verify() - Vérifier un mot de passe
if (password_verify("motdepasse123", $hash)) {
    echo "Mot de passe correct";
}

### htmlspecialchars() - Échapper le HTML
$texte = "<script>alert('XSS')</script>";
echo htmlspecialchars($texte); // Affiche le texte sans exécuter le script 