<?php
class Form
{
    private $message = "";
    private $error = "";
    public function __construct()
    {
        Transaction::open();
    }

    public function controller()
    {
        try {
            Transaction::get();
            $form = new Template("restrict/view/form.html");
            $form->set("id", "");
            $form->set("marca", "");
            $form->set("configuracao", "");
            $form->set("valor", "");
            $this->message = $form->saida();
        } catch (Exception $e) {
            $this->message = "Ocorreu um erro!" . $e->getMessage();
            $this->error = TRUE;
        }
    }

    public function editar()
    {
        if (isset($_GET["id"])) {
            try {
                $conexao = Transaction::get();
                $id = $conexao->quote($_GET["id"]);
                $crud = new Crud("computador");
                $comp = $crud->select("*", "id={$id}");
                if (!$crud->getError()) {
                    $form = new Template("restrict/view/form.html");
                    foreach ($comp[0] as $cod => $c) {
                        $form->set($cod, $c);
                    }
                    $this->message = $form->saida();
                } else {
                    $this->message = $crud->getMessage();
                    $this->error = TRUE;
                }
            } catch (Exception $e) {
                $this->message = "Ocorreu um erro!" . $e->getMessage();
                $this->error = TRUE;
                Transaction::rollback();
            }
        } else {
            $this->message = "Faltando parâmetro!";
            $this->error = TRUE;
        }
    }

    public function salvar()
    {
        if (isset($_POST["marca"]) && isset($_POST["configuracao"]) && isset($_POST["valor"])) {
            try {
                $conexao = Transaction::get();
                $crud = new Crud("computador");
                $marca = $conexao->quote($_POST["marca"]);
                $configuracao = $conexao->quote($_POST["configuracao"]);
                $valor = $conexao->quote($_POST["valor"]);
                if (empty($_POST["id"])) {
                    $crud->insert("marca, configuracao, valor", "{$marca}, {$configuracao}, {$valor}");
                } else {
                    $id = $conexao->quote($_POST["id"]);
                    $crud->update("marca={$marca}, configuracao={$configuracao}, valor={$valor}", "id={$id}");
                }
                $this->message = $crud->getMessage();
                $this->error = $crud->getError();
            } catch (Exception $e) {
                $this->message = "Ocorreu um erro!" . $e->getMessage();
                $this->error = TRUE;
            }
        } else {
            $this->message = "Preencha todos os campos!";
            $this->error = TRUE;
        }
    }

    public function getMessage()
    {
        if (is_string($this->error)) {
            return $this->message;
        } else {
            $msg = new Template("shared/view/msg.html");
            if ($this->error) {
                $msg->set("cor", "danger");
            } else {
                $msg->set("cor", "success");
            }
            $msg->set("msg", $this->message);
            $msg->set("uri", "restrita.php?class=Tabela");
            return $msg->saida();
        }
    }

    public function getError()
    {
        return $this->error;
    }

    public function __destruct()
    {
        Transaction::close();
    }
}
