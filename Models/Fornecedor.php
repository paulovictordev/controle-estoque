<?php

namespace Project\Models;

use Project\Core\Model;

/**
 * Class Fornecedor
 * @package Project\Models
 */
class Fornecedor extends Model
{
    /** @var array $safe não pode atualizar ou criar */
    protected static $safe = ["id"];

    /** @var string $entity tabela no banco de dados */
    protected static $entity = "fornecedor";

    /** @var array $required campos da tabela */
    protected static $required = ["cidade_id", "fornecedor", "endereco", "cep", "cnpj", "inscricao_estadual", "telefone"];

    /**
     * @param string $cidade
     * @param string $uf
     * @return Fornecedor|null
     */
    public function bootstrap(
        int $cidadeId,
        string $fornecedor,
        string $endereco,
        string $cep,
        string $cnpj,
        string $inscricaoEstadual,
        string $telefone,
        string $numero = null,
        string $bairro = null
    ): ?Fornecedor
    {
        $this->cidade_id = $cidadeId;
        $this->fornecedor = $fornecedor;
        $this->endereco = $endereco;
        $this->cep = $cep;
        $this->cnpj = $cnpj;
        $this->inscricao_estadual = $inscricaoEstadual;
        $this->telefone = $telefone;
        $this->numero = $numero;
        $this->bairro = $bairro;
        return $this;
    }

    /**
     * @param string $terms
     * @param string $params
     * @param string $columns
     * @return Fornecedor|null
     */
    public function find(string $terms, string $params, string $columns = "*"): ?Fornecedor
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
     * @return Fornecedor|null
     */
    public function findById(int $id, string $columns = "*"): ?Fornecedor
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
     * @return Fornecedor|null
     */
    public function save(): ?Fornecedor
    {
        if (!$this->required()) {
            $this->message->warning("Alguns campos obrigatório não foram preenchidos");
            return null;
        }

        /** Atualiza a fornecedor */
        if (!empty($this->id)) {
            $fornecedorId = $this->id;

            $this->update(self::$entity, $this->safe(), "id = :id", "id={$fornecedorId}");
            if ($this->fail()) {
                $this->message->error("Erro ao atualizar, verifique os dados");
                return null;
            }
        }

        /** Cria uma fornecedor */
        if (empty($this->id)) {
            $fornecedorId = $this->create(self::$entity, $this->safe());
            if ($this->fail()) {
                $this->message->error("Erro ao cadastrar, verifique os dados");
                return null;
            }
        }

        $this->data = ($this->findById($fornecedorId))->data();
        return $this;
    }

    /**
     * @return Fornecedor|null
     */
    public function destroy(): ?Fornecedor
    {
        if (!empty($this->id)) {
            $this->delete(self::$entity, "id = :id", "id={$this->id}");
        }

        if ($this->fail()) {
            $this->message = "Não foi possível remover a Fornecedor";
            return null;
        }

        $this->message = "Fornecedor removida com sucesso";
        $this->data = null;
        return $this;
    }
}