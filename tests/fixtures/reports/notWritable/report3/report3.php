<?php

/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>.
 */
/**
 * Description of report3.
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class report3 extends RAM\BaseReport
{
    public function install()
    {
    }

    public function render()
    {
        $this->addStyle('css/style.css');
    }

    public function uninstall()
    {
    }
}
