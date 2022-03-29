<?php

class Redvolver_Notification {

  /*
  //$user_info = get_userdata( $user_id );
  $current_user = wp_get_current_user();

  $email_message = str_replace( '[userdisplayname]', $current_user->display_name, $email_message );
  $email_message = str_replace( '[siteurl]', get_bloginfo('url'), $email_message );

  ob_start();
  $this->template_loader->get_template_part( 'email/main' ,'header',true);
  echo $email_message;
  $this->template_loader->get_template_part( 'email/main' ,'footer',true );
  // get_template_part('template/email/main','header');
  // get_template_part('template/email/main','body');
  // get_template_part('template/email/main','footer');
  $body    = ob_get_clean();
  $headers 		 = array(
  'Content-Type: text/html; charset=UTF-8',
  //'Bcc: ' . $emails
  );

  // if( have_rows('send_quote_to','option') ) {
  // while ( have_rows('send_quote_to','option') ) : the_row();
  // 	$to_email = get_sub_field('email');
  // 	wp_mail( $to_email, 'Quote Received', $body, $headers );
  // 	endwhile;
  // 	}else{
  wp_mail( 'filippo@redvolver.it', $email_subject, $body, $headers );
// }
*/
  public function send_to_all( $email_subject,$email_message ) {
    $template_loader = new Redvolver_Template_Loader;

    $users = get_users( array(
      'orderby' => 'login',
      'order' => 'ASC',
      'exclude' => array( 1 )
    ));
    if ( $users) {
      foreach ( $users as $user ) {
        $email_message_def = $email_message;
        $email_message_def = str_replace( '[userdisplayname]', $user->display_name, $email_message_def );
        $email_message_def = str_replace( '[siteurl]', get_bloginfo('url'), $email_message_def );

        ob_start();
        $template_loader->get_template_part( 'email/main' ,'header',true);
        echo $email_message_def;
        $template_loader->get_template_part( 'email/main' ,'footer',true );

        $body    = ob_get_clean();
        $headers 		 = array(
        'Content-Type: text/html; charset=UTF-8',
        //'Bcc: ' . $emails
        );

        wp_mail( 'filippo@redvolver.it', $email_subject, $body, $headers );
      }
    }

  }


  public function send_to_user($to,$email_subject,$email_message) {

    $template_loader = new Redvolver_Template_Loader;

    if ( $to) {
      foreach ( $to as $user ) {
        $email_message_def = $email_message;
        $email_message_def = str_replace( '[userdisplayname]', $user->display_name, $email_message_def );
        $email_message_def = str_replace( '[siteurl]', get_bloginfo('url'), $email_message_def );

        ob_start();
        $template_loader->get_template_part( 'email/main' ,'header',true);
        echo $email_message_def;
        $template_loader->get_template_part( 'email/main' ,'footer',true );

        $body    = ob_get_clean();
        $headers 		 = array(
        'Content-Type: text/html; charset=UTF-8',
        //'Bcc: ' . $emails
        );

        wp_mail( 'filippo@redvolver.it', $email_subject, $body, $headers );
      }
    }
  }

  public function parse_message($message = false) {
    if ( !$message) return false;

  }

}
