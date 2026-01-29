<?php foreach ($banner as $key => $item) : ?>
<div class="full-container page-banner" style="background: url('<?=base_url().'assets/front/img/banner/'.$item['image'] ?>');">
   <div class="page-banner-caption">
      <h1 style="color: #ffffff !important;">About Us</h1>
   </div>
</div>
<?php endforeach;  ?>
<?= view('layouts/front/home-tabs') ?>
	<div class="hhj-about">
		<div class="container about-info">
			<div class="row">
				<div class="col-md-8">
					<h2><?=$about[0]['page_title']?></h2>
					<p><?=$about[0]['content']?></p>
				</div>
				<div class="col-md-4">
					<img src="img/aboutus/<?=$about[0]['image']?>" class="img-fluid" alt="">
				</div>
			</div>
		</div>

		<div class="container">
			<h2 class="member-heading">Our Team Members</h2>
			<div class="row about-member">
				<div class="col-md-12">
					<div class="team-box black">
						<h4><?=$about_team_tbl[0]['title']?></h4>
						<p><?=$about_team_tbl[0]['content']?></p>
					</div>
				</div>
			</div>

			<div class="row about-member">
				<div class="col-md-12">
					<div class="team-box">
						<h4><?=$about_team_tbl[1]['title']?></h4>
						<p><?=$about_team_tbl[1]['content']?></p>
					</div>
				</div>
			</div>

			<div class="row about-member">
				<div class="col-md-12">
					<div class="team-box">
						<h4><?=$about_team_tbl[2]['title']?></h4>
						<p><?=$about_team_tbl[2]['content']?></p>
					</div>
				</div>
			</div>

			<div class="row about-member">
				<div class="col-md-12">
					<div class="team-box red">
						<h4><?=$about_team_tbl[3]['title']?></h4>
						<p><?=$about_team_tbl[3]['content']?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
	<script>