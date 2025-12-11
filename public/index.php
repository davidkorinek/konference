<?php
/* debug */
ini_set('display_errors', 1);
error_reporting(E_ALL);


require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Helpers/security.php';

use Davca\Konference\Core\Router;

/* inicializace */
$router = new Router();

/* homepage */
$router->get('/', 'HomeController@index');

/* program */
$router->get('/program', 'HomeController@program');

/* registrace a login */
$router->get('/register', 'AuthController@showRegister');
$router->post('/register', 'AuthController@register');

$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');

$router->post('/clear-login-error', 'AuthController@clearLoginError');

/* author */
$router->get('/author/files', 'AuthorController@files');

$router->get('/author/files/new', 'AuthorController@newForm');
$router->post('/author/files/new', 'AuthorController@create');

$router->get('/author/files/edit', 'AuthorController@editForm');
$router->post('/author/files/edit', 'AuthorController@edit');

$router->get('/author/files/delete', 'AuthorController@delete');

/* reviewer */
$router->get('/reviewer/tasks', 'ReviewerController@tasks');
$router->get('/reviewer/review', 'ReviewerController@reviewForm');
$router->post('/reviewer/review', 'ReviewerController@submitReview');

/* admin */
$router->get('/admin/users', 'AdminController@users');
$router->post('/admin/users/update-all', 'AdminController@updateAllUsers');

$router->get('/admin', 'AdminController@files');
$router->get('/admin/files', 'AdminController@files');

$router->get('/admin/file/assign', 'AdminController@assignForm');
$router->post('/admin/file/assign', 'AdminController@assignSave');

$router->get('/admin/file/reviews', 'AdminController@reviewSummary');
$router->post('/admin/file/decision', 'AdminController@publishDecision');

$router->post('/admin/file/delete', 'AdminController@deleteFile');
$router->post('/admin/file/reset', 'AdminController@resetReview');

$router->get('/admin/articles', 'AdminController@articles');


/* start */
$router->run();
