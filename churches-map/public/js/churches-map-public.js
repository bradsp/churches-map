(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
        
        /**
         * Renders a Google Maps centered on Jackson, Tennessee. This is done by using
         * the Latitude and Longitude for the city.
         *
         * Getting the coordinates of a city can easily be done using the tool availabled
         * at: http://www.latlong.net
         *
         * @since    1.0.0
         */
        var map, xmlUrl, infoWindow;

        function gmaps_results_initialize() {
            
            if ( null === document.getElementById( 'map-canvas' ) ) {
		return;
            }
            
            var myLatlng = new google.maps.LatLng(35.6574272, -88.8451293);
            var myOptions = {
                zoom: 9,
                center: myLatlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            var geocoder = new google.maps.Geocoder();
            infoWindow = new google.maps.InfoWindow;
            
            map = new google.maps.Map( document.getElementById( 'map-canvas' ), myOptions);
            
            xmlUrl = "https://www.mccbaptists.org/churches.xml";
            
            loadMarkers();
            
        }
        
        //google.maps.event.addDomListener( window, 'load', gmaps_results_initialize );
	    google.maps.event.addEventListener( window, 'load', gmaps_results_initialize );
        
        function loadMarkers() {
            map.markers = map.markers || []
            downloadUrl(xmlUrl, function(data) {
                var xml = data.responseXML;
                var markers = xml.documentElement.getElementsByTagName("marker");
                for (var i = 0; i < markers.length; i++) {
                    var name = markers[i].getAttribute("name");
                    var id = markers[i].getAttribute("id");
                    var address = markers[i].getAttribute("address")+"<br />"+markers[i].getAttribute("postcode");
                    var phone = markers[i].getAttribute("phone");
                    var website;
                    if(markers[i].getAttribute("website") != "") 
                    {
                        website = '<a href="'+markers[i].getAttribute("website")+'">Visit Website</a>';
                    }
                    else
                    {
                        website = "";
                    }
                    var image = {
                      path: google.maps.SymbolPath.CIRCLE,
                      fillColor: '#80c6dd',
                      fillOpacity: 1,
                      scale: 6,
                      strokeWeight: 6,
                      strokeColor: '#80c6dd'
                    };
                    var point = new google.maps.LatLng(
                        parseFloat(markers[i].getAttribute("lat")),
                        parseFloat(markers[i].getAttribute("lng")));
                    var html = "<div class='infowindow'><b>" + name + "</b> <br/>" + address+'<br/>'+phone+'<br/>'+website+'</div>';
                    var marker = new google.maps.Marker({
                      map: map,
                      position: point,
                      //icon: image, 
                      title: name
                    });
                    map.markers.push(marker);
                    bindInfoWindow(marker, map, infoWindow, html);
                }
            });
        }
        
        function downloadUrl(url,callback) {
            var request = window.ActiveXObject ?
                 new ActiveXObject('Microsoft.XMLHTTP') :
                 new XMLHttpRequest;

            request.onreadystatechange = function() {
                if (request.readyState == 4) {
                    //request.onreadystatechange = doNothing;
                    callback(request, request.status);
                }
            };

            request.open('GET', url, true);
            request.send(null);
        }
        
        function bindInfoWindow(marker, map, infoWindow, html) {
            google.maps.event.addListener(marker, 'click', function() {
              infoWindow.setContent(html);
              infoWindow.open(map, marker);
            });
        }

}
)( jQuery );
