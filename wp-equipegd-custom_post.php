<?php

function wp_equipegd_equipe() {
	$labels = array(
			'name' => _x( 'Membro', 'equipe' ),
			'singular_name' => _x( 'Membro', 'equipe' ),
			'add_new' => _x( 'Novo Membro', 'equipe' ),
			'all_items' => _x('Membros', 'equipe'),
			'add_new_item' => _x( 'Adicionar Novo Membro', 'equipe' ),
			'edit_item' => _x( 'Editar Membro', 'equipe' ),
			'new_item' => _x( 'Novo Membro', 'equipe' ),
			'view_item' => _x( 'Visualizar Membro', 'equipe' ),
			'search_items' => _x( 'Pesquisar Membro', 'equipe' ),
			'not_found' => _x( 'Nenhum membro encontrado', 'equipe' ),
			'not_found_in_trash' => _x( 'Nenhum membro encontrado na lixeira', 'equipe' ),
			'parent_item_colon' => _x( 'Membro pai:', 'equipe' ),
			'menu_name' => _x( 'Equipe GD', 'equipe'),
	);
	$args = array(
			'labels' => $labels,
			'hierarchical' => false,
			'supports' => array( 'title', 'editor', 'author', 'comments', 'revisions'),
			'taxonomies' => array( 'grupo_equipegd'),
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 80,
			'show_in_nav_menus' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'has_archive' => true,
			'query_var' => true,
			'can_export' => true,
			'rewrite' => true,
			'capability_type' => 'post'
	);
	register_post_type( 'equipegd_equipe', $args );
}

add_action( 'init', 'wp_equipegd_equipe' );

?>