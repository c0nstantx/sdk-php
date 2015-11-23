<?php
include 'model/TwitterParser.php';

class twitter_report extends RAM\BaseReport
{
    public function render()
    {
        $this->addStyle('css/bootstrap.min.css');
        $this->addStyle('css/style.css');
        $this->addScript('scripts/script.js');
        $twitterConnector = $this->getConnector('twitter');
        $parser = new TwitterParser($twitterConnector);
        $data = $parser->getHandleData();
        return $this->renderEngine->render('views/report.html', array('data'=>$data));
    }
}