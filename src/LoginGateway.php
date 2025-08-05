<?php

class LoginGateway
{
    private PDO $conn;
        
    public function __construct( Database $database ) {
        $this -> conn = $database -> getConnection();
    }

    public function get( array $data ): array | false {
        // Get Data Inside Table By Email
        $sql = "SELECT 
                    id,
                    image_url,
                    email,
                    first_name,
                    last_name,
                    position,
                    department,
                    phone_no,
                    birth_date
                FROM staff
                WHERE email = :email";
        
        $stmt = $this -> conn -> prepare( $sql );

        $stmt -> bindValue( ":email", $data[ "email" ], PDO::PARAM_STR );
        $stmt -> execute();

        $data = $stmt -> fetch( PDO::FETCH_ASSOC );

        return $data;
    }

    public function getPassword( array $data ): array | false {
        // Get Password Inside Table By Email
        $sql = "SELECT password
                FROM staff
                WHERE email = :email";
        
        $stmt = $this -> conn -> prepare( $sql );

        $stmt -> bindValue( ":email", $data[ "email" ], PDO::PARAM_STR );
        $stmt -> execute();

        $data = $stmt -> fetch( PDO::FETCH_ASSOC );

        return $data;
    }

    public function token( string $token, string $id ): int {
        // Update Data Inside Database
        $sql = "UPDATE staff
                SET token = :token
                WHERE id = :id";

        $stmt = $this -> conn -> prepare( $sql );

        $stmt -> bindValue( ":token", $token ?? null, PDO::PARAM_STR );

        $stmt -> bindValue( ":id", $id, PDO::PARAM_INT );

        $stmt -> execute();

        return $stmt -> rowCount();
    }
}

?>