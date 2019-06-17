<?php

namespace frontend\models\collateral;

use common\models\collateral\Collateral;
use common\models\collateral\CollateralSearch as CollateralSearchBase;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CollateralSearch represents the model behind the search form about `custom_modules\collateral\models\Collateral`.
 */
class CollateralSearch extends CollateralSearchBase
{

    /** @var  integer */
    public $statusStrong;
    /** @var  integer */
    public $investorIdStrong;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'investor_id', 'status', 'currency_type', 'created_at'], 'integer'],
            [['amount'], 'number'],
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
        $query = Collateral::find()
            ->alias('c')
            ->orderBy(['updated_at' => SORT_DESC]);

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
            'c.id' => $this->id,
            'c.investor_id' => $this->investor_id,
            'c.status' => $this->status,
            'c.amount' => $this->amount,
            'c.currency_type' => $this->currency_type,
            'c.created_at' => $this->created_at,
            'c.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere([
            'c.status' => $this->statusStrong,
            'c.investor_id' =>  $this->investorIdStrong,
        ]);

        return $dataProvider;
    }
}
