<?php
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
$produtos = $produtos ?? [];
$produto = $produto ?? null;
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Produtos</title>
  <link rel="stylesheet" href="../css/style.css">
</head>

<body>
  <header>
    <div class="container">
      <h1>Produtos</h1>
    </div>
  </header>

  <div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
      <div class="search-bar">
        <div style="position: relative; flex: 1;">
          <input type="text" id="busca" placeholder="Buscar por nome...">
          <span id="limpar" class="search-clear" onclick="limparBusca()" style="display: none;">✕</span>
        </div>
        <button onclick="filtrar()">Buscar</button>
      </div>
      <button onclick="abrirModal()" class="btn-success">Novo Produto</button>
    </div>

    <h2>Lista de Produtos</h2>
    <?php if (empty($produtos)): ?>
      <div class="empty-message">
        <p>Nenhum produto encontrado.</p>
      </div>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Nome</th>
            <th scope="col">Preço</th>
            <th scope="col">Criado em</th>
            <th scope="col">Atualizado em</th>
            <th scope="col">Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($produtos as $p): ?>
            <tr>
              <td><?= htmlspecialchars($p['id']) ?></td>
              <td><?= htmlspecialchars($p['nome']) ?></td>
              <td>R$ <?= number_format((float) $p['preco'], 2, ',', '.') ?></td>
              <td><?= date('d/m/Y H:i', strtotime($p['created_at'])) ?></td>
              <td><?= date('d/m/Y H:i', strtotime($p['updated_at'])) ?></td>
              <td>
                <a href="#" onclick="abrirModalEditar(<?= $p['id'] ?>, '<?= htmlspecialchars($p['nome']) ?>', <?= htmlspecialchars($p['preco']) ?>)">Editar</a>
                <a href="#" class="delete-link" onclick="confirmDelete(<?= $p['id'] ?>)">Deletar</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <?php if ($totalPages > 1): ?>
        <nav class="pagination" aria-label="Paginação de produtos">
          <?php if ($currentPage > 1): ?>
            <a href="index.php?page=<?= $currentPage - 1 ?>">Anterior</a>
          <?php endif; ?>

          <?php
          if ($totalPages <= 7) {
            for ($i = 1; $i <= $totalPages; $i++) {
              echo '<a href="index.php?page=' . $i . '" class="' . ($i === $currentPage ? 'active' : '') . '">' . $i . '</a>';
            }
          } else {
            echo '<a href="index.php?page=1" class="' . (1 === $currentPage ? 'active' : '') . '">1</a>';

            $start = max(2, $currentPage - 1);
            $end = min($totalPages - 1, $currentPage + 1);

            if ($currentPage <= 3) {
              $start = 2;
              $end = 4;
            }

            if ($currentPage >= $totalPages - 2) {
              $start = $totalPages - 3;
              $end = $totalPages - 1;
            }

            if ($start > 2) {
              echo '<span class="pagination-ellipsis">...</span>';
            }

            for ($i = $start; $i <= $end; $i++) {
              echo '<a href="index.php?page=' . $i . '" class="' . ($i === $currentPage ? 'active' : '') . '">' . $i . '</a>';
            }

            if ($end < $totalPages - 1) {
              echo '<span class="pagination-ellipsis">...</span>';
            }

            echo '<a href="index.php?page=' . $totalPages . '" class="' . ($totalPages === $currentPage ? 'active' : '') . '">' . $totalPages . '</a>';
          }
          ?>

          <?php if ($currentPage < $totalPages): ?>
            <a href="index.php?page=<?= $currentPage + 1 ?>">Próxima</a>
          <?php endif; ?>
        </nav>
      <?php endif; ?>
    <?php endif; ?>

    <div style="margin-top: 30px;"></div>
    <div id="formModal" class="modal">
      <div class="modal-content">
        <h2 id="modalTitle">Novo Produto</h2>
        <form id="produtoForm" method="POST" action="index.php?action=create">
          <input type="hidden" id="produtoId" name="id">
          <input type="hidden" id="page" name="page" value="<?= $currentPage ?>">

          <div>
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" required>
          </div>

          <div>
            <label for="preco">Preço</label>
            <input type="text" id="preco" name="preco" placeholder="0,00" inputmode="numeric" required>
          </div>

          <div class="modal-buttons">
            <button type="button" class="btn-cancel" onclick="fecharModal()">Cancelar</button>
            <button type="submit" class="btn-save">Salvar</button>
          </div>
        </form>
      </div>
    </div>

    <div id="deleteModal" class="modal">
      <div class="modal-content">
        <h2>Confirmar Exclusão</h2>
        <p>Tem certeza que deseja excluir este produto?</p>
        <div class="modal-buttons">
          <button class="btn-cancel" onclick="fecharDeleteModal()">Cancelar</button>
          <a id="modal-confirm" href="#" class="btn-save">Confirmar</a>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('preco').addEventListener('input', function(e) {
      let value = e.target.value.replace(/\D/g, '');
      value = (parseInt(value) / 100).toFixed(2);
      e.target.value = isNaN(value) ? '0,00' : value.replace('.', ',');
    });

    function abrirModal() {
      document.getElementById('modalTitle').textContent = 'Novo Produto';
      document.getElementById('produtoForm').action = 'index.php?action=create';
      document.getElementById('produtoForm').method = 'POST';
      document.getElementById('produtoId').value = '';
      document.getElementById('nome').value = '';
      document.getElementById('preco').value = '0,00';
      document.getElementById('formModal').classList.add('show');
    }

    function abrirModalEditar(id, nome, preco) {
      document.getElementById('modalTitle').textContent = 'Editar Produto';
      document.getElementById('produtoForm').action = 'index.php?action=update';
      document.getElementById('produtoForm').method = 'POST';
      document.getElementById('produtoId').value = id;
      document.getElementById('nome').value = nome;
      document.getElementById('preco').value = preco.toString().replace('.', ',');
      document.getElementById('formModal').classList.add('show');
    }

    function fecharModal() {
      document.getElementById('formModal').classList.remove('show');
    }

    document.getElementById('formModal').addEventListener('click', function(e) {
      if (e.target === this) fecharModal();
    });

    function confirmDelete(id) {
      document.getElementById('modal-confirm').href = 'index.php?action=delete&id=' + id + '&page=<?= $currentPage ?>';
      document.getElementById('deleteModal').classList.add('show');
    }

    function fecharDeleteModal() {
      document.getElementById('deleteModal').classList.remove('show');
    }

    document.getElementById('deleteModal').addEventListener('click', function(e) {
      if (e.target === this) fecharDeleteModal();
    });

    function filtrar() {
      const termo = document.getElementById('busca').value.toLowerCase();
      const limpar = document.getElementById('limpar');
      const linhas = document.querySelectorAll('tbody tr');

      limpar.style.display = termo ? 'block' : 'none';

      linhas.forEach(function(linha) {
        const nome = linha.querySelector('td:nth-child(2)').textContent.toLowerCase();
        linha.style.display = nome.includes(termo) ? '' : 'none';
      });
    }

    function limparBusca() {
      document.getElementById('busca').value = '';
      document.getElementById('limpar').style.display = 'none';
      document.querySelectorAll('tbody tr').forEach(function(linha) {
        linha.style.display = '';
      });
    }

    document.getElementById('busca').addEventListener('keydown', function(e) {
      if (e.key === 'Enter') filtrar();
    });

    <?php if ($produto): ?>
      abrirModalEditar(<?= $produto['id'] ?>, '<?= htmlspecialchars($produto['nome']) ?>', <?= htmlspecialchars($produto['preco']) ?>);
    <?php endif; ?>
  </script>
</body>

</html>