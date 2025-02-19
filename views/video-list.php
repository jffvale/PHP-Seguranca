<?php
require_once(dirname(__FILE__) . '/inicio-html.php');
// require_once __DIR__ . '/inicio-html.php';
/** @var \Alura\Mvc\Entity\Video[] $videoList */

function getEmbedUrl(string $url): string{  // Corrige a URL do vídeo para o formato embed
    
    // O YouTube não aceita URLs normais no src. Ele exige que a URL seja no formato embed

    // Se a URL já for no formato embed, retorna como está
    if (strpos($url, 'embed') !== false) {
        return $url;
    }

    // Se for uma URL curta (youtu.be), converte para embed
    if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
        return "https://www.youtube.com/embed/" . $matches[1];
    }

    // Se for uma URL normal do YouTube, converte para embed
    if (preg_match('/v=([a-zA-Z0-9_-]+)/', $url, $matches)) {
        return "https://www.youtube.com/embed/" . $matches[1];
    }

    // Se não for reconhecida, retorna a original (evitar quebrar a página)
    return $url;
}

?>

<ul class="videos__container">
    <?php foreach ($videoList as $video): ?>
        <li class="videos__item">   <!-- As imagens estão armazenadas, mas elas precisam serem buscadas para exibir. -->
            <?php if($video->getFilePath() !== null): ?> <!-- Se o vídeo tiver uma imagem, exibe a imagem -->
            <a href="<?= $video->url; ?>"> <!-- Salvando o link para o vídeo, para mandar para o video correto, mas mostra a iamgem -->
                <img src="/img/uploads/<?= $video->getFilePath(); ?>" alt="<?= $video->title; ?>" style="width: 100%">   <!-- Exibe a imagem do vídeo -->
            </a>
            <?php else: ?> <!-- Se não tiver imagem, exibe o vídeo. Exibe a imagem do vídeo abaixo -->
                <iframe width="100%" height="72%" src="<?= getEmbedUrl($video->url); ?>"
                        title="YouTube video player" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
            <?php endif; ?>
            <div class="descricao-video">
                <h3><?= $video->title; ?></h3>
                <div class="acoes-video">
                    <a href="/editar-video?id=<?= $video->id; ?>">Editar</a>
                    <a href="/remover-video?id=<?= $video->id; ?>">Excluir</a>
                </div>
            </div>
        </li>
    <?php endforeach; ?>
</ul>

<?php require_once(dirname(__FILE__) . '/fim-html.php'); ?>