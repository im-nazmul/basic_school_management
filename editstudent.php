<?php
    include 'inc/header.php';
    include 'lib/Session.php';
    include 'lib/Database.php';
?>  
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>Update Student<a class="btn btn-primary pull-right" href="index.php">Back</a></h3>
        </div>
        <div class="panel-body">
          <?php 
                $id = $_GET['id'];
                $table = 'tbl_student';
                $whereCond = array(
                    'where' => array('id' => $id),
                    'return_type' => 'single'
                );
                $db = new Database();
                $edit = $db->select($table, $whereCond);
                if(!empty($edit)){
           ?>
           <form action="lib/process_student.php" method="post">
               <div class="form-group">
                   <label for="name">Name:</label>
                   <input class="form-control" type="text" name="name" id="name" required value="<?php echo $edit['name'];?>">
               </div>
               <div class="form-group">
                   <label for="department">Department:</label>
                   <input class="form-control" type="text" name="department" id="department" required value="<?php echo $edit['department'];?>">
               </div>
               <div class="form-group">
                   <label for="email">Email:</label>
                   <input class="form-control" type="text" name="email" id="email" required value="<?php echo $edit['email'];?>">
               </div>
               <div class="form-group">
                   <label for="phone">Phone:</label>
                   <input class="form-control" type="text" name="phone" id="phone" required value="<?php echo $edit['phone'];?>">
               </div>
               <div class="form-group">
                   <input type="hidden" name="id" value="<?php echo $edit['id'];?>"/>
                   <input type="hidden" name="action" value="edit"/>
                   <input class="btn btn-success" type="submit" name="submit" value="Update">
               </div>
           </form>
           <?php }?>
        </div>
    </div>
        
<?php include 'inc/footer.php'?>