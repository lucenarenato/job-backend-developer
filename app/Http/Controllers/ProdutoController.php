<?php

namespace App\Http\Controllers;

use App\Repositories\ProdutoRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;

class ProdutoController extends Controller
{
    protected $produtoRepository;

    public function __construct(ProdutoRepository $produtoRepository)
    {
        $this->produtoRepository = $produtoRepository;
    }

    public function index()
    {
        try {
            return response()->json($this->produtoRepository->all(), Response::HTTP_OK);
        } catch (Exception $error) {

        }
    }
}
