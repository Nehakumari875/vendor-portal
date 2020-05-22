<?php
/**
 *
 * Project Name: DAM
 * Class Name: AssetController  //This is a class level comment
 * TechInfo: This class is used to provide asset listing and create/download zip.
 * package : LoginBundle
 *
 */

namespace LoginBundle\Controller;

use Pimcore\Controller\FrontendController;
use Pimcore\Model\Asset;
use LoginBundle\Model\AssetModel;
use LoginBundle\Service\DashboardService;
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
 * @package: LoginBundle
 *
 * @subpackage: CONTROLLER
 */
class DashboardController extends FrontendController
{

  public function defaultAction(Request $request)
  {
  }

    /**
     * This function returns all asset listing
	   *
     * @Route("/dashboard")
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
      
    }

  }
