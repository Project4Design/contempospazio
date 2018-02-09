<?
require_once 'abeautifulsite/SimpleImage.php';

class Img{
	public $name;
	public $thumb;

	public function __DESCTRUCT()
	{
		gc_collect_cycles();
	}

	//Validar si el archivo es de un formato de imagen valido.
	protected function check($temp)
	{
		$info = getimagesize($temp);

		//Extensiones validas.
		$valid = ['image/gif','image/jpeg','image/png'];

	  return in_array($info['mime'], $valid);
	}

	//Asignar un nombre al azar
	protected function rename()
	{
		//Generar un nombre aleatorio y agregar la extension.
		$name = sha1(time());
		$this->name = $name.'.png';
		$this->thumb = $name.'_thumb.png';
	}

	public function load($input,$thumb = false)
	{
		$temp = $input['tmp_name'];
		$name = $input['name'];

		//Verifica si es archivo es una imagen valida
		if($this->check($temp)){
			$img = new abeautifulsite\SimpleImage($temp);

			$width  = $img->get_width();
			$height = $img->get_height();

			if($width > $height){
				$height = $width;
			}else{
				$width = $height;
			}

			$img->best_fit($width,$height);
			
			$final = new abeautifulsite\SimpleImage(null, $width, $height, '#fff');

			//Se le coloca el fondo blanco a la imagen cargada
			$final->overlay($img);

			//Se genera un nombre aleatorio
			$this->rename();

			$final->save('../images/uploads/'.$this->name);

			if($thumb){
				$thumbnail = $final;
				unset($final);
				$thumbnail->thumbnail(250);
				$thumbnail->save('../images/thumbs/'.$this->thumb);
				unset($thumbnail);
			}
		}else{
			return false;
		}

		return $this;
	}
}