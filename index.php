<?php
require 'StringIsland.php';

$object = new StringIsland('abaab', 2);

$object->calculateNumberOfIslandsForCombinations();

echo $object->countOfMatched;

exit;