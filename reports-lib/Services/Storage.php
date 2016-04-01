<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RAM\Services;
use RG\Interfaces\StorageInterface;

/**
 * Description of Storage
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class Storage implements StorageInterface
{
    protected $storagePath;

    public function __construct($path)
    {
        $this->storagePath = getcwd().'/../'.$path;
    }

    /**
     * @param mixed $key
     * @param mixed $value
     *
     * @return bool
     */
    final public function save($key, $value)
    {
        $hash = $this->generateHash($key);
        $data = $this->generateData($key, $value);
        $file = $this->storagePath.'/'.$hash.'.json';
        if (is_writable($this->storagePath)) {
            $fp = fopen($file, 'wb');
            fwrite($fp, $data);
            fclose($fp);
            return true;
        }
        return false;
    }

    /**
     * @param string $key
     *
     * @return null
     */
    final public function find($key, $array = false)
    {
        $hash = $this->generateHash($key);
        $file = $this->storagePath.'/'.$hash.'.json';
        if (file_exists($file)) {
            $content = file_get_contents($file);
            try {
                $content = json_decode($content);
                return $array ? json_decode(json_encode($content->value), true): $content->value;
            } catch (\Exception $ex) {
            }
        }
        return null;
    }

    /**
     * @param mixed $key
     */
    final public function delete($key)
    {
        $hash = $this->generateHash($key);
        $file = $this->storagePath.'/'.$hash.'.json';
        @unlink($file);
    }

    /**
     * @param mixed $key
     * @return string
     */
    protected function generateHash($key)
    {
        return md5(serialize($key));
    }

    /**
     * @param mixed $key
     * @param mixed $value
     *
     * @return string
     */
    protected function generateData($key, $value)
    {
        $data = [
            'key' => $key,
            'value' => $value
        ];
        return json_encode($data);
    }
}