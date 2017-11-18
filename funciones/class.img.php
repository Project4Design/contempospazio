<?
require_once 'abeautifulsite/SimpleImage.php';

class Img{
	public $name;
	public $thumbname;

	//Validar si el archivo es de un formato de imagen valido.
	protected function check($temp){
		$info = getimagesize($temp);

		//Extensiones validas.
		$valid = ['image/gif','image/jpeg','image/png'];

	  return in_array($info['mime'], $valid);
	}
	
	protected function get_type($filename){
    return preg_replace('/^.*\./', '', $filename);
	}

	//Asignar un nombre al azar
	protected function rename($name,$thumbs = false){
		//Tomar la extension de la imagen
		$ext = $this->get_type($name);

		//Generar un nombre aleatorio y agregar la extension.
		$name = sha1(mt_rand().time());
		$this->name = $name.".png"; #.$ext;
		$this->thumbname = $name."_small.png"; #.$ext;

		return $this->name;
	}

	public function thumbnail($temp){
		$img    = new abeautifulsite\SimpleImage();

		//Crear fondo blanco de la imagen
		$fondo  = new abeautifulsite\SimpleImage(null, 250, 250, '#fff');

		//Se carga la imagen a la clase
		$img->load($temp);

		//Se adapta la imagen a las dimensiones dadas
		$img->best_fit(250,250);

		//Se le coloca el fondo blanco a la imagen cargada
		$fondo->overlay($img, 'center', 1,0,0);

		$fondo->save("../images/thumbs/".$this->thumbname);
	}

	public function load($input,$thumb = false){

		$temp = $input['tmp_name'];
		$name = $input['name'];

		//Verifica si es archivo es una imagen valida
		if($this->check($temp)){
			$img   = new abeautifulsite\SimpleImage();
			//Crear fondo blanco de la imagen

			//Se carga la imagen a la clase
			$img->load($temp);

			$width = $img->get_width();
			$height = $img->get_height();

			if($width > $height ){
				$height = $width;
			}else{
				$width = $height;
			}

			$img->best_fit($width,$height);
			
			$final = new abeautifulsite\SimpleImage(null, $width, $height, '#fff');


			//Se le coloca el fondo blanco a la imagen cargada
			$final->overlay($img, 'center', 1,0,0);

			//Si la variable $name existe, se asigna ese nombre a la imagen. Si no, se genera uno aleatorio
			$this->name = $this->rename($name);

			$final->save("../images/uploads/".$this->name);

			if($thumb){
				$this->thumbnail($temp);
			}

		}else{
			return false;
		}

		return $this;
	}

}