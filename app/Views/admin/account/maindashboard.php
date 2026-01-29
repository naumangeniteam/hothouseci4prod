<style type="text/css">
   /*.d-card-body {
    overflow-y: auto;
    height: 300px;
}*/
   #container {
      height: 400px;
   }

   .highcharts-figure,
   .highcharts-data-table table {
      min-width: 310px;
      max-width: 800px;
      margin: 1em auto;
   }

   #datatable {
      font-family: Verdana, sans-serif;
      border-collapse: collapse;
      border: 1px solid #EBEBEB;
      margin: 10px auto;
      text-align: center;
      width: 100%;
      max-width: 500px;
   }

   #datatable caption {
      padding: 1em 0;
      font-size: 1.2em;
      color: #555;
   }

   #datatable th {
      font-weight: 600;
      padding: 0.5em;
   }

   #datatable td,
   #datatable th,
   #datatable caption {
      padding: 0.5em;
   }

   #datatable thead tr,
   #datatable tr:nth-child(even) {
      background: #f8f8f8;
   }

   #datatable tr:hover {
      background: #f1f7ff;
   }

   #container {
      height: 400px;
   }

   .highcharts-figure,
   .highcharts-data-table table {
      min-width: 310px;
      max-width: 800px;
      margin: 1em auto;
   }

   #datatable {
      font-family: Verdana, sans-serif;
      border-collapse: collapse;
      border: 1px solid #EBEBEB;
      margin: 10px auto;
      text-align: center;
      width: 100%;
      max-width: 500px;
   }

   #datatable caption {
      padding: 1em 0;
      font-size: 1.2em;
      color: #555;
   }

   #datatable th {
      font-weight: 600;
      padding: 0.5em;
   }

   #datatable td,
   #datatable th,
   #datatable caption {
      padding: 0.5em;
   }

   #datatable thead tr,
   #datatable tr:nth-child(even) {
      background: #f8f8f8;
   }

   #datatable tr:hover {
      background: #f1f7ff;
   }

   .card-box {
      position: relative;
      color: #fff;
      padding: 20px 10px 40px;
      margin: 10px 0px;
      margin-left: 2px;
   }

   .card-box:hover {
      text-decoration: none;
      color: #f1f1f1;
   }

   .card-box:hover .icon i {
      font-size: 100px;
      transition: 1s;
      -webkit-transition: 1s;
   }

   .card-box .inner {
      padding: 5px 10px 0 10px;
   }

   .card-box h3 {
      font-size: 27px;
      font-weight: bold;
      margin: 0 0 8px 0;
      white-space: nowrap;
      padding: 0;
      text-align: left;
   }

   .card-box p {
      font-size: 15px;
   }

   .card-box .icon {
      position: absolute;
      top: auto;
      bottom: 5px;
      right: 20px;
      z-index: 0;
      font-size: 72px;
      color: rgba(0, 0, 0, 0.15);
   }

   .card-box .card-box-footer {
      position: absolute;
      left: 0px;
      bottom: 0px;
      text-align: center;
      padding: 3px 0;
      color: rgba(255, 255, 255, 0.8);
      background: rgba(0, 0, 0, 0.1);
      width: 100%;
      text-decoration: none;
   }

   .card-box:hover .card-box-footer {
      background: rgba(0, 0, 0, 0.3);
   }

   .bg-blue {
      background-color: #00c0ef !important;
   }

   .bg-green {
      background-color: #00a65a !important;
   }

   .bg-orange {
      background-color: #f39c12 !important;
   }

   .bg-red {
      background-color: #d9534f !important;
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
                     <?php /* ?><h5 class="m-b-10">Welcome <?=sessionData('CMPOP_ADMIN_FIRST_NAME')?></h5><?php */ ?>
                  </div>
                  <ul class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php //echo getCurrentDashboardPath('dashboard/index'); ?>"><i class="feather icon-home"></i></a></li>
                     <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                  </ul>
               </div>
            </div>
         </div>
      </div>
      <!-- [ breadcrumb ] end -->
      <!-- [ Main Content ] start -->
      <div class="row">
         <div class="col-lg-12 col-md-12">
            <div class="card">
               <div class="card-header">
                  <h5>Dashboard</h5>
               </div>
               <div class="main-content">
                  <div class="container-fluid">
                     <div class="panel panel-headline">
                        <div class="panel-heading">
                           <h3 class="panel-title">Welcome To Hothouse</h3>
                        </div>
                        <div class="panel-body">
                           <div class="row">
                              &nbsp;
                           </div>
                           <div class="container">
                              <div class="row">
                                 <div class="col-lg-3 col-sm-6">
                                    <div class="card-box bg-green">
                                       <div class="inner">
                                          <h3><?= is_array($events) ? count($events) : 0 ?></h3>
                                          
                                          <p> Total Events</p>
                                       </div>
                                       <div class="icon">
                                          <i class="fa fa-users" aria-hidden="true"></i>
                                       </div>
                                       <a href="<?= base_url('hhjsitemgmt/eventmanagement/index') ?>" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                 </div>
                                 <div class="col-lg-3 col-sm-6">
                                    <div class="card-box bg-orange">
                                       <div class="inner">
                                          <h3><?= is_array($publishevent) ? count($publishevent) : 0 ?></h3>
                                          <p> Total Published Event</p>
                                       </div>
                                       <div class="icon">
                                          <i class="fa fa-user-plus" aria-hidden="true"></i>
                                       </div>
                                       <a href="<?= base_url('hhjsitemgmt/adminmanagepublishevent') ?>" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                 </div>
                                 <div class="col-lg-3 col-sm-6">
                                    <div class="card-box bg-red">
                                       <div class="inner">
                                          <h3><?= $trashevent ?></h3>
                                          <p> Total Trash Event</p>
                                       </div>
                                       <div class="icon">
                                          <i class="fa fa-user-plus" aria-hidden="true"></i>
                                       </div>
                                       <a href="<?= base_url('hhjsitemgmt/adminmanagetrashevent') ?>" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                 </div>
                                 <div class="col-lg-3 col-sm-6">
                                    <div class="card-box bg-green">
                                       <div class="inner">
                                          <p>User Guide</p>
                                       </div>
                                       <div class="icon">
                                          <i class="fa fa-users" aria-hidden="true"></i>
                                       </div>
                                       <a href="   <?= esc($ASSET_INCLUDE_URL) ?>document/hothousejazz_guide.pdf" class="card-box-footer" target="_blank">View <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                 </div>
                              </div>

                              <div class="row">
                                 <div class="col-lg-3 col-sm-6">
                                    <div class="card-box bg-primary">
                                       <div class="inner">
                                          <h3><?= is_array($newVenues) ? count($newVenues) : 0 ?></h3>
                                          <p>No. of New Venues</p>
                                       </div>
                                       <div class="icon">
                                          <i class="fa fa-users" aria-hidden="true"></i>
                                       </div>
                                       <a href="<?= base_url('hhjsitemgmt/adminmanagevenuecategory/index') ?>" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                 </div>
                                
                                 <div class="col-lg-3 col-sm-6">
                                    <div class="card-box bg-success">
                                       <div class="inner">
                                          <h3>
                                          <h3><?= is_array($newEvents) ? count($newEvents) : 0 ?></h3>
                                          </h3>
                                          <p>No. of New Events</p>
                                       </div>
                                       <div class="icon">
                                          <i class="fa fa-users" aria-hidden="true"></i>
                                       </div>
                                       <a href="<?= base_url('hhjsitemgmt/eventmanagement/index') ?>" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                 </div>

                                 <div class="col-lg-3 col-sm-6">
                                    <div class="card-box bg-danger">
                                       <div class="inner">
                                          <h3><?= $newTrashEvents ?></h3>
                                          <p>No. of Events Deleted</p>
                                       </div>
                                       <div class="icon">
                                          <i class="fa fa-users" aria-hidden="true"></i>
                                       </div>
                                       <a href="<?= base_url('hhjsitemgmt/eventmanagement/index') ?>" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                 </div>

                                 <div class="col-lg-3 col-sm-6">
                                    <div class="card-box bg-blue">
                                       <div class="inner">
                                          <h3><?= is_array($newUsers) ? count($newUsers) : 0 ?></h3>
                                          <p>No. of Users Signing Up</p>
                                       </div>
                                       <div class="icon">
                                          <i class="fa fa-users" aria-hidden="true"></i>
                                       </div>
                                       <a href="<?= base_url('hhjsitemgmt/adminmanageuser/index') ?>" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                 </div>

                              </div>

                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="card-body">
                  <div class="card-block">
                     <div class="row ">
                        <div class="container-fluid">
                           <div class="panel panel-headline">
                              <div class="panel-body">
                                 <div class="row box_guard">

                                 </div>

                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>