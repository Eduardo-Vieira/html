<?php
/*
* Class HTML
* Autor: Eduardo Vieira
*/
class HTML
{
    private static $html;
    
    public static function output()
    {
        echo self::$html;
    } 
    /*
    * HTML::ActionForm(['action'=>'','id'=>'from','class'=>'frm']);
    */
    
    public static function actionForm($option=null)
    {
        self::$html .= '<form ';
        self::$html .=  self::attr($option);
        self::$html .= ' >';
        
        return false;
    }

    public static function endForm()
    {
        self::$html .= '</form>';

        return false;
    }

    public static function page($html)
    {
        foreach($html as $k=>$valor)
        {
            self::$html .= $valor;
        }        
    }
    /*
    *  HTML::tag('div',['texto'],['id'=>'idDiv','class'=>'classDiv']);
    */
    public static function tag($tag,$text,$option=null)
    {
        
        $tags_especial = ['br','hr'];
        if(is_array($text))
        {
            foreach($text as $k=>$v)
            {
                $item .= $v;
            }
            $text = $item;
        }

        $input  = '<'.$tag.' ';
        $input .= self::attr($option);
        $input .= ' >'.$text;
        (!in_array($tag,$tags_especial))? $input .= '</'.$tag.'>':false;

        return (String) $input;
    }
    /*
    * rows
    */
    public static function rows($option=null)
    {
        $dados = self::flipDiagonally($option['dados']);
        $col   = array_filter($option['colums']);       
             
        foreach($dados as $key =>$valor)
        {
            ($bgcolor == '#F0F0F0') ? $bgcolor = '#DDDDDD' : $bgcolor = '#F0F0F0';
            $rows .='<tr bgcolor="'.$bgcolor.'" onmouseover="mudarCor(this);" onmouseout="mudarCor(this);" >';
            foreach($col as $k=>$v)
            {
                if(is_array($v))
                {
                    if(!is_array($v['value'])){
                        $rows .= self::tag('td',self::format($valor[$v['value']],$v['format']),$v['option']);
                    }else{
                        $rows .= self::tag('td',self::format($v['value'],$v['format']),$v['option']);
                    } 
                } else{
                        $rows .= self::tag('td',$valor[$v],[]);
                } 
            }
            $rows .='</tr>';
            
        }
        
        return $rows;
    }
    /*
    * table head
    */
    public static function tbHead($option=null)
    {
        $col = array_filter($option['colums']);

        $th .='<tr>';
        foreach($col as $key=>$valor)
        {
            (is_array($valor))? $th .= self::tag('th',$valor['label'],$valor['option']): $th .= self::tag('th',$valor,[]);; 
        }
        $th .='</tr>';

        return $th;
    }

    /*
    * Grid
    */
    public static function grid($option=null)
    {
        $dados = self::flipDiagonally($option['dados']);
        $col   = array_filter($option['colums']);
        $optionTb = $option['option'];
        
        $table = self::tag('table','{head}{rows}',$optionTb); 

        $th = self::tbHead($option);
        
        $rows = self::rows($option);

        $table = str_replace('{head}',$th,$table);
        $table = str_replace('{rows}',$rows,$table);
        
        return $table;
    }
    
    /*
    * HTML::injection('script',['src'=>'arquivojs']);
    */
    public static function injection($tag,$option=null)
    {
        $input  = '<'.$tag.' ';
        $input .= self::attr($option);
        $input .= ' >';
        $input .= '</'.$tag.'>'; 
        
        return $input; 
    }

    public static function input($option=null)
    {
        $input  = '<input ';
        $input .= self::attr($option);
        $input .= ' />'; 
        
        return $input; 
    }
    /*
    * dropDown([$dados,$option])
    */
    public static function dropDown($dados,$col,$selected=null,$option=null)
    {
        
        $select =  self::tag('select','{options}',$option); 
        foreach($dados as $k=>$v)
        {
            if($selected===$v[$col[0]]){
                $optionhtml .= self::tag('option',$v[$col[1]],['value'=>$v[$col[0]],'selected'=>'true']);
            }
            else
            {
                $optionhtml .= self::tag('option',$v[$col[1]],['value'=>$v[$col[0]]]);
            } 
            
        }
       
        return (String) str_replace('{options}', $optionhtml ,$select);
    }

