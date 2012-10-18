<?php
/**
 * This file is part of the BEAR.Sunday package
 *
 * @package BEAR.Sunday
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace BEAR\Sunday\Module\TemplateEngine\SmartyModule;

use Smarty;
use BEAR\Sunday\Resource\View\TemplateEngineAdapter;
use BEAR\Sunday\Exception\TemplateNotFound;
use Ray\Di\Di\Inject;
use Ray\Di\Di\Named;
use Ray\Di\Di\PostConstruct;

/**
 * Smarty adapter
 *
 * @package    BEAR.Sunday
 * @subpackage Module
 */
class SmartyAdapter implements TemplateEngineAdapter
{
    /**
     * smarty
     *
     * @var Smarty
     */
    private $smarty;

    /**
     * Template file
     *
     * @var string
     */
    private $template;

    /**
     * File extension
     *
     * @var string
     */
    const EXT = 'tpl';

    /**
     * Constructor
     *
     * Smarty $smarty
     *
     * @Inject
     */
    public function __construct(Smarty $smarty)
    {
        $this->smarty = $smarty;
    }

    /**
     * Init
     *
     * @PostConstruct
     */
    public function init()
    {
        $this->smarty->force_compile = false;
        $this->smarty->compile_check = false;
//        $this->smarty->compile_id = PHP_SAPI . 'a';
    }

    /**
     * (non-PHPdoc)
     * @see BEAR\Sunday\Resource\View.TemplateEngineAdapter::assign()
     */
    public function assign($tplVar, $value)
    {
        $this->smarty->assign($tplVar, $value);
    }

    /**
     * (non-PHPdoc)
     * @see BEAR\Sunday\Resource\View.TemplateEngineAdapter::assignAll()
     */
    public function assignAll(array $values)
    {
        $this->smarty->assign($values);
    }

    /**
     * (non-PHPdoc)
     * @see BEAR\Sunday\View.Render::fetch()
     */
    public function fetch($tplWithoutExtension)
    {
        $this->template = $tplWithoutExtension . self::EXT;
        $this->fileExists($this->template);

        return $this->smarty->fetch($this->template);
    }

    /**
     * Return file exists
     *
     * @param string $template
     *
     * @throws TemplateNotFound
     */
    private function fileExists($template)
    {
        if (!file_exists($template)) {
            throw new TemplateNotFound($template);
        }
    }

    /**
     * Return template full path.
     *
     * @return string
     */
    public function getTemplateFile()
    {
        return $this->template;
    }
}
