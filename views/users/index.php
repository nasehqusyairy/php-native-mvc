<?php
$successMessage = flash('success');
if ($successMessage): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $successMessage ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="d-flex gap-3 mb-3">
    <a href="/users/create" class="btn btn-primary"><i class="bi bi-plus"></i></a>
    <form method="get" class="input-group">
        <input type="text" class="form-control" name="q" placeholder="Search..." value="<?= isset($_GET['q']) ? $_GET['q'] : '' ?>">
        <button class="btn btn-primary"><i class="bi bi-search"></i></button>
    </form>
</div>

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="responsive-table">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $index => $user): ?>
                                <tr>
                                    <td><?= ($index + 1) + (($page - 1) * $limit) ?></td>
                                    <td><?= $user->name ?></td>
                                    <td><?= $user->email ?></td>
                                    <td>
                                        <a href="/users/<?= $user->id ?>" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="/users/<?= $user->id ?>/delete"
                                            onclick="return confirm('Are you sure?')"
                                            class="btn btn-sm btn-danger">
                                            <i class="bi bi-x"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($totalPages > 1): ?>
                    <nav class="d-flex justify-content-end">
                        <ul class="pagination">
                            <!-- Prev -->
                            <li class="page-item <?= !$hasPrevPage ? 'disabled' : '' ?>">
                                <a class="page-link"
                                    href="<?= $hasPrevPage ? '/users?page=' . ($page - 1) . (isset($q) ? '&q=' . urlencode($q) : '') : '#' ?>">
                                    Prev
                                </a>
                            </li>

                            <!-- Numbered pages -->
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                                    <a class="page-link"
                                        href="/users?page=<?= $i ?><?= isset($q) ? '&q=' . urlencode($q) : '' ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <!-- Next -->
                            <li class="page-item <?= !$hasNextPage ? 'disabled' : '' ?>">
                                <a class="page-link"
                                    href="<?= $hasNextPage ? '/users?page=' . ($page + 1) . (isset($q) ? '&q=' . urlencode($q) : '') : '#' ?>">
                                    Next
                                </a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>


            </div>
        </div>
    </div>
</div>