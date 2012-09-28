<?php

/********************************************
 * 
 * WIDGET PARA MONTAR UMA LISTA DA EQUIPE
 * 
 ********************************************/
class ListaEquipeGDWidget extends WP_Widget
{
	function ListaEquipeGDWidget()
	{
		$widget_ops = array('classname' => 'ListaEquipeGDWidget', 'description' => 'Lista dos membros da equipe do Gabinete Digital.' );
		$this->WP_Widget('ListaEquipeGDWidget', 'Gabinete Digital - Equipe', $widget_ops);		
	}

	function form($instance)
	{
		$instance = wp_parse_args( (array) $instance, array( 'titulo' => '', 'colunas' => '3', 'css_class' => '' ) );
		$titulo = $instance['titulo'];
		$colunas = $instance['colunas'];
		$css_class = $instance['css_class'];

		?>
  		<p><label for="<?php echo $this->get_field_id('titulo'); ?>">Titulo: <input class="widefat" id="<?php echo $this->get_field_id('titulo'); ?>" name="<?php echo $this->get_field_name('titulo'); ?>" type="text" value="<?php echo attribute_escape($titulo); ?>" /></label></p>
  		<p><label for="<?php echo $this->get_field_id('colunas'); ?>">Colunas: <input class="widefat" id="<?php echo $this->get_field_id('colunas'); ?>" name="<?php echo $this->get_field_name('colunas'); ?>" type="text" value="<?php echo attribute_escape($colunas); ?>" /></label></p>
  		<p><label for="<?php echo $this->get_field_id('css_class'); ?>">Classe CSS: <input class="widefat" id="<?php echo $this->get_field_id('css_class'); ?>" name="<?php echo $this->get_field_name('css_class'); ?>" type="text" value="<?php echo attribute_escape($css_class); ?>" /></label></p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['titulo'] = $new_instance['titulo'];
    $instance['colunas'] = $new_instance['colunas'];
    $instance['css_class'] = $new_instance['css_class'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    $args_query_post = '';
	$txtreturn		 = '';
	$titulo			 = empty($instance['titulo']) ? ' ' : apply_filters('widget_titulo', $instance['titulo']);
    $colunas		 = $instance['colunas'];
    $custom_post 	 = 'equipegd_equipe';
    $args_query_post .= "post_type=" . $custom_post;
	$args_query_post .= "&orderby=meta_value_num&meta_key=wp_equipegd_ordem";
	$args_query_post .= "&order=ASC";

	query_posts($args_query_post);

	$txtreturn .= "<div class='equipe'>";
	$txtreturn .= "<h3>".$titulo."</h3>";
	$txtreturn .= "<ul class='thumbnails'>";

	if (have_posts()) : 
		while (have_posts()) : the_post(); 
			$cargo = get_post_meta(get_the_ID(),'wp_equipegd_cargo', true);
			$ordem = get_post_meta(get_the_ID(),'wp_equipegd_ordem', true);
			$grupo = get_the_term_list(get_the_ID(), 'grupo_equipegd' );

			$txtreturn .= "<li class='span".$colunas."'>";
			$txtreturn .= "<div class='thumbnail'>";
			$txtreturn .= "<h4>".get_the_title()."</h4>";
			$txtreturn .= "<h5>". $cargo . "</h5>";
			$txtreturn .= "</div>";
			$txtreturn .= "</li>";

		endwhile;
	endif; 
	wp_reset_query();
	
	$txtreturn .= "</ul>";
	$txtreturn .= "</div>";
	
	echo $txtreturn;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("ListaEquipeGDWidget");') );

/******************************************
 *WIDGET PARA MONTAGEM DE UM MEMBRO EM ESPECIFICO PELO POST_ID
 *****************************************/
class EquipeGDWidget extends WP_Widget
{
	function EquipeGDWidget()
	{
		$widget_ops = array('classname' => 'EquipeGDWidget', 'description' => 'Exibe apenas um membro da equipe do Gabinete Digital.' );
		$this->WP_Widget('EquipeGDWidget', 'Gabinete Digital - Equipe', $widget_ops);
	}

	function form($instance)
	{
		$instance = wp_parse_args( (array) $instance, array( 'titulo' => '', 'post_id' => '', 'colunas' => '3', 'css_class' => '' ) );
		$titulo = $instance['titulo'];
		$post_id = $instance['post_id'];
		$colunas = $instance['colunas'];
		$css_class = $instance['css_class'];

		?>
  		<p><label for="<?php echo $this->get_field_id('titulo'); ?>">Titulo: <input class="widefat" id="<?php echo $this->get_field_id('titulo'); ?>" name="<?php echo $this->get_field_name('titulo'); ?>" type="text" value="<?php echo attribute_escape($titulo); ?>" /></label></p>
  		<p><label for="<?php echo $this->get_field_id('post_id'); ?>">ID: <input class="widefat" id="<?php echo $this->get_field_id('post_id'); ?>" name="<?php echo $this->get_field_name('post_id'); ?>" type="text" value="<?php echo attribute_escape($post_id); ?>" /></label></p>
  		<p><label for="<?php echo $this->get_field_id('colunas'); ?>">Colunas: <input class="widefat" id="<?php echo $this->get_field_id('colunas'); ?>" name="<?php echo $this->get_field_name('colunas'); ?>" type="text" value="<?php echo attribute_escape($colunas); ?>" /></label></p>
  		<p><label for="<?php echo $this->get_field_id('css_class'); ?>">Classe CSS: <input class="widefat" id="<?php echo $this->get_field_id('css_class'); ?>" name="<?php echo $this->get_field_name('css_class'); ?>" type="text" value="<?php echo attribute_escape($css_class); ?>" /></label></p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['titulo'] = $new_instance['titulo'];
    $instance['post_id'] = $new_instance['post_id'];
    $instance['colunas'] = $new_instance['colunas'];
    $instance['css_class'] = $new_instance['css_class'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    $args_query_post = '';

    echo "<li class='span".$instance['colunas']."'><div class='thumbnail membro ".$instance['css_class']."'>";
    $titulo = empty($instance['titulo']) ? ' ' : apply_filters('widget_titulo', $instance['titulo']);
    $post_id = $instance['post_id'];
    $colunas = $instance['colunas'];
    $custom_post = 'equipegd_equipe';
    
    echo $before_title . $titulo . $after_title;;
    if (!empty($post_id)):
    	$args_query_post = $args_query_post . "p=" . $post_id . "&post_type=" . $custom_post;
    	query_posts($args_query_post);
    endif;
	if (have_posts()) : 
		echo "<ul>";
		while (have_posts()) : the_post(); 
			$cargo = get_post_meta(get_the_ID(),'wp_equipegd_cargo', true);
			$ordem = get_post_meta(get_the_ID(),'wp_equipegd_ordem', true);
			
			echo "<li>".get_the_title()."<br>Cargo: " . $cargo . "<br>Ordem: " . $ordem ."</li>";
	 		
		endwhile;
		echo "</ul>";
	endif; 
	wp_reset_query();
	
	echo "</div></li>";
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("EquipeGDWidget");') );

?>