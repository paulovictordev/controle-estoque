<?php

namespace Project\Models;

use Project\Core\Model;

/**
 * Class Categoria
 * @package Project\Models
 */
class Categoria extends Model
{
    /** @var array $safe não pode atualizar ou criar */
    protected static $safe = ["id"];

    /** @var string $entity tabela no banco de dados */
    protected static $entity = "categoria";

    /** @var array $required campos da tabela */
    protected static $required = ["categoria"];

    /**
     * @param string $cidade
     * @param string $uf
     * @return Categoria|null
     */
    public function bootstrap(string $categoria): ?Categoria
    {
        $this->categoria = $categoria;
        return $this;
    }

    /**
     * @param string $terms
     * @param string $params
     * @param string $columns
     * @return Categoria|null
     */
    public function find(string $terms, string $params, string $columns = "*"): ?Categoria
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
     * @return Categoria|null
     */
    public function findById(int $id, string $columns = "*"): ?Categoria
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
     * @return Categoria|null
     */
    public function save(): ?Categoria
    {
        if (!$this->required()) {
            $this->message->warning("Nome da categoria é obrigatório");
            return null;
        }

        /** Atualiza a categoria */
        if (!empty($this->id)) {
            $categoriaId = $this->id;

            $this->update(self::$entity, $this->safe(), "id = :id", "id={$categoriaId}");
            if ($this->fail()) {
                $this->message->error("Erro ao atualizar, verifique os dados");
                return null;
            }
        }

        /** Cria uma categoria */
        if (empty($this->id)) {
            $categoriaId = $this->create(self::$entity, $this->safe());
            if ($this->fail()) {
                $this->message->error("Erro ao cadastrar, verifique os dados");
                return null;
            }
        }

        $this->data = ($this->findById($categoriaId))->data();
        return $this;
    }

    /**
     * @return Categoria|null
     */
    public function destroy(): ?Categoria
    {
        if (!empty($this->id)) {
            $this->delete(self::$entity, "id = :id", "id={$this->id}");
        }

        if ($this->fail()) {
            $this->message = "Não foi possível remover a Categoria";
            return null;
        }

        $this->message = "Categoria removida com sucesso";
        $this->data = null;
        return $this;
    }
}