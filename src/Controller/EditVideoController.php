<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Entity\Video;
use Alura\Mvc\Repository\VideoRepository;

class EditVideoController implements Controller{
    public function __construct(private VideoRepository $videoRepository){
    }

    public function processaRequisicao(): void{
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if ($id === false || $id === null) {
            header('Location: /?sucesso=0');
            return;
        }

        $url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);
        if ($url === false) {
            header('Location: /?sucesso=0');
            return;
        }
        $titulo = filter_input(INPUT_POST, 'titulo');
        if ($titulo === false) {
            header('Location: /?sucesso=0');
            return;
        }

        $video = new Video($url, $titulo);
        $video->setId($id);

        if($_FILES['image']['error'] === UPLOAD_ERR_OK){    // Se o arquivo foi enviado sem erros, UPLOAD_ERR_OK é uma constante do PHP que indica que o arquivo foi enviado sem erros, valor 0.
            move_uploaded_file(
                $_FILES['image']['tmp_name'],
                __DIR__ . '/../../public/img/uploads/' . $_FILES['image']['name']
            );   // move_uploaded_file é uma função do PHP que move o arquivo enviado para o local desejado e faz a verificação se o arquivo foi enviado com sucesso.

            $video->setFilePath($_FILES['image']['name']); // criar um método setFilePath na classe Video (src/Entity/Video.php)
        }

        $success = $this->videoRepository->update($video);

        if ($success === false) {
            header('Location: /?sucesso=0');
        } else {
            header('Location: /?sucesso=1');
        }
    }
}