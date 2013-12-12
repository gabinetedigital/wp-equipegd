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
		$this->WP_Widget('ListaEquipeGDWidget', 'Gabinete Digital - Equipe Lista', $widget_ops);
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
	$txtreturn_topo  = '';
	$taxonomy		 = 'grupo_equipegd';
	$titulo			 = empty($instance['titulo']) ? ' ' : apply_filters('widget_titulo', $instance['titulo']);
    $colunas		 = $instance['colunas'];
    $custom_post 	 = 'equipegd_equipe';
    $args_query_post .= "post_type=" . $custom_post;
	$js = new ListaEquipeGDWidget();

	$args_query_post .= "&orderby=meta_value_num&meta_key=wp_equipegd_ordem";
	$args_query_post .= "&order=ASC";

	$args_query_post .= "&posts_per_page=-1"; //para vir todos os posts

	query_posts($args_query_post);

	$txtreturn .= "<div class='equipe ".$instance['css_class']."'>";
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

 	$i = 0;
	$cont=0;
	$x = 0;
	$nomegrupo = array();

	while (list($key, $value) = each($array_ordenado)) {
		$grupo = substr($value[grupo], 1,strlen($value[grupo]));
		if ($cont == 0){
    		$grupo_inic = $grupo;
    	}
    	if($grupo != $grupo_inic){
    		$grupo_inic = $grupo;
			$i = 0;
    	}
    	if ($i == 0){
			$x++;
			$nomegrupo[$x] = $grupo;
    	}

		$cont++;
		$i++;
	}
	$txtreturn_topo .= "<div class='btn-group subgrupo' data-toggle='buttons-radio'>";
	$i = 1;
	foreach($nomegrupo as $g){
		$txtreturn_topo .= "<button class='btn equipe-grupo-$i equipe-grupo' data-index=$i>$g</button>";
		$i++;
	}
	$txtreturn_topo .= "</div>";

	$array_ordenado = $js->ordenar_array($arr, 'grupo', SORT_ASC, 'ordem', SORT_ASC) or die('<br>ERROR!<br>');

	$i = 0;
	$cont=0;
	$x = 0;


	while (list($key, $value) = each($array_ordenado)) {
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
    		$txtreturn .= "<div id='equipe-grupo-$x'>";
    		$txtreturn .= "<h3>".$titulo." - ".$grupo."</h3>";
			$txtreturn .= $txtreturn_topo;
			$txtreturn .= "<ul class='thumbnails'>";
    	}
    	$txtreturn .= "<li class='span".$colunas."'>";
		$txtreturn .= "<div class='thumbnail'>";
		$txtreturn .= "<h4>$value[nome]</h4>";
		$txtreturn .= "<h5>". $value[cargo] . "</h5>";
		$txtreturn .= "</div>";
		$txtreturn .= "</li>";

		$cont++;
		$i++;
	}

	$txtreturn .= "</ul>";
	$txtreturn .= "</div>";
	$txtreturn .= "<input name='equipe-perpage' type='hidden' id='equipe-perpage' value='$x' />";
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
		$this->WP_Widget('EquipeGDWidget', 'Gabinete Digital - Equipe Membro', $widget_ops);
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
	$txtreturn = '';

    $txtreturn .= "<li class='span".$instance['colunas']."'>";
    $txtreturn .= "<div class='thumbnail membro ".$instance['css_class']."'>";
    $titulo = empty($instance['titulo']) ? ' ' : apply_filters('widget_titulo', $instance['titulo']);
    $post_id = $instance['post_id'];
    $colunas = $instance['colunas'];
    $custom_post = 'equipegd_equipe';

    if(!empty($titulo)){
    	$txtreturn .= "<h3>".$titulo."</h3>";
	}
    if (!empty($post_id)):
    	$args_query_post = $args_query_post . "p=" . $post_id . "&post_type=" . $custom_post;
    	query_posts($args_query_post);
    endif;
	if (have_posts()) :
		while (have_posts()) : the_post();
			$cargo = get_post_meta(get_the_ID(),'wp_equipegd_cargo', true);

			$txtreturn .= "<h4>".get_the_title()."</h4>";
			$txtreturn .= "<h5>". $cargo . "</h5>";

		endwhile;
	endif;
	wp_reset_query();

	$txtreturn .= "</div></li>";

	echo $txtreturn;
  }

}
add_action( 'widgets_init', create_function('', 'return register_widget("EquipeGDWidget");') );

?>
