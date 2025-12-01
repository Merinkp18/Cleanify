<?php
require 'auth_admin.php';
require '../db.php'; // naik 1 folder ke Backend lalu pakai db.php

$module = $_GET['module'] ?? '';
$action = $_GET['action'] ?? 'view';

switch ($module) {

    case 'customer':
        require __DIR__ . "/customer/$action.php";
        break;

    case 'layanan':
        require __DIR__ . "/layanan/$action.php";
        break;

    case 'order':
        require __DIR__ . "/order/$action.php";
        break;

    case 'pekerja':
        require __DIR__ . "/pekerja/$action.php";
        break;

    case 'jadwal':
        require __DIR__ . "/jadwal/$action.php";
        break;

    default:
        echo "404 Module not found.";
        exit;
}
