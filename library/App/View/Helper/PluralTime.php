<?
class App_View_Helper_PluralTime  extends Zend_View_Helper_Abstract
{

	/**
	 * Преаобазовует дату в слова
	 * @param $date
	 * @return bool|null|string
	 */
	public function pluralTime( $date )
	{
		$realTime 	= strtotime( $date );
		$minutes 	= round(abs( time() - $realTime ) / 60);
		$hours 		= round( $minutes / 60 );

		switch ( $minutes ) {

			case ( $minutes < 60 ) :
				return $minutes . $this->plural( $minutes,   ' минута', ' минуты', ' минут' ) . ' назад';

			case ( $minutes >= 60 && $hours <= 10 ) :
				return $hours . $this->plural( $hours, ' час', ' часa', ' часов' ) . ' назад';

			case ( $hours > 10 && $hours <= 24 ) :
				if( date( 'd', $realTime ) == date( 'd', time() ) ) {
					return 'сегодня в ' . date('H:i', $realTime );
				}
				else
					return 'вчера в ' . date('H:i', $realTime );

			case ( $hours > 24 && $hours <= 48 ) :
					return 'вчера в ' . date('H:i', $realTime );

			case ( $hours > 48 && $hours <= 72 ) :
				return 'позавчера';

			case ( $hours > 72 ) :
				return date('d.m.y', $realTime );
		}

		return null;
	}


	/**
	 * Склонение слов
	 * @param $n
	 * @param $form1
	 * @param $form2
	 * @param $form3
	 * @return mixed
	 */
	public function plural($n, $form1, $form2, $form3) {
		$plural = ($n % 10 == 1 && $n % 100 != 11 ? 0 : ($n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 or $n % 100 >= 20) ? 1 : 2));
		switch($plural) {
			case 0:
			default:
				return $form1;
			case 1:
				return $form2;
			case 2:
				return $form3;
		}
	}

}