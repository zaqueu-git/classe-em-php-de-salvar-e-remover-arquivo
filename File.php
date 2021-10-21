<?php
/**
 * @package File
 * @link    ''
 * @author  Zaqueu Alves <zaqueu.alves01@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0.html
 * @version 1.0
 */
namespace application\libraries\File;

class File 
{    
    /**
     * Atributo para referenciar o status.
     *
     * @var bol
     */
    public $status;    
    /**
     * Atributo para referenciar o nome.
     *
     * @var string
     */
    public $name;    
    /**
     * Atributo para referenciar o tipo.
     *
     * @var string
     */    
    public $type;    
    /**
     * Atributo para referenciar o caminho.
     *
     * @var string
     */
    public $path;    
    /**
     * Atributo para referenciar o arquivo temporário.
     *
     * @var string
     */
    public $temp;    
    /**
     * Atributo para referenciar o tamanho.
     *
     * @var string
     */
    public $size;    
    /**
     * Atributo para referenciar a extensão.
     *
     * @var string
     */
    public $extension;
}
?>