<link href="https://owlcarousel2.github.io/OwlCarousel2/assets/owlcarousel/assets/owl.theme.default.min.css" rel="stylesheet" />
<link href="https://owlcarousel2.github.io/OwlCarousel2/assets/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://owlcarousel2.github.io/OwlCarousel2/assets/owlcarousel/owl.carousel.js"></script>

<?php foreach ($banner as $key => $item) : ?>
<div class="full-container page-banner" style="background: url('<?=base_url().'assets/front/img/banner/'.$item['image'] ?>');">
   <div class="page-banner-caption">
      <h1>Festivals</h1>
   </div>
</div>
<?php endforeach;  ?>


<?= view('layouts/front/home-tabs') ?>
<div class="calendar-section" id="calendar-section">
    <div class="container-fluid hhj-calender-row">
        <div class="row">
            <div class="col-md-12 hhj-calendar-date">
                <div id="heading-carousel" class="owl-carousel">
                <div class="monthsData">
                    <!--<div class="item"><h1 class="default-heading"><?php echo date('F, Y'); ?></h1</div>-->
                </div>
                <div class="owl-nav">
                    <button type="button" role="presentation" class="owl-prev"> <span aria-label="Previous">‹</span> </button>
                    <button type="button" role="presentation" class="owl-next"> <span aria-label="Next">›</span> </button>
                </div>
                </div>
                <div id="day-carousel" class="owl-carousel"> </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10 onclick_Inpputs">
                <ul id="list" style="display: block;">
                    <span id="Span_form">
                        <form action="<?php echo base_url('all-products'); ?>" id="event_search_form" method="GET">
                            <div class="hhj-search-field">
                                <img class="search-icon" src="<?= base_url('assets/front/icons/search.svg') ?>" alt="user Login" />
                                <input class="form-control" placeholder="Search by festival name " id="search-box" name="festival_name">
                                <div id="suggesstion-box" class="suggesstion-box"></div>
                            </div>
                        </form>
                    </span>
                </ul>
            </div>
            <div id="current_display_div" style="display:none;"></div>
            <div class="col-md-1"></div>
        </div>
    </div>
   

    <div class="container">
        <div class="row hhj-festival" id="html">

        </div>
    </div>
</div>

<script>
    document.getElementById('event_search_form').addEventListener('submit', function(event) {
        event.preventDefault();
        console.log('Form submission prevented');
    });
</script>

<script>
    $("#html").hide();
    window.onload = function() {
        setTimeout(function() {
            $("#html").show();
        }, 2000);
        $('#calendar-section').addClass("expand");
        $("#html").show();
    };
</script>

<!-- Main Slider Js -->
<script>
    jQuery("#heading-carousel").owlCarousel({
        autoplay: false,
        loop: true,
        margin: 0,
        /*
     animateOut: 'fadeOut',
     animateIn: 'fadeIn',
     */
        responsiveClass: true,
        autoHeight: true,
        //autoplayTimeout: 7000,
        smartSpeed: 800,
        nav: true,
        dots: false,
        responsive: {
            0: {
                items: 1,
            },
            600: {
                items: 1,
            },

            1024: {
                items: 1,
            },

            1366: {
                items: 1
            }
        }
    });
</script>

<script>
    jQuery("#day-carousel").owlCarousel({
        autoplay: true,
        loop: true,
        margin: 0,
        /*
     animateOut: 'fadeOut',
     animateIn: 'fadeIn',
     */
        responsiveClass: true,
        autoHeight: true,
        autoplayTimeout: 7000,
        smartSpeed: 800,
        nav: true,
        dots: false,
        responsive: {
            0: {
                items: 5
            },

            600: {
                items: 10
            },

            1024: {
                items: 15
            },

            1366: {
                items: 15
            }
        }
    });
</script>

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function(event) {
        //alert('test');
        document.getElementsByClassName('default-heading').innerHTML = "hello";
    });
</script>

<script>
    $(document).ready(function() {
        generateCalender();
    });

    function generateCalender(year, month) {
        // var year = year;
        // var month = month;
        if (year == undefined || year == '') {
            year = "<?php echo date('Y') ?>";
        }
        if (month == undefined || month == '') {
            month = "<?php echo date('m') ?>";

        }
        //alert(month);
        if (year != '' && month != '') {
            var url = "<?= base_url('calendar/getdate') ?>";
            //alert('year : '+year+'   month : '+month);
            var data = 'what=calender&year=' + year + '&month=' + month;

            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: url,
                data: data,
                success: function(data) {
                    // alert(data.dates);

                    $('#day-carousel').html(data.dates);
                    $('#heading-carousel .owl-prev').attr("onclick", "generateCalender(" + data.prev + ")");
                    $('#heading-carousel .owl-next').attr("onclick", "generateCalender(" + data.next + ")");
                    $('#heading-carousel .monthsData').html('<div class="item"><h1 class="default-heading test">' + data.str + '</h1></div>');
                    setdatescroller();
                    var x = $('.evo_day.on_focus', '#day-carousel')[0];
                    $(x).trigger('click');
                }
            });
        } else {
            alert('invalid date');
            return false;
        }
    }

    function setdatescroller() {
        $('.evo_day', '#day-carousel').each(function(index, element) {
            if ($(element).hasClass("on_focus")) {

                var i = Number(index) + 1;

                if (i < 13) {

                    $("#day-carousel").css('margin-left', '0px');
                } else if (i >= 13 && i < 16) {

                    $("#day-carousel").css('margin-left', '-115px');
                } else if (i >= 16 && i <= 25) {

                    $("#day-carousel").css('margin-left', '-430px');
                } else if (i > 25) {

                    $("#day-carousel").css('margin-left', '-640px');
                }
                return false;
            }
        });
    }
