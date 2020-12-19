<?php 
    include 'Session.php';
    Session::init();
    include 'Database.php';
    $db = new Database();
    $table = 'tbl_student';

    if(isset($_REQUEST['action']) && !empty($_REQUEST['action'])){
        if($_REQUEST['action'] == 'add'){
            $student_data = array(
                'name' => $_POST['name'],
                'department' => $_POST['department'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone']
            );
            $insert = $db->insert($table, $student_data);
            if($insert){
               $mgs = '<div class="alert alert-success">Success ! <strong>Data inserted successfuly.</strong></div>'; 
            }else{
                $mgs = '<div class="alert alert-danger">Error ! <strong>Data not inserted.</strong></div>'; 
            }
            
            Session::set('mgs', $mgs);
            header("Location: ../index.php");
        }elseif($_REQUEST['action'] == 'edit'){
            $id = $_POST['id'];
            if(!empty($id)){
                $table = 'tbl_student';
                $cond = array('id' => $id);
                $student_data = array(
                    'name' => $_POST['name'],
                    'department' => $_POST['department'],
                    'email' => $_POST['email'],
                    'phone' => $_POST['phone']
                );
                $update = $db->update($table, $student_data, $cond);
            
                if($update){
                   $mgs = '<div class="alert alert-success">Success ! <strong>Data Updated successfuly.</strong></div>'; 
                }else{
                    $mgs = '<div class="alert alert-danger">Error ! <strong>Data not Updated.</strong></div>'; 
                }
                Session::set('mgs', $mgs);
                header("Location: ../index.php");
            }
            
        }elseif($_REQUEST['action'] == 'delete'){
            $id = $_GET['id'];
            if(!empty($id)){
                $table = 'tbl_student';
                $cond = array('id' => $id);
                $delete = $db->delete($table, $cond);
            
                if($delete){
                   $mgs = '<div class="alert alert-success">Success ! <strong>Data deleted successfuly.</strong></div>'; 
                }else{
                    $mgs = '<div class="alert alert-danger">Error ! <strong>Data not deleted.</strong></div>'; 
                }
                Session::set('mgs', $mgs);
                header("Location: ../index.php");
            }
            
        }
    }
?>