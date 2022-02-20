<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Entities\Usuario;

class Usuarios extends BaseController
{

    private $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new \App\Models\UsuarioModel();
    }

    public function index()
    {

        $data = [
            'titulo' => 'Listando os usuários do sistema'

        ];

        return view('Usuarios/index', $data);
    }

    public function recuperaUsuarios()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }
        $atributos = [
            'id',
            'nome',
            'email',
            'ativo',
            'imagem',
        ];
        $usuarios = $this->usuarioModel->select($atributos)
            ->orderBy('id', 'DESC')
            ->findAll();


        $data = [];

        foreach ($usuarios as $usuario) {




            $data[] = [
                'imagem' => $usuario->imagem,
                'nome' => anchor("usuarios/exibir/$usuario->id", esc($usuario->nome), 'title="Exibir usuário ' . esc($usuario->nome) . ' "'),
                'email' => esc($usuario->email),
                'ativo' => ($usuario->ativo == true ? '<i class="fa fa-unlock text-success"></i>&nbsp;Ativo' : '<i class="fa fa-lock text-warning"></i>&nbsp;Inativo'),
            ];
        }

        $retorno = [
            'data' => $data,
        ];

        return $this->response->setJSON($retorno);
    }


    public function criar()
    {

        $usuario = new Usuario();



        $data = [
            'titulo' => "Criando novo usuário",
            'usuario' => $usuario,
        ];
        return view('Usuarios/criar', $data);
    }

    public function cadastrar()
    {

        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        // Envio o hash do token do form
        $retorno['token'] = csrf_hash();

        // Recupero o post da requisição
        $post = $this->request->getPost();


        // Crio novo objeto da Entidade usuário
        $usuario = new Usuario($post);




        if ($this->usuarioModel->protect(false)->save($usuario)) {

            $btnCriar = anchor("usuarios/criar", 'Cadastrar novo usuário', ['class' => 'btn btn-danger mt-2']);

            session()->setFlashdata('sucesso', "Dados salvos com sucesso!<br> $btnCriar");


            // Retornamos oúltimo ID inserido na tabela de usuarios
            //Ou seja, o ID do usuário recém criado
            $retorno['id'] = $this->usuarioModel->getInsertID();

            return $this->response->setJSON($retorno);
        }
        // Retorno de erros de validação
        $retorno['erro'] = 'Por favor, verifique os erros de verificação e tente novamente';
        $retorno['erros_model'] = $this->usuarioModel->errors();

        // Retorno para o ajax request 
        return $this->response->setJSON($retorno);
    }

    public function exibir(int $id = null)
    {

        $usuario = $this->buscausuarioOu404($id);

        $data = [
            'titulo' => "Detalhando o usuário " . esc($usuario->nome),
            'usuario' => $usuario,
        ];
        return view('Usuarios/exibir', $data);
    }

    public function editar(int $id = null)
    {

        $usuario = $this->buscausuarioOu404($id);

        $data = [
            'titulo' => "Editando o usuário " . esc($usuario->nome),
            'usuario' => $usuario,
        ];
        return view('Usuarios/editar', $data);
    }

    public function atualizar()
    {

        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        // Envio o hash do token do form
        $retorno['token'] = csrf_hash();

        // Recupero o post da requisição
        $post = $this->request->getPost();


        // Validamos a existência do usuário
        $usuario = $this->buscaUsuarioOu404($post['id']);

        // Se não foi informado a senha, removemos do $post
        // Se não fizermos dessa formado hashPassword fará o hash de um string vazia
        if (empty($post['password'])) {

            unset($post['password']);
            unset($post['password_confirmation']);
        }

        // Preenchemos os atributos do usuário com os valores do POST
        $usuario->fill($post);

        if ($usuario->hasChanged() == false) {
            $retorno['info'] = 'Não há dados para serem atualizados';
            return $this->response->setJSON($retorno);
        }


        if ($this->usuarioModel->protect(false)->save($usuario)) {

            session()->setFlashdata('sucesso', 'Dados salvos com sucesso!');

            return $this->response->setJSON($retorno);
        }
        // Retorno de erros de validação
        $retorno['erro'] = 'Por favor, verifique os erros de verificação e tente novamente';
        $retorno['erros_model'] = $this->usuarioModel->errors();

        // Retorno para o ajax request 
        return $this->response->setJSON($retorno);
    }


    public function editarImagem(int $id = null)
    {

        $usuario = $this->buscausuarioOu404($id);

        $data = [
            'titulo' => "Alterando a imagem do usuário " . esc($usuario->nome),
            'usuario' => $usuario,
        ];
        return view('Usuarios/editar_imagem', $data);
    }


    public function upload()
    {

        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        // Envio o hash do token do form
        $retorno['token'] = csrf_hash();

        $validacao = service('validation');

        $regras = [
            'imagem' => 'uploaded[imagem]|max_size[imagem,1024]|ext_in[imagem,png,jpg,jpeg,webp]',
        ];

        $mensagens =  [        // Errors
            'imagem' => [
                'uploaded'  => 'Por favor, escolha uma imagem.', 
                'max_size'  => 'Por favor, escolha uma imagem com no máximo 1024px.',
                'ext_in'    => 'Por favor, escolha uma imagem no formato "png, jpg, jpeg, webp".',
            ],
        ];

        $validacao->setRules($regras, $mensagens);

        if ($validacao->withRequest($this->request)->run() == false) {

            $retorno['erro']        = 'Por favor, verifique os erros de verificação e tente novamente';
            $retorno['erros_model'] = $validacao->getErrors();

            // Retorno para o ajax request 
            return $this->response->setJSON($retorno);
        }

        exit('Alteração feita com sucesso!');


        // Recupero o post da requisição
        $post = $this->request->getPost();


        // Validamos a existência do usuário
        $usuario = $this->buscaUsuarioOu404($post['id']);

        // Recuperamos a imagem que veio no post
        $imagem = $this->request->getFile('imagem');


        list($largura, $altura) = getimagesize($imagem->getPathname());
        if($largura < "300" || $altura < "300") {

            $retorno['erro']        = 'Por favor, verifique os erros de verificação e tente novamente';
            $retorno['erros_model'] = ['dimensao' => 'A imagem não pode ser menor que 300 x 300 pixels'];

            // Retorno para o ajax request 
            return $this->response->setJSON($retorno);
        }

        $caminhoImagem = $imagem->store('usuarios');

        $caminhoImagem = WRITEPATH . "uploads/$caminhoImagem";

        print_r($caminhoImagem);
        exit;


        // Se não foi informado a senha, removemos do $post
        // Se não fizermos dessa formado hashPassword fará o hash de um string vazia
        if (empty($post['password'])) {

            unset($post['password']);
            unset($post['password_confirmation']);
        }

        // Preenchemos os atributos do usuário com os valores do POST
        $usuario->fill($post);

        if ($usuario->hasChanged() == false) {
            $retorno['info'] = 'Não há dados para serem atualizados';
            return $this->response->setJSON($retorno);
        }


        if ($this->usuarioModel->protect(false)->save($usuario)) {

            session()->setFlashdata('sucesso', 'Dados salvos com sucesso!');

            return $this->response->setJSON($retorno);
        }
        // Retorno de erros de validação
        $retorno['erro'] = 'Por favor, verifique os erros de verificação e tente novamente';
        $retorno['erros_model'] = $this->usuarioModel->errors();

        // Retorno para o ajax request 
        return $this->response->setJSON($retorno);
    }
    
    


    /**
     *Método que recupera o usuário
     *@param integer $id
     *@return Exceptions|object
     */
    private function buscaUsuarioOu404(int $id = null)
    {
        if (!$id || !$usuario = $this->usuarioModel->withDeleted(true)->find($id)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Não encontramos o usuário $id");
        }
        return $usuario;
    }
}
