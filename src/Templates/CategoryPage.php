<?php 
    namespace App\Templates;
    use App\Exceptions\NotFoundException;
    use App\Models\Post;


    class CategoryPage extends Template{
        private $posts;
        private $topPosts;
        private $lastPosts;
        public function __construct(){
            parent::__construct();
            if(!$this->request->has('category')){
                throw new NotFoundException('Category not Found');
            }
            $category = $this->request->category;
            $this->title = $this->setting->getTitle().' - '.$category;
            $postModel = new Post();
            $this->posts = $postModel->filterData(function($item)use($category){
                return $item->getCategory() == $category? true : false;
            });
            $this->topPosts = $postModel->sortData(function($first,$second){
                return $first->getView() > $second->getView()?-1:1;
            });
            $this->lastPosts = $postModel->sortData(function($first, $second){
                return $first->getTimeStamp() > $second->getTimeStamp()?-1:1;
            });
        }

        public function renderPage(){
            ?>
            <html lang="en">
                <?php $this->getHead()?>
                <body>
                    <main>
                        <?php $this->getHeader() ?>
                        <?php $this->getNavbar() ?>
                        <section id="content">
                            <?php $this->getSidebar($this->topPosts,$this->lastPosts) ?>
                            <div id="articles">
                                <?php foreach($this->posts as $post): ?>
                                    <article>
                                        <div class="caption">
                                            <h3><?= $post->getTitle()?></h3>
                                            <ul>
                                                <li>Date: <span><?= $post->getDate() ?></span></li>
                                                <li>View: <span><?= $post->getView() ?> View</span></li>
                                            </ul>
                                            <p>
                                                <?= $post->getExcerpt() ?>
                                            </p>
                                            <a href="<?= url('index.php', ['action' => 'single', 'id' => $post->getId()]) ?>">More...</a>
                                        </div>
                                        <div class="image">
                                            <img src="<?= asset($post->getImage()) ?>" alt="<?= $post->getTitle() ?>">
                                        </div>
                                        <div class="clearfix"></div>
                                    </article>
                                <?php endforeach ?>
                            </div>
                            <div class="clearfix"></div>
                        </section>
                        <?php $this->getFooter() ?>
                    </main>
                </body>
            </html>
        <?php
        }
    }