<?php
    class Tool{
        static function connect(
            $host="localhost:3306",
            $user="root",
            $pass="password",
            $dbname="Titles"
        ){
            $cs='mysql:host='.$host.';dbname='.$dbname.';charset=utf8;';
            $options=array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES UTF8');
            try {
                $pdo=new PDO($cs,$user,$pass,$options);
                return $pdo;
            }
            catch(PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }
        static function download($title, $textpath){
            $title=trim($title);
            $textpath =trim($textpath);
            Tool::connect();//подключ к базе данных
            $textfile = new Textfile($title, $textpath);
            $err=$textfile->intoDb();
            if ($err){
                if($err==1062){
                    echo "<h3/><span style='color:red;'>This Login Is Already Taken!</span><h3/>"; 
                }else{
                    echo "<h3/><span style='color:red;'>Error code:".$err."!</span><h3/>";
                    return false;
                }
            }
            return true;
        }



        
    }

    class Textfile{
        public $id;
        public $title;
        public $textpath;
        function __construct($title, $textpath, $id=0){
            $this->title = $title;
            $this->textpath = $textpath;
            $this->id = $id;
        }
        function intoDb(){
            try{
                $pdo=Tool::connect();
                $ps=$pdo->prepare("INSERT INTO title (title, textpath) 
                                   VALUES (:title,:textpath)");//пласхолдеры
                $ar=(array)$this; //преобразование объекта класса в массив. ? трейты
                array_shift($ar); //array_shift — Извлекает первый элемент массива
                $ps->execute($ar);//выполнить запрос    
            }catch(PDOException $e){
                $err=$e->getMessage();
                if(substr($err,0,strrpos($err,":"))=='SQLSTATE[23000]:Integrity constraint violation'){
                    return 1062;
                }else{
                    return $e->getMessage();
                }
            }
        }
        static function GetText($textid=0){
            $ps=null;//переменные
            $texts=null;
            try{
                $pdo=Tool::connect();//подключ
                if($textid == 0){
                    $ps=$pdo->prepare('SELECT * FROM title');
                    $ps->execute();//вып запрос
                }else{
                    $ps=$pdo->prepare('SELECT * FROM title WHERE id=(?)');
                    $ps->execute(array($textid));
                }
                while ($row=$ps->fetch()){// Извлечение следующей строки из результирующего набора
                    $text=new Textfile($row['title'], 
                                       $row['textpath'], 
                                       $row['id']);

                        $texts[]=$text;
                    }
                return $texts;
            }catch(PDOException $e){
                echo $e->getMessage();
                return false;
            }
        }
        function Draw(){
            $filetxt = file_get_contents($this->textpath);
            echo'<form action="pages\textinfo.php" method="post" enctype="multipart/form-data">';
            echo "<div class='row' style='height:100px; margin-top:20px;' class='row'>";
                echo "<textarea class='pull-left' name='text' 
                        style='display:block;
                        height:100%;
                        width:100%;
                        margin-left:10px;
                        color:black;
                        font-size:16px;'>$filetxt";
                echo "</textarea>";
            echo "</div>";
            echo "<button class='btn btn-success col-xs-offset-1 col-xs-10 pull-left' name='redbtn'>Save text</button>";
            echo"</form>";
        }
        function redact(){
            $load = $this->textpath;
            $txt = $_POST['text']; 
            $handle = fopen("..\\".$load, "w");
            fwrite($handle, $txt); 
            fclose($handle);
            $load = $this->textpath;
            echo "<div class='row' style='margin-top:20px; background-color:white;'>";
            echo "<a href='..\\$load' download  class='pull-left' style='margin-left:10px;' target='_blank'>";
            echo  $this->title;
            echo "</a>";
           
        }


    }


?>