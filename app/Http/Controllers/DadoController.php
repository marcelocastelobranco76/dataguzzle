<?php
namespace App\Http\Controllers;
use App\Dado;
use Illuminate\Http\Request;
use Auth;
use Session;
use Redirect;
use View;
use DB;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Client as GuzzleClient;

class DadoController extends Controller
{

    public function __construct() { /** Método __construct especificando o middleware auth (só usuário logado e autorizado tem acesso aos métodos abaixo) **/
      $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /** Lista todas as vigências e seus respectivos valores, e mostra 10 resultados por página **/
        $dados = Dado::orderBy('id','asc')->paginate(3);
        $arrayDados = DB::select("SELECT vigencia,valor_mensal FROM dados");
        return view('dados.index', compact('dados','arrayDados'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function extrair()
    {

      $goutteClient = new Client();
      $guzzleClient = new GuzzleClient(array(
        'timeout' => 60,
      ));
      /** Utlizando Guzzle para acessar o site http://www.guiatrabalhista.com.br/salario_minimo.htm
      * e o web crawler Goutte para tratar o html retornado.
      **/
      $goutteClient->setClient($guzzleClient);
      $crawler = $goutteClient->request('GET', 'http://www.guiatrabalhista.com.br/guia/salario_minimo.htm');
      $retorno = $crawler->filterXPath('//table')->filter('tr')->siblings()->each(function ($coluna, $i) {
          return $coluna->filter('td')->each(function ($linha, $i) {
          return trim($linha->text());
        });
        });;

        /** Loop para manter o arrayRetorno apenas com as posições 0 e 1 (array multidimensional) **/
          for($contador = 0; $contador <= count($retorno); $contador++){
              for($contadorInterno = 2; $contadorInterno <= 5; $contadorInterno++) {
                    unset($retorno[$contador][$contadorInterno]);
              }
          }
              DB::delete('DELETE FROM dados');
                  /** Grava na tabela dados **/
              DB::update("ALTER TABLE dados AUTO_INCREMENT = 1;");
              foreach($retorno as $key => $value){
                  $dado = new Dado;
                  $dado->vigencia = $value[0];
        	        $dado->valor_mensal = $value[1];
                  $dado->save();
              }
           /** Redireciona **/
           Session::flash('message', 'Vigências e respectivos valores mensais cadastrados com sucesso ! ');
           return Redirect::to('dados');
    }






}
