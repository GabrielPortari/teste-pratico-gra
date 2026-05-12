<?php
class ProdutoController {
    private $repo;

    public function __construct($pdo) {
        $this->repo = new ProdutoRepository($pdo);
    }

    public function index() {
        $produtos = $this->repo->getAll();
        $produto = null;
        include 'views/products.php';
    }

    public function edit($id) {
        $produtos = $this->repo->getAll();
        $produto = $this->repo->getById((int) $id);
        if (!$produto) {
            die('Produto não encontrado.');
        }
        include 'views/products.php';
    }

    public function create() {
        if (empty($_POST['nome']) || empty($_POST['preco'])) {
            die("Nome e preço são obrigatórios.");
        }

        $nome = trim($_POST['nome']);
        $preco = (float) str_replace(',', '.', $_POST['preco']);

        $this->repo->create($nome, $preco);
        header("Location: index.php");
        exit;
    }

    public function delete($id) {
        $this->repo->delete((int) $id);
        header("Location: index.php");
        exit;
    }

    public function update() {
        if (empty($_POST['id']) || empty($_POST['nome']) || !isset($_POST['preco'])) {
            die("ID, nome e preço são obrigatórios.");
        }

        $id = (int) $_POST['id'];
        $nome = trim($_POST['nome']);
        $preco = (float) str_replace(',', '.', $_POST['preco']);

        $this->repo->update($id, $nome, $preco);
        header("Location: index.php");
        exit;
    }
}