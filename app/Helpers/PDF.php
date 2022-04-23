<?php

namespace App\Helpers;

use App\Models\Comissao;
use Barryvdh\DomPDF\Facade\Pdf AS DomPDF;

class PDF
{
    /**
     * Gera o pdf da view para stream ou download
     */
    public static function generatePdf($data, $view, $stream = false)
    {
        $uniqid = md5(uniqid(rand(), true)) . '.pdf';
        $pdf = DomPDF::loadView($view, compact('data'));

        if ($stream) {
            return $pdf->setPaper('a4')->stream($uniqid);
        }
        return $pdf->setPaper('a4')->download($uniqid);
    }
}