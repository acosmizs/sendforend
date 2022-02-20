<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CorSeeder extends Seeder
{
    public function run()
    {

        $corModel = new \App\Models\CorModel();


        $cores = [
            [
                'nome' => 'Amarela',
                'descricao' => 'Descricao da cor',
            ],
            [
                'nome' => 'Azul',
                'descricao' => 'Descricao da cor',
            ],
            [
                'nome' => 'Vermelha',
                'descricao' => 'Descricao da cor',
            ],
            [
                'nome' => 'Verde',
                'descricao' => 'Descricao da cor',
            ],
            [
                'nome' => 'Branca',
                'descricao' => 'Descricao da cor',
            ],
            [
                'nome' => 'Preta',
                'descricao' => 'Descricao da cor',
            ],
        ];
        
      //dd($cores); 
        
        foreach($cores as $cor){
            $corModel->insert($cor);
        }

        echo 'Cores inseridas com sucesso!';

    }
}
