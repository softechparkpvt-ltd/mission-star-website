<div class="wpte-row">
	<h3 class="title">Trip Tabs Settings</h3>
	<div class="trip-tab-info-title-wrapper">
		<div class="trip-tab-info-title">
			<h4>Tab Name <span class="required">*</span> <span class="tooltip" title="Tab Name is the label that appears on each of the tabs"><i class="fas fa-question-circle"></i></span></h4>
			<h4>Tab Icon <span class="required">*</span> <span class="tooltip" title="Choose icon for the tab. Leave blank if no icon is required."><i class="fas fa-question-circle"></i></span></h4>
		</div>
	</div>
	<div class="tabs-inner">
		<ul class="tabs-accordion">
			<?php
			$wp_travel_engine_tabs = get_option( 'wp_travel_engine_settings' );
			if( isset( $wp_travel_engine_tabs['trip_tabs'] ) && !empty( $wp_travel_engine_tabs['trip_tabs'] ) )
			{
				$maxlen = max( array_keys( $wp_travel_engine_tabs['trip_tabs']['name'] ) );
				$arr_keys  = array_keys( $wp_travel_engine_tabs['trip_tabs']['name'] );
				foreach ($arr_keys as $key => $value)
				{ 
					$wp_travel_engine_tabs = get_option( 'wp_travel_engine_settings' ); 
					if ( array_key_exists( $value,$wp_travel_engine_tabs['trip_tabs']['name'] ) )
				  	{
					?>
						<li id="trip-tabs<?php echo esc_attr( $value );?>" data-id="<?php echo esc_attr( $value );?>" class="trip-row">
						<span class="tabs-handle">
							<span></span>
						</span>
						<?php if($wp_travel_engine_tabs['trip_tabs']['id'][$value]!='1' && $wp_travel_engine_tabs['trip_tabs']['id'][$value]!='2' && $wp_travel_engine_tabs['trip_tabs']['id'][$value]!='3' && $wp_travel_engine_tabs['trip_tabs']['id'][$value]!='4')
						{ ?>
						 <span class="delete-icon delete-tab"><i class="far fa-trash-alt delete-icon" data-id="<?php echo $value;?>"></i></span>   
						<?php 
						}
						?> 
		              		
		              		<div class="tabs-content">
								<div class="tabs-id">
									<input type="hidden" class="trip-tabs-id" name="wp_travel_engine_settings[trip_tabs][id][<?php  echo $value;?>]" id="wp_travel_engine_settings[trip_tabs][id][<?php echo $value;?>]" 
									value="<?php echo ( isset($wp_travel_engine_tabs['trip_tabs']['id'][$value] ) ? esc_attr( $wp_travel_engine_tabs['trip_tabs']['id'][$value] ):'' ); ?>">
								</div>
								
								<div class="tabs-field">
									<input type="hidden" class="trip-tabs-id" name="wp_travel_engine_settings[trip_tabs][field][<?php  echo $value;?>]" id="wp_travel_engine_settings[trip_tabs][field][<?php echo $value;?>]" 
									value="<?php echo ( isset($wp_travel_engine_tabs['trip_tabs']['field'][$value] ) ? esc_attr( $wp_travel_engine_tabs['trip_tabs']['field'][$value] ):'' ); ?>">
								</div>

								<div class="tabs-name">
									<input type="text" class="trip-tabs-name" name="wp_travel_engine_settings[trip_tabs][name][<?php echo $value;?>]" id="wp_travel_engine_settings[trip_tabs][name][<?php echo $value;?>]" 
									value="<?php echo ( isset($wp_travel_engine_tabs['trip_tabs']['name'][$value] ) ? esc_attr( $wp_travel_engine_tabs['trip_tabs']['name'][$value] ):'' ); ?>" required>
									
								</div>
								<div class="tabs-icon">
									<input type="text" class="trip-tabs-icon" name="wp_travel_engine_settings[trip_tabs][icon][<?php echo $value;?>]" id="wp_travel_engine_settings[trip_tabs][icon][<?php echo $value;?>]" 
									value="<?php echo ( isset($wp_travel_engine_tabs['trip_tabs']['icon'][$value] ) ? esc_attr( $wp_travel_engine_tabs['trip_tabs']['icon'][$value] ):'' ); ?>">
									
								</div>
				 			</div>	
						</li>
			<?php 	} 
				}
			} 
			?>
			<span id="writetrip"></span>
		</ul>
	</div>
</div>
<div id="add_remove_tabs">
	<?php
	$other_attributes = array( 'id' => 'add_remove_tab' );
	submit_button( 'Add Tab', '', '', true, $other_attributes ); ?>
</div>