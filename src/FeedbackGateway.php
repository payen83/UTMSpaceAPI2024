<?php

class FeedbackGateway
{
    private PDO $conn;
        
    public function __construct( Database $database ) {
        $this -> conn = $database -> getConnection();
    }
    
    public function getAll(): array {

        // Get All Data Inside Table
        $sql = "SELECT *
                FROM maklumbalas";
                
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
                FROM maklumbalas
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
        $sql = "INSERT INTO maklumbalas ( image_url, nama, feedback, kategori, tel )
                VALUES ( :image_url, :nama, :feedback, :kategori, :tel )";
        
        $stmt = $this -> conn -> prepare( $sql );

        $stmt -> bindValue( ":image_url", $data[ "image_url" ] ?? null, PDO::PARAM_STR );
        $stmt -> bindValue( ":nama", $data[ "nama" ] ?? null, PDO::PARAM_STR );
        $stmt -> bindValue( ":feedback", $data[ "feedback" ] ?? null, PDO::PARAM_STR );
        $stmt -> bindValue( ":kategori", $data[ "kategori" ] ?? null, PDO::PARAM_STR );
        $stmt -> bindValue( ":tel", $data[ "tel" ] ?? null, PDO::PARAM_STR );


        $stmt -> execute();

        return $this -> conn -> lastInsertId();
    }

    public function update( array $current, array $new ): int {

        // Update Data Inside Database
        $sql = "UPDATE maklumbalas
                SET image_url = :image_url, nama = :nama, feedback = :feedback, kategori = :kategori, tel = :tel
                WHERE id = :id";

        $stmt = $this -> conn -> prepare( $sql );

        $stmt -> bindValue( ":image_url", $new[ "image_url" ] ?? $current[ "image_url" ], PDO::PARAM_STR );
        $stmt -> bindValue( ":nama", $new[ "nama" ] ?? $current[ "nama" ], PDO::PARAM_STR );
        $stmt -> bindValue( ":feedback", $new[ "feedback" ] ?? $current[ "feedback" ], PDO::PARAM_STR );
        $stmt -> bindValue( ":kategori", $new[ "kategori" ] ?? $current[ "kategori" ], PDO::PARAM_STR );
        $stmt -> bindValue( ":tel", $new[ "tel" ] ?? $current[ "tel" ], PDO::PARAM_STR );

        $stmt -> bindValue( ":id", $current[ "id" ], PDO::PARAM_INT );

        $stmt -> execute();

        return $stmt -> rowCount();
    }

    public function delete( string $id ): int {

        // Delete Data Inside Database
        $sql = "DELETE FROM maklumbalas
                WHERE id = :id";

        $stmt = $this -> conn -> prepare( $sql );

        $stmt -> bindValue( ":id", $id, PDO::PARAM_INT );

        $stmt -> execute();

        return $stmt -> rowCount();
    }
}

?>