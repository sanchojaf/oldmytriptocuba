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
              <div class="des-contain">
        <div class="destination">
        
            <?php for($i=0;$i<count($destination);$i++) {  ?>
            <div class="destination-sec destination-small-fig destination-grid">
            <div class="destination-fig destination-fig-pad"><span class="top-corner"></span> <img src="<?php echo $view['assets']->getUrl('img/destination/').$destination[$i+1]['image'];?>" alt="destination"> </div>
            <h2><?php echo $destination[$i]['name']; ?></h2>
            <p>0000 0000 visits</p>
          </div>
          <?php   $i++;  } ?>
                 
                </div>
      </div>
           
     <?php echo $topdestination[0]['content']; ?> 
      <span class="border-story"></span>
              <p><a href="#">Top Destinations </a></p>
            </div>
  </div>
          <div class="container">
    <div class="row review-sec">
              <h1>Top Destination</h1>
              <img src="img/icon2.png" width="47" height="40" alt="icon" align="left" style="float:left;">
              <div class="row clearfix">
        <div class="top-review ">
                  <div class="row-one story-review">
            <h2>Destination name</h2>
            <p>0000 000 00 Visits</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce ut auctor lacus. Curabitur quis leo eu nulla ornare commodo. Donec vulputate ante in felis dignissim, a interdum tortor euismod. Nunc bibendum egestas massa, nec ornare justo dictum eget  Pellentesque suscipit orci at hendrerit lobortis.</p>
            <div class="review">
                      <div class="review-fiq"><img src="img/user-1.jpg"></div>
                      <div class="review-left"> <span class="star-review"><img src="img/star.png" width="14" height="13" alt="star"><img src="img/star.png" width="14" height="13" alt="star"><img src="img/star.png" width="14" height="13" alt="star"></span>
                <h2>Username@gmail.com</h2>
              </div>
                    </div>
          </div>
                  <div class="row-one story-review">
            <h2>Destination name</h2>
            <p>0000 000 00 Visits</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce ut auctor lacus. Curabitur quis leo eu nulla ornare commodo. Donec vulputate ante in felis dignissim, a interdum tortor euismodDonec vulputate ante in felis dignissim, a interdum tortor euismod. Nunc bibendum egestas massa, nec ornare justo dictum eget.
                      Donec vulputate ante in felis dignissim. </p>
            <div class="review">
                      <div class="review-fiq"><img src="img/user-1.jpg"></div>
                      <div class="review-left"> <span class="star-review"><img src="img/star.png" width="14" height="13" alt="star"><img src="img/star.png" width="14" height="13" alt="star"><img src="img/star.png" width="14" height="13" alt="star"></span>
                <h2>Username@gmail.com</h2>
              </div>
                    </div>
          </div>
                </div>
        <div class="top-review ">
                  <div class="row-one story-review">
            <h2>Destination name</h2>
            <p>0000 000 00 Visits</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce ut auctor lacus. Curabitur quis leo eu nulla ornare commodo. Donec vulputate ante in felis dignissim, a interdum tortor euismod. Nunc bibendum egestas massa, nec ornare justo dictum eget. Suspendisse mollis pharetra magna. Nullam aliquet mollis metus, quis congue odio dapibus a. In ac lacinia tortor. Praesent venenatis porta porta. Donec porttitor viverra convallis. Vivamus nec velit ac nisl tempus vehicula. Pellentesque suscipit orci at hendrerit lobortis.</p>
            <div class="review">
                      <div class="review-fiq"><img src="img/user-1.jpg"></div>
                      <div class="review-left"> <span class="star-review"><img src="img/star.png" width="14" height="13" alt="star"><img src="img/star.png" width="14" height="13" alt="star"><img src="img/star.png" width="14" height="13" alt="star"></span>
                <h2>Username@gmail.com</h2>
              </div>
                    </div>
          </div>
                  <div class="row-one story-review">
            <h2>Destination name</h2>
            <p>0000 000 00 Visits</p>
            <p>Lorem ipsum dolor sit amet,  a interdum tortor euismod. Nunc bibendum egestas massa, nec ornare justo dictum eget.</p>
            <div class="review">
                      <div class="review-fiq"><img src="img/user-1.jpg"></div>
                      <div class="review-left"> <span class="star-review"><img src="img/star.png" width="14" height="13" alt="star"><img src="img/star.png" width="14" height="13" alt="star"><img src="img/star.png" width="14" height="13" alt="star"></span>
                <h2>Username@gmail.com</h2>
              </div>
                    </div>
          </div>
                </div>
        <div class="top-review">
                  <div class="row-one story-review">
            <h2>Destination name</h2>
            <p>0000 000 00 Visits</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce ut auctor lacus. Curabitur quis leo eu nulla ornare commodo. Donec vulputate ante in felis dignissim, a interdum tortor euismod. Nunc bibendum egestas massa, nec ornare justo dictum eget. Suspendisse mollis pharetra magna. Nullam aliquet mollis metus, quis congue odio dapibus a. In ac lacinia tortor. Praesent venenatis porta porta. Donec porttitor viverra convallis. Vivamus nec velit ac nisl tempus vehicula. Pellentesque suscipit orci at hendrerit lobortis.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce ut auctor lacus. Curabitur quis leo eu nulla ornare commodo. Donec vulputate ante in felis dignissim, a interdum tortor euismod. Nunc bibendum egestas massa, nec ornare justo dictum eget. Suspendisse mollis pharetra magna. Nullam aliquet mollis metus, quis congue odio dapibus a. In ac lacinia tortor. Praesent venenatis porta porta. Donec porttitor viverra convallis. Vivamus nec velit ac nisl tempus vehicula. Pellentesque suscipit orci at hendrerit lobortis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce ut auctor lacus. Curabitur quis leo eu nulla ornare commodo. Donec vulputate ante in felis dignissim.</p>
            <div class="review">
                      <div class="review-fiq"><img src="img/user-1.jpg"></div>
                      <div class="review-left"> <span class="star-review"><img src="img/star.png" width="14" height="13" alt="star"><img src="img/star.png" width="14" height="13" alt="star"><img src="img/star.png" width="14" height="13" alt="star"></span>
                <h2>Username@gmail.com</h2>
              </div>
                    </div>
          </div>
                </div>
      </div>
            </div>
  </div>

