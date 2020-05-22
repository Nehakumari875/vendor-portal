<?php

/**
 *
 * Project Name: Blog Demo
 * Class Name: BlogService  //This is a class level comment
 * TechInfo: This class is used to provide service method for a blog.
 * package : AppBundle
 *
 */

namespace AppBundle\Service;
use Pimcore\Model\Asset;
use Pimcore\Model\DataObject;
use Pimcore\Model\User;
use AppBundle\Model\AssetModel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Pimcore\Bundle\AdminBundle\Security\User\TokenStorageUserResolver;
use AppBundle\Service\AssetService;

/**
 * This class handles all the functionality regarding Blogs
 * 
 * class DashboardService
 * 
 * @package: AppBundle
 * 
 * @subpackage: Service
 */
class DashboardService
{
  public $assetObj;

  public function __construct() {
    $this->assetObj = new AssetService();
  }

  /**
  * This function returns created assets listing by secific user (folder/images/documents)
  *
  * @param Request $request
  *
  * @return AssetCreatedListing array()
  */
  public function getAssetsCreatedList($userName)
  {
    $condition = array();
    try {
        // Assets created by LoggedIn User
      $currentUser=User::getByName($userName); 
      $condition['conditions'] = "userOwner =" . $currentUser->getId();
      $condition['orderKey'] = "filename";
      $condition['order'] = "DESC";

      $assetsCreatedByMe = $this->assetObj->fetchAssets($condition);
      
      return $assetsCreatedByMe;
    } catch (\Exception $e) {
      return array(
        'error' => $e->getMessage(),
      );
    }
  }

  /**
  * This function returns shared assets with loggedIn user (folder/images/documents)
  *
  * @param Request $request
  *
  * @return AssetSharedListing array()
  */
  public function getAssetsSharedWithMe($userName){
    try{
      $sharedAssetsList = array();
      $condition = array();
      $currentUser=User::getByName($userName);
      $sharedAssetsObj = $this->sharedWithAssets($currentUser->getId(),$userName);
     
      foreach ($sharedAssetsObj as $sharedList) {
        if($sharedList->getSharedItemId()){
          $id = $sharedList->getSharedItemId()[0]->getElementId();
          if (!empty($id)) {
            $condition['conditions'] = "id =".$id;
            $images = $this->assetObj->fetchAssets($condition);
            $sharedBy = User::getById($sharedList->getSharedBy());          
            $sharedAssetsList[] = ["sharedAssets" => $images,"sharedBy" => $sharedBy->getName(), "sharedType" => $sharedList->getSharedType(), "sharedDate"=> date("d/m/y : H:i:s",$sharedList->getModificationDate())];
          }
        }
      }
      return $sharedAssetsList;
    }catch (\Exception $e) {
      return array(
        'error' => $e->getMessage(),
      );
    }
  }

  /**
  * This function returns shared assets with other portal user (folder/images/documents)
  *
  * @param Request $request
  *
  * @return AssetSharedListing array()
  */
  public function getAssetsSharedWithOthers($userName){
    try{
      $sharedAssetsToList = array();
      $currentUser=User::getByName($userName);
      $type = 'Asset';
      $sharedToAssetsObj = $this->sharedToAssets($currentUser->getId(),$userName,$type);
      foreach ($sharedToAssetsObj as $sharedToList) {
        if($sharedToList->getSharedItemId()){
          $id = $sharedToList->getSharedItemId()[0]->getElementId();
          if (!empty($id)) {
            $condition1['conditions'] = "id =" .$id;
            $images = $this->assetObj->fetchAssets($condition1);
            $sharedWith = User::getById($sharedToList->getSharedWith());        
            $sharedAssetsToList[] = ["sharedAssets" => $images,"sharedWith" => $sharedWith->getName(), "sharedType" => $sharedToList->getSharedType(), "sharedDate"=> date("d/m/y : H:i:s",$sharedToList->getModificationDate())];
          }
        }
        
      }
      return $sharedAssetsToList;
    }catch (\Exception $e) {
      return array(
        'error' => $e->getMessage(),
      );
    }
  }

    /**
     * This function returns shared assets listing with other portal user (folder/images/documents)
     *
     * @param userId, userName
     *
     * @return sharedAssets obj
     */
    function sharedWithAssets($userId,$userName)
    {
      try {
        $checkuser=User::getByName($userName);
        $sharedAssetsObj = DataObject\SharedAssetsCollection::getList([]);
        $sharedAssetsObj->setCondition("sharedWith ='" . $userId."' AND sharedType = 'Asset' ");
        $sharedAssetsObj->setOrder('DESC');         
        return $sharedAssetsObj;
      } catch (\Exception $e) {
        return array(
         'error' => $e->getMessage(),
       );
      }
    }

    /**
     * This function returns shared assets by loggedIn user (folder/images/documents)
     *
     * @param userId, userName
     *
     * @return sharedAssets obj
     */
    function sharedToAssets($userId,$userName,$type)
    {
      try {
        $checkuser=User::getByName($userName);
        $sharedAssetsObj = DataObject\SharedAssetsCollection::getList([]);
        $sharedAssetsObj->setCondition("sharedBy ='" . $userId."' AND sharedType = '".$type."' ");
        $sharedAssetsObj->setOrder('DESC');
        return $sharedAssetsObj;
      } catch (\Exception $e) {
        return array(
         'error' => $e->getMessage(),
       );
      }
    }

     /**
  * This function returns shared collections with other portal user 
  *
  * @param Request $request
  *
  * @return CollectionSharedListing array()
  */
  public function getSharedCollections($userName){
    try{
      $sharedCollectionToList = array();
      $currentUser=User::getByName($userName);
      $type ='Collection';
      $sharedToCollectionObj = $this->sharedToAssets($currentUser->getId(),$userName,$type);
      foreach ($sharedToCollectionObj as $sharedToList) {
        if($sharedToList->getSharedItemId()){
          $id = $sharedToList->getSharedItemId()[0]->getElementId();
          if (!empty($id)) {
            $condition1['conditions'] = "o_id =" .$id;
            $images = $this->assetObj->fetchCollections($condition1);
            $sharedWith = User::getById($sharedToList->getSharedWith());          
            $sharedCollectionToList[] = ["sharedAssets" => $images,"sharedWith" => $sharedWith->getName(), "sharedType" => $type, "sharedDate"=> date("d/m/y : H:i:s",$sharedToList->getModificationDate())];
          }
        }
        
      }
      return $sharedCollectionToList;
    }catch (\Exception $e) {
      return array(
        'error' => $e->getMessage(),
      );
    }
  }
}


