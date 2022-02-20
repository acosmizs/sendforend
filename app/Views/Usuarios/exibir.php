<?php echo $this->extend('Layout/principal'); ?>

<?php echo $this->section('titulo') ?> <?php echo $titulo; ?> <?php echo $this->endSection() ?>



<?php echo $this->section('estilos') ?>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.11.4/r-2.2.9/datatables.min.css" />








<?php echo $this->endSection() ?>



<?php echo $this->section('conteudo') ?>


<div class="row">
  <div class="col-lg-4">
    <div class="block">

    

      <div class="text-center">
        <?php if ($usuario->imagem == null) : ?>
          <img src="<?php echo site_url('recursos/img/usuario_sem_imagem.png') ?>" class="card-img-top" style="width: 90%;" alt="Usuário sem imagem">
        <?php else : ?>

          <img src="<?php echo site_url("usuarios/imagem/$usuarios->imagem") ?>" class="card-img-top" style="width: 90%;" alt="<?php echo esc($usuario->nome); ?>">
        <?php endif; ?>
        <a href="<?php echo site_url("usuarios/editarimagem/$usuario->id") ?>" class="btn btn-outline-primary btn-sm mt-3">Alterar imagem</a>
      </div>

      <hr class="border-secondary">
      <h5 class="card-title mt-2"><?php echo esc($usuario->nome); ?></h5>
      <p class="card-text"><?php echo esc($usuario->email); ?></p>
      <p class="card-text"><?php echo ($usuario->ativo == true ? 'Usuário ativo' : 'Usuário inativo'); ?></p>
      <p class="card-text">Criado <?php echo $usuario->criado_em->humanize(); ?></p>
      <p class="card-text">Atualizado <?php echo $usuario->atualizado_em->humanize(); ?></p>
      <!-- Example single danger button -->
      <div class="btn-group">
        <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Ações
        </button>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="<?php echo site_url("usuarios/editar/$usuario->id"); ?>">Editar usuário</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Separated link</a>
        </div>
      </div>
      <a href="<?php echo site_url("usuarios") ?>" class="btn btn-secondary ml-2">Voltar</a>
    </div> <!--block -->
  
    

  </div>
</div>


<div class="row">





  <?php echo $this->endSection() ?>


  <?php echo $this->section('scripts') ?>
  <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.11.4/r-2.2.9/datatables.min.js"></script>

  <script>
    $(document).ready(function() {

      const DATATABLE_PTBR = {
        "sEmptyTable": "Nenhum registro encontrado",
        "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
        "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
        "sInfoFiltered": "(Filtrados de _MAX_ registros)",
        "sInfoPostFix": "",
        "sInfoThousands": ".",
        "sLengthMenu": "_MENU_ resultados por página",
        "sLoadingRecords": "Carregando...",
        "sProcessing": "Processando...",
        "sZeroRecords": "Nenhum registro encontrado",
        "sSearch": "Pesquisar",
        "oPaginate": {
          "sNext": "Próximo",
          "sPrevious": "Anterior",
          "sFirst": "Primeiro",
          "sLast": "Último"
        },
        "oAria": {
          "sSortAscending": ": Ordenar colunas de forma ascendente",
          "sSortDescending": ": Ordenar colunas de forma descendente"
        },
        "select": {
          "rows": {
            "_": "Selecionado %d linhas",
            "0": "Nenhuma linha selecionada",
            "1": "Selecionado 1 linha"
          }
        }
      }

      $('#ajaxTable').DataTable({

        "oLanguage": DATATABLE_PTBR,

        "ajax": "<?php echo site_url('usuarios/recuperausuarios'); ?>",
        "columns": [{
            "data": "imagem"
          },
          {
            "data": "nome"
          },
          {
            "data": "email"
          },
          {
            "data": "ativo"
          },
        ],
        "deferRender": true,
        "processing": true,
        "language": {
          processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>',
        },
        "responsive": true,
        "pagingType": $(window).width() < 768 ? "simple" : "simple",

      });
    });
  </script>
  <?php echo $this->endSection() ?>