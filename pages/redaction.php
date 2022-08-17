
    <?php
        echo '<div class="row" id="result" style="margin-left:20px;">';
        $texts=Textfile::GetText();
        foreach($texts as $text){
            $text->Draw();
        }
        echo '</div>';
    ?>
</form>
<script>
    function getText(txt){
        if (txt==""){
            document.getElementById('result').innerHTML="";
        }
        //creating AJAX object
        if (window.XMLHttpRequest){
            ao=new XMLHttpRequest();//аякс объект
        }else{
           ao=new ActiveXObject('Microsoft.XMLHTTP'); 
        } 
        //anonymous function for result processing
        ao.onreadystatechange=function(){
            if (ao.readyState==4 && ao.status==200){
                document.getElementById('result').innerHTML = ao.responseText;
            }
        }
        //preparing post AJAX request
        ao.open('post','pages/redaction.php',true);
        ao.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        ao.send("txt="+txt);
    }
</script>