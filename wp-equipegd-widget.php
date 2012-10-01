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
	$taxonomy		 = 'grupo_equipegd';
	$titulo			 = empty($instance['titulo']) ? ' ' : apply_filters('widget_titulo', $instance['titulo']);
    $colunas		 = $instance['colunas'];
    $custom_post 	 = 'equipegd_equipe';
    $args_query_post .= "post_type=" . $custom_post;
	$js = new ListaEquipeGDWidget();
	
	//$args_query_post .= "tax_query='terms=gabinetedigital'";
//			'field' => 'slug',
//			'terms' => 'bob''";
	
	$args_query_post .= "&orderby=meta_value_num&meta_key=wp_equipegd_ordem";
	$args_query_post .= "&order=ASC";

	query_posts($args_query_post);

	$txtreturn .= "<div class='equipe'>";
	$arr = array();
	$i = 0;
	if (have_posts()) : 
		while (have_posts()) : the_post(); 
			$nome  = get_the_title();
			$cargo = get_post_meta(get_the_ID(),'wp_equipegd_cargo', true);
			$ordem = get_post_meta(get_the_ID(),'wp_equipegd_ordem', true);
			$grupo = wp_get_post_terms(get_the_ID(), $taxonomy, array('fields' => 'names'));
			$grupo = $grupo[0];

			$arr[$i] =  array("nome" => $nome, "cargo" => $cargo, "ordem" => $ordem, "grupo" => $grupo);
			
			$i++;
		endwhile;
	endif; 
	wp_reset_query();

	$array_ordenado = $js->ordenar_array($arr, 'grupo', SORT_ASC, 'ordem', SORT_ASC) or die('<br>ERROR!<br>');
	
/*	foreach ($arr as $key => $row) {   
    	$filtro[$key]  = $row['grupo'];   
    }
    array_multisort($filtro, SORT_ASC, $arr);
 */ 
 	$i = 0;
	$cont=0;
	$x = 0;
	$nomegrupo = array();
	while (list($key, $value) = each($array_ordenado)) {
    	//$txtreturn .= "Key: $key; Value: $value[grupo]<br />\n";
    	$grupo = substr($value[grupo], 1,strlen($value[grupo]));
    	if ($cont == 0){
    		$grupo_inic = $grupo;
    	}
    	if($grupo != $grupo_inic){
    		$grupo_inic = $grupo;
			$i = 0;
			$txtreturn .= "</ul>";
			$txtreturn .= "</div>";
    	}
    	if ($i == 0){
			$x++;
			$nomegrupo[$x] = $grupo;
    		$txtreturn .= "<div id='equipe-grupo-$x'>";
    		$txtreturn .= "<h3>".$titulo." - ".$grupo."</h3>";
			$txtreturn .= "<ul class='thumbnails'>";
    	}
    	$txtreturn .= "<li class='span".$colunas."'>";
		$txtreturn .= "<div class='thumbnail'>";
		$txtreturn .= "<h4>$value[nome]</h4>";
		//$txtreturn .= "<h5>".$value[ordem]." - ". substr($value[grupo], 1,strlen($value[grupo])) . " - ". $value[cargo] . "</h5>";
		$txtreturn .= "<h5>". $value[cargo] . "</h5>";
		$txtreturn .= "</div>";
		$txtreturn .= "</li>";
		
		$cont++;
		$i++;
	}
	
	$txtreturn .= "</ul>";
	$txtreturn .= "</div>";
	$txtreturn .= "<input name='equipe-perpage' type='hidden' id='equipe-perpage' value='$x' />";
	$txtreturn .= "<div>";
	$i = 1;
	foreach($nomegrupo as $g){
		$txtreturn .= "<a class='equipe-grupo-$i equipe-grupo' data-index=$i>$g</a>";
		$i++;
	}
	$txtreturn .= "</div>";
	$txtreturn .= "</div>";
	
	echo $txtreturn;
  }

	function ordenar_array() { 
		$n_parametros = func_num_args();  
		if ($n_parametros<3 || $n_parametros%2!=1) {  
			return false; 
		} else {  
			$arg_list = func_get_args(); 

			if (!(is_array($arg_list[0]) && is_array(current($arg_list[0])))) { 
				return false;  
			} 

			for ($i = 1; $i<$n_parametros; $i++) {  
				if ($i%2!=0) { 
					if (!array_key_exists($arg_list[$i], current($arg_list[0]))) { 
						return false; 
					} 
				} else {  
					if ($arg_list[$i]!=SORT_ASC && $arg_list[$i]!=SORT_DESC) { 
						return false; 
					} 
				} 
			} 
			$array_salida = $arg_list[0]; 

			$a_evaluar = "foreach (\$array_salida as \$fila){\n"; 
			for ($i=1; $i<$n_parametros; $i+=2) { 
  				$a_evaluar .= "  \$campo{$i}[] = \$fila['$arg_list[$i]'];\n"; 
			} 
			$a_evaluar .= "}\n"; 
			$a_evaluar .= "array_multisort(\n"; 
			for ($i=1; $i<$n_parametros; $i+=2) {  
				$a_evaluar .= "  \$campo{$i}, SORT_REGULAR, \$arg_list[".($i+1)."],\n"; 
			} 
			$a_evaluar .= "  \$array_salida);"; 
			eval($a_evaluar); 
			return $array_salida; 
		} 
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