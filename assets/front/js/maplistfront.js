jQuery(document).ready(function($){

    //Catch return button on search boxes
    $('body').on('keypress','.prettySearchValue,.prettySearchLocationValue',function(e){
        var code = e.keyCode || e.which;
        if(code == 13) {
            $(this).siblings('.doPrettySearch').click();
        }
    });

    //Print button for directions
    $('body').on('click','.printDirections',function(){
        var content = $(this).prev().html();
        printContent(content);
        return false;
    });

    /*ViewModel*/
    function MapViewModel(mapObject,mapid) {

        var self = this;

        self.mapID = mapid;
        self.MapHolder = $('#MapListPro' + self.mapID);

        //Sorting
        self.sortDirection = ko.observable(mapObject.options.orderdir);
        self.selectedSortType = ko.observable(mapObject.options.initialsorttype);

        //Used for unselected markers
        self.useCategoryFilter =  ko.observable(false);

        //Options
        self.maximumResults = null;

        //Location stuff
        self.geocodedLocation = ko.observable();
        self.geoenabled = (mapObject.options.geoenabled.toLowerCase() === 'true');
        self.geoHomePosition = null;

        self.userLocation = {};//new Location();

        //Default start position and zoom
        self.centrePoint = '';
        self.defaultZoom = '';
        self.hasZoomed = false;

        self.markers = []; //The markers container

        //Stylers
        self.customstylers = mapObject.options.customstylers;

        self.mapOptions = {
            zoom: 16,
            center: tempCentre,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            panControl: false,
            zoomControl: true,
            mapTypeControl: true,
            //scrollwheel: false,
            scaleControl: true,
            streetViewControl: true,
            overviewMapControl: false
        };

        if(self.customstylers !== ''){
            self.mapOptions.styles = JSON.parse(self.customstylers);
        }

        //Directions
        self.awaitingGeoDirections = null;
        self.awaitingGeoDirectionsLocation = null;
        self.directionsType = "";
        self.directionsService = new google.maps.DirectionsService();
        self.directionsRenderer = new google.maps.DirectionsRenderer({draggable: true});

        //The chosen distance
        self.chosenFromDistance = ko.observableArray();

        //The dropdown menu of distances used by location search
        self.distanceFilters = [];

        //Search queries
        self.query = ko.observable('');
        self.locationquery = ko.observable('');

        //Check if either search is fired yet
        self.anySearchTermsEntered = ko.computed(function(){
            return self.query().length > 0 || self.locationquery().length > 0;
        },this);

        //Unit system
        self.unitSystemFriendly = maplistScriptParamsKo.measurementUnits == "METRIC" ? 'Kms' : 'Miles';

        //Paging
        self.pageSize = ko.observable(mapObject.options.locationsperpage);   //Items per page
        self.pageIndex = ko.observable(0);  //Current page

        //GET DATA FROM JSON AND CREATE VARS AND OBJECTS
        //==============================================

        //Map the locations to KO objects
        self.mapLocations = ko.utils.arrayMap(mapObject.locations, function (item) {
            var tempItem = new Location(item);
            tempItem.distanceAway = ko.observable(null);
            tempItem.searchDistanceAway = ko.observable(null);
            return tempItem;
        });

        //Map the categories to KO objects
        self.mapCategories = ko.utils.arrayMap(mapObject.categories, function (item) {
            return new Category(item.title, item.slug);
        });

        self.customCategories = {};

        if(mapObject.allTaxonomies !== undefined){
            //Map custom categories to KO objects
            ko.utils.arrayForEach(mapObject.allTaxonomies["taxonomyLookup"], function (taxonomyName) {

                self.customCategories[taxonomyName] = ko.utils.arrayMap(mapObject.allTaxonomies[taxonomyName],function(taxonomy){
                    return new Category(taxonomy.title, taxonomy.slug, taxonomyName);
                });

            });
        }

        //Distance filters
        $.each(mapObject.options.searchdistances,function(index,distance){
            self.distanceFilters.push(new Distance(distance));
        });

        //Home location
        self.homelocation = mapObject.homelocation !== '' ? mapObject.homelocation : null;
        //If a home location is set make it centre
        //TODO:Don't use manual lat/lng here
        var tempCentre = self.homelocation !== null ? new google.maps.LatLng(self.homelocation.latitude, self.homelocation.longitude) : new google.maps.LatLng(51.62921, -0.7545);

        //Directions
        switch (mapObject.options.defaultdirectionsmode){
            case "WALKING" :
                self.directionsType = google.maps.TravelMode.WALKING;
                break;
            case "BICYCLING" :
                self.directionsType = google.maps.TravelMode.BICYCLING;
                break;
            case "TRANSIT" :
                self.directionsType = google.maps.TravelMode.TRANSIT;
                break;
            default :
                self.directionsType = google.maps.TravelMode.DRIVING;
                break;
        }


        /*
         * CUSTOM BINDINGS
         ===============================================*/

        /* Binding to make content appear with 'slideIn' effect */
        ko.bindingHandlers['slideIn'] = {
            'update': function(element, valueAccessor) {
                var options = valueAccessor();
                if(options() === true){
                    $(element).slideDown(300);
                }
            }
        };

        /* Binding to make content disappear with 'slideOut' effect */
        ko.bindingHandlers['slideOut'] = {
            'update': function(element, valueAccessor) {
                var options = valueAccessor();
                if(options() === false){
                    $(element).slideUp(300);
                }
            }
        };

        /*
         * EVENTS
         * Note: some events are complex and appear later in this file
         ===============================================*/

        //Show categories list
        self.showCategories = function(data, element) {
            var thisButton = $(element.currentTarget);
            thisButton.siblings('.prettyFileFilters ').slideToggle(200);
        };

        //Category click
        self.selectCategory = function(category) {

            //If single category mode
            // //de-select all categories
            // ko.utils.arrayForEach(self.mapCategories, function(item) {
            //     item.selected(false);
            // });


            category.selected(!category.selected());
            self.useCategoryFilter(true);
        };

        //Category click
        self.selectCustomCategory = function(data,event) {

            //EACH TERM
            ko.utils.arrayForEach(self.customCategories[data.taxonomyName],function(term){
                if(data.slug === term.slug){
                    term.selected(!term.selected());
                }
            });

        };

        //Geolocation stuff
        //=========================
        //See if geo is neededmap
        if (self.geoenabled === true && navigator.geolocation)
        {
            //Check to see available (from another map)
            if(self.geoHomePosition === null){
                navigator.geolocation.getCurrentPosition(getGeo,getGeoError);
            }
            else{
                getGeo(self.geoHomePosition);
            }
        }
        else{
            //If geo enabled but no navigator (<ie8)
            if(self.geoenabled === true){
                getGeoError();
            }
        }


        /*
        * Takes a list of locations and a lat/lng and updates them with the distance away for each
        */
        function updateAllLocationsWithDistanceFrom(locationList,fromLat,fromLng){
            //Loop over all of the locations and add a distance to each of them
            ko.utils.arrayForEach(locationList, function(location) {
                var distanceAway = calculateDistance(location.latitude,location.longitude,fromLat,fromLng);
                location.distanceAway(distanceAway);
            });
        }

        //Get the geocoded location for the user
        //This is used for onLoad geo and for directions geo
        function getGeo(position)
        {
            //Set the global var for this first time through
            if(self.geoHomePosition === null){
                self.geoHomePosition = {latitude : position.coords.latitude, longitude : position.coords.longitude};
            }

            //If geo is for directions
            if(self.awaitingGeoDirections !== null){
                self.showDirections(self.geoHomePosition.latitude + ',' + self.geoHomePosition.longitude,self.awaitingGeoDirectionsLatLng,self.awaitingGeoDirections);
                self.awaitingGeoDirections = null;
                self.awaitingGeoDirectionsLatLng = null;
            }
            else{
                //Loop over all of the locations and add a distance to each of them
                updateAllLocationsWithDistanceFrom(self.mapLocations,position.coords.latitude,position.coords.longitude);

                //Set sort to distance
                self.selectedSortType('distance');

                //Fire a sort now
                self.sortList('distance');
            }

            //Put a marker on the map for the geocoded location
            showGeoMarker(position.coords.latitude,position.coords.longitude);
        }



        //Fallback geolocate uses ip location
        function getGeoError(position)
        {
            $.getJSON("http://www.geoplugin.net/json.gp?jsoncallback=?",
                function (data) {

                    //Set the global var for this first time through
                    if(self.geoHomePosition === null) {
                        self.geoHomePosition = { latitude: data['geoplugin_latitude'], longitude: data['geoplugin_longitude'] };
                    }

                    //If geo is for directions
                    if(self.awaitingGeoDirections !== null){
                        self.showDirections(self.geoHomePosition.latitude + ',' + self.geoHomePosition.longitude,self.awaitingGeoDirectionsLatLng,self.awaitingGeoDirections);
                        self.awaitingGeoDirections = null;
                        self.awaitingGeoDirectionsLatLng = null;
                    }
                    else{
                        //Loop over all of the locations and add a distance to each of them
                        updateAllLocationsWithDistanceFrom(self.mapLocations,data['geoplugin_latitude'],data['geoplugin_longitude']);

                        //Set sort to distance
                        self.selectedSortType('distance');
                        //Fire a sort now
                        self.sortList('distance');
                    }

                    showGeoMarker(self.geoHomePosition.latitude,self.geoHomePosition.longitude);
                }
            );
        }

        /*
        * Put a marker on the map for the geocoded location
        */
        function showGeoMarker(lat,lng){
            //Create marker for geo
            if(!self.userLocation._mapMarker){

                var position = new google.maps.LatLng(lat, lng);
                var mapToUse = self.map;

                //Create marker
                //-----------------------------
                var marker = new google.maps.Marker({
                        map: mapToUse,
                        position: position,
                        content: '',
                        optimized: false,
                        animation: google.maps.Animation.DROP
                });

                    //Set the marker
                    //-----------------------------u
                    self.userLocation._mapMarker = marker;
                }

                //Show this location
                self.userLocation._mapMarker.setAnimation(google.maps.Animation.DROP);
                self.userLocation._mapMarker.setVisible(true);
                self.resetMapZoom();
        }


        /*
         * MAP FUNCTIONALITY
         *
         ==========================================*/

        if (mapObject.options.viewstyle != 'listonly') {

            //Create a map
            self.map = new google.maps.Map($('#map-canvas' + self.mapID, self.MapHolder)[0], self.mapOptions);

            //add Open Street Map
            self.map.mapTypes.set("OSM", new google.maps.ImageMapType({
                getTileUrl: function (coord, zoom) {
                        return "http://tile.openstreetmap.org/" + zoom + "/" + coord.x + "/" + coord.y + ".png";
                },
                tileSize: new google.maps.Size(256, 256),
                name: "Open Street Map",
                maxZoom: 18
            }));

            //Get default zoom level after bounds updated
            if (mapObject.options.viewstyle != 'listonly') {

                //Set centrepoint for future use (no results found etc.)
                //Fitbounds happens async so need this to get zoom
                var boundsChangeBoundsListener = google.maps.event.addListener(self.map, 'bounds_changed', function (event) {
                    if (!self.defaultZoom) {
                        self.defaultZoom = self.map.getZoom();

                        //If home mode make it centre
                        if(self.homelocation){
                            self.centrePoint = new google.maps.LatLng(self.homelocation.latitude, self.homelocation.longitude) ;
                        }
                        else{
                            //Otherwise get the centrepoint from the current zoomed bounds
                            self.centrePoint = self.map.getCenter();
                        }

                        //Remove this listener as it is only needed once
                        google.maps.event.removeListener(boundsChangeBoundsListener);
                    }
                });
            }

            //INITIAL MAP TYPE
            var mapTypeIdToUse;

            switch (mapObject.options.initialmaptype.toLowerCase()) {
                case "hybrid":
                    mapTypeIdToUse = google.maps.MapTypeId.HYBRID;
                    break;
                case "roadmap":
                    mapTypeIdToUse = google.maps.MapTypeId.ROADMAP;
                    break;
                case "satellite":
                    mapTypeIdToUse = google.maps.MapTypeId.SATELLITE;
                    break;
                case "osm":
                    mapTypeIdToUse = 'OSM';
                    break;
                default:
                    mapTypeIdToUse = google.maps.MapTypeId.HYBRID;
                    break;
            }

            //Pass the map type into the Google map
            self.map.setOptions({
                mapTypeControlOptions: {
                    mapTypeIds: [
                                google.maps.MapTypeId.ROADMAP,
                                google.maps.MapTypeId.SATELLITE,
                                'OSM'
                            ],

                    style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
                },
                mapTypeId : mapTypeIdToUse
            });

            //MARKER CLUSTERING
            //Create marker clusterer if needed
            if (mapObject.options.clustermarkers == 'true') {
                var mcOptions = {
                    gridSize: mapObject.options.clustergridsize,
                    maxZoom: mapObject.options.clustermaxzoomlevel
                };

                self.markerClusterer = new MarkerClusterer(self.map,[],mcOptions);
            }

            //HOME LOCATION
            //If home location set create the marker for it
            if(self.homelocation !== null){
                if(self.homelocation._mapMarker !== undefined){

                    var image = new google.maps.MarkerImage(
                        self.homelocation.pinImageUrl
                    );

                    var position = new google.maps.LatLng(self.homelocation.latitude, self.homelocation.longitude);
                    var mapToUse = self.map;

                    //Create marker
                    //-----------------------------
                    var marker = new google.maps.Marker({
                            map: mapToUse,
                            position: position,
                            title: self.homelocation.title,
                            content: '',
                            icon: image,
                            optimized: false,
                            animation: google.maps.Animation.DROP
                    });

                    //Set the marker
                    //-----------------------------u
                    self.homelocation._mapMarker = marker;
                 }

                //Show this location
                self.homelocation._mapMarker.setAnimation(google.maps.Animation.DROP);
                self.homelocation._mapMarker.setVisible(true);
            }

            /*
             * INFOBOXES
             */
            //If infoboxes are switched off
            if(maplistScriptParamsKo.disableInfoBoxes == 'true'){
                self.infowindow = new google.maps.InfoWindow({pixelOffset: new google.maps.Size(-13, 33)});
            }
            else{
                //Infobox switched on
                self.infoBoxOptions = {
                    content: "",
                    //boxStyle : {
                      //    width : "500px"
                     //},
                    disableAutoPan: false,
                    alignBottom: true,
                    pixelOffset: new google.maps.Size(-203, -40),
                    zIndex: 1500,
                    boxClass:"infoWindowContainer",
                    closeBoxMargin: "10px 2px 2px 2px",
                    closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif",
                    infoBoxClearance: new google.maps.Size(40, 40),
                    isHidden: false,
                    pane: "floatPane",
                    enableEventPropagation: false
                };

                self.infowindow = new InfoBox(self.infoBoxOptions);
            }

            //Catch the Close event of the infowindow
            google.maps.event.addListener(self.infowindow, 'closeclick', function () {
                //If set not to zoom out again leave as it is
                if(mapObject.options.keepzoomlevel != 'true'){
                    self.resetMapZoom();
                }

                //Close all of the locationc
                $.each(self.mapLocations,function(index, thislocation){
                    thislocation.expanded(false);
                });
            });
        }



        //Reset all
        //=====================
        self.resetAll = function() {
            //clear search
            self.query('');
            self.locationquery('');

            //Reset bounds
            self.bounds = '';

            //Clear out zoom marker
            self.hasZoomed = false;

            //de-select all categories
            ko.utils.arrayForEach(self.mapCategories, function(item) {
                item.selected(false);
            });

            //Deselect all items as filtering only kicks in when none selected
            $.each(self.customCategories,function(index,thiscategory){
                $.each(thiscategory,function(index,term){
                    term.selected(false);
                });
            });

            //Ignore categories again
            self.useCategoryFilter(false);
        };

        //Search
        //======================================

        //Search distance
        function Distance(value) {
            if (maplistScriptParamsKo.measurementUnits == 'METRIC') {
                this.label = value + ' ' + maplistScriptParamsKo.measurementUnitsMetricText;
            }
            else{
                this.label = value + ' ' + maplistScriptParamsKo.measurementUnitsImperialText;
            }

            //If combo search
            if (mapObject.options.simplesearch === 'combo') {
                this.label = maplistScriptParamsKo.distanceWithinText + ' ' + this.label + ' ' + maplistScriptParamsKo.distanceOfText;
            }

            this.value = value;
        }


        /*
        * SEARCH
        *
        ==========================================*/

        //see if search param passed to page
        var terms = getParameterByName('locationSearchTerms');

        //TODO:Update to handle combo search
        //If search terms found
        if(terms !== ''){
            //Query object for search
            if (mapObject.options.simplesearch == 'true') {
                self.query(terms);
            }
            else{
                var geocoder = new google.maps.Geocoder();
                var address = terms;

                //Add default country if set
                if (mapObject.options.country !== '') {
                    address = address + ', ' + mapObject.options.country;
                }

                //TODO:Can this ever be not defined?
                if (geocoder) {
                    geocoder.geocode({ 'address': address }, function (results, status) {
                       if (status == google.maps.GeocoderStatus.OK) {
                            //We got an address back so set this
                            self.geocodedLocation({lat:results[0].geometry.location.lat(),lng:results[0].geometry.location.lng()});
                            //Set the query string for checking
                            self.locationquery(address);
                            self.sortDirection('dec');
                            self.selectedSortType('distance');
                            self.sortList('distance');
                       }
                       else {
                           //Geocode fail
                           //TODO:Add front end message here for failure?
                          console.log("Geocoding failed: " + status);
                       }
                    });
                 }
            }
        }

        //Update location search
        self.locationSearch = function(data,element){

            //Get the text from the search box
            var locationTerms = ($(element.currentTarget).siblings('.prettySearchValue')).val();

            //Create a geocoder to send to Google
            var geocoder = new google.maps.Geocoder();

            //Add default country if set
            if (mapObject.options.country !== '') {
                locationTerms = locationTerms + ', ' + mapObject.options.country;
            }

            //Make sure geocode exists and send it on
            if (geocoder) {
                geocoder.geocode({ 'address': locationTerms }, function (results, status) {
                   if (status == google.maps.GeocoderStatus.OK) {
                       //We got an address back so set this
                       self.geocodedLocation({lat:results[0].geometry.location.lat(),lng:results[0].geometry.location.lng()});
                       //Set the query string for checking
                       self.locationquery(locationTerms);
                        //Set sort to distance
                        self.sortDirection('dec');
                        self.selectedSortType('distance');
                        self.sortList('distance');
                   }
                   else {
                       //Geocode fail
                       //TODO:Add front end message here for failure?
                      console.log("Geocoding failed: " + status);
                   }
                });
             }
        };

        //Update combo search
        self.comboSearch = function(data,element){
            var searchTerms = ($(element.currentTarget).siblings('.prettySearchValue')).val();

            //Add this check for ie9 placeholder issues
            var searchTermsPlaceHolder = ($(element.currentTarget).siblings('.prettySearchValue')).attr('placeholder');
            if(searchTerms == searchTermsPlaceHolder){
                searchTerms = '';
            }

            var locationTerms = ($(element.currentTarget).siblings('.prettySearchLocationValue')).val();

            //Add this check for ie9 placeholder issues
            var locationTermsPlaceHolder = ($(element.currentTarget).siblings('.prettySearchLocationValue')).attr('placeholder');
            if(locationTerms == locationTermsPlaceHolder){
                locationTerms = '';
            }

            var geocoder = new google.maps.Geocoder();

            //Add default country if set
            if (mapObject.options.country !== '' && locationTerms.length > 0) {
                locationTerms = locationTerms + ', ' + mapObject.options.country;
            }

            if (geocoder && locationTerms.length > 0) {
                geocoder.geocode({ 'address': locationTerms }, function (results, status) {
                   if (status == google.maps.GeocoderStatus.OK) {
                       //We got an address back so set this
                       self.geocodedLocation({lat:results[0].geometry.location.lat(),lng:results[0].geometry.location.lng()});
                       //Set the query string for checking
                       self.locationquery(locationTerms);

                       self.query(searchTerms);
                        //Set sort to distance
                        self.sortDirection('dec');
                        self.selectedSortType('distance');
                        self.sortList('distance');
                   }
                   else {
                       //Geocode fail
                       //TODO:Add front end message here for failure?
                      console.log("Geocoding failed: " + status);
                      //Fallback to just text
                      self.query(searchTerms);
                   }
                });
             }
             else{
                 self.query(searchTerms);
             }
        };

        //NOTE: Add the following to delay search .extend({ throttle: 500 });

        //Clear search
        self.clearSearch = function() {
            $('.prettySearchValue', self.MapHolder).val('');
            if ($('.prettySearchLocationValue', self.MapHolder).length) {
                $('.prettySearchLocationValue', self.MapHolder).val('');
            }

            self.resetAll();
        };



        //LOCATION CLICK
        //======================================
        self.locationClick = function(location,element){

            //Uncomment to make item click go straight to detail
            // window.location.href = location.locationUrl;
            // return false;

            //Get elements we need
            var targetItem =  $(element.currentTarget);
            var clickedItem =  $(element.target);
            var parentItem = clickedItem.closest('.mapLocationDetail');
            var mapLocationDetail = targetItem.children('.mapLocationDetail');

            //If this is a link in the detail area then exit
            if(parentItem.length){ return true; }

            //Find out if this is an already showing item
            if(location.expanded() === false){

                //Close all locations initially except current
                $.each(self.mapLocations,function(index, thislocation){
                    thislocation.expanded(false);
                });

                //Show the infowindow
                self.showInfoWindow(location);

            }
            else{

                if (mapObject.options.viewstyle != 'listonly') {

                    //Reverse the show status
                    self.infowindow.close();

                    if(mapObject.options.keepzoomlevel != 'true'){
                        self.resetMapZoom();
                    }
                }

                //Clear directions
                (targetItem.find('.mapLocationDirectionsHolder')).html('');
                //Clear the map set on the directions renderer
                self.directionsRenderer.setMap(null);

            }

            location.expanded(!location.expanded());

        };



        //Sorting
        //===================

        //If geoenabled the sort button becomes a sort menu so two items need binding
        if (self.geoenabled == 'true' || mapObject.options.simplesearch != 'true') {
            //show sort list click
            $('.showSortingBtn', self.MapHolder).click(function () {
               var clicked = $(this);

               clicked.siblings('.prettyFileSorting').slideToggle(200);
               return false;
           });
        }

        //Sort click
        self.sortList = function(sortType, element) {

            if(element !== null && element !== undefined){
                //Update UI
                var thisButton = $(element.currentTarget);
                sortType = thisButton.data('sorttype');

                //Reverse arrow on button
                thisButton.toggleClass('sortAsc');

            }else{
                //Go get the button if distance sort as this can be called by other functions
                if(self.selectedSortType() == 'distance'){
                    var distanceSortButton = $(".prettyFileSorting li:nth-child(2) a",self.MapHolder);
                    if(distanceSortButton.length){
                        distanceSortButton.toggleClass('sortAsc');
                    }
                }
            }

            if (sortType == self.selectedSortType()) {
                //No change to type
                //...so change direction

                self.sortDirection(self.sortDirection() == 'asc' ? 'dec' :  'asc');

            } else {
                self.selectedSortType(sortType);
                self.sortDirection('asc');
            }
        };



        //Get directions
        //===================
        self.getDirectionsClick = function(location,element){
           var thisButton = $(element.currentTarget);
           var endLocation = location.latitude + ',' +  location.longitude;

           //If geo
           if(thisButton.hasClass('getdirectionsgeo')){
               //See if home set already
               if(self.geoHomePosition !== null){
                   //from,to,button
                   self.showDirections(self.geoHomePosition.latitude + ',' + self.geoHomePosition.longitude,endLocation,thisButton);
               }
               else{
                    self.awaitingGeoDirections = thisButton;
                    self.awaitingGeoDirectionsLatLng = endLocation;

                    //See if geo is needed
                    if (navigator.geolocation)
                    {
                        //Check to see available (from another map)
                        if(self.geoHomePosition === null){
                            navigator.geolocation.getCurrentPosition(getGeo,getGeoError);
                        }
                        else{
                            getGeo(self.geoHomePosition);
                        }
                    }
                    else{
                        //If geo enabled but no navigator (<ie8)
                        getGeoError();
                    }
               }
           }
           else{
                //The start/end locations
                var locationEntryField = thisButton.siblings('.directionsPostcode');
                var startLocation = locationEntryField.val();

                //If no location entered show error
                if(startLocation === ''){
                    locationEntryField.addClass('error');
                }
                else{
                    locationEntryField.removeClass('error');
                    //Show directions with our data
                    self.showDirections(startLocation,endLocation,thisButton);
                }


           }

           return false;
        };

        /*
         * Show the directions in thee list item
         */
        self.showDirections = function(from, to, buttonClicked) {
            if (mapObject.options.viewstyle != 'listonly') {
                //Link Renderer to the map
                self.directionsRenderer.setMap(self.map);
            }

            //The directions list div
            var directionsHolder = buttonClicked.siblings('.mapLocationDirectionsHolder');

            //Measurement units to use
            var unitSystem;
            if (maplistScriptParamsKo.measurementUnits == "METRIC") {
                unitSystem = google.maps.UnitSystem.METRIC;
            } else {
                unitSystem = google.maps.UnitSystem.IMPERIAL;
            }

            //Request object
            var request = {
                origin: from,
                destination: to,
                travelMode: self.directionsType,
                unitSystem: unitSystem
            };

            self.directionsRenderer.setPanel(directionsHolder[0]);

            self.directionsService.route(request, function(response, status) {
                if (status == google.maps.DirectionsStatus.OK) {
                    self.directionsRenderer.setDirections(response);
                    //Don't add the print button twice
                    if(!$(directionsHolder[0]).next().hasClass('printDirections')){
                        $(directionsHolder[0]).after('<a href="#" class="printDirections corePrettyStyle">' + maplistScriptParamsKo.printDirectionsMessage + '</a>');
                    }

                    //hide the infowindow
                    self.infowindow.close();
                } else {
                    console.log(status);
                }
            });
        };

        //TEXT SEARCH
        self.getLocationsWithMatchingTerm = function(locations,searchTerm){

            return ko.utils.arrayFilter(locations, function(location) {
                var textFound = false;

                //Search title and description
                if(location.title.toLowerCase().indexOf(searchTerm) != -1 || location.description.toLowerCase().indexOf(searchTerm) != -1 ){
                    textFound = true;
                }

                //Search categories
                $.each(location.categories, function(index, locationCat) {
                    if (locationCat.title.toLowerCase().indexOf(searchTerm) != -1) {
                        textFound = true;
                    }
                });

                if(textFound === true){ return location;}

            });
        };

        //LOCATION SEARCH
        self.getLocationsByDistance = function(locations,geocodedLocation){
            return ko.utils.arrayFilter(self.mapLocations, function(location) {
                var distanceAway = calculateDistance(location.latitude,location.longitude,geocodedLocation.lat,geocodedLocation.lng);

                location.searchDistanceAway(distanceAway);

                 if(parseInt(location.searchDistanceAway(),10) < self.chosenFromDistance()){return location;}
            });
        };


        //Main loop
        //===========================

        //Filtered locations
        self.filteredLocations = ko.computed(function () {

            //Search query
            var locations = '';
            var geocodedLocation = self.geocodedLocation();
            var search = self.query().toLowerCase();


            if(self.anySearchTermsEntered()){
                //TEXT SEARCH
                if (mapObject.options.simplesearch == 'true') {
                    locations = self.getLocationsWithMatchingTerm(self.mapLocations,search);
                }
                else if (mapObject.options.simplesearch == 'false') {
                    //LOCATION SEARCH
                    locations = self.getLocationsByDistance(self.mapLocations,geocodedLocation);
                }
                else{
                    //COMBO SEARCH
                    if(self.locationquery().length > 0){
                        //LOCATION SEARCH
                        locations = self.getLocationsByDistance(self.mapLocations,geocodedLocation);
                    }else{
                        //No location entered or found
                        locations = self.mapLocations;
                    }

                    //TEXT SEARCH
                    locations = locations = self.getLocationsWithMatchingTerm(locations,search);
                }
            }
            else{
                //HOME LOCATION SET
                //Check to see if homelocation set as we use this for distance calc
                if(self.homelocation != null && self.homelocation != ''){
                        locations = ko.utils.arrayFilter(self.mapLocations, function(location) {
                            var distanceAway = calculateDistance(location.latitude,location.longitude,self.homelocation.latitude,self.homelocation.longitude);
                            location.distanceAway(distanceAway);
                            self.sortDirection('dec');
                            self.selectedSortType('distance');
                            self.sortList('distance');
                            return location;
                        });

                }else{
                    //No search terms, so send all locations through
                    locations = self.mapLocations;
                }
            }

            //CATEGORY FILTERING
            //===========================

            //TODO:Switch to arrayfirst if possible for speed

            //Only filter if one (or more) has been selected
            if(self.useCategoryFilter() === true){

                locations =  ko.utils.arrayFilter(locations,function(location){
                    var found = false;

                    //Get all selected categories
                    var selectedCategories = ko.utils.arrayFilter(self.mapCategories, function(category) {
                        if (category.selected()) { return category; }
                    });

                    //loop selectedCategories
                    $.each(selectedCategories, function(index, category) {

                        //loop location categories
                        $.each(location.categories, function(index, locationCat) {
                            //See if this location is in this category
                            if (category.slug == locationCat.slug) {
                                //categorisedLocations.push(location);
                                found = true;
                                return false;
                            }
                        });

                        // //Break out of outer loop
                        // if (found) {
                        //     return false;
                        // }

                    });

                    if(found){
                        return location;
                    }
                });
            }


            //Custom category filtering
            //====================================

            if(mapObject.allTaxonomies !== undefined){
                var selectedCategories = [];

                //Get all categories with at least one item selected
                 $.each(mapObject.allTaxonomies["taxonomyLookup"], function(index, taxonomyName) {

                    //Get all selected terms for current custom taxonomy - uses arrayfirst so it quits after one found
                    var hasSelected = ko.utils.arrayFirst(self.customCategories[taxonomyName], function(term) {
                        if (term.selected()) { return term; }
                    });

                    if(hasSelected !== null){
                        selectedCategories.push(self.customCategories[taxonomyName]);
                    }
                });


                //Filter by selected categories
                //=============================

                //Loop over locations
                locations =  ko.utils.arrayFilter(locations,function(location){

                    var found = true;
                    var foundInTaxonomy = false;

                    //TODO: Recursive search would be quickest here
                    ///function(locationList,termList)

                    //Loop the selected categories and see if they're selected
                    $.each(selectedCategories,function(index,customTermArray){

                        //Loop over the terms in this category
                        ko.utils.arrayForEach(customTermArray,function(customTerm){

                            //Not a selected term so quit
                            if(!customTerm.selected()){return;}

                            foundInLocation = ko.utils.arrayFirst(location.customCategories[customTerm.taxonomyName],function(thisLocation){

                                //See if this location is in this category
                                if (customTerm.slug == thisLocation.slug) {
                                    return thisLocation;
                                }
                            });

                            //Found in this location
                            if(foundInLocation !== null){
                                foundInTaxonomy = true;
                            }

                        });

                        //If not found in this category then this location should be hidden
                        if (foundInTaxonomy === false) {
                            found = false;
                        }

                    });

                    //Made it through all checks and is still found
                    if(found === true){
                        return location;
                    }

                });
            }

            //Update the distance text on each location
            $.each(locations,function(index,location){

                var distanceTypeToUse = null;

                //Also set distance text in same query to avoid looping twice
                if (self.locationquery()) {
                    distanceTypeToUse = location.searchDistanceAway();

                } else {
                    //Don't show distance on non-geo maps
                    if ((self.geoenabled === true || (self.homelocation !== null)) && location.distanceAway()) {
                        //Show distance from home
                        distanceTypeToUse = location.distanceAway();
                    }
                }

                //Show distance if set
                location.friendlyDistance(distanceTypeToUse === null ? '' : ' (' + distanceTypeToUse + ' ' + self.unitSystemFriendly + ')');
            });

            //Sort by sort selection
            if(self.sortDirection() == 'asc' && self.selectedSortType() == 'title'){
                locations.sort(asc_bytitle);
            }
            else if(self.sortDirection() == 'dec' && self.selectedSortType() == 'title'){
                locations.sort(dec_bytitle);
            }
            else if(self.sortDirection() == 'asc' && self.selectedSortType() == 'distance'){
                locations.sort(asc_bydistance);
            }
            else if(self.sortDirection() == 'dec' && self.selectedSortType() == 'distance'){
                locations.sort(dec_bydistance);
            }

            //Reduce results number if needed
            if (mapObject.options.limitresults != -1) {
                locations = locations.slice(0, parseInt(mapObject.options.limitresults,10));
            }

            //If only one result expand it
            if (locations.length == 1) {
                locations[0].expanded(true);
            }

            //Reset paging
            self.pageIndex(0);

            return locations;
        }, self).extend({ throttle: 10 });

        //Map binding
        //============================
        ko.bindingHandlers.map = {
            update: function (element, valueAccessor, allBindingsAccessor, viewModel) {

                // First get the latest data that we're bound to
                var value = valueAccessor();
                var search = viewModel.query();
                // Next, whether or not the supplied model property is observable, get its current value
                var valueUnwrapped = ko.utils.unwrapObservable(value);

                //if(valueUnwrapped.length <= 0){ return []; }

                //Hide all markers
                $.each(viewModel.mapLocations, function (index, location) {
                    if (location._mapMarker) {
                        location._mapMarker.setVisible(false);
                    }
                });

                //Loop all locations
                $.each(valueUnwrapped, function (index, location) {
                    //if marker is not already set on the location
                    if(!location._mapMarker){

                        var image = new google.maps.MarkerImage(
                            location.pinImageUrl
                        );

                        var position = new google.maps.LatLng(location.latitude, location.longitude);
                        var mapToUse = self.map;

                        if (mapObject.options.clustermarkers == 'true') {
                            mapToUse = null;
                        }

                        //Create marker
                        //-----------------------------
                        var marker = new google.maps.Marker({
                                map: mapToUse,
                                position: position,
                                title: location.title,
                                content: '',
                                icon: image,
                                optimized: false,
                                animation: google.maps.Animation.DROP
                        });

                        //Marker click
                        //-----------------------------
                        google.maps.event.addListener(marker, 'click', function () {
                            //Show the bubble
                            viewModel.showInfoWindow(location);
                            //Hide the item on
                            location.expanded(!location.expanded());
                        });

                        //Set the marker
                        //-----------------------------u
                        location._mapMarker = marker;
                        viewModel.markers.push(marker);
                    }

                    //Show this location
                    location._mapMarker.setAnimation(google.maps.Animation.DROP);
                    location._mapMarker.setVisible(true);
                });

                //Set zoom
                viewModel.resetMapZoom();
            }
        };



        /*
         * Set the zoom level back to where it should be
         */
        self.resetMapZoom = function () {

            //Clear cluster markers
            if (mapObject.options.clustermarkers == 'true') {
                self.markerClusterer.clearMarkers();
            }

            if (mapObject.options.startlat) {
                //MANUAL LOCATION

                //If centre point doesn't exist yet set it
                if (self.centrePoint === '') {
                    self.centrePoint = new google.maps.LatLng(mapObject.options.startlat, mapObject.options.startlong);
                    self.defaultZoom = parseInt(mapObject.options.defaultzoom,10);
                }

                //Update clusterer if we need it
                if (mapObject.options.clustermarkers == 'true') {
                    ko.utils.arrayForEach(self.filteredLocations.peek(), function(location) {
                        self.markerClusterer.addMarker(location._mapMarker);
                    });
                }

                self.map.panTo(self.centrePoint);
                self.map.setZoom(self.defaultZoom);
            } else {
                //AUTO LOCATION

                //Bounds object
                self.bounds = new google.maps.LatLngBounds();

                ko.utils.arrayForEach(self.filteredLocations.peek(), function(location) {
                    self.bounds.extend(location._mapMarker.position);

                    //If home location is in use make sure that it's in bounds
                    if(self.homelocation){
                        self.bounds.extend(self.homelocation._mapMarker.position);
                    }

                    //If geo is used use it in bounds
                    if(self.userLocation._mapMarker){
                        self.bounds.extend(self.userLocation._mapMarker.position);
                    }

                    if (mapObject.options.clustermarkers == 'true') {
                        self.markerClusterer.addMarker(location._mapMarker);
                    }
                });

                //Single location zoom
                if(self.filteredLocations.peek().length === 1){
                    //TODO:Set single zoom from attribute
                    self.map.setZoom(15);
                    var centrepoint = new google.maps.LatLng(self.filteredLocations.peek()[0].latitude, self.filteredLocations.peek()[0].longitude);
                    self.map.panTo(centrepoint);
                }
                else{

                    //Fit these bounds to the map
                    self.map.fitBounds(self.bounds);

                    //Move to default position and zoom if no results
                    if (self.bounds.isEmpty()) {
                        self.map.setZoom(self.defaultZoom);
                        self.map.panTo(self.centrePoint);
                    }
                }
            }
        };


        //Show the infowindow
        self.showInfoWindow = function(location) {
            var marker = location._mapMarker;
            var position = new google.maps.LatLng(location.latitude, location.longitude);
            //TODO:Create builder for this and move it server sidelis
            var content = "<div class='infoWindow'>";

                //Make the title a link to the pag
                //if (location.locationUrl !== '' && maplistScriptParamsKo.hideviewdetailbuttons != 'true') {
                  //  content += "<h3><a href='" + location.locationUrl + "'>" + location.title + "</h3>";
                //}
                //else{
                    content += "<h3>" + location.title + "</h3>";
                //}

                content += '<div class="infowindowContent">';
                    if (location.imageUrl) {
                        content += "<img src='" + location.imageUrl + "' class='locationImage'/>";
                    }

                    content += location.description;

                    if (location.locationUrl !== '' && maplistScriptParamsKo.hideviewdetailbuttons != 'true') {
                        content += "<a class='viewLocationPage btn corePrettyStyle' " + (mapObject.options.openinnew === false ? "" : "target='_blank'") + " href='" + location.locationUrl + "' >" + maplistScriptParamsKo.viewLocationDetail + "</a>";
                    }
                content += "</div>";
            content += "</div>";

            self.infowindow.setContent(content);

            //If setzoomlevel is set then start to zoom
                //if keepzoomlevel is set then marked as zoomed once
                //clear this setting on clear locations

            if(mapObject.options.keepzoomlevel == 'true' && self.hasZoomed === true){
                //do nothing
            }
            else{
                if (mapObject.options.selectedzoomlevel !== ''){
                    self.map.setZoom(parseInt(mapObject.options.selectedzoomlevel,10));

                    //Mark to show we've zoomed once
                    self.hasZoomed = true;
                }
            }

            self.infowindow.open(self.map, marker);

            //Move to marker
            self.map.panTo(position);
            return true;
        };

        //Show clear search button
        self.showClearButton = ko.computed(function(){
            return self.query().length > 0 || self.locationquery().length > 0;
        });

        //Paging
        //======================================

        //Next clicked
        self.nextPage = function(data,element){
            var locations = self.filteredLocations();
            var size = self.pageSize();
            var start = parseInt(self.pageIndex(),10) * parseInt(self.pageSize(),10);

            //Range check
            if((parseInt(start,10) + parseInt(size,10)) < locations.length){
                self.pageIndex(parseInt(self.pageIndex(),10) + 1);
            }
            else{
              return false;
            }
        };

        //Prev clicked
        self.prevPage = function() {
            var size = self.pageSize();
            var start = self.pageIndex() * self.pageSize();

            //Range check
            if ((parseInt(start,10) - parseInt(size,10)) >= 0) {
                self.pageIndex(self.pageIndex() - 1);
            } else {
                return false;
            }
        };

        //Should paging show
        self.pagingVisible = function(){
            return Math.ceil(self.filteredLocations().length / self.pageSize()) > 0;
        };

        //Disable/Enable next button
        self.nextPageButtonCSS = ko.computed(function(){
            return ((self.pageIndex() + 1) * self.pageSize() >= self.filteredLocations().length) ? "disabled" : "";
        });

        //Disable/Enable prev button
        self.prevPageButtonCSS = ko.computed(function(){
            return self.pageIndex() === 0 ? "disabled" : "";
        });

        //Pages count
        self.totalPages = ko.computed(function(){
            return Math.ceil(self.filteredLocations().length / self.pageSize() );
        });

        //Human current page
        self.currentPageNumber = ko.computed(function(){
            return self.pageIndex() + 1;
        });


        //Any locations
        self.anyLocationsAvailable = ko.computed(function(){
            return self.filteredLocations().length === 0;
        });


        //Paged locations
        //Needs to be separate as map markers are not paged
        self.pagedLocations = ko.computed(function () {
            var locations = self.filteredLocations();

            var start = self.pageIndex() * self.pageSize();

            //Next page
            return locations.slice(start, parseInt(start,10) + parseInt(self.pageSize(),10));
        }, self);

        /*
        * UTILITIES
        =====================================================*/

        //Sort algorithms
        //---------------------------------------------------

        //DISTANCE SORT
        // ascending sort
        function asc_bydistance(a, b){
            if(self.locationquery()){
                return (parseFloat(b.searchDistanceAway()) < parseFloat(a.searchDistanceAway()) ? 1 : -1);
            }
            else{
                return (parseFloat(b.distanceAway()) < parseFloat(a.distanceAway()) ? 1 : -1);
            }

        }

        // decending sort
        function dec_bydistance(a, b){
            if(self.locationquery()){
                return (parseFloat(b.searchDistanceAway()) > parseFloat(a.searchDistanceAway()) ? 1 : -1);
            }
            else{
                return (parseFloat(b.distanceAway()) > parseFloat(a.distanceAway()) ? 1 : -1);
            }
        }

        //TITLE SORT
        // accending sort
        function asc_bytitle(a, b){
            return b.title.toLowerCase() == a.title.toLowerCase() ? 0 : (b.title.toLowerCase() < a.title.toLowerCase() ? -1 : 1);
        }
        // decending sort
        function dec_bytitle(a, b){
            return b.title.toLowerCase() == a.title.toLowerCase() ? 0 : (b.title.toLowerCase() > a.title.toLowerCase() ? -1 : 1);
        }

        //Get url parameters
        function getParameterByName(name)
        {
            name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
            var regexS = "[\\?&]" + name + "=([^&#]*)";
            var regex = new RegExp(regexS);
            var results = regex.exec(window.location.search);

            if(results === null){
                return "";
            }
            else{
                return decodeURIComponent(results[1].replace(/\+/g, " "));
            }
        }

        //Get distance between two items
        var calculateDistance = function(p1lat, p1long, p2lat, p2long) {
            //Convert degrees to radians
            var rad = function(x) { return x * Math.PI / 180; };

            //Haversine formula
            var R;

            if ('METRIC' == 'METRIC') {
                R = 6372.8; // approximation of the earth's radius of the average circumference in km
            } else {
                R = 3961.3; //Radius in miles)
            }


            var dLat = rad(p2lat - p1lat);
            var dLong = rad(p2long - p1long);

            var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(rad(p1lat)) * Math.cos(rad(p2lat)) * Math.sin(dLong / 2) * Math.sin(dLong / 2);
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            var d = R * c;

            return d.toFixed(1);
        };

        //Print directions
        function printContent(content) {
            newwin = window.open('', 'printwin', '');
            newwin.document.write('<HTML>\n<HEAD>\n');
            newwin.document.write('<TITLE>Print Page</TITLE>\n');
            newwin.document.write('<script>\n');
            newwin.document.write('function chkstate(){\n');
            newwin.document.write('if(document.readyState=="complete"){\n');
            newwin.document.write('setTimeout("window.close()", 10); \n');
            newwin.document.write('}\n');
            newwin.document.write('else{\n');
            newwin.document.write('setTimeout("chkstate()",2000)\n');
            newwin.document.write('}\n');
            newwin.document.write('}\n');
            newwin.document.write('function print_win(){\n');
            newwin.document.write('window.print();\n');
            newwin.document.write('chkstate();\n');
            newwin.document.write('}\n');
            newwin.document.write('<\/script>\n');
            newwin.document.write('</HEAD>\n');
            newwin.document.write('<BODY onload="print_win()">\n');
            newwin.document.write(content);
            newwin.document.write('</BODY>\n');
            newwin.document.write('</HTML>\n');
            newwin.document.close();
        }

        /*
         * CONSTRUCTOR FUNCTIONS
         ====================================================*/

        function Location(item) {
            this.title = item.title;
            this.cssClass = item.cssClass;
            this.categories = item.categories;
            this.customCategories = item.customCategories;
            this.latitude = item.latitude;
            this.longitude = item.longitude;
            this.pinColor = 'blue';//GetPinColour;
            this.pinImageUrl = item.pinImageUrl;
            this.imageUrl = item.imageUrl;
            this.smallImageUrl = item.smallImageUrl;
            this.address = item.address;
            this._mapMarker = '';
            this.locationUrl = item.locationUrl;
            this.description = item.description;
            this.expanded = ko.observable(false);
            this.distanceAway = ko.observable();
            this.searchDistanceAway = ko.observable();
            this.friendlyDistance = ko.observable();
        }

        function Category(title, slug, taxonomyName) {
            this.title = title;
            this.slug = slug;
            this.selected = ko.observable(false);
            this.taxonomyName = taxonomyName || '';
            this.cssClass = ko.computed(function(){
                return this.selected() ? "showing corePrettyStyle btn" : "corePrettyStyle btn";
            },this);
        }

    }

    //Convert json to object
    var dataFromServer = maplistScriptParamsKo.KOObject;

    //Create an array of maps in case we need to fire anything
    var MapListProMaps = [];

    //Needed in case do_shortcode is called more than once
    var mapsOnPageCount = $('.prettyMapList').length;
    var dataObjCount = dataFromServer.length;
    var mapIncAmount = dataObjCount/mapsOnPageCount;

    // Activates knockout.js
    $.each(dataFromServer, function (index, value) {
        //Checks to see if do_shortcode was called more than once
        if((index + 1) % mapIncAmount === 0){
            var newMap = new MapViewModel(value, value.id);
            MapListProMaps.push(newMap);
            ko.applyBindings(newMap, document.getElementById('MapListPro' + value.id));
        }
    });



    //Remove loading from all messages
    $('.prettyListItems.loading').removeClass('loading');

    // If you need to resize the map because it's in an accordion etc. and it's not showing the correct size
    // do this (change the [0] to the index of the map you need to redraw):
    // google.maps.event.trigger(MapListProMaps[0].map, "resize");

});