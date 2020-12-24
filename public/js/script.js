/* Note: This example requires that you consent to location sharing when
     * prompted by your browser. If you see the error "Geolocation permission
     * denied.", it means you probably did not give permission for the browser * to locate you. */
    let pos;
    let map;
    let bounds;
    let infoWindow;
    let currentInfoWindow;
    let service;
    let infoPane;
    let lat;
    let lng;
    let keyword;
    var timeout;
    let table;

    /*This is an example function and can be disregarded
    This function sets the loading div to a given string.*/
    
    /*This starts the load on page load, so you don't have to click the button*/
    function loading(){
      /*This is the loading gif, It will popup as soon as startLoad is called*/
      $('#loading').html('<center><button class="btn btn-primary" type="button" id="start_call" disabled>'+'<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"> </span> <span class="ml-1">Loading...</span></button></center>');
      /*
      This is an example of the ajax get method, 
      You would retrieve the html then use the results
      to populate the container.
      
      $.get('example.php', function (results) {
          $('#loading').html(results);
      });
      */
      /*This is an example and can be disregarded
      The clearTimeout makes sure you don't overload the timeout variable
      with multiple timout sessions.*/
      clearTimeout(timeout);
      /*Set timeout delays a given function for given milliseconds*/
      timeout = setTimeout(loaded, 3000);
    }
    //   function initialize() {
  //     initMap();
  //     //initAutoComplete();
  //  }
 
    
  function myFunction() {
    var obj = JSON.parse(document.getElementById("station_id").value);
    keyword = document.getElementById("keyword").value;

    //alert(value);
    //lat = typeof a;
    latitude = parseFloat(obj.lat);
    longitude = parseFloat(obj.lng);
    //document.getElementById("demo").innerHTML = latitude + longitude + keyword ;  

    infoWindow = new google.maps.InfoWindow;
    currentInfoWindow = infoWindow;
    infoPane = document.getElementById('panel');
    table = document.getElementById("myTable");

    pos = { lat: latitude, lng: longitude };
  
    map = new google.maps.Map(document.getElementById('map'), {
      center: pos,
      zoom: 16.4
    });
    const image =
        "https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png";
    var marker =new google.maps.Marker({
      position: pos,
      map,
      title: "Station is here",
      icon : image,
    });

    infoWindow.setPosition(pos);
    infoWindow.setContent('Station is here');
    infoWindow.open(map);
    currentInfoWindow = infoWindow;

    getNearbyPlaces(pos,keyword); 
  
  }

    function initMap() {
      // Initialize variables
      //bounds = new google.maps.LatLngBounds();
      infoWindow = new google.maps.InfoWindow;
      currentInfoWindow = infoWindow;
      /* TODO: Step 4A3: Add a generic sidebar */
      infoPane = document.getElementById('panel');

      // Try HTML5 geolocation
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(position => {
          pos = {
            lat: position.coords.latitude,
            lng: position.coords.longitude
          };
          
          var marker =new google.maps.Marker({
            position: pos,
            map: map,
            title: "You are here",
         });

          map = new google.maps.Map(document.getElementById('map'), {
            center: pos,
            zoom: 15
          });
          //bounds.extend(pos);
          keyword = 'train station';
          currentInfoWindow = infoWindow;

          // Call Places Nearby Search on user's location
          getNearbyPlaces(pos,keyword);

          infoWindow.setPosition(pos);
          infoWindow.setContent('Location found.');
          infoWindow.open(map);
          map.setCenter(pos);
         

          
        }, () => {
          // Browser supports geolocation, but user has denied permission
          handleLocationError(true, infoWindow);
        });
      } else {
        // Browser doesn't support geolocation
        handleLocationError(false, infoWindow);
      }
    }
    
  
    // Handle a geolocation error
    function handleLocationError(browserHasGeolocation, infoWindow) {
      // Set default location to Sydney, Australia
      pos = { lat: 2.9960560, lng: 101.5755560 };
      map = new google.maps.Map(document.getElementById('map'), {
        center: pos,
        zoom: 16.4
      });
      keyword = 'train station'
      // Display an InfoWindow at the map center
      infoWindow.setPosition(pos);
      infoWindow.setContent(browserHasGeolocation ?
        'Geolocation permissions denied. Using default location.' :
        'Error: Your browser doesn\'t support geolocation.');
      infoWindow.open(map);
      currentInfoWindow = infoWindow;

      // Call Places Nearby Search on the default location
      getNearbyPlaces(pos,keyword);
    }

    // Perform a Places Nearby Search Request
    function getNearbyPlaces(position,keyword) {
      //document.getElementById("demo").innerHTML ="hel";
      let request = {
        location: position,
        //rankBy: google.maps.places.RankBy.DISTANCE,
        radius: '300',
        keyword: keyword
      };

      service = new google.maps.places.PlacesService(map);
      service.nearbySearch(request, nearbyCallback);
    }

    // Handle the results (up to 20) of the Nearby Search
    function nearbyCallback(results, status) {
      if (status == google.maps.places.PlacesServiceStatus.OK) {
        document.getElementById("demo").innerHTML =( results.length >= 1) ? results.length + " places found" : "No "+ keyword + " found";  

        createMarkers(results);
        resultTable(results);
      }
    }

    //show list in table
    function resultTable(places){
      //get detail each place
      document.getElementById("myTable").innerHTML = "Hello";
      //var body = document.getElementsByTagName('body')[0];
      var tbl = document.createElement('table');
      tbl.style.width = '70%';
      tbl.setAttribute('border', '1');
      var tr = document.createElement('tr');
      tbl.appendChild(tr);
      var th = document.createElement('th');
      th.appendChild('Image');
      tr.appendChild(th);
      var th = document.createElement('th');
      th.appendChild('Name');
      tr.appendChild(th);
      var th = document.createElement('th');
      th.appendChild('Category');
      tr.appendChild(th);

      var tr = document.createElement('tr');
      tbl.appendChild(tr);

      places.forEach(place => {
        let request = {
            placeId: place.place_id,
            fields: ['name', 'formatted_address', 'geometry', 'rating',
              'website', 'photos']
          };
          
          service.getDetails(request, (placeResult, status) => {
            if (status == google.maps.places.PlacesServiceStatus.OK) {
              
              var td = document.createElement('td');
                if (Result.photos) {
                  let firstPhoto = placeResult.photos[0];
                  let photo = document.createElement('img');
                  //photo.classList.add('hero');
                  photo.src = firstPhoto.getUrl();
                  td.appendChild(photo);
                }
              tr.appendChild(td);

              var td = document.createElement('td');
              td.appendChild(document.createTextNode(placeResult.name));
              tr.appendChild(td);

              var td = document.createElement('td');
              td.appendChild(document.createTextNode(placeResult.name));
              tr.appendChild(td);
              
            }});
      });    
   
    }
    // Set markers at the location of each place result
    function createMarkers(places) {
      
      places.forEach(place => {
        let marker = new google.maps.Marker({
          position: place.geometry.location,
          map: map,
          title: place.name
        });
        
        

        /* TODO: Step 4B: Add click listeners to the markers */
        // Add click listener to each marker
        google.maps.event.addListener(marker, 'click', () => {
          let request = {
            placeId: place.place_id,
            fields: ['name', 'formatted_address', 'geometry', 'rating',
              'website', 'photos']
          };

          /* Only fetch the details of a place when the user clicks on a marker.
           * If we fetch the details for all place results as soon as we get
           * the search response, we will hit API rate limits. */
          service.getDetails(request, (placeResult, status) => {
            showDetails(placeResult, marker, status)
          });
        });

        // Adjust the map bounds to include the location of this marker
       // bounds.extend(place.geometry.location);
      });
      /* Once all the markers have been placed, adjust the bounds of the map to
       * show all the markers within the visible area. */
      //map.fitBounds(bounds);
    }

    /* TODO: Step 4C: Show place details in an info window */
    // Builds an InfoWindow to display details above the marker
    function showDetails(placeResult, marker, status) {
      if (status == google.maps.places.PlacesServiceStatus.OK) {
        let placeInfowindow = new google.maps.InfoWindow();
        let rating = "None";
        if (placeResult.rating) rating = placeResult.rating;
        placeInfowindow.setContent('<div><b>' + placeResult.name +
          '</b><br>' + 'Rating: ' + rating + '</div>');
        placeInfowindow.open(marker.map, marker);
        currentInfoWindow.close();
        currentInfoWindow = placeInfowindow;
        showPanel(placeResult);
      } else {
        console.log('showDetails failed: ' + status);
      }
    }

    /* TODO: Step 4D: Load place details in a sidebar */
    // Displays place details in a sidebar
    function showPanel(placeResult) {
      // If infoPane is already open, close it
      if (infoPane.classList.contains("open")) {
        infoPane.classList.remove("open");
      }

      // Clear the previous details
      while (infoPane.lastChild) {
        infoPane.removeChild(infoPane.lastChild);
      }

      /* TODO: Step 4E: Display a Place Photo with the Place Details */
      // Add the primary photo, if there is one
      if (placeResult.photos) {
        let firstPhoto = placeResult.photos[0];
        let photo = document.createElement('img');
        photo.classList.add('hero');
        photo.src = firstPhoto.getUrl();
        infoPane.appendChild(photo);
      }

      // Add place details with text formatting
      let name = document.createElement('h1');
      name.classList.add('place');
      name.textContent = placeResult.name;
      infoPane.appendChild(name);
      if (placeResult.rating) {
        let rating = document.createElement('p');
        rating.classList.add('details');
        rating.textContent = `Rating: ${placeResult.rating} \u272e`;
        infoPane.appendChild(rating);
      }
      let address = document.createElement('p');
      address.classList.add('details');
      address.textContent = placeResult.formatted_address;
      infoPane.appendChild(address);
      if (placeResult.website) {
        let websitePara = document.createElement('p');
        let websiteLink = document.createElement('a');
        let websiteUrl = document.createTextNode(placeResult.website);
        websiteLink.appendChild(websiteUrl);
        websiteLink.title = placeResult.website;
        websiteLink.href = placeResult.website;
        websitePara.appendChild(websiteLink);
        infoPane.appendChild(websitePara);
      }

      // Open the infoPane
      infoPane.classList.add("open");
    }

