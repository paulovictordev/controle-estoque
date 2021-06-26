<?php

namespace Project\Models;

use Project\Core\Model;

/**
 * Class Produto
 * @package Project\Models
 */
class Produto extends Model
{
    /** @var array $safe não pode atualizar ou criar */
    protected static $safe = ["id"];

    /** @var string $entity tabela no banco de dados */
    protected static $entity = "produto";

    /** @var array $required campos da tabela */
    protected static $required = ["categoria_id", "fornecedor_id", "descricao", "qtd_minima"];

    /**
     * @param int $categoriaId
     * @param int $fornecedorId
     * @param string $descricao
     * @param int $qtdMinima
     * @return Produto|null
     */
    public function bootstrap(
        int $categoriaId,
        int $fornecedorId,
        string $descricao,
        int $qtdMinima,
        float $peso = null,
        bool $controlado = null
    ): ?Produto
    {
        $this->categoria_id = $categoriaId;
        $this->fornecedor_id = $fornecedorId;
        $this->descricao = $descricao;
        $this->qtd_minima = $qtdMinima;
        $this->peso = $peso;
        $this->controlado = $controlado;
        return $this;
    }

    /**
     * @param string $terms
     * @param string $params
     * @param string $columns
     * @return Produto|null
     */
    public function find(string $terms, string $params, string $columns = "*"): ?Produto
    {
        $find = $this->read("SELECT {$columns} FROM " . self::$entity . " WHERE {$terms}", $params);
        if ($this->fail() || !$find->rowCount()) {
            return null;
        }
        return $find->fetchObject(__CLASS__);
    }

    /**
     * @param int $id
     * @param string $columns
     * @return Produto|null
     */
    public function findById(int $id, string $columns = "*"): ?Produto
    {
        return $this->find("id = :id", "id={$id}", $columns);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @param string $columns
     * @return array|null
     */
    public function all(int $limit = 30, int $offset = 0, string $columns = "*"): ?array
    {
        $all = $this->read("SELECT {$columns} FROM " . self::$entity . " LIMIT :limit OFFSET :offset",
            "limit={$limit}&offset={$offset}");

        if ($this->fail() || !$all->rowCount()) {
            return null;
        }
        return $all->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
    }

    /**
     * @return Produto|null
     */
    public function save(): ?Produto
    {
        if (!$this->required()) {
            $this->message->warning("Existem campos que são obrigatórios não preenchidos");
            return null;
        }

        /** Atualiza o usuário */
        if (!empty($this->id)) {
            $productId = $this->id;
            $this->update(self::$entity, $this->safe(), "id = :id", "id={$productId}");
            if ($this->fail()) {
                $this->message->error("Erro ao atualizar, verifique os dados");
                return null;
            }
        }

        /** Cria um usuário */
        if (empty($this->id)) {
            $productId = $this->create(self::$entity, $this->safe());
            if ($this->fail()) {
                $this->message->error("Erro ao cadastrar, verifique os dados");
                return null;
            }
        }

        $this->data = ($this->findById($productId))->data();
        return $this;
    }

    /**
     * @return Produto|null
     */
    public function destroy(): ?Produto
    {
        if (!empty($this->id)) {
            $this->delete(self::$entity, "id = :id", "id={$this->id}");
        }

        if ($this->fail()) {
            $this->message = "Não foi possível remover o usuário";
            return null;
        }

        $this->message = "Usuário removida com sucesso";
        $this->data = null;
        return $this;
    }
}