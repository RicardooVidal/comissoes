<?php

namespace App\Http\Controllers;

use App\Crawlers\MercadoLivre;
use Illuminate\Http\Request;

class CrawlerController extends Controller
{
    public function get(Request $request)
    {
        if (!$request->plataforma) {
            return $this->error('Plataforma não informada', 422);
        }

        if (!$request->url) {
            return $this->error('URL não informado', 422);
        }

        switch ($request->plataforma) {
            case 'mercado_livre':
                return (new MercadoLivre())->getInformacoesAnuncio($request->url);
                break;
            default:
                throw new \Exception('Plataforma não encontrada');
        }
    }
}
