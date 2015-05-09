<?php 
				if( is_home() ): 
					get_sidebar('home');
				else:
					get_sidebar(); 
				endif;
				?>
			</div>
			<div class="row">
				<div class="col-sm-5 pull-right">
					<?php if(function_exists('kc_add_social_share')): ?>
						<div id="share"><?php kc_add_social_share(); ?></div>
					<?php endif ?>
				</div>
			</div>
		</div>
		
		<footer id="footer" class="container">
			<div class="row">
				<p class="col-sm-4">También nos puedes escuchar en: <a href="http://tunein.com/radio/Telemedell%C3%ADn-Radio-s216062/" class="icono tunein" rel="nofollow" target="_blank">TuneIn</a> <a href="http://www.nobexpartners.com/ourwebplayer.aspx?id=38354" class="icono winamp" rel="nofollow" target="_blank">Winamp</a><a href="http://62.210.209.179:2199/tunein/telemedradio.pls" class="icono itunes" rel="nofollow" target="_blank">iTunes</a></p>
				<p class="col-sm-4">Descarga la app en: <a href="https://play.google.com/store/apps/details?id=com.nobexinc.wls_13141528.rc" class="icono playstore" rel="nofollow" target="_blank">Play Store</a>
				 <a href="https://itunes.apple.com/tr/app/telemedellin-radio/id839485359?mt=8" class="icono appstore" rel="nofollow" target="_blank">App Store</a>
				 <a href="http://www.windowsphone.com/en-us/store/app/telemedell%C3%ADn-radio/db2cdeae-2086-4a95-b7a0-62e8deb5f464" class="icono windowsm" rel="nofollow" target="_blank">Windows Phone</a>
				 <a href="http://appworld.blackberry.com/webstore/content/49762905/?lang=en&countrycode=CO" class="icono blackberry" rel="nofollow" target="_blank">Blackberry</a>
				</p>
				<p class="col-sm-3 col-sm-offset-1">Un proyecto de <a href="http://telemedellin.tv" rel="nofollow" target="_blank">www.telemedellin.tv</a></p>
			</div>
		</footer>
		<?php wp_footer(); // js scripts are inserted using this function ?>
		<div id="fb-root"></div>
	</body>
</html>
