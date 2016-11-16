<?php

namespace App\Lib;

class Functions {

	public static function createSubArrayFromIndex($array, $index) {

		$subArray = array();
		foreach ($array as $item) {
			if (isset($item[$index])) {
				$subArray[] = $item[$index];
			}
		}

		return $subArray;
	}

}