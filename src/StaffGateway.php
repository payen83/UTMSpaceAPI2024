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
                    ic,
                    address,
                    position,
                    department,
                    phone_no,
                    birth_date,
                    self_report_date,
                    self_report_location,
                    self_report_letter,
                    self_report_document,
                    tentative_program
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
                    ic,
                    address,
                    position,
                    department,
                    phone_no,
                    birth_date,
                    self_report_date,
                    self_report_location,
                    self_report_letter,
                    self_report_document,
                    tentative_program
                FROM staff
                WHERE id = :id";
                
        $stmt = $this -> conn -> prepare( $sql );

        $stmt -> bindValue( ":id", $id, PDO::PARAM_INT );
        $stmt -> execute();

        $data = $stmt -> fetch( PDO::FETCH_ASSOC );

        return $data;
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

    public function create( array $data ): string {
        $hash_password = password_hash( $data[ "password" ], PASSWORD_DEFAULT );

        // Insert Data Into Database
        $sql = "INSERT INTO staff ( image_url, email, password, first_name, last_name, ic, address, position, department, phone_no, birth_date, self_report_date, self_report_location, self_report_letter, self_report_document, tentative_program )
                VALUES ( :image_url, :email, :password, :first_name, :last_name, :ic, :address, :position, :department, :phone_no, :birth_date, :self_report_date, :self_report_location, :self_report_letter, :self_report_document, :tentative_program )";
        
        $stmt = $this -> conn -> prepare( $sql );

        $stmt -> bindValue( ":image_url", $data[ "image_url" ] ?? null, PDO::PARAM_STR );
        $stmt -> bindValue( ":email", $data[ "email" ], PDO::PARAM_STR );
        $stmt -> bindValue( ":password", $hash_password, PDO::PARAM_STR );
        $stmt -> bindValue( ":first_name", $data[ "first_name" ] ?? null, PDO::PARAM_STR );
        $stmt -> bindValue( ":last_name", $data[ "last_name" ] ?? null, PDO::PARAM_STR );
        $stmt -> bindValue( ":ic", $data[ "ic" ] ?? null, PDO::PARAM_STR );
        $stmt -> bindValue( ":address", $data[ "address" ] ?? null, PDO::PARAM_STR );
        $stmt -> bindValue( ":position", $data[ "position" ] ?? null, PDO::PARAM_STR );
        $stmt -> bindValue( ":department", $data[ "department" ] ?? null, PDO::PARAM_STR );
        $stmt -> bindValue( ":phone_no", $data[ "phone_no" ] ?? null, PDO::PARAM_STR );
        $stmt -> bindValue( ":birth_date", $data[ "birth_date" ] ?? null, PDO::PARAM_STR );
        $stmt -> bindValue( ":self_report_date", $data[ "self_report_date" ] ?? null, PDO::PARAM_STR );
        $stmt -> bindValue( ":self_report_location", $data[ "self_report_location" ] ?? null, PDO::PARAM_STR );
        $stmt -> bindValue( ":self_report_letter", $data[ "self_report_letter" ] ?? null, PDO::PARAM_STR );
        $stmt -> bindValue( ":self_report_document", $data[ "self_report_document" ] ?? null, PDO::PARAM_STR );
        $stmt -> bindValue( ":tentative_program", $data[ "tentative_program" ] ?? null, PDO::PARAM_STR );

        $stmt -> execute();

        return $this -> conn -> lastInsertId();
    }

    public function update( array $current, array $new ): int {
        // Update Data Inside Database
        $sql = "UPDATE staff
                SET image_url = :image_url, email = :email, first_name = :first_name, last_name = :last_name, ic = :ic, address = :address, position = :position, department = :department, phone_no = :phone_no, birth_date = :birth_date, self_report_date = :self_report_date, self_report_location = :self_report_location, self_report_letter = :self_report_letter, self_report_document = :self_report_document, tentative_program = :tentative_program
                WHERE id = :id";

        $stmt = $this -> conn -> prepare( $sql );

        $stmt -> bindValue( ":image_url", $new[ "image_url" ] ?? $current[ "image_url" ], PDO::PARAM_STR );
        $stmt -> bindValue( ":email", $new[ "email" ] ?? $current[ "email" ], PDO::PARAM_STR );
        // $stmt -> bindValue( ":password", $hash_password ?? $current[ "password" ], PDO::PARAM_STR );
        $stmt -> bindValue( ":first_name", $new[ "first_name" ] ?? $current[ "first_name" ], PDO::PARAM_STR );
        $stmt -> bindValue( ":last_name", $new[ "last_name" ] ?? $current[ "last_name" ], PDO::PARAM_STR );
        $stmt -> bindValue( ":ic", $new[ "ic" ] ?? $current[ "ic" ], PDO::PARAM_STR );
        $stmt -> bindValue( ":address", $new[ "address" ] ?? $current[ "address" ], PDO::PARAM_STR );
        $stmt -> bindValue( ":position", $new[ "position" ] ?? $current[ "position" ], PDO::PARAM_STR );
        $stmt -> bindValue( ":department", $new[ "department" ] ?? $current[ "department" ], PDO::PARAM_STR );
        $stmt -> bindValue( ":phone_no", $new[ "phone_no" ] ?? $current[ "phone_no" ], PDO::PARAM_STR );
        $stmt -> bindValue( ":birth_date", $new[ "birth_date" ] ?? $current[ "birth_date" ], PDO::PARAM_STR );
        $stmt -> bindValue( ":self_report_date", $new[ "self_report_date" ] ?? $current[ "self_report_date" ], PDO::PARAM_STR );
        $stmt -> bindValue( ":self_report_location", $new[ "self_report_location" ] ?? $current[ "self_report_location" ], PDO::PARAM_STR );
        $stmt -> bindValue( ":self_report_letter", $new[ "self_report_letter" ] ?? $current[ "self_report_letter" ], PDO::PARAM_STR );
        $stmt -> bindValue( ":self_report_document", $new[ "self_report_document" ] ?? $current[ "self_report_document" ], PDO::PARAM_STR );
        $stmt -> bindValue( ":tentative_program", $new[ "tentative_program" ] ?? $current[ "tentative_program" ], PDO::PARAM_STR );

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

    public function searchIC( string $ic ): array | false {
        // Get Data Inside Table By Id
        $sql = "SELECT
                    id,
                    image_url,
                    email,
                    first_name,
                    last_name,
                    ic,
                    address,
                    position,
                    department,
                    phone_no,
                    birth_date,
                    self_report_date,
                    self_report_location,
                    self_report_letter,
                    self_report_document,
                    tentative_program
                FROM staff
                WHERE ic = :ic";
                
        $stmt = $this -> conn -> prepare( $sql );

        $stmt -> bindValue( ":ic", $ic, PDO::PARAM_INT );
        $stmt -> execute();

        $data = $stmt -> fetch( PDO::FETCH_ASSOC );

        return $data;
    }
    
}

?>