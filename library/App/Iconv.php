<?php

class App_Iconv
{

	public static function toCp1251( $txt )
	{
		return iconv('utf-8', 'cp1251', $txt);
	}

	public static function toUtf8( $txt )
	{
		return iconv('cp1251', 'utf-8',$txt);
	}

}