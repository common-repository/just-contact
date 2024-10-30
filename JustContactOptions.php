<?php 

 define( '\wp-content\plugins\just-contact\JustContactOptions.php', dirname(__FILE__).'/' );

function DisplaySettingsPage()
{

$path= plugins_url()."/just-contact/JustContactOptions.php?page=special-page";

GetuserDetails();

 global $wpdb;
$SUCCESS_MSG;
$fail_MSG;

   $table_name=$wpdb->prefix ."just_contact";
      $results = $wpdb->get_results( "SELECT VAL FROM $table_name WHERE NAME LIKE 'sucess_message'"); 

      if(!empty($results))                       
       {    
        foreach($results as $row){  
        $SUCCESS_MSG = $row->VAL;  
        
       }
       }


     $Fresults = $wpdb->get_results( "SELECT VAL FROM $table_name WHERE NAME LIKE 'failure_message'"); 

      if(!empty($Fresults))                       
       {    
        foreach($Fresults as $row){  
        $fail_MSG = $row->VAL;  
        
       }
       }



$settings=GetuserDetails();

?>
<div class=”wrap”>

<h2>Just Contact Form settings</h2>
<form method=”POST” id=”jc-form” action="<?php echo $path?>">
<table>
<tr>
    <td>
        <label for=email_id>email id</label>
    </td>
    <td>
        <input maxlength="80" size="80" type="text" id="email_id" value="<?php echo $settings['sent-to-emailid'] ?>" value="EMAIL ID" ">
    </td>
</tr>

<tr>
    <td>
        <label for=”Success_message”>Success message</label>
    </td>
    <td>
        <input maxlength="80" size="80" size="50" type=”text”  id="Success_message"  value="<?php echo $settings['sucess_message']?>"">
    </td>
</tr>


<tr>
    <td>
        <label for=”fail_message”>Failure message</label>
    </td>
    <td>
        <input maxlength="80" size="80" size="50" type="text" id="fail_message" value="<?php echo  $settings['failure_message']?>" ">
    </td>
</tr>

<tr>
    <td>
    </td>
    <td>
          <input type="submit" id="ajax-link" value="Update">
  
    </td>

    </tr>


</table>

</form>



<h3></h3>
<p></p>
</p>

</div>
<?php
fetchValues();
}

function fetchValues()
{
   global $wpdb;
  

   $table_name=$wpdb->prefix ."just_contact";
      $results = $wpdb->get_results( "SELECT VAL FROM $table_name WHERE NAME LIKE 'SUC'"); 

      if(!empty($results))                       
       {    
        foreach($results as $row){  
        $SUCCESS_MSG = $row->VAL;  
        
       }
       }


     $Fresults = $wpdb->get_results( "SELECT VAL FROM $table_name WHERE NAME LIKE 'fal'"); 

      if(!empty($Fresults))                       
       {    
        foreach($results as $row){  
        $fail_MSG = $row->VAL;  
       }
       }

   }



function test_ajax_load_scripts() {
    // load our jquery file that sends the $.post request
    wp_enqueue_script( "ajax-test", plugin_dir_url( __FILE__ ) . 'UpdateContactSettings.js', array( 'jquery' ) );
 
    // make the ajaxurl var available to the above script
    wp_localize_script( 'ajax-test', 'the_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );  
}
add_action('wp_print_scripts', 'test_ajax_load_scripts');


//function text_ajax_process_request() {
function ModifyContactDetails() {
    // first check if data is being sent and that it is the data we want
        $emailId=$_POST["emailId"];
        $sucess_msg=$_POST["s_msg"];
        $failure_msg=$_POST["f_msg"];
require_once(ABSPATH .'wp-admin/includes/upgrade.php');
        global $wpdb;

        $tablename=$wpdb->prefix.'just_contact';


        if(!empty($failure_msg))
         $wpdb->update($tablename, array('VAL' =>$failure_msg ), array('NAME' =>'failure_message'));

        if(!empty($sucess_msg))
         $wpdb->update($tablename, array('VAL' =>$sucess_msg ), array('NAME' =>'sucess_message'));

        if(!empty($emailId))
        $wpdb->update($tablename, array('VAL' =>$emailId ), array('NAME' =>'sent-to-emailid'));

        die();

}


function GetuserDetails()
{
   global $wpdb;
   $tablename=$wpdb->prefix.'just_contact';
  $rows = $wpdb->get_results( "SELECT * FROM ".$tablename." WHERE MNDT = 1");
  //return $rows;

$settings = array();
  foreach ( $rows as $row) 
{
    $name=$row->NAME;
     $val=$row->VAL;
     $settings[$name] = $val;

}


return $settings;

}
add_action('wp_ajax_test_response', 'ModifyContactDetails');


