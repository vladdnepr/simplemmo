<?php
namespace VladDnepr\SimpleMMO;
 
class Server {

    const REQUEST_NAME_PREFIX = 'request_';
    const REQUEST_DEFAULT_NAME = 'default';

    protected $contaner;

    function __construct(\Pimple\Container $container)
    {
        $this -> contaner = $container;
    }


    public function handle($uri)
    {
        try {
            try {
                $request = $this -> contaner[self::REQUEST_NAME_PREFIX . $this -> getRequestNameFromURI($uri)];
            } catch (\InvalidArgumentException $e) {
                throw new BadRequestException("Request not found");
            }

            $response_data = $request -> handle();

            echo $this -> contaner['serializer'] -> encode($response_data);
        } catch(BadRequestException $e) {
            header('HTTP/1.1 400 Bad Request');
            echo $e -> getMessage();
        } catch (\Exception $e) {
            header('HTTP/1.1 500 Server Error');
            echo $e -> getMessage();
        }
    }

    public function getRawPOST()
    {
        return file_get_contents("php://input");
    }

    protected function getRequestNameFromURI($uri)
    {
        $nodes = array_filter(explode('/', $uri));
        return !empty($nodes) ? reset($nodes) : self::REQUEST_DEFAULT_NAME;
    }
}