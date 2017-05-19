//Google Maps script
//http://www.google.com/apis/maps/documentation/
//GRB Version 0.1 14/7/07

    function load() {
      if (GBrowserIsCompatible()) {
            var map = new GMap2(document.getElementById("map"));
            var icon = new GIcon();
               icon.image = "http://labs.google.com/ridefinder/images/mm_20_yellow.png";
               icon.shadow = "http://labs.google.com/ridefinder/images/mm_20_shadow.png";
               icon.iconSize = new GSize(12, 20);
               icon.shadowSize = new GSize(22, 20);
               icon.iconAnchor = new GPoint(6, 20);
               icon.infoWindowAnchor = new GPoint(5, 1);
            var point = new GLatLng(-27.8060, 151.9073)
        map.addControl(new GSmallMapControl());
        map.setCenter(new GLatLng(-27.8060, 151.9073), 10);
        map.addOverlay(new GMarker(point, icon));

        }
    }
