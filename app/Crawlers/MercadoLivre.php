<?php

namespace App\Crawlers;

use App\Models\Configuracao;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class MercadoLivre extends Crawler
{
    use ResponseTrait;

    public function getInformacoesAnuncio($url)
    {
        $response = $this->getResponse($url);

        // Verificar se deve recuperar informações
        $configuracao = Configuracao::find(1);
        if (!$configuracao->recuperar_descricao_compra) {
            return $this->success('');
        }
        
        if (!preg_match('/<h1.class=\"ui-pdp-title\">(.*?)<\/h1>/is', $response, $matches)) {
            return $this->errorInformacoesAnuncio(2);
        }

        $titulo = $matches[1];

        if (!preg_match('/<span.class="andes-money-amount__fraction".[^>]*>(.*?)<\/span>/is', $response, $matches)) {
            return $this->errorInformacoesAnuncio(3);
        }

        $valor = $matches[1];

        if (!preg_match('/<span.class="andes-money-amount__cents andes-money-amount__cents--superscript-36".[^>]*>(.*?)<\/span>/is', $response, $matches)) {
            $matches[1] = '00';
        }

        $centavos = $matches[1];

        return $this->success([
            'titulo' => $titulo,
            'valor' => $valor . ',' . $centavos
        ]);
    }

    public function errorInformacoesAnuncio($code)
    {
        return $this->error('Não foi possível recuperar as informações do anúncio. Código: ' . $code);
    }
}