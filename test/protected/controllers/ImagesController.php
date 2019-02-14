<?php
 
class ImagesController extends Controller {
	public function filters()
	{
		return array(
			'rights',
		);
	}
	
	public function allowedActions()
	{
		return 'serve';
	}
	
    public function actionServe() {
        $request = str_replace('/serve','', Yii::app()->request->pathinfo);
        $imagePath = Yii::getPathOfAlias('webroot').'/'.$request;
        $targetPath = Yii::getPathOfAlias('webroot').'/'.Yii::app()->request->pathinfo;
					
        if (preg_match('/_w(\d+)h(\d+).*\.(jpg|jpeg|png|gif)/i', $imagePath, $matches)) {
            if (!isset($matches[0]) || !isset($matches[1]) || !isset($matches[2]) || !isset($matches[3]))
			{
                throw new CHttpException(400, 'Invalid parameters provided.');
			}
            if (!$matches[1] || !$matches[2])
			{
                throw new CHttpException(400, 'Invalid dimensions provided.');
            } 
            $originalFile = str_replace($matches[0],'', $imagePath).".".$matches[3];
            if (!file_exists($originalFile))
			{
                throw new CHttpException(404, 'File not found.');
			}
            $dirname = dirname($targetPath);
            if (!is_dir($dirname))
			{
                mkdir($dirname, 0775, true);
			}
            $image = Yii::app()->image->load($originalFile);
            $image->resize($matches[1], $matches[2]);
            if ($image->save($targetPath))
			{
                if (Yii::app()->request->urlReferrer != Yii::app()->request->requestUri)
                    $this->refresh();
            }
            throw new CHttpException(500, 'Server error.');
        } 
		else 
		{
            throw new CHttpException(400, 'Invalid parameters provided.');
        }
    }
}