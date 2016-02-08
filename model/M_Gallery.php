<?php 
class M_Gallery extends M_Model
{
	private static $instance;
	
	public static function Instance()
	{
		if (self::$instance == null)
			self::$instance = new self();
		
		return self::$instance;
	}
	
	public function __construct() 
	{
		parent::__construct('gallery', 'gallery_id');
	}
	
	public function get_images($gallery_id)
	{
		$gallery_id =(int)$gallery_id;
		return $this->db->Select("SELECT * FROM gallery_mediafile
										LEFT JOIN mediafile USING(fid)
										WHERE gallery_id = $gallery_id
										ORDER BY weight ASC");
	}
	
	// Добавление картинки в галерею
	public function add_image($gallery_id, $image_id)
	{
		$fields = array();
		$fields['gallery_id'] = (int)$gallery_id;
		$fields['fid'] = (int)$image_id;
		return $this->db->Insert('gallery_mediafile', $fields);
	}
	
	// удаление картинки из галереи
	public function delete_image($id_gallery, $id_image)
	{
		$id_gallery = (int)$id_gallery;
		$id_image = (int)$id_image;
		$this->db->Delete('gallery_mediafile', "gallery_id=? 
						   AND fid=?", array($id_gallery, $id_image));
		return M_MediaFiles::Instance()->deleteImg($id_image);
	}
	
	// удаление галерии
	public function delete_gallery($id_gallery)
	{
		$id_gallery = (int)$id_gallery;
		
		// удаляем файлы
		foreach($this->get_images($id_gallery) as $image) {
			$this->delete_image($id_gallery, $image['fid']);
		}

		// чистим базу
		$this->db->Delete('gallery_mediafile', "gallery_id=?",array($id_gallery));
		parent::delete($id_gallery);
	}
	
	// сортировка картинок в галереи
	public function sorting($id_gallery, $images)
	{
		$id_gallery = (int)$id_gallery;
		$obj = array();
		
		for($i = 0; $i < count($images); $i++)
		{
			$id_image = (int)$images[$i];
			$where = "gallery_id=? AND fid=?";
			$obj['weight'] = $i;
			$this->db->Update('gallery_mediafile', $obj, $where, array($id_gallery, $id_image));
		}
		
		return true;
	}
}