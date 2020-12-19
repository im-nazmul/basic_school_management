<?php
    include 'inc/header.php';
    include 'lib/Database.php';
    include 'lib/Session.php';
?>  
    <?php 
        Session::init();
        $mgs = Session::get('mgs');
        if(!empty($mgs)){
            echo $mgs;
            Session::set('mgs', NULL);
        }
    ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>Student List<a class="btn btn-primary pull-right" href="addstudent.php">Add Student</a></h3>
        </div>
        <div class="panel-body">
            <table class="table table-striped">
                <tr>
                    <th width="10%">Serial</th>
                    <th width="20%">Name</th>
                    <th width="15%">Department</th>
                    <th width="20%">Email</th>
                    <th width="20%">Phone</th>
                    <th width="25%">Action</th>
                </tr>
                <?php 
                    $db = new Database();
                    $table = 'tbl_student';
                    $orderBy = array('order_by' => 'id DESC');
                    /*
                    $selectCond = array('select' => 'name');
                    $whereCond = array(
                        'where' => array('id' => 1, 'email' => 'nazmul@live.com'),
                        'return_type' => 'single'
                    );
                    $limit = array('start' => '2', 'limit' => '4');
                    $limit = array('limit' => '4');
                    */
                    $studentDtata = $db->select($table, $orderBy);
                    if(!empty($studentDtata)){
                        $i = 0;
                        foreach($studentDtata as $value){
                            $i++;
                ?>
                <tr>
                    <td><?php echo $i;?></td>
                    <td><?php echo $value['name'];?></td>
                    <td><?php echo $value['department'];?></td>
                    <td><?php echo $value['email'];?></td>
                    <td><?php echo $value['phone'];?></td>
                    <td>
                        <a class="btn btn-default" href="editstudent.php?id=<?php echo $value['id'];?>">Edit</a>
                        <a class="btn btn-danger" href="lib/process_student.php?action=delete&id=<?php echo $value['id'];?>" onclick="return confirm('Are you sure to Delet?')">Delet</a>
                    </td>
                </tr>
                <?php }}else{?>
                <tr><td clospan="5"><h1>No Data found!</h1>h1></td></tr>
                <?php }?>
            </table>
        </div>
    </div>
        
<?php include 'inc/footer.php'?>