<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// HOME
$routes->get('/', 'Home::index');

// PRODUCTOS
// PRODUCTOS — Endpoint AJAX búsqueda
$routes->get('productos/buscar', 'Productos::buscar');
$routes->get('productos',                     'Productos::index');
$routes->get('productos/crear',               'Productos::create');
$routes->post('productos/guardar',            'Productos::store');
$routes->get('productos/editar/(:num)',       'Productos::edit/$1');
$routes->post('productos/actualizar/(:num)',  'Productos::update/$1');
$routes->get('productos/eliminar/(:num)',     'Productos::delete/$1');

// CLIENTES
$routes->get('clientes',                     'Clientes::index');
$routes->get('clientes/crear',               'Clientes::create');
$routes->post('clientes/guardar',            'Clientes::store');
$routes->get('clientes/editar/(:num)',       'Clientes::edit/$1');
$routes->post('clientes/actualizar/(:num)', 'Clientes::update/$1');
$routes->get('clientes/eliminar/(:num)',     'Clientes::delete/$1');

// REPARTIDORES
$routes->get('repartidor',                     'Repartidor::index');
$routes->get('repartidor/crear',               'Repartidor::create');
$routes->post('repartidor/guardar',            'Repartidor::store');
$routes->get('repartidor/editar/(:num)',       'Repartidor::edit/$1');
$routes->post('repartidor/actualizar/(:num)', 'Repartidor::update/$1');
$routes->get('repartidor/eliminar/(:num)',     'Repartidor::delete/$1');

// PEDIDOS
$routes->get('pedidos',                        'Pedidos::index');
$routes->get('pedidos/crear',                  'Pedidos::create');
$routes->post('pedidos/guardar',               'Pedidos::store');
$routes->get('pedidos/ver/(:num)',             'Pedidos::show/$1');
$routes->post('pedidos/cambiar-status/(:num)', 'Pedidos::cambiarStatus/$1');
$routes->get('pedidos/eliminar/(:num)',        'Pedidos::delete/$1');

// INVENTARIO — ENTRADAS
$routes->get('inventario/entradas',                    'Inventario::entradas');
$routes->get('inventario/entradas/crear',              'Inventario::crearEntrada');
$routes->post('inventario/entradas/guardar',           'Inventario::storeEntrada');
$routes->get('inventario/entradas/editar/(:num)',      'Inventario::editarEntrada/$1');
$routes->post('inventario/entradas/actualizar/(:num)', 'Inventario::updateEntrada/$1');
$routes->get('inventario/entradas/eliminar/(:num)',    'Inventario::deleteEntrada/$1');

// INVENTARIO — EXISTENCIAS
$routes->get('inventario/existencias', 'Inventario::existencias');

// INVENTARIO — MERMAS
$routes->get('inventario/mermas',               'Inventario::mermas');
$routes->get('inventario/mermas/crear',         'Inventario::crearMerma');
$routes->post('inventario/mermas/guardar',      'Inventario::storeMerma');
$routes->get('inventario/mermas/eliminar/(:num)', 'Inventario::deleteMerma/$1');