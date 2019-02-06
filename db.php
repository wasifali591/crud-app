<?php
    function get_connection(){
        $connection=mysqli_connect('localhost','root','','crud-app');
        if(!$connection){
            die('failed to connect db');
        }
        return $connection;
    }

    function get_all_records(){
        $connection=get_connection();
        
        $sql="SELECT * FROM `user`";
        $result=mysqli_query($connection,$sql);
        $html_data='';
        $html_data .='<thead>';
        $html_data .='<tr>';
        $html_data .='<th>#</th>';
        $html_data .='<th>First Name</th>';
        $html_data .='<th>Last Name</th>';
        $html_data .='<th>Email</th>';
        $html_data .='<th>Actions</th>';
        $html_data .='</tr>';
        $html_data .='</thead>';
        if(mysqli_num_rows($result)){            

            while($row=mysqli_fetch_assoc($result)){
                $html_data .='<tr>';
                $html_data .='<td>'.$row['id'].'</td>';
                $html_data .='<td>'.$row['fname'].'</td>';
                $html_data .='<td>'.$row['lname'].'</td>';
                $html_data .='<td>'.$row['email'].'</td>';
                $html_data .='<td>';
                $html_data .='<a data-id="'.$row['id'].'" class="icon" id="update" href="#" data-toggle="modal" data-target="#update-modal"><i class="glyphicon glyphicon-edit"></i></a>';
                $html_data .='<a data-id="'.$row['id'].'" class="icon" id="delete" href="#" data-toggle="modal" data-target="#delete-modal"><i class="glyphicon glyphicon-trash"></i></a>';
                $html_data .='</td>';
                $html_data .='</tr>';
            }
            echo json_encode(['status'=>'success','html'=> $html_data]);
        }else{
            $html_data .='<tr>';                
            $html_data .='<td colspan="5" align="center">No Record Found</td>';
            $html_data .='</tr>';

            echo json_encode(['status'=>'success','html'=> $html_data]);

        }
    }

    function add_record($post){
        $connection=get_connection();

        $fname=$post['fname'];
        $lname=$post['lname'];
        $email=$post['email'];

        $sql="INSERT INTO `user`(`fname`,`lname`,`email`) VALUES(?,?,?)";
        $stmt=mysqli_prepare($connection,$sql);

        if(is_object($stmt)){
            mysqli_stmt_bind_param($stmt,'sss',$fname,$lname,$email);
            mysqli_stmt_execute($stmt);

            if(mysqli_stmt_affected_rows($stmt) ==1){
                echo json_encode(['status'=>'success','message'=>'Congratulation! Record inserted successfully.']);
            }else{
                echo json_encode(['status'=>'erroe','message'=>'Error: Failed to insert data.']);
            }
        }
    }

    function get_record($post){
        $connection=get_connection();
        $id=$post['id'];
        
        $sql="SELECT * FROM `user` WHERE `id`=?";
        $stmt=mysqli_prepare($connection,$sql);

        if(is_object($stmt)){
            mysqli_stmt_bind_param($stmt,'i',$id);
            mysqli_stmt_bind_result($stmt,$id,$fname,$lname,$email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_fetch($stmt);
            //echo $fname;

            if(mysqli_stmt_num_rows($stmt)){
                echo json_encode(['status'=>'success','id'=>$id,'fname'=>$fname,'lname'=>$lname,'email'=>$email]);
            }else{
                echo json_encode(['status'=>'erroe','message'=>'Error: Failed to insert data.']);
            }
        }
    }

    function update_record($post){
        $connection=get_connection();
        $id=$post['id'];
        $fname=$post['fname'];
        $lname=$post['lname'];
        $email=$post['email'];

        $sql="UPDATE `user` SET `fname`=?,`lname`=?,`email`=? WHERE `id`=?";
        $stmt=mysqli_prepare($connection,$sql);

        if(is_object($stmt)){
            mysqli_stmt_bind_param($stmt,'sssi',$fname,$lname,$email,$id);           
            mysqli_stmt_execute($stmt);
           
            //mysqli_stmt_fetch($stmt);
            //echo $fname;

            if(mysqli_stmt_affected_rows($stmt) == 1){
                echo json_encode(['status'=>'success','message'=>'Congratulation! Record updated successfully.']);
            }else{
                echo json_encode(['status'=>'erroe','message'=>'Error: Failed to update data.']);
            }
        }
    }

    function delete_record($post){
        $connection=get_connection();

        $id=$post['id'];
        

        $sql="DELETE FROM `user` WHERE `id`=?";
        $stmt=mysqli_prepare($connection,$sql);

        if(is_object($stmt)){
            mysqli_stmt_bind_param($stmt,'i',$id);
           
            mysqli_stmt_execute($stmt);
           
            //mysqli_stmt_fetch($stmt);
            //echo $fname;

            if(mysqli_stmt_affected_rows($stmt) == 1){
                echo json_encode(['status'=>'success','message'=>'Congratulation! Record deleted successfully.']);
            }else{
                echo json_encode(['status'=>'erroe','message'=>'Error: Failed to delete data.']);
            }
        }

    }


?>