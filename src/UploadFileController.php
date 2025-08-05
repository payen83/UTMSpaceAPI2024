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
                    $pdf_path = "";
                    
                    if ( !empty( $_FILES[ 'file' ][ 'tmp_name' ])) {
                            $pdf_path = $this -> checkPdfFile( $_FILES );

                            http_response_code( 201 );
                            echo json_encode([
                                "status" => true,
                                "message" => "Pdf file uploaded successfully!",
                                "data" => [
                                    "pdf_path" => $pdf_path
                                ]
                            ]);
                            exit;
                    }

                    else {
                        http_response_code( 404 );
                        echo json_encode([
                            "status" => false,
                            "message" => "Pdf file not found!"
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
                break;

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
        // echo json_encode([ "Headers" => $headers ]);

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

    private function checkPdfFile( array $file ): string {
        header( "Content-Type: application/json" );

        $file_name = $file[ 'file' ][ "name" ];
        $file_type = $file[ 'file' ][ "type" ];
        $file_tmp = $file[ 'file' ][ 'tmp_name' ];
        $file_error = $file[ 'file' ][ "error" ];
        $file_size = $file[ 'file' ][ "size" ];

        if ( !isset( $file_error ) || is_array( $file_error ) ) {
            // throw new RuntimeException( 'Invalid parameters!' );
            http_response_code( 404 );
            echo json_encode([
                "status" => false,
                "message" => "Invalid parameters!"
            ]);
            exit;
        }

        switch ( $file_error ) {
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
    
        // Check File Size
        if ( $file_size > 1000000000 ) {
            // throw new RuntimeException( 'Exceeded filesize limit!' );
            http_response_code( 404 );
            echo json_encode([
                "status" => false,
                "message" => "Exceeded filesize limit!"
            ]);
            exit;
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
            // throw new RuntimeException( 'Invalid File type!' );
            http_response_code( 404 );
            echo json_encode([
                "status" => false,
                "message" => "Invalid File type!"
            ]);
            exit;
        }

        return '';
    }
}

?>