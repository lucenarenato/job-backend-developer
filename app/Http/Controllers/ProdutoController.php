<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProdutoRequest;
use App\Http\Requests\StoreProdutoRequest;
use App\Repositories\ProdutoRepository;
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
            return response()->json(['error' => $error->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try {
            return response()->json($this->produtoRepository->find($id), Response::HTTP_OK);
        } catch (Exception $error) {
            return response()->json(['error' => $error->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(StoreProdutoRequest $request)
    {
        try {
            return response()->json($this->produtoRepository->createOrUpdate($request->all()), Response::HTTP_CREATED);
        } catch (Exception $error) {
            return response()->json(['error' => $error->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateProdutoRequest $request, $id)
    {
        try {
            return response()->json($this->produtoRepository->createOrUpdate($request->all(), $id), Response::HTTP_OK);
        } catch (Exception $error) {
            return response()->json(['error' => $error->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
