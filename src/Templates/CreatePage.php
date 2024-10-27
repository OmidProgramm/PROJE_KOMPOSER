<?php 

    namespace App\Templates;
    use App\Entities\PostEntity;
    use App\Models\Post;
    use App\Classes\Session;

    class CreatePage extends Template{
        private $errors = [];
        public function __construct(){

            parent::__construct();
            
            $this->title = $this->setting->getTitle().' - Admin panel - Create post';
            
            if($this->request->isPostMethod()){
                $data = $this->validator->validate([
                    'title' => ['required', 'min:3', 'max:100'],
                    'category' => ['required', 'in:political,sport,social'],
                    'content' => ['required', 'min:5', 'max:1500'],                 
                    'image' => ['required', 'file', 'type:jpg,png', 'size:2048']
                ]);

                if(!$data->hasError()){
                    $this->createPost();
                } else{
                    $this->errors = $data->getErrors();
                }
            }
            
        }

        private function createPost(){
            $postModel = new Post();
            $post = new PostEntity([
                'id' => $postModel->getLastData()->getId() + 1,
                'title' => $this->request->title,
                'content' => $this->request->content,
                'category' => $this->request->category,             
                'view' => 0,
                'image' => $this->request->image->upload(),
                'date' => date('Y-m-d H:i:s')
            ]);

            $postModel->createData($post);
            Session::flush('message', 'New Post was created');
            redirect('panel.php', ['action' => 'posts']);
        }
        private function showErrors(){
            if(count($this->errors)){
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
        public function renderPage()
        {
            ?>
            <html>
                <?php $this->getAdminHead() ?>
                <body>
                    <main>
                        <?php $this->getAdminNavbar() ?>
                        <section class="content">
                            <?php $this->showErrors() ?>
                            <form method="POST" enctype="multipart/form-data">
                                <div>
                                    <label for="title">Title</label>
                                    <input type="text" name="title" id="title" value="<?= $this->request->has('title')? $this->request->title :'' ?>">
                                </div>
                                <din>
                                    <label for="category">Category</label>
                                    <select name="category" id="category">
                                        <option value="political" <?= ($this->request->has('category') and $this->request->category == 'political')? 'selected': '' ?>>Political</option>
                                        <option value="sport" <?= ($this->request->has('category') and $this->request->category == 'sport')? 'selected': '' ?>>Sport</option>
                                        <option value="social" <?= ($this->request->has('category') and $this->request->category == 'social')? 'selected': '' ?>>Social</option>
                                    </select>
                                </din>
                                <div>
                                    <label for="content">Content</label>
                                    <textarea name="content" id="content" cols="30" rows="15"><?= $this->request->has('content')? $this->request->content:'' ?></textarea>
                                </div>
                                <div>
                                    <label for="image">Image</label>
                                    <input type="file" name="image" id="image">
                                </div>
                                <div>
                                    <input type="submit" value="Create post">
                                </div>
                            </form>
                        </section>
                    </main>
                </body>
            </html>
            <?php
        }
    }