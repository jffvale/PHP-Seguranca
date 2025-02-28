<?php
declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Entity\Video;
use Alura\Mvc\Repository\VideoRepository;

class NewJsonVideoController implements Controller{

    public function __construct(private VideoRepository $videoRepository){
    }

    public function processaRequisicao(): void{

        // Fazendo a recuperação dos dados via JSON
        $request = file_get_contents('php://input');    // Obtém o conteúdo da requisição como texto puro, sem formatação
        // Decodifica o JSON para um objeto PHP, o segundo parâmetro true retorna um array associativo, senão retorna um objeto
        $videoData = json_decode($request, true);

        $video = new Video($videoData['url'], $videoData['title']);    // Cria um novo objeto do tipo Video
        $this->videoRepository->add($video);    // Adiciona (salva) o vídeo no banco de dados

        http_response_code(201);    // Define o código de status da resposta como 201 (Created)
    }
}