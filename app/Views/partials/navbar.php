<?php

if (!function_exists('esActivo')) {
    function esActivo(string $segmento): string {
        return str_contains(current_url(), $segmento) ? 'activo' : '';
    }
}
$esInicio = (service('uri')->getPath() === '/' || service('uri')->getPath() === '') ? 'activo' : '';
?>
<!– Remix Icons –>
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">

<style>
    .navbar {
        background: #1b5e20;
        color: white;
        padding: 0 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 58px;
        box-shadow: 0 2px 10px rgba(0,0,0,.2);
        position: sticky;
        top: 0;
        z-index: 100;
        font-family: 'Segoe UI', Arial, sans-serif;
    }
    .navbar-brand {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.2rem;
        font-weight: 700;
        color: white;
        text-decoration: none;
        letter-spacing: .5px;
    }
    .navbar-brand i {
        font-size: 1.4rem;
        color: #a5d6a7;
    }
    .navbar-links {
        display: flex;
        align-items: center;
        gap: 2px;
    }
    .navbar-links a {
        color: rgba(255,255,255,.8);
        text-decoration: none;
        padding: 7px 14px;
        border-radius: 7px;
        font-size: .86rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: background .2s, color .2s;
        white-space: nowrap;
    }
    .navbar-links a i {
        font-size: 1rem;
    }
    .navbar-links a:hover {
        background: rgba(255,255,255,.15);
        color: white;
    }
    .navbar-links a.activo {
        background: rgba(255,255,255,.2);
        color: white;
        font-weight: 600;
    }
</style>

<nav class="navbar">
    <a href="<?= base_url('/') ?>" class="navbar-brand">
        <i class="ri-leaf-line"></i> FRUVER
    </a>
    <div class="navbar-links">
        <a href="<?= base_url('/') ?>" class="<?= $esInicio ?>">
            <i class="ri-home-4-line"></i> Inicio
        </a>
        <a href="<?= base_url('productos') ?>" class="<?= esActivo('productos') ?>">
            <i class="ri-stack-line"></i> Productos
        </a>
        <a href="<?= base_url('clientes') ?>" class="<?= esActivo('clientes') ?>">
            <i class="ri-group-line"></i> Clientes
        </a>
        <a href="<?= base_url('repartidor') ?>" class="<?= esActivo('repartidor') ?>">
            <i class="ri-motorbike-line"></i> Repartidores
        </a>
        <a href="<?= base_url('pedidos') ?>" class="<?= esActivo('pedidos') ?>">
            <i class="ri-file-list-3-line"></i> Pedidos
        </a>
        <a href="<?= base_url('inventario/entradas') ?>" class="<?= esActivo('inventario') ?>">
            <i class="ri-archive-line"></i> Inventario
        </a>
    </div>
</nav>