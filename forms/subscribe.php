<?php
    $useajax=false;/* set as true to use ajax once form & javascript are setup */
    $email=false;

    if( $_SERVER['REQUEST_METHOD']=='POST' ) {

        $to_email = "admin@google";
        $name = 'Rest';

        $messages=(object)array(
            'method'    =>  array( 'type' => 'error',   'text' => 'Sorry, Request must be Ajax POST' ),
            'success'   =>  array( 'type' => 'message', 'text' => 'Thank you. Your email was sent successfully.' ),
            'failed'    =>  array( 'type' => 'error',   'text' => 'Could not send mail! Please check your PHP mail configuration.' )
        );


        if( $useajax ) {
            if( !isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) != 'xmlhttprequest' ) {
                exit( json_encode( $messages->method ) );
            }
        }

        $email  = filter_var( filter_input( INPUT_POST, 'subscribe_email', FILTER_SANITIZE_EMAIL ), FILTER_VALIDATE_EMAIL );
        if( $email ){

            $headers = "From: {$name}
                        Reply-To: {$email}
                        X-Mailer: PHP/" . phpversion();

            $subject = 'New subscriber';
            $message = 'You have a new subscriber '.$email;
            $result = @mail( $to_email, $subject, $message , $headers ); 
        }

        $output=$result ? $messages->success : $messages->failed;

        exit( json_encode( $output ) );
    }
?>