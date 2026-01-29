<?php $page_data= $this->front_model->get_product(); //echo "<pre>"; print_r($page_data);die;?>
<header class="menu_link navbar-fixed-top">
<div class="top_bar"> <a href="tel:7011969292" class="footer__top__button"><span class="footer__top__text p-event-none"><i class="fa fa-phone"><i class="r_no"> 7011969292</i></i></i></span><span class="bar-wrapper bar-wrapper--up p-event-none"><span class="bar-whitespace p-event-none"><span class="bar-line bar-line--up p-event-none"></span></span></span></a>
<div class="no_bt"> <a href="tel:7011969292" target="_blank"><i class="fa fa-phone" aria-hidden="true"></i></a> <a href="https://www.facebook.com/algosoftapps" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a> <a href="https://twitter.com/algosoftapps" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a> <a href="https://www.linkedin.com/company/algosoftapps" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a> <a href="tel:7011969292"><i class="fa fa-whatsapp" aria-hidden="true"></i></a> </div>
</div>
<nav class="navbar navbar-default ">
<div class="container-fluid">
<div class="navbar-header page-scroll">
<button type="button" class="navbar-toggle"><span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>

<a class="navbar-brand aos-init aos-animate" href="https://www.algosoft.co/" data-aos="zoom-out"><img alt="Algosoft Apps Technologies Red Logo" class="img-responsive" height="89" src="https://www.algosoft.co/assets/editor_upload_images/logo-red.png" width="901"><img alt="Algosoft Apps Technologies White Logo" class="img-responsive" src="https://www.algosoft.co/assets/editor_upload_images/logo.png"> <span>ISO 9001:2015</span></a>
</div>

<div class="navbar-collapse web_link">
<ul class="nav navbar-nav navbar-right">
<li class=""><a href="<?php echo base_url();?>" data-hover="Home">Home</a> </li>
<li class=""><a href="<?php echo base_url();?>about-us.html" data-hover="About Us">About Us</a> </li>
<!--<li class=""><a href="<?php echo base_url();?>data-cloud-analytics" data-hover="About Us">Data Analytics</a> </li>--> 
<li class="dropdown">
<a href="" class="dropdown-toggle" data-toggle="dropdown" data-hover="Services">Services<span class=""><i class="fa fa-caret-down" aria-hidden="true"></i></span></a>
<ul class="dropdown-menu Mytop_drop aos-init aos-animate" role="menu" data-aos="zoom-out">
    <li class=""><a href="<?php echo base_url();?>data-cloud-analytics" data-hover="About Us">Data Analytics</a> </li>
<?php foreach ($header_menu_services as $header_menu_services_data): ?>
<li><a href="<?php echo base_url().'services/'.stripslashes($header_menu_services_data['slug']); ?>.html" title="<?php echo stripslashes($header_menu_services_data['service_name']); ?>"><?php echo stripslashes($header_menu_services_data['service_name']); ?></a></li>
<?php endforeach;?>
</ul>
<li class="dropdown open">
	<a href="<?php echo base_url();?>solutions.html" class="dropdown-toggle" data-toggle="" data-hover="Solutions" aria-expanded="true">Solutions<span class=""><i class="fa fa-caret-down" aria-hidden="true"></i></span></a>
	<ul class="dropdown-menu Mytop_drop aos-init aos-animate" role="menu" data-aos="zoom-out">
<li><a href="<?php echo base_url();?>solutions/pos-solution.html" title="POS Solution">POS Solution</a></li>
<li><a href="<?php echo base_url();?>solutions/taxibooking-solution.html" title="Taxi Booking System">Taxi Booking System</a></li>
<li><a href="<?php echo base_url();?>solutions/handyman.html" title="Handyman Solution">Handyman Solution</a></li>
<li><a href="<?php echo base_url();?>solutions/learning-management-system.html" title="Learning Management System">Learning Management System</a></li>
<li><a href="<?php echo base_url();?>solutions/cable-solution.html" title="Cable Operator Software">Cable Operator Software</a></li>

<?php if($page_data <> ""): foreach($page_data as $PG): ?>
<li>
	<a href="<?php echo base_url().'solutions/'.stripslashes($PG['slug'].'.html'); ?>" title="<?php echo stripslashes($PG['product']); ?>"><?php echo stripslashes($PG['product']); ?></a>
