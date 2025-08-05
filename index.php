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
    // header( "Access-Control-Allow-Origin: *" );
    // header( "Access-Control-Allow-Headers: *" );

    // header( "Access-Control-Allow-Origin: *" );
    // header( "Content-Type: application/json; charset=UTF-8" );
    // header( "Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE" );
    // header( "Access-Control-Max-Age: 3600" );
    // header( "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With" );
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

    // Only take the path part of the URI ( strip the query string )
    $uri = parse_url( $_SERVER[ "REQUEST_URI" ], PHP_URL_PATH );

    // Now explode the path by "/"
    $parts = explode( "/", $uri );
    // echo json_encode( [ "Parts" => $parts ]);

    // Check if the URL starts with /api
    if ( !isset( $parts[ 1 ]) || $parts[ 1 ] !== "api" ) {
        echo '<h1> 404 Not Found </h1>';
        http_response_code( 404 );
        exit;
    }

    $id = $parts[ 3 ] ?? null;
    $queryParams = $_GET;

    // Database
    $database = new Database( "localhost", "ispaceDB", "root", "" ); // TODO: Put Info in Config
    $news_gateway = new NewsGateway( $database );
    $staff_gateway = new StaffGateway( $database );
    $upload_gateway = new UploadGateway( $database );
    $upload_file_gateway = new UploadFileGateway( $database );
    $login_gateway = new LoginGateway( $database );
    $logout_gateway = new LogoutGateway( $database );

    // Controller
    $news_controller = new NewsController( $news_gateway );
    $staff_controller = new StaffController( $staff_gateway );
    $upload_controller = new UploadController( $upload_gateway );
    $upload_file_controller = new UploadFileController( $upload_file_gateway );
    $login_controller = new LoginController( $login_gateway );
    $logout_controller = new LogoutController( $logout_gateway );

    if ( $parts[ 2 ] === "news" ) {
        header( "Content-type: application/json; charset=UTF-8" );

        $news_controller -> processRequest( $_SERVER[ "REQUEST_METHOD" ], $id );
        exit;
    }

    else if ( isset( $parts[ 2 ]) && $parts[ 2 ] === "staff" ) {
        header( "Content-type: application/json; charset=UTF-8" );
    
        // Initialize $name
        $ic = null;
    
        // If there is a 'name' in the query string (e.g., /api/staff?name=test)
        if ( isset( $queryParams[ 'ic' ])) {
            $ic = $queryParams[ 'ic' ];
        }
    
        // Debugging to see the name parameter (remove this in production)
        // echo json_encode([ "IC" => $ic ]);
    
        // Call the staff_controller's processRequest method with the method, id, and name
        $staff_controller->processRequest( $_SERVER[ "REQUEST_METHOD" ], $id, $ic );
        exit;
    }

    else if ( $parts[ 2 ] === "upload" ) {
        header( "Content-type: application/json; charset=UTF-8" );

        $upload_controller -> processRequest( $_SERVER[ "REQUEST_METHOD" ], $id );
        exit;
    }

    else if ( $parts[ 2 ] === "files") {
        header( "Content-type: application/json; charset=UTF-8" );

        if( $parts[ 3 ] === "upload" ) {
            // echo json_encode([ "PARTS: " => $parts ]);
            header( "Content-type: application/json; charset=UTF-8" );
    
            $upload_file_controller -> processRequest( $_SERVER[ "REQUEST_METHOD" ]);
        }

        else {
            $filename = $parts[ 3 ] ?? null;

            $directory = realpath( __DIR__ . '/files' );

            // Check if the directory was correctly resolved
            if ( $directory === false ) {
                echo '<h1> Files directory not found </h1>';
                http_response_code( 500 ); // Internal Server Error
                exit;
            }

            // Construct the full file path
            $filepath = realpath( $directory . '/' . $filename );

            if ( !$filepath ) {
                echo '<h1> Filepath is invalid or file not found </h1>';
                echo 'Filename: ' . $filename . '<br>';
                echo 'Directory: ' . $directory . '<br>';
                echo 'Full Path: ' . $directory . '/' . $filename;
                exit;
            }

            // Check if the file exists and if it's within the "files" directory
            if ( !$filename || !file_exists( $filepath ) || strpos( $filepath, $directory ) !== 0 ) {
                echo '<h1> File Not Found </h1>';
                http_response_code( 404 );
                exit;
            }

            else {
                http_response_code( 200 );

                // Set headers for file download
                header( 'Content-Type: application/pdf' );
                header( 'Content-Disposition: attachment; filename="' . basename( $filepath ) . '"' );
                header( 'Content-Length: ' . filesize( $filepath ));
    
                // Serve the file
                readfile( $filepath );
                exit;
            }
        }
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
        $file = "images/$id";

        if (!file_exists($file)) {
            http_response_code( 404 );
            echo json_encode([
                "status" => false,
                "message" => "Image not found."
            ]);
            exit;
        }

        $mimetype = mime_content_type( $file );

        header( "Content-Type: $mimetype" );
        header( "Content-Length: " . filesize( $file ));
        readfile( $file );
        exit;
    }

    else if ( $parts[ 2 ] === "generate" ) {
        header( 'Content-Type: text/html; charset=UTF-8' );

        $filePath = __DIR__ . "/html/generatePdf.php";
        
        if ( file_exists( $filePath )) {
            include $filePath; // This will execute your PHP file
        } else {
            echo '<h1> File Not Found </h1>';
            http_response_code( 404 );
            exit;
        }
    }

    else if ( $parts[ 2 ] === "download" ) {
        header( 'Content-Type: text/html; charset=UTF-8' );
        
        // TEST
        $filePath = __DIR__ . "/html/generate.php";

        if ( file_exists( $filePath )) {
            include $filePath; // This will execute your PHP file
        } else {
            echo '<h1> File Not Found </h1>';
            http_response_code( 404 );
            exit;
        }
    }

    else {
        echo '<h1> 404 Not Found </h1>';
        http_response_code( 404 );
        exit;
    }

?>