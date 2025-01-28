<?php

namespace Core\Validator;

use Core\Database\Database;
use Core\Exception\ValidatorException;

class Validator
{
    private Database $db;
    private array $data;
    private array $rules;
    private array $errors = [];
    private array $validated = [];

    private const MESSAGES = [
        'required' => 'Le champ :attribute est requis',
        'email' => 'Le champ :attribute doit être une adresse email valide',
        'min' => 'Le champ :attribute doit avoir au moins :min caractères',
        'max' => 'Le champ :attribute ne doit pas dépasser :max caractères',
        'between' => 'Le champ :attribute doit être entre :min et :max',
        'numeric' => 'Le champ :attribute doit être un nombre',
        'integer' => 'Le champ :attribute doit être un nombre entier',
        'string' => 'Le champ :attribute doit être une chaîne de caractères',
        'array' => 'Le champ :attribute doit être un tableau',
        'boolean' => 'Le champ :attribute doit être un booléen',
        'in' => 'La valeur sélectionnée pour :attribute est invalide',
        'unique' => 'La valeur du champ :attribute est déjà utilisée',
        'confirmed' => 'La confirmation du champ :attribute ne correspond pas',
        'date' => 'Le champ :attribute n\'est pas une date valide',
        'alpha' => 'Le champ :attribute doit contenir uniquement des lettres',
        'alpha_num' => 'Le champ :attribute doit contenir uniquement des lettres et des chiffres',
        'url' => 'Le champ :attribute doit être une URL valide',
        'exists' => 'La valeur sélectionnée pour :attribute n\'existe pas'
    ];

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function validate(array $data, array $rules): array
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->errors = [];
        $this->validated = [];

        foreach ($this->rules as $field => $fieldRules) {
            $this->validateField($field, $fieldRules);
        }

        if (!empty($this->errors)) {
            throw new ValidatorException($this->errors);
        }

        return $this->validated;
    }

    private function validateField(string $field, $fieldRules): void
    {
        // Convertir les règles en tableau si c'est une chaîne
        $rules = is_string($fieldRules) ? explode('|', $fieldRules) : $fieldRules;
        $value = $this->data[$field] ?? null;
        $isOptional = in_array('sometimes', $rules);

        // Si le champ est optionnel et vide, on skip
        if ($isOptional && ($value === null || $value === '')) {
            return;
        }

        foreach ($rules as $rule) {
            $this->processRule($field, $value, $rule);
        }

        // Si aucune erreur, ajouter aux données validées
        if (!isset($this->errors[$field])) {
            $this->validated[$field] = $value;
        }
    }

    private function processRule(string $field, $value, string $rule): void
    {
        // Séparer le nom de la règle et ses paramètres
        $parts = explode(':', $rule);
        $ruleName = $parts[0];
        $parameters = isset($parts[1]) ? explode(',', $parts[1]) : [];

        // Skip la règle 'sometimes'
        if ($ruleName === 'sometimes') {
            return;
        }

        try {
            $method = 'validate' . ucfirst($ruleName);
            if (method_exists($this, $method)) {
                $this->$method($field, $value, $parameters);
            }
        } catch (\Exception $e) {
            $this->addError($field, $this->formatMessage($e->getMessage(), [
                'attribute' => $field,
                'min' => $parameters[0] ?? '',
                'max' => $parameters[1] ?? ''
            ]));
        }
    }

    private function addError(string $field, string $message): void
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $message;
    }

    private function formatMessage(string $message, array $parameters): string
    {
        foreach ($parameters as $key => $value) {
            $message = str_replace(':' . $key, $value, $message);
        }
        return $message;
    }

    // Méthodes de validation
    private function validateRequired(string $field, $value): void
    {
        if ($value === null || $value === '' || $value === []) {
            throw new \Exception(self::MESSAGES['required']);
        }
    }

    private function validateEmail(string $field, $value): void
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception(self::MESSAGES['email']);
        }
    }

    private function validateMin(string $field, $value, array $parameters): void
    {
        $min = (int) $parameters[0];
        if (strlen($value) < $min) {
            throw new \Exception(self::MESSAGES['min']);
        }
    }

    private function validateMax(string $field, $value, array $parameters): void
    {
        $max = (int) $parameters[0];
        if (strlen($value) > $max) {
            throw new \Exception(self::MESSAGES['max']);
        }
    }

    private function validateUnique(string $field, $value, array $parameters): void
    {
        $table = $parameters[0];
        $column = $parameters[1] ?? $field;
        $except = $parameters[2] ?? null;

        $sql = "SELECT COUNT(*) as count FROM {$table} WHERE {$column} = :value";
        $params = ['value' => $value];

        if ($except) {
            $sql .= " AND id != :except";
            $params['except'] = $except;
        }

        $result = $this->db->fetchOne($sql, $params);
        if ($result['count'] > 0) {
            throw new \Exception(self::MESSAGES['unique']);
        }
    }

    private function validateExists(string $field, $value, array $parameters): void
    {
        $table = $parameters[0];
        $column = $parameters[1] ?? $field;

        $sql = "SELECT COUNT(*) as count FROM {$table} WHERE {$column} = :value";
        $result = $this->db->fetchOne($sql, ['value' => $value]);

        if ($result['count'] === 0) {
            throw new \Exception(self::MESSAGES['exists']);
        }
    }

    private function validateConfirmed(string $field, $value): void
    {
        $confirmation = $this->data[$field . '_confirmation'] ?? null;
        if ($value !== $confirmation) {
            throw new \Exception(self::MESSAGES['confirmed']);
        }
    }
} 