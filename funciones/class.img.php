<?
require '../vendor/autoload.php';
use Intervention\Image\ImageManager;

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

	//Load image with original dimensions
	public function loadSmall($input)
	{
		$temp = $input['tmp_name'];
		$name = $input['name'];

		//Verifica si es archivo es una imagen valida
		if($this->check($temp)){
			// create an image manager instance with favored driver
			$manager = new ImageManager();

			$image = $manager->make($temp);

			$this->rename();

			$image->resize(250, 250, function ($constrain){
				$constrain->aspectRatio();
			});

			$image->save('../images/'.$this->name);
			$image->destroy();
		}else{
			return false;
		}
	
		return $this;
	}

	//Load image and create a new square image based on the highest dimension Widht or Height
	public function load($input,$thumb = false)
	{
		$temp = $input['tmp_name'];
		$name = $input['name'];

		//Verifica si es archivo es una imagen valida
		if($this->check($temp)){

			$manager = new ImageManager();
			$image = $manager->make($temp);

			//Se genera un nombre aleatorio
			$this->rename();

			$image->save('../images/uploads/'.$this->name);

			if($thumb){
				$image->resize(250, 250, function ($constrain){
					$constrain->aspectRatio();
				});

				$image->save('../images/thumbs/'.$this->thumb);
			}
			
			$image->destroy();
		}else{
			return false;
		}

		return $this;
	}
}