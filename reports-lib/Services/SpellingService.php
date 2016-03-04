<?php

namespace RAM\Services;

class SpellingService
{
    public function check($words = [])
    {
        $misspelled = [];
        $number = mt_rand(0, count($words));
        if ($number) {
            $selected_words = array_rand($words, $number);

            if (count($selected_words) == 1) {
                $misspelled[0] = $words[$selected_words];

            } else {
                foreach ($selected_words as $word) {
                    $misspelled[] = $words[$word];
                }
            }
        }


        return $misspelled;
    }
}