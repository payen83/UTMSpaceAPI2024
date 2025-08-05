<?php

class UploadGateway
{
    private PDO $conn;
        
    public function __construct( Database $database ) {
        $this -> conn = $database -> getConnection();
    }

    public function checkToken( string $token ): array | false {

        // Get Id Inside Table By token
        $sql = "SELECT id, token
                FROM staff
                WHERE token = :token";
        
        $stmt = $this -> conn -> prepare( $sql );

        $stmt -> bindValue( ":token", $token, PDO::PARAM_STR );
        $stmt -> execute();

        $data = $stmt -> fetch( PDO::FETCH_ASSOC );

        return $data;
    }

    // public function create( string $path ): string {

    //     // Insert Data Into Database
    //     $sql = "INSERT INTO news ( image_url )
    //             VALUES ( :image_url )";
        
    //     $stmt = $this -> conn -> prepare( $sql );

    //     $stmt -> bindValue( ":image_url", $path ?? null, PDO::PARAM_STR );

    //     $stmt -> execute();

    //     return $this -> conn -> lastInsertId();
    // }
}

?>