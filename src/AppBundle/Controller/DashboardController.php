<?php
/**
 *
 * Project Name: DAM
 * Class Name: AssetController  //This is a class level comment
 * TechInfo: This class is used to provide asset listing and create/download zip.
 * package : AppBundle
 *
 */

namespace AppBundle\Controller;

use Pimcore\Controller\FrontendController;
use Pimcore\Model\Asset;
use AppBundle\Model\AssetModel;
use AppBundle\Service\DashboardService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Pimcore\Bundle\AdminBundle\Security\User\TokenStorageUserResolver;
use Pimcore\Model\User;
use Pimcore\Model\DataObject;

/**
 * This class will show assets portlets
 *
 * class DashboardController
 *
 * @package: AppBundle
 *
 * @subpackage: CONTROLLER
 */
class DashboardController extends FrontendController
{

	// public $dashboardObj;

	// public function __construct() {
  //   $this->dashboardObj = new DashboardService();
  // }

  public function defaultAction(Request $request)
  {
  }

    /**
     * This function returns all asset listing
	   *
     * @Route("/dashboard/")
     *
     * @param Request $request
     *
     * @return AssetListing array() and CollectionListing array()
     */
    public function dashboardAction(Request $request)
    {

    	if ($this->get('session')->get('token') == "") {
    		return $this->redirect('/login');
    	}

    	// $condition = array();
      // $condition1 = array();
      // $sharedAssetsList = array();
      // $sharedAssetsToList = array();
      // $sharedWithAssetCount = '';
      // $sharedWithCollectionCount = '';
      // try {

      //     //Added by NEERAJ

      //     $userId = $this->get('session')->get('userid');
      //     $userInfo = User::getById($userId);

      //     $portalUserListing = new DataObject\PortalUser\Listing();
      //     $portalUserListing->setCondition('pimcoreUser = ?',$userId);
      //     $portalUserListing->load();

      //     if(empty($portalUserListing->getData()) ) {
      //         return $this->redirectToRoute('view_user');
      //     }

      //     ///Code end NEERAJ



      //   $currentUser = $this->get('session')->get('username');
      //   $this->view->myAssets = $this->dashboardObj->getAssetsCreatedList($currentUser);
      //   $this->view->count = count($this->dashboardObj->getAssetsCreatedList($currentUser));

      //   //Assets shared with loggedIn user
      //   $this->view->assetsSharedWithMe = $this->dashboardObj->getAssetsSharedWithMe($currentUser);
      //   $this->view->sharedCount = count($this->dashboardObj->getAssetsSharedWithMe($currentUser));

      //   //Assets shared To
      //   $this->view->assetsSharedTo = $this->dashboardObj->getAssetsSharedWithOthers($currentUser);
      //   $this->view->sharedWithCount = count($this->dashboardObj->getAssetsSharedWithOthers($currentUser));
       
      //   //Shared Collections
      //   $this->view->collectionsShared = $this->dashboardObj->getSharedCollections($currentUser);
      //   $this->view->collectionSharedCount = count($this->dashboardObj->getSharedCollections($currentUser));
      // } catch (\Exception $e) {
      //   return array(
      //     'error' => $e->getMessage(),
      //   );
      // }
    }

  }
