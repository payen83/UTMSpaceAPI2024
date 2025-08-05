<?php

class UploadController
{
    public function __construct( private UploadGateway $gateway )
    {
    }

    public function processRequest( string $method, ?string $id ): void
    {
        if ( $id ) {
            $this -> processResourceRequest( $method, $id );

        } else {
            $this -> processCollectionRequest( $method );
            
        }
    }

    private function processResourceRequest( string $method, string $id ): void
    {
        header( "Content-Type: application/json" );
        http_response_code( 404 );
        echo json_encode([
            "status" => false,
            "message" => "Invalid URL or resource not found."
        ]);
        exit;
    }
    
    private function processCollectionRequest( string $method ): void
    {
        switch ( $method ) {
            case "POST":
                header( "Content-Type: application/json" );

                // Check Authorization
                $authorization = $this -> checkAuthorization();

                if( $authorization !== false ) {
                    $image_path = "";
                    
                    if ( !empty( $_FILES[ 'upload' ][ 'tmp_name' ])) {
                        $image_path = $this -> checkImageFile( $_FILES );

                        http_response_code( 201 );
                        echo json_encode([
                            "status" => true,
                            "message" => "Image uploaded successfully!",
                            "data" => [
                                "image_path" => $image_path
                            ]
                        ]);
                        exit;
                    }

                    else {
                        http_response_code( 404 );
                        echo json_encode([
                            "status" => false,
                            "message" => "Image file not found!"
                        ]);
                        exit;
                    }
                }

                else {
                    http_response_code( 401 );
                    echo json_encode([
                        "status" => false,
                        "message" => "Unauthorized!"
                    ]);
                    exit;
                }
                exit;
            
            default:
                header( "Content-Type: application/json" );
                header( "Allow: POST" );
                http_response_code( 405 );
                echo json_encode([
                    "status" => false,
                    "message" => "Method not allowed. Allowed methods: POST."
                ]);
                exit;
        }
    }

    private function checkAuthorization(): array | false {
        // Get All Headers
        $headers = getallheaders();
        $authorization = $headers[ 'authorization' ] ?? $headers[ 'Authorization' ];

        if ( !$authorization ) {
            header( "Content-Type: application/json" );
            http_response_code( 401 );
            echo json_encode([
                "status" => false,
                "message" => "Authorization header is missing!"
            ]);
            exit;
        }

        else {
            $token = trim( substr( $authorization, 6 ));
            $data = $this -> gateway -> checkToken( $token );

            if( $data ) {
                return $data;
            }
        }

        return false;
    }

    private function checkImageFile( array $image ): string {
        header( "Content-Type: application/json" );

        $image_name = $image[ 'upload' ][ "name" ];
        $image_type = $image[ 'upload' ][ "type" ];
        $image_tmp = $image[ 'upload' ][ 'tmp_name' ];
        $image_error = $image[ 'upload' ][ "error" ];
        $image_size = $image[ 'upload' ][ "size" ];

        if ( !isset( $image_error ) || is_array( $image_error ) ) {
            // throw new RuntimeException( 'Invalid parameters!' );
            http_response_code( 404 );
            echo json_encode([
                "status" => false,
                "message" => "Invalid parameters!"
            ]);
            exit;
        }

        switch ( $image_error ) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                // throw new RuntimeException( 'No file sent!' );
                break;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                // throw new RuntimeException( 'Exceeded filesize limit!' );
                http_response_code( 404 );
                echo json_encode([
                    "status" => false,
                    "message" => "Exceeded filesize limit!"
                ]);
                exit;
            default:
                // throw new RuntimeException( 'Unknown errors!' );
                http_response_code( 404 );
                echo json_encode([
                    "status" => false,
                    "message" => "Unknown errors!"
                ]);
                exit;
        }
    
        // Check Image Size
        if ( $image_size > 1000000000 ) {
            // throw new RuntimeException( 'Exceeded filesize limit!' );
            http_response_code( 404 );
            echo json_encode([
                "status" => false,
                "message" => "Exceeded filesize limit!"
            ]);
            exit;
        }
    
        // Check Image Type
        if ( $image_type === 'image/jpeg' || $image_type === 'image/gif' || $image_type === 'image/png' ) {

            // Upload Image
            if ( $image[ 'upload' ]) {
                // echo json_encode([ "Image: " => $image ]);

                if ( is_uploaded_file( $image_tmp )) {
                    
                    // Set Local TimeZone
                    date_default_timezone_set( "Asia/Kuala_Lumpur" );

                    // Convert Image Data into String
                    $imageData = file_get_contents( $image_tmp );

                    // Random Generate String
                    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $charactersLength = strlen( $characters );
                    $randomString = '';
                    for ( $i = 0; $i < 6; $i++ ) {
                        $randomString .= $characters[ random_int( 0, $charactersLength - 1 )];
                    }

                    // Create New Name Based on Current Time
                    $newName = idate( 'Y' ) . idate( 'm' ) . idate( 'd' ) . idate( 'H' ) . idate( 'i' ) . idate( 's' ) . '-' . $randomString;

                    // Save Image into Folder
                    $path_parts = pathinfo( $image_name );
                    $extension = $path_parts[ 'extension' ];
                    $file_path = '/images/' . $newName . '.' . $extension;
                    file_put_contents( '.' . $file_path, $imageData );

                    return $file_path;
                }
            }
        }

        else {
            // throw new RuntimeException( 'Invalid image type!' );
            http_response_code( 404 );
            echo json_encode([
                "status" => false,
                "message" => "Invalid image type!"
            ]);
            exit;
        }

        return '';
    }
}

?>