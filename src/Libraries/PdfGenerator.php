<?php

/**
 * Generador de PDF para reportes
 * Usa FPDF - Librería gratuita de PHP para generar PDFs
 */

// Primero, incluir FPDF si no está cargado
if (!class_exists('FPDF')) {
    require_once __DIR__ . '/fpdf/fpdf.php';
}

class PdfGenerator extends FPDF {
    private $primaryColor = [30, 58, 95]; // #1e3a5f
    private $headerText = '';
    private $footerText = 'Escuela Pablo Neruda - Barrio Las Malvinas, Sector 4 Berlín';
    
    /**
     * Constructor
     */
    public function __construct($orientation = 'P', $unit = 'mm', $size = 'Letter') {
        parent::__construct($orientation, $unit, $size);
        $this->SetAutoPageBreak(true, 25);
        $this->SetMargins(15, 15, 15);
    }
    
    /**
     * Configurar encabezado del documento
     */
    public function setHeaderText($text) {
        $this->headerText = $text;
    }
    
    /**
     * Encabezado de página
     */
    public function Header() {
        // Barra superior azul más delgada
        $this->SetFillColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
        
        // Ajustar ancho según orientación
        $pageWidth = $this->getPageWidth();
        $this->Rect(0, 0, $pageWidth, 15, 'F');
        
        // Icono de escuela (círculo con símbolo)
        $this->SetFillColor(255, 255, 255);
        $this->Circle(15, 7.5, 4.5, 'F');
        $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
        $this->SetFont('Arial', 'B', 14);
        $this->SetXY(11, 3.5);
        $this->Cell(8, 8, utf8_decode('E'), 0, 0, 'C');
        
        // Nombre de la institución
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 14);
        $this->SetXY(25, 5);
        $this->Cell(0, 5, utf8_decode('Escuela Pablo Neruda'), 0, 0, 'L');
        
        // Subtítulo del reporte
        if ($this->headerText) {
            $this->SetFont('Arial', '', 8);
            $this->SetXY(25, 10);
            $this->Cell(0, 3, utf8_decode($this->headerText), 0, 0, 'L');
        }
        
