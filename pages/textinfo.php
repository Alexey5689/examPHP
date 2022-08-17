<?php 
    include_once('classes.php');
    $pdo=Tool::connect();
    $texts=Textfile::GetText();
    echo '<div class="row" id="result" style="margin-left:20px;">';
    foreach($texts as $text){
        $text->redact();
    }
    echo '</div>';
?>
