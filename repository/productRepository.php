<?php
class ProdutoRepository {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        return $this->pdo->query("SELECT * FROM produtos")->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM produtos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    public function create($nome, $preco) {
        $stmt = $this->pdo->prepare("INSERT INTO produtos (nome, preco) VALUES (:nome, :preco)");
        $stmt->execute([':nome' => $nome, ':preco' => $preco]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $nome, $preco) {
        $stmt = $this->pdo->prepare("UPDATE produtos SET nome = :nome, preco = :preco WHERE id = :id");
        $stmt->execute([':nome' => $nome, ':preco' => $preco, ':id' => $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM produtos WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
}