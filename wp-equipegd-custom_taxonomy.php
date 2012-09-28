<?php

function wp_equipegd_taxonomy_grupo() {
	$labels = array(
			'name' => _x( 'Grupo', 'grupo' ),
			'singular_name' => _x( 'Grupo', 'grupo' ),
			'search_items' => _x( 'Pesquisar Grupos', 'grupo' ),
			'popular_items' => _x( 'Grupos populares', 'grupo' ),
			'all_items' => _x( 'Todos os Grupos', 'grupo' ),
			'parent_item' => _x( 'Grupo Pai', 'grupo' ),
			'parent_item_colon' => _x( 'Grupo Pai:', 'grupo' ),
			'edit_item' => _x( 'Editar Grupo', 'grupo' ),
			'update_item' => _x( 'Atualizar Grupo', 'grupo' ),
			'add_new_item' => _x( 'Adicionar Novo Grupo', 'grupo' ),
			'new_item_name' => _x( 'Novo Grupo', 'grupo' ),
			'separate_items_with_commas' => _x( 'Separar grupo por virgulas', 'grupo' ),
			'add_or_remove_items' => _x( 'Adicionar ou Remover Grupos', 'grupo' ),
			'choose_from_most_used' => _x( 'Selecionar Grupos mais utilizados', 'grupo' ),
			'menu_name' => _x( 'Grupos', 'grupo' ),
	);
	$args = array(
			'labels' => $labels,
			'public' => true,
			'show_in_nav_menus' => true,
			'show_ui' => true,
			'show_tagcloud' => true,
			'hierarchical' => true,
			'rewrite' => true,
			'query_var' => true
	);
	register_taxonomy( 'grupo_equipegd', array('equipegd_equipe'), $args );
}

add_action( 'init', 'wp_equipegd_taxonomy_grupo' );

?>