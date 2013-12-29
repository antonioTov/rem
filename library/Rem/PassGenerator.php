<?php
class Rem_PassGenerator
{
	const LENGTH = 8;

	static public function generate() {

		$alphabet = "abcdefghijkmnpqrstuwxyzABCDEFGHJKLMNPQRSTUWXYZ123456789";
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i < self::LENGTH; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		return implode($pass); //turn the array into a string
	}
}