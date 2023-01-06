<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\ORM\Query\ResultSetMapp;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Repository\ArrendatarioRepository;

class SearchController extends AbstractController
{
    #[Route('/searchByArrenAmb', name: 'app_search_arrenamb')]
    public function joinArrendatarioWithAmbiente(EntityManagerInterface $em): Response
    {
        $form = $this->createFormBuilder()
            ->add('arrendatario', ChoiceType::class, ['choices' => $em->getRepository('App\Entity\Arrendatario')->findAllChoices()])
            ->add('ambiente', ChoiceType::class, ['choices' => $em->getRepository('App\Entity\Ambiente')->findAllChoices()])
                ->getForm();

        $rsm = new ResultSetMappingBuilder($em,
            ResultSetMappingBuilder::COLUMN_RENAMING_INCREMENT
        );

        $rsm->addRootEntityFromClassMetadata('App\Entity\Arrendatario', 'a');
        $rsm->addJoinedEntityFromClassMetadata('App\Entity\Ambiente', 'b', 'a',
            'ambientes');

        $query = $em->createNativeQuery(
            'SELECT ' . $rsm->generateSelectClause() .
            ' FROM rentadb.arrendatarios a' .
            ' INNER JOIN rentadb.ambientes b ON a.id=b.arrendatario_id', $rsm);

        $result = $query->getResult();

        return $this->render('search/index_aa.html.twig', [
            'result' =>  $result,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/searchByArrenAmbAjax', name: 'app_search_arrenamb_ajax', methods: 'POST')]
    public function joinArrendatarioWithAmbienteAjax(EntityManagerInterface $em, Request $request) : Response    
    {
        if($request->isXmlHttpRequest()){

            $arrendatario_id = $request->request->get('arrendatario_id');
            $ambiente_id = $request->request->get('ambiente_id');   
            $arrendatario_chk = $request->request->get('arrendatario_chk');
            $ambiente_chk = $request->request->get('ambiente_chk'); 

            $rsm = new ResultSetMappingBuilder($em,
                ResultSetMappingBuilder::COLUMN_RENAMING_INCREMENT
            );

            $rsm->addRootEntityFromClassMetadata('App\Entity\Arrendatario', 'a');
            $rsm->addJoinedEntityFromClassMetadata('App\Entity\Ambiente', 'b', 'a',
                'ambientes');

            $sql = 'SELECT ' . $rsm->generateSelectClause() .
            ' FROM rentadb.arrendatarios a' .
            ' INNER JOIN rentadb.ambientes b ON a.id=b.arrendatario_id';

            $buffer = Array();
            if($arrendatario_chk=="true" && !is_null($arrendatario_id)):
                array_push($buffer,' a.id=' . strval($arrendatario_id));
            endif;
            if($ambiente_chk=="true" && !is_null($ambiente_id)):
                array_push($buffer,' b.id=' . strval($ambiente_id));
            endif;

            if (count($buffer)==1):
                $sql = $sql . ' WHERE ' . $buffer[0];
            elseif (count($buffer)==2):
                $sql = $sql . ' WHERE ( ' . $buffer[0] . ' AND ' . $buffer[1] . ' )';
            endif;

            $query = $em->createNativeQuery($sql, $rsm);

            $result = $query->getResult();

            return $this->render('search/result_aa.html.twig', [
                'result' =>  $result,
            ]);

        }
        
        return new Response ('La vista no esta autorizada, solo se permite ajax',Response::HTTP_UNAUTHORIZED);

    }

    #[Route('/searchByArrenAmbDepo', name: 'app_search_arrenambdepo')]
    public function joinArrendatarioWithAmbienteAndDeposito(EntityManagerInterface $em): Response
    {
        $meses = ["ENE"=>1, "FEB"=>2, "MAR"=>3, "ABR"=>4, "MAY"=>5, "JUN"=>6, "JUL"=>7, "AGO"=>8, "SET"=>9, "OCT"=>10, "NOV"=>11, "DIC"=>12];

        $anios = [];
        for ($anio = 2015; $anio <= 2030; $anio++){
            $anios[strval($anio)]=$anio;
        }

        $form = $this->createFormBuilder()
            ->add('arrendatario', ChoiceType::class, ['choices' => $em->getRepository('App\Entity\Arrendatario')->findAllChoices()])
            ->add('ambiente', ChoiceType::class, ['choices' => $em->getRepository('App\Entity\Ambiente')->findAllChoices()])
            ->add('meses', ChoiceType::class, ['choices' => $meses])
            ->add('anios', ChoiceType::class, ['choices' => $anios])           
            ->add('meses_i', ChoiceType::class, ['choices' => $meses])
            ->add('anios_i', ChoiceType::class, ['choices' => $anios])           
            ->add('meses_f', ChoiceType::class, ['choices' => $meses])
            ->add('anios_f', ChoiceType::class, ['choices' => $anios])          
                ->getForm();

        $rsm = new ResultSetMappingBuilder($em,
            ResultSetMappingBuilder::COLUMN_RENAMING_INCREMENT
        );

        $rsm->addRootEntityFromClassMetadata('App\Entity\Arrendatario', 'a');
        $rsm->addJoinedEntityFromClassMetadata('App\Entity\Ambiente', 'b', 'a',
            'ambientes');
        $rsm->addJoinedEntityFromClassMetadata('App\Entity\Deposito', 'd', 'a',
            'depositos');        

        $query = $em->createNativeQuery(
            'SELECT ' . $rsm->generateSelectClause() .
            ' FROM rentadb.arrendatarios a' .
            ' INNER JOIN rentadb.ambientes b ON a.id=b.arrendatario_id' .
            ' INNER JOIN rentadb.depositos d ON b.id=d.ambiente_id' .
            ' WHERE a.id=d.arrendatario_id' .
            ' ORDER BY a.id,d.anio DESC,' .
            ' CASE' .
                ' WHEN d.mes=\'ENE\' THEN 1' .
                ' WHEN d.mes=\'FEB\' THEN 2' .
                ' WHEN d.mes=\'MAR\' THEN 3' .
                ' WHEN d.mes=\'ABR\' THEN 4' .
                ' WHEN d.mes=\'MAY\' THEN 5' .
                ' WHEN d.mes=\'JUN\' THEN 6' .
                ' WHEN d.mes=\'JUL\' THEN 7' .
                ' WHEN d.mes=\'AGO\' THEN 8' .
                ' WHEN d.mes=\'SET\' THEN 9' .
                ' WHEN d.mes=\'OCT\' THEN 10' .
                ' WHEN d.mes=\'NOV\' THEN 11' .
                ' WHEN d.mes=\'DIC\' THEN 12' .
            ' END DESC', $rsm);



        $result = $query->getResult();
        //dd($result[0]->getDepositos()[0]->getAmbiente());die;
        //dd($result); die;
        return $this->render('search/index_aad.html.twig', [
            'result' =>  $result,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/searchByArrenAmbDepoAjax', name: 'app_search_arrenambdepo_ajax', methods: 'POST')]
    public function joinArrendatarioWithAmbienteAndDepositoAjax(EntityManagerInterface $em, Request $request) : Response    
    {
        if($request->isXmlHttpRequest()){

            $meses = [1=>"ENE", 2=>"FEB", 3=>"MAR", 4=>"ABR", 5=>"MAY", 6=>"JUN", 7=>"JUL", 8=>"AGO", 9=>"SET", 10=>"OCT", 11=>"NOV", 12=>"DIC"];

            $arrendatario_id = $request->request->get('arrendatario_id');
            $ambiente_id = $request->request->get('ambiente_id');   
            $mes = $request->request->get('mes');   
            $anio = $request->request->get('anio');   
            $mes_i = $request->request->get('mes_i');   
            $anio_i = $request->request->get('anio_i');   
            $mes_f = $request->request->get('mes_f');   
            $anio_f = $request->request->get('anio_f');
            $arrendatario_chk = $request->request->get('arrendatario_chk');
            $ambiente_chk = $request->request->get('ambiente_chk'); 
            $mes_chk = $request->request->get('mes_chk'); 
            $anio_chk = $request->request->get('anio_chk'); 
            $periodo_chk = $request->request->get('periodo_chk'); 

            $rsm = new ResultSetMappingBuilder($em,
                ResultSetMappingBuilder::COLUMN_RENAMING_INCREMENT
            );

            $rsm->addRootEntityFromClassMetadata('App\Entity\Arrendatario', 'a');
            $rsm->addJoinedEntityFromClassMetadata('App\Entity\Ambiente', 'b', 'a',
                'ambientes');
            $rsm->addJoinedEntityFromClassMetadata('App\Entity\Deposito', 'd', 'a',
                'depositos');

            $buffer = Array();
            if($arrendatario_chk=="true" && !is_null($arrendatario_id)):
                array_push($buffer,' a.id=' . strval($arrendatario_id));
            endif;
            if($ambiente_chk=="true" && !is_null($ambiente_id)):
                array_push($buffer,' b.id=' . strval($ambiente_id));
            endif;
            if($mes_chk=="true" && !is_null($mes)):
                array_push($buffer,' d.mes=\'' . strval($meses[$mes]) . '\'');
            endif;
            if($anio_chk=="true" && !is_null($anio)):
                array_push($buffer,' d.anio=\'' . strval($anio) . '\'');
            endif;
            if($periodo_chk=="true" && !is_null($mes_i) && !is_null($anio_i) &&
                !is_null($mes_f) && !is_null($anio_f)):
                array_push($buffer,' d.anio>=\'' . strval($anio_i) . '\'' .
                    ' AND d.anio<=\'' . strval($anio_f) . '\'');
            endif;

            $filter = '';
            for($i=0; $i<count($buffer); $i++){
                $filter = $filter . ' AND ' . $buffer[$i];
            }

            $query = $em->createNativeQuery(
                'SELECT ' . $rsm->generateSelectClause() .
                ' FROM rentadb.arrendatarios a' .
                ' INNER JOIN rentadb.ambientes b ON a.id=b.arrendatario_id' .
                ' INNER JOIN rentadb.depositos d ON b.id=d.ambiente_id' .
                ' WHERE a.id=d.arrendatario_id' .
                $filter .
                ' ORDER BY a.id,d.anio DESC,' .
                ' CASE' .
                    ' WHEN d.mes=\'ENE\' THEN 1' .
                    ' WHEN d.mes=\'FEB\' THEN 2' .
                    ' WHEN d.mes=\'MAR\' THEN 3' .
                    ' WHEN d.mes=\'ABR\' THEN 4' .
                    ' WHEN d.mes=\'MAY\' THEN 5' .
                    ' WHEN d.mes=\'JUN\' THEN 6' .
                    ' WHEN d.mes=\'JUL\' THEN 7' .
                    ' WHEN d.mes=\'AGO\' THEN 8' .
                    ' WHEN d.mes=\'SET\' THEN 9' .
                    ' WHEN d.mes=\'OCT\' THEN 10' .
                    ' WHEN d.mes=\'NOV\' THEN 11' .
                    ' WHEN d.mes=\'DIC\' THEN 12' .
                ' END DESC', $rsm);

            $result = $query->getResult();

            return $this->render('search/result_aad.html.twig', [
                'result' =>  $result,
            ]);

        }
        
        return new Response ('La vista no esta autorizada, solo se permite ajax',Response::HTTP_UNAUTHORIZED);

    }

    #[Route('/searchByAmbDepo', name: 'app_search_ambdepo')]
    public function joinAmbienteWithDeposito(EntityManagerInterface $em): Response
    {
        $meses = ["ENE"=>1, "FEB"=>2, "MAR"=>3, "ABR"=>4, "MAY"=>5, "JUN"=>6, "JUL"=>7, "AGO"=>8, "SET"=>9, "OCT"=>10, "NOV"=>11, "DIC"=>12];

        $anios = [];
        for ($anio = 2015; $anio <= 2030; $anio++){
            $anios[strval($anio)]=$anio;
        }

        $form = $this->createFormBuilder()
            ->add('ambiente', ChoiceType::class, ['choices' => $em->getRepository('App\Entity\Ambiente')->findAllChoices()])
            ->add('meses', ChoiceType::class, ['choices' => $meses])
            ->add('anios', ChoiceType::class, ['choices' => $anios])        
                ->getForm();

        $rsm = new ResultSetMappingBuilder($em,
            ResultSetMappingBuilder::COLUMN_RENAMING_INCREMENT
        );

        $rsm->addRootEntityFromClassMetadata('App\Entity\Ambiente', 'a');
        $rsm->addJoinedEntityFromClassMetadata('App\Entity\Deposito', 'd', 'a',
            'depositos');        

        $query = $em->createNativeQuery(
            'SELECT ' . $rsm->generateSelectClause() .
            ' FROM rentadb.ambientes a' .
            ' INNER JOIN rentadb.depositos d ON a.id=d.ambiente_id' .
            ' WHERE a.arrendatario_id=d.arrendatario_id' .
            ' ORDER BY a.id,d.anio DESC,' .
            ' CASE' .
                ' WHEN d.mes=\'ENE\' THEN 1' .
                ' WHEN d.mes=\'FEB\' THEN 2' .
                ' WHEN d.mes=\'MAR\' THEN 3' .
                ' WHEN d.mes=\'ABR\' THEN 4' .
                ' WHEN d.mes=\'MAY\' THEN 5' .
                ' WHEN d.mes=\'JUN\' THEN 6' .
                ' WHEN d.mes=\'JUL\' THEN 7' .
                ' WHEN d.mes=\'AGO\' THEN 8' .
                ' WHEN d.mes=\'SET\' THEN 9' .
                ' WHEN d.mes=\'OCT\' THEN 10' .
                ' WHEN d.mes=\'NOV\' THEN 11' .
                ' WHEN d.mes=\'DIC\' THEN 12' .
            ' END DESC', $rsm);

        $result = $query->getResult();
        //dd($result[0]->getDepositos()[0]->getAmbiente());die;
        //dd($result); die;
        return $this->render('search/index_ad.html.twig', [
            'result' =>  $result,
            'form' => $form->createView(),
        ]);    
    }


    #[Route('/searchByAmbDepoAjax', name: 'app_search_ambdepo_ajax', methods: 'POST')]
    public function joinAmbienteWithDepositoAjax(EntityManagerInterface $em, Request $request) : Response    
    {
        if($request->isXmlHttpRequest()){

            $meses = [1=>"ENE", 2=>"FEB", 3=>"MAR", 4=>"ABR", 5=>"MAY", 6=>"JUN", 7=>"JUL", 8=>"AGO", 9=>"SET", 10=>"OCT", 11=>"NOV", 12=>"DIC"];

            $ambiente_id = $request->request->get('ambiente_id');   
            $mes = $request->request->get('mes');   
            $anio = $request->request->get('anio');   
            $ambiente_chk = $request->request->get('ambiente_chk'); 
            $mes_chk = $request->request->get('mes_chk'); 
            $anio_chk = $request->request->get('anio_chk'); 

            $rsm = new ResultSetMappingBuilder($em,
                ResultSetMappingBuilder::COLUMN_RENAMING_INCREMENT
            );

            $rsm->addRootEntityFromClassMetadata('App\Entity\Ambiente', 'a');
            $rsm->addJoinedEntityFromClassMetadata('App\Entity\Deposito', 'd', 'a',
                'depositos');

            $buffer = Array();
            if($ambiente_chk=="true" && !is_null($ambiente_id)):
                array_push($buffer,' a.id=' . strval($ambiente_id));
            endif;
            if($mes_chk=="true" && !is_null($mes)):
                array_push($buffer,' d.mes=\'' . strval($meses[$mes]) . '\'');
            endif;
            if($anio_chk=="true" && !is_null($anio)):
                array_push($buffer,' d.anio=\'' . strval($anio) . '\'');
            endif;

            $filter = '';
            for($i=0; $i<count($buffer); $i++){
                $filter = $filter . ' AND ' . $buffer[$i];
            }

            $query = $em->createNativeQuery(
                'SELECT ' . $rsm->generateSelectClause() .
                ' FROM rentadb.ambientes a' .
                ' INNER JOIN rentadb.depositos d ON a.id=d.ambiente_id' .
                ' WHERE a.arrendatario_id=d.arrendatario_id' .
                $filter .
                ' ORDER BY a.id,d.anio DESC,' .
                ' CASE' .
                    ' WHEN d.mes=\'ENE\' THEN 1' .
                    ' WHEN d.mes=\'FEB\' THEN 2' .
                    ' WHEN d.mes=\'MAR\' THEN 3' .
                    ' WHEN d.mes=\'ABR\' THEN 4' .
                    ' WHEN d.mes=\'MAY\' THEN 5' .
                    ' WHEN d.mes=\'JUN\' THEN 6' .
                    ' WHEN d.mes=\'JUL\' THEN 7' .
                    ' WHEN d.mes=\'AGO\' THEN 8' .
                    ' WHEN d.mes=\'SET\' THEN 9' .
                    ' WHEN d.mes=\'OCT\' THEN 10' .
                    ' WHEN d.mes=\'NOV\' THEN 11' .
                    ' WHEN d.mes=\'DIC\' THEN 12' .
                ' END DESC', $rsm);

                $result = $query->getResult();

                return $this->render('search/result_ad.html.twig', [
                    'result' =>  $result,
                ]);

        }
        
        return new Response ('La vista no esta autorizada, solo se permite ajax',Response::HTTP_UNAUTHORIZED);

    }

}