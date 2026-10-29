<?php
require __DIR__ . '/../vendor/autoload.php';
use Dompdf\Dompdf; use Dompdf\Options;
$o = new Options(); $o->set('isRemoteEnabled', false); $o->set('isHtml5ParserEnabled', true); $o->set('defaultFont','DejaVu Sans');
$d = new Dompdf($o);
$d->loadHtml(file_get_contents(__DIR__.'/hr-portal-handover.html'), 'UTF-8');
$d->setPaper('A4','portrait'); $d->render();
file_put_contents(__DIR__.'/2B-HR-Portal-Handover.pdf', $d->output());
echo "PDF: ".realpath(__DIR__.'/2B-HR-Portal-Handover.pdf').PHP_EOL;
