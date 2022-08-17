<h3>Загрузка файла</h3>
<form action="index.php?page=1" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title">Заголовок:</label>
        <input type="text" class="form-control" name="title">
    </div> 
    <div class="form-group">
        <label for="textpath">Select file:</label>
        <input type="file" class="form-control" name="textpath">
    </div>
    <button type="submit" class="btn btn-primary" name="regbtn">Загрузить</button>
</form> 

<?php
if(isset($_POST['regbtn'])){
    if(is_uploaded_file($_FILES['textpath']['tmp_name'])){
        $path="textFile/".$_FILES['textpath']['name'];
        move_uploaded_file($_FILES['textpath']['tmp_name'], $path);
    }
    if(Tool::download($_POST['title'],$path)){
        echo "<h3/><span style='color:green;'>New File Added!</span><h3/>";
    } 
}

?>    