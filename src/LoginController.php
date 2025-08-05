<?php

class LoginController
{
    public function __construct( private LoginGateway $gateway )
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

                // Get Headers
                $headers = getallheaders();
                $contentType = $headers[ 'Content-Type' ] ?? $headers[ 'content-type' ] ?? '';

                // JSON
                if ( str_contains( $contentType, 'application/json' )) {
                    $data = json_decode( file_get_contents( "php://input" ), true );

                    if ( json_last_error() !== JSON_ERROR_NONE ) {
                        http_response_code( 400 );
                        echo json_encode([
                            "status" => false,
                            "message" => "Invalid JSON format."
                        ]);
                        exit;
                    }
                } 
                
                // Form Data
                elseif ( str_contains( $contentType, 'multipart/form-data' )) {
                    $data = $_POST;
                } 
                
                // Others
                else {
                    http_response_code( 400 );
                    echo json_encode([
                        "status" => false,
                        "message" => "Unsupported Content-Type"
                    ]);
                    exit;
                }

                // Check Empty Field
                $errors = $this -> getValidationErrors( $data );

                if( !empty( $errors )) {
                    http_response_code( 422 );
                    echo json_encode([
                        "status" => false,
                        "message" => "Validation failed.",
                        "errors" => $errors
                    ]);
                    exit;
                }

                // Get Staff
                $passwordData = $this -> gateway -> getPassword( $data );

                if ( $passwordData ) {
                    if ( password_verify( $data[ "password" ], $passwordData[ "password" ])) {
                        $token = bin2hex( random_bytes( 32 ));

                        $staff = $this -> gateway -> get( $data );
                        $result = $this -> gateway -> token( $token, $staff[ "id" ]);

                        if ( $result ) {
                            http_response_code( 200 );
                            echo json_encode([
                                "status" => true,
                                "message" => "Login successful.",
                                "data" => [
                                    "token" => $token,
                                    "staff" => $staff
                                ]
                            ]);
                            exit;
                        } 
                        
                        else {
                            http_response_code( 500 );
                            echo json_encode([
                                "status" => false,
                                "message" => "Token update failed due to a server error."
                            ]);
                            exit;
                        }
                    } 
                    
                    else {
                        http_response_code( 401 );
                        echo json_encode([
                            "status" => false,
                            "message" => "Invalid email or password."
                        ]);
                        exit;
                    }
                } 
                
                else {
                    http_response_code( 401 );
                    echo json_encode([
                        "status" => false,
                        "message" => "Invalid email or password."
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
    
    private function getValidationErrors( array $data ): array
    {
        $errors = [];
        
        if ( empty( $data[ "email" ])) {
            $errors[] = "email is required";
        }


        if ( empty( $data[ "password" ])) {
            $errors[] = "password is required";
        }
        
        return $errors;
    }
}

?>