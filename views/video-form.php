<?php
require_once __DIR__ . '/inicio-html.php';
/** @var \Alura\Mvc\Entity\Video|null $video */
?>
<main class="container">
    <!-- enctype escolhe o tipo de codificação de dados a ser usada ao enviar o formulário, o padrão é texto -->
    <!-- multipart/form-data é usado quando o formulário contém um controle de upload de arquivo -->
    <form class="container__formulario"
        enctype="multipart/form-data"
        method="post">
        <h2 class="formulario__titulo">Envie um vídeo!</h2>
        <div class="formulario__campo">
            <label class="campo__etiqueta" for="url">Link embed</label>
            <input name="url"
                value="<?= $video?->url; ?>"
                class="campo__escrita"
                required
                placeholder="Por exemplo: https://www.youtube.com/embed/FAY1K2aUg5g"
                id='url' />
        </div>

        <div class="formulario__campo">
            <label class="campo__etiqueta" for="titulo">Titulo do vídeo</label>
            <input name="titulo"
                value="<?= $video?->title; ?>"
                class="campo__escrita"
                required
                placeholder="Neste campo, dê o nome do vídeo"
                id='titulo' />
        </div>

        <div class="formulario__campo">
            <label class="campo__etiqueta" for="image">Imagem do vídeo</label>
            <!-- mostra somente o conteudo do image_path no novo campo, abaixo -->
            <label for="image">&emsp;&emsp;<?php echo ($video?->getFilePath() !== null) ? $video?->getFilePath() : "não há imagem selecionada"; ?></label>
            <?php // Se o caminho da imagem não for nulo, define path como true
            $path = false;
            if($video !== null && $video->getFilePath() !== null){
                $path = true;
            } else {
                $path = false;
            }
            ?>   <!-- Se o caminho da imagem não for nulo, define path como true -->
            <input type="hidden" name="path" value="<?php echo $path; ?>">
            <!-- Botão do tipo <input> com o nome "Deletar" -->
            <input name="image"
                accept="image/*"
                type="file"
                class="campo__escrita"
                id='image' />
        </div>

        <input class="formulario__botao" type="submit" value="Enviar" />
    </form>
</main>

<?php
require_once __DIR__ . '/fim-html.php';
