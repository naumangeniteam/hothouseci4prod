<div class="auth-wrapper">
    <div class="auth-content">
        <div class="card">
            <div class="row align-items-center text-center">
                <div class="col-md-12">
                    <div class="card-body">
                        <form name="passwordRecoverForm" id="passwordRecoverForm" class="form-vertical" action="<?= esc($BASE_URL) ?>hhjsitemgmt/update-password" method="post">
                            <input type="hidden" name="<?php echo csrf_token();?>" value="<?php csrf_hash();?>">
                            <img src="<?= esc($ASSET_INCLUDE_URL) ?>image/logo.png" alt="" class="img-fluid mb-4" style="width: 100px;">
                            <h4 class="mb-3 f-w-400">Set Password</h4>
                            <?php if($recovererror): ?>
                                <div class="form_heading alert alert-danger">
                                    <?=$recovererror?>
                                </div>
                            <?php elseif($recoversuccess): ?>
                                <div class="form_heading alert alert-success">
                                    <?=$recoversuccess?>
                                </div>
                            <?php elseif(session()->getFlashdata('alert_success')): ?>
                                <div class="form_heading alert alert-success">
                                    <?=session()->getFlashdata('alert_success')?>
                                </div>
                            <?php endif; ?>
                            
                            <input type="hidden" name="userEmail" id="userEmail" value="<?= esc($email, 'attr') ?>" >
                            <input type="hidden" name="token" id="token" value="<?= esc($token, 'attr') ?>" >
                            
                            <div class="form-group mb-3">
                                <label class="floating-label" for="userPassword">New Password</label>
                                <input type="password" name="userPassword" id="userPassword" class="form-control required" value="<?php if(old('userPassword') && !$recoversuccess): echo old('userPassword'); endif; ?>" placeholder="Password" autocomplete="off"/>
                                <?php if(session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('userPassword')): ?>
                                    <label for="userPassword" generated="true" class="error"><?php echo session()->getFlashdata('validation')->getError('userPassword'); ?></label>
                                <?php endif; ?>
                            </div>
                            <div class="form-group mb-3">
                                <label class="floating-label" for="userConfPassword">Confirm Password</label>
                                <input type="password" name="userConfPassword" id="userConfPassword" class="form-control required" value="<?php if(old('userConfPassword') && !$recoversuccess): echo old('userConfPassword'); endif; ?>" placeholder="Confirm Password" autocomplete="off"/>
                                <?php if(session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('userConfPassword')): ?>
                                    <label for="userConfPassword" generated="true" class="error"><?php echo session()->getFlashdata('validation')->getError('userConfPassword'); ?></label>
                                <?php endif; ?>
                            </div>
                            <input type="hidden" name="passwordRecoverFormSubmit" id="passwordRecoverFormSubmit" value="Yes">
                            <button class="btn btn-block btn-primary mb-4">Recover</button>
                            <p class="mb-2 text-muted"><a href="<?= esc($FULL_SITE_URL) ?>hhjsitemgmt/login" class="f-w-400 pull-left">Back to login</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>