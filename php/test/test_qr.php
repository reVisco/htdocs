<?php

require '../vendor/autoload.php';
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\ValidationException;

$writer = new PngWriter();

$item_id = '01';
$serial_number = '023';
$item_name = 'printer';
$qr_code_data = $item_id . "-" . $serial_number;

// Create QR code
$qrCode = QrCode::create($qr_code_data)
    ->setEncoding(new Encoding('UTF-8'))
    ->setErrorCorrectionLevel(ErrorCorrectionLevel::Low)
    ->setSize(145)
    ->setMargin(10)
    ->setRoundBlockSizeMode(RoundBlockSizeMode::Margin)
    ->setForegroundColor(new Color(0, 0, 0))
    ->setBackgroundColor(new Color(255, 255, 255));

// Create generic logo
/*$logo = Logo::create(__DIR__.'\assets\astoria.png')
    ->setResizeToWidth(50)
    ->setPunchoutBackground(true)
;*/

// Create generic label
$label = Label::create($item_id . "-". $item_name)
    ->setTextColor(new Color(0, 0, 0));

/*$result = $writer->write($qrCode, $logo, $label);*/

/*$result = $writer->write($qrCode, $logo);*/
$result = $writer->write($qrCode, null ,$label);

/*$result = $writer->write($qrCode);*/

// Validate the result
$writer->validateResult($result, $qr_code_data);

// Directly output the QR code
header('Content-Type: '.$result->getMimeType());
echo $result->getString();

// Save it to a file
/*$result->saveToFile(__DIR__.'\qr_codes\\'. $item_name . '-' . $qr_code_data . '.png');*/

// Generate a data URI to include image data inline (i.e. inside an <img> tag)
$dataUri = $result->getDataUri();
?>
