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
    <?php if (isset($produto) && $produto): ?>
      <form action="index.php?action=update" method="POST" class="product-form">
        <h2>Editar Produto</h2>
        <input type="hidden" name="id" value="<?= $produto['id'] ?>">
        <div class="form-group">
          <label for="nome">Nome:</label>
          <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required>
        </div>
        <div class="form-group">
          <label for="preco">Preço:</label>
          <input type="text" id="preco" name="preco" value="<?= number_format((float) $produto['preco'], 2, ',', '.') ?>" inputmode="decimal" placeholder="0,00" required>
        </div>
        <button type="submit">Atualizar Produto</button>
        <a href="index.php" class="btn-cancel">Cancelar</a>
      </form>
    <?php else: ?>
      <form action="index.php?action=create" method="POST" class="product-form">
        <h2>Adicionar Novo Produto</h2>
        <div class="form-group">
          <label for="nome">Nome:</label>
          <input type="text" id="nome" name="nome" required>
        </div>
        <div class="form-group">
          <label for="preco">Preço:</label>
          <input type="text" id="preco" name="preco" inputmode="decimal" placeholder="0,00" required>
        </div>
        <button type="submit">Adicionar Produto</button>
      </form>
    <?php endif; ?>

    <h2>Lista de Produtos</h2>
    <?php if (empty($produtos)): ?>
      <div class="empty-message">
        <p>Nenhum produto cadastrado.</p>
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
                <a href="index.php?action=edit&id=<?= $p['id'] ?>">Editar</a>
                <a href="index.php?action=delete&id=<?= $p['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
  </div>

  <script>
    const precoInput = document.getElementById('preco');

    if (precoInput) {
      precoInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');

        if (value.length > 0) {
          value = (parseInt(value) / 100).toFixed(2).replace('.', ',');
          e.target.value = value;
        }
      });

      const forms = document.querySelectorAll('.product-form');
      forms.forEach(form => {
        form.addEventListener('submit', function(e) {
          if (precoInput.value) {
            precoInput.value = precoInput.value.replace(',', '.');
          }
        });
      });
    }
  </script>
</body>

</html>