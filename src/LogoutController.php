<?php

class LogoutController
{
    public function __construct( private LogoutGateway $gateway )
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
        http_response_code( 404 );
        echo json_encode([ "message" => "Invalid url" ]);
    }
    
    private function processCollectionRequest( string $method ): void
    {
        switch ( $method ) {
            case "GET":

                // Get All Headers
                $headers = getallheaders();
                // echo json_encode([ "Headers" => $headers ]);

                if ( !array_key_exists( 'Authorization', $headers )) {
                    http_response_code( 404 );
                    echo json_encode([ "message" => "Authorization header is missing" ]);
                    exit;
                }

                else {
                    if ( substr( $headers[ 'Authorization' ], 0, 7) !== 'Bearer ' ) {

                        http_response_code( 404 );
                        echo json_encode([ "message" => "Bearer keyword is missing" ]);
                        exit;
                    }

                    else {
                        $token = trim( substr( $headers[ 'Authorization' ], 6 ));
                        // echo json_encode([ "token" => $token ]);

                        $data = $this -> gateway -> get( $token );
                        // echo json_encode([ "ID: " => $data ]);
                        
                        if( $data ) {
                            $result = $this -> gateway -> logout( $data[ "id" ]);
                            // echo json_encode([ "Result: " => $result ]);

                            if( $result ) {
                                echo json_encode([ "message" => "Logout successful!" ]);
                            }
                        }

                        else {
                            http_response_code( 401 );
                            echo json_encode([ "message" => "Unauthorized" ]);
                        }
                    }
                }
                break;
            
            default:
                http_response_code( 405 );
                header( "Allow: GET, POST" );
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