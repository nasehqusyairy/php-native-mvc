<div class="row justify-content-center">
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Login</h4>
                <?php
                $dangerMessage = flash('danger');
                if ($dangerMessage): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $dangerMessage ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <form action="/auth/login" method="POST">
                    <div class="mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>