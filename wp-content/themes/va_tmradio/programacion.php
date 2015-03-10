<?php
/*
Template Name: Programación
*/
function hora_programa($registro)
{
	$hora = '';
	$h = $registro->hour;
	$m = $registro->minute;
	$ampm = ($h < 12)?'a.m.':'p.m.';
	$h = ($h > 12)?$h-12:$h;
	$h = ($h < 10)?'0'.$h:$h;
	$m = ($m < 10)?'0'.$m:$m;
	$hora = $h.':'.$m.' '.$ampm;
	return $hora;
}

function ordenar_hora($dia)
{
	$cantidadProgramas = count($dia);
	$i = 0;
	
	while($i < $cantidadProgramas )
	{
		if( $i + 1 >= $cantidadProgramas )
		{
			break;
		}
		
		if( $dia[$i]->hour > $dia[$i+1]->hour )
		{
			$aux 		= $dia[$i];
			$dia[$i] 	= $dia[$i+1];
			$dia[$i+1] 	= $aux;
			$i = 0;
		}
		else
		{
			$i = $i + 1;
		}

	}
	return $dia;
}

global $wpdb;
if (!isset($wpdb->term_schedule)) {
	$wpdb->term_schedule = $wpdb->prefix . 'term_schedule';
}
$scheduleq 	= "SELECT * FROM {$wpdb->term_schedule} ORDER BY day ASC";
$schedule 	= $wpdb->get_results( $scheduleq );
$dias = array(1 => array(), 2 => array(), 3 => array(), 4 => array(), 5 => array(), 6 => array(), 7 => array());
foreach($schedule as $registro):
	$dias[$registro->day][] = $registro;
endforeach;
?>
<?php get_header(); ?>
<div class="col-sm-9 col-xs-12 programacion">
	<div id="main-content">
		<div id="programacion">
			<ul class="resp-tabs-list">
		        <li>Lunes</li>
		        <li>Martes</li>
		        <li>Miércoles</li>
		        <li>Jueves</li>
		        <li>Viernes</li>
		        <li>Sábado</li>
		        <li>Domingo</li>
		    </ul>
		    <div class="resp-tabs-container">
		    <?php foreach($dias as $dia): ?>
		   	 <?php $dia = ordenar_hora($dia) ?>
			<div><?php foreach($dia as $registro):
				$cat = get_category( $registro->term_id );
				if (function_exists('z_taxonomy_image_url')) $cat_img = z_taxonomy_image_url($registro->term_id, 'thumbnail');
			?>
				<div class="programa">
					<a href="<?php echo get_category_link( $cat->cat_ID ) ?>">
						<time><?php echo hora_programa($registro);?></time>
						<span><?php echo $cat->name ?></span>
						<!-- <img src="<?php //echo $cat_img?>" alt="<?php //echo $cat->name?>" width="70" height="70" class="hidden-xs" /> -->
					</a>
				</div>	
			<?php endforeach;?></div>
		<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>