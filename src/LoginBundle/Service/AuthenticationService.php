<?php
 /**
  * This file is the part of DAM partner portal project
  * This file contains all the business logic related to Pimcore Assets
  * @copyright: Happiestminds 
  * 
  */

 namespace LoginBundle\Service;


 use Pimcore\Model\User;
 use Pimcore\Tool\Authentication;
 use Symfony\Component\HttpFoundation\Session\Session;
/**
 * Authentication Service contains the user authentication of pimcore user
 * @class: AuthenticationService
 * @author: Sapna
 * @package: Dam Partner Portal 
 * @subpackage: Service
 * @verison: 1.0
 * 
 */

class AuthenticationService
{

    /**
    * Authenticate user by different level of validations
    * @param string $currentUser,$currentPass
    * @return response of token if autheticated
    * @throw Exception Message
    */

    public function userAuthentication($currentUser,$currentPass,$session){
      
      try {
       $checkuser=User::getByName($currentUser);
       if($checkuser){
        $userValidity=Authentication::authenticatePlaintext($currentUser,$currentPass);
                //check if username and password are correct
                
        if($userValidity){
          $userId = $userValidity->getId();
                    //Generate token
          $token=$this->generateToken($currentUser,$currentPass);
          $session->set('token',$token); 
          $session->set('username',$currentUser); 
          $session->set('userid',$userId); 
          $tokenauthenticate = Authentication::authenticateToken($token);
          if($tokenauthenticate){
            return $tokenauthenticate;
          }
        }
      }
    }catch(\Exception $e){
      throw $e->getMessage();
    }        

  }

     /**
     * This function generate the token
     * 
     * @param string $username,$password
     *
     * @return string
     *
     */
     public function generateToken($username, $password)
     {
        $token = Authentication::generateToken($username, $password);
        return $token;
     }

  }