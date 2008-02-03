<?php

$BaseTemplate->Set('title', 'Test site!');


//Set another template file here to add
// inside the template file we can just do
// echo $javascript_head; 
// and it will print out that file
$BaseTemplate->Set('javascript_head', 'javascript.tpl');

?>