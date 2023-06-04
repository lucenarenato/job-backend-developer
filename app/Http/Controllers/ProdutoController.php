<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProdutoRequest;
use App\Http\Requests\StoreProdutoRequest;
use App\Repositories\ProdutoRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
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

    public function destroy($id)
    {
        try {
            $this->produtoRepository->delete($id);
            return new JsonResponse([], Response::HTTP_NO_CONTENT);
        } catch (Exception $error) {
            return response()->json(['error' => $error->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function searchByNameCategory(Request $request)
    {
        try {
            return new JsonResponse($this->produtoRepository->searchByNameCategory($request->input('name'), $request->input('category')), Response::HTTP_OK);
        } catch (Exception $error) {
            return response()->json(['error' => $error->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function searchByCategory($category)
    {
        try{
            return new JsonResponse($this->produtoRepository->searchByCategory($category), Response::HTTP_OK);
        } catch (Exception $error) {
            return response()->json(['error' => $error->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function searchByUrlImage(Request $request)
    {
        try{
            return new JsonResponse($this->produtoRepository->searchByUrlImage($request->urlImage), Response::HTTP_OK);
        } catch (Exception $error) {
             return response()->json(['error' => $error->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
