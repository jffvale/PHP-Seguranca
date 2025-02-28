<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Entity\Video;
use Alura\Mvc\Repository\VideoRepository;

class NewVideoController implements Controller{
    public function __construct(private VideoRepository $videoRepository){
    }

    public function processaRequisicao(): void{
        // echo '<pre>';
        // var_dump($_FILES);
        // echo '</pre>';
        // exit();
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
            // Recebe o nome temporário do arquivo por segurança e pega apenas o nome do arquivo, sem o caminho
            $safeFileName = uniqid('upload_') . '_' . pathinfo($_FILES['image']['name'], PATHINFO_BASENAME);
            // Ler o arquivo temporário e analisar os primeiros 12 bytes do arquivo
            $finfo = new \finfo(FILEINFO_MIME_TYPE); // Instancia um novo objeto finfo para recuperar o tipo do arquivo (mime type)
            $mimeType = $finfo->file($_FILES['image']['tmp_name']); // Recebe o tipo do arquivo
            // echo '<pre>';
            // var_dump($mimeType);
            // echo '</pre>';
            // exit();
            if(str_starts_with($mimeType, 'image/')){ // Se o arquivo enviado for uma imagem faço o processamento
                move_uploaded_file(
                    $_FILES['image']['tmp_name'],
                    __DIR__ . '/../../public/img/uploads/' . $safeFileName
                );   // move_uploaded_file é uma função do PHP que move o arquivo enviado para o local desejado e 
                        //faz a verificação se o arquivo foi enviado com sucesso.

                $video->setFilePath($safeFileName); // criar um método setFilePath na classe Video (src/Entity/Video.php)                
            }

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
