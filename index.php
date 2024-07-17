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

    header('Access-Control-Allow-Origin: *'); // Adjust this to match your actual domain, using '*' is less secure
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS'); // Adjust based on your needs
    header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept, Origin, Authorization'); // Add any other headers you need to support
    header('Access-Control-Allow-Credentials: true'); // If you're handling cookies/session
    
    // Handle preflight request
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        // Return only CORS headers for preflight
        if(isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) // May not be necessary depending on your server
            header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); // Adjust based on your needs
    
        if(isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    
        // No further action needed for preflight
        exit(0);
    }


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
    $feedback_gateway = new FeedbackGateway( $database );
    $staff_gateway = new StaffGateway( $database );
    $upload_gateway = new UploadGateway( $database );
    $login_gateway = new LoginGateway( $database );
    $logout_gateway = new LogoutGateway( $database );

    // Controller
    $news_controller = new NewsController( $news_gateway );
    $feedback_controller = new FeedbackController( $feedback_gateway );
    $staff_controller = new StaffController( $staff_gateway );
    $upload_controller = new UploadController( $upload_gateway );
    $login_controller = new LoginController( $login_gateway );
    $logout_controller = new LogoutController( $logout_gateway );

    if ( $parts[ 2 ] === "news" ) {
        header( "Content-type: application/json; charset=UTF-8" );

        $news_controller -> processRequest( $_SERVER[ "REQUEST_METHOD" ], $id );
        exit;
    }

    else if ( $parts[ 2 ] === "maklumbalas" ) {
        header( "Content-type: application/json; charset=UTF-8" );

        $feedback_controller -> processRequest( $_SERVER[ "REQUEST_METHOD" ], $id );
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