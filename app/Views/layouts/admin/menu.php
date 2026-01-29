<?php
        $adminModel = new \App\Models\AdminModel();
        ?>
        <nav class="pcoded-navbar menu-light">
  <div class="navbar-wrapper  ">
    <div class="navbar-content scroll-div ">
      <div class="">
        <div class="main-menu-header">
          <canvas id="clockCanvas" width="100" height="100"style="background-color:#fff;display:none"></canvas>
          <div id="clockText" style="color:#000;display:none"></div>
        </div>
        <div class="collapse" id="nav-user-link">
          <ul class="list-inline">
            <li class="list-inline-item"><a href="<?= esc($FULL_SITE_URL) ?>profile" data-toggle="tooltip" title="View Profile"><i class="feather icon-user"></i></a></li>
            <li class="list-inline-item"><a href="<?= esc($FULL_SITE_URL) ?>logout" data-toggle="tooltip" title="Logout" class="text-danger"><i class="feather icon-power"></i></a></li>
          </ul>
        </div>
      </div>


      <ul class="nav pcoded-inner-navbar ">
        <?php $admin_role = session()->get('ILCADM_ADMIN_ROLE');
        $admin_role_id = session()->get('ILCADM_ADMIN_ROLE_ID'); ?>
        <?php if ($admin_role == 2) {
          $userId = session()->get('ILCADM_ADMIN_ID');
          
          $permissionData = $adminModel->getAllPermissionData($admin_role_id, 'maindashboard');
          // echo"<pre>";print_r($permissionData);die;
        ?>
          <?php if (!empty($permissionData)) { ?>
            <li class="nav-item">
              <a href="<?= esc($FULL_SITE_URL) ?>maindashboard" class="nav-link ">
                <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                <span class="pcoded-mtext">Dashboard</span>
              </a>
            </li>
          <?php   }
        } else { ?>
          <li class="nav-item">
            <a href="<?= esc($FULL_SITE_URL) ?>maindashboard" class="nav-link ">
              <span class="pcoded-micon"><i class="feather icon-home"></i></span>
              <span class="pcoded-mtext">Dashboard</span>
            </a>
          </li>
        <?php } ?>

        <?php if (session()->get('ILCADM_ADMIN_TYPE') == 'Super Admin') : ?>
          <?php /* <li class="nav-item pcoded-hasmenu <?php if (strtolower($activeMenu) == 'subadmin') : ?> active pcoded-trigger<?php endif; ?>">
            <a href="javascript:void(0);" class="nav-link "><span class="pcoded-micon"><i class="fa fa-user"></i></span><span class="pcoded-mtext">Sub-admin</span></a>
            <ul class="pcoded-submenu">
              <li <?php if (strtolower($activeSubMenu) == 'department') : ?>class="active"<?php endif; ?>><a href="<?= esc($FULL_SITE_URL) ?>department/index">Department list</a></li>
              <li <?php if (strtolower($activeSubMenu) == 'designation') : ?>class="active"<?php endif; ?>><a href="<?= esc($FULL_SITE_URL) ?>designation/index">Designation list</a></li>
              <li <?php if (strtolower($activeSubMenu) == 'subadmin') : ?>class="active"<?php endif; ?>><a href="<?= esc($FULL_SITE_URL) ?>subadmin/index">Sub-admin list</a></li>
            </ul>
        </li>*/ ?>
        <?php endif; ?>
        <?php
        $APURL = $adminModel->getMenuModule();
        $admin_role = session()->get('ILCADM_ADMIN_ROLE');
        $admin_role_id = session()->get('ILCADM_ADMIN_ROLE_ID');
        // echo"<pre>";print_r($admin_role);die;
        if ($APURL <> "") : foreach ($APURL as $APURLinfo) :
            $mchilddata = $adminModel->getMenuChildModule($APURLinfo['module_id']);
            if ($admin_role == 2) {
              $userId = session()->get('ILCADM_ADMIN_ID');
              $permissionData = $adminModel->getAllPermissionData($admin_role_id, $APURLinfo['module_name']);
              if (!empty($permissionData)) {
                if ($mchilddata) : ?>
                  <li class="nav-item pcoded-hasmenu <?php if (strtolower($activeMenu) == strtolower(stripslashes($APURLinfo['module_name']))) : ?> active pcoded-trigger<?php endif; ?>">
                    <a href="javascript:void(0);" class="nav-link "><span class="pcoded-micon"><?php echo stripslashes($APURLinfo['module_icone']); ?></span><span class="pcoded-mtext"><?php echo stripslashes($APURLinfo['module_display_name']); ?></span></a>
                    <ul class="pcoded-submenu">
                      <?php foreach ($mchilddata as $MCDinfo) :  ?>
                        <?php /* ?><li <?php if(strtolower($activeSubMenu) == strtolower(stripslashes($MCDinfo['module_name']))):?>class="active"<?php endif; ?>><a href="<?= esc($FULL_SITE_URL) ?><?php echo stripslashes($APURLinfo['module_name']); ?>/<?php echo stripslashes($MCDinfo['module_name']); ?>/index"><?php echo stripslashes($MCDinfo['module_display_name']); ?></a></li><?php */ ?>
                        <li <?php if (strtolower($activeSubMenu) == strtolower(stripslashes($MCDinfo['module_name']))) : ?>class="active" <?php endif; ?>><a href="<?= esc($FULL_SITE_URL) ?><?php echo stripslashes($MCDinfo['module_name']); ?>/index"><?php echo stripslashes($MCDinfo['module_display_name']); ?></a></li>
                      <?php endforeach; ?>
                    </ul>
                  </li>
                <?php else : ?>
                  <li class="nav-item <?php if (strtolower($activeMenu) == strtolower(stripslashes($APURLinfo['module_name']))) : ?> active<?php endif; ?>">
                    <a href="<?= esc($FULL_SITE_URL) ?><?php echo stripslashes($APURLinfo['module_name']); ?>/index" class="nav-link ">
                      <span class="pcoded-micon"><?php echo stripslashes($APURLinfo['module_icone']); ?></i></span>
                      <span class="pcoded-mtext"><?php echo stripslashes($APURLinfo['module_display_name']); ?></span>
                    </a>
                  </li>
                <?php endif; ?>
              <?php } ?>
              <?php  } else {
              if ($mchilddata) : ?>
                <li class="nav-item pcoded-hasmenu <?php if (strtolower($activeMenu) == strtolower(stripslashes($APURLinfo['module_name']))) : ?> active pcoded-trigger<?php endif; ?>">
                  <a href="javascript:void(0);" class="nav-link "><span class="pcoded-micon"><?php echo stripslashes($APURLinfo['module_icone']); ?></span><span class="pcoded-mtext"><?php echo stripslashes($APURLinfo['module_display_name']); ?></span></a>
                  <ul class="pcoded-submenu">
                    <?php foreach ($mchilddata as $MCDinfo) :  ?>
                      <?php /* ?><li <?php if(strtolower($activeSubMenu) == strtolower(stripslashes($MCDinfo['module_name']))):?>class="active"<?php endif; ?>><a href="<?= esc($FULL_SITE_URL) ?><?php echo stripslashes($APURLinfo['module_name']); ?>/<?php echo stripslashes($MCDinfo['module_name']); ?>/index"><?php echo stripslashes($MCDinfo['module_display_name']); ?></a></li><?php */ ?>
                      <li <?php if (strtolower($activeSubMenu) == strtolower(stripslashes($MCDinfo['module_name']))) : ?>class="active" <?php endif; ?>><a href="<?= esc($FULL_SITE_URL) ?><?php echo stripslashes($MCDinfo['module_name']); ?>/index"><?php echo stripslashes($MCDinfo['module_display_name']); ?></a></li>
                    <?php endforeach; ?>
                  </ul>
                </li>
              <?php else : ?>
                <li class="nav-item <?php if (strtolower($activeMenu) == strtolower(stripslashes($APURLinfo['module_name']))) : ?> active<?php endif; ?>">
                  <a href="<?= esc($FULL_SITE_URL) ?><?php echo stripslashes($APURLinfo['module_name']); ?>/index" class="nav-link ">
                    <span class="pcoded-micon"><?php echo stripslashes($APURLinfo['module_icone']); ?></i></span>
                    <span class="pcoded-mtext"><?php echo stripslashes($APURLinfo['module_display_name']); ?></span>
                  </a>
                </li>
              <?php endif; ?>
            <?php  } ?>

        <?php endforeach;
        endif; ?>
      </ul>
    </div>
  </div>
