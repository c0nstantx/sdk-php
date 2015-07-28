<?php
class TwitterParser
{
    protected $connector;

    public function __construct($connector)
    {
        $this->connector = $connector;
    }

    /**
     * Get the wanted data from user handle *
     * @return array
     */
    public function getHandleData()
    {
        $profile = $this->getProfile();
        $tweets = $this->getTweets($profile->screen_name);
        return $this->processTweets($tweets);
    }
    /**
     * Process tweets and get the desired data *
     * @param mixed $tweets
     * @return array
     */
    protected function processTweets($tweets)
    {
        $topRetweets = array();
        foreach ($tweets as $tweet) {
            if (isset($tweet->retweeted_status)) {
                /* Map top retweets */
                $topRetweets[$tweet->retweeted_status->retweet_count] = $tweet;
            }
        }

        /* Extract top retweets list and limit 3 top */
        ksort($topRetweets, SORT_NUMERIC);
        $topRetweets = array_slice(array_reverse($topRetweets), 0, 3);
        return [
            'top_retweets' => $topRetweets,
        ];
    }

    /**
     * Get latest tweets from handle *
     * @param string $handle
     * @return mixed
     */
    protected function getTweets($handle)
    {
        return $this->connector->get("statuses/user_timeline", array('screen_name'=>$handle));
    }

    /**
     * Get user profile info *
     * @return mixed
     */
    protected function getProfile()
    {
        return $this->connector->get('account/verify_credentials');
    }
}