</li>
<?php  endforeach; endif; ?>
</ul>
</li>
<li class=""><a href="<?php echo base_url();?>clients.html" data-hover="Clients">Clients</a> </li>
<li class=""><a href="<?php echo base_url();?>contact.html" data-hover="Contact">Contact</a> </li>
</li>
<li> <a href="<?php echo base_url();?>raq.html" data-hover="Test" class="new_quite">Get A Free Quote</a> </li>
</ul>
</div>
<div class="navbar-collapse mobile_link">
<div class="nav_mobu_head"><span><i class="fa fa-tasks" aria-hidden="true"></i></span>Menu</div>
<ul class="nav navbar-nav navbar-right">
<li class=""><a href="<?php echo base_url();?>" data-hover="Home"><span class="sp"><img src="<?php echo base_url();?>assets/front/image/home.png" alt="home"/></span>Home</a> </li>
<li class=""><a href="<?php echo base_url();?>about-us.html" data-hover="About Us"><span class="sp"><img src="<?php echo base_url();?>assets/front/image/about-us.png" alt="about-us"/></span>About Us</a> </li>
<li class="dropdown">

<a href="" class="dropdown-toggle" data-toggle="dropdown" data-hover="Services"><span class="sp"><img src="<?php echo base_url();?>assets/front/image/services.png" alt="services"/></span>Services<span class=""><i class="fa fa-caret-down" aria-hidden="true"></i></span></a>

<ul class="dropdown-menu Mytop_drop aos-init aos-animate" role="menu" data-aos="zoom-out">
<?php foreach ($header_menu_services as $header_menu_services_data): ?>
<li><a href="<?php echo base_url().'services/'.stripslashes($header_menu_services_data['slug']); ?>.html" title="<?php echo stripslashes($header_menu_services_data['service_name']); ?>"><?php echo stripslashes($header_menu_services_data['service_name']); ?></a></li>
<?php endforeach;?>
</ul>
<li class="dropdown">

	<a href="" class="dropdown-toggle" data-toggle="dropdown" data-hover="Solutions"><span class="sp"><img src="<?php echo base_url();?>assets/front/image/product.png" alt="Solutions"/></span>Solutions<span class=""><i class="fa fa-caret-down" aria-hidden="true"></i></span></a>
	
	<ul class="dropdown-menu Mytop_drop aos-init aos-animate" role="menu" data-aos="zoom-out">
	<li><a href="<?php echo base_url();?>solutions/pos-solution.html" title="POS Solution">POS Solution</a></li>
<li><a href="<?php echo base_url();?>solutions/taxibooking-solution.html" title="Taxi Booking System">Taxi Booking System</a></li>
<li><a href="<?php echo base_url();?>solutions/handyman.html" title="Handyman Solution">Handyman Solution</a></li>
<li><a href="<?php echo base_url();?>solutions/learning-management-system.html" title="Learning Management System">Learning Management System</a></li>
<li><a href="<?php echo base_url();?>solutions/cable-solution.html" title="Cable Operator Software">Cable Operator Software</a></li>    
	<?php if($page_data <> ""): foreach($page_data as $PG): ?>
<li>
	<a href="<?php echo base_url().'solutions/'.stripslashes($PG['slug']); ?>"  title="<?php echo stripslashes($PG['product']); ?>"><?php echo stripslashes($PG['product']); ?></a>
</li>
<?php  endforeach; endif; ?>

</ul>
</li>
</li>
<li class=""><a href="<?php echo base_url();?>clients.html" data-hover="Clients"><span class="sp"><img src="<?php echo base_url();?>assets/front/image/clients.png" alt="clients"/></span>Clients</a> </li>
<li class=""><a href="<?php echo base_url();?>contact.html" data-hover="Contact"><span class="sp"><img src="<?php echo base_url();?>assets/front/image/contact.png" alt="contact"/></span>Contact</a> </li>
</li>
</ul>
</div>
<div class="mobile-fade"></div>
</nav>
</header>

<script>
$(function() {
    $('marquee').mouseover(function() {
        $(this).attr('scrollamount',0);
    }).mouseout(function() {
         $(this).attr('scrollamount',5);
    });

});
</script>
<style>

</style>