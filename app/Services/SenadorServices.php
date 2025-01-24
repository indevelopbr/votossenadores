<?php
namespace App\Services;

use App\Models\Partido;
use App\Models\Senador;
use Illuminate\Support\Facades\Storage;

class SenadorServices
{
    public $url;

    public function __construct()
    {
        $this->url = env('SENADO_DADOSABERTOS_URL'); 
    }

    public function get($url = null, $uri = null)
    {
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL               => ($url ?? $this->url) . $uri,
            CURLOPT_RETURNTRANSFER    => true,
            CURLOPT_ENCODING          => '',
            CURLOPT_MAXREDIRS         => 10,
            CURLOPT_TIMEOUT           => 0,
            CURLOPT_FOLLOWLOCATION    => true,
            CURLOPT_HTTP_VERSION      => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST     => 'GET',
            CURLOPT_HTTPHEADER        => array(
                'Accept: application/json'
            ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);

        return $response;
    }

    public function getPartidos()
    {
        $response = $this->get(null, 'senador/partidos');

        return $response;
    }

    public function updatePartidos()
    {
        try {
            $partidos = json_decode($this->getPartidos(), true)['ListaPartidos']['Partidos']['Partido'];

            foreach ($partidos as $item) {

                if (!($item['DataExtincao'] ?? false)) {
                    if ($item['Sigla'] != 'S/Partido') {
                        // Muda a sigla para o formato sem acentos e caracteres especiais
                        $sigla = iconv('UTF-8', 'ASCII//TRANSLIT', $item['Sigla']);
                        $sigla = preg_replace('/[^a-zA-Z0-9]/', '', $sigla);
                        
                        $tempPartido = json_decode($this->get("https://dadosabertos.camara.leg.br/api/v2/partidos?sigla={$sigla}&ordem=ASC&ordenarPor=sigla"), true)['dados'];
                        $tempPartido = json_decode($this->get($tempPartido[0]['uri'] ?? null), true)['dados'] ?? [];

                        // Baixar imagem e salvar no storage
                        $logoUrl = $tempPartido['urlLogo'] ?? '';
                        $logoPath = null;

                        if ($logoUrl) {
                            $logoPath = $this->downloadImageLogoPartido($logoUrl, $item['Sigla']);
                        }

                        $item['logo']       = $logoPath;
                        $item['site']       = $tempPartido['urlWebSite'] ?? null;
                        $item['facebook']   = $tempPartido['urlFacebook'] ?? null;
                    }
                }

                $partido = Partido::updateOrCreate([
                    'id'            => $item['Codigo'],
                    'sigla'         => $item['Sigla'],
                ]);

                $partido->update([
                    'nome'          => $item['Nome'],
                    'logo'          => $item['logo'] ?? null,
                    'site'          => $item['site'] ?? null,
                    'facebook'      => $item['facebook'] ?? null,
                    'data_criacao'  => $item['DataCriacao'],
                    'data_extincao' => $item['DataExtincao'] ?? null,
                ]);
                $partido->save();

                echo "Partido {$item['Sigla']} atualizado com sucesso!\n";
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    private function downloadImageLogoPartido($url, $sigla)
    {
        try {
            $imageContent = @file_get_contents($url);

            if ($imageContent) {
                $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
                $filename = "uploads/partidos/{$sigla}/logo." . ($extension ?: 'png'); // Default para PNG
                Storage::disk('public')->put($filename, $imageContent);

                return Storage::disk('public')->url($filename);
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        return null;
    }

    public function getSenadoresAtual()
    {
        $response = $this->get(null, 'senador/lista/atual');

        return $response;
    }

    public function updateSenadoresAtual()
    {
        try {
            $senadores = json_decode($this->getSenadoresAtual(), true)['ListaParlamentarEmExercicio']['Parlamentares']['Parlamentar'];

            foreach ($senadores as $item) {
                $senador = $item['IdentificacaoParlamentar'];

                if (($senador['UrlFotoParlamentar'] ?? '') != '') {
                    $imagePath = $this->downloadImageSenador($senador['UrlFotoParlamentar'] ?? '', $senador['SiglaPartidoParlamentar'] ?? '', $senador['NomeParlamentar'] ?? '');
                }

                $sigla = iconv('UTF-8', 'ASCII//TRANSLIT', $senador['SiglaPartidoParlamentar'] ?? '');
                $sigla = preg_replace('/[^a-zA-Z0-9]/', '', $sigla);
                $sigla = Partido::where('sigla', $sigla)
                    ->whereNull('data_extincao')
                    ->first();

                $newSenador = Senador::updateOrCreate([
                    'id'            => (int) ($senador['CodigoParlamentar'] ?? 0)
                ]);

                $newSenador->update([
                    'codigo_publico_na_leg_atual'   => $senador['CodigoPublicoNaLegAtual'] ?? '',
                    'nome'                          => $senador['NomeParlamentar'] ?? '',
                    'nome_completo'                 => $senador['NomeCompletoParlamentar'] ?? '',
                    'sexo'                          => $senador['SexoParlamentar'] ?? '',
                    'forma_tratamento'              => $senador['FormaTratamento'] ?? '',
                    'url_foto'                      => $imagePath ?? '',
                    'url_pagina'                    => $senador['UrlPaginaParlamentar'] ?? null,
                    'email'                         => $senador['EmailParlamentar'] ?? null,
                    'telefone'                      => '+5561' . ($senador['Telefones']['Telefone'][0]['NumeroTelefone'] ?? '6100000000'),
                    'partido_id'                    => $sigla->id ?? null,
                    'uf'                            => $senador['UfParlamentar'] ?? '',
                    'membro_mesa'                   => ($senador['MembroMesa'] ?? '') == 'Sim' ? true : false,
                    'membro_lideranca'              => ($senador['MembroLideranca'] ?? '') == 'Sim' ? true : false,
                ]);
                $newSenador->save();

                echo "Senador {$newSenador->nome} atualizado com sucesso!\n";
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    private function downloadImageSenador($url, $sigla, $nome)
    {
        try {
            $imageContent = file_get_contents($url);

            if ($imageContent) {
                $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
                $filename = "uploads/partidos/{$sigla}/senadores/{$nome}." . ($extension ?: 'png'); // Default para PNG
                Storage::put($filename, $imageContent);

                return Storage::url($filename); // Retorna URL pública ou caminho
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        return null;
    }

    public function initUpdate()
    {
        $this->updatePartidos();
        $this->updateSenadoresAtual();
    }
}
