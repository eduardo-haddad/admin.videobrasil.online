<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ComercialDescsTableSeeder extends Seeder
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
                'Aqui excelente unidade de [property_type] em [neighborhood],',
                'Melhor opção de [property_type] em [neighborhood],',
                'Unidade de [property_type], melhor investimento em [neighborhood],',
                'A melhor escolha de [property_type] em [neighborhood], ',
                'A escolha certa de [property_type] para investir em [neighborhood],',
                'A melhor oportunidade em (property_typea) comercial para o seu investimento em [neighborhood],',
                'A escolha certa de [property_type] em [neighborhood],',
                'Melhor oportunidade de [property_type] em (bairro,',
                'Excelente [property_type] comercial em [neighborhood],',
                'Belissima unidade de [property_type] em [neighborhood],',
                'Otimas opções de [property_type] em [neighborhood],',
                'Belissimas opções de [property_type] em [neighborhood].',
                'Unidade exclusiva de [property_type]  em [neighborhood],',
                'Unidades impecáveis de [property_type] em [neighborhood],',
                'Impecável [property_type] comercial em [neighborhood],',
                'Favorito da região! Unidade de [property_type] em [neighborhood],',
                'Unidades selecionadas a dedo, melhores opções de [property_type] em [neighborhood],',
                'O melhor [property_type] em [neighborhood],'
            ],

            'area_sm' => [
                'são [area] m², na medida para escritórios e outras atividades.',
                'são [area] m², no tamanho exato de escritórios otimizados e funcionais.',
                'são [area] m², para atividades comerciais.',
                'são [area] m², na medida para ambientes de negócios.',
                'são [area] m², planejados para atividades profissionais.',
                'são [area] m², ambientes compactos planejados para atividades profissionais.',
                'são [area] m², na medida de escritórios dinâmicos e compactos.',
                'são [area] m², compactos e perfeitos para negócios.',
                'são [area] m², ideais para atividades profissionais e investimentos.',
                'são [area] m², funcionais e planejados para profissionais.',
                'são [area] m², ideais para investimentos e prestação de serviços.',
                'são [area] m², compactos e na medida de bons negócios.',
                'são [area] m², para empreendedores e prestadores de serviços.',
                'são [area] m², na medida para prestação de serviços.',
                'com [area] m², para negócios e prestação de serviços.',
                'são [area] m², espaços compactos para atividades profissionais.',
                'são [area] m², ´perfeitos para escritórios compactos e funcionais.',
                'são [area] m², otimizados para profissionais.',
                'são [area] m², perfeitos para atividades profissionais.',
                'são [area] m², ideais para escritórios dinâmicos e funcionais.'
            ],

            'area_md' => [
                'são [area] m², ambientes funcionais projetados para profissionais.',
                'são [area] m², espaços planejados para profissionais modernos e exigentes.',
                'são [area] m², projetados para prestação de serviços e outras atividades profissionais.',
                'são [area] m², planejados para ambientes de trabalho.',
                'são [area] m², para ambientes profissionais versáteis e dinâmicos.',
                'são [area] m², na medida para profissionais exigentes e modernos.',
                'são [area] m², na medida de escritórios e espaços profissionais.',
                'são [area] m², na medida para ambientes profissionais modernos e práticos.',
                'são [area] m², tamanho perfeito para espaços de trabalho.',
                'são [area] m², na medida de ambientes profissionais modernos e versáteis.',
                'são [area] m², planejados para ambientes de negócios.',
                'são [area] m², espaços modernos e planejados para atividades profissionais.',
                'são [area] m², perfeitos para ambientes ambientes de trabalho modernos.',
                'são [area] m², planejados para espaços profissionais práticos e versáteis.',
                'são [area] m², perfeitos para profissionais modernos.',
                'são [area] m², customizados para profissionais dinâmicos.',
                'são [area] m², ambientes planejados para profissionais.',
                'são [area] m², espaço ideal para escritórios modernos.',
                'são [area] m², tamanho perfeito para escritórios modernos e espaços profissionais.',
                'são [area] m², ambientes versáteis ideiais para profissionais.'
            ],

            'area_lg' => [
                'são [area] m², para escritórios e ambientes de trabalho amplos.',
                'são [area] m², para empresas e profissionais que buscam áreas amplas.',
                'são [area] m², para escritórios espaçosos e multifuncionais.',
                'são [area] m², espaços ideais para escritórios amplos para empresase profissionais.',
                'são [area] m², ambientes amplos para empresas e profissionais.',
                'são [area] m², ambientes amplos para profissionais, empresas e investimentos.',
                'são [area] m², amplos para profissionais e empresas.',
                'são [area] m², para profissionais exigentes que buscam espaços amplos.',
                'são [area] m², ambientes espaçosos para profissionais que buscam conforto e versatilidade.',
                'são [area] m², para profissionais e empresas em busca de grandes espaços. ',
                'são [area] m², ambientes de negócio espaçosos, confortáveis e multifuncionais.',
                'são [area] m², perfeitos e amplos para realização de atividades profissionais.',
                'são [area] m², para empresas, profissionais e investidores em busca de áreas espaçosas.',
                'são [area] m², ambientes de trabalho projetados com espaço e conforto.',
                'são [area] m², para empresas e profissionais em busca de mutifuncionalidade e espaço.',
                'são [area] m², áreas amplas para atividades profissionais diversas.',
                'são [area] m², projetados para profissionais e empresas que priorizam espaço e versatilidade.',
                'são [area] m², com áreas amplas para ambientes de trabalho de alto nível.',
                'são [area] m², medida ideal para escritórios amplos e multiplas opções de ambientes.',
                'são [area] m², espaços amplos para profissionais e empresas. '
            ],

            'real_state' => [
                'Confira, entre em contato para negociar com [client].',
                'Converse com o corretor! Ligue para [client].',
                'Para mais informações, contate [client].',
                'Fale com o corretor, contate [client].',
                'Converse com um dos corretores, contate [client].',
                'Verifique mais informações, fale com [client].',
                'Converse com um dos corretores, contate [client].',
                'Consulte o especialista, contate [client]',
                'Não deixe a para depois, fale agora com [client]!',
                'Consulte as condições, entre em contato com [client].',
                'Entre em contato com o especialista, ligue para [client].',
                'Consulte mais informações,contate [client].',
                'Converse com os especialistas, entre em contato com [client].',
                'Entre em contato com o corretor, ligue para [client].',
                'Verifique as condições, ligue para [client].',
                'Converse com o corretor responsável, fale com [client].',
                'Para conversar com o corretor ligue para [client].',
                'Consulte agora as condições, fale com um dos corretores ',
                'Fale agora mesmo com [client].',
                'Realize um bom negócio, contate [client].'
            ],
        ]);

        $data = [];

        foreach($tags as $tag => $texts){
            $descs = array_map(function($text) use($tag){
                return [
                    'text' => $text,
                    'tag' => $tag,
                    'type' => 'comercial',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ];
            }, $texts);

            $data = array_merge($data, $descs);
        }

        DB::table('auto_descs')->insert($data);
    }
}
