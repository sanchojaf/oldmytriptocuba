<?php $view->extend('::user.html.php');?>
<div class="body-sec">
          <section class="demo_wrapper">
    <ul id="demo1">
        <?php for($i=0;$i<count($banner);$i++) {  ?>      
              <li><a href="#slide<?php echo $i ?>"><img src="<?php echo $view['assets']->getUrl('img/hostal_banner/').$banner[$i]['image']; ?>"  alt="" width="1457px" height="491px"></a>
        <div class="banner-info story-banner">
                  <h1>Miriam y Manuel <br/>
            <span>"Our guests are like family"</span> </h1>
                  <span class="star-review" style="float:right;"><img alt="star" src="<?php echo $view['assets']->getUrl('user/').'img/w-star.png'?>"><img  alt="star" src="<?php echo $view['assets']->getUrl('user/').'img/w-star.png'?>"><img  alt="star" src="<?php echo $view['assets']->getUrl('user/').'img/w-star.png'?>"><img  alt="star" src="<?php echo $view['assets']->getUrl('user/').'img/b-star-1.png'?>"><img  alt="star" src="<?php echo $view['assets']->getUrl('user/').'img/b-star-1.png'?>"></span> </div>
      </li>
        <?php } ?>
            </ul>
  </section>
          <div class="story-container">
    <div class="container">
              <ul class="breadcumbs">
        <li ><a href="#" class="first">Destination Guides</a></li>
        <li ><a href="#"><i class="fa fa-chevron-right"></i> <?php echo $hostalcontent[1]['name'] ?> <span>(87 970 visits)</span></a></li>
      </ul>
              <div class="story-left-container">
        <div class="story-left">
                  <h3><?php echo $story[0]['name']; ?><br/>
            <?php echo $story[0]['subHead']; ?></h3>
                  <p><?php echo $story[0]['content']; ?></p>
                </div>
        <div class="row clearfix" > <span class="destination-right-head border-none destination-left-heder review-sec ">
          <h1>Wrie a Review</h1>
          <img src="img/review.png"alt="icon"> </span>
                  <form class="write-review write-review-margin">
            <textarea></textarea>
            <input type="button" value="submit review" class="submit-review">
          </form>
                </div>
      </div>
              <div class="story-right-container">
        <div class="story-description row-one">
                  <h1>Description</h1>
                  <img src="<?php echo $view['assets']->getUrl('user/').'img/icon.png'?>" width="48" height="41" alt="hospitality">
                  <p><?php echo $hostalcontent[0]['description']; ?>.</p>
                  <span class="readmore"><a href="#">Read more</a></span> </div>
        <div class="story-description row-one">
                  <h1>How to get here</h1>
                  <img src="<?php echo $view['assets']->getUrl('user/').'img/icon.png'?>" width="48" height="41" alt="hospitality">
                  <p><?php echo $hostalcontent[0]['locationDesc']; ?>..</p>
                  <div class="map">
            <div id="gmap_canvas" style="height:150px;width:100%;"></div>
            <style>
#gmap_canvas img{max-width:none!important;background:none!important}
</style>
          </div>
                </div>
        <div class="  story-description row-one">
                  <h1>Reviews </h1>
                  <img src="<?php echo $view['assets']->getUrl('user/').'img/icon.png'?>" width="47" height="40" alt="icon"> <span class="com-txt">(10)</span>
                  <div class="row clearfix">
            <div class="row-right">
                      <div class="review">
                <div class="review-fiq"><img src="<?php echo $view['assets']->getUrl('user/').'img/user-1.jpg'?>"></div>
                <div class="review-left"> <span class="star-review"><img src="img/star.png" width="14" height="13" alt="star"><img src="img/star.png" width="14" height="13" alt="star"><img src="img/star.png" width="14" height="13" alt="star"></span>
                          <h2>Username@gmail.com</h2>
                        </div>
              </div>
                      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce ut auctor lacus. Curabitur quis leo eu nulla ornare commodo. Donec vulputate ante in felis dignissim, a interdum tortor euismod. Nunc bibendum egestas massa, nec ornare justo dictum eget. Suspendisse mollis pharetra magna. Nullam aliquet mollis metus, quis congue odio dapibus a. In ac lacinia tortor. Praesent venenatis porta porta. Donec porttitor viverra convallis. Vivamus nec velit ac nisl tempus vehicula. Pellentesque suscipit orci at hendrerit lobortis.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce ut auctor lacus. Curabitur quis leo eu nulla ornare commodo. Donec vulputate ante in felis dignissim, a interdum tortor euismod. Nunc bibendum egestas massa, nec ornare justo dictum eget. Suspendisse mollis pharetra magna. Nullam aliquet mollis metus, quis congue odio dapibus a. In ac lacinia tortor. Praesent venenatis porta porta. Donec porttitor viverra convallis. Vivamus nec velit ac nisl tempus vehicula. Pellentesque suscipit orci at hendrerit lobortis.</p>
                      <span class="readmore"><a href="#">Read more</a></span> </div>
          </div>
                </div>
        <a href="#">
                <div class="submit-review"> Visit the Hostals</div>
                </a> </div>
            </div>
  </div>
          <div class="container"> </div>       
<script src="<?php echo $view['assets']->getUrl('user/js/jquery.mCustomScrollbar.concat.min.js') ?>"></script> 

<!----> <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script> 
<script type="text/javascript"> function init_map(){var myOptions = {zoom:14,center:new google.maps.LatLng(<?php echo $hostalcontent[1]['latitude'] ?>,<?php echo $hostalcontent[1]['longitude'] ?>),mapTypeId: google.maps.MapTypeId.ROADMAP};map = new google.maps.Map(document.getElementById("gmap_canvas"), myOptions);marker = new google.maps.Marker({map: map,position: new google.maps.LatLng(<?php echo $hostalcontent[1]['latitude'] ?>,<?php echo $hostalcontent[1]['longitude'] ?>)});infowindow = new google.maps.InfoWindow({content:"<b>ItBright</b><br/>31/1, Sri nagar colony North Avenue Saidapet<br/>600 015  chennai" });google.maps.event.addListener(marker, "click", function(){infowindow.open(map,marker);});infowindow.open(map,marker);}google.maps.event.addDomListener(window, 'load', init_map);</script>