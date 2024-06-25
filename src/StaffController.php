<?php

class StaffController
{
    public function __construct( private StaffGateway $gateway )
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
        $staff = $this -> gateway -> get( $id );
        
        if ( !$staff ) {
            http_response_code( 404 );
            echo json_encode([ "message" => "Staff not found" ]);
            return;
        }
        
        switch ( $method ) {
            case "GET":
                echo json_encode( $staff );
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
    
                    $rows = $this -> gateway -> update( $staff, $data );
    
                    echo json_encode([
                        "id" => $id,
                        "count" => $rows,
                        "message" => "Staff is updated successfully!",
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
                        "message" => "Staff is deleted successfully!",
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
    
                    $id = $this -> gateway -> create( $data );
    
                    http_response_code( 201 );
                    echo json_encode([
                        "id" => $id,
                        "message" => "Staff is created successfully!",
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