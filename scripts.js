function closeLogin() {
    document.querySelector('.fundo-login').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function() {
    const locacoesList = document.getElementById('locacoes-list');
    const locacoesItems = locacoesList.getElementsByClassName('lista-locacao');
    
    if (locacoesItems.length > 3) {
        locacoesList.style.maxHeight = '450px';
        locacoesList.style.overflowY = 'scroll';
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const formButtons = document.querySelectorAll('.butao-adm[data-form]');
    const formDisplayDiv = document.getElementById('display-form');

    const loadForm = (formFile) => {
        fetch(formFile)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao carregar o formulário');
                }
                return response.text();
            })
            .then(html => {
                formDisplayDiv.innerHTML = html;
            })
            .catch(error => {
                console.error('Erro ao carregar o formulário:', error);
                formDisplayDiv.innerHTML = '<p>Erro ao carregar o formulário</p>';
            });
    };

    formButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            const formFile = event.target.getAttribute('data-form');
            loadForm(formFile);
        });
    });

    loadForm('./src/forms/form-alterar-usuario.php');

});

function mostrarLista(lista) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                document.getElementById('display-list').innerHTML = xhr.responseText;
            } else {
                console.error('Erro ao carregar lista');
            }
        }
    };
    xhr.open('GET', 'carregar_lista.php?lista=' + lista, true);
    xhr.send();

}

mostrarLista('usuarios');

function scrollDown() {
    var historicoCorpo = document.querySelector('.div-display');
    historicoCorpo.scrollTop = historicoCorpo.scrollHeight;
}
scrollDown();

setTimeout(function() {
    var debugMessage = document.getElementById('debugMessage');
    if (debugMessage) {
        debugMessage.style.display = 'none';
    }
}, 2500);