</script>

<script>
    function change(year, month, date) {
        var start_date = year + '-' + month + '-' + date;
        // alert(start_date)
        var d = new Date(start_date.replace(/-/g, "/")),
            month = '' + (d.getMonth() + 1),
            //day = '' + d.getDate(),
            day = '' + date,
            year = d.getFullYear();
        // alert(d)


        if (month.length < 2)
            month = '0' + month;
        if (day.length < 2)
            day = '0' + day;
        var selected_date = [year, month, day].join('-');
        // alert(selected_date)
        var ur = "<?= base_url('festivals-filter-artist') ?>"
        // alert(ur);
        $.ajax({
            url: ur,
            method: "POST",
            dataType: 'json',
            data: $('#event_search_form').serialize() + "&Selected_Date_=" + start_date + "&selected_date=" + selected_date,
            success: function(data) {
                // alert(data)
                $('.selectdate').removeClass('selectdate');
                var elements = document.getElementsByClassName('item selectdate');
                $("#" + data.selected_date).addClass("selectdate");
                document.getElementById('current_display_div').style.display = 'none';
                //document.getElementById(data.selected_date).style.backgroundColor = 'grey';
                document.getElementById(data.selected_date).style.backgroundColor = '';
                $('#html').html(data.data);

                $('#selDate').val(data.selected_date);
            }
        });
    }
</script>
<!-- <script>
   function change(year, month, date) {
      var start_date = year + '-' + month + '-' + date;
      var today = new Date(); 

      var selected_date_obj = new Date(start_date.replace(/-/g, "/"));
      
      if (selected_date_obj < today.setHours(0, 0, 0, 0)) {
         // alert("You cannot select a past date.");
         return; 
      }

      var month = '' + (selected_date_obj.getMonth() + 1),
          day = '' + date,
          year = selected_date_obj.getFullYear();

      if (month.length < 2)
         month = '0' + month;
      if (day.length < 2)
         day = '0' + day;
      
      var selected_date = [year, month, day].join('-');
      var ur = "<?= base_url('festivals-filter-artist') ?>";

      $.ajax({
         url: ur,
         method: "POST",
         dataType: 'json',
         data: $('#event_search_form').serialize() + "&Selected_Date_=" + start_date + "&selected_date=" + selected_date,
         success: function(data) {
            $('.selectdate').removeClass('selectdate');
            $("#" + data.selected_date).addClass("selectdate");
            document.getElementById('current_display_div').style.display = 'none';
            document.getElementById(data.selected_date).style.backgroundColor = '';
            $('#html').html(data.data);
            $('#selDate').val(data.selected_date);
         }
      });
   }
</script> -->
<script>
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

    // $(document).ready(function() {
    //     $("#search-box").keyup(function() {
    //         var Selected_Date_ = $("#selDate").val();
    //         var current_val = $(this).val();
    //         // alert(current_val);
    //         var ur = '<?= base_url() ?>';
    //         $.ajax({
    //             type: "POST",
    //             url: ur + "global-search-festivals",
    //             data: {
    //                 keyword: current_val,
    //                 Selected_Date_: Selected_Date_
    //             },
    //             /*beforeSend: function() {
    //                $("#search-box").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
    //             },*/
    //             success: function(data) {
    //                 // alert(data);
    //                 $("#suggesstion-box").show();
    //                 $("#suggesstion-box").html(data);
    //                 $("#search-box").css("background", "#FFF");
    //             }
    //         });
    //     });

    // });
    //To select a country name
    function selectProduct(val) {
        $("#search-box").val(val);
        getartist();
        $("#suggesstion-box").hide();
    }

    function getartist() {
        var Selected_Date_ = '';
        var url = '<?= base_url('festivals-filter-artist') ?>';
        $.ajax({
            url: url,
            method: "POST",
            data: $('#event_search_form').serialize() + "&Selected_Date_=" + Selected_Date_,
            dataType: "json",
            success: function(data) {
                $('#html').html(data.data);
                // $('#venue_location').html(data.data2);

            }
        });
    }
</script>

<script>
    $(document).on('keyup', '#search-box', function() {
        // console.log(this.value)
        if (this.value != "") {
            var Selected_Date_ = $("#selDate").val();
            var current_val = $(this).val();
            // alert(current_val);
            var ur = '<?= base_url() ?>';
            $.ajax({
                type: "POST",
                url: ur + "global-search-festivals-name",
                data: {
                    keyword: current_val,
                    Selected_Date_: Selected_Date_
                },
                success: function(data) {
                    // alert(data);
                    $("#suggesstion-box").show();
                    $("#suggesstion-box").html(data);
                    $("#search-box").css("background", "#FFF");
                }
            });
        }
    });
</script>

<script>
   document.getElementById("clearButton").addEventListener("click", function(event) {
      event.preventDefault();
      clearAllData();
      location.reload();
   });

   function clearAllData() {
      console.log("All data cleared");
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
   <script>