<?php

class StaffGateway
{
    private PDO $conn;
        
    public function __construct( Database $database ) {
        $this -> conn = $database -> getConnection();
    }
    
    public function getAll(): array {

        // Get All Data Inside Table
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
                FROM staff";
                
        $stmt = $this -> conn -> query( $sql );

        $data = [];
        
        while ( $row = $stmt -> fetch( PDO::FETCH_ASSOC )) {
            $data[] = $row;
        }
        
        return $data;
    }

    public function get( string $id ): array | false {

        // Get Data Inside Table By Id
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
        $hash_password = password_hash( $data[ "password" ], PASSWORD_DEFAULT );

        // Print the generated hash 
        // echo "Generated Hash Password: ".$hash_password;

        // $verify = password_verify( "test123456", $hash_password );

        // echo "<br>Verify Password: ".$verify;

        // Insert Data Into Database
        $sql = "INSERT INTO staff ( image_url, email, password, first_name, last_name, position, department, phone_no, birth_date )
                VALUES ( :image_url, :email, :password, :first_name, :last_name, :position, :department, :phone_no, :birth_date )";
        
        $stmt = $this -> conn -> prepare( $sql );

        $stmt -> bindValue( ":image_url", $data[ "image_url" ] ?? null, PDO::PARAM_STR );
        $stmt -> bindValue( ":email", $data[ "email" ], PDO::PARAM_STR );
        $stmt -> bindValue( ":password", $hash_password, PDO::PARAM_STR );
        $stmt -> bindValue( ":first_name", $data[ "first_name" ] ?? null, PDO::PARAM_STR );
        $stmt -> bindValue( ":last_name", $data[ "last_name" ] ?? null, PDO::PARAM_STR );
        $stmt -> bindValue( ":position", $data[ "position" ] ?? null, PDO::PARAM_STR );
        $stmt -> bindValue( ":department", $data[ "department" ] ?? null, PDO::PARAM_STR );
        $stmt -> bindValue( ":phone_no", $data[ "phone_no" ] ?? null, PDO::PARAM_STR );
        $stmt -> bindValue( ":birth_date", $data[ "birth_date" ] ?? null, PDO::PARAM_STR );

        $stmt -> execute();

        return $this -> conn -> lastInsertId();
    }

    public function update( array $current, array $new ): int {
        // $hash_password = null;
        // $is_password = $new[ "password" ] ?? null;
        
        // if( $is_password ) {
        //     $hash_password = password_hash( $new[ "password" ], PASSWORD_DEFAULT );
        //     // echo "Generated Hash Password: ".$hash_password;
        // }

        // Update Data Inside Database
        $sql = "UPDATE staff
                SET image_url = :image_url, email = :email, first_name = :first_name, last_name = :last_name, position = :position, department = :department, phone_no = :phone_no, birth_date = :birth_date
                WHERE id = :id";

        $stmt = $this -> conn -> prepare( $sql );

        $stmt -> bindValue( ":image_url", $new[ "image_url" ] ?? $current[ "image_url" ], PDO::PARAM_STR );
        $stmt -> bindValue( ":email", $new[ "email" ] ?? $current[ "email" ], PDO::PARAM_STR );
        // $stmt -> bindValue( ":password", $hash_password ?? $current[ "password" ], PDO::PARAM_STR );
        $stmt -> bindValue( ":first_name", $new[ "first_name" ] ?? $current[ "first_name" ], PDO::PARAM_STR );
        $stmt -> bindValue( ":last_name", $new[ "last_name" ] ?? $current[ "last_name" ], PDO::PARAM_STR );
        $stmt -> bindValue( ":position", $new[ "position" ] ?? $current[ "position" ], PDO::PARAM_STR );
        $stmt -> bindValue( ":department", $new[ "department" ] ?? $current[ "department" ], PDO::PARAM_STR );
        $stmt -> bindValue( ":phone_no", $new[ "phone_no" ] ?? $current[ "phone_no" ], PDO::PARAM_STR );
        $stmt -> bindValue( ":birth_date", $new[ "birth_date" ] ?? $current[ "birth_date" ], PDO::PARAM_STR );

        $stmt -> bindValue( ":id", $current[ "id" ], PDO::PARAM_INT );

        $stmt -> execute();

        return $stmt -> rowCount();
    }

    public function delete( string $id ): int {

        // Delete Data Inside Database
        $sql = "DELETE FROM staff
                WHERE id = :id";

        $stmt = $this -> conn -> prepare( $sql );

        $stmt -> bindValue( ":id", $id, PDO::PARAM_INT );

        $stmt -> execute();

        return $stmt -> rowCount();
    }
}

?>