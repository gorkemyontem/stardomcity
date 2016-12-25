<?php

/**
 * Output Specification Post.
 *
 * @since 1.0.0
 */


function wpsm_spec_tabs_render($postid){
	if(!$postid) return;
	$out = '';
	$i = $count = 0;
	$gettabs = get_post_meta($postid, '_wpsm_spec_tab_group', true);
	$tabscompact = get_post_meta($postid, 'tabs_compact', true);
	$tabs_style = (!empty($tabscompact)) ? ' compact_spec_ul': '';	
	if(!empty($gettabs) && is_array($gettabs)){
		$out .= '<div class="wpsm_spec_tab_group"><ul class="wpsm_spec_tab_ul'.$tabs_style.'">';
		foreach ($gettabs as $gettab) {
			$icontab = (!empty($gettab['tab_layout_icon'])) ? '<i class="fa '.$gettab['tab_layout_icon'].'"></i>' : '';
            $out .= '<li id="wpsm_color_tab_li_'.$i.'"';
            if ($i==0){
            	$out .= ' class="active"';
            }
            $out .=' role="wpsm_tab_spec_li">';
		    if (!empty($gettab['spec_assign_tab_color'])) :
			    $out .= '<style scoped>#wpsm_color_tab_li_'.$i.' a{color: '.$gettab['spec_assign_tab_color'].'}#wpsm_color_tab_li_'.$i.':not(.active) a:hover{background-color: '.$gettab['spec_assign_tab_color'].'}</style>';
		    endif;            
            $out .='<a href="#wpsm_spec_tab_li_'.$i.'" aria-controls="wpsm_spec_tab_li_'.$i.'" role="tab" data-toggle="tab" aria-expanded="true">';
            $out .= $icontab.'<span class="wpsm_spec_tab_title">'.$gettab['tab_layout_title'].'</span>';
            $out .='</a></li>';
			$i++;
		}
		$out .= '</ul></div>';		
		$out .= '<div class="wpsm_spec_tab_wrapcont">';
		foreach ($gettabs as $gettab) {
			$activecont = ($count ==0) ? ' active' : '';
			$out .= '<div role="wpsmtabcontent" class="tab-pane'.$activecont.'" id="wpsm_spec_tab_li_'.$count.'">';
				$out .= do_shortcode($gettab['tab_layout_shortcode']);
			$out .= '</div>';
			$count++;
		}
		$out .= '</div>';
	}
	return $out;
}

function wpsm_spec_tabs_render_inner($postid, $content, $title = '', $icon = '', $color=''){
	if(!$postid) return;
	$out = '';

	$i = $count = 1;
	$gettabs = get_post_meta($postid, '_wpsm_spec_tab_group', true);
	$tabscompact = get_post_meta($postid, 'tabs_compact', true);
	$tabs_style = (!empty($tabscompact)) ? ' compact_spec_ul': '';
	if(!empty($gettabs) && is_array($gettabs)){
		$out .= '<div class="wpsm_spec_tab_group"><ul class="wpsm_spec_tab_ul'.$tabs_style.'">';
		//First tab for default content
		$out .= '<li id="wpsm_color_tab_li_0" class="active">';
		if (!empty($color)) :
	    	$out .= '<style scoped>#wpsm_color_tab_li_0 a{color: '.$color.'}#wpsm_color_tab_li_0:not(.active) a:hover{background-color: '.$color.'}</style>';
	    endif;		
        $out .='<a href="#wpsm_spec_tab_li_0" aria-controls="wpsm_spec_tab_li_0" role="tab" data-toggle="tab" aria-expanded="true">';
            $out .= '<i class="fa '.$icon.'"></i><span class="wpsm_spec_tab_title">'.$title.'</span>';
        $out .='</a></li>';
        // END First tab for default content

		foreach ($gettabs as $gettab) {
			$icontab = (!empty($gettab['tab_layout_icon'])) ? '<i class="fa '.$gettab['tab_layout_icon'].'"></i>' : '';
            $out .= '<li id="wpsm_color_tab_li_'.$i.'"';
            $out .='>';
		    if (!empty($gettab['spec_assign_tab_color'])) :
			    $out .= '<style scoped>#wpsm_color_tab_li_'.$i.' a{color: '.$gettab['spec_assign_tab_color'].'}#wpsm_color_tab_li_'.$i.':not(.active) a:hover{background-color: '.$gettab['spec_assign_tab_color'].'}</style>';
		    endif;            
            $out .='<a href="#wpsm_spec_tab_li_'.$i.'" aria-controls="wpsm_spec_tab_li_'.$i.'" role="tab" data-toggle="tab" aria-expanded="true">';
            $out .= $icontab.'<span class="wpsm_spec_tab_title">'.$gettab['tab_layout_title'].'</span>';
            $out .='</a></li>';
			$i++;
		}
		$out .= '</ul></div>';		
		$out .= '<div class="wpsm_spec_tab_wrapcont">';

		$out .= '<div role="wpsmtabcontent" class="tab-pane active" id="wpsm_spec_tab_li_0">';
			$out .= $content;
		$out .= '</div>';

		foreach ($gettabs as $gettab) {
			$out .= '<div role="wpsmtabcontent" class="tab-pane" id="wpsm_spec_tab_li_'.$count.'">';
				$out .= do_shortcode($gettab['tab_layout_shortcode']);
			$out .= '</div>';
			$count++;
		}
		$out .= '</div>';
	}
	return $out;
}

