<?php

namespace RAM\Services;

class SpellingService
{
    public function check($words = [])
    {
        $misspelled = [];
        $number = mt_rand(0, count($words));
        $selected_words = array_rand($words, $number);

        foreach ($selected_words as $word) {
            $misspelled[] = $words[$word];
        }

        return $misspelled;
    }
}