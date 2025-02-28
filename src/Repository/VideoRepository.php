<?php
declare(strict_types=1);

namespace Alura\Mvc\Repository;

use Alura\Mvc\Entity\Video;
use PDO;

class VideoRepository{
    public function __construct(private PDO $pdo){
    }

    public function add(Video $video): bool{
        $sql = 'INSERT INTO videos (url, title, image_path) VALUES (?, ?, ?)';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $video->url);
        $statement->bindValue(2, $video->title);
        $statement->bindValue(3, $video->getFilePath());

        $result = $statement->execute();
        $id = $this->pdo->lastInsertId();

        $video->setId(intval($id));

        return $result;
    }

    public function removeVideo(int $id): bool{
        $sql = 'DELETE FROM videos WHERE id = ?';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $id);

        return $statement->execute();
    }

    public function update(Video $video): bool{
        $updateImageSql = '';   // Inicializa a variável
        $path = filter_input(INPUT_POST, 'path', FILTER_VALIDATE_BOOLEAN); // Recebe o valor do campo hidden "path"
        if($video->getFilePath() !== null){
            $updateImageSql = ', image_path = :image_path'; // Se a imagem não for nula, atualiza o caminho da imagem
        } else {
            // recuperando o valor "path" do campo hidden do formulário
            if($path === true){ // Se o caminho da imagem for verdadeiro, carregue o image_path
                $updateImageSql = ', image_path = :image_path'; // Se a imagem não for nula, atualiza o caminho da imagem
            } else {
                $updateImageSql = ', image_path = NULL'; // Se a imagem for nula, define o caminho da imagem como NULL
            }
        }
            // var_dump($path);
            // var_dump($updateImageSql);
            // exit();

        // Tem que mudar o UPDATE para "(aspas duplas) para aceitar a variável $updateImageSql
        $sql = "UPDATE videos SET
                    url = :url, 
                    title = :title
                    $updateImageSql 
                    WHERE id = :id;";
        $statement = $this->pdo->prepare($sql);

        $statement->bindValue(':url', $video->url);
        $statement->bindValue(':title', $video->title);
        $statement->bindValue(':id', $video->id, PDO::PARAM_INT);
        
        if ($video->getFilePath() !== null) {
            $statement->bindValue(':image_path', $video->getFilePath());
        }
    
        // var_dump($statement);
        // $statement->debugDumpParams(); // Verifica os parâmetros do statement
    
        // Executa a consulta de atualização
        $statement->execute();
    
        // // Verifica o conteúdo da coluna image_path após a atualização
        // $sql = 'SELECT image_path FROM videos WHERE id = :id';
        // $selectStatement = $this->pdo->prepare($sql);
        // $selectStatement->bindValue(':id', $video->id, PDO::PARAM_INT);
        // $selectStatement->execute();
        // $result = $selectStatement->fetch(PDO::FETCH_ASSOC);
        // var_dump($result['image_path']); // Exibe o conteúdo da coluna image_path
    
        // exit();


        return $statement->execute();
    }

    /**
     * @return Video[]
     */
    public function all(): array{   // Retorna todos os vídeos
        // O hydrateVideo é uma função que converte os dados do banco em um objeto Video
        $videoList = $this->pdo
            ->query('SELECT * FROM videos;')
            ->fetchAll(\PDO::FETCH_ASSOC);
        return array_map(
            $this->hydrateVideo(...),
            $videoList
        );
    }

    public function find(int $id){
        $statement = $this->pdo->prepare('SELECT * FROM videos WHERE id = ?;');
        $statement->bindValue(1, $id, \PDO::PARAM_INT);
        $statement->execute();

        return $this->hydrateVideo($statement->fetch(\PDO::FETCH_ASSOC));
    }

    private function hydrateVideo(array $videoData): Video{ // Converte os dados do banco em um objeto Video
        $video = new Video($videoData['url'], $videoData['title']); // Cria um novo objeto Video
        $video->setId($videoData['id']);    // Adiciona o id ao vídeo

        if($videoData['image_path'] !== null){ // Se a imagem não for nula, adiciona o caminho da imagem ao vídeo
            $video->setFilePath($videoData['image_path']);
        }
        return $video;
    }

    function criarSlug($texto) {
        // Converte para minúsculas
        $texto = mb_strtolower($texto, 'UTF-8');
    
        // Substitui caracteres acentuados por equivalentes sem acento
        $texto = iconv('UTF-8', 'ASCII//TRANSLIT', $texto);
    
        // Remove caracteres especiais
        $texto = preg_replace('/[^a-z0-9-]/', '-', $texto);
    
        // Substitui múltiplos hífens por um único
        $texto = preg_replace('/-+/', '-', $texto);
    
        // Remove hífens no início e fim da string
        $texto = trim($texto, '-');
    
        return $texto;
    }

    function slugifyFilename($filename) {
        // Remove a extensão do arquivo
        $fileInfo = pathinfo($filename);
        $name = $fileInfo['filename']; // Nome do arquivo sem extensão
    
        // Converte para minúsculas
        $name = mb_strtolower($name, 'UTF-8');
    
        // Substitui caracteres especiais por seus equivalentes sem acento
        $name = iconv('UTF-8', 'ASCII//TRANSLIT', $name);
    
        // Remove tudo que não for alfanumérico ou espaços
        $name = preg_replace('/[^a-z0-9\s-]/', '', $name);
    
        // Substitui múltiplos espaços ou underscores por um único hífen
        $name = preg_replace('/[\s_]+/', '-', $name);
    
        // Remove hífens extras no começo e no fim
        $name = trim($name, '-');
    
        // Retorna o slug com a extensão original
        return $name . '.' . $fileInfo['extension'];
        // Adiciona um identificador único ao nome do arquivo
        // return $name . '-' . uniqid() . '.' . $fileInfo['extension'];
    }
    
    // Testando a função
    // $filename = "Câmera-Fotográfica 2024 (nova versão).jpg";
    // $slug = slugifyFilename($filename);
    
    // echo $slug; // Saída: "camera-fotografica-2024-nova-versao.jpg"
}
