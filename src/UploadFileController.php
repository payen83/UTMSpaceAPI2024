<?php

class UploadFileController
{
    public function __construct( private UploadFileGateway $gateway )
    {
    }

    public function processRequest( string $method ): void
    {
        $this -> processCollectionRequest( $method );
    }

    // private function processResourceRequest( string $method, string $id ): void
    // {
    //     http_response_code( 404 );
    //     echo json_encode([ "message" => "Invalid url" ]);
    //     exit;
    // }
    
    private function processCollectionRequest( string $method ): void
    {
        switch ( $method ) {
            case "GET":
                http_response_code( 405 );
                echo json_encode([ "message" => "Method not allowed!" ]);
                break;

            case "POST":

                // Check Authorization
                $authorization = $this -> checkAuthorization();
                // echo json_encode([ "Authorization" => $authorization ]);

                if( $authorization ) {
                    $pdf_path = "";
                    
                    if( $_FILES ) {
                        if( $_FILES[ 'file' ][ 'tmp_name' ]) {
                            $pdf_path = $this -> checkPdfFile( $_FILES );
                            // echo json_encode([ "Pdf Path: " => $pdf_path ]);

                            http_response_code( 201 );
                            echo json_encode([ "pdf_path" => $pdf_path ]);
                        }
                    }

                    else {
                        http_response_code( 404 );
                        echo json_encode([ "message" => "Pdf file not found!" ]);
                    }
                }

                else {
                    http_response_code( 401 );
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

        $authorization = $headers[ 'authorization' ] ?? $headers[ 'Authorization' ];

        if ( !$authorization ) {
            http_response_code( 501 );
            echo json_encode([ "message" => "Authorization header is missing" ]);
            exit;
        }

        else {
            $token = trim( substr( $authorization, 6 ));
            // echo json_encode([ "token" => $token ]);

            $data = $this -> gateway -> getToken( $token );
            // echo json_encode([ "ID: " => $data ]);

            if( $data ) {
                return $data;
            }
        }

        return [];
    }

    private function checkPdfFile( array $file ): string {
        $file_name = $file[ 'file' ][ "name" ];
        $file_type = $file[ 'file' ][ "type" ];
        $file_tmp = $file[ 'file' ][ 'tmp_name' ];
        $file_error = $file[ 'file' ][ "error" ];
        $file_size = $file[ 'file' ][ "size" ];

        if (
            !isset( $file_error ) || is_array( $file_error )
        ) {
            http_response_code( 404 );
            echo json_encode([ "message" => "Invalid parameters!" ]);
            exit;
            // throw new RuntimeException( 'Invalid parameters!' );
        }

        switch ( $file_error ) {
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
    
        // Check File Size
        if ( $file_size > 1000000000 ) {
            http_response_code( 404 );
            echo json_encode([ "message" => "Exceeded filesize limit!" ]);
            exit;
            // throw new RuntimeException( 'Exceeded filesize limit!' );
        }
    
        // Check File Type
        if ( $file_type === 'application/pdf' ) {

            // Upload File
            if ( $file[ 'file' ]) {
                // echo json_encode([ "Image: " => $image ]);

                if ( is_uploaded_file( $file_tmp )) {
                    
                    // Set Local TimeZone
                    date_default_timezone_set( "Asia/Kuala_Lumpur" );

                    // Convert Image Data into String
                    $fileData = file_get_contents( $file_tmp );

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
                    $path_parts = pathinfo( $file_name );
                    $extension = $path_parts[ 'extension' ];
                    $file_path = '/files/' . $newName . '.' . $extension;
                    file_put_contents( '.' . $file_path, $fileData );

                    return $file_path;
                }
            }
        }

        else {
            http_response_code( 404 );
            echo json_encode([ "message" => "Invalid File type!" ]);
            exit;
            // throw new RuntimeException( 'Invalid image type!' );
        }

        return '';
    }
}

?>