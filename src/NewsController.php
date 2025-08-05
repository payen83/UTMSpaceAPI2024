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
            header( "Content-Type: application/json" );
            http_response_code( 404 );
            echo json_encode([
                "status" => false,
                "message" => "News data not found."
            ]);
            exit;
        }
        
        switch ( $method ) {
            case "GET":
                header( "Content-Type: application/json" );
                http_response_code( 200 );
                echo json_encode([
                    "status" => true,
                    "message" => "News data retrieved successfully.",
                    "data" => $news
                ]);
                exit;
                
            case "PATCH":
                header( "Content-Type: application/json" );

                // Check Authorization
                $authorization = $this -> checkAuthorization();

                if( $authorization !== false ) {
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
                        $rawData = file_get_contents( "php://input" );
                        $data = $this -> parseRawMultipartFormData( $rawData, $contentType );
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
    
                    $rows = $this -> gateway -> update( $news, $data );
    
                    http_response_code( 200 );
                    echo json_encode([
                        "status" => true,
                        "message" => "News updated successfully.",
                        "data" => [
                            "id" => $id,
                            "affected_rows" => $rows
                        ]
                    ]);
                    exit;
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
                
            case "DELETE":
                header( "Content-Type: application/json" );

                // Check Authorization
                $authorization = $this -> checkAuthorization();

                if( $authorization !== false ) {
                    $rows = $this -> gateway -> delete( $id );

                    http_response_code( 200 );
                    echo json_encode([
                        "status" => true,
                        "message" => "News item deleted successfully.",
                        "data" => [
                            "id" => $id,
                            "affected_rows" => $rows
                        ]
                    ]);
                    exit;
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
                header( "Allow: GET, PATCH, DELETE" );
                http_response_code( 405 );
                echo json_encode([
                    "status" => false,
                    "message" => "Method not allowed. Allowed methods: GET, PATCH, DELETE."
                ]);
                exit;
        }
    }
    
    private function processCollectionRequest( string $method ): void
    {
        switch ( $method ) {
            case "GET":
                header( "Content-Type: application/json" );

                $newsItems = $this -> gateway -> getAll();

                http_response_code( 200 );
                echo json_encode([
                    "status" => true,
                    "message" => "News list retrieved successfully.",
                    "data" => $newsItems
                ]);
                exit;

            case "POST":
                header( "Content-Type: application/json" );

                // Check Authorization
                $authorization = $this -> checkAuthorization();

                if( $authorization !== false ) {
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
                    
                    $id = $this -> gateway -> create( $data );
    
                    http_response_code( 201 );
                    echo json_encode([
                        "status" => true,
                        "message" => "News is created successfully!",
                        "data" => [
                            "id" => $id,
                            "title" => $data["title"] ?? null
                        ]
                    ]);
                    exit;
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
                header("Content-Type: application/json");
                header( "Allow: GET, POST" );
                http_response_code( 405 );
                echo json_encode([
                    "status" => false,
                    "message" => "Method not allowed. Allowed methods: GET, POST."
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

    function parseRawMultipartFormData(string $rawData, string $contentType): array {
        $result = [];

        // Extract boundary from content type
        if (preg_match('/boundary=(.*)$/', $contentType, $matches)) {
            $boundary = $matches[1];
        } else {
            return $result; // No boundary found
        }

        // Split data by boundary
        $blocks = explode("--" . $boundary, $rawData);

        foreach ($blocks as $block) {
            // Ignore empty blocks or closing boundary
            if (empty(trim($block)) || $block == "--\r\n") continue;

            // Separate headers and body
            list($rawHeaders, $body) = explode("\r\n\r\n", $block, 2);

            $body = trim($body, "\r\n--");

            // Parse headers
            $headers = [];
            foreach (explode("\r\n", $rawHeaders) as $headerLine) {
                if (strpos($headerLine, ':') !== false) {
                    list($name, $value) = explode(':', $headerLine, 2);
                    $headers[strtolower(trim($name))] = trim($value);
                }
            }

            // Look for Content-Disposition header to find the form field name
            if (isset($headers['content-disposition'])) {
                if (preg_match('/name="([^"]+)"/', $headers['content-disposition'], $nameMatch)) {
                    $fieldName = $nameMatch[1];
                    // If this is a file input, you'd handle $_FILES manually here
                    $result[$fieldName] = $body;
                }
            }
        }

        return $result;
    }
}

?>