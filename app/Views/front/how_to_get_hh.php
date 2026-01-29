<?php foreach ($banner as $key => $item) : ?>
<div class="full-container page-banner" style="background: url('<?=base_url().'assets/front/img/banner/'.$item['image'] ?>');">
   <div class="page-banner-caption">
      <h1>Subscribe For One Year</h1>
   </div>
</div>
<?php endforeach;  ?>

   <?= view('layouts/front/home-tabs') ?>

<div class="hhj-how-to">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <h2>How to Get Hot House Jazz Magazine</h2>
            <p><?=$get_hh_tbl[0]['content'] ?? ''?></p>
         </div>
      </div>
   </div>
</div>

<script>
   // JavaScript to remove <p> elements that contain only &nbsp;
   document.querySelectorAll('p').forEach(function(paragraph) {
   if (paragraph.innerHTML.trim() === '&nbsp;') {
      paragraph.remove(); // Remove the <p> element from the DOM
   }
   });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
   <script>