# Introduction aux APIs RESTful

## Qu'est-ce qu'une API REST ?

Une API REST (Representational State Transfer) est un style d'architecture pour la création de services web. Elle définit un ensemble de contraintes et de conventions pour la communication entre clients et serveurs via HTTP.

## Principes Fondamentaux

### 1. Architecture Client-Serveur
- Séparation claire entre client et serveur
- Le client ne se préoccupe pas du stockage des données
- Le serveur ne se préoccupe pas de l'interface utilisateur

### 2. Sans État (Stateless)
- Chaque requête contient toutes les informations nécessaires
- Pas de session côté serveur
- Chaque requête est indépendante

### 3. Mise en Cache
- Les réponses doivent indiquer si elles peuvent être mises en cache
- Améliore les performances
- Réduit la charge serveur

### 4. Interface Uniforme
- Identification des ressources (URI)
- Manipulation des ressources via leurs représentations
- Messages auto-descriptifs
- HATEOAS (Hypermedia as the Engine of Application State)

## Méthodes HTTP Standard

### GET
Exemple de requête GET :
GET /api/users/123
Accept: application/json

### POST
Exemple de création d'utilisateur :
POST /api/users
Content-Type: application/json
{
    "name": "John Doe",
    "email": "john@example.com"
}

### PUT
Exemple de mise à jour complète :
PUT /api/users/123
Content-Type: application/json
{
    "name": "John Doe",
    "email": "john@example.com",
    "age": 30
}

### PATCH
Exemple de mise à jour partielle :
PATCH /api/users/123
Content-Type: application/json
{
    "email": "john.doe@example.com"
}

### DELETE
Exemple de suppression :
DELETE /api/users/123

## Format des URLs

### Bonnes Pratiques
Exemples de bonnes URLs :
/api/users                  # Liste des utilisateurs
/api/users/123             # Un utilisateur spécifique
/api/users/123/orders      # Commandes d'un utilisateur
/api/orders/456            # Une commande spécifique

### Mauvaises Pratiques à Éviter
/api/getUsers              # Utilise un verbe
/api/user                  # Utilise le singulier
/api/users/123/delete      # Utilise une action dans l'URL

## Codes de Statut HTTP

### 2xx - Succès
Exemple de réponse 201 Created :
HTTP/1.1 201 Created
Location: /api/users/123
{
    "id": 123,
    "message": "User created successfully"
}

### 4xx - Erreur Client
Exemple de réponse 400 Bad Request :
HTTP/1.1 400 Bad Request
{
    "error": "Invalid email format",
    "field": "email"
}

### 5xx - Erreur Serveur
Exemple de réponse 500 :
HTTP/1.1 500 Internal Server Error
{
    "error": "An unexpected error occurred",
    "reference": "ERR_12345"
}

## Format des Réponses

### Succès
{
    "status": "success",
    "data": {
        "id": 123,
        "name": "John Doe",
        "email": "john@example.com"
    }
}

### Erreur
{
    "status": "error",
    "message": "Resource not found",
    "code": "404"
}

### Collection
{
    "data": [
        { "id": 1, "name": "John" },
        { "id": 2, "name": "Jane" }
    ],
    "meta": {
        "total": 50,
        "page": 1,
        "per_page": 2
    }
}

## Sécurité

### Authentification par JWT
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...

### Rate Limiting
Exemple d'en-têtes :
X-RateLimit-Limit: 100
X-RateLimit-Remaining: 98
X-RateLimit-Reset: 1640995200

### Validation des Entrées
Exemple de validation :
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return [
        "status": "error",
        "message": "Invalid email format"
    ];
} 