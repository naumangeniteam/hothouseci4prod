<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/dropzone/dist/dropzone.css" />
<link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet" />
<script src="https://unpkg.com/dropzone"></script>
<script src="https://unpkg.com/cropperjs"></script>
<style>
  .image_area {
    position: relative;
  }

  img {
    display: block;
    max-width: 100%;
  }

  .preview {
    overflow: hidden;
    width: 160px;
    height: 160px;
    margin: 10px;
    border: 1px solid red;
  }

  .modal-lg {
    max-width: 1000px !important;
  }

  .overlay {
    position: absolute;
    bottom: 10px;
    left: 0;
    right: 0;
    background-color: rgba(255, 255, 255, 0.5);
    overflow: hidden;
    height: 0;
    transition: .5s ease;
    width: 100%;
  }

  .image_area:hover .overlay {

    cursor: pointer;
  }

  .text {
    color: #333;
    font-size: 20px;
    position: absolute;
    top: 50%;
    left: 50%;
    -webkit-transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
    text-align: center;
  }

  .preview-container {
    min-height: 400px;
  }


  .fileUpload {
    position: relative;
    overflow: hidden;
  }

  .fileUpload input.upload {
    position: absolute;
    top: 0;
    right: 0;
    margin: 0;

    padding: 0;
    font-size: 20px;
    cursor: pointer;
    opacity: 0;
    filter: alpha(opacity=0);
  }

  .btn--browse {
    border: 1px solid gray;
    border-left: 0;
    border-radius: 0 2px 2px 0;
    background-color: #ccc;
    color: black;
    /* margin-left: 50%; */
    height: 40px;
    padding: 7px 32px;
    margin-top: 28px;
  }

  .f-input {
    height: 42px;
    background-color: white;
    border: 1px solid gray;
    width: 100%;
    max-width: 400px;
    float: left;
    padding: 0 14px;
  }

  .text {
    color: #333;
    font-size: 20px;
    /* position: absolute; */
    top: 50%;
    left: 50%;
    -webkit-transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
    text-align: center;
  }


  html {
    margin: 50px;
  }

  .hide {
    display: none;
  }

  .show {
    display: block;
  }
</style>



<div class="pcoded-main-container">
  <div class="pcoded-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <div class="page-header-title">
              <?php /* ?><h5 class="m-b-10">Welcome <?=sessionData('ILCADM_ADMIN_FIRST_NAME')?></h5><?php */ ?>
            </div>
            <!-- <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo correctLink('webinarILCADMData', getCurrentControllerPath('index')); ?>">Import Details List</a></li>
                            <li class="breadcrumb-item"><a href="javascript:void(0);"><?= $EDITDATA ? 'Edit' : 'Add' ?>Import Details Content</a></li>
                        </ul> -->
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
            <h5><?= $EDITDATA ? 'Edit' : 'Add' ?> Content</h5>
          </div>
          <div class="card-body">
            <div class="basic-login-inner">
              <form id="currentPageFormSubadmin" name="currentPageFormSubadmin" class="form-auth-small" method="post" action="" enctype="multipart/form-data" autocomplete="off">
                <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <a href="index" style="margin-left: 12px;" class="btn btn-sm btn-primary pull-right">Back</a>
                    <button class="btn btn-primary  btn-sm pull-right">Submit</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="index" style="margin-right: 11px;" class="btn btn-danger has-ripple btn-sm pull-right">Cancel</a>

                    <input type="hidden" name="CurrentFieldForUnique" id="CurrentFieldForUnique" value="id" />
                    <input type="hidden" name="CurrentIdForUnique" id="CurrentIdForUnique" value="<?= $EDITDATA['id'] ?>" />
                    <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?= $EDITDATA['id'] ?>" />
                    <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
                  </div>
                </div>

                <div class="row">
                  <div class="form-group-inner col-lg-6 col-md-3 col-sm-3 col-xs-12">
                    <div class="animation">
                      <label>Import File<span class="required"></span></label><br>
                      <input id="uploadFile" name="import_file" class="f-input" value="" placeholder="upload here..." />
                    </div>
                  </div>

                  <div class="col-lg-4 px-0">
                    <div class="fileUpload btn btn--browse">
                      <span>Browse</span>
                      <input id="uploadBtn" type="file" name="import_file" class="upload" />
                    </div>
                  </div>
                </div>
            </div>
            <div class="row">
              <div class="login-btn-inner col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="inline-remember-me mt-4">
                  <input type="hidden" name="SaveChanges" id="SaveChanges" value="Yes">
                  <span class="tools pull-right">Note:- <strong><span style="color:#FF0000;">*</span> Indicates Required Fields</strong> </span>
                </div>
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




<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  document.getElementById("uploadBtn").onchange = function() {
    document.getElementById("uploadFile").value = this.value.replace("C:\\fakepath\\", "");
  };
</script>