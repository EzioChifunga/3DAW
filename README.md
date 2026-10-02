# Water Falls - Jogo Corporativo (3DAW)

Cadastro de **Usuários**, **Perguntas** e **Respostas** para o jogo de treinamento de gestores.
Feito em PHP puro (procedural), com os dados salvos em arquivos texto (`.txt`) separados por `;`.

## Funcionalidades

| # | Funcionalidade | Arquivo |
|---|----------------|---------|
| 1 | Criar pergunta de múltipla escolha | `incluirPerguntaMultipla.php` |
| 2 | Criar pergunta de texto | `incluirPerguntaTexto.php` |
| 3 | Alterar pergunta de múltipla escolha | `alterarPerguntaMultipla.php?id=N` |
| 4 | Alterar pergunta de texto | `alterarPerguntaTexto.php?id=N` |
| 5 | Listar perguntas e respostas | `listarPerguntas.php` |
| 6 | Listar uma pergunta | `listarPergunta.php?id=N` |
| 7 | Excluir pergunta e respostas | `excluirPergunta.php?id=N` |
| 8 | CRUD de usuários | `incluirUsuario.php`, `listarUsuarios.php`, `alterarUsuario.php`, `excluirUsuario.php` |

Arquivos de apoio:

- `index.php`: página inicial com o menu
- `menu.php`: menu incluído em todas as telas
- `funcoes.php`: leitura e gravação dos arquivos `.txt`, além das validações
- `estilo.css`: visual das telas

## Arquivos de dados (pasta `dados/`)

Eles são criados sozinhos na primeira vez que o sistema é usado:

```
usuarios.txt   id;nome;email;senha;perfil      (senha gravada com password_hash)
perguntas.txt  id;tipo;enunciado;resposta      (tipo = multipla | texto)
respostas.txt  idPergunta;letra;texto          (alternativas das perguntas de múltipla escolha)
```

- Na pergunta de **múltipla escolha**, a coluna `resposta` guarda a letra correta (A–E).
- Na pergunta de **texto**, a coluna `resposta` guarda a resposta esperada.
- Se o usuário digitar `;`, ele é trocado por `,`, para não quebrar o formato do arquivo.

## Como rodar no XAMPP

Requisito: XAMPP com PHP 8.0 ou mais novo.

### Opção 1: copiar para o htdocs

1. Copie a pasta do projeto para `C:\xampp\htdocs\3DAW`.
2. Abra o **XAMPP Control Panel** e clique em **Start** no **Apache**.
3. Acesse <http://localhost/3DAW/>.

### Opção 2: link simbólico (o código continua na pasta do GitHub)

Abra o **Prompt de Comando como Administrador** e rode:

```
mklink /D C:\xampp\htdocs\3DAW C:\caminho\para\o\repositorio\3DAW
```

Depois inicie o Apache e acesse <http://localhost/3DAW/>.

### Opção 3: servidor embutido do PHP do XAMPP (sem Apache)

Na pasta do projeto, rode:

```
C:\xampp\php\php.exe -S localhost:8000
```

Depois acesse <http://localhost:8000/>.

> Todos os caminhos do sistema são relativos, então ele funciona em qualquer pasta e em qualquer máquina.
> No Linux/macOS, a pasta `dados/` precisa ter permissão de escrita para o usuário do Apache.
