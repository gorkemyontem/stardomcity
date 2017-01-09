<?php
/*
Plugin Name: Non ASCII Attachment URL
Description: convert attachment url non ASCII.
Version:     1.0
Author:      Gorkem Yontem
Author URI:  http://www.gorkemyontem.com
Created:     14.01.20107
License:     GPL

 * Non ASCII Attachment URL, Copyright (C) 2017 Gorkem Yontem *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

if ( is_admin() || ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) )
{
	remove_filter( 'sanitize_title', 'sanitize_title_with_dashes', 11 );

	add_filter( 'sanitize_title', array ( 'Non_Ascii_Url', 'sanitize_title_filter' ), 10, 2 );

	add_filter( 'sanitize_file_name', array ( 'Non_Ascii_Url', 'sanitize_filename_filter' ),  10, 1 );

	add_filter( 'http_request_args', array ( 'Non_Ascii_Url', 'no_upgrade_check' ), 5, 2 );
}

class Non_Ascii_Url
{

	/**
	 * Fixes URI slugs.
	 *
	 * If you don't have any latin characters in your title you may end up
	 * with an empty title. WordPress will use the post ID then.
	 *
	 * @param  string $title
	 * @param  string $raw_title
	 * @return string
	 */
	static function sanitize_title_filter( $title, $raw_title = NULL )
	{
		! is_null( $raw_title ) and $title = $raw_title;

		$title = self::sanitize_filename_filter( $title );
		$title = str_replace( '.', '-', $title );
		// Avoid double minus. WordPress cannot resolve such URLs.
		$title = preg_replace( '~--+~', '-', $title );
		// For %postname%-%post_id% permalinks.
		return rtrim( $title, '-' );
	}
	/**
	 * Fixes names of uploaded files.
	 * »häßliches bild.jpg« => haessliches-bild.jpg
	 *
	 * @param  string $filename
	 * @return string
	 */
	static function sanitize_filename_filter( $filename )
	{
		// Windows LiveWriter sends escaped strings.
		$filename = html_entity_decode( $filename, ENT_QUOTES, 'utf-8' );
		$filename = self::translit($filename);
		$filename = self::lower_ascii($filename);
		$filename = self::remove_doubles($filename);
		return $filename;
	}

	/**
	 * Reduces repeated meta characters (-=+.) to one.
	 *
	 * @param  string $str Input string
	 * @return string
	 */
	static function remove_doubles( $str )
	{
		return preg_replace( '~([=+.-])\\1+~', "\\1", $str );
	}

	/**
	 * Converts uppercase characters to lowercase and removes the rest.
	 *
	 * @uses   apply_filters( 'germanix_lower_ascii_regex' )
	 * @param  string $str Input string
	 * @return string
	 */
	static function lower_ascii( $str )
	{
		// Leave underscores, otherwise the taxonomy tag cloud in the
		// backend won’t work anymore.
		$str   = strtolower( $str );
		return preg_replace( '~([^a-z\d_.-])~', '', $str );
	}

	static function translit( $str )
	{
		$utf8 = array (
				'Ä' => 'A'
			,	'ä' => 'a'
			,	'Æ' => 'Ae'
			,	'æ' => 'ae'
			,	'À' => 'A'
			,	'à' => 'a'
			,	'Á' => 'A'
			,	'á' => 'a'
			,	'Â' => 'A'
			,	'â' => 'a'
			,	'Ã' => 'A'
			,	'ã' => 'a'
			,	'Å' => 'A'
			,	'å' => 'a'
			,	'ª' => 'a'
			,	'ₐ' => 'a'
			,	'ā' => 'a'
			,	'Ć' => 'C'
			,	'ć' => 'c'
			,	'Ç' => 'C'
			,	'ç' => 'c'
			,	'Ð' => 'D'
			,	'đ' => 'd'
			,	'È' => 'E'
			,	'è' => 'e'
			,	'É' => 'E'
			,	'é' => 'e'
			,	'Ê' => 'E'
			,	'ê' => 'e'
			,	'Ë' => 'E'
			,	'ë' => 'e'
			,	'ₑ' => 'e'
			,	'ƒ' => 'f'
			,	'ğ' => 'g'
			,	'Ğ' => 'G'
			,	'Ì' => 'I'
			,	'ì' => 'i'
			,	'Í' => 'I'
			,	'í' => 'i'
			,	'Î' => 'I'
			,	'î' => 'i'
			,	'Ï' => 'I'
			,	'ï' => 'ii'
			,	'ī' => 'i'
			,	'ı' => 'i'
			,	'I' => 'I' // turkish, correct?
			,	'İ' => 'I'
			,	'Ñ' => 'N'
			,	'ñ' => 'n'
			,	'ⁿ' => 'n'
			,	'Ò' => 'O'
			,	'ò' => 'o'
			,	'Ó' => 'O'
			,	'ó' => 'o'
			,	'Ô' => 'O'
			,	'ô' => 'o'
			,	'Õ' => 'O'
			,	'õ' => 'o'
			,	'Ø' => 'O'
			,	'ø' => 'o'
			,	'ₒ' => 'o'
			,	'Ö' => 'O'
			,	'ö' => 'o'
			,	'Œ' => 'Oe'
			,	'œ' => 'oe'
			,	'ß' => 'ss'
			,	'Š' => 'S'
			,	'š' => 's'
			,	'ş' => 's'
			,	'Ş' => 'S'
			,	'™' => 'TM'
			,	'Ù' => 'U'
			,	'ù' => 'u'
			,	'Ú' => 'U'
			,	'ú' => 'u'
			,	'Û' => 'U'
			,	'û' => 'u'
			,	'Ü' => 'U'
			,	'ü' => 'u'
			,	'Ý' => 'Y'
			,	'ý' => 'y'
			,	'ÿ' => 'y'
			,	'Ž' => 'Z'
			,	'ž' => 'z'
			// misc
			,	'¢' => 'Cent'
			,	'€' => 'Euro'
			,	'‰' => 'promille'
			,	'№' => 'Nr'
			,	'$' => 'Dollar'
			,	'℃' => 'Grad Celsius'
			,	'°C' => 'Grad Celsius'
			,	'℉' => 'Grad Fahrenheit'
			,	'°F' => 'Grad Fahrenheit'
			// Superscripts
			,	'⁰' => '0'
			,	'¹' => '1'
			,	'²' => '2'
			,	'³' => '3'
			,	'⁴' => '4'
			,	'⁵' => '5'
			,	'⁶' => '6'
			,	'⁷' => '7'
			,	'⁸' => '8'
			,	'⁹' => '9'
			// Subscripts
			,	'₀' => '0'
			,	'₁' => '1'
			,	'₂' => '2'
			,	'₃' => '3'
			,	'₄' => '4'
			,	'₅' => '5'
			,	'₆' => '6'
			,	'₇' => '7'
			,	'₈' => '8'
			,	'₉' => '9'
			// Operators, punctuation
			,	'±' => 'plusminus'
			,	'×' => 'x'
			,	'₊' => 'plus'
			,	'₌' => '='
			,	'⁼' => '='
			,	'⁻' => '-'    // sup minus
			,	'₋' => '-'    // sub minus
			,	'–' => '-'    // ndash
			,	'—' => '-'    // mdash
			,	'‑' => '-'    // non breaking hyphen
			,	'․' => '.'    // one dot leader
			,	'‥' => '..'  // two dot leader
			,	'…' => '...'  // ellipsis
			,	'‧' => '.'    // hyphenation point
			,	' ' => '-'   // nobreak space
			,	' ' => '-'   // normal space
			// Russian
			,	'А' => 'A'
			,	'Б' => 'B'
			,	'В' => 'V'
			,	'Г' => 'G'
			,	'Д' => 'D'
			,	'Е' => 'E'
			,	'Ё' => 'YO'
			,	'Ж' => 'ZH'
			,	'З' => 'Z'
			,	'И' => 'I'
			,	'Й' => 'Y'
			,	'К' => 'K'
			,	'Л' => 'L'
			,	'М' => 'M'
			,	'Н' => 'N'
			,	'О' => 'O'
			,	'П' => 'P'
			,	'Р' => 'R'
			,	'С' => 'S'
			,	'Т' => 'T'
			,	'У' => 'U'
			,	'Ф' => 'F'
			,	'Х' => 'H'
			,	'Ц' => 'TS'
			,	'Ч' => 'CH'
			,	'Ш' => 'SH'
			,	'Щ' => 'SCH'
			,	'Ъ' => ''
			,	'Ы' => 'YI'
			,	'Ь' => ''
			,	'Э' => 'E'
			,	'Ю' => 'YU'
			,	'Я' => 'YA'
			,	'а' => 'a'
			,	'б' => 'b'
			,	'в' => 'v'
			,	'г' => 'g'
			,	'д' => 'd'
			,	'е' => 'e'
			,	'ё' => 'yo'
			,	'ж' => 'zh'
			,	'з' => 'z'
			,	'и' => 'i'
			,	'й' => 'y'
			,	'к' => 'k'
			,	'л' => 'l'
			,	'м' => 'm'
			,	'н' => 'n'
			,	'о' => 'o'
			,	'п' => 'p'
			,	'р' => 'r'
			,	'с' => 's'
			,	'т' => 't'
			,	'у' => 'u'
			,	'ф' => 'f'
			,	'х' => 'h'
			,	'ц' => 'ts'
			,	'ч' => 'ch'
			,	'ш' => 'sh'
			,	'щ' => 'sch'
			,	'ъ' => ''
			,	'ы' => 'yi'
			,	'ь' => ''
			,	'э' => 'e'
			,	'ю' => 'yu'
			,	'я' => 'ya'
		);

		$str = strtr( $str, $utf8 );
		return trim( $str, '-' );
	}

	/**
	 * Blocks update checks for this plugin.
	 *
	 * @author Mark Jaquith http://markjaquith.wordpress.com
	 * @link   http://wp.me/p56-65
	 * @param  array $r
	 * @param  string $url
	 * @return array
	 */
	static function no_upgrade_check( $r, $url )
	{
		if ( 0 !== strpos($url,	'http://api.wordpress.org/plugins/update-check'))
		{ // Not a plugin update request. Bail immediately.
			return $r;
		}

		$plugins = unserialize( $r['body']['plugins'] );
		$p_base  = plugin_basename( __FILE__ );

		unset($plugins->plugins[$p_base],	$plugins->active[array_search( $p_base, $plugins->active )]);

		$r['body']['plugins'] = serialize( $plugins );
		return $r;
	}
}
