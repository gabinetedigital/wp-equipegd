<?php
/*
 Plugin Name: WP Equipe GD
Plugin URI: http://www.procergs.rs.gov.br
Description: Plugin Wordpress Equipe GD, desenvolvido pela PROCERGS.
Version: 1.0.0
Author: Cristiane | Felipe | Leo
Author URI: http://www.procergs.rs.gov.br
*/

/*  Copyright 2012

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class WPEquipeGD{

	public function ativar(){
		add_option('wp_equipeggd', '');
	}
	public function desativar(){
		delete_option('wp_equipegd');
	}
}

$prefix = 'wp_equipegd_';

$pathPlugin = substr(strrchr(dirname(__FILE__),DIRECTORY_SEPARATOR),1).DIRECTORY_SEPARATOR.basename(__FILE__);

// Função ativar
register_activation_hook( $pathPlugin, array('WPEquipeGD','ativar'));

// Função desativar
register_deactivation_hook( $pathPlugin, array('WPEquipeGD','desativar'));

include_once('wp-equipegd-custom_post.php');
include_once('wp-equipegd-custom_taxonomy.php');
include_once('wp-equipegd-custom_metabox.php');
include_once('wp-equipegd-widget.php');

?>
