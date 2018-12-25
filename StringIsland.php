<?php

/**
 * Class StringIsland
 * This class holds combinations and their related elements
 * to determine number of the matched combination that has given number of island
 */
class StringIsland
{

    //This variable holds the original string
    private $string;

    // This variable holds the combination of the string and related data like
    // example: array(array('a', 'XbXXb', 2), array('ab', 'XXaXX') .. )
    // first element of the array indicates combination from given string
    // second elements indicates mapped version of the string by combination
    // third element indicates number of island that string has
    private $combinations = array();

    //This variable holds the given number of the island
    private $expectedNumberOfIsland;

    public $countOfMatched;

    public function __construct(string $string, int $numberOfIsland)
    {
        $this->string = $string;
        $this->expectedNumberOfIsland = $numberOfIsland;

        //Generating all unique combinations of the given string
        $this->generateUniqueCombination();

        $this->countOfMatched = 0;
    }

    /**
     * Generating all unique combinations of the given string
     */
    private function generateUniqueCombination() : void
    {
        $tempArray = array();
        $lengthOfString = strlen($this->string);
        for ($i = 0; $i < $lengthOfString; $i++)
        {
            array_push($tempArray, $this->string[$i]);
            for ($j = 2; $j <= $lengthOfString - $i; $j++)
            {
                array_push($tempArray, substr($this->string, $i, $j));
            }
        }
        $this->combinations = array_unique($tempArray);
    }

    /**
     * This function gets the ranged substring to search specific combination
     * @param int $index
     * @param int $substringLength
     * @return bool|string
     */
    private function getRangedSubstring(int $index, int $substringLength)
    {
        $stringLength = strlen($this->string);
        if($index >= $stringLength || $substringLength > $stringLength)
            return false;


        $start = $index - ($substringLength - 1);

        $end = $index + ($substringLength - 1);

        if($start < 0)
            $start = 0;

        if ($end >= $stringLength)
            $end = $stringLength - 1;

        $rangeLength = $end - $start + 1;

        return substr($this->string, $start, $rangeLength);
    }

    /**
     * If we found our combination on rangedSubstring then we mark that index 'X'
     * in the copy of the original string
     * @param string $subString
     * @return string
     */
    private function getChangedGlobalString(string $subString) : string
    {
        $copyString = $this->string;

        for ($i = 0; $i < strlen($this->string); $i++)
        {
            $rangedSubstring = $this->getRangedSubstring($i, strlen($subString));

            if($rangedSubstring !== false)
            {
                if(strpos($rangedSubstring, $subString) !== false)
                {
                    $copyString[$i] = 'X';
                }
            }

        }
        return $copyString;

    }

    /**
     * Calculates all mapped strings and number of island belongs to that strings
     * assign the count of mapped string that has given number of island to countOfMatched property
     */
    function calculateNumberOfIslandsForCombinations() : void
    {
        $countOfMatched = 0;
        foreach ($this->combinations as $key => $combination)
        {
            $tempArray = array();
            array_push($tempArray, $combination);
            $mappedString = $this->getChangedGlobalString($combination);
            array_push($tempArray, $mappedString);

            $numberOfIsland = $this->calculateNumberOfIsland($mappedString);
            array_push($tempArray, $numberOfIsland);

            $this->combinations[$key] = $tempArray;

            if($this->expectedNumberOfIsland === $numberOfIsland)
                $countOfMatched++;

        }

        $this->countOfMatched = $countOfMatched;
    }

    /**
     * Calculates number of island in mapped string
     * @param string $mappedString
     * @return int
     */
    function calculateNumberOfIsland(string $mappedString) : int
    {
        // We eleminating all repeating 'X' via regular expression and
        // finding number of 'X' in mapped string to determine number of island
        $tempMappedString = preg_replace("/(X)\\1+/", "$1", $mappedString);
        $numberOfIsland = substr_count($tempMappedString, 'X');
        return $numberOfIsland;
    }
}