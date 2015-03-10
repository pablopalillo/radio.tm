<?php

	class Actions {
		
		
		// Espace de nom à utiliser sur Wordpress
		const _NAMESPACE = 'simple_google_analytics' ;
		
		
		// Fichiers JS à charger
		private $javascript = array(
			'admin' => array(
				'sga.js',
			),
			'front' => array(
			),
		) ;
		
		
		// Actions utilisées, et fonctions associés
		private $actions = array(
			'init' => 'launch_i18n',
			'admin_init' => 'registerSettings',
			'admin_menu' => 'menu',
			'wp_%%LOCATION%%' => 'insertCode',
		) ;
		
		
		// Filtres
		private $filters = array(
			'plugin_action_links' => 'actionLinks',
		) ;
		
		
		// Constructeur
		public function __construct() {
			$this->addAll() ;
		}
		
		
		// On lance les ajouts d'actions, de filtres, ...
		private function addAll() {
			$this->addJS() ;
			$this->addActions() ;
			$this->addFilters() ;
		}
		
		// Ajout des Javascripts
		// To fix .. Fichiers JS à charger
		protected $adminJSFiles = array() ;
		protected $frontJSFiles = array() ;
		private function addJS() {
			// Pour chaque fichier JS
			foreach ($this->javascript as $location => $files) {
				switch ($location) {
					case 'admin':
						if (!empty($files)) {
							$this->adminJSFiles = $files ;
							add_action('admin_enqueue_scripts', array(&$this, 'addAdminJS')) ;
						}
						break ;
					case 'front':
						if (!empty($files)) {
							$this->frontJSFiles = $files ;
							add_action('wp_enqueue_scripts', array(&$this, 'addFrontJS')) ;
						}
						break ;
					default:
						break ;
				}
			}
		}

		public function addAdminJS() {
			foreach ($this->adminJSFiles as $file) {
				wp_enqueue_script(self::_NAMESPACE, plugins_url('', dirname(__FILE__)) . '/js/' . $file, array('jquery')) ;
			}
		}

		public function addFrontJS() {
			foreach ($this->frontJSFiles as $file) {
				wp_enqueue_script(self::_NAMESPACE, plugins_url('', dirname(__FILE__)) . '/js/' . $file, array('jquery')) ;
			}
		}
		
		
		///////////// ACTIONS
		
		// Lancement des actions
		private function addActions() {
				
			// Pour chaques actions
			foreach ($this->actions as $key => $value) {
				$key = str_replace('%%LOCATION%%', Settings::getVal('sga_code_location'), $key) ;
				add_action($key, 'Actions::' . $value) ;
			}
			
		}
		
		
		// Internationalisation : Fonction lancée au chargement du plugin
		public static function launch_i18n() {
			$lngPath = SGA_ROOT . '/languages/' ;
			load_plugin_textdomain(self::_NAMESPACE, false, $lngPath) ;
		}
		
		
		// Enregistrement des paramètres (et récuperation aussi)
		public static function registerSettings() {
			Settings::registerSettings() ;
		}
		
		
		// Ajoute le sous-menu aux réglages
		public static function menu() {
			add_options_page(SGA_PLUGIN_TITLE, Output::__('Simple Google Analytics Settings'), SGA_SETTINGS_AUTH, 'simple-google-analytics-config', "Output::settingsPage") ;
		}
		
		
		// Insère le code Google Analytics
		public static function insertCode() {
			if (self::showCode()) {
				
				$options = array() ;
				$demographic = false ;
				
				// Seulement si on a enregistré un ID
				if (Settings::getVal('sga_analytics_id') !== false) {
					$options['_setAccount'] = Settings::getVal('sga_analytics_id') ;
					
					// Option multi-domaine
					if (Settings::getVal('sga_multidomain_setting') == 1 && Settings::getVal('sga_multidomain_domain') !== false) {
						$options['_setDomainName'] = Settings::getVal('sga_multidomain_domain') ;
					}
					
					// Option vitesse du site
					if (Settings::getVal('sga_sitespeed_setting') == 1) {
						$options['_trackPageLoadTime'] = null ;
					}

					// Option demographique / interets
					if (Settings::getVal('sga_demographic_and_interest') == 1) {
						$demographic = true ;
					}
					
					$options['_trackPageview'] = null ;
					

					echo "\n" ;
					echo '<!-- Simple Google Analytics Begin -->' . "\n" ;
					echo Output::googleCode($options, $demographic) ;
					// Si l'option de tracking est activée
					if (Settings::getVal('sga_track_links_downloads') == 1) {
						echo Output::addTracking() ;
					}
					echo "\n" . '<!-- Simple Google Analytics End -->' ;
					echo "\n" ;
				}
			}
			else {

				// Afficher un commentaire pour spécifier que le plugin est bien chargé
				echo "\n" . '<!-- Simple Google Analytics - Code is not active while you are being logged. This option can be changed in the parameters -->' . "\n" ;
			}
		}

		
		///////////////// FILTRES
		
		// Ajout des filtres
		private function addFilters() {
			
			// Pour chaques filtres
			foreach ($this->filters as $key => $value) {
				add_filter($key . '_' . SGA_BASENAME, 'Actions::' . $value) ;
			}
			
		}
		
		
		// Insère un lien sur la page des plugins
		public static function actionLinks($links) {
			$link = '<a href="' . menu_page_url('simple-google-analytics-config' , false) . '">' . Output::__('Settings') .'</a>' ;
			array_unshift($links, $link) ;
			return $links ;
		}
		
		
		// @TODO
		// Vérifie si on doit afficher le code ou pas, selon l'utilisateur connecté
		public static function showCode() {
			$result = true ;
			if( is_user_logged_in() ) {
				$result = Settings::getVal('sga_render_when_loggedin') ;
			}
			return $result ;
		}

	}
	
?>
