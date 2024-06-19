<form class="flex" action="" method="post" style="flex-wrap: wrap;" enctype="multipart/form-data">

    <input class="form-input" type="text" name="titulo-cadastrar" placeholder="titulo" required>

    <input class="form-input" type="text" name="autor" placeholder="autor">

    <input class="form-input" type="text" name="editora" placeholder="editora" required>

    <input class="form-input" type="number" name="quantidade" placeholder="quantidade" required>

    <input class="form-input" type="text" name="categoria" placeholder="categoria" required>

    <div class="form-input form-file">
            <span class="form-file-label">Escolher arquivo</span>
            <input type="file" name="capa" accept="image/*" required>
    </div>

    <input class="form-input" type="submit" value="Confirmar">

</form>