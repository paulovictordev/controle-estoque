<?php

namespace Project\Models;

use Project\Core\Model;

/**
 * Class Usuario
 * @package Project\Models
 */
class Usuario extends Model
{
    /** @var array $safe não pode atualizar ou criar */
    protected static $safe = ["id"];

    /** @var string $entity tabela no banco de dados */
    protected static $entity = "usuario";

    /** @var array $required campos da tabela */
    protected static $required = ["nome", "email", "senha"];

    /**
     * @param string $nome
     * @param string $email
     * @param string $senha
     * @return Usuario|null
     */
    public function bootstrap(string $nome, string $email, string $senha): ?Usuario
    {
        $this->nome = $nome;
        $this->email = $email;
        $this->senha = $senha;
        return $this;
    }

    /**
     * @param string $terms
     * @param string $params
     * @param string $columns
     * @return Usuario|null
     */
    public function find(string $terms, string $params, string $columns = "*"): ?Usuario
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
     * @return Usuario|null
     */
    public function findById(int $id, string $columns = "*"): ?Usuario
    {
        return $this->find("id = :id", "id={$id}", $columns);
    }

    /**
     * @param $email
     * @param string $columns
     * @return Usuario|null
     */
    public function findByEmail($email, string $columns = "*"): ?Usuario
    {
        return $this->find("email = :email", "email={$email}", $columns);
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
     * @return Usuario|null
     */
    public function save(): ?Usuario
    {
        if (!$this->required()) {
            $this->message->warning("Nome, email e senha são obrigatórios");
            return null;
        }

        if (!is_email($this->email)) {
            $this->message->warning("O e-mail informado não tem um formato válido");
            return null;
        }

        if (!is_passwd($this->senha)) {
            $min = CONF_PASSWD_MIN_LEN;
            $max = CONF_PASSWD_MAX_LEN;
            $this->message->warning("A senha deve ter entre {$min} e {$max} caracteres");
            return null;
        } else {
            $this->senha = passwd($this->senha);
        }

        /** Atualiza o usuário */
        if (!empty($this->id)) {
            $usuarioId = $this->id;

            if ($this->find("email = :e AND id != :i", "e={$this->email}&i={$usuarioId}")) {
                $this->message->warning("O e-mail informado já está cadastrado");
                return null;
            }

            $this->update(self::$entity, $this->safe(), "id = :id", "id={$usuarioId}");
            if ($this->fail()) {
                $this->message->error("Erro ao atualizar, verifique os dados");
                return null;
            }
        }

        /** Cria um usuário */
        if (empty($this->id)) {
            if ($this->findByEmail($this->email)) {
                $this->message->warning("O e-mail informado já está cadastrado");
                return null;
            }

            $usuarioId = $this->create(self::$entity, $this->safe());
            if ($this->fail()) {
                $this->message->error("Erro ao cadastrar, verifique os dados");
                return null;
            }
        }

        $this->data = ($this->findById($usuarioId))->data();
        return $this;
    }

    /**
     * @return Usuario|null
     */
    public function destroy(): ?Usuario
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