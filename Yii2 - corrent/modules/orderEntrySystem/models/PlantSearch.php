<?php

namespace app\modules\orderEntrySystem\models;

use yii\data\ActiveDataProvider;
use yii\data\Sort;

class PlantSearch extends Plant
{
    public $customerName;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['PL1_ID', 'CU1_ID', 'FLAG', 'PL1_SHOW_DEFAULT', 'PL1_NAME', 'customerName'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = self::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => new Sort([
                'defaultOrder' => 'PL1_ID DESC',
            ]),
        ]);

        $this->load($params, '');

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'CU1_ID' => $this->customerName,
        ]);

        if (isset($this->PL1_SHOW_DEFAULT)) {
            $query->andWhere([
                'PL1_SHOW_DEFAULT' => $this->PL1_SHOW_DEFAULT,
            ]);
        }

        foreach (['PL1_ID', 'FLAG', 'PL1_NAME'] as $attribute) {
            $query->andFilterCompare($attribute, $this->$attribute, 'like');
        }

        return $dataProvider;
    }
}
