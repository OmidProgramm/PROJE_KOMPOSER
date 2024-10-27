<?php 
    namespace App\Templates;

use App\Classes\Auth;
use App\Classes\Validator;
use App\Models\Setting;
use App\Classes\Request;

    abstract class Template{
        protected $title;
        protected $setting;
        protected $request;
        protected $validator;
        public function __construct(){
            $this->request = new Request();
            $this->validator = new Validator($this->request);
            $settingModel = new Setting();
            $this-> setting = $settingModel->getFirstData();
        }
        protected function getHead(){
            ?>
            <html lang="en">
            <head>
                <meta charset="UTF-8"> 
                <meta name="description" content="<?= $this->setting->getDescription() ?>">
                <meta name="keyword" content="<?= $this->setting->getKeywords() ?>">
                <meta name="Author" content="<?= $this->setting->getAuthor() ?>">
                <title><?= $this->title ?></title>
                <link rel="stylesheet" href="<?= asset('css/website.css') ?>">
                
            </head>
            <?php
        }

        protected function getAdminHead(){
            ?>
            <head>
                <title><?= $this->title ?></title>
                <link rel="stylesheet" href="<?= asset('css/website.css') ?>">
                <link rel="stylesheet" href="<?= asset('css/login.css') ?>">
                <link rel="stylesheet" href="<?= asset('css/panel.css') ?>">
            </head>
            <?php
        }

        protected function getHeader(){
            ?>
            <header>
            <!-- AUFRUF-SETTING => DYNAMIC -->
            <h1><?= $this->setting->getTitle()?></h1>
            <!-- image in DIV-TAG=> wie ein Block -->
            <div id="logo">
                <!-- AUFRUF-SETTING => DYNAMIC -->
                <img src="<?= asset($this->setting->getLogo()) ?>" alt="<?= $this->setting->getTitle()?>">
            </div>
        </header>
            <?php
        }

        protected function getFooter(){
            ?>
            <footer>
                <p><?= $this->setting->getFooter()?></p>
            </footer>
            <?php
        }
        protected function getSidebar($topPosts,$lastPosts){
            ?>
            <aside>
                <?php if(count($topPosts)): ?>
                    <div class="aside-box">                     
                        <h2>Top Post</h2>
                        <ul>
                            <?php foreach($topPosts as $item): ?>
                                <li>
                                    <a href="<?= url('index.php', ['action' => 'single', 'id' => $item->getId()]) ?>">
                                        <?= $item->getTitle()?> <small>( <?= $item->getView()?> )</small>
                                    </a>
                                </li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                <?php endif ?>
                <?php if(count($lastPosts)): ?>
                    <div class="aside-box">
                        <h2>Last Post</h2>
                        <ul>
                            <?php foreach($lastPosts as $item): ?>
                                <li>
                                    <a href="<?= url('index.php', ['action' => 'single', 'id' => $item->getId()]) ?>">
                                        <?= $item->getTitle()?> <small>( <?= $item->getDate()?> )</small>
                                    </a>
                                </li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                <?php endif ?>
            </aside>
            <?php

        }
        protected function getNavbar(){
            $word = $this->request->has('word')? $this->request->word:'';
            ?>
                <nav>
                    <ul>
                        <li><a href="<?= url('index.php') ?>">Home</a></li>
                        <li><a href="<?= url('index.php',['action' => 'category', 'category' => 'sport']) ?>">Sport</a></li>
                        <li><a href="<?= url('index.php',['action' => 'category', 'category' => 'social' ]) ?>">Social</a></li>
                        <li><a href="<?= url('index.php',['action' => 'category', 'category' => 'political' ]) ?>">Political</a></li>
                        <li><a href="<?= url('index.php',['action' => 'login' ]) ?>">Login</a></li>
                        <li><a href="#">Contact us</a></li>
                    </ul>
                    <form action="<?= url('index.php') ?>" method="GET">
                        <input type="hidden" name="action" value="search">
                        <input type="text" name="word" placeholder="Search your Word" value="<?= $word ?>">
                        <input type="submit" value="Search">
                    </form>
                </nav>
            <?php
        }
        protected function getAdminNavbar(){
            $user = Auth::getLoginUser();
            ?>
            <nav>
                <ul>
                    <li><a href="<?= url('index.php') ?>">Website</a></li>
                    <li><a href="<?= url('panel.php', ['action' => 'posts']) ?>">Posts</a></li>
                    <li><a href="<?= url('panel.php', ['action' => 'create']) ?>">Create Posts</a></li>
                    <li><a href="<?= url('panel.php', ['action' => 'logout']) ?>">Logout</a></li>
                </ul>
                <ul>
                    <li><?= $user->getFullName() ?></li>
                </ul>
            </nav>
            <?php
        }
        abstract public function renderPage();
    }