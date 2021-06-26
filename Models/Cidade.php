<?php

namespace Project\Models;

use Project\Core\Model;

/**
 * Class Cidade
 * @package Project\Models
 */
class Cidade extends Model
{
    /** @var array $safe não pode atualizar ou criar */
    protected static $safe = ["id"];

    /** @var string $entity tabela no banco de dados */
    protected static $entity = "cidade";

    /** @var array $required campos da tbela */
    protected static $required = ["cidade", "uf"];

    /**
     * @param string $cidade
     * @param string $uf
     * @return City|null
     */
    public function bootstrap(string $cidade, string $uf): ?City
    {
        $this->cidade = $cidade;
        $this->uf = $uf;
        return $this;
    }

    /**
     * @param string $terms
     * @param string $params
     * @param string $columns
     * @return City|null
     */
    public function find(string $terms, string $params, string $columns = "*"): ?City
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
     * @return City|null
     */
    public function findById(int $id, string $columns = "*"): ?City
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
     * @return City|null
     */
    public function save(): ?City
    {
        if (!$this->required()) {
            $this->message->warning("Nome da cidade e UF são obrigatórios");
            return null;
        }

        /** Atualiza a cidade */
        if (!empty($this->id)) {
            $cityId = $this->id;

            $this->update(self::$entity, $this->safe(), "id = :id", "id={$cityId}");
            if ($this->fail()) {
                $this->message->error("Erro ao atualizar, verifique os dados");
                return null;
            }
        }

        /** Cria uma cidade */
        if (empty($this->id)) {
            $cityId = $this->create(self::$entity, $this->safe());
            if ($this->fail()) {
                $this->message->error("Erro ao cadastrar, verifique os dados");
                return null;
            }
        }

        $this->data = ($this->findById($cityId))->data();
        return $this;
    }

    /**
     * @return City|null
     */
    public function destroy(): ?City
    {
        if (!empty($this->id)) {
            $this->delete(self::$entity, "id = :id", "id={$this->id}");
        }

        if ($this->fail()) {
            $this->message = "Não foi possível remover a Cidade";
            return null;
        }

        $this->message = "Cidade removida com sucesso";
        $this->data = null;
        return $this;
    }
}