        $this->Ln(18);
    }
    
    /**
     * Dibujar círculo
     */
    private function Circle($x, $y, $r, $style = 'D') {
        $this->Ellipse($x, $y, $r, $r, $style);
    }
    
    /**
     * Dibujar elipse
     */
    private function Ellipse($x, $y, $rx, $ry, $style = 'D') {
        if ($style == 'F') {
            $op = 'f';
        } elseif ($style == 'FD' || $style == 'DF') {
            $op = 'B';
        } else {
            $op = 'S';
        }
        $lx = 4/3 * (M_SQRT2 - 1) * $rx;
        $ly = 4/3 * (M_SQRT2 - 1) * $ry;
        $k = $this->k;
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F m %.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x+$rx)*$k, ($h-$y)*$k,
            ($x+$rx)*$k, ($h-($y-$ly))*$k,
            ($x+$lx)*$k, ($h-($y-$ry))*$k,
            $x*$k, ($h-($y-$ry))*$k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x-$lx)*$k, ($h-($y-$ry))*$k,
            ($x-$rx)*$k, ($h-($y-$ly))*$k,
            ($x-$rx)*$k, ($h-$y)*$k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x-$rx)*$k, ($h-($y+$ly))*$k,
            ($x-$lx)*$k, ($h-($y+$ry))*$k,
            $x*$k, ($h-($y+$ry))*$k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c %s',
            ($x+$lx)*$k, ($h-($y+$ry))*$k,
            ($x+$rx)*$k, ($h-($y+$ly))*$k,
            ($x+$rx)*$k, ($h-$y)*$k,
            $op));
    }
    
    /**
     * Pie de página
     */
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 7);
        $this->SetTextColor(128, 128, 128);
        
        // Línea divisoria
        $this->SetDrawColor(200, 200, 200);
        $pageWidth = $this->getPageWidth();
        $this->Line(15, $this->GetY(), $pageWidth - 15, $this->GetY());
        $this->Ln(2);
        
        // Información de la escuela a la izquierda
        $this->Cell($pageWidth / 3, 4, utf8_decode($this->footerText), 0, 0, 'L');
        
        // Fecha en el centro
        $this->Cell($pageWidth / 3, 4, utf8_decode('Generado: ' . date('d/m/Y H:i')), 0, 0, 'C');
        
        // Número de página a la derecha
        $this->Cell($pageWidth / 3 - 30, 4, utf8_decode('Página ') . $this->PageNo() . ' de {nb}', 0, 0, 'R');
    }
    
    /**
     * Agregar título de sección
     */
    public function addSectionTitle($title) {
        $this->SetFillColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(0, 7, utf8_decode($title), 0, 1, 'L', true);
        $this->Ln(2);
    }
    
    /**
     * Agregar subtítulo
     */
    public function addSubtitle($text) {
        $this->SetFont('Arial', 'B', 10);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(0, 6, utf8_decode($text), 0, 1, 'L');
        $this->Ln(2);
    }
    
    /**
     * Agregar texto normal
     */
    public function addText($text) {
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(0, 0, 0);
        $this->MultiCell(0, 5, utf8_decode($text));
        $this->Ln(2);
    }
    
    /**
     * Agregar información en formato de tarjeta
     */
    public function addInfoCard($label, $value, $width = 60) {
        $this->SetFont('Arial', 'B', 9);
        $this->SetTextColor(100, 100, 100);
        $this->Cell($width, 6, utf8_decode($label . ':'), 0, 0, 'L');
        
        $this->SetFont('Arial', '', 9);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(0, 6, utf8_decode($value), 0, 1, 'L');
    }
    
    /**
     * Crear tabla con encabezado azul
     */
    public function createTable($headers, $data, $widths) {
        // Encabezado de tabla
        $this->SetFillColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 9);
        
        foreach ($headers as $i => $header) {
            $width = isset($widths[$i]) ? $widths[$i] : 20;
            $this->Cell($width, 7, utf8_decode($header), 1, 0, 'C', true);
        }
        $this->Ln();
        
        // Datos de la tabla
        $this->SetFillColor(240, 240, 240);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial', '', 8);
        
        $fill = false;
        foreach ($data as $row) {
            // Verificar si hay espacio suficiente para la fila
            if ($this->GetY() > 250) {
                $orientation = $this->DefOrientation == 'P' ? 'P' : 'L';
                $this->AddPage($orientation);
                
                // Repetir encabezado en nueva página
                $this->SetFillColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
                $this->SetTextColor(255, 255, 255);
                $this->SetFont('Arial', 'B', 9);
                
                foreach ($headers as $i => $header) {
                    $width = isset($widths[$i]) ? $widths[$i] : 20;
                    $this->Cell($width, 7, utf8_decode($header), 1, 0, 'C', true);
                }
                $this->Ln();
                
                $this->SetFillColor(240, 240, 240);
                $this->SetTextColor(0, 0, 0);
                $this->SetFont('Arial', '', 8);
            }
            
            foreach ($row as $i => $cell) {
                $align = 'L';
                $width = isset($widths[$i]) ? $widths[$i] : 20;
                // Convertir valores a string
                $cellValue = is_null($cell) ? '' : (string)$cell;
                $this->Cell($width, 6, utf8_decode($cellValue), 1, 0, $align, $fill);
            }
            $this->Ln();
            $fill = !$fill;
        }
        
        $this->Ln(5);
    }
    
    /**
     * Agregar caja de alerta/información
     */
    public function addAlertBox($text, $type = 'info') {
        // Colores según el tipo
        $colors = [
            'info' => [52, 152, 219],
            'success' => [25, 135, 84],
            'warning' => [255, 193, 7],
            'danger' => [220, 53, 69]
        ];
        
        $color = $colors[$type] ?? $colors['info'];
        
        $boxWidth = $this->getPageWidth() - 30; // Ancho dinámico
        
        $this->SetFillColor($color[0], $color[1], $color[2]);
        $this->SetDrawColor($color[0], $color[1], $color[2]);
        $this->Rect($this->GetX(), $this->GetY(), $boxWidth, 7, 'FD');
        
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell($boxWidth, 7, utf8_decode($text), 0, 1, 'C');
        $this->Ln(2);
    }
    
    /**
     * Obtener ancho de página disponible
     */
    public function getPageWidth() {
        return $this->w;
    }
    
    /**
     * Obtener alto de página disponible
     */
    public function getPageHeight() {
        return $this->h;
    }
    
    /**
     * Agregar estadísticas en recuadros
     */
    public function addStatBox($label, $value, $width = 60, $x = null) {
        $currentY = $this->GetY();
        
        if ($x !== null) {
            $this->SetX($x);
        }
        
        // Fondo y borde
        $this->SetFillColor(248, 249, 250);
        $this->SetDrawColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
        $this->SetLineWidth(0.5);
        $this->Rect($this->GetX(), $currentY, $width, 15, 'FD');
        $this->SetLineWidth(0.2);
        
        // Etiqueta
        $this->SetFont('Arial', '', 7);
        $this->SetTextColor(100, 100, 100);
        $this->SetXY($x ?? $this->GetX(), $currentY + 2);
        $this->Cell($width, 4, utf8_decode($label), 0, 0, 'C');
        
        // Valor
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
        $this->SetXY($x ?? $this->GetX(), $currentY + 7);
        $this->Cell($width, 6, utf8_decode((string)$value), 0, 0, 'C');
    }
}

