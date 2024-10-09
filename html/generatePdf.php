<?php
    // Fetch data from the database
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "ispaceDB";

    // Create connection
    $conn = new mysqli( $servername, $username, $password, $dbname );

    // Check connection
    if ( $conn -> connect_error ) {
        die( "Connection failed: " . $conn -> connect_error );
    }

    $sql = "SELECT id, first_name, email, ic, address, phone_no, birth_date, self_report_date, self_report_location, self_report_letter, self_report_document, tentative_program FROM staff WHERE ic = ?";
    $stmt = $conn -> prepare( $sql );
    $stmt -> bind_param( "s", $ic );
    $ic = $_GET[ 'ic' ]; // Assuming 'ic' is passed in the URL as a query parameter

    // Execute and fetch results
    $stmt -> execute();
    $result = $stmt -> get_result();
    $row = $result -> fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Title -->
        <title> Generate PDF </title>

        <!-- Google Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Space+Grotesk:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

        <!-- Boxicons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

        <style>
            table, th, td {
                border: 1px solid #c0c0c0;
                border-collapse: collapse;
            }

            .header-container {
                margin: 30px;

                .header-title {
                    font-size: 50px !important;
                    font-weight: 600;
                    color: #000;
                }
            }

            .table-container {
                margin: 30px;

                table {
                    width: 100%;

                    tr {
                        width: 100%;
                    }

                    .table-header {
                        padding: 10px;
                        width: 250px;
                        border: 1px solid #000;
                        color: #fff;
                        background-color: #387cb4;
                        text-align: left;
                    }

                    .table-content {
                        padding: 10px;
                        width: 350px;
                        color: #000;
                        background-color: #fff;
                    }
                }
            }
        </style>
    </head>
    <body>

        <div class="header-container">
            <span class="header-title"> Maklumat Staff </span>
        </div>

        <div class="table-container">

            <div>
                <div>
                    <table cellspacing="0">
                        
                        <!-- Name -->
                        <tr>
                            <th class="table-header">
                                <span> Nama Staff </span>
                            </th>
                            <td class="table-content">
                                <span><?php echo htmlspecialchars( $row[ 'first_name' ]); ?></span>
                            </td>
                        </tr>
                        <!-- Name End -->
    
                        <!-- IC -->
                        <tr>
                            <th class="table-header">                  
                                <span> No. Kp </span>
                            </th>
                            <td class="table-content">
                                <span><?php echo htmlspecialchars( $row[ 'ic' ]); ?></span>
                            </td>
                        </tr>
                        <!-- IC End -->
                        
                        <!-- Address -->
                        <tr>
                            <th class="table-header">                  
                                <span> Alamat </span>
                            </th>
                            <td class="table-content">
                                <span><?php echo htmlspecialchars( $row[ 'address' ]); ?></span>
                            </td>
                        </tr>
                        <!-- Address End -->
                        
                        <!-- Phone -->
                        <tr>
                            <th class="table-header">                  
                                <span> No. Telefon </span>
                            </th>
                            <td class="table-content">
                                <span><?php echo htmlspecialchars( $row[ 'phone_no' ]); ?></span>
                            </td>
                        </tr>
                        <!-- Phone End -->
                        
                        <!-- Email -->
                        <tr>
                            <th class="table-header">                  
                                <span> Emel </span>
                            </th>
                            <td class="table-content">
                                <span><?php echo htmlspecialchars( $row[ 'email' ]); ?></span>
                            </td>
                        </tr>
                        <!-- Email End -->
                        
                        <!-- Self Report Date -->
                        <tr>
                            <th class="table-header">                  
                                <span> Tarikh Lapor Diri </span>
                            </th>
                            <td class="table-content">
                                <span><?php echo htmlspecialchars( $row[ 'self_report_date' ]); ?></span>
                            </td>
                        </tr>
                        <!-- Self Report Date End -->
                        
                        <!-- Self Report Date Location -->
                        <tr>
                            <th class="table-header">                  
                                <span> Lokasi Lapor Diri </span>
                            </th>
                            <td class="table-content">
                                <span><?php echo htmlspecialchars( $row[ 'self_report_location' ]); ?></span>
                            </td>
                        </tr>
                        <!-- Self Report Date Location End -->
                        
                        <!-- Self Report Letter -->
                        <tr>
                            <th class="table-header">                  
                                <span> Surat Arahan Lapor Diri </span>
                            </th>
                            <td class="table-content">
                                <span><?php echo htmlspecialchars( $row[ 'self_report_letter' ]); ?></span>
                            </td>
                        </tr>
                        <!-- Self Report Letter End -->
                        
                        <!-- Self Report Document -->
                        <tr>
                            <th class="table-header">                  
                                <span> Dokumen Lapor Diri </span>
                            </th>
                            <td class="table-content">
                                <span><?php echo htmlspecialchars( $row[ 'self_report_document' ]); ?></span>
                            </td>
                        </tr>
                        <!-- Self Report Document End -->
                        
                        <!-- Tentative Program -->
                        <tr>
                            <th class="table-header">                  
                                <span> Tentatif Program </span>
                            </th>
                            <td class="table-content">
                                <span><?php echo htmlspecialchars( $row[ 'tentative_program' ]); ?></span>
                            </td>
                        </tr>
                        <!-- Tentative Program End -->
        
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>