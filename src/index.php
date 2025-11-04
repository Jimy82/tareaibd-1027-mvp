<?php require __DIR__.'/bootstrap.php'; ?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>MVP Formulario</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="/assets/bootstrap/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h1 class="h4 mb-3">Contacto</h1>
          <?php if (!empty($_GET['e'])): ?>
            <div class="alert alert-danger"><?php echo e($_GET['e']); ?></div>
          <?php endif; ?>
          <form method="post" action="/process.php" novalidate>
            <input type="hidden" name="csrf" value="<?php echo e(Csrf::token()); ?>">
            <div class="mb-3">
              <label class="form-label" for="name">Nombre</label>
              <input class="form-control" id="name" name="name" maxlength="80" required>
            </div>
            <div class="mb-3">
              <label class="form-label" for="email">Email</label>
              <input class="form-control" id="email" name="email" type="email" maxlength="255" required>
            </div>
            <div class="mb-3">
              <label class="form-label" for="message">Mensaje</label>
              <textarea class="form-control" id="message" name="message" rows="5" minlength="10" maxlength="2000" required></textarea>
            </div>
            <div class="d-none">
              <label for="website">Website</label>
              <input id="website" name="website" autocomplete="off">
            </div>
            <button class="btn btn-primary w-100" type="submit">Enviar</button>
          </form>
        </div>
      </div>

      <div class="mt-4">
        <h2 class="h6">Últimos envíos (paginado)</h2>
        <?php
          $repo = new Repository($pdo);
          $page = max(1, (int)($_GET['page'] ?? 1));
          $list = $repo->findPage($page, 5);
        ?>
        <ul class="list-group">
          <?php foreach ($list['data'] as $row): ?>
            <li class="list-group-item">
              <strong><?php echo e($row['name']); ?></strong>
              <div class="small text-muted"><?php echo e($row['email']); ?> · <?php echo e($row['created_at']); ?></div>
              <div><?php echo nl2br(e(mb_substr($row['message'],0,200))); ?></div>
            </li>
          <?php endforeach; ?>
        </ul>
        <?php if ($list['pages'] > 1): ?>
          <nav class="mt-2">
            <ul class="pagination pagination-sm">
              <?php for ($p=1; $p<=$list['pages']; $p++): ?>
                <li class="page-item <?php echo $p==$list['page']?'active':''; ?>">
                  <a class="page-link" href="?page=<?php echo $p; ?>"><?php echo $p; ?></a>
                </li>
              <?php endfor; ?>
            </ul>
          </nav>
        <?php endif; ?>
      </div>

    </div>
  </div>
</div>
<script src="/assets/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>