</nav>
<script type="text/javascript">
  var requiredTimeZone = 0; // UTC (0 hrs)  UTC+0530  
  var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

  var canvas = document.getElementById("clockCanvas");
  var ctx = canvas.getContext("2d");
  var radius = canvas.height / 2;
  ctx.translate(radius, radius);
  radius = radius * 0.90
  setInterval(drawClock, 1000);

  function drawClock() {
    drawFace(ctx, radius);
    drawNumbers(ctx, radius);
    drawTime(ctx, radius);
  }

  function drawFace(ctx, radius) {
    var grad;
    ctx.beginPath();
    ctx.arc(0, 0, radius, 0, 2 * Math.PI);
    ctx.fillStyle = '#ecf0f5';
    ctx.fill();
    grad = ctx.createRadialGradient(0, 0, radius * 0.95, 0, 0, radius * 1.05);
    grad.addColorStop(0, '#4680ff');
    grad.addColorStop(0.5, '#4680ff');
    grad.addColorStop(1, '#4680ff');
    ctx.strokeStyle = grad;
    ctx.lineWidth = radius * 0.1;
    ctx.stroke();
    ctx.beginPath();
    ctx.arc(0, 0, radius * 0.1, 0, 2 * Math.PI);
    ctx.fillStyle = '#000000';
    ctx.fill();
  }

  function drawNumbers(ctx, radius) {
    var ang;
    var num;
    ctx.font = radius * 0.20 + "px arial";
    ctx.textBaseline = "middle";
    ctx.textAlign = "center";
    for (num = 1; num < 13; num++) {
      ang = num * Math.PI / 6;
      ctx.rotate(ang);
      ctx.translate(0, -radius * 0.85);
      ctx.rotate(-ang);
      ctx.fillText(num.toString(), 0, 0);
      ctx.rotate(ang);
      ctx.translate(0, radius * 0.85);
      ctx.rotate(-ang);
    }
  }

  function drawTime(ctx, radius) {
    var d = new Date();
    var utc = d.getTime() + (d.getTimezoneOffset() * 60000);
    var now = new Date(utc + (3600000 * 5.5));
    var calculatedTime = now.getTime(); // + (now.getTimezoneOffset() * 60000) - (requiredTimeZone * 60000);
    now.setTime(calculatedTime);
    var hour = now.getHours();
    var minute = now.getMinutes();
    var second = now.getSeconds();
    //hour
    hour = hour % 12;
    hour = (hour * Math.PI / 6) +
      (minute * Math.PI / (6 * 60)) +
      (second * Math.PI / (360 * 60));
    drawHand(ctx, hour, radius * 0.5, radius * 0.06);
    //minute
    minute = (minute * Math.PI / 30) + (second * Math.PI / (30 * 60));
    drawHand(ctx, minute, radius * 0.7, radius * 0.04);
    // second
    second = (second * Math.PI / 30);
    drawHand(ctx, second, radius * 0.7, radius * 0.02);
  }

  function drawHand(ctx, pos, length, width) {
    ctx.beginPath();
    ctx.lineWidth = width;
    ctx.lineCap = "round";
    ctx.moveTo(0, 0);
    ctx.rotate(pos);
    ctx.lineTo(0, -length);
    ctx.stroke();
    ctx.rotate(-pos);
  }
