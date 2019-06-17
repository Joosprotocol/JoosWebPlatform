<?php

namespace common\models\collateral;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CollateralSearch represents the model behind the search form about `custom_modules\collateral\models\Collateral`.
 */
class CollateralSearch extends Collateral
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'amount', 'lender_id', 'investor_id', 'status', 'currency_type', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Collateral::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!empty($params['pageSize'])) {
            $dataProvider->pagination->pageSize = $params['pageSize'];
        }

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'investor_id' => $this->investor_id,
            'status' => $this->status,
            'amount' => $this->amount,
            'currency_type' => $this->currency_type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
