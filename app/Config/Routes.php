<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\Pages;
use App\Controllers\Usuarios;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

/*Login*/
    $routes->get('login','Login::index');
    $routes->post('logged','Login::login');
    $routes->post('iniciar','Login::IniciarSesion');
    $routes->get('logout','Login::logOut');
    $routes->get('listado', 'Usuarios::listarUsuarios');
    $routes->get('listadoId', 'Usuarios::ListarId');
    $routes->post('token', 'Usuarios::validarToken');
    
    /*User*/
    $routes->group('admin', ['filter' => 'NoauthFilter'], function($routes){
    $routes->get('usuario', 'Usuarios::index');
    $routes->get('eliminar', 'Usuarios::eliminar', ['filter' => 'Filter']);
    $routes->get('ObtenerId', 'Usuarios::ObtenerId');
    $routes->post('crear', 'Usuarios::crear', ['filter' => 'Filter']);
    $routes->post('actualizar', 'Usuarios::actualizar', ['filter' => 'Filter']);
});


