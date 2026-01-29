<header class="navbar pcoded-header navbar-expand-lg navbar-light header-blue">
  <div class="m-header">
    <a class="mobile-menu" id="mobile-collapse" href="javascript:void(0);"><span></span></a>
    <a href="{FULL_SITE_URL}" class="b-brand">
      <img src="{ASSET_INCLUDE_URL}image/logo1.png" alt="" class="logo" style="width:90%">
      <img src="{ASSET_INCLUDE_URL}image/logo1.png" alt="" class="logo-thumb">
    </a>
    <a href="javascript:void(0);" class="mob-toggler">
      <i class="feather icon-more-vertical"></i>
    </a>
  </div>
  <div class="collapse navbar-collapse">
    <ul class="navbar-nav ml-auto">
      <li class="navigation-name">Welcome <?=sessionData('user_name')?></li>
      <li style="margin-left: 5px;"><a href="<?= $BASE_URL?>user/logout"><i class="feather icon-log-out"></i></a></li>
    </ul>
  </div>
</header>