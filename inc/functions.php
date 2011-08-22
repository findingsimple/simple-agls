<?php

// This should work with Greek, Russian, Polish & French amongst other languages...
function wpagls_strtolower_utf8($string){ 
	$convert_to = array( 
	  "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", 
	  "v", "w", "x", "y", "z", "à", "á", "â", "ã", "ä", "å", "æ", "ç", "è", "é", "ê", "ë", "ì", "í", "î", "ï", 
	  "ð", "ñ", "ò", "ó", "ô", "õ", "ö", "ø", "ù", "ú", "û", "ü", "ý", "а", "б", "в", "г", "д", "е", "ё", "ж", 
	  "з", "и", "й", "к", "л", "м", "н", "о", "п", "р", "с", "т", "у", "ф", "х", "ц", "ч", "ш", "щ", "ъ", "ы", 
	  "ь", "э", "ю", "я", "ą", "ć", "ę", "ł", "ń", "ó", "ś", "ź", "ż" 
	); 
	$convert_from = array( 
	  "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", 
	  "V", "W", "X", "Y", "Z", "À", "Á", "Â", "Ã", "Ä", "Å", "Æ", "Ç", "È", "É", "Ê", "Ë", "Ì", "Í", "Î", "Ï", 
	  "Ð", "Ñ", "Ò", "Ó", "Ô", "Õ", "Ö", "Ø", "Ù", "Ú", "Û", "Ü", "Ý", "А", "Б", "В", "Г", "Д", "Е", "Ё", "Ж", 
	  "З", "И", "Й", "К", "Л", "М", "Н", "О", "П", "Р", "С", "Т", "У", "Ф", "Х", "Ц", "Ч", "Ш", "Щ", "Ъ", "Ъ", 
	  "Ь", "Э", "Ю", "Я", "Ą", "Ć", "Ę", "Ł", "Ń", "Ó", "Ś", "Ź", "Ż"
	); 

	return str_replace($convert_from, $convert_to, $string);
}


function wpagls_strip_shortcode( $text ) {
	return preg_replace( '|\[(.+?)\](.+?\[/\\1\])?|s', '', $text );
}

function wpagls_limit_words( $text, $limit = 30 ) {
	$explode = explode(' ',$text);
    $string  = '';	
	$i = 0;
    while ( $limit > $i ) {
        $string .= $explode[$i]." ";
		$i++;
    }
    return $string;
}

function wpagls_get_terms($id, $taxonomy) {
	// If we're on a specific tag, category or taxonomy page, return that and bail.
	if ( is_category() || is_tag() || is_tax() ) {
		global $wp_query;
		$term = $wp_query->get_queried_object();
		return $term->name;
	}
	
	$output = '';
	$terms = get_the_terms($id, $taxonomy);
	if ( $terms ) {
		foreach ($terms as $term) {
			$output .= $term->name.', ';
		}
		return rtrim( trim($output), ',' );
	}
	return '';
}


?>