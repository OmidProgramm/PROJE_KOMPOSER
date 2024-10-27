<?php 
    namespace App\Templates;

    use App\Models\User;
    use App\Classes\Auth;

    class LoginPage extends Template{
        private $errors = [];

        public function __construct(){
            parent::__construct();

            if(Auth::isAuthenticated())
                redirect('panel.php', ['action' => 'posts']);

            $this->title = $this->setting->getTitle().' - login to system';

            if($this->request->isPostMethod()){
                $data = $this->validator->validate([
                    'email' => ['required', 'email'],
                    'password' => ['required', 'min:6']
                ]);
                
                if(!$data -> hasError()){
                    $userMethod = new User();
                    $user = $userMethod->authenticateUser($this->request->email, $this->request->password);
                    if($user){
                        Auth::loginUser($user);
                        redirect('panel.php', ['action' => 'posts']);
                    }else{
                        
                        $this->errors[] = "invalid credential";
                    }
                }else{
                    
                    $this->errors = $data->getErrors();
                }
            }

        }
        private function showErrors(){
            if(count($this->errors))
            {
                ?>
                <div class="errors">
                    <ul>
                        <?php foreach($this->errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach ?>  
                    </ul>
                </div>
                <?php 
            }
        }
        public function renderPage(){
            ?>
            <html lang="en">
                <?php $this->getAdminHead() ?>
                <body>
                    <main>
                        <form method="POST" action="<?= url('index.php', ['action' => 'login']) ?>">
                            <div class="login">
                                <h3>Login to System</h3>
                                <?php $this->showErrors() ?>              
                                <div>
                                    <label for="email">Email:</label>
                                    <input type="text" id="email" name="email">
                                </div>
                                <div>
                                    <label for="password">Password:</label>
                                    <input type="test" id="password" name="password">
                                </div>
                                <div>
                                    <input type="submit" value="login">
                                </div>
                            </div>
                        </form>
                    </main>
                </body>
            </html>
            <?php
        }
    }