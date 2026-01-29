<?php foreach ($banner as $key => $item) : ?>
<div class="full-container page-banner" style="background: url('<?=base_url().'assets/front/img/banner/'.$item['image'] ?>');">
   <div class="page-banner-caption">
      <h1>Contact Us</h1>
   </div>
</div>
<?php endforeach;  ?>
<?= view('layouts/front/home-tabs') ?>

<div class="contact-info">
	<div class="container">
		<h2>Contact Details</h2>
		<p><span>Cell Phone:</span> <?=$contact_details_tbl[0]['phone']?></p>
		<p><span>Address:</span> <?=$contact_details_tbl[0]['address']?></p>
		<p><span><?=$contact_details_tbl[0]['content1']?></span></p>
		<p><span><?=$contact_details_tbl[0]['content2']?></span></p>
		<p><span><?=$footer_tbl[3]['page_title']?></span> <?=$footer_tbl[3]['content']?></p>
		<iframe src="https://embeds.beehiiv.com/f2ba26e2-c9f8-474c-9c0e-1a5b44302052" data-test-id="beehiiv-embed" width="100%" height="320" frameborder="0" scrolling="no" style="border-radius: 4px; border: 2px solid #e5e7eb; margin: 0; background-color: transparent;"   sandbox="allow-scripts allow-forms allow-same-origin"></iframe>
		<iframe src="https://us3.list-manage.com/contact-form?u=53e9cf8c7d892b08a99c1e421&form_id=3eb06c8ca5a559c150f7e587749798cd" data-test-id="beehiiv-embed" width="100%" height="1020" frameborder="0" scrolling="no" style="border-radius: 4px; border: 2px solid #e5e7eb; margin: 0; background-color: transparent;"  sandbox="allow-scripts allow-forms allow-same-origin"></iframe>	
	</div>

	<div class="container">
		<h2><?=$advertisement_tbl[0]['title1']?></h3>
		<p><?=$advertisement_tbl[0]['content1']?></p>
		<h2><?=$advertisement_tbl[0]['title2']?></h3>
		<?=$advertisement_tbl[0]['content2']?>
	</div>
	
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
<script>
