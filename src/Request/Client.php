<?php 
namespace VladDnepr\SimpleMMO\Request;
 
class Client extends AbstractRequest{

    function handle()
    {
        $r = $this -> container['template'] -> render('client.html.twig', array('name' => 'Fabien'));
        return $r;
    }


}