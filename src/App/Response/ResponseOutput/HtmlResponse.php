<?php
declare(strict_types = 1);

namespace App\Response\ResponseOutput;

use App\Response\AbstractOutput;

class HtmlResponse extends AbstractOutput
{
    const LAYOUTS_FOLDER = 'template/';
    const VIEWS_FOLDER = 'template/';

    const DEFAULT_LAYOUT = 'layout/default';
    const EMPTY_LAYOUT = '_empty_';
    const EXT = '.php';

    const GZIP_OUTPUT = false;

    /**
     * @var string
     */
    private $layout;

    /**
     * @var string
     */
    private $view;

    /**
     * HtmlResponse constructor.
     * @param string $view
     * @param string|null $layout
     */
    public function __construct(string $view, ?string $layout = null)
    {
        $this->setView($view, $layout);
    }

    /**
     * @param string $name
     * @return HtmlResponse
     */
    public function setLayout(string $name) : HtmlResponse
    {
        $this->layout = $name;
        return $this;
    }

    /**
     * @param string $name
     * @param string|null $layout
     * @return HtmlResponse
     */
    public function setView(string $name, ?string $layout = null) : HtmlResponse
    {
        $this->view = $name;
        $this->layout = $layout ?? self::DEFAULT_LAYOUT;
        return $this;
    }

    /**
     * @return string
     */
    public function printPage() : string
    {
        $layoutFile = self::LAYOUTS_FOLDER . $this->layout . self::EXT;
        $viewFile = self::VIEWS_FOLDER . $this->view . self::EXT;

        extract($this->vars, EXTR_OVERWRITE);

        ob_start();
        if (is_file($viewFile)) {
            include $viewFile;
        } else {
            throw new \InvalidArgumentException('View file not found ' . $viewFile);
        }
        $content = ob_get_clean();

        if ($this->layout == self::EMPTY_LAYOUT) {
            return $content;
        }

        if (self::GZIP_OUTPUT) {
            ob_start("ob_gzhandler") or ob_start();
            header("Content-length: " . ob_get_length());
        } else {
            ob_start();
        }

        if (is_file($layoutFile)) {
            include $layoutFile;
        } else {
            throw new \InvalidArgumentException('Layout file not found ' . $layoutFile);
        }

        return ob_get_clean();
    }
}
