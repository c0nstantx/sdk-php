<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RG;

/**
 * Description of Storage
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class Storage
{
    protected $storagePath;

    public function __construct($path)
    {
        $this->storagePath = getcwd().'/../'.$path;
    }

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

    final public function find($key)
    {
        $hash = $this->generateHash($key);
        $file = $this->storagePath.'/'.$hash.'.json';
        if (file_exists($file)) {
            $content = file_get_contents($file);
            try {
                $content = json_decode($content);
                return $content->value;
            } catch (\Exception $ex) {
            }
        }
        return null;
    }

    final public function delete($key)
    {
        $hash = $this->generateHash($key);
        $file = $this->storagePath.'/'.$hash.'.json';
        @unlink($file);
    }

    protected function generateHash($key)
    {
        return md5(serialize($key));
    }

    protected function generateData($key, $value)
    {
        $data = [
            'key' => $key,
            'value' => $value
        ];
        return json_encode($data);
    }

}