<?php

// Configuration de l'encodage
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
// Supprimons mb_http_input car il nécessite un type spécifique
mb_regex_encoding('UTF-8');
setlocale(LC_ALL, 'fr_FR.UTF-8');
ini_set('default_charset', 'UTF-8');

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};