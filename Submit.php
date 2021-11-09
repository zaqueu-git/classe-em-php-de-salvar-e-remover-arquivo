<?php
namespace application\libraries\File;

use application\libraries\File\Submit;

class Submit 
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
     * Instância o arquivo e define nome, entrada e caminho.
     * 
     * @param  bol $debugger - depurador.
     * @param  string $nameFile - nome do arquivo que vai salvar.
     * @param  string $nameInput - nome do campo de entrada.
     * @param  string $path - caminho do arquivo que vai salvar.
     */
    public function __construct($debugger, $nameFile, $nameInput, $path) 
    {
        $this->file = new File();
        $this->debugger = $debugger;
        $this->name($nameFile);
        $this->input($nameInput);
        $this->path($path);
    }
    
    /**
     * Método para mover (upload) o arquivo.
     *
     * @param  string $temp - arquivo temporário.
     * @param  string $path - caminho do arquivo.
     * @param  string $name - nome do arquivo.
     * @param  string $extension - entensão do arquivo.
     * @return bol
     */
    private function move($temp, $path, $name, $extension) 
    {
        if (move_uploaded_file($temp, "{$path}{$name}.{$extension}")) {
            $this->file->status = 1;
            return 1;
        }
        return 0;
    }
    
    /**
     * Método para salvar o arquivo.
     *
     * @return bol
     */
    public function saveFile() 
    {
        $save = 0;
        
        $checkName = $this->check($this->file->name);
        $checkTemp = $this->check($this->file->temp);
        $checkSize = $this->check($this->file->size);        
        $checkExtension = $this->check($this->file->extension);
        $checkPath = $this->check($this->file->path);

        if ($checkName && $checkTemp && $checkSize && $checkExtension && $checkPath) {
            $save = $this->move($this->file->temp, $this->file->path, $this->file->name, $this->file->extension);
        }

        $phases["name"] = [
            "name" => "Nome do arquivo",
            "value" => $checkName,
        ];
        $phases["temp"] = [
            "name" => "Arquivo temporário",
            "value" => $checkTemp,
        ];
        $phases["size"] = [
            "name" => "Tamanho do arquivo",
            "value" => $checkSize,
        ];  
        $phases["extension"] = [
            "name" => "Extensão do arquivo",
            "value" => $checkExtension,
        ];        
        $phases["path"] = [
            "name" => "Caminho do upload",
            "value" => $checkPath,
        ];
        $phases["save"] = [
            "name" => "Arquivo salvo",
            "value" => $save,
        ];
    
        $this->debuggerOutput($phases);
        return $save;
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
     * Método para verificar e definir o arquivo temporário.
     *
     * @param  mixed $temp - temporário
     * @return bol
     */
    private function temp($temp) 
    {
        if ($temp == "") {
            return 0;
        }
        $this->file->temp = $temp;   
        return 1;
    }
    
    /**
     * Método para verificar e definir a extensão do arquivo.
     *
     * @param  string $extension - extensão
     */
    private function extension($extension) 
    {
        $extension = str_replace("image/","", $extension);
        $extension = str_replace("document/","", $extension);

        switch ($extension) {
            case 'bmp':
            case 'png':
            case 'svg':
            case 'jpeg':
            case 'jpg':
                $this->file->extension = $extension;
                break;
            case 'doc':
            case 'docx':
            case 'dotx':
                $this->file->extension = $extension;                     
                break;
            case 'pdf':
                $this->file->extension = $extension;
                break;
            case 'xls':
            case 'xlsx':
                $this->file->extension = $extension;                     
                break;
            default:
                break;
        }
    }
    
    /**
     * Método para verificar e definir o tamanho do arquivo.
     *
     * @param  int $size - tamanho.
     * @return bol
     */
    private function size($size) 
    {
        if ($size > 0 && $size <= 1000000000) {
            $this->file->size = $size;
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
     * Método de saída do arquivo manipulado.
     *
     * @return obj
     */
    public function output() 
    {
        return $this->file;
    }
    
    /**
     * Método de entrada do campo com o arquivo enviado.
     *
     * @param  mixed $input - entrada.
     * @return bol
     */
    public function input($input) 
    {
        if (isset($_FILES[$input])) {
            if (is_uploaded_file($_FILES[$input]["tmp_name"])) {
                $this->temp($_FILES[$input]["tmp_name"]);
                $this->size($_FILES[$input]["size"]);
                $this->extension($_FILES[$input]["type"]);
                return 1;
            }
        }
        return 0;
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
