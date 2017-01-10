<?php 


class pcfme_update_checkout_fields {

     private $billing_settings_key = 'pcfme_billing_settings';
	 private $shipping_settings_key = 'pcfme_shipping_settings';
	 private $additional_settings_key = 'pcfme_additional_settings';
     
	 public function __construct() {
	    
	    
	      add_filter('woocommerce_checkout_fields', array( &$this, 'update_billing_fields' ) );
	      add_filter('woocommerce_checkout_fields', array( &$this, 'update_shipping_fields' ) );
	      add_action('woocommerce_after_order_notes', array( &$this, 'add_additional_fields' ) );
		  add_action('woocommerce_checkout_process', array( &$this, 'validate_additional_required_fields'));
 

	    
	 }
	 
	 public function validate_additional_required_fields() {
		 
		 $additional_fields = (array) get_option( $this->additional_settings_key );
	     $additional_fields =  array_filter($additional_fields);
		 $requiredtext      =  __('is a required field','pcfme');
         

       if (isset($additional_fields) && (sizeof($additional_fields) >= 1)) { 
         
        foreach ($additional_fields as $key=>$value) {
			 if (isset($value['visibility'])) {
				 $visibilityarray = $value['visibility'];
				 
				  if (isset($value['products'])) { 
				    $allowedproducts = $value['products'];
				 } else {
					$allowedproducts = array(); 
				 }
				 
				 if (isset($value['category'])) {
					 $allowedcats = $value['category'];
				 } else {
					 $allowedcats = array();
				 }
				 
				 $is_field_hidden=$this->pcfme_check_if_field_is_hidden($visibilityarray,$allowedproducts,$allowedcats);
				 
				 if ((!isset($is_field_hidden)) || ($is_field_hidden != 0)) {
					if (isset($value['required']) && ( ! $_POST[$key] )) {
				      $noticetext='<strong>'.$value['label'].'</strong> '.$requiredtext.'';
                      wc_add_notice( __( $noticetext ), 'error' );
                    }
				 }
			 }
			}
    
      }
	 }


