<div class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <?php /* ?><h5 class="m-b-10">Welcome <?=sessionData('CMPOP_ADMIN_FIRST_NAME')?></h5><?php */ ?>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= esc($FULL_SITE_URL) ?>profile">Profile Details</a></li>
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Change Password - Verify OTP</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <!-- [ Main Content ] start -->
        <div class="row">
          <div class="col-sm-12">
            <div class="card">
              <div class="card-header">
                <h5>Change Password - Verify OTP</h5>
                <a href="<?= esc($FULL_SITE_URL) ?>profile" class="btn btn-sm btn-primary pull-right">Back</a>
              </div>
              <div class="card-body">
                <div class="basic-login-inner">
                  <form id="changePasswordForm" name="changePasswordForm" class="form-auth-small" method="post" action="">
                    <input type="hidden" name="CurrentFieldForUnique" id="CurrentFieldForUnique" value="admin_id"/>
                    <input type="hidden" name="CurrentIdForUnique" id="CurrentIdForUnique" value="<?=$EDITDATA['admin_id']?>"/>
                    <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['admin_id']?>"/>
                    <input type="hidden" name="<?php echo csrf_token();?>" value="<?php csrf_hash();?>">
                    <div class="row">
                      <div class="form-group-inner col-lg-6 col-md-6 col-sm-6 col-xs-12 <?php if(session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('admin_title')): ?>error<?php endif; ?>">
                        <label>OTP<span class="required">*</span></label>
                        <input type="password" name="userOtp" id="userOtp" value="<?php if(old('userOtp')): echo old('userOtp'); endif; ?>" class="form-control required number" placeholder="OTP">
                        <?php if(session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('userOtp')): ?>
                          <span for="userOtp" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('userOtp'); ?></span>
                        <?php endif; ?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="login-btn-inner col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="inline-remember-me mt-4">
                          <input type="hidden" name="SaveChanges" id="SaveChanges" value="Yes">
                          <button class="btn btn-primary mb-4">Submit</button>
                          <a href="<?= esc($FULL_SITE_URL) ?>profile" class="btn btn-danger has-ripple mb-4">Cancel</a>
                          <span class="tools pull-right">Note:- <strong><span style="color:#FF0000;">*</span> Indicates Required Fields</strong> </span> 
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>
</div>