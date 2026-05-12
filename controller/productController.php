<?php
class ProdutoController
{
    private $repo;
    private $perPage = 10;

    public function __construct($pdo)
    {
        $this->repo = new ProdutoRepository($pdo);
    }

    public function index($page = 1)
    {
        $currentPage = max(1, (int) $page);
        $totalItems = $this->repo->countAll();
        $totalPages = max(1, (int) ceil($totalItems / $this->perPage));
        if ($currentPage > $totalPages) {
            $currentPage = $totalPages;
        }

        $offset = ($currentPage - 1) * $this->perPage;
        $produtos = $this->repo->getPaginated($this->perPage, $offset);
        $produto = null;
        include 'views/products.php';
    }

    public function edit($id, $page = 1)
    {
        $currentPage = max(1, (int) $page);
        $totalItems = $this->repo->countAll();
        $totalPages = max(1, (int) ceil($totalItems / $this->perPage));
        if ($currentPage > $totalPages) {
            $currentPage = $totalPages;
        }

        $offset = ($currentPage - 1) * $this->perPage;
        $produtos = $this->repo->getPaginated($this->perPage, $offset);
        $produto = $this->repo->getById((int) $id);
        if (!$produto) {
            die('Produto não encontrado.');
        }
        include 'views/products.php';
    }

    public function create()
    {
        if (empty($_POST['nome']) || empty($_POST['preco'])) {
            $this->handleError("Nome e preço são obrigatórios.");
        }

        $nome = trim($_POST['nome']);
        if (strlen($nome) < 3 || strlen($nome) > 100) {
            $this->handleError("Nome deve ter entre 3 e 100 caracteres.");
        }

        $preco = (float) str_replace(',', '.', $_POST['preco']);
        if ($preco <= 0) {
            $this->handleError("Preço deve ser maior que 0.");
        }

        $page = isset($_POST['page']) ? (int) $_POST['page'] : 1;

        try {
            $this->repo->create($nome, $preco);
            header("Location: index.php?page=" . max(1, $page));
            exit;
        } catch (Exception $e) {
            $this->handleError("Erro ao criar produto: " . $e->getMessage());
        }
    }

    public function delete($id)
    {
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $this->repo->delete((int) $id);
        header("Location: index.php?page=" . max(1, $page));
        exit;
    }

    public function update()
    {
        if (empty($_POST['id']) || empty($_POST['nome']) || !isset($_POST['preco'])) {
            $this->handleError("ID, nome e preço são obrigatórios.");
        }

        $id = (int) $_POST['id'];
        if ($id <= 0) {
            $this->handleError("ID inválido.");
        }

        $nome = trim($_POST['nome']);
        if (strlen($nome) < 3 || strlen($nome) > 100) {
            $this->handleError("Nome deve ter entre 3 e 100 caracteres.");
        }

        $preco = (float) str_replace(',', '.', $_POST['preco']);
        if ($preco <= 0) {
            $this->handleError("Preço deve ser maior que 0.");
        }

        $page = isset($_POST['page']) ? (int) $_POST['page'] : 1;

        try {
            $this->repo->update($id, $nome, $preco);
            header("Location: index.php?page=" . max(1, $page));
            exit;
        } catch (Exception $e) {
            $this->handleError("Erro ao atualizar produto: " . $e->getMessage());
        }
    }

    private function handleError($message)
    {
        header('Content-Type: text/html; charset=utf-8');
        http_response_code(400);
?>
        <!DOCTYPE html>
        <html lang="pt-BR">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Erro</title>
            <link rel="stylesheet" href="css/style.css">
            <style>
                .error-container {
                    max-width: 500px;
                    margin: 50px auto;
                    padding: 20px;
                    background: #ffe6e6;
                    border: 1px solid #ff4444;
                    border-radius: 5px;
                }

                .error-container h2 {
                    color: #cc0000;
                    margin-top: 0;
                }

                .error-container p {
                    margin: 0;
                    color: #333;
                }

                .error-container a {
                    display: inline-block;
                    margin-top: 15px;
                }
            </style>
        </head>

        <body>
            <div class="error-container">
                <h2>⚠️ Erro</h2>
                <p><?= htmlspecialchars($message) ?></p>
                <a href="index.php" class="btn-cancel">← Voltar</a>
            </div>
        </body>

        </html>
<?php
        exit;
    }
}
