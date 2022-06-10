<?php
session_start();

$text ='';
$content = '';

if(isset($_SESSION['text'])){
    $text = $_SESSION['text'];
    unset($_SESSION['text']);
}
if(isset($_SESSION['content'])){
    $content = $_SESSION['content'];
    unset($_SESSION['content']);
    
}


$html = file_get_contents('input.html');


$html = str_replace('{{text}}',$text,$html);
$html = str_replace('{{content}}',$content,$html);

print($html);


?>