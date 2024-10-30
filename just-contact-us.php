<?php
/*
Plugin Name: Just Contact
Plugin URI: https://github.com/shuklaashish/simplecontact
Description: Simple way to add contact form on your wordpress site.Just add the shortcode as[just_contact_form] on the page to create contact form.
It is very easy to add contact forms on your site.No configuration is required.
Version: 1.2
Author: codecompiled
Author URI: http://www.codecompiled.com
License: GPL2
*/

define("DEFAULT_SUCESS_MESSSAGE", "Thanks for contacting us.Expect a reply soon!");
define("DEFAULT_FAILURE_MESSSAGE", "Unfortunately we are not able to accept your request.Please try after some time.");


include( plugin_dir_path( __FILE__ ) . '/JustContactOptions.php');
add_action( 'admin_menu', function()
{
	add_options_page( 
		'JustContact-ContactForm',
		'JustContact',
		'manage_options',
		'',
		'addJustContactFormMenu'
	);
});

   function addJustContactFormMenu()
{


DisplaySettingsPage();


}



$toemail='';
function just_contact_form_generateHTML()
{
	

           $showForm=just_contact_form_validate();
           if($showForm==false)
	{

	echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
	echo '<p>';
	echo 'Your Name (required) <br/>';
	echo '<input maxlength="50" type="text" name="cf-name" pattern="[a-zA-Z0-9 ]+" value="' . ( isset( $_POST["cf-name"] ) ? esc_attr( $_POST["cf-name"] ) : '' ) . '" size="60" />';
	echo '</p>';
	echo '<p>';
	echo 'Your Email (required) <br/>';
	echo '<input maxlength="50" type="email" name="cf-email" value="' . ( isset( $_POST["cf-email"] ) ? esc_attr( $_POST["cf-email"] ) : '' ) . '" size="60" />';
	echo '</p>';
	echo '<p>';
	echo 'Subject (required) <br/>';
	echo '<input maxlength="50" type="text" name="cf-subject" pattern="[a-zA-Z ]+" value="' . ( isset( $_POST["cf-subject"] ) ? esc_attr( $_POST["cf-subject"] ) : '' ) . '" size="60" />';
	echo '</p>';
	echo '<p>';
	echo 'Your Message (required) <br/>';
	echo '<textarea maxlength="200" rows="5" cols="20" name="cf-message">' . ( isset( $_POST["cf-message"] ) ? esc_attr( $_POST["cf-message"] ) : '' ) . '</textarea>';
	echo '</p>';
	echo '<p><input type="submit" name="cf-submitted" value="Send"></p>';
	echo '</form>';
}
	
	 global $wpdb;
		 
	
 require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	
}
function just_contact_form_cf_shortcode() {
	just_contact_form_generateHTML();
	
}

 function just_contact_form_getAdminMailId() {
	 return get_bloginfo('admin_email');
		
}

function just_contact_form_validate()
{
	echo 'ccc';
	$showForm=false;;
	 if ( isset( $_POST['cf-submitted'] ) ) {
         //validate input values 
				   
		 $name=  sanitize_text_field($_POST["cf-name"]);
		 $fromemail= sanitize_email($_POST["cf-email"]);
         $subject = sanitize_text_field( $_POST["cf-subject"] );
		 $message=sanitize_text_field( $_POST["cf-message"] );
		     /*check for empty values*/
             if($fromemail!=='' and  $subject!=='' and $message!==''){
				 if(is_email($fromemail) && is_string($name) && is_string($subject) && is_string($message))
				 {
                
                $settings=GetuserDetails();
               
		$toemail=$settings['sent-to-emailid'];//just_contact_form_getAdminMailId();
                  				 
		if ( wp_mail($toemail, $subject , $message) ) {
			echo '<div>';
			echo  $settings['sucess_message'];
			
			echo '</div>';
			$showForm=true;
		} else {
			
			echo $settings['failure_message'];
			
			$showForm=true;
		}
				 }
				 else
				 {
					 echo 'Please enter valid values';
				 }
             }
		else
              {
				  $GLOBALS['errorMandatoryFields']='Please enter Name,Email,Subject and Message';
                  echo 'Please enter Name,Email,Subject and Message';
               }
	 }
	 return $showForm;
}



add_shortcode( 'just_contact_form', 'just_contact_form_cf_shortcode' );
add_filter( 'wp_mail_from', function() {
    $from="wordpress@".$_SERVER['SERVER_NAME'];
      return $from;
  
} );


function CreateDatabase()
{

   global $wpdb;

   $table_name=$wpdb->prefix ."just_contact";

   if($wpdb->get_var('SHOW TABLES LIKE ' .$table_name)!=$table_name)
    {
      $sql = 'CREATE TABLE '.$table_name . '(
       ID INTEGER(20) AUTO_INCREMENT,
       PRIMARY KEY (ID),
       NAME VARCHAR(200),
       VAL VARCHAR(200),
        MNDT BIT DEFAULT 1)';
         
      
      error_log("This message is written to the log file");

      require_once(ABSPATH .'wp-admin/includes/upgrade.php');
      dbDelta($sql);

      
    }
   //DEFAULT VALUES
    $default_email_id=just_contact_form_getAdminMailId();

      
       $wpdb->insert($table_name, array('ID'=>1,'NAME'=>'sucess_message','VAL' =>DEFAULT_SUCESS_MESSSAGE,'MNDT'=>1 ));
       $wpdb->insert($table_name, array('ID'=>2,'NAME'=>'sent-to-emailid','VAL' =>$default_email_id,'MNDT'=>1 ));
       $wpdb->insert($table_name, array('ID'=>3,'NAME'=>'failure_message','VAL' =>DEFAULT_FAILURE_MESSSAGE,'MNDT'=>1 ));
}

register_activation_hook(__FILE__,'CreateDatabase');


function just_contact_form_form_deactivation() 
{
	// No action
}


register_deactivation_hook(__FILE__, 'just_contact_form_form_deactivation');

?>