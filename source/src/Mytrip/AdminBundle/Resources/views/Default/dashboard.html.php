<?php $view->extend('::admin.html.php');
$em = $this->container->get('doctrine')->getManager();
$confirmbooking=$em->createQuery("SELECT COUNT(b) AS counts, MONTH(b.createdDate) AS months FROM MytripAdminBundle:Booking b WHERE b.status = 'Confirmed' GROUP BY months ORDER BY b.bookingId DESC ")->getArrayResult();
$cancelbooking=$em->createQuery("SELECT COUNT(b) AS counts, MONTH(b.createdDate) AS months FROM MytripAdminBundle:Booking b WHERE b.status = 'Cancelled' GROUP BY months ORDER BY b.bookingId DESC ")->getArrayResult();
if(!empty($confirmbooking)){
	foreach($confirmbooking as $confirmbooking){
		$cbook[$confirmbooking['months']]=$confirmbooking['counts'];
	}
}
if(!empty($cancelbooking)){
	foreach($cancelbooking as $cancelbooking){
		$cancelbook[$cancelbooking['months']]=$cancelbooking['counts'];
	}
}
	for($i=0;$i<date('m');$i++){
		$mon[]=date('M',mktime(0,0,0,$i+2,0,0));
		if(!empty($cbook[$i+1])){
			$confirm[]=$cbook[$i+1];
		}else{
			$confirm[]=0;
		}
		if(!empty($cancelbook[$i+1])){
			$cancel[]=$cancelbook[$i+1];
		}else{
			$cancel[]=0;
		}
	}
?>
<script type="text/javascript" src="<?php echo $view['assets']->getUrl('js/highcharts.src.js') ?>"></script>
<script type="text/javascript">
$(function () {
    $('#charts').highcharts({
        title: {
            text: 'Monthly Booking Details',
            x: -20
        },
        xAxis: {
            categories: [ <?php echo "'".implode("','",$mon)."'";?> ]
        },
        yAxis: {
            title: {
                text: 'Hostal Booking'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: ''
        },
        legend: {
            layout: 'vertical',
            align: 'center',
            verticalAlign: 'top',
            borderWidth: 0,
			y:20
        },
        series: [{
            name: 'Confirm Booking',
            data: [<?php echo implode(",",$confirm);?>]
        }, {
            name: 'Cancel Booking',
            data: [<?php echo implode(",",$cancel);?>]
        }]
    });
});
</script>
<div id="content" class="clearfix" align="center">
  <div id="overviewContent">
    <h2>DASHBOARD</h2>   
    <div id="charts"></div>
  </div>
  <div id="quickLinks">
    <h2>Quick Links</h2>
    <div class="icons">
      <ul>       
        <li><a href="<?php //echo $view['router']->generate('mytrip_admin_staticpage');?>"><img src="<?php echo $view['assets']->getUrl('img/quick_links/Paper-pencil.png');?>"/><span>Static&nbsp;Pages</span></a></li>       
        <li><a href="<?php echo $view['router']->generate('mytrip_admin_logout');?>"><img src="<?php echo $view['assets']->getUrl('img/quick_links/X.png');?>"/><span>Logout</span></a></li>
      </ul>
    </div>
  </div>
</div>
 