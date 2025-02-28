<?php

declare(strict_types=1);    // Declaração de tipos estritos

namespace Alura\Mvc\Controller; // Define o namespace, namespace é como se fosse uma pasta

use Alura\Mvc\Repository\VideoRepository;

class DeleteImageVideoController implements Controller{ // Define a classe e implementa a interface Controller

    public function __construct(private VideoRepository $videoRepository){ // Método construtor que recebe um objeto do tipo VideoRepository

    }

    public function processaRequisicao(): void // Método que processa a requisição
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT); // Recebe o id do vídeo a ser removido
        if ($id === null || $id === false) { // Se o id for nulo ou falso
            header('Location: /?success=0'); // Redireciona para a página inicial com a mensagem de erro
            return; // Encerra a execução do método
        }

        $video = $this->videoRepository->find($id); // Busca o vídeo pelo id
        if ($video === null) { // Se o vídeo não for encontrado
            header('Location: /?success=0'); // Redireciona para a página inicial com a mensagem de erro
            return; // Encerra a execução do método
        }

        // $video->removeImage(); // Remove a imagem do vídeo
        $video->setFilePath(null); // Define o caminho da imagem como nulo
        $this->videoRepository->update($video); // Atualiza o vídeo no banco de dados

        header('Location: /?success=1'); // Redireciona para a página inicial com a mensagem de sucesso
    }
}