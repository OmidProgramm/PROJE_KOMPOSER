
<?php 

    session_start();

    use App\Classes\Auth;
    use App\Classes\Request;
    use App\Exceptions\DoesNotExistException;
    use App\Exceptions\NotFoundException;
    use App\Templates\CreatePage;
    use App\Templates\EditPage;
    use App\Templates\DeletePage;
    use App\Templates\ErrorPage;
    use App\Templates\PostPage;
    use App\Templates\NotFoundPage;

    require "./vendor/autoload.php";

    try
    {
        Auth::checkAuthenticated();
        $request = new Request();
        switch($request->get('action')){
            case 'posts':
                $page = new PostPage();
                break;
            case 'create':
                $page = new CreatePage();
                break;
            case 'edit':
                $page = new EditPage();
                break;
            case 'delete':
                $page = new DeletePage();
                break;
            case 'logout':
                Auth::logoutUser();
                break;
            default:
                throw new NotFoundException('not found page!');
        }
    }catch(DoesNotExistException | NotFoundException $exception){
        $page = new NotFoundPage($exception->getMessage());
    }catch(Exception $exception){
        $page = new ErrorPage($exception->getMessage());
    }finally{
        $page->renderPage();
    }
