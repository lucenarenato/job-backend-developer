<?php

namespace App\Repositories;

use Exception;
use App\Models\Produto;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProdutoRepository
{
    public function all()
    {
        try {
            return Produto::all();
        } catch (Exception $error) {
            throw new Exception("Falha ao recuperar os produtos: " . $error->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function find($id)
    {
        try {
            $produto = Produto::find($id);

            if (!$produto) {
                throw new ModelNotFoundException;
            }

            return $produto;
        } catch (Exception $error) {
            throw new Exception("Falha ao recuperar produto com ID {$id}: " . $error->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function createOrUpdate($data, $id = 0)
    {
        try {
            $produto = Produto::find($id);

            if (!$produto) {
                return Produto::create($data);
            }

            $produto->update($data);
            return $produto;
        } catch (Exception $error) {
            throw new Exception("Falha ao criar ou atualizar produto com ID {$id}: " . $error->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function update($id, $data)
    {
        try {
            $produto = Produto::find($id);

            if (!$produto) {
                throw new ModelNotFoundException;
            }

            $produto->update($data);
            return $produto;
        } catch (Exception $error) {
            throw new Exception("Falha ao atualizar produto com ID {$id}: " . $error->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function delete($id)
    {
        try {
            $produto = Produto::find($id);

            if (!$produto) {
                throw new ModelNotFoundException;
            }

            $produto->delete();

            return true;
        } catch (Exception $error) {
            throw new Exception("Falha ao excluir produto com ID {$id}: " . $error->getMessage());
        }
    }
    public function searchByNameCategory($name, $category)
    {
        try {
            return Produto::where('name', 'like', "%{$name}%")
                ->where('category', 'like', "%{$category}%")
                ->get();
        } catch (Exception $error) {
            return $error->getMessage();
        }
    }

    public function searchByCategory($category)
    {
        try {
            return Produto::where('category', 'like', "%{$category}%")->get();
        } catch (Exception $error) {
            return $error->getMessage();
        }
    }

    public function searchByUrlImage($urlImage)
    {
        try {
            if ($urlImage) {
                return Produto::whereNotNull('image_url')
                    ->get();
            } else {
                return Produto::whereNull('image_url')
                    ->get();
            }
        } catch (Exception $error) {
            return $error->getMessage();
        }
    }
}
