<?php $view->extend('::user.html.php');?>
<div class="body-sec">
    <section class="demo_wrapper">
    <ul id="demo1">
    <?php for($i=0;$i<count($banner);$i++) {  ?>
        <li><a href="#slide<?php echo $i+1;?>">
        <img src="<?php echo $view['assets']->getUrl('img/destination_banner/').$banner[$i]['image']; ?>" alt="Caption slide-<?php echo $i+1;?>" width="1457px" height="491px">
        </a></li>
        <?php }  ?>        
      </ul>
  </section>
    <div class="t-destination">
    <div class="container">
        <h1>Our Desinations Guides.&nbsp; &nbsp;<span>Take a look to our handy guides for cities all arround the island</span></h1>
        <span class="w-destination">
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce ut auctor lacus. Curabitur quis leo eu nulla ornare commodo. Donec vulputate ante in felis dignissim, a interdum tortor euismod. Nunc bibendum egestas massa, nec ornare justo dictum eget. Suspendisse mollis pharetra magna. Nullam aliquet mollis metus, quis congue odio dapibus a. In ac lacinia tortor. Praesent venenatis porta porta. Donec porttitor viverra convallis. </p>
      </span>
        <div class="des-contain">
       
        <div class="destination">
         <?php $k=0; for($i=0;$i<count($destination);$i++) { $k++; ?>
            <div class="destination-sec destination-small-fig destination-grid">
            <div class="destination-fig destination-fig-pad"><!--<span class="top-corner"></span>--> <img src="<?php echo $view['assets']->getUrl('img/destination/').$destination[$i+1]['image'];?>" alt="destination"> </div>
            <h2><a href="<?php echo $view['assets']->getUrl('user')."/destination/".$destination[$i]['name'];?>" ><?php echo $destination[$i]['name']; ?></a></h2>
            <p>0000 0000 visits</p>
          </div>
          <?php   if($k==4) {  ?>
           </div>
		   <div class="destination">
		    <?php } $i++;  } ?>
         
          </div>
        
      </div>
        <p><a href="<?php echo $view['assets']->getUrl('user') ?>/destinationtop">Top Destinations </a></p>
      </div>
  </div>
    <div class="container">
    <div class="row review-sec">
        <h1>Reviews about our Destination Guides?</h1>
        <img src="img/icon2.png" width="47" height="40" alt="icon" align="left" style="float:left;">
        <div class="row clearfix">
        <div class="row-cover">
            <div class="row-one">
            <div class="review">
                <div class="review-fiq"><img src="img/user-1.jpg"></div>
                <div class="review-left"> <span class="star-review"><img src="img/star.png" width="14" height="13" alt="star"><img src="img/star.png" width="14" height="13" alt="star"><img src="img/star.png" width="14" height="13" alt="star"></span>
                <h2>Username@gmail.com</h2>
              </div>
              </div>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce ut auctor lacus. Curabitur quis leo eu nulla ornare commodo. Donec vulputate ante in felis dignissim, a interdum tortor euismod. Nunc bibendum egestas massa, nec ornare justo dictum eget. Suspendisse mollis pharetra magna. Nullam aliquet mollis metus, quis congue odio dapibus a. In ac lacinia tortor. Praesent venenatis porta porta. Donec porttitor viverra convallis. Vivamus nec velit ac nisl tempus vehicula. Pellentesque suscipit orci at hendrerit lobortis.</p>
          </div>
          </div>
        <div class="row-cover">
            <div class="row-one">
            <div class="review">
                <div class="review-fiq"><img src="img/user-1.jpg"></div>
                <div class="review-left"> <span class="star-review"><img src="img/star.png" width="14" height="13" alt="star"><img src="img/star.png" width="14" height="13" alt="star"><img src="img/star.png" width="14" height="13" alt="star"></span>
                <h2>Username@gmail.com</h2>
              </div>
              </div>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce ut auctor lacus. Curabitur quis leo eu nulla ornare commodo. Donec vulputate ante in felis dignissim, a interdum tortor euismod. Nunc bibendum egestas massa, nec ornare justo dictum eget. Suspendisse mollis pharetra magna. Nullam aliquet mollis metus, quis congue odio dapibus a. In ac lacinia tortor. Praesent venenatis porta porta. Donec porttitor viverra convallis. Vivamus nec velit ac nisl tempus vehicula. Pellentesque suscipit orci at hendrerit lobortis.</p>
          </div>
          </div>
        <div class="row-cover">
            <div class="row-one border-none">
            <div class="review">
                <div class="review-fiq"><img src="img/user-1.jpg"></div>
                <div class="review-left"> <span class="star-review"><img src="img/star.png" width="14" height="13" alt="star"><img src="img/star.png" width="14" height="13" alt="star"><img src="img/star.png" width="14" height="13" alt="star"></span>
                <h2>Username@gmail.com</h2>
              </div>
              </div>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce ut auctor lacus. Curabitur quis leo eu nulla ornare commodo. Donec vulputate ante in felis dignissim, a interdum tortor euismod. Nunc bibendum egestas massa, nec ornare justo dictum eget. Suspendisse mollis pharetra magna. Nullam aliquet mollis metus, quis congue odio dapibus a. In ac lacinia tortor. Praesent venenatis porta porta. Donec porttitor viverra convallis. Vivamus nec velit ac nisl tempus vehicula. Pellentesque suscipit orci at hendrerit lobortis.</p>
          </div>
          </div>
      </div>
      </div>
     </div>
    