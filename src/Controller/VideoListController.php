<?php
declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Repository\VideoRepository;

class VideoListController implements Controller{
    public function __construct(private VideoRepository $videoRepository){

    }

    public function processaRequisicao(): void{

        $videoList = $this->videoRepository->all();
            // var_dump($videoList);   // Verifica se a lista de vídeos está sendo retornada
            // exit();
        require_once __DIR__ . '/../../views/video-list.php';
    }
}
