<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *    @author Municipio de Itajaí - Secretaria de Educação - DITEC         *
 *    @updated 30/06/2016                                                  *
 *    Pacote: Erudio                                                       *
 *                                                                         *
 *    Copyright (C) 2016 Prefeitura de Itajaí - Secretaria de Educação     *
 *                       DITEC - Diretoria de Tecnologias educacionais     *
 *                        ditec@itajai.sc.gov.br                           *
 *                                                                         *
 *    Este  programa  é  software livre, você pode redistribuí-lo e/ou     *
 *    modificá-lo sob os termos da Licença Pública Geral GNU, conforme     *
 *    publicada pela Free  Software  Foundation,  tanto  a versão 2 da     *
 *    Licença   como  (a  seu  critério)  qualquer  versão  mais  nova.    *
 *                                                                         *
 *    Este programa  é distribuído na expectativa de ser útil, mas SEM     *
 *    QUALQUER GARANTIA. Sem mesmo a garantia implícita de COMERCIALI-     *
 *    ZAÇÃO  ou  de ADEQUAÇÃO A QUALQUER PROPÓSITO EM PARTICULAR. Con-     *
 *    sulte  a  Licença  Pública  Geral  GNU para obter mais detalhes.     *
 *                                                                         *
 *    Você  deve  ter  recebido uma cópia da Licença Pública Geral GNU     *
 *    junto  com  este  programa. Se não, escreva para a Free Software     *
 *    Foundation,  Inc.,  59  Temple  Place,  Suite  330,  Boston,  MA     *
 *    02111-1307, USA.                                                     *
 *                                                                         *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace CursoBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use JMS\Serializer\Annotation as JMS;
use CoreBundle\ORM\AbstractEditableEntity;

/**
* @ORM\Entity
* @ORM\Table(name = "edu_turma")
*/
class Turma extends AbstractEditableEntity {
    
    const STATUS_CRIADO = 'CRIADO',
          STATUS_EM_ANDAMENTO = 'EM_ANDAMENTO',
          STATUS_ENCERRADO = 'ENCERRADO';
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\Column(nullable = false) 
    */
    private $nome;
    
    /** 
     * @JMS\Groups({"LIST"})
     * @ORM\Column(nullable = false) 
     */
    private $apelido;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\Column(nullable = false) 
    */
    private $status;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\Column(name = "limite_alunos", type = "integer", nullable = false) 
    */
    private $limiteAlunos;
    
    /**
    * @JMS\Type("DateTime<'Y-m-d\TH:i:s'>")
    * @ORM\Column(name="data_encerramento", type="datetime", nullable=false) 
    */
    protected $dataEncerramento;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @JMS\Type("PessoaBundle\Entity\UnidadeEnsino")
    * @ORM\ManyToOne(targetEntity = "PessoaBundle\Entity\UnidadeEnsino")
    * @ORM\JoinColumn(name = "unidade_ensino_id") 
    */
    private $unidadeEnsino;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\ManyToOne(targetEntity = "Etapa") 
    */
    private $etapa;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\ManyToOne(targetEntity = "Turno") 
    */
    private $turno;
    
    /** 
    * @ORM\ManyToOne(targetEntity = "CalendarioBundle\Entity\Calendario")
    */
    private $calendario;
    
    /** 
    * @JMS\MaxDepth(depth = 1)
    * @ORM\ManyToOne(targetEntity = "CalendarioBundle\Entity\QuadroHorario")
    * @ORM\JoinColumn(name = "quadro_horario_id") 
    */
    private $quadroHorario;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\ManyToOne(targetEntity = "AgrupamentoTurma")
    * @ORM\JoinColumn(name = "turma_agrupamento_id") 
    */
    private $agrupamento;
    
    /**
    * @JMS\Exclude 
    * @ORM\OneToMany(targetEntity = "DisciplinaOfertada", mappedBy = "turma", cascade = {"persist"}) 
    */
    private $disciplinas;
    
    /** 
    * @JMS\Exclude
    * @ORM\OneToMany(targetEntity = "MatriculaBundle\Entity\Enturmacao", mappedBy = "turma", fetch="EXTRA_LAZY") 
    */
    private $enturmacoes;
    
    /** 
    * @JMS\Exclude
    * @ORM\OneToMany(targetEntity = "Vaga", mappedBy = "turma", fetch="EXTRA_LAZY") 
    */
    private $vagas;
    
    function init() {
        $this->status = self::STATUS_CRIADO;
        $this->enturmacoes = new ArrayCollection();
        $this->solicitacoes = new ArrayCollection();
        $this->vagas = new ArrayCollection();
        if($this->etapa->getIntegral()) {
            $this->disciplinas = new ArrayCollection();
            foreach($this->etapa->getDisciplinas() as $disciplina) {
                $disciplinaOfertada = new DisciplinaOfertada($this, $disciplina);
                $this->disciplinas->add($disciplinaOfertada);
            }
        }
    }
    
    function getNome() {
        return $this->nome;
    }

    function getApelido() {
        return $this->apelido;
    }

    function getLimiteAlunos() {
        return $this->limiteAlunos;
    }

    function getUnidadeEnsino() {
        return $this->unidadeEnsino;
    }

    function getEtapa() {
        return $this->etapa;
    }

    function getTurno() {
        return $this->turno;
    }
    
    function getCalendario() {
        return $this->calendario;
    }
    
    function getQuadroHorario() {
        return $this->quadroHorario;
    }

    function getAgrupamento() {
        return $this->agrupamento;
    }
    
    function getDisciplinas() {
        return $this->disciplinas;
    }
    
    function getDataEncerramento() {
        return $this->dataEncerramento;
    }

    function getEnturmacoes() {
        return $this->enturmacoes->matching(
            Criteria::create()->where(Criteria::expr()->andX(              
                Criteria::expr()->eq('ativo', true), 
                Criteria::expr()->eq('encerrado', false)
            ))
        );
    }
        
    /**
    * @JMS\Groups({"LIST"})
    * @JMS\VirtualProperty
    */
    function getEncerrado() {
        return $this->status === self::STATUS_ENCERRADO;
    }
    
    /**
    * @JMS\Groups({"LIST"})
    * @JMS\VirtualProperty
    */
    function getQuantidadeAlunos() {
        return $this->getEnturmacoes()->count();
    }
    
    /**
    * @JMS\Groups({"LIST"})
    * @JMS\VirtualProperty
    */
    function getNomeCompleto() {
        return $this->getApelido() ? $this->getNome() . ' - ' . $this->getApelido() : $this->getNome();
    }
    
    function setNome($nome) {
        $this->nome = $nome;
    }

    function setApelido($apelido) {
        $this->apelido = $apelido;
    }

    function setLimiteAlunos($limiteAlunos) {
        $this->limiteAlunos = $limiteAlunos;
    }

    function setTurno(Turno $turno) {
        $this->turno = $turno;
    }
    
    function setAgrupamento(AgrupamentoTurma $agrupamento = null) {
        $this->agrupamento = $agrupamento;
    }
    
    function getStatus() {
        return $this->status;
    }

    function setStatus($status) {
        $this->status = $status;
    }
    
    function setQuadroHorario($quadroHorario) {
        $this->quadroHorario = $quadroHorario;
    }
    
    function getVagas() {
        return $this->vagas;
    }
    
    function getVagasAbertas() {
         return $this->vagas->matching(
            Criteria::create()->where(Criteria::expr()->andX(              
                Criteria::expr()->eq('ativo', true), 
                Criteria::expr()->isNull('enturmacao')
            ))
        );
    }
    
}
