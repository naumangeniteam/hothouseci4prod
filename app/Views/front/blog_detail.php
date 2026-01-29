<?php if($banner <> ''):?>
<?php foreach($banner as $key => $item): ?>
   <div class="full-container page-banner" style="background: url('<?=base_url().'assets/front/img/banner/'.$item['image']?>');">
      <div class="page-banner-caption"><h1>Magazine</h1></div>
   </div>
<?php endforeach; endif; ?>
<?= view('layouts/front/home-tabs') ?>
<div class="single-contents">
   <div class="container">
   <?php foreach($blog as $key => $item): ?>
      <div class="row">
         <div class="col-lg-12 col-md-12 col-sm-12 col-12">
            <h1><?=$item['page_title']?></h1>
            <?=$item['content']?>
         </div>
      </div>
      <?php endforeach;  ?>
   </div>
</div>