<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RG;

/**
 * Description of Sentiment
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class Sentiment
{
    public function calculate($phrase)
    {
        return mt_rand(-101, 100) / 100;
    }
}