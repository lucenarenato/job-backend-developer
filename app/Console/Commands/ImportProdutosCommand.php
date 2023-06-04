<?php

namespace App\Console\Commands;

use App\Models\Produto;
use App\Services\ProdutoService;
use App\Repositories\ProdutoRepository;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ImportProdutosCommand extends Command
{
    protected $signature = 'products:import  {id? : Id especifico.}';
    protected $description = 'Importar dados de produtos da API fakestoreapi';
    protected $produtoService;
    protected $produtoRepository;
    protected $client;

    public function __construct(ProdutoService $produtoService, ProdutoRepository $produtoRepository)
    {
        parent::__construct();
        $this->produtoService = $produtoService;
        $this->produtoRepository = $produtoRepository;
        $this->client = new Client();
    }

    public function handle()
    {
        $produtoId = $this->argument('id');

        if ($produtoId) {
            $return = $this->produtoService->importProduto($produtoId);

            if ($return) {
                $this->info("Produto ID: $produtoId importado com sucesso!");
            } else {
                Log::error("Falha ao importar produto ID: $produtoId!");
                $this->error("Falha ao importar produto ID: $produtoId!");
            }
        } else {
            $produtoAll = Produto::all()->count();
            $progressBar = $this->output->createProgressBar($produtoAll);
            $progressBar->start();
            $response = $this->client->get('https://fakestoreapi.com/products');
            $produtos = json_decode($response->getBody()->getContents(), true);
            foreach ($produtos as $value) {
                $progressBar->advance();
                $produto = [
                    'name' => $value['title'],
                    'price' => $value['price'],
                    'description' => $value['description'],
                    'category' => $value['category'],
                    'image_url' => $value['image']
                ];
                $return = $this->produtoRepository->createOrUpdate($produto, $value['id']);
            }

            if ($return) {
                $progressBar->finish();
                echo " " . "\n";
                $this->info('Success! Dados importados');
            } else {
                Log::error('Falha ao atualizar os dados dos produtos!');
                $this->error('Falha ao atualizar os dados dos produtos!');
            }
        }
    }
}