	public function update_billing_fields($fields) {
	  global $post;
	  
	  
	  $billing_fields = (array) get_option( $this->billing_settings_key );
		
		
	  
		if (isset($billing_fields) && ($billing_fields != '')) {
		 
		 foreach ($billing_fields as $key2=>$value) {
		 
		    if (isset($value['options']) && ($value['options'] != '')) {
		      $tempoptions = explode(',',$value['options']);
		      
		      
		      $options = array();
                      
                      foreach($tempoptions as $val){
    
                         $options[$val]  = $val;
      
                      }
			 
		    }
		
		 //builds extraclass array
		 if (isset($value['extraclass']) && ($value['extraclass'] != '')) {
		      $tempclasses = explode(',',$value['extraclass']);
		      
		      
		      $extraclass = array();
                      
                      foreach($tempclasses as $classval1){
    
                         $extraclass[$classval1]  = $classval1;
      
                      }
			 
		}
			
		 if (isset($fields['billing']) && (sizeof($fields['billing']) >1)) {
		  foreach ($fields['billing'] as $key=>$billing)  {
		   
		   
		    if ($key == $key2) {
			
			
				if (isset($value['type'])) 
				    { 
					  $fields['billing'][$key]['type'] = $value['type']; 
					   
					}
				
			    
				if (isset($value['label'])) { $fields['billing'][$key]['label'] = $value['label']; }

				
				if (isset($value['width'])) { 
					       if (isset( $fields['billing'][$key]['class'])) {

					       	  foreach ($fields['billing'][$key]['class'] as $classkey=>$classvalue) {

					       	  	if ($classvalue == 'form-row-wide' || $classvalue == "form-row-first"  || $classvalue == "form-row-last") {
                                   unset($fields['billing'][$key]['class'][$classkey]);
					       	  	}
  
					       	  }
					       }
					       
				           $fields['billing'][$key]['class'][]=$value['width'];
				}
				
				if (isset($value['required']) && ($value['required'] == 1)) 
				     { $fields['billing'][$key]['required'] = $value['required']; } else {
                                      $fields['billing'][$key]['required'] = false;
                                 }
					
					 
                               if (isset($value['clear']) && ($value['clear'] == 1)) 
				     { $fields['billing'][$key]['clear'] = $value['clear']; } else {
                                     $fields['billing'][$key]['clear'] = false;
                               }	
                			 
				if (isset($value['placeholder'])) { $fields['billing'][$key]['placeholder'] = $value['placeholder']; }
				
				if (isset($value['options'])) { 
				     
					 if (isset($value['options']) && ($value['options'] != '')) {
				       $fields['billing'][$key]['options'] =$options;
					  }
					  
				}
				
				//adds extra classes to billing fields
				if (isset($extraclass) && ($extraclass != '')) {
                     
					foreach ($extraclass as $billingclassval) {
						$fields['billing'][$key]['class'][] = $billingclassval;
					}
				
				}
				
				
				
				
				if (isset($value['validate'])) { 
				      $fields['billing'][$key]['validate'] =$value['validate'];
				    }
                
				if (isset($value['disable_past'])) { 
				      $fields['billing'][$key]['disable_past'] =$value['disable_past'];
				    }
			} 
			           
			           

			 /*
			  * adds extra custom field
			  * since version 1.0.1
			  */
			  
			 if (isset($billing_fields[$key2]) && (!isset($fields['billing'][$key2]))) {
				   
				  
				   
				   if (isset($billing_fields[$key2])) {
				      $fields['billing'][$key2] = $value;
				    }
				   
				   if (isset($value['width']) && ($value['width'] != '')) {
				       $fields['billing'][$key2]['class'][] =$value['width'];
					  } 
					  
                   //adds extra classes to billing fields
				   if (isset($extraclass) && ($extraclass != '')) {
                     
					 foreach ($extraclass as $billingclassval2) {
						$fields['billing'][$key2]['class'][] = $billingclassval2;
					 }
				
				    }						  
				   
				   if (isset($value['options']) && ($value['options'] != '')) {
				       $fields['billing'][$key2]['options'] =$options;
					  }
					  
					
			 }
			 
		
			 
			
			
			
		   }
		   }
		}
       }
	   
     if ( is_checkout() ) {
	  
	   if (isset($billing_fields) && (sizeof($billing_fields) >1)) {
	     $order = $this->get_order_array($billing_fields);
		 
			foreach($order as $field)
             {
               $ordered_fields[$field] = $fields["billing"][$field];
             }

        $fields["billing"] = $ordered_fields;
           
	   } 
	   
	   
       }
    

    /**
     * Unset field key if hide is set
     */

      if (isset($billing_fields) && ($billing_fields != '')) {
		 foreach ($billing_fields as $hidekey=>$hidevalue) {
             if (isset($hidevalue['hide']) && ($hidevalue['hide'] == 1)) {
             	unset($fields['billing'][$hidekey]);
             }
			 
			 if (isset($hidevalue['visibility'])) {
				 $visibilityarray = $hidevalue['visibility'];
				 
				 if (isset($hidevalue['products'])) { 
				    $allowedproducts = $hidevalue['products'];
				 } else {
					$allowedproducts = array(); 
				 }
				 
				 if (isset($hidevalue['category'])) {
					 $allowedcats = $hidevalue['category'];
				 } else {
					 $allowedcats = array();
				 }
				 
				 
				 $is_field_hidden=$this->pcfme_check_if_field_is_hidden($visibilityarray,$allowedproducts,$allowedcats);
				 
				 if (isset($is_field_hidden) && ($is_field_hidden != 1)) {
					unset($fields['billing'][$hidekey]);
				 }
			 }
		 }
	  }


	   
	 
	  
	
	  
      return $fields;
	}
	
