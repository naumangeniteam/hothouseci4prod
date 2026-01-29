<?php foreach ($banner as $key => $item) : ?>
   <div class="full-container page-banner" style="background: url('<?=base_url().'assets/front/img/banner/'.$item['image'] ?>');">
      <div class="page-banner-caption">
         <h1><?= $item['title'] ?></h1>
      </div>
   </div>
<?php endforeach;  ?>

<div class="container">
   <div class="row">
      <div class="col-md-1"></div>
      <div class="col-md-10 onclick_Inpputs">
         <ul id="list" style="display: block;">
            <span id="Span_form">
               <form action="<?php echo base_url('global-search-filters'); ?>" id="event_search_form" method="POST">
                  <?= csrf_field() ?>
                  <div class="hhj-search-field">
                     <img class="search-icon" src="<?= base_url('assets/front/icons/search.svg') ?>" alt="user Login" />
                     <input value="<?php echo isset($keyword) ? $keyword : ''; ?>" class="form-control" autocomplete="off" placeholder="Search by event name" id="search-box" name="event_title">
                     <img class="filter-icon" src="<?= base_url('assets/front/icons/filter.svg') ?>" onclick="toggleSection()" alt="user Login" />
                  </div>
                  <div id="toggleSection">
                     <div class="hhj-search-fields">
                        <div class="hhj-adv-box">
                           <input class="form-control tags" placeholder="Search by event tag" id="event_tags" name="event_tags">
                        </div>

                        <div class="hhj-adv-box">
                           <select class="form-control venues" name="venue" id="mySelect">
                              <option value="">Select a location</option>
                              <?php foreach ($location_tbl as $location) : ?>
                                 <option value="<?= $location['id']; ?>"><?= $location['venue_title'] ?></option>
                              <?php endforeach; ?>
                           </select>
                        </div>

                        <div class="hhj-adv-box">
                           <select class="form-control locs" name="location" id="venue_location">
                              <option value="">Select a venue</option>
                              <?php foreach ($venue_tbl as $venue) : ?>
                                 <option value="<?= $venue['id']; ?>"><?= $venue['location_name'] ?></option>
                              <?php endforeach; ?>
                           </select>
                        </div>

                        <div class="hhj-adv-box">
                           <select class="form-control jazzed" name="jazz" id="jazz">
                              <option value="">Select a Jazz</option>
                              <?php foreach ($jazzType as $jType) : ?>
                                 <option value="<?= $jType->id ?>"><?= $jType->name ?></option>
                              <?php endforeach; ?>
                           </select>
                        </div>
                        <input type="hidden" id="venue" value="<?= isset($location['id']) ? $location['id'] : ''; ?>">
                        <input type="hidden" id="artist" value="<?= isset($artist['id']) ? $artist['id'] : ''; ?>">
                        <input type="hidden" id="selDate" value="">
                        <div class="clear-filter">
                           <a href="#" id="clearButton"><img src="<?= base_url('icons/eraser.svg') ?>" alt="user Login" /> Clear</a>
                        </div>
                     </div>
                  </div>
               </form>
            </span>
         </ul>
         <div id="current_display_div" style="display:none;"></div>
      </div>
      <div class="col-md-1"></div>
   </div>
</div>

<div class="container">
   <div class="row hhj-calender" id="html" style="margin-right: -15px;margin-left: -15px;"></div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<script>
   function toggleSection() {
      jQuery('#toggleSection').toggle();
   }

   const selectElement = document.getElementById("mySelect");
   selectElement.addEventListener("change", function() {
      getartist();
   });

   const selectElement_location = document.getElementById("venue_location");
   selectElement_location.addEventListener("change", function() {
      getartist();
   });

   const selectElement_name = document.getElementById("jazz");
   selectElement_name.addEventListener("change", function() {
      getartist();
   });

   jQuery(document).ready(function() {
      // Extract query string parameters
      const urlParams = new URLSearchParams(window.location.search);
      const urlKeyword = urlParams.get('keyword');
      
      // If there's a keyword in query string, add it to the search box
      if (urlKeyword) {
         jQuery('#search-box').val(decodeURIComponent(urlKeyword));
      }
      
      // Auto-submit if we have a keyword (either from controller or query param)
      if (jQuery('#search-box').val().trim() !== '') {
         setTimeout(function() {
            getartist();
         }, 100);
      }
      
      jQuery("#search-box").keyup(function() {
         if (jQuery.trim(jQuery(this).val()) != "") {
            getartist();
         }
      });

      jQuery(document).on('keyup', '#event_tags', function() {
         getartist();
      });
   });

   function getartist() {
      var Selected_Date_ = jQuery("#selDate").val();
      var url = '<?= base_url('global-search-filters') ?>';
      
      jQuery.ajax({
         url: url,
         method: "POST",
         data: jQuery('#event_search_form').serialize() + "&Selected_Date_=" + Selected_Date_,
         dataType: "json",
         beforeSend: function() {
            jQuery('#html').html('<div class="text-center"><p>Loading results...</p></div>');
         },
         success: function(data) {
            jQuery('#html').html(data.data);
            
            // Update URL with the search term
            var searchTitle = jQuery('#search-box').val();
            if (searchTitle) {
               var newUrl = updateQueryStringParameter(
                  '<?= base_url('global-search') ?>', 
                  'keyword', 
                  encodeURIComponent(searchTitle)
               );
               history.pushState({}, 'Search: ' + searchTitle, newUrl);
            }
         },
         error: function(xhr, status, error) {
            console.error("Search error:", error);
            jQuery('#html').html('<div class="alert alert-danger">Search failed. Please try again later.</div>');
         }
      });
   }

   function showPopup(id) {
      jQuery.ajax({
         url: '<?php echo base_url('get-artist-data'); ?>',
         data: {
            id: id
         },
         type: 'GET',
         success: function(response) {
            jQuery('#artist_content').html(response);
            jQuery('#openInfoModal').trigger('click');
         }
      });
   }
   
   jQuery(document).on('click', '.more_info_artist_btn', function() {
      var id = jQuery(this).attr('data-id')
      showPopup(id);
   });

   document.getElementById("clearButton").addEventListener("click", function(event) {
      event.preventDefault();
      document.getElementById("search-box").value = "";
      document.getElementById("event_tags").value = "";
      document.getElementById("mySelect").selectedIndex = 0;
      document.getElementById("venue_location").selectedIndex = 0;
      document.getElementById("jazz").selectedIndex = 0;
      document.getElementById("selDate").value = "";
      location.reload();
   });

   function updateQueryStringParameter(uri, key, value) {
      var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
      var separator = uri.indexOf('?') !== -1 ? "&" : "?";
      
      if (uri.match(re)) {
         return uri.replace(re, '$1' + key + "=" + value + '$2');
      } else {
         return uri + separator + key + "=" + value;
      }
   }
</script>