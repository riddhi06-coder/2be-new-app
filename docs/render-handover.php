<?php
require __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled', false);
$options->set('isHtml5ParserEnabled', true);
$options->set('defaultFont', 'DejaVu Sans');

$dompdf = new Dompdf($options);
$html = file_get_contents(__DIR__ . '/technical-handover.html');
$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

file_put_contents(__DIR__ . '/2B-Environmental-Technical-Handover.pdf', $dompdf->output());
echo "PDF written: " . realpath(__DIR__ . '/2B-Environmental-Technical-Handover.pdf') . PHP_EOL;
