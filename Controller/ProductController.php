<?php

namespace Project\Controller;

use Exception;

class ProductController extends AbstractController
{
    public function listAction(): void
    {
        try {
            $products = product()->all();

            $this->render('produto/listar', [
                'products' => $products,
            ]);
        } catch (Exception $e) {
            die("Erro"); // Refatorar
        }
    }

    public function addAction(): void
    {
        if (!$_POST) {
            $categories = category()->all();
            $suppliers = supplier()->all();
            $this->render('produto/adicionar',[
                'categories' => $categories,
                'suppliers' => $suppliers
            ]);
            return;
        }

        try {
            $product = product()->bootstrap(
                $_POST['categoria'],
                $_POST['fornecedor'],
                $_POST['nome'],
                (int)$_POST['quantidade'],
                (float)$_POST['peso']
            );

            $product->save();
        } catch (Exception $e) {
            die("Erro"); // Refatorar
        }

        header('location: /produto/listar');
    }

    public function editAction(): void
    {
        $id = $_GET['id'];
        $product = product()->findById($id);
        $categories = category()->all();
        $suppliers = supplier()->all();
        $category = category()->findById($product->categoria_id);
        $supplier = supplier()->findById($product->fornecedor_id);

        if (!$_POST) {
            $this->render('produto/editar', [
                'product' => $product,
                'categories' => $categories,
                'suppliers' => $suppliers,
                'category' => $category,
                'supplier' => $supplier
            ]);
            return;
        }

        try {
            $product->categoria_id = $_POST['categoria'];
            $product->fornecedor_id = $_POST['fornecedor'];
            $product->descricao = $_POST['nome'];
            $product->peso = (float)$_POST['peso'];
            $product->qtd_minima = (int)$_POST['quantidade'];

            $product->save();

        } catch (Exception $e) {
            die("Erro"); // Refatorar
        }

        header('location: /produto/listar');
    }

    public function removeAction(): void
    {
        $id = $_GET['id'];

        $product = product()->findById($id);

        $product->destroy();

        header('location: /produto/listar');
    }
}