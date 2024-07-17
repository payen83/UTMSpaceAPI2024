<?php

class UploadController
{
    public function __construct( private UploadGateway $gateway )
    {
    }

    public function processRequest( string $method, ?string $id ): void
    {
        if ( $id ) {
            if($id == 'no_token'){
                $this -> processCollectionRequest( $method, $id );
            } else {
                $this -> processResourceRequest( $method, $id );
            }

        } else {
            $this -> processCollectionRequest( $method );
        }
    }

    private function processResourceRequest( string $method ): void
    {
        http_response_code( 404 );
        echo json_encode([ "message" => "Invalid url" ]);
        exit;
    }
    
    private function processCollectionRequest( string $method, ?string $istoken=null ): void
    {
        switch ( $method ) {
            case "GET":
                http_response_code( 405 );
                echo json_encode([ "message" => "Method not allowed!" ]);
                break;

            case "POST":

                // Check Authorization
                if($istoken){
                    $authorization = true;
                } else {
                    $authorization = $this -> checkAuthorization();   
                }
                // echo json_encode([ "Authorization" => $authorization ]);

                if( $authorization ) {
                    $image_path = "";
                    
                    if( $_FILES ) {
                        if( $_FILES[ 'upload' ][ 'tmp_name' ]) {
                            $image_path = $this -> checkImageFile( $_FILES );
                            // echo json_encode([ "Image Path: " => $image_path ]);

                            http_response_code( 201 );
                            echo json_encode([ "image_path" => $image_path ]);
                        }
                    }

                    else {
                        http_response_code( 500 );
                        echo json_encode([ "message" => "Image file not found!" ]);
                    }
                }

                else {
                    http_response_code( 501 );
                    echo json_encode([ "message" => "Unauthorized" ]);
                }
                break;
            
            default:
                http_response_code( 405 );
                header( "Allow: GET, POST" );
        }
    }

    private function checkAuthorization(): array {

        // Get All Headers
        $headers = getallheaders();
        // echo json_encode([ "Headers" => $headers ]);

        if ( !array_key_exists( 'Authorization', $headers )) {
            http_response_code( 501 );
            echo json_encode([ "message" => "Authorization header is missing" ]);
            exit;
        }

        else {
            $token = trim( substr( $headers[ 'Authorization' ], 6 ));
            // echo json_encode([ "token" => $token ]);

            $data = $this -> gateway -> getToken( $token );
            // echo json_encode([ "ID: " => $data ]);

            if( $data ) {
                return $data;
            }
        }

        return [];
    }

    private function checkImageFile( array $image ): string {
        $image_name = $image[ 'upload' ][ "name" ];
        $image_type = $image[ 'upload' ][ "type" ];
        $image_tmp = $image[ 'upload' ][ 'tmp_name' ];
        $image_error = $image[ 'upload' ][ "error" ];
        $image_size = $image[ 'upload' ][ "size" ];

        if (
            !isset( $image_error ) || is_array( $image_error )
        ) {
            http_response_code( 404 );
            echo json_encode([ "message" => "Invalid parameters!" ]);
            exit;
            // throw new RuntimeException( 'Invalid parameters!' );
        }

        switch ( $image_error ) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                // throw new RuntimeException( 'No file sent!' );
                break;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                http_response_code( 404 );
                echo json_encode([ "message" => "Exceeded filesize limit!" ]);
                exit;
                // throw new RuntimeException( 'Exceeded filesize limit!' );
            default:
                http_response_code( 404 );
                echo json_encode([ "message" => "Unknown errors!" ]);
                exit;
                // throw new RuntimeException( 'Unknown errors!' );
        }
    
        // Check Image Size
        if ( $image_size > 1000000000 ) {
            http_response_code( 404 );
            echo json_encode([ "message" => "Exceeded filesize limit!" ]);
            exit;
            // throw new RuntimeException( 'Exceeded filesize limit!' );
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
            http_response_code( 404 );
            echo json_encode([ "message" => "Invalid image type!" ]);
            exit;
            // throw new RuntimeException( 'Invalid image type!' );
        }

        return '';
    }
}

?>