<?php

function debugPrint($stuff)
{
	echo "<pre>";
	print_r($stuff);
	echo "</pre>";
}


function setURLWithName(string $text): string
{
	$text = strtolower($text);
	return str_replace(" ", '-', $text);
}