	public function update_shipping_fields($fields) {
	  global $post;
	  
	  $shipping_fields = (array) get_option( $this->shipping_settings_key );
	  
	 
	   
	   
	    if (isset($shipping_fields) && ($shipping_fields != '')) {
	     foreach ($shipping_fields as $key2=>$value) {
		 
		  if (isset($value['options']) && ($value['options'] != '')) {
		      
		      
		      $tempoptions = explode(',',$value['options']);
		      
		      
		      $options = array();
                      
                      foreach($tempoptions as $val){
    
                         $options[$val]  = $val;
      
                      }


			 
		   }
		   
		 //builds extraclass array
		 if (isset($value['extraclass']) && ($value['extraclass'] != '')) {
		      $tempclasses = explode(',',$value['extraclass']);
		      
		      
		      $extraclass = array();
                      
                      foreach($tempclasses as $classval2){
    
                         $extraclass[$classval2]  = $classval2;
      
                      }
			 
		  }
			
		  if (isset($fields['shipping']) && (sizeof($fields['shipping']) >1)) {
		  foreach ($fields['shipping'] as $key=>$shipping)  {
		   
		    if ($key == $key2) {
			    
			    if (isset($value['type'])) 
				    { 
					  $fields['shipping'][$key]['type'] = $value['type']; 
					   
					}
				
			    if (isset($value['label'])) { $fields['shipping'][$key]['label'] = $value['label']; }
				
				if (isset($value['width'])) { 
					       if (isset( $fields['shipping'][$key]['class'])) {

					       	  foreach ($fields['shipping'][$key]['class'] as $classkey=>$classvalue) {

					       	  	if ($classvalue == 'form-row-wide' || $classvalue == "form-row-first"  || $classvalue == "form-row-last") {
                                   unset($fields['shipping'][$key]['class'][$classkey]);
					       	  	}
  
					       	  }
					       }
					       
				           $fields['shipping'][$key]['class'][]=$value['width'];
				}
				
				
				
			    if (isset($value['required']) && ($value['required'] == 1)) 
				     { $fields['shipping'][$key]['required'] = $value['required']; } else {
                                $fields['shipping'][$key]['required'] = false;
                              }					 
				
			   
			    if (isset($value['clear']) && ($value['clear'] == 1)) 
				     { $fields['shipping'][$key]['clear'] = $value['clear']; } else {
                                $fields['shipping'][$key]['clear'] = false;
                             }	
					 
			   
			    if (isset($value['placeholder'])) { $fields['shipping'][$key]['placeholder'] = $value['placeholder']; }
				
			    if (isset($value['options'])) { 
				     
					 if (isset($value['options']) && ($value['options'] != '')) {
				       $fields['shipping'][$key]['options'] =$options;
					  }
					  
				}
				
				//adds extra classes to billing fields
				if (isset($extraclass) && ($extraclass != '')) {
                     
					foreach ($extraclass as $shippingclassval) {
						$fields['shipping'][$key]['class'][] = $shippingclassval;
					}
				
				}
				
				if (isset($value['validate'])) { 
				      
					  $fields['shipping'][$key]['validate'] =$value['validate'];
				
				}
				
				if (isset($value['disable_past'])) { 
				      $fields['shipping'][$key]['disable_past'] =$value['disable_past'];
				}
					
                
			    if (isset($value['hide']) && (($value['hide'] == 1))) {
				   unset($fields['shipping'][$key]);	  
					
			    }
				
				
				
			}
			
			
			/*
			  * adds extra custom field
			  * since version 1.0.1
			  */
			  
			 if (isset($shipping_fields[$key2]) && (!isset($fields['shipping'][$key2]))) {
			       
				    if (isset($shipping_fields[$key2])) {
				      $fields['shipping'][$key2] = $value;
				    }

				    if (isset($value['width']) && ($value['width'] != '')) {
				       $fields['shipping'][$key2]['class'][] =$value['width'];
					}
				   
				    //adds extra classes to billing fields
				    if (isset($extraclass) && ($extraclass != '')) {
                     
					   foreach ($extraclass as $shippingclassval2) {
						 $fields['shipping'][$key2]['class'][] = $shippingclassval2;
					   }
				
				    }
				   
				    if (isset($value['options']) && ($value['options'] != '')) {
				        $fields['shipping'][$key2]['options'] =$options;
					}
				   
			 }
			 
			 
		
			
			
		   }
		   }
		   
		  }
        }
	    
		
		
     if ( is_checkout() ) {
	  
	   if (isset($shipping_fields) && (sizeof($shipping_fields) >1)) {
	     $order = $this->get_order_array($shipping_fields);
		 
			foreach($order as $field)
             {
               $ordered_fields[$field] = $fields["shipping"][$field];
             }

        $fields["shipping"] = $ordered_fields;
           
	   } 
	   
	   
      }



    /**
     * Unset field key if hide is set
     */

      if (isset($shipping_fields) && ($shipping_fields != '')) {
		 foreach ($shipping_fields as $hidekey=>$hidevalue) {
             if (isset($hidevalue['hide']) && ($hidevalue['hide'] == 1)) {
             	unset($fields['shipping'][$hidekey]);
             }
			 
			  if (isset($hidevalue['visibility'])) {
				 $visibilityarray = $hidevalue['visibility'];
				 
				 if (isset($hidevalue['products'])) { 
				    $allowedproducts = $hidevalue['products'];
				 } else {
					$allowedproducts = array(); 
				 }
				 
				 if (isset($hidevalue['category'])) {
					 $allowedcats = $hidevalue['category'];
				 } else {
					 $allowedcats = array();
				 }
				 
				 $is_field_hidden=$this->pcfme_check_if_field_is_hidden($visibilityarray,$allowedproducts,$allowedcats);
				 
				 if (isset($is_field_hidden) && ($is_field_hidden != 1)) {
					unset($fields['shipping'][$hidekey]);
				 }
			 }
		 }
	  }

	  
      return $fields;
	}





	
	public function add_additional_fields() {
		 $additional_fields = (array) get_option( $this->additional_settings_key );
	     $additional_fields =  array_filter($additional_fields);
         

       if (isset($additional_fields) && (sizeof($additional_fields) >= 1)) { 
         
        foreach ($additional_fields as $additionalkey=>$additionalvalue) {
		  
          
             $extrafield= array();

            if (isset($additionalvalue['label'])) {
             $extrafield['label'] = $additionalvalue['label'];
            }

            if (isset($additionalvalue['type'])) {
             $extrafield['type'] = $additionalvalue['type'];
            }
          
            if (isset($additionalvalue['width'])) {
		     $extrafield['class'][] =$additionalvalue['width'];
		    }
            
			//hider/opener class
            if (isset($additionalvalue['visibility']) && ($additionalvalue['visibility'] == 'field-specific')) {
			  $showhide                 = $additionalvalue['conditional']['showhide'];
			  $parentfield              = $additionalvalue['conditional']['parentfield'];
			  $equalto                  = $additionalvalue['conditional']['equalto'];
			
 		      if (isset($equalto) && ($equalto != '')) {
				$pcfme_conditional_class  = '' . $showhide . '_by_' . $parentfield . '_' . $equalto .''; 
			  } else {
				$pcfme_conditional_class  = '' . $showhide . '_by_' . $parentfield . ''; 
			  }
			 
		    } else {
			    $pcfme_conditional_class  = '';
            }	
               
			  $extrafield['class'][] = $pcfme_conditional_class;		  

            if (isset($additionalvalue['required'])) {
              $extrafield['required'] = $additionalvalue['required'];
            }

            if (isset($additionalvalue['placeholder'])) {
              $extrafield['placeholder'] = $additionalvalue['placeholder'];
            }

            if (isset($additionalvalue['validate'])) {
             $extrafield['validate'] = $additionalvalue['validate'];
            }
           
            if (isset($additionalvalue['options'])) {
             $tempoptions = explode(',',$additionalvalue['options']);
		      
		      
		     $options = array();
                      
                      foreach($tempoptions as $val){
    
                         $options[$val]  = $val;
      
                      }
             $extrafield['options'] = $options;

            }
			
			
		    //builds extraclass array
		    if (isset($additionalvalue['extraclass']) && ($additionalvalue['extraclass'] != '')) {
		      $tempclasses = explode(',',$additionalvalue['extraclass']);
		      
		      
		      $extraclass = array();
                      
                      foreach($tempclasses as $classval3){
    
                         $extraclass[$classval3]  = $classval3;
      
                      }
			 
		    }
			
			
			//adds extra classes to billing fields
			if (isset($extraclass) && ($extraclass != '')) {
                     
					foreach ($extraclass as $additionalclassval) {
						$extrafield['class'][] = $additionalclassval;
					}
				
			}
		   
		    
		    if (isset($additionalvalue['disable_past'])) {
             
			    $extrafield['disable_past'] = $additionalvalue['disable_past'];
            }
			
			$field_post_meta = get_user_meta( get_current_user_id(), $additionalkey , true ); 
						  
		    if (isset($field_post_meta) && ($field_post_meta != '')) {
					$additional_field_value = $field_post_meta;
			} elseif (isset($additionalvalue['value'])) {
				    $additional_field_value = $additionalvalue['value'];
			} else {
				    $additional_field_value = '';
			}

        
		
			 
		    if (isset($additionalvalue['visibility'])) {
				 $visibilityarray = $additionalvalue['visibility'];
				 
				  if (isset($additionalvalue['products'])) { 
				    $allowedproducts = $additionalvalue['products'];
				 } else {
					$allowedproducts = array(); 
				 }
				 
				 if (isset($additionalvalue['category'])) {
					 $allowedcats = $additionalvalue['category'];
				 } else {
					 $allowedcats = array();
				 }
				 
				 $is_field_hidden=$this->pcfme_check_if_field_is_hidden($visibilityarray,$allowedproducts,$allowedcats);
				 
				 if ((!isset($is_field_hidden)) || ($is_field_hidden != 0)) {
					woocommerce_form_field( $additionalkey,  $extrafield ,! empty( $_POST[ $additionalkey ] ) ? wc_clean( $_POST[ $additionalkey ] ) : $additional_field_value);
				 }
			}
		
	

          
 
          
        }
     }
	
	}
	
	
	public function get_order_array($billing_fields) {
	  $order=array();
	  
	  foreach ($billing_fields as $key=>$value) {
	     array_push($order, $key);
	  }
	  return $order;
	}
	
