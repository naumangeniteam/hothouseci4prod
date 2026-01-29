<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Search Form</title>
    <!-- Include jQuery from a CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        #country-list {
            list-style-type: none;
            padding: 10px;
            margin: 0;
            max-width: 282px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            height: auto;
            overflow-y: scroll;
        }


        .festival-item {
            padding: 10px;
            margin: 0;
            cursor: pointer;
            border-bottom: 1px solid #eee;
            transition: background-color 0.3s ease;
        }

        .festival-item:last-child {
            border-bottom: none;
        }

        .festival-item:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>

<body>
    <form action="<?php echo base_url('all-products'); ?>" id="event_search_form" method="GET" class="userformes d-flex justify-content-between" style="gap:30px;">
        <input class="form-control" placeholder="Search here" id="search-box" name="festival_name" style="width: 18%; height: 30px;">
        <div id="suggesstion-box"></div>
        <input type="hidden" id="selDate" value="">
    </form>

    <script>
        document.getElementById('event_search_form').addEventListener('submit', function(event) {
            event.preventDefault(); 
            console.log('Form submission prevented');
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#search-box').on('keyup', function() {
                if (this.value !== "") {
                    var selectedDate = $("#selDate").val();
                    var currentVal = $(this).val();
                    var url = '<?= base_url() ?>global-search-festivals';

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {
                            keyword: currentVal,
                            Selected_Date_: selectedDate
                        },
                        success: function(data) {
                            $("#suggesstion-box").show().html(data);
                            $("#search-box").css("background", "#FFF");
                        }
                    });
                } else {
                    $("#suggesstion-box").hide();
                }
            });
        });

        function selectProduct(val) {
            window.location.href = val;
        }

        function getartist() {
            var selectedDate = $("#selDate").val();
            var url = '<?= base_url('elastic-search') ?>';

            $.ajax({
                url: url,
                method: "POST",
                data: $('#event_search_form').serialize() + "&Selected_Date_=" + selectedDate,
                dataType: "json",
                success: function(data) {
                    $('#html').html(data.data);
                }
            });
        }
    </script>
</body>

</html>