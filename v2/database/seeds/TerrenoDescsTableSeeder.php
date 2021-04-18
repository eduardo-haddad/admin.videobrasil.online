<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TerrenoDescsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = collect([
            'first_impact' => [
                'O melhor terreno em [neighborhood].',
                'Sua construção em um terreno destaque em [neighborhood].',
                'Realize seu sonho com este terreno em [neighborhood].',
                'Sua melhor escolha é este terreno em [neighborhood]',
                'Construa seu sonho no terreno destaque em [neighborhood].',
                'Terreno exclusivo em [neighborhood].',
                'A melhor opção de terreno em [neighborhood].',
                'A melhor escolha de terreno em [neighborhood].',
                'Terreno lindo em [neighborhood].',
                'O terreno ideal em [neighborhood].',
                'Exclusivo! Terreno impecável em [neighborhood].',
                'Este terreno em [neighborhood] esta te aguardando!',
                'Terreno impecável em [neighborhood].',
                'Torne seus sonhos reais neste terreno em [neighborhood]',
                'Terreno impecável, a melhor opção em [neighborhood].',
                'Lindo terreno em [neighborhood].',
                'O melhor terreno de [neighborhood].',
                'Seu terreno está em [neighborhood].',
                'Excelente terreno em [neighborhood].',
                'Impecável, a melhor escolha em [neighborhood].'
            ],

            'area_sm' => [
                'São [area] m² perfeitos para construir a casa dos sonhos!',
                'Com [area] m² na medida para a sua residencia.',
                'São [area] m² ideais para construir sua casa otimizada e funcional.',
                'São [area] m² pensados para uma construção inteligente e personalizada.',
                '[area] m² na medida para uma construção funcional e dinâmica.',
                'Com [area] m², ideiais para uma construção compacta.',
                'São [area] m² na medida de uma construção funcional e personalizada.',
                'Com [area] m² para um imóvel compacto e otimizado.',
                'São [area] m² para uma construção compacta e personalizada.',
                'Com [area] m² na medida certa da sua nova casa.',
                '[area] m²,cada centimetro ideal para uma construção inteligente.',
                'São [area] m² pensados para construir na medida certa.',
                'Com [area] m² planejados para uma construção inteligente..',
                'São [area]m² para uma construção compacta e funcional.',
                '[area] m², cada centimetro perfeito para um imóvel compacto e funcional.',
                'São [area] m², na medida de uma construção inteligente.',
                'São [area]m² perfeitos para uma construção inteligente.',
                'Com [area] m²,cada centimetro pensado para uma construção otimizada.',
                'Com [area] m² ideais para um imóvel inteligente.',
                'São [area] m² pensados para um imóvel funcional e dinâmico.'
            ],

            'area_md' => [
                'São [area] perfeitos para um imóvel moderno e prático.',
                'Com [area] m² perfeitos para uma construção confortável e atraente.',
                'São [area] m², perfeitos para a construção de imóveis atrativos e confortáveis.',
                'Com [area] m² ideais para a construção de um imóvel confortável e moderno.',
                'São [area] m², pensados para um imóvel confortável e versátil.',
                'São [area] m², na medida de um imóvel convidativo e acolhedor.',
                'São [area] m² na medida de um imóvel acolhedor e moderno.',
                'São [area] m², perfeitos para a construção de imóveis versáteis e práticos.',
                'São [area] m², para a construção de espaçoes versáteis e adaptáveis.',
                'São [area] m² perfeitos para um imóvel dinâmico e moderno.',
                'Com [area] m², ideiais para a construção de um imóvel adaptável e moderno.',
                'São [area] m², tamanho perfeito para espaços modernos e convidativos.',
                'São [area] m² perfeitos para a construção de um imóvel versátil e prático.',
                'Com [area] m², tamanho perfeito para imóveis modernos e atrativos.',
                'Com [area] m² na medida de uma construção versátil e moderna.',
                'Com [area] m², no tamanho ideal para uma construção versátil e moderna.',
                'São [area] m², ideais para um imóvel moderno e aconhegante.',
                'Com [area] m², tamanho ideal para uma construção moderna e versátil.',
                'São [area] m², a medida perfeita de um imóvel moderno e acolhedor.',
                'São [area] m² para uma construção adaptável e prática.'
            ],

            'area_lg' => [
                'Com [area] m², para uma construção ampla e multifuncional.',
                'Com [area] m², na medida de construções modernas e amplas.',
                'São [area] m², perfeitos para uma construção moderna com grandes áreas.',
                'São [area] m², a medida de construções de grandes espaços.',
                'São [area] m², na medida de um imóvel confortável e com áreas amplas.',
                'Com [area] m², para a construção de imóveis com grandes áreas.',
                'São [area] m², na medida de imóveis amplos. ',
                'São [area] m², perfeitos para um imóvel amplo e multifuncional.',
                'Com [area] m², ideais para a construção de grandes espaços multifuncionais. ',
                'São [area] m², para um imóvel confortável e amplo.',
                'São [area] m², ideal para a construção de espaços amplos e versáteis.',
                'Com [area] m², para a construção de grandes imóveis. ',
                'Com [area] m², para uma construção multifuncional e versátil.',
                'São [area] m², para construção de imóveis amplos e grandes espaços.',
                'São [area] m², para grandes imóveis, áreas amplas e modernas.',
                'São [area] m², que permitem a construção de imóveis amplos e multifuncionais.',
                'São [area] m², ideais para um imóvel confortável e amplo. ',
                'Com [area] m², perfeitos para a construção de áreas amplas e confortáveis.',
                'São [area] m², para a construção de grandes áreas e espaços confortáveis.',
                'Com [area] m², perfeitos para um imóvel amplo e confortável.'
            ],

            'price_20_lt_avg' => [
                'Um achado! ',
                'O melhor preço do mercado!',
                'Melhor momento para fechar negócio!',
                'Melhor oportunidade do mercado!',
                'Um verdadeiro presente!',
                'As melhores condições do mercado!',
                'Não deixe para depois! ',
                'Oportunidade imperdível! ',
                'Sua melhor chance!',
                'Sua melhor oportunidade de negócio! ',
                'Um verdadeiro achado!',
                'Melhor hora para comprar!',
                'Hora certa para negociar!',
                'A hora certa de fechar negócio!',
                'Melhor hora para fechar negócio! ',
                'Momento certo para sua compra!',
                'Seu momento de investir!',
                'Melhor momento para sua compra!',
                'Melhor hora para investir!',
                'O melhor valor!'
            ],

            'price_bt_2_n_19_lt_avg' => [
                'Excelente oportunidade de negócio!',
                'Aproveite a chance!',
                'Excelente negócio!',
                'Momento excelente para fechar! ',
                'Essa oportunidade vale a pena!',
                'Ótimo momento para fazer negócio! ',
                'Aproveite, vale a pena! ',
                'Oportunidade de ouro! ',
                'Realize um excelente negócio!',
                'Não perca essa oportunidade de ouro! ',
                'Não perca essa excelente oportunidade!',
                'Momento perfeito para sua compra! ',
                'Hora excelente para realizar sua compra!',
                'Oportunidade de ouro para investir!',
                'Não deixe a chance passar!',
                'Excelente oportunidade de investimento!',
                'Seu momento de fechar um negócio de ouro!',
                'Oportunidade de ouro para investimento!',
                'Chance de ouro!',
                'Excelente negócio! Não perca a oportunidade.'
            ],

            'price_eq_avg' => [
                'Bom investimento!',
                'Esse é um bom negócio!',
                'Faça um investimento seguro!',
                'Boas condições para negociação.',
                'Bom momento para investir.',
                'Investimento seguro!',
                'Aproveite essa oportunidade!',
                'Um bom negócio e com boas condições. ',
                'Aproveite as boas condições do mercado.',
                'Boa oportunidade de investimento.',
                'Não deixe passar a oportunidade.',
                'Boas condições para investir.',
                'Momento favorável para compra.',
                'Boa hora para investir.',
                'Momento favorável para investimento',
                'Boas condições para compra.',
                'Não deixe a oportunidade passar!',
                'Condições favoráveis para compra.',
                'Bom momento para comprar.',
                'Bom momento para comprar.'
            ],

            'price_gt_avg' => [
                'Mercado aquecido!',
                'Oportunidade de valorização.',
                'Seu investimento em um destaque no mercado!',
                'Essa é uma oportunidade em valorização!',
                'Em alta no mercado!',
                'Oportunidade de investimento em valorização!',
                'Oportunidade valorizada no mercado!',
                'Oportunidade de investimento aquecido no mercado.',
                'Chance de investimento com potencial de crescimento e valorização!',
                'Aproveite a chance em crescimento e valorização!',
                'Área em valorização!',
                'Chance de investir em um dos mais desejados! ',
                'Entre os mais desejados!',
                'O mais buscado!',
                'Oportunidade em destaque no mercado!',
                'Região em crescimento.',
                'Entre os destaques do mercado! ',
                'Entre as oportunidades mas desejadas!',
                'Entre os mais buscados!',
                'Um dos favoritos do mercado!'
            ],

            'real_state' => [
                'Não perca tempo! Entre em contato com [client] agora!',
                'Ligue agora e converse com [client]!',
                'Contate [client] e fale com um corretor.',
                'Entre em contato com o corretor, ligue para [client].',
                'Fale com um dos corretores, contate [client].',
                'Ligue para [client] para falar com um dos corretores.',
                'Converse com o corretor! Ligue para [client].',
                'Fale agora mesmo com [client].',
                'Entre em contato com [client].',
                'Fale com o corretor! Ligue para [client]',
                'Não deixe a para depois, fale agora com [client]!',
                'Não demore a contatar o corretor!Ligue agora para [client].',
                'Contate agora [client].',
                'Fale agora com o corretor, ligue para [client].',
                'Ligue para [client] e converse com um dos corretores.',
                'Para conversar com o corretor ligue para [client].',
                'Fale com o corretor! Contate [client] agora.',
                'Ligue agora para [client] e converse com um dos corretores.',
                'Converse agora mesmo com [client]!',
                'Fale agora com [client]!'
            ],
        ]);

        $data = [];

        foreach($tags as $tag => $texts){
            $descs = array_map(function($text) use($tag){
                return [
                    'text' => $text,
                    'tag' => $tag,
                    'type' => 'terreno',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ];
            }, $texts);

            $data = array_merge($data, $descs);
        }

        DB::table('auto_descs')->insert($data);
    }
}
