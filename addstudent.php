<?php include 'inc/header.php';?>  
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>Add Student<a class="btn btn-primary pull-right" href="index.php">Back</a></h3>
        </div>
        <div class="panel-body">
           <form action="lib/process_student.php" method="post">
               <div class="form-group">
                   <label for="name">Name:</label>
                   <input class="form-control" type="text" name="name" id="name" required>
               </div>
               <div class="form-group">
                   <label for="department">Department:</label>
                   <input class="form-control" type="text" name="department" id="department" required>
               </div>
               <div class="form-group">
                   <label for="email">Email:</label>
                   <input class="form-control" type="text" name="email" id="email" required>
               </div>
               <div class="form-group">
                   <label for="phone">Phone:</label>
                   <input class="form-control" type="text" name="phone" id="phone" required>
               </div>
               <div class="form-group">
                   <input type="hidden" name="action" value="add"/>
                   <input class="btn btn-success" type="submit" name="submit" value="Submit">
               </div>
           </form>
        </div>
    </div>
        
<?php include 'inc/footer.php'?>