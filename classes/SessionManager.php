<?php

//ova klasa će služiti kao wrapper za rad sa sessionima
	class SessionManager{
		public static function isLoggedIn(){
			return isset($_SESSION['user_id']);
		}
			
		public static function doLogout(){
			unset($_SESSION['user_id']);
			session_destroy();
			$current_user=NULL;
		}		
		
		public static function doLogin($id){
			$_SESSION['user_id']=$id;
			$cu=User::load($id);
			$_SESSION['current_user_type']=$cu->Type;
		}
		
		public static function getCurrentUser(){
			$id=$_SESSION['user_id'];
			return User::load($id);
		}
		
		public static function getCurrentUserId(){
			return $_SESSION['user_id'];
		}
		
		public static function getCurrentUserType(){
			return $_SESSION['current_user_type'];
		}
		
		public static function getVar($name){
			if (isset($_SESSION[$name])) return $_SESSION[$name];
				else return NULL;
		}
		
		public static function setVar($name,$value){
			$_SESSION[$name]=$value;
		}
		
		public static function start(){
			session_start();
		}
		
	}
?>
