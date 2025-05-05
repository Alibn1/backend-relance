<?php

namespace App\Services;

use App\Models\Client;
use App\Models\SousModele;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class SousModelePDFService
{
    /**
     * Génère un PDF à partir du sous-modèle, client, et relevé.
     *
     * @param Client $client
     * @param mixed $releve (tu peux typer si tu as un modèle Releve)
     * @param SousModele $sousModele
     * @return array ['path' => '...']
     */
    public function generatePDF(Client $client, $releve, SousModele $sousModele)
    {
        // Charge la vue Blade avec les données
        $pdfContent = Pdf::loadView('pdf.releve_template', [
            'client' => $client,
            'releve' => $releve,
            'sousModele' => $sousModele,
        ])->output();

        $pdfName = 'pdf_' . $client->code_client . '_' . now()->timestamp . '.pdf';
        $path = 'relances/' . $pdfName;

        Storage::disk('public')->put($path, $pdfContent);

        return [
            'path' => $path,
        ];
    }
}
