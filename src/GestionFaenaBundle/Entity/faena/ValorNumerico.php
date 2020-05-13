<?php

namespace GestionFaenaBundle\Entity\faena;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ValorNumerico
 *
 * @ORM\Table(name="sp_st_mov_val_nro")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\faena\ValorNumericoRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ValorNumerico extends ValorAtributo
{
    /**
     * @var float
     *
     * @ORM\Column(name="valor", type="float")
     * @Assert\NotNull
     */

    private $valor;

    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\gestionBD\UnidadMedida") 
    * @ORM\JoinColumn(name="id_unt_med", referencedColumnName="id")
    */      
    private $unidadMedida;

    /**
     *
     * @ORM\Column(name="decimales", type="integer", options={"default":0})
     */
    private $decimales = 0;

    /**
     *
     * @ORM\Column(name="acumula", type="boolean", options={"default":true}, nullable=true)
     */
    private $acumula;

    /**
     *
     * @ORM\Column(name="promedia", type="boolean", options={"default":false}, nullable=true)
     */
    private $promedia;

    /**
     * Set valor
     *
     * @param float $valor
     *
     * @return ValorNumerico
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set unidadMedida
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\UnidadMedida $unidadMedida
     *
     * @return ValorNumerico
     */
    public function setUnidadMedida(\GestionFaenaBundle\Entity\gestionBD\UnidadMedida $unidadMedida = null)
    {
        $this->unidadMedida = $unidadMedida;

        return $this;
    }

    /**
     * Get unidadMedida
     *
     * @return \GestionFaenaBundle\Entity\gestionBD\UnidadMedida
     */
    public function getUnidadMedida()
    {
        return $this->unidadMedida;
    }

    public function __toString()
    {
        return $this->getAtributo()->getNombre()." (".$this->unidadMedida.")";
    }

    public function calcularValor($movimiento, $entityManager, $promedio = 0)
    {
        if (false){//$movimiento->getType() == 3){  ///////////////////REVEEERRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRR
           /* if (get_class($this->getAtributo()->getAtributo()) == AtributoMedibleAutomatico::class)
                $this->valor = $movimiento->getFactor()*$movimiento->getValorAtributoConNombre('Aves') * $promedio;*/
        }
        else
        {
            $factores = $this->getAtributo()->getFactoresCalculo(); //obtiene todos los atributos para realizar el calculo del valor del atributo

            if ($factores){
                $repository = $entityManager->getRepository(MovimientoStock::class);
                try{
                            $factor1 = $factor2 = null;
                            if (!isset($factores['factores'][1]))  //no se configuro el factor 1 de la operacion
                            {
                                if (!$factores['factorAjuste']) //si no esta configurado el factor 1 de la operacion y no existe factor de ajuste no se puede calcular el valor
                                {
                                    throw new \Exception("Alguno de los atributos del articulo  ".$movimiento->getArtProcFaena()->getArticulo()." se encuentra mal definido!");
                                }
                                else{
                                    $factor1 = $factores['factorAjuste'];
                                }
                            }
                            else //existe el factor 1
                            {     
                                $f1 = $factores['factores'][1];
                                if ($f1->getTipoCalculo() == 'U')//Significa que se debe buscar el valor unitario del registro dentro del movimiento
                                {
                                    $factor1 = $movimiento->getValorConAtributo($f1->getAtributo());
                                    $factor1 = $factor1->getValor();
                                }     
                                else{ //significa que debe recuperar el valor del acumulado del atributo
                                    $accion = ($f1->getTipoCalculo()=='S'?'s':'p');
                                    $due = $repository->getPromedioAtributo($movimiento->getProcesoFnDay(),
                                                                            $f1->getArticulo(),
                                                                            $f1->getAtributo(),
                                                                            $accion);
                                    $factor1 = $due['stock'];
                                }                  
                            }

                            if (!isset($factores['factores'][2]))  //no se configuro el factor 2 de la operacion
                            {
                                if (!$factores['factorAjuste']) // no esta configurado el factor 2 de la operacion y no existe factor de ajuste
                                {
                                    if ($factores['operacion'] != 'R')
                                    {
                                        throw new \Exception("Alguno de los atributos del articulo  ".$movimiento->getArtProcFaena()->getArticulo()." se encuentra mal definido!");
                                    }
                                    else{
                                        $factor2 = 1;
                                    }
                                }
                                else{
                                    $factor2 = $factores['factorAjuste'];
                                }
                            }
                            else //existe el factor 2
                            {     
                                $f2 = $factores['factores'][2];
                                if ($f2->getTipoCalculo() == 'U')//Significa que se debe buscar el valor unitario del registro dentro del movimiento
                                {
                                    $factor2 = $movimiento->getValorConAtributo($f2->getAtributo());
                                    $factor2 = $factor2->getValor();
                                }     
                                else{ //significa que debe recuperar el valor del acumulado del atributo
                                    $accion = ($f2->getTipoCalculo()=='S'?'s':'p');
                                    $due = $repository->getPromedioAtributo($movimiento->getProcesoFnDay(),
                                                                            $f2->getArticulo(),
                                                                            $f2->getAtributo(),
                                                                            $accion);
                                    $factor2 = $due['stock'];
                                }                  
                            }

                            switch($factores['operacion']) {
                                                case '/': $this->valor = $factor1 / ($factor2?$factor2:$factores['factor']); break;
                                                case '*': $this->valor = $factor1 * ($factor2?$factor2:$factores['factor']); break;
                                                case '+': $this->valor = $factor1 + ($factor2?$factor2:$factores['factor']); break;
                                                case '-': $this->valor = $factor1 - ($factor2?$factor2:$factores['factor']); break;
                                                case 'R': $this->valor = $factor1 * ($factor2?$factor2:$factores['factor']); break;
                            }
                            
                    }
                    catch (\Exception $e) {throw $e;
                     }
            }
        }
    }

    public function getData($redonedo = true)
    {
        $valor = $this->valor;//getMovimiento()->updateValueAtribute($this->getValor());
        if ($redonedo)
            return number_format($valor,($this->getAtributo()?$this->getAtributo()->getDecimales():$this->getDecimales()),'.','');
        else
            return $valor;
    }

    /**
     * Set decimales
     *
     * @param integer $decimales
     *
     * @return ValorNumerico
     */
    public function setDecimales($decimales)
    {
        $this->decimales = $decimales;

        return $this;
    }

    /**
     * Get decimales
     *
     * @return integer
     */
    public function getDecimales()
    {
        return $this->decimales;
    }

    /**
     * Set acumula
     *
     * @param boolean $acumula
     *
     * @return ValorNumerico
     */
    public function setAcumula($acumula)
    {
        $this->acumula = $acumula;

        return $this;
    }

    /**
     * Get acumula
     *
     * @return boolean
     */
    public function getAcumula()
    {
        return $this->acumula;
    }

    /**
     * Set promedia
     *
     * @param boolean $promedia
     *
     * @return ValorNumerico
     */
    public function setPromedia($promedia)
    {
        $this->promedia = $promedia;

        return $this;
    }

    /**
     * Get promedia
     *
     * @return boolean
     */
    public function getPromedia()
    {
        return $this->promedia;
    }

    /**
     * @ORM\PrePersist
     */
    public function setPrePersist()
    {
        //para el caso de la salida de stock, transforma el valor en negativo
        $this->valor = $this->getMovimiento()->updateValueAtribute($this->valor);
    }
}
