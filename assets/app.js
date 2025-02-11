/*
 * Welcome to your app's main JavaScript file!
 */

// Import jQuery et ses plugins
import $ from 'jquery';
global.$ = global.jQuery = $;

// Import des autres bibliothèques
import 'jquery-ui-dist/jquery-ui.min';
import 'select2';
import axios from 'axios';

// Import des styles
import './styles/app.scss';
import 'jquery-ui-dist/jquery-ui.min.css';
import 'select2/dist/css/select2.min.css';
import '@fortawesome/fontawesome-free/css/all.min.css';

// Import Bootstrap
import 'bootstrap';

// Start the Stimulus application
// import './bootstrap';  // Commenté temporairement

// Import flash messages component
import './js/components/flash-messages';

// Import navigation component
import './js/navigation';

// Vous pouvez ajouter votre code JavaScript personnalisé ici 