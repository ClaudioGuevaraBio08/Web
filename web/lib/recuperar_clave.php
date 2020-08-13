<?php

$to = "guevaravasquez.claudionicolas@gmail.com";
$subject = "Asunto del email";
$message = "Este es mi primer envío de email con PHP";
$header = "From: noreply@example.com";
 
mail($to, $subject, $message,$header);
?>
