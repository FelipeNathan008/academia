<?php

namespace App\View\Components;

use Illuminate\View\Component;

class GraduacaoCard extends Component
{
    public $titulo;
    public $subtitulo;
    public $valor;
    public $borderColor;
    public $bgColor;
    public $textColor;
    public $valueColor;
    public $circleStyle;

    public function __construct(
        $titulo,
        $subtitulo,
        $valor,
        $borderColor,
        $bgColor,
        $textColor,
        $valueColor,
        $circleStyle
    ) {
        $this->titulo = $titulo;
        $this->subtitulo = $subtitulo;
        $this->valor = $valor;
        $this->borderColor = $borderColor;
        $this->bgColor = $bgColor;
        $this->textColor = $textColor;
        $this->valueColor = $valueColor;
        $this->circleStyle = $circleStyle;
    }

    public function render()
    {
        return view('components.graduacao-card');
    }
}