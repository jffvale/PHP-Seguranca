<?php
declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Repository\VideoRepository;

class VideoListController implements Controller
{
    public function __construct(private VideoRepository $videoRepository){

    }

    public function processaRequisicao(): void{
        session_start();    // Verifica se foi enviado um cookie de sessão, se não, cria um novo.
        // Toda vez que eu quiser verificar se o usuário está logado, eu preciso iniciar a sessão ou te-la iniciada em outro lugar
        if(!array_key_exists('logado', $_SESSION)){    
            header('Location: /login');
            return;
        }

        $videoList = $this->videoRepository->all();
            // var_dump($videoList);   // Verifica se a lista de vídeos está sendo retornada
            // exit();
        require_once __DIR__ . '/../../views/video-list.php';
    }
}
