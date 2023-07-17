<?php

/**
 * Handles multidimentional array sorting by a key (not recursive)
 *
 * @author Oliwier Ptak <aleczapka at gmx dot net>
 */
class array_sorter
{
	var $skey = false;
	var $sarray = false;
	var $sasc = true;

	/**
	 * Constructor
	 *
	 * @access public
	 * @param mixed $array array to sort
	 * @param string $key array key to sort by
	 * @param boolean $asc sort order (ascending or descending)
	 */

	//function array_sorter(&$array, $key, $asc = true)
	public function __construct(&$array, $key, $asc = true)
	{
		$this->sarray = $array;
		$this->skey = $key;
		$this->sasc = $asc;
	}
	/**
	 * Sort method
	 *
	 * @access public
	 * @param boolean $remap if true reindex the array to rewrite indexes
	 */
	function sortit($remap = true)
	{
		$array = &$this->sarray;
		uksort($array, array($this, "_as_cmp"));

		if ($remap) {
			$tmp = array();
			foreach ($array as $data) {
				$tmp[] = $data;
			}
			return $tmp;
		}

		return $array;
	}

	/**
	 * Custom sort function
	 *
	 * @access private
	 * @param mixed $a an array entry
	 * @param mixed $b an array entry
	 */
	function _as_cmp($a, $b)
	{
		//since uksort will pass here only indexes get real values from our array
		if (!is_array($a) && !is_array($b)) {
			$a = $this->sarray[$a][$this->skey];
			$b = $this->sarray[$b][$this->skey];
		}

		//if string - use string comparision
		if (!ctype_digit($a) && !ctype_digit($b)) {
			if ($this->sasc)
				return strcasecmp($a, $b);
			else
				return strcasecmp($b, $a);
		} else {
			if ($a == $b)
				return 0;

			if ($this->sasc)
				return ($a > $b) ? -1 : 1;
			else
				return ($a > $b) ? 1 : -1;
		}
	}
} //end of class

function array_sorter(&$array, $key, $asc = true)
{
	$sorter = new array_sorter($array, $key, $asc);
	return $sorter->sortit();
}

/*
//sort by parent_id in descending order
$my_array = multi_sort($input_array, "parent_id", false);
 */