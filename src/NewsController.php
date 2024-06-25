<?php

class NewsController
{
    public function __construct( private NewsGateway $gateway )
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
        $news = $this -> gateway -> get( $id );
        
        if ( !$news ) {
            http_response_code( 404 );
            echo json_encode([ "message" => "News not found" ]);
            return;
        }
        
        switch ( $method ) {
            case "GET":
                echo json_encode( $news );
                break;
                
            case "PATCH":
                
                // Check Authorization
                $authorization = $this -> checkAuthorization();
                // echo json_encode([ "Authorization" => $authorization ]);

                if( $authorization ) {
                    $data = array();
                    $data = file_get_contents( 'php://input' );
    
                    // convert json to array of params
                    $data = json_decode( $data, true );
    
                    $rows = $this -> gateway -> update( $news, $data );
    
                    echo json_encode([
                        "id" => $id,
                        "count" => $rows,
                        "message" => "News is updated successfully!",
                    ]);
                }

                else {
                    http_response_code( 401 );
                    echo json_encode([ "message" => "Unauthorized" ]);
                }
                break;
                
            case "DELETE":
                
                // Check Authorization
                $authorization = $this -> checkAuthorization();
                // echo json_encode([ "Authorization" => $authorization ]);

                if( $authorization ) {
                    $rows = $this -> gateway -> delete( $id );
                    
                    echo json_encode([
                        "id" => $id,
                        "rows" => $rows,
                        "message" => "News is deleted successfully!",
                    ]);
                }

                else {
                    http_response_code( 401 );
                    echo json_encode([ "message" => "Unauthorized" ]);
                }
                break;
                
            default:
                http_response_code( 405 );
                header( "Allow: GET, PATCH, DELETE" );
        }
    }
    
    private function processCollectionRequest( string $method ): void
    {
        switch ( $method ) {
            case "GET":
                // echo json_encode( [ "id" => 123, "name" => "Jamil" ]);
                // echo json_encode( $this-> gateway -> getAll() );
                echo json_encode( mb_convert_encoding( $this-> gateway -> getAll(), 'UTF-8', 'UTF-8'), JSON_THROW_ON_ERROR );
                break;

            case "POST":

                // Check Authorization
                $authorization = $this -> checkAuthorization();
                // echo json_encode([ "Authorization" => $authorization ]);

                if( $authorization ) {
                    $_POST = json_decode( file_get_contents( "php://input" ), true );
                    $data = ( array ) $_POST;
                    
                    $id = $this -> gateway -> create( $data );
    
                    http_response_code( 201 );
                    echo json_encode([
                        "id" => $id,
                        "message" => "News is created successfully!",
                    ]);
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

                $data = $this -> gateway -> getToken( $token );
                // echo json_encode([ "ID: " => $data ]);

                if( $data ) {
                    return $data;
                }
            }
        }

        return [];
    }
}

?>