// var map;
// var service;
// var infowindow;

// $(document).ready(function(){

// //   "use strict";
// //   var myLatLng = new google.maps.Latlng(2.9960560,101.5755560);
  
// //   let map = new google.maps.Map(document.getElementById("map"), {
// //     center:  myLatLng,
// //     zoom: 15
// //   });
// // });

  

//   function geoLocationInit(){
//     if(navigator.geolocation){
//       navigator.geolocation.getCurrentPosition();
//     }else{
//       alert("browser not supported");
//     }
//   }

//   function success(position){
//     console.log(position);
//     var latval= position.coords.latitude;
//     var lngval=position.coords.longitude;
    
//     var myLatLng = new google.maps.LatLng(latval, lngval);
//     initMap(myLatLng)
//   }

//   function fail() {
//     alert("it fails");
//   }

//   //Create marker
//   function createMarker(latlng, icn, name) {
//     var marker = new google.maps.Marker({
//         position: latlng,
//         map: map,
//         icon: icn,
//         title: name
//     });
//   }

  // function initMap() {

    
  //     infowindow = new google.maps.InfoWindow();

  //     map = new google.maps.Map(
  //       document.getElementById('map'), {center: myLatLng, zoom: 15});


  //   infowindow = new google.maps.InfoWindow();

  //   map = new google.maps.Map(
  //       document.getElementById('map'), {center: kj01, zoom: 15});

  //   var request = {
  //     query: 'giant',
  //     fields: ['name', 'geometry'],
  //   };

  //   var service = new google.maps.places.PlacesService(map);

  //   service.findPlaceFromQuery(request, function(results, status) {
  //     if (status === google.maps.places.PlacesServiceStatus.OK) {
  //       for (var i = 0; i < results.length; i++) {
  //         createMarker(results[i]);
  //       }
  //       map.setCenter(results[0].geometry.location);
  //     }
  //   });
  // }
