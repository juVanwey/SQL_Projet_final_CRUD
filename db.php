<!-- Base pour chaque projet : -->

<?php
    // try{
    //     $db = new PDO("mysql:host=localhost;dbname=SQL_Projet_final_CRUD", "root", "root");
    //     $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //     $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        
    // }
    // catch(Exception $e){
    //     echo $e->getMessage();
    // }
?>

<?php
    try{
        $db = new PDO("mysql:host=localhost;dbname=SQL_Projet_final_CRUD", "root", "root");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        // $users = $db->query("SELECT * FROM user")->fetchAll();
        // $db->query = $db->exec
    }
    catch(Exception $e){
        echo $e->getMessage();
    }
?>