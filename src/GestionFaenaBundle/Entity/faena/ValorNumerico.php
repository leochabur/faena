<?php

namespace GestionFaenaBundle\Entity\faena;
use GestionFaenaBundle\Entity\gestionBD\AtributoMedibleAutomatico;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * ValorNumerico
 *
 * @ORM\Table(name="sp_st_mov_val_nro")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\faena\ValorNumericoRepository")
 */
class ValorNumerico extends ValorAtributo
{
    /**
     * @var float
     *
     * @ORM\Column(name="valor", type="float")
     * @Assert\NotBlank
     */

    private $valor;

    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\gestionBD\UnidadMedida") 
    * @ORM\JoinColumn(name="id_unt_med", referencedColumnName="id")
    */      
    private $unidadMedida;

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

    public function calcularValor($movimiento, $promedio = 0)
    {
        if ($movimiento->getType() == 3){
            if (get_class($this->getAtributo()->getAtributo()) == AtributoMedibleAutomatico::class)
                $this->valor = $movimiento->getValorAtributoConNombre('Aves') * $promedio;
        }
        else
        {
            $factores = $this->getAtributo()->getAtributo()->getFactoresCalculo();

            if ($factores){
                if ($factores['ajustable']){
                    $this->valor = $factores['factor']*$movimiento->getValorConAtributo($factores['factores'][1])->getValor();
                }
                else{
                    try{
                        $factor1 = $movimiento->getValorConAtributo($factores['factores'][1])->getValor();
                        $factor2 = null;
                        if ($factores['factores'][2])
                            $factor2 = $movimiento->getValorConAtributo($factores['factores'][2])->getValor();
                        switch($factores['operacion']) {
                                            case '/': $this->valor = $factor1 / ($factor2?$factor2:$factores['factor']); break;
                                            case '*': $this->valor = $factor1 * ($factor2?$factor2:$factores['factor']); break;
                                            case '+': $this->valor = $factor1 + ($factor2?$factor2:$factores['factor']); break;
                                            case '-': $this->valor = $factor1 - ($factor2?$factor2:$factores['factor']); break;
                                        }
                        }
                        catch (\Exception $e) {throw new \Exception(get_class($factor2), 1);
                        }
                }
            }
        }
    }

    public function getData($redonedo = true)
    {
        $valor = $this->getMovimiento()->updateValueAtribute($this->getValor());
        if ($redonedo)
            return number_format($valor,$this->getAtributo()->getDecimales(),',','');
        else
            return $valor;
    }
}
