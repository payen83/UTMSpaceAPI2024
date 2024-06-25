<?php

    // var_dump( $_SERVER[ "REQUEST_URI" ]);

    // Auto Loader
    // TODO: Change to Composer Loader
    declare( strict_types=1 );

    spl_autoload_register( function ( $class ) {
        require __DIR__ . "/src/$class.php";
    });

    // Exception Handler
    set_error_handler( "ErrorHandler::handleError" );
    set_exception_handler( "ErrorHandler::handleException" );

    // Change to JSON Type
    header( "Access-Control-Allow-Origin: *" );
    header( "Access-Control-Allow-Headers: *" );

    // header( "Access-Control-Allow-Origin: *" );
    // header( "Content-Type: application/json; charset=UTF-8" );
    // header( "Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE" );
    // header( "Access-Control-Max-Age: 3600" );
    // header( "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With" );

    $parts = explode( "/", $_SERVER[ "REQUEST_URI" ]);

    if ( $parts[ 1 ] != "api" ) {
        echo '<h1> 404 Not Found </h1>';
        http_response_code( 404 );
        exit;
    }

    $id = $parts[ 3 ] ?? null;

    // Database
    $database = new Database( "localhost", "ispaceDB", "root", "root" ); // TODO: Put Info in Config
    $news_gateway = new NewsGateway( $database );
    $staff_gateway = new StaffGateway( $database );
    $upload_gateway = new UploadGateway( $database );
    $login_gateway = new LoginGateway( $database );
    $logout_gateway = new LogoutGateway( $database );

    // Controller
    $news_controller = new NewsController( $news_gateway );
    $staff_controller = new StaffController( $staff_gateway );
    $upload_controller = new UploadController( $upload_gateway );
    $login_controller = new LoginController( $login_gateway );
    $logout_controller = new LogoutController( $logout_gateway );

    if ( $parts[ 2 ] === "news" ) {
        header( "Content-type: application/json; charset=UTF-8" );

        $news_controller -> processRequest( $_SERVER[ "REQUEST_METHOD" ], $id );
        exit;
    }

    else if ( $parts[ 2 ] === "staff" ) {
        header( "Content-type: application/json; charset=UTF-8" );

        $staff_controller -> processRequest( $_SERVER[ "REQUEST_METHOD" ], $id );
        exit;
    }

    else if ( $parts[ 2 ] === "upload" ) {
        header( "Content-type: application/json; charset=UTF-8" );

        $upload_controller -> processRequest( $_SERVER[ "REQUEST_METHOD" ], $id );
        exit;
    }

    else if ( $parts[ 2 ] === "login" ) {
        header( "Content-type: application/json; charset=UTF-8" );

        $login_controller -> processRequest( $_SERVER[ "REQUEST_METHOD" ], $id );
        exit;
    }

    else if ( $parts[ 2 ] === "logout" ) {
        header( "Content-type: application/json; charset=UTF-8" );

        $logout_controller -> processRequest( $_SERVER[ "REQUEST_METHOD" ], $id );
        exit;
    }
    
    else if ( $parts[ 2 ] === "images" && $id ) {

        // Set Header
        // echo $id; // Get Filename
        $mimetype = mime_content_type( "images/$id" );
        // echo $mimetype;

        $imageData = file_get_contents( "images/$id" );

        // Encode image data to base64
        $base64 = base64_encode( $imageData );

        echo '<img src="data:' . $mimetype . ';base64,' . $base64 . '">';
        exit;
    }

    else {
        echo '<h1> 404 Not Found </h1>';
        http_response_code( 404 );
        exit;
    }

?>