<?php 
$useragent = $_SERVER['HTTP_USER_AGENT'];
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od|ad)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
{
	$version = 'movil';
}else
{
	$version =  'pc';
}/**/
date_default_timezone_set('America/Bogota');
$c_dow 		= date('N');
$c_hour 	= date('H');
$c_minute	= date('i');
$ch = mktime($c_hour, $c_minute);

$c = mysqli_connect('localhost', 'telemede_radio', '?t0NZc6f4-&b', 'telemede_radio');
$q = "SELECT * ";
$q .= "FROM wp_term_schedule AS ts ";
$q .= "INNER JOIN wp_terms AS t ON ts.term_id = t.term_id ";
$q .= "WHERE ts.day = $c_dow ";
//$q .= "AND ( (ts.hour = $c_hour ) ) ";
//$q .= "LIMIT 1";
$r = mysqli_query($c, $q);
$programas = array();
$current = array();
if($r)
{
	while($programa = mysqli_fetch_assoc($r))
	{
		$ip = mktime($programa['hour'], $programa['minute']);
		$fp = $ip + ($programa['duration']*60);
		
		if( $ip <= $ch && $fp >= $ch)
		{
			$current = $programa;
			$programa['current'] = true;
		}
		$programas[] = $programa;
	}
}
?>
<!doctype html>  
<html lang="es">
	<head>
		<meta charset="utf-8">
		<title>Telemedellín Radio - En vivo</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
  		<link rel="shortcut icon" href="/favicon.ico" />
  		<style type="text/css">
  			body{background:#f07b0a;font-family:"Century Gothic",Arial,sans-serif;margin:0 15px;}
  			header h1{float:left;}
  			header div{vertical-align:top;}
  			header > div{float: right;margin-top:30px;}
  			#content{clear:both;}
  			#player{float:right;}
  			#programacion{background: #98c01f;color:#fff;float:left;margin-right:15px;overflow:hidden;padding:10px;width:205px;}
  			video{width: 300px;height: 20px}
  		</style>
	</head>
	<body>
		<header>
			<h1>
				<a href="/" target="_blank">
					<img src="wp-content/themes/va_tmradio/images/logo-tmradio.jpg" alt="Telemedellín Radio" />
				</a>
			</h1>
			<div>
				<!--Facebook-->
				<div class="fb-share-button" data-type="button_count" data-width="130"></div>
				<!--Twitter-->
				<a href="https://twitter.com/share" class="twitter-share-button" data-via="telemedellin" data-lang="es">Twittear</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
				<!--G+-->
				<div class="g-plusone" data-size="medium"></div>
				<!--Pinterest-->
				<a href="//pinterest.com/pin/create/button/" data-pin-do="buttonBookmark" ><img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" /></a>

				<div id="fb-root"></div>
			</div>	
			</div>
	    </header>
	    <div id="content">
	    	<div id="programacion">
	    		<?php echo (!empty($current))?utf8_encode($current['name']):''; //El artista de mi vida ?>
	    	</div>
			<div id="player">
			<?php if($version == 'pc'): ?>
				<video controls="" autoplay="" name="media"><source src="http://62.210.209.179:8056/stream" type="audio/mpeg"></video>
			<?php else: ?>
				<video controls="" autoplay="" name="media"><source src="http://62.210.209.179:8056/stream" type="audio/mpeg"></video>
			<?php endif; ?>
			</div>
		</div>
		<script>
		var programas 	= <?php echo json_encode($programas)?>,
			current		= <?php echo (!empty($current))?json_encode($current):0?>,
			dTime 		= new Date(),
   			hours 		= dTime.getHours(),
   			minute 		= dTime.getMinutes(),
			c_hour		= (hours*60*60)+(minute*60);
			TimeO 		= new Array();
		programas.forEach(function(e, i, a){
			console.log(e);
			var ph = (e.hour*60*60)+(e.minute*60);
			console.log(ph);
			console.log(c_hour);
			if( ph > c_hour )
				setchange(ph, e, i);
		});
		function setchange(ph, e, i)
		{
			TimeO[i] = setTimeout(change, ph, e, i);
			console.log('Set timeout ' + TimeO[i]);
		}
		function change(e, i)
		{
			var pr = document.getElementById('programacion');
			pr.innerHTML = e.name;
			console.log('Change ' + e.name);
			clearTimeOut(TimeO[i]);
		}
		</script>
		<script>
		(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/es_LA/all.js#xfbml=1&appId=118796586430";
		fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>
		<script type="text/javascript">
		window.___gcfg = {lang: 'es'};
		(function() {
		var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
		po.src = 'https://apis.google.com/js/plusone.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
		})();
		</script>
		<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>
	</body>
</html>