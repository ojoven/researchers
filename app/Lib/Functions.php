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

	public static function getFileContentsRetryIfFail($url, $tries = 5) {

		try {
			$content = file_get_contents($url);
			return $content;

		} catch (\Exception $e) {

			if ($tries > 0) {
				sleep(5);
				$tries = $tries - 1;
				return self::getFileContentsRetryIfFail($url, $tries);
			} else {
				echo $e->getMessage();
			}

		}
	}

}