<?php namespace Pixel\Shop\Classes;

use Html;
use Yaml;

class WordsSeo{
	public static function lists($text, $limit = null, $count = false){
		$string = Html::strip($text);
		$stop_words = Yaml::parseFile('plugins/pixel/shop/stopwords.yaml');

		$string = trim($string);
		$string = strtolower($string);
		$string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);

		preg_match_all('/\b.*?\b/i', $string, $match_words);

		$match_words = $match_words[0];

		foreach ( $match_words as $key => $item )
			if ( $item == '' || in_array(strtolower($item), $stop_words) || strlen($item) <= 3 )
				unset($match_words[$key]);

		$word_count = str_word_count( implode(" ", $match_words) , 1); 
		$frequency = array_count_values($word_count);

		arsort($frequency);

		if($limit)
			$frequency = array_slice($frequency, 0, $limit);

		if(!$count)
			$frequency = array_keys($frequency);

		return $frequency;
	}
}
?>