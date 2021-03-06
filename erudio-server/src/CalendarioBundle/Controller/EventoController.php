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

namespace CalendarioBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use CoreBundle\REST\AbstractEntityResource;
use CalendarioBundle\Entity\Evento;

/**
 * @FOS\RouteResource("eventos")
 */
class EventoController extends AbstractEntityResource {
    
    function getEntityClass() {
        return 'CalendarioBundle:Evento';
    }
    
    function queryAlias() {
        return 'e';
    }
    
    function parameterMap() {
        return array (
            'nome' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('e.nome LIKE :nome')->setParameter('nome', '%' . $value . '%');
            },
            'descricao' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('e.descricao LIKE :descricao')->setParameter('descricao', '%' . $value . '%');
            },
            'tipo' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('e.tipo = :tipo')->setParameter('tipo', $value);
            }
        );
    }
    
    /**
        *   @ApiDoc()
        */
    function getAction(Request $request, $id) {
        return $this->getOne($id);
    }
    
    /**
        * @ApiDoc()
        * 
        * @FOS\QueryParam(name = "page", requirements="\d+", default = null) 
        * @FOS\QueryParam(name = "nome", nullable = true) 
        * @FOS\QueryParam(name = "descricao", nullable = true) 
        * @FOS\QueryParam(name = "tipo", nullable = true) 
        */
    function cgetAction(Request $request, ParamFetcher $paramFetcher) {
        return $this->getList($paramFetcher);
    }
    
    /**
        *  @ApiDoc()
        * 
        *  @FOS\Post("eventos")
        *  @ParamConverter("evento", converter="fos_rest.request_body")
        */
    function postAction(Request $request, Evento $evento, ConstraintViolationListInterface $errors) {
        if(count($errors) > 0) {
            return $this->handleValidationErrors($errors);
        }
        return $this->create($evento);
    }
    
    /**
        *  @ApiDoc()
        * 
        *  @FOS\Put("eventos/{id}")
        *  @ParamConverter("evento", converter="fos_rest.request_body")
        */
    function putAction(Request $request, $id, Evento $evento, ConstraintViolationListInterface $errors) {
        if(count($errors) > 0) {
            return $this->handleValidationErrors($errors);
        }
        return $this->update($id, $evento);
    }
    
    /**
        *   @ApiDoc()
        */
    function deleteAction(Request $request, $id) {
        return $this->remove($id);
    }

}