	public function pcfme_check_if_field_is_hidden($hiddenvalue,$allowedproduts ,$allowedcats ) {
		global $woocommerce;
		$cart_items = $woocommerce->cart->get_cart();
		$extra_options = (array) get_option( 'pcfme_extra_settings' );
		
		switch($hiddenvalue) {
			case "product-specific" :
			 $allowedproductindex =0;
			 
			 if (( ! empty( $allowedproduts ) ) && (is_array($allowedproduts)))  {
		         foreach ($allowedproduts as $allowedproductkey=>$allowedproductid) {
					 
					foreach ($cart_items as $cartitem_key=>$cartitemvalue) {
						
					   if (isset($extra_options['include_variation'])) {
						   if (isset($cartitemvalue['variation_id']) &&  ($cartitemvalue['variation_id'] != 0)) {
						     $product_id=$cartitemvalue['variation_id'];
				            } else {
					         $product_id=$cartitemvalue['product_id'];
				            }
					   } else {
						     $product_id=$cartitemvalue['product_id'];
					   }
					   
				       
						
						  
				        if ($product_id == $allowedproductid) {
							$allowedproductindex++;
						}
			        }
				 }
			 }
			
			if ($allowedproductindex == 0)  {
				return 0;
			} else {
				return 1;
			}
			
			break;
			
			case "category-specific" :
			 $categoryproductindex = 0;
			 if (( ! empty( $allowedcats ) ) && (is_array($allowedcats)))  {
		         foreach ($allowedcats as $allowedcatvalue) {
					 
					 foreach ($cart_items as $cartitem_key=>$cartitemvalue) {
						
					    $product_id=$cartitemvalue['product_id'];
				     
						$catterms = get_the_terms( $product_id, 'product_cat' );
						
						if (( ! empty( $catterms ) ) && (is_array($catterms)))  {
							
							foreach ($catterms as $catterm) {
							if ($catterm->term_id == $allowedcatvalue) {
								$categoryproductindex++;
							}
						  }
						}
						
						
			         }
				 }
			 }
			 
			 if ($categoryproductindex == 0)  {
				return 0;
			 } else {
				return 1;
			 }
			break;
			
			case "always-visible" :
			 return 1;
			break;
			
			default:
			 return 1;
		}
	}
	
	
	
}

new pcfme_update_checkout_fields();
?>