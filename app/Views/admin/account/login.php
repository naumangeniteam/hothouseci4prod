<div class="auth-wrapper admin-login-block">
    <div class="auth-content">
        <div class="card">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-body">
                        <form name="loginform" id="loginform" class="form-vertical" action="<?= route_to('submit_login') ?>" method="post">
                        <input type="hidden" name="<?= esc($CSRF_API_KEY) ?>" value="<?= esc($CSRF_API_VALUE) ?>"><img src="<?= esc($ASSET_INCLUDE_URL) ?>image/logo1.png" alt="" class="img-fluid mb-4 d-block mx-auto">
                            <h4 class="mb-3 f-w-400">Signin</h4>
                            <?php if (session()->getFlashdata('alert_success')): ?>
                                <div class="alert alert-success">
                                    <?= session()->getFlashdata('alert_success') ?>
                                </div>
                            <?php endif; ?>

                            <?php if (session()->getFlashdata('alert_error')): ?>
                                <div class="alert alert-danger">
                                    <?= session()->getFlashdata('alert_error') ?>
                                </div>
                            <?php endif; ?>
                            <?php if($error): ?>
                                <div class="form_heading alert alert-danger">
                                    <?=$error?>
                                </div>
                            <?php endif; ?>
                            <div class="form-group mb-3">
                                <label class="floating-label text-left" for="userEmail">Email address</label>
                                <input type="text" name="userEmail" id="userEmail" class="form-control required email" value="<?= old('userEmail') ?>" placeholder="Email" autocomplete="off"/>
                                <?php if(session('errors.userEmail')): ?>
                                    <label for="userEmail" generated="true" class="error"><?php echo session('errors.userEmail') ?></label>
                                <?php endif; ?>
                            </div>
                            <div class="form-group mb-4">
                                <label class="floating-label text-left" for="userPassword">Password</label>
                                <input type="password" name="userPassword" id="userPassword" class="form-control required" value="<?= old('userPassword') ?>" placeholder="Password" autocomplete="off"/>
                                <?php if(session('errors.userPassword')): ?>
                                    <label for="userPassword" generated="true" class="error"><?php echo session('errors.userPassword'); ?></label>
                                <?php endif; ?>
                            </div>
                            <input type="hidden" name="loginFormSubmit" id="loginFormSubmit" value="Yes">
                            <button class="btn btn-block btn-primary mb-4">Signin</button>
                            <p class="mb-2 text-muted"><a href="<?= esc($BASE_URL).'hhjsitemgmt/' ?>forgot-password" class="f-w-400 pull-right">Forgot password?</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>