<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Entity\Video;
use Alura\Mvc\Repository\VideoRepository;

class NewVideoController implements Controller{
    public function __construct(private VideoRepository $videoRepository){
    }

    public function processaRequisicao(): void{
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

        $video = new Video($url, $titulo);  // Instancia um novo vídeo
        $_FILES['image'];   // Recebe o arquivo enviado pelo formulário. O nome do arquivo é o mesmo do campo input do formulário.
        // echo '<pre>';
        // var_dump($_FILES);
        // echo '</pre>';
        // exit();
        if($_FILES['image']['error'] === UPLOAD_ERR_OK){    // Se o arquivo foi enviado sem erros, UPLOAD_ERR_OK é uma constante do PHP que indica que o arquivo foi enviado sem erros, valor 0.
            move_uploaded_file(
                $_FILES['image']['tmp_name'],
                __DIR__ . '/../../public/img/uploads/' . $_FILES['image']['name']
            );   // move_uploaded_file é uma função do PHP que move o arquivo enviado para o local desejado e 
                    //faz a verificação se o arquivo foi enviado com sucesso.

            $video->setFilePath($_FILES['image']['name']); // criar um método setFilePath na classe Video (src/Entity/Video.php)
        }

        $success = $this->videoRepository->add($video); // Adiciona o vídeo no banco de dados
        if ($success === false) {
            header('Location: /?sucesso=0');
            exit();
        } else {
            header('Location: /?sucesso=1');
            exit();
        }
    }
}
