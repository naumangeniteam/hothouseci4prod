<div class="container-fluid hj-home-banner">
    <div class="row">
        <div class="col-md-12">
            <div class="hj-banner-img">
                <!--- Original code <img src="img/banner/<?= $item['image'] ?>" class="img-fluid w-100" alt="home-banner"> ---->
                <img class="show-desktop" src="img/banner/home-banner.svg" class="img-fluid w-100" alt="home-banner">
                <img class="show-mobile" src="img/banner/mb-home.jpg" alt="home-banner">
            </div>
            <div class="hj-banner-details">
                <h1>HOT HOUSE JAZZ GUIDE</h1>
                <h4>Comprehensive list of Jazz Events in New York</h4>
            </div>
        </div>
    </div>
</div>
<!--- Home Search Events ---->
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <?php
            $where5['where']       =   "is_active = '1'";
            $tbl5                =   'setting_tbl as ftable';
            $shortField5         =   'id DESC';
            $shortField6          =   'type_name ASC';
            $data['setting_tbl'] =    $this->common_model->getData('multiple', $tbl5, $where5);
            ?>
            <a href="<?= base_url('home') ?>" class="hhj-home-logo"><img src="img/banner/logo.svg" class="img-logo" alt="" /></a>

            <form action="<?php echo base_url('all-products'); ?>" id="event_search_form" method="GET" class="userformes hhj-home-search">
                <div class="hhj-search-field">
                    <img class="search-icon" src="icons/search.svg" alt="user Login" />
                    <input class="form-control artist-event searched searched-mob" placeholder="Search by event or artist name " id="search-box" name="event_title" />
                    <img class="filter-icon" src="icons/filter.svg" onclick="toggleSection()" alt="user Login" />
                    <div class="suggesstion-box" id="suggesstion-box"></div>
                </div>

                <div id="toggleSection">
                    <div class="hhj-search-fields">
                        <div class="hhj-adv-box">
                            <input class="form-control tags" id="search-box1" value="Search by event tag" name="keyword" />
                            <div id="suggesstion-box1"></div>
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
                        <div class="clear-filter">
                            <a href="#" id="clearButton"><img src="icons/eraser.svg" alt="user Login" /> Clear</a>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="venue" value="<?= $location['id']; ?>">
                <input type="hidden" id="artist" value="<?= $artist['id']; ?>">
                <input type="hidden" id="selDate" value="">
            </form>

            <?= view('layouts/front/home-tabs') ?>
        </div>
        <div class="col-md-2"></div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Decode HTML entities and output iframe -->
            <?= html_entity_decode($ALLDATA[0]['name']) ?>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>