<?php
global $meta_boxes_equipegd;

$prefix = 'wp_equipegd_';

$meta_boxes_equipegd = array();

$meta_boxes_equipegd[] = array(
		'id' => $prefix.'info_geral',
		'title' => 'Informações Gerais',
		'pages' => array('equipegd_equipe'),
		'context'=> 'normal',
		'priority'=> 'high',
		'fields' => array(
				array(
						'name'		=> 'Cargo',
						'id'		=> $prefix . 'cargo',
						'desc'		=> 'Cargo que o membro da equipe ocupa',
						'type'		=> 'text'
				),
				array(
						'name'		=> 'Ordem',
						'id'		=> $prefix . 'ordem',
						'desc'		=> 'Ordem que o membro deve aparecer',
						'type'		=> 'text'
				),
				array(
						'name'		=> 'Grupo',
						'id'		=> $prefix . 'grupo',
						'desc'		=> 'Grupo a que o membro pertence',
						'type'		=> 'text'
				)
		)
);


function wp_equipegd_register_meta_boxes()
{
	global $meta_boxes_equipegd;

	if ( class_exists( 'RW_Meta_Box' ) )
	{
		foreach ( $meta_boxes_equipegd as $meta_box )
		{
			new RW_Meta_Box( $meta_box );
		}
	}
}

add_action('admin_init', 'wp_equipegd_register_meta_boxes' );

?>