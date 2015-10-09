function init_map(a,b,c){
	var myOptions = {zoom:14,center:new google.maps.LatLng(a,b),mapTypeId: google.maps.MapTypeId.ROADMAP};
	map = new google.maps.Map(document.getElementById("gmap_canvas"), myOptions);
	marker = new google.maps.Marker({map: map,position: new google.maps.LatLng(a, b)});
	infowindow = new google.maps.InfoWindow({content:c });
	google.maps.event.addListener(marker, "click", function(){infowindow.open(map,marker);});
	infowindow.open(map,marker);
}