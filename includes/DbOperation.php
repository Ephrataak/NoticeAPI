<?php
 
class DbOperation
{
    //Database connection link
    private $con;
 
    //Class constructor
    function __construct()
    {
        //Getting the DbConnect.php file
        require_once dirname(__FILE__) . '/DbConnect.php';
 
        //Creating a DbConnect object to connect to the database
        $db = new DbConnect();
 
        //Initializing our connection link of this class
        //by calling the method connect of DbConnect class
        $this->con = $db->connect();
    }
	
	/*
	* The create operation
	* When this method is called a new record is created in the database
	*/
	function createPost($subject, $message, $date, $userType){
		$stmt = $this->con->prepare("INSERT INTO Post (subject, message, date, userType) VALUES (?, ?, ?, ?)");
		$stmt->bind_param("ssss", $subject, $message, $date, $userType);
		if($stmt->execute())
			return true; 
		return false; 
	}

	/*
	* The read operation
	* When this method is called it is returning all the existing record of the database
	*/
	function getPost(){
		$stmt = $this->con->prepare("SELECT id, subject, message, date, userType FROM Post ORDER BY id desc");
		$stmt->execute();
		$stmt->bind_result($id, $subject, $message, $date, $userType);
		
		$Post = array(); 
		
		while($stmt->fetch()){
			$postd  = array();
			$postd['id'] = $id; 
			$postd['subject'] = $subject; 
			$postd['message'] = $message; 
			$postd['date'] = $date;
			$postd['userType'] = $userType;
			 
			 
			
			array_push($Post, $postd); 
		}
		
		return $Post; 
	}
	
	/*
	* The update operation
	* When this method is called the record with the given id is updated with the new given values
	*/
	function updatePost($id, $subject, $message, $date, $userType){
		$stmt = $this->con->prepare("UPDATE Post SET subject = ?, message = ?, date = ?, userType = ? WHERE id = ?");
		$stmt->bind_param("sssi", $subject, $message, $date, $userType, $id);
		if($stmt->execute())
			return true; 
		return false; 
	}
	
	
	/*
	* The delete operation
	* When this method is called record is deleted for the given id 
	*/
	function deletePost($id){
		$stmt = $this->con->prepare("DELETE FROM Post WHERE id = ? ");
		$stmt->bind_param("s", $id);
		if($stmt->execute())
			return true; 
		
		return false; 
	}
	function login($username, $password){
		
		$stmt = $this->con->prepare("SELECT * FROM USER where username = ? and password = ?");
		$stmt->bind_param("ss", $username, $password);
		$stmt->execute();
		$stmt->store_result();	
		return ($stmt->num_rows);
		
		// if($stmt->execute())
		// 	return true; 
		
		// return false; 
	}
	/*
	* getpost by id operation
	* When this method is called it is returning all the existing record of the database by its id
	*/
	function getPostByID($id){
		$stmt = $this->con->prepare("SELECT * FROM Post WHERE id = ?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$stmt->bind_result($id, $subject, $message, $date, $userType);
		$stmt->fetch();
		$postd  = array();
		$postd['id'] = $id; 
		$postd['subject'] = $subject; 
		$postd['message'] = $message; 
		$postd['date'] = $date;
		$postd['userType'] = $userType;
		return $postd;



		//$stmt->bind_result($id, $subject, $message, $date, $userType);
		//$stmt->store_result();	
		//return $stmt->resu
		// $Post = array(); 
		
		// while($stmt->fetch()){
		// 	$postd  = array();
		// 	$postd['id'] = $id; 
		// 	$postd['subject'] = $subject; 
		// 	$postd['message'] = $message; 
		// 	$postd['date'] = $date;
		// 	$postd['userType'] = $userType;
			 
		
			
		// 	array_push($Post, $postd); 
		// }
		
		// return $Post; 
	}
}