    /*
    * HTML::button(['label'=>'Salvar','id'=>'bntSalvar','class'=>'bntSalvar']);
    */
    public static function button($option=null)
    {
        $label = $option['label'];
        unset($option['label']);
        $inputText ='<button ';
        $inputText .= self::attr($option);
        $inputText .= ' >'.$label.'</button>';
       
        return $inputText;
    }
    
    /*
    * HTML::textArea(['valor'=>'texto','rows'=>'6']);
    */
    public static function textArea($option=null)
    {
        $value = $option['value'];
        unset($option['value']);
        $input  = '<textarea ';
        $input .= self::attr($option);
        $input.= ' >'.$value.'</textarea>';
        
        return $input;
    }
    
    /*
    * função para usar com simpleAutoComplete Js
    * echo HTML::simpleAutoComplete(['dados'=>$dados, 
    *                                'autocomplete'=>'ID_MATERIAL_SERVICO',
    *                                'query'=>$_REQUEST['query'],
    *                                'rel'=>'',
    *                                'descricao'=>'TX_DESCRICAO_GENERICA',
    *                               ]);
    **/
    
    private static function simpleAutoComplete($option=null)
    {
        $result ='';
        $dados = self::flipDiagonally($option['dados']);
        $autocomplete = $option['autocomplete'];
        $query = $option['query'];
        $descricao = $option['descricao'];
        $rel = $option['rel'];
        
        $ul =  self::tag('ul','{li}');
        foreach($dados as $k=>$v)
        {
            $p = $descricao;
            $p = preg_replace('/('.$query.')/i', '<span style="font-weight:bold;">$1</span>', $p);
            $li .= self::tag('li',$p,['id'=>'autocomplete_'.$v[$autocomplete], 'rel'=>$rel]);
        }
  
        return (String) str_replace('{li}', $li, $ul);
    }

    private static function attr($option)
    {        
        $attr ='';
        foreach($option as $k =>$v)
        {
            $attr .= $k.'="'.$v.'"';
        }
        return $attr;
    }

    private static function format($input=null,$format=null)
    {
        switch($format){
            case 'moeda':
                $input =  number_format($input,2,",",".");
                break;
            case 'numero':
                $input =  number_format($input,2,",",".");
                break;
            default:
                $input = $input;
            break;
        }
        return $input;
    }

    // Matriz Transposta - transforma array dados de linhas em colunas
	private static function flipDiagonally($arr)
	{
		$out = array();
		foreach ($arr as $key => $subarr) {
			foreach ($subarr as $subkey => $subvalue) {
				$out[$subkey][$key] = $subvalue;
			}
		}
		return $out;
	}

}

/*    $total1=  $VO->pesquisarConferente();
    $dados1 = $VO->flipDiagonally($VO->getVetor());

    HTML::page([
                 HTML::dropDown($dados1,['ID_USUARIO','TX_NOME'],'4005',['width'=>'100%'])
                ,HTML::grid(['dados'=>$dados,
                            'option' =>['width'=>'100%','class'=>'dataGrid'], 
                            'colums'=>[
                             'TX_NOME',
                             'TX_RECEBEDOR',
                             'TX_TIPO',
                             ['atributo'=>'TX_TIPO',
                              'label'=>'Tipo Resp.',
                              'value'=>'TX_TIPO'
                             ],
                             ['atributo'=>'Col-link',
                              'label'=>'opções',
                              'options'=>['width'=>'100px','style'=>'display:none;'],
                              'value'=> [
                                         HTML::tag('img','',['src'=>$urlimg . 'icones/excluirItem.png','class'=>'bntExcluirItem']),
                                         HTML::tag('img','',['src'=>$urlimg . 'icones/editar.png','class'=>'bntEditarItem'])
                                        ]
                             ],
                             ],
                           ])                               
              ]);
*/