</script>
<script type="text/javascript">
  function startTime() {
    var d = new Date();
    var utc = d.getTime() + (d.getTimezoneOffset() * 60000);
    var today = new Date(utc + (3600000 * 5.5));
    var calculatedTime = today.getTime(); // + (today.getTimezoneOffset() * 60000) - (requiredTimeZone * 60000);
    today.setTime(calculatedTime);

    var year = today.getFullYear();
    var month = today.getMonth();
    month = month;
    var day = today.getDate();
    day = checkTime(day);

    var hour = today.getHours();
    var minute = today.getMinutes();
    var second = today.getSeconds();
    minute = checkTime(minute);
    second = checkTime(second);

    var prepand = (hour >= 12) ? " PM " : " AM ";
    hour = (hour >= 12) ? hour - 12 : hour;
    if (hour === 0 && prepand === ' PM ') {
      if (minute === 0 && second === 0) {
        hour = 12;
        prepand = ' Noon';
      } else {
        hour = 12;
        prepand = ' PM';
      }
    }
    if (hour === 0 && prepand === ' AM ') {
      if (minute === 0 && second === 0) {
        hour = 12;
        prepand = ' Midnight';
      } else {
        hour = 12;
        prepand = ' AM';
      }
    }
    //document.getElementById('clockText').innerHTML = day+', '+monthNames[month]+' '+year+' '+hour + ":" + minute + ":" + second+' '+prepand;
    document.getElementById('clockText').innerHTML = monthNames[month] + ' ' + day + ' ' + hour + ":" + minute + ":" + second + ' ' + prepand;
    var t = setTimeout(startTime, 500);
  }

  function checkTime(i) {
    if (i < 10) {
      i = "0" + i
    }; // add zero in front of numbers < 10
    return i;
  }
  startTime();
</script>