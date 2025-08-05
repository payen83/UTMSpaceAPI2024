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
            case "GET":
                header( "Content-Type: application/json" );

                // Check Authorization
                $authorization = $this -> checkAuthorization();

                if( $authorization !== false ) {
                    $result = $this -> gateway -> logout( $authorization[ "id" ]);

                    if ( $result ) {
                        http_response_code( 200 );
                        echo json_encode([
                            "status" => true,
                            "message" => "Logout successful!"
                        ]);
                        exit;
                    } 
                    
                    else {
                        http_response_code( 500 );
                        echo json_encode([
                            "status" => false,
                            "message" => "Logout failed due to a server error."
                        ]);
                        exit;
                    }
                }

                else {
                    http_response_code( 401 );
                    echo json_encode([
                        "status" => false,
                        "message" => "Unauthorized"
                    ]);
                    exit;
                }
                exit;

            default:
                header( "Content-Type: application/json" );
                header( "Allow: GET" );
                http_response_code( 405 );
                echo json_encode([
                    "status" => false,
                    "message" => "Method not allowed. Allowed methods: GET."
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
                "message" => "Authorization header is missing"
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
}

?>