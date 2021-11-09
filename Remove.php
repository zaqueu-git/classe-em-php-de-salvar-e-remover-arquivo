<?php
namespace application\libraries\File;

use application\libraries\File\Submit;

class Remove 
{
    /**
     * Atributo para referenciar o depurador.
     *
     * @var bol
     */
    private $debugger;    
    /**
     * Atributo para referenciar o caminho raiz.
     *
     * @var string
     */
    private $rootPath;    
    /**
     * Atributo para referenciar o arquivo.
     *
     * @var string
     */
    private $file;

    /**
     * Classe construtora.
     * Instância o arquivo e define o caminho raiz do sistema.
     * 
     * @param  bol $debugger - depurador.
     * @param  string $nameFile - nome do arquivo que vai salvar.
     * @param  string $path - caminho do arquivo que vai salvar.
     */     
    public function __construct($debugger, $nameFile, $path) 
    {
        $this->file = new File();
        $this->debugger = $debugger;
        $this->name($nameFile);
        $this->path($path);        
    }
    
    /**
     * Método para deletar (unlink) o arquivo.
     *
     * @param  string $path - caminho.
     * @param  string $name - nome.
     * @return bol
     */
    private function delete($path, $name) 
    {
        if (unlink("{$path}{$name}")) {
            return 1;
        }
        return 0;
    }

    /**
     * Método para remover o arquivo.
     *
     * @return bol
     */    
    public function execute() 
    {
        $remove = 0;

        $name = $this->check($this->file->name);
        $path = $this->check($this->file->path);
        $file = $this->isFile();

        if ($name && $path && $file) {
            $remove = $this->delete($this->file->path, $this->file->name);
        }

        $phases["name"] = [
            "name" => "Nome do file",
            "value" => $name,
        ];
        $phases["path"] = [
            "name" => "Caminho",
            "value" => $path,
        ];
        $phases["file"] = [
            "name" => "Arquivo existe",
            "value" => $file,
        ];        
        $phases["remove"] = [
            "name" => "Arquivo removido",
            "value" => $remove,
        ];
    
        $this->debuggerOutput($phases);
        return $remove;
    }

    /**
     * Método para auxiliar as verificações de dado boleano.
     *
     * @param  bol $check - checar.
     * @return bol
     */   
    private function check($check) 
    {
        if ($check) {
            return 1;
        }
        return 0;
    }
    
    /**
     * Método para verificar o caminho do arquivo.
     *
     * @return bol
     */
    public function isFile() 
    { 
        if (is_file($this->file->path . $this->file->name)) {
            return 1;
        }
        return 0;
    }
    
    /**
     * Método para verificar e definir o caminho do arquivo.
     *
     * @param  string $path - caminho.
     * @return bol
     */
    public function path($path) 
    { 
        if ($path == "") {
            return 0;
        }
        if (is_dir($_SERVER["DOCUMENT_ROOT"] . $path)) {
            $this->file->path = $_SERVER["DOCUMENT_ROOT"] . $path;
            return 1;
        }
        return 0;
    }

    /**
     * Método para verificar e definir o nome do arquivo.
     *
     * @param  string $name - nome.
     * @return bol
     */    
    public function name($name) 
    {
        if ($name == "") {
            return 0;
        }
        $this->file->name = $name;
        return 1;
    }

    /**
     * Método para mostrar as mensagens do depurador.
     *
     * @param  array $phases - fases.
     * @return html
     */    
    private function debuggerOutput($phases) 
    {
        if ($this->debugger) {
            echo "<div style='display: flex; flex-direction: column; align-items: flex-start; width: 320px; padding: 20px; margin: 20px; background: #eeeeee; border: 1px solid #9e9e9e;'>";
            foreach ($phases as $key => $value) {

                $object = (object) $value;
                if ($object->value) {
                    echo "<div>{$object->name} :: <span style='color: #4caf50; font-weight: 800;'>OK</span></div>";
                    continue;                    
                }

                echo "<div>{$object->name} :: <span style='color: #ff0000; font-weight: 800;'>ERRO</span></div>";
            }
            echo "</div>";            
        }
    }
}
?>