/**
 * Assign function for post types
 *
 * @since 1.1
 */
function wpsm_spec_fields_assign( $content ) {
	global $post;

	if( is_feed() || !is_singular()) return $content;
	$assign_options = wpsmsf_get_option('_wpsm_spec_options_layout');
	if (empty($assign_options)) {return $content;}
	foreach ($assign_options as $assign_option) {
		if (!empty($assign_option['spec_assign_layout']) && !empty($assign_option['spec_assign_posttype'])){
			if (is_singular($assign_option['spec_assign_posttype'])){

				if(!empty($assign_option['spec_assign_custom_cat'])){
					$catarray = explode(',', $assign_option['spec_assign_custom_cat']);
					$taxonomy = (empty($assign_option['spec_assign_custom_tax'])) ? 'category' : $assign_option['spec_assign_custom_tax'];
					if( has_term( $catarray, $taxonomy) ) {
						if($assign_option['spec_assign_show'] == 'top'){
							$tabsrender = wpsm_spec_tabs_render($assign_option['spec_assign_layout']);
							$content = $tabsrender.$content;
						}
						elseif($assign_option['spec_assign_show'] == 'bottom'){
							$tabsrender = wpsm_spec_tabs_render($assign_option['spec_assign_layout']);
							$content = $content.$tabsrender;					
						}
						elseif($assign_option['spec_assign_show'] == 'firsttab'){
							$title = (!empty($assign_option['spec_assign_tab_title'])) ? $assign_option['spec_assign_tab_title'] : 'Overview';
							$icon = (!empty($assign_option['spec_assign_tab_icon'])) ? $assign_option['spec_assign_tab_icon'] : 'fa-star';
							$color = (!empty($assign_option['spec_assign_tab_color'])) ? $assign_option['spec_assign_tab_color'] : '#111111';
							$content = wpsm_spec_tabs_render_inner($assign_option['spec_assign_layout'], $content, $title, $icon, $color);
						}
					}
				}else{
					if($assign_option['spec_assign_show'] == 'top'){
						$tabsrender = wpsm_spec_tabs_render($assign_option['spec_assign_layout']);
						$content = $tabsrender.$content;
					}
					elseif($assign_option['spec_assign_show'] == 'bottom'){
						$tabsrender = wpsm_spec_tabs_render($assign_option['spec_assign_layout']);
						$content = $content.$tabsrender;					
					}
					elseif($assign_option['spec_assign_show'] == 'firsttab'){
						$title = (!empty($assign_option['spec_assign_tab_title'])) ? $assign_option['spec_assign_tab_title'] : 'Overview';
						$icon = (!empty($assign_option['spec_assign_tab_icon'])) ? $assign_option['spec_assign_tab_icon'] : 'fa-star';
						$color = (!empty($assign_option['spec_assign_tab_color'])) ? $assign_option['spec_assign_tab_color'] : '#111111';
						$content = wpsm_spec_tabs_render_inner($assign_option['spec_assign_layout'], $content, $title, $icon, $color);
					}					
				}
								
			}
		}
	}
    return $content;
}
add_filter( 'the_content', 'wpsm_spec_fields_assign', 99999 ); 