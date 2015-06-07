<?php 
namespace phplcd7segment;
require_once 'vendor/autoload.php';

class messagelcd
{
    const GPIO_LOW = 0;
    const GPIO_HIGH = 1;
    const GAUCHE = 0;
    const DROITE = 1;
    private $lcds = array();
    public static function cli()
    {
        if ('cli' == PHP_SAPI)
        {
            global $argc;
            global $argv;
            $phpled = new messagelcd();
            $phpled->run(isset($argc)?$argc:'',isset($argv)?$argv:'');
        }
    }
    public function run($argc, $argv)
    {
        if ('cli' == PHP_SAPI) 
        {
            /*if ('root' !== $_SERVER['USER'] || empty($_SERVER['SUDO_USER'])) {
                echo $msg = "Please run this script as root, using sudo -t ; please check the README file";
                throw new \Exception($msg);
            }*/
            
            $lcds = array(
                    'lcd1'=>array('SDI'=>18,'RCLK'=>23,'SRCLK'=>24),
                    'lcd2'=>array('SDI'=>6,'RCLK'=>13,'SRCLK'=>19)
            );
            $this->setup($lcds);
            //$this->heure();
            //$this->compteur($argv[1], 10);
            $this->messageDefilant($argv[1], 200, self::GAUCHE, self::DROITE);
            $this->messageFixe($argv[1], 200);
        }
        else
        {
            //var_dump('sudo php '.__FILE__.' "'.(isset($_REQUEST['q'])?$_REQUEST['q']:'salut').'"');
            exec('sudo php '.__FILE__.' "'.(isset($_REQUEST['q'])?$_REQUEST['q']:'salut').'"', $a);
            echo '<pre>';var_dump($a);
        
        }
    }
    public static function shutdwon()
    {
        exec('pkill -f "'.__FILE__.'"');
    }
    public function messageDefilant($texte, $vitesse = 300, $demarre=self::DROITE ,$direction=self::GAUCHE, $infini=false)
    {
        $taille_chaine  = strlen($texte);
        $padding        = str_repeat(' ',count($this->lcds));
        $texte          = ($demarre==self::DROITE?$padding:'').$texte.($demarre!=self::DROITE?$padding:'');
        if( $direction == self::DROITE)
            $texte = strrev($texte);
        $lcds = $direction==self::DROITE?array_reverse ($this->lcds):$this->lcds;
        do
        {
            foreach( range(0,$taille_chaine) as $i)
            {
                $lcd_number = 0;
                foreach( $lcds as $lcd )
                {
                    $lcd->affiche($texte[''.$i+$lcd_number++]);
                }
                usleep($vitesse*1000);
            }
        }while($infini);
    }
    public function heure()
    {
        do
        {
            $this->messageDefilant(date('d m Y H\hi'), $vitesse = 400, $demarre=self::GAUCHE);
        }
        while(0&&true);
        
    }
    public function compteur($nombre, $vitesse = 300)
    {
        $nombre = min($nombre, pow(10, count($this->lcds))-1 );
        $i=0;
        for( $i = 0; $i < $nombre; $i++)
        {
            $this->messageFixe(str_pad($nombre-$i,count($this->lcds),'0',STR_PAD_LEFT));
            usleep($vitesse*1000);
        }
    }
    public function messageFixe($texte)
    {
        $taille_chaine  = strlen($texte);
        $texte         .= str_repeat(' ',count($this->lcds));
        $lcd_number = 0;
        foreach( $this->lcds as $lcd )
            $lcd->affiche($texte[''.$lcd_number++]);
    }
    public function setup($lcds)
    {
        foreach( $lcds as $lcd )
        {
            $this->lcds[] = new lcd7segment($lcd['SDI'],$lcd['RCLK'],$lcd['SRCLK']);
        }
    }
}
messagelcd::cli();
