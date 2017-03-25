<?php

	echo "<b>"."Delimiters extracted and replaced by (#)"."</b>";
	
	echo"<br>";
	$con=new mysqli("localhost",'root','','classifier');  // connection to database
	
	$text=file_get_contents($_FILES['mes']['tmp_name']);
	$text=str_replace(",",'#',$text);   // replace delimiters
	$text=str_replace(" ",'#',$text);
	echo $text;
	
	echo"<br>";
	
	
	echo"<b>" ."Total words test document = ".str_word_count($text). "</b>";   // get total words 
	

	
	// set array to strings 
	$text_words= explode("#",$text);
	
	$wc=array(); //create  new array
	foreach($text_words as $tw)  // assign new temp var $tw
	{
		if(!isset($wc[$tw]))  // check if any values are not set
			$wc[$tw]=1;   // if not then set to 1
		else
			
		$wc[$tw]++;// increment that var
		 
		
	}
	echo "<br>";
		var_dump($wc);
		echo "<br>";
	//print_r($wc);


	
	$res=$con->query("SELECT `term`,`p_spam`,`p_non_spam` FROM `classifier_data`");
	$list_term=array();
	

	
	
	
	// Fetch SQL data to table

	?>
		<table border="1" id="list_term">
			<tr>
				<th>term</th><th>spam</th><th>p_non_spam</th><
			</tr>
	<?php
	while($row=$res->fetch_object())
	{
		$list_term[]=$row;
		?>
		<tr>
			<td><?php echo $row->term;?></td><td><?php echo $row->p_spam;?></td><td><?php echo $row->p_non_spam;?></td>
		</tr>
		<?php 
		
	}?>
	</table> 
	
		<?php
		
// finding the list_term values from database
	$ss=0;  // initialise variables 
	
	$sh=0;

	$mw=array();  // set a new array

    foreach($text_words as $tw)
	{
	
		// apply loop to test for each list_term by similar text
		for($i=0;$i<count($list_term);$i++){
		similar_text($list_term[$i]->term,$tw,$percent);
			if($percent>90)
			{
			 $mw[]=$list_term[$i];   // assign to new var
			 
			}
	}
}
// process of removing duplicate elements if required  
$mwv=array();
		for($i=0;$i<count($mw);$i++) // get the values for $mw
		{
			 if(!in_array($mw[$i]->term,$mwv))  // in_array to check if terms do not exist in array $mwv
			 {
				 for($j=$i+1;$j<count($mw);$j++) // nested loop conditon to check for next value in array
				{
				

				if($mw[$i]->term==$mw[$j]->term)  // filter to check if both arrays match
					{
					$mw[$i]->p_spam+=$mw[$j]->p_spam;			//if so increment
					$mw[$i]->p_non_spam+=$mw[$j]->p_non_spam;
					
					$mw[$i]->p_spam;
					$mw[$i]->p_non_spam;
					}
				}
			 }
			 else
			 {
				 array_splice($mw,$i,1); // if not remove element if duplicated 
			 }
	
		}
	$mwv=$mw;	  // assign $mwv with new array values

 	$ss=1;  // intialise spam var to 1 as probability values are all usually below 1.
	$sh=1;
	$count_ten=0; // count for 10 iterations to get an avg value
	foreach($mwv as $wv)
	{
	
	$ss*=$wv->p_spam;  // increment spam var
	$sh*=$wv->p_non_spam;
	
	
	 if($ss*$sh<0.00001)   // if both values product is less than lowest value for probability of a term 
	 {
	
		$ss*=10;
		$sh*=10;
		$count_ten++;  // increment the count as we need to try to reduce any variances
	}   
} 


	?>

	<?php
	
	if($ss>$sh)
	{
		
		echo "Emails are spam = ".$ss/pow(10,$count_ten);  //divide all by the count iteration value to get avg count
	
		?>
		<h1>matched words</h1>
		<table border="1">
			<tr>
				<th>term</th><th>p_spam</th><th>count word</th>
				
		
			</tr>
		
		
		<?php 
		foreach($mwv as $w)
		{
		?>
		<tr>
			<td><?php echo $w->term;?></td><td><?php echo $w->p_non_spam;?></td><td><?php if(isset($wc[$w->term]))echo $wc[$w->term];else echo 0;?></td>
			
			
		</tr>
	  
		<?php
	
	
		}?>	 		
		</table>	

	
	<?php	
	}
	else{
		
		echo "Emails are Non_Spam =".$sh/pow(10,$count_ten);   // divide by count iteration value to get avg count
	
	
	?>	
	<h1>matched words</h1>
	<table border="1">
		<tr>
				<th>term</th><th>p_non_spam</th><th>count word</th>
				
			</tr>
	<?php 
	foreach($mwv as $w)
	{
		?>
		<tr>
			<td><?php echo $w->term;?></td><td><?php echo $w->p_non_spam;?></td><td><?php if(isset($wc[$w->term]))echo $wc[$w->term];else echo 0;?></td>
			
			
		</tr>
	  
	<?php
	}
	
	}?>	 		
	</table>	
	