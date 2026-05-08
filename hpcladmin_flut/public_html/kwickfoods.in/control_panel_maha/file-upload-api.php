<?php
  	//$_FILES['image']['name']   give original name from parameter where 'image' == parametername eg. city.jpg
  	//$_FILES['image']['tmp_name']  temporary system generated name
 // $_FILES['filename'] = "sample.pdf";
        $originalImgName= $_FILES['filename']['name'];
        $tempName= $_FILES['filename']['tmp_name'];
        $folder="uploadedFiles/";
        /*$url = "https://www.demonuts.com/Demonuts/JsonTest/Tennis/uploadedFiles/".$originalImgName; */
        //update path as per your directory structure 
        
        if(move_uploaded_file($tempName,$folder.$originalImgName)){
                echo json_encode(array( "status" => "true","message" => "Successfully file added!" , "data" => $emparray) );
        }
			   
        else{
                echo json_encode(array( "status" => "false","message" => "Failed!") );
            }
        	//echo "moved to ".$url;
        
  
?>
<!--<form method="post" enctype="multipart/form-data">
<input type="file" name="filename">
  <button type="submit" name="submit" class="btn btn-primary btn-finish">Submit</button>
</form>-->