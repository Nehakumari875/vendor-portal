<?php
/**
 *
 * Project Name: DAM
 * Class Name: LoginController
 * TechInfo: This class is used to authenticate user info and manage session using login and logout function .
 * package : AppBundle
 */
namespace AppBundle\Controller;

use AppBundle\Form\LoginFormType;
use Couchbase\Document;
use Pimcore\Controller\FrontendController;
use AppBundle\Service\AuthenticationService;
use Pimcore\Model\DataObject\PortalUser;
use Pimcore\Model\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * This class handles all the functionality regarding user authentication
 *
 * Class LoginController
 *
 * @package: AppBundle
 *
 * @subpackage: CONTROLLER
 */
class LoginController extends FrontendController
{
    public $session;

    public function __construct(SessionInterface $session)
    {
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
                    $authenticate = $userObject->userAuthentication($currentUser, $currentPass, $this->session);
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
        $this->session->set('token', null); //set token value null
        $this->session->set('username', null); //set username value null
        $this->session->set('userid', null); //set username value null
        $this->session->invalidate(); //here we can now clear the session.
        return $this->redirect('/login'); //redirect to login page

    }


}
