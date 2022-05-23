<?php
class Inicio
{
    private $message = "";
    private $error = false;
    public function controller()
    {
        $inicio = new Template("restrict/view/inicio.html");
        $inicio->set("inicio", "Área restrita!!!");
        $this->message = $inicio->saida();
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
