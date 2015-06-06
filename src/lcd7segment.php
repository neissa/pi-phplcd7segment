<?php

namespace phplcd7segment;
use PhpGpio\Gpio;
class lcd7segment
{
    private $SDI;
    private $SRCLK;
    private $RCLK;
    public static $segement_code = [
        ''=>0b000000000,
        ' '=>0b000000000,
        0=>0x3f,
        1=>0x06,
        2=>0x5b,
        3=>0x4f,
        4=>0x66,
        5=>0x6d,
        6=>0x7d,
        7=>0x07,
        8=>0x7f,
        9=>0x6f,
        'A'=>0x77,
        'B'=>0x7c,
        'C'=>0x39,
        'D'=>0x5e,
        'E'=>0x79,
        'F'=>0x71,
        'G'=>0b00111101,
        'H'=>0b01110110,
        'I'=>0b00110000,
        'J'=>0b00001110,
        'K'=>0x7f,
        'L'=>0b00111000,
        'M'=>0b10110111,
        'N'=>0b00110111,
        'O'=>0b00111111,
        'P'=>0b01110011,
        'Q'=>0b10111111,
        'R'=>0b10111111,
        'S'=>0x6d,
        'T'=>0b01111000,
        'U'=>0b00111110,
        'V'=>0b10111110,
        'W'=>0b11111110,
        'X'=>0b11110110,
        'Y'=>0b11100110, 
        'Z'=>0b11011011,
        ];
    #p c hg bg b bd hd h
    public function getGpio()
    {
        static $gpio;
        if( empty($gpio) )
            $gpio = new GPIO();
        return $gpio;
    }
    public function affiche($quoi)
    {
        $gpio = $this->getGpio();
        $charactere = ' ';
        if( isset(self::$segement_code[strtoupper($quoi)]) )
            $charactere = self::$segement_code[strtoupper($quoi)];
        foreach( range(0, 7) as $bit)
        {
            $gpio->output($this->SDI,(int)min(1,(0x80 & ($charactere << $bit))));
            $gpio->output($this->SRCLK, 1);
            usleep(1);
            $gpio->output($this->SRCLK, 0);
        }
        $gpio->output($this->RCLK, 1);
        usleep(1);
        $gpio->output($this->RCLK, 0);
    }

    public function __construct($SDI,$RCLK,$SRCLK)
    {
        $this->SDI      = $SDI;
        $this->RCLK     = $RCLK;
        $this->SRCLK    = $SRCLK;
        $gpio = $this->getGpio();
        $gpio->setup($this->SDI, 'out');
        $gpio->setup($this->RCLK, 'out');
        $gpio->setup($this->SRCLK, 'out');
    
        $gpio->output($this->SDI, 0);
        $gpio->output($this->RCLK, 0);
        $gpio->output($this->SRCLK, 0);
        return $this;
    }
    public function __destruct()
    {
        $this->getGpio()->unexportAll();
    }
}