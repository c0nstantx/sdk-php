<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */
 
namespace RG;

/**
 * Description of Tokenizer
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class Tokenizer
{
    protected $tokens;

    protected $class;

    protected $isExtended = false;

    protected $baseClass;

    protected $methods = [];

    public function __construct($classFile)
    {
        $this->tokens = self::tokenizeFile($classFile);
        $this->scrapeTokens();
    }

    public function isExtended()
    {
        return $this->isExtended;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function getBaseClass()
    {
        return $this->baseClass;
    }

    public function getMethods()
    {
        return $this->methods;
    }

    public function getTokens()
    {
        return $this->tokens;
    }

    /**
     * Tokenize a PHP file
     *
     * @param string $file
     * @return array|null
     */
    public static function tokenizeFile($file)
    {
        if (file_exists($file)) {
            return self::tokenize(file_get_contents($file));
        }
        return null;
    }

    /**
     * Tokenize PHP code (including tags)
     *
     * @param string $code
     * @return array
     */
    public static function tokenize($code)
    {
        return token_get_all($code);
    }

    /**
     * Scrape all wanted tokens
     */
    protected function scrapeTokens()
    {
        if (null !== $this->tokens) {
            for ($i=0; $i<count($this->tokens); $i++) {
                if (!is_string($this->tokens[$i])) {
                    list($id, $content) = $this->tokens[$i];

                    switch($id) {
                        case T_CLASS:
                            $this->class = $this->getClassName($i+2);
                            break;
                        case T_EXTENDS:
                            $this->baseClass = $this->getClassName($i+2);
                            $this->isExtended = true;
                            break;
                        case T_FUNCTION:
                            $this->methods[] = $this->tokens[$i+2][1];
                            break;
                        default:
                            break;
                    }
                }
            }
        }
    }

    /**
     * Get full class name including any namespace
     *
     * @param int $tokenIndex
     * @return string
     */
    protected function getClassName($tokenIndex)
    {
        $i = $tokenIndex;
        $className = '';
        $token = $this->tokens[$i];
        while(!is_string($token) && ($token[0] === T_STRING || $token[0] === T_NS_SEPARATOR)) {
            $className .= $token[1];
            $i++;
            $token = $this->tokens[$i];
        }
        return $className;
    }

}