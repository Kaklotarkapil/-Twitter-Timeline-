<?php


	session_start();
	// Include config file and twitter PHP Library
	include_once("config.php");
	include_once("inc/twitteroauth.php");
	if(isset($_SESSION['status']) && $_SESSION['status'] == 'verified') 
	{
		$screen_name 		= $_SESSION['request_vars']['screen_name'];
		$twitter_id			= $_SESSION['request_vars']['user_id'];
		$oauth_token 		= $_SESSION['request_vars']['oauth_token'];
		$oauth_token_secret = $_SESSION['request_vars']['oauth_token_secret'];
		
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_token_secret);
		
		if (isset($_POST['Create_csv']))
		{
			//Store the Input type value whenever used to download any user followers
			$formvalue = $_POST['input_value'];

			//  tell the browser it's going to be a csv file
			 header("Content-type: application/csv");
			 //tell the browser we want to save it instead of displaying it
   			header("Content-Disposition: attachment; filename=Followers_CSV.csv");
   			
   			$delimiter=",";

   			//// open the "output" stream
	   		$fp = fopen('php://output', 'w');
	   		
			$data=array();
		
			$followers = $connection->get('followers/list', array('count'=>200,'screen_name'=>$formvalue));
			fputcsv($fp, array("Name","Screen_Name","Created At"));
			fputcsv($fp, array(" "," "," "));
			foreach($followers->users as $follower)
			{
				$c=date('d/m/Y H:i:s',strtotime($follower->created_at));
				fputcsv($fp, array($follower->name,$follower->screen_name,$c));	
			}
			fclose($fp);

	 
		}

	}
	?>