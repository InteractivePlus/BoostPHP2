<?php
namespace BoostPHP\FastCompare{
    /**
	* Sort Array by Native Quick Sort Method(Partition)
	* About 20 times faster then quicksort implemented by the PHP code.
	* @param array $array array to be sorted
	* @return void
    */
    function quickSort(array &$array) : void{
		sort($array);
		return;
    }
    
    function customCompareSort(array &$array, callable $functionName): void{
        usort($array, $functionName);
        return;
    }
}