// });






// let map;
// let service;
// let infowindow;

//   function initMap() {
//     const myLatLng = { lat: 2.9960560, lng: 101.5755560 };
//     const map = new google.maps.Map(document.getElementById("map"), {
//       zoom: 16.4,
//       center: myLatLng,
//     });

//     infoWindow = new google.maps.InfoWindow();
    
//     new google.maps.Marker({
//       position: myLatLng,
//       map,
//       title: "You are here",
//     });
    

//     // nearby search 
//         var request = {
//           location: myLatLng,
//           radius: '500',
//           type: ['restaurant']
//         };

//         service = new google.maps.places.PlacesService(map);
//         service.nearbySearch(request, callback);

//       //current location

//       if (navigator.geolocation) {
//         navigator.geolocation.getCurrentPosition(
//           (position) => {
//             const pos = {
//               lat: position.coords.latitude,
//               lng: position.coords.longitude,
//             };
//             infoWindow.setPosition(pos);
//             infoWindow.setContent("Location found.");
//             infoWindow.open(map);
//             map.setCenter(pos);
//           },
//           () => {
//             handleLocationError(true, infoWindow, map.getCenter());
//           }
//         );
//       } else {
//         // Browser doesn't support Geolocation
//         handleLocationError(false, infoWindow, map.getCenter());
//       }

    

//   }
//   //get location

//   function handleLocationError(browserHasGeolocation, infoWindow, pos) {
//     infoWindow.setPosition(pos);
//     infoWindow.setContent(
//       browserHasGeolocation
//         ? "Error: The Geolocation service failed."
//         : "Error: Your browser doesn't support geolocation."
//     );
//     infoWindow.open(map);
//   }
  
//   //result nearby search
//     function callback(results, status) {
//       document.write(status)
//       // if (status == google.maps.places.PlacesServiceStatus.OK) {
//       //   for (var i = 0; i < results.length; i++) {
//       //     document.write(results[i]);
//       //   }
//       // }
//     }






