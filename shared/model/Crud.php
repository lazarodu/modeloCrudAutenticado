<?php
class Crud
{
    private $message = "";
    private $error = false;
    private $tabela;
    public function __construct($tabela)
    {
        $this->tabela = $tabela;
    }
    public function select($campos = "*", $condicao = NULL)
    {
        try {
            $conexao = Transaction::get();
            if (!$condicao) {
                $sql = "SELECT {$campos} FROM {$this->tabela}";
            } else {
                $sql = "SELECT {$campos} FROM {$this->tabela} WHERE {$condicao}";
            }
            $resultado = $conexao->query($sql);
            if ($resultado->rowCount() > 0) {
                while ($dados = $resultado->fetch(PDO::FETCH_ASSOC)) {
                    $linhas[] = $dados;
                }
                $this->error = FALSE;
                return $linhas;
            } else {
                $this->message = "Nenhum registro encontrado!";
                $this->error = TRUE;
            }
        } catch (Exception $ex) {
            $this->message = "Ocorreu um erro! " . $ex->getMessage();
            $this->error = TRUE;
        }
    }

    public function insert($campos = NULL, $valores = NULL)
    {
        try {
            if ($campos && $valores) {
                $conexao = Transaction::get();
                $sql = "INSERT INTO {$this->tabela} ({$campos}) VALUES ({$valores}) ";
                $resultado = $conexao->query($sql);
                if ($resultado->rowCount() > 0) {
                    $this->message = "Inserido com sucesso!!!";
                    $this->error = FALSE;
                } else {
                    $this->error = TRUE;
                    $this->message = "Nenhum registro inserido!";
                }
            } else {
                $this->message = "Faltando parâmetro!";
                $this->error = TRUE;
            }
        } catch (Exception $ex) {
            $this->message = "Ocorreu um erro! " . $ex->getMessage();
            $this->error = TRUE;
        }
    }

    public function update($valores = NULL, $condicao = NULL)
    {
        try {
            if ($this->tabela && $valores && $condicao) {
                $conexao = Transaction::get();
                $sql = "UPDATE {$this->tabela} SET {$valores} WHERE {$condicao} ";
                $resultado = $conexao->query($sql);
                if ($resultado->rowCount() > 0) {
                    $this->message = "Atualizado com sucesso!!!";
                    $this->error = FALSE;
                } else {
                    $this->error = TRUE;
                    $this->message = "Nenhum registro atualizado!";
                }
            } else {
                $this->message = "Faltando parâmetro!";
                $this->error = TRUE;
            }
        } catch (Exception $ex) {
            $this->message = "Ocorreu um erro! " . $ex->getMessage();
            $this->error = TRUE;
        }
    }

    public function delete($condicao = NULL)
    {
        try {
            if ($condicao) {
                $conexao = Transaction::get();
                $sql = "DELETE FROM {$this->tabela} WHERE {$condicao} ";
                $resultado = $conexao->query($sql);
                if ($resultado->rowCount() > 0) {
                    $this->message = "Apagado com sucesso!!!";
                    $this->error = FALSE;
                } else {
                    $this->error = TRUE;
                    $this->message = "Nenhum registro apagado!";
                }
            } else {
                $this->message = "Faltando parâmetro!";
                $this->error = TRUE;
            }
        } catch (Exception $ex) {
            $this->error = TRUE;
            $this->message = "Ocorreu um erro! " . $ex->getMessage();
        }
    }
    public function getMessage()
    {
        return $this->message;
    }
    public function getError()
    {
        return $this->error;
    }
}
