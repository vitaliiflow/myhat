<?php 

global $product;

		$badges = get_field('badges');
		$new = false;
		$top = false;
		$last = false;
		$hide = false;
		$limited = false;
		
		$label_new = 'NEW';
		$label_top = 'Top seller';
		$label_last = 'Last Chance';
		$label_limited = 'Limited Edition';

		$locale = get_locale();

		if ($locale == 'sv_SE') :
			$label_new = 'NYHET';
			$label_top = 'Toppsäljare';
			$label_last = 'Sista Chansen';
			$label_limited = 'Limited Edition';
		endif;

		if(!empty($badges)):
			foreach($badges as $badge):
				switch($badge['value']):
					case 'hide':
						$hide= true;
						break;
					case 'limited':
						$limited= true;
						break;
					case 'top':
						$top = true;
						break;
					case 'new':
						$new = true;
						break;
					case 'last':
						$last = true;
						break;
				endswitch;
			endforeach;
		else: 
			$badges = false;
		endif;
		if(!$hide):
			//New Product Label
			$newness_days = 30; 
			$created = strtotime( $product->get_date_created() );
			if ( ( time() - ( 60 * 60 * 24 * $newness_days ) ) < $created  || $new) {
				echo '<span class="new-badge shopPage__listItem__badge">' . $label_new . '</span>';
			}

			//Last Chance Label
			$total_stock = 0;
			if($product->is_type('variable')){
				$product_variable = new WC_Product_Variable($product->get_id());
				$product_variations = $product_variable->get_available_variations();
				foreach ($product_variations as $variation)  {
					$total_stock += intval($variation['max_qty']);
				}
			} else {
				$total_stock = $product->get_stock_quantity();
			}
			if($total_stock <= 1 || $last){
				echo '<span class="last-chance shopPage__listItem__badge">' . $label_last . '</span>';
			}
			if(dw_product_totals() > 50 || $top){
				echo '<span class="top-seller shopPage__listItem__badge">' . $label_top . '</span>';
			}
			if($limited):
				echo '<span class="limited-edition shopPage__listItem__badge">' . $label_limited . '</span>';
			endif;
		endif;
		?>
