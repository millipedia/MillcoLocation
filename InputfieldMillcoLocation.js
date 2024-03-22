
// This is the on event fired when we open a repeater item
// should be a native event imo.
$(document).on('reloaded', '.InputfieldRepeaterItem', function () {

    init_maps();

});

var maps = [];
var markers = [];

document.addEventListener('DOMContentLoaded', function () {

    init_maps();

})

/**
 * init_maps()
 * 
 * loops through millco_map fields and initialises the map 
 * if not already done.
 * 
 */
function init_maps(){
    
    // loop through all of our map instances
    document.querySelectorAll('.millco_map').forEach(map_div => {

        let map_item_id = map_div.dataset.map_id;

        let map_lat = map_div.dataset.map_lat;
        let map_lng = map_div.dataset.map_lng;

        let map_id = 'map_' + map_item_id;
        let ml_id = 'ml_' + map_item_id;

        // if we've not got this one then add it.
        if(typeof(maps[map_item_id])=='undefined'){
            
            maps[map_item_id] = new L.map(map_id, {
            });

            maps[map_item_id].setView([map_lat, map_lng], 10);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(maps[map_item_id]);

            markers[map_item_id] = new L.marker([map_lat, map_lng], {
                draggable: 'true'
            }).addTo(maps[map_item_id]);


            markers[map_item_id].on('dragend', function (e) {

                let lat = e.target._latlng.lat;
                let lng = e.target._latlng.lng;

                lat = lat.toFixed(6);
                lng = lng.toFixed(6);

                // we can find the first input using css query selectors these days.
                var ml_input = document.querySelector('#' + ml_id + ' input');

                let lat_lng_string = lat + ',' + lng;
                ml_input.value = lat_lng_string;


            });
            
            // Get the lat lng input field
            // we can find the first input using css query selectors these days.
            var ml_input = document.querySelector('#' + ml_id + ' input');


            maps[map_item_id].on('click', function (e) {

                let lat = e.latlng.lat;
                let lng = e.latlng.lng;

                lat = lat.toFixed(6);
                lng = lng.toFixed(6);

                let lat_lng_string = lat + ',' + lng;
                ml_input.value = lat_lng_string;

                // move the marker just in case this is a click
                // not a drag.
                markers[map_item_id].setLatLng([lat, lng]);


            });

            // clear button
            var clear_butt = document.querySelector('#' + ml_id + ' .millcol_clear');

            clear_butt.addEventListener('click', event => {

                event.preventDefault();
                ml_input.value='';

            });


            // lookup button
            // odd things happened in repeaters when we weren't looking this up 
            // by ID.

            var lookup_butt = document.querySelector('#' + map_id + '_lookup_butt');

            lookup_butt.addEventListener('click', event => {

                event.preventDefault();
    
                // get the address field which immediately follows this button.
                // uikit sticks in some nodes so can't just use nextSibling
                let address_field = lookup_butt.parentElement.querySelector('.millcol_lookup_field');
    
    
                let lookup_value = address_field.value;
    
                if (lookup_value !== '') {
    
                    // URL of Bing Maps^^^^h Nominatim REST Services Locations API 
                    var lookupURL = '//nominatim.openstreetmap.org/search?format=json&limit=1&countrycodes=gb&q=' + lookup_value;
    
                    fetch(lookupURL)
                        .then(res => res.json())
                        .then(out => {
    
                            // we get anything sensible?
                            if (typeof (out[0]) !== "undefined") {
    
                                if (out[0].lat > 0) {
    
                                    let ml_id = 'ml_' + map_item_id;
    
                                    let lat = out[0].lat;
                                    let lng = out[0].lon;
    
                                    // lat = lat.toFixed(6);
                                    // lng = lng.toFixed(6);
    
                                    // we can find the first input using css query selectors these days.
                                    var ml_input = document.querySelector('#' + ml_id + ' input');
    
                                    let lat_lng_string = lat + ',' + lng;
                                    ml_input.value = lat_lng_string;
    
                                    markers[map_item_id].setLatLng([lat, lng]);
                                    maps[map_item_id].setView(new L.LatLng(out[0].lat, out[0].lon),14);
    
                                }
    
                            } else {
                                alert("sorry we cant find that location");
                            }
    
                        })
    
                        .catch(err => { throw err });
    
    
                }
                return false;
            });

        }
        

    })



}