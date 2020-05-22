<?php
/**
 * @var \Pimcore\Templating\PhpEngine $this
 * @var \Pimcore\Templating\PhpEngine $view
 * @var \Pimcore\Templating\GlobalVariables $app
 */
/**
 * @todo remove unused files
 * @todo move css into css file
 *
 */

use Symfony\Component\HttpFoundation\Session\SessionInterface;

$this->extend('layout.html.php');

?>

 <div class="container-login100">
   <div class="wrap-login100 p-l-55 p-r-55 p-t-65 p-b-50">
       <?php $form = $this->form;
                echo $this->form()->start(
                    $form,
                    [ 'attr' => [ 'name' => "login", 'class' => 'login100-form validate-form ', 'role' => 'form', 'method' => 'post' ] ]
                ); ?> 
               <h2 class="card-title text-center">DAM Login</h2>
               <div class="error-msg up-error" style="color:red;"></div>
               <div class="form-label-group p-t-25 p-b-15" >
                <?php echo $this->form()->row($form['username'], [ 'attr' => []]) ?>
               </div>

              <div class="form-label-group p-t-15 p-b-25">
                <?php echo $this->form()->row($form['password'], [ 'attr' => []]) ?>
              </div>
        <?= $this->form()->end($form) ?>          
    
</div>
</div>