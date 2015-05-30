<?php 
namespace VladDnepr\SimpleMMO\Request;
 
use VladDnepr\SimpleMMO\BadRequestException;
use VladDnepr\SimpleMMO\Model\Character;

abstract class API extends AbstractRequest {

    protected $methods_available = array();
    protected $data_required = array();
    protected $need_character = true;

    /**
     * @var Character
     */
    protected $character;

    function __construct(\Pimple\Container $container)
    {
        parent::__construct($container);

        if ($this -> need_character) {
            $this -> data_required[] = 'token';
        }
    }


    public function handle()
    {
        if (!in_array($_SERVER["REQUEST_METHOD"], $this -> methods_available)) {
            throw new BadRequestException('Request method not supported');
        }

        $data = array();

        $post = $this -> container['server'] -> getRawPOST();

        if (!empty($post)) {

            // Decode request body
            try {
                $data = $this -> container['serializer'] -> decode($post);
            } catch (\InvalidArgumentException $e) {
                throw new BadRequestException("Invalid json");
            }
        }

        // Check required fields in request data
        foreach ($this -> data_required as $key_required) {
            if (!isset($data[$key_required])) {
                throw new BadRequestException("Key "  .$key_required . " in request body missed");
            }
        }

        // Check, if needed, token field and handle data
        if (!$this -> need_character || $this -> character = $this -> getCharacterFromRequest($data['token'])) {
            $response_data = $this -> handleData($data);
        } else {
            $response_data = array('error' => 'Character not found');
        }

        $serializer = $this -> container['serializer'];
        header("Content-Type: " . $serializer -> getContentType());

        return $serializer -> encode($response_data);
    }

    protected function getCharacterFromRequest($token)
    {
        $char = null;

        if ($this -> need_character) {
            $char = $this -> container['character_repository'] -> getByToken($token);
        }

        return $char;
    }

    abstract function handleData($data);
}