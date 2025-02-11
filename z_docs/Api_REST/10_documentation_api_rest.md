# Documentation d'API REST

## Swagger/OpenAPI

### Configuration OpenAPI
openapi.yaml :
openapi: 3.0.0
info:
  title: API de Gestion Pharmacie
  version: 1.0.0
  description: API REST pour la gestion d'une pharmacie
servers:
  - url: http://localhost:8000/api
    description: Serveur de développement
  - url: https://api.pharmacie.com
    description: Serveur de production

### Exemple de Documentation d'Endpoints
paths:
  /users:
    get:
      summary: Liste des utilisateurs
      security:
        - BearerAuth: []
      parameters:
        - in: query
          name: page
          schema:
            type: integer
          description: Numéro de page
        - in: query
          name: limit
          schema:
            type: integer
          description: Nombre d'éléments par page
      responses:
        '200':
          description: Liste des utilisateurs
          content:
            application/json:
              schema:
                type: object
                properties:
                  data:
                    type: array
                    items:
                      $ref: '#/components/schemas/User'
                  meta:
                    $ref: '#/components/schemas/PaginationMeta'
    post:
      summary: Créer un utilisateur
      security:
        - BearerAuth: []
      requestBody:
        required: true
        content:
          application/json:
            schema:
              type: object
              required:
                - email
                - password
                - name
              properties:
                email:
                  type: string
                  format: email
                password:
                  type: string
                  minLength: 8
                name:
                  type: string
      responses:
        '201':
          description: Utilisateur créé
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/User'

### Définition des Schémas
components:
  schemas:
    User:
      type: object
      properties:
        id:
          type: integer
        email:
          type: string
          format: email
        name:
          type: string
        role:
          type: string
          enum: [user, admin]
        created_at:
          type: string
          format: date-time
    
    PaginationMeta:
      type: object
      properties:
        total:
          type: integer
        page:
          type: integer
        limit:
          type: integer
        last_page:
          type: integer

  securitySchemes:
    BearerAuth:
      type: http
      scheme: bearer
      bearerFormat: JWT

## Postman Collections

### Collection de Base
{
    "info": {
        "name": "API Pharmacie",
        "description": "Collection pour l'API de gestion de pharmacie",
        "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
    },
    "item": [
        {
            "name": "Authentification",
            "item": [
                {
                    "name": "Login",
                    "request": {
                        "method": "POST",
                        "url": "{{base_url}}/auth/login",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": {
                                "email": "admin@example.com",
                                "password": "password123"
                            }
                        }
                    }
                }
            ]
        },
        {
            "name": "Utilisateurs",
            "item": [
                {
                    "name": "Liste des utilisateurs",
                    "request": {
                        "method": "GET",
                        "url": "{{base_url}}/users",
                        "auth": {
                            "type": "bearer",
                            "bearer": "{{token}}"
                        }
                    }
                }
            ]
        }
    ],
    "variable": [
        {
            "key": "base_url",
            "value": "http://localhost:8000/api"
        }
    ]
}

### Variables d'Environnement
{
    "name": "Development",
    "values": [
        {
            "key": "base_url",
            "value": "http://localhost:8000/api",
            "enabled": true
        },
        {
            "key": "token",
            "value": "",
            "enabled": true
        }
    ]
}

## Documentation Automatique

### Annotations PHP pour la Documentation
class UserController extends ApiController
{
    /**
     * Liste des utilisateurs
     * 
     * @OA\Get(
     *     path="/users",
     *     summary="Récupère la liste des utilisateurs",
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Numéro de page",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des utilisateurs",
     *         @OA\JsonContent(ref="#/components/schemas/UserCollection")
     *     )
     * )
     */
    public function index(Request $request): Response
    {
        // Code de l'action...
    }

    /**
     * Création d'un utilisateur
     * 
     * @OA\Post(
     *     path="/users",
     *     summary="Crée un nouvel utilisateur",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserCreate")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Utilisateur créé",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     )
     * )
     */
    public function store(Request $request): Response
    {
        // Code de l'action...
    }
}

### Génération de la Documentation
script de génération :
#!/usr/bin/env php
<?php

require 'vendor/autoload.php';

$openapi = \OpenApi\Generator::scan([
    'app/Controllers',
    'app/Models'
]);

file_put_contents(
    'public/docs/openapi.json',
    $openapi->toJson()
);

### Interface Web de Documentation
<!DOCTYPE html>
<html>
<head>
    <title>API Documentation</title>
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@4/swagger-ui.css">
</head>
<body>
    <div id="swagger-ui"></div>
    <script src="https://unpkg.com/swagger-ui-dist@4/swagger-ui-bundle.js"></script>
    <script>
        window.onload = () => {
            SwaggerUIBundle({
                url: '/docs/openapi.json',
                dom_id: '#swagger-ui'
            });
        };
    </script>
</body>
</html>

## Bonnes Pratiques

1. **Documentation à Jour**
   - Mettre à jour la documentation en même temps que le code
   - Utiliser des outils de génération automatique
   - Versionner la documentation avec le code

2. **Exemples Clairs**
   - Fournir des exemples pour chaque endpoint
   - Inclure les cas d'erreur courants
   - Documenter les en-têtes requis

3. **Tests de Documentation**
   - Vérifier que les exemples fonctionnent
   - Tester les collections Postman
   - Valider le fichier OpenAPI

4. **Environnements**
   - Documenter les différences entre environnements
   - Fournir des variables d'environnement
   - Expliquer le processus de déploiement 