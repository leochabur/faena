<?php
namespace GestionFaenaBundle\Services\fpdf;

class PDF extends FPDF
{
	private $entidad, $fecha, $logo, $oc, $transporte, $numero;

	public function setData($entidad, $fecha, $numero, $oc, $transporte)
	{
		$this->entidad = $entidad;
		$this->fecha = $fecha;
		$this->numero = $numero;
		$this->oc = $oc;
		$this->transporte = $transporte;
	}

	public function setLogo($logo)
	{
		$this->logo = $logo;
	}

	// Page header
	function Header()
	{
		$x = $this->getX();
		$x-=3;
		$y = $this->getY();
	    // Logo
	 	$this->Image($this->logo,$x+140,6,30);

	 	$this->SetFont('Arial','',12);

	 	if ($this->oc)
	 	{
	 		$this->text($x+60, $y, 'USO EXCLUSIVO DE CAMARA');
	 	}
	 	else
	 	{
	 		$this->text($x+60, $y, 'ORIGINAL');
	 	}
	 	$this->SetFont('Arial','',10);
	 	$this->text($x+60, $y+4, 'Pagina '.$this->PageNo().'/{nb}');
	 	$this->SetFont('Arial','',15);
	 	$this->text($x+60, $y+12, $this->entidad);

	 	$this->SetFont('Arial','',10);
	 	$this->text($x, $y+12, 'REMITO INTERNO');
	 	$this->text($x+150, $y+12, 'FECHA');
	 	$this->Rect($x-1, $y-5, 170, 18);
	 	$this->Rect($x-1, $y+13, 170, 5);
	 	$this->text($x+150, $y+17, $this->fecha->format('d/m/Y'));

	 	$this->text($x, $y+17, ("N ".$this->numero));

	 	$x+= 173;

	 	if (!$this->oc)
	 	{
		

	    // Logo
	 	$this->Image($this->logo,$x+140,6,30);

	 	$this->SetFont('Arial','',12);

	 	$this->text($x+60, $y, 'DUPLICADO');
	 	$this->SetFont('Arial','',10);
	 	$this->text($x+60, $y+4, 'Pagina '.$this->PageNo().'/{nb}');
	 	$this->SetFont('Arial','',15);
	 	$this->text($x+60, $y+12, $this->entidad);

	 	$this->SetFont('Arial','',10);
	 	$this->text($x, $y+12, 'REMITO INTERNO');
	 	$this->text($x+150, $y+12, 'FECHA');
	 	$this->Rect($x-1, $y-5, 170, 18);
	 	$this->Rect($x-1, $y+13, 170, 5);
	 	$this->text($x+150, $y+17, $this->fecha->format('d/m/Y'));
	 	$this->text($x, $y+17, ("N ".$this->numero));
	    
		}
		$this->Ln(20);
	}

	// Page footer
	function Footer()
	{
		$this->SetY(-30);

		$x = $this->getX();
		$y = $this->getY();

	 	$this->SetFont('Arial','',10);
	 	$this->text($x, $y, 'LLEVA.........CANASTOS');
	 	$this->text($x+45, $y, 'OBSERVACIONES');
	 	$this->text($x, $y+5, 'TRAE...........CANASTOS');
	 	$this->Rect($x-1, $y-5, 41, 12);
	 	$this->Rect($x+40, $y-5, 127, 12);
	 	$this->SetFont('Arial','',7);
	 	$this->text($x+3, $y+10, 'NOMBRE FIRMA CONTROL');
	 	$this->Rect($x-1, $y+7, 41, 5);
	 	$this->text($x, $y+20, '._._._._._._._._._._._._._._._._._');
	 	$this->text($x+4, $y+25, 'ENTREGUE CONFORME');
	 	$this->Rect($x-1, $y+12, 41, 15);

	 	$x+=41;
	 	$this->text($x+3, $y+10, 'NOMBRE FIRMA CHOFER');
	 	$this->Rect($x-1, $y+7, 41, 5);
	 	$this->text($x, $y+20, '._._._._._._._._._._._._._._._._._');
	 	$this->text($x+4, $y+25, 'RECIBI CONFORME');
	 	$this->Rect($x-1, $y+12, 41, 15);
	 	$this->text($x+43, $y+10, 'Numero');
	 	$this->text($x+43, $y+14, 'Vehiculo');
	 	$this->Rect($x+40, $y+7, 86, 10);
	 	$this->text($x+43, $y+21, 'Numero');
	 	$this->text($x+43, $y+25, 'Vehiculo');
	 	$this->text($x+55, $y+23, $this->transporte);
	 	$this->Rect($x+40, $y+17, 86, 10);

	 	if (!$this->oc)
	 	{
	 	$x-=41;
	 	$x+=173;
	 	$this->SetFont('Arial','',10);
	 	$this->text($x, $y, 'LLEVA.........CANASTOS');
	 	$this->text($x+45, $y, 'OBSERVACIONES');
	 	$this->text($x, $y+5, 'TRAE...........CANASTOS');
	 	$this->Rect($x-1, $y-5, 41, 12);
	 	$this->Rect($x+40, $y-5, 127, 12);
	 	$this->SetFont('Arial','',7);
	 	$this->text($x+3, $y+10, 'NOMBRE FIRMA CONTROL');
	 	$this->Rect($x-1, $y+7, 41, 5);
	 	$this->text($x, $y+20, '._._._._._._._._._._._._._._._._._');
	 	$this->text($x+4, $y+25, 'ENTREGUE CONFORME');
	 	$this->Rect($x-1, $y+12, 41, 15);

	 	$x+=41;
	 	$this->text($x+3, $y+10, 'NOMBRE FIRMA CHOFER');
	 	$this->Rect($x-1, $y+7, 41, 5);
	 	$this->text($x, $y+20, '._._._._._._._._._._._._._._._._._');
	 	$this->text($x+4, $y+25, 'RECIBI CONFORME');
	 	$this->Rect($x-1, $y+12, 41, 15);
	 	$this->text($x+43, $y+10, 'Numero');
	 	$this->text($x+43, $y+14, 'Vehiculo');
	 	$this->Rect($x+40, $y+7, 86, 10);
	 	$this->text($x+43, $y+21, 'Numero');
	 	$this->text($x+43, $y+25, 'Vehiculo');
	 	$this->text($x+55, $y+23, $this->transporte);
	 	$this->Rect($x+40, $y+17, 86, 10);
	 	}

	}
}