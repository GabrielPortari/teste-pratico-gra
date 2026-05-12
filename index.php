<?php
require_once 'config/db.php';
require_once 'repository/productRepository.php';
require_once 'controller/productController.php';

$controller = new ProdutoController($pdo);
$action = $_GET['action'] ?? 'index';

if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
	$controller->create();
	exit;
}

if ($action === 'edit' && isset($_GET['id'])) {
	$controller->edit($_GET['id']);
	exit;
}

if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
	$controller->update();
	exit;
}

if ($action === 'delete' && isset($_GET['id'])) {
	$controller->delete((int) $_GET['id']);
	exit;
}

$controller->index();