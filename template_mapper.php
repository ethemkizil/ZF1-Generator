<?php
 /**
  * @package Application_Model_Mapper_Base
  *
  * @method mixed save(Application_Model_{modelname} $model)
  * @method mixed find($id, Application_Model_{modelname} $model)
  * @method mixed select($id)
  * @method mixed fetchAll($params = array())
  * @method mixed delete($id)
  * @method mixed getCount($params = array())
  */
class Application_Model_Mapper_{modelname} extends Application_Model_Mapper_Base
{

	public $model = "{modelname}";
	
	public function customFunction ()
	{
		//codes...
		return;
	}
	
}