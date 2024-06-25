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
        http_response_code( 404 );
        echo json_encode([ "message" => "Invalid url" ]);
    }
    
    private function processCollectionRequest( string $method ): void
    {
        switch ( $method ) {
            case "POST":
                // $data = ( array ) $_POST;
                $_POST = json_decode( file_get_contents( "php://input" ), true );
                $data = ( array ) $_POST;

                // Check Empty Field
                $errors = $this -> getValidationErrors( $data );

                if( !empty( $errors )) {
                    http_response_code( 422 );
                    echo json_encode([ "errors" => $errors ]);
                    break;
                }

                // Get Staff
                $password = $this -> gateway -> getPassword( $data );
                // echo json_encode([ "Staff DATA" => $staff ]);

                // Verify Password
                $verify = password_verify( $data[ "password" ], $password[ "password" ]);
                // echo json_encode([ "Verify: " => $verify ]);

                if( $verify ) {
                    $token = password_hash( $data[ "password" ], PASSWORD_DEFAULT );
                    // echo json_encode([ "Token: " => $token ]);

                    $staff = $this -> gateway -> get( $data );
                    $result = $this -> gateway -> token( $token, $staff[ "id" ]);
                    // echo json_encode([ "Row: " => $result ]);

                    if( $result ) {
                        http_response_code( 201 );
                        echo json_encode([
                            "token" => $token,
                            "staff" => $staff
                        ]);
                    }
                }

                else {
                    http_response_code( 404 );
                    echo json_encode([ "message" => "Invalid Password" ]);
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