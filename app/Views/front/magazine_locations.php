<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" integrity="sha512-sMXtMNL1zRzolHYKEujM2AqCLUR9F2C4/05cdbxjjLSRvMQIciEPCQZo++nk7go3BtSuK9kfa/s+a4f4i5pLkw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
  .slider {
    position: relative;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
  }

  .caption_here {
    position: absolute;
    width: 80%;
    bottom: 7%;
    color: #fff;
  }

  #partner-carousel.owl-carousel .owl-item img {
    display: block;
    width: 35%;
    margin: auto;
  }

  #map {
    height: 500px;
    width: 100%;
  }

  .address {
    font-size: 14px;
    margin-bottom: 10px;
  }

  .location-name {
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 10px;
  }

  .info {
    font-size: 14px;
    margin-bottom: 3px;
  }
</style>

<div class="home-history">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-12">
        <h3 class="default-heading text-center" style="margin-bottom: 31px;">Magazine <span>Locations</span> </h3>
        <form  method="get" action="<?php echo base_url('magazine_locations'); ?>">
          <div class="row mb-4">
            <div class="form-group-inner col-lg-4 col-md-4 col-sm-4 col-xs-12">
              <input type="text" name="zipcode" id="zipcode" class="form-control" placeholder="Enter Zip Code" value="<?php echo isset($_GET['zipcode']) ? htmlspecialchars($_GET['zipcode']) : ''; ?>">
            </div>
            <div class="form-group-inner col-lg-4 col-md-4 col-sm-4 col-xs-12">
              <button type="submit" class="btn btn-danger">Search</button>
            </div>
          </div>
        </form>
        <div id="map"></div>
      </div>
    </div>
  </div>
</div>

<script>
  function initMap() {
    var map = new google.maps.Map(document.getElementById('map'), {
      center: {
        lat: 20.5937,
        lng: 78.9629
      },
      zoom: 5
    });

    var geocoder = new google.maps.Geocoder();
    var locations = [
        <?php foreach ($location_tbl as $location) : ?> {
          name: '<?php echo $location['name']; ?>',
          lat: <?php echo $location['latitude']; ?>,
          lng: <?php echo $location['longitude']; ?>,
          info: '<?php echo $location['info']; ?>'
        },
      <?php endforeach; ?>
    ];

    function geocodeLocation(location, marker) {
      var latLng = new google.maps.LatLng(location.lat, location.lng);
      geocoder.geocode({
        'location': latLng
      }, function(results, status) {
        if (status === 'OK') {
          if (results[0]) {
            var address = results[0].formatted_address;
            var infoWindow = new google.maps.InfoWindow({
              content: '<div class="location-name">' + location.name + '</div>' +
                '<div class="address">' + address + '</div>' +
                '<div class="info">' + location.info + '</div>' +
                '<a href="https://www.google.com/maps/search/?api=1&query=' + location.name + '" target="_blank">View on Google Maps</a>'
            });
            marker.addListener('click', function() {
              infoWindow.open(map, marker);
            });
          } else {
            console.log('No results found');
          }
        } else {
          console.log('Geocoder failed due to: ' + status);
        }
      });
    }

    locations.forEach(function(location) {
      var marker = new google.maps.Marker({
        position: {
          lat: location.lat,
          lng: location.lng
        },
        map: map
      });

      geocodeLocation(location, marker);
    });
  }
</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDPyWbxCqalUPsqs3f2bY1w_FDh5rAAXEE&callback=initMap"></script>