<?php

namespace App\Services;

use App\Repositories\ProdutoRepository;
use GuzzleHttp\Client;

class ProdutoService
{
    protected $produtoRepository;
    protected $client;

    public function __construct(ProdutoRepository $produtoRepository)
    {
        $this->produtoRepository = $produtoRepository;
        $this->client = new Client();
    }

    public function importProduto($id)
    {
        try {
            $response = $this->client->get('https://fakestoreapi.com/products/'.$id);
            $produtos = json_decode($response->getBody()->getContents(), true);
            $produto = [
                'name' => $produtos['title'],
                'description' => $produtos['description'],
                'price' => $produtos['price'],
                'category' => $produtos['category'],
                'image_url' => $produtos['image']
            ];
            $this->produtoRepository->createOrUpdate($produto, $produtos['id']);


            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
