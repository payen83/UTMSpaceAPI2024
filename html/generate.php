<?php
    require_once( './dompdf/autoload.inc.php' );

    use Dompdf\Dompdf;
    
    // Turn on output buffering to avoid sending headers prematurely
    ob_start();

    $dompdf = new Dompdf();

    $ic = $_GET[ 'ic' ];

    $url = "http://localhost:8888/api/generate?ic=" . urlencode( $ic );

    // Fetch the HTML content
    $html = file_get_contents( $url );

    // Load HTML content into dompdf
    $dompdf -> loadHtml( $html );

    // Set paper size and orientation
    $dompdf -> setPaper( 'A4', 'portrait' );

    // Render the HTML as PDF
    $dompdf -> render();

    // Create New Name Based on Current Time
    $newName = "Staft_" . idate( 'Y' ) . idate( 'm' ) . idate( 'd' ) . idate( 'H' ) . idate( 'i' ) . idate( 's' );

    // Clear the output buffer
    ob_end_clean();

    // Output the generated PDF (stream to browser)
    $dompdf -> stream( $newName . '.pdf', [ 'Attachment' => 1 ]); // 0 = display in browser, 1 = download
?>