
 
<?php
	echo "<b>"."Delimiters extracted and replaced by (#)"."</b>";  // dellimiters extracted
	
	
	// connection to localhost
	echo"<br>";
	$con=new mysqli("localhost",'root','','classifier');
	set_time_limit(1000);  // reconfirgured time limit to allow for larage dataset processing
	
	$text=file_get_contents($_FILES['mes']['tmp_name']);
	$text=str_replace(",",'#',$text);
	$text=str_replace(" ",'#',$text);
	echo $text;
	
	echo"<br>";
	
	
	echo"<b>" ."Total words test document = ".str_word_count($text). "</b>";   // gives total words in dataset
	
	

	
	// breaks the array into strings
	$text_words= explode("#",$text);
	
	// create a new array
	$wc=array();
	foreach($text_words as $tw)  // foreach loop to run the if condition
	{
		if(!isset($wc[$tw]))   // checks if $tw is set or not 
			$wc[$tw]=1;     // setting that var to = 1
		else
			
		$wc[$tw]++;  // if not then we increment that var
		
	}
	
	// running a var_dum to get all term data to display on browser
	echo "<br>";
		var_dump($wc);
		echo "<br>";



	//fetch from SQL database table required data
	$res=$con->query("SELECT `term`,`p_spam`,`p_non_spam` FROM `classifier_data` ");
	$list_terms=array();
	

	
	
	
	
// intialising a new table for the called data used in fetch
	?>
		<table border="1">
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
	
	// setting initial variables for $ss=spam ==>0 and $sh=non_spam ==>0
	$ss=0;
	$sh=0;

	$mw=array();  // set up new array callingit $mw ==> matched words
	
	
	// start a foreach loop to assign that fetched data
    foreach($text_words as $tw)
	{
		
		for($i=0;$i<count($list_term);$i++){   // condition to count the $list_term 
		
		similar_text($list_term[$i]->term,$tw,$percent);// similar text then takes that count and new $tw variable to compare each string and rate in percentage form
	
		
			if($percent>90)  // if we assume setting it to >90% the similar text percentage
			{
			
				$ss+=$list_term[$i]->p_spam;			//we increment the spam variable with all list_term of spam 
				$sh+=$list_term[$i]->p_non_spam;        //we increment the spam variable with all list_term of non
				$mw[]=$list_term[$i];   				// update the new array $mw with updated data
		
				}
			
			
			}
	}

		
	?>

	<?php
	
	if($ss>$sh)   // spam >non_spam as in Vmap formula or max liklihood
	{
		
		echo "Emails are spam = ".$ss;  // spam is output in the table form at below
		?>
		<h1>matched table</h1>
		
		<table border = "1">
		<tr>
		<th>term</th><th>p_spam</th><th>count word</th>
		</tr>
		<?php
		foreach($mw as $w)
		{
			
			?>
			<tr>
			<td><?php echo $w->term;?> </td><td> <?php echo $w->p_spam;?></td><td><?php if(isset($wc[$w->term]))echo $wc[$w->term];else echo 0;?></td>
			</tr>
			<?php
			
	
		}?>
		</table>
		
	<?php	
	}
	else{
		
		echo "<b>" ."Emails are Non_Spam =".$sh."</b>";    // non spam is output as well via table below with all matched words 

	?>	
	<h1>matched words</h1>
	<table border="1">
		<tr>
				<th>term</th><th>p_non_spam</th><th>count word</th>
				
			</tr>
	<?php 
	foreach($mw as $w)
	{
		?>
		<tr>
			<td><?php echo $w->term;?></td><td><?php echo $w->p_non_spam;?></td><td><?php if(isset($wc[$w->term]))echo $wc[$w->term];else echo 0;?></td>
			
			
		</tr>
	  
	<?php
	}
	

	}?>
	</table>
