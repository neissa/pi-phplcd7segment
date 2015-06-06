<?php 
namespace phplcd7segment;
require_once 'vendor/autoload.php';

class messagelcd
{
    const GPIO_LOW = 0;
    const GPIO_HIGH = 1;
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
            if ('root' !== $_SERVER['USER'] || empty($_SERVER['SUDO_USER'])) {
                echo $msg = "Please run this script as root, using sudo -t ; please check the README file";
                throw new \Exception($msg);
            }
            
            $lcds = array(
                    'lcd1'=>array('SDI'=>18,'RCLK'=>23,'SRCLK'=>22),
                    'lcd2'=>array('SDI'=>6,'RCLK'=>13,'SRCLK'=>19)
            );
            $this->setup($lcds);
            $texte          = $argv[1];
            $taille_chaine  = strlen($texte);
            $texte         .= str_repeat(' ',count($this->lcds));
            foreach( range(0,$taille_chaine) as $i)
            {
                $lcd_number = 0;
                foreach( $this->lcds as $lcd )
                    $lcd->affiche($texte[''.$i+$lcd_number++]);
                usleep(300*1000);
            }
        }
        else
        {
            //var_dump('sudo php '.__FILE__.' "'.(isset($_REQUEST['q'])?$_REQUEST['q']:'salut').'"');
            exec('sudo php '.__FILE__.' "'.(isset($_REQUEST['q'])?$_REQUEST['q']:'salut').'"', $a);
            echo '<pre>';var_dump($a);
        
        }
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