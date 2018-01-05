<?php

#Using at productspec.php
function productSpec(){
    ?>
<?php
                $submit_id = $_GET['id'];
                $result = mysql_query("SELECT * FROM product WHERE idproduct=$submit_id");  
                if(mysql_num_rows($result) > 0)  
                {  
                     while($row = mysql_fetch_array($result))  
                     {  
                ?>  
                <div class="col-md-4">  
                    <form method="post" action="index.php?action=add&id=<?php echo $row["idproduct"]; ?>">  
                        <div style="" align="center">  
                            <img src="../images/<?php echo $row["idproduct"]; ?>.png" class="img-responsive" /><br />  
                            <h4 class="text-info"><?php echo $row["Name"]; ?></h4>  
                            <h4 class="text-danger">$ <?php echo $row["Price"]; ?></h4>  
							<h4 class="text-danger">Rating: <?php
							$finalRating = number_format((float)($row["Rating"] / $row["TotalRaters"]), 2, '.', '');
							echo $finalRating; ?></h4>  
							
							
							
							
                            <?php
							$comments = mysql_query("SELECT comment FROM comments WHERE product_idproduct=$submit_id");
                            
							
                            if(mysql_num_rows($comments) > 0){  
								while($row = mysql_fetch_array($comments)){
                                    ?>
                                <table style="width:10%" align="center">
                                    <tr>
                                        <?php echo $row["comment"]; ?>
                                    </tr>
                                </table>
                                <?php
                                
								}
							} 

							?>
   
                            

							
                          </div>
                    </form>  
        </div>
        
        

                <?php  
                     }  
                } 
                ?>

        <div align="center">
          <h3>Review this product</h3>

          
          <form method="post" id="">
			<input type="radio" name="rate" value="1" required> 1 
			<input type="radio" name="rate" value="2"> 2 
			<input type="radio" name="rate" value="3"> 3 
			<input type="radio" name="rate" value="4"> 4 
			<input type="radio" name="rate" value="5"> 5 

			<br>
			<textarea id="txt" class="text" rows="4" cols="50" name="txt" required>
				Write your review of this product</textarea>
			<br>
			<input type="submit" name="comment" style="margin-top:5px;" class="btn btn-success" value="Comment"/>  
          </form>
          
        </div>

<?php
}







?>