<div class="auth-wrapper admin-login-block">
    <div class="auth-content">
        <div class="card">
            <div class="row ">
                <div class="col-md-12">
                    <div class="card-body">
                        <form name="recoverform" id="recoverform" class="form-vertical" action="<?= esc($FULL_SITE_URL) ?>hhjsitemgmt/forgot-password" method="post">
                            <input type="hidden" name="<?php echo csrf_token();?>" value="<?php csrf_hash();?>">
                            <img src="<?= esc($ASSET_INCLUDE_URL) ?>image/logo1.png" alt="" class="img-fluid mb-4 d-block mx-auto" style="width: 100px;">
                            <h6 class="mb-3 f-w-400" style="color:#fff;">Enter Your Mobile Number Below And We Will Send You OTP To Recover a Password.</h6>
                            <?php if($forgoterror): ?>
                                <div class="form_heading alert alert-danger">
                                    <?=$forgoterror?>
                                </div>
                            <?php elseif($forgotsuccess): ?>
                                <div class="form_heading alert alert-success">
                                    <?=$forgotsuccess?>
                                </div>
                            <?php endif; ?>
                            <!-- <div class="form-group mb-3">
                                <label class="floating-label" for="forgotMobile">Mobile</label>
                                <input type="text" name="forgotMobile" id="forgotMobile" class="form-control required" value="<?php if(old('forgotMobile') && $forgotsuccess ==''): echo old('forgotMobile'); endif; ?>" placeholder="Mobile" autocomplete="off"/>
                                <?php if(session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('forgotMobile')): ?>
                                    <label for="forgotMobile" generated="true" class="error"><?php echo session()->getFlashdata('validation')->getError('forgotMobile'); ?></label>
                                <?php endif; ?>
                            </div> -->
                            <div class="form-group mb-3">
                                <label class="floating-label" for="forgotEmail">Email</label>
                                <input type="text" name="forgotEmail" id="forgotEmail" class="form-control required" value="<?php if(old('forgotEmail') && $forgotsuccess ==''): echo old('forgotEmail'); endif; ?>" placeholder="Email" autocomplete="off"/>
                                <?php if(session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('forgotMobile')): ?>
                                    <label for="forgotEmail" generated="true" class="error"><?php echo session()->getFlashdata('validation')->getError('forgotEmail'); ?></label>
                                <?php endif; ?>
                            </div>
                            <input type="hidden" name="recoverformSubmit" id="recoverformSubmit" value="Yes">
                            <button class="btn btn-block btn-primary mb-4">Send</button>
                            <p class="mb-2 text-muted"><a href="<?= esc($FULL_SITE_URL) ?>hhjsitemgmt/login" class="f-w-400 pull-left">Back to login</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>