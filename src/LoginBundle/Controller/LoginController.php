<?php
/**
 *
 * Project Name: DAM
 * Class Name: LoginController
 * TechInfo: This class is used to authenticate user info and manage session using login and logout function .
 * package : LoginBundle
 */
namespace LoginBundle\Controller;

use LoginBundle\Form\LoginFormType;
use Couchbase\Document;
use Pimcore\Controller\FrontendController;
use LoginBundle\Service\AuthenticationService;
use Pimcore\Model\DataObject\PortalUser;
use Pimcore\Model\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\SessionValueResolver;
use Symfony\Component\HttpFoundation\Session\Attribute\NamespacedAttributeBag;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * This class handles all the functionality regarding user authentication
 *
 * Class LoginController
 *
 * @package: LoginBundle
 *
 * @subpackage: CONTROLLER
 */
class LoginController extends FrontendController
{
    public $session;

    public function __construct()
    {
        $session = new Session();
        $this->session = $session;
    }

    /**
     *
     *
     * This function login user to the website and redirect to landing page if user is valid
     *
     * @Route("/login")
     *
     * @param Request $request
     *
     */
    public function loginAction(Request $request)
    {
        try {
            if ($this->get('session')->get('token')) {
                return $this->redirect('/dashboard');
            }

            // initialize form and handle request data
            $form = $this->createForm(LoginFormType::class);
            $form->handleRequest($request);

            // handle login and pre-fill form
            if ($form->isSubmitted()) {
                if ($form->isValid()) {
                    $formData = $form->getData();
                    $currentUser = $formData["username"];
                    $currentPass = $formData["password"];
                    $userObject = new AuthenticationService();
                    $session = new Session();
                    $authenticate = $userObject->userAuthentication($currentUser, $currentPass, $session);
                    if($authenticate){
                        
                        $userId = $this->get('session')->get('userid');
                        $userInfo = User::getById($userId);
                        $portalUserListing = new PortalUser\Listing();
                        $portalUserListing->setCondition('pimcoreUser = ?',$userId);
                        $portalUserListing->load();

                        if(empty($portalUserListing->getData()) ) {
                            return $this->redirectToRoute('view_user');
                        }else{
                            return $this->redirect('/dashboard');
                        }

                    }else{
                        return $this->redirect('/login');
                    }
                }
            }
            $this->view->form = $form->createView();
        } catch (exception $e) {
            return array(
                'error' => $e->getMessage(),
            );
        }

    }

    /**
     * This function will set the token null, clear the session and logout the user from the website
     *
     * @Route("/logout", name="logout" )
     *
     * @param Request $request
     *
     */
    public function logoutAction(Request $request)
    {
        $sessionData = $request->getSession();
        $sessionData->set('token', null); //set token value null
        $sessionData->set('username', null); //set username value null
        $sessionData->set('userid', null); //set username value null
        $sessionData->invalidate(); //here we can now clear the session.
        return $this->redirect('/login'); //redirect to login page

    }


}
