<?php
declare(strict_types=1);
namespace Alura\Mvc\Controller;

use Alura\Mvc\Entity\Video;
use Alura\Mvc\Repository\VideoRepository;

class JsonVideoListController implements Controller{
    public function __construct(private VideoRepository $videoRepository){
    }

    public function processaRequisicao(): void{
        $videoList = array_map(function(Video $video): array{   // Mapeia o array de vídeos
            return [
                'id' => $video->id,
                'url' => $video->url,
                'title' => $video->title,
                'image_path' => '/img/uploads/' . $video->getFilePath()
            ];

        }, $this->videoRepository->all());
        echo json_encode($videoList);   // Converte o array em JSON
    }
}