<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Enchere::index');
$routes->get('Enchere/index', 'Enchere::index');

// Inscription
$routes->get('Enchere/inscription', 'Enchere::inscription');
$routes->post('Enchere/validerInscription', 'Enchere::validerInscription');

// Connexion / Déconnexion
$routes->get('Enchere/connexion', 'Enchere::connexion');
$routes->post('Enchere/connecter', 'Enchere::connecter');
$routes->get('Enchere/deconnexion', 'Enchere::deconnexion');

// Ventes
$routes->get('Enchere/listeVentes', 'Enchere::listeVentes');
$routes->get('Enchere/detailVente/(:num)', 'Enchere::detailVente/$1');
$routes->get('Enchere/creerVente', 'Enchere::creerVente');
$routes->post('Enchere/validerCreerVente', 'Enchere::validerCreerVente');
$routes->get('Enchere/inscrireVente/(:num)', 'Enchere::inscrireVente/$1');
$routes->get('Enchere/cloturerVente/(:num)', 'Enchere::cloturerVente/$1');
$routes->get('Enchere/qrcodeVente/(:num)', 'Enchere::qrcodeVente/$1');

// Articles
$routes->get('Enchere/listeArticles', 'Enchere::listeArticles');
$routes->get('Enchere/creerArticle', 'Enchere::creerArticle');
$routes->post('Enchere/validerCreerArticle', 'Enchere::validerCreerArticle');
$routes->post('Enchere/selectionnerArticle/(:num)', 'Enchere::selectionnerArticle/$1', ['filter' => 'role:benevole']);

// Enchères
$routes->post('Enchere/encherir/(:num)', 'Enchere::encherir/$1');
$routes->get('Enchere/annulerEnchere/(:num)', 'Enchere::annulerEnchere/$1');
$routes->get('Enchere/historiqueEncheres', 'Enchere::historiqueEncheres');

// Achats
$routes->get('Enchere/mesAchats', 'Enchere::mesAchats');
$routes->get('Enchere/confirmerAchat/(:num)', 'Enchere::confirmerAchat/$1');

// Profil
$routes->get('Enchere/profil', 'Enchere::profil');
$routes->post('Enchere/modifierProfil', 'Enchere::modifierProfil');

// Dashboard
$routes->get('Enchere/dashboard', 'Enchere::dashboard');
