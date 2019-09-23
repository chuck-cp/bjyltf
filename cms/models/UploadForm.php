<?php
namespace cms\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }

    public function upload($dir)
    {
        if ($this->validate()) {
            $folder = date('Ymd')."/";
            $pre = rand(999,9999).time();
//            $this->imageFile->saveAs('upload/image/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
            //存入阿里云
            $uploadResult = \Yii::$app->cos->upload($this->imageFile->tempName, $dir.'/' .$folder. $pre . '.' . $this->imageFile->extension);
            return $uploadResult['data']['source_url'];
        } else {
            return false;
        }
    }
}