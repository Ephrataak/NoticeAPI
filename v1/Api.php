<?php 

	//getting the dboperation class
	require_once '../includes/DbOperation.php';

	//function validating all the paramters are available
	//we will pass the required parameters to this function 
	function isTheseParametersAvailable($params){
		//assuming all parameters are available 
		$available = true; 
		$missingparams = ""; 
		
		foreach($params as $param){
			if(!isset($_POST[$param]) || strlen($_POST[$param])<=0){
				$available = false; 
				$missingparams = $missingparams . ", " . $param; 
			}
		}
		
		//if parameters are missing 
		if(!$available){
			$response = array(); 
			$response['error'] = true; 
			$response['message'] = 'Parameters ' . substr($missingparams, 1, strlen($missingparams)) . ' missing';
			
			//displaying error
			echo json_encode($response);
			
			//stopping further execution
			die();
		}
	}
	
	//an array to display response
	$response = array();
	
	//if it is an api call 
	//that means a get parameter named api call is set in the URL 
	//and with this parameter we are concluding that it is an api call
	if(isset($_GET['apicall'])){
		
		switch($_GET['apicall']){
			
			//the CREATE operation
			//if the api call value is 'createpost'
			//we will create a record in the database
			case 'createpost':
				//first check the parameters required for this request are available or not 
				isTheseParametersAvailable(array('subject','message','date','userType'));
				
				//creating a new dboperation object
				$db = new DbOperation();
				
				//creating a new record in the database
				$result = $db->createPost(
					$_POST['subject'],
					$_POST['message'],
					$_POST['date'],
					$_POST['userType']
				);
				

				//if the record is created adding success to response
				if($result){
					//record is created means there is no error
					$response['error'] = false; 

					//in message we have a success message
					$response['message'] = 'Post addedd successfully';

					//and we are getting all the heroes from the database in the response
					$response['post'] = $db->getPost();
				}else{

					//if record is not added that means there is an error 
					$response['error'] = true; 

					//and we have the error message
					$response['message'] = 'Some error occurred please try again';
				}
				
			break; 
			
			//the READ operation
			//if the call is getpost
			case 'getpost':
				$db = new DbOperation();
				$response['error'] = false; 
				$response['message'] = 'Request successfully completed';
				$response['post'] = $db->getPost();
			break; 
			
			
			//the UPDATE operation
			case 'updatepost':
				isTheseParametersAvailable(array('id','subject','message','date'));
				$db = new DbOperation();
				$result = $db->updatePost(
					$_POST['id'],
					$_POST['subject'],
					$_POST['message'],
					$_POST['date'],
					$_POST['userType']
				);
				
				if($result){
					$response['error'] = false; 
					$response['message'] = 'Post updated successfully';
					$response['post'] = $db->getPost();
				}else{
					$response['error'] = true; 
					$response['message'] = 'Some error occurred please try again';
				}
			break; 
			
			//the delete operation
			case 'deletepost':

				//for the delete operation we are getting a GET parameter from the url having the id of the record to be deleted
				if(isset($_GET['id'])){
					$db = new DbOperation();
					if($db->deletePost($_GET['subject'])){
						$response['error'] = false; 
						$response['message'] = 'Post deleted successfully';
						$response['post'] = $db->getPost();
					}else{
						$response['error'] = true; 
						$response['message'] = 'Some error occurred please try again';
					}
				}else{
					$response['error'] = true; 
					$response['message'] = 'Nothing to delete, provide a subject please';
				}
			break; 
			//user login
			case 'login':
			$db = new DbOperation();
			$result = $db->login($_POST["username"], $_POST["password"]);
			
					if($result > 0){
						$response['error'] = false; 
						$response['message'] = 'login successful';
						
					}
					else{
						$response['error'] = true; 
						$response['message'] = 'Some error occurred please try again';						
					}

					//the READ post by id operation
		
		case 'getpostbyid':		
		if(isset($_GET['id'])){
			$db = new DbOperation();
			$result = $db->getPostByID($_GET['id']);
			
			if($db->getPostByID($_GET['id'])){
				$response['error'] = false; 
				$response['message'] = 'successfull';
				$response['post'] = $db->getPostByID($_GET['id']);
				
			}else{
				$response['error'] = true; 
				$response['message'] = 'Some error occurred please try again';
			}
		}else{
			$response['error'] = true; 
			$response['message'] = 'Missing id parameter';
		}
			
		break; 		
			
			
		}
		
	}else{
		//if it is not api call 
		//pushing appropriate values to response array 
		$response['error'] = true; 
		$response['message'] = 'Invalid API Call';
	}
	
	//displaying the response in json structure 
	echo json_encode($response);

	
