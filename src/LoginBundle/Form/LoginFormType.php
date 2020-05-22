<?php
/**
 * 
 * Project Name: DAM
 * Class Name: LoginFormType 
 * TechInfo: This class is for login form
 * package : LoginBundle
 */
namespace LoginBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
/**
 * This class handles the user authentication form
 *
 * Class LoginFormType
 * @author: Sapna
 * @package: LoginBundle
 * @subpackage: Form
 * @verison: 1.0
 */
class LoginFormType extends AbstractType
{
    /**
     * This function build the form for login
     * 
     * @param FormBuilderInterface $builder, array $options
     *
     * @return string
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label'       => 'Username:',
                'required'    => true,
                'attr'     => [
                    'placeholder' => 'Username',
                    'class' => 'form-control',
                    'id'=>'username',
                    "style"=>""
                ]
            ])
            ->add('password', PasswordType::class, [
                'label'    => 'Password:',
                'required' => true,
                'attr'     => [
                    'placeholder' => 'Password',
                    'class' => 'form-control',
                    'id'=>'password',
                    "style"=>""
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Sign in',
                 'attr'     => [
                    'class' => 'btn btn-lg btn-primary btn-block text-uppercase'
                ]
            ]);
    }

    
}
