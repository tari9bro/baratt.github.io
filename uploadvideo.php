<?php 
 
 //importing dbDetails file 
 require_once 'init.php';
 
 //this is our upload folder 
 $upload_path = 'uploads/livewallpapers/';
 $thumbnail_path = 'uploads/thumbnail/';
 

 
 //creating the upload url 
 $upload_url = 'http://'.$server_ip.'/AndroidLiveWallpaper/codecanyon/'.$upload_path; 
 $thumbnail_url = 'http://'.$server_ip.'/AndroidLiveWallpaper/codecanyon/'.$thumbnail_path;
 //response array 
 $response = array(); 
 
 
 if($_SERVER['REQUEST_METHOD']=='POST'){
 
 //checking the required parameters from the request 
 if(isset($_POST['name']) and isset($_FILES['image']['name']) and isset($_FILES['thumbnail']['name'])){
 
 //connecting to the database 
 $con = new mysqli(servername,username,password,database) or die('Unable to Connect...');
 
 //getting name from the request 
 $name = $_POST['name'];
 
 //getting file info from the request 
 $fileinfo = pathinfo($_FILES['image']['name']);
 $thumbnailinfo = pathinfo($_FILES['thumbnail']['name']);
 
 //getting the file extension 
 $extension = $fileinfo['extension'];
 $thumbnailExt = $thumbnailinfo['extension'];
 
 //file url to store in the database 
 $file_url = $upload_url . getFileName() . '.' . $extension;
 
 //file path to upload in the server 
 $file_path = $upload_path . getFileName() . '.'. $extension; 
 
  //thumbnail url to store in the database 
 $thumbnail_file_url = $thumbnail_url . getFileName() . '.' . $thumbnailExt;
 
 //file path to upload in the server 
 $thumbnail_file_path = $thumbnail_path . getFileName() . '.'. $thumbnailExt; 
 
 //trying to save the file in the directory 
 try{
 //saving the file 
 move_uploaded_file($_FILES['image']['tmp_name'],$file_path);
 move_uploaded_file($_FILES['thumbnail']['tmp_name'],$thumbnail_file_path);
 $sql = "INSERT INTO new_live_wallpaper (id, url, name,thumbnail) VALUES (NULL, '$file_url', '$name','$thumbnail_file_url');";
 
 //adding the path and name to database 
 if(mysqli_query($con,$sql)){
 
 //filling response array with values 
 $response['error'] = false; 
 $response['url'] = $file_url; 
 $response['thumbnail'] = $thumbnail_file_url;
 $response['name'] = $name;
 }
 //if some error occurred 
 }catch(Exception $e){
 $response['error']=true;
 $response['message']=$e->getMessage();
 } 
 //displaying the response 
 echo json_encode($response);
 
 //closing the connection 
 mysqli_close($con);
 }else{
 $response['error']=true;
 $response['message']='Please choose a file';
 }
 }
 
 /*
 We are generating the file name 
 so this method will return a file name for the image to be upload 
 */
 function getFileName(){
 $con = new mysqli(servername,username,password,database) or die('Unable to Connect...');
 $sql = "SELECT max(id) as id FROM new_live_wallpaper";
 $result = mysqli_fetch_array(mysqli_query($con,$sql));
 
 mysqli_close($con);
 if($result['id']==null)
 return 1; 
 else 
 return ++$result['id']; 
 }
