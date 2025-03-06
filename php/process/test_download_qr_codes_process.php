<?php
require 'test_db_connect.php';
require '../vendor/autoload.php'; // Ensure Endroid QR Code library is installed

use ZipStream\ZipStream;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

if (isset($_POST['item_ids']) && is_array($_POST['item_ids'])) {
    $itemIds = $_POST['item_ids'];
    $zip = new ZipStream(outputName: 'qr_codes.zip');

    $stmt = $conn->prepare("SELECT item_id, item_details FROM Items WHERE item_id IN (" . implode(',', array_fill(0, count($itemIds), '?')) . ")");
    $stmt->bind_param(str_repeat('i', count($itemIds)), ...$itemIds);
    $stmt->execute();
    $result = $stmt->get_result();

    $writer = new PngWriter();
    while ($row = $result->fetch_assoc()) {
        $itemId = $row['item_id'];
        $itemDetails = preg_replace('/[^A-Za-z0-9\-]/', '-', $row['item_details']);
        $qrCodeData = $itemId . '-' . $itemDetails;
        $qrCode = QrCode::create($qrCodeData)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::Low)
            ->setSize(145)
            ->setMargin(10)
            ->setRoundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));

        $label = Label::create($qrCodeData)->setTextColor(new Color(0, 0, 0));
        $result = $writer->write($qrCode, null, $label);
        $zip->addFile("qr_code_{$itemId}.png", $result->getString());
    }

    $zip->finish();
}

$conn->close();
?>