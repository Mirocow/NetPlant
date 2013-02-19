<?php

class SiteController extends CController {
	public function actionIndex() {
		if (Yii::app()->user->isGuest) {
			$this->redirect("/User/user/login");
			return;
		}
		$this->render("index");
	}

	public function actionError() {
		if($error=Yii::app()->errorHandler->error)
        {
            if(Yii::app()->request->isAjaxRequest)
                    echo $error['message'];
            else
                    $this->render('error', $error);
        }
	}

	public function actionFlushCache() {
		Yii::app()->cache->flush();
	    Yii::app()->fileCache->flush();

	    // remove minscript caches
	    $path = Yii::getPathOfAlias("application.runtime");
	    $this->delete($path."/MinifyClientScript");
	    $this->delete($path."/minScript");
	    $this->delete($path."/cache");

	    echo "Cache flushed";
	    $this->redirect("/");
	}

	private function delete($path) {
	    if(!file_exists($path)) {
	        throw new RecursiveDirectoryException('Directory doesn\'t exist.');
	    }

	    $directoryIterator = new DirectoryIterator($path);

	    foreach($directoryIterator as $fileInfo) {
	        $filePath = $fileInfo->getPathname();
	        if(!$fileInfo->isDot()) {
	            if($fileInfo->isFile()) {
	                unlink($filePath);
	            } elseif($fileInfo->isDir()) {
	                if($this->emptyDirectory($filePath)) {
	                    rmdir($filePath);
	                } else {
	                    $this->delete($filePath);
	                }
	            }
	        }
	    }
	    rmdir($path);
	}
}