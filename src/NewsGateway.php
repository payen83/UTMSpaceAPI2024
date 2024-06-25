<?php

class NewsGateway
{
    private PDO $conn;
        
    public function __construct( Database $database ) {
        $this -> conn = $database -> getConnection();
    }
    
    public function getAll(): array {

        // Get All Data Inside Table
        $sql = "SELECT *
                FROM news";
                
        $stmt = $this -> conn -> query( $sql );

        $data = [];
        
        while ( $row = $stmt -> fetch( PDO::FETCH_ASSOC )) {
            $data[] = $row;
        }
        
        return $data;
    }

    public function get( string $id ): array | false {

        // Get Data Inside Table By Id
        $sql = "SELECT *
                FROM news
                WHERE id = :id";
                
        $stmt = $this -> conn -> prepare( $sql );

        $stmt -> bindValue( ":id", $id, PDO::PARAM_INT );
        $stmt -> execute();

        $data = $stmt -> fetch( PDO::FETCH_ASSOC );

        return $data;
    }

    public function getToken( string $token ): array | false {

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

    public function create( array $data ): string {

        // Insert Data Into Database
        $sql = "INSERT INTO news ( image_url, title, description, location, date )
                VALUES ( :image_url, :title, :description, :location, :date )";
        
        $stmt = $this -> conn -> prepare( $sql );

        $stmt -> bindValue( ":image_url", $data[ "image_url" ] ?? null, PDO::PARAM_STR );
        $stmt -> bindValue( ":title", $data[ "title" ] ?? null, PDO::PARAM_STR );
        $stmt -> bindValue( ":description", $data[ "description" ] ?? null, PDO::PARAM_STR );
        $stmt -> bindValue( ":location", $data[ "location" ] ?? null, PDO::PARAM_STR );
        $stmt -> bindValue( ":date", $data[ "date" ] ?? null, PDO::PARAM_STR );

        $stmt -> execute();

        return $this -> conn -> lastInsertId();
    }

    public function update( array $current, array $new ): int {

        // Update Data Inside Database
        $sql = "UPDATE news
                SET image_url = :image_url, title = :title, description = :description, location = :location, date = :date
                WHERE id = :id";

        $stmt = $this -> conn -> prepare( $sql );

        $stmt -> bindValue( ":image_url", $new[ "image_url" ] ?? $current[ "image_url" ], PDO::PARAM_STR );
        $stmt -> bindValue( ":title", $new[ "title" ] ?? $current[ "title" ], PDO::PARAM_STR );
        $stmt -> bindValue( ":description", $new[ "description" ] ?? $current[ "description" ], PDO::PARAM_STR );
        $stmt -> bindValue( ":location", $new[ "location" ] ?? $current[ "location" ], PDO::PARAM_STR );
        $stmt -> bindValue( ":date", $new[ "date" ] ?? $current[ "date" ], PDO::PARAM_STR );

        $stmt -> bindValue( ":id", $current[ "id" ], PDO::PARAM_INT );

        $stmt -> execute();

        return $stmt -> rowCount();
    }

    public function delete( string $id ): int {

        // Delete Data Inside Database
        $sql = "DELETE FROM news
                WHERE id = :id";

        $stmt = $this -> conn -> prepare( $sql );

        $stmt -> bindValue( ":id", $id, PDO::PARAM_INT );

        $stmt -> execute();

        return $stmt -> rowCount();
    }
}

?>