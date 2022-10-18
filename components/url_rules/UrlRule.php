<?php

namespace app\components\url_rules;
use app\models\Tree;

class UrlRule extends \yii\web\UrlRule
{

    public $pageId;

    /**
     * Parses the given request and returns the corresponding route and parameters.
     * @param \app\components\UrlManager $manager the URL manager
     * @param \yii\web\Request $request the request component
     * @return array|bool the parsing result. The route and the parameters are returned as an array.
     * If `false`, it means this rule cannot be used to parse this path info.
     */
    public function parseRequest($manager, $request)
    {
        $pathInfo = $request->getPathInfo();

        if (empty($pathInfo) === true) {
            return false;
        }

        $result = parent::parseRequest($manager, $request);
        if ($result === false) {
            return false;
        }

        if (Tree::find()->andWhere(['id' => $this->pageId, 'status' => Tree::STATUS_ACTIVE])->exists() === false) {
            return false;
        }

        \Yii::$app->urlManager->pageId = $this->pageId;

        return $result;
    }

}
