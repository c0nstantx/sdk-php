<?php

/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>.
 */
/**
 * Description of report1.
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class report1 extends RAM\BaseReport
{
    public function install()
    {
    }

    public function render()
    {
        $this->addStyle('css/style.css');
        return 'content';
    }

    public function uninstall()
    {
    }
}
