<?php 
    session_start();

    use App\Classes\Request;
    use App\Exceptions\DoesNotExistException;
    use App\Exceptions\NotFoundException;
    use App\Templates\CategoryPage;
    use App\Templates\ErrorPage;
    use App\Templates\LoginPage;
    use App\Templates\SearchPage;
    use App\Templates\SinglePage;
    use App\Templates\MainPage;
    use App\Templates\NotFoundPage;

    
    require "./vendor/autoload.php";
    
    try{
        $request = new Request();

        switch($request->get('action'))  /* $request->action  */
        {
            case 'single':
                $page = new SinglePage();     
                break;
            case 'search':
                $page = new SearchPage();
                break;
            case 'category':
                $page = new CategoryPage();
                break;
            case 'login':
                    $page = new LoginPage();
                    break;
            case null:
                $page = new MainPage();
                break;
            default:
                throw new NotFoundException('Page Not Found!!!');
        }
        
    }catch(DoesNotExistException | NotFoundException $exception){
        $page = new NotFoundPage($exception->getMessage());
    }catch(Exception $exception){
        $page = new ErrorPage($exception->getMessage());
    }finally{
        
        $page->renderPage();
    }

   
    
    

  