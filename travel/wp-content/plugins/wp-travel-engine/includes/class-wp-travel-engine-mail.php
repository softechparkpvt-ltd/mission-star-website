<?php
/**
 * Email Functionality
 *
 * Maintain a list of tags and functions that are used in email templates.
 *
 * @package    Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes
 * @author    
 */
class Wp_Travel_Engine_Mail_Template
{
	function wpte_get_client_ip()
	{
	 	$ipaddress = '';
	    if (getenv('HTTP_CLIENT_IP'))
	        $ipaddress = getenv('HTTP_CLIENT_IP');
	    else if(getenv('HTTP_X_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	    else if(getenv('HTTP_X_FORWARDED'))
	        $ipaddress = getenv('HTTP_X_FORWARDED');
	    else if(getenv('HTTP_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_FORWARDED_FOR');
	    else if(getenv('HTTP_FORWARDED'))
	        $ipaddress = getenv('HTTP_FORWARDED');
	    else if(getenv('REMOTE_ADDR'))
	        $ipaddress = getenv('REMOTE_ADDR');
	    else
	        $ipaddress = 'UNKNOWN';
	 
	    return $ipaddress;
	}
	
	function mail_editor( $settings, $pid )
	{
		$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings' );
		$booking_url = get_edit_post_link( $pid );
		$booking_url = '#<a href="'.esc_url($booking_url).'">'.$pid.'</a>';
		$subject_receipt = __( 'Booking Confirmation', 'wp-travel-engine' );
		if( isset( $wp_travel_engine_settings['email']['subject'] ) && $wp_travel_engine_settings['email']['subject']!='' )
		{
			$subject_receipt = $wp_travel_engine_settings['email']['subject'];
		}
		
		$from_name = get_bloginfo("name");
		if( isset( $wp_travel_engine_settings['email']['name'] ) && $wp_travel_engine_settings['email']['name']!='' )
		{
			$from_name = $wp_travel_engine_settings['email']['name'];
		}

		$from_email = get_option("admin_email");
		if( isset( $wp_travel_engine_settings['email']['from'] ) && $wp_travel_engine_settings['email']['from']!='' )
		{
			$from_email = $wp_travel_engine_settings['email']['from'];
		}

		$from_receipt =$from_name.'*<'.$from_email.'>';
		// $from_receipt = trim($from_receipt);
		  // To send HTML mail, the Content-type header must be set
		$headers_receipt  = 'MIME-Version: 1.0' . "\r\n";
		$charset = apply_filters('wp_travel_engine_mail_charset', 'Content-type: text/html; charset=UTF-8');
		$headers_receipt .= $charset . "\r\n";
		  // Create email headers
		$headers_receipt .='From:'.$from_receipt."\r\n".
		    'Reply-To: '.$from_receipt."\r\n" .
		    'X-Mailer: PHP/' . phpversion();
		$sitename = get_bloginfo( 'name' );
		$purchase_open = '<html><body style="font-family: Arial, Helvetica, sans-serif; font-size: 1rem; line-height: 1.35em;"><div style="line-height: 2em; margin: 0 auto; background: #fafafa; padding: 50px;">';
		$post = get_post( $_SESSION['trip-id'] ); 
		$slug = $post->post_title;
		$trip = '<a href='.esc_url( get_permalink( $_SESSION['trip-id'] ) ).'>'.$slug.'</a>';
		$tdate = $_SESSION['trip-date'];
		$traveler = $_SESSION['travelers'];
        
		$code = 'USD';
        if( isset( $wp_travel_engine_settings['currency_code'] ) && $wp_travel_engine_settings['currency_code']!= '' )
        {
            $code = $wp_travel_engine_settings['currency_code'];
        } 
        $obj = new Wp_Travel_Engine_Functions();
        $currency = $obj->wp_travel_engine_currencies_symbol( $code );		

		$price = $currency.$_SESSION['trip-cost'].' '.$code; 
		$due = isset( $settings['place_order']['due'] ) ? $currency.esc_attr( $settings['place_order']['due'].' '.$code ) : '' ;
		$total_cost = isset( $settings['place_order']['due'] ) && $settings['place_order']['due'] !='' ? intval( $_SESSION['trip-cost'] ) + intval( $settings['place_order']['due'] ) : $_SESSION['trip-cost'] ;
		$total_cost = $currency.$total_cost.' '.$code;

		$fullname = $settings['place_order']['booking']['fname'].' '.$settings['place_order']['booking']['lname'];
		$trip_settings = get_post_meta( $_SESSION['trip-id'],'wp_travel_engine_setting',true );
		$singletripprice = str_replace( ',', '', $_SESSION['trip-cost'] );
        $cost = isset( $trip_settings['trip_price'] ) ? $trip_settings['trip_price']: '';
		if( $cost!='' && isset($trip_settings['sale']) )
        {
			$tripprice = $cost;
		}
		else{
			if( isset( $trip_settings['trip_prev_price'] ) && $trip_settings['trip_prev_price']!='' ) 
			{
				$tripprice = $trip_settings['trip_prev_price'];
			}
		}

		$tprice = $currency.number_format($tripprice).' '.$code;

		if( isset( $wp_travel_engine_settings['email']['purchase_wpeditor'] ) && $wp_travel_engine_settings['email']['purchase_wpeditor']!='' )
		{
			$wp_travel_engine_settings['email']['purchase_wpeditor'] = wpautop( html_entity_decode( $wp_travel_engine_settings['email']['purchase_wpeditor'],3,'UTF-8' ) );
			$purchase_receipt = apply_filters( 'meta_content', $wp_travel_engine_settings['email']['purchase_wpeditor'] );	
			$purchase_receipt = str_replace( '{name}', $settings['place_order']['booking']['fname'], $purchase_receipt );
			$purchase_receipt = str_replace( '{fullname}', $fullname, $purchase_receipt );
			$purchase_receipt = str_replace( '{user_email}', $settings['place_order']['booking']['email'], $purchase_receipt );
			$purchase_receipt = str_replace( '{billing_address}', $settings['place_order']['booking']['address'], $purchase_receipt );
			$purchase_receipt = str_replace( '{sitename}', $sitename, $purchase_receipt );
			$purchase_receipt = str_replace( '{price}', $price, $purchase_receipt );
			$purchase_receipt = str_replace( '{tprice}', $tprice, $purchase_receipt );
			$purchase_receipt = str_replace( '{trip_url}', '#'.$trip, $purchase_receipt );
			$purchase_receipt = str_replace( '{tdate}', $_SESSION['trip-date'], $purchase_receipt );
			$purchase_receipt = str_replace( '{traveler}', $traveler, $purchase_receipt );
			$purchase_receipt = str_replace( '{child-traveler}', isset( $_SESSION['child-travelers'] ) ? esc_attr( $_SESSION['child-travelers'] ) : '' , $purchase_receipt );
			$purchase_receipt = str_replace( '{booking_url}', $booking_url, $purchase_receipt );

			$purchase_receipt = str_replace( '{total_cost}', $total_cost, $purchase_receipt );

			$purchase_receipt = str_replace( '{due}', $due, $purchase_receipt );

			$ip = $this->wpte_get_client_ip();
			
			$purchase_receipt = str_replace( '{ip_address}', $ip, $purchase_receipt );
		}
		else{

			$purchase_receipt  = '<p>'.__( 'Dear {name},', 'wp-travel-engine' ) . "<br />" ;
			$purchase_receipt .= '</p>'.'<p>'.__( 'You have successfully made the trip booking. Your booking information is below.', 'wp-travel-engine' ).'</p>';
			$purchase_receipt .= '' . "<br />";
			$purchase_receipt .= __( 'Trip Name: {trip_url}','wp-travel-engine' ). "<br />";
			$purchase_receipt .= __( 'Trip Cost: {tprice}','wp-travel-engine' ). "<br />";
			$purchase_receipt .= __( 'Trip Start Date : {tdate}','wp-travel-engine' ). "<br />";
			$purchase_receipt .= __( 'Total Number of Traveler(s): {traveler}','wp-travel-engine' ). "<br />";
			$purchase_receipt .= __( 'Total Number of Child Traveler(s): {child-traveler}','wp-travel-engine' ). "<br />";
			$purchase_receipt .= __( 'Booking Url: {booking_url}','wp-travel-engine' ). "<br />";
			$purchase_receipt .= __( 'Total Cost: {price}','wp-travel-engine'). "<br />";
			$purchase_receipt .= __( 'Thank you.','wp-travel-engine'). "<br />";
			$purchase_receipt .= __( 'Best regards,','wp-travel-engine'). "<br />";
			$purchase_receipt .= get_bloginfo('name').'<br />';


			$purchase_receipt = str_replace( '{name}', $settings['place_order']['booking']['fname'], $purchase_receipt );
			$purchase_receipt = str_replace( '{fullname}', $fullname, $purchase_receipt );
			$purchase_receipt = str_replace( '{user_email}', $settings['place_order']['booking']['email'], $purchase_receipt );
			$purchase_receipt = str_replace( '{billing_address}', $settings['place_order']['booking']['address'], $purchase_receipt );
			$purchase_receipt = str_replace( '{date}', date('Y-m-d H:i:s'), $purchase_receipt );
			$purchase_receipt = str_replace( '{sitename}', $sitename, $purchase_receipt );
			$purchase_receipt = str_replace( '{price}', $price, $purchase_receipt );
			$purchase_receipt = str_replace( '{tprice}', $tprice, $purchase_receipt );
			$purchase_receipt = str_replace( '{trip_url}', '#'.$trip. "<br />", $purchase_receipt );
			$purchase_receipt = str_replace( '{tdate}', $_SESSION['trip-date'], $purchase_receipt );
			$purchase_receipt = str_replace( '{traveler}', $traveler, $purchase_receipt );
			$purchase_receipt = str_replace( '{child-traveler}', isset( $_SESSION['child-travelers'] ) ? esc_attr( $_SESSION['child-travelers'] ) : '', $purchase_receipt );
			$purchase_receipt = str_replace( '{booking_url}', $booking_url. "<br />", $purchase_receipt );

			$ip = $this->wpte_get_client_ip();
			
			
			$purchase_receipt = str_replace( '{ip_address}', $ip, $purchase_receipt );
		}

		$purchase_close = '</div></body></html>';
		$purchase_receipt = $purchase_open.$purchase_receipt.$purchase_close;
		$purchase_receipt = wpautop( html_entity_decode( $purchase_receipt,3,'UTF-8' ) );
		// die;
		wp_mail( $settings['place_order']['booking']['email'], $subject_receipt, $purchase_receipt, $headers_receipt );
		

		//Mail for Admin
		if( isset( $wp_travel_engine_settings['email']['sale_subject'] ) && $wp_travel_engine_settings['email']['sale_subject']!='' )
		{
			$subject_book = esc_attr( $wp_travel_engine_settings['email']['sale_subject'] );
		}
		$subject_book = 'New Booking Order #'.$pid;
		$from_book = $from_name.'*<'.$from_email.'>';
		// $from_book = trim($from_book);
		  // To send HTML mail, the Content-type header must be set
		$headers_book  = 'MIME-Version: 1.0' . "\r\n";
		$charset = apply_filters('wp_travel_engine_mail_charset', 'Content-type: text/html; charset=UTF-8');
		$headers_book .= $charset . "\r\n";
		  // Create email headers
		$headers_book .= 'From: '.$from_book."\r\n".
		    'Reply-To: '.$from_receipt."\r\n" .
		    'X-Mailer: PHP/' . phpversion();


		$book_open = '<html><body style="font-family: Arial, Helvetica, sans-serif; font-size: 1rem; line-height: 1.35em;"><div style="line-height: 2em; margin: 0 auto; background: #fafafa; padding: 50px;">';
		$post = get_post( $_SESSION['trip-id'] ); 
		$slug = $post->post_title;
		$trip = '<a href='.esc_url( get_permalink( $_SESSION['trip-id'] ) ).'>'.$slug.'</a>';
		
		if( isset( $wp_travel_engine_settings['email']['sales_wpeditor'] ) && $wp_travel_engine_settings['email']['sales_wpeditor']!='' )
		{
			$wp_travel_engine_settings['email']['sales_wpeditor'] = wpautop( html_entity_decode( $wp_travel_engine_settings['email']['sales_wpeditor'],3,'UTF-8' ) );
			$book_receipt = apply_filters( 'meta_content', $wp_travel_engine_settings['email']['sales_wpeditor'] );
			$book_receipt = str_replace( '{name}', $settings['place_order']['booking']['fname'], $book_receipt );
			$book_receipt = str_replace( '{fullname}', $fullname, $book_receipt );
			$book_receipt = str_replace( '{user_email}', $settings['place_order']['booking']['email'], $book_receipt );
			$book_receipt = str_replace( '{billing_address}', $settings['place_order']['booking']['address'], $book_receipt );
			$book_receipt = str_replace( '{date}', date('Y-m-d H:i:s'), $book_receipt );
			$book_receipt = str_replace( '{sitename}', $sitename, $book_receipt );
			$book_receipt = str_replace( '{price}', $price, $book_receipt );
			$book_receipt = str_replace( '{tprice}', $tprice, $book_receipt );
			$book_receipt = str_replace( '{trip_url}', '#'.$trip, $book_receipt );
			$book_receipt = str_replace( '{tdate}', $_SESSION['trip-date'], $book_receipt );
			$book_receipt = str_replace( '{traveler}', $traveler, $book_receipt );
			$book_receipt = str_replace( '{booking_url}', $booking_url, $book_receipt );
			$book_receipt = str_replace( '{child-traveler}', isset( $_SESSION['child-travelers'] ) ? esc_attr( $_SESSION['child-travelers'] ) : '', $book_receipt );
			$book_receipt = str_replace( '{total_cost}', $total_cost, $book_receipt );
			
			$book_receipt = str_replace( '{due}', $due, $book_receipt );
			$ip = $this->wpte_get_client_ip();
			$book_receipt = str_replace( '{ip_address}', $ip, $book_receipt );
		}
		else{
			
			$book_receipt  = '<p>'.__( 'Dear Admin,', 'wp-travel-engine' ).'</p>'. "<br />";
			$book_receipt .= __( 'The following booking has been successfully made.','wp-travel-engine'). "<br />";
			$book_receipt .= "<br />".__( 'Trip Name : {trip_url}','wp-travel-engine' ).  "<br />"; 
			$book_receipt .= __( 'Trip Cost:  {tprice}','wp-travel-engine' ).  "<br />";
			$book_receipt .= __( 'Trip Start Date : {tdate}','wp-travel-engine' ). "<br />";
			$book_receipt .= __( 'Total Number of Traveler(s): {traveler}','wp-travel-engine' ). "<br />";
			$book_receipt .= __( 'Total Number of Child Traveler(s): {child-traveler}','wp-travel-engine' ). "<br />";
			$book_receipt .= __( 'Trip Booking URL: {booking_url}','wp-travel-engine' ). "<br />";
			$book_receipt .= __( 'Total Cost: {price}','wp-travel-engine'). "<br />";
			$book_receipt .= __( 'Thank you.','wp-travel-engine'). "<br />";
			$book_receipt .= __( 'Best regards,','wp-travel-engine'). "<br />";
			$book_receipt .= get_bloginfo('name').'<br />';

			$book_receipt = str_replace( '{name}', $settings['place_order']['booking']['fname'], $book_receipt );
			$book_receipt = str_replace( '{fullname}', $fullname, $book_receipt );
			$book_receipt = str_replace( '{user_email}', $settings['place_order']['booking']['email'], $book_receipt );
			$book_receipt = str_replace( '{billing_address}', $settings['place_order']['booking']['address'], $book_receipt );
			$book_receipt = str_replace( '{date}', date('Y-m-d H:i:s'), $book_receipt );
			$book_receipt = str_replace( '{sitename}', $sitename, $book_receipt );
			$book_receipt = str_replace( '{price}', $price, $book_receipt );
			$book_receipt = str_replace( '{tprice}', $tprice, $book_receipt );
			$book_receipt = str_replace( '{trip_url}', '#'.$trip, $book_receipt );
			$book_receipt = str_replace( '{tdate}', $_SESSION['trip-date'], $book_receipt );
			$book_receipt = str_replace( '{traveler}', $traveler, $book_receipt );
			$book_receipt = str_replace( '{booking_url}', $booking_url, $book_receipt );
			$book_receipt = str_replace( '{child-traveler}', isset( $_SESSION['child-travelers'] ) ? esc_attr( $_SESSION['child-travelers'] ) : '', $book_receipt );
		}	

		$book_close = '</div></body></html>';

		if( !isset ( $wp_travel_engine_settings['email']['disable_notif'] ) || $wp_travel_engine_settings['email']['disable_notif'] != '1' )
		{	
			if ( strpos( $wp_travel_engine_settings['email']['emails'], ',') !== false ) {
				$wp_travel_engine_settings['email']['emails'] = str_replace(' ', '', $wp_travel_engine_settings['email']['emails']);
				$admin_emails = explode( ',', $wp_travel_engine_settings['email']['emails'] );
				$book_receipt = $book_open.$book_receipt.$book_close;
				$book_receipt = wpautop( html_entity_decode( $book_receipt,3,'UTF-8' ) );
				foreach ( $admin_emails as $key => $value ) {
					wp_mail( $value, $subject_book, $book_receipt, $headers_book );
				}
			}
			else{
				$wp_travel_engine_settings['email']['emails'] = str_replace(' ', '', $wp_travel_engine_settings['email']['emails']);
				$book_receipt = $book_open.$book_receipt.$book_close;
				$book_receipt = wpautop( html_entity_decode( $book_receipt,3,'UTF-8' ) );
				wp_mail( $wp_travel_engine_settings['email']['emails'], $subject_book, $book_receipt, $headers_book );
			}
		}
	}
}