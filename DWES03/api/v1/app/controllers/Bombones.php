<?php
class Bombones{
        private $dataFile;

        //Constructor
        public function __construct() {
            $this->dataFile = __DIR__ . '/../../data/bombones.json';
        }
    
        //Obtener todos los bombones
        public function getAllBombones() {
            $bombones = json_decode(file_get_contents($this->dataFile), true);

            //Respuesta success
            if ($bombones) {
                echo json_encode([
                    'status' => 'OK',
                    'code' => '200',
                    'message' => 'Bombones obtenidos exitosamente.',
                    'response' => $bombones
                ]);

            //Respuesta error    
            } else {
                http_response_code(404);
                echo json_encode([
                    'status' => 'ERROR',
                    'code' => '404',
                    'response' => 'No se encontraron bombones'
                ]);
            }
        }
    
        //Obtener un bombón por su ID
        public function getBombonesById($id) {
            $bombones = json_decode(file_get_contents($this->dataFile), true);

            //Filtra el array para recoger el bombon con la id elegida
            $bombon = array_filter($bombones, fn($b) => $b['id'] == $id);
    
            //Respuesta error de id no encontrado   
            if (empty($bombon)) {
                http_response_code(404);
                echo json_encode([
                    'status' => 'ERROR',
                    'code' => '404',
                    'response' => 'Bombón no encontrado'
                ]);

            //Respuesta success    
            } else {
                echo json_encode([
                    'status' => 'OK',
                    'code' => '200',
                    'message' => 'Bombón encontrado exitosamente.',
                    'response' => array_values($bombon)[0]
                ]);
            }
        }
    
        //Crear un nuevo bombón
        public function createBombon($data) {
            
            //Respuesta error de campos faltantes
            if (!isset($data['nombre'], $data['descripcion'], $data['precio'], $data['imagen'], $data['stock'])) {
 
                http_response_code(400);
                echo json_encode([
                    'status' => 'ERROR',
                    'code' => '400',
                    'response' => 'Faltan campos necesarios'
                ]);
                return;
            }
    
            $bombones = json_decode(file_get_contents($this->dataFile), true);
            //Asignar un nuevo ID
            $data['id'] = end($bombones)['id'] + 1;  
            
    
            $bombones[] = $data;
            file_put_contents($this->dataFile, json_encode($bombones, JSON_PRETTY_PRINT));
    
            //Respuesta success   
            echo json_encode([
                'status' => 'OK',
                'code' => '201',
                'message' => 'Bombón creado exitosamente.',
                'response' => $data
            ]);
        }
    
        //Actualizar un bombón por su ID
        public function updateBombon($id, $data) {
            $bombones = json_decode(file_get_contents($this->dataFile), true);
    
            $found = false;
            foreach ($bombones as &$bombon) {
                if ($bombon['id'] == $id) {
                    $bombon = array_merge($bombon, $data);
                    $found = true;
                    break;
                }
            }
            
            //Respuesta error de id no encontrado
            if (!$found) {
                http_response_code(404);
                echo json_encode([
                    'status' => 'ERROR',
                    'code' => '404',
                    'response' => 'Bombón no encontrado'
                ]);
                return;
            }
    
            //Respuesta success   
            file_put_contents($this->dataFile, json_encode($bombones, JSON_PRETTY_PRINT));
            echo json_encode([
                'status' => 'OK',
                'code' => '200',
                'message' => 'Bombón actualizado exitosamente.',
                'response' => $data
            ]);
        }
    
        //Eliminar un bombón por su ID
        public function deleteBombon($id) {
            $bombones = json_decode(file_get_contents($this->dataFile), true);

            //Filtra el array eliminando el bombon elegido
            $bombones = array_filter($bombones, fn($b) => $b['id'] != $id);
    
            //Compara el array con el original para ver si se ha eliminado algo
            if (count($bombones) == count(json_decode(file_get_contents($this->dataFile), true))) {

                //Respuesta error de id no encontrado
                http_response_code(404);
                echo json_encode([
                    'status' => 'ERROR',
                    'code' => '404',
                    'response' => 'Bombón no encontrado'
                ]);
                return;
            }
            
            //Respuesta success   
            file_put_contents($this->dataFile, json_encode(array_values($bombones), JSON_PRETTY_PRINT));
            echo json_encode([
                'status' => 'OK',
                'code' => '200',
                'response' => 'Bombón eliminado'
            ]);
        }
}