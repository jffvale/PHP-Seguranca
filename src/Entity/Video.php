<?php
declare(strict_types=1);

namespace Alura\Mvc\Entity;

class Video{
    public readonly int $id;
    public readonly string $url;
    private ?string $filePath = null;   // O ?string permite que a variável seja nula (string | null), no DB a coluna é image_path

    public function __construct(
        string $url,
        public readonly string $title,
    ) {
        $this->setUrl($url);
    }

    private function setUrl(string $url){
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new \InvalidArgumentException();
        }

        $this->url = $url;
    }

    public function setId(int $id): void{
        $this->id = $id;
    }

    public function setFilePath(?string $filePath): void{   // O ?string permite que a variável seja nula (string | null)
        $this->filePath = $filePath;
    }

    public function getFilePath(): ?string{  // O ?string permite que o retorno seja nulo (string | null)
        return $this->filePath;
    }

    // public function removeImage(): void {
    //     if ($this->filePath !== null && file_exists($this->filePath)) {
    //         unlink($this->filePath); // Remove o arquivo de imagem do diretório
    //     }
    //     $this->filePath = null; // Define o caminho da imagem como nulo
    // }
}
