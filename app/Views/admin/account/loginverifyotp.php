<div class="auth-wrapper admin-login-block">
    <div class="auth-content">
        <div class="card">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-body">
                        <form name="otpVerificationForm" id="otpVerificationForm" class="form-vertical" action="" method="post">
                            <input type="hidden" name="<?php echo csrf_token();?>" value="<?php csrf_hash();?>">
                            <img src="<?= esc($ASSET_INCLUDE_URL) ?>image/logo.png" alt="" class="img-fluid mb-4 d-block mx-auto">
                            <h4 class="mb-3 f-w-400 text-center">OTP Verification</h4>
                            <?php if($recovererror): ?>
                                <div class="form_heading alert alert-danger">
                                    <?=$recovererror?>
                                </div>
                            <?php elseif($this->session->getFlashdata('alert_success')): ?>
                                <div class="form_heading alert alert-success">
                                    <?=$this->session->getFlashdata('alert_success')?>
                                </div>
                            <?php endif; ?>
                            <div class="form-group mb-3">
                                <label class="floating-label" for="userOtp">OTP</label>
                                <input type="text" name="userOtp" id="userOtp" class="form-control required" value="<?php if(old('userOtp')): echo old('userOtp'); endif; ?>" placeholder="OTP" autocomplete="off"/>
                                <?php if(session()->getFlashdata('validation')->getError('userOtp')): ?>
                                    <label for="userOtp" generated="true" class="error"><?php echo session()->getFlashdata('validation')->getError('userOtp'); ?></label>
                                <?php endif; ?>
                            </div>
                            <input type="hidden" name="otpVerificationFormSubmit" id="otpVerificationFormSubmit" value="Yes">
                            <button class="btn btn-block btn-primary mb-4">Submit</button>
                            <p class="mb-2 text-muted"><a href="<?= esc($FULL_SITE_URL) ?>login" class="f-w-400 pull-left">Back to login</a>
                            <a href="<?= esc($FULL_SITE_URL) ?>resend-otp" class="f-w-400 pull-right">Resend OTP</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>