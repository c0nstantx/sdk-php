<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RG\Composer;

use Composer\Script\Event;
/**
 * Description of ScriptHandler
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class ScriptHandler
{
    public static $neededFolders = [
        'src',
        'web/assets',
        'storage',
        'proxy'
    ];

    /**
     * Create all needed folders
     *
     * @param Event $event
     */
    public static function createFolders(Event $event)
    {
        foreach(self::$neededFolders as $folder) {
            if (!is_dir($folder)) {
                umask(0);
                mkdir($folder, 0777, true);
            }
        }
    }
}