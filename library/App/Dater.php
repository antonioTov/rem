<?
class App_Dater
{

	public static function convertToDiffTime( $minuts )
	{
		$hours  = floor($minuts/60);
		$minuts = $minuts % 60;
		return $hours . ':' . $minuts;
	}
}